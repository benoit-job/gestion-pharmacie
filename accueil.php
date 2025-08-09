<?php
include("includes/connexion_acces_page.php");
include("includes/connexion_bdd.php");
include("includes/fonctions.php");

// Vérification de la session utilisateur
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

// Fonction pour récupérer les statistiques
function getStatistics($bdd) {
    $stats = [];
    
    // Souscripteurs
    $res = mysqli_query($bdd, "SELECT COUNT(*) as nb FROM souscripteurs");
    $stats['total_souscripteurs'] = mysqli_fetch_assoc($res)['nb'] ?? 0;
    
    // Montant souscrit
    $res = mysqli_query($bdd, "SELECT SUM(montant_souscrit) as total FROM souscripteurs");
    $stats['total_montant'] = mysqli_fetch_assoc($res)['total'] ?? 0;
    
    // Versements
    $res = mysqli_query($bdd, "SELECT SUM(montant) as total FROM versements_souscripteurs");
    $stats['total_versements'] = mysqli_fetch_assoc($res)['total'] ?? 0;
    
    // Taux de versement
    $stats['taux_versement'] = ($stats['total_montant'] > 0) 
        ? round(($stats['total_versements'] / $stats['total_montant']) * 100, 2) 
        : 0;
    
    return $stats;
}

// Fonction pour récupérer les tendances mensuelles
function getMonthlyTrends($bdd, $months = 6) {
    $data = [];
    for ($i = $months-1; $i >= 0; $i--) {
        $mois = date('Y-m', strtotime("-$i months"));
        $label = date('M Y', strtotime($mois . '-01'));
        
        // Souscriptions
        $query = "SELECT COUNT(*) as nb, SUM(montant_souscrit) as total 
                 FROM souscripteurs 
                 WHERE DATE_FORMAT(date_souscription, '%Y-%m') = '$mois'";
        $res = mysqli_query($bdd, $query);
        $row = mysqli_fetch_assoc($res);
        
        // Versements
        $query = "SELECT SUM(montant) as versements 
                 FROM versements_souscripteurs 
                 WHERE DATE_FORMAT(date, '%Y-%m') = '$mois'";
        $res_vers = mysqli_query($bdd, $query);
        $vers = mysqli_fetch_assoc($res_vers);
        
        $data['labels'][] = $label;
        $data['souscriptions'][] = $row['nb'] ?? 0;
        $data['montants'][] = $row['total'] ?? 0;
        $data['versements'][] = $vers['versements'] ?? 0;
    }
    return $data;
}

// Fonction pour répartition par genre
function getGenderDistribution($bdd) {
    $data = ['Homme' => 0, 'Femme' => 0, 'Autre' => 0];
    $total = 0;
    
    $res = mysqli_query($bdd, "SELECT 
        CASE 
            WHEN civilite = 'M.' THEN 'Homme'
            WHEN civilite IN ('Mme', 'Mlle') THEN 'Femme'
            ELSE 'Autre'
        END as genre, 
        COUNT(*) as nb 
        FROM souscripteurs 
        GROUP BY genre");
    
    while($row = mysqli_fetch_assoc($res)) {
        $data[$row['genre']] = $row['nb'];
        $total += $row['nb'];
    }
    
    return [
        'data' => $data,
        'total' => $total,
        'percent' => [
            'Homme' => ($total > 0) ? round($data['Homme'] / $total * 100, 1) : 0,
            'Femme' => ($total > 0) ? round($data['Femme'] / $total * 100, 1) : 0,
            'Autre' => ($total > 0) ? round($data['Autre'] / $total * 100, 1) : 0
        ]
    ];
}

// Récupération des données
$stats = getStatistics($bdd);
$monthlyData = getMonthlyTrends($bdd);
$genderData = getGenderDistribution($bdd);

// Derniers souscripteurs
$lastSubscribers = [];
$res = mysqli_query($bdd, "SELECT 
    s.id_souscripteur, 
    CONCAT(s.nom, ' ', s.prenom) as nom_complet,
    s.nom_etablissement,
    s.montant_souscrit,
    DATE_FORMAT(s.date_souscription, '%d/%m/%Y') as date_souscription,
    r.nom_region
    FROM souscripteurs s
    LEFT JOIN lieu_exercices l ON s.id_lieu_exercice = l.id
    LEFT JOIN regions r ON l.id_region = r.id
    ORDER BY s.date_souscription DESC 
    LIMIT 5");
while($row = mysqli_fetch_assoc($res)) {
    $lastSubscribers[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tableau de bord | Gestion Souscripteurs</title>
  <?php include('includes/php/includes-css.php');?>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .stat-card {
      padding: 1.5rem;
      border-radius: 0.5rem;
      background: white;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      transition: all 0.3s ease;
      border-left: 4px solid;
      height: 100%;
    }
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .stat-card.souscripteurs { border-left-color: #4e73df; }
    .stat-card.montant { border-left-color: #1cc88a; }
    .stat-card.versements { border-left-color: #36b9cc; }
    .stat-card.taux { border-left-color: #f6c23e; }
    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }
    .stat-card.souscripteurs .stat-value { color: #4e73df; }
    .stat-card.montant .stat-value { color: #1cc88a; }
    .stat-card.versements .stat-value { color: #36b9cc; }
    .stat-card.taux .stat-value { color: #f6c23e; }
    .stat-label {
      font-size: 0.875rem;
      color: #6c757d;
      margin-bottom: 0.5rem;
    }
    .stat-icon {
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
    .chart-container {
      background: white;
      border-radius: 0.5rem;
      padding: 1.5rem;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      margin-bottom: 1.5rem;
      height: 100%;
    }
    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #f8f9fa;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      color: #4e73df;
    }
    .badge-percent {
      font-size: 0.75rem;
      padding: 0.25rem 0.5rem;
    }
  </style>
</head>
<body>

<?php include('includes/php/menu.php');?>
<?php include('includes/php/header.php');?>

<div class="content">
  <div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Tableau de bord</h1>
      <div class="d-none d-sm-inline-block">
        <span class="text-muted"><?= date('d/m/Y') ?></span>
      </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card souscripteurs">
          <div class="stat-icon text-primary">
            <i class="fas fa-users"></i>
          </div>
          <div class="stat-value"><?= number_format($stats['total_souscripteurs']) ?></div>
          <div class="stat-label">Souscripteurs</div>
          <div class="small text-muted">
            <a href="liste_souscripteurs.php" class="text-primary">
              Voir la liste <i class="fas fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card montant">
          <div class="stat-icon text-success">
            <i class="fas fa-hand-holding-usd"></i>
          </div>
          <div class="stat-value"><?= number_format((float)($stats['total_montant'] ?? 0), 0, ',', '') ?> <small>FCFA</small></div>
          <div class="stat-label">Montant total souscrit</div>
          <div class="small text-muted">
            <a href="statistiques_generales.php" class="text-success">
              Détails <i class="fas fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card versements">
          <div class="stat-icon text-info">
            <i class="fas fa-money-bill-wave"></i>
          </div>
          <div class="stat-value"><?= number_format((float)($stats['total_versements'] ?? 0), 0, ',', '') ?> <small>FCFA</small></div>
          <div class="stat-label">Total des versements</div>
          <div class="small text-muted">
            <a href="versements.php" class="text-info">
              Voir les versements <i class="fas fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card taux">
          <div class="stat-icon text-warning">
            <i class="fas fa-percent"></i>
          </div>
          <div class="stat-value"><?= $stats['taux_versement'] ?>%</div>
          <div class="stat-label">Taux de versement</div>
          <div class="small text-muted">
            <span class="<?= $stats['taux_versement'] > 50 ? 'text-success' : 'text-danger' ?>">
              <i class="fas fa-<?= $stats['taux_versement'] > 50 ? 'arrow-up' : 'arrow-down' ?>"></i>
              vs mois précédent
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Graphiques et données -->
    <div class="row">
      <!-- Tendance mensuelle -->
      <div class="col-xl-8 col-lg-7">
        <div class="chart-container">
          <h5 class="mb-3">Activité des 6 derniers mois</h5>
          <canvas id="trendChart"></canvas>
        </div>
      </div>

      <!-- Répartition par genre -->
      <div class="col-xl-4 col-lg-5">
        <div class="chart-container">
          <h5 class="mb-3">Répartition par genre</h5>
          <canvas id="genderChart"></canvas>
          <div class="mt-3 text-center">
            <span class="badge bg-primary me-2">
              Hommes: <?= $genderData['data']['Homme'] ?> (<?= $genderData['percent']['Homme'] ?>%)
            </span>
            <span class="badge bg-danger me-2">
              Femmes: <?= $genderData['data']['Femme'] ?> (<?= $genderData['percent']['Femme'] ?>%)
            </span>
            <?php if($genderData['data']['Autre'] > 0): ?>
            <span class="badge bg-secondary">
              Autres: <?= $genderData['data']['Autre'] ?> (<?= $genderData['percent']['Autre'] ?>%)
            </span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Derniers souscripteurs -->
    <div class="row mt-4">
      <div class="col-12">
        <div class="chart-container">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Derniers souscripteurs</h5>
            <a href="liste_souscripteurs.php" class="btn btn-sm btn-primary">
              Voir tous <i class="fas fa-arrow-right ms-1"></i>
            </a>
          </div>
          
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Nom</th>
                  <th>Établissement</th>
                  <th>Région</th>
                  <th>Montant</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($lastSubscribers as $sub): ?>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="user-avatar me-3">
                        <?= mb_substr($sub['nom_complet'], 0, 1) ?>
                      </div>
                      <div><?= htmlspecialchars($sub['nom_complet']) ?></div>
                    </div>
                  </td>
                  <td><?= htmlspecialchars($sub['nom_etablissement']) ?></td>
                  <td><?= htmlspecialchars($sub['nom_region'] ?? 'N/A') ?></td>
                  <td><?= number_format((float)($sub['montant_souscrit'] ?? 0), 0, ',', '') ?> FCFA</td>
                  <td><?= htmlspecialchars($sub['date_souscription']) ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php include('includes/php/footer.php');?>
</div>

<script>
// Graphique de tendance
const trendCtx = document.getElementById('trendChart');
new Chart(trendCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($monthlyData['labels']) ?>,
        datasets: [
            {
                label: 'Nombre de souscriptions',
                data: <?= json_encode($monthlyData['souscriptions']) ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.7)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1,
                yAxisID: 'y'
            },
            {
                label: 'Montant (millions FCFA)',
                data: <?= json_encode(array_map(function($v) { return $v / 1000000; }, $monthlyData['montants'])) ?>,
                backgroundColor: 'rgba(28, 200, 138, 0.7)',
                borderColor: 'rgba(28, 200, 138, 1)',
                borderWidth: 1,
                type: 'line',
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Nombre de souscriptions'
                },
                beginAtZero: true
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Montant (millions FCFA)'
                },
                beginAtZero: true,
                grid: {
                    drawOnChartArea: false
                }
            }
        }
    }
});

// Graphique de répartition par genre
const genderCtx = document.getElementById('genderChart');
new Chart(genderCtx, {
    type: 'doughnut',
    data: {
        labels: ['Hommes', 'Femmes'<?= $genderData['data']['Autre'] > 0 ? ", 'Autres'" : "" ?>],
        datasets: [{
            data: [<?= $genderData['data']['Homme'] ?>, <?= $genderData['data']['Femme'] ?><?= $genderData['data']['Autre'] > 0 ? ", ".$genderData['data']['Autre'] : "" ?>],
            backgroundColor: ['#4e73df', '#e74a3b'<?= $genderData['data']['Autre'] > 0 ? ", '#6c757d'" : "" ?>],
            hoverOffset: 20
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
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
    }
});
</script>

<?php include('includes/php/includes-js.php');?>
</body>
</html>