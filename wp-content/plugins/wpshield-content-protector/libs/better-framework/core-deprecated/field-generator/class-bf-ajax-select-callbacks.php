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
 * Contain General callbacks that used in BF Ajax Select field
 */
class BF_Ajax_Select_Callbacks {


	/**
	 * Used for finding post name from ID
	 *
	 * @param $id
	 *
	 * @return string
	 */
	public static function post_name( $id ) {

		return get_the_title( $id );

	}


	/**
	 * Used to retrieving posts from an keyword
	 *
	 * @param $keyword
	 * @param $exclude
	 *
	 * @return array<int|string, mixed>
	 */
	public static function posts_callback( $keyword, $exclude ): array {

		$exclude = explode( ',', $exclude );

		$q = new WP_Query(
			[
				'post_type'      => [ 'post' ],
				'posts_per_page' => 10,
				's'              => $keyword,
				'post__not_in'   => $exclude,
			]
		);

		$results = [];

		while ( $q->have_posts() ) {

			$q->the_post();

			$results[ get_the_ID() ] = get_the_title();

		}

		return $results;
	}


	/**
	 * Used for finding page name from ID
	 *
	 * @param $id
	 *
	 * @return string
	 */
	public static function page_name( $id ) {

		if ( empty( get_the_title( $id ) ) ) {

			return __( '(no title)', 'better-studio' );
		}

		return get_the_title( $id );

	}


	/**
	 * Used to retrieving pages from an keyword
	 *
	 * @param $keyword
	 * @param $exclude
	 *
	 * @return array<int|string, mixed>
	 */
	public static function pages_callback( $keyword, $exclude ): array {

		$exclude = explode( ',', $exclude );

		$q = new WP_Query(
			[
				'post_type'      => [ 'page' ],
				'posts_per_page' => 10,
				's'              => $keyword,
				'post__not_in'   => $exclude,
			]
		);

		$results = [];

		while ( $q->have_posts() ) {

			$q->the_post();

			$results[ get_the_ID() ] = get_the_title();

		}

		return $results;
	}

	/**
	 * Used to retrieving pages from an keyword
	 *
	 * @param $keyword
	 * @param $exclude
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public static function all_pages_callback( $keyword, $exclude ) {

		return bf_get_pages(
			[
				'post_type'      => [ 'page' ],
				'posts_per_page' => 10,
				's'              => $keyword,
				'post__not_in'   => explode( ',', $exclude ),
				'post_status'    => [ 'publish', 'future', 'pending', 'draft', 'private' ],
			]
		);
	}

	/**
	 * Used for finding user name from ID
	 *
	 * @param $id
	 *
	 * @return string
	 */
	public static function user_name( $id ) {

		$user = get_user_by( 'ID', $id );

		if ( ! $user ) {

			return __( '(no name)', 'better-studio' );
		}

		return $user->display_name ?? '';

	}

	/**
	 * Used to retrieving users from an keyword
	 *
	 * @param $keyword
	 * @param $exclude
	 *
	 * @return array<int|string, mixed>
	 */
	public static function users_callback( $keyword, $exclude ): array {

		$q = new WP_User_Query(
			[
				'number'  => 10,
				's'       => $keyword,
				'exclude' => explode( ',', $exclude ),
			]
		);

		$results = [];

		if ( ! empty( $q->get_results() ) ) {

			foreach ( $q->get_results() as $user ) {

				$results[ $user->ID ] = $user->display_name;
			}
		}

		return $results;
	}


	/**
	 * Used for finding Category Name from ID
	 *
	 * @param $id
	 *
	 * @return string
	 */
	public static function cat_name( $id ) {

		$cat = get_term( $id, 'category' );

		return $cat && ! is_wp_error( $cat ) ? $cat->name : __( '(no label)', 'better-studio' );
	}


	/**
	 * Used for finding Category Name from slug
	 *
	 * @param $id
	 *
	 * @return string
	 */
	public static function cat_by_slug_name( $id ) {

		$cat = get_term_by( 'slug', $id, 'category' );

		return $cat->name ?? __( '(no label)', 'better-studio' );
	}


	/**
	 * Used to retrieving Categories from an keyword
	 *
	 * @param $keyword
	 * @param $exclude
	 *
	 * @return array<int|string, mixed>
	 */
	public static function cats_callback( $keyword, $exclude ): array {

		$results = [];
		$exclude = explode( ',', $exclude );

		$args = [
			'search'  => $keyword,
			'exclude' => $exclude,
		];

		$terms = get_terms( 'category', $args );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

			foreach ( $terms as $term ) {
				$results[ $term->term_id ] = $term->name;
			}
		}

		return $results;
	}


	/**
	 * Used to retrieving Categories from an keyword ( cat slug as id )
	 *
	 * @param $keyword
	 * @param $exclude
	 *
	 * @return array<int|string, mixed>
	 */
	public static function cats_slug_callback( $keyword, $exclude ): array {

		$results = [];

		$exclude = explode( ',', $exclude );

		$args = [
			'search'  => $keyword,
			'exclude' => $exclude,
		];

		$terms = get_terms( 'category', $args );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

			foreach ( $terms as $term ) {
				$results[ $term->slug ] = $term->name;
			}
		}

		return $results;
	}


	/**
	 * Used for finding Category Name from ID
	 *
	 * @param $id
	 *
	 * @return string string on success or null on failure.
	 */
	public static function tag_name( $id ) {

		$cat = get_term( $id, 'post_tag' );

		if ( $cat && ! is_wp_error( $cat ) ) {
			return $cat->name;
		}

		return __( '(no label)', 'better-studio' );
	}


	/**
	 * Used for finding Category Name from slug
	 *
	 * @param $id
	 *
	 * @return string
	 */
	public static function tag_by_slug_name( $id ) {

		$cat = get_term_by( 'slug', $id, 'post_tag' );

		return $cat->name ?? '';
	}


	/**
	 * Used to retrieving Tags from an keyword
	 *
	 * @param $keyword
	 * @param $exclude
	 * @param $include
	 *
	 * @return array<int|string, mixed>
	 */
	public static function tags_callback( $keyword, $exclude, $include = [] ): array {

		$results = [];
		$exclude = explode( ',', $exclude );

		$args = [
			'search'     => $keyword,
			'exclude'    => $exclude,
			'hide_empty' => false,
		];

		if ( ! empty( $include ) ) {

			$args['include'] = $include;
			unset( $args['search'] );
		}

		$terms = get_terms( 'post_tag', $args );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

			foreach ( $terms as $term ) {
				$results[ $term->term_id ] = $term->name;
			}
		}

		return $results;
	}


	/**
	 * Used to retrieving Tags from an keyword
	 *
	 * @param $keyword
	 * @param $exclude
	 *
	 * @return array<int|string, mixed>
	 */
	public static function tags_slug_callback( $keyword, $exclude ): array {

		$results = [];

		$exclude = explode( ',', $exclude );

		$args = [
			'search'  => $keyword,
			'exclude' => $exclude,
		];

		$terms = get_terms( 'post_tag', $args );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

			foreach ( $terms as $term ) {
				$results[ $term->slug ] = $term->name;
			}
		}

		return $results;
	}
}
