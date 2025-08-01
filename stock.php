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

    <title>Région Pharmacie</title>

    <?php include('includes/php/includes-css.php');?>

<!-- Styles personnalisés pour améliorer le dropdown -->


  </head>


  <body>

    <main class="main" id="top">
    	
      <?php include('includes/php/menu.php');?>

      <?php include('includes/php/header.php');?>

      <div class="content">

        <div class="pb-3">
            <div class="mb-8">
              <h2 class="mb-2">Région Pharmacie</h2>
              <h5 class="text-body-tertiary fw-semibold">Gérer vos région Pharmacie </h5>
            </div>

            <div class="page-section">
                <div class="card card-fluid">
                    <div class="card-header border-0 p-1 d-flex justify-content-end">
                        <button class="btn btn-phoenix-secondary rounded-pill me-1 mb-1" 
                            type="button" data-bs-toggle="modal" data-bs-target="#regionModal">
                            <span class="fas fa-square-plus fa-lg me-2" data-fa-transform="shrink-3"></span>
                            Créer nouveau
                        </button>
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
         
        <div class="modal fade modalAnimate regionModal" id="regionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <form method="post" action="<?php echo $url; ?>" id="regionForm">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter une région</h5>
                <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span class="fas fa-times fs-9"></span>
                </button>
                </div>
                <div class="modal-body">
                <div class="mb-3">
  <label for="organizerSingle" class="form-label">Organisateur</label>
  <select class="form-select organizerSingle" id="organizerSingle">
    <option value="">Select organizer...</option>
    <option value="California Institute of Technology">California Institute of Technology</option>
    <option value="GSAS Open Labs At Harvard">GSAS Open Labs At Harvard</option>
    <option value="Massachusetts Institute of Technology">Massachusetts Institute of Technology</option>
    <option value="University of Chicago">University of Chicago</option>
  </select>
</div>
                <div class="mb-3">
                    <label for="regionCode" class="form-label">Code région</label>
                    <input type="text" class="form-control" id="regionCode" name="regionCode">
                </div>
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" id="activeRegion" name="activeRegion" checked>
                    <label class="form-check-label" for="activeRegion">Région active</label>
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-phoenix-danger px-4" data-bs-dismiss="modal">
                    <span class="fas fa-times me-2"></span>Annuler
                </button>
                <button type="submit" class="btn btn-phoenix-primary px-4">
                    <span class="fas fa-save me-2"></span>Enregistrer
                </button>
                </div>
            </form>
            </div>
        </div>
        </div>


        <?php include('includes/php/footer.php');?>

      </div>
      
    </main>

    <?php include('includes/php/includes-js.php');?>
<!-- LIENS À METTRE DANS LE <head> -->


  </body>

</html>

