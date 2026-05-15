<?php


/**
 * Right Click Protector
 */
$fields['right-click']                = array(
	'std' => 'enable',
);
$fields['right-click/type']           = array(
	'std' => 'disable',
);
$fields['right-click/internal-links'] = array(
	'std' => 'disable',
);
$fields['right-click/input-fields']   = array(
	'std' => 'disable',
);

$fields = wpshield_cp_alert_popup_std( $fields, 'right-click',
	[
		'heading' => __( 'Content Copy Protected!', 'wpshield-content-protector' ),
		'message' => __( 'Because of the copyrights associated with the content, right clicking and copying content is not allowed.', 'wpshield-content-protector' ),
	]
);

$fields = wpshield_cp_alert_audio_std( $fields, 'right-click' );

$fields = wpshield_cp_filter_std( $fields, 'right-click' );

/**
 * Text Copy Protector
 */
$fields['text-copy']                               = array(
	'std' => 'enable',
);
$fields['text-copy/type']                          = array(
	'std' => 'disable',
);
$fields['text-copy/exclude-inputs']                = array(
	'std' => 'disable',
);
$fields['text-copy/copy-appender/text']            = array(
	'std' => '%TEXT% <br> Reference: <a href="%POSTLINK%">%POSTTITLE%</a>',
);
$fields['text-copy/copy-appender/max-text-length'] = array(
	'std' => 80,
);

$fields = wpshield_cp_alert_popup_std( $fields, 'text-copy',
	[
		'heading' => __( 'Content Copy Protected!', 'wpshield-content-protector' ),
		'message' => __( 'Because of the copyrights associated with the content, copying content is not allowed.', 'wpshield-content-protector' ),
	]
);

$fields = wpshield_cp_alert_audio_std( $fields, 'text-copy' );

$fields = wpshield_cp_filter_std( $fields, 'text-copy' );


/**
 * Images Protector
 */

$fields['images']                     = array(
	'std' => 'enable',
);
$fields['images/disable-right-click'] = array(
	'std' => 'enable',
);
$fields['images/disable-drag']        = array(
	'std' => 'enable',
);
$fields['images/remove-links']        = array(
	'std' => 'enable',
);
$fields['images/disable-hotlink']     = array(
	'std' => 'enable',
);
$fields['images/disable-attachment-pages']     = array(
	'std' => 'disable',
);

$fields = wpshield_cp_alert_popup_std( $fields, 'images',
	[
		'heading' => __( 'Images Are Protected!', 'wpshield-content-protector' ),
		'message' => __( 'Because of the copyrights associated with the content, copying images is not allowed.', 'wpshield-content-protector' ),
	]
);

$fields = wpshield_cp_alert_audio_std( $fields, 'images' );

$fields = wpshield_cp_filter_std( $fields, 'images' );


/**
 * Videos Protector
 */

$fields['videos']                     = array(
	'std' => 'enable',
);
$fields['videos/disable-right-click'] = array(
	'std' => 'enable',
);
$fields['videos/download-button']     = array(
	'std' => 'enable',
);
$fields['videos/disable-hotlink']     = array(
	'std' => 'enable',
);

$fields = wpshield_cp_alert_popup_std( $fields, 'videos',
	[
		'heading' => __( 'Videos Are Protected!', 'wpshield-content-protector' ),
		'message' => __( 'Because of the copyrights associated with the content, copying video is not allowed.', 'wpshield-content-protector' ),
	]
);

$fields = wpshield_cp_alert_audio_std( $fields, 'videos' );

$fields = wpshield_cp_filter_std( $fields, 'videos' );


/**
 * Audios Protector
 */

$fields['audios']                     = array(
	'std' => 'enable',
);
$fields['audios/disable-right-click'] = array(
	'std' => 'enable',
);
$fields['audios/download-button']     = array(
	'std' => 'enable',
);
$fields['audios/disable-hotlink']     = array(
	'std' => 'enable',
);

$fields = wpshield_cp_filter_std( $fields, 'audios',
	[
		'heading' => __( 'Audios Are Protected!', 'wpshield-content-protector' ),
		'message' => __( 'Because of the copyrights associated with the content, copying audio is not allowed.', 'wpshield-content-protector' ),
	]
);

$fields = wpshield_cp_alert_popup_std( $fields, 'audios' );

$fields = wpshield_cp_alert_audio_std( $fields, 'audios' );


/**
 * Developer Tools Protector
 */

$fields['developer-tools']               = array(
	'std' => 'enable',
);
$fields['developer-tools/type']          = array(
	'std' => 'hotkeys',
);
$fields['developer-tools/redirect/page'] = array(
	'std' => '',
);

$fields = wpshield_cp_filter_std( $fields, 'developer-tools' );

$fields = wpshield_cp_alert_popup_std( $fields, 'developer-tools',
	[
		'heading' => __( 'Content Copy Protected!', 'wpshield-content-protector' ),
		'message' => __( 'Because of the copyrights associated with the content, the developer tools is not allowed.', 'wpshield-content-protector' ),
	]
);


/**
 * View Source Protector
 */

$fields['view-source']               = array(
	'std' => 'enable',
);
$fields['view-source/type']          = array(
	'std' => 'hotkeys',
);
$fields['view-source/message/title'] = array(
	'std' => 'Source Code Protected',
);
$fields['view-source/message/text']  = array(
	'std' => '© Copyright (C) 2022 %SITENAME% - All Rights Reserved

The %SITELINK% site may not be copied or duplicated in whole or part by any means
without express prior agreement in writing.

Some photographs or documents contained on the site may be the copyrighted property of others;
Acknowledgement of those copyrights is hereby given. All such material is used with the permission of the owner.',
);

$fields = wpshield_cp_alert_popup_std( $fields, 'view-source',
	[
		'heading' => __( 'Source Code Protected!', 'wpshield-content-protector' ),
		'message' => __( 'Because of the copyrights associated with the content, viewing source code is not allowed.', 'wpshield-content-protector' ),
	]
);

$fields = wpshield_cp_alert_audio_std( $fields, 'view-source' );

$fields = wpshield_cp_filter_std( $fields, 'view-source' );


/**
 * JavaScript Disabled Protector
 */

$fields['javascript']               = array(
	'std' => 'enable',
);
$fields['javascript/type']          = array(
	'std' => 'message',
);
$fields['javascript/redirect/page'] = array(
	'std' => '',
);

$fields = wpshield_cp_alert_popup_std( $fields, 'javascript',
	[
		'heading' => __( 'Content Protected!', 'wpshield-content-protector' ),
		'message' => __( 'Because of the copyrights associated with the content, disabling javascript is not allowed.', 'wpshield-content-protector' ),
	]
);

$fields = wpshield_cp_filter_std( $fields, 'javascript' );


/**
 * Print Protector
 */

$fields['print']                   = array(
	'std' => 'enable',
);
$fields['print/type']              = array(
	'std' => 'hotkeys',
);
$fields['print/watermark/file']    = array(
	'std' => '',
);
$fields['print/watermark/opacity'] = array(
	'std' => 50,
);

$fields = wpshield_cp_filter_std( $fields, 'print' );

$fields = wpshield_cp_alert_popup_std( $fields, 'print',
	[
		'heading' => __( 'Content Print Protected!', 'wpshield-content-protector' ),
		'message' => __( 'Because of the copyrights associated with the content, printing content is not allowed.', 'wpshield-content-protector' ),
	]
);

$fields = wpshield_cp_alert_audio_std( $fields, 'print' );


/**
 * Extensions Protector
 */

$fields['extensions'] = array(
	'std' => 'disable',
);

$fields = wpshield_cp_filter_std( $fields, 'extensions' );

$fields = wpshield_cp_alert_popup_std( $fields, 'extensions',
	[
		'heading' => __( 'Content Copy Protected!', 'wpshield-content-protector' ),
		'message' => __( 'Because of the copyrights associated with the content, copying content is not allowed.', 'wpshield-content-protector' ),
	]
);

$fields = wpshield_cp_alert_audio_std( $fields, 'extensions' );

/**
 * IDM Extension Protector
 */

$fields['idm-extension'] = array(
	'std' => 'disable',
);

$fields = wpshield_cp_filter_std( $fields, 'idm-extension' );

$fields = wpshield_cp_alert_popup_std( $fields, 'idm-extension',
	[
		'heading' => __( 'IDM Extension Detected!', 'wpshield-content-protector' ),
		'message' => __( 'Because of the copyrights associated with the content, IDM browser extension is not allowed while using our site. Please disable IDM extension and visit again.', 'wpshield-content-protector' ),
	]
);

$fields = wpshield_cp_alert_audio_std( $fields, 'idm-extension' );

/**
 * Feed Protector
 */

$fields['feed']                = array(
	'std' => 'enable',
);
$fields['feed/type']           = array(
	'std' => 'redirect',
);
$fields['feed/message/before'] = array(
	'std' => '',
);
$fields['feed/message/after']  = array(
	'std' => '',
);

$fields = wpshield_cp_filter_std( $fields, 'feed' );


/**
 * iFrame Protector
 */

$fields['iframe']                   = array(
	'std' => 'enable',
);
$fields['iframe/type']              = array(
	'std' => 'message',
);
$fields['iframe/redirect/page']     = array(
	'std' => '',
);
$fields['iframe/watermark/file']    = array(
	'std' => '',
);
$fields['iframe/watermark/opacity'] = array(
	'std' => '',
);

$fields = wpshield_cp_filter_std( $fields, 'iframe' );

$fields = wpshield_cp_alert_popup_std( $fields, 'iframe',
	[
		'heading' => __( 'iFrame Loading Protected!', 'wpshield-content-protector' ),
		'message' => __( 'Because of the copyrights associated with the content, loading this site in iframes is not allowed.', 'wpshield-content-protector' ),
	]
);

$fields = wpshield_cp_alert_audio_std( $fields, 'iframe' );

/**
 * Email Protector
 */

$fields['email-address']      = array(
	'std' => 'enable',
);
$fields['email-address/type'] = array(
	'std' => 'char-encoding',
);

$fields = wpshield_cp_filter_std( $fields, 'email-address' );

/**
 * Phone Numbers Protector
 */

$fields['phone-number']      = array(
	'std' => 'enable',
);
$fields['phone-number/type'] = array(
	'std' => 'char-encoding',
);

$fields = wpshield_cp_filter_std( $fields, 'phone-number' );
