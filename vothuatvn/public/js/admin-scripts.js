/**
 * VoThuatVN Admin - JavaScript Utilities
 * Modern interactions and animations
 */

(function () {
    'use strict';

    // ============================================
    // SMOOTH SCROLL ANIMATIONS
    // ============================================
    const observeElements = () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        // Observe cards and tables
        document.querySelectorAll('.stat-card, .warning-card, .table-container, .card').forEach(el => {
            observer.observe(el);
        });
    };

    // ============================================
    // MOBILE MENU TOGGLE
    // ============================================
    const initMobileMenu = () => {
        const sidebar = document.querySelector('.sidebar');
        const menuToggle = document.getElementById('mobile-menu-toggle');
        const overlay = document.getElementById('sidebar-overlay');

        if (menuToggle && sidebar) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                if (overlay) overlay.classList.toggle('active');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        }
    };

    // ============================================
    // FORM VALIDATION HELPERS
    // ============================================
    const initFormValidation = () => {
        const forms = document.querySelectorAll('.needs-validation');

        forms.forEach(form => {
            form.addEventListener('submit', (event) => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Real-time validation feedback
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                if (input.value.trim() !== '') {
                    input.classList.add('touched');
                }
            });

            input.addEventListener('focus', () => {
                input.parentElement?.querySelector('.error-message')?.remove();
            });
        });
    };

    // ============================================
    // LOADING STATES
    // ============================================
    const showLoading = (button) => {
        if (!button) return;

        const originalText = button.innerHTML;
        button.dataset.originalText = originalText;
        button.disabled = true;
        button.innerHTML = '<span class="spinner"></span> Đang xử lý...';
    };

    const hideLoading = (button) => {
        if (!button) return;

        button.disabled = false;
        button.innerHTML = button.dataset.originalText || 'Submit';
    };

    // Attach to forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', (e) => {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                showLoading(submitBtn);
            }
        });
    });

    // ============================================
    // MODAL UTILITIES
    // ============================================
    const initModals = () => {
        // Auto-focus first input in modal
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('shown.bs.modal', () => {
                const firstInput = modal.querySelector('input:not([type="hidden"]), textarea, select');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 100);
                }
            });

            // Clear form on modal close
            modal.addEventListener('hidden.bs.modal', () => {
                const form = modal.querySelector('form');
                if (form && form.classList.contains('clear-on-close')) {
                    form.reset();
                    form.classList.remove('was-validated');
                }
            });
        });
    };

    // ============================================
    // CONFIRM DELETE
    // ============================================
    const initDeleteConfirmation = () => {
        document.querySelectorAll('[data-confirm-delete]').forEach(element => {
            element.addEventListener('click', (e) => {
                const message = element.dataset.confirmDelete || 'Bạn có chắc chắn muốn xóa?';
                if (!confirm(message)) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    };

    // ============================================
    // AUTO-DISMISS ALERTS
    // ============================================
    const initAlerts = () => {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    };

    // ============================================
    // TABLE UTILITIES
    // ============================================
    const initTables = () => {
        // Make tables responsive
        document.querySelectorAll('table:not(.no-responsive)').forEach(table => {
            if (!table.closest('.table-responsive')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'table-responsive';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            }
        });

        // Row click to expand (if has data-expandable)
        document.querySelectorAll('tr[data-expandable]').forEach(row => {
            row.style.cursor = 'pointer';
            row.addEventListener('click', () => {
                const targetId = row.dataset.expandable;
                const target = document.getElementById(targetId);
                if (target) {
                    target.classList.toggle('d-none');
                }
            });
        });
    };

    // ============================================
    // NUMBER FORMATTING
    // ============================================
    const formatCurrency = (number) => {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(number);
    };

    const initCurrencyInputs = () => {
        document.querySelectorAll('input[data-currency]').forEach(input => {
            input.addEventListener('blur', (e) => {
                const value = parseFloat(e.target.value.replace(/[^0-9]/g, ''));
                if (!isNaN(value)) {
                    e.target.value = value.toLocaleString('vi-VN');
                }
            });

            input.addEventListener('focus', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
        });
    };

    // ============================================
    // SEARCH/FILTER UTILITIES
    // ============================================
    const initTableSearch = () => {
        const searchInputs = document.querySelectorAll('[data-table-search]');

        searchInputs.forEach(input => {
            const tableId = input.dataset.tableSearch;
            const table = document.getElementById(tableId);

            if (!table) return;

            input.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                const rows = table.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        });
    };

    // ============================================
    // TOOLTIP INITIALIZATION
    // ============================================
    const initTooltips = () => {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    };

    // ============================================
    // COPY TO CLIPBOARD
    // ============================================
    const initCopyButtons = () => {
        document.querySelectorAll('[data-copy]').forEach(button => {
            button.addEventListener('click', async () => {
                const text = button.dataset.copy;
                try {
                    await navigator.clipboard.writeText(text);
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="bi bi-check"></i> Đã sao chép';
                    setTimeout(() => {
                        button.innerHTML = originalText;
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy:', err);
                }
            });
        });
    };

    // ============================================
    // DYNAMIC FORM FIELDS
    // ============================================
    const initDynamicFields = () => {
        // Add more fields functionality
        document.querySelectorAll('[data-add-field]').forEach(button => {
            button.addEventListener('click', () => {
                const templateId = button.dataset.addField;
                const template = document.getElementById(templateId);
                const container = button.previousElementSibling;

                if (template && container) {
                    const clone = template.content.cloneNode(true);
                    container.appendChild(clone);
                }
            });
        });

        // Remove field functionality
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-remove-field]')) {
                e.target.closest('[data-field-item]').remove();
            }
        });
    };

    // ============================================
    // MOBILE TOUCH FIXES
    // ============================================
    const initMobileTouchFixes = () => {
        // Fix for iOS Safari touch events
        if ('ontouchstart' in window) {
            // Add touch event listeners to all clickable elements
            const clickableElements = document.querySelectorAll('a, button, .btn, .menu-item, .stat-card, .clickable, [onclick]');

            clickableElements.forEach(element => {
                // Prevent ghost clicks on mobile
                element.addEventListener('touchstart', function () {
                    this.classList.add('touching');
                }, { passive: true });

                element.addEventListener('touchend', function () {
                    this.classList.remove('touching');
                }, { passive: true });

                element.addEventListener('touchcancel', function () {
                    this.classList.remove('touching');
                }, { passive: true });
            });
        }

        // Fix for double-tap zoom on specific elements
        let lastTouchEnd = 0;
        document.addEventListener('touchend', (event) => {
            const now = Date.now();
            if (now - lastTouchEnd <= 300) {
                // Only prevent default on non-clickable elements to avoid blocking UI interactions
                const isClickable = event.target.closest('a, button, .btn, i, select, input, textarea, .mobile-menu-toggle, [onclick]');
                if (!isClickable) {
                    event.preventDefault();
                }
            }
            lastTouchEnd = now;
        }, { passive: false });

        // Fix for iOS momentum scrolling
        const scrollableElements = document.querySelectorAll('.sidebar, .table-container, .modal-body');
        scrollableElements.forEach(element => {
            element.style.webkitOverflowScrolling = 'touch';
        });
    };

    // ============================================
    // INITIALIZE ALL
    // ============================================
    const init = () => {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
            return;
        }

        // Initialize all features
        observeElements();
        initMobileMenu();
        initFormValidation();
        initModals();
        initDeleteConfirmation();
        initAlerts();
        initTables();
        initCurrencyInputs();
        initTableSearch();
        initCopyButtons();
        initDynamicFields();
        initMobileTouchFixes(); // Add mobile touch fixes

        // Initialize Bootstrap tooltips if available
        if (typeof bootstrap !== 'undefined') {
            initTooltips();
        }

        console.log('✅ VoThuatVN Admin initialized');
    };

    // Start initialization
    init();

    // Export utilities for global use
    window.VoThuatVN = {
        showLoading,
        hideLoading,
        formatCurrency,
        observeElements
    };

    // ============================================
    // CRITICAL MOBILE FIX - DO NOT REMOVE
    // ============================================
    (function () {
        // Fix overlay pointer-events
        const overlay = document.getElementById('sidebar-overlay');
        if (overlay) {
            const updateOverlayState = () => {
                if (overlay.classList.contains('active')) {
                    overlay.style.pointerEvents = 'auto';
                } else {
                    overlay.style.pointerEvents = 'none';
                }
            };

            // Set initial state
            updateOverlayState();

            // Watch for class changes
            const observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    if (mutation.attributeName === 'class') {
                        updateOverlayState();
                    }
                });
            });

            observer.observe(overlay, { attributes: true });
        }

        // Enable touch events support
        if ('ontouchstart' in window) {
            document.body.addEventListener('touchstart', function () { }, { passive: true });
        }

        console.log('✅ Critical mobile fixes loaded');
    })();

    // ============================================
    // THEME TOGGLE FUNCTIONALITY
    // ============================================
    (function () {
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const html = document.documentElement;

        if (!themeToggle || !themeIcon) return;

        // Load saved theme or default to dark
        const savedTheme = localStorage.getItem('theme') || 'dark';
        setTheme(savedTheme);

        // Toggle theme on button click
        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme') || 'dark';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);

            // Add rotation animation
            themeToggle.classList.add('rotating');
            setTimeout(() => {
                themeToggle.classList.remove('rotating');
            }, 500);
        });

        function setTheme(theme) {
            html.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);

            // Update icon
            if (theme === 'light') {
                themeIcon.className = 'bi bi-moon-fill';
                themeToggle.title = 'Chuyển sang giao diện tối';
            } else {
                themeIcon.className = 'bi bi-sun-fill';
                themeToggle.title = 'Chuyển sang giao diện sáng';
            }
        }

        console.log('✅ Theme toggle loaded - Current theme:', savedTheme);
    })();

})();
