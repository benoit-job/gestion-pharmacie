<?php
// Fichier: includes/auth_functions.php
require_once __DIR__ . '/connexion_bdd.php';
/**
 * Vérifier si l'utilisateur connecté a une permission spécifique
 */
function hasPermission($permission_name, $bdd = null) {
    global $bdd;

    // Validate database connection
    if (!($bdd instanceof mysqli)) {
        if (!isset($GLOBALS['bdd']) || !($GLOBALS['bdd'] instanceof mysqli)) {
            throw new RuntimeException("Database connection is not available");
        }
        $bdd = $GLOBALS['bdd'];
    }

    if (!isset($_SESSION['user']) || empty($_SESSION['user']['role_id'])) {
        return false;
    }

    // Autoriser tout pour super admin (role_id = 4)
    if ($_SESSION['user']['role_id'] == 4) {
        return true;
    }

    $role_id = intval($_SESSION['user']['role_id']);
    $permission_name = mysqli_real_escape_string($bdd, $permission_name);

    $query = "SELECT COUNT(*) AS count 
              FROM role_permissions rp
              INNER JOIN permissions p ON rp.permission_id = p.id
              WHERE rp.role_id = $role_id 
              AND p.nom = '$permission_name'
              AND p.statut = 'actif'";

    $result = mysqli_query($bdd, $query);
    if (!$result) {
        error_log("Permission query failed: " . mysqli_error($bdd));
        return false;
    }

    $row = mysqli_fetch_assoc($result);
    return $row['count'] > 0;
}

/**
 * Vérifier si l'utilisateur a accès à un module complet
 */
function hasModuleAccess($module_name, $bdd = null) {
    global $bdd;

    // Validate database connection
    if (!($bdd instanceof mysqli)) {
        if (!isset($GLOBALS['bdd']) || !($GLOBALS['bdd'] instanceof mysqli)) {
            throw new RuntimeException("Database connection is not available");
        }
        $bdd = $GLOBALS['bdd'];
    }

    if (!isset($_SESSION['user']) || empty($_SESSION['user']['role_id'])) {
        return false;
    }

    $role_id = intval($_SESSION['user']['role_id']);
    $module_name = mysqli_real_escape_string($bdd, $module_name);

    $query = "SELECT COUNT(*) AS count 
              FROM role_permissions rp
              INNER JOIN permissions p ON rp.permission_id = p.id
              WHERE rp.role_id = $role_id 
              AND p.module = '$module_name'
              AND p.statut = 'actif'";

    $result = mysqli_query($bdd, $query);
    if (!$result) {
        error_log("Module access query failed: " . mysqli_error($bdd));
        return false;
    }

    $row = mysqli_fetch_assoc($result);
    return $row['count'] > 0;
}

/**
 * Obtenir toutes les permissions d'un utilisateur
 */
function getUserPermissions($user_id = null, $bdd = null) {
    global $bdd;

    // Validate database connection
    if (!($bdd instanceof mysqli)) {
        if (!isset($GLOBALS['bdd']) || !($GLOBALS['bdd'] instanceof mysqli)) {
            throw new RuntimeException("Database connection is not available");
        }
        $bdd = $GLOBALS['bdd'];
    }

    if (!$user_id && isset($_SESSION['user'])) {
        $user_id = $_SESSION['user']['id'];
    }

    if (!$user_id) {
        return [];
    }

    $user_id = intval($user_id);

    $query = "SELECT p.nom, p.module, p.action 
              FROM users u
              INNER JOIN roles r ON u.role_id = r.id
              INNER JOIN role_permissions rp ON r.id = rp.role_id
              INNER JOIN permissions p ON rp.permission_id = p.id
              WHERE u.id = $user_id 
              AND u.statut = 'actif' 
              AND r.statut = 'actif' 
              AND p.statut = 'actif'";

    $result = mysqli_query($bdd, $query);
    if (!$result) {
        error_log("Get user permissions query failed: " . mysqli_error($bdd));
        return [];
    }

    $permissions = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $permissions[] = $row;
    }

    return $permissions;
}

/**
 * Vérifier si l'utilisateur est super admin
 */
function isSuperAdmin() {
    return isset($_SESSION['user']['role_id']) && $_SESSION['user']['role_id'] == 4;
}

/**
 * Rediriger si pas de permission
 */
function requirePermission($permission_name, $redirect_url = 'accueil.php') {
    if (!hasPermission($permission_name)) {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires.'
        ];
        header('Location: ' . $redirect_url);
        exit();
    }
}

/**
 * Mettre à jour les informations de l'utilisateur en session
 */
function refreshUserSession($user_id, $bdd = null) {
    global $bdd;

    // Validate database connection
    if (!($bdd instanceof mysqli)) {
        if (!isset($GLOBALS['bdd']) || !($GLOBALS['bdd'] instanceof mysqli)) {
            throw new RuntimeException("Database connection is not available");
        }
        $bdd = $GLOBALS['bdd'];
    }

    $user_id = intval($user_id);

    $query = "SELECT u.*, r.nom as role_name 
              FROM users u 
              LEFT JOIN roles r ON u.role_id = r.id 
              WHERE u.id = $user_id AND u.statut = 'actif'";

    $result = mysqli_query($bdd, $query);
    if (!$result) {
        error_log("Refresh user session query failed: " . mysqli_error($bdd));
        return false;
    }

    if ($user = mysqli_fetch_assoc($result)) {
        $_SESSION['user'] = $user;
        $_SESSION['user_permissions'] = getUserPermissions($user_id, $bdd);
        return true;
    }

    return false;
}


/**
 * Vérifier si un utilisateur peut accéder à une page
 */
function checkPageAccess($page_name) {
    $page_permissions = [
        'liste_souscripteurs.php' => 'souscripteurs.read',
        'add_souscripteur.php' => 'souscripteurs.create',
        'souscripteurs_a_jour.php' => 'souscripteurs.read',
        'sans_versement.php' => 'souscripteurs.read',
        'versements_partiels.php' => 'souscripteurs.read',
        'add_versement.php' => 'versements.create',
        'historique_versements.php' => 'versements.read',
        'modif_versements.php' => 'versements.update',
        'suivi_versements.php' => 'versements.read',
        'statistiques_generales.php' => 'rapports.view',
        'repartition_genre.php' => 'rapports.view',
        'repartition_region.php' => 'rapports.view',
        'tableau_croise.php' => 'rapports.advanced',
        'regions_pharma.php' => 'geographie.read',
        'lieux_exercice.php' => 'geographie.read',
        'cartographie.php' => 'geographie.read',
        'utilisateurs.php' => 'admin.users',
        'roles_permissions.php' => 'admin.roles',
        'sauvegardes.php' => 'admin.backup',
        'accueil.php' => 'dashboard.view'
    ];

    if (isset($page_permissions[$page_name])) {
        return hasPermission($page_permissions[$page_name]);
    }

    return true;
}

/**
 * Générer un message d'erreur de permission personnalisé
 */
function getPermissionErrorMessage($permission_name) {
    $messages = [
        'souscripteurs.create' => 'Vous n\'êtes pas autorisé à créer de nouveaux souscripteurs.',
        'souscripteurs.update' => 'Vous n\'êtes pas autorisé à modifier les souscripteurs.',
        'souscripteurs.delete' => 'Vous n\'êtes pas autorisé à supprimer des souscripteurs.',
        'versements.create' => 'Vous n\'êtes pas autorisé à créer de nouveaux versements.',
        'versements.update' => 'Vous n\'êtes pas autorisé à modifier les versements.',
        'versements.delete' => 'Vous n\'êtes pas autorisé à supprimer des versements.',
        'admin.users' => 'Vous n\'avez pas accès à la gestion des utilisateurs.',
        'admin.roles' => 'Vous n\'avez pas accès à la gestion des rôles et permissions.',
        'rapports.advanced' => 'Vous n\'avez pas accès aux rapports avancés.'
    ];

    return isset($messages[$permission_name]) 
        ? $messages[$permission_name] 
        : 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires.';
}

/**
 * Logger les actions des utilisateurs
 */
function logUserActivity($action, $details = '', $bdd = null) {
    global $bdd;

    if (!isset($_SESSION['user'])) {
        return false;
    }

    $user_id = intval($_SESSION['user']['id']);
    $ip_address = mysqli_real_escape_string($bdd, $_SERVER['REMOTE_ADDR'] ?? '');
    $user_agent = mysqli_real_escape_string($bdd, $_SERVER['HTTP_USER_AGENT'] ?? '');
    $action = mysqli_real_escape_string($bdd, $action);
    $details = mysqli_real_escape_string($bdd, $details);

    $query = "INSERT INTO user_logs (user_id, action, details, ip_address, user_agent, date_action) 
              VALUES ($user_id, '$action', '$details', '$ip_address', '$user_agent', NOW())";

    return mysqli_query($bdd, $query);
}
?>
