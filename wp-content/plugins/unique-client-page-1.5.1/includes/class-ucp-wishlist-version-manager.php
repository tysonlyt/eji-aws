<?php
/**
 * Core functionality for wishlist versioning
 * 
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class UCP_Wishlist_Version_Manager {
    private static $instance = null;
    private $db;
    private $table;
    private $debug = false;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->table = $wpdb->prefix . 'ucp_wishlist_versions';
    }
    
    public function save_version($user_id, $page_id, $wishlist_data, $version_name = '', $notes = '') {
        // Mark all other versions as not current
        $this->db->update(
            $this->table,
            array('is_current' => 0),
            array(
                'user_id' => $user_id,
                'page_id' => $page_id
            ),
            array('%d'),
            array('%d', '%d')
        );
        
        // Get next version number
        $version_number = $this->get_next_version_number($user_id, $page_id);
        
        // Insert new version
        $result = $this->db->insert(
            $this->table,
            array(
                'user_id' => $user_id,
                'page_id' => $page_id,
                'version_number' => $version_number,
                'version_name' => !empty($version_name) ? $version_name : 'Version ' . $version_number,
                'wishlist_data' => maybe_serialize($wishlist_data),
                'created_by' => get_current_user_id() ?: $user_id,
                'created_at' => current_time('mysql'),
                'is_current' => 1,
                'notes' => $notes
            ),
            array(
                '%d', '%d', '%d', '%s', '%s', '%d', '%s', '%d', '%s'
            )
        );
        
        if ($result === false) {
            error_log('Failed to save wishlist version: ' . $this->db->last_error);
            return false;
        }
        
        return $this->db->insert_id;
    }
    
    private function get_next_version_number($user_id, $page_id) {
        $version = $this->db->get_var($this->db->prepare(
            "SELECT COALESCE(MAX(version_number), 0) + 1 FROM {$this->table} 
             WHERE user_id = %d AND page_id = %d",
            $user_id,
            $page_id
        ));
        
        return intval($version);
    }
    
    public function get_versions($user_id, $page_id) {
        $results = $this->db->get_results($this->db->prepare(
            "SELECT * FROM {$this->table} 
             WHERE user_id = %d AND page_id = %d 
             ORDER BY version_number DESC",
            $user_id,
            $page_id
        ));
        
        if ($results) {
            foreach ($results as &$result) {
                $result->wishlist_data = maybe_unserialize($result->wishlist_data);
            }
        }
        
        return $results;
    }
    
    public function get_version($version_id) {
        $result = $this->db->get_row($this->db->prepare(
            "SELECT * FROM {$this->table} WHERE version_id = %d",
            $version_id
        ));
        
        if ($result) {
            $result->wishlist_data = maybe_unserialize($result->wishlist_data);
        }
        
        return $result;
    }
    
    public function restore_version($version_id) {
        $version = $this->get_version($version_id);
        if (!$version) {
            return false;
        }
        
        return $this->save_version(
            $version->user_id,
            $version->page_id,
            $version->wishlist_data,
            'Restored: ' . $version->version_name,
            'Restored from version ' . $version->version_number
        );
    }
    
    /**
     * Get all versions with optional filtering
     * 
     * @param int $user_id Optional user ID to filter by
     * @param int $page_id Optional page ID to filter by
     * @return array Array of version objects
     */
    public function get_all_versions($user_id = 0, $page_id = 0) {
        $query = "SELECT * FROM {$this->table}";
        $where = array();
        $params = array();
        
        if ($user_id > 0) {
            $where[] = "user_id = %d";
            $params[] = $user_id;
        }
        
        if ($page_id > 0) {
            $where[] = "page_id = %d";
            $params[] = $page_id;
        }
        
        if (!empty($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }
        
        $query .= " ORDER BY created_at DESC";
        
        if (!empty($params)) {
            $query = $this->db->prepare($query, $params);
        }
        
        $results = $this->db->get_results($query);
        
        return $results;
    }
    
    /**
     * Get a version by its ID
     * 
     * @param int $version_id Version ID
     * @return object|null Version object or null if not found
     */
    public function get_version_by_id($version_id) {
        if (!$version_id) {
            return null;
        }
        
        $result = $this->db->get_row($this->db->prepare(
            "SELECT * FROM {$this->table} WHERE version_id = %d",
            $version_id
        ));
        
        return $result;
    }
    
    /**
     * Get a list of users who have wishlist versions
     * 
     * @return array Array of user IDs
     */
    public function get_users_with_versions() {
        $results = $this->db->get_col("SELECT DISTINCT user_id FROM {$this->table} ORDER BY user_id");
        
        return array_map('intval', $results);
    }
    
    /**
     * Set a version as the current active version
     * 
     * @param int $version_id Version ID to set as current
     * @param int $page_id Page ID (used for validation)
     * @return bool Success or failure
     */
    public function set_current_version($version_id, $page_id) {
        if (!$version_id || !$page_id) {
            return false;
        }
        
        // Verify the version exists and belongs to the specified page
        $version = $this->get_version($version_id);
        if (!$version || $version->page_id != $page_id) {
            return false;
        }
        
        // Start transaction
        $this->db->query('START TRANSACTION');
        
        try {
            // Mark all other versions for this user and page as not current
            $result1 = $this->db->update(
                $this->table,
                array('is_current' => 0),
                array(
                    'user_id' => $version->user_id,
                    'page_id' => $page_id
                ),
                array('%d'),
                array('%d', '%d')
            );
            
            // Mark the specified version as current
            $result2 = $this->db->update(
                $this->table,
                array('is_current' => 1),
                array('version_id' => $version_id),
                array('%d'),
                array('%d')
            );
            
            // Check if both operations were successful
            if ($result2 !== false) {
                $this->db->query('COMMIT');
                return true;
            } else {
                $this->db->query('ROLLBACK');
                return false;
            }
        } catch (Exception $e) {
            $this->db->query('ROLLBACK');
            error_log('Error setting current version: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete a version
     * 
     * @param int $version_id Version ID to delete
     * @return bool Success or failure
     */
    public function delete_version($version_id) {
        if (!$version_id) {
            return false;
        }
        
        // Check if this is a current version before deleting
        $is_current = $this->db->get_var($this->db->prepare(
            "SELECT is_current FROM {$this->table} WHERE version_id = %d",
            $version_id
        ));
        
        // Delete the version
        $result = $this->db->delete(
            $this->table,
            array('version_id' => $version_id),
            array('%d')
        );
        
        // If we deleted a current version, we need to set the most recent remaining version as current
        if ($result && $is_current) {
            $version = $this->db->get_row($this->db->prepare(
                "SELECT version_id, user_id, page_id FROM {$this->table} WHERE version_id = %d",
                $version_id
            ));
            
            if ($version) {
                // Get the most recent version for this user and page
                $new_current = $this->db->get_var($this->db->prepare(
                    "SELECT version_id FROM {$this->table} 
                     WHERE user_id = %d AND page_id = %d 
                     ORDER BY created_at DESC LIMIT 1",
                    $version->user_id,
                    $version->page_id
                ));
                
                if ($new_current) {
                    // Set this as the current version
                    $this->db->update(
                        $this->table,
                        array('is_current' => 1),
                        array('version_id' => $new_current),
                        array('%d'),
                        array('%d')
                    );
                }
            }
        }
        
        return $result !== false;
    }
    
    /**
     * Delete all versions associated with a specific page
     * 
     * @param int $page_id Page ID to delete versions for
     * @return int|bool Number of rows affected or false on error
     */
    public function delete_page_versions($page_id) {
        if (!$page_id) {
            return false;
        }
        
        if ($this->debug) {
            error_log("Deleting all wishlist versions for page ID: " . $page_id);
        }
        
        // Delete all versions for this page
        $result = $this->db->delete(
            $this->table,
            array('page_id' => $page_id),
            array('%d')
        );
        
        if ($this->debug && $result !== false) {
            error_log("Deleted {$result} wishlist version(s) for page ID: {$page_id}");
        }
        
        return $result;
    }
}

// Initialize
UCP_Wishlist_Version_Manager::get_instance();
