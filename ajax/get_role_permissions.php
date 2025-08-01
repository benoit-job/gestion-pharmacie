<?php
include("../includes/connexion_bdd.php");

$role_id = intval($_GET['role_id']);
$query = "SELECT permission_id FROM role_permission WHERE role_id = $role_id";
$result = mysqli_query($bdd, $query);

$permissions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $permissions[] = $row['permission_id'];
}

header('Content-Type: application/json');
echo json_encode($permissions);
?>