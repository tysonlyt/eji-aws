/**
 * UCP Product Selector - Simplified Implementation with Tabs
 * Version: 2.0 - Added tab interface for selected products
 * Optimized version with streamlined structure, improved performance and better user experience
 */

(function($) {
    'use strict';
    
    // Version check - confirm new version is loaded
    console.log('UCP Product Selector v2.0 with Tabs - Loaded at ' + new Date().toLocaleTimeString());
    
    // Core configuration and state
    const Config = {
        isDebug: false,
        ajaxUrl: typeof ucp_params !== 'undefined' ? ucp_params.ajax_url : '',
        nonce: typeof ucp_params !== 'undefined' ? ucp_params.nonce : '',
        selectors: {
            modal: '#ucp-product-selector-modal',
            productGrid: '.ucp-products-grid',
            searchInput: '#ucp-product-search',
            categoryFilter: '#ucp-category-filter',
            selectAllCheckbox: '#ucp-select-all',
            cancelButton: '.ucp-cancel-selection',
            closeButton: '.ucp-modal-close',
            productItem: '.ucp-product-item',
            selectButton: '.ucp-select-btn',
            modalContent: '.ucp-modal-body',
            loader: '.ucp-loader',
            selectedCount: '.ucp-selected-count'
        }
    };
    
    // Centralized state management
    const State = {
        selectedProducts: [],
        currentPage: 1,
        totalPages: 1,
        currentCategory: '',
        currentSearch: '',
        isLoading: false,
        cache: {}
    };
    
    // Utility functions
    const Utils = {
        log: function(message, ...args) {
            // Debug messages commented out
            // if (Config.isDebug) {
            //     console.log(`UCP: ${message}`, ...args);
            // }
        },
        
        error: function(message, ...args) {
            // Debug messages commented out
            // console.error(`UCP Error: ${message}`, ...args);
        },
        
        debounce: function(func, wait = 300) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        },
        
        showNotice: function(message, type = 'info') {
            try {
                // Create notification container if not exists
                let container = $('.ucp-notification-container');
                if (!container.length) {
                    container = $('<div class="ucp-notification-container"></div>');
                    $('body').append(container);
                }
                
                // Create notice with close button
                const notice = $(`
                    <div class="ucp-notice ${type}">
                        <div class="ucp-notice-content">${message}</div>
                        <button class="ucp-notice-close" aria-label="Close notification">&times;</button>
                    </div>
                `);
                
                // Add to container
                container.append(notice);
                
                // Force reflow to ensure animation works
                notice[0].offsetHeight;
                
                // Add show class with animation
                notice.addClass('show');
                
                // Add click handler for close button
                notice.find('.ucp-notice-close').on('click', function(e) {
                    e.preventDefault();
                    notice.removeClass('show');
                    setTimeout(() => notice.remove(), 300);
                });
                
                // Auto-close after 5 seconds
                setTimeout(() => {
                    if (notice.hasClass('show')) {
                        notice.removeClass('show');
                        setTimeout(() => notice.remove(), 300);
                    }
                }, 5000);
                
                return notice;
            } catch (error) {
                // console.error('Error showing notification:', error);
                if (type === 'error') alert(message);
                return $('<div>');
            }
        },
        
        getCacheKey: function(page, category, search) {
            return `page_${page}_cat_${category || 'all'}_search_${search || 'none'}`;
        }
    };
    
    // Modal management
    const Modal = {
        open: function() {
            const modalSelector = Config.selectors.modal;
            let $modal = $(modalSelector);
            
            // Create modal if it doesn't exist
            if (!$modal.length) {
                $modal = this.create();
            }
            
            // Reset modal state before opening
            $('.ucp-products-grid').empty();
            $('#ucp-product-search').val('');
            $('#ucp-category-filter').val('');
            State.currentPage = 1;
            State.currentCategory = '';
            State.currentSearch = '';
            State.cache = {}; // Clear cache to reload all products
            
            // Reload selected products from hidden field to ensure consistency
            const preSelectedProducts = $('#ucp-selected-products').val();
            if (preSelectedProducts) {
                State.selectedProducts = preSelectedProducts.split(',').map(id => id.trim()).filter(id => id);
                console.log('UCP: Reloaded selected products on modal open:', State.selectedProducts);
            }
            
            // Show modal
            $modal.css({
                'display': 'flex',
                'visibility': 'visible',
                'opacity': '1',
                'z-index': '999999'
            });
            
            $('body').addClass('ucp-modal-open');
            
            console.log('About to trigger modalOpened event...');
            
            // Trigger event for other components
            $(document).trigger('modalOpened');
            
            console.log('modalOpened event triggered');
            
            return $modal;
        },
        
        close: function() {
            const $modal = $(Config.selectors.modal);
            
            if (!$modal.length) return;
            
            // Hide modal
            $modal.css({
                'opacity': '0',
                'visibility': 'hidden'
            });
            
            setTimeout(() => {
                $modal.css('display', 'none');
                $('body').removeClass('ucp-modal-open');
            }, 300);
        },
        
        create: function() {
            console.log('Using existing PHP-rendered modal...');
            
            // Use the modal that PHP already rendered (includes all categories)
            const $modal = $(Config.selectors.modal);
            
            if ($modal.length === 0) {
                console.error('Modal not found! PHP should have rendered it.');
                return null;
            }
            
            console.log('Modal found. Tabs exist:', $('.ucp-modal-tabs').length);
            console.log('Tab buttons:', $('.ucp-tab-btn').length);
            console.log('Tab contents:', $('.ucp-tab-content').length);
            console.log('Category options:', $('#ucp-category-filter option').length);
            
            return $modal;
        }
    };
    
    // Data loading
    const Data = {
        loadNextPage: function() {
            // Load next page
            if (State.isLoading || State.currentPage >= State.totalPages) {
                return Promise.resolve();
            }
            
            return this.load(State.currentPage + 1, State.currentCategory, State.currentSearch);
        },
        
        load: function(currentPage = 1, currentCategory = '', currentSearch = '') {
            // Debug messages commented out
            // console.log({
            //     page: currentPage,
            //     category: currentCategory,
            //     search: currentSearch,
            //     ajax_url: Config.ajaxUrl
            // });
            
            if (!Config.ajaxUrl) {
                // console.error('Error: AJAX URL not defined, cannot load products');
                UI.hideLoading();
                State.isLoading = false;
                Utils.showNotice('Unable to load products: AJAX URL not defined', 'error');
                return Promise.reject('Missing AJAX URL');
            }
            
            State.isLoading = true;
            UI.showLoading();
            
            // Update state
            State.currentPage = currentPage;
            State.currentCategory = currentCategory;
            State.currentSearch = currentSearch;
            
            // Check cache first
            const cacheKey = Utils.getCacheKey(currentPage, currentCategory, currentSearch);
            if (State.cache[cacheKey]) {
                const cachedData = State.cache[cacheKey];
                UI.renderProducts(cachedData.products);
                UI.updatePagination(currentPage, cachedData.total_pages);
                State.totalPages = cachedData.total_pages;
                State.isLoading = false;
                UI.hideLoading();
                return Promise.resolve(cachedData);
            }
            
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: Config.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'ucp_load_selector_products',
                        nonce: Config.nonce,
                        page: currentPage,
                        category: currentCategory,
                        search: currentSearch,
                        exclude_products: State.selectedProducts
                    },
                    timeout: 20000, // 20 second timeout
                    success: (response) => {
                        if (response.success) {
                            // Store in cache
                            State.cache[cacheKey] = response.data;
                            
                            // Debug messages commented out
                            // console.log({
                            //     totalProducts: response.data.total_products,
                            //     totalPages: response.data.total_pages,
                            //     productsCount: response.data.products ? response.data.products.length : 0
                            // });
                            
                            // Update UI
                            UI.renderProducts(response.data.products);
                            UI.updatePagination(currentPage, response.data.total_pages);
                            
                            // Update state
                            State.totalPages = response.data.total_pages;
                            
                            resolve(response.data);
                        } else {
                            const errorMsg = response.data && response.data.message 
                                ? response.data.message 
                                : 'Failed to load products';
                                
                            // console.error('Product loading failed:', response);
                            Utils.showNotice(errorMsg, 'error');
                            reject(errorMsg);
                        }
                    },
                    error: (xhr, status, error) => {
                        // Debug messages commented out
                        // console.error('AJAX request error:', {
                        //     status: status,
                        //     error: error,
                        //     responseText: xhr.responseText,
                        //     statusCode: xhr.status
                        // });
                        
                        Utils.error('AJAX error', status, error);
                        
                        let errorMessage = 'Failed to load products. Please try again.';
                        
                        // Try to parse error message from response
                        try {
                            if (xhr.responseText) {
                                const jsonResponse = JSON.parse(xhr.responseText);
                                if (jsonResponse && jsonResponse.data && jsonResponse.data.message) {
                                    errorMessage = jsonResponse.data.message;
                                }
                            }
                        } catch (e) {
                            // console.error('Unable to parse error response:', e);
                        }
                        
                        if (status === 'timeout') {
                            Utils.showNotice('Request timed out. Please try again.', 'error');
                        } else {
                            Utils.showNotice(errorMessage, 'error');
                        }
                        
                        reject(error);
                    },
                    complete: () => {
                        State.isLoading = false;
                        UI.hideLoading();
                    }
                });
            });
        },
        
        loadNextPage: function() {
            if (State.currentPage < State.totalPages) {
                return this.load(State.currentPage + 1, State.currentCategory, State.currentSearch);
            }
            return Promise.reject('No more pages');
        },
        
        loadPreviousPage: function() {
            if (State.currentPage > 1) {
                return this.load(State.currentPage - 1, State.currentCategory, State.currentSearch);
            }
            return Promise.reject('Already on first page');
        }
    };
    
    // UI management
    const UI = {
        init: function() {
            // Initialize product grid
            if (!$(Config.selectors.productGrid).length) {
                $(Config.selectors.modalContent).append('<div class="ucp-products-grid"></div>');
            }
            // If on edit page, show selected count
            if (window.location.href.includes('edit-unique-client-page')) {
                this.updateSelectedCount = function() {
                    $(Config.selectors.selectedCount).text(State.selectedProducts.length);
                };
            }
        },
        
        showLoading: function() {
            $(Config.selectors.modal).addClass('loading');
            
            // Create loader if not exists
            if (!$(Config.selectors.loader).length) {
                $(Config.selectors.productGrid).before('<div class="ucp-loader"><div class="ucp-spinner"></div><div>Loading products...</div></div>');
            } else {
                $(Config.selectors.loader).show();
            }
        },
        
        hideLoading: function() {
            $(Config.selectors.modal).removeClass('loading');
            $(Config.selectors.loader).hide();
        },
        
        renderProducts: function(products) {
            if (!products || !products.length) {
                $(Config.selectors.productGrid).html('<div class="ucp-no-products">No products found</div>');
                return;
            }
            
            // Clear grid or append based on page
            if (State.currentPage === 1) {
                $(Config.selectors.productGrid).empty();
            }
            
            // Backend already excluded selected products, so render all returned products
            const productsHtml = products.map(product => {
                return `
                    <div class="ucp-product-item" data-product-id="${product.id}">
                        <div class="ucp-product-image">
                            <img src="${product.image || 'placeholder.jpg'}" alt="${product.name}">
                        </div>
                        <div class="ucp-product-details">
                            <h3 class="ucp-product-name">${product.name}</h3>
                            <div class="ucp-product-sku">SKU: ${product.sku || '-'}</div>
                        </div>
                        <div class="ucp-product-select">
                            <button class="ucp-btn ucp-select-btn">
                                Select
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
            
            // Append to grid
            $(Config.selectors.productGrid).append(productsHtml);
            
            // Update selected count
            this.updateSelectedCount();
        },
        
        updatePagination: function(currentPage, totalPages) {
            // Show a simple message if all products are loaded
            if (currentPage >= totalPages && totalPages > 0) {
                // Remove existing pagination container
                $('.ucp-pagination').remove();
                
                // Add message when all products are loaded
                $(Config.selectors.productGrid).after('<div class="ucp-pagination"><div class="ucp-all-loaded">All products loaded</div></div>');
            } else if (!$('.ucp-pagination').length) {
                // Show loading indicator
                $(Config.selectors.productGrid).after('<div class="ucp-pagination"><div class="ucp-loading-more">Scroll to load more...</div></div>');
            }
        },
        
        updateSelectedCount: function() {
            const count = State.selectedProducts.length;
            $(Config.selectors.selectedCount).text(count);
        },
        
        
        selectAll: function(isChecked) {
            const $products = $(Config.selectors.productItem);
            
            if (isChecked) {
                // Select all products on current page (add to existing selection)
                $products.each((index, product) => {
                    const $product = $(product);
                    const productId = $product.data('product-id');
                    if (productId) {
                        const productIdStr = productId.toString();
                        // Only add if not already selected
                        if (!State.selectedProducts.includes(productIdStr)) {
                            State.selectedProducts.push(productIdStr);
                        }
                        $product.addClass('selected');
                        $product.find(Config.selectors.selectButton).addClass('selected').text('Selected');
                    }
                });
            } else {
                // Deselect only products on current page (keep others)
                $products.each((index, product) => {
                    const $product = $(product);
                    const productId = $product.data('product-id');
                    if (productId) {
                        const productIdStr = productId.toString();
                        // Remove from selected list
                        const index = State.selectedProducts.indexOf(productIdStr);
                        if (index > -1) {
                            State.selectedProducts.splice(index, 1);
                        }
                        $product.removeClass('selected');
                        $product.find(Config.selectors.selectButton).removeClass('selected').text('Select');
                    }
                });
            }
            
            // Update selected count display
            this.updateSelectedCount();
            
            // Auto save selection
            this.autoSaveSelection();
        },
        
        /**
         * Auto-save selected products without clicking "Add" button
         * Save to hidden field on create page, save via AJAX on edit page
         */
        /**
         * Toggle product selection state
         * @param {jQuery} $product Product element jQuery object
         */
        toggleProductSelection: function($product) {
            try {
                const productId = $product.data('product-id');
                if (!productId) {
                    return;
                }
                
                const productIdStr = productId.toString();
                const $selectBtn = $product.find(Config.selectors.selectButton);
                
                // Check if product is already selected
                const isSelected = State.selectedProducts.includes(productIdStr);
                
                if (isSelected) {
                    // Remove selected state
                    $product.removeClass('selected');
                    $selectBtn.removeClass('selected').text('Select');
                    
                    // Remove from selected list
                    const index = State.selectedProducts.indexOf(productIdStr);
                    if (index > -1) {
                        State.selectedProducts.splice(index, 1);
                    }
                } else {
                    // Add selected state
                    $product.addClass('selected');
                    $selectBtn.addClass('selected').text('Selected');
                    
                    // Add to selected list
                    if (!State.selectedProducts.includes(productIdStr)) {
                        State.selectedProducts.push(productIdStr);
                    }
                }
                
                // Update selected count
                this.updateSelectedCount();
                
                // Auto save selection
                this.autoSaveSelection();
            } catch (error) {
                Utils.error('Error toggling product selection state:', error);
            }
        },
        
        autoSaveSelection: function() {
            try {
                // Detailed log: record function call
                // console.log('UCP DEBUG: autoSaveSelection called');
                
                // If no products selected, do nothing
                if (State.selectedProducts.length === 0) {
                    // console.log('UCP DEBUG: No products selected, skip save');
                    return;
                }
                
                // console.log('UCP DEBUG: Auto-saving selected products');
                
                // Check if hidden field exists - no longer check button ID
                // console.log('UCP DEBUG: Hidden field exists');
                
                // Save product IDs if hidden field exists
                if ($('#ucp-selected-products').length > 0) {
                    // Record current value before save
                    var oldValue = $('#ucp-selected-products').val();
                    // console.log('UCP DEBUG: Hidden field value before save');
                    
                    // Update hidden field value
                    var newValue = State.selectedProducts.join(',');
                    $('#ucp-selected-products').val(newValue);
                    
                    // Verify value is set correctly
                    var verifyValue = $('#ucp-selected-products').val();
                    // console.log('UCP DEBUG: Hidden field value after save');
                    // console.log('UCP DEBUG: Save successful');
                    
                    // Add change event to hidden field, verify again before form submit
                    $('#ucp-selected-products').closest('form').on('submit', function() {
                        // console.log('UCP DEBUG: Hidden field value on form submit');
                        return true;
                    });
                    
                    Utils.showNotice(`Selected ${State.selectedProducts.length} products`, 'success', 1500);
                    return;
                }
                
                // On edit page, save via AJAX
                const pageId = $('#ucp-page-id').val();
                if (!pageId) {
                    Utils.error('Unable to get page ID, cannot save product selection');
                    return;
                }
                
                // Use throttle to prevent frequent requests
                if (this._saveTimeout) {
                    clearTimeout(this._saveTimeout);
                }
                
                this._saveTimeout = setTimeout(() => {
                    $.ajax({
                        url: Config.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'ucp_add_products_to_page',
                            nonce: Config.nonce,
                            // Send as array for robust backend parsing
                            product_ids: State.selectedProducts,
                            page_id: pageId
                        },
                        beforeSend: function() {
                            Utils.showNotice('Saving product selection...', 'info');
                        },
                        success: function(response) {
                            if (response.success) {
                                Utils.showNotice(`Successfully saved ${State.selectedProducts.length} products`, 'success', 1500);
                            } else {
                                Utils.showNotice(response.data || 'Failed to save product selection', 'error');
                            }
                        },
                        error: function(xhr) {
                            Utils.error('AJAX request failed:', xhr);
                            Utils.showNotice('Failed to save product selection, please try again', 'error');
                        }
                    });
                }, 1000); // Delay 1000ms to further reduce frequent requests
            } catch (error) {
                Utils.error('Error during auto-save of product selection:', error);
                Utils.showNotice('Failed to save product selection, please try again', 'error');
            }
        },
        
        /**
         * Render selected products list in the selected products tab
         */
        renderSelectedProducts: function() {
            const $container = $('.ucp-selected-products-list');
            
            if (!State.selectedProducts.length) {
                $container.html('<div class="ucp-no-products">No products selected</div>');
                return;
            }
            
            // Need to fetch product details for selected IDs
            this.fetchSelectedProductDetails().then(products => {
                const productsHtml = products.map(product => {
                    return `
                        <div class="ucp-selected-product-item" data-product-id="${product.id}">
                            <div class="ucp-selected-product-image">
                                <img src="${product.image || 'placeholder.jpg'}" alt="${product.name}">
                            </div>
                            <div class="ucp-selected-product-details">
                                <h4 class="ucp-selected-product-name">${product.name}</h4>
                                <div class="ucp-selected-product-sku">SKU: ${product.sku || '-'}</div>
                            </div>
                            <button class="ucp-btn ucp-btn-remove" data-product-id="${product.id}" title="Remove">
                                <span>&times;</span>
                            </button>
                        </div>
                    `;
                }).join('');
                
                $container.html(productsHtml);
            }).catch(error => {
                $container.html('<div class="ucp-error">Failed to load selected products</div>');
                Utils.error('Failed to load selected products:', error);
            });
        },
        
        /**
         * Fetch product details for selected product IDs
         */
        fetchSelectedProductDetails: function() {
            return new Promise((resolve, reject) => {
                if (!State.selectedProducts.length) {
                    resolve([]);
                    return;
                }
                
                $.ajax({
                    url: Config.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'ucp_get_products_by_ids',
                        nonce: Config.nonce,
                        product_ids: State.selectedProducts
                    },
                    success: (response) => {
                        if (response.success && response.data) {
                            resolve(response.data);
                        } else {
                            reject('Failed to fetch product details');
                        }
                    },
                    error: (xhr, status, error) => {
                        reject(error);
                    }
                });
            });
        },
        
        /**
         * Remove a product from selection
         */
        removeSelectedProduct: function(productId) {
            const productIdStr = productId.toString();
            const index = State.selectedProducts.indexOf(productIdStr);
            
            if (index > -1) {
                State.selectedProducts.splice(index, 1);
                
                // Update UI
                this.updateSelectedCount();
                this.renderSelectedProducts();
                
                // Reload select tab to show the removed product
                Data.load(State.currentPage, State.currentCategory, State.currentSearch);
                
                // Auto save
                this.autoSaveSelection();
                
                Utils.showNotice('Product removed', 'success', 1500);
            }
        },
        
        /**
         * Switch between tabs
         */
        switchTab: function(tabName) {
            // Update tab buttons
            $('.ucp-tab-btn').removeClass('active');
            $(`.ucp-tab-btn[data-tab="${tabName}"]`).addClass('active');
            
            // Update tab content
            $('.ucp-tab-content').removeClass('active');
            $(`.ucp-tab-content[data-tab-content="${tabName}"]`).addClass('active');
            
            // If switching to selected tab, render the list
            if (tabName === 'selected') {
                this.renderSelectedProducts();
            }
        }
    };
    
    // Event handlers
    const Events = {
        init: function() {
            // Debug check ucp_params
            console.log('DEBUG ucp_params:', {
                exists: typeof ucp_params !== 'undefined',
                ajaxUrl: Config.ajaxUrl,
                nonce: Config.nonce ? 'exists' : 'missing'
            });
            
            this.initButtonHandler();
            this.initModalHandlers();
            this.initSelectionHandlers();
            this.initSearchHandlers();
            this.initScrollHandlers();
            this.initTabHandlers();
        },
        
        initButtonHandler: function() {
            console.log('Initializing product selector button events');
            
            // More precise product selector button selection
            $(document).on('click', 'button#ucp-create-select-products, button.ucp-select-products-btn', function(e) {
                // Ensure clicking the button itself, not child elements
                if (e.target !== this) {
                    return;
                }
                
                e.preventDefault();
                e.stopPropagation();
                
                console.log('Product selector button clicked: ', e.target);
                
                // Show modal
                const $modal = Modal.open();
                console.log('Modal opened: ', $modal.length > 0);
                
                // Initialize UI
                UI.init();
                console.log('UI initialized');
                
                // Preload selected products from hidden fields
                try {
                    let preselected = [];
                    const $hidden = $('#ucp-selected-products');
                    if ($hidden.length) {
                        const val = ($hidden.val() || '').toString().trim();
                        if (val.length) {
                            preselected = val.split(',')
                                .map(v => v.trim())
                                .filter(v => v !== '' && !isNaN(v))
                                .map(v => parseInt(v, 10))
                                .filter(v => v > 0)
                                .map(v => v.toString());
                        }
                    } else {
                        // Support multiple selected_products[] hidden fields
                        $('input[name="selected_products[]"]').each(function() {
                            const v = ($(this).val() || '').toString().trim();
                            if (v !== '' && !isNaN(v)) {
                                const n = parseInt(v, 10);
                                if (n > 0) preselected.push(n.toString());
                            }
                        });
                    }
                    // Deduplicate and set state
                    State.selectedProducts = Array.from(new Set(preselected));
                    
                    console.log('Preloaded products:', State.selectedProducts);
                    
                    // Update count and button state immediately
                    UI.updateSelectedCount();
                } catch (e) {
                    // Silent failure, don't block subsequent processes
                    console.error('Error preloading products:', e);
                }
                
                // Products will be loaded by modalOpened event
                console.log('Modal opened, products will be loaded by event handler');
            });
        },
        
        initModalHandlers: function() {
            // Close button handler
            $(document).on('click', Config.selectors.closeButton + ', ' + Config.selectors.cancelButton, function(e) {
                e.preventDefault();
                Modal.close();
            });
            
            // Add selected products button
            $(document).on('click', '.ucp-add-selected-products', function(e) {
                e.preventDefault();
                
                console.log('Add button clicked. State:', {
                    selectedProducts: State.selectedProducts,
                    count: State.selectedProducts.length
                });
                
                if (State.selectedProducts.length === 0) {
                    Utils.showNotice('Please select at least one product', 'warning');
                    return;
                }
                
                // Get page ID
                const pageId = $('#ucp-page-id').val();
                
                // Check if this is create page (page_id is 0 or empty)
                if (!pageId || pageId === '0') {
                    // Create page: just update hidden field, no AJAX
                    $('#ucp-selected-products').val(State.selectedProducts.join(','));
                    UI.updateSelectedCount();
                    Utils.showNotice('Products selected. Click "Create Page" to save.', 'success');
                    Modal.close();
                    return;
                }
                
                console.log('Sending AJAX with:', {
                    product_ids: State.selectedProducts,
                    page_id: pageId
                });
                
                // Edit page: send AJAX request to save products
                $.ajax({
                    url: Config.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'ucp_add_products_to_page',
                        nonce: Config.nonce,
                        product_ids: State.selectedProducts,
                        page_id: pageId
                    },
                    success: function(response) {
                        if (response.success) {
                            Utils.showNotice(response.data.message || 'Products added successfully', 'success');
                            
                            // Update state with complete product list from server
                            if (response.data.product_ids) {
                                State.selectedProducts = response.data.product_ids;
                                
                                // Update hidden field
                                $('#ucp-selected-products').val(response.data.product_ids.join(','));
                                
                                // Update display count
                                UI.updateSelectedCount();
                            }
                            
                            Modal.close();
                            
                            // No need to reload - state is updated
                        } else {
                            Utils.showNotice(response.data.message || 'Failed to add products', 'error');
                        }
                    },
                    error: function() {
                        Utils.showNotice('An error occurred. Please try again.', 'error');
                    }
                });
            });
            
            // Click outside to close
            $(document).on('click', Config.selectors.modal, function(e) {
                if (e.target === this) {
                    Modal.close();
                }
            });
            
            // ESC key to close
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $(Config.selectors.modal).is(':visible')) {
                    Modal.close();
                }
            });
        },
        
        initSelectionHandlers: function() {
            // Product selection event
            $(document).on('click', Config.selectors.productItem + ', ' + Config.selectors.selectButton, function(e) {
                e.preventDefault();
                e.stopPropagation();
                const $product = $(this).closest(Config.selectors.productItem);
                UI.toggleProductSelection($product);
            });
            
            // Select all checkbox
            $(document).on('change', Config.selectors.selectAllCheckbox, function() {
                const isChecked = $(this).prop('checked');
                UI.selectAll(isChecked);
            });
        },
        
        initSearchHandlers: function() {
            // Search input with debounce
            $(document).on('keyup', Config.selectors.searchInput, Utils.debounce(function() {
                const searchTerm = $(this).val().trim();
                if (searchTerm.length >= 2 || searchTerm.length === 0) {
                    Data.load(1, State.currentCategory, searchTerm);
                }
            }, 200));
            
            // Category filter
            $(document).on('change', Config.selectors.categoryFilter, function() {
                const category = $(this).val();
                const searchTerm = $(Config.selectors.searchInput).val().trim();
                Data.load(1, category, searchTerm);
            });
        },
        
        initScrollHandlers: function() {
            // Infinite scroll
            $(document).on('modalOpened', function(e, $modal) {
                console.log('Modal opened, preparing to load products');
                
                // Load products immediately when modal opens
                Data.load(1, '', '');
                
                // Bind infinite scroll event
                $(Config.selectors.modalContent).on('scroll', Utils.debounce(function() {
                    if (State.isLoading || State.currentPage >= State.totalPages) {
                        return;
                    }
                    
                    const scrollHeight = $(this).prop('scrollHeight');
                    const scrollPosition = $(this).height() + $(this).scrollTop();
                    const scrollThreshold = 200;
                    
                    if (scrollHeight - scrollPosition < scrollThreshold) {
                        Data.loadNextPage();
                    }
                }, 200));
            });
        },
        
        initTabHandlers: function() {
            // Tab switching
            $(document).on('click', '.ucp-tab-btn', function(e) {
                e.preventDefault();
                const tabName = $(this).data('tab');
                UI.switchTab(tabName);
            });
            
            // Remove product from selected list
            $(document).on('click', '.ucp-btn-remove', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const productId = $(this).data('product-id');
                if (productId) {
                    UI.removeSelectedProduct(productId);
                }
            });
        }
    };
    
    // Global error handling
    function setupErrorHandling() {
        window.addEventListener('error', function(e) {
            Utils.error('Global error:', e.message, e);
            Utils.showNotice('An error occurred. Please try again.', 'error');
            return false;
        });
        
        window.addEventListener('unhandledrejection', function(e) {
            Utils.error('Unhandled promise rejection:', e.reason);
            Utils.showNotice('Operation failed. Please try again.', 'error');
            return false;
        });
    }
    
    // Initialize product selector
    function init() {
        Utils.log('Initializing UCP Product Selector');
        
        // Load pre-selected products from hidden field
        const preSelectedProducts = $('#ucp-selected-products').val();
        if (preSelectedProducts) {
            State.selectedProducts = preSelectedProducts.split(',').map(id => id.trim()).filter(id => id);
            console.log('UCP: Loaded pre-selected products:', State.selectedProducts);
        }
        
        // Setup error handling
        setupErrorHandling();
        
        // Initialize events
        Events.init();
        
        // Make available globally
        window.UCPProductSelector = {
            init: init,
            showNotice: Utils.showNotice,
            openSelector: function() {
                $('#ucp-create-select-products').trigger('click');
            },
            debug: {
                getState: function() {
                    return {
                        selectedProducts: State.selectedProducts,
                        currentPage: State.currentPage,
                        totalPages: State.totalPages,
                        isLoading: State.isLoading,
                        modalExists: $(Config.selectors.modal).length > 0,
                        buttonExists: $('#ucp-create-select-products').length > 0
                    };
                }
            }
        };
    }
    
    // Initialize on document ready
    $(document).ready(function() {
        try {
            init();
        } catch (error) {
            console.error('Error initializing UCP Product Selector:', error);
            Utils.showNotice('Error occurred while initializing product selector, please refresh the page and try again', 'error');
        }
    });
    
    // Expose functions globally
    window.initProductSelectorButton = function() {
        Events.initButtonHandler();
        return true;
    };
    
})(jQuery);
