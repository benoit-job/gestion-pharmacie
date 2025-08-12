

<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Users</title>

    <?php include('includes/php/includes-css.php');?>

  </head>


  <body>

    <main class="main" id="top">
    	
      <?php include('includes/php/menu.php');?>

      <?php include('includes/php/header.php');?>

      <div class="content">

        <div class="pb-3">
            <div class="mb-8">
              <h2 class="mb-2">Utilisateur</h2>
              <h5 class="text-body-tertiary fw-semibold">Gérer vos users </h5>
            </div>

            <div class="page-section">
                <div class="card card-fluid">
                  <div class="card-header border-0 p-1 d-flex justify-content-end">
                        <button class="btn btn-phoenix-secondary rounded-pill me-1 mb-1" 
                            type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <span class="fas fa-square-plus fa-lg me-2" data-fa-transform="shrink-3"></span>
                            Créer nouveau
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table id="usersTable" class="table table-hover m-0 usersTable" style="width:100%">
                            <thead class="thead-">
                                <tr style='font-size: 0.8rem;'>
                                    <th>N</th>
                                    <th>Pseudo</th>
                                    <th>Contact</th>
                                    <th>Date enreg</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $query = "SELECT *,
                                                    pseudo,
                                                    telephone,
                                                    date_format(date_heure, '%d/%m/%Y %Hh%i') AS date_heure
                                            FROM users ORDER BY pseudo";
                                    $resultat = mysqli_query($bdd, $query) or die("Erreur de requête");
                                    
                                    $ligne = 0;
                                    while($utilisateur = mysqli_fetch_array($resultat)) {                           
                                        echo "<tr>
                                                <td>".++$ligne."</td>
                                                <td>".htmlspecialchars($utilisateur["pseudo"])."</td>
                                                <td>".htmlspecialchars($utilisateur["telephone"])."</td>
                                                <td>".htmlspecialchars($utilisateur["date_heure"])."</td>
                                                <td class='text-end'>
                                                    <form method='post' action='utilisateurs.php' style='display:inline;'>
                                                        <input type='hidden' name='id_utilisateur' value='".crypt_decrypt_chaine($utilisateur['id'], 'C')."'>
                                                        
                                                        <button type='button' class='btn btn-light btn-sm modififierInfos' 
                                                        id_utilisateur='".crypt_decrypt_chaine($utilisateur['id'], 'C')."'
                                                            pseudo='".htmlspecialchars($utilisateur['pseudo'])."'
                                                            telephone='".htmlspecialchars($utilisateur['telephone'])."'>
                                                            <i class='fas fa-edit me-1'></i>
                                                        </button>
                                                        
                                                        <button type='button' class='btn btn-light btn-sm btn-supprimer' 
                                                            data-id='".crypt_decrypt_chaine($utilisateur['id'], 'C')."'
                                                            data-type='utilisateur'>
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

         <!-- Ajouter utilisateur -->
    <!-- ===============================================-->
        <form method="post" action="utilisateurs.php">
            <div class="modal fade modalAnimate" id="exampleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ajouter utilisateur</h5>
                        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs-9"></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pseudo </label>
                            <input type="text" name="pseudo" class="form-control" required/>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact</label>
                            <input type="tel" name="telephone" class="form-control" required/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="ajouterUtilisateur" class="btn btn-primary">Valider</button>
                        <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Fermer</button>
                    </div>
                    </div>
                </div>
            </div>
        </form>

         <!-- Modifier utilisateur -->
    <!-- ===============================================-->
        <form method="post" action="utilisateurs.php">
            <div class="modal fade modalAnimate" id="exampleModal2" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modifier utilisateur</h5>
                        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs-9"></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pseudo </label>
                            <input type="text" name="pseudo" class="form-control" required/>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact</label>
                            <input type="tel" name="telephone" class="form-control" required/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_utilisateur">
                        <button type="submit" name="modifierUtilisateur" class="btn btn-primary">Valider</button>
                        <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Fermer</button>
                    </div>
                    </div>
                </div>
            </div>
        </form>


        <?php include('includes/php/footer.php');?>

      </div>
      
    </main>

    <?php include('includes/php/includes-js.php');?>

  </body>

</html>

<script>
$('.modififierInfos').click(function(){     

    $("#exampleModal2 input[name='pseudo']").val($(this).attr('pseudo'));
    $("#exampleModal2 input[name='mot_de_passe']").val($(this).attr('mot_de_passe'));
    $("#exampleModal2 input[name='telephone']").val($(this).attr('telephone')); 
    $("#exampleModal2 input[name='id_utilisateur']").val($(this).attr('id_utilisateur')); 


    $('#exampleModal2').modal('show');
});
</script> 