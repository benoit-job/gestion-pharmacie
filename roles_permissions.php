<?php
include("includes/connexion_acces_page.php");
include("includes/connexion_bdd.php");
include("includes/fonctions.php");

// Vérifier la permission
if (!est_autorise('gerer_roles')) {
    header('Location: index.php');
    exit;
}

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ajouter_role'])) {
        $nom = mysqli_real_escape_string($bdd, $_POST['nom']);
        $description = mysqli_real_escape_string($bdd, $_POST['description']);
        $couleur = mysqli_real_escape_string($bdd, $_POST['couleur']);
        
        mysqli_query($bdd, "INSERT INTO roles (nom, description, couleur) VALUES ('$nom', '$description', '$couleur')");
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Rôle ajouté avec succès'];
    }
    
    if (isset($_POST['mettre_a_jour_permissions'])) {
        $role_id = intval($_POST['role_id']);
        $permissions = $_POST['permissions'] ?? [];
        
        // Supprimer les anciennes permissions
        mysqli_query($bdd, "DELETE FROM role_permission WHERE role_id = $role_id");
        
        // Ajouter les nouvelles permissions
        foreach ($permissions as $perm_id) {
            $perm_id = intval($perm_id);
            mysqli_query($bdd, "INSERT INTO role_permission (role_id, permission_id) VALUES ($role_id, $perm_id)");
        }
        
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Permissions mises à jour avec succès'];
    }
}

// Récupérer les rôles existants
$roles = mysqli_query($bdd, "SELECT r.*, 
                            (SELECT COUNT(*) FROM users WHERE role_id = r.id) as nb_utilisateurs
                            FROM roles r ORDER BY nom");

// Récupérer toutes les permissions disponibles
$permissions = mysqli_query($bdd, "SELECT * FROM permissions ORDER BY description");
?>

<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rôles & Permissions</title>

    <?php include('includes/php/includes-css.php');?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>

<body>
    <main class="main" id="top">
        <?php include('includes/php/menu.php');?>
        <?php include('includes/php/header.php');?>

        <div class="content">
            <div class="pb-3">
                <div class="mb-8">
                    <h2 class="mb-2">Gestion des rôles et permissions</h2>
                    <h5 class="text-body-tertiary fw-semibold">Configurez les accès de votre application</h5>
                </div>

                <!-- Message de notification -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?= $_SESSION['message']['type'] ?> alert-dismissible fade show" role="alert">
                        <?= $_SESSION['message']['text'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <div class="page-section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Liste des rôles</h5>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ajouterRoleModal">
                                <i class="fas fa-plus me-1"></i> Ajouter un rôle
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Rôle</th>
                                            <th>Description</th>
                                            <th>Utilisateurs</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($role = mysqli_fetch_assoc($roles)): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-<?= $role['couleur'] ?>">
                                                    <?= htmlspecialchars($role['nom']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($role['description']) ?></td>
                                            <td><?= $role['nb_utilisateurs'] ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary gerer-permissions" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#permissionsModal"
                                                        data-role-id="<?= $role['id'] ?>"
                                                        data-role-nom="<?= htmlspecialchars($role['nom']) ?>">
                                                    <i class="fas fa-shield-alt me-1"></i> Permissions
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('includes/php/footer.php');?>
        </div>
    </main>

    <!-- Modal Ajouter un rôle -->
    <div class="modal fade" id="ajouterRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un nouveau rôle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom du rôle</label>
                            <input type="text" class="form-control" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Couleur</label>
                            <select class="form-select" name="couleur">
                                <option value="primary">Bleu</option>
                                <option value="secondary">Gris</option>
                                <option value="success">Vert</option>
                                <option value="danger">Rouge</option>
                                <option value="warning">Jaune</option>
                                <option value="info">Cyan</option>
                                <option value="dark">Noir</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="ajouter_role" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Gérer les permissions -->
    <div class="modal fade" id="permissionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <input type="hidden" name="role_id" id="roleIdInput">
                    <div class="modal-header">
                        <h5 class="modal-title">Permissions pour <span id="roleNomDisplay"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="permissions-list">
                            <?php while($perm = mysqli_fetch_assoc($permissions)): ?>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[]" 
                                       value="<?= $perm['id'] ?>" 
                                       id="perm-<?= $perm['id'] ?>">
                                <label class="form-check-label" for="perm-<?= $perm['id'] ?>">
                                    <strong><?= htmlspecialchars($perm['description']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($perm['code']) ?></small>
                                </label>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="mettre_a_jour_permissions" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include('includes/php/includes-js.php');?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    $(document).ready(function() {
        // Gestion du modal des permissions
        $('#permissionsModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var roleId = button.data('role-id');
            var roleNom = button.data('role-nom');
            
            var modal = $(this);
            modal.find('#roleIdInput').val(roleId);
            modal.find('#roleNomDisplay').text(roleNom);
            
            // Charger les permissions actuelles via AJAX
            $.ajax({
                url: 'ajax/get_role_permissions.php',
                type: 'GET',
                data: { role_id: roleId },
                dataType: 'json',
                success: function(response) {
                    $('input[name="permissions[]"]').prop('checked', false);
                    $.each(response, function(index, permId) {
                        $('#perm-' + permId).prop('checked', true);
                    });
                }
            });
        });
        
        // Confirmation avant suppression
        $('.supprimer-role').click(function(e) {
            e.preventDefault();
            var roleId = $(this).data('role-id');
            var roleNom = $(this).data('role-nom');
            
            Swal.fire({
                title: 'Confirmer la suppression',
                html: `Voulez-vous vraiment supprimer le rôle <strong>${roleNom}</strong> ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                showClass: {
                    popup: 'animate__animated animate__fadeInUp animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutDown animate__faster'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'traiter_roles.php?action=supprimer&id=' + roleId;
                }
            });
        });
    });
    </script>
</body>
</html>