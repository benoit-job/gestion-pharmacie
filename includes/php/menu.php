
<nav class="navbar navbar-vertical navbar-expand-lg">
  <script>
    var navbarStyle = window.config?.config?.phoenixNavbarStyle;
    if (navbarStyle && navbarStyle !== 'transparent') {
      document.querySelector('body').classList.add(`navbar-${navbarStyle}`);
    }
  </script>

  <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
    <div class="navbar-vertical-content">
      <ul class="navbar-nav flex-column" id="navbarVerticalNav">
        <li class="nav-item">

<!-- Tableau de Bord -->
<div class="nav-item-wrapper">
  <a class="nav-link label-1" href="dashboard.php" role="button" data-bs-toggle="" aria-expanded="false">
    <div class="d-flex align-items-center">
      <span class="nav-link-icon icon-dashboard"><i class="fas fa-chart-line"></i></span>
      <span class="nav-link-text-wrapper"><span class="nav-link-text">Tableau de Bord</span></span>
    </div>
  </a>
</div>

<!-- Tableau de Bord -->
<div class="nav-item-wrapper">
  <a class="nav-link label-1" href="type_souscripteurs.php" role="button" data-bs-toggle="" aria-expanded="false">
    <div class="d-flex align-items-center">
      <span class="nav-link-icon icon-dashboard"><i class="fas fa-user-friends"></i></span>
      <span class="nav-link-text-wrapper"><span class="nav-link-text">Type souscripteur</span></span>
    </div>
  </a>
</div>

<!-- Gestion des Souscripteurs -->
<div class="nav-item-wrapper">
  <a class="nav-link dropdown-indicator label-1" href="#souscripteurs" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="souscripteurs">
    <div class="d-flex align-items-center">
      <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div>
      <span class="nav-link-icon icon-users"><i class="fas fa-user-md"></i></span>
      <span class="nav-link-text">Souscripteurs</span>
    </div>
  </a>
  <div class="parent-wrapper label-1">
    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="souscripteurs">
      <li class="nav-item">
        <a class="nav-link" href="tous_souscripteurs.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-list text-primary"></i></span>
            <span class="nav-link-text">Tous les souscripteurs</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="nouveau_souscripteur.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-plus-circle text-success"></i></span>
            <span class="nav-link-text">Nouveau souscripteur</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="recherche_avancee.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-search text-info"></i></span>
            <span class="nav-link-text">Recherche avancée</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <hr class="my-2">
        <small class="text-muted ps-3">Classification par statut</small>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="souscripteurs_a_jour.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-check-circle text-success"></i></span>
            <span class="nav-link-text">À jour</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="sans_versement.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-times-circle text-danger"></i></span>
            <span class="nav-link-text">Sans versement</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="versements_partiels.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-exclamation-triangle text-warning"></i></span>
            <span class="nav-link-text">Versements partiels</span>
          </div>
        </a>
      </li>
    </ul>
  </div>
</div>

<!-- Classification par Montant -->
<div class="nav-item-wrapper">
  <a class="nav-link dropdown-indicator label-1" href="#montants" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="montants">
    <div class="d-flex align-items-center">
      <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div>
      <span class="nav-link-icon icon-payments"><i class="fas fa-coins"></i></span>
      <span class="nav-link-text">Par Montant</span>
    </div>
  </a>
  <div class="parent-wrapper label-1">
    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="montants">
      <li class="nav-item">
        <a class="nav-link" href="montant_1_749k.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-coins text-secondary"></i></span>
            <span class="nav-link-text">1F - 749 000F</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="montant_750k_2999k.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-coins text-warning"></i></span>
            <span class="nav-link-text">750K - 2 999K</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="montant_3m_plus.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-coins text-success"></i></span>
            <span class="nav-link-text">3 000 000F et plus</span>
          </div>
        </a>
      </li>
    </ul>
  </div>
</div>

<!-- Gestion des Versements -->
<div class="nav-item-wrapper">
  <a class="nav-link dropdown-indicator label-1" href="#versements" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="versements">
    <div class="d-flex align-items-center">
      <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div>
      <span class="nav-link-icon icon-payments"><i class="fas fa-credit-card"></i></span>
      <span class="nav-link-text">Versements</span>
    </div>
  </a>
  <div class="parent-wrapper label-1">
    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="versements">
      <li class="nav-item">
        <a class="nav-link" href="nouveau_versement.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-plus text-success"></i></span>
            <span class="nav-link-text">Nouveau versement</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="historique_versements.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-history text-info"></i></span>
            <span class="nav-link-text">Historique</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="modification_versements.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-edit text-warning"></i></span>
            <span class="nav-link-text">Modifications</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="suivi_paiements.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-chart-pie text-primary"></i></span>
            <span class="nav-link-text">Suivi paiements</span>
          </div>
        </a>
      </li>
    </ul>
  </div>
</div>

<!-- Rapports & Statistiques -->
<div class="nav-item-wrapper">
  <a class="nav-link dropdown-indicator label-1" href="#rapports" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="rapports">
    <div class="d-flex align-items-center">
      <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div>
      <span class="nav-link-icon icon-reports"><i class="fas fa-chart-bar"></i></span>
      <span class="nav-link-text">Rapports</span>
    </div>
  </a>
  <div class="parent-wrapper label-1">
    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="rapports">
      <li class="nav-item">
        <a class="nav-link" href="statistiques_generales.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-chart-line text-primary"></i></span>
            <span class="nav-link-text">Vue d'ensemble</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="repartition_genre.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-venus-mars text-info"></i></span>
            <span class="nav-link-text">Par genre</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="repartition_region.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-map-marked-alt text-success"></i></span>
            <span class="nav-link-text">Par région</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="tableau_croise.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-table text-warning"></i></span>
            <span class="nav-link-text">Tableau croisé</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <hr class="my-2">
        <small class="text-muted ps-3">Exports</small>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="export_excel.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-file-excel text-success"></i></span>
            <span class="nav-link-text">Export Excel</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="export_pdf.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-file-pdf text-danger"></i></span>
            <span class="nav-link-text">Export PDF</span>
          </div>
        </a>
      </li>
    </ul>
  </div>
</div>

<!-- Gestion Géographique -->
<div class="nav-item-wrapper">
  <a class="nav-link dropdown-indicator label-1" href="#geographie" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="geographie">
    <div class="d-flex align-items-center">
      <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div>
      <span class="nav-link-icon icon-geography"><i class="fas fa-globe-africa"></i></span>
      <span class="nav-link-text">Géographie</span>
    </div>
  </a>
  <div class="parent-wrapper label-1">
    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="geographie">
      <li class="nav-item">
        <a class="nav-link" href="regions_pharma.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-map text-primary"></i></span>
            <span class="nav-link-text">Régions Pharma</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="lieux_exercice.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-building text-info"></i></span>
            <span class="nav-link-text">Lieux d'exercice</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="cartographie.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-map-marked text-success"></i></span>
            <span class="nav-link-text">Cartographie</span>
          </div>
        </a>
      </li>
    </ul>
  </div>
</div>

<!-- Administration -->
<div class="nav-item-wrapper">
  <a class="nav-link dropdown-indicator label-1" href="#administration" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="administration">
    <div class="d-flex align-items-center">
      <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div>
      <span class="nav-link-icon icon-admin"><i class="fas fa-cogs"></i></span>
      <span class="nav-link-text">Administration</span>
    </div>
  </a>
  <div class="parent-wrapper label-1">
    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="administration">
      <li class="nav-item">
        <a class="nav-link" href="utilisateurs.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-users text-primary"></i></span>
            <span class="nav-link-text">Utilisateurs</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="roles_permissions.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-shield-alt text-warning"></i></span>
            <span class="nav-link-text">Rôles & Permissions</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="sauvegarde.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-database text-success"></i></span>
            <span class="nav-link-text">Sauvegarde/Restauration</span>
          </div>
        </a>
      </li>
    </ul>
  </div>
</div>

<!-- Profil Utilisateur -->
<div class="nav-item-wrapper">
  <a class="nav-link dropdown-indicator label-1" href="#profil" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="profil">
    <div class="d-flex align-items-center">
      <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div>
      <span class="nav-link-icon icon-profile"><i class="fas fa-user-circle"></i></span>
      <span class="nav-link-text">Mon Profil</span>
    </div>
  </a>
  <div class="parent-wrapper label-1">
    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="profil">
      <li class="nav-item">
        <a class="nav-link" href="mon_profil.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-user text-primary"></i></span>
            <span class="nav-link-text">Informations</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="changer_mot_de_passe.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-key text-warning"></i></span>
            <span class="nav-link-text">Mot de passe</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="preferences.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-cog text-secondary"></i></span>
            <span class="nav-link-text">Préférences</span>
          </div>
        </a>
      </li>
      <li class="nav-item">
        <hr class="my-2">
      </li>
      <li class="nav-item">
        <a class="nav-link text-danger" href="logout.php" data-bs-toggle="" aria-expanded="false">
          <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-sign-out-alt text-danger"></i></span>
            <span class="nav-link-text">Déconnexion</span>
          </div>
        </a>
      </li>
    </ul>
  </div>
</div>

        </li>
      </ul>
    </div>
  </div>

  <div class="navbar-vertical-footer">
    <button class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center">
      <span class="uil uil-left-arrow-to-left fs-8"></span>
      <span class="uil uil-arrow-from-right fs-8"></span>
      <span class="navbar-vertical-footer-text ms-2">Réduire</span>
    </button>
  </div>
</nav>

