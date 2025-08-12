<?php
  include("includes/connexion_acces_page.php");
  include("includes/connexion_bdd.php");
  include("includes/fonctions.php");
include_once("includes/auth_functions.php");   
  $url = "add_souscripteur.php.php";
?>


<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Nouveau souscripteur</title>

    <?php include('includes/php/includes-css.php');?>
    
<style>
.image-upload-container {
    transition: all 0.3s ease;
}

.image-preview-frame:hover {
    border-color: #86b7fe !important;
}

.image-preview-frame.highlight {
    border: 2px dashed #0d6efd;
    background-color: #e9f5ff;
}

/* Style pour le message par défaut */
#default_icon {
    transition: all 0.3s ease;
}
</style>
  </head>


  <body>

    <main class="main" id="top">
    	
      <?php include('includes/php/menu.php');?>

      <?php include('includes/php/header.php');?>

      <div class="content">

        <div class="pb-3">
            <div class="mb-8">
              <h3 class="mb-2">Nouveau souscripteur</h3>
              <h5 class="text-body-tertiary fw-semibold">Ajouter un souscripteur</h5>
            </div>

            <div class="page-section">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Nouveau Souscripteur</h5>
                    </div>
                    <form id="form-nouveau-souscripteur" method="POST">
                        <div class="card-body">
                            <!-- Section 1: Informations Personnelles (Repliable) -->
                            <div class="accordion mb-4" id="accordionInfosPerso">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInfosPerso" aria-expanded="true">
                                            <i class="fas fa-id-card me-2"></i> Informations Personnelles
                                        </button>
                                    </h2>
                                    <div id="collapseInfosPerso" class="accordion-collapse collapse show" data-bs-parent="#accordionInfosPerso">
                                        <div class="accordion-body">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Type Souscripteur <strong class="text-danger">*</strong></label>
                                                    <select class="form-select organizerSingle" id="id_type_souscripteur" name="id_type_souscripteur" required>
                                                        <option disabled selected>Choisissez ...</option> 
                                                        <?php
                                                        $query = "SELECT id, UPPER(nom) AS nom FROM type_souscripteurs WHERE active = 'oui' ORDER BY type_souscripteurs.nom ASC";
                                                        $resultat = mysqli_query($bdd, $query) or die("Erreur SQL");
                                                        while ($region = mysqli_fetch_assoc($resultat)) {
                                                            echo "<option value='" . htmlspecialchars($region['id']) . "'>" . htmlspecialchars($region['nom']) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Civilité <strong class="text-danger">*</strong></label>
                                                    <select class="form-select organizerSingle" id="organizerSingle" name="civilite" required>
                                                        <option value="" disabled selected>Sélectionnez une civilité...</option>
                                                        <option value="M.">Monsieur (M.)</option>
                                                        <option value="Mme">Madame (Mme)</option>
                                                        <option value="Mlle">Mademoiselle (Mlle)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Nom <strong class="text-danger">*</strong></label>
                                                    <input type="text" class="form-control" name="nom" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Prénom <strong class="text-danger">*</strong></label>
                                                    <input type="text" class="form-control" name="prenom" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Date de Naissance</label>
                                                    <input type="date" class="form-control" name="date_naissance">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Lieu de Naissance</label>
                                                    <input type="text" class="form-control" name="lieu_naissance">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Coordonnées (Repliable) -->
                            <div class="accordion mb-4" id="accordionCoordonnees">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCoordonnees">
                                            <i class="fas fa-address-book me-2"></i> Coordonnées
                                        </button>
                                    </h2>
                                    <div id="collapseCoordonnees" class="accordion-collapse collapse" data-bs-parent="#accordionCoordonnees">
                                        <div class="accordion-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Adresse <strong class="text-danger">*</strong></label>
                                                    <input type="text" class="form-control" name="adresse" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Code postal</label>
                                                    <input type="text" class="form-control" name="complement_adresse">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Nationalité <strong class="text-danger">*</strong></label>
                                                    <input type="text" class="form-control" name="nationalite" required>
                                                </div>
                                                <!-- <div class="col-md-4">
                                                    <label class="form-label">Région <strong class="text-danger">*</strong></label>
                                                    <select class="form-select organizerSingle" id="id_region" name="id_region" required>
                                                        <option disabled selected>Choisissez ...</option> 
                                                        <?php
                                                        $query = "SELECT id, UPPER(nom_region) AS nom_region FROM regions WHERE active = 'oui' ORDER BY nom_region";
                                                        $resultat = mysqli_query($bdd, $query) or die("Erreur SQL");
                                                        while ($region = mysqli_fetch_assoc($resultat)) {
                                                            echo "<option value='" . htmlspecialchars($region['id']) . "'>" . htmlspecialchars($region['nom_region']) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div> -->
                                                <div class="col-md-4">
                                                    <label class="form-label">Lieu d'exercice <strong class="text-danger">*</strong></label>
                                                    <select class="form-select organizerSingle" id="id_lieu_exercice" name="id_lieu_exercice" required>
                                                        <option disabled selected>Choisissez ...</option> 
                                                        <?php
                                                        $query = "SELECT id, UPPER(nom_lieu) AS nom_lieu FROM lieu_exercices WHERE active = 'oui' ORDER BY lieu_exercices.nom_lieu";
                                                        $resultat = mysqli_query($bdd, $query) or die("Erreur SQL");
                                                        while ($lieu_exercice = mysqli_fetch_assoc($resultat)) {
                                                            echo "<option value='" . htmlspecialchars($lieu_exercice['id']) . "'>" . htmlspecialchars($lieu_exercice['nom_lieu']) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Téléphone fixe</label>
                                                    <input type="tel" class="form-control" name="telephone_fixe">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Téléphone portable <strong class="text-danger">*</strong></label>
                                                    <input type="tel" class="form-control" name="telephone_portable" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="email">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Profession (Repliable) -->
                            <div class="accordion mb-4" id="accordionProfession">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProfession">
                                            <i class="fas fa-briefcase me-2"></i> Souscription – Établissement
                                        </button>
                                    </h2>
                                    <div id="collapseProfession" class="accordion-collapse collapse" data-bs-parent="#accordionProfession">
                                        <div class="accordion-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Nom de l'établissement <strong class="text-danger">*</strong></label>
                                                    <input type="text" class="form-control" name="nom_etablissement" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Secteur d'activité <strong class="text-danger">*</strong></label>
                                                    <select class="form-select organizerSingle" id="secteur_activite" name="secteur_activite" required>
                                                        <option value="" disabled selected>Sélectionnez le secteur ...</option>
                                                        <!-- Secteur public -->
                                                        <option value="pharmacie hospitaliere publique">Pharmacie hospitalière publique</option>
                                                        <option value="pharmacie sante publique">Pharmacie de la Santé Publique (PSP)</option>
                                                        <option value="programme national">Programme national (VIH, palu, tuberculose…)</option>
                                                        <!-- Secteur privé -->
                                                        <option value="pharmacie officine">Pharmacie d'officine (privée)</option>
                                                        <option value="grossiste repartiteur">Grossiste répartiteur privé</option>
                                                        <option value="parapharmacie">Parapharmacie</option>
                                                        <option value="pharmacie privee hospitaliere">Pharmacie hospitalière privée (clinique)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Montant souscrit (FCFA)</label>
                                                    <input type="number" class="form-control" name="montant_souscrit">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Montant souscrit type 1 (FCFA)</label>
                                                    <input type="number" class="form-control" name="montant_souscrit_type1">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Montant souscrit type 2 (FCFA)</label>
                                                    <input type="number" class="form-control" name="montant_souscrit_type2">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Nombre d'actions</label>
                                                    <input type="number" class="form-control" name="nombre_actions">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Numéro de souscription </label>
                                                    <input type="number" class="form-control" name="n_souscription" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Date de souscription <strong class="text-danger">*</strong></label>
                                                    <input type="date" class="form-control" name="date_souscription" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 4: Documents (Repliable) -->
                            <div class="accordion mb-4" id="accordionDocuments">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDocuments">
                                            <i class="fas fa-file-upload me-2"></i> Image Établissement
                                        </button>
                                    </h2>
                                    <div id="collapseDocuments" class="accordion-collapse collapse" data-bs-parent="#accordionDocuments">
                                        <div class="accordion-body">
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <div class="text-center mt-2 mb-3">
                                                        <label class="form-label d-block mb-2">Image établissement (Optionnel)</label>
                                                        
                                                        <div class="image-upload-container" style="max-width: 300px; margin: 0 auto;">
                                                            <!-- Cadre cliquable avec position relative -->
                                                            <div class="image-preview-frame border rounded p-2 mb-2 position-relative" 
                                                                style="height: 200px; cursor: pointer; overflow: hidden; background-color: #f8f9fa;"
                                                                onclick="handleImageClick()">
                                                                
                                                                <!-- Aperçu de l'image qui remplit tout le cadre -->
                                                                <img id="image_preview" src="" alt="Aperçu" 
                                                                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; display: none;">
                                                                
                                                                <!-- Message par défaut centré qui disparaît COMPLÈTEMENT quand une image est sélectionnée -->
                                                                <div id="default_icon" class="h-100 d-flex flex-column align-items-center justify-content-center">
                                                                    <i class="fas fa-camera fa-3x text-secondary mb-2"></i>
                                                                    <p class="mb-0 text-center">Cliquez pour ajouter une image</p>
                                                                    <small class="text-muted">(Glisser-déposer possible)</small>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Champ fichier caché -->
                                                            <input type="file" id="image_etablissement" name="image_etablissement" 
                                                                class="d-none" accept="image/*" onchange="previewImage(this)">
                                                            
                                                            <!-- Boutons d'action -->
                                                            <div class="d-flex justify-content-center gap-2 mt-2">
                                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="handleUploadClick()">
                                                                    <i class="fas fa-upload me-1"></i> Télécharger
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-outline-primary" id="cameraBtn" onclick="handleCameraClick()">
                                                                    <i class="fas fa-camera me-1"></i> Prendre photo
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="resetImage()" id="resetBtn" style="display: none;">
                                                                    <i class="fas fa-trash me-1"></i> Supprimer
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bouton d'enregistrement global -->
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-outline-secondary me-3" id="btn-annuler-tout">
                                    <i class="fas fa-eraser me-2"></i> Tout Annuler
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Enregistrer le Souscripteur
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


        </div>

        <!-- Modal Structure -->


        <?php include('includes/php/footer.php');?>

      </div>
      
    </main>

    <?php include('includes/php/includes-js.php');?>
<!-- LIENS À METTRE DANS LE <head> -->


  </body>

</html>

<script>
    // Détection appareil mobile
    function isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    // Gestion du clic principal
    function handleImageClick() {
        if (isMobileDevice()) {
            showActionDialog();
        } else {
            document.getElementById('image_etablissement').click();
        }
    }

    // Gestion du clic sur "Télécharger"
    function handleUploadClick() {
        const input = document.getElementById('image_etablissement');
        input.removeAttribute('capture');
        input.click();
    }

    // Gestion du clic sur "Prendre photo"
    function handleCameraClick() {
        if (isMobileDevice()) {
            const input = document.getElementById('image_etablissement');
            input.setAttribute('capture', 'environment');
            input.click();
        } else {
            alert("La capture photo directe n'est disponible que sur mobile. Veuillez sélectionner une image.");
            document.getElementById('image_etablissement').click();
        }
    }

    // Afficher un dialogue de choix sur mobile
    function showActionDialog() {
        if (confirm("Voulez-vous :\n\n1. Prendre une photo (Appuyez sur OK)\n2. Choisir depuis la galerie (Appuyez sur Annuler)")) {
            handleCameraClick();
        } else {
            handleUploadClick();
        }
    }

    // Aperçu de l'image
    function previewImage(input) {
        const preview = document.getElementById('image_preview');
        const defaultIcon = document.getElementById('default_icon');
        const resetBtn = document.getElementById('resetBtn');
        const frame = document.querySelector('.image-preview-frame');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                defaultIcon.style.display = 'none'; // Cache COMPLÈTEMENT le message
                frame.style.backgroundColor = 'transparent'; // Retire le fond gris
                resetBtn.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Réinitialiser l'image
    function resetImage() {
        const input = document.getElementById('image_etablissement');
        const preview = document.getElementById('image_preview');
        const defaultIcon = document.getElementById('default_icon');
        const resetBtn = document.getElementById('resetBtn');
        const frame = document.querySelector('.image-preview-frame');
        
        input.value = '';
        preview.src = '';
        preview.style.display = 'none';
        defaultIcon.style.display = 'flex'; // Réaffiche le message
        frame.style.backgroundColor = '#f8f9fa'; // Remet le fond gris
        resetBtn.style.display = 'none';
    }

    // Glisser-déposer
    const dropArea = document.querySelector('.image-preview-frame');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropArea.classList.add('border-primary');
        dropArea.style.backgroundColor = '#e9f5ff';
    }

    function unhighlight() {
        dropArea.classList.remove('border-primary');
        dropArea.style.backgroundColor = '#f8f9fa';
    }

    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        const input = document.getElementById('image_etablissement');
        
        input.files = files;
        previewImage(input);
    }
</script>

<script>
    $(document).ready(function() {
    $('#form-nouveau-souscripteur').on('submit', function(e) {
        e.preventDefault();

        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="fas fa-spinner fa-spin me-2"></span>Insertion en cours...');

        let formData = new FormData(this);

        // On laisse tourner le spinner pendant 500 ms avant l'envoi
        setTimeout(function() {
        $.ajax({
            url: 'ajax/insertInto.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
            console.log("Réponse serveur:", response);
            if (response.trim() === 'success') {
                showToast('success', 'Souscripteur enregistré avec succès');
                $('#form-nouveau-souscripteur')[0].reset();
                // Attendre 4 secondes avant de montrer la 2e notification
                setTimeout(function() {
                    showToast('success', 'Redirection en cours...');
                    // Attendre 1 seconde pour laisser le toast s'afficher avant de rediriger
                    setTimeout(function() {
                        window.location.href = 'liste_souscripteurs.php';
                    }, 1000);

                }, 4000);
            } else {
                showToast('error', 'Une erreur est survenue');
            }
            },
            error: function() {
            showToast('error', 'Erreur de connexion au serveur');
            },
            complete: function() {
            submitBtn.prop('disabled', false);
            submitBtn.html('<i class="fas fa-save me-2"></i> Enregistrer le Souscripteur');
            }
        });
        }, 2000); // 0,5 seconde de spinner avant l'envoi
    });
    });
</script>