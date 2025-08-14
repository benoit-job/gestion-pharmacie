<nav class="navbar navbar-top fixed-top navbar-expand" id="navbarDefault">
  <div class="collapse navbar-collapse justify-content-between">
    <div class="navbar-logo">
      <!-- Bouton pour ouvrir la sidebar -->
      <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation">
        <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
      </button>

      <!-- Logo + Nom du site -->
      <a class="navbar-brand me-1 me-sm-3" href="accueil.php">    
        <div class="d-flex align-items-center">
          <div class="d-flex align-items-center">
            <img id='imgLogo' src="assets/img/icons/logo.png" class='rounded' height='40px'/>
            <p class="logo-text ms-2 text-truncate d-inline-block" 
              style="max-width: 150px;" 
              data-bs-toggle="tooltip" 
              data-bs-placement="bottom" 
              title="PharmaGestion">
              PharmaGestion
            </p>
          </div> 
        </div>
      </a>
    </div>
    
    <div class="search-box navbar-top-search-box d-none d-lg-block" style="width:25rem;">
      <form class="position-relative" method="post" action="javascript:void(0);">
        <input class="form-control search-input rounded-pill form-control-sm"
               type="search"
               name="search_term"
               placeholder="Rechercher un souscripteur..."
               aria-label="Rechercher un souscripteur"
               autocomplete="off" />
        <span class="fas fa-search search-box-icon"></span>
      </form>

      <button type="button"
              class="btn-close position-absolute end-0 top-50 translate-middle cursor-pointer shadow-none"
              aria-label="Fermer"
              data-bs-dismiss="search"
              style="display: none;">
      </button>
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
                  <img class="rounded-circle" src="<?php echo !empty($_SESSION['user']['logo']) ? $_SESSION['user']['logo'] : 'https://th.bing.com/th?id=OIP.fqSvfYQB0rQ-6EG_oqvonQHaHa&w=250&h=250&c=8&rs=1&qlt=90&o=6&pid=3.1&rm=2'; ?>"/>
          </div>
        </a>

        <!-- Dropdown utilisateur -->
        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border" aria-labelledby="navbarDropdownUser">
          <div class="card position-relative border-0">
            <div class="card-body p-0">
              <div class="text-center pt-4 pb-3">
                <div class="avatar avatar-xl ">
                  <img class="rounded-circle" src="<?php echo !empty($_SESSION['user']['logo']) ? $_SESSION['user']['logo'] : 'https://th.bing.com/th?id=OIP.fqSvfYQB0rQ-6EG_oqvonQHaHa&w=250&h=250&c=8&rs=1&qlt=90&o=6&pid=3.1&rm=2'; ?>"/>
                </div>
                <h6 class="mt-2 text-body-emphasis"><?php echo htmlspecialchars($_SESSION['user']['pseudo'] ?? '');?></h6>
              </div>
            </div>

            <!-- Liens dans la dropdown -->
            <div class="overflow-auto scrollbar">
              <ul class="nav d-flex flex-column mb-2 pb-1">
                <!-- Lien vers mon compte -->
                <div class="card-footer p-0 border-top border-translucent">
                  <div class="p-2"> 
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

<style>
.lds-dual-ring {
    display: inline-block;
    width: 24px;
    height: 24px;
}
.lds-dual-ring:after {
    content: " ";
    display: block;
    width: 20px;
    height: 20px;
    margin: 2px;
    border-radius: 50%;
    border: 3px solid #0d6efd;
    border-color: #0d6efd transparent #0d6efd transparent;
    animation: lds-dual-ring 1.2s linear infinite;
}
@keyframes lds-dual-ring {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(function() {
    if (typeof $ === 'undefined') {
        console.error('jQuery est requis pour le script de recherche.');
        return;
    }

    let searchTimeout;
    let lastSearch = '';
    let isSearching = false;
    const $searchInput = $('.search-input');
    const $searchContainer = $('.search-box');
    const $closeBtn = $('.btn-close[data-bs-dismiss="search"]');

    // Crée le conteneur de résultats si inexistant
    if ($('.search-results-container').length === 0) {
        $searchContainer.append(`
            <div class="search-results-container position-absolute w-100 bg-white border rounded shadow-sm"
                 role="listbox"
                 aria-live="polite"
                 style="top: 100%; left: 0; z-index: 1050; max-height: 400px; overflow-y: auto; display: none;">
                <div class="search-results-content p-2"></div>
            </div>
        `);
    }

    const $resultsContainer = $('.search-results-container');
    const $resultsContent = $('.search-results-content');

    function performSearch(searchTerm) {
        if (searchTerm.length < 2) {
            hideResults();
            return;
        }
        
        if (searchTerm === lastSearch && !isSearching) {
            return;
        }
        
        lastSearch = searchTerm;
        isSearching = true;
        
        // Afficher le spinner pendant au moins 500ms pour un bon feedback visuel
        showLoader();
        
        const startTime = Date.now();
        
        $.ajax({
            url: 'ajax/recherche_souscripteurs.php',
            method: 'POST',
            data: { search_term: searchTerm },
            dataType: 'json',
            timeout: 10000
        })
        .done(function(response) {
            const elapsed = Date.now() - startTime;
            const minDelay = Math.max(0, 500 - elapsed); // Au minimum 500ms de spinner
            
            setTimeout(function() {
                isSearching = false;
                if (response.error) {
                    showError(response.error);
                } else if (Array.isArray(response)) {
                    displayResults(response);
                } else {
                    showError('Format de réponse invalide');
                }
            }, minDelay);
        })
        .fail(function(xhr, status, error) {
            const elapsed = Date.now() - startTime;
            const minDelay = Math.max(0, 500 - elapsed);
            
            setTimeout(function() {
                isSearching = false;
                let errorMessage = 'Erreur lors de la recherche';
                
                if (status === 'timeout') {
                    errorMessage = 'La recherche a pris trop de temps';
                } else if (status === 'error' && xhr.status === 404) {
                    errorMessage = 'Service de recherche non trouvé';
                } else if (status === 'error' && xhr.status === 500) {
                    errorMessage = 'Erreur serveur interne';
                }
                
                showError(errorMessage);
                console.error('Erreur AJAX:', status, error, xhr.responseText);
            }, minDelay);
        });
    }

    function showLoader() {
        $resultsContent.html(`
          <div class="text-center py-4">
            <div class="lds-dual-ring"></div>
            <div class="text-muted mt-2">Recherche en cours...</div>
        </div>
        `);
        $resultsContainer.show();
        $closeBtn.show();
    }

    function showError(message) {
        $resultsContent.html(`
            <div class="text-center py-3 text-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>${message}
                <div class="mt-2">
                    <button class="btn btn-sm btn-outline-secondary retry-search">
                        <i class="fas fa-redo me-1"></i>Réessayer
                    </button>
                </div>
            </div>
        `);
        $resultsContainer.show();
        $closeBtn.show();
    }

    function displayResults(results) {
        if (!results.length) {
            $resultsContent.html(`
                <div class="text-center py-3 text-muted">
                    <i class="fas fa-search me-2"></i>Aucun souscripteur trouvé pour "${lastSearch}"
                </div>
            `);
        } else {
            let html = '<div class="list-group list-group-flush">';
            results.forEach((s, index) => {
                const icon = s.secteur_activite?.toLowerCase().includes('pharmacie') ? 'fas fa-pills' :
                             s.secteur_activite?.toLowerCase().includes('hospitaliere') ? 'fas fa-hospital' :
                             'fas fa-user';
                             console.log("ID SOUSCRIPTEUR", s.id_crypte);
                html += `
                    <a href="update_souscripteurs.php?id_souscripteur=${encodeURIComponent(s.id_crypte)}"
                       class="list-group-item list-group-item-action souscripteur-item border-0"
                       data-id="${encodeURIComponent(s.id_crypte || '')}"
                       role="option" 
                       tabindex="0"
                       aria-describedby="result-${index}">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="${icon} text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold">
                                    ${[s.civilite, s.nom, s.prenom].filter(Boolean).join(' ')}
                                </h6>
                                <p class="mb-1 text-muted small">
                                    ${s.nom_etablissement || 'Établissement non renseigné'}
                                </p>
                                ${s.telephone_portable ? 
                                    `<small class="text-muted">📱 ${s.telephone_portable}</small>` : ''}
                                ${s.secteur_activite ? 
                                    `<small class="text-info d-block">${s.secteur_activite}</small>` : ''}
                            </div>
                            <small class="text-primary ms-2">
                                <i class="fas fa-arrow-right"></i>
                            </small>
                        </div>
                    </a>
                `;
            });
            html += '</div>';
            $resultsContent.html(html);
        }
        $resultsContainer.show();
        $closeBtn.show();
    }

    function hideResults() {
        $resultsContainer.hide();
        $closeBtn.hide();
        $('.souscripteur-item').removeClass('active');
    }

    function clearSearch() {
        $searchInput.val('');
        hideResults();
        lastSearch = '';
        $searchInput.focus();
    }

    // Événements
    $searchInput.on('input', function() {
        clearTimeout(searchTimeout);
        const term = $(this).val().trim();
        
        if (term.length === 0) {
            hideResults();
            return;
        }
        
        searchTimeout = setTimeout(() => performSearch(term), 300);
    });

    $searchInput.on('focus', function() {
        const term = $(this).val().trim();
        if (term.length >= 2 && $resultsContainer.find('.search-results-content').children().length > 0) {
            $resultsContainer.show();
            $closeBtn.show();
        }
    });

    // Clic sur un élément de résultat
    $(document).on('click', '.souscripteur-item', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        if (id) {
            window.location.href = `update_souscripteurs.php?id_souscripteur=${id}`;
        }
    });

    // Bouton réessayer
    $(document).on('click', '.retry-search', function(e) {
        e.preventDefault();
        const term = $searchInput.val().trim();
        if (term) {
            isSearching = false;
            performSearch(term);
        }
    });

    // Bouton fermer
    $closeBtn.on('click', function(e) {
        e.preventDefault();
        clearSearch();
    });

    // Clic à l'extérieur
    $(document).on('click', function(e) {
        if (!$searchContainer.is(e.target) && $searchContainer.has(e.target).length === 0) {
            hideResults();
        }
    });

    // Navigation au clavier
    $searchInput.on('keydown', function(e) {
        const $items = $('.souscripteur-item:visible');
        const $current = $('.souscripteur-item.active');
        let $next;

        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                if ($items.length === 0) return;
                
                $next = $current.length ? $current.removeClass('active').next('.souscripteur-item') : $items.first();
                if (!$next.length) $next = $items.first();
                $next.addClass('active').focus();
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                if ($items.length === 0) return;
                
                $next = $current.length ? $current.removeClass('active').prev('.souscripteur-item') : $items.last();
                if (!$next.length) $next = $items.last();
                $next.addClass('active').focus();
                break;
                
            case 'Enter':
                e.preventDefault();
                const $target = $current.length ? $current : $items.first();
                if ($target.length) {
                    $target[0].click();
                }
                break;
                
            case 'Escape':
                e.preventDefault();
                hideResults();
                $(this).blur();
                break;
        }
    });

    // Gestion du focus sur les éléments de résultat
    $(document).on('keydown', '.souscripteur-item', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            $(this)[0].click();
        }
    });
});
</script>