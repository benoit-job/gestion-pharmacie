 <?php
include("includes/connexion_acces_page.php");
include("includes/connexion_bdd.php");
include("includes/fonctions.php");
include_once("includes/auth_functions.php");   
$url = "sauvegardes.php";
?>

<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="fr-FR" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sauvegarde des données</title>
    <?php include('includes/php/includes-css.php');?>
    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .data-table {
            font-size: 0.85rem;
        }
        .data-table th {
            background-color: #f8f9fa;
            position: sticky;
            top: 0;
        }
        .tab-content {
            padding: 20px;
            background: white;
            border-radius: 0 0 10px 10px;
        }
        .nav-tabs .nav-link.active {
            font-weight: bold;
            border-bottom: 3px solid #2c7be5;
        }
        .badge-action {
            font-size: 0.75em;
            padding: 5px 8px;
        }
        .badge-add {
            background-color: #2ecc71;
        }
        .badge-edit {
            background-color: #3498db;
        }
        .badge-delete {
            background-color: #e74c3c;
        }
        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
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
                    <h3 class="mb-2">Sauvegarde des données</h3>
                    <h5 class="text-body-tertiary fw-semibold">
                        Historique des modifications et données supprimées
                    </h5>
                </div>

                <div class="page-section">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="card card-hover h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-uppercase text-body-tertiary mb-2">Souscripteurs</h6>
                                            <?php 
                                                $query = "SELECT COUNT(*) as total FROM historiques_souscripteurs";
                                                $result = $bdd->query($query);
                                                $row = $result->fetch_assoc();
                                            ?>
                                            <h4 class="mb-0"><?= $row['total'] ?></h4>
                                        </div>
                                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-users text-primary fs-3"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card card-hover h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-uppercase text-body-tertiary mb-2">Lieux</h6>
                                            <?php 
                                                $query = "SELECT COUNT(*) as total FROM historique_lieu";
                                                $result = $bdd->query($query);
                                                $row = $result->fetch_assoc();
                                            ?>
                                            <h4 class="mb-0"><?= $row['total'] ?></h4>
                                        </div>
                                        <div class="bg-info bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-map-marker-alt text-info fs-3"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card card-hover h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-uppercase text-body-tertiary mb-2">Régions</h6>
                                            <?php 
                                                $query = "SELECT COUNT(*) as total FROM historique_region";
                                                $result = $bdd->query($query);
                                                $row = $result->fetch_assoc();
                                            ?>
                                            <h4 class="mb-0"><?= $row['total'] ?></h4>
                                        </div>
                                        <div class="bg-success bg-opacity-10 p-3 rounded">
                                            <i class="fas fa-globe-africa text-success fs-3"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="souscripteurs1-tab" data-bs-toggle="tab" data-bs-target="#souscripteurs1" type="button" role="tab" aria-controls="souscripteurs1" aria-selected="true">
                                        Souscripteurs
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="lieux-tab" data-bs-toggle="tab" data-bs-target="#lieux" type="button" role="tab" aria-controls="lieux" aria-selected="false">
                                        Lieux
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="regions-tab" data-bs-toggle="tab" data-bs-target="#regions" type="button" role="tab" aria-controls="regions" aria-selected="false">
                                        Régions
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="types-tab" data-bs-toggle="tab" data-bs-target="#types" type="button" role="tab" aria-controls="types" aria-selected="false">
                                        Types Souscripteurs
                                    </button>
                                </li>
                            </ul>
                            
                            <div class="tab-content" id="myTabContent">
                                <!-- Tab Souscripteurs1 -->
                                <div class="tab-pane fade show active" id="souscripteurs1" role="tabpanel" aria-labelledby="souscripteurs1-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover data-table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nom</th>
                                                    <th>Prénom</th>
                                                    <th>Email</th>
                                                    <th>Téléphone</th>
                                                    <th>Montant</th>
                                                    <th>Actions</th>
                                                    <th>Date Suppression</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query = "SELECT * FROM historiques_souscripteurs ORDER BY date_suppression DESC";
                                                $result = $bdd->query($query);
                                                
                                                while($row = $result->fetch_assoc()):
                                                    $actionBadgeClass = '';
                                                    if($row['action'] == 'add') $actionBadgeClass = 'bg-success';
                                                    elseif($row['action'] == 'edit') $actionBadgeClass = 'bg-primary';
                                                    else $actionBadgeClass = 'bg-danger';
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['id_souscripteur'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['nom'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['prenom'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                                    <td><?= htmlspecialchars($row['telephone_portable'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['montant_souscrit'] ?? '') ?> €</td>
                                                    <td><span class="badge <?= $actionBadgeClass ?> badge-action"><?= strtoupper(htmlspecialchars($row['action']) ?? '') ?></span></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($row['date_suppression'])) ?></td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Tab Lieux -->
                                <div class="tab-pane fade" id="lieux" role="tabpanel" aria-labelledby="lieux-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover data-table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nom Lieu</th>
                                                    <th>Région</th>
                                                    <th>Coordonnées</th>
                                                    <th>Action</th>
                                                    <th>Date Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query = "SELECT * FROM historique_lieu ORDER BY date_action DESC";
                                                $result = $bdd->query($query);
                                                
                                                while($row = $result->fetch_assoc()):
                                                    $actionBadgeClass = '';
                                                    if($row['action'] == 'add') $actionBadgeClass = 'bg-success';
                                                    elseif($row['action'] == 'edit') $actionBadgeClass = 'bg-primary';
                                                    else $actionBadgeClass = 'bg-danger';
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['id_lieu'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['nom_lieu'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['nom_region'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['lieulat'] ?? '') ?>, <?= htmlspecialchars($row['lieulong'] ?? '') ?></td>
                                                    <td><span class="badge <?= $actionBadgeClass ?> badge-action"><?= strtoupper(htmlspecialchars($row['action']) ?? '') ?></span></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($row['date_action'])) ?></td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Tab Régions -->
                                <div class="tab-pane fade" id="regions" role="tabpanel" aria-labelledby="regions-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover data-table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nom Région</th>
                                                    <th>Code Région</th>
                                                    <th>Action</th>
                                                    <th>Date Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query = "SELECT * FROM historique_region ORDER BY date_action DESC";
                                                $result = $bdd->query($query);
                                                
                                                while($row = $result->fetch_assoc()):
                                                    $actionBadgeClass = '';
                                                    if($row['action'] == 'add') $actionBadgeClass = 'bg-success';
                                                    elseif($row['action'] == 'edit') $actionBadgeClass = 'bg-primary';
                                                    else $actionBadgeClass = 'bg-danger';
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['id_region']?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['nom_region'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['code_region'] ?? '') ?></td>
                                                    <td><span class="badge <?= $actionBadgeClass ?> badge-action"><?= strtoupper(htmlspecialchars($row['action'])) ?></span></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($row['date_action'])) ?></td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Tab Types Souscripteurs1 -->
                                <div class="tab-pane fade" id="types" role="tabpanel" aria-labelledby="types-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover data-table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nom Type</th>
                                                    <th>Action</th>
                                                    <th>Date Suppression</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query = "SELECT * FROM historique_type_souscripts ORDER BY date_delet DESC";
                                                $result = $bdd->query($query);
                                                
                                                while($row = $result->fetch_assoc()):
                                                    $actionBadgeClass = '';
                                                    if($row['action'] == 'add') $actionBadgeClass = 'bg-success';
                                                    elseif($row['action'] == 'edit') $actionBadgeClass = 'bg-primary';
                                                    else $actionBadgeClass = 'bg-danger';
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['id_type'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($row['nom'] ?? '') ?></td>
                                                    <td><span class="badge <?= $actionBadgeClass ?> badge-action"><?= strtoupper(htmlspecialchars($row['action']) ?? '') ?></span></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($row['date_delet'])) ?></td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
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
        // Solution alternative plus robuste pour les onglets
document.addEventListener('DOMContentLoaded', function() {
    const tabElms = document.querySelectorAll('button[data-bs-toggle="tab"]');
    
    tabElms.forEach(tabEl => {
        tabEl.addEventListener('shown.bs.tab', function (event) {
            const target = event.target.getAttribute('data-bs-target');
            // Masquer tous les contenus d'onglets
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            // Afficher seulement le contenu cible
            document.querySelector(target).classList.add('show', 'active');
        });
    });
});
    </script>
</body>
</html>