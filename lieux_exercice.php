<?php
  include("includes/connexion_acces_page.php");
  include("includes/connexion_bdd.php");
  include("includes/fonctions.php");
include_once("includes/auth_functions.php");   
  $url = "lieux_exercice.php";
?>
<?php
if(isset($_POST['modifierLieu']))  
    {
        $id_lieu = strip_tags(htmlspecialchars(trim( crypt_decrypt_chaine($_POST['id_lieu'], 'D') )));
        $nom_lieu = strip_tags(htmlspecialchars(trim($_POST["nomLieu"])));
        $id_region = strip_tags(htmlspecialchars(trim($_POST["id_region"])));
        $lieulong = strip_tags(htmlspecialchars(trim($_POST["lieulong"])));
        $lieulat = strip_tags(htmlspecialchars(trim($_POST["lieulat"])));
        $active = isset($_POST['active']) ? 'oui' : 'non';


        $query = "UPDATE lieu_exercices  
                  SET nom_lieu = \"$nom_lieu\", 
                      id_region = \"$id_region\", 
                      lieulong = \"$lieulong\",
                      lieulat = \"$lieulat\",
                      active = \"$active\",
                      date_update  = '".date('Y-m-d H:i:s')."'
                  WHERE id =".$id_lieu;
        $result = mysqli_query($bdd, $query);
    
    if($result) {
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Lieu modifié avec succès'
        ];
    } else {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Erreur lors de la modification'
        ];
    }
    
    reload_current_page();
}

   //DELETE FROM
if (isset($_POST["supprimerLieu"])) {
    $id_lieu = crypt_decrypt_chaine($_POST["id_lieu"], 'D');

    // Récupérer les données actuelles de la lieu
    $selectQuery = "SELECT * FROM lieu_exercices WHERE id = $id_lieu";$selectQuery = "
    SELECT le.*, r.nom_region 
    FROM lieu_exercices AS le
    LEFT JOIN regions AS r 
        ON r.id = le.id_region
    WHERE le.id = $id_lieu";
    $result = mysqli_query($bdd, $selectQuery) or die("Erreur lors de la récupération des données");

    if ($lieu = mysqli_fetch_assoc($result)) {
        // Insertion dans historique_lieu
        $nom_lieu = mysqli_real_escape_string($bdd, $lieu['nom_lieu']);
        $nom_region = mysqli_real_escape_string($bdd, $lieu['nom_region']);
        $lieulong = mysqli_real_escape_string($bdd, $lieu['lieulong']);
        $lieulat = mysqli_real_escape_string($bdd, $lieu['lieulat']);

        $insertQuery = "INSERT INTO historique_lieu (id_user, id_lieu, nom_lieu, nom_region, lieulong, lieulat, action, date_action)
                        VALUES (".$_SESSION['user']['id'].", $id_lieu, '$nom_lieu', '$nom_region', '$lieulong', '$lieulat', 'supprimer', NOW())";
        mysqli_query($bdd, $insertQuery) or die("Erreur lors de l'insertion dans historique_lieu");
    }

    $deleteQuery = "DELETE FROM lieu_exercices WHERE id = ".intval($id_lieu);
    $result = mysqli_query($bdd, $deleteQuery);
    
    if($result) {
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Lieu supprimée avec succès'
        ];
    } else {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Erreur lors de la suppression de la lieu: '
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

    <title>Lieu Pharmacie</title>

    <?php include('includes/php/includes-css.php');?>
    

  </head>


  <body>

    <main class="main" id="top">
    	
      <?php include('includes/php/menu.php');?>

      <?php include('includes/php/header.php');?>

      <div class="content">

        <div class="pb-3">
            <div class="mb-8">
              <h3 class="mb-2">Lieu Pharmacie</h3>
              <h5 class="text-body-tertiary fw-semibold">Gérer vos lieu Pharmacie </h5>
            </div>

            <div class="page-section">
                <div class="card card-fluid">
                    <div class="card-header border-0 p-1 d-flex justify-content-end">
                        <button class="btn btn-phoenix-secondary rounded-pill me-1 mb-1" 
                            type="button" data-bs-toggle="modal" data-bs-target="#lieuModal">
                            <span class="fas fa-square-plus fa-lg me-2" data-fa-transform="shrink-3"></span>
                            Créer nouveau
                        </button>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-hover m-0 usersTable" style="width:100%">
                        <thead class="thead-">
                        <tr style="font-size: 0.8rem;">
                            <th>N</th>
                            <th>NOM DU LIEU</th>
                            <th>REGION LIEU</th>
                            <th>GEOLOCALISATION</th>
                            <th>ACTIVE</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "SELECT 
                                            le.id,
                                            le.id_region,
                                            le.lieulong,
                                            le.lieulat,
                                            UPPER(le.nom_lieu) AS nom_lieu,
                                            r.nom_region AS region,
                                            CONCAT(le.lieulong, ' / ', le.lieulat) AS geolocalisation,
                                            le.active
                                        FROM lieu_exercices AS le
                                        LEFT JOIN regions AS r ON r.id = le.id_region
                                        ORDER BY le.nom_lieu";
                                $resultat = mysqli_query($bdd, $query) or die("Erreur de requête");

                                $ligne = 0;
                                while($lieu = mysqli_fetch_array($resultat)) {                           
                                    $isActive = strtolower($lieu["active"]) === 'oui';
                                    $activeClass = $isActive ? 'bg-success' : '';
                                    $checkedAttr = $isActive ? 'checked' : '';
                                    $lien = "https://www.google.com/maps?q=".htmlspecialchars($lieu['lieulat'] ?? '').",".htmlspecialchars($lieu['lieulong'] ?? '');

                                    
                                    echo "<tr>
                                            <td>".++$ligne."</td>
                                            <td>".htmlspecialchars($lieu["nom_lieu"] ?? '')."</td>
                                            <td>".htmlspecialchars($lieu["region"] ?? '')."</td>
                                            <td><i class='fas fa-map-marker-alt text-danger me-1'></i>".htmlspecialchars($lieu["geolocalisation"] ?? '')."  <a href=".$lien." target='_BANK' rel='noopener noreferrer'  class='btn btn-warning btn-sm py-0 px-3'><i class='fas fa-map-pin'></i></a> </td>
                                            <td class='text-center'>
                                                <div class='form-check form-switch  mb-4'> 
                                                    <input class='form-check-input $activeClass' type='checkbox' $checkedAttr disabled>
                                                </div>
                                            </td>
                                            <td class='text-end'>
                                                <form method='post' action='".$url."' style='display:inline;'>
                                                    <input type='hidden' name='id_lieu' value='".crypt_decrypt_chaine($lieu['id'], 'C')."'>
                                                    
                                                    <button type='button' class='btn btn-light btn-sm modififierInfos' 
                                                        id_lieu='".crypt_decrypt_chaine($lieu['id'], 'C')."'
                                                        nom_lieu='".htmlspecialchars($lieu['nom_lieu'] ?? '')."'
                                                        id_region='".htmlspecialchars($lieu['id_region'] ?? '')."'
                                                        lieulong='".htmlspecialchars($lieu['lieulong'] ?? '')."'
                                                        lieulat='".htmlspecialchars($lieu['lieulat'] ?? '')."'
                                                        active='".htmlspecialchars($lieu['active'] ?? '')."'>
                                                        <i class='fas fa-edit me-1'></i>
                                                    </button>
                                                    
                                                    <button type='button' class='btn btn-light btn-sm btn-supprimer' 
                                                        data-id='".crypt_decrypt_chaine($lieu['id'], 'C')."'
                                                        data-type='lieu'>
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

        <!-- Modal Structure -->
         
        <div class="modal fade modalAnimate lieuModal" id="lieuModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="<?php echo $url; ?>" id="lieuForm">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ajouter un lieu</h5>
                        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                            <span class="fas fa-times fs-9"></span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="lieuName" class="form-label">Nom du lieu</label>
                                <input type="text" class="form-control" id="lieuName" name="nomLieu" required>
                            </div>
                            <div class="mb-3">
                                <label for="organizerSingle"  class="form-label">Région</label>
                                <select class="form-select organizerSingle" id="organizerSingle" id="id_region" name="id_region">
                                    <option disabled selected>Choisissez ...</option> 
                                        <?php
                                        $query = "SELECT id, UPPER(nom_region) AS nom_region FROM regions WHERE active = 'oui' ORDER BY nom_region";
                                        $resultat = mysqli_query($bdd, $query) or die("Erreur SQL");
                                        while ($region = mysqli_fetch_assoc($resultat)) {
                                            echo "<option value='" . htmlspecialchars($region['id']) . "'>" . htmlspecialchars($region['nom_region']) . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="row g-0"> <!-- Suppression de l'espace entre les colonnes avec g-0 -->
                                <div class="col-md-6 pe-1"> <!-- Ajout d'un petit espace à droite -->
                                    <div class="mb-3">
                                    <label for="lieulong" class="form-label">Longitude</label>
                                    <input type="text" class="form-control" id="lieulong" name="lieulong"  step="any" required>
                                    </div>
                                </div>
                                <div class="col-md-6 ps-1"> <!-- Ajout d'un petit espace à gauche -->
                                    <div class="mb-3">
                                    <label for="lieulat" class="form-label">Latitude</label>
                                    <input type="text" class="form-control" id="lieulat" name="lieulat"  step="any" required>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                                    <label class="form-check-label" for="active">Lieu actif</label>
                                </div>
                                <button type="button" class="btn btn-success ms-3" onclick="getAndShowLocation(this)"><i class="fas fa-map-marked-alt me-2"></i>Générer</button>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-phoenix-danger px-4" data-bs-dismiss="modal">
                            <span class="fas fa-times me-2"></span>Annuler
                        </button>
                        <button type="submit" class="btn btn-phoenix-primary px-4" name="submitLieu" id="submitLieuBtn">
                            <span class="fas fa-save me-2"></span>Enregistrer
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade modalAnimate lieuModal" id="lieuModal1" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="<?php echo $url; ?>" id="lieuForm">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ajouter un lieu</h5>
                        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                            <span class="fas fa-times fs-9"></span>
                        </button>
                        </div>
                        
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="lieuName" class="form-label">Nom du lieu</label>
                                <input type="text" class="form-control" id="lieuName" name="nomLieu" required>
                            </div>
                            <div class="mb-3">
                                <label for="organizerSingle"  class="form-label">Région</label>
                                <select class="form-select organizerSingle" id="id_region" name="id_region">
                                    <option disabled selected>Choisissez ...</option> 
                                        <?php
                                        $query = "SELECT id, UPPER(nom_region) AS nom_region FROM regions WHERE active = 'oui' ORDER BY nom_region";
                                        $resultat = mysqli_query($bdd, $query) or die("Erreur SQL");
                                        while ($region = mysqli_fetch_assoc($resultat)) {
                                            echo "<option value='" . htmlspecialchars($region['id']) . "'>" . htmlspecialchars($region['nom_region']) . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="row g-0"> <!-- Suppression de l'espace entre les colonnes avec g-0 -->
                                <div class="col-md-6 pe-1"> <!-- Ajout d'un petit espace à droite -->
                                    <div class="mb-3">
                                    <label for="lieulong" class="form-label">Longitude</label>
                                    <input type="text" class="form-control" id="lieulong" name="lieulong" required>
                                    </div>
                                </div>
                                <div class="col-md-6 ps-1"> <!-- Ajout d'un petit espace à gauche -->
                                    <div class="mb-3">
                                    <label for="lieulat" class="form-label">Latitude</label>
                                    <input type="text" class="form-control" id="lieulat" name="lieulat" required>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                                    <label class="form-check-label" for="active">Lieu actif</label>
                                </div>
                                <button type="button" class="btn btn-success ms-3" onclick="getAndShowLocation(this)"><i class="fas fa-map-marked-alt me-2"></i>Générer</button>
                            </div>
                        
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_lieu" id="id_lieu">
                            <input type="hidden" class="form-control" id="lieuCode" name="codeLieu" readonly>
                        <button type="button" class="btn btn-phoenix-danger px-4" data-bs-dismiss="modal">
                            <span class="fas fa-times me-2"></span>Annuler
                        </button>
                        <button type="submit" class="btn btn-success px-4" name="modifierLieu">
                        <span class="fas fa-edit me-2"></span>Mettre à jour
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

  </body>

</html>

<script>
    function getAndShowLocation(element) {
        // Bouton en mode chargement
        $(element).html("<span class='spinner-border spinner-border-sm' role='status' style='width: 15px; height: 15px;'></span> Génération...");

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    // Récupération des coordonnées
                    const longitude = position.coords.longitude;
                    const latitude = position.coords.latitude;

                    // Remplir les champs du formulaire
                    const form = $(element).closest('form');
                    form.find("input[name='lieulong']").val(longitude);
                    form.find("input[name='lieulat']").val(latitude);

                    // Rétablir le bouton
                    $(element).html('<i class="fas fa-map-marked-alt me-2"></i>Générer');
                },
                function (error) {
                    // Gestion des erreurs
                    let message = 'Echec de détection !';
                    if (error.code === error.PERMISSION_DENIED) message = 'Permission refusée pour la géolocalisation.';
                    else if (error.code === error.POSITION_UNAVAILABLE) message = 'Position non disponible.';
                    else if (error.code === error.TIMEOUT) message = 'Temps de localisation dépassé.';

                    alert(message);
                    $(element).html('<i class="fas fa-map-marked-alt me-2"></i>Générer');
                }
            );
        } else {
            alert('Votre navigateur ne supporte pas la géolocalisation.');
            $(element).html('<i class="fas fa-map-marked-alt me-2"></i>Générer');
        }
    }

    // Nouveau code AJAX
    $('#lieuForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submitLieuBtn');
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="fas fa-spinner fa-spin me-2"></span>Enregistrement...');

        $.ajax({
            url: 'ajax/insertInto.php', 
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                console.log("Réponse brute du serveur:", response); // Ajoutez ceci pour le débogage
                if (response.trim() === 'success') {
                    showToast('success', 'Lieu ajoutée avec succès');
                    $('#lieuModal').modal('hide');
                    $('#lieuForm')[0].reset();
                    // refreshTable();
                } else {
                    showToast('error', 'Une erreur est survenue');
                }
            },
            error: function() {
                showToast('error', 'Erreur de connexion au serveur');
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                submitBtn.html('<span class="fas fa-save me-2"></span>Enregistrer');
            }
        });
    });
</script>

<script>
    $('.modififierInfos').click(function() {
        const isActive = $(this).attr('active').toLowerCase() === 'oui';

        $("#lieuModal1 input[name='nomLieu']").val($(this).attr('nom_lieu'));
        $("#lieuModal1 input[name='lieulong']").val($(this).attr('lieulong'));
        $("#lieuModal1 input[name='lieulat']").val($(this).attr('lieulat'));
        $("#lieuModal1 input[name='id_lieu']").val($(this).attr('id_lieu'));
        $("#lieuModal1 input[name='active']").prop('checked', isActive);

        const regionId = $(this).attr('id_region');
        
        // Affiche d’abord le modal
        $('#lieuModal1').modal('show');

        // Une fois le modal complètement affiché → MAJ du Choices
        $('#lieuModal1').on('shown.bs.modal', function() {
            choicesInstances['id_region'].setChoiceByValue(regionId);
        });
    });
</script>
