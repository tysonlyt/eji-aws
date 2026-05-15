<?php

/**
 * Right Click Protector
 */

$fields[] = array(
	'name' => __( 'Right Click Protector', 'wpshield-content-protector' ),
	'id'   => 'right-click-tab',
	'type' => 'tab',
	'icon' => 'cp-right-click',
);

$fields['right-click']                = array(
	'name'          => __( 'Right Click Menu Protector', 'wpshield-content-protector' ),
	'desc'          => __( 'The right-click context menu should be limited or disable in order to prevent users from viewing the source, saving images, copying text, printing page and other functions related to right-clicking.', 'wpshield-content-protector' ),
	'id'            => 'right-click',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-disable-right-click.svg'
	],
	'options'       => [
		'enable'  => __( 'ON', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	],
);
$fields['right-click/type']           = array(
	'name'         => __( 'Protection Protocol', 'wpshield-content-protector' ),
	'desc'         => __( 'Consider selecting a protocol that is secure, user-friendly, and offers the best protection.', 'wpshield-content-protector' ),
	'id'           => 'right-click/type',
	'type'         => 'advance_select',
	'vertical'     => true,
	'options'      => [
		'disable'  => [
			'label' => __( 'Disable Right Click Context Menu Completely', 'wpshield-content-protector' ),
		],
		'simulate' => [
			'label'       => __( 'Right Click Menu Limiter', 'wpshield-content-protector' ),
			'badge'       => [
				'label' => __( '100% Secure - Best UX', 'wpshield-content-protector' ),
			],
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
	],
	'show_on_type' => array(
		'disable',
	),
	'show_on'      => array(
		array(
			'right-click=enable',
		),
	),
);
$fields['right-click/internal-links'] = array(
	'name'          => __( 'Enable Right Click On Site Internal Links?', 'wpshield-content-protector' ),
	'desc'          => __( 'Users can right-click on internal links on your site to open them in a new tab or window.', 'wpshield-content-protector' ),
	'id'            => 'right-click/internal-links',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => [
		'enable'  => [
			'label' => __( 'Yes', 'wpshield-content-protector' ),
		],
		'disable' => [
			'label' => __( 'No', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	],
	'show_on_type'  => array(
		'disable',
	),
	'show_on'       => array(
		array(
			'right-click=enable',
			'right-click/type=disable',
		),
	),
	'pro_feature'   => [
		'activate' => true,
		'modal_id' => 'content-protector',
	]
);

$fields['right-click/input-fields'] = array(
	'name'          => __( 'Enable Right Click On Input Fields?', 'wpshield-content-protector' ),
	'desc'          => __( 'Users can right-click to copy and paste text into forms such as contact forms and comment forms.', 'wpshield-content-protector' ),
	'id'            => 'right-click/input-fields',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => [
		'enable'  => [
			'label' => __( 'Yes', 'wpshield-content-protector' ),
		],
		'disable' => [
			'label' => __( 'No', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	],
	'show_on_type'  => array(
		'disable',
	),
	'show_on'       => array(
		array(
			'right-click=enable',
			'right-click/type=disable',
		),
	),
	'pro_feature'   => [
		'activate' => true,
		'modal_id' => 'content-protector',
	]
);

$fields = wpshield_cp_alert_popup_options( $fields, 'right-click' );

$fields = wpshield_cp_alert_audio_options( $fields, 'right-click' );

$fields = wpshield_cp_filter_options( $fields, 'right-click' );


/**
 * Text Copy Protector
 */

$fields[]                                          = array(
	'name' => __( 'Text Copy Protector', 'wpshield-content-protector' ),
	'id'   => 'text-copy-tab',
	'type' => 'tab',
	'icon' => 'cp-text-copy',
);
$fields['text-copy']                               = array(
	'name'          => __( 'Text Copy Protector', 'wpshield-content-protector' ),
	'desc'          => __( 'This protection prevent users from selecting and copying the contents of your web page. Additionally, you can enable them to select, but append custom copyright messages at the end of copied content.', 'wpshield-content-protector' ),
	'id'            => 'text-copy',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-text-copy.svg'
	],
	'options'       => array(
		'enable'  => __( 'ON', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
);
$fields['text-copy/type']                          = array(
	'name'         => __( 'Protection Protocol', 'wpshield-content-protector' ),
	'desc'         => __( 'Consider selecting a protocol that is secure, user-friendly, and offers the best protection.', 'wpshield-content-protector' ),
	'id'           => 'text-copy/type',
	'type'         => 'advance_select',
	'vertical'     => true,
	'options'      => [
		'disable' => [
			'label' => __( 'Disable Text Selection & Copy Completely', 'wpshield-content-protector' ),
			'badge' => [
				'label' => __( 'BAD UX', 'wpshield-content-protector' ),
				'color' => '#2a49619e',
			],
		],
		'select'  => [
			'label'       => __( 'Allow Text Selection, Disable Copying', 'wpshield-content-protector' ),
			'badge'       => [
				'label' => __( 'Best UX', 'wpshield-content-protector' ),
			],
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
		'append'  => [
			'label'       => __( 'Allow Copy, But Append Copyright Notice', 'wpshield-content-protector' ),
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
	],
	'show_on_type' => array(
		'disable'
	),
	'show_on'      => array(
		array( 'text-copy=enable' )
	),
);
$fields['text-copy/exclude-inputs']                = array(
	'name'          => __( 'Enable Copy and Paste Capabilities on Form Inputs', 'wpshield-content-protector' ),
	'desc'          => __( 'Users should be able to copy and paste information into input boxes and textareas. This is particularly helpful for comment forms and contact forms.', 'wpshield-content-protector' ),
	'id'            => 'text-copy/exclude-inputs',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => array(
		'enable'  => __( 'Enable', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'Disable', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
	'show_on_type'  => array(
		'disable'
	),
	'show_on'       => array(
		array( 'text-copy=enable' )
	),
);
$fields['gr-text-copy-appender']                   = array(
	'name'         => __( 'Copyright Notice Appender', 'wpshield-content-protector' ),
	'id'           => 'gr-text-copy-appender',
	'icon'         => 'bsfi-cc0',
	'type'         => 'group',
	'state'        => 'open',
	'show_on_type' => array(
		'disable'
	),
	'show_on'      => array(
		array( 'text-copy=enable' )
	),
);
$fields['text-copy/copy-appender/text']            = array(
	'name'         => __( 'Copyright Notice for Appending', 'wpshield-content-protector' ),
	'desc'         => __( 'A notice, referencing your copyright, will be appended to the copied text. You may also create a hyperlink to reference your ownership of the content.<br><br>
 <b>You can use following placeholders:</b> <br>
<code>%TEXT%</code> for copied text<br>
<code>%SITELINK%</code> for site link<br>
<code>%SITETITLE%</code> for site title<br>
<code>%POSTLINK%</code> for post link<br>
<code>%POSTTITLE%</code> for post title<br>
', 'wpshield-content-protector' ),
	'id'           => 'text-copy/copy-appender/text',
	'type'         => 'textarea',
	'show_on_type' => array(
		'disable',
	),
	'show_on'      => array(
		array(
			'text-copy=enable',
			'text-copy/type=append',
		)
	),
	'pro_feature'  => [
		'activate' => true,
		'modal_id' => 'content-protector',
	]
);
$fields['text-copy/copy-appender/max-text-length'] = array(
	'name'         => __( 'Maximum Length of Copied Text', 'wpshield-content-protector' ),
	'desc'         => __( 'Specify the maximum length of characters that may be copied. Texts that are longer than this will be trimmed.', 'wpshield-content-protector' ),
	'id'           => 'text-copy/copy-appender/max-text-length',
	'suffix'       => __( 'Characters', 'wpshield-content-protector' ),
	'type'         => 'text',
	'show_on_type' => array(
		'disable',
	),
	'show_on'      => array(
		array(
			'text-copy=enable',
			'text-copy/type=append',
		)
	),
	'pro_feature'  => [
		'activate' => true,
		'modal_id' => 'content-protector',
	]
);

$fields = wpshield_cp_alert_popup_options( $fields, 'text-copy' );

$fields = wpshield_cp_alert_audio_options( $fields, 'text-copy' );

$fields = wpshield_cp_filter_options( $fields, 'text-copy' );


/**
 * Images Protector
 */

$fields[]                             = array(
	'name'       => __( 'Image Protector', 'wpshield-content-protector' ),
	'id'         => 'images-tab',
	'type'       => 'tab',
	'icon'       => 'cp-image',
	'margin-top' => '20',
);
$fields['images']                     = array(
	'name'          => __( 'Images Theft Protector', 'wpshield-content-protector' ),
	'desc'          => __( 'It helps to prevent users from dragging or downloading your site\'s images. Your photos and images will be protected on the web, and you will be able to maintain their exclusivity as a result.', 'wpshield-content-protector' ),
	'id'            => 'images',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-image-protector.svg'
	],
	'options'       => array(
		'enable'  => __( 'ON', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
);
$fields['images/disable-right-click'] = array(
	'name'          => __( 'Disable Right Click On Images', 'wpshield-content-protector' ),
	'desc'          => __( 'To prevent people from downloading images from your site, disable the right-click menu on images, or use the right-click simulator to limit the choice of downloading options from right-click menus.', 'wpshield-content-protector' ),
	'id'            => 'images/disable-right-click',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => array(
		'enable'  => __( 'Enable', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'Disable', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
	'show_on_type'  => array(
		'disable',
	),
	'show_on'       => array(
		array(
			'images=enable',
		)
	),
);
$fields['images/disable-drag']        = array(
	'name'          => __( 'Disable Drag and Drop on Images', 'wpshield-content-protector' ),
	'desc'          => __( 'This feature prevents your visitors from dragging your site images from the browser to download them. It is one of the most useful ways to download site images.', 'wpshield-content-protector' ),
	'id'            => 'images/disable-drag',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => array(
		'enable'  => __( 'Enable', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'Disable', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
	'show_on_type'  => array(
		'disable',
	),
	'show_on'       => array(
		array(
			'images=enable',
		)
	),
);
$fields['images/remove-links']        = array(
	'name'          => __( 'Remove Anchor Link Around Images', 'wpshield-content-protector' ),
	'desc'          => __( 'This feature will ensure that your site images will not be surrounded by links to the full or larger version of the image.', 'wpshield-content-protector' ),
	'id'            => 'images/remove-links',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => array(
		'enable'  => __( 'Enable', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'Disable', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
	'show_on_type'  => array(
		'disable',
	),
	'show_on'       => array(
		array(
			'images=enable',
		)
	),
	'pro_feature'   => [
		'activate' => true,
		'modal_id' => 'content-protector',
	]
);
$fields['images/disable-hotlink']     = array(
	'name'          => __( 'Hotlink Protection for Images', 'wpshield-content-protector' ),
	'desc'          => __( 'The feature will prevent the loading of your images on other websites by using the URL for your site images.', 'wpshield-content-protector' ),
	'id'            => 'images/disable-hotlink',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => array(
		'enable'  => __( 'Enable', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'Disable', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
	'show_on_type'  => array(
		'disable',
	),
	'show_on'       => array(
		array(
			'images=enable',
		)
	),
	'pro_feature'   => [
		'activate' => true,
		'modal_id' => 'content-protector',
	]
);
$fields['images/disable-attachment-pages']     = array(
	'name'          => __( 'Disable Attachment Pages', 'wpshield-content-protector' ),
	'desc'          => __( 'It will disable the attachment pages in WordPress to protect your images.', 'wpshield-content-protector' ),
	'id'            => 'images/disable-attachment-pages',
	'type'          => 'advance_select',
	'vertical'     => true,
	'options'       => array(
		'home' => [
			'label' => __( 'Disable & Redirect to Homepage', 'wpshield-content-protector' ),
		],
		'to-related-post' => [
			'label' => __( 'Disable & Redirect to Related Post or Home URL', 'wpshield-content-protector' ),
		],
		'disable' => [
			'label' => __( 'No', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
	'show_on_type'  => array(
		'disable',
	),
	'show_on'       => array(
		array(
			'images=enable',
		)
	),
);

$fields = wpshield_cp_alert_popup_options( $fields, 'images' );

$fields = wpshield_cp_alert_audio_options( $fields, 'images' );

$fields = wpshield_cp_filter_options( $fields, 'images' );

/**
 * Images Watermark
 */

//$fields[] = array(
//	'name' => __( 'Image Watermark', 'wpshield-content-protector' ),
//	'id'   => 'watermark-tab',
//	'type' => 'tab',
//	'icon' => 'cp-image-watermark',
//);


/**
 * Videos Protector
 */

$fields[]                             = array(
	'name' => __( 'Video Protector', 'wpshield-content-protector' ),
	'id'   => 'video-tab',
	'type' => 'tab',
	'icon' => 'cp-video',
);
$fields['videos']                     = array(
	'name'          => __( 'Video Download Protector', 'wpshield-content-protector' ),
	'desc'          => __( 'Your website can be protected against downloads, copies, and the location of the hosted video\'s URL on the internet by using this method. Your videos will remain safe and unique on the internet as a result.', 'wpshield-content-protector' ),
	'id'            => 'videos',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-videos-protector.svg'
	],
	'options'       => array(
		'enable'  => __( 'ON', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
);
$fields['videos/disable-right-click'] = array(
	'name'          => __( 'Disable Right Click On Videos', 'wpshield-content-protector' ),
	'desc'          => __( 'You can prevent users from downloading your videos by disabling the right-click menu on them, or you can use a right-click simulator to limit the options that your users can choose from the right-click menu.', 'wpshield-content-protector' ),
	'id'            => 'videos/disable-right-click',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => array(
		'enable'  => __( 'Enable', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'Disable', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
	'show_on_type'  => array(
		'disable',
	),
	'show_on'       => array(
		array(
			'videos=enable',
		)
	),
);
$fields['videos/download-button']     = array(
	'name'          => __( 'Remove Videos Download Button?', 'wpshield-content-protector' ),
	'desc'          => __( 'Taking away the download button from the WordPress video player prevents users from finding and downloading your videos.', 'wpshield-content-protector' ),
	'id'            => 'videos/download-button',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => array(
		'enable'  => __( 'Enable', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'Disable', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
	'show_on_type'  => array(
		'disable',
	),
	'show_on'       => array(
		array(
			'videos=enable',
		)
	),
);

$fields['videos/disable-hotlink']     = array(
	'name'          => __( 'Hotlink Protection for Videos', 'wpshield-content-protector' ),
	'desc'          => __( 'The feature will prevent the loading of your videos on other websites by using the URL for your site videos.', 'wpshield-content-protector' ),
	'id'            => 'videos/disable-hotlink',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => array(
		'enable'  => __( 'Enable', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'Disable', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
	'show_on_type'  => array(
		'disable',
	),
	'show_on'       => array(
		array(
			'videos=enable',
		)
	),
	'pro_feature'   => [
		'activate' => true,
		'modal_id' => 'content-protector',
	]
);

$fields = wpshield_cp_alert_popup_options( $fields, 'videos' );

$fields = wpshield_cp_alert_audio_options( $fields, 'videos' );

$fields = wpshield_cp_filter_options( $fields, 'videos' );

/**
 * Audios Protector
 */

$fields[]                             = array(
	'name' => __( 'Audio Protector', 'wpshield-content-protector' ),
	'id'   => 'audio-tab',
	'type' => 'tab',
	'icon' => 'cp-music',
);
$fields['audios']                     = array(
	'name'          => __( 'Audio Download Protector', 'wpshield-content-protector' ),
	'desc'          => __( 'The protected audio files and music will remain safe from downloads, copies, and the location of the files on the Internet thanks to this method.', 'wpshield-content-protector' ),
	'id'            => 'audios',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-musics-protector.svg'
	],
	'options'       => array(
		'enable'  => __( 'ON', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
);
$fields['audios/disable-right-click'] = array(
	'name'          => __( 'Disable Right Click On Audios?', 'wpshield-content-protector' ),
	'desc'          => __( 'In order to prevent users from downloading your audio files, you should disable their right-click menu on WordPress audio player or limit the options your users can select by using the right-click simulator.', 'wpshield-content-protector' ),
	'id'            => 'audios/disable-right-click',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => array(
		'enable'  => __( 'Enable', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'Disable', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
	'show_on_type'  => array(
		'disable',
	),
	'show_on'       => array(
		array(
			'audios=enable',
		)
	),
);
$fields['audios/download-button']     = array(
	'name'          => __( 'Remove Audios Download Button?', 'wpshield-content-protector' ),
	'desc'          => __( 'Removing the download button from the WordPress audio player prevents users from finding your audio files and downloading them.', 'wpshield-content-protector' ),
	'id'            => 'audios/download-button',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => array(
		'enable'  => __( 'Enable', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'Disable', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
	'show_on_type'  => array(
		'disable',
	),
	'show_on'       => array(
		array(
			'audios=enable',
		)
	),
);

$fields['audios/disable-hotlink']     = array(
	'name'          => __( 'Hotlink Protection for Audios', 'wpshield-content-protector' ),
	'desc'          => __( 'The feature will prevent the loading of your audios on other websites by using the URL for your site audios.', 'wpshield-content-protector' ),
	'id'            => 'audios/disable-hotlink',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => array(
		'enable'  => __( 'Enable', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'Disable', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	),
	'show_on_type'  => array(
		'disable',
	),
	'show_on'       => array(
		array(
			'audios=enable',
		)
	),
	'pro_feature'   => [
		'activate' => true,
		'modal_id' => 'content-protector',
	]
);

$fields = wpshield_cp_alert_popup_options( $fields, 'audios' );

$fields = wpshield_cp_alert_audio_options( $fields, 'audios' );

$fields = wpshield_cp_filter_options( $fields, 'audios' );


/**
 * IDM Extension Protector
 */

$fields[]             = array(
	'name' => __( 'IDM Protector', 'wpshield-content-protector' ),
	'id'   => 'idm-extension-tab',
	'type' => 'tab',
	'icon' => 'cp-idm',
);
$fields['idm-extension'] = array(
	'name'          => __( 'IDM Protector', 'wpshield-content-protector' ),
	'desc'          => __( 'Internet Download Manager is an advanced download manager for Windows. With its browser extensions, users can easily download videos and audio files from your site. By enabling this protector, IDM cannot download videos, audio or other files from your site.', 'wpshield-content-protector' ),
	'id'            => 'idm-extension',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-idm-protector.svg'
	],
	'options'       => [
		'enable'  => [
			'label'       => __( 'ON', 'wpshield-content-protector' ),
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
				'template' => [
					'title' => __( '<span>“ <b>IDM Protector</b> ”</span> Is A PRO Feature', 'wpshield-content-protector' ),
				],
			],
		],
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	],
);


$fields = wpshield_cp_alert_popup_options( $fields, 'idm-extension' );

$fields = wpshield_cp_filter_options( $fields, 'idm-extension' );


/**
 * Developer Tools Protector
 */
$fields[]                                = array(
	'name'       => __( 'Developer Tools Protector', 'wpshield-content-protector' ),
	'id'         => 'developer-tools-tab',
	'type'       => 'tab',
	'icon'       => 'cp-developer-tools',
	'margin-top' => '20',
);
$fields['developer-tools']               = array(
	'name'          => __( 'Developer Tools Protector (Inspect Element)', 'wpshield-content-protector' ),
	'desc'          => __( 'It is the most effective way to copy content from websites by using the developer tools of the browser. By enabling this protector, you block access to the browser\'s developer tools on your site.', 'wpshield-content-protector' ),
	'id'            => 'developer-tools',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-developer-tools-protector.svg'
	],
	'options'       => [
		'enable'  => __( 'ON', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	],
);
$fields['developer-tools/type']          = [
	'name'         => __( 'Protection Protocol', 'wpshield-content-protector' ),
	'desc'         => __( 'Consider selecting a protocol that is secure, user-friendly, and offers the best protection.', 'wpshield-content-protector' ),
	'id'           => 'developer-tools/type',
	'type'         => 'advance_select',
	'vertical'     => true,
	'options'      => [
		'hotkeys'  => [
			'label' => __( 'Disable Only HotKeys', 'wpshield-content-protector' ),
			'badge' => [
				'label' => __( 'Not Secure', 'wpshield-content-protector' ),
				'color' => '#1f44617a',
			],
		],
		'blank'    => [
			'label'       => __( 'Clear Page Content After Opening Dev Tools', 'wpshield-content-protector' ),
			'badge'       => [
				'label' => __( '100% Secure', 'wpshield-content-protector' ),
			],
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
		'redirect' => [
			'label'       => __( 'Redirect To a Page After Opening Dev Tools', 'wpshield-content-protector' ),
			'badge'       => [
				'label' => __( '100% Secure', 'wpshield-content-protector' ),
			],
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
	],
	'show_on_type' => [
		'disable'
	],
	'show_on'      => [
		[
			'developer-tools=enable'
		]
	]
];
$fields['developer-tools/redirect/page'] = array(
	'name'             => __( 'Redirect To Page', 'wpshield-content-protector' ),
	'desc'             => __( 'Select a page on your site that you would like to be redirected to if users open the developer tools. When you make a custom page with the appropriate design and content, you can warn users against copying the contents of your site.', 'wpshield-content-protector' ),
	'id'               => 'developer-tools/redirect/page',
	'type'             => 'ajax_select',
	'deferred-options' => 'cp_list_pages',
	'show_on_type'     => array(
		'disable',
	),
	'show_on'          => array(
		array(
			'developer-tools=enable',
			'developer-tools/type=redirect',
		),
	),
	'callback'         => 'BF_Ajax_Select_Callbacks::pages_callback',
	'get_name'         => 'BF_Ajax_Select_Callbacks::page_name',
	'placeholder'      => __( 'Search and Select Pages...', 'wpshield-content-protector' ),
);

$fields = wpshield_cp_alert_popup_options( $fields, 'developer-tools' );

$fields = wpshield_cp_alert_audio_options( $fields, 'developer-tools' );

$fields = wpshield_cp_filter_options( $fields, 'developer-tools',
	[
		'excluded-filter-types' => [ 'css-class' ],
	]
);

/**
 * View Source Protector
 */

$fields[]                            = array(
	'name' => __( 'View Source Protector', 'wpshield-content-protector' ),
	'id'   => 'view-source-tab',
	'type' => 'tab',
	'icon' => 'cp-view-source',
);
$fields['view-source']               = array(
	'name'          => __( 'View Source Code Protector', 'wpshield-content-protector' ),
	'desc'          => __( 'The source code of websites can be easily copied by opening it in the browser. By using this protection, you can hide the source code and make it difficult for others to open and copy source codes.', 'wpshield-content-protector' ),
	'id'            => 'view-source',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-view-source-protector.svg'
	],
	'options'       => [
		'enable'  => __( 'ON', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	],
);
$fields['view-source/type']          = array(
	'name'         => __( 'Protection Protocol', 'wpshield-content-protector' ),
	'desc'         => __( 'Consider selecting a protocol that is secure, user-friendly, and offers the best protection.', 'wpshield-content-protector' ),
	'id'           => 'view-source/type',
	'type'         => 'advance_select',
	'vertical'     => true,
	'options'      => array(
		'hotkeys' => [
			'label' => __( 'Disable Only HotKeys', 'wpshield-content-protector' ),
			'badge' => [
				'label' => __( 'Not Secure', 'wpshield-content-protector' ),
				'color' => '#1f44617a',
			],
		],
		'comment' => [
			'label'       => __( 'Hide Source Code + Add Copyright Warning', 'wpshield-content-protector' ),
			'badge'       => [
				'label' => __( 'Secure', 'wpshield-content-protector' ),
			],
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
	),
	'show_on_type' => array(
		'disable'
	),
	'show_on'      => array(
		array(
			'view-source=enable'
		)
	)
);
$fields['view-source-message-gr']    = array(
	'name'         => __( 'Source Code Copyright Notice', 'wpshield-content-protector' ),
	'id'           => 'view-source-message-gr',
	'icon'         => 'bsfi-cc0',
	'type'         => 'group',
	'state'        => 'open',
	'show_on_type' => array(
		'disable',
	),
	'show_on'      => array(
		array(
			'view-source=enable',
		)
	),
);
$fields['view-source/message/title'] = array(
	'name'         => __( 'Copyright Notice Heading', 'wpshield-content-protector' ),
	'desc'         => __( 'Change the big heading message of the copyright notice.', 'wpshield-content-protector' ),
	'id'           => 'view-source/message/title',
	'type'         => 'text',
	'show_on_type' => array(
		'disable',
	),
	'show_on'      => array(
		array(
			'view-source=enable',
			'view-source/type=comment',
		)
	),
	'pro_feature'  => [
		'activate' => true,
		'modal_id' => 'content-protector',
	]
);
$fields['view-source/message/text']  = array(
	'name'         => __( 'Copyright Message', 'wpshield-content-protector' ),
	'desc'         => __( 'You can change the text of the notice by customizing it to fit your copyright concerns.', 'wpshield-content-protector' ),
	'id'           => 'view-source/message/text',
	'type'         => 'textarea',
	'show_on_type' => array(
		'disable',
	),
	'show_on'      => array(
		array(
			'view-source=enable',
			'view-source/type=comment',
		)
	),
	'pro_feature'  => [
		'activate' => true,
		'modal_id' => 'content-protector',
	]
);

$fields = wpshield_cp_alert_popup_options( $fields, 'view-source' );

$fields = wpshield_cp_alert_audio_options( $fields, 'view-source' );

$fields = wpshield_cp_filter_options( $fields, 'view-source',
	[
		'excluded-filter-types' => [ 'css-class' ],
	]
);


/**
 * Disabled avaScript Protector
 */

$fields[]                           = array(
	'name' => __( 'Disabled JavaScript Protector', 'wpshield-content-protector' ),
	'id'   => 'javascript-tab',
	'type' => 'tab',
	'icon' => 'cp-javascript',
);
$fields['javascript']               = array(
	'name'          => __( 'Disabled JavaScript Protector', 'wpshield-content-protector' ),
	'desc'          => __( 'Users can disable JavaScript in browsers to access your protected contents!. However, with this protector, you can be sure that JavaScript cannot be disabled from your browser in order to access your content.', 'wpshield-content-protector' ),
	'id'            => 'javascript',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-javascript-disabled-protector.svg'
	],
	'options'       => [
		'enable'  => __( 'ON', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	],
);
$fields['javascript/type']          = array(
	'name'         => __( 'Protection Protocol', 'wpshield-content-protector' ),
	'desc'         => __( 'Consider selecting a protocol that is secure, user-friendly, and offers the best protection.', 'wpshield-content-protector' ),
	'id'           => 'javascript/type',
	'type'         => 'advance_select',
	'vertical'     => true,
	'options'      => array(
		'message'  => [
			'label' => __( 'Show Simple Notice Message', 'wpshield-content-protector' ),
			'badge' => [
				'label' => __( 'Not Secure', 'wpshield-content-protector' ),
				'color' => '#1f44617a',
			],
		],
		'blank'    => [
			'label'       => __( 'Clear Page Content + Show Notice Message', 'wpshield-content-protector' ),
			'badge'       => [
				'label' => __( '100% Secure', 'wpshield-content-protector' ),
			],
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
		'redirect' => [
			'label'       => __( 'Redirect User To Another Page', 'wpshield-content-protector' ),
			'badge'       => [
				'label' => __( '100% Secure', 'wpshield-content-protector' ),
			],
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
	),
	'show_on_type' => array(
		'disable'
	),
	'show_on'      => array(
		array(
			'javascript=enable'
		)
	)
);
$fields['javascript/redirect/page'] = array(
	'name'             => __( 'Redirect To Page', 'wpshield-content-protector' ),
	'desc'             => __( 'Select a page on your site to which you would like to redirect visitors if JavaScript is not enabled. If you create a custom page with the appropriate design and content, you can warn users about copying your website.', 'wpshield-content-protector' ),
	'id'               => 'javascript/redirect/page',
	'type'             => 'ajax_select',
	'deferred-options' => 'cp_list_pages',
	'show_on_type'     => array(
		'disable',
	),
	'show_on'          => array(
		array(
			'javascript=enable',
			'javascript/type=redirect',
		),
	),
	'callback'         => 'BF_Ajax_Select_Callbacks::pages_callback',
	'get_name'         => 'BF_Ajax_Select_Callbacks::page_name',
	'placeholder'      => __( 'Search and Select Pages...', 'wpshield-content-protector' ),
);

$fields = wpshield_cp_alert_popup_options( $fields, 'javascript' );

$fields = wpshield_cp_filter_options( $fields, 'javascript',
	[
		'excluded-filter-types' => [ 'css-class' ],
	]
);


/**
 * Print Protector
 */

$fields[]             = array(
	'name' => __( 'Print Protector', 'wpshield-content-protector' ),
	'id'   => 'print-tab',
	'type' => 'tab',
	'icon' => 'cp-print',
);
$fields['print']      = array(
	'name'          => __( 'Print Protector', 'wpshield-content-protector' ),
	'desc'          => __( 'By default, users can print web pages, but by using the Protector you have the ability to disable the print feature on your site or add a watermark message to printed pages.', 'wpshield-content-protector' ),
	'id'            => 'print',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-print-protector.svg'
	],
	'options'       => [
		'enable'  => __( 'ON', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	],
);
$fields['print/type'] = array(
	'name'         => __( 'Protection Protocol', 'wpshield-content-protector' ),
	'desc'         => __( 'Consider selecting a protocol that is secure, user-friendly, and offers the best protection.', 'wpshield-content-protector' ),
	'id'           => 'print/type',
	'type'         => 'advance_select',
	'vertical'     => true,
	'options'      => array(
		'hotkeys'   => [
			'label' => __( 'Disable Only HotKeys', 'wpshield-content-protector' ),
			'badge' => [
				'label' => __( 'Not Secure', 'wpshield-content-protector' ),
				'color' => '#1f44617a',
			],
		],
		'blank'     => [
			'label'       => __( 'Clear Content and Show Blank Page for Print', 'wpshield-content-protector' ),
			'badge'       => [
				'label' => __( '100% Secure', 'wpshield-content-protector' ),
			],
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
		'watermark' => [
			'label'       => __( 'Show Watermark On Print Page', 'wpshield-content-protector' ),
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
	),
	'show_on_type' => array(
		'disable'
	),
	'show_on'      => array(
		array(
			'print=enable'
		)
	)
);

$fields['print-watermark-gr']      = array(
	'name'         => __( 'Watermark On Print Pages', 'wpshield-content-protector' ),
	'id'           => 'print-watermark-gr',
	'icon'         => 'bsfi-stamp3',
	'type'         => 'group',
	'state'        => 'open',
	'show_on_type' => array(
		'disable'
	),
	'show_on'      => array(
		array(
			'print=enable',
			'print/type=watermark',
		)
	),
);
$fields['print/watermark/file']    = array(
	'name'         => __( 'Watermark Image', 'wpshield-content-protector' ),
	'desc'         => __( 'You can choose an image that will be watermarked on the print pages for users.', 'wpshield-content-protector' ),
	'id'           => 'print/watermark/file',
	'type'         => 'media_image',
	'data-type'    => 'id',
	'media_title'  => __( 'Select or Upload Watermark Image', 'wpshield-content-protector' ),
	'upload_label' => __( 'Upload Watermark', 'wpshield-content-protector' ),
	'media_button' => __( 'Select Image', 'wpshield-content-protector' ),
	'remove_label' => __( 'Remove', 'wpshield-content-protector' ),
	'pro_feature'  => [
		'activate' => true,
		'modal_id' => 'content-protector',
	],
);
$fields['print/watermark/opacity'] = array(
	'name'        => __( 'Watermark Opacity', 'wpshield-content-protector' ),
	'desc'        => __( 'You can change the opacity or transparency of the watermark copyright on the print page.', 'wpshield-content-protector' ),
	'id'          => 'print/watermark/opacity',
	'type'        => 'slider',
	'pro_feature' => [
		'activate' => true,
		'modal_id' => 'content-protector',
	],
);

$fields = wpshield_cp_alert_popup_options( $fields, 'print' );

$fields = wpshield_cp_alert_audio_options( $fields, 'print' );

$fields = wpshield_cp_filter_options( $fields, 'print',
	[
		'excluded-filter-types' => [ 'css-class' ],
	]
);


/**
 * Extensions Protector
 */

$fields[]             = array(
	'name' => __( 'Extensions Protector', 'wpshield-content-protector' ),
	'id'   => 'extensions-tab',
	'type' => 'tab',
	'icon' => 'cp-puzzle',
);
$fields['extensions'] = array(
	'name'          => __( 'Browsers Copy Enabler Extensions Protector', 'wpshield-content-protector' ),
	'desc'          => __( 'Expert users are able to manipulate copy protection on websites using Google Chrome and Firefox extensions that enables content copy on all sites. Using this protector, these extensions will be disabled on your website.', 'wpshield-content-protector' ),
	'id'            => 'extensions',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-enable-copy-extensions-protector.svg'
	],
	'options'       => [
		'enable'  => [
			'label'       => __( 'ON', 'wpshield-content-protector' ),
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
				'template' => [
					'title' => __( '<span>“ <b>Browsers Copy Enabler Extensions Protector</b> ”</span> Is A PRO Feature', 'wpshield-content-protector' ),
				],
			],
		],
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	],
);


$fields = wpshield_cp_alert_popup_options( $fields, 'extensions' );

$fields = wpshield_cp_alert_audio_options( $fields, 'extensions' );

$fields = wpshield_cp_filter_options( $fields, 'extensions' );


/**
 * Feed Protector
 */

$fields[]                      = array(
	'name' => __( 'Feed Protector', 'wpshield-content-protector' ),
	'id'   => 'feed-tab',
	'type' => 'tab',
	'icon' => 'cp-rss',
);
$fields['feed']                = array(
	'name'          => __( 'Feed Protector', 'wpshield-content-protector' ),
	'desc'          => __( 'Users can use feeds in conjunction with <a href="https://betterstudio.com/wordpress-plugins/best-wordpress-content-curation-plugins/" target="_blank">WordPress Autoblogging plugins</a> to stream and copy your content. By using this protector, you can prohibit users from using feeds', 'wpshield-content-protector' ),
	'id'            => 'feed',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-feed-protector.svg'
	],
	'options'       => [
		'enable'  => __( 'ON', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	],
);
$fields['feed/type']           = array(
	'name'         => __( 'Protection Protocol', 'wpshield-content-protector' ),
	'desc'         => __( 'Consider selecting a protocol that is secure, user-friendly, and offers the best protection.', 'wpshield-content-protector' ),
	'id'           => 'feed/type',
	'type'         => 'advance_select',
	'vertical'     => true,
	'options'      => array(
		'redirect' => [
			'label' => __( 'Disable and Redirect Feed URLs to Normal Pages', 'wpshield-content-protector' ),
		],
		'excerpt'  => [
			'label'       => __( 'Show Only Post Excerpts in Feeds', 'wpshield-content-protector' ),
			'badge'       => [
				'label' => __( 'Best UX', 'wpshield-content-protector' ),
			],
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
				'template' => [
					'title' => __( '<span>“ <b>Show Only Post Excerpts in Feeds</b> ”</span> Is A PRO Feature', 'wpshield-content-protector' ),
				],
			],
		],
		'404'      => [
			'label'       => __( '404 Page Not Found Error for All Feed Requests', 'wpshield-content-protector' ),
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
				'template' => [
					'title' => __( '<span>“ <b>404 Page Not Found Error for All Feed Requests</b> ”</span> Is A PRO Feature', 'wpshield-content-protector' ),
				],
			],
		],
	),
	'show_on_type' => array(
		'disable'
	),
	'show_on'      => array(
		array(
			'feed=enable'
		)
	)
);
$fields['feed/message/before'] = array(
	'name' => __( 'Copyright Notice Before Post Contents in Feed', 'wpshield-content-protector' ),
	'desc' => __( 'The message will appear <b>before</b> the actual post content in feeds. If you have any copyright concerns, you may add a copyright notice.', 'wpshield-content-protector' ),
	'id'   => 'feed/message/before',
	'type' => 'textarea',
);
$fields['feed/message/after']  = array(
	'name' => __( 'Copyright Notice After Post Contents in Feed', 'wpshield-content-protector' ),
	'desc' => __( 'The message will appear <b>after</b> the actual post content in feeds. If you have any copyright concerns, you may add a copyright notice.', 'wpshield-content-protector' ),
	'type' => 'textarea',
	'id'   => 'feed/message/after',
);

$fields = wpshield_cp_filter_options( $fields, 'feed',
	[
		'excluded-filter-types' => [ 'css-class' ],
	]
);


/**
 * iFrame Protector
 */

$fields[]                           = array(
	'name' => __( 'iFrame Hotlink Protector', 'wpshield-content-protector' ),
	'id'   => 'iframe-tab',
	'type' => 'tab',
	'icon' => 'cp-iframe',
);
$fields['iframe']                   = array(
	'name'          => __( 'iFrame Hotlink Protector', 'wpshield-content-protector' ),
	'desc'          => __( 'Your website may be embedded in other websites using iframes and this can compromise your website\'s security and create copyright issues. By using this tool, you can disable this or add a copyright watermark to it.', 'wpshield-content-protector' ),
	'id'            => 'iframe',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-hotlink-protector.svg'
	],
	'options'       => [
		'enable'  => __( 'ON', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	],
);
$fields['iframe/type']              = array(
	'name'         => __( 'Protection Protocol', 'wpshield-content-protector' ),
	'desc'         => __( 'Consider selecting a protocol that is secure, user-friendly, and offers the best protection.', 'wpshield-content-protector' ),
	'id'           => 'iframe/type',
	'type'         => 'advance_select',
	'vertical'     => true,
	'options'      => array(
		'message'   => [
			'label' => __( 'Show Popup Message in iFrame Requests', 'wpshield-content-protector' ),
			'badge' => [
				'label' => __( 'Not Secure', 'wpshield-content-protector' ),
				'color' => '#1f44617a',
			],
		],
		'blank'     => [
			'label'       => __( 'Block and Show a Blank Page in iFrames', 'wpshield-content-protector' ),
			'badge'       => [
				'label' => __( '100% Secure', 'wpshield-content-protector' ),
			],
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
		'watermark' => [
			'label'       => __( 'Show a Watermark Copyright on iFrame Requests', 'wpshield-content-protector' ),
			'badge'       => [
				'label' => __( 'Best UX', 'wpshield-content-protector' ),
			],
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
		'redirect'  => [
			'label'       => __( 'Redirect iFrame Request to Custom Page', 'wpshield-content-protector' ),
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
	),
	'show_on_type' => array(
		'disable'
	),
	'show_on'      => array(
		array(
			'iframe=enable'
		)
	)
);
$fields['iframe/redirect/page']     = array(
	'name'             => __( 'Redirect To Page', 'wpshield-content-protector' ),
	'desc'             => __( 'Select a page on your site to which you would like to redirect the iFrame loading request. If you create a custom page with the appropriate design and content, you can warn users about copying your website.', 'wpshield-content-protector' ),
	'id'               => 'iframe/redirect/page',
	'type'             => 'ajax_select',
	'deferred-options' => 'cp_list_pages',
	'show_on_type'     => array(
		'disable',
	),
	'show_on'          => array(
		array(
			'iframe/type=redirect',
		),
	),
	'pro_feature'      => [
		'activate' => true,
		'modal_id' => 'content-protector',
	],
	'callback'         => 'BF_Ajax_Select_Callbacks::pages_callback',
	'get_name'         => 'BF_Ajax_Select_Callbacks::page_name',
	'placeholder'      => __( 'Search and Select Pages...', 'wpshield-content-protector' ),
);
$fields['iframe-watermark-gr']      = array(
	'name'         => __( 'Watermark on iFrame Pages', 'wpshield-content-protector' ),
	'id'           => 'iframe-watermark-gr',
	'icon'         => 'bsfi-stamp3',
	'type'         => 'group',
	'state'        => 'open',
	'show_on_type' => array(
		'disable'
	),
	'show_on'      => array(
		array(
			'iframe=enable',
			'iframe/type=watermark',
		)
	),
);
$fields['iframe/watermark/file']    = array(
	'name'         => __( 'Watermark Image', 'wpshield-content-protector' ),
	'desc'         => __( 'You can choose an image that will be watermarked on iFrame requests for users.', 'wpshield-content-protector' ),
	'id'           => 'iframe/watermark/file',
	'type'         => 'media_image',
	'data-type'    => 'id',
	'media_title'  => __( 'Select or Upload Watermark Image', 'wpshield-content-protector' ),
	'upload_label' => __( 'Upload Watermark', 'wpshield-content-protector' ),
	'media_button' => __( 'Select Image', 'wpshield-content-protector' ),
	'remove_label' => __( 'Remove', 'wpshield-content-protector' ),
	'pro_feature'  => [
		'activate' => true,
		'modal_id' => 'content-protector',
	],
);
$fields['iframe/watermark/opacity'] = array(
	'name'        => __( 'Watermark Opacity', 'wpshield-content-protector' ),
	'desc'        => __( 'You can change the opacity or transparency of the watermark copyright on the iFrame requests.', 'wpshield-content-protector' ),
	'type'        => 'slider',
	'id'          => 'iframe/watermark/opacity',
	'pro_feature' => [
		'activate' => true,
		'modal_id' => 'content-protector',
	],
);

$fields = wpshield_cp_alert_popup_options( $fields, 'iframe' );

$fields = wpshield_cp_alert_popup_options( $fields, 'iframe' );

$fields = wpshield_cp_filter_options( $fields, 'iframe' );


/**
 * Email Protector
 */
$fields[]                     = array(
	'name'       => __( 'Email Address Protector', 'wpshield-content-protector' ),
	'id'         => 'email-address-tab',
	'type'       => 'tab',
	'icon'       => 'cp-email',
	'margin-top' => '20',
);
$fields['email-address']      = array(
	'name'          => __( 'Email Address Protector', 'wpshield-content-protector' ),
	'desc'          => __( 'Crawlers and robots can search your website for emails and send spam emails to you and other emails on your site. Using this protector, you can encode or obfuscate emails and prevent them from finding emails.', 'wpshield-content-protector' ),
	'id'            => 'email-address',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-emails-protector.svg'
	],
	'options'       => [
		'enable'  => __( 'ON', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	],
);
$fields['email-address/type'] = array(
	'name'          => __( 'Protection Protocol', 'wpshield-content-protector' ),
	'desc'          => __( 'Consider selecting a protocol that is secure, user-friendly, and offers the best protection.', 'wpshield-content-protector' ),
	'id'            => 'email-address/type',
	'type'          => 'advance_select',
	'vertical'      => true,
	'section_class' => 'width-70',
	'options'       => array(
		'char-encoding' => [
			'label' => __( 'Convert Emails Characters Encoding', 'wpshield-content-protector' ),
			'badge' => [
				'label' => __( 'Not Secure', 'wpshield-content-protector' ),
				'color' => '#1f44617a',
			],
		],
		'javascript'    => [
			'label'       => __( 'Obfuscate and Make Emails Invisible for Crawlers', 'wpshield-content-protector' ),
			'badge'       => [
				'label' => __( '100% Secure', 'wpshield-content-protector' ),
			],
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
	),
	'show_on_type'  => array(
		'disable'
	),
	'show_on'       => array(
		array(
			'email-address=enable'
		)
	)
);

$fields = wpshield_cp_filter_options( $fields, 'email-address' );


/**
 * Phone Number Protector
 */
$fields[]                    = array(
	'name' => __( 'Phone Number Protector', 'wpshield-content-protector' ),
	'id'   => 'phone-numbers-tab',
	'type' => 'tab',
	'icon' => 'cp-phone',
);
$fields['phone-number']      = array(
	'name'          => __( 'Phone Number Protector', 'wpshield-content-protector' ),
	'desc'          => __( 'Crawlers and robots can search your website for phone numbers and send spam messages to you and other numbers on your site. Using this protector, you can encode or obfuscate phone numbers and prevent them from finding numbers.', 'wpshield-content-protector' ),
	'id'            => 'phone-number',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'image'         => [
		'src' => WPSHIELD_CP_URL . 'assets/images/wpshield-content-protector-phone-number-protector.svg'
	],
	'options'       => [
		'enable'  => __( 'ON', 'wpshield-content-protector' ),
		'disable' => [
			'label' => __( 'OFF', 'wpshield-content-protector' ),
			'color' => '#4f6d84',
		],
	],
);
$fields['phone-number/type'] = array(
	'name'          => __( 'Protection Protocol', 'wpshield-content-protector' ),
	'desc'          => __( 'Consider selecting a protocol that is secure, user-friendly, and offers the best protection.', 'wpshield-content-protector' ),
	'type'          => 'advance_select',
	'id'            => 'phone-number/type',
	'vertical'      => true,
	'section_class' => 'width-70',
	'options'       => array(
		'char-encoding' => [
			'label' => __( 'Convert Number Characters Encoding', 'wpshield-content-protector' ),
			'badge' => [
				'label' => __( 'Not Secure', 'wpshield-content-protector' ),
				'color' => '#1f44617a',
			],
		],
		'javascript'    => [
			'label'       => __( 'Obfuscate and Make Numbers Invisible for Crawlers', 'wpshield-content-protector' ),
			'badge'       => [
				'label' => __( '100% Secure', 'wpshield-content-protector' ),
			],
			'pro_feature' => [
				'activate' => true,
				'modal_id' => 'content-protector',
			],
		],
	),
	'show_on_type'  => array(
		'disable'
	),
	'show_on'       => array(
		array(
			'phone-number=enable'
		)
	)
);

$fields = wpshield_cp_filter_options( $fields, 'phone-number' );


/**
 * => Import & Export
 */
bf_inject_panel_import_export_fields( $fields, array(
	'panel-id'         => 'wpshield-content-protector',
	'export-file-name' => 'wpshield-content-protector-backup',
) );
