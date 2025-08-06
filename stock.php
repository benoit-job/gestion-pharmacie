<?php
include("includes/connexion_acces_page.php");
include("includes/connexion_bdd.php");
include("includes/fonctions.php");
$url = "add_souscrit_versement.php";
?>

<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Versement</title>
    <?php include('includes/php/includes-css.php');?>
    <style>
        .versement-row {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }
        .versement-row .form-control {
            margin-right: 10px;
        }
        .versement-numero {
            width: 80px;
            text-align: center;
            font-weight: bold;
        }
        .montant-input {
            width: 150px;
        }
        .date-nature-container {
            display: flex;
        }
        .date-input {
            width: 150px;
        }
        .nature-input {
            width: 200px;
        }
        .buttons-container {
            margin-top: 20px;
        }
        .remove-row {
            color: #dc3545;
            cursor: pointer;
            margin-left: 10px;
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
                    <h2 class="mb-2">Versement</h2>
                    <h5 class="text-body-tertiary fw-semibold">
                        <?php if(!empty($souscripteur_info)): ?>
                            Souscripteur: <?php echo htmlspecialchars($souscripteur_info['nom_complet']); ?>
                        <?php endif; ?>
                    </h5>
                </div>

                <div class="page-section">
                    <div class="card">
                        <div class="card-body">
                            <form id="versementForm" action="traitement_versement.php" method="post">
                                <input type="hidden" name="id_souscripteur" value="<?php echo isset($_SESSION["id_s"]) ? htmlspecialchars($_SESSION["id_s"]) : ''; ?>">
                                
                                <div id="versementRows">
                                    <!-- Ligne initiale -->
                                    <div class="versement-row" data-row="1">
                                        <div class="versement-numero">1</div>
                                        <input type="number" name="montant[]" class="form-control montant-input" placeholder="Montant" step="0.01" min="0" required>
                                        <div class="date-nature-container">
                                            <input type="date" name="date_versement[]" class="form-control date-input" value="<?php echo date('Y-m-d'); ?>" required>
                                            <select name="nature[]" class="form-select nature-input" required>
                                                <option value="">Nature</option>
                                                <option value="Espèces">Espèces</option>
                                                <option value="Chèque">Chèque</option>
                                                <option value="Virement">Virement</option>
                                                <option value="Prélèvement">Prélèvement</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="buttons-container">
                                    <button type="button" id="addRowBtn" class="btn btn-secondary">
                                        <i class="fas fa-plus-circle me-2"></i>Ajouter une ligne
                                    </button>
                                    <button type="submit" class="btn btn-primary ms-2">
                                        <i class="fas fa-check-circle me-2"></i>Valider les versements
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('includes/php/footer.php');?>
        </div>
    </main>

    <?php include('includes/php/includes-js.php');?>
    <script>
        $(document).ready(function() {
            let rowCount = 1;
            
            // Ajouter une nouvelle ligne
            $('#addRowBtn').click(function() {
                rowCount++;
                const newRow = `
                    <div class="versement-row" data-row="${rowCount}">
                        <div class="versement-numero">${rowCount}</div>
                        <input type="number" name="montant[]" class="form-control montant-input" placeholder="Montant" step="0.01" min="0" required>
                        <div class="date-nature-container">
                            <input type="date" name="date_versement[]" class="form-control date-input" value="${new Date().toISOString().split('T')[0]}" required>
                            <select name="nature[]" class="form-select nature-input" required>
                                <option value="">Nature</option>
                                <option value="Espèces">Espèces</option>
                                <option value="Chèque">Chèque</option>
                                <option value="Virement">Virement</option>
                                <option value="Prélèvement">Prélèvement</option>
                            </select>
                        </div>
                        <span class="remove-row"><i class="fas fa-times-circle"></i></span>
                    </div>
                `;
                $('#versementRows').append(newRow);
            });

            // Supprimer une ligne (délégué car les nouvelles lignes n'existent pas au chargement)
            $(document).on('click', '.remove-row', function() {
                if($('.versement-row').length > 1) {
                    $(this).closest('.versement-row').remove();
                    // Recalculer les numéros de ligne
                    $('.versement-row').each(function(index) {
                        $(this).find('.versement-numero').text(index + 1);
                        $(this).attr('data-row', index + 1);
                    });
                    rowCount = $('.versement-row').length;
                } else {
                    alert("Vous devez avoir au moins une ligne de versement.");
                }
            });
        });
    </script>
</body>
</html>