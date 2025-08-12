<?php
include("includes/connexion_acces_page.php");
include("includes/connexion_bdd.php");
include("includes/fonctions.php");
include_once("includes/auth_functions.php");   

// Vérifier les permissions
requirePermission('admin.users');

// Traitement des actions
if(isset($_POST["ajouterUtilisateur"])) {
    $pseudo = strip_tags(htmlspecialchars(trim($_POST["pseudo"])));
    $telephone = strip_tags(htmlspecialchars(trim($_POST["telephone"])));
    $email = strip_tags(htmlspecialchars(trim($_POST["email"])));
    $password = strip_tags(htmlspecialchars(trim($_POST["password"])));
    $role_id = intval($_POST["role_id"]);

    // Vérifier si l'email ou le téléphone existe déjà
    $check_query = "SELECT COUNT(*) as count FROM users WHERE email = ? OR telephone = ?";
    $stmt = mysqli_prepare($bdd, $check_query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $telephone);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $count = mysqli_fetch_assoc($result)['count'];

    if ($count > 0) {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Email ou téléphone déjà utilisé'
        ];
    } else {
        $query = "INSERT INTO users (pseudo, telephone, email, password, role_id, statut, date_heure) 
                  VALUES (?, ?, ?, ?, ?, 'actif', ?)";
        $stmt = mysqli_prepare($bdd, $query);
        $date_now = date('Y-m-d H:i:s');
        mysqli_stmt_bind_param($stmt, "ssssds", $pseudo, $telephone, $email, $password, $role_id, $date_now);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => 'Utilisateur ajouté avec succès'
            ];
            logUserActivity('create_user', "Nouvel utilisateur créé: $pseudo", $bdd);
        } else {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'Erreur lors de l\'ajout'
            ];
        }
    }
    reload_current_page();
}

if(isset($_POST['modifierUtilisateur'])) {
    $id_utilisateur = crypt_decrypt_chaine($_POST['id_utilisateur'], 'D');
    $pseudo = strip_tags(htmlspecialchars(trim($_POST["pseudo"])));
    $telephone = strip_tags(htmlspecialchars(trim($_POST["telephone"])));
    $email = strip_tags(htmlspecialchars(trim($_POST["email"])));
    $role_id = intval($_POST["role_id"]);
    $statut = $_POST["statut"];

    $query = "UPDATE users SET 
                pseudo = ?, 
                telephone = ?, 
                email = ?, 
                role_id = ?, 
                statut = ?, 
                date_heure = ?
              WHERE id = ?";
    $stmt = mysqli_prepare($bdd, $query);
    $date_now = date('Y-m-d H:i:s');
    mysqli_stmt_bind_param($stmt, "sssdssi", $pseudo, $telephone, $email, $role_id, $statut, $date_now, $id_utilisateur);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Utilisateur modifié avec succès'
        ];
        logUserActivity('update_user', "Utilisateur modifié: $pseudo", $bdd);
    }
    reload_current_page();
}

if(isset($_POST["desactiverUtilisateur"])) {
    $id_utilisateur = crypt_decrypt_chaine($_POST["id_utilisateur"], 'D');
    
    // Ne pas permettre de supprimer son propre compte
    if ($id_utilisateur == $_SESSION['user']['id']) {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Vous ne pouvez pas desactiver votre propre compte'
        ];
    } else {
        $query = "UPDATE users SET statut = 'inactif' WHERE id = ?";
        $stmt = mysqli_prepare($bdd, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_utilisateur);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => 'Utilisateur désactivé avec succès'
            ];
            logUserActivity('deactivate_user', "Utilisateur désactivé: ID $id_utilisateur", $bdd);
        }
    }
    reload_current_page();
}

//DELETE FROM
  if(isset($_POST["supprimerUtilisateur"]))
  {
    $id_utilisateur = crypt_decrypt_chaine( $_POST["id_utilisateur"], 'D');

    $query = "DELETE FROM users WHERE id =".$id_utilisateur;
    $result =mysqli_query($bdd, $query) or die("Requête non conforme2222"); 
    if($result) {
        // Stocker le message de succès dans la session
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'User supprimée avec succès'
        ];
    } else {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Erreur lors de la suppression du user'
        ];
    }
       reload_current_page();
}

// Récupérer les rôles pour les selects
$roles_query = "SELECT * FROM roles WHERE statut = 'actif' ORDER BY nom";
$roles_result = mysqli_query($bdd, $roles_query);
$roles = [];
while ($role = mysqli_fetch_assoc($roles_result)) {
    $roles[] = $role;
}
?>

<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Utilisateurs</title>
    <?php include('includes/php/includes-css.php');?>
</head>

<body>
    <main class="main" id="top">
        <?php include('includes/php/menu.php');?>
        <?php include('includes/php/header.php');?>

        <div class="content">
            <div class="pb-3">
                <div class="mb-8">
                    <h2 class="mb-2">Utilisateurs</h2>
                    <h5 class="text-body-tertiary fw-semibold">Gérer les utilisateurs du système</h5>
                </div>

                <div class="page-section">
                    <div class="card card-fluid">
                        <div class="card-header border-0 p-1 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-info">
                                    <?php 
                                    $count_query = "SELECT COUNT(*) as total FROM users WHERE statut = 'actif'";
                                    $count_result = mysqli_query($bdd, $count_query);
                                    $total_users = mysqli_fetch_assoc($count_result)['total'];
                                    echo $total_users . ' utilisateur(s) actif(s)';
                                    ?>
                                </span>
                            </div>
                            <button class="btn btn-phoenix-secondary rounded-pill me-1 mb-1" 
                                type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <span class="fas fa-square-plus fa-lg me-2" data-fa-transform="shrink-3"></span>
                                Nouvel utilisateur
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="usersTable" class="table table-hover m-0" style="width:100%">
                                <thead>
                                    <tr style='font-size: 0.8rem;'>
                                        <th>N</th>
                                        <th>Pseudo</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Statut</th>
                                        <th>Dernière activité</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $query = "SELECT u.*, r.nom as role_name,
                                                    DATE_FORMAT(u.date_heure, '%d/%m/%Y %Hh%i') AS date_heure_format
                                            FROM users u
                                            LEFT JOIN roles r ON u.role_id = r.id
                                            ORDER BY u.pseudo";
                                    $resultat = mysqli_query($bdd, $query);
                                    
                                    $ligne = 0;
                                    while($utilisateur = mysqli_fetch_array($resultat)) {
                                        $statut_badge = $utilisateur['statut'] == 'actif' ? 'bg-success' : 'bg-danger';
                                        $role_badge = $utilisateur['role_name'] ? 
                                            '<span class="badge bg-primary">' . htmlspecialchars($utilisateur['role_name']) . '</span>' : 
                                            '<span class="badge bg-secondary">Aucun rôle</span>';
                                        
                                        echo "<tr>
                                                <td>".++$ligne."</td>
                                                <td><strong>".htmlspecialchars($utilisateur["pseudo"])."</strong></td>
                                                <td>".htmlspecialchars($utilisateur["telephone"])."</td>
                                                <td>".htmlspecialchars($utilisateur["email"] ?? 'Non défini')."</td>
                                                <td>$role_badge</td>
                                                <td><span class='badge $statut_badge'>".ucfirst($utilisateur["statut"])."</span></td>
                                                <td>".htmlspecialchars($utilisateur["date_heure_format"])."</td>
                                                <td class='text-end'>
                                                    <form method='post' action='utilisateurs.php' style='display:inline;'>
                                                        <input type='hidden' name='id_utilisateur' value='".crypt_decrypt_chaine($utilisateur['id'], 'C')."'>
                                                    <div class='btn-group btn-group-sm' role='group'>
                                                        <button type='button' class='btn btn-outline-primary modifierInfos' 
                                                            data-id='".crypt_decrypt_chaine($utilisateur['id'], 'C')."'
                                                            data-pseudo='".htmlspecialchars($utilisateur['pseudo'])."'
                                                            data-telephone='".htmlspecialchars($utilisateur['telephone'])."'
                                                            data-email='".htmlspecialchars($utilisateur['email'] ?? '')."'
                                                            data-role='".($utilisateur['role_id'] ?? '')."'
                                                            data-statut='".htmlspecialchars($utilisateur['statut'])."'
                                                            title='Modifier'>
                                                            <i class='fas fa-edit'></i>
                                                        </button>";
                                        // Ne pas permettre de supprimer son propre compte
                                        if ($utilisateur['id'] != $_SESSION['user']['id']) {
                                            echo "<button type='button' class='btn btn-outline-danger btn-desactiver' 
                                                    data-id='".crypt_decrypt_chaine($utilisateur['id'], 'C')."'
                                                    data-type='utilisateur'
                                                    data-nom='".htmlspecialchars($utilisateur['pseudo'])."'
                                                    title='Désactiver'>
                                                    <i class='fas fa-user-slash'></i>
                                                </button>";
                                        }
                                        
                                        if ($utilisateur['id'] != $_SESSION['user']['id']) {
                                            echo "<button type='button' class='btn btn-outline-danger btn-supprimer' 
                                                    data-id='".crypt_decrypt_chaine($utilisateur['id'], 'C')."'
                                                    data-type='utilisateur'
                                                    title='Supprimer'>
                                                    <i class='fas fa-trash-alt'></i>
                                                </button>";
                                        }
                                        
                                        echo "  </div>
                                                </td>
                                             </form>
                                            </tr>";
                                    }
                                    ?>                            
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Ajouter utilisateur -->
            <form method="post" action="utilisateurs.php" id="formAjouterUtilisateur">
                <div class="modal fade modalAnimate" id="exampleModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Ajouter un utilisateur</h5>
                                <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                                    <span class="fas fa-times fs-9"></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Pseudo *</label>
                                        <input type="text" name="pseudo" class="form-control" required/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Téléphone *</label>
                                        <input type="tel" name="telephone" class="form-control" required/>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mot de passe *</label>
                                        <input type="password" name="password" class="form-control" required/>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Rôle *</label>
                                        <select name="role_id" class="form-select" required>
                                            <option value="">Sélectionner un rôle</option>
                                            <?php foreach($roles as $role): ?>
                                                <option value="<?= $role['id'] ?>">
                                                    <?= htmlspecialchars($role['nom']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="alert alert-info">
                                    <small>
                                        <i class="fas fa-info-circle me-2"></i>
                                        L'utilisateur recevra ses identifiants et devra se connecter pour accéder au système.
                                    </small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="ajouterUtilisateur" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Créer l'utilisateur
                                </button>
                                <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">
                                    Annuler
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Modal Modifier utilisateur -->
            <form method="post" action="utilisateurs.php" id="formModifierUtilisateur">
                <div class="modal fade modalAnimate" id="exampleModal2" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modifier l'utilisateur</h5>
                                <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close">
                                    <span class="fas fa-times fs-9"></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_utilisateur">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Pseudo *</label>
                                        <input type="text" name="pseudo" class="form-control" required/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Téléphone *</label>
                                        <input type="tel" name="telephone" class="form-control" required/>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Rôle *</label>
                                        <select name="role_id" class="form-select" required>
                                            <option value="">Sélectionner un rôle</option>
                                            <?php foreach($roles as $role): ?>
                                                <option value="<?= $role['id'] ?>">
                                                    <?= htmlspecialchars($role['nom']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Statut</label>
                                        <select name="statut" class="form-select">
                                            <option value="actif">Actif</option>
                                            <option value="inactif">Inactif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="alert alert-warning">
                                    <small>
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        La modification du rôle prendra effet à la prochaine connexion de l'utilisateur.
                                    </small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="modifierUtilisateur" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Sauvegarder
                                </button>
                                <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">
                                    Annuler
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <?php include('includes/php/footer.php');?>
        </div>
    </main>

    <?php include('includes/php/includes-js.php');?>

    <script>
    $(document).ready(function() {
        // Initialiser DataTable
        $('#usersTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
            },
            order: [[1, 'asc']],
            columnDefs: [
                { orderable: false, targets: [7] }
            ]
        });

        // Modifier un utilisateur
        $('.modifierInfos').click(function(){
            var modal = $("#exampleModal2");
            modal.find("input[name='pseudo']").val($(this).data('pseudo'));
            modal.find("input[name='telephone']").val($(this).data('telephone'));
            modal.find("input[name='email']").val($(this).data('email'));
            modal.find("select[name='role_id']").val($(this).data('role'));
            modal.find("select[name='statut']").val($(this).data('statut'));
            modal.find("input[name='id_utilisateur']").val($(this).data('id'));
            modal.modal('show');
        });

        // Gestion de la suppression/désactivation
        $('.btn-desactiver').click(function() {
            var id = $(this).data('id');
            var nom = $(this).data('nom');
            var type = $(this).data('type');
            
            if (confirm('Êtes-vous sûr de vouloir désactiver l\'utilisateur "' + nom + '" ?\n\nCette action peut être annulée en modifiant le statut.')) {
                var form = $('<form method="post" action="utilisateurs.php"></form>');
                form.append('<input type="hidden" name="id_' + type + '" value="' + id + '">');
                form.append('<input type="hidden" name="desactiver' + type.charAt(0).toUpperCase() + type.slice(1) + '" value="1">');
                $('body').append(form);
                form.submit();
            }
        });

        // Validation des formulaires
        $('#formAjouterUtilisateur').on('submit', function(e) {
            var password = $(this).find('input[name="password"]').val();
            if (password.length < 6) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 6 caractères.');
                return false;
            }
        });

        // Afficher les descriptions des rôles
        $('select[name="role_id"]').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var roleId = selectedOption.val();
            
            // Vous pouvez ajouter ici un appel AJAX pour récupérer et afficher 
            // la description détaillée du rôle sélectionné
        });
    });
    </script>

</body>
</html>
