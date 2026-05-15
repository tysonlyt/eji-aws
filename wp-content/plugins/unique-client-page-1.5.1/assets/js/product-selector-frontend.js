/**
 * UCP Product Selector - Frontend Simplified Version
 * Streamlined version with improved performance and better user experience
 * Optimization date: 2025-05-23
 */

(function($) {
    'use strict';
    
    // Core configuration 
    const Config = {
        isDebug: false, // 永久禁用调试模式
        animationDuration: 300,
        noticeAutoCloseTime: 5000
    };
    
    // Utility functions
    const Utils = {
        log: function(message, ...args) {
            if (Config.isDebug) console.log(`UCP: ${message}`, ...args);
        },
        
        error: function(message, ...args) {
            console.error(`UCP Error: ${message}`, ...args);
        },
        
        debounce: function(func, wait = 300) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        },
        
        throttle: function(func, limit = 300) {
            let inThrottle;
            return function(...args) {
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        },
        
        generateId: function() {
            return 'ucp-' + Math.random().toString(36).substr(2, 9);
        },
        
        isEditPage: function() {
            return window.location.href.includes('edit-unique-client-page');
        },
        
        isMobile: function() {
            return window.innerWidth <= 768;
        }
    };
    
    // Notification manager
    const Notifications = {
        container: null,
        notices: new Map(),
        
        init: function() {
            if (this.container) return;
            
            this.container = $('<div class="ucp-notification-container"></div>');
            $('body').append(this.container);
            
            // Add swipe to dismiss on mobile
            if (Utils.isMobile() && typeof $.fn.swipe !== 'undefined') {
                this.container.swipe({
                    swipeLeft: (event, direction) => {
                        const $notice = $(event.target).closest('.ucp-notice');
                        if ($notice.length) {
                            const id = $notice.data('notice-id');
                            if (id) this.close(id);
                        }
                    },
                    threshold: 50
                });
            }
        },
        
        show: function(message, type = 'info', autoClose = true) {
            this.init();
            
            const id = Utils.generateId();
            const notice = $(`
                <div class="ucp-notice ${type}" data-notice-id="${id}">
                    <div class="ucp-notice-content">${message}</div>
                    <button class="ucp-notice-close" aria-label="Close notification">&times;</button>
                </div>
            `);
            
            // Add to container
            this.container.append(notice);
            
            // Force reflow to ensure animation works
            notice[0].offsetHeight;
            
            // Add show class with animation
            notice.addClass('show');
            
            // Add click handler for close button
            notice.find('.ucp-notice-close').on('click', () => {
                this.close(id);
            });
            
            // Store reference to notice
            this.notices.set(id, notice);
            
            // Auto-close after timeout if enabled
            if (autoClose) {
                setTimeout(() => {
                    if (this.notices.has(id)) {
                        this.close(id);
                    }
                }, Config.noticeAutoCloseTime);
            }
            
            return id;
        },
        
        close: function(id) {
            const notice = this.notices.get(id);
            if (!notice) return;
            
            notice.removeClass('show');
            
            setTimeout(() => {
                notice.remove();
                this.notices.delete(id);
            }, Config.animationDuration);
        },
        
        closeAll: function() {
            this.notices.forEach((notice, id) => {
                this.close(id);
            });
        }
    };
    
    // Modal manager
    const Modal = {
        activeModals: new Map(),
        
        create: function(options = {}) {
            const id = options.id || Utils.generateId();
            const title = options.title || '';
            const content = options.content || '';
            const classes = options.classes || '';
            const closeOnOverlayClick = options.closeOnOverlayClick !== false;
            
            // Create modal HTML
            const $modal = $(`
                <div id="${id}" class="ucp-modal ${classes}">
                    <div class="ucp-modal-container">
                        <div class="ucp-modal-header">
                            <h3>${title}</h3>
                            <button class="ucp-modal-close">&times;</button>
                        </div>
                        <div class="ucp-modal-content">
                            ${content}
                        </div>
                    </div>
                </div>
            `);
            
            // Add to page
            $('body').append($modal);
            
            // Store modal data
            const modalData = {
                id: id,
                $element: $modal,
                settings: options
            };
            
            this.activeModals.set(id, modalData);
            
            // Attach event handlers
            this.attachEvents($modal, closeOnOverlayClick);
            
            return modalData;
        },
        
        open: function(modal) {
            let modalData;
            
            // Handle different input types
            if (typeof modal === 'string') {
                // If ID provided
                modalData = this.activeModals.get(modal) || { id: modal, $element: $(`#${modal}`) };
            } else if (modal.jquery) {
                // If jQuery element provided
                modalData = { id: modal.attr('id'), $element: modal };
            } else if (modal.$element) {
                // If modal data object provided
                modalData = modal;
            } else {
                Utils.error('Invalid modal parameter');
                return false;
            }
            
            const $modal = modalData.$element;
            
            if (!$modal || !$modal.length) {
                Utils.error('Modal element not found');
                return false;
            }
            
            // Show modal
            $modal.css({
                display: 'flex',
                visibility: 'visible',
                opacity: '0'
            }).addClass('show');
            
            // Force reflow for animation
            $modal[0].offsetHeight;
            
            // Animate in
            $modal.css('opacity', '1');
            
            // Add body class
            $('body').addClass('ucp-modal-open');
            
            // Trigger events
            $(document).trigger('modalOpened', [modalData]);
            
            return modalData;
        },
        
        close: function(modal) {
            let modalData;
            
            // Handle different input types
            if (typeof modal === 'string') {
                modalData = this.activeModals.get(modal) || { id: modal, $element: $(`#${modal}`) };
            } else if (modal.jquery) {
                modalData = { id: modal.attr('id'), $element: modal };
            } else if (modal.$element) {
                modalData = modal;
            } else {
                Utils.error('Invalid modal parameter');
                return false;
            }
            
            const $modal = modalData.$element;
            
            if (!$modal || !$modal.length) {
                Utils.error('Modal element not found');
                return false;
            }
            
            // Add closing class
            $modal.removeClass('show').addClass('closing');
            
            // Animate out
            $modal.css('opacity', '0');
            
            // Remove modal after animation
            setTimeout(() => {
                // If callback provided
                if (modalData.settings && typeof modalData.settings.onClose === 'function') {
                    modalData.settings.onClose(modalData);
                }
                
                // If set to remove on close
                if (!modalData.settings || modalData.settings.removeOnClose !== false) {
                    $modal.remove();
                    this.activeModals.delete(modalData.id);
                } else {
                    $modal.hide().removeClass('closing');
                }
                
                // If no other modals, restore page scroll
                if (!document.querySelector('.ucp-modal.show')) {
                    $('body').removeClass('ucp-modal-open');
                }
                
                // Trigger events
                $(document).trigger('modalClosed', [modalData]);
            }, Config.animationDuration);
            
            return true;
        },
        
        attachEvents: function($modal, closeOnOverlayClick) {
            if (!$modal || !$modal.length) return;
            
            // Close button click
            $modal.on('click', '.ucp-modal-close, .ucp-close-modal, .ucp-cancel-selection', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.close($modal);
            });
            
            // Click background to close if enabled
            if (closeOnOverlayClick) {
                $modal.on('click', (e) => {
                    if (e.target === $modal[0]) {
                        this.close($modal);
                    }
                });
            }
            
            // Prevent clicks in content area from bubbling up
            $modal.find('.ucp-modal-content').on('click', (e) => {
                e.stopPropagation();
            });
            
            // Add ESC key handler
            $(document).on('keydown.ucpModal', (e) => {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    this.close($modal);
                }
            });
        },
        
        closeAll: function() {
            let closedCount = 0;
            
            // Close all active modals
            this.activeModals.forEach((modalData) => {
                if (this.close(modalData)) {
                    closedCount++;
                }
            });
            
            // Fallback: close any modals that might not be in our tracking
            $('.ucp-modal.show').each((index, modal) => {
                this.close($(modal));
                closedCount++;
            });
            
            return closedCount > 0;
        }
    };
    
    // Global error handler
    function setupErrorHandling() {
        window.addEventListener('error', function(e) {
            Utils.error('Global error:', e.message);
            Notifications.show('An error occurred. Please try again.', 'error');
            return false;
        });
        
        window.addEventListener('unhandledrejection', function(e) {
            Utils.error('Unhandled promise rejection:', e.reason);
            Notifications.show('Operation failed. Please try again.', 'error');
            return false;
        });
    }
    
    // Initialize everything
    function init() {
        // Initialize notifications
        Notifications.init();
        
        // Setup error handling
        setupErrorHandling();
        
        Utils.log('UCP Product Selector frontend initialized');
    }
    
    // Expose to window
    window.UCP_NotificationManager = Notifications;
    window.UCP_ModalManager = Modal;
    
    // Initialize when DOM is ready
    $(document).ready(init);
    
})(jQuery);
