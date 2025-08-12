<?php
  include("includes/connexion_acces_page.php");
  include("includes/connexion_bdd.php");
  include("includes/fonctions.php");
include_once("includes/auth_functions.php");   
  $url = "regions_pharma.php";
?>
<?php
if(isset($_POST['modifierRegion']))  
    {
        $id_region = strip_tags(htmlspecialchars(trim( crypt_decrypt_chaine($_POST['id_region'], 'D') )));
        $nom_region = strip_tags(htmlspecialchars(trim($_POST["nomRegion"])));
        $code_region = strip_tags(htmlspecialchars(trim($_POST["codeRegion"])));
        $activeRegion = isset($_POST['activeRegion']) ? 'oui' : 'non';


        $query = "UPDATE regions  
                  SET nom_region = \"$nom_region\", 
                      code_region = \"$code_region\",
                      active = \"$activeRegion\",
                      date_update  = '".date('Y-m-d H:i:s')."'
                  WHERE id =".$id_region;
        $result = mysqli_query($bdd, $query);
    
    if($result) {
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Région modifié avec succès'
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

    <title>Région Pharmacie</title>

    <?php include('includes/php/includes-css.php');?>
    

  </head>


  <body>

    <main class="main" id="top">
    	
      <?php include('includes/php/menu.php');?>

      <?php include('includes/php/header.php');?>

      <div class="content">

        <div class="pb-3">
            <div class="mb-8">
              <h3 class="mb-2">Région Pharmacie</h3>
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
                            <label for="regionName" class="form-label">Nom de la région</label>
                            <input type="text" class="form-control" id="regionName" name="nomRegion" required>
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="activeRegion" name="activeRegion" checked>
                            <label class="form-check-label" for="activeRegion">Région active</label>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" class="form-control" id="regionCode" name="codeRegion" readonly>
                        <button type="button" class="btn btn-phoenix-danger px-4" data-bs-dismiss="modal">
                            <span class="fas fa-times me-2"></span>Annuler
                        </button>
                        <button type="submit" class="btn btn-phoenix-primary px-4" name="submitRegion" id="submitRegionBtn">
                            <span class="fas fa-save me-2"></span>Enregistrer
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade modalAnimate regionModal" id="regionModal1" tabindex="-1" aria-hidden="true">
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
                            <label for="regionName" class="form-label">Nom de la région</label>
                            <input type="text" class="form-control" id="regionName" name="nomRegion" required>
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="activeRegion" name="activeRegion" checked>
                            <label class="form-check-label" for="activeRegion">Région active</label>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_region" id="id_region">
                            <input type="hidden" class="form-control" id="regionCode" name="codeRegion" readonly>
                        <button type="button" class="btn btn-phoenix-danger px-4" data-bs-dismiss="modal">
                            <span class="fas fa-times me-2"></span>Annuler
                        </button>
                        <button type="submit" class="btn btn-success px-4" name="modifierRegion">
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
document.addEventListener('DOMContentLoaded', function () {
    // Votre code existant pour générer le code région...
    const inputRegion = document.getElementById('regionName');
    const inputCode = document.getElementById('regionCode');

    inputRegion.addEventListener('input', function () {
        const nom = inputRegion.value.trim();
        if (nom.length === 0) {
            inputCode.value = '';
            return;
        }
        const sansAccents = nom.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        const format = sansAccents.toUpperCase().replace(/\s+/g, '-').replace(/[^A-Z\-]/g, '');
        const code = format + "-001";
        inputCode.value = code;
    });

    // Nouveau code AJAX
    $('#regionForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submitRegionBtn');
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="fas fa-spinner fa-spin me-2"></span>Enregistrement...');

        $.ajax({
            url: 'ajax/insertInto.php', 
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                console.log("Réponse brute du serveur:", response); // Ajoutez ceci pour le débogage
                if (response.trim() === 'success') {
                    showToast('success', 'Région ajoutée avec succès');
                    $('#regionModal').modal('hide');
                    $('#regionForm')[0].reset();
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

});
</script>

<script>
$('.modififierInfos').click(function() {
    const isActive = $(this).attr('active').toLowerCase() === 'oui';

    $("#regionModal1 input[name='nomRegion']").val($(this).attr('nom_region'));
    $("#regionModal1 input[name='codeRegion']").val($(this).attr('code_region'));
    $("#regionModal1 input[name='id_region']").val($(this).attr('id_region'));
    
    // Cocher ou décocher la case
    $("#regionModal1 input[name='activeRegion']").prop('checked', isActive);

    $('#regionModal1').modal('show');
});

</script> 
</script>
