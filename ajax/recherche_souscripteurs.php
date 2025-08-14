<?php
// Fichier: ajax/recherche_souscripteurs.php

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

function sendJsonResponse($data, $httpCode = 200) {
    http_response_code($httpCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function logDebug($message, $data = null) {
    $log = date('Y-m-d H:i:s') . " - " . $message;
    if ($data !== null) {
        $log .= " - " . print_r($data, true);
    }
    error_log($log);
}

// Votre fonction de cryptage/décryptage
function safe_encrypt_id($string, $action) {
    $secret_key = 'HeNocH_key';
    $secret_iv = 'HeNocH_iv';
    
    if (empty($string)) {
        return '';
    }
    
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    
    // Assurer que c'est une string
    $string = (string)$string;
    
    try {
        if ($action == 'C') {
            $encrypted = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            if ($encrypted !== false) {
                $output = base64_encode($encrypted);
            }
        } elseif ($action == 'D') {
            $decoded = base64_decode($string);
            if ($decoded !== false) {
                $output = openssl_decrypt($decoded, $encrypt_method, $key, 0, $iv);
            }
        }
    } catch (Exception $e) {
        logDebug("Erreur dans safe_encrypt_id", [
            'action' => $action,
            'string' => $string,
            'error' => $e->getMessage()
        ]);
        return false;
    }
    
    return $output;
}

try {
    logDebug("=== DÉBUT RECHERCHE ===");
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendJsonResponse(['error' => 'Méthode non autorisée'], 405);
    }
    
    if (!isset($_POST['search_term'])) {
        sendJsonResponse(['error' => 'Paramètre search_term manquant'], 400);
    }
    
    $search_term = trim($_POST['search_term']);
    logDebug("Terme de recherche", $search_term);
    
    if (empty($search_term) || strlen($search_term) < 2) {
        logDebug("Terme trop court, retour tableau vide");
        sendJsonResponse([]);
    }
    
    // Inclusion de la connexion BDD
    if (!file_exists("../includes/connexion_bdd.php")) {
        throw new Exception("Fichier connexion_bdd.php introuvable");
    }
    
    include("../includes/connexion_bdd.php");
    
    if (!isset($bdd) || !$bdd) {
        throw new Exception("Connexion à la base de données échouée");
    }
    
    if (!mysqli_ping($bdd)) {
        throw new Exception("Base de données non disponible");
    }
    
    logDebug("Connexion BDD OK");
    
    // Test de la fonction de cryptage
    $test_crypto = safe_encrypt_id("123", "C");
    logDebug("Test cryptage", [
        'input' => "123",
        'output' => $test_crypto,
        'success' => !empty($test_crypto)
    ]);
    
    // Requête SQL
    $query = "SELECT 
                id_souscripteur,
                nom,
                prenom,
                civilite,
                nom_etablissement,
                telephone_portable,
                secteur_activite,
                active
              FROM souscripteurs 
              WHERE active = 'oui' 
              AND (
                nom LIKE ? OR 
                prenom LIKE ? OR 
                nom_etablissement LIKE ? OR 
                telephone_portable LIKE ? OR
                CONCAT(nom, ' ', prenom) LIKE ? OR
                CONCAT(prenom, ' ', nom) LIKE ?
              )
              ORDER BY nom, prenom 
              LIMIT 10";
    
    $stmt = mysqli_prepare($bdd, $query);
    if (!$stmt) {
        throw new Exception("Erreur préparation requête: " . mysqli_error($bdd));
    }
    
    $search_param = '%' . $search_term . '%';
    mysqli_stmt_bind_param($stmt, "ssssss", 
        $search_param, $search_param, $search_param, 
        $search_param, $search_param, $search_param
    );
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Erreur exécution requête: " . mysqli_stmt_error($stmt));
    }
    
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        throw new Exception("Erreur récupération résultats: " . mysqli_stmt_error($stmt));
    }
    
    logDebug("Requête exécutée avec succès");
    
    $souscripteurs = [];
    $count = 0;
    
    while ($row = mysqli_fetch_assoc($result)) {
        $count++;
        $id_original = $row['id_souscripteur'];
        
        logDebug("Traitement ligne $count", [
            'id_original' => $id_original,
            'nom' => $row['nom'] ?? 'Sans nom'
        ]);
        
        // Cryptage de l'ID avec votre fonction
        $id_crypte = safe_encrypt_id($id_original, 'C');
        
        if ($id_crypte === false || empty($id_crypte)) {
            logDebug("ERREUR cryptage pour ID $id_original");
            // Fallback sécurisé
            $id_crypte = 'ERR_' . base64_encode($id_original);
        } else {
            logDebug("Cryptage OK", [
                'original' => $id_original,
                'crypte' => substr($id_crypte, 0, 20) . '...', // Tronqué pour les logs
                'longueur' => strlen($id_crypte)
            ]);
            
            // Vérification en décryptant
            $verification = safe_encrypt_id($id_crypte, 'D');
            if ($verification != $id_original) {
                logDebug("PROBLÈME vérification cryptage", [
                    'original' => $id_original,
                    'decrypte' => $verification
                ]);
            }
        }
        
        $souscripteur = [
            'nom' => trim($row['nom'] ?? ''),
            'prenom' => trim($row['prenom'] ?? ''),
            'civilite' => trim($row['civilite'] ?? ''),
            'nom_etablissement' => trim($row['nom_etablissement'] ?? ''),
            'telephone_portable' => trim($row['telephone_portable'] ?? ''),
            'secteur_activite' => trim($row['secteur_activite'] ?? ''),
            'active' => $row['active'] ?? '',
            'id_crypte' => $id_crypte
        ];
        
        $souscripteurs[] = $souscripteur;
    }
    
    mysqli_stmt_close($stmt);
    
    logDebug("=== FIN RECHERCHE ===", [
        'terme' => $search_term,
        'resultats' => count($souscripteurs)
    ]);
    
    sendJsonResponse($souscripteurs);
    
} catch (Exception $e) {
    logDebug("EXCEPTION", [
        'message' => $e->getMessage(),
        'file' => basename($e->getFile()),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
    
    sendJsonResponse([
        'error' => 'Erreur lors de la recherche: ' . $e->getMessage()
    ], 500);
}
?>