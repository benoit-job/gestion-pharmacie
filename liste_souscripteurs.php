<?php
  include("includes/connexion_acces_page.php");
  include("includes/connexion_bdd.php");
  include("includes/fonctions.php");
  $url = "regions_pharma.php";
?>
<?php
   //DELETE FROM
if (isset($_POST["supprimerRegion"])) {
    $id_region = crypt_decrypt_chaine($_POST["id_region"], 'D');

    // Récupérer les données actuelles de la région
    $selectQuery = "SELECT * FROM regions WHERE id = $id_region";
    $result = mysqli_query($bdd, $selectQuery) or die("Erreur lors de la récupération des données");

    if ($region = mysqli_fetch_assoc($result)) {
        // Insertion dans historique_region
        $nom_region = mysqli_real_escape_string($bdd, $region['nom_region']);
        $code_region = mysqli_real_escape_string($bdd, $region['code_region']);

        $insertQuery = "INSERT INTO historique_region (id_user, id_region, nom_region, code_region, action, date_action)
                        VALUES (".$_SESSION['user']['id'].", $id_region, '$nom_region', '$code_region', 'supprimer', NOW())";
        mysqli_query($bdd, $insertQuery) or die("Erreur lors de l'insertion dans historique_region");
    }

    $deleteQuery = "DELETE FROM regions WHERE id = ".intval($id_region);
    $result = mysqli_query($bdd, $deleteQuery);
    
    if($result) {
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Région supprimée avec succès'
        ];
    } else {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Erreur lors de la suppression de la région: '
        ];
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
              <h2 class="mb-2">Liste des souscripteurs</h2>
              <h5 class="text-body-tertiary fw-semibold">Gérer vos liste des souscripteurs </h5>
            </div>

            <div class="page-section">
                <div class="card card-fluid">
                    <div class="card-header border-0 p-1 d-flex justify-content-end">
                        <a href="add_souscripteur.php" class="btn btn-phoenix-secondary rounded-pill me-1 mb-1" 
                            >
                            <span class="fas fa-square-plus fa-lg me-2" data-fa-transform="shrink-3"></span>
                            Créer nouveau
                        </a>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-hover m-0 usersTable" style="width:100%">
                        <thead class="thead-">
                        <tr style="font-size: 0.8rem;">
                            <th>N</th>
                            <th>NOM REGION</th>
                            <th>CODE REGION</th>
                            <th>ACTIVE</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "SELECT id,
                                                UPPER(nom_region) AS nom_region,
                                                code_region,
                                                active
                                        FROM regions ORDER BY nom_region";
                                $resultat = mysqli_query($bdd, $query) or die("Erreur de requête");

                                $ligne = 0;
                                while($region = mysqli_fetch_array($resultat)) {                           
                                    $isActive = strtolower($region["active"]) === 'oui';
                                    $activeClass = $isActive ? 'bg-success' : '';
                                    $checkedAttr = $isActive ? 'checked' : '';
                                    
                                    echo "<tr>
                                            <td>".++$ligne."</td>
                                            <td>".htmlspecialchars($region["nom_region"])."</td>
                                            <td>".htmlspecialchars($region["code_region"])."</td>
                                            <td class='text-center'>
                                                <div class='form-check form-switch  mb-4'> 
                                                    <input class='form-check-input $activeClass' type='checkbox' $checkedAttr disabled>
                                                </div>
                                            </td>
                                            <td class='text-end'>
                                                <form method='post' action='".$url."' style='display:inline;'>
                                                    <input type='hidden' name='id_region' value='".crypt_decrypt_chaine($region['id'], 'C')."'>
                                                    
                                                    <button type='button' class='btn btn-light btn-sm modififierInfos' 
                                                    id_region='".crypt_decrypt_chaine($region['id'], 'C')."'
                                                        nom_region='".htmlspecialchars($region['nom_region'])."'
                                                        code_region='".htmlspecialchars($region['code_region'])."'
                                                        active='".htmlspecialchars($region['active'])."'>
                                                        <i class='fas fa-edit me-1'></i>
                                                    </button>
                                                    
                                                    <button type='button' class='btn btn-light btn-sm btn-supprimer' 
                                                        data-id='".crypt_decrypt_chaine($region['id'], 'C')."'
                                                        data-type='region'>
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

