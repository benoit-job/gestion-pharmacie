<?php
  include("includes/connexion_acces_page.php");
  include("includes/connexion_bdd.php");
  include("includes/fonctions.php");
  $url = "souscripteurs_pharma.php";
?>
<?php
   //DELETE FROM

// Gestion de la suppression
if (isset($_POST["supprimerSouscripteur"])) {
    $success = supprimerSouscripteurAvecHistorique($bdd, $_POST["id_souscripteur"], $_SESSION['user']['id']);

    if ($success) {
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Souscripteur supprimé avec succès'];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Erreur lors de la suppression du souscripteur'];
    }

    reload_current_page();
}


?>


<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Liste des souscripteurs</title>

    <?php include('includes/php/includes-css.php');?>
 
<style>
        /* Animation de flottement */
    @keyframes floating {
        0%, 100% { transform: translateY(0) rotate(-15deg); }
        50% { transform: translateY(-8px) rotate(15deg); }
    }

    .animate-float {
        animation: floating 2.5s ease-in-out infinite;
    }

    /* Forme étoile avec clip-path */
    .excel-btn, .word-btn, .pdf-btn {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: all 0.4s ease;
        clip-path: polygon(
            50% 0%,
            61% 35%,
            98% 35%,
            68% 57%,
            79% 91%,
            50% 70%,
            21% 91%,
            32% 57%,
            2% 35%,
            39% 35%
        );
        cursor: pointer;
    }

    /* Couleurs */
    .excel-btn { background-color: #1d6f42; }
    .word-btn { background-color: #2b579a; }
    .pdf-btn { background-color: #e74c3c; }

    /* Effets au survol */
    .excel-btn:hover {
        background-color: #28a745;
        transform: scale(1.15) rotate(15deg);
        box-shadow: 0 8px 20px rgba(40, 167, 69, 0.6);
    }

    .word-btn:hover {
        background-color: #0078d7;
        transform: scale(1.15) rotate(15deg);
        box-shadow: 0 8px 20px rgba(0, 120, 215, 0.6);
    }

    .pdf-btn:hover {
        background-color: #ff6b5b;
        transform: scale(1.15) rotate(15deg);
        box-shadow: 0 8px 20px rgba(231, 76, 60, 0.6);
    }

    /* Désactive la flottement pendant le hover */
    .excel-btn:hover, .word-btn:hover, .pdf-btn:hover {
        animation: none;
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
              <h2 class="mb-2">Liste des souscripteurs</h2>
              <h5 class="text-body-tertiary fw-semibold">Gérer vos liste des souscripteurs </h5>
            </div>

            <div class="page-section">
                <div class="card card-fluid">
                    <div class="card-header border-0 p-1 d-flex justify-content-between align-items-center">
                        <!-- Boutons icônes à gauche -->
                        <div class="d-flex">
                            <!-- Bouton Excel (vert) -->
                            <button type="button" class="btn excel-btn rounded-circle me-2 animate-float">
                                <span class="fas fa-file-excel fa-lg" data-fa-transform="shrink-3"></span>
                            </button>
                            
                            <!-- Bouton Word (bleu) -->
                            <button type="button" class="btn word-btn rounded-circle me-2 animate-float">
                                <span class="fas fa-file-word fa-lg" data-fa-transform="shrink-3"></span>
                            </button>
                            
                            <!-- Bouton PDF (orange) -->
                            <button type="button" class="btn pdf-btn rounded-circle me-2 animate-float">
                                <span class="fas fa-file-pdf fa-lg" data-fa-transform="shrink-3"></span>
                            </button>
                        </div>
                        
                        <!-- Bouton "Créer nouveau" à droite -->
                        <a href="add_souscripteur.php" class="btn btn-phoenix-secondary rounded-pill">
                            <span class="fas fa-square-plus fa-lg me-2" data-fa-transform="shrink-3"></span>
                            Créer nouveau
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover m-0 usersTable" style="width:100%" data-title="Souscripteurs">
                            <thead class="thead-">
                            <tr style="font-size: 0.8rem;">
                                <th>NBRE SC</th>
                                <th>N° SOUSCRIPT</th>
                                <th class='hidden'>NOM</th>
                                <th class='hidden'>PRENOMS</th>
                                <th class='hidden'>SEXE</th>
                                <th class='no_export'>NOM & PRENOMS</th>
                                <th class='hidden'>NATIONNALITE</th>
                                <th class='hidden'>TELEPHONE FIXE</th>
                                <th class='no_export'>FIXE/PORTABLE</th>
                                <th class='hidden'>TELEPHONE PORTABLE</th>
                                <th class='hidden'>EMAIL</th>
                                <th class='hidden'>SECTEUR D 'ACTIVITE</th>
                                <th>NOM DE L’ETABLISSEMENT</th>
                                <th>LIEU D’EXERCICE</th>
                                <th class='hidden'>REGION PHARMACIE</th>
                                <th>DATE DE SOUCRIPTION</th>
                                <th>MONTANT SOUSCRIT</th>
                                <th class='hidden'>MONTANT SOUSCRIT TYPE 1</th>
                                <th class='hidden'>MONTANT SOUSCRIT TYPE 2</th>
                                <th class='hidden'>NOMBRE D 'ACTION</th>
                                <th class='no_export'>ACTIVE</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $query = "SELECT s.*,
                                                    UPPER(s.nom) AS nom_src,
                                                    UPPER(s.prenom) AS prenom_src,
                                                    UPPER(CONCAT_WS(' ', s.telephone_fixe, s.telephone_portable)) AS contacts,
                                                    UPPER(CONCAT_WS(' ', s.civilite, s.nom, s.prenom)) AS nom_complet,
                                                    UPPER(s.nom_etablissement) AS nom_etablissement,
                                                    DATE_FORMAT(s.date_souscription, '%d/%m/%Y') AS date_souscription,
                                                    UPPER(l.nom_lieu) AS lieu_exercice,
                                                    UPPER(r.nom_region) AS nom_region,
                                                    s.active
                                            FROM souscripteurs AS s
                                            LEFT JOIN lieu_exercices AS l ON l.id = s.id_lieu_exercice
                                            LEFT JOIN regions AS r ON r.id = l.id_region
                                            ORDER BY s.nom_etablissement";
                                    $resultat = mysqli_query($bdd, $query) or die("Erreur de requête");

                                    $ligne = 0;
                                    while($souscripteur = mysqli_fetch_array($resultat)) {                           
                                        $isActive = strtolower($souscripteur["active"]) === 'oui';
                                        $activeClass = $isActive ? 'bg-success' : '';
                                        $checkedAttr = $isActive ? 'checked' : '';
                                        
                                        echo "<tr>
                                                <td>".++$ligne."</td>
                                                <td>".htmlspecialchars($souscripteur["n_souscription"] ?? '')."</td>
                                                <td class='hidden'>".htmlspecialchars($souscripteur["nom_src"] ?? '')."</td>
                                                <td class='hidden'>".htmlspecialchars($souscripteur["prenom_src"] ?? '')."</td>
                                                <td class='hidden'>".ucfirst(htmlspecialchars($souscripteur["civilite"] ?? ''))."</td>
                                                <td class='no_export'>".htmlspecialchars($souscripteur["nom_complet"] ?? '')."</td>
                                                <td class='hidden'>".htmlspecialchars($souscripteur["nationalite"] ?? '')."</td>
                                                <td class='no_export'>".htmlspecialchars($souscripteur["contacts"] ?? '')."</td>
                                                <td class='hidden'> +225".htmlspecialchars($souscripteur["telephone_fixe"] ?? '')."</td>
                                                <td class='hidden'> +225".htmlspecialchars($souscripteur["telephone_portable"] ?? '')."</td>
                                                <td class='hidden'>".htmlspecialchars($souscripteur["email"] ?? '')."</td>
                                                <td class='hidden'>".htmlspecialchars($souscripteur["secteur_activite"] ?? '')."</td>
                                                <td>".htmlspecialchars($souscripteur["nom_etablissement"] ?? '')."</td>
                                                <td>".htmlspecialchars($souscripteur["lieu_exercice"] ?? '')."</td>
                                                <td class='hidden'>".htmlspecialchars($souscripteur["nom_region"] ?? '')."</td>
                                                <td>".htmlspecialchars($souscripteur["date_souscription"] ?? '')."</td>
                                                <td>".htmlspecialchars($souscripteur["montant_souscrit"] ?? '')."</td>
                                                <td class='hidden'>".htmlspecialchars($souscripteur["montant_souscrit_type1"] ?? '')."</td>
                                                <td class='hidden'>".htmlspecialchars($souscripteur["montant_souscrit_type2"] ?? '')."</td>
                                                <td class='hidden'>".htmlspecialchars($souscripteur["nombre_actions"] ?? '0')."</td>
                                                <td class='text-center no_export'>
                                                    <div class='form-check form-switch  mb-4'> 
                                                        <input class='form-check-input $activeClass' type='checkbox' $checkedAttr disabled>
                                                    </div>
                                                </td>
                                                <td class='text-end no_export'>
                                                    <form method='post' action='".$url."' style='display:inline;'>
                                                        <input type='hidden' name='id_souscripteur' value='".crypt_decrypt_chaine($souscripteur['id_souscripteur'], 'C')."'>
                                                        
                                                        <a href='update_souscripteurs.php?id_souscripteur=" . crypt_decrypt_chaine($souscripteur['id_souscripteur'], 'C') . "' class='btn btn-light btn-sm'>
                                                            <i class='fas fa-edit me-1'></i>
                                                        </a>
                                                        
                                                        <button type='button' class='btn btn-light btn-sm btn-supprimer' 
                                                            data-id='".crypt_decrypt_chaine($souscripteur['id_souscripteur'], 'C')."'
                                                            data-type='souscripteur'>
                                                            <i class='fas fa-trash-alt me-1'></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>";
                                    }
                                ?>                          
                            </tbody>
                        </table>
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

