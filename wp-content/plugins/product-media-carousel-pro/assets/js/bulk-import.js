/**
 * Bulk Import JavaScript
 */

jQuery(document).ready(function($) {
    let importData = [];
    
    // Tab switching
    $('.nav-tab').on('click', function(e) {
        e.preventDefault();
        const target = $(this).attr('href');
        
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        
        $('.pmc-tab-panel').removeClass('active');
        $(target).addClass('active');
    });
    
    // CSV Upload
    $('#pmc-upload-csv').on('click', function() {
        const fileInput = $('#pmc-csv-file')[0];
        
        if (!fileInput.files.length) {
            alert('Please select a CSV file');
            return;
        }
        
        const file = fileInput.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const csv = e.target.result;
            parseCSV(csv);
        };
        
        reader.readAsText(file);
    });
    
    // Parse CSV
    function parseCSV(csv) {
        const lines = csv.split('\n');
        const data = [];
        
        // Skip header
        for (let i = 1; i < lines.length; i++) {
            const line = lines[i].trim();
            if (!line) continue;
            
            const parts = line.split(',');
            if (parts.length >= 3) {
                data.push({
                    product_id: parts[0].trim(),
                    video_url: parts[1].trim(),
                    video_type: parts[2].trim()
                });
            }
        }
        
        if (data.length === 0) {
            alert('No valid data found in CSV');
            return;
        }
        
        importData = data;
        displayPreview(data);
    }
    
    // Display preview
    function displayPreview(data) {
        let html = '<table class="wp-list-table widefat fixed striped">';
        html += '<thead><tr>';
        html += '<th>Product ID</th>';
        html += '<th>Video URL</th>';
        html += '<th>Video Type</th>';
        html += '</tr></thead><tbody>';
        
        data.forEach(function(item) {
            html += '<tr>';
            html += '<td>' + item.product_id + '</td>';
            html += '<td>' + item.video_url + '</td>';
            html += '<td>' + item.video_type + '</td>';
            html += '</tr>';
        });
        
        html += '</tbody></table>';
        html += '<p><strong>Total: ' + data.length + ' videos</strong></p>';
        
        $('#pmc-preview-table').html(html);
        $('#pmc-preview-area').show();
    }
    
    // Cancel import
    $('#pmc-cancel-import').on('click', function() {
        $('#pmc-preview-area').hide();
        $('#pmc-csv-file').val('');
        importData = [];
    });
    
    // Start import
    $('#pmc-start-import').on('click', function() {
        if (importData.length === 0) {
            alert('No data to import');
            return;
        }
        
        $('#pmc-preview-area').hide();
        $('#pmc-progress-area').show();
        
        startImport(importData);
    });
    
    // Start import process
    function startImport(data) {
        const total = data.length;
        let completed = 0;
        let success = 0;
        let failed = 0;
        
        $('#pmc-progress-text').text('0 / ' + total);
        $('#pmc-import-log').html('');
        
        // Process in batches
        const batchSize = 5;
        let currentBatch = 0;
        
        function processBatch() {
            const start = currentBatch * batchSize;
            const end = Math.min(start + batchSize, total);
            const batch = data.slice(start, end);
            
            if (batch.length === 0) {
                // All done
                showResults(total, success, failed);
                return;
            }
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'pmc_bulk_import',
                    nonce: pmcAdmin.nonce,
                    data: JSON.stringify(batch)
                },
                success: function(response) {
                    if (response.success) {
                        const result = response.data;
                        success += result.success;
                        failed += result.failed;
                        
                        // Log details
                        result.details.forEach(function(detail) {
                            const logClass = detail.status === 'success' ? 'success' : 'error';
                            const icon = detail.status === 'success' ? '✓' : '✗';
                            const productName = detail.product_name || 'Unknown';
                            const videoUrl = detail.video_url || '';
                            $('#pmc-import-log').append(
                                '<div class="pmc-log-item ' + logClass + '">' +
                                icon + ' Product #' + detail.product_id + ' (' + productName + '): ' + detail.message +
                                (videoUrl ? '<br>&nbsp;&nbsp;&nbsp;URL: ' + videoUrl : '') +
                                '</div>'
                            );
                        });
                    }
                    
                    completed += batch.length;
                    updateProgress(completed, total);
                    
                    currentBatch++;
                    processBatch();
                },
                error: function() {
                    failed += batch.length;
                    completed += batch.length;
                    updateProgress(completed, total);
                    
                    $('#pmc-import-log').append(
                        '<div class="pmc-log-item error">✗ Batch failed</div>'
                    );
                    
                    currentBatch++;
                    processBatch();
                }
            });
        }
        
        processBatch();
    }
    
    // Update progress
    function updateProgress(completed, total) {
        const percent = (completed / total) * 100;
        $('.pmc-progress-fill').css('width', percent + '%');
        $('.pmc-progress-text').text(completed + ' / ' + total);
    }
    
    // Show results
    function showResults(total, success, failed) {
        // Keep progress area visible to show the log
        // $('#pmc-progress-area').hide();
        $('#pmc-results-area').show();
        
        let html = '<div class="notice notice-success"><p>';
        html += '<strong>Import Completed!</strong><br>';
        html += 'Total: ' + total + ' | ';
        html += '<span style="color: #46b450;">Success: ' + success + '</span> | ';
        html += '<span style="color: #dc3232;">Failed: ' + failed + '</span>';
        html += '</p></div>';
        
        $('#pmc-results-summary').html(html);
        
        // Add reload button instead of auto-reload
        $('#pmc-results-summary').append(
            '<p><button type="button" class="button button-primary" onclick="location.reload()">Reload Page</button></p>'
        );
    }
    
    // Product search
    let searchTimeout;
    $(document).on('input', '.pmc-product-search', function() {
        const $input = $(this);
        const $row = $input.closest('tr');
        const $results = $row.find('.pmc-search-results');
        const searchTerm = $input.val().trim();
        
        clearTimeout(searchTimeout);
        
        if (searchTerm.length < 2) {
            $results.hide().html('');
            return;
        }
        
        searchTimeout = setTimeout(function() {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'woocommerce_json_search_products',
                    security: pmcAdmin.nonce,
                    term: searchTerm,
                    limit: 10
                },
                success: function(response) {
                    if (response && Object.keys(response).length > 0) {
                        let html = '<ul class="pmc-product-list">';
                        $.each(response, function(id, name) {
                            html += '<li data-id="' + id + '">' + name + ' (ID: ' + id + ')</li>';
                        });
                        html += '</ul>';
                        $results.html(html).show();
                    } else {
                        $results.html('<p>No products found</p>').show();
                    }
                },
                error: function() {
                    $results.html('<p>Search error</p>').show();
                }
            });
        }, 300);
    });
    
    // Select product from search results
    $(document).on('click', '.pmc-product-list li', function() {
        const $li = $(this);
        const productId = $li.data('id');
        const productName = $li.text();
        const $row = $li.closest('tr');
        
        $row.find('.pmc-product-id').val(productId);
        $row.find('.pmc-product-search').val(productName);
        $row.find('.pmc-search-results').hide().html('');
    });
    
    // Hide search results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.pmc-product-search, .pmc-search-results').length) {
            $('.pmc-search-results').hide();
        }
    });
    
    // Manual input - Add row
    $('#pmc-add-row').on('click', function() {
        const row = `
            <tr>
                <td>
                    <input type="text" class="regular-text pmc-product-search" placeholder="Type product name..." />
                    <div class="pmc-search-results"></div>
                </td>
                <td><input type="number" class="small-text pmc-product-id" placeholder="ID" readonly /></td>
                <td><input type="url" class="regular-text pmc-video-url" placeholder="https://..." /></td>
                <td>
                    <select class="pmc-video-type">
                        <option value="youtube">YouTube</option>
                        <option value="vimeo">Vimeo</option>
                        <option value="self_hosted">Self-Hosted</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="button pmc-remove-row">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                </td>
            </tr>
        `;
        $('#pmc-manual-rows').append(row);
    });
    
    // Manual input - Remove row
    $(document).on('click', '.pmc-remove-row', function() {
        if ($('#pmc-manual-rows tr').length > 1) {
            $(this).closest('tr').remove();
        }
    });
    
    // Manual input - Lookup product name
    $(document).on('blur', '.pmc-product-id', function() {
        const $input = $(this);
        const productId = $input.val();
        const $nameCell = $input.closest('tr').find('.pmc-product-name');
        
        if (!productId) {
            $nameCell.text('-');
            return;
        }
        
        $nameCell.text('Loading...');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'woocommerce_json_search_products',
                security: pmcAdmin.nonce,
                term: productId
            },
            success: function(response) {
                if (response && response[productId]) {
                    $nameCell.text(response[productId]);
                } else {
                    $nameCell.text('Not found');
                }
            },
            error: function() {
                $nameCell.text('Error');
            }
        });
    });
    
    // Manual input - Import
    $('#pmc-import-manual').on('click', function() {
        const data = [];
        
        $('#pmc-manual-rows tr').each(function() {
            const productId = $(this).find('.pmc-product-id').val();
            const videoUrl = $(this).find('.pmc-video-url').val();
            const videoType = $(this).find('.pmc-video-type').val();
            
            if (productId && videoUrl) {
                data.push({
                    product_id: productId,
                    video_url: videoUrl,
                    video_type: videoType
                });
            }
        });
        
        if (data.length === 0) {
            alert('Please add at least one video');
            return;
        }
        
        importData = data;
        $('#manual-input').hide();
        $('#pmc-progress-area').show();
        startImport(data);
    });
});
