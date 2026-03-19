const SidebarManager = {
    init: function() {
        this.loadSidebar();
        this.setActiveMenu();
        this.initMenuHandlers();
    },

    loadSidebar: function() {
        fetch('/frontend/src/pages/admin-ukm/js/sidebar.html')
            .then(response => response.text())
            .then(html => {
                document.querySelector('#sidebar-container').innerHTML = html;
                this.initMenuHandlers();
                this.setActiveMenu();
                
                if ($.fn.overlayScrollbars) {
                    $('.nav-sidebar').overlayScrollbars({
                        className: 'os-theme-light',
                        sizeAutoCapable: true,
                        scrollbars: {
                            autoHide: 'leave',
                            clickScrolling: true
                        }
                    });
                }
            })
            .catch(error => console.error('Error loading sidebar:', error));
    },

    initMenuHandlers: function() {
        // Clear any existing event handlers
        $(document).off('click', '.nav-item.has-treeview > .nav-link');
        $(document).off('click', '.nav-treeview .nav-link');

        // Handle dropdown menu clicks
        $(document).on('click', '.nav-item.has-treeview > .nav-link', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $menuItem = $(this).parent('.nav-item.has-treeview');
            const $subMenu = $menuItem.children('.nav-treeview');
            
            // Close other open menus
            $('.nav-item.has-treeview').not($menuItem).removeClass('menu-open')
                .children('.nav-treeview').slideUp('fast');
            
            // Toggle current menu
            $menuItem.toggleClass('menu-open');
            
            if ($menuItem.hasClass('menu-open')) {
                $subMenu.slideDown('fast');
            } else {
                $subMenu.slideUp('fast');
            }
        });

        // Handle submenu item clicks
        $(document).on('click', '.nav-treeview .nav-link', function(e) {
            $('.nav-treeview .nav-link').removeClass('active');
            $(this).addClass('active');
            
            // Keep parent menu open
            const $parentMenu = $(this).closest('.nav-item.has-treeview');
            $parentMenu.addClass('menu-open');
            $parentMenu.children('.nav-link').addClass('active');
        });
    },

    setActiveMenu: function() {
        const currentPage = window.location.pathname.split('/').pop();
        
        // Reset all active states
        $('.nav-link').removeClass('active');
        $('.nav-item.has-treeview').removeClass('menu-open');
        $('.nav-treeview').hide();
        
        // Find and activate current menu item
        const $activeLink = $(`a[href="${currentPage}"]`);
        if ($activeLink.length) {
            $activeLink.addClass('active');
            
            // If it's a submenu item
            if ($activeLink.closest('.nav-treeview').length) {
                const $parentMenu = $activeLink.closest('.nav-item.has-treeview');
                $parentMenu.addClass('menu-open');
                $parentMenu.children('.nav-link').addClass('active');
                $activeLink.closest('.nav-treeview').show();
            }
        }
    },

    toggleSidebarMini: function() {
        document.body.classList.toggle('sidebar-mini');
    },

    logout: function() {
        $.ajax({
            url: '/backend/controllers/logout.php',
            type: 'POST',
            success: function() {
                window.location.href = '/index.html';
            },
            error: function() {
                alert('Terjadi kesalahan saat logout.');
            }
        });
    }
};

// Add necessary styles
const style = document.createElement('style');
style.textContent = `
    .nav-treeview {
        display: none;
    }
    
    .menu-open > .nav-treeview {
        display: block;
    }
    
    .nav-item.has-treeview > .nav-link {
        cursor: pointer;
    }
`;
document.head.appendChild(style);

export default SidebarManager;