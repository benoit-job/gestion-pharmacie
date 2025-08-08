<?php
include("includes/connexion_acces_page.php");
include("includes/connexion_bdd.php");
include("includes/fonctions.php");

// Récupération de tous les souscripteurs avec leurs versements
$query = "SELECT s.id_souscripteur, UPPER(CONCAT(s.nom, ' ', s.prenom)) AS nom_complet,
           v.montant, v.date, v.nature
          FROM souscripteurs s 
          LEFT JOIN versements_souscripteurs v ON s.id_souscripteur = v.id_souscripteur
          WHERE s.active = 'oui'
          ORDER BY s.nom, s.prenom, v.date DESC";
$result = mysqli_query($bdd, $query);

// Organisation des données
$souscripteurs = [];
while($row = mysqli_fetch_assoc($result)) {
    $id = $row['id_souscripteur'];
    if(!isset($souscripteurs[$id])) {
        $souscripteurs[$id] = [
            'nom_complet' => $row['nom_complet'],
            'total' => 0,
            'versements' => [],
            'par_mois' => []
        ];
    }
    
    if($row['montant']) {
        $souscripteurs[$id]['total'] += $row['montant'];
        $mois = date('m/Y', strtotime($row['date']));
        
        if(!isset($souscripteurs[$id]['par_mois'][$mois])) {
            $souscripteurs[$id]['par_mois'][$mois] = [
                'count' => 0,
                'montant' => 0
            ];
        }
        
        $souscripteurs[$id]['par_mois'][$mois]['count']++;
        $souscripteurs[$id]['par_mois'][$mois]['montant'] += $row['montant'];
        $souscripteurs[$id]['versements'][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="fr-FR" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historique des Versements</title>
    <?php include('includes/php/includes-css.php');?>
    
    <style>
        .table-responsive { overflow-x: auto; }
        .table-hover tbody tr:hover { background-color: rgba(44, 123, 229, 0.05); }
        .total-cell { font-weight: bold; background-color: #f8f9fa; }
        .month-cell { min-width: 100px; text-align: center; }
        .souscripteur-cell { position: sticky; left: 0; background: white; z-index: 1; }
        .badge-count { background-color: #2c7be5; color: white; border-radius: 10px; padding: 2px 8px; }
        .no-versements { color: #6c757d; font-style: italic; }
        
    </style>

</head>

<body>
<main class="main" id="top">
    <?php include('includes/php/menu.php');?>
    <?php include('includes/php/header.php');?>

    <div class="content">
        <div class="pb-3">
            <div class="mb-5">
                <h3 class="mb-2">Historique complet des Versements</h3>
                <p class="text-body-tertiary">Liste de tous les souscripteurs avec leurs versements groupés par mois</p>
            </div>

            <div class="page-section">
                
                <div class="card card-fluid">
                    <div class="card-header border-0 p-3 d-flex justify-content-between align-items-center bg-light">
                             <!-- Boutons icônes à gauche -->
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
                        
                        <!-- Bouton "Créer nouveau" à droite -->
                        <a href="add_souscripteur.php" class="btn btn-phoenix-secondary rounded-pill" style="visibility: hidden;">
                            <span class="fas fa-square-plus fa-lg me-2" data-fa-transform="shrink-3"></span>
                            Créer nouveau
                        </a>
                    </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover usersTable"  data-title="Historique des Versements" id="versementTable">
                        <thead class="table-light">
                            <tr>
                                <th class="souscripteur-cell">SOUSCRIPTEUR</th>
                                <?php
                                // Récupérer tous les mois uniques
                                $all_months = [];
                                foreach($souscripteurs as $s) {
                                    $all_months = array_merge($all_months, array_keys($s['par_mois']));
                                }
                                $all_months = array_unique($all_months);
                                rsort($all_months); // Du plus récent au plus ancien
                                
                                foreach($all_months as $month): ?>
                                    <th class="month-cell"><?php echo $month; ?></th>
                                <?php endforeach; ?>
                                <th class="total-cell">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($souscripteurs as $id => $souscripteur): ?>
                                <tr>
                                    <td class="souscripteur-cell">
                                        <a href="add_souscrit_versement.php?id_souscripteur=<?php echo crypt_decrypt_chaine((string)$id, 'C'); ?>" style="color: #2c7be5; font-weight: bold; text-decoration: none;" data-toggle="tooltip" data-placement="top" title="Ajouter un versement pour <?php echo htmlspecialchars($souscripteur['nom_complet']); ?>">
                                            <?php echo htmlspecialchars($souscripteur['nom_complet']); ?>
                                        </a>
                                    </td>
                                    
                                    <?php foreach($all_months as $month): ?>
                                        <td class="month-cell">
                                            <?php if(isset($souscripteur['par_mois'][$month])): ?>
                                                <span class="badge-count"><?php echo $souscripteur['par_mois'][$month]['count']; ?></span>
                                                <div><?php echo number_format($souscripteur['par_mois'][$month]['montant'], 0, ',', ' '); ?> FCFA</div>
                                            <?php else: ?>
                                                <span class="no-versements">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                    
                                    <td class="total-cell">
                                        <?php echo number_format($souscripteur['total'], 0, ',', ' '); ?> FCFA
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php include('includes/php/footer.php');?>
    </div>
</main>

<?php include('includes/php/includes-js.php');?>

<script>
$(document).ready(function() {
    // Tooltip pour afficher plus d'infos
    $('[data-toggle="tooltip"]').tooltip();
    
    // Tri des colonnes (si vous ajoutez DataTables)
    // $('#versementTable').DataTable();
});
</script>
</body>
</html>