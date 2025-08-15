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
    <title>Répartition des souscripteurs par région</title>
    <?php include('includes/php/includes-css.php');?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map { height: 500px; border-radius: 0.5rem; }
        .region-card { transition: transform 0.3s; }
        .region-card:hover { transform: translateY(-5px); }
    </style>
</head>

<body>
    <main class="main" id="top">
        <?php include('includes/php/menu.php');?>
        <?php include('includes/php/header.php');?>

        <div class="content">
            <div class="pb-3">
                <div class="mb-8">
                    <h3 class="mb-2">Répartition des souscripteurs par région</h3>
                    <h5 class="text-body-tertiary fw-semibold">Analyse géographique des souscripteurs</h5>
                </div>

                <!-- Carte et graphique -->
                <div class="row g-3 mb-6">
                    <!-- Carte des régions -->
                    <div class="col-lg-7">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Carte des souscripteurs par région</h5>
                            </div>
                            <div class="card-body p-0">
                                <div id="map"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Graphique des régions -->
                    <div class="col-lg-5">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Nombre de souscripteurs par région</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="regionChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cartes de statistiques par région -->
                <div class="row g-3 mb-6">
                    <?php
                    // Requête pour les statistiques par région
                    $query = "SELECT r.id, r.nom_region, 
                             COUNT(s.id_souscripteur) as nombre_souscripteurs,
                             SUM(s.montant_souscrit) as total_montant,
                             AVG(s.montant_souscrit) as moyenne_montant
                             FROM regions r
                             LEFT JOIN lieu_exercices l ON l.id_region = r.id
                             LEFT JOIN souscripteurs s ON s.id_lieu_exercice = l.id
                             GROUP BY r.id, r.nom_region
                             ORDER BY nombre_souscripteurs DESC";
                    $result = mysqli_query($bdd, $query);
                    
                    while($region = mysqli_fetch_array($result)) {
                        $percentage = ($region['nombre_souscripteurs'] > 0) ? round(($region['total_montant'] / $region['nombre_souscripteurs']) / 10000) * 100 : 0;
                        $color_class = ($percentage > 75) ? 'bg-success' : (($percentage > 50) ? 'bg-info' : (($percentage > 25) ? 'bg-warning' : 'bg-danger'));
                        
                        echo "<div class='col-md-6 col-lg-4'>
                                <div class='card region-card h-100'>
                                    <div class='card-header bg-light'>
                                        <h5 class='mb-0'>".htmlspecialchars($region['nom_region'])."</h5>
                                    </div>
                                    <div class='card-body'>
                                        <div class='row g-2 mb-3'>
                                            <div class='col-6'>
                                                <div class='d-flex align-items-center'>
                                                    <div class='bg-primary bg-opacity-10 p-2 rounded me-3'>
                                                        <i class='fas fa-users text-primary'></i>
                                                    </div>
                                                    <div>
                                                        <p class='mb-0 text-800'>Souscripteurs</p>
                                                        <h5 class='mb-0 text-primary'>".number_format((float)($region['nombre_souscripteurs'] ?? 0), 0, ',', '')."</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='col-6'>
                                                <div class='d-flex align-items-center'>
                                                    <div class='bg-success bg-opacity-10 p-2 rounded me-3'>
                                                        <i class='fas fa-money-bill-wave text-success'></i>
                                                    </div>
                                                    <div>
                                                        <p class='mb-0 text-800'>Montant total</p>
                                                        <h5 class='mb-0 text-success'>".number_format((float)($region['total_montant'] ?? 0), 0, ',', '')." FCFA</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='mb-3'>
                                            <p class='mb-1 text-800'>Moyenne par souscripteur</p>
                                            <h5 class='mb-0'>".number_format((float)($region['moyenne_montant'] ?? 0), 0, ',', '')." FCFA</h5>
                                        </div>
                                        
                                        <div>
                                            <p class='mb-1 text-800'>Taux d'engagement</p>
                                            <div class='progress rounded-pill' style='height: 10px;'>
                                                <div class='progress-bar $color_class' role='progressbar' style='width: $percentage%' aria-valuenow='$percentage' aria-valuemin='0' aria-valuemax='100'></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='card-footer bg-light py-2'>
                                    </div>
                                </div>
                              </div>";
                    }
                    ?>
                </div>

                <!-- Tableau détaillé -->
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Détails par région</h5>
                        <div class="d-flex justify-content-center">
                            <!-- Bouton Excel -->
                            <div class="tooltip-wrapper me-4" data-tooltip="Exporter en Excel">
                                <button type="button" class="export-btn excel-btn animate-float" onclick="exportExcel()">
                                    <span class="fas fa-file-excel"></span>
                                </button>
                            </div>
                            
                            <!-- Bouton Word -->
                            <div class="tooltip-wrapper me-4" data-tooltip="Exporter en Word">
                                <button type="button" class="export-btn word-btn animate-float" onclick="exportWord()">
                                    <span class="fas fa-file-word"></span>
                                </button>
                            </div>
                            
                            <!-- Bouton PDF -->
                            <div class="tooltip-wrapper" data-tooltip="Exporter en PDF">
                                <button type="button" class="export-btn pdf-btn animate-float" onclick="exportPDF()">
                                    <span class="fas fa-file-pdf"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover m-0 usersTable" style="width:100%" data-title="Répartition par région" id="regionTable">
                            <thead>
                                <tr>
                                    <th>Région</th>
                                    <th>Souscripteurs</th>
                                    <th>% Total</th>
                                    <th>Montant total</th>
                                    <th>Moyenne</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                mysqli_data_seek($result, 0); // Reset du pointeur
                                $total_souscripteurs = mysqli_fetch_array(mysqli_query($bdd, "SELECT COUNT(*) as total FROM souscripteurs"))['total'];
                                
                                while($region = mysqli_fetch_array($result)) {
                                    $percentage = ($total_souscripteurs > 0) ? round(($region['nombre_souscripteurs'] / $total_souscripteurs) * 100, 1) : 0;
                                    echo "<tr>
                                            <td>".htmlspecialchars($region['nom_region'])."</td>
                                            <td>".number_format((float)($region['nombre_souscripteurs'] ?? 0), 0, ',', '')."</td>
                                            <td>
                                                <div class='d-flex align-items-center'>
                                                    <div class='progress flex-grow-1' style='height: 5px;'>
                                                        <div class='progress-bar' role='progressbar' style='width: $percentage%' aria-valuenow='$percentage' aria-valuemin='0' aria-valuemax='100'></div>
                                                    </div>
                                                    <small class='ms-2 text-700'>$percentage%</small>
                                                </div>
                                            </td>
                                            <td>".number_format((float)($region['total_montant'] ?? 0), 0, ',', '')." FCFA</td>
                                            <td>".number_format((float)($region['moyenne_montant'] ?? 0), 0, ',', '')." FCFA</td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php include('includes/php/footer.php');?>
    </main>

    <?php include('includes/php/includes-js.php');?>

    <!-- Scripts pour la carte et les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        
        <?php
// Nouvelle requête : récupère le nom de région, lat/lng et le nombre de souscripteurs
$query = "
    SELECT 
        r.nom_region,
        l.lieulat,
        l.lieulong,
        COUNT(s.id_souscripteur) AS nombre_souscripteurs
    FROM regions r
    LEFT JOIN lieu_exercices l ON l.id_region = r.id
    LEFT JOIN souscripteurs s ON s.id_lieu_exercice = l.id
    GROUP BY r.nom_region, l.lieulat, l.lieulong
    HAVING l.lieulat IS NOT NULL AND l.lieulong IS NOT NULL
    ORDER BY nombre_souscripteurs DESC
";
$result = mysqli_query($bdd, $query);

// Tableau pour Chart.js
$labels = [];
$data = [];
$colors = [];
$colorPalette = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#5a5c69', '#858796', '#e74a3b', '#fd7e14'];

// Tableau pour Leaflet
$regions_map_data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = "'".htmlspecialchars($row['nom_region'])."'";
    $data[] = $row['nombre_souscripteurs'];
    $colors[] = "'".$colorPalette[count($colors) % count($colorPalette)]."'";

    $regions_map_data[] = [
        'nom_region' => $row['nom_region'],
        'latitude' => (float)$row['lieulat'],
        'longitude' => (float)$row['lieulong'],
        'nombre_souscripteurs' => (int)$row['nombre_souscripteurs']
    ];
}
?>

        
        // ------------------ Chart.js ------------------
const regionCtx = document.getElementById('regionChart').getContext('2d');

const regionChart = new Chart(regionCtx, {
    type: 'bar',
    data: {
        labels: [<?= implode(',', $labels) ?>],
        datasets: [{
            label: 'Nombre de souscripteurs',
            data: [<?= implode(',', $data) ?>],
            backgroundColor: [<?= implode(',', $colors) ?>],
            borderColor: [<?= implode(',', $colors) ?>],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.parsed.y + ' souscripteurs';
                    }
                }
            }
        },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});

// ------------------ Leaflet ------------------
const map = L.map('map').setView([7.5399, -5.5471], 7);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

const regionsMapData = <?= json_encode($regions_map_data) ?>;

regionsMapData.forEach(region => {
    L.marker([region.latitude, region.longitude])
        .addTo(map)
        .bindPopup(`<b>${region.nom_region}</b><br>${region.nombre_souscripteurs} souscripteurs`);
});
    </script>
</body>
</html>