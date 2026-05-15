<?php
/**
 * Bulk Import Handler
 * Handles bulk video import for multiple products
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class PMC_Bulk_Import {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        // Add admin menu with higher priority
        add_action('admin_menu', array($this, 'add_menu_page'), 99);
        
        // Enqueue scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add Product ID column to product list
        add_filter('manage_product_posts_columns', array($this, 'add_product_id_column'));
        add_action('manage_product_posts_custom_column', array($this, 'show_product_id_column'), 10, 2);
        add_filter('manage_edit-product_sortable_columns', array($this, 'make_product_id_sortable'));
        add_action('admin_head', array($this, 'add_product_id_column_style'));
        
        // AJAX handlers
        add_action('wp_ajax_pmc_bulk_import', array($this, 'ajax_bulk_import'));
        add_action('wp_ajax_pmc_parse_csv', array($this, 'ajax_parse_csv'));
    }
    
    /**
     * Add Product ID column to product list
     */
    public function add_product_id_column($columns) {
        // Force ID column to be second (after checkbox)
        $new_columns = array();
        
        // First, add checkbox
        if (isset($columns['cb'])) {
            $new_columns['cb'] = $columns['cb'];
        }
        
        // Second, add Product ID
        $new_columns['pmc_product_id'] = __('ID', 'product-media-carousel');
        
        // Then add all other columns
        foreach ($columns as $key => $value) {
            if ($key !== 'cb') {
                $new_columns[$key] = $value;
            }
        }
        
        return $new_columns;
    }
    
    /**
     * Show Product ID in column
     */
    public function show_product_id_column($column, $post_id) {
        if ($column === 'pmc_product_id') {
            echo '<strong style="color: #2271b1;">#' . $post_id . '</strong>';
        }
    }
    
    /**
     * Make Product ID column sortable
     */
    public function make_product_id_sortable($columns) {
        $columns['pmc_product_id'] = 'ID';
        return $columns;
    }
    
    /**
     * Add custom CSS for Product ID column
     */
    public function add_product_id_column_style() {
        global $pagenow, $typenow;
        
        if ($pagenow === 'edit.php' && $typenow === 'product') {
            ?>
            <style>
                .column-pmc_product_id {
                    width: 80px !important;
                }
            </style>
            <?php
        }
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts($hook) {
        // Only load on bulk import page
        if ($hook !== 'product_page_pmc-bulk-import') {
            return;
        }
        
        wp_enqueue_style(
            'pmc-bulk-import',
            PMC_PLUGIN_URL . 'assets/css/bulk-import.css',
            array(),
            PMC_VERSION
        );
        
        wp_enqueue_script(
            'pmc-bulk-import',
            PMC_PLUGIN_URL . 'assets/js/bulk-import.js',
            array('jquery'),
            PMC_VERSION,
            true
        );
        
        wp_localize_script('pmc-bulk-import', 'pmcAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pmc_admin_nonce')
        ));
    }
    
    /**
     * Add menu page
     */
    public function add_menu_page() {
        error_log('PMC: Adding bulk import menu');
        
        $hook = add_submenu_page(
            'edit.php?post_type=product',
            __('Bulk Video Import', 'product-media-carousel'),
            __('Bulk Video Import', 'product-media-carousel'),
            'manage_options',
            'pmc-bulk-import',
            array($this, 'render_page')
        );
        
        error_log('PMC: Menu hook: ' . $hook);
    }
    
    /**
     * Render bulk import page
     */
    public function render_page() {
        // Check Pro version
        if (!PMC_Restrictions::is_pro()) {
            ?>
            <div class="wrap">
                <h1><?php _e('Bulk Video Import', 'product-media-carousel'); ?></h1>
                <div class="notice notice-warning">
                    <p><strong><?php _e('This is a Pro feature', 'product-media-carousel'); ?></strong></p>
                    <p><?php _e('Upgrade to Pro to use bulk video import functionality.', 'product-media-carousel'); ?></p>
                    <p>
                        <a href="<?php echo esc_url(PMC_Restrictions::get_upgrade_url()); ?>" class="button button-primary" target="_blank">
                            <?php _e('Upgrade to Pro', 'product-media-carousel'); ?>
                        </a>
                    </p>
                </div>
            </div>
            <?php
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php _e('Bulk Video Import', 'product-media-carousel'); ?></h1>
            <p class="description"><?php _e('Import videos for multiple products at once using CSV file or manual input.', 'product-media-carousel'); ?></p>
            
            <div class="pmc-bulk-import-wrapper">
                <!-- Tabs -->
                <h2 class="nav-tab-wrapper">
                    <a href="#csv-upload" class="nav-tab nav-tab-active"><?php _e('CSV Upload', 'product-media-carousel'); ?></a>
                    <a href="#manual-input" class="nav-tab"><?php _e('Manual Input', 'product-media-carousel'); ?></a>
                    <a href="#import-history" class="nav-tab"><?php _e('Import History', 'product-media-carousel'); ?></a>
                </h2>
                
                <!-- CSV Upload Tab -->
                <div id="csv-upload" class="pmc-tab-panel active">
                    <div class="pmc-import-section">
                        <h3><?php _e('Upload CSV File', 'product-media-carousel'); ?></h3>
                        <p class="description">
                            <?php _e('CSV format: Product ID, Video URL, Video Type (youtube/vimeo/self_hosted)', 'product-media-carousel'); ?>
                        </p>
                        
                        <div class="pmc-csv-example">
                            <strong><?php _e('Example CSV:', 'product-media-carousel'); ?></strong>
                            <pre>product_id,video_url,video_type
100,https://www.youtube.com/watch?v=dQw4w9WgXcQ,youtube
101,https://vimeo.com/148751763,vimeo
102,https://example.com/video.mp4,self_hosted
100,https://www.youtube.com/watch?v=9bZkp7q19f0,youtube</pre>
                            <a href="<?php echo PMC_PLUGIN_URL . 'assets/sample-import.csv'; ?>" class="button" download>
                                <span class="dashicons dashicons-download"></span>
                                <?php _e('Download Sample CSV', 'product-media-carousel'); ?>
                            </a>
                        </div>
                        
                        <div class="pmc-upload-area">
                            <input type="file" id="pmc-csv-file" accept=".csv" />
                            <button type="button" class="button button-primary button-large" id="pmc-upload-csv">
                                <span class="dashicons dashicons-upload"></span>
                                <?php _e('Upload and Preview', 'product-media-carousel'); ?>
                            </button>
                        </div>
                        
                        <!-- Preview Area -->
                        <div id="pmc-preview-area" style="display: none;">
                            <h3><?php _e('Preview Import Data', 'product-media-carousel'); ?></h3>
                            <div id="pmc-preview-table"></div>
                            <div class="pmc-import-actions">
                                <button type="button" class="button button-primary button-large" id="pmc-start-import">
                                    <span class="dashicons dashicons-yes"></span>
                                    <?php _e('Start Import', 'product-media-carousel'); ?>
                                </button>
                                <button type="button" class="button" id="pmc-cancel-import">
                                    <?php _e('Cancel', 'product-media-carousel'); ?>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Progress Area -->
                        <div id="pmc-progress-area" style="display: none;">
                            <h3><?php _e('Import Progress', 'product-media-carousel'); ?></h3>
                            <div class="pmc-progress-bar">
                                <div class="pmc-progress-fill"></div>
                            </div>
                            <p class="pmc-progress-text">0 / 0</p>
                            <div id="pmc-import-log"></div>
                        </div>
                        
                        <!-- Results Area -->
                        <div id="pmc-results-area" style="display: none;">
                            <h3><?php _e('Import Results', 'product-media-carousel'); ?></h3>
                            <div id="pmc-results-summary"></div>
                            <div id="pmc-results-details"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Manual Input Tab -->
                <div id="manual-input" class="pmc-tab-panel">
                    <div class="pmc-import-section">
                        <h3><?php _e('Manual Video Import', 'product-media-carousel'); ?></h3>
                        <p class="description"><?php _e('Add videos manually for multiple products.', 'product-media-carousel'); ?></p>
                        
                        <table class="wp-list-table widefat fixed striped" id="pmc-manual-table">
                            <thead>
                                <tr>
                                    <th style="width: 300px;"><?php _e('Search Product', 'product-media-carousel'); ?></th>
                                    <th style="width: 100px;"><?php _e('Product ID', 'product-media-carousel'); ?></th>
                                    <th><?php _e('Video URL', 'product-media-carousel'); ?></th>
                                    <th style="width: 150px;"><?php _e('Video Type', 'product-media-carousel'); ?></th>
                                    <th style="width: 80px;"><?php _e('Actions', 'product-media-carousel'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="pmc-manual-rows">
                                <tr>
                                    <td>
                                        <input type="text" class="regular-text pmc-product-search" placeholder="<?php _e('Type product name...', 'product-media-carousel'); ?>" />
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
                            </tbody>
                        </table>
                        
                        <div style="margin-top: 15px;">
                            <button type="button" class="button" id="pmc-add-row">
                                <span class="dashicons dashicons-plus"></span>
                                <?php _e('Add Row', 'product-media-carousel'); ?>
                            </button>
                            <button type="button" class="button button-primary" id="pmc-import-manual">
                                <span class="dashicons dashicons-yes"></span>
                                <?php _e('Import Videos', 'product-media-carousel'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Import History Tab -->
                <div id="import-history" class="pmc-tab-panel">
                    <div class="pmc-import-section">
                        <h3><?php _e('Import History', 'product-media-carousel'); ?></h3>
                        <p class="description"><?php _e('View previous import operations.', 'product-media-carousel'); ?></p>
                        
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th><?php _e('Date', 'product-media-carousel'); ?></th>
                                    <th><?php _e('Type', 'product-media-carousel'); ?></th>
                                    <th><?php _e('Total', 'product-media-carousel'); ?></th>
                                    <th><?php _e('Success', 'product-media-carousel'); ?></th>
                                    <th><?php _e('Failed', 'product-media-carousel'); ?></th>
                                    <th><?php _e('Status', 'product-media-carousel'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $history = get_option('pmc_import_history', array());
                                if (empty($history)) {
                                    echo '<tr><td colspan="6">' . __('No import history yet.', 'product-media-carousel') . '</td></tr>';
                                } else {
                                    foreach (array_slice($history, -20) as $record) {
                                        ?>
                                        <tr>
                                            <td><?php echo esc_html($record['date']); ?></td>
                                            <td><?php echo esc_html($record['type']); ?></td>
                                            <td><?php echo intval($record['total']); ?></td>
                                            <td style="color: #46b450;"><?php echo intval($record['success']); ?></td>
                                            <td style="color: #dc3232;"><?php echo intval($record['failed']); ?></td>
                                            <td><?php echo esc_html($record['status']); ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .pmc-bulk-import-wrapper {
            background: #fff;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid #ccd0d4;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        .pmc-tab-panel {
            display: none;
            padding: 20px 0;
        }
        .pmc-tab-panel.active {
            display: block;
        }
        .pmc-import-section {
            max-width: 1200px;
        }
        .pmc-csv-example {
            background: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin: 15px 0;
        }
        .pmc-csv-example pre {
            background: #fff;
            padding: 10px;
            border: 1px solid #ddd;
            overflow-x: auto;
            margin: 10px 0;
        }
        .pmc-upload-area {
            margin: 20px 0;
        }
        .pmc-upload-area input[type="file"] {
            margin-right: 10px;
        }
        .pmc-progress-bar {
            width: 100%;
            height: 30px;
            background: #f0f0f0;
            border-radius: 4px;
            overflow: hidden;
            margin: 15px 0;
        }
        .pmc-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #0073aa, #00a0d2);
            width: 0%;
            transition: width 0.3s;
        }
        .pmc-progress-text {
            text-align: center;
            font-weight: 600;
            font-size: 16px;
        }
        #pmc-import-log {
            max-height: 300px;
            overflow-y: auto;
            background: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            margin-top: 15px;
        }
        .pmc-log-item {
            padding: 5px;
            margin: 2px 0;
            border-left: 3px solid #999;
        }
        .pmc-log-item.success {
            border-left-color: #46b450;
            background: #ecf7ed;
        }
        .pmc-log-item.error {
            border-left-color: #dc3232;
            background: #fef7f1;
        }
        .pmc-import-actions {
            margin-top: 20px;
        }
        #pmc-manual-table input,
        #pmc-manual-table select {
            width: 100%;
        }
        .pmc-search-results {
            position: relative;
            background: #fff;
            border: 1px solid #ddd;
            margin-top: 5px;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }
        .pmc-product-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .pmc-product-list li {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }
        .pmc-product-list li:hover {
            background: #f0f6fc;
        }
        </style>
        <?php
    }
    
    /**
     * AJAX: Parse CSV file
     */
    public function ajax_parse_csv() {
        check_ajax_referer('pmc_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error(array('message' => __('Permission denied', 'product-media-carousel')));
        }
        
        if (!PMC_Restrictions::is_pro()) {
            wp_send_json_error(array('message' => __('Pro version required', 'product-media-carousel')));
        }
        
        // Handle file upload
        if (empty($_FILES['csv_file'])) {
            wp_send_json_error(array('message' => __('No file uploaded', 'product-media-carousel')));
        }
        
        $file = $_FILES['csv_file'];
        $csv_data = array();
        
        if (($handle = fopen($file['tmp_name'], 'r')) !== false) {
            $header = fgetcsv($handle);
            
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 3) {
                    $csv_data[] = array(
                        'product_id' => intval($row[0]),
                        'video_url' => esc_url_raw($row[1]),
                        'video_type' => sanitize_text_field($row[2])
                    );
                }
            }
            fclose($handle);
        }
        
        wp_send_json_success(array(
            'data' => $csv_data,
            'count' => count($csv_data)
        ));
    }
    
    /**
     * AJAX: Bulk import videos
     */
    public function ajax_bulk_import() {
        try {
            check_ajax_referer('pmc_admin_nonce', 'nonce');
            
            if (!current_user_can('manage_woocommerce')) {
                wp_send_json_error(array('message' => __('Permission denied', 'product-media-carousel')));
            }
            
            if (!PMC_Restrictions::is_pro()) {
                wp_send_json_error(array('message' => __('Pro version required', 'product-media-carousel')));
            }
            
            $data = isset($_POST['data']) ? json_decode(stripslashes($_POST['data']), true) : array();
            
            if (empty($data)) {
                wp_send_json_error(array('message' => __('No data provided', 'product-media-carousel')));
            }
        } catch (Exception $e) {
            error_log('PMC Bulk Import Error: ' . $e->getMessage());
            wp_send_json_error(array('message' => 'Error: ' . $e->getMessage()));
        }
        
        $results = array(
            'total' => count($data),
            'success' => 0,
            'failed' => 0,
            'details' => array()
        );
        
        foreach ($data as $item) {
            $product_id = intval($item['product_id']);
            $video_url = esc_url_raw($item['video_url']);
            $video_type = sanitize_text_field($item['video_type']);
            
            // Validate product exists
            $product = wc_get_product($product_id);
            if (!$product) {
                $results['failed']++;
                $results['details'][] = array(
                    'product_id' => $product_id,
                    'status' => 'error',
                    'message' => __('Product not found', 'product-media-carousel')
                );
                continue;
            }
            
            // Extract video ID based on type
            $video_id = '';
            
            if ($video_type === 'youtube') {
                // Extract YouTube ID
                preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video_url, $matches);
                $video_id = isset($matches[1]) ? $matches[1] : '';
            } elseif ($video_type === 'vimeo') {
                // Extract Vimeo ID
                preg_match('/vimeo\.com\/(\d+)/', $video_url, $matches);
                $video_id = isset($matches[1]) ? $matches[1] : '';
            } elseif ($video_type === 'self_hosted') {
                // For self-hosted, use the full URL
                $video_id = $video_url;
            }
            
            if (!$video_id) {
                $results['failed']++;
                $results['details'][] = array(
                    'product_id' => $product_id,
                    'status' => 'error',
                    'message' => 'Invalid ' . $video_type . ' video URL: ' . $video_url
                );
                continue;
            }
            
            // Get max order
            $max_order = PMC_Database::get_max_order($product_id, 0);
            
            // Add media
            error_log("PMC Bulk Import: Adding video - Product: $product_id, Type: $video_type, ID: $video_id");
            
            $result = PMC_Database::add_media($product_id, $video_type, $video_id, $max_order + 1, 0);
            
            error_log("PMC Bulk Import: Add media result: " . ($result ? 'SUCCESS' : 'FAILED'));
            
            if ($result) {
                $results['success']++;
                $results['details'][] = array(
                    'product_id' => $product_id,
                    'product_name' => $product->get_name(),
                    'video_url' => $video_url,
                    'status' => 'success',
                    'message' => __('Video added successfully', 'product-media-carousel')
                );
            } else {
                $results['failed']++;
                $results['details'][] = array(
                    'product_id' => $product_id,
                    'product_name' => $product->get_name(),
                    'video_url' => $video_url,
                    'status' => 'error',
                    'message' => __('Database error: Failed to insert video', 'product-media-carousel')
                );
            }
        }
        
        error_log("PMC Bulk Import: Final results - Total: {$results['total']}, Success: {$results['success']}, Failed: {$results['failed']}");
        
        // Save to history
        $history = get_option('pmc_import_history', array());
        $history[] = array(
            'date' => current_time('mysql'),
            'type' => 'Bulk Import',
            'total' => $results['total'],
            'success' => $results['success'],
            'failed' => $results['failed'],
            'status' => 'Completed'
        );
        update_option('pmc_import_history', $history);
        
        wp_send_json_success($results);
    }
}

// Initialize
PMC_Bulk_Import::get_instance();
