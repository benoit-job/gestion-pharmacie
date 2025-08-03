<!-- Favicon et icônes -->

<!-- <link rel="apple-touch-icon" sizes="180x180" href="<?php echo setSrcImg($_SESSION["site"]["logo"]);?>"> -->
<link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/logo.png">

<!-- <link rel="icon" type="image/png" sizes="32x32" href="<?php echo setSrcImg($_SESSION["site"]["logo"]);?>"> -->
<link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/logo-32.png">

<!-- <link rel="icon" type="image/png" sizes="16x16" href="<?php echo setSrcImg($_SESSION["site"]["logo"]);?>"> -->
<link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/logo-16.png">

<!-- <link rel="shortcut icon" type="image/x-icon" href="<?php echo setSrcImg($_SESSION["site"]["logo"]);?>"> -->
<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">

<link rel="manifest" href="assets/img/favicons/manifest.json">

<!-- <meta name="msapplication-TileImage" content="<?php echo setSrcImg($_SESSION["site"]["logo"]);?>"> -->
<meta name="msapplication-TileImage" content="assets/img/favicons/logo-144.png">

<meta name="theme-color" content="#ffffff">

    <script src="vendors/simplebar/simplebar.min.js"></script>
    <script src="assets/js/config.js"></script>


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
    <link href="vendors/simplebar/simplebar.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link href="assets/css/theme-rtl.min.css" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="assets/css/theme.min.css" type="text/css" rel="stylesheet" id="style-default">
    <link href="assets/css/user-rtl.min.css" type="text/css" rel="stylesheet" id="user-style-rtl">
    <link href="assets/css/user.min.css" type="text/css" rel="stylesheet" id="user-style-default">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <style type="text/css">
      /* Masquer toutes les colonnes hidden */
.hidden {
    display: none !important;
}

      .table th, table td{padding: 5px 5px !important; vertical-align: middle !important;font-size: 0.8rem;}
      .table tbody, .table thead{white-space: nowrap;}
      #navbarVerticalCollapse .nav-item-wrapper img{width: 15px; height: 15px; margin-right: 5px;}
    </style>

    <style>
      /* Augmenter la taille des liens du menu */
.navbar-vertical .nav-link-text {
  font-size: 14px; /* par défaut ~14px */
  font-weight: 700; /* léger gras pour mieux lire */
}

/* Pour les petits textes "small" */
.navbar-vertical small {
  font-size: 14px;
}

        .nav-link-icon {
            font-size: 20px;
            margin-right: 12px;
            width: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .nav-link-icon i {
            font-size: 20px;
        }
        
        /* Couleurs des icônes */
        .icon-dashboard { color: #3b82f6; }
        .icon-users { color: #10b981; }
        .icon-payments { color: #f59e0b; }
        .icon-reports { color: #8b5cf6; }
        .icon-geography { color: #ef4444; }
        .icon-admin { color: #6b7280; }
        .icon-profile { color: #06b6d4; }
        .icon-settings { color: #d97706; }
        
        .nav-link {
            padding: 12px 16px;
            border-radius: 8px;
            margin: 2px 8px;
            transition: all 0.2s;
        }
        
        .nav-link:hover {
            background-color: #f8fafc;
            transform: translateX(2px);
        }
        
        .nav-link.active {
            background-color: #e0f2fe;
            color: #0369a1;
        }
        
        .dropdown-indicator-icon {
            transition: transform 0.2s;
        }
        
        .nav-link[aria-expanded="true"] .dropdown-indicator-icon {
            transform: rotate(90deg);
        }

        .nav .collapse {
          transition: height 0.3s ease;
          overflow: hidden;
        }

    </style>
    <!-- Animation CSS -->
    <style>
      .modal.fade .modal-dialog {
        transform: translateY(100%);
        transition: transform 0.3s ease-out;
      }
      
      .modal.show .modal-dialog {
        transform: translateY(0);
      }
      
      .modal.hiding .modal-dialog {
        transform: translateY(100%);
        transition: transform 0.3s ease-in;
      }
    </style>
    <style>
      /* Conteneur principal du dropdown */
      .choices__list--dropdown {
        max-height: 200px !important;
        overflow-y: auto !important;
        border: 1px solid #ddd !important;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
        z-index: 1000 !important;
      }
      
      /* Options individuelles */
      .choices__list--dropdown .choices__item {
        padding: 10px 15px !important;
        border-bottom: 1px solid #f5f5f5 !important;
        font-size: 14px !important;
        color: #333 !important;
      }
      
      /* Option survolée */
      .choices__list--dropdown .choices__item--selectable:hover {
        background-color: #f8f9fa !important;
        color: #000 !important;
      }
      
      /* Option sélectionnée */
      .choices__list--dropdown .choices__item--selected {
        background-color: #e9ecef !important;
        color: #000 !important;
      }
      
      /* Barre de défilement */
      .choices__list--dropdown::-webkit-scrollbar {
        width: 8px;
      }
      
      .choices__list--dropdown::-webkit-scrollbar-track {
        background: #f1f1f1;
      }
      
      .choices__list--dropdown::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
      }
      
      .choices__list--dropdown::-webkit-scrollbar-thumb:hover {
        background: #555;
      }
    </style>
    
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
