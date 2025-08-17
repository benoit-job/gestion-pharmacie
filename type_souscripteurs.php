<?php
  include("includes/connexion_acces_page.php");
  include("includes/connexion_bdd.php");
  include("includes/fonctions.php");
include_once("includes/auth_functions.php");   
  $url = "type_souscripteurs.php";
?>
<?php
if(isset($_POST['modifierType']))  
    {
        $id_type = strip_tags(htmlspecialchars(trim(crypt_decrypt_chaine($_POST['id_type'], 'D') )));
        $nom_type = strip_tags(htmlspecialchars(trim($_POST["nomType"])));
        $activeType = isset($_POST['activeType']) ? 'oui' : 'non';


        $query = "UPDATE type_souscripteurs  
                  SET nom = \"$nom_type\", 
                      active = \"$activeType\",
                      date_update  = '".date('Y-m-d H:i:s')."'
                  WHERE id =".$id_type;
        $result = mysqli_query($bdd, $query);
    
    if($result) {
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Type modifié avec succès'
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
if (isset($_POST["supprimerType"])) {
    $id_type = crypt_decrypt_chaine($_POST["id_type"], 'D');
    
        // Récupération du nom de l'utilisateur
        $nom_user = getUserPseudo($bdd, $_SESSION['user']['id']);

    // Récupérer les données actuelles de la type
    $selectQuery = "SELECT * FROM type_souscripteurs WHERE id = $id_type";
    $result = mysqli_query($bdd, $selectQuery) or die("Erreur lors de la récupération des données");

    if ($type = mysqli_fetch_assoc($result)) {
        // Insertion dans historique_type
        $nom_type = mysqli_real_escape_string($bdd, $type['nom']);

        $insertQuery = "INSERT INTO historique_type_souscripts (id_user, id_type, nom, action, date_delet)
                        VALUES ('$nom_user', $id_type, '$nom_type', 'supprimer', NOW())";
        mysqli_query($bdd, $insertQuery) or die("Erreur lors de l'insertion dans historique_type");
    }

    $deleteQuery = "DELETE FROM type_souscripteurs WHERE id = ".intval($id_type);
    $result = mysqli_query($bdd, $deleteQuery);
    
    if($result) {
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Type supprimée avec succès'
        ];
    } else {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Erreur lors de la suppression de la type: '
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

    <title>Type de souscripteur</title>

    <?php include('includes/php/includes-css.php');?>
    

  </head>


  <body>

    <main class="main" id="top">
    	
      <?php include('includes/php/menu.php');?>

      <?php include('includes/php/header.php');?>

      <div class="content">

        <div class="pb-3">
            <div class="mb-8">
              <h2 class="mb-2">Type de souscripteur</h2>
              <h5 class="text-body-tertiary fw-semibold">Gérer vos type de souscripteur </h5>
            </div>

            <div class="page-section">
                <div class="card card-fluid">
                    <div class="card-header border-0 p-1 d-flex justify-content-end">
                        <button class="btn btn-phoenix-secondary rounded-pill me-1 mb-1" 
                            type="button" data-bs-toggle="modal" data-bs-target="#typeModal">
                            <span class="fas fa-square-plus fa-lg me-2" data-fa-transform="shrink-3"></span>
                            Créer nouveau
                        </button>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-hover m-0 usersTable" style="width:100%">
                        <thead class="thead-">
                        <tr style="font-size: 0.8rem;">
                            <th>N</th>
                            <th>TYPE SOUSCRIPTEUR</th>
                            <th>DATE CREATION</th>
                            <th>ACTIVE</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $query = "SELECT id,
                                                UPPER(nom) AS nom_type,
                                                DATE_FORMAT(date_insert, '%d/%m/%Y %Hh%i') AS date_creation,
                                                active
                                        FROM type_souscripteurs ORDER BY nom_type";
                                $resultat = mysqli_query($bdd, $query) or die("Erreur de requête");

                                $ligne = 0;
                                while($type = mysqli_fetch_array($resultat)) {                           
                                    $isActive = strtolower($type["active"]) === 'oui';
                                    $activeClass = $isActive ? 'bg-success' : '';
                                    $checkedAttr = $isActive ? 'checked' : '';
                                    
                                    echo "<tr>
                                            <td>".++$ligne."</td>
                                            <td>".htmlspecialchars($type["nom_type"])."</td>
                                            <td>".htmlspecialchars($type["date_creation"])."</td>
                                            <td class='text-center'>
                                                <div class='form-check form-switch  mb-4'> 
                                                    <input class='form-check-input $activeClass' type='checkbox' $checkedAttr disabled>
                                                </div>
                                            </td>
                                            <td class='text-end'>
                                                <form method='post' action='".$url."' style='display:inline;'>
                                                    <input type='hidden' name='id_type' value='".crypt_decrypt_chaine($type['id'], 'C')."'>";
                                                if (hasPermission('type_souscripteurs.edit')) {
                                                    echo "<button type='button' class='btn btn-light btn-sm modififierInfos' 
                                                        id_type='".crypt_decrypt_chaine($type['id'], 'C')."'
                                                            nom_type='".htmlspecialchars($type['nom_type'])."'
                                                            active='".htmlspecialchars($type['active'])."'>
                                                            <i class='fas fa-edit me-1'></i>
                                                        </button>";
                                                }
                                                
                                                if (hasPermission('type_souscripteurs.delete')) {
                                                echo "<button type='button' class='btn btn-light btn-sm btn-supprimer' 
                                                        data-id='".crypt_decrypt_chaine($type['id'], 'C')."'
                                                        data-type='type'>
                                                        <i class='fas fa-trash-alt me-1'></i>
                                                    </button>";
                                            }

                                         echo "</form>
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
         
        <div class="modal fade modalAnimate typeModal" id="typeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="<?php echo $url; ?>" id="typeForm">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ajouter un type</h5>
                        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                            <span class="fas fa-times fs-9"></span>
                        </button>
                        </div>
                        <div class="modal-body">
                        <div class="mb-3">
                            <label for="typeName" class="form-label">Nom du type</label>
                            <input type="text" class="form-control" id="typeName" name="nomType" required>
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="activeType" name="activeType" checked>
                            <label class="form-check-label" for="activeType">Active</label>
                        </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-phoenix-danger px-4" data-bs-dismiss="modal">
                            <span class="fas fa-times me-2"></span>Annuler
                        </button>
                        <button type="submit" class="btn btn-phoenix-primary px-4" name="submitType" id="submitTypeBtn">
                            <span class="fas fa-save me-2"></span>Enregistrer
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade modalAnimate typeModal" id="typeModal1" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="<?php echo $url; ?>" id="typeForm">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modifier le type</h5>
                        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                            <span class="fas fa-times fs-9"></span>
                        </button>
                        </div>
                        <div class="modal-body">
                        <div class="mb-3">
                            <label for="typeName" class="form-label">Nom du type</label>
                            <input type="text" class="form-control" id="typeName" name="nomType" required>
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="activeType" name="activeType" checked>
                            <label class="form-check-label" for="activeType">Active</label>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_type" id="id_type">
                            <input type="hidden" class="form-control" id="typeCode" name="codeType" readonly>
                        <button type="button" class="btn btn-phoenix-danger px-4" data-bs-dismiss="modal">
                            <span class="fas fa-times me-2"></span>Annuler
                        </button>
                        <button type="submit" class="btn btn-success px-4" name="modifierType">
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

    // Nouveau code AJAX
    $('#typeForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submitTypeBtn');
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="fas fa-spinner fa-spin me-2"></span>Enregistrement...');

        $.ajax({
            url: 'ajax/insertInto.php', 
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                console.log("Réponse brute du serveur:", response); // Ajoutez ceci pour le débogage
                if (response.trim() === 'success') {
                    showToast('success', 'Type ajoutée avec succès');
                    $('#typeModal').modal('hide');
                    $('#typeForm')[0].reset();
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

    $("#typeModal1 input[name='nomType']").val($(this).attr('nom_type'));
    $("#typeModal1 input[name='id_type']").val($(this).attr('id_type'));
    
    // Cocher ou décocher la case
    $("#typeModal1 input[name='activeType']").prop('checked', isActive);

    $('#typeModal1').modal('show');
});

</script> 
</script>
