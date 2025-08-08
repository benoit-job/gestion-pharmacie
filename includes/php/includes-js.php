
<script src="vendors/popper/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

<script src="vendors/tinymce/tinymce.min.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<!-- SheetJS pour Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- FileSaver pour Word et Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/docx@7.8.2/build/index.js"></script>
<script>
    // Initialisez docx comme variable globale
    var docx = window.docx;
</script>

<!-- html2canvas et jsPDF pour PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>



<script>
$(document).ready(function() {

    if($(window).width() < 768)
    {
        $('.content').css('padding-left', '2px');
        $('.content').css('padding-right', '2px');
        $('.content').css('overflow-x', 'hidden');
    }
});



  
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
</script>

<!-- Inclure les scripts nécessaires pour DataTables -->

<script>
    $(document).ready(function() {
        $('.usersTable').DataTable({
            "pageLength": 20, // 20 éléments par page
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json" // Français
            },
            "columnDefs": [
                { "orderable": false, "targets": [4] } // Désactiver le tri sur la colonne actions
            ]
        });
    });

    // Fonction générique de suppression
function confirmerSuppression() {
    // Écouteur pour tous les boutons de suppression
    $(document).on('click', '.btn-supprimer', function(e) {
        e.preventDefault();
        
        const element = $(this);
        const id = element.data('id');
        const type = element.data('type');
        const url = element.data('url');
        console.log('supprimer' + type.charAt(0).toUpperCase() + type.slice(1));
        
        Swal.fire({
            title: 'Confirmer la suppression',
            text: `Voulez-vous vraiment supprimer cet ${type} ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler',
            showClass: {
                popup: 'animate__animated animate__fadeInUp animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutDown animate__faster'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Créer un formulaire dynamique
                const form = $('<form>').attr({
                    method: 'post',
                    action: url
                }).append(
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'id_' + type,
                        value: id
                    }),
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'supprimer' + type.charAt(0).toUpperCase() + type.slice(1),
                        value: '1'
                    })
                );
                
                // Ajouter au DOM et soumettre
                $('body').append(form);
                form.submit();
                
                // Animation pendant le traitement
                Swal.fire({
                    title: 'Suppression en cours...',
                    html: 'Veuillez patienter',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    showClass: {
                        popup: 'animate__animated animate__fadeInUp'
                    }
                });
            }
        });
    });
}

// Initialiser la fonction au chargement
$(document).ready(function() {
    confirmerSuppression();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const regionModals = document.querySelectorAll('.regionModal');

  regionModals.forEach(function(modal) {
    modal.addEventListener('hide.bs.modal', function () {
      this.classList.add('hiding');
      setTimeout(() => {
        this.classList.remove('hiding');
      }, 300);
    });
  });
});

const choicesInstances = {}; // Pour garder chaque instance

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.organizerSingle').forEach(select => {
    const instance = new Choices(select, {
      removeItemButton: true,
      placeholder: true,
      searchEnabled: true,
      shouldSort: false,
      itemSelectText: '',
      classNames: {
        containerInner: 'choices__inner',
        input: 'choices__input',
      },
      position: 'auto',
      loadingText: 'Chargement...',
      renderSelectedChoices: 'always',
      noResultsText: 'Aucun résultat trouvé',
      noChoicesText: 'Aucun choix disponible',
    });

    // On garde l'instance associée au select
    choicesInstances[select.name] = instance;
  });
});


</script>

<script>
function showToast(type, message) {
    // Création du toast
    const toast = document.createElement('div');
    toast.style.position = 'fixed';
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.width = '300px';
    toast.style.padding = '15px';
    toast.style.borderRadius = '4px';
    toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    toast.style.zIndex = '9999';
    toast.style.display = 'flex';
    toast.style.flexDirection = 'column';
    toast.style.overflow = 'hidden';
    
    // Couleur en fonction du type
    if (type === 'success') {
        toast.style.backgroundColor = '#d1e7dd';
        toast.style.color = '#0f5132';
    } else if (type === 'error') {
        toast.style.backgroundColor = '#f8d7da';
        toast.style.color = '#842029';
    } else {
        toast.style.backgroundColor = '#fff3cd';
        toast.style.color = '#664d03';
    }
    
    // Message
    const messageEl = document.createElement('div');
    messageEl.textContent = message;
    messageEl.style.marginBottom = '10px';
    toast.appendChild(messageEl);
    
    // Barre de progression
    const progressBar = document.createElement('div');
    progressBar.style.height = '4px';
    progressBar.style.width = '100%';
    progressBar.style.backgroundColor = type === 'success' ? '#0f512eff' : type === 'error' ? '#842029' : '#664d03';
    progressBar.style.borderRadius = '2px';
    progressBar.style.overflow = 'hidden';
    
    const progressInner = document.createElement('div');
    progressInner.style.height = '100%';
    progressInner.style.width = '100%';
    progressInner.style.backgroundColor = type === 'success' ? '#75b798' : type === 'error' ? '#ea868f' : '#ffda6a';
    progressInner.style.transition = 'width 3s linear';
    
    progressBar.appendChild(progressInner);
    toast.appendChild(progressBar);
    
    // Ajout au DOM
    document.body.appendChild(toast);
    
    // Animation de la barre de progression
    setTimeout(() => {
        progressInner.style.width = '0%';
    }, 50);
    
    // Disparition après 3 secondes
    setTimeout(() => {
        toast.style.transition = 'opacity 0.5s';
        toast.style.opacity = '0';
        
        // Après disparition du toast, afficher l'icône centrale
        setTimeout(() => {
            document.body.removeChild(toast);
            showCenterIcon(type);
        }, 500);
    }, 3000);
}

function showCenterIcon(type) {
    // Création de l'overlay
    const overlay = document.createElement('div');
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.backgroundColor = 'rgba(0,0,0,0.5)';
    overlay.style.zIndex = '10000';
    overlay.style.display = 'flex';
    overlay.style.justifyContent = 'center';
    overlay.style.alignItems = 'center';
    
    // Création de l'icône
    const icon = document.createElement('div');
    icon.style.fontSize = '80px';
    icon.style.color = type === 'success' ? '#0f5132' : '#842029';
    icon.style.animation = 'bounceIn 0.5s';
    
    if (type === 'success') {
        icon.innerHTML = '✓';
    } else {
        icon.innerHTML = '✗';
    }
    
    overlay.appendChild(icon);
    document.body.appendChild(overlay);
    
    // Disparition après 1 seconde
    setTimeout(() => {
        overlay.style.transition = 'opacity 0.5s';
        overlay.style.opacity = '0';
        
        // Actualisation après disparition
        setTimeout(() => {
            document.body.removeChild(overlay);
            window.location.reload();
        }, 500);
    }, 1000);
}

// Ajout de l'animation bounceIn
const style = document.createElement('style');
style.textContent = `
@keyframes bounceIn {
    0% { transform: scale(0.1); opacity: 0; }
    60% { transform: scale(1.2); opacity: 1; }
    100% { transform: scale(1); }
}
`;
document.head.appendChild(style);
</script>

<script>
// Fonction pour afficher les toasts
function showToastSupp(type, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    
    Toast.fire({
        icon: type,
        title: message
    });
}

// Vérifier et afficher le toast au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    <?php if(isset($_SESSION['toast'])): ?>
        showToastSupp('<?php echo $_SESSION['toast']['type']; ?>', '<?php echo $_SESSION['toast']['message']; ?>');
        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {

    // Gestion générique des boutons
    document.querySelectorAll('.excel-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            exportToExcel(this);
        });
    });
    document.querySelectorAll('.word-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            exportToWord(this);
        });
    });
    document.querySelectorAll('.pdf-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            exportToPDF(this);
        });
    });

    // Récupère le tableau lié au bouton cliqué
    function getRelatedTable(button) {
        return button.closest('.card').querySelector('table');
    }

    // Génère le nom du fichier avec date du jour
    function generateFileName(table, extension) {
        const title = table.dataset.title || "export";
        const date = new Date().toISOString().slice(0,10); // YYYY-MM-DD
        return `${title}_${date}.${extension}`;
    }

    // Export Excel
    function exportToExcel(button) {
        const table = getRelatedTable(button);
        const clonedTable = table.cloneNode(true);

        clonedTable.querySelectorAll('.no_export').forEach(el => el.remove());
        clonedTable.querySelectorAll('.hidden').forEach(el => el.classList.remove('hidden'));

        const workbook = XLSX.utils.table_to_book(clonedTable);
        XLSX.writeFile(workbook, generateFileName(table, 'xlsx'));

        showToastSupp('success', 'Export Excel réussi !');
    }

    // Export Word
    function exportToWord(button) {
        try {
            const table = getRelatedTable(button);
            const clonedTable = table.cloneNode(true);

            clonedTable.querySelectorAll('.no_export').forEach(el => el.remove());
            clonedTable.querySelectorAll('.hidden').forEach(el => el.classList.remove('hidden'));

            const { Document, Paragraph, Table, TableRow, TableCell, Packer, HeadingLevel } = docx;
            const rows = [];
            const tableRows = clonedTable.querySelectorAll('tr');

            // En-têtes
            const headerCells = [];
            tableRows[0].querySelectorAll('th').forEach(th => {
                headerCells.push(
                    new TableCell({
                        children: [new Paragraph({
                            text: th.textContent,
                            heading: HeadingLevel.HEADING_4
                        })],
                        shading: { fill: "DDDDDD" }
                    })
                );
            });
            rows.push(new TableRow({ children: headerCells }));

            // Lignes
            for (let i = 1; i < tableRows.length; i++) {
                const cells = [];
                tableRows[i].querySelectorAll('td').forEach(td => {
                    cells.push(new TableCell({ children: [new Paragraph(td.textContent)] }));
                });
                rows.push(new TableRow({ children: cells }));
            }

            const doc = new Document({
                sections: [{
                    children: [
                        new Paragraph({
                            text: table.dataset.title || "Export",
                            heading: HeadingLevel.HEADING_1
                        }),
                        new Paragraph({
                            text: new Date().toLocaleDateString(),
                            spacing: { after: 200 }
                        }),
                        new Table({ rows: rows, width: { size: 100, type: "PERCENTAGE" } })
                    ]
                }]
            });

            Packer.toBlob(doc).then(blob => {
                saveAs(blob, generateFileName(table, 'docx'));
                showToastSupp('success', 'Export Word réussi !');
            });

        } catch (error) {
            console.error("Erreur lors de l'export Word:", error);
            showToastSupp('error', 'Échec de l\'export Word');
        }
    }

    // Export PDF
    function exportToPDF(button) {
    const table = getRelatedTable(button);
    const clonedTable = table.cloneNode(true);

    clonedTable.querySelectorAll('.no_export').forEach(el => el.remove());
    clonedTable.querySelectorAll('.hidden').forEach(el => el.classList.remove('hidden'));

    const headers = [];
    const body = [];

    const headerCells = clonedTable.querySelectorAll('thead tr th');
    headerCells.forEach(th => {
        headers.push(th.textContent.trim());
    });

    const rows = clonedTable.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const rowData = [];
        row.querySelectorAll('td').forEach(td => {
            rowData.push(td.textContent.trim());
        });
        body.push(rowData);
    });

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('landscape', 'mm', 'a4');

    doc.setFontSize(14);
    doc.text(table.dataset.title || "Export", 14, 15);

    doc.autoTable({
        head: [headers],
        body: body,
        startY: 20,
        styles: {
            fontSize: 8,
            cellPadding: 2,
        },
        headStyles: {
            fillColor: [221, 221, 221],
        },
        theme: 'grid'
    });

    doc.save(generateFileName(table, 'pdf'));
    showToastSupp('success', 'Export PDF réussi !');
}

});

</script>