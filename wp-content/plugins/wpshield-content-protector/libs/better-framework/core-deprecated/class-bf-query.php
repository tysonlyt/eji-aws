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

/**
 *
 * Deprecated!
 *
 * Functions of this file was moved to BF/functions/query.php
 */
class BF_Query {

	/**
	 * Deprecated!
	 *
	 * @return array
	 */
	public static function get_pages( $extra = [] ) {

		return bf_get_pages( $extra );
	}


	/**
	 * Deprecated!
	 *
	 * @return array
	 */
	public static function get_posts( $extra = [] ) {

		return bf_get_posts( $extra );
	}


	/**
	 * Deprecated!
	 *
	 * @return bool|string
	 */
	static function get_random_post_link( $echo = true ) {

		if ( $echo ) {
			echo bf_get_random_post_link( $echo ); // escaped before in generating
		} else {
			return bf_get_random_post_link( $echo );
		}
	}


	/**
	 * Deprecated!
	 *
	 * @return array
	 */
	public static function get_categories( $extra = [] ) {

		return bf_get_categories( $extra );
	}


	/**
	 * Deprecated!
	 *
	 * @return array
	 */
	public static function get_categories_by_slug( $extra = [] ) {

		return bf_get_categories_by_slug( $extra );
	}


	/**
	 * Deprecated!
	 *
	 * @return mixed
	 */
	public static function get_tags( $extra = [] ) {

		return bf_get_tags( $extra );
	}


	/**
	 * Deprecated!
	 *
	 * @return array
	 */
	public static function get_users( $extra = [], $advanced_ouput = false ) {

		return bf_get_users( $extra, $advanced_ouput );
	}


	/**
	 * Deprecated!
	 *
	 * @return array
	 */
	public static function get_post_types( $extra = [] ) {

		return bf_get_post_types( $extra );
	}


	/**
	 * Deprecated!
	 *
	 * @return mixed
	 */
	public static function get_page_templates( $extra = [] ) {

		return bf_get_page_templates( $extra );
	}


	/**
	 * Deprecated!
	 *
	 * @return array
	 */
	public static function get_taxonomies( $extra = [] ) {

		return bf_get_taxonomies( $extra );
	}


	/**
	 * Deprecated!
	 *
	 * @return array
	 */
	public static function get_terms( $tax = 'category', $extra = [] ) {

		return bf_get_terms( $tax, $extra );
	}


	/**
	 * Deprecated!
	 *
	 * @return array
	 */
	public static function get_roles( $extra = [] ) {

		return bf_get_roles( $extra );
	}


	/**
	 * Deprecated!
	 *
	 * @return array
	 */
	public static function get_menus( $hide_empty = false ) {

		return bf_get_menus( $hide_empty );
	}


	/**
	 * Deprecated!
	 *
	 * @return bool|mixed
	 */
	public static function is_a_category( $id = null ) {

		return bf_is_a_category( $id );
	}


	/**
	 * Deprecated!
	 *
	 * @return bool|mixed
	 */
	public static function is_a_tag( $id = null ) {

		return bf_is_a_tag( $id );
	}


	/**
	 * Deprecated!
	 *
	 * @return array
	 */
	public static function get_rev_sliders() {

		return bf_get_rev_sliders();
	}
} // BF_Query
