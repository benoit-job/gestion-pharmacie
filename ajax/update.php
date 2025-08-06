<?php 
include("../includes/connexion_acces_page.php");
include("../includes/connexion_bdd.php");
include("../includes/fonctions.php");

if (isset($_POST['nom']) && isset($_POST['prenom'])) {

    $id_souscripteur = intval($_POST['id_souscripteur']);
    $id_type_souscripteur = isset($_POST['id_type_souscripteur']) && is_numeric($_POST['id_type_souscripteur']) ? intval($_POST['id_type_souscripteur']) : "NULL";

    $civilite = mysqli_real_escape_string($bdd, trim($_POST['civilite'] ?? ''));
    $nom = mysqli_real_escape_string($bdd, trim($_POST['nom']));
    $prenom = mysqli_real_escape_string($bdd, trim($_POST['prenom']));
    $date_naissance = !empty($_POST['date_naissance']) ? mysqli_real_escape_string($bdd, $_POST['date_naissance']) : null;
    $lieu_naissance = !empty($_POST['lieu_naissance']) ? mysqli_real_escape_string($bdd, trim($_POST['lieu_naissance'])) : null;

    $adresse = mysqli_real_escape_string($bdd, trim($_POST['adresse']));
    $complement_adresse = !empty($_POST['complement_adresse']) ? mysqli_real_escape_string($bdd, trim($_POST['complement_adresse'])) : null;
    $nationalite = mysqli_real_escape_string($bdd, trim($_POST['nationalite']));
    $id_lieu_exercice = isset($_POST['id_lieu_exercice']) && is_numeric($_POST['id_lieu_exercice']) ? intval($_POST['id_lieu_exercice']) : "NULL";
    $telephone_fixe = !empty($_POST['telephone_fixe']) ? mysqli_real_escape_string($bdd, trim($_POST['telephone_fixe'])) : null;
    $telephone_portable = mysqli_real_escape_string($bdd, trim($_POST['telephone_portable']));
    $email = !empty($_POST['email']) ? mysqli_real_escape_string($bdd, trim($_POST['email'])) : null;

    $nom_etablissement = mysqli_real_escape_string($bdd, trim($_POST['nom_etablissement']));
    $secteur_activite = mysqli_real_escape_string($bdd, trim($_POST['secteur_activite']));
    $montant_souscrit = isset($_POST['montant_souscrit']) && is_numeric($_POST['montant_souscrit']) ? intval($_POST['montant_souscrit']) : "NULL";
    $montant_souscrit_type1 = isset($_POST['montant_souscrit_type1']) && is_numeric($_POST['montant_souscrit_type1']) ? intval($_POST['montant_souscrit_type1']) : "NULL";
    $montant_souscrit_type2 = isset($_POST['montant_souscrit_type2']) && is_numeric($_POST['montant_souscrit_type2']) ? intval($_POST['montant_souscrit_type2']) : "NULL";
    $nombre_actions = isset($_POST['nombre_actions']) && is_numeric($_POST['nombre_actions']) ? intval($_POST['nombre_actions']) : "NULL";
    $n_souscription = isset($_POST['n_souscription']) && is_numeric($_POST['n_souscription']) ? intval($_POST['n_souscription']) : "NULL";
    $date_souscription = !empty($_POST['date_souscription']) ? mysqli_real_escape_string($bdd, $_POST['date_souscription']) : null;
    $active = isset($_POST['active']) ? 'oui' : 'non';
    $id_user = intval($_SESSION['user']['id'] ?? 0);

    // --- GESTION IMAGE ---
    $image_etablissement = null; 
    if(!empty($_FILES['image_etablissement']['name'])) {
        $fileTmp  = $_FILES['image_etablissement']['tmp_name'];
        $fileName = basename($_FILES['image_etablissement']['name']);
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $destination = createPathFile('../fichiers/uploads/').uniqid().'.'.$fileExt;
        if(move_uploaded_file($fileTmp, $destination)) {
            $image_etablissement = mysqli_real_escape_string($bdd, $destination);
            
            // Suppression ancienne image
            $query = "SELECT image_etablissement FROM souscripteurs WHERE id_souscripteur = $id_souscripteur";
            $result = mysqli_query($bdd, $query);
            if($row = mysqli_fetch_assoc($result)) {
                if(!empty($row['image_etablissement']) && file_exists($row['image_etablissement'])) {
                    unlink($row['image_etablissement']);
                }
            }
        }
    }

    // --- UPDATE ---
    $query = "UPDATE souscripteurs SET
        id_type_souscripteur = $id_type_souscripteur,
        civilite = '$civilite',
        nom = '$nom',
        prenom = '$prenom',
        date_naissance = ".($date_naissance ? "'$date_naissance'" : "NULL").",
        lieu_naissance = ".($lieu_naissance ? "'$lieu_naissance'" : "NULL").",
        adresse = '$adresse',
        code_postal = ".($complement_adresse ? "'$complement_adresse'" : "NULL").",
        nationalite = '$nationalite',
        id_lieu_exercice = $id_lieu_exercice,
        telephone_fixe = ".($telephone_fixe ? "'$telephone_fixe'" : "NULL").",
        telephone_portable = '$telephone_portable',
        email = ".($email ? "'$email'" : "NULL").",
        nom_etablissement = '$nom_etablissement',
        secteur_activite = '$secteur_activite',
        montant_souscrit = $montant_souscrit,
        montant_souscrit_type1 = $montant_souscrit_type1,
        montant_souscrit_type2 = $montant_souscrit_type2,
        nombre_actions = $nombre_actions,
        n_souscription = $n_souscription,
        active = '$active',
        date_souscription = ".($date_souscription ? "'$date_souscription'" : "NULL").",
        date_update = '".date('Y-m-d H:i:s')."'";

    if(!empty($image_etablissement)) {
        $query .= ", image_etablissement = '$image_etablissement'";
    }

    $query .= " WHERE id_souscripteur = $id_souscripteur";

    $result = mysqli_query($bdd, $query);

    echo $result ? 'success' : 'fail: ' . mysqli_error($bdd);
}


if (!isset($_POST['versements'])) {
    exit('no_data');
}

$data = json_decode($_POST['versements'], true);
if (!is_array($data) || empty($data)) {
    exit('no_data');
}

$success = true;

foreach ($data as $row) {
    $id_versement = intval(crypt_decrypt_chaine($row['id_versement'], 'D')); 
    $montant = floatval($row['montant']);
    $date = mysqli_real_escape_string($bdd, $row['date']);
    $nature = mysqli_real_escape_string($bdd, $row['nature']);

    // ⚡ UPDATE uniquement
    $query = "UPDATE versements_souscripteurs
              SET montant = $montant,
                  date = '$date',
                  nature = '$nature',
                  date_update = NOW()
              WHERE id = $id_versement";

    if (!mysqli_query($bdd, $query)) {
        $success = false;
        break;
    }
}

echo $success ? 'success' : 'fail: ' . mysqli_error($bdd);

?>
