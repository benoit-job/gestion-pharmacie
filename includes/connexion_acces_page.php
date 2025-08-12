<?php
// Fichier: includes/connexion_acces_page.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("auth_functions.php");


// Vérifier si l'utilisateur est toujours actif
if ($_SESSION['user']['statut'] != 'actif') {
    session_destroy();
    header('Location: index.php?error=account_disabled');
    exit();
}

// Vérifier la validité de la session (optionnel - timeout)
$session_timeout = 120; // 2 heures par défaut
if (isset($_SESSION['last_activity']) && 
    (time() - $_SESSION['last_activity']) > ($session_timeout * 60)) {
    
    // Logger la déconnexion automatique
    if (function_exists('logUserActivity')) {
        logUserActivity('auto_logout', 'Déconnexion automatique - timeout de session');
    }
    
    session_destroy();
    header('Location: index.php?error=session_expired');
    exit();
}

// Mettre à jour l'horodatage de la dernière activité
$_SESSION['last_activity'] = time();

// Récupérer le nom de la page actuelle
$current_page = basename($_SERVER['PHP_SELF']);

// Vérifier les permissions pour la page actuelle
if (function_exists('checkPageAccess') && !checkPageAccess($current_page)) {
    $_SESSION['toast'] = [
        'type' => 'error',
        'message' => 'Accès non autorisé à cette page.'
    ];
    header('Location: accueil.php');
    exit();
}

// Rafraîchir périodiquement les informations utilisateur (toutes les 10 minutes)
if (!isset($_SESSION['last_user_refresh']) || 
    (time() - $_SESSION['last_user_refresh']) > 600) {
    
    if (function_exists('refreshUserSession')) {
        refreshUserSession($_SESSION['user']['id']);
        $_SESSION['last_user_refresh'] = time();
    }
}

// Fonction d'aide pour vérifier rapidement les permissions dans les pages
if (!function_exists('can')) {
    function can($permission) {
        return hasPermission($permission);
    }
}


// Fonction d'aide pour vérifier si l'utilisateur est admin
function isAdmin() {
    return isset($_SESSION['user']['role_id']) && 
           ($_SESSION['user']['role_id'] == 4 || $_SESSION['user']['role_id'] == 5);
}

// Fonction pour obtenir le nom d'affichage de l'utilisateur
function getDisplayName() {
    return isset($_SESSION['user']['pseudo']) ? $_SESSION['user']['pseudo'] : 'Utilisateur';
}

// Fonction pour obtenir le rôle de l'utilisateur
function getUserRole() {
    return isset($_SESSION['user']['role_name']) ? $_SESSION['user']['role_name'] : 'Aucun rôle';
}
?>
