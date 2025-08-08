<?php
include("../includes/connexion_acces_page.php");
include("../includes/connexion_bdd.php");
include("../includes/fonctions.php");

header('Content-Type: application/json');

$regionId = isset($_GET['region_id']) ? intval($_GET['region_id']) : '';

$query = "
    SELECT 
        le.id,
        le.nom_lieu,
        s.montant_souscrit,
        s.nom_etablissement,
        s.telephone_portable,
        CONCAT(s.nom, ' ', s.prenom) AS nom_prenom,
        le.lieulat,
        le.lieulong,
        r.nom_region,
        s.image_etablissement AS image_path
    FROM 
        lieu_exercices le
    LEFT JOIN 
        regions r ON le.id_region = r.id
    LEFT JOIN
        souscripteurs s ON s.id_lieu_exercice = le.id
    WHERE 
        le.active = 'oui'";

if (!empty($regionId)) {
    $query .= " AND le.id_region = " . $regionId;
}

$result = mysqli_query($bdd, $query);

if (!$result) {
    // En cas d'erreur SQL, renvoyer un JSON avec le message d'erreur
    echo json_encode(["error" => mysqli_error($bdd)]);
    exit;
}

$pharmacies = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Si le champ image_path est vide → image par défaut
    if (empty($row['image_path'])) {
        $row['image_path'] = 'assets/img/default-pharmacy.png';
    } else {
        // Supprimer "../" s'il est présent au début
        $row['image_path'] = preg_replace('/^(\.\.\/)+/', '', $row['image_path']);
    }

    $pharmacies[] = $row;
}


echo json_encode($pharmacies);
