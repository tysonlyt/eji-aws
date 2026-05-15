/**
 * Frontend JavaScript for Product Media Carousel
 */

(function($) {
    'use strict';
    
    // Additional frontend functionality can be added here
    // The main carousel initialization is in the template file
    
    $(document).ready(function() {
        
        // Pause video when modal/lightbox closes (if using lightbox)
        $(document).on('click', '.pmc-carousel-wrapper', function(e) {
            // Custom click handlers can be added here
        });
        
        // Handle responsive behavior
        handleResponsive();
        $(window).on('resize', handleResponsive);
        
    });
    
    /**
     * Handle responsive behavior
     */
    function handleResponsive() {
        const width = $(window).width();
        
        $('.pmc-carousel-wrapper').each(function() {
            const $wrapper = $(this);
            
            if (width < 768) {
                $wrapper.addClass('pmc-mobile');
            } else {
                $wrapper.removeClass('pmc-mobile');
            }
        });
    }
    
})(jQuery);
