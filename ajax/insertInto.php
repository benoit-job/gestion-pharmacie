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
?>