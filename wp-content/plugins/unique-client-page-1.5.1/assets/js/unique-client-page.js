/**
 * Unique Client Page JavaScript - Optimized Version
 * 
 * Provides product filtering, load more, and UI interaction functionality.
 */

// Create namespace
const UCP = window.UCP || {};

// Global function definition for external calls
window.filterProducts = null;

// Constants
const SELECTORS = {
    // Product Grid
    PRODUCT_GRID: '.ucp-products-grid',
    PRODUCT_ITEM: '.ucp-product-item',
    
    // Filtering
    FILTER_FORM: '.ucp-filter-form',
    FILTER_BUTTON: '.ucp-filter-button',
    RESET_BUTTON: '.ucp-reset-button',
    
    // Pagination
    LOAD_MORE_BUTTON: '.ucp-load-more-button',
    
    // Modals
    MODAL_CLOSE: '.ucp-modal-close',
    MODAL_OVERLAY: '.ucp-modal-overlay',
    

};

// Default settings
const DEFAULTS = {
    animationSpeed: 300
};

// Error messages
const ERROR_MESSAGES = {
    ajaxError: 'Error processing your request. Please try again.',
    loadError: 'Failed to load content. Please refresh the page.'
};

(function($) {
    'use strict';
    
    // Ensure UCP namespace exists
    var UCP = window.UCP = window.UCP || {};

    // -------------------------
    // Product Filter Module
    // -------------------------
    UCP.ProductFilter = {
        /**
         * Initialize filter module
         */
        init() {
            this.bindEvents();
            this.logInitialState();
        },
        
        /**
         * Log initial state for debugging
         */
        logInitialState() {
            const $filterButton = $(SELECTORS.FILTER_BUTTON);
            const $productIds = $('#ucp-product-ids');
            
            // Logging disabled for production
        },
        
        /**
         * Bind events using event delegation
         */
        bindEvents() {
            const events = {
                'submit.ucp': {
                    selector: SELECTORS.FILTER_FORM,
                    handler: this.handleFormSubmit
                },
                'click.ucp': {
                    selector: SELECTORS.FILTER_BUTTON,
                    handler: this.handleButtonClick
                },
                'click.ucpReset': {
                    selector: SELECTORS.RESET_BUTTON,
                    handler: this.handleReset
                }
            };

            Object.entries(events).forEach(([event, { selector, handler }]) => {
                $(document).off(event).on(event, selector, handler.bind(this));
            });
        },
        
        /**
         * Get form data as object
         * @param {jQuery} $form - Form element
         * @returns {Object} Form data
         */
        getFormData($form) {
            return {
                category: $form.find('#ucp-category').val(),
                orderby: $form.find('#ucp-orderby').val(),
                product_ids: $form.find('#ucp-product-ids').val()
            };
        },
        
        /**
         * Send AJAX request
         * @param {Object} data - Request data
         * @param {Object} callbacks - Success/error/complete callbacks
         */
        sendAjaxRequest(data, callbacks = {}) {
            const defaultCallbacks = {
                beforeSend: () => UCP.UI.showLoading(),
                complete: () => UCP.UI.hideLoading(),
                error: (xhr, status, error) => this.handleAjaxError(xhr, status, error)
            };
            
            return $.ajax({
                url: ucp_params.ajax_url,
                type: 'POST',
                data: {
                    ...data,
                    nonce: ucp_params.nonce,
                    action: data.action || 'ucp_ajax_handler'
                },
                ...defaultCallbacks,
                ...callbacks
            });
        },
        
        /**
         * Handle AJAX errors
         */
        handleAjaxError(xhr, status, error) {
            // Log to console only in development
            if (typeof ucp_params !== 'undefined' && ucp_params.debug) {
                console.error('AJAX Error:', { status, error, response: xhr.responseText });
            }
            UCP.UI.showError(ERROR_MESSAGES.ajaxError);
        },
        
        /**
         * Handle API error responses
         */
        handleApiError(response) {
            const message = (response.data && response.data.message) || ERROR_MESSAGES.loadError;
            if (typeof ucp_params !== 'undefined' && ucp_params.debug) {
                console.error('API Error:', message);
            }
            UCP.UI.showError(message);
        },
        
        /**
         * Handle form submission
         * @param {Event} e - Form submit event
         */
        handleFormSubmit(e) {
            e.preventDefault();
            this.doFilter();
            return false;
        },
        
        /**
         * Handle filter button click
         * @param {Event} e - Click event
         */
        handleButtonClick(e) {
            e.preventDefault();
            this.doFilter();
        },
        
        /**
         * Handle reset button click
         * @param {Event} e - Click event
         */
        handleReset(e) {
            e.preventDefault();
            $(SELECTORS.FILTER_FORM).trigger('reset');
            this.doFilter();
        },
        
        /**
         * Execute filter with current form values
         */
        doFilter() {
            const $form = $(SELECTORS.FILTER_FORM);
            const formData = this.getFormData($form);
            
            // Form data being submitted
            
            this.sendAjaxRequest({
                custom_action: 'filter_products',
                ...formData
            }, {
                success: (response) => {
                    if (response.success) {
                        this.updateProductGrid(response.data);
                    } else {
                        this.handleApiError(response);
                    }
                }
            });
        },
        
        /**
         * Update product grid with new content
         * @param {Object} data - Response data containing HTML and pagination info
         */
        updateProductGrid(data) {
            if (!data || !data.html) {
                throw new Error('Invalid response data');
            }
            
            const $productGrid = $(SELECTORS.PRODUCT_GRID);
            
            // Update product grid content
            $productGrid.html(data.html);
            
            // Update pagination if available
            if (typeof data.max_pages !== 'undefined') {
                UCP.Pagination.update(data.max_pages);
            }
            
            // Reinitialize any dynamic content
            this.initializeDynamicContent();
        },
        
        /**
         * Initialize dynamic content after AJAX updates
         */
        initializeDynamicContent() {
            // Reinitialize any plugins or components here
        }
    };
    
    // -------------------------
    // Pagination Module
    // -------------------------
    UCP.Pagination = {
        /**
         * Initialize pagination module
         */
        init() {
            this.bindEvents();
            this.updateButtonState();
        },
        
        /**
         * Bind pagination events
         */
        bindEvents() {
            $(document).off('click.ucpLoadMore')
                       .on('click.ucpLoadMore', SELECTORS.LOAD_MORE_BUTTON, this.handleLoadMore.bind(this));
        },
        
        /**
         * Update button visibility based on pagination state
         */
        updateButtonState() {
            const $button = $(SELECTORS.LOAD_MORE_BUTTON);
            if (!$button.length) {
                // Load more button not found
                return;
            }
            
            const maxPages = parseInt($button.data('max'), 10);
            const isMultiplePages = maxPages > 1;
            
            // Updating pagination state
            
            $button.toggle(isMultiplePages);
        },
        
        /**
         * Update pagination state
         * @param {number} maxPages - Maximum number of pages
         */
        update(maxPages) {
            const $button = $(SELECTORS.LOAD_MORE_BUTTON);
            if (!$button.length) return;
            
            const currentPage = parseInt($button.data('page'), 10) || 1;
            const hasMorePages = currentPage < maxPages;
            
            // Updating pagination controls
            
            $button.toggle(hasMorePages);
        },
        
        /**
         * Handle load more button click
         * @param {Event} e - Click event
         */
        handleLoadMore(e) {
            e.preventDefault();
            
            const $button = $(e.currentTarget);
            const nextPage = (parseInt($button.data('page'), 10) || 1) + 1;
            const maxPages = parseInt($button.data('max'), 10);
            
            // Loading more products
            
            this.loadMore($button, nextPage, maxPages);
        },
        
        /**
         * Load more products via AJAX
         * @param {jQuery} $button - The load more button
         * @param {number} nextPage - Next page number to load
         * @param {number} maxPages - Maximum number of pages
         */
        loadMore($button, nextPage, maxPages) {
            const requestData = {
                custom_action: 'load_more',
                page: nextPage,
                product_ids: $button.data('product-ids') || ''
            };
            
            // Sending request for more products
            
            UCP.ProductFilter.sendAjaxRequest(requestData, {
                beforeSend: () => {
                    $button.prop('disabled', true).text(ucp_params.loading_text || 'Loading...');
                },
                success: (response) => {
                    if (!response.success) {
                        throw new Error(response.data?.message || 'Failed to load more products');
                    }
                    
                    this.appendProducts(response.data.html);
                    $button.data('page', nextPage);
                    
                    // Hide button if no more pages
                    if (nextPage >= maxPages) {
                        $button.hide();
                    } else {
                        $button.prop('disabled', false).text(ucp_params.load_more_text || 'Load More');
                    }
                },
                error: (xhr, status, error) => {
                    if (typeof ucp_params !== 'undefined' && ucp_params.debug) {
                        console.error('Failed to load more products:', { status, error });
                    }
                    $button.prop('disabled', false).text(ucp_params.load_more_text || 'Load More');
                    UCP.UI.showError(ERROR_MESSAGES.loadError);
                }
            });
        },
        
        /**
         * Append products to the grid
         * @param {string} html - HTML content to append
         */
        appendProducts(html) {
            if (!html) return;
            
            const $target = $(SELECTORS.PRODUCT_GRID).first();
            
            if ($target.length) {
                $target.append(html);
            } else {
                // Product grid container not found
            }
        }
    };
    
    // -------------------------
    // UI Module
    // -------------------------
    UCP.UI = {
        /**
         * Initialize UI module
         */
        init() {
            this.setupUI();
            this.bindEvents();
        },
        
        /**
         * Set up initial UI state
         */
        setupUI() {
            const $productArea = $('.ucp-product-area');
            
            if ($productArea.length) {
                // Product area element initialized
                $productArea.removeClass('loading');
            } else {
                // Product area element not found
            }
            
            $('.ucp-loading-icon').hide();
        },
        
        /**
         * Bind UI events
         */
        bindEvents() {
            // Add any UI-specific event bindings here
        },
        
        /**
         * Show loading state
         */
        showLoading() {
            $('body').addClass('ucp-loading');
            $('.ucp-loading-overlay, .ucp-loading-icon').show();
        },
        
        /**
         * Hide loading state
         */
        hideLoading() {
            $('body').removeClass('ucp-loading');
            $('.ucp-loading-overlay, .ucp-loading-icon').hide();
        },
        
        /**
         * Show error message
         * @param {string} message - Error message to display
         */
        showError(message) {
            // Error message displayed to user
            
            // Check if error container exists, if not create it
            let $errorContainer = $('.ucp-error-container');
            if (!$errorContainer.length) {
                $errorContainer = $('<div class="ucp-error-container"></div>').prependTo('body');
            }
            
            // Create and show error message
            const $error = $(`
                <div class="ucp-error-message">
                    <p>${message}</p>
                    <button class="ucp-error-close">&times;</button>
                </div>
            `);
            
            // Add close handler
            $error.find('.ucp-error-close').on('click', () => {
                $error.fadeOut(300, () => $error.remove());
            });
            
            // Auto-remove after 5 seconds
            $error.appendTo($errorContainer).hide().fadeIn(300);
            setTimeout(() => {
                $error.fadeOut(300, () => $error.remove());
            }, 5000);
        },
        
        /**
         * Toggle element visibility
         * @param {jQuery} $element - Element to toggle
         * @param {boolean} [state] - Optional state to set (true = show, false = hide)
         */
        toggleElement($element, state) {
            if (typeof state === 'boolean') {
                return state ? $element.show() : $element.hide();
            }
            return $element.toggle();
        }
    };
    
    // -------------------------
    // Load More Module
    // -------------------------
    UCP.LoadMore = {
        /**
         * Initialize load more module
         */
        init: function() {
            // Initialize load more functionality
            this.bindEvents();
        },
        
        /**
         * Bind load more events
         */
        bindEvents: function() {
            $(document).on('click', '.ucp-load-more-button', this.handleLoadMore.bind(this));
        },
        
        /**
         * Handle load more button click
         */
        handleLoadMore: function(e) {
            e.preventDefault();
            const $button = $(e.currentTarget);
            const nextPage = (parseInt($button.data('page'), 10) || 1) + 1;
            const maxPages = parseInt($button.data('max'), 10);
            
            if (nextPage > maxPages) return;
            
            this.loadMore($button, nextPage, maxPages);
        },
        
        /**
         * Load more products
         */
        loadMore: function($button, nextPage, maxPages) {
            // Implementation can be added here if needed
        }
    }
};
        
    // -------------------------
    // Initialization
    // -------------------------
    
    // Document ready handler
    $(function() {
        // Initialize modules safely
        var initModule = function(module, name) {
            try {
                if (module && typeof module.init === 'function') {
                    module.init();
                }
            } catch (error) {
                if (typeof ucp_params !== 'undefined' && ucp_params.debug) {
                    console.error('Error initializing ' + name + ':', error);
                }
            }
        };
        
        // Initialize all modules
        initModule(UCP.ProductFilter, 'ProductFilter');
        initModule(UCP.Pagination, 'Pagination');
        initModule(UCP.UI, 'UI');
        initModule(UCP.LoadMore, 'LoadMore');
        
        // Set global filter function
        if (UCP.ProductFilter && typeof UCP.ProductFilter.doFilter === 'function') {
            window.filterProducts = UCP.ProductFilter.doFilter.bind(UCP.ProductFilter);
        }
    });
    
    // Expose UCP to global scope
    window.UCP = UCP;
    
})(jQuery);
