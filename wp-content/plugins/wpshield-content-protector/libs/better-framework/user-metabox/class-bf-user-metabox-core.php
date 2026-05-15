<?php
/***
 *  BetterFramework is BetterStudio framework for themes and plugins.
 *
 *  ______      _   _             ______                                           _
 *  | ___ \    | | | |            |  ___|                                         | |
 *  | |_/ / ___| |_| |_ ___ _ __  | |_ _ __ __ _ _ __ ___   _____      _____  _ __| | __
 *  | ___ \/ _ \ __| __/ _ \ '__| |  _| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
 *  | |_/ /  __/ |_| ||  __/ |    | | | | | (_| | | | | | |  __/\ V  V / (_) | |  |   <
 *  \____/ \___|\__|\__\___|_|    \_| |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\
 *
 *  Copyright © 2017 Better Studio
 *
 *
 *  Our portfolio is here: https://betterstudio.com/
 *
 *  \--> BetterStudio, 2018 <--/
 */

// Prevent Direct Access
defined( 'ABSPATH' ) or die;

// use Control API
use BetterFrameworkPackage\Component\Control;

/**
 * This class handles all functionality of BetterFramework Users Meta box feature for creating, saving, editing
 *
 * @package    BetterFramework
 * @since      1.4
 */
class BF_User_Metabox_Core {


	/**
	 * Contains all metabox's
	 *
	 * @var array
	 */
	public static $metabox = [];


	/**
	 * Contains config for all metabox's
	 *
	 * @var array
	 */
	public static $config = [];


	/**
	 * Contains all fields
	 *
	 * @var array
	 */
	public static $fields = [];


	/**
	 * Contains all std
	 *
	 * @var array
	 */
	public static $std = [];


	/**
	 * Contains all css
	 *
	 * @var array
	 */
	public static $css = [];


	/**
	 * Initializes all metaboxes
	 */
	public static function init_metabox() {

		static $loaded;

		if ( $loaded ) {
			return;
		}

		self::$metabox = apply_filters( 'better-framework/user-metabox/add', [] );

	}


	/**
	 * loads and returns metabox config
	 *
	 * @param string $metabox_id
	 *
	 * @return array
	 */
	public static function get_metabox_config( $metabox_id = '' ) {

		if ( empty( $metabox_id ) ) {
			return [];
		}

		if ( isset( self::$config[ $metabox_id ] ) ) {
			return self::$config[ $metabox_id ];
		}

		return self::$config[ $metabox_id ] = apply_filters( 'better-framework/user-metabox/' . $metabox_id . '/config', [] );
	}


	/**
	 * loads and returns metabox std values
	 *
	 * @param string $metabox_id
	 *
	 * @return array
	 */
	public static function get_metabox_std( $metabox_id = '' ) {

		if ( empty( $metabox_id ) || ! isset( self::$metabox[ $metabox_id ] ) ) {
			return [];
		}

		if ( isset( self::$std[ $metabox_id ] ) ) {
			return self::$std[ $metabox_id ];
		}

		return self::$std[ $metabox_id ] = apply_filters( 'better-framework/user-metabox/' . $metabox_id . '/std', [] );
	}


	/**
	 * loads and returns metabox std values
	 *
	 * @param string $metabox_id
	 *
	 * @return array
	 */
	public static function get_metabox_fields( $metabox_id = '' ) {

		if ( empty( $metabox_id ) || ! isset( self::$metabox[ $metabox_id ] ) ) {
			return [];
		}

		if ( isset( self::$fields[ $metabox_id ] ) ) {
			return self::$fields[ $metabox_id ];
		}

		return self::$fields[ $metabox_id ] = apply_filters( 'better-framework/user-metabox/' . $metabox_id . '/fields', [] );
	}


	/**
	 * loads and returns metabox css
	 *
	 * @param string $metabox_id
	 *
	 * @return array
	 */
	public static function get_metabox_css( $metabox_id = '' ) {

		if ( empty( $metabox_id ) || ! isset( self::$metabox[ $metabox_id ] ) ) {
			return [];
		}

		if ( isset( self::$css[ $metabox_id ] ) ) {
			return self::$css[ $metabox_id ];
		}

		return self::$css[ $metabox_id ] = apply_filters( 'better-framework/user-metabox/' . $metabox_id . '/css', [] );
	}


	/**
	 * Used to add action for constructing the meta box
	 *
	 * @since     1.4
	 * @access    public
	 */
	public function __construct() {

		self::init_metabox();

		// Add options form
		add_action( 'show_user_profile', [ $this, 'add_meta_boxes' ] );
		add_action( 'edit_user_profile', [ $this, 'add_meta_boxes' ] );

		add_action( 'edit_user_profile_update', [ $this, 'save' ], 1 );
		add_action( 'personal_options_update', [ $this, 'save' ], 1 );

		/**
		 * Action to handle ajax user metabox tabs
		 */
		add_action( 'better-framework/user-metabox/ajax-tab', [ $this, 'ajax_tab' ], 10, 3 );
	}


	/**
	 * Used for retrieve meta data values
	 *
	 * @param string $id meta box id
	 * @param          $user
	 *
	 * @since    1.4
	 * @return  array
	 * @access   public
	 */
	public function get_full_meta_data( $id, $user ) {

		global $pagenow;

		$output = [];

		if ( isset( self::$metabox[ $id ]['panel-id'] ) ) {
			$std_id = Better_Framework::options()->get_panel_std_id( self::$metabox[ $id ]['panel-id'] );
		} else {
			$std_id = 'std';
		}

		$metabox_std = self::get_metabox_std( $id );

		if ( $pagenow === 'post-new.php' ) {

			if ( ! empty( $metabox_std ) ) {

				foreach ( (array) $metabox_std as $field_id => $field ) {

					if ( isset( $field[ $std_id ] ) ) {
						$output[ $field_id ] = $field[ $std_id ];
					} elseif ( isset( $field['std'] ) ) {
						$output[ $field_id ] = $field['std'];
					}
				}
			}

			return $output;
		}

		$meta = get_user_meta( $user->ID );

		foreach ( (array) $metabox_std as $field_id => $field ) {

			if ( isset( $meta[ $field_id ] ) ) {

				$output[ $field_id ] = maybe_unserialize( $meta[ $field_id ][0] );

			} else {

				if ( isset( $field[ $std_id ] ) ) {
					$output[ $field_id ] = $field[ $std_id ];
				} elseif ( isset( $field['std'] ) ) {
					$output[ $field_id ] = $field['std'];
				}
			}
		}

		return $output;
	}


	/**
	 * Deprecated: Use bf_get_user_meta
	 *
	 * Used for finding user meta field value.
	 *
	 * @since   1.4
	 *
	 * @param $field_key    string              User field ID
	 * @param $user         string|WP_User      User ID or object
	 *
	 * @return mixed
	 */
	public function get_meta( $field_key, $user ) {

		return bf_get_user_meta( $field_key, $user );
	}


	/**
	 * Callback: Used for creating meta boxes
	 *
	 * Action: show_user_profile
	 * Action: edit_user_profile
	 *
	 * @since   1.4
	 * @access  public
	 *
	 * @param   $user   string|WP_User      User ID or object
	 */
	public function add_meta_boxes( $user ) {

		foreach ( (array) self::$metabox as $metabox_id => $metabox ) {

			$metabox_value = $this->get_full_meta_data( $metabox_id, $user );

			$metabox_config = self::get_metabox_config( $metabox_id );

			if ( empty( $metabox_config['title'] ) ) {
				$metabox_config['title'] = __( 'Better User Options', 'better-studio' );
			}

			$front_end = new BF_User_Metabox_Front_End_Generator( $metabox_config, $metabox_id, $metabox_value );

			$front_end->callback();
		}

	} // add


	/**
	 * Updates user meta in safely
	 *
	 * @param string|WP_User $user  User ID or object
	 * @param string         $key   User meta key name
	 * @param string         $value User meta value
	 *
	 * @static
	 * @since   1.4
	 * @return  bool
	 */
	public static function add_meta( $user, $key, $value ) {

		if ( ! is_object( $user ) ) {
			$user = get_user_by( 'id', $user );
		}

		$old_value = get_user_meta( $user->ID, $key, true );

		if ( $old_value === false ) {
			return add_user_meta( $user->ID, $key, $value );
		} else {
			if ( $old_value === $value ) {
				return true;
			} else {
				delete_user_meta( $user->ID, $key );

				return add_user_meta( $user->ID, $key, $value );
			}
		}

	}


	/**
	 * Callback: Save user meta box values
	 *
	 * Action: edit_user_profile_update
	 * Action: personal_options_update
	 *
	 * @param int $user_id
	 *
	 * @static
	 * @return  mixed
	 * @since   1.4
	 */
	public function save( $user_id ) {

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		// Iterate all meta boxes
		foreach ( (array) self::$metabox as $metabox_id => $metabox ) {

			if ( isset( $metabox['panel-id'] ) ) {
				$std_id = Better_Framework::options()->get_panel_std_id( $metabox['panel-id'] );
			} else {
				$std_id = 'std';
			}

			$metabox_std    = self::get_metabox_std( $metabox_id );
			$metabox_fields = self::get_metabox_std( $metabox_id );

			// Iterate all fields
			foreach ( (array) $metabox_std as $field_id => $field ) {

				if ( ! isset( $_POST[ $field_id ] ) ) {
					continue;
				}

				$value = \BetterFrameworkPackage\Component\Control\filter_control_value( $metabox_fields[ $field_id ]['type'] ?? '', $_POST[ $field_id ], $metabox_fields[ $field_id ] ?? null );

				if ( ! isset( $value ) ) {

					continue;
				}

				// Save value if save-std is true or not defined
				if ( ! isset( $field['save-std'] ) || $field['save-std'] == true ) {

					self::add_meta( $user_id, $field_id, $value );

				} // Don't Save Default Value
				elseif ( isset( $field['save-std'] ) ) {

					// If style std defined then save it
					if ( isset( $field[ $std_id ] ) ) {

						if ( $field[ $std_id ] != $value ) {
							self::add_meta( $user_id, $field_id, $value );
						} else {
							delete_user_meta( $user_id, $field_id );
						}
					} // If style std defined then save it
					elseif ( isset( $field['std'] ) ) {

						if ( $field['std'] != $value ) {
							self::add_meta( $user_id, $field_id, $value );
						} else {
							delete_user_meta( $user_id, $field_id );
						}
					}
				} // Delete Custom field
				else {
					delete_user_meta( $user_id, $field_id );
				}
			}
		}

	} // save


	/**
	 *
	 *
	 * @param string     $tab_id
	 * @param string     $metabox_id
	 * @param int|string $user_id
	 */
	public function ajax_tab( $tab_id, $metabox_id, $user_id ) {

		$user           = get_user_to_edit( $user_id );
		$fields         = BF_User_Metabox_Core::get_metabox_fields( $metabox_id );
		$metabox_value  = [];
		$metabox_config = self::get_metabox_config( $metabox_id );
		$use_generator  = true;

		if ( empty( $fields[ $tab_id ]['ajax-section-handler'] ) ) {

			$metabox_value = $this->get_full_meta_data( $metabox_id, $user );

			// Modify fields array
			foreach ( $fields as $idx => $field ) {

				// Backward compatibility
				if ( isset( $field['ajax-tab-field'] ) ) {
					$field['ajax-section-field'] = $field['ajax-tab-field'];
				}

				if ( empty( $field['ajax-section-field'] ) || $field['ajax-section-field'] !== $tab_id ) {
					unset( $fields[ $idx ] );
				}
			}
		} else {

			$parent_field = $fields[ $tab_id ];

			$args = $parent_field['ajax-section-handler-args'] ?? [];
			$args = array_merge( $args, compact( 'metabox_id', 'tab_id' ) );

			if (
				! isset( $parent_field['ajax-section-handler-type'] ) ||
				$parent_field['ajax-section-handler-type'] === 'field-generator'
			) {

				$fields = call_user_func( $parent_field['ajax-section-handler'], $args );

				foreach ( $fields as $key => $field ) {

					$metabox_value[ $field['id'] ] = bf_get_user_meta( $field['id'], $user_id );
				}
			} else {

				$use_generator = false;
				$out           = call_user_func( $parent_field['ajax-section-handler'], $args );

			}
		}

		if ( $use_generator ) {

			$front_end = new BF_User_Metabox_Front_End_Generator( $metabox_config, $metabox_id, $metabox_value );
			$front_end->set_fields( $fields );
			BF_User_Metabox_Core::$fields[ $metabox_id ] = $fields;

			ob_start();
			// print output
			echo $front_end->callback( true );  // escaped before
			$out = ob_get_clean();
		}

		wp_send_json(
			[
				'out'    => $out,
				'tab_id' => $tab_id,
			]
		);
	}
}
