<?php
  include("includes/connexion_acces_page.php");
  include("includes/connexion_bdd.php");
  include("includes/fonctions.php");
  $url = "regions_pharma.php";
?>


<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Versement souscripteur</title>

    <?php include('includes/php/includes-css.php');?>

    <style>
        .hide-button {
            display: none;
        }
        .show-button {
            display: block;
        }
        .animate__fadeIn {
            animation-duration: 0.5s;
        }
    </style>


  </head>


  <body>

    <main class="main" id="top">
    	
      <?php include('includes/php/menu.php');?>

      <?php include('includes/php/header.php');?>

      <div class="content">

        <div class="pb-3">
            <div class="mb-8">
              <h2 class="mb-2">Versement souscripteur</h2>
              <h5 class="text-body-tertiary fw-semibold">Gérer vos versement souscripteur </h5>
            </div>

            <div class="page-section">
                <div class="card card-fluid">
                    <div class="card-header border-0 p-3 d-flex justify-content-between align-items-center bg-light">
                        <form action="" method="get" class="d-flex align-items-center">
                            <div class="me-3">
                            </div>
                            <div class="position-relative" style="width: 250px;">
                                <label for="id_souscripteur" class="form-label mb-0 fw-bold text-primary">Choisir un souscripteur :</label>
                                <select class="form-select organizerSingle shadow-sm" id="id_souscripteur" name="id_souscripteur" style="border-radius: 20px; border: 1px solid #ced4da; padding: 8px 15px;">
                                    <option disabled selected>Choisissez un souscripteur...</option>
                                    <?php
                                    $query = "SELECT id_souscripteur, UPPER(CONCAT(nom, ' ', prenom)) AS nom_souscripteur FROM souscripteurs WHERE active = 'oui' ORDER BY souscripteurs.nom, souscripteurs.prenom ASC";
                                    $resultat = mysqli_query($bdd, $query) or die("Erreur SQL");
                                    while ($souscripteur = mysqli_fetch_assoc($resultat)) {
                                        $selected = (isset($_GET['id_souscripteur']) && $_GET['id_souscripteur'] == $souscripteur['id_souscripteur']) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($souscripteur['id_souscripteur']) . "' $selected>" . htmlspecialchars($souscripteur['nom_souscripteur']) . "</option>";
                                    }
                                    ?>
                                </select>
                                <i class="fas fa-chevron-down position-absolute end-0 top-50 translate-middle-y me-3 text-secondary"></i>
                            </div>
                        </form>
                        
                        <!-- Bouton de versement (caché par défaut) -->
                        <div id="versementButtonContainer" class="<?php echo (isset($_GET['id_souscripteur'])) ? 'show-button' : 'hide-button'; ?>">
                            <?php if (isset($_GET['id_souscripteur'])): ?>
                                <a href="add_souscrit_versement.php?id_souscripteur=<?php echo htmlspecialchars(crypt_decrypt_chaine($_GET['id_souscripteur'], 'C')); ?>" class="btn btn-phoenix-success rounded-pill animate__animated animate__fadeIn">
                                    <span class="fas fa-money-bill-wave me-2 fa-lg" data-fa-transform="shrink-3"></span>
                                    Versement
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="usersTable" class="table table-hover m-0 usersTable" style="width:100%">
                            <thead class="thead-">
                            <tr style="font-size: 0.8rem;">
                                <th>N</th>
                                <th>Pseudo</th>
                                <th>Contact</th>
                                <th>Date enreg</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal Structure -->


        <?php include('includes/php/footer.php');?>

      </div>
      
    </main>

    <?php include('includes/php/includes-js.php');?>
<!-- LIENS À METTRE DANS LE <head> -->


  </body>

</html>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.getElementById('id_souscripteur');
    const formElement = selectElement.closest('form');
    
    selectElement.addEventListener('change', function() {
        formElement.submit();
    });
});
</script>

