<?php
include("includes/connexion_acces_page.php");
include("includes/connexion_bdd.php");
include("includes/fonctions.php");
include_once("includes/auth_functions.php");   
?>

<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cartographie des Pharmacies</title>
    <?php include('includes/php/includes-css.php');?>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        #map { height: 600px; }
        .custom-popup img { max-width: 200px; max-height: 150px; margin-bottom: 10px; }

        /* Dans votre fichier CSS */
.custom-popup {
    max-width: 300px !important;
}

.custom-popup img {
    max-height: 150px;
    width: auto;
    margin: 0 auto;
    display: block;
}

.custom-popup .leaflet-popup-content-wrapper {
    padding: 0;
}

.custom-popup .leaflet-popup-content {
    margin: 0;
    width: auto !important;
}
.leaflet-marker-icon {
    width: 30px !important;
    height: 30px !important;
    border-radius: 10px !important;
    border: 2px solid white !important; /* Cadre blanc */
    box-shadow: 0 0 3px rgba(0,0,0,0.5) !important; 
    object-fit: cover !important; 
}

    </style>
</head>

<body>
    <main class="main" id="top">
        <?php include('includes/php/menu.php');?>
        <?php include('includes/php/header.php');?>

        <div class="content">
            <div class="pb-3">
                <div class="mb-4">
                    <h3 class="mb-2">Cartographie des Pharmacies</h3>
                    <h5 class="text-body-tertiary fw-semibold">Visualisation géographique des souscripteurs</h5>
                </div>

                <div class="page-section">
                    <div class="card card-fluid">
                        <div class="card-header border-0 p-1 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <select id="filter-region" class="form-select me-2" style="width: 200px;">
                                    <option value="">Toutes les régions</option>
                                    <?php
                                    $query = "SELECT id, UPPER(nom_region) AS nom_region FROM regions WHERE active = 'oui' ORDER BY nom_region";
                                    $resultat = mysqli_query($bdd, $query);
                                    while ($region = mysqli_fetch_assoc($resultat)) {
                                        echo "<option value='".htmlspecialchars($region['id'])."'>".htmlspecialchars($region['nom_region'])."</option>";
                                    }
                                    ?>
                                </select>
                                <button id="reset-filters" class="btn btn-sm btn-outline-secondary">Réinitialiser</button>
                            </div>
                            <button id="locate-me" class="btn btn-sm btn-success">
                                <i class="fas fa-location-arrow me-1"></i> Me localiser
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
            </div>
        
        <?php include('includes/php/footer.php');?>
        </div>
    </main>

    <?php include('includes/php/includes-js.php');?>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialiser la carte
            const map = L.map('map').setView([7.5399, -5.5471], 7); // Centré sur la Côte d'Ivoire

            // Ajouter le fond de carte
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            let lastOpenedPopup = null; // Pour gérer l'affichage en premier plan

            // Fonction pour charger les pharmacies
            function loadPharmacies(regionId = '') {
                $.ajax({
                    url: 'ajax/carte_view.php',
                    type: 'GET',
                    data: { region_id: regionId },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);

                        // Supprimer les anciens marqueurs
                        map.eachLayer(function(layer) {
                            if (layer instanceof L.Marker) {
                                map.removeLayer(layer);
                            }
                        });

                        let bounds = [];

                        data.forEach(function(pharmacy, index) {
                            if (pharmacy.lieulat && pharmacy.lieulong) {

                                const markerIcon = L.icon({
                                    iconUrl: pharmacy.image_path,
                                    iconSize: [15, 15],
                                    iconAnchor: [7, 15],
                                    popupAnchor: [0, -15]
                                });

                                const marker = L.marker([pharmacy.lieulat, pharmacy.lieulong], { icon: markerIcon }).addTo(map);

                                // Contenu du popup
                            let popupContent = `
                                <div class="custom-popup" style="
                                    max-width: 280px;
                                    font-family: 'Segoe UI', Roboto, sans-serif;
                                    border-radius: 12px;
                                    overflow: hidden;
                                    box-shadow: 0 6px 18px rgba(0,0,0,0.15);">
                                    <!-- En-tête avec image -->
                                    <div style="
                                        height: 150px;
                                        overflow: hidden;
                                        position: relative;
                                    ">
                                        <img src="${pharmacy.image_path}" 
                                            alt="${pharmacy.nom_etablissement}" 
                                            style="
                                                width: 100%;
                                                height: 100%;
                                                object-fit: cover;
                                                transition: transform 0.3s;
                                            "
                                            onmouseover="this.style.transform='scale(1.03)'"
                                            onmouseout="this.style.transform='scale(1)'">
                                        <div style="
                                            position: absolute;
                                            bottom: 0;
                                            left: 0;
                                            right: 0;
                                            background: linear-gradient(transparent, rgba(0,0,0,0.7));
                                            padding: 10px;
                                        ">
                                            <h4 style="
                                                margin: 0;
                                                color: white;
                                                font-size: 18px;
                                                font-weight: 600;
                                                text-shadow: 1px 1px 3px rgba(0,0,0,0.8);
                                            ">${pharmacy.nom_etablissement}</h4>
                                        </div>
                                    </div>

                                    <!-- Corps du popup -->
                                    <div style="
                                        padding: 15px;
                                        background: white;
                                    ">
                                        <div style="
                                            display: flex;
                                            align-items: center;
                                            margin-bottom: 10px;
                                        ">
                                            <span style="
                                                background: #f0f7ff;
                                                width: 30px;
                                                height: 30px;
                                                border-radius: 50%;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                margin-right: 10px;
                                                color: #2a7bf5;
                                            ">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            <div>
                                                <p style="margin: 0; font-weight: 500;">${pharmacy.nom_prenom}</p>
                                                <p style="margin: 0; font-size: 13px; color: #666;">Propriétaire</p>
                                            </div>
                                        </div>

                                        <div style="
                                            display: flex;
                                            align-items: center;
                                            margin-bottom: 10px;
                                        ">
                                            <span style="
                                                background: #f0f7ff;
                                                width: 30px;
                                                height: 30px;
                                                border-radius: 50%;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                margin-right: 10px;
                                                color: #2a7bf5;
                                            ">
                                                <i class="fas fa-phone"></i>
                                            </span>
                                            <p style="margin: 0; font-weight: 500;">${pharmacy.telephone_portable}</p>
                                        </div>

                                        <div style="
                                            display: flex;
                                            align-items: center;
                                            margin-bottom: 15px;
                                        ">
                                            <span style="
                                                background: #f0f7ff;
                                                width: 30px;
                                                height: 30px;
                                                border-radius: 50%;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                margin-right: 10px;
                                                color: #2a7bf5;
                                            ">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                            <p style="margin: 0;">${pharmacy.nom_lieu}</p>
                                        </div>

                                        <div style="
                                            background: #f8f9fa;
                                            border-radius: 8px;
                                            padding: 10px;
                                            margin-bottom: 15px;
                                        ">
                                            <p style="margin: 0 0 5px 0; font-size: 13px;"><strong>Région:</strong> ${pharmacy.nom_region}</p>
                                            <p style="margin: 0; font-size: 13px;"><strong>Investissement:</strong> <span style="color: #2a7bf5; font-weight: 600;">${pharmacy.montant_souscrit || 'N/A'} F CFA</span></p>
                                        </div>

                                        <a href="https://www.google.com/maps?q=${pharmacy.lieulat},${pharmacy.lieulong}" 
                                        target="_blank" 
                                        style="
                                                display: block;
                                                text-align: center;
                                                background: linear-gradient(to right, #2a7bf5, #4facfe);
                                                color: white;
                                                padding: 8px;
                                                border-radius: 6px;
                                                text-decoration: none;
                                                font-weight: 500;
                                                transition: all 0.3s;
                                                box-shadow: 0 2px 8px rgba(42, 123, 245, 0.3);
                                        "
                                        onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-1px)'"
                                        onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)'">
                                            <i class="fas fa-map-marked-alt"></i> Voir sur Google Maps
                                        </a>
                                    </div>
                                </div>`;

                                marker.bindPopup(popupContent, {
                                    maxWidth: 500,
                                    autoPan: true,
                                    closeButton: true
                                });

                                // Ouvrir le popup automatiquement pour le premier marqueur
                                if (index === 0) {
                                    marker.openPopup();
                                    lastOpenedPopup = marker;
                                }

                                // Quand on clique, mettre ce popup au premier plan
                                marker.on('click', function() {
                                    if (lastOpenedPopup && lastOpenedPopup !== marker) {
                                        lastOpenedPopup.closePopup();
                                    }
                                    marker.openPopup();
                                    lastOpenedPopup = marker;
                                });

                                bounds.push([pharmacy.lieulat, pharmacy.lieulong]);
                            }
                        });
                        showToastSupp('success', 'cartographire afficher avec succes !');

                        // Ajuster la vue
                        if (bounds.length > 0) {
                            map.fitBounds(bounds);
                        }
                    },
                    error: function() {
                        console.error("Erreur lors du chargement des pharmacies");
                    }
                });
            }

            // Charger au démarrage
            loadPharmacies();

            // Filtrer par région
            $('#filter-region').change(function() {
                loadPharmacies($(this).val());
            });

            // Réinitialiser
            $('#reset-filters').click(function() {
                $('#filter-region').val('').trigger('change');
            });

            // Localisation utilisateur
            $('#locate-me').click(function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        map.setView([position.coords.latitude, position.coords.longitude], 15);
                        L.marker([position.coords.latitude, position.coords.longitude])
                            .addTo(map)
                            .bindPopup("Votre position actuelle")
                            .openPopup();
                    }, function(error) {
                            showToast('erreur', 'Impossible d obtenir votre position');
                    });
                } else {
                    showToast('erreur', 'La géolocalisation n est pas supportée par votre navigateur');
                }
            });
        });
    </script>

</body>
</html>