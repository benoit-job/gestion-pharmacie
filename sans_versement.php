<?php
  include("includes/connexion_acces_page.php");
  include("includes/connexion_bdd.php");
  include("includes/fonctions.php");
include_once("includes/auth_functions.php");   
  $url = "souscripteurs_pharma.php";
?>
<?php
   //DELETE FROM

// Gestion de la suppression
if (isset($_POST["supprimerSouscripteur"])) {
    $success = supprimerSouscripteurAvecHistorique($bdd, $_POST["id_souscripteur"], $_SESSION['user']['id']);

    if ($success) {
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Souscripteur supprimé avec succès'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Erreur lors de la suppression du souscripteur'];
    }

    reload_current_page();
}


?>


<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Souscripteurs sans versement</title>

    <?php include('includes/php/includes-css.php');?>

  </head>


  <body>

    <main class="main" id="top">
    	
      <?php include('includes/php/menu.php');?>

      <?php include('includes/php/header.php');?>

      <div class="content">

        <div class="pb-3">
            <div class="mb-8">
              <h3 class="mb-2">Souscripteurs sans versement</h3>
              <h5 class="text-body-tertiary fw-semibold">Visualiser vos Souscripteurs sans versement </h5>
            </div>

            <div class="page-section">
                <div class="card card-fluid">
                    <div class="card-header border-0 p-1 d-flex justify-content-between align-items-center bg-light">
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
                        <?php
                            // Première requête pour déterminer le nombre maximum de versements
                            $query_max_versements = "SELECT s.id_souscripteur, COUNT(v.id) as nb_versements 
                                                    FROM souscripteurs AS s
                                                    LEFT JOIN versements_souscripteurs AS v ON v.id_souscripteur = s.id_souscripteur
                                                    GROUP BY s.id_souscripteur
                                                    ORDER BY nb_versements DESC
                                                    LIMIT 1";
                            $result_max = mysqli_query($bdd, $query_max_versements);
                            $max_versements = 0;
                            if($row_max = mysqli_fetch_array($result_max)) {
                                $max_versements = $row_max['nb_versements'];
                            }
                        ?>

                        <table class="table table-hover m-0 usersTable" style="width:100%" data-title="Souscripteurs à Jour">
                            <thead class="thead-">
                                <tr style="font-size: 0.8rem;">
                                    <th>NBRE SC</th>
                                    <th>N° SOUSCRIPT</th>
                                    <th class='hidden'>NOM</th>
                                    <th class='hidden'>PRENOMS</th>
                                    <th class='hidden'>SEXE</th>
                                    <th class='no_export'>NOM & PRENOMS</th>
                                    <th class='hidden'>NATIONNALITE</th>
                                    <th class='hidden'>TELEPHONE FIXE</th>
                                    <th class='no_export'>FIXE/PORTABLE</th>
                                    <th class='hidden'>TELEPHONE PORTABLE</th>
                                    <th class='hidden'>EMAIL</th>
                                    <th class='hidden'>SECTEUR D 'ACTIVITE</th>
                                    <th>NOM DE L'ETABLISSEMENT</th>
                                    <th>LIEU D'EXERCICE</th>
                                    <th>REGION PHARMACIE</th>
                                    <th>DATE DE SOUSCRIPTION</th>
                                    <th>MONTANT SOUSCRIT</th>
                                    <th>MONTANT SOUSCRIT TYPE 1</th>
                                    <th>MONTANT SOUSCRIT TYPE 2</th>
                                    <th>NOMBRE D 'ACTION</th>
                                    
                                    <?php 
                                    // Génération dynamique des en-têtes de versements
                                    for($i = 1; $i <= $max_versements; $i++) {
                                        echo "<th class='hidden'>VERSEMENT $i</th>";
                                        echo "<th class='hidden'>DATE VERSEMENT $i</th>";
                                        echo "<th class='hidden'>NATURE VERSEMENT $i</th>";
                                    }
                                    ?>
                                    
                                    <th>TOTAL VERSEMENTS</th>
                                    <th>STATUT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Requête principale avec sous-requête pour calculer le total des versements
                                $query = "SELECT s.*,
                                                UPPER(s.nom) AS nom_src,
                                                UPPER(s.prenom) AS prenom_src,
                                                UPPER(CONCAT_WS(' / ', s.telephone_fixe, s.telephone_portable)) AS contacts,
                                                UPPER(CONCAT_WS(' ', s.civilite, s.nom, s.prenom)) AS nom_complet,
                                                UPPER(s.nom_etablissement) AS nom_etablissement,
                                                DATE_FORMAT(s.date_souscription, '%d/%m/%Y') AS date_souscription,
                                                UPPER(l.nom_lieu) AS lieu_exercice,
                                                UPPER(r.nom_region) AS nom_region,
                                                COALESCE(SUM(v.montant), 0) AS total_verse
                                        FROM souscripteurs AS s
                                        LEFT JOIN lieu_exercices AS l ON l.id = s.id_lieu_exercice
                                        LEFT JOIN regions AS r ON r.id = l.id_region
                                        LEFT JOIN versements_souscripteurs AS v ON v.id_souscripteur = s.id_souscripteur
                                        GROUP BY s.id_souscripteur
                                        HAVING total_verse = 0
                                        ORDER BY s.nom_etablissement";
                                
                                $resultat = mysqli_query($bdd, $query) or die("Erreur de requête: " . mysqli_error($bdd));

                                $ligne = 0;
                                while($souscripteur = mysqli_fetch_array($resultat)) {   
                                    $isActive = strtolower($souscripteur["active"]) === 'oui';
                                    $activeClass = $isActive ? 'bg-success' : '';
                                    $checkedAttr = $isActive ? 'checked' : '';
                                    
                                    // Récupérer les détails des versements
                                    $query_versements = "SELECT montant, DATE_FORMAT(date, '%d/%m/%Y') as date_versement, nature
                                                        FROM versements_souscripteurs 
                                                        WHERE id_souscripteur = '" . $souscripteur['id_souscripteur'] . "'
                                                        ORDER BY date ASC";
                                    $result_versements = mysqli_query($bdd, $query_versements);
                                    $versements = [];
                                    $total_versements = 0;
                                    
                                    while($versement = mysqli_fetch_array($result_versements)) {
                                        $versements[] = $versement;
                                        $total_versements += floatval($versement['montant']);
                                    }
                                    
                                    // Déterminer le statut
                                    $montant_souscrit = floatval($souscripteur["montant_souscrit"]);
                                    $statut = "AUCUN VERSEMENT";
                                    $statut_class = "badge bg-danger";
                                    
                                    echo "<tr>
                                            <td>".++$ligne."</td>
                                            <td class='text-truncate' style='max-width: 100px;' title='".htmlspecialchars($souscripteur["n_souscription"] ?? '')."'>
                                                ".htmlspecialchars($souscripteur["n_souscription"] ?? '')."
                                            </td>
                                            <td class='hidden text-truncate' style='max-width: 120px;' title='".htmlspecialchars($souscripteur["nom_src"] ?? '')."'>
                                                ".htmlspecialchars($souscripteur["nom_src"] ?? '')."
                                            </td>
                                            <td class='hidden text-truncate' style='max-width: 120px;' title='".htmlspecialchars($souscripteur["prenom_src"] ?? '')."'>
                                                ".htmlspecialchars($souscripteur["prenom_src"] ?? '')."
                                            </td>
                                            <td class='hidden text-truncate' style='max-width: 80px;' title='".ucfirst(htmlspecialchars($souscripteur["civilite"] ?? ''))."'>
                                                ".ucfirst(htmlspecialchars($souscripteur["civilite"] ?? ''))."
                                            </td>
                                            <td class='no_export text-truncate' style='max-width: 150px;' title='".htmlspecialchars($souscripteur["nom_complet"] ?? '')."'>
                                                ".htmlspecialchars($souscripteur["nom_complet"] ?? '')."
                                            </td>
                                            <td class='hidden text-truncate' style='max-width: 100px;' title='".htmlspecialchars($souscripteur["nationalite"] ?? '')."'>
                                                ".htmlspecialchars($souscripteur["nationalite"] ?? '')."
                                            </td>
                                            <td class='hidden text-truncate' style='max-width: 120px;' title='+225".htmlspecialchars($souscripteur["telephone_fixe"] ?? '')."'>
                                                +225".htmlspecialchars($souscripteur["telephone_fixe"] ?? '')."
                                            </td>
                                            <td class='no_export text-truncate' style='max-width: 150px;' title='".htmlspecialchars($souscripteur["contacts"] ?? '')."'>
                                                ".htmlspecialchars($souscripteur["contacts"] ?? '')."
                                            </td>
                                            <td class='hidden text-truncate' style='max-width: 120px;' title='+225".htmlspecialchars($souscripteur["telephone_portable"] ?? '')."'>
                                                +225".htmlspecialchars($souscripteur["telephone_portable"] ?? '')."
                                            </td>
                                            <td class='hidden text-truncate' style='max-width: 180px;' title='".htmlspecialchars($souscripteur["email"] ?? '')."'>
                                                ".htmlspecialchars($souscripteur["email"] ?? '')."
                                            </td>
                                            <td class='hidden text-truncate' style='max-width: 150px;' title='".htmlspecialchars($souscripteur["secteur_activite"] ?? '')."'>
                                                ".htmlspecialchars($souscripteur["secteur_activite"] ?? '')."
                                            </td>
                                            <td class='text-truncate' style='max-width: 180px;' title='".htmlspecialchars($souscripteur["nom_etablissement"] ?? '')."'>
                                                ".htmlspecialchars($souscripteur["nom_etablissement"] ?? '')."
                                            </td>
                                            <td class='text-truncate' style='max-width: 150px;' title='".htmlspecialchars($souscripteur["lieu_exercice"] ?? '')."'>
                                                ".htmlspecialchars($souscripteur["lieu_exercice"] ?? '')."
                                            </td>
                                            <td class='text-truncate' style='max-width: 150px;' title='".htmlspecialchars($souscripteur["nom_region"] ?? '')."'>
                                                ".htmlspecialchars($souscripteur["nom_region"] ?? '')."
                                            </td>
                                            <td class='text-truncate' style='max-width: 120px;' title='".htmlspecialchars($souscripteur["date_souscription"] ?? '')."'>
                                                ".htmlspecialchars($souscripteur["date_souscription"] ?? '')."
                                            </td>
                                            <td class='text-truncate' style='max-width: 120px;' title='".number_format($montant_souscrit, 0, ',', ' ')." FCFA'>
                                                ".number_format($montant_souscrit, 0, ',', ' ')." FCFA
                                            </td>
                                            <td class='text-truncate' style='max-width: 120px;' title='".htmlspecialchars($souscripteur["montant_souscrit_type1"] ?? '')."'>
                                                ".htmlspecialchars($souscripteur["montant_souscrit_type1"] ?? '')."
                                            </td>
                                            <td class='text-truncate' style='max-width: 120px;' title='".htmlspecialchars($souscripteur["montant_souscrit_type2"] ?? '')."'>
                                                ".htmlspecialchars($souscripteur["montant_souscrit_type2"] ?? '')."
                                            </td>
                                            <td class='text-truncate' style='max-width: 100px;' title='".htmlspecialchars($souscripteur["nombre_actions"] ?? '0')."'>
                                                ".htmlspecialchars($souscripteur["nombre_actions"] ?? '0')."
                                            </td>";
                                    
                                    // Affichage des versements
                                    for($i = 1; $i <= $max_versements; $i++) {
                                        $versement_index = $i - 1;
                                        if(isset($versements[$versement_index])) {
                                            echo "<td class='hidden text-truncate' style='max-width: 120px;' title='".number_format($versements[$versement_index]['montant'], 0, ',', ' ')." FCFA'>
                                                    ".number_format($versements[$versement_index]['montant'], 0, ',', ' ')." FCFA
                                                </td>";
                                            echo "<td class='hidden text-truncate' style='max-width: 100px;' title='".htmlspecialchars($versements[$versement_index]['date_versement'])."'>
                                                    ".htmlspecialchars($versements[$versement_index]['date_versement'])."
                                                </td>";
                                            echo "<td class='hidden text-truncate' style='max-width: 150px;' title='".htmlspecialchars($versements[$versement_index]['nature'] ?? '')."'>
                                                    ".htmlspecialchars($versements[$versement_index]['nature'] ?? '')."
                                                </td>";
                                        } else {
                                            echo "<td class='hidden'>-</td>";
                                            echo "<td class='hidden'>-</td>";
                                            echo "<td class='hidden'>-</td>";
                                        }
                                    }
                                    
                                    // Affichage du total des versements
                                    echo "<td class='text-truncate' style='max-width: 120px;' title='".number_format($total_versements, 0, ',', ' ')." FCFA'>
                                            <strong>".number_format($total_versements, 0, ',', ' ')." FCFA</strong>
                                        </td>";
                                    
                                    // Affichage du statut
                                    echo "<td class='text-truncate' style='max-width: 150px;' title='$statut'>
                                            <span class='$statut_class'>$statut</span>
                                        </td>";
                                }
                                ?>                          
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

  </body>

</html>

