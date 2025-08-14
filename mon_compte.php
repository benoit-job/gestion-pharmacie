<?php
  include("includes/connexion_acces_page.php");
  include("includes/connexion_bdd.php");
  include("includes/fonctions.php");
include_once("includes/auth_functions.php");   
?>

<?php  
if(isset($_GET["utilisateur"])) 
{
    $_SESSION["user"]["id"] = $_GET["utilisateur"];
}

if (isset($_POST['modifierPassword'])) {
    // Vérification de l'ancien mot de passe
    if (!hash_equals($_SESSION["user"]["password"], $_POST["ancien_mdp"])) {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Ancien mot de passe incorrect'
        ];
    } 
    // Vérification de la correspondance des nouveaux mots de passe
    elseif ($_POST["nouveau_mdp"] !== $_POST["confirmer_mdp"]) {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Les nouveaux mots de passe ne correspondent pas'
        ];
    }
    // Vérification de la force du nouveau mot de passe
    elseif (strlen($_POST["nouveau_mdp"]) < 8) {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Le mot de passe doit contenir au moins 8 caractères'
        ];
    }
    // Toutes les vérifications sont passées
    else {
        $nouveau_mdp = strip_tags(htmlspecialchars(trim($_POST["nouveau_mdp"])));
        
        // Utilisation de requête préparée pour plus de sécurité
        $query = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = mysqli_prepare($bdd, $query);
        mysqli_stmt_bind_param($stmt, "si", $nouveau_mdp, $_SESSION["user"]["id"]);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => 'Mot de passe modifié avec succès',
                'duration' => 5000
            ];
            
            // Mise à jour du mot de passe en session
            $_SESSION["user"]["password"] = $nouveau_mdp;
        } else {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'Erreur lors de la modification du mot de passe',
                'duration' => 5000
            ];
        }
        
        mysqli_stmt_close($stmt);
    }
    
    // Redirection pour afficher les notifications
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
}

if(isset($_POST['modifierInfo']))   
{
    $pseudo = strip_tags(htmlspecialchars(trim($_POST["pseudo"])));
    $contact = strip_tags(htmlspecialchars(trim($_POST["contact"])));
    $email = strip_tags(htmlspecialchars(trim($_POST["email"])));
    
    // Gestion de l'upload d'image
    $logo = null;
    
    if(isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['logo']['tmp_name'];
        $fileName = basename($_FILES['logo']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Vérification des extensions autorisées
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if(in_array($fileExt, $allowedExtensions)) {
            
            // Vérification de la taille du fichier (5MB max)
            if($_FILES['logo']['size'] <= 5242880) {
                
                // Création du répertoire s'il n'existe pas
                if(!is_dir('fichiers/uploads/')) {
                    mkdir('fichiers/uploads/', 0755, true);
                }
                
                $destination = 'fichiers/uploads/'.uniqid('profile_').'.'.$fileExt;
                
                if(move_uploaded_file($fileTmp, $destination)) {
                    $logo = mysqli_real_escape_string($bdd, $destination);
                    
                    $_SESSION['toast'] = [
                        'type' => 'success',
                        'message' => 'Image de profil mise à jour avec succès'
                    ];
                } else {
                    $_SESSION['toast'] = [
                        'type' => 'error',
                        'message' => 'Erreur lors de l\'upload de l\'image'
                    ];
                }
            } else {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => 'L\'image est trop volumineuse (max 5MB)'
                ];
            }
        } else {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'Format d\'image non autorisé. Utilisez JPG, PNG ou GIF'
            ];
        }
    }

    // Construction de la requête
    if($logo !== null) {
        $query = "UPDATE users SET pseudo = ?, telephone = ?, email = ?, logo = ? WHERE id = ?";
        $stmt = mysqli_prepare($bdd, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", $pseudo, $contact, $email, $logo, $_SESSION["user"]["id"]);
    } else {
        $query = "UPDATE users SET pseudo = ?, telephone = ?, email = ? WHERE id = ?";
        $stmt = mysqli_prepare($bdd, $query);
        mysqli_stmt_bind_param($stmt, "sssi", $pseudo, $contact, $email, $_SESSION["user"]["id"]);
    }
    
    $result = mysqli_stmt_execute($stmt);
    
    if($result && !isset($_SESSION['toast'])) {
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Informations mises à jour avec succès'
        ];
    } elseif(!$result) {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Erreur lors de la mise à jour des informations'
        ];
    }
    
    mysqli_stmt_close($stmt);
    
    // Redirection pour afficher les notifications
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
}

$query = "SELECT * FROM users WHERE id=".$_SESSION["user"]["id"];
$result = mysqli_query($bdd,$query) or die ("system error");
$_SESSION["user"] = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>

<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Mon Compte</title>

    <?php include('includes/php/includes-css.php');?>

    <style>
        .profile-image-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
        }
        
        .profile-image-preview {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .profile-image-preview:hover {
            border-color: #007bff;
            opacity: 0.8;
        }
        
        .image-upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }
        
        .profile-image-container:hover .image-upload-overlay {
            opacity: 1;
        }
        
        .upload-icon {
            color: white;
            font-size: 24px;
        }
        
        .image-input {
            display: none;
        }
    </style>

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
                                
                                <!-- Section image de profil -->
                                <div class="profile-image-container">
                                    <img id="profileImagePreview" 
                                         src="<?php echo !empty($_SESSION["user"]['logo']) ? $_SESSION["user"]['logo'] : 'https://th.bing.com/th?id=OIP.fqSvfYQB0rQ-6EG_oqvonQHaHa&w=250&h=250&c=8&rs=1&qlt=90&o=6&pid=3.1&rm=2'; ?>" 
                                         alt="Photo de profil" 
                                         class="profile-image-preview"
                                         onclick="document.getElementById('imageInput').click()">
                                    <div class="image-upload-overlay" onclick="document.getElementById('imageInput').click()">
                                        <i class="fas fa-camera upload-icon"></i>
                                    </div>
                                </div>
                                
                                <form action="mon_compte.php" method="post" enctype="multipart/form-data">
                                    <!-- Champ caché pour l'image -->
                                    <input type="file" id="imageInput" name="logo" class="image-input" accept="image/*" onchange="previewImage(this)">
                                    
                                    <!-- Champ pour le nom -->
                                    <div class="mb-3">
                                        <label for="pseudo" class="form-label">Pseudo</label>
                                        <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?php echo $_SESSION["user"]['pseudo']; ?>" required>
                                    </div>

                                    <!-- Champ pour le téléphone -->
                                    <div class="mb-3">
                                        <label for="contact" class="form-label">Téléphone</label>
                                        <input type="text" name="contact" id="contact" class="form-control" value="<?php echo $_SESSION["user"]['telephone']; ?>" required>
                                    </div>

                                    <!-- Champ pour l'email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" value="<?php echo $_SESSION["user"]['email']; ?>" required>
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
                                        <button type="submit" class="btn btn-primary px-5" name="modifierPassword">Changer mot de passe</button>
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
    
    <script>
        function previewImage(input) {
            const preview = document.getElementById('profileImagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                
                reader.readAsDataURL(input.files[0]);
                
                // Validation côté client
                const file = input.files[0];
                const maxSize = 5 * 1024 * 1024; // 5MB
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                
                if (!allowedTypes.includes(file.type)) {
                    alert('Format non autorisé. Utilisez JPG, PNG ou GIF');
                    input.value = '';
                    return;
                }
                
                if (file.size > maxSize) {
                    alert('L\'image est trop volumineuse (max 5MB)');
                    input.value = '';
                    return;
                }
            }
        }
    </script>

  </body>

</html>