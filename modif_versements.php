<?php
include("includes/connexion_acces_page.php");
include("includes/connexion_bdd.php");
include("includes/fonctions.php");
include_once("includes/auth_functions.php");   
$url = "modif_versements.php";

// Récupérer les versements existants si un souscripteur est sélectionné
$versements_existants = [];
if(isset($_GET['id_souscripteur'])) {
    $id_souscripteur = intval($_GET['id_souscripteur']);
    $_SESSION["id_s"] = $id_souscripteur;
    
    // Construire la requête en fonction de l'année sélectionnée
    $query_versements = "SELECT * FROM versements_souscripteurs 
                         WHERE id_souscripteur = ".$_SESSION["id_s"];
    
    if(isset($_GET['annee'])) {
        $annee = intval($_GET['annee']);
        $query_versements .= " AND YEAR(date) = $annee";
    }
    
    $query_versements .= " ORDER BY date ASC, ordre ASC";
    
    $result = mysqli_query($bdd, $query_versements);
    while($row = mysqli_fetch_assoc($result)) {
        $versements_existants[] = $row;
    }
    
    // Récupérer les infos du souscripteur
    $query_souscripteur = "SELECT id_souscripteur, UPPER(CONCAT(nom, ' ', prenom)) AS nom_complet 
                          FROM souscripteurs 
                          WHERE id_souscripteur = ".$_SESSION["id_s"];
    $result = mysqli_query($bdd, $query_souscripteur);
    $souscripteur_info = mysqli_fetch_assoc($result);
}

// DELETE FROM
if (isset($_POST["supprimerVersement"])) {
    $id_versement = crypt_decrypt_chaine($_POST["id_versement"], 'D');

    $deleteQuery = "DELETE FROM versements_souscripteurs WHERE id = ".intval($id_versement);
    $result = mysqli_query($bdd, $deleteQuery);
    
    if($result) {
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Versement supprimé avec succès'
        ];
    } else {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => 'Erreur lors de la suppression du versement.'
        ];
    }

    // Redirection avec les paramètres GET actuels
    $redirectUrl = $url;
    $params = [];

    if (!empty($_GET['id_souscripteur'])) {
        $params[] = "id_souscripteur=" . urlencode($_GET['id_souscripteur']);
    }
    if (!empty($_GET['annee'])) {
        $params[] = "annee=" . urlencode($_GET['annee']);
    }

    if (!empty($params)) {
        $redirectUrl .= "?" . implode("&", $params);
    }

    header("Location: $redirectUrl");
    exit();
}

?>

<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier Versement</title>
    <?php include('includes/php/includes-css.php');?>
    <style>
        .alert-no-souscripteur {
            border-left: 4px solid #dc3545;
            background-color: #f8d7da;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .versement-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .versement-table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .btn-add {
            border-radius: 20px;
            padding: 8px 20px;
        }
        .btn-submit {
            border-radius: 20px;
            padding: 8px 20px;
        }
        .remove-row-btn {
            color: #dc3545;
            cursor: pointer;
            margin-left: 10px;
        }
        .total-versement {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
        }
    </style>
</head>
<body>
    <main class="main" id="top">
        <?php include('includes/php/menu.php');?>
        <?php include('includes/php/header.php');?>

        <div class="content">
            <div class="pb-3">
                <div class="mb-5">
                    <h3 class="mb-2">Modifier Versement</h3>
                    <?php if(!empty($souscripteur_info)): ?>
                    <h5 class="text-body-tertiary fw-semibold">
                        Souscripteur: <span class="text-primary"><?php echo htmlspecialchars($souscripteur_info['nom_complet']); ?></span>
                    </h5>
                    <?php endif; ?>
                </div>

                <div class="page-section">
                    <div class="card">
                        <div class="card-header">
                            <form action="" method="get" class="d-flex align-items-center">
                                <!-- Select Souscripteur -->
                                <div class="position-relative me-3" style="width: 400px;">
                                    <label for="id_souscripteur" class="form-label mb-0 fw-bold text-primary">Choisir un souscripteur :</label>
                                    <select class="form-select organizerSingle shadow-sm" 
                                            id="id_souscripteur" 
                                            name="id_souscripteur"
                                            style="border-radius: 20px; border: 1px solid #ced4da; padding: 8px 15px;"
                                            onchange="this.form.submit()">
                                        <option disabled selected>------------</option>
                                        <?php
                                            $selectedId = isset($_GET['id_souscripteur']) ? $_GET['id_souscripteur'] : 0;
                                            $query = "SELECT id_souscripteur, UPPER(CONCAT(nom, ' ', prenom)) AS nom_souscripteur 
                                                    FROM souscripteurs 
                                                    WHERE active = 'oui' 
                                                    ORDER BY souscripteurs.nom, souscripteurs.prenom ASC";
                                            $resultat = mysqli_query($bdd, $query) or die("Erreur SQL");
                                            while ($souscripteur = mysqli_fetch_assoc($resultat)) {
                                                $selected = ($selectedId == $souscripteur['id_souscripteur']) ? 'selected' : '';
                                                echo "<option value='" . htmlspecialchars($souscripteur['id_souscripteur']) . "' $selected>" . htmlspecialchars($souscripteur['nom_souscripteur']) . "</option>";
                                            }
                                        ?>
                                    </select>
                                    <i class="fas fa-chevron-down position-absolute end-0 top-50 translate-middle-y me-3 text-secondary"></i>
                                </div>
                                
                                <!-- Select Année -->
                                <div class="position-relative" style="width: 200px;">
                                    <label for="annee" class="form-label mb-0 fw-bold text-primary">Choisir une année :</label>
                                    <select class="form-select organizerSingle shadow-sm" 
                                            id="annee" 
                                            name="annee"
                                            style="border-radius: 20px; border: 1px solid #ced4da; padding: 8px 15px;"
                                            onchange="this.form.submit()"
                                            <?php echo empty($_GET['id_souscripteur']) ? 'disabled' : ''; ?>>
                                        <option disabled selected>-----------</option>
                                        <?php
                                            if(!empty($_GET['id_souscripteur'])) {
                                                $currentYear = date('Y');
                                                for ($year = $currentYear; $year >= 2015; $year--) {
                                                    $selectedYear = (isset($_GET['annee']) && $_GET['annee'] == $year) ? 'selected' : '';
                                                    echo "<option value='$year' $selectedYear>$year</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                    <i class="fas fa-chevron-down position-absolute end-0 top-50 translate-middle-y me-3 text-secondary"></i>
                                </div>
                            </form>
                        </div>
                        
                        <div class="card-body">
                            <?php if(empty($_GET['id_souscripteur'])): ?>
                                <div class="alert-no-souscripteur">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    Veuillez sélectionner un souscripteur pour modifier ou consulter ses versements.
                                </div>
                            <?php else: ?>
                                <!-- Formulaire de versement -->
                                <form id="formVersement" method="post">
                                    <input type="hidden" name="id_souscripteur" value="<?php echo htmlspecialchars($_GET['id_souscripteur']); ?>">
                                    
                                    <div class="table-responsive mb-4">
                                        <table class="table table-bordered versement-table m-0" id="versementTable">
                                            <thead class="table-light">
                                                <tr style="font-size:0.8rem;">
                                                    <th width="10%">#</th>
                                                    <th width="25%">Montant</th>
                                                    <th width="25%">Date</th>
                                                    <th width="35%">Nature</th>
                                                    <th width="5%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(empty($versements_existants)): ?>
                                                    <!-- Ligne vide pour nouveau versement -->
                                                    <script>
                                                        document.addEventListener("DOMContentLoaded", function () {
                                                            showNoVersementAlert();
                                                        });
                                                    </script>
                                               <?php else: ?>
                                                    <!-- Afficher les versements existants -->
                                                    <?php foreach($versements_existants as $versement): ?>
                                                        <tr>
                                                            <td class="numero">Versement <?php echo htmlspecialchars($versement['ordre']); ?></td>
                                                            <td>
                                                                <input type="number" name="montant[]" class="form-control form-control-sm" 
                                                                    value="<?php echo htmlspecialchars($versement['montant']); ?>" required>
                                                            </td>
                                                            <td>
                                                                <input type="date" name="date[]" class="form-control form-control-sm" 
                                                                    value="<?php echo htmlspecialchars($versement['date']); ?>" required>
                                                            </td>
                                                            <td>
                                                                <select name="nature[]" class="form-select form-select-sm" required>
                                                                    <option value="Espèces" <?php echo $versement['nature'] == 'Espèces' ? 'selected' : ''; ?>>Espèces</option>
                                                                    <option value="Chèque" <?php echo $versement['nature'] == 'Chèque' ? 'selected' : ''; ?>>Chèque</option>
                                                                    <option value="Virement" <?php echo $versement['nature'] == 'Virement' ? 'selected' : ''; ?>>Virement</option>
                                                                    <option value="Prélèvement" <?php echo $versement['nature'] == 'Prélèvement' ? 'selected' : ''; ?>>Prélèvement</option>
                                                                    <option value="Mobile Money" <?php echo $versement['nature'] == 'Mobile Money' ? 'selected' : ''; ?>>Mobile Money</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <button type='button' class='btn btn-light btn-sm btn-supprimer' 
                                                                    data-id='<?php echo crypt_decrypt_chaine($versement['id'], 'C'); ?>'
                                                                    data-type='versement'>
                                                                    <i class='fas fa-trash-alt me-1 text-danger'></i>
                                                                </button>
                                                                <input type="hidden" name="id_versement" 
                                                                    value="<?php echo crypt_decrypt_chaine($versement['id'], 'C'); ?>">
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" id="addRow" class="btn btn-primary btn-add" style="visibility: hidden;">
                                            <i class="fas fa-plus-circle me-2"></i>Ajouter une ligne
                                        </button>
                                        <button type="submit" class="btn btn-success btn-submit">
                                            <i class="fas fa-check-circle me-2"></i>Valider le versement
                                        </button>
                                    </div>
                                </form>
                                
                                <!-- Total des versements -->
                                <?php if(!empty($versements_existants)): ?>
                                    <?php
                                        $total = 0;
                                        foreach($versements_existants as $versement) {
                                            $total += $versement['montant'];
                                        }
                                    ?>
                                    <div class="mt-4 p-3 bg-light rounded">
                                        <h5 class="mb-0">
                                            Total des versements : <span class="text-primary"><?php echo number_format($total, 0, ',', ' '); ?> FCFA</span>
                                        </h5>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
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

<script>
function showNoVersementAlert() {
    Swal.fire({
        title: "Aucun versement trouvé !",
        showClass: {
            popup: `
                animate__animated
                animate__fadeInUp
                animate__faster
            `
        },
        hideClass: {
            popup: `
                animate__animated
                animate__fadeOutDown
                animate__faster
            `
        }
    });
}

// Soumission Ajax pour UPDATE
$("#formVersement").on("submit", function(e) {
    e.preventDefault();

    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true)
             .html('<span class="fas fa-spinner fa-spin me-2"></span>Mise à jour...');

    let data = [];
    const idSouscripteur = $('input[name="id_souscripteur"]').val();

    $('#versementTable tbody tr').each(function(index) {
        let montant = $(this).find('input[name="montant[]"]').val();
        let date = $(this).find('input[name="date[]"]').val();
        let nature = $(this).find('select[name="nature[]"]').val();
        let id_versement = $(this).find('input[name="id_versement"]').val();

        if (montant && date && nature && id_versement) {
            data.push({
                ordre: index + 1,
                montant: montant,
                date: date,
                nature: nature,
                id_souscripteur: idSouscripteur,
                id_versement: id_versement
            });
        }
    });

    if (data.length === 0) {
        showToast('error', 'Aucune ligne à mettre à jour');
        submitBtn.prop('disabled', false)
                 .html('<i class="fas fa-check-circle me-2"></i>Valider le versement');
        return;
    }

    // 🔹 Laisser le spinner tourner 1 seconde avant de lancer la requête
    setTimeout(function() {
        $.ajax({
            url: 'ajax/update.php',
            type: 'POST',
            data: { versements: JSON.stringify(data) },
            success: function(response) {
                console.log("Réponse serveur:", response);
                if (response.trim() === 'success') {
                    showToastSupp('success', 'Versements mis à jour avec succès!');
                } else {
                    showToastSupp('error', 'Erreur lors de la mise à jour');
                }
            },
            error: function() {
                showToastSupp('error', 'Erreur de connexion au serveur');
            },
            complete: function() {
                submitBtn.prop('disabled', false)
                         .html('<i class="fas fa-check-circle me-2"></i>Valider le versement');
            }
        });
    }, 2000); // délai d'1 seconde pour voir le spinner
});


</script>
