<?php
/**
 * Plugin Name: WP URL Update
 * Plugin URI: https://github.com/below43/wp-url-update
 * Description: Update image URLs from one domain to another across your WordPress site
 * Version: 1.0.0
 * Author: Andrew Drake
 * Author URI: https://github.com/below43
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-url-update
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WP_URL_UPDATE_VERSION', '1.0.0');
define('WP_URL_UPDATE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_URL_UPDATE_PLUGIN_URL', plugin_dir_url(__FILE__));

class WP_URL_Update {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('wp_ajax_wp_url_update_process', array($this, 'ajax_process_update'));
    }
    
    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_management_page(
            __('Image URL Update', 'wp-url-update'),
            __('Image URL Update', 'wp-url-update'),
            'manage_options',
            'wp-url-update',
            array($this, 'render_admin_page')
        );
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if ('tools_page_wp-url-update' !== $hook) {
            return;
        }
        
        wp_enqueue_style(
            'wp-url-update-admin',
            WP_URL_UPDATE_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            WP_URL_UPDATE_VERSION
        );
        
        wp_enqueue_script(
            'wp-url-update-admin',
            WP_URL_UPDATE_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            WP_URL_UPDATE_VERSION,
            true
        );
        
        wp_localize_script('wp-url-update-admin', 'wpUrlUpdate', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_url_update_nonce')
        ));
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        ?>
        <div class="wrap wp-url-update-wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="wp-url-update-notice notice notice-warning">
                <p><strong><?php _e('Important:', 'wp-url-update'); ?></strong> <?php _e('Always backup your database before running URL updates. This operation cannot be undone!', 'wp-url-update'); ?></p>
            </div>
            
            <div class="wp-url-update-container">
                <form id="wp-url-update-form" method="post">
                    <?php wp_nonce_field('wp_url_update_action', 'wp_url_update_nonce'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="old_url"><?php _e('Old URL', 'wp-url-update'); ?></label>
                            </th>
                            <td>
                                <input type="url" 
                                       id="old_url" 
                                       name="old_url" 
                                       class="regular-text" 
                                       placeholder="https://old-domain.com"
                                       required>
                                <p class="description"><?php _e('Enter the old URL (including http:// or https://)', 'wp-url-update'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="new_url"><?php _e('New URL', 'wp-url-update'); ?></label>
                            </th>
                            <td>
                                <input type="url" 
                                       id="new_url" 
                                       name="new_url" 
                                       class="regular-text" 
                                       placeholder="https://new-domain.com"
                                       required>
                                <p class="description"><?php _e('Enter the new URL (including http:// or https://)', 'wp-url-update'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('What will be updated', 'wp-url-update'); ?></th>
                            <td>
                                <p class="description">
                                    <?php _e('The following will be automatically updated:', 'wp-url-update'); ?>
                                </p>
                                <ul style="list-style: disc; margin-left: 20px;">
                                    <li><?php _e('Image URLs in post content within wp-content/uploads/', 'wp-url-update'); ?></li>
                                </ul>
                                <p class="description">
                                    <strong><?php _e('Note:', 'wp-url-update'); ?></strong> 
                                    <?php _e('Only images in the uploads directory will be updated. This is the safest approach for image URL migration.', 'wp-url-update'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Test Mode', 'wp-url-update'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="dry_run" id="dry_run" checked>
                                    <?php _e('Dry run (preview only - no changes will be made)', 'wp-url-update'); ?>
                                </label>
                                <p class="description">
                                    <?php _e('Recommended: Run in dry run mode first to see what will be changed before making actual updates.', 'wp-url-update'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <button type="submit" class="button button-primary" id="wp-url-update-submit">
                            <?php _e('Update URLs', 'wp-url-update'); ?>
                        </button>
                    </p>
                </form>
                
                <div id="wp-url-update-results" style="display: none;">
                    <h2><?php _e('Update Results', 'wp-url-update'); ?></h2>
                    <div id="wp-url-update-progress"></div>
                    <div id="wp-url-update-log"></div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * AJAX handler for image URL update process
     */
    public function ajax_process_update() {
        check_ajax_referer('wp_url_update_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Insufficient permissions', 'wp-url-update')]);
        }
        
        if (is_multisite()) {
            wp_send_json_error(['message' => __('Multisite is not supported.', 'wp-url-update')]);
        }
        
        $old_url = isset($_POST['old_url']) ? esc_url_raw($_POST['old_url']) : '';
        $new_url = isset($_POST['new_url']) ? esc_url_raw($_POST['new_url']) : '';
        
        $old_url = rtrim($old_url, '/');
        $new_url = rtrim($new_url, '/');
        
        if (empty($old_url) || empty($new_url)) {
            wp_send_json_error(['message' => __('Both URLs are required', 'wp-url-update')]);
        }
        
        $dry_run = isset($_POST['dry_run']) && $_POST['dry_run'] === 'true';
        
        // Image-only replacement path - target wp-content/uploads specifically
        $old_path = trailingslashit($old_url) . 'wp-content/uploads/';
        $new_path = trailingslashit($new_url) . 'wp-content/uploads/';
        
        global $wpdb;
        
        try {
            // Count affected rows
            $count = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->posts}
                     WHERE post_content LIKE %s",
                    '%' . $wpdb->esc_like($old_path) . '%'
                )
            );
            
            $updated = 0;
            
            // Only perform update if not in dry run mode
            if (!$dry_run) {
                $updated = $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE {$wpdb->posts}
                         SET post_content = REPLACE(post_content, %s, %s)
                         WHERE post_content LIKE %s",
                        $old_path,
                        $new_path,
                        '%' . $wpdb->esc_like($old_path) . '%'
                    )
                );
                
                wp_cache_flush();
            }
            
            wp_send_json_success([
                'message' => $dry_run 
                    ? __('Dry run complete - no changes were made.', 'wp-url-update')
                    : __('Image URLs updated successfully.', 'wp-url-update'),
                'dry_run' => $dry_run,
                'found'   => intval($count),
                'updated' => intval($updated),
                'from'    => $old_path,
                'to'      => $new_path,
            ]);
            
        } catch (Throwable $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }
    }
}

// Initialize the plugin
function wp_url_update_init() {
    return WP_URL_Update::get_instance();
}
add_action('plugins_loaded', 'wp_url_update_init');
