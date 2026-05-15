/**
 * Simplified Admin Direct Modal Controller
 * Provides simple and direct modal window control functionality
 */

(function($) {
    'use strict';
    
    // Prevent multiple initializations
    if (window.UCPAdminModalControllerInitialized) {
        // console.log('Modal Controller: Already initialized, skipping');
        return;
    }
    
    // Set initialization flag
    window.UCPAdminModalControllerInitialized = true;
    
    // Core functionality: Open modal window
    function openModal($modal) {
        if (!$modal || !$modal.length) {
            console.warn('Modal Controller: Invalid modal window');
            return false;
        }
        
        // console.log('Modal Controller: Opening modal window', $modal.attr('id'));
        
        // Close all other modal windows
        closeAllModals();
        
        // Reset modal window state
        $modal.removeClass('closing hidden')
              .addClass('show');
        
        // Set display styles
        $modal.css({
            'display': 'block',
            'visibility': 'visible',
            'opacity': '1',
            'z-index': '999999'
        });
        
        // Add body class
        $('body').addClass('ucp-modal-open');
        $modal[0].offsetHeight;
        
        // Trigger the animation
        setTimeout(function() {
            $modal.css('opacity', '1');
        }, 10);
        
        // Set body state
        $('body').addClass('ucp-modal-open');
        
        // 触发modalOpened事件，便于产品选择器加载产品
        $(document).trigger('modalOpened', [$modal]);
        
        return true;
    }
    
    // Core functionality: Close modal window
    function closeModal($modal) {
        if (!$modal || !$modal.length) {
            return false;
        }
        
        // console.log('Modal Controller: Closing modal window', $modal.attr('id'));
        
        // 直接关闭模态框，不使用动画以避免问题
        $modal.removeClass('show active visible')
              .addClass('hidden')
              .css({
                  'display': 'none',
                  'visibility': 'hidden',
                  'opacity': '0'
              });
        
        // 恢复正常页面状态
        $('body').removeClass('ucp-modal-open');
        
        // 触发关闭事件以便其他代码响应
        $(document).trigger('modalClosed', [$modal]);
        
        return true;
    }
    
    // Close all modal windows
    function closeAllModals() {
        $('.ucp-modal:visible, .ucp-modal.show').each(function() {
            closeModal($(this));
        });
    }
    
    // Set up event handlers
    function setupEventHandlers() {
        // Close button click handler
        $(document).off('click.ucpModal').on('click.ucpModal', '.ucp-modal-close, .ucp-close-modal, .ucp-cancel-selection', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $modal = $(this).closest('.ucp-modal');
            if ($modal.length) {
                closeModal($modal);
            }
            
            return false;
        });
        
        // ESC key close handler
        $(document).off('keydown.ucpModal').on('keydown.ucpModal', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                closeAllModals();
                e.preventDefault();
                return false;
            }
        });
        
        // Click on modal window background to close
        $(document).off('click.ucpModalBg').on('click.ucpModalBg', '.ucp-modal', function(e) {
            if (e.target === this) {
                closeModal($(this));
                return false;
            }
        });
    }
    
    // Initialize
    function init() {
        // console.log('Modal Controller: Initializing');
        setupEventHandlers();
    }
    
    // Initialize when document is ready
    $(document).ready(function() {
        init();
    });
    
    // Expose API globally
    window.UCPAdminModalController = {
        openModal: function(modal) {
            const $modal = modal.$element || $(modal);
            return openModal($modal);
        },
        closeModal: function(modal) {
            const $modal = modal.$element || $(modal);
            return closeModal($modal);
        },
        closeAllModals: closeAllModals,
        init: init
    };
    
    // Compatibility
    window.UCPAdminModalFix = window.UCPAdminModalController;
    
})(jQuery);
