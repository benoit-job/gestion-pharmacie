<nav class="navbar navbar-top fixed-top navbar-expand" id="navbarDefault">
  <div class="collapse navbar-collapse justify-content-between">
    <div class="navbar-logo">

      <!-- Bouton pour ouvrir la sidebar -->
      <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation">
        <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
      </button>

      <!-- Logo + Nom du site -->
      <a class="navbar-brand me-1 me-sm-3" href="#">    
        <div class="d-flex align-items-center">
          <div class="d-flex align-items-center">
            <!-- Image du logo -->
            <!-- <img id='imgLogo' src="<?php echo setSrcImg($_SESSION["site"]["logo"]);?>" class='rounded' height='50px'/> -->
            <img id='imgLogo' src="assets/img/icons/logo.png" class='rounded' height='50px'/>

            <!-- Nom du site -->
            <!-- <p class="logo-text ms-2 d-none d-sm-block"><?php echo break_last_name($_SESSION['site']['nom'], 18);?></p> -->
            <p class="logo-text ms-2 d-none d-sm-block">PharmaGestion</p>
          </div> 
        </div>
      </a>
    </div>
    
    <div class="search-box navbar-top-search-box d-none d-lg-block" data-list='{"valueNames":["title"]}' style="width:25rem;">
      <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
        <input class="form-control search-input fuzzy-search rounded-pill form-control-sm" type="search" placeholder="Rechercher une pharmacie..." aria-label="Search" />
        <span class="fas fa-search search-box-icon"></span>

      </form>
      <div class="btn-close position-absolute end-0 top-50 translate-middle cursor-pointer shadow-none" data-bs-dismiss="search">
        <button class="btn btn-link p-0" aria-label="Close"></button>
      </div>
    </div>
    <!-- Partie droite de la navbar -->
    <ul class="navbar-nav navbar-nav-icons flex-row">
      
      <!-- Bouton pour changer de thème clair/sombre -->
      <li class="nav-item">
        <div class="theme-control-toggle fa-icon-wait px-2">
          <input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" />
          <label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Mode clair"><span class="icon" data-feather="moon"></span></label>
          <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Mode sombre"><span class="icon" data-feather="sun"></span></label>
        </div>
      </li>

      <!-- Profil utilisateur -->
      <li class="nav-item dropdown">
        <a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
          <div class="avatar avatar-l ">
            <img class="rounded-circle" src="https://th.bing.com/th?id=OIP.fqSvfYQB0rQ-6EG_oqvonQHaHa&w=250&h=250&c=8&rs=1&qlt=90&o=6&pid=3.1&rm=2" alt="Photo de profil" />
          </div>
        </a>

        <!-- Dropdown utilisateur -->
        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border" aria-labelledby="navbarDropdownUser">
          <div class="card position-relative border-0">
            <div class="card-body p-0">
              <div class="text-center pt-4 pb-3">
                <div class="avatar avatar-xl ">
                  <img class="rounded-circle" src="https://th.bing.com/th?id=OIP.fqSvfYQB0rQ-6EG_oqvonQHaHa&w=250&h=250&c=8&rs=1&qlt=90&o=6&pid=3.1&rm=2" alt="Photo de profil" />
                </div>
                
                <!-- Nom de l'utilisateur -->
                <!-- <h6 class="mt-2 text-body-emphasis"><?php echo ucfirst($_SESSION['utilisateur']['nom']);?></h6> -->
                <h6 class="mt-2 text-body-emphasis">Jean Pharmacien</h6>
              </div>
            </div>

            <!-- Liens dans la dropdown -->
            <div class="overflow-auto scrollbar">
              <ul class="nav d-flex flex-column mb-2 pb-1">
                <!-- Lien vers mon compte -->
                <div class="card-footer p-0 border-top border-translucent">
                  <div class="p-2"> 
                    <!-- <a class="btn btn-phoenix-secondary d-flex flex-center w-100" href="mon_compte.php?utilisateur=<?php echo $_SESSION['utilisateur']['id']; ?>"> -->
                    <a class="btn btn-phoenix-secondary d-flex flex-center w-100" href="mon_compte.php">
                      <span class="me-2 text-body" data-feather="settings"></span> Mon compte
                    </a>
                  </div>
                </div>

                <!-- Lien vers la déconnexion -->
                <div class="card-footer p-0 border-top border-translucent">
                  <div class="p-2"> 
                    <a class="btn btn-phoenix-secondary d-flex flex-center w-100" href="deconnexion.php"> 
                      <span class="me-2" data-feather="log-out"></span> Déconnexion
                    </a>
                  </div>
                </div>
              </ul>
            </div>

          </div>
        </div>
      </li>
    </ul>
  </div>
</nav>
