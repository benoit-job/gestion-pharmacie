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
    <title>Répartition des souscripteurs par genre</title>
    <?php include('includes/php/includes-css.php');?>
</head>

<body>
    <main class="main" id="top">
        <?php include('includes/php/menu.php');?>
        <?php include('includes/php/header.php');?>

        <div class="content">
            <div class="pb-3">
                <div class="mb-8">
                    <h3 class="mb-2">Répartition des souscripteurs par genre</h3>
                    <h5 class="text-body-tertiary fw-semibold">Analyse démographique des souscripteurs</h5>
                </div>

                <div class="row g-3 mb-6">
                    <!-- Graphique principal -->
                    <div class="col-lg-8">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Répartition par genre</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="genderChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques détaillées -->
                    <div class="col-lg-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Statistiques par genre</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                // Requêtes pour les données par genre
                                $query = "SELECT 
                                            civilite as genre, 
                                            COUNT(*) as nombre,
                                            SUM(montant_souscrit) as total_montant,
                                            AVG(montant_souscrit) as moyenne_montant
                                          FROM souscripteurs
                                          GROUP BY civilite";
                                $result = mysqli_query($bdd, $query);
                                
                                $total_genre = mysqli_fetch_array(mysqli_query($bdd, "SELECT COUNT(*) as total FROM souscripteurs"))['total'];
                                ?>
                                
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Genre</th>
                                                <th>Nombre</th>
                                                <th>%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while($row = mysqli_fetch_array($result)) {
                                                $percentage = round(($row['nombre'] / $total_genre) * 100, 1);
                                                echo "<tr>
                                                        <td>".htmlspecialchars($row['genre'])."</td>
                                                        <td>".number_format($row['nombre'], 0, ',', ' ')."</td>
                                                        <td>
                                                            <div class='progress' style='height: 20px;'>
                                                                <div class='progress-bar' role='progressbar' style='width: $percentage%' aria-valuenow='$percentage' aria-valuemin='0' aria-valuemax='100'>$percentage%</div>
                                                            </div>
                                                        </td>
                                                      </tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <hr>
                                
                                <h6 class="mb-3">Montants par genre</h6>
                                <div class="row">
                                    <?php
                                    mysqli_data_seek($result, 0); // Reset le pointeur du résultat
                                    while($row = mysqli_fetch_array($result)) {
                                        echo "<div class='col-md-6 mb-3'>
                                                <div class='card border-0 bg-light'>
                                                    <div class='card-body p-3'>
                                                        <h6 class='mb-1'>".htmlspecialchars($row['genre'])."</h6>
                                                        <p class='mb-0 text-700'>Total: ".number_format($row['total_montant'], 0, ',', ' ')." FCFA</p>
                                                        <p class='mb-0 text-700'>Moyenne: ".number_format($row['moyenne_montant'], 0, ',', ' ')." FCFA</p>
                                                    </div>
                                                </div>
                                              </div>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau croisé genre/région -->
                <div class="card mb-6">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Répartition genre/région</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-200">
                                    <tr>
                                        <th>Région</th>
                                        <?php
                                        // En-têtes de colonnes (genres)
                                        $query_genres = "SELECT DISTINCT civilite FROM souscripteurs ORDER BY civilite";
                                        $result_genres = mysqli_query($bdd, $query_genres);
                                        
                                        echo "<th>Total</th>";
                                        while($genre = mysqli_fetch_array($result_genres)) {
                                            echo "<th>".htmlspecialchars($genre['civilite'])."</th>";
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Corps du tableau (régions vs genres)
                                    $query_regions = "SELECT r.id, r.nom_region 
                                                     FROM regions r
                                                     JOIN lieu_exercices l ON l.id_region = r.id
                                                     JOIN souscripteurs s ON s.id_lieu_exercice = l.id
                                                     GROUP BY r.id, r.nom_region
                                                     ORDER BY r.nom_region";
                                    $result_regions = mysqli_query($bdd, $query_regions);
                                    
                                    while($region = mysqli_fetch_array($result_regions)) {
                                        echo "<tr>
                                                <td>".htmlspecialchars($region['nom_region'])."</td>";
                                        
                                        // Total pour la région
                                        $query_total = "SELECT COUNT(*) as total 
                                                        FROM souscripteurs s
                                                        JOIN lieu_exercices l ON s.id_lieu_exercice = l.id
                                                        WHERE l.id_region = '".$region['id']."'";
                                        $total_region = mysqli_fetch_array(mysqli_query($bdd, $query_total))['total'];
                                        echo "<td><strong>".$total_region."</strong></td>";
                                        
                                        // Par genre
                                        mysqli_data_seek($result_genres, 0);
                                        while($genre = mysqli_fetch_array($result_genres)) {
                                            $query_count = "SELECT COUNT(*) as count 
                                                           FROM souscripteurs s
                                                           JOIN lieu_exercices l ON s.id_lieu_exercice = l.id
                                                           WHERE l.id_region = '".$region['id']."' 
                                                           AND s.civilite = '".$genre['civilite']."'";
                                            $count = mysqli_fetch_array(mysqli_query($bdd, $query_count))['count'];
                                            echo "<td>".$count."</td>";
                                        }
                                        
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('includes/php/footer.php');?>
    </main>

    <?php include('includes/php/includes-js.php');?>

    <!-- Script pour le graphique genre -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        
        <?php
        // Requête pour les données de genre
        $query = "SELECT civilite as genre, COUNT(*) as nombre 
                 FROM souscripteurs 
                 GROUP BY civilite";
        $result = mysqli_query($bdd, $query);
        
        $labels = [];
        $data = [];
        $colors = [];
        
        while($row = mysqli_fetch_array($result)) {
            $labels[] = "'".htmlspecialchars($row['genre'])."'";
            $data[] = $row['nombre'];
            
            // Couleurs différentes selon le genre
            if(strtolower($row['genre']) == 'homme') {
                $colors[] = "'#36b9cc'";
            } elseif(strtolower($row['genre']) == 'femme') {
                $colors[] = "'#f6c23e'";
            } else {
                $colors[] = "'#858796'";
            }
        }
        ?>
        
        const genderChart = new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: [<?= implode(',', $labels) ?>],
                datasets: [{
                    data: [<?= implode(',', $data) ?>],
                    backgroundColor: [<?= implode(',', $colors) ?>],
                    hoverBackgroundColor: [<?= implode(',', $colors) ?>],
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
            },
        });
    </script>
</body>
</html>