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
<style>
    .export-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .export-btn {
            position: relative;
            width: 50px;
            height: 40px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            transform-origin: center;
        }

        .export-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s ease;
        }

        .export-btn:hover::before {
            left: 100%;
        }

        .export-btn:hover {
            transform: translateY(-8px) scale(1.1);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
        }

        .export-btn:active {
            transform: translateY(-4px) scale(1.05);
            transition: all 0.1s ease;
        }

        /* Bouton Excel */
        .excel-btn {
            background: linear-gradient(135deg, #1d7347 0%, #4caf50 100%);
            border: 2px solid rgba(76, 175, 80, 0.3);
        }

        .excel-btn:hover {
            background: linear-gradient(135deg, #2e8b57 0%, #66bb6a 100%);
            border-color: rgba(76, 175, 80, 0.6);
        }

        /* Bouton Word */
        .word-btn {
            background: linear-gradient(135deg, #1565c0 0%, #2196f3 100%);
            border: 2px solid rgba(33, 150, 243, 0.3);
        }

        .word-btn:hover {
            background: linear-gradient(135deg, #1976d2 0%, #42a5f5 100%);
            border-color: rgba(33, 150, 243, 0.6);
        }

        /* Bouton PDF */
        .pdf-btn {
            background: linear-gradient(135deg, #c62828 0%, #f44336 100%);
            border: 2px solid rgba(244, 67, 54, 0.3);
        }

        .pdf-btn:hover {
            background: linear-gradient(135deg, #d32f2f 0%, #ef5350 100%);
            border-color: rgba(244, 67, 54, 0.6);
        }

        /* Icônes */
        .export-btn span {
            color: white;
            font-size: 1.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .export-btn:hover span {
            transform: scale(1.2) rotate(5deg);
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
        }

        /* Animation de flottement */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-float:nth-child(2) {
            animation-delay: -1s;
        }

        .animate-float:nth-child(3) {
            animation-delay: -2s;
        }

        /* Tooltip moderne */
        .tooltip-wrapper {
            position: relative;
            display: inline-block;
        }

        .tooltip-wrapper::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: -45px;
            left: 50%;
            transform: translateX(-50%) scale(0);
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            pointer-events: none;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .tooltip-wrapper::before {
            content: '';
            position: absolute;
            bottom: -37px;
            left: 50%;
            transform: translateX(-50%) scale(0);
            border: 6px solid transparent;
            border-bottom-color: rgba(0, 0, 0, 0.9);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .tooltip-wrapper:hover::after,
        .tooltip-wrapper:hover::before {
            transform: translateX(-50%) scale(1);
            opacity: 1;
        }

        /* Effet de particules (optionnel) */
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            pointer-events: none;
            animation: particle-float 4s linear infinite;
        }

        @keyframes particle-float {
            0% {
                opacity: 0;
                transform: translateY(0px) rotate(0deg);
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: translateY(-100px) rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .export-btn {
                width: 60px;
                height: 60px;
                margin: 0 10px !important;
            }
            
            .export-btn span {
                font-size: 1.2rem;
            }
            
            .export-container {
                padding: 20px;
            }
        }

        /* Titre décoratif */
        .title {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 300;
            font-size: 1.8rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.8);
            text-align: center;
            margin-bottom: 40px;
            font-size: 0.9rem;
            font-weight: 300;
        }
    </style>
    
<style>
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 0;
    }

    /* Amélioration des tooltips */
    [data-toggle="tooltip"] {
        cursor: pointer;
        position: relative;
    }

    /* Style pour les cellules avec texte tronqué */
    td.text-truncate {
        position: relative;
    }

    td.text-truncate:hover::after {
        content: attr(title);
        position: absolute;
        left: 0;
        top: 100%;
        background: #333;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        z-index: 100;
        white-space: normal;
        width: auto;
        max-width: 300px;
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
