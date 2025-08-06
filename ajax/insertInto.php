<?php 
    include("../includes/connexion_acces_page.php");
    include("../includes/connexion_bdd.php");
    include("../includes/fonctions.php");

if (isset($_POST['nomRegion'])) {
    // Nettoyage des données
    $nomRegion = trim($_POST['nomRegion']);
    $codeRegion = trim($_POST['codeRegion']);
    $activeRegion = isset($_POST['activeRegion']) ? 'oui' : 'non';
    $id_user = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 0;

    // Vérification des données obligatoires
        $query = "INSERT INTO regions (id_user, nom_region, code_region, active, date_insert) 
                  VALUES ($id_user, '$nomRegion', '$codeRegion', '$activeRegion', NOW())";
        
        $result = mysqli_query($bdd, $query);

        if ($result) {
            echo 'success';
        } else {
            echo 'fail: ' . mysqli_error($bdd);
        }
} 

if (isset($_POST['nomType'])) {
    // Nettoyage des données
    $nomType = trim($_POST['nomType']);
    $activeType = isset($_POST['activeType']) ? 'oui' : 'non';
    $id_user = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 0;

    // Vérification des données obligatoires
        $query = "INSERT INTO type_souscripteurs (id_user, nom, active, date_insert) 
                  VALUES ($id_user, '$nomType', '$activeType', NOW())";
        
        $result = mysqli_query($bdd, $query);

        if ($result) {
            echo 'success';
            exit;
        } else {
            echo 'fail: ' . mysqli_error($bdd);
        }
} 

if (isset($_POST['nomLieu'])) {
    // Nettoyage des données
    $nomLieu = trim($_POST['nomLieu']);
    $id_region = trim($_POST['id_region']);
    $lieulong = trim($_POST['lieulong']);
    $lieulat = trim($_POST['lieulat']);
    $active = isset($_POST['active']) ? 'oui' : 'non';
    $id_user = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 0;

    // Vérification des données obligatoires
        $query = "INSERT INTO lieu_exercices (id_user, nom_lieu, id_region, lieulong, lieulat, active, date_insert) 
                  VALUES ($id_user, '$nomLieu', '$id_region', $lieulong, $lieulat, '$active', NOW())";
        
        $result = mysqli_query($bdd, $query);

        if ($result) {
            echo 'success';
        } else {
            echo 'fail: ' . mysqli_error($bdd);
        }
} 

if (isset($_POST['nom']) && isset($_POST['prenom'])) {

    // --- Sécurisation des champs texte ---
    $id_type_souscripteur = isset($_POST['id_type_souscripteur']) && is_numeric($_POST['id_type_souscripteur']) ? intval($_POST['id_type_souscripteur']) : "NULL";
    $civilite = isset($_POST['civilite']) ? "'".mysqli_real_escape_string($bdd, trim($_POST['civilite']))."'" : "NULL";
    $nom = "'".mysqli_real_escape_string($bdd, trim($_POST['nom']))."'";
    $prenom = "'".mysqli_real_escape_string($bdd, trim($_POST['prenom']))."'";
    $date_naissance = !empty($_POST['date_naissance']) ? "'".mysqli_real_escape_string($bdd, $_POST['date_naissance'])."'" : "NULL";
    $lieu_naissance = isset($_POST['lieu_naissance']) ? "'".mysqli_real_escape_string($bdd, trim($_POST['lieu_naissance']))."'" : "NULL";

    $adresse = "'".mysqli_real_escape_string($bdd, trim($_POST['adresse']))."'";
    $complement_adresse = isset($_POST['complement_adresse']) ? "'".mysqli_real_escape_string($bdd, trim($_POST['complement_adresse']))."'" : "NULL";
    $nationalite = "'".mysqli_real_escape_string($bdd, trim($_POST['nationalite']))."'";
    // $id_region = isset($_POST['id_region']) && is_numeric($_POST['id_region']) ? intval($_POST['id_region']) : "NULL";
    $id_lieu_exercice = isset($_POST['id_lieu_exercice']) && is_numeric($_POST['id_lieu_exercice']) ? intval($_POST['id_lieu_exercice']) : "NULL";
    $telephone_fixe = isset($_POST['telephone_fixe']) ? "'".mysqli_real_escape_string($bdd, trim($_POST['telephone_fixe']))."'" : "NULL";
    $telephone_portable = "'".mysqli_real_escape_string($bdd, trim($_POST['telephone_portable']))."'";
    $email = isset($_POST['email']) ? "'".mysqli_real_escape_string($bdd, trim($_POST['email']))."'" : "NULL";

    $nom_etablissement = "'".mysqli_real_escape_string($bdd, trim($_POST['nom_etablissement']))."'";
    $secteur_activite = "'".mysqli_real_escape_string($bdd, trim($_POST['secteur_activite']))."'";
    $montant_souscrit = isset($_POST['montant_souscrit']) && is_numeric($_POST['montant_souscrit']) ? intval($_POST['montant_souscrit']) : "NULL";
    $montant_souscrit_type1 = isset($_POST['montant_souscrit_type1']) && is_numeric($_POST['montant_souscrit_type1']) ? intval($_POST['montant_souscrit_type1']) : "NULL";
    $montant_souscrit_type2 = isset($_POST['montant_souscrit_type2']) && is_numeric($_POST['montant_souscrit_type2']) ? intval($_POST['montant_souscrit_type2']) : "NULL";
    $nombre_actions = isset($_POST['nombre_actions']) && is_numeric($_POST['nombre_actions']) ? intval($_POST['nombre_actions']) : "NULL";
    $n_souscription = isset($_POST['n_souscription']) && is_numeric($_POST['n_souscription']) ? intval($_POST['n_souscription']) : "NULL";
    $date_souscription = !empty($_POST['date_souscription']) ? "'".mysqli_real_escape_string($bdd, $_POST['date_souscription'])."'" : "NULL";
    $id_user = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 0;

    // --- GESTION IMAGE ---
    $image_etablissement = "NULL"; 

        $fileTmp  = $_FILES['image_etablissement']['tmp_name'];
        $fileName = basename($_FILES['image_etablissement']['name']);
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $destination = '../fichiers/uploads/'.uniqid('img_').'.'.$fileExt;
            $destination = createPathFile('../fichiers/uploads/').uniqid().'.'.$fileExt;
            if(move_uploaded_file($fileTmp, $destination)) {
                $image_etablissement = "'".mysqli_real_escape_string($bdd, $destination)."'";
            }

    // --- INSERTION ---
    $query = "INSERT INTO souscripteurs (
        id_user, id_type_souscripteur, civilite, nom, prenom, date_naissance, lieu_naissance,
        adresse, code_postal, nationalite, id_lieu_exercice,
        telephone_fixe, telephone_portable, email,
        nom_etablissement, secteur_activite, montant_souscrit, montant_souscrit_type1, montant_souscrit_type2,
        nombre_actions, n_souscription, date_souscription, image_etablissement, date_insert
    ) VALUES (
        $id_user, $id_type_souscripteur, $civilite, $nom, $prenom, $date_naissance, $lieu_naissance,
        $adresse, $complement_adresse, $nationalite, $id_lieu_exercice,
        $telephone_fixe, $telephone_portable, $email,
        $nom_etablissement, $secteur_activite, $montant_souscrit, $montant_souscrit_type1, $montant_souscrit_type2,
        $nombre_actions, $n_souscription, $date_souscription, $image_etablissement, NOW()
    )";

    $result = mysqli_query($bdd, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'fail: ' . mysqli_error($bdd);
    }
}
// INSERTION VERSEMENT

if (!isset($_POST['versements'])) {
    exit('no_data');
}

$data = json_decode($_POST['versements'], true);
if (!is_array($data) || empty($data)) {
    exit('no_data');
}

$success = true;

foreach ($data as $row) {
    $id_souscripteur = intval($row['id_souscripteur']);
    $montant = floatval($row['montant']);
    $date = mysqli_real_escape_string($bdd, $row['date']);
    $nature = mysqli_real_escape_string($bdd, $row['nature']);
    $id_user = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 0;

    // 🔹 1. Récupérer le dernier ordre pour ce souscripteur
    $lastOrdreQuery = "SELECT MAX(ordre) AS dernier_ordre 
                       FROM versements_souscripteurs 
                       WHERE id_souscripteur = $id_souscripteur";
    $lastOrdreResult = mysqli_query($bdd, $lastOrdreQuery);
    $lastOrdreData = mysqli_fetch_assoc($lastOrdreResult);
    $dernier_ordre = $lastOrdreData['dernier_ordre'] ? intval($lastOrdreData['dernier_ordre']) : 0;

    // 🔹 2. Définir le prochain ordre
    $nouvel_ordre = $dernier_ordre + 1;

    // 🔹 3. Insertion avec ordre unique
    $query = "INSERT INTO versements_souscripteurs 
              (id_user, id_souscripteur, ordre, montant, date, nature, date_insert) 
              VALUES ($id_user, $id_souscripteur, $nouvel_ordre, $montant, '$date', '$nature', NOW())";

    $result = mysqli_query($bdd, $query);
    if (!$result) {
        $success = false;
        break; // Si une insertion échoue, on stoppe
    }
}

echo $success ? 'success' : 'fail: ' . mysqli_error($bdd);

?>