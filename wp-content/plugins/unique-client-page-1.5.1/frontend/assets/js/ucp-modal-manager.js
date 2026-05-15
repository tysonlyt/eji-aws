/**
 * UCP Modal Manager
 * Centralized modal management for all popups in the plugin
 */
(function($) {
    'use strict';
    
    // Make UCP_Modal available globally
    window.UCP_Modal = {};
    
    // Private variables with WeakMap for better memory management
    const modalStore = new WeakMap();
    let activeModals = [];
    let scrollbarWidth = 0;
    let debugMode = true;
    
    // Debug logger - centralized logging
    const log = (type, message, data) => {
        if (!debugMode) return;
        
        const timestamp = new Date().toISOString().substr(11, 8);
        const prefix = `[UCP_Modal ${timestamp}]`;
        
        switch(type) {
            case 'error':
                console.error(prefix, message, data || '');
                break;
            case 'warn':
                console.warn(prefix, message, data || '');
                break;
            case 'info':
            default:
                console.log(prefix, message, data || '');
        }
    };
    
    // Calculate scrollbar width once
    const calculateScrollbarWidth = () => {
        // Use cached value if available
        if (scrollbarWidth > 0) return scrollbarWidth;
        
        try {
            const scrollDiv = document.createElement('div');
            scrollDiv.style.cssText = 'width:100px;height:100px;overflow:scroll;position:absolute;top:-9999px;';
            document.body.appendChild(scrollDiv);
            
            scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth;
            document.body.removeChild(scrollDiv);
            
            return scrollbarWidth;
        } catch (error) {
            log('error', 'Error calculating scrollbar width', error);
            return 0;
        }
    };
    
    // Handle body scroll locking/unlocking
    const manageBodyScroll = (lock) => {
        const $body = $('body');
        
        if (lock) {
            requestAnimationFrame(() => {
                if (window.innerHeight < document.body.scrollHeight) {
                    $body.css('padding-right', calculateScrollbarWidth() + 'px');
                }
                $body.addClass('ucp-modal-open');
            });
        } else {
            $body.removeClass('ucp-modal-open');
            $body.css('padding-right', '');
        }
    };
    
    // Initialize
    const init = () => {
        scrollbarWidth = calculateScrollbarWidth();
        
        // Use event delegation for all modal-related clicks
        $(document).on('click.ucpModalGlobal', handleGlobalClicks);
        
        // Global ESC key handler
        $(document).on('keydown.ucpModalGlobal', function(e) {
            if (e.key === 'Escape' && activeModals.length > 0) {
                const topModal = activeModals[activeModals.length - 1];
                close(topModal);
            }
        });
        
        log('info', 'Modal manager initialized');
    };
    
    // Centralized global click handler using event delegation
    const handleGlobalClicks = (e) => {
        const $target = $(e.target);
        
        // Handle close buttons
        if ($target.hasClass('ucp-modal-close') || 
            $target.hasClass('ucp-close-modal') || 
            $target.hasClass('ucp-cancel-selection') ||
            $target.closest('.ucp-modal-close, .ucp-close-modal, .ucp-cancel-selection').length) {
            
            e.preventDefault();
            e.stopPropagation();
            
            // Find closest modal
            const $modal = $target.closest('.ucp-modal');
            if ($modal.length) {
                const modalId = $modal.attr('id');
                const modal = activeModals.find(m => m.id === modalId);
                
                if (modal) {
                    log('info', 'Closing modal via button', modalId);
                    close(modal);
                } else {
                    // Fallback for modals not in our registry
                    log('warn', 'Closing unregistered modal via DOM', modalId);
                    $modal.removeClass('show').hide();
                }
            }
            return;
        }
        
        // Handle overlay clicks
        if ($target.hasClass('ucp-modal')) {
            const modalId = $target.attr('id');
            const modal = activeModals.find(m => m.id === modalId);
            
            if (modal && modal.settings.closeOnOverlayClick) {
                log('info', 'Closing modal via overlay click', modalId);
                close(modal);
            }
        }
    };
    
    // Create a new modal with better error handling
    const create = (options = {}) => {
        try {
            const defaults = {
                content: '',
                classes: '',
                onOpen: null,
                onClose: null,
                closeOnOverlayClick: true,
                closeButton: true,
                animation: 'fade', // 'fade', 'slide', 'zoom'
                removeOnClose: true
            };
            
            const settings = { ...defaults, ...options };
            const modalId = 'ucp-modal-' + (Math.random().toString(36) + Date.now().toString(36)).substring(2, 15);
            
            // Create modal HTML
            const closeButton = settings.closeButton ? 
                `<button type="button" class="ucp-modal-close" aria-label="Close">&times;</button>` : '';
                
            const modalHTML = `
                <div id="${modalId}" class="ucp-modal ${settings.classes}" role="dialog" aria-modal="true">
                    <div class="ucp-modal-content">
                        ${closeButton}
                        <div class="ucp-modal-body">
                            ${settings.content}
                        </div>
                    </div>
                </div>
            `;
            
            // Add to DOM
            $('body').append(modalHTML);
            const $modal = $(`#${modalId}`);
            
            // Store modal data
            const modalData = {
                id: modalId,
                $element: $modal,
                settings: settings,
                isOpen: false,
                created: Date.now()
            };
            
            // Store for garbage collection safety
            modalStore.set($modal[0], modalData);
            
            log('info', 'Created new modal', modalId);
            return modalData;
        } catch (error) {
            log('error', 'Failed to create modal', error);
            return null;
        }
    };
    
    // Open modal with better animation handling
    const open = (modalData) => {
        if (!modalData || !modalData.$element) {
            log('error', 'Attempted to open invalid modal');
            return null;
        }
        
        if (modalData.isOpen) {
            log('info', 'Modal already open', modalData.id);
            return modalData;
        }
        
        try {
            // Add to active modals
            activeModals.push(modalData);
            
            // Prevent body scroll and handle scrollbar
            if (activeModals.length === 1) {
                manageBodyScroll(true);
            }
            
            // Use requestAnimationFrame for smooth animations
            requestAnimationFrame(() => {
                modalData.$element.addClass('show');
                modalData.isOpen = true;
                
                // Focus first focusable element for accessibility
                setTimeout(() => {
                    const focusable = modalData.$element.find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])').first();
                    if (focusable.length) {
                        focusable.focus();
                    }
                }, 100);
                
                // Call onOpen callback
                if (typeof modalData.settings.onOpen === 'function') {
                    modalData.settings.onOpen(modalData);
                }
                
                log('info', 'Opened modal', modalData.id);
            });
            
            return modalData;
        } catch (error) {
            log('error', 'Failed to open modal', error);
            return null;
        }
    };
    
    // Close modal with improved cleanup
    const close = (modalData) => {
        if (!modalData || !modalData.$element) {
            log('error', 'Attempted to close invalid modal');
            return false;
        }
        
        if (!modalData.isOpen) {
            log('info', 'Modal already closed', modalData.id);
            return false;
        }
        
        try {
            log('info', 'Closing modal', modalData.id);
            
            // Immediately remove show class to trigger closing effect
            modalData.$element.removeClass('show');
            modalData.isOpen = false;
            
            // Remove from active modals
            activeModals = activeModals.filter(m => m.id !== modalData.id);
            
            // Handle body scroll
            if (activeModals.length === 0) {
                manageBodyScroll(false);
            }
            
            // Set timeout to remove DOM
            const animationDuration = 300; // ms
            
            setTimeout(() => {
                // Trigger closed event
                $(document).trigger('ucpModalClosed', [modalData.id]);
                
                // Execute callback
                if (typeof modalData.settings.onClose === 'function') {
                    modalData.settings.onClose(modalData);
                }
                
                // Remove from DOM if setting allows
                if (modalData.settings.removeOnClose) {
                    log('info', 'Removing modal from DOM', modalData.id);
                    modalData.$element.remove();
                    
                    // Clean up WeakMap
                    if (modalStore.has(modalData.$element[0])) {
                        modalStore.delete(modalData.$element[0]);
                    }
                } else {
                    // Just hide it
                    modalData.$element.hide();
                }
            }, animationDuration);
            
            return true;
        } catch (error) {
            log('error', 'Error closing modal', error);
            
            // Emergency cleanup attempt
            try {
                modalData.$element.removeClass('show').hide();
                activeModals = activeModals.filter(m => m.id !== modalData.id);
                if (activeModals.length === 0) {
                    manageBodyScroll(false);
                }
            } catch (e) {
                // Last resort
                log('error', 'Emergency close failed', e);
            }
            
            return false;
        }
    };
    
    // Public API with improved error handling
    $.extend(window.UCP_Modal, {
        init: init,
        create: create,
        open: open,
        close: close,
        getActiveModals: () => [...activeModals],
        closeAll: () => {
            const modals = [...activeModals];
            log('info', `Closing all modals, count: ${modals.length}`);
            let closeCount = 0;
            
            modals.forEach(modal => {
                if (close(modal)) closeCount++;
            });
            
            return closeCount;
        },
        setDebug: (enabled) => {
            debugMode = !!enabled;
            log('info', `Debug mode ${debugMode ? 'enabled' : 'disabled'}`);
            return debugMode;
        },
        version: '1.1.0'
    });
    
    // Auto-initialize when DOM is ready
    $(document).ready(function() {
        window.UCP_Modal.init();
    });
    
})(jQuery);
