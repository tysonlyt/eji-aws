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

use BetterStudio\Framework\Pro\{
	Booster
};

$compatible = __( 'Compatible with all cache plugins', 'better' );
$compatible = "<br><br><strong style='color: var(--bf-primary-color);'>$compatible</strong>";

$fields['minify'] = array(
	'name'          => __( 'Minify & Combine All CSS & Javascript Files', 'better-studio' ),
	'desc'          => __( 'BS Booster will minify and combine all BetterStudio theme & plugins CSS & Javascript files into 1 file. It\'s highly compatible with all cache plugins.', 'better-studio' ) . $compatible,
	'id'            => 'minify',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => array(
		1 => __( 'On', 'better-studio' ),
		0 => array(
			'label' => __( 'Off', 'better-studio' ),
			'color' => '#4f6d84',
		),
	),
);


if ( apply_filters( 'better-framework/booster/mega-menu/config', array() ) ) {
	$fields['cache-mega-menu'] = array(
		'name'          => __( 'Speed up Mega menus by cache', 'better-studio' ),
		'desc'          => __( 'Cache all mega menus to speed up site loading time.', 'better-studio' ) . $compatible,
		'id'            => 'cache-mega-menu',
		'section_class' => 'bf-input-max-width-50',
		'type'          => 'advance_select',
		'options'       => array(
			1 => __( 'On', 'better-studio' ),
			0 => array(
				'label' => __( 'Off', 'better-studio' ),
				'color' => '#4f6d84',
			),
		),
	);
}


if ( apply_filters( 'better-framework/booster/widgets/config', array() ) ) {
	$fields['cache-widgets'] = array(
		'name'          => __( 'Speed up Widgets by cache', 'better-studio' ),
		'desc'          => __( 'Cache widgets to speed up site loading time.', 'better-studio' ) . $compatible,
		'id'            => 'cache-widgets',
		'section_class' => 'bf-input-max-width-50',
		'type'          => 'advance_select',
		'options'       => array(
			1 => __( 'On', 'better-studio' ),
			0 => array(
				'label' => __( 'Off', 'better-studio' ),
				'color' => '#4f6d84',
			),
		),
	);
}

if ( apply_filters( 'better-framework/booster/shortcodes/config', array() ) ) {
	$fields['cache-shortcodes'] = array(
		'name'          => __( 'Speed up Shortcodes by cache', 'better-studio' ),
		'desc'          => __( 'Cache shortcodes to speed up site loading time.', 'better-studio' ) . $compatible,
		'id'            => 'cache-shortcodes',
		'section_class' => 'bf-input-max-width-50',
		'type'          => 'advance_select',
		'options'       => array(
			1 => __( 'On', 'better-studio' ),
			0 => array(
				'label' => __( 'Off', 'better-studio' ),
				'color' => '#4f6d84',
			),
		),
	);
}

$fields['combine-whole-icons'] = array(
	'name'          => __( 'Optimize page icons (beta)', 'better-studio' ),
	'desc'          => __( 'Optimize font-awesome v4 size by loading only the ones that are needed in the page.', 'better-studio' ),
	'id'            => 'combine-whole-icons',
	'section_class' => 'bf-input-max-width-50',
	'type'          => 'advance_select',
	'options'       => array(
		1 => __( 'On', 'better-studio' ),
		0 => array(
			'label' => __( 'Off', 'better-studio' ),
			'color' => '#4f6d84',
		),
	),
);

$fields['reset_cache'] = array(
	'name'        => __( 'Reset All Cache', 'better-studio' ),
	'id'          => 'reset_cache',
	'type'        => 'ajax_action',
	'button-name' => bf_get_icon_tag( 'bsfi-trash' ) . __( 'Purge BS Booster Cache', 'better-studio' ),
	'callback'    => Booster\Booster::class . '::reset_cache_cb',
	'confirm'     => __( 'Are you sure for resetting BS Booster cache?', 'better-studio' ),
	'desc'        => __( 'This allows you to reset all Booster cache.', 'better-studio' ),
);
