/**
 * Admin JavaScript for Product Media Carousel
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        console.log('PMC Admin JS loaded');
        
        // Initialize sortable
        initSortable();
        
        // Add video (YouTube/Vimeo/Self-hosted)
        $('#pmc-add-video').on('click', function() {
            console.log('PMC Admin JS: Add video button clicked');
            const url = $('#pmc-video-url').val().trim();
            
            if (!url) {
                alert('Please enter a video URL');
                return;
            }
            
            const productId = $('input[name="pmc_product_id"]').val();
            
            // Let server-side detect video type
            addMedia(productId, 'video', url);
        });
        
        // Upload video file (Pro only)
        $('#pmc-upload-video').on('click', function(e) {
            e.preventDefault();
            console.log('PMC Admin JS: Upload video button clicked');
            
            // WordPress media uploader
            var mediaUploader;
            
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            mediaUploader = wp.media({
                title: 'Upload Video File',
                button: {
                    text: 'Use this video'
                },
                library: {
                    type: ['video/mp4', 'video/webm']
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                console.log('PMC Admin JS: Video selected:', attachment);
                
                const productId = $('input[name="pmc_product_id"]').val();
                const videoUrl = attachment.url;
                
                // Add self-hosted video
                addMedia(productId, 'self_hosted', videoUrl);
            });
            
            mediaUploader.open();
        });
        
        // Move up
        $(document).on('click', '.pmc-move-up', function() {
            const $item = $(this).closest('.pmc-media-item');
            const $prev = $item.prev('.pmc-media-item');
            
            if ($prev.length) {
                $item.insertBefore($prev);
                updateMediaOrder();
            }
        });
        
        // Move down
        $(document).on('click', '.pmc-move-down', function() {
            const $item = $(this).closest('.pmc-media-item');
            const $next = $item.next('.pmc-media-item');
            
            if ($next.length) {
                $item.insertAfter($next);
                updateMediaOrder();
            }
        });
        
        // Delete media
        $(document).on('click', '.pmc-delete-media', function() {
            if (!confirm(pmcAdmin.confirmDelete)) {
                return;
            }
            
            const mediaId = $(this).data('id');
            const $item = $(this).closest('.pmc-media-item');
            
            deleteMedia(mediaId, $item);
        });
        
        /**
         * Add media via AJAX
         */
        function addMedia(productId, mediaType, mediaValue) {
            console.log('PMC Admin JS: addMedia called', {productId, mediaType, mediaValue});
            
            $.ajax({
                url: pmcAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'pmc_add_media',
                    nonce: pmcAdmin.nonce,
                    product_id: productId,
                    media_type: mediaType,
                    media_value: mediaValue
                },
                beforeSend: function() {
                    console.log('PMC Admin JS: AJAX request starting');
                    $('#pmc-add-video').prop('disabled', true).text('Adding...');
                },
                success: function(response) {
                    console.log('PMC Admin JS: AJAX response:', response);
                    console.log('PMC Admin JS: Response data:', response.data);
                    if (response.success) {
                        // Clear input
                        $('#pmc-video-url').val('');
                        
                        // Add new item to list
                        const item = response.data.item;
                        addMediaItemToList(item);
                        
                        // Show success message
                        showNotice('success', response.data.message);
                    } else {
                        console.error('PMC Admin JS: Error response:', response.data);
                        const errorMsg = response.data.message || 'Failed to add media';
                        showNotice('error', errorMsg);
                        
                        // Show debug info if available
                        if (response.data.debug) {
                            console.log('PMC Admin JS: Debug info:', response.data.debug);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('PMC Admin JS: AJAX error:', {xhr, status, error});
                    console.error('PMC Admin JS: Response text:', xhr.responseText);
                    showNotice('error', 'Failed to add media. Please try again.');
                },
                complete: function() {
                    $('#pmc-add-video').prop('disabled', false).text('Add Video URL');
                }
            });
        }
        
        /**
         * Delete media via AJAX
         */
        function deleteMedia(mediaId, $item) {
            $.ajax({
                url: pmcAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'pmc_delete_media',
                    nonce: pmcAdmin.nonce,
                    media_id: mediaId
                },
                beforeSend: function() {
                    $item.addClass('pmc-loading');
                },
                success: function(response) {
                    if (response.success) {
                        $item.fadeOut(300, function() {
                            $(this).remove();
                            checkEmptyList();
                        });
                        showNotice('success', response.data.message);
                    } else {
                        $item.removeClass('pmc-loading');
                        showNotice('error', response.data.message);
                    }
                },
                error: function() {
                    $item.removeClass('pmc-loading');
                    showNotice('error', 'Failed to delete media. Please try again.');
                }
            });
        }
        
        /**
         * Initialize sortable
         */
        function initSortable() {
            if ($('#pmc-media-list').length && typeof $.fn.sortable !== 'undefined') {
                $('#pmc-media-list').sortable({
                    handle: '.pmc-drag-handle',
                    placeholder: 'pmc-sortable-placeholder',
                    cursor: 'move',
                    opacity: 0.8,
                    update: function(event, ui) {
                        updateMediaOrder();
                    }
                });
                console.log('PMC: Sortable initialized');
            } else {
                console.warn('PMC: Sortable not available or list not found');
            }
        }
        
        /**
         * Update media order via AJAX
         */
        function updateMediaOrder() {
            const productId = $('input[name="pmc_product_id"]').val();
            const order = [];
            
            $('#pmc-media-list .pmc-media-item').each(function() {
                order.push($(this).data('id'));
            });
            
            $.ajax({
                url: pmcAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'pmc_update_order',
                    nonce: pmcAdmin.nonce,
                    product_id: productId,
                    order: order
                },
                success: function(response) {
                    if (response.success) {
                        showNotice('success', response.data.message, 2000);
                    }
                }
            });
        }
        
        /**
         * Add media item to list
         */
        function addMediaItemToList(item) {
            // Remove "no media" message if exists
            $('.pmc-no-media').remove();
            
            let thumbnail = '';
            let label = '';
            let displayValue = item.media_value;
            
            // Generate thumbnail and label based on media type
            if (item.media_type === 'youtube') {
                thumbnail = `https://img.youtube.com/vi/${item.media_value}/mqdefault.jpg`;
                label = '<span class="dashicons dashicons-video-alt3"></span> YouTube';
            } else if (item.media_type === 'vimeo') {
                thumbnail = 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22150%22 height=%22150%22%3E%3Crect fill=%22%2300adef%22 width=%22150%22 height=%22150%22/%3E%3Ctext fill=%22white%22 font-family=%22Arial%22 font-size=%2216%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EVimeo%3C/text%3E%3C/svg%3E';
                label = '<span class="dashicons dashicons-video-alt3"></span> Vimeo';
            } else if (item.media_type === 'self_hosted') {
                thumbnail = item.media_value + '#t=0.5';
                label = '<span class="dashicons dashicons-video-alt3"></span> Self-Hosted';
                displayValue = item.media_value.split('/').pop(); // Show filename only
            }
            
            const html = `
                <li class="pmc-media-item" data-id="${item.id}" data-type="${item.media_type}">
                    <div class="pmc-media-preview">
                        ${item.media_type === 'self_hosted' 
                            ? `<video src="${thumbnail}" style="width:100%;height:100%;object-fit:cover;" preload="metadata"></video>`
                            : `<img src="${thumbnail}" alt="Video" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22150%22 height=%22150%22%3E%3Crect fill=%22%23333%22 width=%22150%22 height=%22150%22/%3E%3Ctext fill=%22white%22 font-family=%22Arial%22 font-size=%2216%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EVideo%3C/text%3E%3C/svg%3E';" />`
                        }
                        <span class="pmc-media-label pmc-video-label">
                            ${label}
                        </span>
                    </div>
                    <div class="pmc-media-info">
                        <div class="pmc-media-url">${displayValue}</div>
                        <div class="pmc-media-source">Custom</div>
                    </div>
                    <div class="pmc-media-actions">
                        <button type="button" class="button pmc-move-up" title="Move Up">
                            <span class="dashicons dashicons-arrow-up-alt2"></span>
                        </button>
                        <button type="button" class="button pmc-move-down" title="Move Down">
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </button>
                        <button type="button" class="button pmc-delete-media" data-id="${item.id}">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </div>
                </li>
            `;
            
            $('#pmc-media-list').append(html);
        }
        
        /**
         * Check if list is empty
         */
        function checkEmptyList() {
            if ($('#pmc-media-list .pmc-media-item').length === 0) {
                $('#pmc-media-list').html('<li class="pmc-no-media">No media items yet. Add product images in the Product Gallery or add YouTube videos above.</li>');
            }
        }
        
        /**
         * Show notice
         */
        function showNotice(type, message, duration) {
            const $notice = $('<div class="pmc-notice ' + type + '">' + message + '</div>');
            $('.pmc-admin-wrapper').prepend($notice);
            
            setTimeout(function() {
                $notice.fadeOut(300, function() {
                    $(this).remove();
                });
            }, duration || 3000);
        }
        
        /**
         * Validate YouTube URL
         */
        function isValidYouTubeUrl(url) {
            const pattern = /^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)[a-zA-Z0-9_-]+/;
            return pattern.test(url);
        }
        
        /**
         * Extract YouTube video ID
         */
        function extractYouTubeId(url) {
            const pattern = /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/;
            const match = url.match(pattern);
            return match ? match[1] : '';
        }
        
    });
    
})(jQuery);
