<?php
include("includes/connexion_acces_page.php");
include("includes/connexion_bdd.php");
include("includes/fonctions.php");
include_once("includes/auth_functions.php");


// Vérifier si l'utilisateur a la permission admin.roles
if (!hasPermission('admin.roles')) {
    header('Location: accueil.php');
    exit();
}

// Traitement des actions
if(isset($_POST["ajouterRole"])) {
    $nom = strip_tags(htmlspecialchars(trim($_POST["nom"])));
    $description = strip_tags(htmlspecialchars(trim($_POST["description"])));
    
    $query = "INSERT INTO roles (nom, description) VALUES (?, ?)";
    $stmt = mysqli_prepare($bdd, $query);
    mysqli_stmt_bind_param($stmt, "ss", $nom, $description);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Rôle ajouté avec succès'];
    }
    reload_current_page();
}

if(isset($_POST['modifierRole'])) {
    $id_role = crypt_decrypt_chaine($_POST['id_role'], 'D');
    $nom = strip_tags(htmlspecialchars(trim($_POST["nom"])));
    $description = strip_tags(htmlspecialchars(trim($_POST["description"])));
    
    $query = "UPDATE roles SET nom = ?, description = ? WHERE id = ?";
    $stmt = mysqli_prepare($bdd, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $nom, $description, $id_role);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Rôle modifié avec succès'];
    }
    reload_current_page();
}

if(isset($_POST["supprimerRole"])) {
    $id_role = crypt_decrypt_chaine($_POST["id_role"], 'D');
    
    // Vérifier qu'aucun utilisateur n'utilise ce rôle
    $check_query = "SELECT COUNT(*) as count FROM users WHERE role_id = ?";
    $stmt = mysqli_prepare($bdd, $check_query);
    mysqli_stmt_bind_param($stmt, "i", $id_role);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $count = mysqli_fetch_assoc($result)['count'];
    
    if ($count > 0) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Impossible de supprimer : des utilisateurs utilisent ce rôle'];
    } else {
        $query = "DELETE FROM roles WHERE id = ?";
        $stmt = mysqli_prepare($bdd, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_role);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Rôle supprimé avec succès'];
        }
    }
    reload_current_page();
}

if(isset($_POST["updatePermissions"])) {
    $role_id = crypt_decrypt_chaine($_POST["role_id"], 'D');
    $permissions = isset($_POST["permissions"]) ? $_POST["permissions"] : [];
    
    // Supprimer toutes les permissions actuelles du rôle
    $delete_query = "DELETE FROM role_permissions WHERE role_id = ?";
    $stmt = mysqli_prepare($bdd, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $role_id);
    mysqli_stmt_execute($stmt);
    
    // Ajouter les nouvelles permissions
    if (!empty($permissions)) {
        $insert_query = "INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($bdd, $insert_query);
        
        foreach ($permissions as $permission_id) {
            mysqli_stmt_bind_param($stmt, "ii", $role_id, $permission_id);
            mysqli_stmt_execute($stmt);
        }
    }
    
    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Permissions mises à jour avec succès'];
    reload_current_page();
}
?>

<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rôles & Permissions</title>
    <?php include('includes/php/includes-css.php');?>
</head>

<body>
    <main class="main" id="top">
        <?php include('includes/php/menu.php');?>
        <?php include('includes/php/header.php');?>

        <div class="content">
            <div class="pb-3">
                <div class="mb-8">
                    <h2 class="mb-2">Rôles & Permissions</h2>
                    <h5 class="text-body-tertiary fw-semibold">Gérer les accès et autorisations</h5>
                </div>

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">
                            <i class="fas fa-user-tag me-2"></i>Rôles
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab">
                            <i class="fas fa-key me-2"></i>Permissions
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="myTabContent">
                    
                    <!-- Onglet Rôles -->
                    <div class="tab-pane fade show active" id="roles" role="tabpanel">
                        <div class="card card-fluid">
                            <div class="card-header border-0 p-1 d-flex justify-content-end">
                                <button class="btn btn-phoenix-secondary rounded-pill me-1 mb-1" 
                                    type="button" data-bs-toggle="modal" data-bs-target="#modalAjouterRole">
                                    <span class="fas fa-square-plus fa-lg me-2"></span>
                                    Nouveau rôle
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover m-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Nom</th>
                                            <th>Description</th>
                                            <th>Utilisateurs</th>
                                            <th>Permissions</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $query = "SELECT r.*, 
                                                        COUNT(u.id) as nb_users,
                                                        COUNT(rp.permission_id) as nb_permissions
                                                FROM roles r
                                                LEFT JOIN users u ON r.id = u.role_id
                                                LEFT JOIN role_permissions rp ON r.id = rp.role_id
                                                WHERE r.statut = 'actif'
                                                GROUP BY r.id
                                                ORDER BY r.nom";
                                        $resultat = mysqli_query($bdd, $query);
                                        
                                        $ligne = 0;
                                        while($role = mysqli_fetch_array($resultat)) {                           
                                            echo "<tr>
                                                    <td>".++$ligne."</td>
                                                    <td><strong>".htmlspecialchars($role["nom"])."</strong></td>
                                                    <td>".htmlspecialchars($role["description"])."</td>
                                                    <td><span class='badge bg-info'>".$role["nb_users"]." utilisateur(s)</span></td>
                                                    <td><span class='badge bg-success'>".$role["nb_permissions"]." permission(s)</span></td>
                                                    <td class='text-end'>
                                                        <button type='button' class='btn btn-light btn-sm modifierRole' 
                                                            data-id='".crypt_decrypt_chaine($role['id'], 'C')."'
                                                            data-nom='".htmlspecialchars($role['nom'])."'
                                                            data-description='".htmlspecialchars($role['description'])."'>
                                                            <i class='fas fa-edit'></i>
                                                        </button>
                                                        
                                                        <button type='button' class='btn btn-primary btn-sm gererPermissions' 
                                                            data-id='".crypt_decrypt_chaine($role['id'], 'C')."'
                                                            data-nom='".htmlspecialchars($role['nom'])."'>
                                                            <i class='fas fa-key'></i>
                                                        </button>";
                                            
                                            if ($role['id'] > 4) { // Ne pas supprimer les rôles par défaut
                                                echo "<button type='button' class='btn btn-light btn-sm btn-supprimer' 
                                                        data-id='".crypt_decrypt_chaine($role['id'], 'C')."'
                                                        data-type='role'>
                                                        <i class='fas fa-trash-alt'></i>
                                                    </button>";
                                            }
                                            
                                            echo "</td>
                                                </tr>";
                                        }
                                        ?>                            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Onglet Permissions -->
                    <div class="tab-pane fade" id="permissions" role="tabpanel">
                        <div class="card card-fluid">
                            <div class="card-header">
                                <h5 class="mb-0">Liste des permissions système</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover m-0">
                                    <thead>
                                        <tr>
                                            <th>Module</th>
                                            <th>Permission</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $query = "SELECT * FROM permissions WHERE statut = 'actif' ORDER BY module, action";
                                        $resultat = mysqli_query($bdd, $query);
                                        
                                        $current_module = '';
                                        while($permission = mysqli_fetch_array($resultat)) {
                                            if ($current_module != $permission['module']) {
                                                $current_module = $permission['module'];
                                                echo "<tr class='table-secondary'>
                                                        <td colspan='4'><strong>".strtoupper($current_module)."</strong></td>
                                                    </tr>";
                                            }
                                            
                                            echo "<tr>
                                                    <td></td>
                                                    <td><code>".htmlspecialchars($permission["nom"])."</code></td>
                                                    <td>".htmlspecialchars($permission["description"])."</td>
                                                    <td><span class='badge bg-secondary'>".htmlspecialchars($permission["action"])."</span></td>
                                                </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Ajouter Rôle -->
            <form method="post" action="">
                <div class="modal fade" id="modalAjouterRole" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Ajouter un rôle</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nom du rôle *</label>
                                    <input type="text" name="nom" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="ajouterRole" class="btn btn-primary">Créer</button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Modal Modifier Rôle -->
            <form method="post" action="">
                <div class="modal fade" id="modalModifierRole" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modifier le rôle</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_role">
                                <div class="mb-3">
                                    <label class="form-label">Nom du rôle *</label>
                                    <input type="text" name="nom" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="modifierRole" class="btn btn-primary">Sauvegarder</button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Modal Gérer Permissions -->
            <form method="post" action="">
                <div class="modal fade" id="modalPermissions" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Gérer les permissions - <span id="role-name"></span></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                                <input type="hidden" name="role_id">
                                <div id="permissions-list">
                                    <!-- Les permissions seront chargées ici via JavaScript -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="updatePermissions" class="btn btn-primary">Sauvegarder</button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
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
    // Modifier un rôle
    $('.modifierRole').click(function(){
        $("#modalModifierRole input[name='nom']").val($(this).data('nom'));
        $("#modalModifierRole textarea[name='description']").val($(this).data('description'));
        $("#modalModifierRole input[name='id_role']").val($(this).data('id'));
        $('#modalModifierRole').modal('show');
    });

    // Gérer les permissions
    $('.gererPermissions').click(function(){
        var roleId = $(this).data('id');
        var roleName = $(this).data('nom');
        console.log("ROLE ID ", roleId);
        console.log("ROLE NAME ", roleName);
        $("#modalPermissions input[name='role_id']").val(roleId);
        $("#role-name").text(roleName);
        
        // Charger les permissions via AJAX
        $.ajax({
            url: 'ajax/get_permissions.php',
            type: 'POST',
            data: {role_id: roleId},
            success: function(response) {
                $('#permissions-list').html(response);
                $('#modalPermissions').modal('show');
            }
        });
    });

    // Gestion de la suppression
    $('.btn-supprimer').click(function() {
        var id = $(this).data('id');
        var type = $(this).data('type');
        
        if (confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
            var form = $('<form method="post" action=""></form>');
            form.append('<input type="hidden" name="id_' + type + '" value="' + id + '">');
            form.append('<input type="hidden" name="supprimer' + type.charAt(0).toUpperCase() + type.slice(1) + '" value="1">');
            $('body').append(form);
            form.submit();
        }
    });
    </script>

</body>
</html>