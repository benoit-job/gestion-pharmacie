<?php
include("includes/connexion_acces_page.php");
include("includes/connexion_bdd.php");
include("includes/fonctions.php");

// Traitement du formulaire
if(isset($_POST["modifierProfil"])) {
    $pseudo = strip_tags(htmlspecialchars(trim($_POST["pseudo"])));
    $email = strip_tags(htmlspecialchars(trim($_POST["email"])));
    $telephone = strip_tags(htmlspecialchars(trim($_POST["telephone"])));
    
    // Traitement de l'image
    if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $uploadDir = 'fichiers/uploads/';
        if(!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = uniqid() . '_' . basename($_FILES['logo']['name']);
        $targetPath = $uploadDir . $fileName;
        
        // Vérification du type de fichier
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['logo']['tmp_name']);
        
        if(in_array($fileType, $allowedTypes)) {
            if(move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
                // Mise à jour de la logo dans la base de données
                $query = "UPDATE users SET logo = ? WHERE id = ?";
                $stmt = mysqli_prepare($bdd, $query);
                mysqli_stmt_bind_param($stmt, "si", $fileName, $_SESSION['user']['id']);
                mysqli_stmt_execute($stmt);
            }
        }
    }
    
    // Mise à jour des autres informations
    $query = "UPDATE users SET pseudo = ?, email = ?, telephone = ? WHERE id = ?";
    $stmt = mysqli_prepare($bdd, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $pseudo, $email, $telephone, $_SESSION['user']['id']);
    mysqli_stmt_execute($stmt);
    
    $_SESSION['toast'] = [
        'type' => 'success',
        'message' => 'Profil mis à jour avec succès'
    ];
    reload_current_page();
}

// Récupération des informations utilisateur
$query = "SELECT pseudo, email, telephone, logo FROM users WHERE id = ?";
$stmt = mysqli_prepare($bdd, $query);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user']['id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

echo $user['logo'];
?>

<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon Profil</title>
    <?php include('includes/php/includes-css.php');?>
    <style>
        :root {
            --primary-color: #6c5ce7;
            --secondary-color: #a29bfe;
            --accent-color: #fdcb6e;
        }
        
        .profile-card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: none;
            overflow: hidden;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 2rem;
            color: white;
            text-align: center;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            margin: 0 auto;
            display: block;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .profile-body {
            padding: 2rem;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 10px 25px;
        }
        
        .btn-primary:hover {
            background-color: #5649c0;
            border-color: #5649c0;
        }
        
        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-upload-label {
            display: block;
            padding: 15px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .file-upload-label:hover {
            border-color: var(--primary-color);
            background: rgba(108, 92, 231, 0.05);
        }
        
        .file-upload-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .preview-container {
            text-align: center;
            margin-top: 20px;
        }
        
        .image-preview {
            max-width: 100%;
            max-height: 100px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: none;
        }
        
        .drag-over {
            border-color: var(--primary-color);
            background-color: rgba(108, 92, 231, 0.1);
        }
    </style>
</head>

<body style="background-color: #f8f9fa;">

    <main class="main" id="top">
        <?php include('includes/php/menu.php');?>
        <?php include('includes/php/header.php');?>

        <div class="content">
            <div class="pb-5">
                <div class="mb-5">
                    <h2 class="mb-2" style="color: var(--primary-color);">Mon Profil</h2>
                    <h5 class="text-body-tertiary fw-semibold">Gérez vos informations personnelles</h5>
                </div>

                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="card profile-card">
                            <div class="profile-header">

                                <img src="<?php echo !empty($user['logo']) ? 'fichiers/uploads/'.$user['logo'] : 'assets/img/avatar-default.png'; ?>" 
                                     class="profile-avatar" id="avatarPreview">
                                <h4 class="mt-3 mb-0"><?php echo htmlspecialchars($user['pseudo'] ?? ''); ?></h4>
                            </div>
                            
                            <div class="profile-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="mb-4">
                                        <h5 class="mb-3" style="color: var(--primary-color);">
                                            <i class="fas fa-camera me-2"></i>Photo de profil
                                        </h5>
                                        
                                        <div class="file-upload">
                                            <input type="file" id="photoInput" name="photo" class="file-upload-input" accept="image/*" capture="camera">
                                            <label for="photoInput" class="file-upload-label" id="dropZone">
                                                <div class="file-upload-icon">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                </div>
                                                <h6>Glissez-déposez votre photo ici</h6>
                                                <p class="text-muted">ou cliquez pour sélectionner</p>
                                                <small class="text-muted">Formats supportés: JPG, PNG, GIF (max 5MB)</small>
                                            </label>
                                        </div>
                                        
                                        <div class="preview-container mt-3">
                                            <img id="imagePreview" class="image-preview">
                                            <button type="button" id="removeImage" class="btn btn-outline-danger btn-sm mt-2" style="display: none;">
                                                <i class="fas fa-trash me-1"></i> Supprimer
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h5 class="mb-3" style="color: var(--primary-color);">
                                            <i class="fas fa-user-edit me-2"></i>Informations personnelles
                                        </h5>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Pseudo</label>
                                            <input type="text" name="pseudo" class="form-control" value="<?php echo htmlspecialchars($user['pseudo'] ?? ''); ?>" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Téléphone</label>
                                            <input type="tel" name="telephone" class="form-control" value="<?php echo htmlspecialchars($user['telephone'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" name="modifierProfil" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i> Enregistrer les modifications
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include('includes/php/footer.php');?>
    </main>

    <?php include('includes/php/includes-js.php');?>
    <script>
        $(document).ready(function() {
            // Gestion du glisser-déposer
            const dropZone = document.getElementById('dropZone');
            const photoInput = document.getElementById('photoInput');
            const imagePreview = document.getElementById('imagePreview');
            const avatarPreview = document.getElementById('avatarPreview');
            const removeImage = document.getElementById('removeImage');
            
            // Empêcher le comportement par défaut pour les événements de glisser
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            // Gestion des classes pendant le drag
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight() {
                dropZone.classList.add('drag-over');
            }
            
            function unhighlight() {
                dropZone.classList.remove('drag-over');
            }
            
            // Gestion du drop
            dropZone.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                photoInput.files = files;
                handleFiles(files);
            }
            
            // Gestion de la sélection de fichier
            photoInput.addEventListener('change', function() {
                handleFiles(this.files);
            });
            
            function handleFiles(files) {
                if (files.length) {
                    const file = files[0];
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreview.style.display = 'block';
                            removeImage.style.display = 'inline-block';
                            
                            // Mettre à jour aussi l'avatar preview
                            avatarPreview.src = e.target.result;
                        }
                        
                        reader.readAsDataURL(file);
                    }
                }
            }
            
            // Suppression de l'image
            removeImage.addEventListener('click', function() {
                photoInput.value = '';
                imagePreview.src = '';
                imagePreview.style.display = 'none';
                removeImage.style.display = 'none';
                
                // Revenir à l'avatar par défaut
                avatarPreview.src = '<?php echo !empty($user['photo']) ? 'uploads/profils/'.$user['photo'] : 'assets/img/avatar-default.png'; ?>';
            });
        });
    </script>
</body>
</html>