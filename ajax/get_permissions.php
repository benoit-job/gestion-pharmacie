<?php
// Fichier: ajax/get_permissions.php

// Inclure les fichiers nécessaires dans le bon ordre
include_once("../includes/connexion_bdd.php");      // Pour $bdd
include_once("../includes/fonctions.php");          // Fonctions générales
include_once("../includes/auth_functions.php");     // Pour hasPermission()

// Démarrer la session si ce n'est pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier que l'utilisateur a les droits admin.roles
if (!hasPermission('admin.roles')) {
    http_response_code(403);
    exit('Accès non autorisé');
}

if (isset($_POST['role_id'])) {
    $role_id = crypt_decrypt_chaine($_POST['role_id'], 'D');
    
    // Validation de l'ID du rôle
    if (!$role_id || !is_numeric($role_id)) {
        http_response_code(400);
        exit('ID de rôle invalide');
    }
    
    // Récupérer toutes les permissions disponibles
    $query_all = "SELECT * FROM permissions WHERE statut = 'actif' ORDER BY module, action";
    $result_all = mysqli_query($bdd, $query_all);
    
    if (!$result_all) {
        http_response_code(500);
        exit('Erreur lors de la récupération des permissions');
    }
    
    // Récupérer les permissions actuelles du rôle
    $query_role = "SELECT permission_id FROM role_permissions WHERE role_id = ?";
    $stmt = mysqli_prepare($bdd, $query_role);
    
    if (!$stmt) {
        http_response_code(500);
        exit('Erreur de préparation de la requête');
    }
    
    mysqli_stmt_bind_param($stmt, "i", $role_id);
    mysqli_stmt_execute($stmt);
    $result_role = mysqli_stmt_get_result($stmt);
    
    $current_permissions = [];
    while ($row = mysqli_fetch_assoc($result_role)) {
        $current_permissions[] = $row['permission_id'];
    }
    
    // Grouper les permissions par module
    $permissions_by_module = [];
    while ($permission = mysqli_fetch_assoc($result_all)) {
        $permissions_by_module[$permission['module']][] = $permission;
    }
    
    // Générer le HTML
    echo '<div class="permissions-container">';
    
    foreach ($permissions_by_module as $module => $permissions) {
        echo '<div class="module-section mb-4">';
        echo '<h6 class="fw-bold text-primary border-bottom pb-2">' . strtoupper(htmlspecialchars($module)) . '</h6>';
        echo '<div class="row">';
        
        foreach ($permissions as $permission) {
            $checked = in_array($permission['id'], $current_permissions) ? 'checked' : '';
            $badge_color = getActionBadgeColor($permission['action']);
            
            echo '<div class="col-md-6 mb-2">';
            echo '<div class="form-check">';
            echo '<input class="form-check-input" type="checkbox" name="permissions[]" 
                    value="' . intval($permission['id']) . '" id="perm_' . intval($permission['id']) . '" ' . $checked . '>';
            echo '<label class="form-check-label small" for="perm_' . intval($permission['id']) . '">';
            echo '<span class="badge bg-' . $badge_color . ' me-2">' . htmlspecialchars($permission['action']) . '</span>';
            echo htmlspecialchars($permission['description']);
            echo '</label>';
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
    
    // JavaScript pour sélectionner/désélectionner tout
    echo '<script>
    // Ajouter des boutons de sélection rapide
    $(".module-section").each(function() {
        var moduleSection = $(this);
        var moduleTitle = moduleSection.find("h6");
        var selectAllBtn = $("<button type=\"button\" class=\"btn btn-outline-primary btn-sm ms-2\">Tout sélectionner</button>");
        var deselectAllBtn = $("<button type=\"button\" class=\"btn btn-outline-secondary btn-sm ms-1\">Tout désélectionner</button>");
        
        selectAllBtn.click(function() {
            moduleSection.find("input[type=checkbox]").prop("checked", true);
        });
        
        deselectAllBtn.click(function() {
            moduleSection.find("input[type=checkbox]").prop("checked", false);
        });
        
        moduleTitle.append(selectAllBtn).append(deselectAllBtn);
    });
    </script>';
    
} else {
    http_response_code(400);
    exit('Paramètre role_id manquant');
}

function getActionBadgeColor($action) {
    switch (strtolower($action)) {
        case 'create': return 'success';
        case 'read': return 'info';
        case 'update': return 'warning';
        case 'delete': return 'danger';
        case 'export': return 'secondary';
        case 'all': return 'primary';
        default: return 'light';
    }
}
?>