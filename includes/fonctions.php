<?php 

function createPathFile($path_save)
{
    $year  = date('Y');
    $month = date('m');
    if(is_dir($path_save."$year"))
    {
        if(is_dir($path_save."$year/$month")) 
        {
            $path_save = $path_save."$year/$month";
        }
        else 
        {
            mkdir($path_save."$year/$month"); //On crée le dossier mois
            $path_save = $path_save."$year/$month";
        }
    }
    else
    {
        mkdir($path_save."$year"); //On crée le dossier annee
        mkdir($path_save."$year/$month"); //On crée le dossier mois
        $path_save = $path_save."$year/$month";
    }
    return $path_save.'/';   
}

//Fonction upload file, peut être utiliser avec la fonction createPathFile()
//$chemin   = uploadFile(createPathFile(), $_FILES['photo_eleve_upload'], 'jpg,jpeg', 1000000);
function uploadFile($path, $fichier, $type_fichier, $taille)
{
    $infosfichier = pathinfo($fichier['name']);
    $ext_upload   = $infosfichier['extension'];
    if($fichier['size'] <= $taille AND (in_array($ext_upload, explode(',', $type_fichier))))
    {
        $chars      = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; 
        $length     = rand(10, 16); 
        $nom_random = substr( str_shuffle(sha1(rand() . time()) . $chars ), 0, $length );

        $nom_fichier = $nom_random . '.' . $ext_upload;
        $chemin = $path.$nom_fichier;
        move_uploaded_file($fichier['tmp_name'], $chemin);//Enreg du fichier
        return $chemin;
    }
}

//Génère un mot de passe de 10 à 16 caractères;
function MotDePasse()
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; 
    $length = rand(10, 16); 
    $password = substr( str_shuffle(sha1(rand() . time()) . $chars ), 0, $length );
     return $password;
}


//alert js avec php
    //window.location = \"$page_php\"; Encienne ligne
function alertJS($message, $reloadPage)
{
  if(trim($reloadPage) == '')
  {
    $reloadPage = basename($_SERVER['SCRIPT_NAME']);
  }
  echo "<script> 
    		if(!alert(\"$message\"))
    		{
    			window.location = '$reloadPage';
    		} 
    	</script>";
}

//génerer l'entete print des documents pour ecole
function headerPrint($headerTitle)
{
  echo "<table style='width:100%; border-bottom: 1px solid black; height: 79px;'> <tr><td style='width=80px;'><img style='float: left; position: relative; z-index: 99999; border: 1px solid #ccc;' width='75' height='75' src='".srcImage($_SESSION['ferme']['logo'])."' style='position:absolute; top:0; left:0;'/></td> <td style='text-align:center;'><h5 style='margin: 0 50px;'> ".$headerTitle." </h5></td> <td style='text-align:right;width=100px;'><h5><b>".$_SESSION['ferme']['nom']."</b><br>".date('d/m/Y')."</h5></td></tr></table>";
}



function ids_in_ids($valeur1, $valeur2)//Ex: ids_in_ids($_SESSION['utilisateur']['id_niv_enseignement'], $personnel['id_niv_enseignements'])
{
    $autorisation = 0;
    foreach (explode(',', $valeur1) as $value1)
    {
        if(in_array($value1, explode(',', $valeur2)))
        {
            $autorisation = 1;
            break;
        }
    }
    return $autorisation;
}

//Fonction pour enlever les failles pour ids (254,586,752,45)
function htmlspecialchars_ids($ids_separes_par_virgule)
{
    $ids_separes_par_virgule = str_replace(',', '_', $ids_separes_par_virgule);
    $ids_separes_par_virgule = strip_tags(htmlspecialchars(trim($ids_separes_par_virgule)));
    $ids_separes_par_virgule = str_replace('_', ',', $ids_separes_par_virgule);
    return $ids_separes_par_virgule;
}

//Fonction pour rendre un tableau de value en : 52,54,36 Idéal pour le select multiple
function id_select_multiple_en_ids($id_select_multiple)
{
    if(!empty($id_select_multiple) AND is_array($id_select_multiple))
    {
        $resultat = implode(',', $id_select_multiple);
        $resultat = str_replace(',', '_', $resultat);
        $resultat = strip_tags(htmlspecialchars(trim($resultat)));
        return str_replace('_', ',', $resultat);
    }
    else
    {
        return 0;
    }
}


//fonction intersection ids_ niveau d'enseignement
function ids_intersection_ids($ids_1, $ids_2)
{
    $resultat = 0;
    foreach (explode(',', $ids_1) AS $id_1)
    {
        if(in_array($id_1, explode(',', $ids_2)))
        {
            $resultat = 1;
            break;
        }
    }
    return $resultat;
}

function reload_current_page()
{
    header("location: ".substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1));
}

function crypt_decrypt_chaine($string, $action)
{
    // you may change these values to your own
    $secret_key = 'HeNocH_key';
    $secret_iv = 'HeNocH_iv';

    if(!empty($string))
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if (!is_string($string)) {
            // Si $string n'est pas une chaîne, vous pouvez gérer l'erreur de votre choix ici.
            // Vous pouvez lancer une exception, enregistrer un message d'erreur, etc.
            return $output;
        }

        if ($action == 'C') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } elseif ($action == 'D') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
    }
    else
    {
        $output = ''; 
    }

    return $output;
}
function LAST_INSERT_ID($bdd)
{
    $query = "SELECT LAST_INSERT_ID() AS dernier_id";
    $resultat = mysqli_query($bdd, $query) or die("Requête non conforme");  
    $resultat = mysqli_fetch_array($resultat);
    return $resultat['dernier_id'];
}

function ids_crypt_decrypt($ids_crypt)
{
    $ids_decrypt = '';
    foreach(explode(',', $ids_crypt) AS $id_crypt)
    {
        if($ids_decrypt == '')
        {
            $ids_decrypt = crypt_decrypt_chaine($id_crypt, 'D');
        }
        else
        {
            $ids_decrypt .= ','.crypt_decrypt_chaine($id_crypt, 'D');
        }
    }

    if(str_contains($ids_decrypt, ','))
    {
        $ids_decrypt = explode(',', $ids_decrypt);
        sort($ids_decrypt);//On ordonne les id pour faciliter les recherches
        $ids_decrypt = implode(',', $ids_decrypt);
        return $ids_decrypt;
    }
    else{return $ids_decrypt;}
}

function ids_decrypt($ids)
{
    if(str_contains($ids, ','))
    {
        $ids_decrypt = '';
        foreach(explode(',', $ids) AS $id)
        {
            if($ids_decrypt == '')
            {
                $ids_decrypt = strip_tags(htmlspecialchars(trim(  crypt_decrypt_chaine($id, 'D') )));
            }
            else
            {
                $ids_decrypt .= ','.strip_tags(htmlspecialchars(trim(  crypt_decrypt_chaine($id, 'D') )));
            }
        }
    }
    else
    {
        $ids_decrypt = strip_tags(htmlspecialchars(trim(  crypt_decrypt_chaine($ids, 'D') )));
    }
    return $ids_decrypt;
}
function getLocalhost()
{
    $is_https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    $protocol = $is_https ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    return $protocol . $host;
}


function envoyerSMS($API_KEY, $API_PASS, $AdressReceiver, $message, $senderName) 
{
    $responses = [];
    $numeros = strpos($AdressReceiver, ',') !== false ? explode(',', $AdressReceiver) : [$AdressReceiver];

    foreach ($numeros as $numero) 
    {
        $numero = trim($numero); // Nettoyage des espaces
        
        $curl = curl_init();

        $datas = [
            "API_KEY"  => $API_KEY,
            "API_PASS" => $API_PASS,
            "sender"   => $senderName,
            "message"  => $message,
            "date"     => "",
            "numero"   => $numero,
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://prosms.pro/api/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_POSTFIELDS     => json_encode($datas),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'cache-control: no-cache'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $responses[] = json_decode($response);
    }

    return count($responses) === 1 ? $responses[0] : $responses;
}

// Vérifie si un utilisateur a une permission spécifique
function est_autorise($permission_code, $user_id = null) {
    global $bdd;
    
    // Si aucun user_id n'est spécifié, utilise celui de la session
    if ($user_id === null) {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        $user_id = $_SESSION['user_id'];
    }

    // Requête pour vérifier la permission
    $query = "SELECT COUNT(*) as count 
              FROM role_permission rp
              JOIN permissions p ON rp.permission_id = p.id
              JOIN users u ON u.role_id = rp.role_id
              WHERE u.id = ? AND p.code = ?";
    
    $stmt = mysqli_prepare($bdd, $query);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $permission_code);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    return ($row['count'] > 0);
}

// Fonction pour obtenir le rôle d'un utilisateur
function obtenir_role_utilisateur($user_id) {
    global $bdd;
    $query = "SELECT r.* FROM users u 
              JOIN roles r ON u.role_id = r.id 
              WHERE u.id = ?";
    $stmt = mysqli_prepare($bdd, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

function getUserPseudo($bdd, $userId) {
    // Sécurise l'ID en entier
    $userId = intval($userId);

    $query = "SELECT pseudo FROM users WHERE id = $userId LIMIT 1";
    $result = mysqli_query($bdd, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['pseudo'];
    }

    return null; // Retourne null si aucun pseudo trouvé
}


function supprimerSouscripteurAvecHistorique(mysqli $bdd, string $idCrypted, int $idUser) : bool {
    // Décryptage et sécurisation ID
    $id_souscripteur = intval(crypt_decrypt_chaine($idCrypted, 'D'));
    if ($id_souscripteur <= 0) {
        return false;
    }

    // Récupérer le pseudo utilisateur
    $nom_user = mysqli_real_escape_string($bdd, getUserPseudo($bdd, $idUser));

    // Récupérer toutes les données du souscripteur à supprimer
    $selectQuery = "
        SELECT s.*,
               l.id_region,
               l.nom_lieu,
               r.nom_region,
               ts.nom AS nom_type_souscripteur
        FROM souscripteurs AS s
        LEFT JOIN lieu_exercices AS l ON l.id = s.id_lieu_exercice
        LEFT JOIN regions AS r ON r.id = l.id_region
        LEFT JOIN type_souscripteurs AS ts ON ts.id = s.id_type_souscripteur
        WHERE s.id_souscripteur = $id_souscripteur
        LIMIT 1
    ";
    $result = mysqli_query($bdd, $selectQuery);
    
    if (!$result) {
        die("Erreur lors de la récupération des données: " . mysqli_error($bdd));
    }

    if ($souscripteur = mysqli_fetch_assoc($result)) {
        // Échapper toutes les valeurs pour sécurité
        $type_souscripteur = mysqli_real_escape_string($bdd, $souscripteur['nom_type_souscripteur'] ?? '');
        $region = mysqli_real_escape_string($bdd, $souscripteur['nom_region'] ?? '');
        $lieu_exercice = mysqli_real_escape_string($bdd, $souscripteur['nom_lieu'] ?? '');
        $civilite = mysqli_real_escape_string($bdd, $souscripteur['civilite'] ?? '');
        $nom = mysqli_real_escape_string($bdd, $souscripteur['nom'] ?? '');
        $prenom = mysqli_real_escape_string($bdd, $souscripteur['prenom'] ?? '');
        $date_naissance = !empty($souscripteur['date_naissance']) ? "'" . mysqli_real_escape_string($bdd, $souscripteur['date_naissance']) . "'" : "NULL";
        $lieu_naissance = mysqli_real_escape_string($bdd, $souscripteur['lieu_naissance'] ?? '');
        $adresse = mysqli_real_escape_string($bdd, $souscripteur['adresse'] ?? '');
        $code_postal = mysqli_real_escape_string($bdd, $souscripteur['code_postal'] ?? '');
        $nationalite = mysqli_real_escape_string($bdd, $souscripteur['nationalite'] ?? '');
        $telephone_fixe = mysqli_real_escape_string($bdd, $souscripteur['telephone_fixe'] ?? '');
        $telephone_portable = mysqli_real_escape_string($bdd, $souscripteur['telephone_portable'] ?? '');
        $email = mysqli_real_escape_string($bdd, $souscripteur['email'] ?? '');
        $nom_etablissement = mysqli_real_escape_string($bdd, $souscripteur['nom_etablissement'] ?? '');
        $secteur_activite = mysqli_real_escape_string($bdd, $souscripteur['secteur_activite'] ?? '');
        $n_souscription = mysqli_real_escape_string($bdd, $souscripteur['n_souscription'] ?? '');
        $date_souscription = !empty($souscripteur['date_souscription']) ? "'" . mysqli_real_escape_string($bdd, $souscripteur['date_souscription']) . "'" : "NULL";
        
        // Conversion des nombres
        $montant_souscrit = floatval($souscripteur['montant_souscrit'] ?? 0);
        $montant_souscrit_type1 = floatval($souscripteur['montant_souscrit_type1'] ?? 0);
        $montant_souscrit_type2 = floatval($souscripteur['montant_souscrit_type2'] ?? 0);
        $nombre_actions = intval($souscripteur['nombre_actions'] ?? 0);

        // Requête d'insertion dans l'historique
        $insertQuery = "
            INSERT INTO historiques_souscripteurs (
                id_souscripteur, user, type_souscripteur, region, lieu_exercice,
                civilite, nom, prenom, date_naissance, lieu_naissance, adresse, code_postal,
                nationalite, telephone_fixe, telephone_portable, email, nom_etablissement,
                secteur_activite, montant_souscrit, montant_souscrit_type1, montant_souscrit_type2,
                nombre_actions, n_souscription, date_souscription, action, date_suppression
            ) VALUES (
                $id_souscripteur, '$nom_user', '$type_souscripteur', '$region', '$lieu_exercice',
                '$civilite', '$nom', '$prenom', $date_naissance, '$lieu_naissance', '$adresse', '$code_postal',
                '$nationalite', '$telephone_fixe', '$telephone_portable', '$email', '$nom_etablissement',
                '$secteur_activite', $montant_souscrit, $montant_souscrit_type1, $montant_souscrit_type2,
                $nombre_actions, '$n_souscription', $date_souscription,
                'supprimer', NOW()
            )
        ";
        
        if (!mysqli_query($bdd, $insertQuery)) {
            die("Erreur lors de l'insertion dans l'historique: " . mysqli_error($bdd));
        }

        // Supprimer le souscripteur
        $deleteQuery = "DELETE FROM souscripteurs WHERE id_souscripteur = $id_souscripteur";
        if (!mysqli_query($bdd, $deleteQuery)) {
            die("Erreur lors de la suppression du souscripteur: " . mysqli_error($bdd));
        }

        return true;
    } else {
        die("Souscripteur non trouvé pour suppression.");
    }
}




?>  