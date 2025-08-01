<?php
session_start();
include("includes/connexion_bdd.php");
include("includes/fonctions.php"); 

if (isset($_POST['connexion'])) {
    $telephone = strip_tags(htmlspecialchars(trim($_POST["telephone"])));
    $password = strip_tags(htmlspecialchars(trim($_POST["password"])));

    // Connexion utilisateur classique
    $query = "SELECT * FROM users
              WHERE (telephone = \"$telephone\" OR email = \"$telephone\") 
              AND password = \"$password\" 
              AND statut = 'actif' 
              LIMIT 1";
              
    $resultat = mysqli_query($bdd, $query) or die("Requête non conforme utilisateur");

    $_SESSION['user'] = mysqli_fetch_assoc($resultat);

    if (!empty($_SESSION['user'])) {
        echo "succes";
    } else {
        echo "failed !! incorrect";
    }
}

?>
