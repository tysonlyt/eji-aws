<?php

namespace BetterFrameworkPackage\Component\Control\IconSelect;

use BetterFrameworkPackage\Component\Control;

/**
 * Used for handling all actions about BS Icons in PHP
 */
class BsIcons {

	/**
	 * List of all icons
	 *
	 * @var array
	 */
	public $icons = [];


	/**
	 * List of all categories
	 *
	 * @var array
	 */
	public $categories = [];


	/**
	 * Version on current BS Font Icons
	 *
	 * @var string
	 */
	public $version = '1.0.0';


	function __construct() {

		// Categories

		$this->categories = [
			'bs-cat-1' => [
				'id'    => 'bs-cat-1',
				'label' => 'BS Icons',
			],
		];

		$this->icons = [
			'bsfi-facebook'           => [
				'label'     => 'Facebook',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b000',
			],
			'bsfi-facebook-f'         => [
				'label'     => 'Facebook F',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b000',
			],
			'bsfi-twitter'            => [
				'label'     => 'Twitter',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b001',
			],
			'bsfi-dribbble'           => [
				'label'     => 'Dribbble',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b002',
			],
			'bsfi-vimeo'              => [
				'label'     => 'Viemo',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b003',
			],
			'bsfi-rss'                => [
				'label'     => 'RSS',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b004',
			],
			'bsfi-rss-2'              => [
				'label'    => 'RSS',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-github'             => [
				'label'     => 'Github',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b005',
			],
			'bsfi-vk'                 => [
				'label'     => 'VK',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b006',
			],
			'bsfi-delicious'          => [
				'label'     => 'Delicious',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b007',
			],
			'bsfi-youtube'            => [
				'label'     => 'Youtube',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b008',
			],
			'bsfi-soundcloud'         => [
				'label'     => 'SoundCloud',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b009',
			],
			'bsfi-behance'            => [
				'label'     => 'Behance',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b00a',
			],
			'bsfi-pinterest'          => [
				'label'     => 'Pinterest',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b00b',
			],
			'bsfi-vine'               => [
				'label'     => 'Vine',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b00c',
			],
			'bsfi-steam'              => [
				'label'     => 'Steam',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b00d',
			],
			'bsfi-flickr'             => [
				'label'     => 'Flickr',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b00e',
			],
			'bsfi-envato'             => [
				'label'     => 'Envato',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b00f',
			],
			'bsfi-forrst'             => [
				'label'     => 'Forrst',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b010',
			],
			'bsfi-mailchimp'          => [
				'label'     => 'MailChimp',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b011',
			],
			'bsfi-linkedin'           => [
				'label'     => 'Linkedin',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b012',
			],
			'bsfi-tumblr'             => [
				'label'     => 'Tumblr',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b013',
			],
			'bsfi-500px'              => [
				'label'     => '500px',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b014',
			],
			'bsfi-members'            => [
				'label'     => 'Members',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b015',
			],
			'bsfi-comments'           => [
				'label'     => 'Comments',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b016',
			],
			'bsfi-posts'              => [
				'label'     => 'Posts',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b017',
			],
			'bsfi-instagram'          => [
				'label'     => 'Instagram',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b018',
			],
			'bsfi-whatsapp'           => [
				'label'     => 'Whatsapp',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b019',
			],
			'bsfi-whatsapp-2'         => [
				'label'     => 'Whatsapp - Filled',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b019',
			],
			'bsfi-line'               => [
				'label'     => 'Line',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b01a',
			],
			'bsfi-blackberry'         => [
				'label'     => 'BlackBerry',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b01b',
			],
			'bsfi-viber'              => [
				'label'     => 'Viber',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b01c',
			],
			'bsfi-skype'              => [
				'label'     => 'Skype',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b01d',
			],
			'bsfi-gplus'              => [
				'label'     => 'Google Plus',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b01e',
			],
			'bsfi-telegram'           => [
				'label'     => 'Telegram',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b01f',
			],
			'bsfi-apple'              => [
				'label'     => 'Apple',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b020',
			],
			'bsfi-android'            => [
				'label'     => 'Android',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b021',
			],
			'bsfi-fire-1'             => [
				'label'     => 'Fire 1',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b022',
			],
			'bsfi-fire-2'             => [
				'label'     => 'Fire 2',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b023',
			],
			'bsfi-fire-3'             => [
				'label'     => 'Fire 3',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b026',
			],
			'bsfi-fire-4'             => [
				'label'     => 'Fire 4',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b027',
			],
			'bsfi-betterstudio'       => [
				'label'     => 'BetterStudio',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b025',
			],
			'bsfi-publisher'          => [
				'label'     => 'Publisher',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b024',
			],
			'bsfi-google'             => [
				'label'     => 'Google+ <span class="text-muted">Alias</span>',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b01e',
			],
			'bsfi-bbm'                => [
				'label'     => 'BBM <span class="text-muted">Alias</span>',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b01b',
			],
			'bsfi-appstore'           => [
				'label'     => 'AppStore <span class="text-muted">Alias</span>',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b020',
			],
			'bsfi-quote-1'            => [
				'label'     => 'Quote 1',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b040',
			],
			'bsfi-quote-2'            => [
				'label'     => 'Quote 2',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b041',
			],
			'bsfi-quote-3'            => [
				'label'     => 'Quote 3',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b042',
			],
			'bsfi-quote-4'            => [
				'label'     => 'Quote 4',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b043',
			],
			'bsfi-quote-5'            => [
				'label'     => 'Quote 5',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b044',
			],
			'bsfi-quote-6'            => [
				'label'     => 'Quote 6',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b045',
			],
			'bsfi-quote-7'            => [
				'label'     => 'Quote 7',
				'category'  => [ 'bs-cat-1' ],
				'font_code' => '\b046',
			],
			'bsfi-quote-1'            => [
				'label'    => 'Quote 1',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-quote-2'            => [
				'label'    => 'Quote 2',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-quote-3'            => [
				'label'    => 'Quote 3',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-quote-4'            => [
				'label'    => 'Quote 4',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-quote-5'            => [
				'label'    => 'Quote 5',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-quote-6'            => [
				'label'    => 'Quote 6',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-quote-7'            => [
				'label'    => 'Quote 7',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-better-amp'         => [
				'label'    => 'Better AMP',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-ok-ru'              => [
				'label'    => 'Okru',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-snapchat'           => [
				'label'    => 'Snapchat',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-comments-1'         => [
				'label'    => 'Comment 1',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-comments-2'         => [
				'label'    => 'Comment 2',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-comments-3'         => [
				'label'    => 'Comment 3',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-comments-4'         => [
				'label'    => 'Comment 4',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-comments-5'         => [
				'label'    => 'Comment 5',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-comments-6'         => [
				'label'    => 'Comment 6',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-comments-7'         => [
				'label'    => 'Comment 7',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-calender'           => [
				'label'    => 'Calender',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-close'              => [
				'label'    => 'Remove or Close',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-checkmark'          => [
				'label'    => 'Checkmark',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-cloud-upload'       => [
				'label'    => 'Cloud Upload',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-cloud-download'     => [
				'label'    => 'Cloud Download',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-star'               => [
				'label'    => 'Star',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-trash'              => [
				'label'    => 'Trash',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-404'                => [
				'label'    => '404',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-bbpress'            => [
				'label'    => 'bbPress',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-woocommerce'        => [
				'label'    => 'WooCommerce',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-woocommerce-simple' => [
				'label'    => 'WooCommerce Simplified',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-digg'               => [
				'label'    => 'Digg',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-folder-open'        => [
				'label'    => 'Folder Open',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-global'             => [
				'label'    => 'Global',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-plus'               => [
				'label'    => 'Plus - Add',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-remove'             => [
				'label'    => 'Remove - Delete',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-print'              => [
				'label'    => 'Print',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-print-2'            => [
				'label'    => 'Print 2',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-refresh'            => [
				'label'    => 'Refresh',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-search'             => [
				'label'    => 'Search',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-shopping-cart-1'    => [
				'label'    => 'Shopping Cart 1',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-shopping-cart-2'    => [
				'label'    => 'Shopping Cart 2',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-shopping-cart-3'    => [
				'label'    => 'Shopping Cart 3',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-category'           => [
				'label'    => 'Category',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-edit'               => [
				'label'    => 'Edit',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-email-1'            => [
				'label'    => 'Email',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-google-drive'       => [
				'label'    => 'Google Drive',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-html'               => [
				'label'    => 'HTML',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-images'             => [
				'label'    => 'Images',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-images-2'           => [
				'label'    => 'Images 2',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-javascript'         => [
				'label'    => 'Javascript',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-lock'               => [
				'label'    => 'Lock',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-password-1'         => [
				'label'    => 'Password',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-read'               => [
				'label'    => 'Read',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-stamp'              => [
				'label'    => 'Stamp',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-stop'               => [
				'label'    => 'Stop',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-thunderbolt'        => [
				'label'    => 'Thunderbolt',
				'category' => [ 'bs-cat-1' ],
			],
			'bsfi-back-to-top'        => [
				'label'    => 'Back To Top',
				'category' => [ 'bs-cat-1' ],
			],
		];

		// Count each category icons
		$this->count_categories_icons();

	}


	/**
	 * Counts icons in each category
	 */
	function count_categories_icons() {

		foreach ( (array) $this->icons as $icon ) {

			if ( isset( $icon['category'] ) && \count( $icon['category'] ) ) {

				foreach ( $icon['category'] as $category ) {

					if ( ! isset( $this->categories[ $category ] ) ) {

						continue;
					}

					if ( isset( $this->categories[ $category ]['counts'] ) ) {

						$this->categories[ $category ]['counts'] = (int) $this->categories[ $category ]['counts'] + 1;

					} else {

						$this->categories[ $category ]['counts'] = 1;
					}
				}
			}
		}

	}


	/**
	 * Generate tag icon
	 *
	 * @param $icon_key
	 * @param $classes
	 *
	 * @return string
	 */
	function get_icon_tag( $icon_key, $classes = '' ) {

		$classes = apply_filters( 'better_bs_icons_classes', $classes );

		if ( ! isset( $this->icons[ $icon_key ] ) ) {
			return '';
		}

		return \BetterFrameworkPackage\Component\Control\the_icon( $icon_key, $classes );
	}

	/**
	 * @param string $icon_key
	 */
	public function html_attributes( string $icon_key, bool $is_selected = false ): void {

		if ( ! isset( $this->icons[ $icon_key ] ) ) {

			return;
		}

		$icon       = $this->icons[ $icon_key ];
		$categories = '';

		foreach ( ( $icon['category'] ?? [] ) as $category ) {
			$categories .= ' cat-' . $category;
		}

		$classes = 'icon-select-option ';

		if ( $is_selected ) {

			$classes .= 'selected ';
		}

		\BetterFrameworkPackage\Component\Control\IconSelect\Helpers::print_attributes(
			[
				'data-categories' => $categories,
				'data-font-code'  => $icon['font_code'] ?? '',
				'data-value'      => $icon_key,
				'data-label'      => $icon['label'] ?? '',
				'data-type'       => 'bs-icons',
				'class'           => $classes . $categories,
			]
		);
	}
}
