<?php
include("includes/connexion_acces_page.php");
include("includes/connexion_bdd.php");
include("includes/fonctions.php");
$url = "add_souscrit_versement.php";

if(isset($_GET["id_souscripteur"]) && !empty($_GET["id_souscripteur"])) {
    $_SESSION["id_s"] = strip_tags(htmlspecialchars(trim(crypt_decrypt_chaine($_GET["id_souscripteur"], 'D'))));
    reload_current_page();
}

// Récupération des informations du souscripteur
$souscripteur_info = [];
if(isset($_SESSION["id_s"])) {
    $query = "SELECT id_souscripteur, UPPER(CONCAT(nom, ' ', prenom)) AS nom_complet 
              FROM souscripteurs 
              WHERE id_souscripteur = ".intval($_SESSION["id_s"]);
    $result = mysqli_query($bdd, $query);
    $souscripteur_info = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="fr-FR" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Versement</title>

    <?php include('includes/php/includes-css.php');?>
    
    <style>
        
        .versement-table input, .versement-table select {
            width: 100%;
            transition: all 0.3s ease;
        }
        .versement-table th, .versement-table td {
            text-align: center;
            vertical-align: middle;
            transition: all 0.3s ease;
        }
     
        .btn-remove {
            color: #dc3545;
            background: transparent;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-remove:hover {
            transform: scale(1.2);
                color: #ff0000;
        }
        .numero {
            font-weight: bold;
            color: #2c7be5;
        }
        .animate-row {
            animation: fadeInSlide 0.5s ease-out;
        }
        @keyframes fadeInSlide {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .table-hover tbody tr:hover {
            background-color: rgba(44, 123, 229, 0.05);
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .btn-add {
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .btn-submit {
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(40, 167, 69, 0.3);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.4);
        }
           /* Assure que le select passe au-dessus */
    /* Force le dropdown natif à flotter */
.versement-table select {
    position: relative;
    z-index: 9999;
    background: white; /* utile si transparence */
}

.card,
.container {
    overflow: visible !important;
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
                <nav class="mb-2" aria-label="breadcrumb">
                  <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <i class='breadcrumb-icon fa fa-angle-left mr-2'></i> 
                        <a href="add_versement.php" class='text-secondary' style="text-decoration: none;">Retour</a>
                    </li>
                  </ol>
                </nav>
                <h3 class="mb-2">Versement</h3>
                <h5 class="text-body-tertiary  fw-semibold">
                    <?php if(!empty($souscripteur_info)): ?>
                        Souscripteur: <span class="text-primary"><?php echo htmlspecialchars($souscripteur_info['nom_complet']); ?></span>
                    <?php endif; ?>
                </h5>
            </div>

            <div class="page-section">
                <form id="formVersement" method="post">
                    <input type="hidden" name="id_souscripteur" value="<?php echo isset($_SESSION["id_s"]) ? htmlspecialchars($_SESSION["id_s"]) : ''; ?>">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered versement-table m-0" id="versementTable" style="width:100%">
                            <thead class="table-light">
                            <tr style="font-size:0.8rem;">
                                <th width="10%">#</th>
                                <th width="25%">Montant</th>
                                <th width="25%">Date</th>
                                <th width="40%">Nature</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="numero">Versement 1</td>
                                <td><input type="number" name="montant[]" class="form-control form-control-sm" required></td>
                                <td><input type="date" name="date[]" class="form-control form-control-sm" required></td>
                                <td>
                                    <select name="nature[]" class="form-select form-select-sm" required>
                                    <option disabled selected>-----</option>
                                    <option value="Espèces">Espèces</option>
                                    <option value="Chèque">Chèque</option>
                                    <option value="Virement">Virement</option>
                                    <option value="Prélèvement">Prélèvement</option>
                                    <option value="Mobile Money">Mobile Money</option>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" id="addRow" class="btn btn-primary btn-add">
                            <i class="fas fa-plus-circle me-2"></i>Ajouter une ligne
                        </button>
                        <button type="submit" class="btn btn-success btn-submit">
                            <i class="fas fa-check-circle me-2"></i>Valider le versement
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php include('includes/php/footer.php');?>
    </div>
</main>

<?php include('includes/php/includes-js.php');?>

<script>
        document.addEventListener("DOMContentLoaded", function() {
            const table = document.getElementById("versementTable").getElementsByTagName("tbody")[0];
            const addRowBtn = document.getElementById("addRow");

            // Ajouter une nouvelle ligne avec animation
            addRowBtn.addEventListener("click", function() {
                const rowCount = table.rows.length + 1;
                const newRow = table.insertRow();
                newRow.className = "animate-row";
                
                newRow.innerHTML = `
                    <td class="numero"> Versement ${rowCount}</td>
                    <td><input type="number" name="montant[]" class="form-control" step="0.01" min="0" required></td>
                    <td><input type="date" name="date[]" class="form-control" value="${new Date().toISOString().split('T')[0]}" required></td>
                    <td>
                        <select name="nature[]" class="form-select organizerSingle" required>
                            <option value="">-----</option>
                            <option value="Espèces">Espèces</option>
                            <option value="Chèque">Chèque</option>
                            <option value="Virement">Virement</option>
                            <option value="Prélèvement">Prélèvement</option>
                            <option value="Mobile Money">Mobile Money</option>
                        </select>
                    </td>
                    <button type="button" class="btn-remove"><span><i class="fas fa-times-circle"></i></span></button>
                `;

                // Effet visuel supplémentaire
                setTimeout(() => {
                    newRow.style.transform = "scale(1.02)";
                    setTimeout(() => {
                        newRow.style.transform = "scale(1)";
                    }, 300);
                }, 10);
            });

            // Supprimer une ligne
            document.addEventListener("click", function(e) {
                if (e.target.closest(".btn-remove") && !e.target.closest(".btn-remove").disabled) {
                    const row = e.target.closest("tr");
                    row.style.animation = "fadeOutSlide 0.3s ease-out";
                    row.style.opacity = "0";
                    row.style.transform = "translateX(50px)";
                    
                    setTimeout(() => {
                        row.remove();
                        // Recalculer les numéros
                        const rows = table.querySelectorAll("tr");
                        rows.forEach((tr, index) => {
                            tr.querySelector(".numero").textContent = index + 1;
                            // Désactiver le bouton supprimer pour la première ligne
                            if (index === 0) {
                                tr.querySelector(".btn-remove").disabled = true;
                            }
                        });
                    }, 300);
                }
            });
        });
</script>
</body>
</html>

<script>
    // Soumission Ajax
$("#formVersement").on("submit", function(e) {
    e.preventDefault();

    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true)
             .html('<span class="fas fa-spinner fa-spin me-2"></span>Insertion en cours...');

    // Récupérer les lignes avec données
    let data = [];
    const idSouscripteur = $('input[name="id_souscripteur"]').val();
    $('#versementTable tbody tr').each(function(index) {
        let montant = $(this).find('input[name="montant[]"]').val();
        let date = $(this).find('input[name="date[]"]').val();
        let nature = $(this).find('select[name="nature[]"]').val();
        if (montant && date && nature) {
            data.push({
                ordre: index + 1,
                montant: montant,
                date: date,
                nature: nature,
                id_souscripteur: idSouscripteur
            });
        }
    });

    if (data.length === 0) {
        showToast('error', 'Veuillez remplir au moins une ligne');
        submitBtn.prop('disabled', false)
                 .html('<i class="fas fa-check-circle me-2"></i>Valider le versement');
        return;
    }

    // On laisse tourner le spinner 1 seconde pour l'affichage
    setTimeout(function() {
        $.ajax({
            url: 'ajax/insertInto.php',
            type: 'POST',
            data: { versements: JSON.stringify(data) },
            success: function(response) {
                console.log("Réponse serveur:", response);
                if (response.trim() === 'success') {
                    showToastSupp('success', 'Versements effectué avec succès!');
                    $('#formVersement')[0].reset();
                    $('#versementTable tbody').html($('#versementTable tbody tr:first')[0].outerHTML);
                    setTimeout(function() {
                    showToast('success', 'Redirection en cours...');
                    // Attendre 1 seconde pour laisser le toast s'afficher avant de rediriger
                    setTimeout(function() {
                        window.location.href = 'add_versement.php';
                    }, 1000);

                }, 4000);
                } else {
                    showToastSupp('error', 'Erreur lors de l’insertion');
                }
            },
            error: function() {
                showToastSupp('success', 'Erreur de connexion au serveur');
            },
            complete: function() {
                submitBtn.prop('disabled', false)
                         .html('<i class="fas fa-check-circle me-2"></i>Valider le versement');
            }
        });
    }, 1000); // délai d'1 seconde pour voir le spinner
});

</script>