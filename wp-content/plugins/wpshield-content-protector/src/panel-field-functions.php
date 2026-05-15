<?php
//phpcs:disable

/**
 * Prepare alert message popup fields.
 *
 * @param array  $fields
 * @param string $prefix
 *
 * @since 1.0.0
 * @return array
 */
function wpshield_cp_alert_popup_options( array $fields, string $prefix ) {

	$fields[ $prefix . '-alert-message-gr' ] = array(
		'name'         => __( 'Alert Message', 'wpshield-content-protector' ),
		'id'           => $prefix . '-alert-message-gr',
		'type'         => 'group',
		'icon'         => 'bsfi-warning-2',
		'state'        => 'open',
		'show_on_type' => array(
			'disable',
		),
		'show_on'      => array(
			array(
				$prefix . '=enable',
			)
		),
	);
	$fields[ $prefix . '/alert-popup' ]      = array(
		'name'          => __( 'Show Alert Popup Message?', 'wpshield-content-protector' ),
		'desc'          => __( 'Inform users when they perform an action that is disabled.', 'wpshield-content-protector' ),
		'id'            => $prefix . '/alert-popup',
		'section_class' => 'bf-input-max-width-50',
		'type'          => 'advance_select',
		'options'       => array(
			'enable'  => __( 'Yes', 'wpshield-content-protector' ),
			'disable' => array(
				'label' => __( 'No', 'wpshield-content-protector' ),
				'color' => '#4f6d84',
			),
		),
	);

	$plugin_setup = \WPShield\Plugin\ContentProtector\ContentProtectorSetup::instance();

	$fields[ $prefix . '/alert-popup/template' ] = array(
		'name'            => __( 'Alert Message Template', 'wpshield-content-protector' ),
		'desc'            => __( 'Specify the appearance of the error message for disabled actions', 'wpshield-content-protector' ),
		'id'              => $prefix . '/alert-popup/template',
		'type'            => 'select_popup',
		'options'         => array(
			'template-1' => array(
				'img'   => WPSHIELD_CP_URL . 'assets/images/popup-template-1.png?v=' . $plugin_setup->version(),
				'label' => __( 'Template 1', 'wpshield-content-protector' ),
				'views' => false,
				'info'  => array(
					'cat' => array(
						__( 'Big', 'wpshield-content-protector' ),
					),
				),
			),
		),
		'texts'           => array(
			'modal_title'   => __( 'Choose Alert Message Template', 'wpshield-content-protector' ),
			'box_pre_title' => __( 'Alert Template', 'wpshield-content-protector' ),
			'box_button'    => __( 'Change Template', 'wpshield-content-protector' ),
		),
		'confirm_changes' => true,
		'show_on_type'    => array(
			'disable'
		),
		'show_on'         => array(
			array( $prefix . '/alert-popup=enable' ),
		),
	);

	$fields[ $prefix . '/alert-popup-wrap1' ] = array(
		'name'         => __( 'Alert Message Customization', 'wpshield-content-protector' ),
		'desc'         => __( 'The following fields can be used to customize the alert message\'s design and appearance', 'wpshield-content-protector' ),
		'id'           => $prefix . '/alert-popup-wrap1',
		'type'         => 'multiple_controls',
		"controls"     => array(
			$prefix . '/alert-popup/title' => array(
				'name' => __( 'Heading Text', 'wpshield-content-protector' ),
				'id'   => $prefix . '/alert-popup/title',
				'type' => 'text',
			),
			$prefix . '/alert-popup/text'  => array(
				'name' => __( 'Message Text', 'wpshield-content-protector' ),
				'id'   => $prefix . '/alert-popup/text',
				'type' => 'textarea',
			),
			$prefix . '/alert-popup/color' => array(
				'name' => __( 'Alert Color', 'wpshield-content-protector' ),
				'id'   => $prefix . '/alert-popup/color',
				'type' => 'color',
				'std'  => $default_values['color'] ?? '#DC1F1F'
			),
			$prefix . '/alert-popup/icon'  => array(
				'name' => __( 'Alert Icon', 'wpshield-content-protector' ),
				'id'   => $prefix . '/alert-popup/icon',
				'type' => 'icon_select',
				'std'  => $default_values['icon'] ?? bf_get_icon_tag( 'bsfi-warning-1', 'cp-icon' ),
			),
		),
		'show_on_type' => array(
			'disable'
		),
		'show_on'      => array(
			array( $prefix . '/alert-popup=enable' ),
		),
		'pro_feature'  => [
			'activate' => true,
			'modal_id' => 'content-protector',
		],
	);

	return $fields;
}


function wpshield_cp_alert_popup_std( $fields, $prefix, array $default_values = [] ) {

	$fields[ $prefix . '/alert-popup' ]          = array(
		'std' => 'enable',
	);
	$fields[ $prefix . '/alert-popup/template' ] = array(
		'std' => 'template-1',
	);
	$fields[ $prefix . '/alert-popup/title' ]    = array(
		'std'          => $default_values['heading'] ?? __( 'Content Protected!', 'wpshield-content-protector' ),
		'save_default' => false,
	);
	$fields[ $prefix . '/alert-popup/text' ]     = array(
		'std'          => $default_values['message'] ?? __( 'The content of this website cannot be copied!', 'wpshield-content-protector' ),
		'save_default' => false,
	);
	$fields[ $prefix . '/alert-popup/color' ]    = array(
		'std' => '',
	);
	$fields[ $prefix . '/alert-popup/icon' ]     = array(
		'std' => '',
	);

	return $fields;
}

/**
 * Prepare audio alert extension fields.
 *
 * @param array  $fields
 * @param string $prefix
 *
 * @return mixed
 */
function wpshield_cp_alert_audio_options( array $fields, string $prefix ) {

	$fields[ $prefix . '_audio_alert_g' ]      = array(
		'name'         => __( 'Audio Warning Alert', 'wpshield-content-protector' ),
		'id'           => $prefix . '_audio_alert_g',
		'type'         => 'group',
		'icon'         => 'bsfi-bell',
		'state'        => 'open',
		'show_on_type' => array(
			'disable',
		),
		'show_on'      => array(
			array(
				$prefix . '=enable',
			)
		),
	);
	$fields[ $prefix . '/audio-alert' ]        = array(
		'name'          => __( 'Play Audio Alert', 'wpshield-content-protector' ),
		'desc'          => __( 'Audio alerts serve as a tool to inform your website visitors of their suspicious activities. It enhances the user experience on your website.', 'wpshield-content-protector' ),
		'id'            => $prefix . '/audio-alert',
		'section_class' => 'bf-input-max-width-50',
		'type'          => 'advance_select',
		'options'       => array(
			'enable'  => __( 'Yes', 'wpshield-content-protector' ),
			'disable' => array(
				'label' => __( 'No', 'wpshield-content-protector' ),
				'color' => '#4f6d84',
			),
		),
		'show_on_type'  => array(
			'disable',
		),
		'show_on'       => array(
			array(
				$prefix . '=enable',
			)
		),
		'pro_feature'   => [
			'activate' => true,
			'modal_id' => 'content-protector',
		],
	);
	$fields[ $prefix . '/audio-alert/sound' ]  = array(
		'name'         => __( 'Alert Sound', 'wpshield-content-protector' ),
		'desc'         => __( 'Choosing an alarm sound to play every time a user violates this security protocol.', 'wpshield-content-protector' ),
		'id'           => $prefix . '/audio-alert/sound',
		'type'         => 'advance_select',
		'vertical'     => true,
		'options'      => array(
			'beep-warning.mp3'      => __( 'Beep', 'wpshield-content-protector' ),
			'bell-notification.wav' => __( 'Bell', 'wpshield-content-protector' ),
			'fail.wav'              => __( 'Fail', 'wpshield-content-protector' ),
			'stop.mp3'              => __( 'Stop', 'wpshield-content-protector' ),
			'boing.mp3'             => __( 'Boing', 'wpshield-content-protector' ),
			'short-beep.mp3'        => __( 'Short beep', 'wpshield-content-protector' ),
			'short-siren.mp3'       => __( 'Short siren', 'wpshield-content-protector' ),
			'double-beep.wav'       => __( 'Double beep', 'wpshield-content-protector' ),
		),
		'show_on_type' => array(
			'disable'
		),
		'show_on'      => array(
			array( $prefix . '/audio-alert=enable' )
		),
		'pro_feature'  => [
			'activate' => true,
			'modal_id' => 'content-protector',
		],
	);
	$fields[ $prefix . '/audio-alert/volume' ] = array(
		'name'         => __( 'Alert Volume', 'wpshield-content-protector' ),
		'desc'         => __( 'You can change the volume of audio alert by using this feature.', 'wpshield-content-protector' ),
		'type'         => 'slider',
		'id'           => $prefix . '/audio-alert/volume',
		'show_on_type' => array(
			'disable'
		),
		'show_on'      => array(
			array( $prefix . '/audio-alert=enable' )
		),
		'pro_feature'  => [
			'activate' => true,
			'modal_id' => 'content-protector',
		],
	);

	return $fields;
}


function wpshield_cp_alert_audio_std( $fields, $prefix ) {

	$fields[ $prefix . '/audio-alert' ]        = array(
		'std' => 'disable',
	);
	$fields[ $prefix . '/audio-alert/sound' ]  = array(
		'std' => 'beep-warning.mp3',
	);
	$fields[ $prefix . '/audio-alert/volume' ] = array(
		'std' => 50,
	);

	return $fields;
}

/**
 * Prepare filter and conditions extension options.
 *
 * @param array  $fields
 * @param string $prefix
 * @param array  $args
 *
 * @return array
 */
function wpshield_cp_filter_options( array $fields, string $prefix, array $args = [] ): array {

	$fields[ $prefix . '-filter-group' ] = array(
		'name'         => __( 'Protector Activation Filter', 'wpshield-content-protector' ),
		'desc'         => __( 'The protector can be activated or deactivated based on advanced settings such as special users, specific pages, or specific URLs on your website.', 'wpshield-content-protector' ),
		'id'           => $prefix . '-filter-group',
		'icon'         => 'bsfi-filter',
		'type'         => 'group',
		'state'        => 'open',
		'show_on_type' => array(
			'disable',
		),
		'show_on'      => array(
			array(
				$prefix . '=enable',
			)
		),
	);
	$fields[ $prefix . '/filters' ]      = cp_remove_excluded_options(
		array(
			'name'          => __( '', 'wpshield-content-protector' ),
			'id'            => $prefix . '/filters',
			'type'          => 'repeater',
			'add_label'     => bf_get_icon_tag( 'bsfi-plus' ) . __( 'Add Another Filter', 'wpshield-content-protector' ),
			'delete_label'  => __( 'Delete', 'wpshield-content-protector' ),
			'item_title'    => __( 'Filter Title', 'wpshield-content-protector' ),
			'section_class' => 'full-with-both',
			'std'           => array(
				array(
					'type'       => 'include',
					'in'         => 'global',
					'user-roles' => '',
					'post-types' => '',
					'posts'      => '',
					'urls'       => '',
				),
			),
			'default'       => array(
				array(
					'type'       => 'include',
					'in'         => 'global',
					'user-roles' => '',
					'post-types' => '',
					'posts'      => '',
					'urls'       => '',
				),
			),
			'show_on_type'  => array(
				'disable',
			),
			'show_on'       => array(
				array(
					$prefix . '=enable',
				)
			),
			'options'       => array(
				'type'       => array(
					'name'          => __( 'Filter Type', 'wpshield-content-protector' ),
					'desc'          => __( 'Choose the filter type for activation or deactivation in the following conditions.', 'wpshield-content-protector' ),
					'id'            => 'type',
					'type'          => 'advance_select',
					'options'       => [
						'include' => [
							'label' => __( 'Include', 'wpshield-content-protector' ),
							'color' => '#0aa602',
							'icon'  => 'bsfi-plus',
						],
						'exclude' => [
							'label' => __( 'Exclude', 'wpshield-content-protector' ),
							'color' => '#a60e0e',
							'icon'  => 'bsfi-minus',
						],
					],
					'repeater_item' => true
				),
				'in'         => array(
					'name'          => __( 'Filter In...', 'wpshield-content-protector' ),
					'desc'          => __( 'Choose the filter type. ', 'wpshield-content-protector' ),
					'id'            => 'in',
					'type'          => 'advance_select',
					'vertical'      => true,
					'options'       => array(
						'global'     => [
							'label' => __( 'Everywhere', 'wpshield-content-protector' ),
						],
						'user-role'  => [
							'label'       => __( 'User Roles', 'wpshield-content-protector' ),
							'pro_feature' => [
								'activate'   => true,
								'selectable' => true,
								'modal_id'   => 'content-protector',
								'template'   => [
									'title' => __( '<span>“ <b>User Roles Filter</b> ”</span> Is A PRO Feature', 'wpshield-content-protector' ),
								],
							],
						],
						'user'       => [
							'label'       => __( 'Users', 'wpshield-content-protector' ),
							'pro_feature' => [
								'activate'   => true,
								'selectable' => true,
								'modal_id'   => 'content-protector',
								'template'   => [
									'title' => __( '<span>“ <b>Selected Users Filter</b> ”</span> Is A PRO Feature', 'wpshield-content-protector' ),
								],
							],
						],
						'taxonomies' => [
							'label'       => __( 'Categories & Custom Taxonomies', 'wpshield-content-protector' ),
							'pro_feature' => [
								'activate'   => true,
								'selectable' => true,
								'modal_id'   => 'content-protector',
								'template'   => [
									'title' => __( '<span>“ <b>Categories and Custom Taxonomies Filter</b> ”</span> Is A PRO Feature', 'wpshield-content-protector' ),
								],
							],
						],
						'post'       => [
							'label'       => __( 'Posts & Custom Post Types', 'wpshield-content-protector' ),
							'pro_feature' => [
								'activate'   => true,
								'selectable' => true,
								'modal_id'   => 'content-protector',
								'template'   => [
									'title' => __( '<span>“ <b>Posts & Custom Post Types Filter</b> ”</span> Is A PRO Feature', 'wpshield-content-protector' ),
								],
							],
						],
						'url'        => [
							'label'       => __( 'Custom URLs', 'wpshield-content-protector' ),
							'pro_feature' => [
								'activate'   => true,
								'selectable' => true,
								'modal_id'   => 'content-protector',
								'template'   => [
									'title' => __( '<span>“ <b>Custom URLs Filter</b> ”</span> Is A PRO Feature', 'wpshield-content-protector' ),
								],
							],
						],
						'css-class'  => [
							'label'       => __( 'Custom CSS Classes', 'wpshield-content-protector' ),
							'pro_feature' => [
								'activate'   => true,
								'selectable' => true,
								'modal_id'   => 'content-protector',
								'template'   => [
									'title' => __( '<span>“ <b>Custom CSS Classes Filter</b> ”</span> Is A PRO Feature', 'wpshield-content-protector' ),
								],
							],
						],
					),
					'repeater_item' => true
				),
				'user-role'  => array(
					'name'        => __( 'Select User Roles', 'wpshield-content-protector' ),
					'desc'        => __( 'Choose user roles that you want to enable/disable this protector only for them.', 'wpshield-content-protector' ),
					'id'          => 'user-role',
					'type'        => 'checkbox',
					'options'     => wpshield_cp_get_roles(),
					'show_on'     => array(
						array( 'in=user-role' )
					),
					'pro_feature' => [
						'activate' => true,
						'modal_id' => 'content-protector',
					]
				),
				'user'       => array(
					'name'        => __( 'Users', 'wpshield-content-protector' ),
					'desc'        => __( 'Choose users that you want to enable/disable this protector only for them.', 'wpshield-content-protector' ),
					'id'          => 'user',
					'type'        => 'ajax_select',
					"callback"    => 'BF_Ajax_Select_Callbacks::users_callback',
					"get_name"    => 'BF_Ajax_Select_Callbacks::user_name',
					'placeholder' => __( "Search and Select Users...", 'wpshield-content-protector' ),
					'show_on'     => array(
						array( 'in=user' )
					),
					'pro_feature' => [
						'activate' => true,
						'modal_id' => 'content-protector',
					],
				),
				'categories' => array(
					'name'        => __( 'Specific categories', 'wpshield-content-protector' ),
					'desc'        => __( 'Choose categories that you want to enable/disable this protector only in them.', 'wpshield-content-protector' ),
					'id'          => 'category',
					'type'        => 'ajax_select',
					"callback"    => 'BF_Ajax_Select_Callbacks::cats_callback',
					"get_name"    => 'BF_Ajax_Select_Callbacks::cat_name',
					'placeholder' => __( "Search and Select Categories...", 'wpshield-content-protector' ),
					'show_on'     => array(
						array( 'in=taxonomies' )
					),
					'pro_feature' => [
						'activate' => true,
						'modal_id' => 'content-protector',
					],
				),
				'taxonomies' => array(
					'name'        => __( 'Taxonomies', 'wpshield-content-protector' ),
					'desc'        => __( 'Choose taxonomies that you want to enable/disable this protector only in them.', 'wpshield-content-protector' ),
					'id'          => 'taxonomies',
					'type'        => 'checkbox',
					'options'     => cp_get_taxonomies(),
					'show_on'     => array(
						array( 'in=taxonomies' )
					),
					'pro_feature' => [
						'activate' => true,
						'modal_id' => 'content-protector',
					],
				),
				'post'       => array(
					'name'        => __( 'Specific Posts', 'wpshield-content-protector' ),
					'desc'        => __( 'Choose posts that you want to enable/disable this protector only in them.', 'wpshield-content-protector' ),
					'id'          => 'post',
					'type'        => 'ajax_select',
					"callback"    => 'BF_Ajax_Select_Callbacks::posts_callback',
					"get_name"    => 'BF_Ajax_Select_Callbacks::posts_name',
					'placeholder' => __( "Search and Select Posts...", 'wpshield-content-protector' ),
					'show_on'     => array(
						array( 'in=post' )
					),
					'pro_feature' => [
						'activate' => true,
						'modal_id' => 'content-protector',
					],
				),
				'post-type'  => array(
					'name'        => __( 'Post Types', 'wpshield-content-protector' ),
					'desc'        => __( 'Choose post types that you want to enable/disable this protector only in them.', 'wpshield-content-protector' ),
					'id'          => 'post-type',
					'type'        => 'checkbox',
					'options'     => wpshield_cp_get_post_types(),
					'show_on'     => array(
						array( 'in=post' )
					),
					'pro_feature' => [
						'activate' => true,
						'modal_id' => 'content-protector',
					],
				),
				'url'        => array(
					'name'          => __( 'Custom URLs', 'wpshield-content-protector' ),
					'desc'          => __( 'Enter URLs that you want to enable/disable this protector only in them. Multiple items can be entered in new lines.', 'wpshield-content-protector' ),
					'id'            => 'url',
					'section_class' => 'full-width-controls',
					'type'          => 'textarea',
					'show_on'       => array(
						array( 'in=url' )
					),
					'pro_feature'   => [
						'activate' => true,
						'modal_id' => 'content-protector',
					],
				),
				'css-class'  => array(
					'name'          => __( 'Custom Elements (CSS Classes)', 'wpshield-content-protector' ),
					'desc'          => __( 'Enter custom CSS classes and selectors that you want to enable/disable this protector only in them. Multiple items can be entered in new lines.', 'wpshield-content-protector' ),
					'id'            => 'css-class',
					'section_class' => 'full-width-controls',
					'type'          => 'textarea',
					'show_on'       => array(
						array( 'in=css-class' )
					),
					'pro_feature'   => [
						'activate' => true,
						'modal_id' => 'content-protector',
					],
				),
			),
		),
		$args['excluded-filter-types'] ?? []
	);

	return $fields;
}


function wpshield_cp_filter_std( $fields, $prefix ) {

	$fields[ $prefix . '/filters' ] = array(
		'std'     => array(
			array(
				'type'       => 'include',
				'in'         => 'global',
				'user-roles' => '',
				'post-types' => '',
				'posts'      => '',
				'urls'       => '',
			),
		),
		'default' => array(
			array(
				'type'       => 'include',
				'in'         => 'global',
				'user-roles' => '',
				'post-types' => '',
				'posts'      => '',
				'urls'       => '',
			),
		),
	);

	return $fields;
}

if ( ! function_exists( 'cp_list_pages' ) ) {

	/**
	 * List available pages.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function cp_list_pages(): array {

		$results = array(
			0 => __( 'None', 'content-protector-pack' )
		);

		if ( ! $pages = get_pages( 'post_status=publish,private' ) ) {
			return $results;
		}

		foreach ( $pages as $page ) {

			$results[ get_the_permalink( $page->ID ) ] = empty( $page->post_title ) ? wp_sprintf( '(page: %d)', $page->ID ) : $page->post_title;
		}

		return $results;
	}
}

if ( ! function_exists( 'cp_get_taxonomies' ) ) {

	/**
	 * Get list of all public taxonomies!
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function cp_get_taxonomies(): array {

		return array_map( 'cp_get_taxonomy', get_taxonomies( [ 'public' => true ] ) );
	}
}

if ( ! function_exists( 'cp_get_taxonomy' ) ) {

	/**
	 * Get list of all public taxonomies!
	 *
	 * @param string $slug
	 *
	 * @since 1.0.0
	 * @return string
	 */
	function cp_get_taxonomy( string $slug ): string {

		$taxonomy = get_taxonomy( $slug );

		return wp_sprintf( '%s (%s)', $taxonomy->label, $taxonomy->name );
	}
}

if ( ! function_exists( 'cp_remove_excluded_options' ) ) {

	/**
	 * Retrieve excludes option items.
	 *
	 * @param array $field
	 * @param array $excludes
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function cp_remove_excluded_options( array $field, array $excludes = [] ): array {

		foreach ( $excludes as $type ) {

			if ( ! isset( $field['options'][ $type ], $field['options']['in']['options'][ $type ] ) ) {

				continue;
			}

			unset( $field['options']['in']['options'][ $type ], $field['options'][ $type ] );
		}

		return $field;
	}
}