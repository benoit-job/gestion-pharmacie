<?php
  include("includes/connexion_acces_page.php");
  include("includes/connexion_bdd.php");
  include("includes/fonctions.php");
?>



<?php  
if(isset($_GET["utilisateur"])) 
{
    $_SESSION["utilisateurs"] = $_GET["utilisateur"];
}

if (isset($_POST['modifierMot_de_passe']) && hash_equals($_SESSION["utilisateur"]["mot_de_passe"], $_POST["ancien_mdp"])) 
{
    if($_POST["nouveau_mdp"] === $_POST["confirmer_mdp"]) 
    {
        $nouveau_mdp = strip_tags(htmlspecialchars(trim($_POST["nouveau_mdp"])));
        $query = "UPDATE utilisateurs
                    SET mot_de_passe = \"$nouveau_mdp\"
                    WHERE id_site = '".$_SESSION['site']['id']."' AND 
                            id=".$_SESSION["utilisateurs"];
        mysqli_query($bdd, $query) or die("Requête non conforme");
        echo "<script>alert('Mot de passe a été changé');</script>";
    } 
    else 
    {
        echo "<script>alert('Les mots de passe ne correspondent pas');</script>";
    }
}


if(isset($_POST['modifierInfo']))   
{
    $pseudo = strip_tags(htmlspecialchars(trim($_POST["pseudo"])));
    $contact = strip_tags(htmlspecialchars(trim($_POST["contact"])));

    $query = "UPDATE utilisateurs
              SET nom = \"$pseudo\",
                  telephone = \"$contact\"
              WHERE id_site ='".$_SESSION['site']['id']."' AND 
                    id =".$_SESSION["utilisateurs"];
    mysqli_query($bdd, $query) or die("Requête non conforme");
} 

 
$query = "SELECT * FROM utilisateurs WHERE id_site = ".$_SESSION['site']['id']." AND id=".$_SESSION["utilisateurs"];
$result = mysqli_query($bdd,$query) or die ("system error");
$_SESSION["utilisateur"] = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>

<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Mon Compte</title>

    <?php include('includes/php/includes-css.php');?>

  </head>

  <body>

    <main class="main" id="top">

      <?php include('includes/php/menu.php');?>

      <?php include('includes/php/header.php');?>

      <div class="content">

        <div>

          <div class="row g-4">

            <div class="col-12 col-xxl-6">

              <div class="mb-8">
                <h2 class="mb-2">Mon Compte</h2>
                <h5 class="text-body-tertiary fw-semibold">Gérer les information de vos Compte</h5>
              </div>

              <div class="page-section py-5">
                    <div class="container">
                        <div class="row justify-content-center">
                            <!-- Formulaire pour mettre à jour les informations -->
                            <div class="col-md-7 bg-white p-4 rounded-3 shadow-sm">
                                <h4 class="mb-4">Mettre à jour vos informations</h4>
                                <form action="mon_compte.php" method="post">
                                    <!-- Champ pour le nom -->
                                    <div class="mb-3">
                                        <label for="pseudo" class="form-label">Nom</label>
                                        <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?php echo $_SESSION['utilisateur']['nom']; ?>" required>
                                    </div>

                                    <!-- Champ pour le téléphone -->
                                    <div class="mb-3">
                                        <label for="contact" class="form-label">Téléphone</label>
                                        <input type="text" name="contact" id="contact" class="form-control" value="<?php echo $_SESSION['utilisateur']['telephone']; ?>" required>
                                    </div>

                                    <!-- Bouton de soumission -->
                                    <div>
                                        <button type="submit" class="btn btn-primary px-5" name="modifierInfo">Actualiser</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Formulaire pour réinitialiser le mot de passe -->
                            <div class="col-md-4 bg-white ms-3 rounded-3 shadow-sm p-4">
                                <h5 class="mb-4 ">Réinstaller Mot de Passe</h5>
                                <form action="mon_compte.php" method="post">
                                    <!-- Champ pour l'ancien mot de passe -->
                                    <div class="mb-3">
                                        <label for="ancien_mdp" class="form-label">Ancien mot de passe</label>
                                        <input type="password" name="ancien_mdp" id="ancien_mdp" class="form-control" required>
                                    </div>

                                    <!-- Champ pour le nouveau mot de passe -->
                                    <div class="mb-3">
                                        <label for="nouveau_mdp" class="form-label">Nouveau mot de passe</label>
                                        <input type="password" name="nouveau_mdp" id="nouveau_mdp" class="form-control" required>
                                    </div>

                                    <!-- Champ pour confirmer le nouveau mot de passe -->
                                    <div class="mb-3">
                                        <label for="confirmer_mdp" class="form-label">Confirmer mot de passe</label>
                                        <input type="password" name="confirmer_mdp" id="confirmer_mdp" class="form-control" required>
                                    </div>

                                    <!-- Bouton de soumission -->
                                    <div>
                                        <button type="submit" class="btn btn-primary px-5" name="modifierMot_de_passe">Changer mot de passe</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

              
            </div>

          </div>

        </div>

        <?php include('includes/php/footer.php');?>

      </div>

    </main>

    <?php include('includes/php/includes-js.php');?>

  </body>

</html>

