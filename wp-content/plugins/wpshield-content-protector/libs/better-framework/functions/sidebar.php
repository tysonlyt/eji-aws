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


if ( ! function_exists( 'bf_get_current_sidebar' ) ) {
	/**
	 * Used For retrieving current sidebar
	 *
	 * @since 2.5.5
	 *
	 * @return string
	 */
	function bf_get_current_sidebar() {

		return Better_Framework::widget_manager()->get_current_sidebar();
	}
}


if ( ! function_exists( 'bf_get_sidebar_name_from_id' ) ) {
	/**
	 * Used For retrieving current sidebar
	 *
	 * @param $sidebar_id
	 *
	 * @since 2.0
	 *
	 * @return
	 */
	function bf_get_sidebar_name_from_id( $sidebar_id ) {

		global $wp_registered_sidebars;

		if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
			return $wp_registered_sidebars[ $sidebar_id ]['name'];
		}

	}
}


if ( ! function_exists( 'bf_is_widget_block_sidebar' ) ) {
	/**
	 * For detecting the current sidebar is a Widget Block Sidebar or normal sidebar
	 *
	 * @since 3.15.2
	 *
	 * @return bool
	 */
	function bf_is_widget_block_sidebar() {

		return isset( $GLOBALS['bf_is_widget_block_sidebar'] ) ? $GLOBALS['bf_is_widget_block_sidebar'] : false;
	}
}

add_filter( 'widget_block_content', 'bf_is_widget_block_sidebar_begin', 2 );

if ( ! function_exists( 'bf_is_widget_block_sidebar_begin' ) ) {

	/**
	 * Start widget block content.
	 *
	 * @param string $content
	 *
	 * @since 3.15.2
	 * @return string
	 */
	function bf_is_widget_block_sidebar_begin( $content ) {

		$GLOBALS['bf_is_widget_block_sidebar'] = true;

		return $content;
	}
}


add_filter( 'widget_block_content', 'bf_is_widget_block_sidebar_end', 9999 );

if ( ! function_exists( 'bf_is_widget_block_sidebar_end' ) ) {

	/**
	 * End widget block content.
	 *
	 * @param string $content
	 *
	 * @since 3.15.2
	 * @return string
	 */
	function bf_is_widget_block_sidebar_end( $content ) {

		$GLOBALS['bf_is_widget_block_sidebar'] = false;

		return $content;
	}
}
