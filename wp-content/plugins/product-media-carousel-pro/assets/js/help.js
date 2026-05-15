/**
 * Help Page JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Open Documentation Modal
        $('.pmc-open-docs').on('click', function() {
            $('#pmc-docs-modal').fadeIn(300);
        });
        
        // Open Videos Modal
        $('.pmc-open-videos').on('click', function() {
            $('#pmc-videos-modal').fadeIn(300);
        });
        
        // Open Support Modal
        $('.pmc-open-support').on('click', function() {
            $('#pmc-support-modal').fadeIn(300);
        });
        
        // Open FAQ Modal
        $('.pmc-open-faq').on('click', function() {
            $('#pmc-faq-modal').fadeIn(300);
        });
        
        // Close Modal
        $('.pmc-modal-close').on('click', function() {
            $(this).closest('.pmc-modal').fadeOut(300);
        });
        
        // Close modal when clicking outside
        $(window).on('click', function(e) {
            if ($(e.target).hasClass('pmc-modal')) {
                $(e.target).fadeOut(300);
            }
        });
        
        // FAQ Accordion
        $('.pmc-faq-accordion-button').on('click', function() {
            const $item = $(this).closest('.pmc-faq-accordion-item');
            const $content = $item.find('.pmc-faq-accordion-content');
            const $toggle = $(this).find('.pmc-faq-toggle');
            
            // Toggle current item
            $item.toggleClass('active');
            $content.slideToggle(300);
            $toggle.text($item.hasClass('active') ? '−' : '+');
        });
        
        // Support Form Submit
        $('#pmc-support-form').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $button = $form.find('button[type="submit"]');
            const $response = $('#pmc-support-response');
            const buttonText = $button.text();
            
            // Get form data
            const formData = {
                action: 'pmc_submit_support',
                nonce: pmcHelp.nonce,
                name: $('#pmc-support-name').val(),
                email: $('#pmc-support-email').val(),
                subject: $('#pmc-support-subject').val(),
                message: $('#pmc-support-message').val()
            };
            
            // Disable button
            $button.prop('disabled', true).text(pmcHelp.strings.sending);
            $response.html('');
            
            // Send AJAX request
            $.ajax({
                url: pmcHelp.ajaxUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $response.html('<div class="notice notice-success"><p>' + pmcHelp.strings.sent + '</p></div>');
                        $form[0].reset();
                    } else {
                        $response.html('<div class="notice notice-error"><p>' + (response.data.message || pmcHelp.strings.error) + '</p></div>');
                    }
                },
                error: function() {
                    $response.html('<div class="notice notice-error"><p>' + pmcHelp.strings.error + '</p></div>');
                },
                complete: function() {
                    $button.prop('disabled', false).text(buttonText);
                }
            });
        });
        
    });
    
})(jQuery);
