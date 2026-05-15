<?php
/**
 * Help & Support Page
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class PMC_Help_Page {
    
    /**
     * Instance
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
        add_action('admin_menu', array($this, 'add_help_page'), 100);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_ajax_pmc_submit_support', array($this, 'ajax_submit_support'));
    }
    
    /**
     * AJAX: Submit support ticket
     */
    public function ajax_submit_support() {
        check_ajax_referer('pmc_help_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'product-media-carousel')));
        }
        
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $subject = sanitize_text_field($_POST['subject']);
        $message = sanitize_textarea_field($_POST['message']);
        
        // Validate
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            wp_send_json_error(array('message' => __('All fields are required', 'product-media-carousel')));
        }
        
        if (!is_email($email)) {
            wp_send_json_error(array('message' => __('Invalid email address', 'product-media-carousel')));
        }
        
        // Send email
        $to = 'frankie@everyideas.com';
        $email_subject = '[PMC Support] ' . $subject;
        $email_message = "Name: $name\n";
        $email_message .= "Email: $email\n";
        $email_message .= "Website: " . get_site_url() . "\n\n";
        $email_message .= "Message:\n$message\n\n";
        $email_message .= "---\n";
        $email_message .= "Plugin Version: " . PMC_VERSION . "\n";
        $email_message .= "WordPress Version: " . get_bloginfo('version') . "\n";
        $email_message .= "WooCommerce Version: " . (defined('WC_VERSION') ? WC_VERSION : 'N/A') . "\n";
        
        $headers = array(
            'From: ' . $name . ' <' . $email . '>',
            'Reply-To: ' . $email
        );
        
        $sent = wp_mail($to, $email_subject, $email_message, $headers);
        
        if ($sent) {
            wp_send_json_success(array('message' => __('Your message has been sent successfully!', 'product-media-carousel')));
        } else {
            wp_send_json_error(array('message' => __('Failed to send message. Please try again or email us directly.', 'product-media-carousel')));
        }
    }
    
    /**
     * Add help page to menu
     */
    public function add_help_page() {
        // Add top-level menu
        add_menu_page(
            __('Product Media Carousel', 'product-media-carousel'),
            __('Media Carousel', 'product-media-carousel'),
            'manage_options',
            'product-media-carousel',
            array($this, 'render_help_page'),
            'dashicons-images-alt2',
            56
        );
        
        // Add submenu items
        add_submenu_page(
            'product-media-carousel',
            __('Get Help', 'product-media-carousel'),
            __('Get Help', 'product-media-carousel'),
            'manage_options',
            'product-media-carousel',
            array($this, 'render_help_page')
        );
        
        // Add Settings submenu
        add_submenu_page(
            'product-media-carousel',
            __('Settings', 'product-media-carousel'),
            __('Settings', 'product-media-carousel'),
            'manage_options',
            'pmc-settings',
            array($this, 'render_settings_page')
        );
        
        // Add link to Bulk Import (if Pro)
        if (PMC_Restrictions::is_pro()) {
            add_submenu_page(
                'product-media-carousel',
                __('Bulk Video Import', 'product-media-carousel'),
                __('Bulk Import', 'product-media-carousel'),
                'manage_options',
                'admin.php?page=pmc-bulk-import'
            );
        }
        
        // Add link to Products
        add_submenu_page(
            'product-media-carousel',
            __('All Products', 'product-media-carousel'),
            __('All Products', 'product-media-carousel'),
            'manage_options',
            'edit.php?post_type=product'
        );
    }
    
    /**
     * Enqueue styles
     */
    public function enqueue_styles($hook) {
        if (strpos($hook, 'product-media-carousel') === false && strpos($hook, 'pmc-settings') === false) {
            return;
        }
        
        wp_enqueue_style('pmc-help', PMC_PLUGIN_URL . 'assets/css/help.css', array(), PMC_VERSION);
        wp_enqueue_script('pmc-help', PMC_PLUGIN_URL . 'assets/js/help.js', array('jquery'), PMC_VERSION, true);
        
        // Localize script
        wp_localize_script('pmc-help', 'pmcHelp', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pmc_help_nonce'),
            'strings' => array(
                'sending' => __('Sending...', 'product-media-carousel'),
                'sent' => __('Message sent successfully!', 'product-media-carousel'),
                'error' => __('Error sending message. Please try again.', 'product-media-carousel'),
            )
        ));
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap pmc-help-page">
            <h1><?php _e('Settings', 'product-media-carousel'); ?></h1>
            
            <div class="pmc-help-container">
                <div class="pmc-help-section">
                    <h2>⚙️ <?php _e('General Settings', 'product-media-carousel'); ?></h2>
                    <p><?php _e('Settings page coming soon...', 'product-media-carousel'); ?></p>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render help page
     */
    public function render_help_page() {
        ?>
        <div class="wrap pmc-help-page">
            <h1><?php _e('Product Media Carousel - Help & Support', 'product-media-carousel'); ?></h1>
            
            <div class="pmc-help-container">
                
                <!-- Welcome Section -->
                <div class="pmc-help-section pmc-welcome">
                    <div class="pmc-welcome-content">
                        <h2>👋 <?php _e('Welcome to Product Media Carousel!', 'product-media-carousel'); ?></h2>
                        <p><?php _e('Thank you for choosing Product Media Carousel. We\'re here to help you get the most out of the plugin.', 'product-media-carousel'); ?></p>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="pmc-help-grid">
                    
                    <!-- Documentation -->
                    <div class="pmc-help-card">
                        <div class="pmc-help-icon">📚</div>
                        <h3><?php _e('Documentation', 'product-media-carousel'); ?></h3>
                        <p><?php _e('Comprehensive guides and tutorials to help you get started.', 'product-media-carousel'); ?></p>
                        <button type="button" class="button button-primary pmc-open-docs">
                            <?php _e('View Documentation', 'product-media-carousel'); ?>
                        </button>
                    </div>
                    
                    <!-- Video Tutorials -->
                    <div class="pmc-help-card">
                        <div class="pmc-help-icon">🎬</div>
                        <h3><?php _e('Video Tutorials', 'product-media-carousel'); ?></h3>
                        <p><?php _e('Watch step-by-step video guides for all features.', 'product-media-carousel'); ?></p>
                        <button type="button" class="button button-primary pmc-open-videos">
                            <?php _e('Watch Videos', 'product-media-carousel'); ?>
                        </button>
                    </div>
                    
                    <!-- Support Ticket -->
                    <div class="pmc-help-card">
                        <div class="pmc-help-icon">🎫</div>
                        <h3><?php _e('Submit Support Ticket', 'product-media-carousel'); ?></h3>
                        <p><?php _e('Need help? Our support team is ready to assist you.', 'product-media-carousel'); ?></p>
                        <button type="button" class="button button-primary pmc-open-support">
                            <?php _e('Get Support', 'product-media-carousel'); ?>
                        </button>
                    </div>
                    
                    <!-- FAQ -->
                    <div class="pmc-help-card">
                        <div class="pmc-help-icon">❓</div>
                        <h3><?php _e('FAQ', 'product-media-carousel'); ?></h3>
                        <p><?php _e('Find answers to commonly asked questions.', 'product-media-carousel'); ?></p>
                        <button type="button" class="button button-primary pmc-open-faq">
                            <?php _e('View FAQ', 'product-media-carousel'); ?>
                        </button>
                    </div>
                    
                </div>
                
                <!-- Quick Start Guide -->
                <div class="pmc-help-section">
                    <h2>🚀 <?php _e('Quick Start Guide', 'product-media-carousel'); ?></h2>
                    
                    <div class="pmc-steps">
                        <div class="pmc-step">
                            <div class="pmc-step-number">1</div>
                            <div class="pmc-step-content">
                                <h4><?php _e('Add Videos to Products', 'product-media-carousel'); ?></h4>
                                <p><?php _e('Go to Products → Edit Product → Product Media Carousel section. Add YouTube, Vimeo, or self-hosted videos.', 'product-media-carousel'); ?></p>
                            </div>
                        </div>
                        
                        <div class="pmc-step">
                            <div class="pmc-step-number">2</div>
                            <div class="pmc-step-content">
                                <h4><?php _e('Use Elementor Widget', 'product-media-carousel'); ?></h4>
                                <p><?php _e('Edit your product page with Elementor, search for "Product Media Carousel" widget and drag it to your page.', 'product-media-carousel'); ?></p>
                            </div>
                        </div>
                        
                        <div class="pmc-step">
                            <div class="pmc-step-number">3</div>
                            <div class="pmc-step-content">
                                <h4><?php _e('Customize Settings', 'product-media-carousel'); ?></h4>
                                <p><?php _e('Configure carousel effects, navigation styles, thumbnails, and more from the widget settings.', 'product-media-carousel'); ?></p>
                            </div>
                        </div>
                        
                        <?php if (PMC_Restrictions::is_pro()): ?>
                        <div class="pmc-step">
                            <div class="pmc-step-number">4</div>
                            <div class="pmc-step-content">
                                <h4><?php _e('Bulk Import Videos (Pro)', 'product-media-carousel'); ?></h4>
                                <p><?php _e('Go to Products → Bulk Video Import. Upload CSV or manually add videos to multiple products at once.', 'product-media-carousel'); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Common Issues -->
                <div class="pmc-help-section">
                    <h2>🔧 <?php _e('Common Issues & Solutions', 'product-media-carousel'); ?></h2>
                    
                    <div class="pmc-faq-list">
                        <div class="pmc-faq-item">
                            <h4><?php _e('Videos not showing on frontend?', 'product-media-carousel'); ?></h4>
                            <p><?php _e('Make sure you\'ve added the Elementor widget to your product page template. Check if videos are added in the product edit page.', 'product-media-carousel'); ?></p>
                        </div>
                        
                        <div class="pmc-faq-item">
                            <h4><?php _e('Carousel not working?', 'product-media-carousel'); ?></h4>
                            <p><?php _e('Clear your browser cache and WordPress cache. Make sure jQuery and Swiper.js are loaded correctly.', 'product-media-carousel'); ?></p>
                        </div>
                        
                        <div class="pmc-faq-item">
                            <h4><?php _e('Bulk import not working?', 'product-media-carousel'); ?></h4>
                            <p><?php _e('Check your CSV format: product_id,video_url,video_type. Make sure product IDs exist and video URLs are valid.', 'product-media-carousel'); ?></p>
                        </div>
                        
                        <div class="pmc-faq-item">
                            <h4><?php _e('How to find Product ID?', 'product-media-carousel'); ?></h4>
                            <p><?php _e('Go to Products → All Products. The ID column shows each product\'s ID. You can also search by product name in bulk import.', 'product-media-carousel'); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- System Info -->
                <div class="pmc-help-section">
                    <h2>⚙️ <?php _e('System Information', 'product-media-carousel'); ?></h2>
                    
                    <div class="pmc-system-info">
                        <table class="widefat">
                            <tbody>
                                <tr>
                                    <td><strong><?php _e('Plugin Version', 'product-media-carousel'); ?></strong></td>
                                    <td><?php echo PMC_VERSION; ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php _e('License Type', 'product-media-carousel'); ?></strong></td>
                                    <td><?php echo PMC_Restrictions::is_pro() ? '<span class="pmc-badge-pro">✨ PRO</span>' : '<span class="pmc-badge-free">FREE</span>'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php _e('WordPress Version', 'product-media-carousel'); ?></strong></td>
                                    <td><?php echo get_bloginfo('version'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php _e('WooCommerce Version', 'product-media-carousel'); ?></strong></td>
                                    <td><?php echo defined('WC_VERSION') ? WC_VERSION : 'Not installed'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php _e('Elementor Version', 'product-media-carousel'); ?></strong></td>
                                    <td><?php echo defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : 'Not installed'; ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php _e('PHP Version', 'product-media-carousel'); ?></strong></td>
                                    <td><?php echo PHP_VERSION; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Upgrade to Pro -->
                <?php if (!PMC_Restrictions::is_pro()): ?>
                <div class="pmc-help-section pmc-upgrade-section">
                    <h2>✨ <?php _e('Upgrade to Pro', 'product-media-carousel'); ?></h2>
                    <p><?php _e('Unlock powerful features and save hours of work!', 'product-media-carousel'); ?></p>
                    
                    <div class="pmc-pro-features">
                        <ul>
                            <li>✅ <?php _e('Vimeo & Self-Hosted Videos', 'product-media-carousel'); ?></li>
                            <li>✅ <?php _e('Bulk Video Import (CSV)', 'product-media-carousel'); ?></li>
                            <li>✅ <?php _e('Product Name Search', 'product-media-carousel'); ?></li>
                            <li>✅ <?php _e('Import History Tracking', 'product-media-carousel'); ?></li>
                            <li>✅ <?php _e('Video File Upload', 'product-media-carousel'); ?></li>
                            <li>✅ <?php _e('Priority Support', 'product-media-carousel'); ?></li>
                        </ul>
                    </div>
                    
                    <a href="https://everyideas.com/product/product-media-carousel-pro/" target="_blank" class="button button-primary button-hero">
                        <?php _e('Upgrade to Pro - $39/year', 'product-media-carousel'); ?>
                    </a>
                </div>
                <?php endif; ?>
                
                <!-- Contact -->
                <div class="pmc-help-section pmc-contact">
                    <h2>📧 <?php _e('Need More Help?', 'product-media-carousel'); ?></h2>
                    <p><?php _e('Our support team is here to help you succeed!', 'product-media-carousel'); ?></p>
                    
                    <div class="pmc-contact-info">
                        <p>
                            <strong><?php _e('Email:', 'product-media-carousel'); ?></strong> 
                            <a href="mailto:frankie@everyideas.com">frankie@everyideas.com</a>
                        </p>
                        <p>
                            <strong><?php _e('Website:', 'product-media-carousel'); ?></strong> 
                            <a href="https://everyideas.com" target="_blank">everyideas.com</a>
                        </p>
                        <p>
                            <strong><?php _e('Response Time:', 'product-media-carousel'); ?></strong> 
                            <?php echo PMC_Restrictions::is_pro() ? __('Within 24 hours (Priority)', 'product-media-carousel') : __('Within 48 hours', 'product-media-carousel'); ?>
                        </p>
                    </div>
                </div>
                
            </div>
            
            <!-- Modals -->
            <?php $this->render_modals(); ?>
        </div>
        <?php
    }
    
    /**
     * Render modals
     */
    private function render_modals() {
        ?>
        <!-- Documentation Modal -->
        <div id="pmc-docs-modal" class="pmc-modal">
            <div class="pmc-modal-content">
                <span class="pmc-modal-close">&times;</span>
                <h2>📚 <?php _e('Documentation', 'product-media-carousel'); ?></h2>
                <div class="pmc-modal-body">
                    <?php $this->render_documentation(); ?>
                </div>
            </div>
        </div>
        
        <!-- Video Tutorials Modal -->
        <div id="pmc-videos-modal" class="pmc-modal">
            <div class="pmc-modal-content">
                <span class="pmc-modal-close">&times;</span>
                <h2>🎬 <?php _e('Video Tutorials', 'product-media-carousel'); ?></h2>
                <div class="pmc-modal-body">
                    <p style="text-align: center; padding: 40px;">
                        <span style="font-size: 48px;">🎬</span><br><br>
                        <strong><?php _e('Coming Soon!', 'product-media-carousel'); ?></strong><br>
                        <?php _e('Video tutorials are being created. Check back soon!', 'product-media-carousel'); ?>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Support Modal -->
        <div id="pmc-support-modal" class="pmc-modal">
            <div class="pmc-modal-content">
                <span class="pmc-modal-close">&times;</span>
                <h2>🎫 <?php _e('Submit Support Ticket', 'product-media-carousel'); ?></h2>
                <div class="pmc-modal-body">
                    <form id="pmc-support-form">
                        <p>
                            <label for="pmc-support-name"><?php _e('Your Name', 'product-media-carousel'); ?> *</label>
                            <input type="text" id="pmc-support-name" name="name" required class="widefat">
                        </p>
                        <p>
                            <label for="pmc-support-email"><?php _e('Your Email', 'product-media-carousel'); ?> *</label>
                            <input type="email" id="pmc-support-email" name="email" required class="widefat">
                        </p>
                        <p>
                            <label for="pmc-support-subject"><?php _e('Subject', 'product-media-carousel'); ?> *</label>
                            <input type="text" id="pmc-support-subject" name="subject" required class="widefat">
                        </p>
                        <p>
                            <label for="pmc-support-message"><?php _e('Message', 'product-media-carousel'); ?> *</label>
                            <textarea id="pmc-support-message" name="message" required rows="8" class="widefat"></textarea>
                        </p>
                        <p>
                            <button type="submit" class="button button-primary button-large">
                                <?php _e('Send Message', 'product-media-carousel'); ?>
                            </button>
                        </p>
                        <div id="pmc-support-response"></div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- FAQ Modal -->
        <div id="pmc-faq-modal" class="pmc-modal">
            <div class="pmc-modal-content pmc-modal-large">
                <span class="pmc-modal-close">&times;</span>
                <h2>❓ <?php _e('Frequently Asked Questions', 'product-media-carousel'); ?></h2>
                <div class="pmc-modal-body">
                    <?php $this->render_faq(); ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render documentation content
     */
    private function render_documentation() {
        ?>
        <div class="pmc-docs-list">
            <div class="pmc-doc-item">
                <h3>🚀 <?php _e('Getting Started', 'product-media-carousel'); ?></h3>
                <ul>
                    <li><?php _e('Installation and activation', 'product-media-carousel'); ?></li>
                    <li><?php _e('Adding your first video', 'product-media-carousel'); ?></li>
                    <li><?php _e('Using the Elementor widget', 'product-media-carousel'); ?></li>
                    <li><?php _e('Customizing carousel settings', 'product-media-carousel'); ?></li>
                </ul>
            </div>
            
            <div class="pmc-doc-item">
                <h3>📹 <?php _e('Video Management', 'product-media-carousel'); ?></h3>
                <ul>
                    <li><?php _e('Adding YouTube videos', 'product-media-carousel'); ?></li>
                    <li><?php _e('Adding Vimeo videos (Pro)', 'product-media-carousel'); ?></li>
                    <li><?php _e('Uploading self-hosted videos (Pro)', 'product-media-carousel'); ?></li>
                    <li><?php _e('Reordering videos with arrows', 'product-media-carousel'); ?></li>
                    <li><?php _e('Deleting videos', 'product-media-carousel'); ?></li>
                </ul>
            </div>
            
            <div class="pmc-doc-item">
                <h3>⚡ <?php _e('Bulk Import (Pro)', 'product-media-carousel'); ?></h3>
                <ul>
                    <li><?php _e('CSV file format', 'product-media-carousel'); ?></li>
                    <li><?php _e('Bulk import via CSV upload', 'product-media-carousel'); ?></li>
                    <li><?php _e('Manual bulk input', 'product-media-carousel'); ?></li>
                    <li><?php _e('Product name search', 'product-media-carousel'); ?></li>
                    <li><?php _e('Viewing import history', 'product-media-carousel'); ?></li>
                </ul>
            </div>
            
            <div class="pmc-doc-item">
                <h3>🎨 <?php _e('Customization', 'product-media-carousel'); ?></h3>
                <ul>
                    <li><?php _e('Carousel effects and transitions', 'product-media-carousel'); ?></li>
                    <li><?php _e('Navigation styles', 'product-media-carousel'); ?></li>
                    <li><?php _e('Thumbnail positioning', 'product-media-carousel'); ?></li>
                    <li><?php _e('Autoplay settings', 'product-media-carousel'); ?></li>
                    <li><?php _e('Lightbox configuration', 'product-media-carousel'); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render FAQ content
     */
    private function render_faq() {
        $faqs = array(
            array(
                'question' => __('How do I add videos to my products?', 'product-media-carousel'),
                'answer' => __('Go to Products → Edit Product, scroll down to the "Product Media Carousel" section, enter a YouTube URL and click "Add Video URL". For Pro users, you can also add Vimeo and self-hosted videos.', 'product-media-carousel')
            ),
            array(
                'question' => __('Can I use this plugin without Elementor?', 'product-media-carousel'),
                'answer' => __('Currently, the plugin requires Elementor to display the carousel on the frontend. We are working on adding shortcode support for non-Elementor users.', 'product-media-carousel')
            ),
            array(
                'question' => __('How does bulk import work?', 'product-media-carousel'),
                'answer' => __('Bulk import (Pro feature) allows you to add videos to multiple products at once. You can either upload a CSV file with product IDs and video URLs, or manually enter them using the product search feature. The CSV format is: product_id,video_url,video_type', 'product-media-carousel')
            ),
            array(
                'question' => __('Where can I find the Product ID?', 'product-media-carousel'),
                'answer' => __('Go to Products → All Products. You\'ll see an "ID" column showing each product\'s ID. You can also use the product name search in bulk import (Pro) instead of remembering IDs.', 'product-media-carousel')
            ),
            array(
                'question' => __('Videos not showing on frontend?', 'product-media-carousel'),
                'answer' => __('Make sure you\'ve added the "Product Media Carousel" Elementor widget to your product page template. Also check if videos are properly added in the product edit page.', 'product-media-carousel')
            ),
            array(
                'question' => __('What video formats are supported?', 'product-media-carousel'),
                'answer' => __('Free version supports YouTube. Pro version supports YouTube, Vimeo, and self-hosted videos (MP4, WebM).', 'product-media-carousel')
            ),
            array(
                'question' => __('Can I reorder videos?', 'product-media-carousel'),
                'answer' => __('Yes! Use the up/down arrow buttons next to each video in the product edit page to reorder them. The order will be automatically saved.', 'product-media-carousel')
            ),
            array(
                'question' => __('How do I upgrade to Pro?', 'product-media-carousel'),
                'answer' => __('Visit our website at everyideas.com to purchase a Pro license. After purchase, you\'ll receive a license key to activate Pro features.', 'product-media-carousel')
            ),
            array(
                'question' => __('Do you offer refunds?', 'product-media-carousel'),
                'answer' => __('Yes, we offer a 30-day money-back guarantee. If you\'re not satisfied with the plugin, contact us for a full refund.', 'product-media-carousel')
            ),
            array(
                'question' => __('How do I get support?', 'product-media-carousel'),
                'answer' => __('Use the "Submit Support Ticket" button on this page to send us a message. Pro users get priority support with response within 24 hours.', 'product-media-carousel')
            ),
        );
        ?>
        <div class="pmc-faq-accordion">
            <?php foreach ($faqs as $index => $faq): ?>
            <div class="pmc-faq-accordion-item">
                <button class="pmc-faq-accordion-button" type="button">
                    <span class="pmc-faq-icon">❓</span>
                    <?php echo esc_html($faq['question']); ?>
                    <span class="pmc-faq-toggle">+</span>
                </button>
                <div class="pmc-faq-accordion-content">
                    <p><?php echo esc_html($faq['answer']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}

// Initialize
PMC_Help_Page::get_instance();
