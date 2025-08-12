
<?php
session_start();

if (empty($_SESSION['user']) || $_SESSION['user']['statut'] != 'actif') {
    header('Location: index.php');
    exit();
}
?>