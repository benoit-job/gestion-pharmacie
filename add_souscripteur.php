<?php
  include("includes/connexion_acces_page.php");
  include("includes/connexion_bdd.php");
  include("includes/fonctions.php");
  $url = "regions_pharma.php";
?>


<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Nouveau souscripteur</title>

    <?php include('includes/php/includes-css.php');?>

  </head>


  <body>

    <main class="main" id="top">
    	
      <?php include('includes/php/menu.php');?>

      <?php include('includes/php/header.php');?>

      <div class="content">

        <div class="pb-3">
            <div class="mb-8">
              <h2 class="mb-2">Nouveau souscripteur</h2>
              <h5 class="text-body-tertiary fw-semibold">Ajouter un souscripteur</h5>
            </div>

            <div class="page-section">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Nouveau Souscripteur</h5>
                    </div>
                    <form id="form-nouveau-souscripteur" action="traitement_souscripteur.php" method="POST">
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
                                            <label class="form-label"> Type Souscripteur <strong class="text-danger">*</strong></label>
                                            <select class="form-select organizerSingle" id="id_region" name="id_region" required>
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
                                            <label class="form-label"> Civilité <strong class="text-danger">*</strong></label>
                                            <select class="form-select organizerSingle" id="organizerSingle" name="civilite" required>
                                                <option value="" disabled selected>Sélectionnez une civilité...</option>
                                                <option value="M.">Monsieur (M.)</option>
                                                <option value="Mme">Madame (Mme)</option>
                                                <option value="Mlle">Mademoiselle (Mlle)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label"> Nom <strong class="text-danger">*</strong></label>
                                            <input type="text" class="form-control" name="nom" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label"> Prénom <strong class="text-danger">*</strong></label>
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
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="button" class="btn btn-success btn-sauvegarder-section">
                                        <i class="fas fa-check me-1"></i> Sauvegarder Section
                                        </button>
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
                                                <label class="form-label">Complément d'adresse</label>
                                                <input type="text" class="form-control" name="complement_adresse">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nationalité <strong class="text-danger">*</strong></label>
                                                <input type="text" class="form-control" name="nationalite" required>
                                            </div>
                                            <div class="col-md-4">
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
                                            </div>
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
                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="button" class="btn btn-success btn-sauvegarder-section">
                                                <i class="fas fa-check me-1"></i> Sauvegarder Section
                                            </button>
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
                                                    <option value="pharmacie_hospitaliere_publique">Pharmacie hospitalière publique</option>
                                                    <option value="pharmacie_sante_publique">Pharmacie de la Santé Publique (PSP)</option>
                                                    <option value="programme_national">Programme national (VIH, palu, tuberculose…)</option>
                                                    <!-- Secteur privé -->
                                                    <option value="pharmacie_officine">Pharmacie d'officine (privée)</option>
                                                    <option value="grossiste_repartiteur">Grossiste répartiteur privé</option>
                                                    <option value="parapharmacie">Parapharmacie</option>
                                                    <option value="pharmacie_privee_hospitaliere">Pharmacie hospitalière privée (clinique)</option>
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
                                            <div class="col-md-6">
                                                <label class="form-label">Nombre d'actions</label>
                                                <input type="number" class="form-control" name="nombre_actions">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Date de souscription <strong class="text-danger">*</strong></label>
                                                <input type="date" class="form-control" name="date_souscription" required>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="button" class="btn btn-success btn-sauvegarder-section">
                                                <i class="fas fa-check me-1"></i> Sauvegarder Section
                                            </button>
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
                                <i class="fas fa-file-upload me-2"></i> DOCUMENTS ET AUTRES 
                                </button>
                            </h2>
                            <div id="collapseDocuments" class="accordion-collapse collapse" data-bs-parent="#accordionDocuments">
                                <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                    <label class="form-label">Pièce d'identité*</label>
                                    <input type="file" class="form-control" name="piece_identite" required>
                                    </div>
                                    <div class="col-md-6">
                                    <label class="form-label">Justificatif de domicile*</label>
                                    <input type="file" class="form-control" name="justificatif_domicile" required>
                                    </div>
                                    <div class="col-md-6">
                                    <label class="form-label">Autre document 1</label>
                                    <input type="file" class="form-control" name="autre_document1">
                                    </div>
                                    <div class="col-md-6">
                                    <label class="form-label">Autre document 2</label>
                                    <input type="file" class="form-control" name="autre_document2">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="button" class="btn btn-success btn-sauvegarder-section">
                                    <i class="fas fa-check me-1"></i> Sauvegarder Section
                                    </button>
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


