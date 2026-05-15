/**
 * Unique Client Page - Pagination Script
 * Handles product pagination and page navigation
 * Simplified version - Uses URL redirection instead of AJAX
 */
(function($) {
    'use strict';

    // Cache jQuery selectors for better performance
    var $pageIndicator = $('.ucp-page-indicator');
    var $currentPageIndicator = $pageIndicator.find('.current-page');
    var $paginationContainer = $('.ucp-pagination');

    /**
     * Debug helper function - Logs events with context
     * @param {string} eventName - Name of the event
     * @param {Object} element - DOM element related to the event
     */
    function logEvent(eventName, element) {
        console.log('UCP Event:', eventName, element);
    }

    /**
     * Safely parse integers with default value
     * @param {string|number} value - Value to parse
     * @param {number} defaultValue - Default value if parsing fails
     * @return {number} - Parsed integer or default value
     */
    function safeParseInt(value, defaultValue) {
        var parsed = parseInt(value, 10);
        return isNaN(parsed) ? defaultValue : parsed;
    }

    /**
     * Get current page number from URL parameter or default to 1
     * @return {number} - Current page number
     */
    function getCurrentPage() {
        var urlParams = new URLSearchParams(window.location.search);
        return safeParseInt(urlParams.get('paged'), 1);
    }

    /**
     * Get maximum number of pages
     * @return {number} - Maximum page number
     */
    function getMaxPages() {
        var totalPages = $('.ucp-page-indicator .total-pages').text();
        return safeParseInt(totalPages, 1);
    }

    /**
     * Show or hide loading indicator
     * @param {boolean} show - Whether to show or hide the loading indicator
     */
    function showLoading(show) {
        if (show) {
            // Create loading overlay if it doesn't exist
            if ($('.ucp-loading-overlay').length === 0) {
                $('body').append('<div class="ucp-loading-overlay"><div class="ucp-spinner"></div></div>');
            }
            $('body').addClass('ucp-loading');
            $('.ucp-loading-overlay').show();
        } else {
            $('body').removeClass('ucp-loading');
            $('.ucp-loading-overlay').hide();
        }
    }

    /**
     * Update page indicator with current page
     */
    function updatePageIndicator() {
        if ($currentPageIndicator.length) {
            $currentPageIndicator.text(getCurrentPage());
        }
    }

    /**
     * Load specified page - Uses URL redirection, not AJAX
     * @param {number} page - Page number to load
     * @return {boolean} - Whether the page load was initiated
     */
    function loadPage(page) {
        // Validate page number
        var maxPages = getMaxPages();
        if (page < 1 || page > maxPages) {
            console.warn('UCP: Invalid page number:', page, 'max:', maxPages);
            return false;
        }
        
        logEvent('Loading page via URL redirect: ' + page, null);
        showLoading(true);
        
        // Build new URL, preserve all existing parameters
        var urlParams = new URLSearchParams(window.location.search);
        urlParams.set('paged', page);
        
        // More efficient collection of form data
        $('form[data-preserve-state="true"] input, form[data-preserve-state="true"] select, form[data-preserve-state="true"] textarea, .ucp-hidden-fields input[type="hidden"]').each(function() {
            var $input = $(this);
            var name = $input.attr('name');
            var value = $input.val();
            
            if (name && value && name !== 'paged') {
                urlParams.set(name, value);
            }
        });
        
        // Use URL object for better URL handling
        var baseUrl = window.location.href.split('?')[0];
        var newUrl = baseUrl + '?' + urlParams.toString();
        
        // Execute redirect
        logEvent('Redirecting to: ' + newUrl, null);
        window.location.href = newUrl;
        return true;
    }
    
    /**
     * Handle pagination button click
     * @param {Event} e - Click event
     * @param {jQuery} $button - Button that was clicked
     */
    function handlePaginationClick(e, $button) {
        e.preventDefault();
        logEvent('Clicked pagination button: ' + $button.text(), $button);
        
        // If it's the current page button, do nothing
        if ($button.hasClass('current')) {
            return false;
        }
        
        var page = 1;
        var currentPage = getCurrentPage();
        var maxPages = getMaxPages();
        
        // Determine which page to load
        if ($button.attr('data-page')) {
            page = safeParseInt($button.attr('data-page'), 0);
        } else if ($button.hasClass('prev-page')) {
            page = Math.max(1, currentPage - 1);
        } else if ($button.hasClass('next-page')) {
            page = Math.min(maxPages, currentPage + 1);
        }
        
        // Only load valid pages that aren't the current page
        if (page > 0 && page <= maxPages && page !== currentPage) {
            loadPage(page);
            return true;
        }
        
        return false;
    }
    
    /**
     * Debug pagination elements in the DOM
     */
    function debugPaginationElements() {
        logEvent('Starting to scan pagination elements...', null);
        
        // Check UCP pagination buttons
        $paginationContainer.find('button').each(function() {
            var $btn = $(this);
            logEvent('Found UCP pagination button: ' + $btn.text() + ', class: ' + $btn.attr('class') + ', data-page: ' + $btn.attr('data-page'), $btn);
        });
        
        // Check WordPress default pagination
        $('.page-numbers, .nav-links a, .pagination a, .pager a').each(function() {
            var $this = $(this);
            var classes = $this.attr('class') || '';
            var text = $this.text() || '';
            var href = $this.attr('href') || '#';
            logEvent('Found pagination element: [' + text + '] - class: ' + classes + ', link: ' + href, $this);
        });
        
        // Report pagination status
        console.log('Pagination debug - Current:', getCurrentPage(), '/ Max:', getMaxPages());
    }

    // Bind event handlers
    function bindEvents() {
        // Use event delegation for all pagination buttons
        $(document).on('click', '.ucp-pagination .ucp-btn', function(e) {
            handlePaginationClick(e, $(this));
        });
        
        // Handle keyboard navigation (optional)
        $(document).on('keydown', function(e) {
            // Only if no input element is focused
            if (!$(e.target).is('input, textarea, select')) {
                var currentPage = getCurrentPage();
                
                // Left arrow key for previous page
                if (e.keyCode === 37) {
                    loadPage(Math.max(1, currentPage - 1));
                }
                // Right arrow key for next page
                else if (e.keyCode === 39) {
                    loadPage(Math.min(getMaxPages(), currentPage + 1));
                }
            }
        });
    }

    // Initialize
    $(document).ready(function() {
        console.log('UCP pagination script initialized');
        
        // Bind all event handlers
        bindEvents();
        
        // Update UI with current page info
        updatePageIndicator();
        
        // Debug information
        var currentPage = getCurrentPage();
        var maxPages = getMaxPages();
        console.log('Current page/Total pages:', currentPage, '/', maxPages);
        
        // Debug URL parameters
        var urlParams = new URLSearchParams(window.location.search);
        console.log('URL parameters debug:');
        urlParams.forEach(function(value, key) {
            console.log(key + ':', value);
        });
        
        // Scan pagination elements (delayed to ensure DOM is fully loaded)
        setTimeout(debugPaginationElements, 1000);
    });

})(jQuery);
