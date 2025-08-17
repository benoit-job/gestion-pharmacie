<?php
session_start();
include("includes/connexion_bdd.php");
include("includes/fonctions.php");
include("includes/auth_functions.php");

if (isset($_POST['connexion'])) {
    $telephone = strip_tags(htmlspecialchars(trim($_POST["telephone"])));
    $password = strip_tags(htmlspecialchars(trim($_POST["password"])));

    $query = "SELECT u.*, r.nom as role_name, r.description as role_description
              FROM users u
              LEFT JOIN roles r ON u.role_id = r.id
              WHERE (u.telephone = ? OR u.email = ?) 
              AND u.password = ? 
              AND u.statut = 'actif' 
              AND (r.statut = 'actif' OR u.role_id IS NULL)
              LIMIT 1";
              
    $stmt = mysqli_prepare($bdd, $query);
    mysqli_stmt_bind_param($stmt, "sss", $telephone, $telephone, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $_SESSION['user'] = $user;

        $_SESSION['user_permissions'] = getUserPermissions($user['id'], $bdd);
        
        logUserActivity('index', 'Connexion réussie', $bdd);
        
        if (hasPermission('dashboard.view') || isSuperAdmin()) {
            echo "succes";
        } else {
            session_destroy();
            echo "failed !! Aucune permission accordée";
        }
    } else {
        // Logger la tentative de connexion échouée
        $query_log = "INSERT INTO login_attempts (telephone_email, ip_address, success, date_tentative) 
                      VALUES (?, ?, 0, NOW())";
        $stmt_log = mysqli_prepare($bdd, $query_log);
        if ($stmt_log) {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            mysqli_stmt_bind_param($stmt_log, "ss", $telephone, $ip);
            mysqli_stmt_execute($stmt_log);
        }
        
        echo "failed !! Identifiants incorrects";
    }
}
?>