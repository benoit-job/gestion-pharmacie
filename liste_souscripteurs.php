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

    <title>Liste des souscripteurs</title>

    <?php include('includes/php/includes-css.php');?>

  </head>


  <body>

    <main class="main" id="top">
    	
      <?php include('includes/php/menu.php');?>

      <?php include('includes/php/header.php');?>

      <div class="content">

        <div class="pb-3">
            <div class="mb-8">
              <h3 class="mb-2">Liste des souscripteurs</h3>
              <h5 class="text-body-tertiary fw-semibold">Gérer vos liste des souscripteurs </h5>
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
                        <a href="add_souscripteur.php" class="btn btn-phoenix-secondary rounded-pill">
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
                        <table class="table table-hover m-0 usersTable" style="width:100%" data-title="Souscripteurs">
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
                                    <th class='hidden'>REGION PHARMACIE</th>
                                    <th>DATE DE SOUCRIPTION</th>
                                    <th>MONTANT SOUSCRIT</th>
                                    <th class='hidden'>MONTANT SOUSCRIT TYPE 1</th>
                                    <th class='hidden'>MONTANT SOUSCRIT TYPE 2</th>
                                    <th class='hidden'>NOMBRE D 'ACTION</th>
                                    
                                    <?php 
                                    // Génération dynamique des en-têtes de versements
                                    for($i = 1; $i <= $max_versements; $i++) {
                                        echo "<th class='hidden'>VERSEMENT $i</th>";
                                        echo "<th class='hidden'>DATE VERSEMENT $i</th>";
                                        echo "<th class='hidden'>NATURE VERSEMENT $i</th>";
                                    }
                                    ?>
                                    
                                    <th>TOTAL VERSEMENTS</th>
                                    <th class='no_export'>ACTIVE</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $query = "SELECT s.*,
                                                UPPER(s.nom) AS nom_src,
                                                UPPER(s.prenom) AS prenom_src,
                                                UPPER(CONCAT_WS(' ', s.telephone_fixe, s.telephone_portable)) AS contacts,
                                                UPPER(CONCAT_WS(' ', s.civilite, s.nom, s.prenom)) AS nom_complet,
                                                UPPER(s.nom_etablissement) AS nom_etablissement,
                                                DATE_FORMAT(s.date_souscription, '%d/%m/%Y') AS date_souscription,
                                                UPPER(l.nom_lieu) AS lieu_exercice,
                                                UPPER(r.nom_region) AS nom_region,
                                                s.active
                                        FROM souscripteurs AS s
                                        LEFT JOIN lieu_exercices AS l ON l.id = s.id_lieu_exercice
                                        LEFT JOIN regions AS r ON r.id = l.id_region
                                        ORDER BY s.nom_etablissement";
                                $resultat = mysqli_query($bdd, $query) or die("Erreur de requête");

                                $ligne = 0;
                                while($souscripteur = mysqli_fetch_array($resultat)) {                           
                                    $isActive = strtolower($souscripteur["active"]) === 'oui';
                                    $activeClass = $isActive ? 'bg-success' : '';
                                    $checkedAttr = $isActive ? 'checked' : '';
                                    
                                    // Récupérer les versements pour ce souscripteur
                                    $query_versements = "SELECT montant, DATE_FORMAT(date, '%d/%m/%Y') as date_versement, nature
                                                        FROM versements_souscripteurs 
                                                        WHERE id_souscripteur = '" . $souscripteur['id_souscripteur'] . "'
                                                        ORDER BY date_versement ASC";
                                    $result_versements = mysqli_query($bdd, $query_versements);
                                    $versements = [];
                                    $total_versements = 0;
                                    
                                    while($versement = mysqli_fetch_array($result_versements)) {
                                        $versements[] = $versement;
                                        $total_versements += floatval($versement['montant']);
                                    }
                                    
                                    echo "<tr>
                                            <td>".++$ligne."</td>
                                            <td>".htmlspecialchars($souscripteur["n_souscription"] ?? '')."</td>
                                            <td class='hidden'>".htmlspecialchars($souscripteur["nom_src"] ?? '')."</td>
                                            <td class='hidden'>".htmlspecialchars($souscripteur["prenom_src"] ?? '')."</td>
                                            <td class='hidden'>".ucfirst(htmlspecialchars($souscripteur["civilite"] ?? ''))."</td>
                                            <td class='no_export'>".htmlspecialchars($souscripteur["nom_complet"] ?? '')."</td>
                                            <td class='hidden'>".htmlspecialchars($souscripteur["nationalite"] ?? '')."</td>
                                            <td class='hidden'> +225".htmlspecialchars($souscripteur["telephone_fixe"] ?? '')."</td>
                                            <td class='no_export'>".htmlspecialchars($souscripteur["contacts"] ?? '')."</td>
                                            <td class='hidden'> +225".htmlspecialchars($souscripteur["telephone_portable"] ?? '')."</td>
                                            <td class='hidden'>".htmlspecialchars($souscripteur["email"] ?? '')."</td>
                                            <td class='hidden'>".htmlspecialchars($souscripteur["secteur_activite"] ?? '')."</td>
                                            <td>".htmlspecialchars($souscripteur["nom_etablissement"] ?? '')."</td>
                                            <td>".htmlspecialchars($souscripteur["lieu_exercice"] ?? '')."</td>
                                            <td class='hidden'>".htmlspecialchars($souscripteur["nom_region"] ?? '')."</td>
                                            <td>".htmlspecialchars($souscripteur["date_souscription"] ?? '')."</td>
                                            <td>".number_format($souscripteur["montant_souscrit"] ?? 0, 0, ',', ' ')." FCFA</td>
                                            <td class='hidden'>".htmlspecialchars($souscripteur["montant_souscrit_type1"] ?? '')."</td>
                                            <td class='hidden'>".htmlspecialchars($souscripteur["montant_souscrit_type2"] ?? '')."</td>
                                            <td class='hidden'>".htmlspecialchars($souscripteur["nombre_actions"] ?? '0')."</td>";
                                    
                                    // Affichage des versements avec incrémentation
                                    for($i = 1; $i <= $max_versements; $i++) {
                                        $versement_index = $i - 1;
                                        if(isset($versements[$versement_index])) {
                                            echo "<td class='hidden'>".number_format($versements[$versement_index]['montant'], 0, ',', ' ')." FCFA</td>";
                                            echo "<td class='hidden'>".htmlspecialchars($versements[$versement_index]['date_versement'])."</td>";
                                            echo "<td class='hidden'>".htmlspecialchars($versements[$versement_index]['nature_versement'] ?? '')."</td>";
                                        } else {
                                            echo "<td class='hidden'>-</td>";
                                            echo "<td class='hidden'>-</td>";
                                            echo "<td class='hidden'>-</td>";
                                        }
                                    }
                                    
                                    // Affichage du total des versements
                                    echo "<td><strong>".number_format($total_versements, 0, ',', ' ')." FCFA</strong></td>";
                                    
                                    echo "<td class='text-center no_export'>
                                            <div class='form-check form-switch mb-4'> 
                                                <input class='form-check-input $activeClass' type='checkbox' $checkedAttr disabled>
                                            </div>
                                        </td>
                                        <td class='text-end no_export'>
                                            <form method='post' action='".$url."' style='display:inline;'>
                                                <input type='hidden' name='id_souscripteur' value='".crypt_decrypt_chaine($souscripteur['id_souscripteur'], 'C')."'>
                                                
                                                <a href='update_souscripteurs.php?id_souscripteur=" . crypt_decrypt_chaine($souscripteur['id_souscripteur'], 'C') . "' class='btn btn-light btn-sm' data-toggle='tooltip' data-placement='top' title='Modifier le souscripteur " . htmlspecialchars($souscripteur['nom_complet'] ?? '') . "'>                                                            
                                                <i class='fas fa-edit me-1'></i>
                                                </a>
                                                
                                                <button type='button' class='btn btn-light btn-sm btn-supprimer' 
                                                    data-id='".crypt_decrypt_chaine($souscripteur['id_souscripteur'], 'C')."'
                                                    data-type='souscripteur'>
                                                    <i class='fas fa-trash-alt me-1'></i>
                                                </button>
                                            </form>
                                        </td>
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

      </div>
      
    </main>

    <?php include('includes/php/includes-js.php');?>

  </body>

</html>

