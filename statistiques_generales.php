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
    <title>Vue d'ensemble des souscripteurs</title>
    <?php include('includes/php/includes-css.php');?>
</head>

<body>
    <main class="main" id="top">
        <?php include('includes/php/menu.php');?>
        <?php include('includes/php/header.php');?>

        <div class="content">
            <div class="pb-3">
                <div class="mb-8">
                    <h3 class="mb-2">Vue d'ensemble des souscripteurs</h3>
                    <h5 class="text-body-tertiary fw-semibold">Statistiques globales et indicateurs clés</h5>
                </div>

                <!-- Cartes de statistiques -->
                <div class="row g-3 mb-6">
                    <?php
                    // Requêtes pour les statistiques
                    $total_souscripteurs = mysqli_fetch_array(mysqli_query($bdd, "SELECT COUNT(*) as total FROM souscripteurs"))['total'];
                    $total_montant = mysqli_fetch_array(mysqli_query($bdd, "SELECT SUM(montant_souscrit) as total FROM souscripteurs"))['total'];
                    $total_versements = mysqli_fetch_array(mysqli_query($bdd, "SELECT SUM(montant) as total FROM versements_souscripteurs"))['total'];
                    $taux_versement = ($total_montant > 0) ? round(($total_versements / $total_montant) * 100, 2) : 0;
                    ?>

                    <!-- Carte 1: Total souscripteurs -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card h-100 bg-primary bg-opacity-10 border-primary border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-2 text-primary">Souscripteurs</h6>
                                        <h2 class="mb-0 text-primary"><?= number_format($total_souscripteurs, 0, ',', ' ') ?></h2>
                                    </div>
                                    <div class="bg-primary text-white p-3 rounded-circle">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte 2: Montant total souscrit -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card h-100 bg-success bg-opacity-10 border-success border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-2 text-success">Montant souscrit</h6>
                                        <h2 class="mb-0 text-success"><?= number_format($total_montant, 0, ',', ' ') ?> FCFA</h2>
                                    </div>
                                    <div class="bg-success text-white p-3 rounded-circle">
                                        <i class="fas fa-hand-holding-usd fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte 3: Total versé -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card h-100 bg-info bg-opacity-10 border-info border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-2 text-info">Total versé</h6>
                                        <h2 class="mb-0 text-info"><?= number_format($total_versements, 0, ',', ' ') ?> FCFA</h2>
                                    </div>
                                    <div class="bg-info text-white p-3 rounded-circle">
                                        <i class="fas fa-money-bill-wave fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte 4: Taux de versement -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card h-100 bg-warning bg-opacity-10 border-warning border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-2 text-warning">Taux de versement</h6>
                                        <h2 class="mb-0 text-warning"><?= $taux_versement ?>%</h2>
                                    </div>
                                    <div class="bg-warning text-white p-3 rounded-circle">
                                        <i class="fas fa-percentage fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="row g-3 mb-6">
                    <!-- Graphique 1: Evolution mensuelle -->
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Evolution mensuelle des souscriptions</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="monthlySubscriptionsChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Graphique 2: Répartition par montant -->
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Répartition par tranche de montant</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="amountDistributionChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau des derniers souscripteurs -->
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Derniers souscripteurs</h5>
                        <a href="liste_souscripteurs.php" class="btn btn-sm btn-phoenix-primary">Voir tous</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover m-0 usersTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Nom & Prénoms</th>
                                    <th>Etablissement</th>
                                    <th>Montant</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT s.id_souscripteur, CONCAT(s.nom, ' ', s.prenom) as nom_complet, 
                                         s.nom_etablissement, s.montant_souscrit, 
                                         DATE_FORMAT(s.date_souscription, '%d/%m/%Y') as date_souscription,
                                         s.active
                                         FROM souscripteurs s
                                         ORDER BY s.date_souscription DESC LIMIT 5";
                                $result = mysqli_query($bdd, $query);
                                
                                while($row = mysqli_fetch_array($result)) {
                                    $status_class = ($row['active'] == 'oui') ? 'badge bg-success' : 'badge bg-secondary';
                                    $status_text = ($row['active'] == 'oui') ? 'Actif' : 'Inactif';
                                    echo "<tr>
                                            <td>".htmlspecialchars($row['id_souscripteur'])."</td>
                                            <td>".htmlspecialchars(ucfirst($row['nom_complet']))."</td>
                                            <td>".htmlspecialchars($row['nom_etablissement'])."</td>
                                            <td>".number_format((float)($row['montant_souscrit'] ?? 0), 0, ',', '')." FCFA</td>
                                            <td>".htmlspecialchars($row['date_souscription'])."</td>
                                            <td><span class='$status_class'>$status_text</span></td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php include('includes/php/footer.php');?>
        </div>
    </main>

    <?php include('includes/php/includes-js.php');?>

    <!-- Scripts pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Graphique d'évolution mensuelle
        const monthlyCtx = document.getElementById('monthlySubscriptionsChart').getContext('2d');
        
        <?php
        // Requête pour les données mensuelles
        $query = "SELECT DATE_FORMAT(date_souscription, '%Y-%m') as mois, COUNT(*) as nombre 
                 FROM souscripteurs 
                 GROUP BY DATE_FORMAT(date_souscription, '%Y-%m') 
                 ORDER BY mois";
        $result = mysqli_query($bdd, $query);
        
        $labels = [];
        $data = [];
        while($row = mysqli_fetch_array($result)) {
            $labels[] = "'".date('M Y', strtotime($row['mois']))."'";
            $data[] = $row['nombre'];
        }
        ?>
        
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: [<?= implode(',', $labels) ?>],
                datasets: [{
                    label: 'Souscriptions mensuelles',
                    data: [<?= implode(',', $data) ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Graphique de répartition par montant
        const amountCtx = document.getElementById('amountDistributionChart').getContext('2d');
        
        <?php
        // Requête pour les tranches de montant
        $query = "SELECT 
                    CASE 
                        WHEN montant_souscrit < 1000000 THEN 'Moins de 1M'
                        WHEN montant_souscrit BETWEEN 1000000 AND 5000000 THEN '1M - 5M'
                        WHEN montant_souscrit BETWEEN 5000001 AND 10000000 THEN '5M - 10M'
                        ELSE 'Plus de 10M'
                    END as tranche,
                    COUNT(*) as nombre
                 FROM souscripteurs
                 GROUP BY tranche
                 ORDER BY tranche";
        $result = mysqli_query($bdd, $query);
        
        $tranches = [];
        $values = [];
        $colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'];
        
        while($row = mysqli_fetch_array($result)) {
            $tranches[] = "'".$row['tranche']."'";
            $values[] = $row['nombre'];
        }
        ?>
        
        const amountChart = new Chart(amountCtx, {
            type: 'doughnut',
            data: {
                labels: [<?= implode(',', $tranches) ?>],
                datasets: [{
                    data: [<?= implode(',', $values) ?>],
                    backgroundColor: [<?= "'".implode("','", array_slice($colors, 0, count($tranches)))."'" ?>],
                    hoverBackgroundColor: [<?= "'".implode("','", array_slice($colors, 0, count($tranches)))."'" ?>],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '70%',
            },
        });
    </script>
</body>
</html>