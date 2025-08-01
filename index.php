
<!DOCTYPE html>
<html data-navigation-type="default" data-navbar-horizontal-shape="default" lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Se connecter</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/logos/koueStore.webp">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/logos/koueStore.webp">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/logos/koueStore.webp">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logos/koueStore.webp">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/logos/koueStore.webp">
    <meta name="theme-color" content="#ffffff">
    <script src="vendors/simplebar/simplebar.min.js"></script>
    <script src="assets/js/config.js"></script>


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
    <link href="vendors/simplebar/simplebar.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link href="assets/css/theme-rtl.min.css" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="assets/css/theme.min.css" type="text/css" rel="stylesheet" id="style-default">
    <link href="assets/css/user-rtl.min.css" type="text/css" rel="stylesheet" id="user-style-rtl">
    <link href="assets/css/user.min.css" type="text/css" rel="stylesheet" id="user-style-default">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <script>
      var phoenixIsRTL = window.config.config.phoenixIsRTL;
      if (phoenixIsRTL) {
        var linkDefault = document.getElementById('style-default');
        var userLinkDefault = document.getElementById('user-style-default');
        linkDefault.setAttribute('disabled', true);
        userLinkDefault.setAttribute('disabled', true);
        document.querySelector('html').setAttribute('dir', 'rtl');
      } else {
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');
        linkRTL.setAttribute('disabled', true);
        userLinkRTL.setAttribute('disabled', true);
      }
    </script>
    

    <style type="text/css">
      body {
  margin: 0;
  padding: 0;
  background-image: url('assets/img/logos/background.jpg');
  background-size: cover;
  background-position: center;
  font-family: Arial, sans-serif;
  color: #333;
}

.container > .row {
  background: rgba(255, 255, 255, 0.85); /* fond blanc semi-transparent */
  padding: 30px 20px;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

/* Améliorer la lisibilité des labels */
.form-label {
  font-weight: 600;
  color: #1a1a1a;
}

/* Champ avec icône */
.form-icon-container {
  position: relative;
}

a.text-decoration-none:hover {
  color: #1e7e34;
  text-decoration: underline;
}


    </style>
    <!-- Ajoutez ces liens dans votre head -->

<style>
    /* Animation personnalisée */
    .form-icon-input:focus, .password:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .divider-content-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 0 1rem;
        background: white;
        color: #6c757d;
        font-size: 0.8rem;
    }
</style>

    
  </head>


  <body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
      <div class="container">
    <div class="row flex-center min-vh-100 py-5">
        <div class="col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-3">
            <a class="d-flex flex-center text-decoration-none mb-4" href="index.php">
                <div class="d-flex align-items-center fw-bolder fs-3 d-inline-block">
                    <img src="assets/img/logos/koueStore.webp" alt="KoueStore" width="75" class="rounded" />
                </div>
            </a>
            
            <!-- Animation d'entrée du formulaire -->
            <div class="card p-4 animate__animated animate__fadeInUp animate__faster" style="box-shadow: 0 5px 20px rgba(0,0,0,0.1); border: none;">
                <div class="text-center mb-3">
                    <h3 class="text-body-highlight">Se connecter</h3>
                </div>
                
                <form action="index.php" method="post">
                    <div class="position-relative">
                        <hr class="bg-body-secondary mt-3 mb-4" />
                        <div class="divider-content-center bg-white px-2">Via telephone or email</div>
                    </div>
                    
                    <div class="mb-3 text-start">
                        <label class="form-label" for="email">Contact/Email</label>
                        <div class="form-icon-container">
                            <input class="form-control form-icon-input" type="tel" name="telephone" placeholder="numéro ou email" id="telephone" required/>
                            <span class="fas fa-user text-body fs-9 form-icon"></span>
                        </div>
                    </div>
                    
                    <div class="mb-3 text-start">
                        <label class="form-label" for="password">PASSWORD</label>
                        <div class="position-relative">
                            <span class="fas fa-key text-body fs-9 position-absolute" style="left: 10px; top: 50%; transform: translateY(-50%);"></span>
                            <input class="form-control ps-5 pe-5 password" id="password" type="password" name="mot_de_passe" placeholder="password" required/>
                            <span class="fas fa-eye-slash text-body fs-9 position-absolute" id="toggle-password"
                            onclick="togglePassword()" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></span>
                        </div>
                    </div>
                    
                    <div class="row flex-between-center mb-4">
                       
                        <div class="col-auto">
                            <a class="fs-9 fw-semibold text-decoration-none" href="forgot-password.php">
                                <i class="fas fa-unlock-alt me-1"></i> Forgot Password?
                            </a>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <button class="btn btn-primary w-100" type="button" name="connexion" id="connexions">
                            Connectez-vous
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


      <script>
        var navbarTopStyle = window.config.config.phoenixNavbarTopStyle;
        var navbarTop = document.querySelector('.navbar-top');
        if (navbarTopStyle === 'darker') {
          navbarTop.setAttribute('data-navbar-appearance', 'darker');
        }

        var navbarVerticalStyle = window.config.config.phoenixNavbarVerticalStyle;
        var navbarVertical = document.querySelector('.navbar-vertical');
        if (navbarVertical && navbarVerticalStyle === 'darker') {
          navbarVertical.setAttribute('data-navbar-appearance', 'darker');
        }
      </script>

      <script>
        // Fonction pour afficher/masquer le mot de passe
        function togglePassword() {
          var passwordField = document.getElementById("password");
          var toggleIcon = document.getElementById("toggle-password");
          
          // Vérifier si le mot de passe est visible
          if (passwordField.type === "password") {
            passwordField.type = "text";  // Afficher le mot de passe
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
          } else {
            passwordField.type = "password";  // Cacher le mot de passe
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
          }
        }
      </script>
    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->


    <div class="offcanvas offcanvas-end settings-panel border-0" id="settings-offcanvas" tabindex="-1" aria-labelledby="settings-offcanvas">
      <div class="offcanvas-header align-items-start border-bottom flex-column border-translucent">
        <div class="pt-1 w-100 mb-6 d-flex justify-content-between align-items-start">
          <div>
            <h5 class="mb-2 me-2 lh-sm"><span class="fas fa-palette me-2 fs-8"></span>Theme Customizer</h5>
            <p class="mb-0 fs-9">Explore different styles according to your preferences</p>
          </div>
          <button class="btn p-1 fw-bolder" type="button" data-bs-dismiss="offcanvas" aria-label="Close"><span class="fas fa-times fs-8"> </span></button>
        </div>
        <button class="btn btn-phoenix-secondary w-100" data-theme-control="reset"><span class="fas fa-arrows-rotate me-2 fs-10"></span>Reset to default</button>
      </div>
      <div class="offcanvas-body scrollbar px-card" id="themeController">
        <div class="setting-panel-item mt-0">
          <h5 class="setting-panel-item-title">Color Scheme</h5>
          <div class="row gx-2">
            <div class="col-4">
              <input class="btn-check" id="themeSwitcherLight" name="theme-color" type="radio" value="light" data-theme-control="phoenixTheme" />
              <label class="btn d-inline-block btn-navbar-style fs-9" for="themeSwitcherLight"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype mb-0" src="assets/img/generic/default-light.png" alt=""/></span><span class="label-text">Light</span></label>
            </div>
            <div class="col-4">
              <input class="btn-check" id="themeSwitcherDark" name="theme-color" type="radio" value="dark" data-theme-control="phoenixTheme" />
              <label class="btn d-inline-block btn-navbar-style fs-9" for="themeSwitcherDark"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype mb-0" src="assets/img/generic/default-dark.png" alt=""/></span><span class="label-text"> Dark</span></label>
            </div>
            <div class="col-4">
              <input class="btn-check" id="themeSwitcherAuto" name="theme-color" type="radio" value="auto" data-theme-control="phoenixTheme" />
              <label class="btn d-inline-block btn-navbar-style fs-9" for="themeSwitcherAuto"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype mb-0" src="assets/img/generic/auto.png" alt=""/></span><span class="label-text"> Auto</span></label>
            </div>
          </div>
        </div>
        <div class="border border-translucent rounded-3 p-4 setting-panel-item bg-body-emphasis">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="setting-panel-item-title mb-1">RTL </h5>
            <div class="form-check form-switch mb-0">
              <input class="form-check-input ms-auto" type="checkbox" data-theme-control="phoenixIsRTL" />
            </div>
          </div>
          <p class="mb-0 text-body-tertiary">Change text direction</p>
        </div>
        <div class="border border-translucent rounded-3 p-4 setting-panel-item bg-body-emphasis">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="setting-panel-item-title mb-1">Support Chat </h5>
            <div class="form-check form-switch mb-0">
              <input class="form-check-input ms-auto" type="checkbox" data-theme-control="phoenixSupportChat" />
            </div>
          </div>
          <p class="mb-0 text-body-tertiary">Toggle support chat</p>
        </div>
        <div class="setting-panel-item">
          <h5 class="setting-panel-item-title">Navigation Type</h5>
          <div class="row gx-2">
            <div class="col-6">
              <input class="btn-check" id="navbarPositionVertical" name="navigation-type" type="radio" value="vertical" data-theme-control="phoenixNavbarPosition" data-page-url="documentation/layouts/vertical-navbar.html" />
              <label class="btn d-inline-block btn-navbar-style fs-9" for="navbarPositionVertical"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none" src="assets/img/generic/default-light.png" alt=""/><img class="img-fluid img-prototype d-light-none" src="assets/img/generic/default-dark.png" alt=""/></span><span class="label-text">Vertical</span></label>
            </div>
            <div class="col-6">
              <input class="btn-check" id="navbarPositionHorizontal" name="navigation-type" type="radio" value="horizontal" data-theme-control="phoenixNavbarPosition" data-page-url="documentation/layouts/horizontal-navbar.html" />
              <label class="btn d-inline-block btn-navbar-style fs-9" for="navbarPositionHorizontal"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none" src="assets/img/generic/top-default.png" alt=""/><img class="img-fluid img-prototype d-light-none" src="assets/img/generic/top-default-dark.png" alt=""/></span><span class="label-text"> Horizontal</span></label>
            </div>
            <div class="col-6">
              <input class="btn-check" id="navbarPositionCombo" name="navigation-type" type="radio" value="combo" data-theme-control="phoenixNavbarPosition" data-page-url="documentation/layouts/combo-navbar.html" />
              <label class="btn d-inline-block btn-navbar-style fs-9" for="navbarPositionCombo"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none" src="assets/img/generic/nav-combo-light.png" alt=""/><img class="img-fluid img-prototype d-light-none" src="assets/img/generic/nav-combo-dark.png" alt=""/></span><span class="label-text"> Combo</span></label>
            </div>
            <div class="col-6">
              <input class="btn-check" id="navbarPositionTopDouble" name="navigation-type" type="radio" value="dual-nav" data-theme-control="phoenixNavbarPosition" data-page-url="documentation/layouts/dual-nav.html" />
              <label class="btn d-inline-block btn-navbar-style fs-9" for="navbarPositionTopDouble"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none" src="assets/img/generic/dual-light.png" alt=""/><img class="img-fluid img-prototype d-light-none" src="assets/img/generic/dual-dark.png" alt=""/></span><span class="label-text"> Dual nav</span></label>
            </div>
          </div>
        </div>
        <div class="setting-panel-item">
          <h5 class="setting-panel-item-title">Vertical Navbar Appearance</h5>
          <div class="row gx-2">
            <div class="col-6">
              <input class="btn-check" id="navbar-style-default" type="radio" name="config.name" value="default" data-theme-control="phoenixNavbarVerticalStyle" />
              <label class="btn d-block w-100 btn-navbar-style fs-9" for="navbar-style-default"> <img class="img-fluid img-prototype d-dark-none" src="assets/img/generic/default-light.png" alt="" /><img class="img-fluid img-prototype d-light-none" src="assets/img/generic/default-dark.png" alt="" /><span class="label-text d-dark-none"> Default</span><span class="label-text d-light-none">Default</span></label>
            </div>
            <div class="col-6">
              <input class="btn-check" id="navbar-style-dark" type="radio" name="config.name" value="darker" data-theme-control="phoenixNavbarVerticalStyle" />
              <label class="btn d-block w-100 btn-navbar-style fs-9" for="navbar-style-dark"> <img class="img-fluid img-prototype d-dark-none" src="assets/img/generic/vertical-darker.png" alt="" /><img class="img-fluid img-prototype d-light-none" src="assets/img/generic/vertical-lighter.png" alt="" /><span class="label-text d-dark-none"> Darker</span><span class="label-text d-light-none">Lighter</span></label>
            </div>
          </div>
        </div>
        <div class="setting-panel-item">
          <h5 class="setting-panel-item-title">Horizontal Navbar Shape</h5>
          <div class="row gx-2">
            <div class="col-6">
              <input class="btn-check" id="navbarShapeDefault" name="navbar-shape" type="radio" value="default" data-theme-control="phoenixNavbarTopShape" data-page-url="documentation/layouts/horizontal-navbar.html" />
              <label class="btn d-inline-block btn-navbar-style fs-9" for="navbarShapeDefault"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none mb-0" src="assets/img/generic/top-default.png" alt=""/><img class="img-fluid img-prototype d-light-none mb-0" src="assets/img/generic/top-default-dark.png" alt=""/></span><span class="label-text">Default</span></label>
            </div>
            <div class="col-6">
              <input class="btn-check" id="navbarShapeSlim" name="navbar-shape" type="radio" value="slim" data-theme-control="phoenixNavbarTopShape" data-page-url="documentation/layouts/horizontal-navbar.html#horizontal-navbar-slim" />
              <label class="btn d-inline-block btn-navbar-style fs-9" for="navbarShapeSlim"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none mb-0" src="assets/img/generic/top-slim.png" alt=""/><img class="img-fluid img-prototype d-light-none mb-0" src="assets/img/generic/top-slim-dark.png" alt=""/></span><span class="label-text"> Slim</span></label>
            </div>
          </div>
        </div>
        <div class="setting-panel-item">
          <h5 class="setting-panel-item-title">Horizontal Navbar Appearance</h5>
          <div class="row gx-2">
            <div class="col-6">
              <input class="btn-check" id="navbarTopDefault" name="navbar-top-style" type="radio" value="default" data-theme-control="phoenixNavbarTopStyle" />
              <label class="btn d-inline-block btn-navbar-style fs-9" for="navbarTopDefault"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none mb-0" src="assets/img/generic/top-default.png" alt=""/><img class="img-fluid img-prototype d-light-none mb-0" src="assets/img/generic/top-style-darker.png" alt=""/></span><span class="label-text">Default</span></label>
            </div>
            <div class="col-6">
              <input class="btn-check" id="navbarTopDarker" name="navbar-top-style" type="radio" value="darker" data-theme-control="phoenixNavbarTopStyle" />
              <label class="btn d-inline-block btn-navbar-style fs-9" for="navbarTopDarker"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none mb-0" src="assets/img/generic/navbar-top-style-light.png" alt=""/><img class="img-fluid img-prototype d-light-none mb-0" src="assets/img/generic/top-style-lighter.png" alt=""/></span><span class="label-text d-dark-none">Darker</span><span class="label-text d-light-none">Lighter</span></label>
            </div>
          </div>
        </div><a class="bun btn-primary d-grid mb-3 text-white mt-5 btn btn-primary" href="https://themes.getbootstrap.com/product/phoenix-admin-dashboard-webapp-template/" target="_blank">Purchase template</a>
      </div>
    </div><a class="card setting-toggle" href="#settings-offcanvas" data-bs-toggle="offcanvas">
      <div class="card-body d-flex align-items-center px-2 py-1">
        <div class="position-relative rounded-start" style="height:34px;width:28px">
          <div class="settings-popover"><span class="ripple"><span class="fa-spin position-absolute all-0 d-flex flex-center"><span class="icon-spin position-absolute all-0 d-flex flex-center">
                  <svg width="20" height="20" viewBox="0 0 20 20" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.7369 12.3941L19.1989 12.1065C18.4459 11.7041 18.0843 10.8487 18.0843 9.99495C18.0843 9.14118 18.4459 8.28582 19.1989 7.88336L19.7369 7.59581C19.9474 7.47484 20.0316 7.23291 19.9474 7.03131C19.4842 5.57973 18.6843 4.28943 17.6738 3.20075C17.5053 3.03946 17.2527 2.99914 17.0422 3.12011L16.393 3.46714C15.6883 3.84379 14.8377 3.74529 14.1476 3.3427C14.0988 3.31422 14.0496 3.28621 14.0002 3.25868C13.2568 2.84453 12.7055 2.10629 12.7055 1.25525V0.70081C12.7055 0.499202 12.5371 0.297594 12.2845 0.257272C10.7266 -0.105622 9.16879 -0.0653007 7.69516 0.257272C7.44254 0.297594 7.31623 0.499202 7.31623 0.70081V1.23474C7.31623 2.09575 6.74999 2.8362 5.99824 3.25599C5.95774 3.27861 5.91747 3.30159 5.87744 3.32493C5.15643 3.74527 4.26453 3.85902 3.53534 3.45302L2.93743 3.12011C2.72691 2.99914 2.47429 3.03946 2.30587 3.20075C1.29538 4.28943 0.495411 5.57973 0.0322686 7.03131C-0.051939 7.23291 0.0322686 7.47484 0.242788 7.59581L0.784376 7.8853C1.54166 8.29007 1.92694 9.13627 1.92694 9.99495C1.92694 10.8536 1.54166 11.6998 0.784375 12.1046L0.242788 12.3941C0.0322686 12.515 -0.051939 12.757 0.0322686 12.9586C0.495411 14.4102 1.29538 15.7005 2.30587 16.7891C2.47429 16.9504 2.72691 16.9907 2.93743 16.8698L3.58669 16.5227C4.29133 16.1461 5.14131 16.2457 5.8331 16.6455C5.88713 16.6767 5.94159 16.7074 5.99648 16.7375C6.75162 17.1511 7.31623 17.8941 7.31623 18.7552V19.2891C7.31623 19.4425 7.41373 19.5959 7.55309 19.696C7.64066 19.7589 7.74815 19.7843 7.85406 19.8046C9.35884 20.0925 10.8609 20.0456 12.2845 19.7729C12.5371 19.6923 12.7055 19.4907 12.7055 19.2891V18.7346C12.7055 17.8836 13.2568 17.1454 14.0002 16.7312C14.0496 16.7037 14.0988 16.6757 14.1476 16.6472C14.8377 16.2446 15.6883 16.1461 16.393 16.5227L17.0422 16.8698C17.2527 16.9907 17.5053 16.9504 17.6738 16.7891C18.7264 15.7005 19.4842 14.4102 19.9895 12.9586C20.0316 12.757 19.9474 12.515 19.7369 12.3941ZM10.0109 13.2005C8.1162 13.2005 6.64257 11.7893 6.64257 9.97478C6.64257 8.20063 8.1162 6.74905 10.0109 6.74905C11.8634 6.74905 13.3792 8.20063 13.3792 9.97478C13.3792 11.7893 11.8634 13.2005 10.0109 13.2005Z" fill="#2A7BE4"></path>
                  </svg></span></span></span></div>
        </div><small class="text-uppercase text-body-tertiary fw-bold py-2 pe-2 ps-1 rounded-end">customize</small>
      </div>
    </a>


    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
     <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="vendors/popper/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/anchorjs/anchor.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="vendors/fontawesome/all.min.js"></script>
    <script src="vendors/lodash/lodash.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="vendors/list.js/list.min.js"></script>
    <script src="vendors/feather-icons/feather.min.js"></script>
    <script src="vendors/dayjs/dayjs.min.js"></script>
    <script src="assets/js/phoenix.js"></script>

  </body>

</html>
<script>
  
function pop_notif(c, t, i) {
    if (typeof i === typeof undefined) i = '';
    
    // Icônes et couleurs pour chaque type de notification
    const notificationStyles = {
        'success': {
            icon: '✓',
            color: '#4CAF50',
            bgColor: '#EDF7ED',
            iconColor: '#4CAF50'
        },
        'error': {
            icon: '✕',
            color: '#F44336',
            bgColor: '#FDEDED',
            iconColor: '#F44336'
        },
        'warning': {
            icon: '⚠',
            color: '#FF9800',
            bgColor: '#FFF4E5',
            iconColor: '#FF9800'
        },
        'info': {
            icon: 'ℹ',
            color: '#2196F3',
            bgColor: '#E5F6FD',
            iconColor: '#2196F3'
        }
    };
    
    // Style par défaut si le type n'est pas reconnu
    const style = notificationStyles[c] || notificationStyles.info;
    
    // Création de la notification avec un design moderne
    const notificationHTML = `
    <div class="pop_notif" id="pop_notif_${i}" style="background: ${style.bgColor}; border-left: 4px solid ${style.color};">
        <div class="notification-content">
            <span class="notification-icon" style="color: ${style.iconColor}">${style.icon}</span>
            <p class="text_notif" style="color: ${style.color}">${t}</p>
            <a class="close" style="color: ${style.color}">×</a>
        </div>
    </div>
    `;
    
    $('body').append(notificationHTML);
    
    // Fond semi-transparent
    $('body').append(`<div id="fade_${i}" class="pop_notif_fade"></div>`);
    let next_index = 10000 + ($('.pop_notif').length * 2) + 1;
    
    // Positionnement centré
    const $notification = $(`#pop_notif_${i}`);
    const notificationWidth = $notification.outerWidth();
    const notificationHeight = $notification.outerHeight();
    
    // Styles CSS dynamiques
    $notification.css({
        'position': 'fixed',
        'top': '50%',
        'left': '50%',
        'transform': 'translate(-50%, -50%)',
        'padding': '20px',
        'border-radius': '8px',
        'box-shadow': '0 4px 12px rgba(0,0,0,0.15)',
        'max-width': '90%',
        'width': 'auto',
        'min-width': '300px',
        'display': 'flex',
        'align-items': 'center',
        'z-index': next_index + 1,
        'opacity': '0',
        'transition': 'opacity 0.3s ease'
    });
    
    $('.notification-content').css({
        'display': 'flex',
        'align-items': 'center',
        'width': '100%'
    });
    
    $('.notification-icon').css({
        'font-size': '24px',
        'margin-right': '15px',
        'flex-shrink': '0'
    });
    
    $('.text_notif').css({
        'margin': '0',
        'flex-grow': '1',
        'font-size': '16px',
        'line-height': '1.5'
    });
    
    $('.close').css({
        'font-size': '24px',
        'margin-left': '15px',
        'cursor': 'pointer',
        'flex-shrink': '0',
        'text-decoration': 'none',
        'font-weight': 'bold',
        'opacity': '0.7',
        'transition': 'opacity 0.2s',
        'align-self': 'flex-start'
    }).hover(function() {
        $(this).css('opacity', '1');
    }, function() {
        $(this).css('opacity', '0.7');
    });
    
    $('#fade_' + i).css({
        'position': 'fixed',
        'top': '0',
        'left': '0',
        'width': '100%',
        'height': '100%',
        'background': 'rgba(0,0,0,0.5)',
        'z-index': next_index,
        'opacity': '0',
        'transition': 'opacity 0.3s ease'
    }).fadeTo(200, 0.5);
    
    $notification.fadeTo(200, 1);
    
    $('body').addClass('pop_notif_open');
    
    // Fermeture de la notification
    $(`#pop_notif_${i} a.close, #fade_${i}`).on('click', function() {
        $('#fade_' + i).fadeOut(200);
        $notification.fadeOut(200, function() {
            $(this).remove();
            $('#fade_' + i).remove();
            if ($('.pop_notif').length === 0) {
                $('body').removeClass('pop_notif_open');
            }
        });
    });
    
    // Fermeture automatique pour les notifications simples
    if (!i) {
        setTimeout(function() {
            $('#fade_' + i).fadeOut(200);
            $notification.fadeOut(200, function() {
                $(this).remove();
                $('#fade_' + i).remove();
                if ($('.pop_notif').length === 0) {
                    $('body').removeClass('pop_notif_open');
                }
            });
        }, 5000);
    }
    
    return false;
}
   
   
$("#connexions").click(function () {
    var telephone = $('#telephone').val();
    var password = $('.password').val();

$("#connexions").html(`
  <span class="loading-spinner" style="display: inline-flex; align-items: center; color: white;">
    <svg width="32" height="32" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="margin-right: 10px;">
      <path fill="white" d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".15"/>
      <path fill="white" d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z">
        <animateTransform attributeName="transform" type="rotate" dur="1.2s" values="0 12 12;360 12 12" repeatCount="indefinite"/>
      </path>
    </svg>
    <span style="font-size: 1.1em;">Connexion en cours...</span>
  </span>
`);
    $.ajax({
        url: "ajax.php",
        method: "POST",
        data: {
            'telephone': telephone,
            'password': password,
            'connexion': ''
        },
        dataType: "html",
        success: function (data) {
            setTimeout(function () {
                $("#connexions").html("Connectez-vous");

                if (data.trim() == "succes") {
                    pop_notif('success', 'Connexion réussie ! Redirection en cours...');
                    setTimeout(function () {
                        window.location.href = "accueil.php";
                    }, 3000);
                } else {
                    pop_notif('error', data);
                    $("#message").css("color", "red").html(data);
                }
            }, 2000);
        },
        error: function () {
            pop_notif('error', 'Erreur de connexion au serveur');
        }
    });
});

// Quand on appuie sur la touche Entrée dans n’importe quel champ
$(document).on("keypress", function (e) {
    if (e.which === 13) { // 13 = touche Entrée
        $("#connexions").click();
    }
});

</script>