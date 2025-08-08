<?php
include("includes/connexion_acces_page.php");
include("includes/connexion_bdd.php");
include("includes/fonctions.php");
?>

<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau croisé des souscripteurs</title>
    <?php include('includes/php/includes-css.php');?>
    <style>
        .pivot-table th { position: sticky; top: 0; background: white; z-index: 10; }
        .pivot-table td, .pivot-table th { white-space: nowrap; }
        .highlight { background-color: #fff2cc !important; }
    </style>
    
</head>

<body>
    <main class="main" id="top">
        <?php include('includes/php/menu.php');?>
        <?php include('includes/php/header.php');?>

        <div class="content">
            <div class="pb-3">
                <div class="mb-8">
                    <h3 class="mb-2">Tableau croisé des souscripteurs</h3>
                    <h5 class="text-body-tertiary fw-semibold">Analyse multidimensionnelle des données</h5>
                </div>

                <!-- Tableau croisé dynamique -->
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Résultats du tableau croisé</h5>
                        <div>
                            
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm pivot-table" style="width:100%" data-title="Tableau croisé" id="pivotTable">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="bg-light">Région</th>
                                        <th colspan="2" class="text-center bg-light">Homme</th>
                                        <th colspan="2" class="text-center bg-light">Femme</th>
                                        <th colspan="2" class="text-center bg-light">Total</th>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Nombre</th>
                                        <th class="bg-light">Montant</th>
                                        <th class="bg-light">Nombre</th>
                                        <th class="bg-light">Montant</th>
                                        <th class="bg-light">Nombre</th>
                                        <th class="bg-light">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                            // Fonction pour normaliser civilité en genre Homme/Femme/autre
                                            function normalizeGenre($civilite) {
                                                $civilite = strtolower(trim($civilite));
                                                $hommes = ['m.', 'mr', 'monsieur', 'm'];
                                                $femmes = ['mme', 'mlle', 'madame', 'mademoiselle'];
                                                
                                                if (in_array($civilite, $hommes)) return 'Homme';
                                                if (in_array($civilite, $femmes)) return 'Femme';
                                                return 'Autre';
                                            }

                                            // Récupération des données par région et genre
                                            $query = "SELECT 
                                                        r.nom_region AS region,
                                                        s.civilite AS civilite,
                                                        COUNT(s.id_souscripteur) AS nombre,
                                                        SUM(s.montant_souscrit) AS montant_total
                                                    FROM souscripteurs s
                                                    JOIN lieu_exercices l ON s.id_lieu_exercice = l.id
                                                    JOIN regions r ON l.id_region = r.id
                                                    GROUP BY r.nom_region, s.civilite
                                                    ORDER BY r.nom_region, s.civilite";

                                            $result = mysqli_query($bdd, $query);

                                            $data = [];
                                            $regions = [];
                                            $totals = [
                                                'Homme' => ['nombre' => 0, 'montant' => 0],
                                                'Femme' => ['nombre' => 0, 'montant' => 0],
                                                'Autre' => ['nombre' => 0, 'montant' => 0],
                                                'Total' => ['nombre' => 0, 'montant' => 0],
                                            ];

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $region = $row['region'];
                                                $genre = normalizeGenre($row['civilite']);
                                                $nombre = (int)$row['nombre'];
                                                $montant = (float)$row['montant_total'];

                                                if (!in_array($region, $regions)) {
                                                    $regions[] = $region;
                                                }

                                                // Initialiser si pas encore set
                                                if (!isset($data[$region])) {
                                                    $data[$region] = [
                                                        'Homme' => ['nombre' => 0, 'montant' => 0],
                                                        'Femme' => ['nombre' => 0, 'montant' => 0],
                                                        'Autre' => ['nombre' => 0, 'montant' => 0],
                                                    ];
                                                }

                                                $data[$region][$genre]['nombre'] += $nombre;
                                                $data[$region][$genre]['montant'] += $montant;

                                                $totals[$genre]['nombre'] += $nombre;
                                                $totals[$genre]['montant'] += $montant;

                                                $totals['Total']['nombre'] += $nombre;
                                                $totals['Total']['montant'] += $montant;
                                            }
                                    ?>

                                    <!-- Tableau HTML -->
                                    <table id="pivotTable" class="table table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th rowspan="2">Région</th>
                                                <th colspan="2">Hommes</th>
                                                <th colspan="2">Femmes</th>
                                                <th colspan="2">Autres</th>
                                                <th colspan="2">Total</th>
                                            </tr>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Montant</th>
                                                <th>Nombre</th>
                                                <th>Montant</th>
                                                <th>Nombre</th>
                                                <th>Montant</th>
                                                <th>Nombre</th>
                                                <th>Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data as $region => $genres_data): ?>
                                                <?php 
                                                $totalNombreRegion = $genres_data['Homme']['nombre'] + $genres_data['Femme']['nombre'] + $genres_data['Autre']['nombre'];
                                                $totalMontantRegion = $genres_data['Homme']['montant'] + $genres_data['Femme']['montant'] + $genres_data['Autre']['montant'];
                                                ?>
                                                <tr>
                                                    <td><strong><?= htmlspecialchars($region) ?></strong></td>
                                                    <td><?= $genres_data['Homme']['nombre'] ?></td>
                                                    <td><?= number_format($genres_data['Homme']['montant'], 0, ',', ' ') ?></td>
                                                    <td><?= $genres_data['Femme']['nombre'] ?></td>
                                                    <td><?= number_format($genres_data['Femme']['montant'], 0, ',', ' ') ?></td>
                                                    <td><?= $genres_data['Autre']['nombre'] ?></td>
                                                    <td><?= number_format($genres_data['Autre']['montant'], 0, ',', ' ') ?></td>
                                                    <td><?= $totalNombreRegion ?></td>
                                                    <td><?= number_format($totalMontantRegion, 0, ',', ' ') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="table-active">
                                                <td><strong>Total</strong></td>
                                                <td><?= $totals['Homme']['nombre'] ?></td>
                                                <td><?= number_format($totals['Homme']['montant'], 0, ',', ' ') ?></td>
                                                <td><?= $totals['Femme']['nombre'] ?></td>
                                                <td><?= number_format($totals['Femme']['montant'], 0, ',', ' ') ?></td>
                                                <td><?= $totals['Autre']['nombre'] ?></td>
                                                <td><?= number_format($totals['Autre']['montant'], 0, ',', ' ') ?></td>
                                                <td><?= $totals['Total']['nombre'] ?></td>
                                                <td><?= number_format($totals['Total']['montant'], 0, ',', ' ') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Graphique croisé dynamique -->
                <div class="card mt-6">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Visualisation graphique</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="pivotBarChart" height="300"></canvas>
                            </div>
                            <div class="col-md-6">
                                <canvas id="pivotStackedChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php include('includes/php/footer.php');?>
        </div>
    </main>

    <?php include('includes/php/includes-js.php');?>

    <!-- Scripts pour le tableau croisé -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>


<script>
    // Préparation des données en PHP
    <?php
    $labels = [];
    $hommes = [];
    $femmes = [];
    $autres = [];

    foreach($data as $region => $genres_data) {
        $labels[] = $region;
        $hommes[] = $genres_data['Homme']['nombre'] ?? 0;
        $femmes[] = $genres_data['Femme']['nombre'] ?? 0;
        $autres[] = $genres_data['Autre']['nombre'] ?? 0;
    }
    ?>

    const labels = <?= json_encode($labels) ?>;
    const hommesData = <?= json_encode($hommes) ?>;
    const femmesData = <?= json_encode($femmes) ?>;
    const autresData = <?= json_encode($autres) ?>;

    // Vérification des données dans la console
    console.log("Données Hommes:", hommesData);
    console.log("Données Femmes:", femmesData);
    console.log("Données Autres:", autresData);

    // Bar chart simple
    const pivotBarCtx = document.getElementById('pivotBarChart').getContext('2d');
    const pivotBarChart = new Chart(pivotBarCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Hommes',
                    data: hommesData,
                    backgroundColor: '#36b9cc',
                    borderColor: '#36b9cc',
                    borderWidth: 1
                },
                {
                    label: 'Femmes',
                    data: femmesData,
                    backgroundColor: '#f6c23e',
                    borderColor: '#f6c23e',
                    borderWidth: 1
                },
                {
                    label: 'Autres',
                    data: autresData,
                    backgroundColor: '#9b59b6',
                    borderColor: '#9b59b6',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Répartition par genre et région'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y;
                        },
                        afterLabel: function(context) {
                            const idx = context.dataIndex;
                            const total = hommesData[idx] + femmesData[idx] + autresData[idx];
                            const val = context.parsed.y;
                            const pct = total ? Math.round((val / total) * 100) : 0;
                            return `Pourcentage: ${pct}%`;
                        }
                    }
                }
            },
            scales: {
                x: { stacked: false },
                y: { 
                    stacked: false, 
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Graphique empilé (avec la catégorie Autre ajoutée)
    const pivotStackedCtx = document.getElementById('pivotStackedChart').getContext('2d');
    const pivotStackedChart = new Chart(pivotStackedCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Hommes',
                    data: hommesData,
                    backgroundColor: '#36b9cc',
                    borderColor: '#36b9cc',
                    borderWidth: 1
                },
                {
                    label: 'Femmes',
                    data: femmesData,
                    backgroundColor: '#f6c23e',
                    borderColor: '#f6c23e',
                    borderWidth: 1
                },
                {
                    label: 'Autres',
                    data: autresData,
                    backgroundColor: '#9b59b6',
                    borderColor: '#9b59b6',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Répartition empilée par région'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                x: { 
                    stacked: true,
                    grid: {
                        display: false
                    }
                },
                y: { 
                    stacked: true, 
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Highlight des cellules au survol
    document.querySelectorAll('#pivotTable td').forEach(cell => {
        cell.addEventListener('mouseover', function() {
            const colIndex = this.cellIndex;
            const rowIndex = this.parentNode.rowIndex;

            // Highlight toute la ligne
            this.parentNode.querySelectorAll('td').forEach(td => {
                td.classList.add('bg-light');
            });

            // Highlight toute la colonne
            document.querySelectorAll(`#pivotTable tr td:nth-child(${colIndex + 1})`).forEach(td => {
                td.classList.add('bg-light');
            });
        });

        cell.addEventListener('mouseout', function() {
            document.querySelectorAll('#pivotTable td').forEach(td => {
                td.classList.remove('bg-light');
            });
        });
    });
</script>
</body>
</html>