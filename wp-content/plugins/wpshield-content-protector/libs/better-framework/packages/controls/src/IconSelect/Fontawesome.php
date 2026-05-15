<?php

namespace BetterFrameworkPackage\Component\Control\IconSelect;

use BetterFrameworkPackage\Component\Control;

/**
 * Used for handling all actions about Fontawesome in PHP
 */
class Fontawesome {

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
	 * Version on current Awesomefont
	 *
	 * @var string
	 */
	public $version = '4.7.0';


	function __construct() {

		// Categories
		$this->categories = [
			1  => [
				'id'    => 1,
				'label' => '41 New Icons In 4.7',
			],
			2  => [
				'id'    => 2,
				'label' => 'Web Application Icons',
			],
			3  => [
				'id'    => 3,
				'label' => 'Accessibility Icons',
			],
			4  => [
				'id'    => 4,
				'label' => 'Hand Icons',
			],
			5  => [
				'id'    => 5,
				'label' => 'Transportation Icons',
			],
			6  => [
				'id'    => 6,
				'label' => 'Gender Icons',
			],
			7  => [
				'id'    => 7,
				'label' => 'File Type Icons',
			],
			8  => [
				'id'    => 8,
				'label' => 'Spinner Icons',
			],
			9  => [
				'id'    => 9,
				'label' => 'Form Control Icons',
			],
			10 => [
				'id'    => 10,
				'label' => 'Payment Icons',
			],
			11 => [
				'id'    => 11,
				'label' => 'Chart Icons',
			],
			12 => [
				'id'    => 12,
				'label' => 'Currency Icons',
			],
			13 => [
				'id'    => 13,
				'label' => 'Text Editor Icons',
			],
			14 => [
				'id'    => 14,
				'label' => 'Directional Icons',
			],
			15 => [
				'id'    => 15,
				'label' => 'Video Player Icons',
			],
			16 => [
				'id'    => 16,
				'label' => 'Brand Icons',
			],
			17 => [
				'id'    => 17,
				'label' => 'Medical Icons',
			],
		];

		// Cat 1

		$this->icons = [
			'fa-address-book'                        => [
				'label'     => 'Address Book',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2b9',
			],
			'fa-address-book-o'                      => [
				'label'     => 'Address Book <span class="text-muted">(Outline)</span>',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2ba',
			],
			'fa-address-card'                        => [
				'label'     => 'Address Card',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2bb',
			],
			'fa-address-card-o'                      => [
				'label'     => 'Address Card <span class="text-muted">(Outline)</span>',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2bc',
			],
			'fa-bandcamp'                            => [
				'label'     => 'Bandcamp',
				'category'  => [ 1, 16 ],
				'font_code' => '\f2d5',
			],
			'fa-bath'                                => [
				'label'     => 'Bath',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2cd',
			],
			'fa-bathtub'                             => [
				'label'     => 'Bathtub',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2cd',
			],
			'fa-drivers-license'                     => [
				'label'     => 'Drivers License',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2c2',
			],
			'fa-drivers-license-o'                   => [
				'label'     => 'Drivers License <span class="text-muted">(Outline)</span>',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2c3',
			],
			'fa-eercast'                             => [
				'label'     => 'Eercast',
				'category'  => [ 1, 16 ],
				'font_code' => '\f2da',
			],
			'fa-envelope-open'                       => [
				'label'     => 'Envelope Open',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2b6',
			],
			'fa-envelope-open-o'                     => [
				'label'     => 'Envelope Open <span class="text-muted">(Outline)</span>',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2b7',
			],
			'fa-etsy'                                => [
				'label'     => 'Etsy',
				'category'  => [ 1, 16 ],
				'font_code' => '\f2d7',
			],
			'fa-free-code-camp'                      => [
				'label'     => 'Free Code Camp',
				'category'  => [ 1, 16 ],
				'font_code' => '\f2c5',
			],
			'fa-grav'                                => [
				'label'     => 'Grav',
				'category'  => [ 1, 16 ],
				'font_code' => '\f2d6',
			],
			'fa-handshake-o'                         => [
				'label'     => 'Handshake <span class="text-muted">(Outline)</span>',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2b5',
			],
			'fa-id-badge'                            => [
				'label'     => 'Id Badge',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2c1',
			],
			'fa-id-card'                             => [
				'label'     => 'Id Card',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2c2',
			],
			'fa-id-card-o'                           => [
				'label'     => 'Id Card <span class="text-muted">(Outline)</span>',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2c3',
			],
			'fa-imdb'                                => [
				'label'     => 'Imdb',
				'category'  => [ 1, 16 ],
				'font_code' => '\f2d8',
			],
			'fa-linode'                              => [
				'label'     => 'Linode',
				'category'  => [ 1, 16 ],
				'font_code' => '\f2b8',
			],
			'fa-meetup'                              => [
				'label'     => 'Meetup',
				'category'  => [ 1, 16 ],
				'font_code' => '\f2e0',
			],
			'fa-microchip'                           => [
				'label'     => 'Microchip',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2db',
			],
			'fa-podcast'                             => [
				'label'     => 'Podcast',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2ce',
			],
			'fa-quora'                               => [
				'label'     => 'Quora',
				'category'  => [ 1, 16 ],
				'font_code' => '\f2c4',
			],
			'fa-ravelry'                             => [
				'label'     => 'Ravelry',
				'category'  => [ 1, 16 ],
				'font_code' => '\f2d9',
			],
			'fa-s15'                                 => [
				'label'     => 'S15',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2cd',
			],
			'fa-shower'                              => [
				'label'     => 'Shower',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2cc',
			],
			'fa-snowflake-o'                         => [
				'label'     => 'Snowflake <span class="text-muted">(Outline)</span>',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2dc',
			],
			'fa-superpowers'                         => [
				'label'     => 'Superpowers',
				'category'  => [ 1, 16 ],
				'font_code' => '\f2dd',
			],
			'fa-telegram'                            => [
				'label'     => 'Telegram',
				'category'  => [ 1, 16 ],
				'font_code' => '\f2c6',
			],
			'fa-thermometer'                         => [
				'label'     => 'Thermometer',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2c7',
			],
			'fa-thermometer-0'                       => [
				'label'     => 'Thermometer 0',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2cb',
			],
			'fa-thermometer-1'                       => [
				'label'     => 'Thermometer 1',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2ca',
			],
			'fa-thermometer-2'                       => [
				'label'     => 'Thermometer 2',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2c9',
			],
			'fa-thermometer-3'                       => [
				'label'     => 'Thermometer 3',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2c8',
			],
			'fa-thermometer-4'                       => [
				'label'     => 'Thermometer 4',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2c7',
			],
			'fa-thermometer-empty'                   => [
				'label'     => 'Thermometer Empty',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2cb',
			],
			'fa-thermometer-full'                    => [
				'label'     => 'Thermometer Full',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2c7',
			],
			'fa-thermometer-half'                    => [
				'label'     => 'Thermometer Half',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2c9',
			],
			'fa-thermometer-quarter'                 => [
				'label'     => 'Thermometer Quarter',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2ca',
			],
			'fa-thermometer-three-quarters'          => [
				'label'     => 'Thermometer Three Quarters',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2c8',
			],
			'fa-times-rectangle'                     => [
				'label'     => 'Times Rectangle',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2d3',
			],
			'fa-times-rectangle-o'                   => [
				'label'     => 'Times Rectangle <span class="text-muted">(Outline)</span>',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2d4',
			],
			'fa-user-circle'                         => [
				'label'     => 'User Circle',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2bd',
			],
			'fa-user-circle-o'                       => [
				'label'     => 'User Circle <span class="text-muted">(Outline)</span>',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2be',
			],
			'fa-user-o'                              => [
				'label'     => 'User <span class="text-muted">(Outline)</span>',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2c0',
			],
			'fa-vcard'                               => [
				'label'     => 'Vcard',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2bb',
			],
			'fa-vcard-o'                             => [
				'label'     => 'Vcard <span class="text-muted">(Outline)</span>',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2bc',
			],
			'fa-window-close'                        => [
				'label'     => 'Window Close',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2d3',
			],
			'fa-window-close-o'                      => [
				'label'     => 'Window Close <span class="text-muted">(Outline)</span>',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2d4',
			],
			'fa-window-maximize'                     => [
				'label'     => 'Window Maximize',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2d0',
			],
			'fa-window-minimize'                     => [
				'label'     => 'Window Minimize',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2d1',
			],
			'fa-window-restore'                      => [
				'label'     => 'Window Restore',
				'category'  => [ 1, 2 ],
				'font_code' => '\f2d2',
			],
			'fa-wpexplorer'                          => [
				'label'     => 'Wpexplorer',
				'category'  => [ 1, 16 ],
				'font_code' => '\f2de',
			],
			'fa-adjust'                              => [
				'label'     => 'Adjust',
				'category'  => [ 2 ],
				'font_code' => '\f042',
			],
			'fa-american-sign-language-interpreting' => [
				'label'     => 'American Sign Language Interpreting',
				'category'  => [ 2, 3 ],
				'font_code' => '\f2a3',
			],
			'fa-anchor'                              => [
				'label'     => 'Anchor',
				'category'  => [ 2 ],
				'font_code' => '\f13d',
			],
			'fa-archive'                             => [
				'label'     => 'Archive',
				'category'  => [ 2 ],
				'font_code' => '\f187',
			],
			'fa-area-chart'                          => [
				'label'     => 'Area Chart',
				'category'  => [ 2, 11 ],
				'font_code' => '\f1fe',
			],
			'fa-arrows'                              => [
				'label'     => 'Arrows',
				'category'  => [ 2, 14 ],
				'font_code' => '\f047',
			],
			'fa-arrows-h'                            => [
				'label'     => 'Arrows H',
				'category'  => [ 2, 14 ],
				'font_code' => '\f07e',
			],
			'fa-arrows-v'                            => [
				'label'     => 'Arrows V',
				'category'  => [ 2, 14 ],
				'font_code' => '\f07d',
			],
			'fa-asl-interpreting'                    => [
				'label'     => 'Asl Interpreting',
				'category'  => [ 2, 3 ],
				'font_code' => '\f2a3',
			],
			'fa-assistive-listening-systems'         => [
				'label'     => 'Assistive Listening Systems',
				'category'  => [ 2, 3 ],
				'font_code' => '\f2a2',
			],
			'fa-asterisk'                            => [
				'label'     => 'Asterisk',
				'category'  => [ 2 ],
				'font_code' => '\f069',
			],
			'fa-at'                                  => [
				'label'     => 'At',
				'category'  => [ 2 ],
				'font_code' => '\f1fa',
			],
			'fa-audio-description'                   => [
				'label'     => 'Audio Description',
				'category'  => [ 2, 3 ],
				'font_code' => '\f29e',
			],
			'fa-automobile'                          => [
				'label'     => 'Automobile',
				'category'  => [ 2, 5 ],
				'font_code' => '\f1b9',
			],
			'fa-balance-scale'                       => [
				'label'     => 'Balance Scale',
				'category'  => [ 2 ],
				'font_code' => '\f24e',
			],
			'fa-ban'                                 => [
				'label'     => 'Ban',
				'category'  => [ 2 ],
				'font_code' => '\f05e',
			],
			'fa-bank'                                => [
				'label'     => 'Bank',
				'category'  => [ 2 ],
				'font_code' => '\f19c',
			],
			'fa-bar-chart'                           => [
				'label'     => 'Bar Chart',
				'category'  => [ 2, 11 ],
				'font_code' => '\f080',
			],
			'fa-bar-chart-o'                         => [
				'label'     => 'Bar Chart <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 11 ],
				'font_code' => '\f080',
			],
			'fa-barcode'                             => [
				'label'     => 'Barcode',
				'category'  => [ 2 ],
				'font_code' => '\f02a',
			],
			'fa-bars'                                => [
				'label'     => 'Bars',
				'category'  => [ 2 ],
				'font_code' => '\f0c9',
			],
			'fa-battery'                             => [
				'label'     => 'Battery',
				'category'  => [ 2 ],
				'font_code' => '\f240',
			],
			'fa-battery-0'                           => [
				'label'     => 'Battery 0',
				'category'  => [ 2 ],
				'font_code' => '\f244',
			],
			'fa-battery-1'                           => [
				'label'     => 'Battery 1',
				'category'  => [ 2 ],
				'font_code' => '\f243',
			],
			'fa-battery-2'                           => [
				'label'     => 'Battery 2',
				'category'  => [ 2 ],
				'font_code' => '\f242',
			],
			'fa-battery-3'                           => [
				'label'     => 'Battery 3',
				'category'  => [ 2 ],
				'font_code' => '\f241',
			],
			'fa-battery-4'                           => [
				'label'     => 'Battery 4',
				'category'  => [ 2 ],
				'font_code' => '\f240',
			],
			'fa-battery-empty'                       => [
				'label'     => 'Battery Empty',
				'category'  => [ 2 ],
				'font_code' => '\f244',
			],
			'fa-battery-full'                        => [
				'label'     => 'Battery Full',
				'category'  => [ 2 ],
				'font_code' => '\f240',
			],
			'fa-battery-half'                        => [
				'label'     => 'Battery Half',
				'category'  => [ 2 ],
				'font_code' => '\f242',
			],
			'fa-battery-quarter'                     => [
				'label'     => 'Battery Quarter',
				'category'  => [ 2 ],
				'font_code' => '\f243',
			],
			'fa-battery-three-quarters'              => [
				'label'     => 'Battery Three Quarters',
				'category'  => [ 2 ],
				'font_code' => '\f241',
			],
			'fa-bed'                                 => [
				'label'     => 'Bed',
				'category'  => [ 2 ],
				'font_code' => '\f236',
			],
			'fa-beer'                                => [
				'label'     => 'Beer',
				'category'  => [ 2 ],
				'font_code' => '\f0fc',
			],
			'fa-bell'                                => [
				'label'     => 'Bell',
				'category'  => [ 2 ],
				'font_code' => '\f0f3',
			],
			'fa-bell-o'                              => [
				'label'     => 'Bell <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f0a2',
			],
			'fa-bell-slash'                          => [
				'label'     => 'Bell Slash',
				'category'  => [ 2 ],
				'font_code' => '\f1f6',
			],
			'fa-bell-slash-o'                        => [
				'label'     => 'Bell Slash <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f1f7',
			],
			'fa-bicycle'                             => [
				'label'     => 'Bicycle',
				'category'  => [ 2, 5 ],
				'font_code' => '\f206',
			],
			'fa-binoculars'                          => [
				'label'     => 'Binoculars',
				'category'  => [ 2 ],
				'font_code' => '\f1e5',
			],
			'fa-birthday-cake'                       => [
				'label'     => 'Birthday Cake',
				'category'  => [ 2 ],
				'font_code' => '\f1fd',
			],
			'fa-blind'                               => [
				'label'     => 'Blind',
				'category'  => [ 2, 3 ],
				'font_code' => '\f29d',
			],
			'fa-bluetooth'                           => [
				'label'     => 'Bluetooth',
				'category'  => [ 2, 16 ],
				'font_code' => '\f293',
			],
			'fa-bluetooth-b'                         => [
				'label'     => 'Bluetooth B',
				'category'  => [ 2, 16 ],
				'font_code' => '\f294',
			],
			'fa-bolt'                                => [
				'label'     => 'Bolt',
				'category'  => [ 2 ],
				'font_code' => '\f0e7',
			],
			'fa-bomb'                                => [
				'label'     => 'Bomb',
				'category'  => [ 2 ],
				'font_code' => '\f1e2',
			],
			'fa-book'                                => [
				'label'     => 'Book',
				'category'  => [ 2 ],
				'font_code' => '\f02d',
			],
			'fa-bookmark'                            => [
				'label'     => 'Bookmark',
				'category'  => [ 2 ],
				'font_code' => '\f02e',
			],
			'fa-bookmark-o'                          => [
				'label'     => 'Bookmark <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f097',
			],
			'fa-braille'                             => [
				'label'     => 'Braille',
				'category'  => [ 2, 3 ],
				'font_code' => '\f2a1',
			],
			'fa-briefcase'                           => [
				'label'     => 'Briefcase',
				'category'  => [ 2 ],
				'font_code' => '\f0b1',
			],
			'fa-bug'                                 => [
				'label'     => 'Bug',
				'category'  => [ 2 ],
				'font_code' => '\f188',
			],
			'fa-building'                            => [
				'label'     => 'Building',
				'category'  => [ 2 ],
				'font_code' => '\f1ad',
			],
			'fa-building-o'                          => [
				'label'     => 'Building <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f0f7',
			],
			'fa-bullhorn'                            => [
				'label'     => 'Bullhorn',
				'category'  => [ 2 ],
				'font_code' => '\f0a1',
			],
			'fa-bullseye'                            => [
				'label'     => 'Bullseye',
				'category'  => [ 2 ],
				'font_code' => '\f140',
			],
			'fa-bus'                                 => [
				'label'     => 'Bus',
				'category'  => [ 2, 5 ],
				'font_code' => '\f207',
			],
			'fa-cab'                                 => [
				'label'     => 'Cab',
				'category'  => [ 2, 5 ],
				'font_code' => '\f1ba',
			],
			'fa-calculator'                          => [
				'label'     => 'Calculator',
				'category'  => [ 2 ],
				'font_code' => '\f1ec',
			],
			'fa-calendar'                            => [
				'label'     => 'Calendar',
				'category'  => [ 2 ],
				'font_code' => '\f073',
			],
			'fa-calendar-check-o'                    => [
				'label'     => 'Calendar Check <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f274',
			],
			'fa-calendar-minus-o'                    => [
				'label'     => 'Calendar Minus <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f272',
			],
			'fa-calendar-o'                          => [
				'label'     => 'Calendar <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f133',
			],
			'fa-calendar-plus-o'                     => [
				'label'     => 'Calendar Plus <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f271',
			],
			'fa-calendar-times-o'                    => [
				'label'     => 'Calendar Times <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f273',
			],
			'fa-camera'                              => [
				'label'     => 'Camera',
				'category'  => [ 2 ],
				'font_code' => '\f030',
			],
			'fa-camera-retro'                        => [
				'label'     => 'Camera Retro',
				'category'  => [ 2 ],
				'font_code' => '\f083',
			],
			'fa-car'                                 => [
				'label'     => 'Car',
				'category'  => [ 2, 5 ],
				'font_code' => '\f1b9',
			],
			'fa-caret-square-o-down'                 => [
				'label'     => 'Caret Square Down <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 14 ],
				'font_code' => '\f150',
			],
			'fa-caret-square-o-left'                 => [
				'label'     => 'Caret Square Left <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 14 ],
				'font_code' => '\f191',
			],
			'fa-caret-square-o-right'                => [
				'label'     => 'Caret Square Right <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 14 ],
				'font_code' => '\f152',
			],
			'fa-caret-square-o-up'                   => [
				'label'     => 'Caret Square Up <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 14 ],
				'font_code' => '\f151',
			],
			'fa-cart-arrow-down'                     => [
				'label'     => 'Cart Arrow Down',
				'category'  => [ 2 ],
				'font_code' => '\f218',
			],
			'fa-cart-plus'                           => [
				'label'     => 'Cart Plus',
				'category'  => [ 2 ],
				'font_code' => '\f217',
			],
			'fa-cc'                                  => [
				'label'     => 'Cc',
				'category'  => [ 2, 3 ],
				'font_code' => '\f20a',
			],
			'fa-certificate'                         => [
				'label'     => 'Certificate',
				'category'  => [ 2 ],
				'font_code' => '\f0a3',
			],
			'fa-check'                               => [
				'label'     => 'Check',
				'category'  => [ 2 ],
				'font_code' => '\f00c',
			],
			'fa-check-circle'                        => [
				'label'     => 'Check Circle',
				'category'  => [ 2 ],
				'font_code' => '\f058',
			],
			'fa-check-circle-o'                      => [
				'label'     => 'Check Circle <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f05d',
			],
			'fa-check-square'                        => [
				'label'     => 'Check Square',
				'category'  => [ 2, 9 ],
				'font_code' => '\f14a',
			],
			'fa-check-square-o'                      => [
				'label'     => 'Check Square <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 9 ],
				'font_code' => '\f046',
			],
			'fa-child'                               => [
				'label'     => 'Child',
				'category'  => [ 2 ],
				'font_code' => '\f1ae',
			],
			'fa-circle'                              => [
				'label'     => 'Circle',
				'category'  => [ 2, 9 ],
				'font_code' => '\f111',
			],
			'fa-circle-o'                            => [
				'label'     => 'Circle <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 9 ],
				'font_code' => '\f10c',
			],
			'fa-circle-o-notch'                      => [
				'label'     => 'Circle Notch <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 8 ],
				'font_code' => '\f1ce',
			],
			'fa-circle-thin'                         => [
				'label'     => 'Circle Thin',
				'category'  => [ 2 ],
				'font_code' => '\f1db',
			],
			'fa-clock-o'                             => [
				'label'     => 'Clock <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f017',
			],
			'fa-clone'                               => [
				'label'     => 'Clone',
				'category'  => [ 2 ],
				'font_code' => '\f24d',
			],
			'fa-close'                               => [
				'label'     => 'Close',
				'category'  => [ 2 ],
				'font_code' => '\f00d',
			],
			'fa-cloud'                               => [
				'label'     => 'Cloud',
				'category'  => [ 2 ],
				'font_code' => '\f0c2',
			],
			'fa-cloud-download'                      => [
				'label'     => 'Cloud Download',
				'category'  => [ 2 ],
				'font_code' => '\f0ed',
			],
			'fa-cloud-upload'                        => [
				'label'     => 'Cloud Upload',
				'category'  => [ 2 ],
				'font_code' => '\f0ee',
			],
			'fa-code'                                => [
				'label'     => 'Code',
				'category'  => [ 2 ],
				'font_code' => '\f121',
			],
			'fa-code-fork'                           => [
				'label'     => 'Code Fork',
				'category'  => [ 2 ],
				'font_code' => '\f126',
			],
			'fa-coffee'                              => [
				'label'     => 'Coffee',
				'category'  => [ 2 ],
				'font_code' => '\f0f4',
			],
			'fa-cog'                                 => [
				'label'     => 'Cog',
				'category'  => [ 2, 8 ],
				'font_code' => '\f013',
			],
			'fa-cogs'                                => [
				'label'     => 'Cogs',
				'category'  => [ 2 ],
				'font_code' => '\f085',
			],
			'fa-comment'                             => [
				'label'     => 'Comment',
				'category'  => [ 2 ],
				'font_code' => '\f075',
			],
			'fa-comment-o'                           => [
				'label'     => 'Comment <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f0e5',
			],
			'fa-commenting'                          => [
				'label'     => 'Commenting',
				'category'  => [ 2 ],
				'font_code' => '\f27a',
			],
			'fa-commenting-o'                        => [
				'label'     => 'Commenting <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f27b',
			],
			'fa-comments'                            => [
				'label'     => 'Comments',
				'category'  => [ 2 ],
				'font_code' => '\f086',
			],
			'fa-comments-o'                          => [
				'label'     => 'Comments <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f0e6',
			],
			'fa-compass'                             => [
				'label'     => 'Compass',
				'category'  => [ 2 ],
				'font_code' => '\f14e',
			],
			'fa-copyright'                           => [
				'label'     => 'Copyright',
				'category'  => [ 2 ],
				'font_code' => '\f1f9',
			],
			'fa-creative-commons'                    => [
				'label'     => 'Creative Commons',
				'category'  => [ 2 ],
				'font_code' => '\f25e',
			],
			'fa-credit-card'                         => [
				'label'     => 'Credit Card',
				'category'  => [ 2, 10 ],
				'font_code' => '\f09d',
			],
			'fa-credit-card-alt'                     => [
				'label'     => 'Credit Card Alt',
				'category'  => [ 2, 10 ],
				'font_code' => '\f283',
			],
			'fa-crop'                                => [
				'label'     => 'Crop',
				'category'  => [ 2 ],
				'font_code' => '\f125',
			],
			'fa-crosshairs'                          => [
				'label'     => 'Crosshairs',
				'category'  => [ 2 ],
				'font_code' => '\f05b',
			],
			'fa-cube'                                => [
				'label'     => 'Cube',
				'category'  => [ 2 ],
				'font_code' => '\f1b2',
			],
			'fa-cubes'                               => [
				'label'     => 'Cubes',
				'category'  => [ 2 ],
				'font_code' => '\f1b3',
			],
			'fa-cutlery'                             => [
				'label'     => 'Cutlery',
				'category'  => [ 2 ],
				'font_code' => '\f0f5',
			],
			'fa-dashboard'                           => [
				'label'     => 'Dashboard',
				'category'  => [ 2 ],
				'font_code' => '\f0e4',
			],
			'fa-database'                            => [
				'label'     => 'Database',
				'category'  => [ 2 ],
				'font_code' => '\f1c0',
			],
			'fa-deaf'                                => [
				'label'     => 'Deaf',
				'category'  => [ 2, 3 ],
				'font_code' => '\f2a4',
			],
			'fa-deafness'                            => [
				'label'     => 'Deafness',
				'category'  => [ 2, 3 ],
				'font_code' => '\f2a4',
			],
			'fa-desktop'                             => [
				'label'     => 'Desktop',
				'category'  => [ 2 ],
				'font_code' => '\f108',
			],
			'fa-diamond'                             => [
				'label'     => 'Diamond',
				'category'  => [ 2 ],
				'font_code' => '\f219',
			],
			'fa-dot-circle-o'                        => [
				'label'     => 'Dot Circle <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 9 ],
				'font_code' => '\f192',
			],
			'fa-download'                            => [
				'label'     => 'Download',
				'category'  => [ 2 ],
				'font_code' => '\f019',
			],
			'fa-edit'                                => [
				'label'     => 'Edit',
				'category'  => [ 2 ],
				'font_code' => '\f044',
			],
			'fa-ellipsis-h'                          => [
				'label'     => 'Ellipsis H',
				'category'  => [ 2 ],
				'font_code' => '\f141',
			],
			'fa-ellipsis-v'                          => [
				'label'     => 'Ellipsis V',
				'category'  => [ 2 ],
				'font_code' => '\f142',
			],
			'fa-envelope'                            => [
				'label'     => 'Envelope',
				'category'  => [ 2 ],
				'font_code' => '\f0e0',
			],
			'fa-envelope-o'                          => [
				'label'     => 'Envelope <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f003',
			],
			'fa-envelope-square'                     => [
				'label'     => 'Envelope Square',
				'category'  => [ 2 ],
				'font_code' => '\f199',
			],
			'fa-eraser'                              => [
				'label'     => 'Eraser',
				'category'  => [ 2, 13 ],
				'font_code' => '\f12d',
			],
			'fa-exchange'                            => [
				'label'     => 'Exchange',
				'category'  => [ 2, 14 ],
				'font_code' => '\f0ec',
			],
			'fa-exclamation'                         => [
				'label'     => 'Exclamation',
				'category'  => [ 2 ],
				'font_code' => '\f12a',
			],
			'fa-exclamation-circle'                  => [
				'label'     => 'Exclamation Circle',
				'category'  => [ 2 ],
				'font_code' => '\f06a',
			],
			'fa-exclamation-triangle'                => [
				'label'     => 'Exclamation Triangle',
				'category'  => [ 2 ],
				'font_code' => '\f071',
			],
			'fa-external-link'                       => [
				'label'     => 'External Link',
				'category'  => [ 2 ],
				'font_code' => '\f08e',
			],
			'fa-external-link-square'                => [
				'label'     => 'External Link Square',
				'category'  => [ 2 ],
				'font_code' => '\f14c',
			],
			'fa-eye'                                 => [
				'label'     => 'Eye',
				'category'  => [ 2 ],
				'font_code' => '\f06e',
			],
			'fa-eye-slash'                           => [
				'label'     => 'Eye Slash',
				'category'  => [ 2 ],
				'font_code' => '\f070',
			],
			'fa-eyedropper'                          => [
				'label'     => 'Eyedropper',
				'category'  => [ 2 ],
				'font_code' => '\f1fb',
			],
			'fa-fax'                                 => [
				'label'     => 'Fax',
				'category'  => [ 2 ],
				'font_code' => '\f1ac',
			],
			'fa-feed'                                => [
				'label'     => 'Feed',
				'category'  => [ 2 ],
				'font_code' => '\f09e',
			],
			'fa-female'                              => [
				'label'     => 'Female',
				'category'  => [ 2 ],
				'font_code' => '\f182',
			],
			'fa-fighter-jet'                         => [
				'label'     => 'Fighter Jet',
				'category'  => [ 2, 5 ],
				'font_code' => '\f0fb',
			],
			'fa-file-archive-o'                      => [
				'label'     => 'File Archive <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 7 ],
				'font_code' => '\f1c6',
			],
			'fa-file-audio-o'                        => [
				'label'     => 'File Audio <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 7 ],
				'font_code' => '\f1c7',
			],
			'fa-file-code-o'                         => [
				'label'     => 'File Code <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 7 ],
				'font_code' => '\f1c9',
			],
			'fa-file-excel-o'                        => [
				'label'     => 'File Excel <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 7 ],
				'font_code' => '\f1c3',
			],
			'fa-file-image-o'                        => [
				'label'     => 'File Image <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 7 ],
				'font_code' => '\f1c5',
			],
			'fa-file-movie-o'                        => [
				'label'     => 'File Movie <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 7 ],
				'font_code' => '\f1c8',
			],
			'fa-file-pdf-o'                          => [
				'label'     => 'File Pdf <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 7 ],
				'font_code' => '\f1c1',
			],
			'fa-file-photo-o'                        => [
				'label'     => 'File Photo <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 7 ],
				'font_code' => '\f1c5',
			],
			'fa-file-picture-o'                      => [
				'label'     => 'File Picture <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 7 ],
				'font_code' => '\f1c5',
			],
			'fa-file-powerpoint-o'                   => [
				'label'     => 'File Powerpoint <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 7 ],
				'font_code' => '\f1c4',
			],
			'fa-file-sound-o'                        => [
				'label'     => 'File Sound <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 7 ],
				'font_code' => '\f1c7',
			],
			'fa-file-video-o'                        => [
				'label'     => 'File Video <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 7 ],
				'font_code' => '\f1c8',
			],
			'fa-file-word-o'                         => [
				'label'     => 'File Word <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 7 ],
				'font_code' => '\f1c2',
			],
			'fa-file-zip-o'                          => [
				'label'     => 'File Zip <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 7 ],
				'font_code' => '\f1c6',
			],
			'fa-film'                                => [
				'label'     => 'Film',
				'category'  => [ 2 ],
				'font_code' => '\f008',
			],
			'fa-filter'                              => [
				'label'     => 'Filter',
				'category'  => [ 2 ],
				'font_code' => '\f0b0',
			],
			'fa-fire'                                => [
				'label'     => 'Fire',
				'category'  => [ 2 ],
				'font_code' => '\f06d',
			],
			'fa-fire-extinguisher'                   => [
				'label'     => 'Fire Extinguisher',
				'category'  => [ 2 ],
				'font_code' => '\f134',
			],
			'fa-flag'                                => [
				'label'     => 'Flag',
				'category'  => [ 2 ],
				'font_code' => '\f024',
			],
			'fa-flag-checkered'                      => [
				'label'     => 'Flag Checkered',
				'category'  => [ 2 ],
				'font_code' => '\f11e',
			],
			'fa-flag-o'                              => [
				'label'     => 'Flag <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f11d',
			],
			'fa-flash'                               => [
				'label'     => 'Flash',
				'category'  => [ 2 ],
				'font_code' => '\f0e7',
			],
			'fa-flask'                               => [
				'label'     => 'Flask',
				'category'  => [ 2 ],
				'font_code' => '\f0c3',
			],
			'fa-folder'                              => [
				'label'     => 'Folder',
				'category'  => [ 2 ],
				'font_code' => '\f07b',
			],
			'fa-folder-o'                            => [
				'label'     => 'Folder <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f114',
			],
			'fa-folder-open'                         => [
				'label'     => 'Folder Open',
				'category'  => [ 2 ],
				'font_code' => '\f07c',
			],
			'fa-folder-open-o'                       => [
				'label'     => 'Folder Open <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f115',
			],
			'fa-frown-o'                             => [
				'label'     => 'Frown <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f119',
			],
			'fa-futbol-o'                            => [
				'label'     => 'Futbol <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f1e3',
			],
			'fa-gamepad'                             => [
				'label'     => 'Gamepad',
				'category'  => [ 2 ],
				'font_code' => '\f11b',
			],
			'fa-gavel'                               => [
				'label'     => 'Gavel',
				'category'  => [ 2 ],
				'font_code' => '\f0e3',
			],
			'fa-gear'                                => [
				'label'     => 'Gear',
				'category'  => [ 2, 8 ],
				'font_code' => '\f013',
			],
			'fa-gears'                               => [
				'label'     => 'Gears',
				'category'  => [ 2 ],
				'font_code' => '\f085',
			],
			'fa-gift'                                => [
				'label'     => 'Gift',
				'category'  => [ 2 ],
				'font_code' => '\f06b',
			],
			'fa-glass'                               => [
				'label'     => 'Glass',
				'category'  => [ 2 ],
				'font_code' => '\f000',
			],
			'fa-globe'                               => [
				'label'     => 'Globe',
				'category'  => [ 2 ],
				'font_code' => '\f0ac',
			],
			'fa-graduation-cap'                      => [
				'label'     => 'Graduation Cap',
				'category'  => [ 2 ],
				'font_code' => '\f19d',
			],
			'fa-group'                               => [
				'label'     => 'Group',
				'category'  => [ 2 ],
				'font_code' => '\f0c0',
			],
			'fa-hand-grab-o'                         => [
				'label'     => 'Hand Grab <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 4 ],
				'font_code' => '\f255',
			],
			'fa-hand-lizard-o'                       => [
				'label'     => 'Hand Lizard <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 4 ],
				'font_code' => '\f258',
			],
			'fa-hand-paper-o'                        => [
				'label'     => 'Hand Paper <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 4 ],
				'font_code' => '\f256',
			],
			'fa-hand-peace-o'                        => [
				'label'     => 'Hand Peace <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 4 ],
				'font_code' => '\f25b',
			],
			'fa-hand-pointer-o'                      => [
				'label'     => 'Hand Pointer <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 4 ],
				'font_code' => '\f25a',
			],
			'fa-hand-rock-o'                         => [
				'label'     => 'Hand Rock <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 4 ],
				'font_code' => '\f255',
			],
			'fa-hand-scissors-o'                     => [
				'label'     => 'Hand Scissors <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 4 ],
				'font_code' => '\f257',
			],
			'fa-hand-spock-o'                        => [
				'label'     => 'Hand Spock <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 4 ],
				'font_code' => '\f259',
			],
			'fa-hand-stop-o'                         => [
				'label'     => 'Hand Stop <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 4 ],
				'font_code' => '\f256',
			],
			'fa-hard-of-hearing'                     => [
				'label'     => 'Hard Of Hearing',
				'category'  => [ 2, 3 ],
				'font_code' => '\f2a4',
			],
			'fa-hashtag'                             => [
				'label'     => 'Hashtag',
				'category'  => [ 2 ],
				'font_code' => '\f292',
			],
			'fa-hdd-o'                               => [
				'label'     => 'Hdd <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f0a0',
			],
			'fa-headphones'                          => [
				'label'     => 'Headphones',
				'category'  => [ 2 ],
				'font_code' => '\f025',
			],
			'fa-heart'                               => [
				'label'     => 'Heart',
				'category'  => [ 2, 17 ],
				'font_code' => '\f004',
			],
			'fa-heart-o'                             => [
				'label'     => 'Heart <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 17 ],
				'font_code' => '\f08a',
			],
			'fa-heartbeat'                           => [
				'label'     => 'Heartbeat',
				'category'  => [ 2, 17 ],
				'font_code' => '\f21e',
			],
			'fa-history'                             => [
				'label'     => 'History',
				'category'  => [ 2 ],
				'font_code' => '\f1da',
			],
			'fa-home'                                => [
				'label'     => 'Home',
				'category'  => [ 2 ],
				'font_code' => '\f015',
			],
			'fa-hotel'                               => [
				'label'     => 'Hotel',
				'category'  => [ 2 ],
				'font_code' => '\f236',
			],
			'fa-hourglass'                           => [
				'label'     => 'Hourglass',
				'category'  => [ 2 ],
				'font_code' => '\f254',
			],
			'fa-hourglass-1'                         => [
				'label'     => 'Hourglass 1',
				'category'  => [ 2 ],
				'font_code' => '\f251',
			],
			'fa-hourglass-2'                         => [
				'label'     => 'Hourglass 2',
				'category'  => [ 2 ],
				'font_code' => '\f252',
			],
			'fa-hourglass-3'                         => [
				'label'     => 'Hourglass 3',
				'category'  => [ 2 ],
				'font_code' => '\f253',
			],
			'fa-hourglass-end'                       => [
				'label'     => 'Hourglass End',
				'category'  => [ 2 ],
				'font_code' => '\f253',
			],
			'fa-hourglass-half'                      => [
				'label'     => 'Hourglass Half',
				'category'  => [ 2 ],
				'font_code' => '\f252',
			],
			'fa-hourglass-o'                         => [
				'label'     => 'Hourglass <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f250',
			],
			'fa-hourglass-start'                     => [
				'label'     => 'Hourglass Start',
				'category'  => [ 2 ],
				'font_code' => '\f251',
			],
			'fa-i-cursor'                            => [
				'label'     => 'I Cursor',
				'category'  => [ 2 ],
				'font_code' => '\f246',
			],
			'fa-image'                               => [
				'label'     => 'Image',
				'category'  => [ 2 ],
				'font_code' => '\f03e',
			],
			'fa-inbox'                               => [
				'label'     => 'Inbox',
				'category'  => [ 2 ],
				'font_code' => '\f01c',
			],
			'fa-industry'                            => [
				'label'     => 'Industry',
				'category'  => [ 2 ],
				'font_code' => '\f275',
			],
			'fa-info'                                => [
				'label'     => 'Info',
				'category'  => [ 2 ],
				'font_code' => '\f129',
			],
			'fa-info-circle'                         => [
				'label'     => 'Info Circle',
				'category'  => [ 2 ],
				'font_code' => '\f05a',
			],
			'fa-institution'                         => [
				'label'     => 'Institution',
				'category'  => [ 2 ],
				'font_code' => '\f19c',
			],
			'fa-key'                                 => [
				'label'     => 'Key',
				'category'  => [ 2 ],
				'font_code' => '\f084',
			],
			'fa-keyboard-o'                          => [
				'label'     => 'Keyboard <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f11c',
			],
			'fa-language'                            => [
				'label'     => 'Language',
				'category'  => [ 2 ],
				'font_code' => '\f1ab',
			],
			'fa-laptop'                              => [
				'label'     => 'Laptop',
				'category'  => [ 2 ],
				'font_code' => '\f109',
			],
			'fa-leaf'                                => [
				'label'     => 'Leaf',
				'category'  => [ 2 ],
				'font_code' => '\f06c',
			],
			'fa-legal'                               => [
				'label'     => 'Legal',
				'category'  => [ 2 ],
				'font_code' => '\f0e3',
			],
			'fa-lemon-o'                             => [
				'label'     => 'Lemon <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f094',
			],
			'fa-level-down'                          => [
				'label'     => 'Level Down',
				'category'  => [ 2 ],
				'font_code' => '\f149',
			],
			'fa-level-up'                            => [
				'label'     => 'Level Up',
				'category'  => [ 2 ],
				'font_code' => '\f148',
			],
			'fa-life-bouy'                           => [
				'label'     => 'Life Bouy',
				'category'  => [ 2 ],
				'font_code' => '\f1cd',
			],
			'fa-life-buoy'                           => [
				'label'     => 'Life Buoy',
				'category'  => [ 2 ],
				'font_code' => '\f1cd',
			],
			'fa-life-ring'                           => [
				'label'     => 'Life Ring',
				'category'  => [ 2 ],
				'font_code' => '\f1cd',
			],
			'fa-life-saver'                          => [
				'label'     => 'Life Saver',
				'category'  => [ 2 ],
				'font_code' => '\f1cd',
			],
			'fa-lightbulb-o'                         => [
				'label'     => 'Lightbulb <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f0eb',
			],
			'fa-line-chart'                          => [
				'label'     => 'Line Chart',
				'category'  => [ 2, 11 ],
				'font_code' => '\f201',
			],
			'fa-location-arrow'                      => [
				'label'     => 'Location Arrow',
				'category'  => [ 2 ],
				'font_code' => '\f124',
			],
			'fa-lock'                                => [
				'label'     => 'Lock',
				'category'  => [ 2 ],
				'font_code' => '\f023',
			],
			'fa-low-vision'                          => [
				'label'     => 'Low Vision',
				'category'  => [ 2, 3 ],
				'font_code' => '\f2a8',
			],
			'fa-magic'                               => [
				'label'     => 'Magic',
				'category'  => [ 2 ],
				'font_code' => '\f0d0',
			],
			'fa-magnet'                              => [
				'label'     => 'Magnet',
				'category'  => [ 2 ],
				'font_code' => '\f076',
			],
			'fa-mail-forward'                        => [
				'label'     => 'Mail Forward',
				'category'  => [ 2 ],
				'font_code' => '\f064',
			],
			'fa-mail-reply'                          => [
				'label'     => 'Mail Reply',
				'category'  => [ 2 ],
				'font_code' => '\f112',
			],
			'fa-mail-reply-all'                      => [
				'label'     => 'Mail Reply All',
				'category'  => [ 2 ],
				'font_code' => '\f122',
			],
			'fa-male'                                => [
				'label'     => 'Male',
				'category'  => [ 2 ],
				'font_code' => '\f183',
			],
			'fa-map'                                 => [
				'label'     => 'Map',
				'category'  => [ 2 ],
				'font_code' => '\f279',
			],
			'fa-map-marker'                          => [
				'label'     => 'Map Marker',
				'category'  => [ 2 ],
				'font_code' => '\f041',
			],
			'fa-map-o'                               => [
				'label'     => 'Map <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f278',
			],
			'fa-map-pin'                             => [
				'label'     => 'Map Pin',
				'category'  => [ 2 ],
				'font_code' => '\f276',
			],
			'fa-map-signs'                           => [
				'label'     => 'Map Signs',
				'category'  => [ 2 ],
				'font_code' => '\f277',
			],
			'fa-meh-o'                               => [
				'label'     => 'Meh <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f11a',
			],
			'fa-microphone'                          => [
				'label'     => 'Microphone',
				'category'  => [ 2 ],
				'font_code' => '\f130',
			],
			'fa-microphone-slash'                    => [
				'label'     => 'Microphone Slash',
				'category'  => [ 2 ],
				'font_code' => '\f131',
			],
			'fa-minus'                               => [
				'label'     => 'Minus',
				'category'  => [ 2 ],
				'font_code' => '\f068',
			],
			'fa-minus-circle'                        => [
				'label'     => 'Minus Circle',
				'category'  => [ 2 ],
				'font_code' => '\f056',
			],
			'fa-minus-square'                        => [
				'label'     => 'Minus Square',
				'category'  => [ 2, 9 ],
				'font_code' => '\f146',
			],
			'fa-minus-square-o'                      => [
				'label'     => 'Minus Square <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 9 ],
				'font_code' => '\f147',
			],
			'fa-mobile'                              => [
				'label'     => 'Mobile',
				'category'  => [ 2 ],
				'font_code' => '\f10b',
			],
			'fa-mobile-phone'                        => [
				'label'     => 'Mobile Phone',
				'category'  => [ 2 ],
				'font_code' => '\f10b',
			],
			'fa-money'                               => [
				'label'     => 'Money',
				'category'  => [ 2, 12 ],
				'font_code' => '\f0d6',
			],
			'fa-moon-o'                              => [
				'label'     => 'Moon <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f186',
			],
			'fa-mortar-board'                        => [
				'label'     => 'Mortar Board',
				'category'  => [ 2 ],
				'font_code' => '\f19d',
			],
			'fa-motorcycle'                          => [
				'label'     => 'Motorcycle',
				'category'  => [ 2, 5 ],
				'font_code' => '\f21c',
			],
			'fa-mouse-pointer'                       => [
				'label'     => 'Mouse Pointer',
				'category'  => [ 2 ],
				'font_code' => '\f245',
			],
			'fa-music'                               => [
				'label'     => 'Music',
				'category'  => [ 2 ],
				'font_code' => '\f001',
			],
			'fa-navicon'                             => [
				'label'     => 'Navicon',
				'category'  => [ 2 ],
				'font_code' => '\f0c9',
			],
			'fa-newspaper-o'                         => [
				'label'     => 'Newspaper <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f1ea',
			],
			'fa-object-group'                        => [
				'label'     => 'Object Group',
				'category'  => [ 2 ],
				'font_code' => '\f247',
			],
			'fa-object-ungroup'                      => [
				'label'     => 'Object Ungroup',
				'category'  => [ 2 ],
				'font_code' => '\f248',
			],
			'fa-paint-brush'                         => [
				'label'     => 'Paint Brush',
				'category'  => [ 2 ],
				'font_code' => '\f1fc',
			],
			'fa-paper-plane'                         => [
				'label'     => 'Paper Plane',
				'category'  => [ 2 ],
				'font_code' => '\f1d8',
			],
			'fa-paper-plane-o'                       => [
				'label'     => 'Paper Plane <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f1d9',
			],
			'fa-paw'                                 => [
				'label'     => 'Paw',
				'category'  => [ 2 ],
				'font_code' => '\f1b0',
			],
			'fa-pencil'                              => [
				'label'     => 'Pencil',
				'category'  => [ 2 ],
				'font_code' => '\f040',
			],
			'fa-pencil-square'                       => [
				'label'     => 'Pencil Square',
				'category'  => [ 2 ],
				'font_code' => '\f14b',
			],
			'fa-pencil-square-o'                     => [
				'label'     => 'Pencil Square <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f044',
			],
			'fa-percent'                             => [
				'label'     => 'Percent',
				'category'  => [ 2 ],
				'font_code' => '\f295',
			],
			'fa-phone'                               => [
				'label'     => 'Phone',
				'category'  => [ 2 ],
				'font_code' => '\f095',
			],
			'fa-phone-square'                        => [
				'label'     => 'Phone Square',
				'category'  => [ 2 ],
				'font_code' => '\f098',
			],
			'fa-photo'                               => [
				'label'     => 'Photo',
				'category'  => [ 2 ],
				'font_code' => '\f03e',
			],
			'fa-picture-o'                           => [
				'label'     => 'Picture <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f03e',
			],
			'fa-pie-chart'                           => [
				'label'     => 'Pie Chart',
				'category'  => [ 2, 11 ],
				'font_code' => '\f200',
			],
			'fa-plane'                               => [
				'label'     => 'Plane',
				'category'  => [ 2, 5 ],
				'font_code' => '\f072',
			],
			'fa-plug'                                => [
				'label'     => 'Plug',
				'category'  => [ 2 ],
				'font_code' => '\f1e6',
			],
			'fa-plus'                                => [
				'label'     => 'Plus',
				'category'  => [ 2 ],
				'font_code' => '\f067',
			],
			'fa-plus-circle'                         => [
				'label'     => 'Plus Circle',
				'category'  => [ 2 ],
				'font_code' => '\f055',
			],
			'fa-plus-square'                         => [
				'label'     => 'Plus Square',
				'category'  => [ 2, 9, 17 ],
				'font_code' => '\f0fe',
			],
			'fa-plus-square-o'                       => [
				'label'     => 'Plus Square <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 9 ],
				'font_code' => '\f196',
			],
			'fa-power-off'                           => [
				'label'     => 'Power Off',
				'category'  => [ 2 ],
				'font_code' => '\f011',
			],
			'fa-print'                               => [
				'label'     => 'Print',
				'category'  => [ 2 ],
				'font_code' => '\f02f',
			],
			'fa-puzzle-piece'                        => [
				'label'     => 'Puzzle Piece',
				'category'  => [ 2 ],
				'font_code' => '\f12e',
			],
			'fa-qrcode'                              => [
				'label'     => 'Qrcode',
				'category'  => [ 2 ],
				'font_code' => '\f029',
			],
			'fa-question'                            => [
				'label'     => 'Question',
				'category'  => [ 2 ],
				'font_code' => '\f128',
			],
			'fa-question-circle'                     => [
				'label'     => 'Question Circle',
				'category'  => [ 2 ],
				'font_code' => '\f059',
			],
			'fa-question-circle-o'                   => [
				'label'     => 'Question Circle <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 3 ],
				'font_code' => '\f29c',
			],
			'fa-quote-left'                          => [
				'label'     => 'Quote Left',
				'category'  => [ 2 ],
				'font_code' => '\f10d',
			],
			'fa-quote-right'                         => [
				'label'     => 'Quote Right',
				'category'  => [ 2 ],
				'font_code' => '\f10e',
			],
			'fa-random'                              => [
				'label'     => 'Random',
				'category'  => [ 2, 15 ],
				'font_code' => '\f074',
			],
			'fa-recycle'                             => [
				'label'     => 'Recycle',
				'category'  => [ 2 ],
				'font_code' => '\f1b8',
			],
			'fa-refresh'                             => [
				'label'     => 'Refresh',
				'category'  => [ 2, 8 ],
				'font_code' => '\f021',
			],
			'fa-registered'                          => [
				'label'     => 'Registered',
				'category'  => [ 2 ],
				'font_code' => '\f25d',
			],
			'fa-remove'                              => [
				'label'     => 'Remove',
				'category'  => [ 2 ],
				'font_code' => '\f00d',
			],
			'fa-reorder'                             => [
				'label'     => 'Reorder',
				'category'  => [ 2 ],
				'font_code' => '\f0c9',
			],
			'fa-reply'                               => [
				'label'     => 'Reply',
				'category'  => [ 2 ],
				'font_code' => '\f112',
			],
			'fa-reply-all'                           => [
				'label'     => 'Reply All',
				'category'  => [ 2 ],
				'font_code' => '\f122',
			],
			'fa-retweet'                             => [
				'label'     => 'Retweet',
				'category'  => [ 2 ],
				'font_code' => '\f079',
			],
			'fa-road'                                => [
				'label'     => 'Road',
				'category'  => [ 2 ],
				'font_code' => '\f018',
			],
			'fa-rocket'                              => [
				'label'     => 'Rocket',
				'category'  => [ 2, 5 ],
				'font_code' => '\f135',
			],
			'fa-rss'                                 => [
				'label'     => 'Rss',
				'category'  => [ 2 ],
				'font_code' => '\f09e',
			],
			'fa-rss-square'                          => [
				'label'     => 'Rss Square',
				'category'  => [ 2 ],
				'font_code' => '\f143',
			],
			'fa-search'                              => [
				'label'     => 'Search',
				'category'  => [ 2 ],
				'font_code' => '\f002',
			],
			'fa-search-minus'                        => [
				'label'     => 'Search Minus',
				'category'  => [ 2 ],
				'font_code' => '\f010',
			],
			'fa-search-plus'                         => [
				'label'     => 'Search Plus',
				'category'  => [ 2 ],
				'font_code' => '\f00e',
			],
			'fa-send'                                => [
				'label'     => 'Send',
				'category'  => [ 2 ],
				'font_code' => '\f1d8',
			],
			'fa-send-o'                              => [
				'label'     => 'Send <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f1d9',
			],
			'fa-server'                              => [
				'label'     => 'Server',
				'category'  => [ 2 ],
				'font_code' => '\f233',
			],
			'fa-share'                               => [
				'label'     => 'Share',
				'category'  => [ 2 ],
				'font_code' => '\f064',
			],
			'fa-share-alt'                           => [
				'label'     => 'Share Alt',
				'category'  => [ 2, 16 ],
				'font_code' => '\f1e0',
			],
			'fa-share-alt-square'                    => [
				'label'     => 'Share Alt Square',
				'category'  => [ 2, 16 ],
				'font_code' => '\f1e1',
			],
			'fa-share-square'                        => [
				'label'     => 'Share Square',
				'category'  => [ 2 ],
				'font_code' => '\f14d',
			],
			'fa-share-square-o'                      => [
				'label'     => 'Share Square <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f045',
			],
			'fa-shield'                              => [
				'label'     => 'Shield',
				'category'  => [ 2 ],
				'font_code' => '\f132',
			],
			'fa-ship'                                => [
				'label'     => 'Ship',
				'category'  => [ 2, 5 ],
				'font_code' => '\f21a',
			],
			'fa-shopping-bag'                        => [
				'label'     => 'Shopping Bag',
				'category'  => [ 2 ],
				'font_code' => '\f290',
			],
			'fa-shopping-basket'                     => [
				'label'     => 'Shopping Basket',
				'category'  => [ 2 ],
				'font_code' => '\f291',
			],
			'fa-shopping-cart'                       => [
				'label'     => 'Shopping Cart',
				'category'  => [ 2 ],
				'font_code' => '\f07a',
			],
			'fa-sign-in'                             => [
				'label'     => 'Sign In',
				'category'  => [ 2 ],
				'font_code' => '\f090',
			],
			'fa-sign-language'                       => [
				'label'     => 'Sign Language',
				'category'  => [ 2, 3 ],
				'font_code' => '\f2a7',
			],
			'fa-sign-out'                            => [
				'label'     => 'Sign Out',
				'category'  => [ 2 ],
				'font_code' => '\f08b',
			],
			'fa-signal'                              => [
				'label'     => 'Signal',
				'category'  => [ 2 ],
				'font_code' => '\f012',
			],
			'fa-signing'                             => [
				'label'     => 'Signing',
				'category'  => [ 2, 3 ],
				'font_code' => '\f2a7',
			],
			'fa-sitemap'                             => [
				'label'     => 'Sitemap',
				'category'  => [ 2 ],
				'font_code' => '\f0e8',
			],
			'fa-sliders'                             => [
				'label'     => 'Sliders',
				'category'  => [ 2 ],
				'font_code' => '\f1de',
			],
			'fa-smile-o'                             => [
				'label'     => 'Smile <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f118',
			],
			'fa-soccer-ball-o'                       => [
				'label'     => 'Soccer Ball <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f1e3',
			],
			'fa-sort'                                => [
				'label'     => 'Sort',
				'category'  => [ 2 ],
				'font_code' => '\f0dc',
			],
			'fa-sort-alpha-asc'                      => [
				'label'     => 'Sort Alpha Asc',
				'category'  => [ 2 ],
				'font_code' => '\f15d',
			],
			'fa-sort-alpha-desc'                     => [
				'label'     => 'Sort Alpha Desc',
				'category'  => [ 2 ],
				'font_code' => '\f15e',
			],
			'fa-sort-amount-asc'                     => [
				'label'     => 'Sort Amount Asc',
				'category'  => [ 2 ],
				'font_code' => '\f160',
			],
			'fa-sort-amount-desc'                    => [
				'label'     => 'Sort Amount Desc',
				'category'  => [ 2 ],
				'font_code' => '\f161',
			],
			'fa-sort-asc'                            => [
				'label'     => 'Sort Asc',
				'category'  => [ 2 ],
				'font_code' => '\f0de',
			],
			'fa-sort-desc'                           => [
				'label'     => 'Sort Desc',
				'category'  => [ 2 ],
				'font_code' => '\f0dd',
			],
			'fa-sort-down'                           => [
				'label'     => 'Sort Down',
				'category'  => [ 2 ],
				'font_code' => '\f0dd',
			],
			'fa-sort-numeric-asc'                    => [
				'label'     => 'Sort Numeric Asc',
				'category'  => [ 2 ],
				'font_code' => '\f162',
			],
			'fa-sort-numeric-desc'                   => [
				'label'     => 'Sort Numeric Desc',
				'category'  => [ 2 ],
				'font_code' => '\f163',
			],
			'fa-sort-up'                             => [
				'label'     => 'Sort Up',
				'category'  => [ 2 ],
				'font_code' => '\f0de',
			],
			'fa-space-shuttle'                       => [
				'label'     => 'Space Shuttle',
				'category'  => [ 2, 5 ],
				'font_code' => '\f197',
			],
			'fa-spinner'                             => [
				'label'     => 'Spinner',
				'category'  => [ 2, 8 ],
				'font_code' => '\f110',
			],
			'fa-spoon'                               => [
				'label'     => 'Spoon',
				'category'  => [ 2 ],
				'font_code' => '\f1b1',
			],
			'fa-square'                              => [
				'label'     => 'Square',
				'category'  => [ 2, 9 ],
				'font_code' => '\f0c8',
			],
			'fa-square-o'                            => [
				'label'     => 'Square <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 9 ],
				'font_code' => '\f096',
			],
			'fa-star'                                => [
				'label'     => 'Star',
				'category'  => [ 2 ],
				'font_code' => '\f005',
			],
			'fa-star-half'                           => [
				'label'     => 'Star Half',
				'category'  => [ 2 ],
				'font_code' => '\f089',
			],
			'fa-star-half-empty'                     => [
				'label'     => 'Star Half Empty',
				'category'  => [ 2 ],
				'font_code' => '\f123',
			],
			'fa-star-half-full'                      => [
				'label'     => 'Star Half Full',
				'category'  => [ 2 ],
				'font_code' => '\f123',
			],
			'fa-star-half-o'                         => [
				'label'     => 'Star Half <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f123',
			],
			'fa-star-o'                              => [
				'label'     => 'Star <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f006',
			],
			'fa-sticky-note'                         => [
				'label'     => 'Sticky Note',
				'category'  => [ 2 ],
				'font_code' => '\f249',
			],
			'fa-sticky-note-o'                       => [
				'label'     => 'Sticky Note <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f24a',
			],
			'fa-street-view'                         => [
				'label'     => 'Street View',
				'category'  => [ 2 ],
				'font_code' => '\f21d',
			],
			'fa-suitcase'                            => [
				'label'     => 'Suitcase',
				'category'  => [ 2 ],
				'font_code' => '\f0f2',
			],
			'fa-sun-o'                               => [
				'label'     => 'Sun <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f185',
			],
			'fa-support'                             => [
				'label'     => 'Support',
				'category'  => [ 2 ],
				'font_code' => '\f1cd',
			],
			'fa-tablet'                              => [
				'label'     => 'Tablet',
				'category'  => [ 2 ],
				'font_code' => '\f10a',
			],
			'fa-tachometer'                          => [
				'label'     => 'Tachometer',
				'category'  => [ 2 ],
				'font_code' => '\f0e4',
			],
			'fa-tag'                                 => [
				'label'     => 'Tag',
				'category'  => [ 2 ],
				'font_code' => '\f02b',
			],
			'fa-tags'                                => [
				'label'     => 'Tags',
				'category'  => [ 2 ],
				'font_code' => '\f02c',
			],
			'fa-tasks'                               => [
				'label'     => 'Tasks',
				'category'  => [ 2 ],
				'font_code' => '\f0ae',
			],
			'fa-taxi'                                => [
				'label'     => 'Taxi',
				'category'  => [ 2, 5 ],
				'font_code' => '\f1ba',
			],
			'fa-television'                          => [
				'label'     => 'Television',
				'category'  => [ 2 ],
				'font_code' => '\f26c',
			],
			'fa-terminal'                            => [
				'label'     => 'Terminal',
				'category'  => [ 2 ],
				'font_code' => '\f120',
			],
			'fa-thumb-tack'                          => [
				'label'     => 'Thumb Tack',
				'category'  => [ 2 ],
				'font_code' => '\f08d',
			],
			'fa-thumbs-down'                         => [
				'label'     => 'Thumbs Down',
				'category'  => [ 2, 4 ],
				'font_code' => '\f165',
			],
			'fa-thumbs-o-down'                       => [
				'label'     => 'Thumbs Down <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 4 ],
				'font_code' => '\f088',
			],
			'fa-thumbs-o-up'                         => [
				'label'     => 'Thumbs Up <span class="text-muted">(Outline)</span>',
				'category'  => [ 2, 4 ],
				'font_code' => '\f087',
			],
			'fa-thumbs-up'                           => [
				'label'     => 'Thumbs Up',
				'category'  => [ 2, 4 ],
				'font_code' => '\f164',
			],
			'fa-ticket'                              => [
				'label'     => 'Ticket',
				'category'  => [ 2 ],
				'font_code' => '\f145',
			],
			'fa-times'                               => [
				'label'     => 'Times',
				'category'  => [ 2 ],
				'font_code' => '\f00d',
			],
			'fa-times-circle'                        => [
				'label'     => 'Times Circle',
				'category'  => [ 2 ],
				'font_code' => '\f057',
			],
			'fa-times-circle-o'                      => [
				'label'     => 'Times Circle <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f05c',
			],
			'fa-tint'                                => [
				'label'     => 'Tint',
				'category'  => [ 2 ],
				'font_code' => '\f043',
			],
			'fa-toggle-down'                         => [
				'label'     => 'Toggle Down',
				'category'  => [ 2, 14 ],
				'font_code' => '\f150',
			],
			'fa-toggle-left'                         => [
				'label'     => 'Toggle Left',
				'category'  => [ 2, 14 ],
				'font_code' => '\f191',
			],
			'fa-toggle-off'                          => [
				'label'     => 'Toggle Off',
				'category'  => [ 2 ],
				'font_code' => '\f204',
			],
			'fa-toggle-on'                           => [
				'label'     => 'Toggle On',
				'category'  => [ 2 ],
				'font_code' => '\f205',
			],
			'fa-toggle-right'                        => [
				'label'     => 'Toggle Right',
				'category'  => [ 2, 14 ],
				'font_code' => '\f152',
			],
			'fa-toggle-up'                           => [
				'label'     => 'Toggle Up',
				'category'  => [ 2, 14 ],
				'font_code' => '\f151',
			],
			'fa-trademark'                           => [
				'label'     => 'Trademark',
				'category'  => [ 2 ],
				'font_code' => '\f25c',
			],
			'fa-trash'                               => [
				'label'     => 'Trash',
				'category'  => [ 2 ],
				'font_code' => '\f1f8',
			],
			'fa-trash-o'                             => [
				'label'     => 'Trash <span class="text-muted">(Outline)</span>',
				'category'  => [ 2 ],
				'font_code' => '\f014',
			],
			'fa-tree'                                => [
				'label'     => 'Tree',
				'category'  => [ 2 ],
				'font_code' => '\f1bb',
			],
			'fa-trophy'                              => [
				'label'     => 'Trophy',
				'category'  => [ 2 ],
				'font_code' => '\f091',
			],
			'fa-truck'                               => [
				'label'     => 'Truck',
				'category'  => [ 2, 5 ],
				'font_code' => '\f0d1',
			],
			'fa-tty'                                 => [
				'label'     => 'Tty',
				'category'  => [ 2, 3 ],
				'font_code' => '\f1e4',
			],
			'fa-tv'                                  => [
				'label'     => 'Tv',
				'category'  => [ 2 ],
				'font_code' => '\f26c',
			],
			'fa-umbrella'                            => [
				'label'     => 'Umbrella',
				'category'  => [ 2 ],
				'font_code' => '\f0e9',
			],
			'fa-universal-access'                    => [
				'label'     => 'Universal Access',
				'category'  => [ 2, 3 ],
				'font_code' => '\f29a',
			],
			'fa-university'                          => [
				'label'     => 'University',
				'category'  => [ 2 ],
				'font_code' => '\f19c',
			],
			'fa-unlock'                              => [
				'label'     => 'Unlock',
				'category'  => [ 2 ],
				'font_code' => '\f09c',
			],
			'fa-unlock-alt'                          => [
				'label'     => 'Unlock Alt',
				'category'  => [ 2 ],
				'font_code' => '\f13e',
			],
			'fa-unsorted'                            => [
				'label'     => 'Unsorted',
				'category'  => [ 2 ],
				'font_code' => '\f0dc',
			],
			'fa-upload'                              => [
				'label'     => 'Upload',
				'category'  => [ 2 ],
				'font_code' => '\f093',
			],
			'fa-user'                                => [
				'label'     => 'User',
				'category'  => [ 2 ],
				'font_code' => '\f007',
			],
			'fa-user-plus'                           => [
				'label'     => 'User Plus',
				'category'  => [ 2 ],
				'font_code' => '\f234',
			],
			'fa-user-secret'                         => [
				'label'     => 'User Secret',
				'category'  => [ 2 ],
				'font_code' => '\f21b',
			],
			'fa-user-times'                          => [
				'label'     => 'User Times',
				'category'  => [ 2 ],
				'font_code' => '\f235',
			],
			'fa-users'                               => [
				'label'     => 'Users',
				'category'  => [ 2 ],
				'font_code' => '\f0c0',
			],
			'fa-video-camera'                        => [
				'label'     => 'Video Camera',
				'category'  => [ 2 ],
				'font_code' => '\f03d',
			],
			'fa-volume-control-phone'                => [
				'label'     => 'Volume Control Phone',
				'category'  => [ 2, 3 ],
				'font_code' => '\f2a0',
			],
			'fa-volume-down'                         => [
				'label'     => 'Volume Down',
				'category'  => [ 2 ],
				'font_code' => '\f027',
			],
			'fa-volume-off'                          => [
				'label'     => 'Volume Off',
				'category'  => [ 2 ],
				'font_code' => '\f026',
			],
			'fa-volume-up'                           => [
				'label'     => 'Volume Up',
				'category'  => [ 2 ],
				'font_code' => '\f028',
			],
			'fa-warning'                             => [
				'label'     => 'Warning',
				'category'  => [ 2 ],
				'font_code' => '\f071',
			],
			'fa-wheelchair'                          => [
				'label'     => 'Wheelchair',
				'category'  => [ 2, 3, 5, 17 ],
				'font_code' => '\f193',
			],
			'fa-wheelchair-alt'                      => [
				'label'     => 'Wheelchair Alt',
				'category'  => [ 2, 3, 5, 17 ],
				'font_code' => '\f29b',
			],
			'fa-wifi'                                => [
				'label'     => 'Wifi',
				'category'  => [ 2 ],
				'font_code' => '\f1eb',
			],
			'fa-wrench'                              => [
				'label'     => 'Wrench',
				'category'  => [ 2 ],
				'font_code' => '\f0ad',
			],
			'fa-hand-o-down'                         => [
				'label'     => 'Hand Down <span class="text-muted">(Outline)</span>',
				'category'  => [ 4, 14 ],
				'font_code' => '\f0a7',
			],
			'fa-hand-o-left'                         => [
				'label'     => 'Hand Left <span class="text-muted">(Outline)</span>',
				'category'  => [ 4, 14 ],
				'font_code' => '\f0a5',
			],
			'fa-hand-o-right'                        => [
				'label'     => 'Hand Right <span class="text-muted">(Outline)</span>',
				'category'  => [ 4, 14 ],
				'font_code' => '\f0a4',
			],
			'fa-hand-o-up'                           => [
				'label'     => 'Hand Up <span class="text-muted">(Outline)</span>',
				'category'  => [ 4, 14 ],
				'font_code' => '\f0a6',
			],
			'fa-ambulance'                           => [
				'label'     => 'Ambulance',
				'category'  => [ 5, 17 ],
				'font_code' => '\f0f9',
			],
			'fa-subway'                              => [
				'label'     => 'Subway',
				'category'  => [ 5 ],
				'font_code' => '\f239',
			],
			'fa-train'                               => [
				'label'     => 'Train',
				'category'  => [ 5 ],
				'font_code' => '\f238',
			],
			'fa-genderless'                          => [
				'label'     => 'Genderless',
				'category'  => [ 6 ],
				'font_code' => '\f22d',
			],
			'fa-intersex'                            => [
				'label'     => 'Intersex',
				'category'  => [ 6 ],
				'font_code' => '\f224',
			],
			'fa-mars'                                => [
				'label'     => 'Mars',
				'category'  => [ 6 ],
				'font_code' => '\f222',
			],
			'fa-mars-double'                         => [
				'label'     => 'Mars Double',
				'category'  => [ 6 ],
				'font_code' => '\f227',
			],
			'fa-mars-stroke'                         => [
				'label'     => 'Mars Stroke',
				'category'  => [ 6 ],
				'font_code' => '\f229',
			],
			'fa-mars-stroke-h'                       => [
				'label'     => 'Mars Stroke H',
				'category'  => [ 6 ],
				'font_code' => '\f22b',
			],
			'fa-mars-stroke-v'                       => [
				'label'     => 'Mars Stroke V',
				'category'  => [ 6 ],
				'font_code' => '\f22a',
			],
			'fa-mercury'                             => [
				'label'     => 'Mercury',
				'category'  => [ 6 ],
				'font_code' => '\f223',
			],
			'fa-neuter'                              => [
				'label'     => 'Neuter',
				'category'  => [ 6 ],
				'font_code' => '\f22c',
			],
			'fa-transgender'                         => [
				'label'     => 'Transgender',
				'category'  => [ 6 ],
				'font_code' => '\f224',
			],
			'fa-transgender-alt'                     => [
				'label'     => 'Transgender Alt',
				'category'  => [ 6 ],
				'font_code' => '\f225',
			],
			'fa-venus'                               => [
				'label'     => 'Venus',
				'category'  => [ 6 ],
				'font_code' => '\f221',
			],
			'fa-venus-double'                        => [
				'label'     => 'Venus Double',
				'category'  => [ 6 ],
				'font_code' => '\f226',
			],
			'fa-venus-mars'                          => [
				'label'     => 'Venus Mars',
				'category'  => [ 6 ],
				'font_code' => '\f228',
			],
			'fa-file'                                => [
				'label'     => 'File',
				'category'  => [ 7, 13 ],
				'font_code' => '\f15b',
			],
			'fa-file-o'                              => [
				'label'     => 'File <span class="text-muted">(Outline)</span>',
				'category'  => [ 7, 13 ],
				'font_code' => '\f016',
			],
			'fa-file-text'                           => [
				'label'     => 'File Text',
				'category'  => [ 7, 13 ],
				'font_code' => '\f15c',
			],
			'fa-file-text-o'                         => [
				'label'     => 'File Text <span class="text-muted">(Outline)</span>',
				'category'  => [ 7, 13 ],
				'font_code' => '\f0f6',
			],
			'fa-cc-amex'                             => [
				'label'     => 'Cc Amex',
				'category'  => [ 10, 16 ],
				'font_code' => '\f1f3',
			],
			'fa-cc-diners-club'                      => [
				'label'     => 'Cc Diners Club',
				'category'  => [ 10, 16 ],
				'font_code' => '\f24c',
			],
			'fa-cc-discover'                         => [
				'label'     => 'Cc Discover',
				'category'  => [ 10, 16 ],
				'font_code' => '\f1f2',
			],
			'fa-cc-jcb'                              => [
				'label'     => 'Cc Jcb',
				'category'  => [ 10, 16 ],
				'font_code' => '\f24b',
			],
			'fa-cc-mastercard'                       => [
				'label'     => 'Cc Mastercard',
				'category'  => [ 10, 16 ],
				'font_code' => '\f1f1',
			],
			'fa-cc-paypal'                           => [
				'label'     => 'Cc Paypal',
				'category'  => [ 10, 16 ],
				'font_code' => '\f1f4',
			],
			'fa-cc-stripe'                           => [
				'label'     => 'Cc Stripe',
				'category'  => [ 10, 16 ],
				'font_code' => '\f1f5',
			],
			'fa-cc-visa'                             => [
				'label'     => 'Cc Visa',
				'category'  => [ 10, 16 ],
				'font_code' => '\f1f0',
			],
			'fa-google-wallet'                       => [
				'label'     => 'Google Wallet',
				'category'  => [ 10, 16 ],
				'font_code' => '\f1ee',
			],
			'fa-paypal'                              => [
				'label'     => 'Paypal',
				'category'  => [ 10, 16 ],
				'font_code' => '\f1ed',
			],
			'fa-bitcoin'                             => [
				'label'     => 'Bitcoin',
				'category'  => [ 12, 16 ],
				'font_code' => '\f15a',
			],
			'fa-btc'                                 => [
				'label'     => 'Btc',
				'category'  => [ 12, 16 ],
				'font_code' => '\f15a',
			],
			'fa-cny'                                 => [
				'label'     => 'Cny',
				'category'  => [ 12 ],
				'font_code' => '\f157',
			],
			'fa-dollar'                              => [
				'label'     => 'Dollar',
				'category'  => [ 12 ],
				'font_code' => '\f155',
			],
			'fa-eur'                                 => [
				'label'     => 'Eur',
				'category'  => [ 12 ],
				'font_code' => '\f153',
			],
			'fa-euro'                                => [
				'label'     => 'Euro',
				'category'  => [ 12 ],
				'font_code' => '\f153',
			],
			'fa-gbp'                                 => [
				'label'     => 'Gbp',
				'category'  => [ 12 ],
				'font_code' => '\f154',
			],
			'fa-gg'                                  => [
				'label'     => 'Gg',
				'category'  => [ 12, 16 ],
				'font_code' => '\f260',
			],
			'fa-gg-circle'                           => [
				'label'     => 'Gg Circle',
				'category'  => [ 12, 16 ],
				'font_code' => '\f261',
			],
			'fa-ils'                                 => [
				'label'     => 'Ils',
				'category'  => [ 12 ],
				'font_code' => '\f20b',
			],
			'fa-inr'                                 => [
				'label'     => 'Inr',
				'category'  => [ 12 ],
				'font_code' => '\f156',
			],
			'fa-jpy'                                 => [
				'label'     => 'Jpy',
				'category'  => [ 12 ],
				'font_code' => '\f157',
			],
			'fa-krw'                                 => [
				'label'     => 'Krw',
				'category'  => [ 12 ],
				'font_code' => '\f159',
			],
			'fa-rmb'                                 => [
				'label'     => 'Rmb',
				'category'  => [ 12 ],
				'font_code' => '\f157',
			],
			'fa-rouble'                              => [
				'label'     => 'Rouble',
				'category'  => [ 12 ],
				'font_code' => '\f158',
			],
			'fa-rub'                                 => [
				'label'     => 'Rub',
				'category'  => [ 12 ],
				'font_code' => '\f158',
			],
			'fa-ruble'                               => [
				'label'     => 'Ruble',
				'category'  => [ 12 ],
				'font_code' => '\f158',
			],
			'fa-rupee'                               => [
				'label'     => 'Rupee',
				'category'  => [ 12 ],
				'font_code' => '\f156',
			],
			'fa-shekel'                              => [
				'label'     => 'Shekel',
				'category'  => [ 12 ],
				'font_code' => '\f20b',
			],
			'fa-sheqel'                              => [
				'label'     => 'Sheqel',
				'category'  => [ 12 ],
				'font_code' => '\f20b',
			],
			'fa-try'                                 => [
				'label'     => 'Try',
				'category'  => [ 12 ],
				'font_code' => '\f195',
			],
			'fa-turkish-lira'                        => [
				'label'     => 'Turkish Lira',
				'category'  => [ 12 ],
				'font_code' => '\f195',
			],
			'fa-usd'                                 => [
				'label'     => 'Usd',
				'category'  => [ 12 ],
				'font_code' => '\f155',
			],
			'fa-won'                                 => [
				'label'     => 'Won',
				'category'  => [ 12 ],
				'font_code' => '\f159',
			],
			'fa-yen'                                 => [
				'label'     => 'Yen',
				'category'  => [ 12 ],
				'font_code' => '\f157',
			],
			'fa-align-center'                        => [
				'label'     => 'Align Center',
				'category'  => [ 13 ],
				'font_code' => '\f037',
			],
			'fa-align-justify'                       => [
				'label'     => 'Align Justify',
				'category'  => [ 13 ],
				'font_code' => '\f039',
			],
			'fa-align-left'                          => [
				'label'     => 'Align Left',
				'category'  => [ 13 ],
				'font_code' => '\f036',
			],
			'fa-align-right'                         => [
				'label'     => 'Align Right',
				'category'  => [ 13 ],
				'font_code' => '\f038',
			],
			'fa-bold'                                => [
				'label'     => 'Bold',
				'category'  => [ 13 ],
				'font_code' => '\f032',
			],
			'fa-chain'                               => [
				'label'     => 'Chain',
				'category'  => [ 13 ],
				'font_code' => '\f0c1',
			],
			'fa-chain-broken'                        => [
				'label'     => 'Chain Broken',
				'category'  => [ 13 ],
				'font_code' => '\f127',
			],
			'fa-clipboard'                           => [
				'label'     => 'Clipboard',
				'category'  => [ 13 ],
				'font_code' => '\f0ea',
			],
			'fa-columns'                             => [
				'label'     => 'Columns',
				'category'  => [ 13 ],
				'font_code' => '\f0db',
			],
			'fa-copy'                                => [
				'label'     => 'Copy',
				'category'  => [ 13 ],
				'font_code' => '\f0c5',
			],
			'fa-cut'                                 => [
				'label'     => 'Cut',
				'category'  => [ 13 ],
				'font_code' => '\f0c4',
			],
			'fa-dedent'                              => [
				'label'     => 'Dedent',
				'category'  => [ 13 ],
				'font_code' => '\f03b',
			],
			'fa-files-o'                             => [
				'label'     => 'Files <span class="text-muted">(Outline)</span>',
				'category'  => [ 13 ],
				'font_code' => '\f0c5',
			],
			'fa-floppy-o'                            => [
				'label'     => 'Floppy <span class="text-muted">(Outline)</span>',
				'category'  => [ 13 ],
				'font_code' => '\f0c7',
			],
			'fa-font'                                => [
				'label'     => 'Font',
				'category'  => [ 13 ],
				'font_code' => '\f031',
			],
			'fa-header'                              => [
				'label'     => 'Header',
				'category'  => [ 13 ],
				'font_code' => '\f1dc',
			],
			'fa-indent'                              => [
				'label'     => 'Indent',
				'category'  => [ 13 ],
				'font_code' => '\f03c',
			],
			'fa-italic'                              => [
				'label'     => 'Italic',
				'category'  => [ 13 ],
				'font_code' => '\f033',
			],
			'fa-link'                                => [
				'label'     => 'Link',
				'category'  => [ 13 ],
				'font_code' => '\f0c1',
			],
			'fa-list'                                => [
				'label'     => 'List',
				'category'  => [ 13 ],
				'font_code' => '\f03a',
			],
			'fa-list-alt'                            => [
				'label'     => 'List Alt',
				'category'  => [ 13 ],
				'font_code' => '\f022',
			],
			'fa-list-ol'                             => [
				'label'     => 'List Ol',
				'category'  => [ 13 ],
				'font_code' => '\f0cb',
			],
			'fa-list-ul'                             => [
				'label'     => 'List Ul',
				'category'  => [ 13 ],
				'font_code' => '\f0ca',
			],
			'fa-outdent'                             => [
				'label'     => 'Outdent',
				'category'  => [ 13 ],
				'font_code' => '\f03b',
			],
			'fa-paperclip'                           => [
				'label'     => 'Paperclip',
				'category'  => [ 13 ],
				'font_code' => '\f0c6',
			],
			'fa-paragraph'                           => [
				'label'     => 'Paragraph',
				'category'  => [ 13 ],
				'font_code' => '\f1dd',
			],
			'fa-paste'                               => [
				'label'     => 'Paste',
				'category'  => [ 13 ],
				'font_code' => '\f0ea',
			],
			'fa-repeat'                              => [
				'label'     => 'Repeat',
				'category'  => [ 13 ],
				'font_code' => '\f01e',
			],
			'fa-rotate-left'                         => [
				'label'     => 'Rotate Left',
				'category'  => [ 13 ],
				'font_code' => '\f0e2',
			],
			'fa-rotate-right'                        => [
				'label'     => 'Rotate Right',
				'category'  => [ 13 ],
				'font_code' => '\f01e',
			],
			'fa-save'                                => [
				'label'     => 'Save',
				'category'  => [ 13 ],
				'font_code' => '\f0c7',
			],
			'fa-scissors'                            => [
				'label'     => 'Scissors',
				'category'  => [ 13 ],
				'font_code' => '\f0c4',
			],
			'fa-strikethrough'                       => [
				'label'     => 'Strikethrough',
				'category'  => [ 13 ],
				'font_code' => '\f0cc',
			],
			'fa-subscript'                           => [
				'label'     => 'Subscript',
				'category'  => [ 13 ],
				'font_code' => '\f12c',
			],
			'fa-superscript'                         => [
				'label'     => 'Superscript',
				'category'  => [ 13 ],
				'font_code' => '\f12b',
			],
			'fa-table'                               => [
				'label'     => 'Table',
				'category'  => [ 13 ],
				'font_code' => '\f0ce',
			],
			'fa-text-height'                         => [
				'label'     => 'Text Height',
				'category'  => [ 13 ],
				'font_code' => '\f034',
			],
			'fa-text-width'                          => [
				'label'     => 'Text Width',
				'category'  => [ 13 ],
				'font_code' => '\f035',
			],
			'fa-th'                                  => [
				'label'     => 'Th',
				'category'  => [ 13 ],
				'font_code' => '\f00a',
			],
			'fa-th-large'                            => [
				'label'     => 'Th Large',
				'category'  => [ 13 ],
				'font_code' => '\f009',
			],
			'fa-th-list'                             => [
				'label'     => 'Th List',
				'category'  => [ 13 ],
				'font_code' => '\f00b',
			],
			'fa-underline'                           => [
				'label'     => 'Underline',
				'category'  => [ 13 ],
				'font_code' => '\f0cd',
			],
			'fa-undo'                                => [
				'label'     => 'Undo',
				'category'  => [ 13 ],
				'font_code' => '\f0e2',
			],
			'fa-unlink'                              => [
				'label'     => 'Unlink',
				'category'  => [ 13 ],
				'font_code' => '\f127',
			],
			'fa-angle-double-down'                   => [
				'label'     => 'Angle Double Down',
				'category'  => [ 14 ],
				'font_code' => '\f103',
			],
			'fa-angle-double-left'                   => [
				'label'     => 'Angle Double Left',
				'category'  => [ 14 ],
				'font_code' => '\f100',
			],
			'fa-angle-double-right'                  => [
				'label'     => 'Angle Double Right',
				'category'  => [ 14 ],
				'font_code' => '\f101',
			],
			'fa-angle-double-up'                     => [
				'label'     => 'Angle Double Up',
				'category'  => [ 14 ],
				'font_code' => '\f102',
			],
			'fa-angle-down'                          => [
				'label'     => 'Angle Down',
				'category'  => [ 14 ],
				'font_code' => '\f107',
			],
			'fa-angle-left'                          => [
				'label'     => 'Angle Left',
				'category'  => [ 14 ],
				'font_code' => '\f104',
			],
			'fa-angle-right'                         => [
				'label'     => 'Angle Right',
				'category'  => [ 14 ],
				'font_code' => '\f105',
			],
			'fa-angle-up'                            => [
				'label'     => 'Angle Up',
				'category'  => [ 14 ],
				'font_code' => '\f106',
			],
			'fa-arrow-circle-down'                   => [
				'label'     => 'Arrow Circle Down',
				'category'  => [ 14 ],
				'font_code' => '\f0ab',
			],
			'fa-arrow-circle-left'                   => [
				'label'     => 'Arrow Circle Left',
				'category'  => [ 14 ],
				'font_code' => '\f0a8',
			],
			'fa-arrow-circle-o-down'                 => [
				'label'     => 'Arrow Circle Down <span class="text-muted">(Outline)</span>',
				'category'  => [ 14 ],
				'font_code' => '\f01a',
			],
			'fa-arrow-circle-o-left'                 => [
				'label'     => 'Arrow Circle Left <span class="text-muted">(Outline)</span>',
				'category'  => [ 14 ],
				'font_code' => '\f190',
			],
			'fa-arrow-circle-o-right'                => [
				'label'     => 'Arrow Circle Right <span class="text-muted">(Outline)</span>',
				'category'  => [ 14 ],
				'font_code' => '\f18e',
			],
			'fa-arrow-circle-o-up'                   => [
				'label'     => 'Arrow Circle Up <span class="text-muted">(Outline)</span>',
				'category'  => [ 14 ],
				'font_code' => '\f01b',
			],
			'fa-arrow-circle-right'                  => [
				'label'     => 'Arrow Circle Right',
				'category'  => [ 14 ],
				'font_code' => '\f0a9',
			],
			'fa-arrow-circle-up'                     => [
				'label'     => 'Arrow Circle Up',
				'category'  => [ 14 ],
				'font_code' => '\f0aa',
			],
			'fa-arrow-down'                          => [
				'label'     => 'Arrow Down',
				'category'  => [ 14 ],
				'font_code' => '\f063',
			],
			'fa-arrow-left'                          => [
				'label'     => 'Arrow Left',
				'category'  => [ 14 ],
				'font_code' => '\f060',
			],
			'fa-arrow-right'                         => [
				'label'     => 'Arrow Right',
				'category'  => [ 14 ],
				'font_code' => '\f061',
			],
			'fa-arrow-up'                            => [
				'label'     => 'Arrow Up',
				'category'  => [ 14 ],
				'font_code' => '\f062',
			],
			'fa-arrows-alt'                          => [
				'label'     => 'Arrows Alt',
				'category'  => [ 14, 15 ],
				'font_code' => '\f0b2',
			],
			'fa-caret-down'                          => [
				'label'     => 'Caret Down',
				'category'  => [ 14 ],
				'font_code' => '\f0d7',
			],
			'fa-caret-left'                          => [
				'label'     => 'Caret Left',
				'category'  => [ 14 ],
				'font_code' => '\f0d9',
			],
			'fa-caret-right'                         => [
				'label'     => 'Caret Right',
				'category'  => [ 14 ],
				'font_code' => '\f0da',
			],
			'fa-caret-up'                            => [
				'label'     => 'Caret Up',
				'category'  => [ 14 ],
				'font_code' => '\f0d8',
			],
			'fa-chevron-circle-down'                 => [
				'label'     => 'Chevron Circle Down',
				'category'  => [ 14 ],
				'font_code' => '\f13a',
			],
			'fa-chevron-circle-left'                 => [
				'label'     => 'Chevron Circle Left',
				'category'  => [ 14 ],
				'font_code' => '\f137',
			],
			'fa-chevron-circle-right'                => [
				'label'     => 'Chevron Circle Right',
				'category'  => [ 14 ],
				'font_code' => '\f138',
			],
			'fa-chevron-circle-up'                   => [
				'label'     => 'Chevron Circle Up',
				'category'  => [ 14 ],
				'font_code' => '\f139',
			],
			'fa-chevron-down'                        => [
				'label'     => 'Chevron Down',
				'category'  => [ 14 ],
				'font_code' => '\f078',
			],
			'fa-chevron-left'                        => [
				'label'     => 'Chevron Left',
				'category'  => [ 14 ],
				'font_code' => '\f053',
			],
			'fa-chevron-right'                       => [
				'label'     => 'Chevron Right',
				'category'  => [ 14 ],
				'font_code' => '\f054',
			],
			'fa-chevron-up'                          => [
				'label'     => 'Chevron Up',
				'category'  => [ 14 ],
				'font_code' => '\f077',
			],
			'fa-long-arrow-down'                     => [
				'label'     => 'Long Arrow Down',
				'category'  => [ 14 ],
				'font_code' => '\f175',
			],
			'fa-long-arrow-left'                     => [
				'label'     => 'Long Arrow Left',
				'category'  => [ 14 ],
				'font_code' => '\f177',
			],
			'fa-long-arrow-right'                    => [
				'label'     => 'Long Arrow Right',
				'category'  => [ 14 ],
				'font_code' => '\f178',
			],
			'fa-long-arrow-up'                       => [
				'label'     => 'Long Arrow Up',
				'category'  => [ 14 ],
				'font_code' => '\f176',
			],
			'fa-backward'                            => [
				'label'     => 'Backward',
				'category'  => [ 15 ],
				'font_code' => '\f04a',
			],
			'fa-compress'                            => [
				'label'     => 'Compress',
				'category'  => [ 15 ],
				'font_code' => '\f066',
			],
			'fa-eject'                               => [
				'label'     => 'Eject',
				'category'  => [ 15 ],
				'font_code' => '\f052',
			],
			'fa-expand'                              => [
				'label'     => 'Expand',
				'category'  => [ 15 ],
				'font_code' => '\f065',
			],
			'fa-fast-backward'                       => [
				'label'     => 'Fast Backward',
				'category'  => [ 15 ],
				'font_code' => '\f049',
			],
			'fa-fast-forward'                        => [
				'label'     => 'Fast Forward',
				'category'  => [ 15 ],
				'font_code' => '\f050',
			],
			'fa-forward'                             => [
				'label'     => 'Forward',
				'category'  => [ 15 ],
				'font_code' => '\f04e',
			],
			'fa-pause'                               => [
				'label'     => 'Pause',
				'category'  => [ 15 ],
				'font_code' => '\f04c',
			],
			'fa-pause-circle'                        => [
				'label'     => 'Pause Circle',
				'category'  => [ 15 ],
				'font_code' => '\f28b',
			],
			'fa-pause-circle-o'                      => [
				'label'     => 'Pause Circle <span class="text-muted">(Outline)</span>',
				'category'  => [ 15 ],
				'font_code' => '\f28c',
			],
			'fa-play'                                => [
				'label'     => 'Play',
				'category'  => [ 15 ],
				'font_code' => '\f04b',
			],
			'fa-play-circle'                         => [
				'label'     => 'Play Circle',
				'category'  => [ 15 ],
				'font_code' => '\f144',
			],
			'fa-play-circle-o'                       => [
				'label'     => 'Play Circle <span class="text-muted">(Outline)</span>',
				'category'  => [ 15 ],
				'font_code' => '\f01d',
			],
			'fa-step-backward'                       => [
				'label'     => 'Step Backward',
				'category'  => [ 15 ],
				'font_code' => '\f048',
			],
			'fa-step-forward'                        => [
				'label'     => 'Step Forward',
				'category'  => [ 15 ],
				'font_code' => '\f051',
			],
			'fa-stop'                                => [
				'label'     => 'Stop',
				'category'  => [ 15 ],
				'font_code' => '\f04d',
			],
			'fa-stop-circle'                         => [
				'label'     => 'Stop Circle',
				'category'  => [ 15 ],
				'font_code' => '\f28d',
			],
			'fa-stop-circle-o'                       => [
				'label'     => 'Stop Circle <span class="text-muted">(Outline)</span>',
				'category'  => [ 15 ],
				'font_code' => '\f28e',
			],
			'fa-youtube-play'                        => [
				'label'     => 'Youtube Play',
				'category'  => [ 15, 16 ],
				'font_code' => '\f16a',
			],
			'fa-500px'                               => [
				'label'     => '500px',
				'category'  => [ 16 ],
				'font_code' => '\f26e',
			],
			'fa-adn'                                 => [
				'label'     => 'Adn',
				'category'  => [ 16 ],
				'font_code' => '\f170',
			],
			'fa-amazon'                              => [
				'label'     => 'Amazon',
				'category'  => [ 16 ],
				'font_code' => '\f270',
			],
			'fa-android'                             => [
				'label'     => 'Android',
				'category'  => [ 16 ],
				'font_code' => '\f17b',
			],
			'fa-angellist'                           => [
				'label'     => 'Angellist',
				'category'  => [ 16 ],
				'font_code' => '\f209',
			],
			'fa-apple'                               => [
				'label'     => 'Apple',
				'category'  => [ 16 ],
				'font_code' => '\f179',
			],
			'fa-behance'                             => [
				'label'     => 'Behance',
				'category'  => [ 16 ],
				'font_code' => '\f1b4',
			],
			'fa-behance-square'                      => [
				'label'     => 'Behance Square',
				'category'  => [ 16 ],
				'font_code' => '\f1b5',
			],
			'fa-bitbucket'                           => [
				'label'     => 'Bitbucket',
				'category'  => [ 16 ],
				'font_code' => '\f171',
			],
			'fa-bitbucket-square'                    => [
				'label'     => 'Bitbucket Square',
				'category'  => [ 16 ],
				'font_code' => '\f172',
			],
			'fa-black-tie'                           => [
				'label'     => 'Black Tie',
				'category'  => [ 16 ],
				'font_code' => '\f27e',
			],
			'fa-buysellads'                          => [
				'label'     => 'Buysellads',
				'category'  => [ 16 ],
				'font_code' => '\f20d',
			],
			'fa-chrome'                              => [
				'label'     => 'Chrome',
				'category'  => [ 16 ],
				'font_code' => '\f268',
			],
			'fa-codepen'                             => [
				'label'     => 'Codepen',
				'category'  => [ 16 ],
				'font_code' => '\f1cb',
			],
			'fa-codiepie'                            => [
				'label'     => 'Codiepie',
				'category'  => [ 16 ],
				'font_code' => '\f284',
			],
			'fa-connectdevelop'                      => [
				'label'     => 'Connectdevelop',
				'category'  => [ 16 ],
				'font_code' => '\f20e',
			],
			'fa-contao'                              => [
				'label'     => 'Contao',
				'category'  => [ 16 ],
				'font_code' => '\f26d',
			],
			'fa-css3'                                => [
				'label'     => 'Css3',
				'category'  => [ 16 ],
				'font_code' => '\f13c',
			],
			'fa-dashcube'                            => [
				'label'     => 'Dashcube',
				'category'  => [ 16 ],
				'font_code' => '\f210',
			],
			'fa-delicious'                           => [
				'label'     => 'Delicious',
				'category'  => [ 16 ],
				'font_code' => '\f1a5',
			],
			'fa-deviantart'                          => [
				'label'     => 'Deviantart',
				'category'  => [ 16 ],
				'font_code' => '\f1bd',
			],
			'fa-digg'                                => [
				'label'     => 'Digg',
				'category'  => [ 16 ],
				'font_code' => '\f1a6',
			],
			'fa-dribbble'                            => [
				'label'     => 'Dribbble',
				'category'  => [ 16 ],
				'font_code' => '\f17d',
			],
			'fa-dropbox'                             => [
				'label'     => 'Dropbox',
				'category'  => [ 16 ],
				'font_code' => '\f16b',
			],
			'fa-drupal'                              => [
				'label'     => 'Drupal',
				'category'  => [ 16 ],
				'font_code' => '\f1a9',
			],
			'fa-edge'                                => [
				'label'     => 'Edge',
				'category'  => [ 16 ],
				'font_code' => '\f282',
			],
			'fa-empire'                              => [
				'label'     => 'Empire',
				'category'  => [ 16 ],
				'font_code' => '\f1d1',
			],
			'fa-envira'                              => [
				'label'     => 'Envira',
				'category'  => [ 16 ],
				'font_code' => '\f299',
			],
			'fa-expeditedssl'                        => [
				'label'     => 'Expeditedssl',
				'category'  => [ 16 ],
				'font_code' => '\f23e',
			],
			'fa-fa'                                  => [
				'label'     => 'Fa',
				'category'  => [ 16 ],
				'font_code' => '\f2b4',
			],
			'fa-facebook'                            => [
				'label'     => 'Facebook',
				'category'  => [ 16 ],
				'font_code' => '\f09a',
			],
			'fa-facebook-f'                          => [
				'label'     => 'Facebook F',
				'category'  => [ 16 ],
				'font_code' => '\f09a',
			],
			'fa-facebook-official'                   => [
				'label'     => 'Facebook Official',
				'category'  => [ 16 ],
				'font_code' => '\f230',
			],
			'fa-facebook-square'                     => [
				'label'     => 'Facebook Square',
				'category'  => [ 16 ],
				'font_code' => '\f082',
			],
			'fa-firefox'                             => [
				'label'     => 'Firefox',
				'category'  => [ 16 ],
				'font_code' => '\f269',
			],
			'fa-first-order'                         => [
				'label'     => 'First Order',
				'category'  => [ 16 ],
				'font_code' => '\f2b0',
			],
			'fa-flickr'                              => [
				'label'     => 'Flickr',
				'category'  => [ 16 ],
				'font_code' => '\f16e',
			],
			'fa-font-awesome'                        => [
				'label'     => 'Font Awesome',
				'category'  => [ 16 ],
				'font_code' => '\f2b4',
			],
			'fa-fonticons'                           => [
				'label'     => 'Fonticons',
				'category'  => [ 16 ],
				'font_code' => '\f280',
			],
			'fa-fort-awesome'                        => [
				'label'     => 'Fort Awesome',
				'category'  => [ 16 ],
				'font_code' => '\f286',
			],
			'fa-forumbee'                            => [
				'label'     => 'Forumbee',
				'category'  => [ 16 ],
				'font_code' => '\f211',
			],
			'fa-foursquare'                          => [
				'label'     => 'Foursquare',
				'category'  => [ 16 ],
				'font_code' => '\f180',
			],
			'fa-ge'                                  => [
				'label'     => 'Ge',
				'category'  => [ 16 ],
				'font_code' => '\f1d1',
			],
			'fa-get-pocket'                          => [
				'label'     => 'Get Pocket',
				'category'  => [ 16 ],
				'font_code' => '\f265',
			],
			'fa-git'                                 => [
				'label'     => 'Git',
				'category'  => [ 16 ],
				'font_code' => '\f1d3',
			],
			'fa-git-square'                          => [
				'label'     => 'Git Square',
				'category'  => [ 16 ],
				'font_code' => '\f1d2',
			],
			'fa-github'                              => [
				'label'     => 'Github',
				'category'  => [ 16 ],
				'font_code' => '\f09b',
			],
			'fa-github-alt'                          => [
				'label'     => 'Github Alt',
				'category'  => [ 16 ],
				'font_code' => '\f113',
			],
			'fa-github-square'                       => [
				'label'     => 'Github Square',
				'category'  => [ 16 ],
				'font_code' => '\f092',
			],
			'fa-gitlab'                              => [
				'label'     => 'Gitlab',
				'category'  => [ 16 ],
				'font_code' => '\f296',
			],
			'fa-gittip'                              => [
				'label'     => 'Gittip',
				'category'  => [ 16 ],
				'font_code' => '\f184',
			],
			'fa-glide'                               => [
				'label'     => 'Glide',
				'category'  => [ 16 ],
				'font_code' => '\f2a5',
			],
			'fa-glide-g'                             => [
				'label'     => 'Glide G',
				'category'  => [ 16 ],
				'font_code' => '\f2a6',
			],
			'fa-google'                              => [
				'label'     => 'Google',
				'category'  => [ 16 ],
				'font_code' => '\f1a0',
			],
			'fa-google-plus'                         => [
				'label'     => 'Google Plus',
				'category'  => [ 16 ],
				'font_code' => '\f0d5',
			],
			'fa-google-plus-circle'                  => [
				'label'     => 'Google Plus Circle',
				'category'  => [ 16 ],
				'font_code' => '\f2b3',
			],
			'fa-google-plus-official'                => [
				'label'     => 'Google Plus Official',
				'category'  => [ 16 ],
				'font_code' => '\f2b3',
			],
			'fa-google-plus-square'                  => [
				'label'     => 'Google Plus Square',
				'category'  => [ 16 ],
				'font_code' => '\f0d4',
			],
			'fa-gratipay'                            => [
				'label'     => 'Gratipay',
				'category'  => [ 16 ],
				'font_code' => '\f184',
			],
			'fa-hacker-news'                         => [
				'label'     => 'Hacker News',
				'category'  => [ 16 ],
				'font_code' => '\f1d4',
			],
			'fa-houzz'                               => [
				'label'     => 'Houzz',
				'category'  => [ 16 ],
				'font_code' => '\f27c',
			],
			'fa-html5'                               => [
				'label'     => 'Html5',
				'category'  => [ 16 ],
				'font_code' => '\f13b',
			],
			'fa-instagram'                           => [
				'label'     => 'Instagram',
				'category'  => [ 16 ],
				'font_code' => '\f16d',
			],
			'fa-internet-explorer'                   => [
				'label'     => 'Internet Explorer',
				'category'  => [ 16 ],
				'font_code' => '\f26b',
			],
			'fa-ioxhost'                             => [
				'label'     => 'Ioxhost',
				'category'  => [ 16 ],
				'font_code' => '\f208',
			],
			'fa-joomla'                              => [
				'label'     => 'Joomla',
				'category'  => [ 16 ],
				'font_code' => '\f1aa',
			],
			'fa-jsfiddle'                            => [
				'label'     => 'Jsfiddle',
				'category'  => [ 16 ],
				'font_code' => '\f1cc',
			],
			'fa-lastfm'                              => [
				'label'     => 'Lastfm',
				'category'  => [ 16 ],
				'font_code' => '\f202',
			],
			'fa-lastfm-square'                       => [
				'label'     => 'Lastfm Square',
				'category'  => [ 16 ],
				'font_code' => '\f203',
			],
			'fa-leanpub'                             => [
				'label'     => 'Leanpub',
				'category'  => [ 16 ],
				'font_code' => '\f212',
			],
			'fa-linkedin'                            => [
				'label'     => 'Linkedin',
				'category'  => [ 16 ],
				'font_code' => '\f0e1',
			],
			'fa-linkedin-square'                     => [
				'label'     => 'Linkedin Square',
				'category'  => [ 16 ],
				'font_code' => '\f08c',
			],
			'fa-linux'                               => [
				'label'     => 'Linux',
				'category'  => [ 16 ],
				'font_code' => '\f17c',
			],
			'fa-maxcdn'                              => [
				'label'     => 'Maxcdn',
				'category'  => [ 16 ],
				'font_code' => '\f136',
			],
			'fa-meanpath'                            => [
				'label'     => 'Meanpath',
				'category'  => [ 16 ],
				'font_code' => '\f20c',
			],
			'fa-medium'                              => [
				'label'     => 'Medium',
				'category'  => [ 16 ],
				'font_code' => '\f23a',
			],
			'fa-mixcloud'                            => [
				'label'     => 'Mixcloud',
				'category'  => [ 16 ],
				'font_code' => '\f289',
			],
			'fa-modx'                                => [
				'label'     => 'Modx',
				'category'  => [ 16 ],
				'font_code' => '\f285',
			],
			'fa-odnoklassniki'                       => [
				'label'     => 'Odnoklassniki',
				'category'  => [ 16 ],
				'font_code' => '\f263',
			],
			'fa-odnoklassniki-square'                => [
				'label'     => 'Odnoklassniki Square',
				'category'  => [ 16 ],
				'font_code' => '\f264',
			],
			'fa-opencart'                            => [
				'label'     => 'Opencart',
				'category'  => [ 16 ],
				'font_code' => '\f23d',
			],
			'fa-openid'                              => [
				'label'     => 'Openid',
				'category'  => [ 16 ],
				'font_code' => '\f19b',
			],
			'fa-opera'                               => [
				'label'     => 'Opera',
				'category'  => [ 16 ],
				'font_code' => '\f26a',
			],
			'fa-optin-monster'                       => [
				'label'     => 'Optin Monster',
				'category'  => [ 16 ],
				'font_code' => '\f23c',
			],
			'fa-pagelines'                           => [
				'label'     => 'Pagelines',
				'category'  => [ 16 ],
				'font_code' => '\f18c',
			],
			'fa-pied-piper'                          => [
				'label'     => 'Pied Piper',
				'category'  => [ 16 ],
				'font_code' => '\f2ae',
			],
			'fa-pied-piper-alt'                      => [
				'label'     => 'Pied Piper Alt',
				'category'  => [ 16 ],
				'font_code' => '\f1a8',
			],
			'fa-pied-piper-pp'                       => [
				'label'     => 'Pied Piper Pp',
				'category'  => [ 16 ],
				'font_code' => '\f1a7',
			],
			'fa-pinterest'                           => [
				'label'     => 'Pinterest',
				'category'  => [ 16 ],
				'font_code' => '\f0d2',
			],
			'fa-pinterest-p'                         => [
				'label'     => 'Pinterest P',
				'category'  => [ 16 ],
				'font_code' => '\f231',
			],
			'fa-pinterest-square'                    => [
				'label'     => 'Pinterest Square',
				'category'  => [ 16 ],
				'font_code' => '\f0d3',
			],
			'fa-product-hunt'                        => [
				'label'     => 'Product Hunt',
				'category'  => [ 16 ],
				'font_code' => '\f288',
			],
			'fa-qq'                                  => [
				'label'     => 'Qq',
				'category'  => [ 16 ],
				'font_code' => '\f1d6',
			],
			'fa-ra'                                  => [
				'label'     => 'Ra',
				'category'  => [ 16 ],
				'font_code' => '\f1d0',
			],
			'fa-rebel'                               => [
				'label'     => 'Rebel',
				'category'  => [ 16 ],
				'font_code' => '\f1d0',
			],
			'fa-reddit'                              => [
				'label'     => 'Reddit',
				'category'  => [ 16 ],
				'font_code' => '\f1a1',
			],
			'fa-reddit-alien'                        => [
				'label'     => 'Reddit Alien',
				'category'  => [ 16 ],
				'font_code' => '\f281',
			],
			'fa-reddit-square'                       => [
				'label'     => 'Reddit Square',
				'category'  => [ 16 ],
				'font_code' => '\f1a2',
			],
			'fa-renren'                              => [
				'label'     => 'Renren',
				'category'  => [ 16 ],
				'font_code' => '\f18b',
			],
			'fa-resistance'                          => [
				'label'     => 'Resistance',
				'category'  => [ 16 ],
				'font_code' => '\f1d0',
			],
			'fa-safari'                              => [
				'label'     => 'Safari',
				'category'  => [ 16 ],
				'font_code' => '\f267',
			],
			'fa-scribd'                              => [
				'label'     => 'Scribd',
				'category'  => [ 16 ],
				'font_code' => '\f28a',
			],
			'fa-sellsy'                              => [
				'label'     => 'Sellsy',
				'category'  => [ 16 ],
				'font_code' => '\f213',
			],
			'fa-shirtsinbulk'                        => [
				'label'     => 'Shirtsinbulk',
				'category'  => [ 16 ],
				'font_code' => '\f214',
			],
			'fa-simplybuilt'                         => [
				'label'     => 'Simplybuilt',
				'category'  => [ 16 ],
				'font_code' => '\f215',
			],
			'fa-skyatlas'                            => [
				'label'     => 'Skyatlas',
				'category'  => [ 16 ],
				'font_code' => '\f216',
			],
			'fa-skype'                               => [
				'label'     => 'Skype',
				'category'  => [ 16 ],
				'font_code' => '\f17e',
			],
			'fa-slack'                               => [
				'label'     => 'Slack',
				'category'  => [ 16 ],
				'font_code' => '\f198',
			],
			'fa-slideshare'                          => [
				'label'     => 'Slideshare',
				'category'  => [ 16 ],
				'font_code' => '\f1e7',
			],
			'fa-snapchat'                            => [
				'label'     => 'Snapchat',
				'category'  => [ 16 ],
				'font_code' => '\f2ab',
			],
			'fa-snapchat-ghost'                      => [
				'label'     => 'Snapchat Ghost',
				'category'  => [ 16 ],
				'font_code' => '\f2ac',
			],
			'fa-snapchat-square'                     => [
				'label'     => 'Snapchat Square',
				'category'  => [ 16 ],
				'font_code' => '\f2ad',
			],
			'fa-soundcloud'                          => [
				'label'     => 'Soundcloud',
				'category'  => [ 16 ],
				'font_code' => '\f1be',
			],
			'fa-spotify'                             => [
				'label'     => 'Spotify',
				'category'  => [ 16 ],
				'font_code' => '\f1bc',
			],
			'fa-stack-exchange'                      => [
				'label'     => 'Stack Exchange',
				'category'  => [ 16 ],
				'font_code' => '\f18d',
			],
			'fa-stack-overflow'                      => [
				'label'     => 'Stack Overflow',
				'category'  => [ 16 ],
				'font_code' => '\f16c',
			],
			'fa-steam'                               => [
				'label'     => 'Steam',
				'category'  => [ 16 ],
				'font_code' => '\f1b6',
			],
			'fa-steam-square'                        => [
				'label'     => 'Steam Square',
				'category'  => [ 16 ],
				'font_code' => '\f1b7',
			],
			'fa-stumbleupon'                         => [
				'label'     => 'Stumbleupon',
				'category'  => [ 16 ],
				'font_code' => '\f1a4',
			],
			'fa-stumbleupon-circle'                  => [
				'label'     => 'Stumbleupon Circle',
				'category'  => [ 16 ],
				'font_code' => '\f1a3',
			],
			'fa-tencent-weibo'                       => [
				'label'     => 'Tencent Weibo',
				'category'  => [ 16 ],
				'font_code' => '\f1d5',
			],
			'fa-themeisle'                           => [
				'label'     => 'Themeisle',
				'category'  => [ 16 ],
				'font_code' => '\f2b2',
			],
			'fa-trello'                              => [
				'label'     => 'Trello',
				'category'  => [ 16 ],
				'font_code' => '\f181',
			],
			'fa-tripadvisor'                         => [
				'label'     => 'Tripadvisor',
				'category'  => [ 16 ],
				'font_code' => '\f262',
			],
			'fa-tumblr'                              => [
				'label'     => 'Tumblr',
				'category'  => [ 16 ],
				'font_code' => '\f173',
			],
			'fa-tumblr-square'                       => [
				'label'     => 'Tumblr Square',
				'category'  => [ 16 ],
				'font_code' => '\f174',
			],
			'fa-twitch'                              => [
				'label'     => 'Twitch',
				'category'  => [ 16 ],
				'font_code' => '\f1e8',
			],
			'fa-twitter'                             => [
				'label'     => 'Twitter',
				'category'  => [ 16 ],
				'font_code' => '\f099',
			],
			'fa-twitter-square'                      => [
				'label'     => 'Twitter Square',
				'category'  => [ 16 ],
				'font_code' => '\f081',
			],
			'fa-usb'                                 => [
				'label'     => 'Usb',
				'category'  => [ 16 ],
				'font_code' => '\f287',
			],
			'fa-viacoin'                             => [
				'label'     => 'Viacoin',
				'category'  => [ 16 ],
				'font_code' => '\f237',
			],
			'fa-viadeo'                              => [
				'label'     => 'Viadeo',
				'category'  => [ 16 ],
				'font_code' => '\f2a9',
			],
			'fa-viadeo-square'                       => [
				'label'     => 'Viadeo Square',
				'category'  => [ 16 ],
				'font_code' => '\f2aa',
			],
			'fa-vimeo'                               => [
				'label'     => 'Vimeo',
				'category'  => [ 16 ],
				'font_code' => '\f27d',
			],
			'fa-vimeo-square'                        => [
				'label'     => 'Vimeo Square',
				'category'  => [ 16 ],
				'font_code' => '\f194',
			],
			'fa-vine'                                => [
				'label'     => 'Vine',
				'category'  => [ 16 ],
				'font_code' => '\f1ca',
			],
			'fa-vk'                                  => [
				'label'     => 'Vk',
				'category'  => [ 16 ],
				'font_code' => '\f189',
			],
			'fa-wechat'                              => [
				'label'     => 'Wechat',
				'category'  => [ 16 ],
				'font_code' => '\f1d7',
			],
			'fa-weibo'                               => [
				'label'     => 'Weibo',
				'category'  => [ 16 ],
				'font_code' => '\f18a',
			],
			'fa-weixin'                              => [
				'label'     => 'Weixin',
				'category'  => [ 16 ],
				'font_code' => '\f1d7',
			],
			'fa-whatsapp'                            => [
				'label'     => 'Whatsapp',
				'category'  => [ 16 ],
				'font_code' => '\f232',
			],
			'fa-wikipedia-w'                         => [
				'label'     => 'Wikipedia W',
				'category'  => [ 16 ],
				'font_code' => '\f266',
			],
			'fa-windows'                             => [
				'label'     => 'Windows',
				'category'  => [ 16 ],
				'font_code' => '\f17a',
			],
			'fa-wordpress'                           => [
				'label'     => 'Wordpress',
				'category'  => [ 16 ],
				'font_code' => '\f19a',
			],
			'fa-wpbeginner'                          => [
				'label'     => 'Wpbeginner',
				'category'  => [ 16 ],
				'font_code' => '\f297',
			],
			'fa-wpforms'                             => [
				'label'     => 'Wpforms',
				'category'  => [ 16 ],
				'font_code' => '\f298',
			],
			'fa-xing'                                => [
				'label'     => 'Xing',
				'category'  => [ 16 ],
				'font_code' => '\f168',
			],
			'fa-xing-square'                         => [
				'label'     => 'Xing Square',
				'category'  => [ 16 ],
				'font_code' => '\f169',
			],
			'fa-y-combinator'                        => [
				'label'     => 'Y Combinator',
				'category'  => [ 16 ],
				'font_code' => '\f23b',
			],
			'fa-y-combinator-square'                 => [
				'label'     => 'Y Combinator Square',
				'category'  => [ 16 ],
				'font_code' => '\f1d4',
			],
			'fa-yahoo'                               => [
				'label'     => 'Yahoo',
				'category'  => [ 16 ],
				'font_code' => '\f19e',
			],
			'fa-yc'                                  => [
				'label'     => 'Yc',
				'category'  => [ 16 ],
				'font_code' => '\f23b',
			],
			'fa-yc-square'                           => [
				'label'     => 'Yc Square',
				'category'  => [ 16 ],
				'font_code' => '\f1d4',
			],
			'fa-yelp'                                => [
				'label'     => 'Yelp',
				'category'  => [ 16 ],
				'font_code' => '\f1e9',
			],
			'fa-yoast'                               => [
				'label'     => 'Yoast',
				'category'  => [ 16 ],
				'font_code' => '\f2b1',
			],
			'fa-youtube'                             => [
				'label'     => 'Youtube',
				'category'  => [ 16 ],
				'font_code' => '\f167',
			],
			'fa-youtube-square'                      => [
				'label'     => 'Youtube Square',
				'category'  => [ 16 ],
				'font_code' => '\f166',
			],
			'fa-h-square'                            => [
				'label'     => 'H Square',
				'category'  => [ 17 ],
				'font_code' => '\f0fd',
			],
			'fa-hospital-o'                          => [
				'label'     => 'Hospital <span class="text-muted">(Outline)</span>',
				'category'  => [ 17 ],
				'font_code' => '\f0f8',
			],
			'fa-medkit'                              => [
				'label'     => 'Medkit',
				'category'  => [ 17 ],
				'font_code' => '\f0fa',
			],
			'fa-stethoscope'                         => [
				'label'     => 'Stethoscope',
				'category'  => [ 17 ],
				'font_code' => '\f0f1',
			],
			'fa-user-md'                             => [
				'label'     => 'User Md',
				'category'  => [ 17 ],
				'font_code' => '\f0f0',
			],
		];

		// Count each category icons
		$this->count_categories_icons();

	}


	/**
	 * Counts icons in each category
	 */
	function count_categories_icons(): void {

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
	 * @param string $icon_key
	 * @param string $classes
	 *
	 * @return string
	 */
	function get_icon_tag( string $icon_key, string $classes = '' ) {

		$classes = apply_filters( 'better_fontawesome_icons_classes', $classes );

		if ( ! isset( $this->icons[ $icon_key ] ) ) {
			return '';
		}

		return \BetterFrameworkPackage\Component\Control\the_icon( $icon_key, $classes );
	}

	/**
	 * @param string $icon_key
	 * @param bool   $is_selected
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
				'data-font-code'  => $icon['font_code'],
				'data-value'      => $icon_key,
				'data-label'      => $icon['label'],
				'data-type'       => 'fontawesome',
				'class'           => $classes . $categories,
			]
		);
	}
}
