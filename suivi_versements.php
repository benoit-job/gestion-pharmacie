<?php
include("includes/connexion_acces_page.php");
include("includes/connexion_bdd.php");
include("includes/fonctions.php");
include_once("includes/auth_functions.php");   

// Paramètres de période
$debut = isset($_GET['debut']) ? $_GET['debut'] : date('Y-m-01');
$fin = isset($_GET['fin']) ? $_GET['fin'] : date('Y-m-t');

// Récupération des données
$query = "SELECT 
            s.id_souscripteur, 
            UPPER(CONCAT(s.nom, ' ', s.prenom)) AS nom_complet,
            COUNT(v.id) AS nombre_versements,
            SUM(v.montant) AS total_verse,
            MAX(v.date) AS dernier_versement
          FROM souscripteurs s
          LEFT JOIN versements_souscripteurs v ON s.id_souscripteur = v.id_souscripteur
            AND v.date BETWEEN '$debut' AND '$fin 23:59:59'
          GROUP BY s.id_souscripteur
          ORDER BY s.nom, s.prenom";

$souscripteurs = mysqli_query($bdd, $query);

// Statistiques globales
$stats_query = "SELECT 
                 COUNT(DISTINCT id_souscripteur) AS total_souscripteurs,
                 COUNT(id) AS total_versements,
                 SUM(montant) AS montant_total,
                 AVG(montant) AS moyenne_versement
               FROM versements_souscripteurs
               WHERE date BETWEEN '$debut' AND '$fin 23:59:59'";
$stats = mysqli_fetch_assoc(mysqli_query($bdd, $stats_query));
?>

<!DOCTYPE html>
<html lang="fr-FR" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Suivi des Versements</title>
    <?php include('includes/php/includes-css.php');?>
    
    <style>
        .card-stat {
            border-left: 4px solid #2c7be5;
            transition: all 0.3s ease;
        }
        .card-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .table-details {
            font-size: 0.9rem;
        }
        .badge-paiement {
            font-size: 0.8em;
            padding: 4px 8px;
            border-radius: 10px;
        }
        .bg-espèces { background-color: #28a745; }
        .bg-chèque { background-color: #17a2b8; }
        .bg-virement { background-color: #6f42c1; }
        .bg-mobile { background-color: #e83e8c; }
        .cursor-pointer { cursor: pointer; }
        .accordion-toggle:after {
            font-family: 'Font Awesome 5 Free';
            content: "\f078";
            float: right;
            transition: all 0.3s;
        }
        .accordion-toggle.collapsed:after {
            transform: rotate(-90deg);
        }
        .progress {
            height: 8px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
<main class="main" id="top">
    <?php include('includes/php/menu.php');?>
    <?php include('includes/php/header.php');?>

    <div class="content">
        <div class="pb-3">
            <div class="mb-5">
                <h3 class="mb-2">Suivi des Versements</h3>
                <p class="text-body-tertiary">Suivi détaillé des versements par souscripteur</p>
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="get" class="row g-3">
                        <div class="col-md-5">
                            <label for="debut" class="form-label">Date début</label>
                            <input type="date" class="form-control" id="debut" name="debut" value="<?= $debut ?>">
                        </div>
                        <div class="col-md-5">
                            <label for="fin" class="form-label">Date fin</label>
                            <input type="date" class="form-control" id="fin" name="fin" value="<?= $fin ?>">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i>Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card card-stat h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted">Souscripteurs</h6>
                            <h2 class="mb-0"><?= $stats['total_souscripteurs'] ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stat h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted">Versements</h6>
                            <h2 class="mb-0"><?= $stats['total_versements'] ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stat h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted">Total collecté</h6>
                            <h2 class="mb-0"><?= number_format($stats['montant_total'], 0, ',', ' ') ?> FCFA</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stat h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted">Moyenne/versement</h6>
                            <h2 class="mb-0"><?= number_format($stats['moyenne_versement'], 0, ',', ' ') ?> FCFA</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau principal -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 usersTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Souscripteur</th>
                                    <th class="text-center">Versements</th>
                                    <th class="text-end">Total versé</th>
                                    <th class="text-center">Dernier versement</th>
                                    <th class="text-center">Détails</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($souscripteur = mysqli_fetch_assoc($souscripteurs)): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($souscripteur['nom_complet'] ?? '') ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $souscripteur['nombre_versements'] ?: '0' ?>
                                    </td>
                                    <td class="text-end">
                                        <?= number_format($souscripteur['total_verse'] ?: 0, 0, ',', ' ') ?> F
                                    </td>
                                    <td class="text-center">
                                        <?= $souscripteur['dernier_versement'] ? date('d/m/Y', strtotime($souscripteur['dernier_versement'])) : '-' ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary btn-details" 
                                                data-id="<?= $souscripteur['id_souscripteur'] ?>" data-toggle="tooltip" data-placement="top" title="Voir les détails de <?php echo htmlspecialchars($souscripteur['nom_complet']); ?>">
                                            <i class="fas fa-eye"></i> Voir
                                        </button>
                                    </td>
                                </tr>
                                <!-- Ligne des détails (cachée par défaut) -->
                                <tr class="detail-row" id="detail-<?= $souscripteur['id_souscripteur'] ?>" style="display: none;">
                                    <td colspan="5" class="p-0">
                                        <div class="p-3 bg-light">
                                            <h6 class="mb-3">Détails des versements</h6>
                                            <?php
                                            $details_query = "SELECT * FROM versements_souscripteurs 
                                                            WHERE id_souscripteur = ".$souscripteur['id_souscripteur']."
                                                            AND date BETWEEN '$debut' AND '$fin 23:59:59'
                                                            ORDER BY date DESC";
                                            $details = mysqli_query($bdd, $details_query);
                                            
                                            if(mysqli_num_rows($details) > 0): ?>
                                                <table class="table table-details table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class='text-center'>Date</th>
                                                            <th class='text-center'>Montant</th>
                                                            <th class='text-center'>Nature</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while($versement = mysqli_fetch_assoc($details)): ?>
                                                        <tr>
                                                            <td class='text-center'><?= date('d/m/Y', strtotime($versement['date'])) ?></td>
                                                            <td class='text-center'><?= number_format($versement['montant'], 0, ',', ' ') ?> FCFA</td>
                                                            <td class='text-center'>
                                                                <span class="badge badge-paiement bg-<?= strtolower($versement['nature']) ?>">
                                                                    <?= htmlspecialchars($versement['nature']) ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <?php endwhile; ?>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                                <div class="alert alert-info mb-0">Aucun versement pour cette période</div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php include('includes/php/footer.php');?>
    </div>
</main>

<?php include('includes/php/includes-js.php');?>

<script>
$(document).ready(function() {
    // Affichage des détails
    $('.btn-details').click(function() {
        var id = $(this).data('id');
        $('#detail-'+id).toggle();
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });
    
    // Initialisation des tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Initialisation de DataTables (optionnel)
    // $('table').DataTable({
    //     responsive: true,
    //     ordering: false
    // });
});
</script>
</body>
</html>