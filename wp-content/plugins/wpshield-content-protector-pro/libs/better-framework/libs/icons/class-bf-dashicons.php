<?php


/**
 * Used for handling all actions about Dashicons in PHP
 */
class BF_Dashicons {

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
			'dashicons-cat-1' => [
				'id'    => 'bs-cat-1',
				'label' => 'Dashicons',
			],
		];

		$this->icons =
			[
				'dashicons-admin-settings'            =>
					[
						'label'     => 'Admin Settings',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-site'                =>
					[
						'label'     => 'Admin Site',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-site-alt'            =>
					[
						'label'     => 'Admin Site Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-site-alt2'           =>
					[
						'label'     => 'Admin Site Alt2',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-site-alt3'           =>
					[
						'label'     => 'Admin Site Alt3',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-tools'               =>
					[
						'label'     => 'Admin Tools',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-users'               =>
					[
						'label'     => 'Admin Users',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-airplane'                  =>
					[
						'label'     => 'Airplane',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-album'                     =>
					[
						'label'     => 'Album',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-align-center'              =>
					[
						'label'     => 'Align Center',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-align-full-width'          =>
					[
						'label'     => 'Align Full Width',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-align-left'                =>
					[
						'label'     => 'Align Left',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-align-none'                =>
					[
						'label'     => 'Align None',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-align-pull-left'           =>
					[
						'label'     => 'Align Pull Left',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-align-pull-right'          =>
					[
						'label'     => 'Align Pull Right',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-align-right'               =>
					[
						'label'     => 'Align Right',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-align-wide'                =>
					[
						'label'     => 'Align Wide',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-amazon'                    =>
					[
						'label'     => 'Amazon',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-analytics'                 =>
					[
						'label'     => 'Analytics',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-archive'                   =>
					[
						'label'     => 'Archive',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-arrow-down'                =>
					[
						'label'     => 'Arrow Down',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-arrow-down-alt'            =>
					[
						'label'     => 'Arrow Down Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-arrow-down-alt2'           =>
					[
						'label'     => 'Arrow Down Alt2',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-arrow-left'                =>
					[
						'label'     => 'Arrow Left',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-arrow-left-alt'            =>
					[
						'label'     => 'Arrow Left Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-arrow-left-alt2'           =>
					[
						'label'     => 'Arrow Left Alt2',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-arrow-right'               =>
					[
						'label'     => 'Arrow Right',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-arrow-right-alt'           =>
					[
						'label'     => 'Arrow Right Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-arrow-right-alt2'          =>
					[
						'label'     => 'Arrow Right Alt2',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-arrow-up'                  =>
					[
						'label'     => 'Arrow Up',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-arrow-up-alt'              =>
					[
						'label'     => 'Arrow Up Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-arrow-up-alt2'             =>
					[
						'label'     => 'Arrow Up Alt2',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-arrow-up-duplicate'        =>
					[
						'label'     => 'Arrow Up Duplicate',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-art'                       =>
					[
						'label'     => 'Art',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-awards'                    =>
					[
						'label'     => 'Awards',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-backup'                    =>
					[
						'label'     => 'Backup',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-bank'                      =>
					[
						'label'     => 'Bank',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-beer'                      =>
					[
						'label'     => 'Beer',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-bell'                      =>
					[
						'label'     => 'Bell',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-block-default'             =>
					[
						'label'     => 'Block Default',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-book'                      =>
					[
						'label'     => 'Book',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-book-alt'                  =>
					[
						'label'     => 'Book Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-buddicons-activity'        =>
					[
						'label'     => 'Buddicons Activity',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-buddicons-bbpress-logo'    =>
					[
						'label'     => 'Buddicons Bbpress Logo',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-buddicons-buddypress-logo' =>
					[
						'label'     => 'Buddicons Buddypress Logo',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-buddicons-community'       =>
					[
						'label'     => 'Buddicons Community',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-buddicons-forums'          =>
					[
						'label'     => 'Buddicons Forums',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-buddicons-friends'         =>
					[
						'label'     => 'Buddicons Friends',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-buddicons-groups'          =>
					[
						'label'     => 'Buddicons Groups',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-buddicons-pm'              =>
					[
						'label'     => 'Buddicons Pm',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-buddicons-replies'         =>
					[
						'label'     => 'Buddicons Replies',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-buddicons-topics'          =>
					[
						'label'     => 'Buddicons Topics',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-buddicons-tracking'        =>
					[
						'label'     => 'Buddicons Tracking',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-building'                  =>
					[
						'label'     => 'Building',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-businessman'               =>
					[
						'label'     => 'Businessman',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-businessperson'            =>
					[
						'label'     => 'Businessperson',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-businesswoman'             =>
					[
						'label'     => 'Businesswoman',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-button'                    =>
					[
						'label'     => 'Button',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-calculator'                =>
					[
						'label'     => 'Calculator',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-calendar'                  =>
					[
						'label'     => 'Calendar',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-calendar-alt'              =>
					[
						'label'     => 'Calendar Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-camera'                    =>
					[
						'label'     => 'Camera',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-camera-alt'                =>
					[
						'label'     => 'Camera Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-car'                       =>
					[
						'label'     => 'Car',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-carrot'                    =>
					[
						'label'     => 'Carrot',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-cart'                      =>
					[
						'label'     => 'Cart',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-category'                  =>
					[
						'label'     => 'Category',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-chart-area'                =>
					[
						'label'     => 'Chart Area',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-chart-bar'                 =>
					[
						'label'     => 'Chart Bar',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-chart-line'                =>
					[
						'label'     => 'Chart Line',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-chart-pie'                 =>
					[
						'label'     => 'Chart Pie',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-clipboard'                 =>
					[
						'label'     => 'Clipboard',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-clock'                     =>
					[
						'label'     => 'Clock',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-cloud'                     =>
					[
						'label'     => 'Cloud',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-cloud-saved'               =>
					[
						'label'     => 'Cloud Saved',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-cloud-upload'              =>
					[
						'label'     => 'Cloud Upload',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-code-standards'            =>
					[
						'label'     => 'Code Standards',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-coffee'                    =>
					[
						'label'     => 'Coffee',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-color-picker'              =>
					[
						'label'     => 'Color Picker',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-columns'                   =>
					[
						'label'     => 'Columns',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-controls-back'             =>
					[
						'label'     => 'Controls Back',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-controls-forward'          =>
					[
						'label'     => 'Controls Forward',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-controls-pause'            =>
					[
						'label'     => 'Controls Pause',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-controls-play'             =>
					[
						'label'     => 'Controls Play',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-controls-repeat'           =>
					[
						'label'     => 'Controls Repeat',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-controls-skipback'         =>
					[
						'label'     => 'Controls Skipback',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-controls-skipforward'      =>
					[
						'label'     => 'Controls Skipforward',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-controls-volumeoff'        =>
					[
						'label'     => 'Controls Volumeoff',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-controls-volumeon'         =>
					[
						'label'     => 'Controls Volumeon',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-cover-image'               =>
					[
						'label'     => 'Cover Image',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-dashboard'                 =>
					[
						'label'     => 'Dashboard',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-database'                  =>
					[
						'label'     => 'Database',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-database-add'              =>
					[
						'label'     => 'Database Add',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-database-export'           =>
					[
						'label'     => 'Database Export',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-database-import'           =>
					[
						'label'     => 'Database Import',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-database-remove'           =>
					[
						'label'     => 'Database Remove',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-database-view'             =>
					[
						'label'     => 'Database View',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-desktop'                   =>
					[
						'label'     => 'Desktop',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-dismiss'                   =>
					[
						'label'     => 'Dismiss',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-download'                  =>
					[
						'label'     => 'Download',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-drumstick'                 =>
					[
						'label'     => 'Drumstick',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-edit'                      =>
					[
						'label'     => 'Edit',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-edit-large'                =>
					[
						'label'     => 'Edit Large',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-aligncenter'        =>
					[
						'label'     => 'Editor Aligncenter',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-alignleft'          =>
					[
						'label'     => 'Editor Alignleft',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-alignright'         =>
					[
						'label'     => 'Editor Alignright',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-bold'               =>
					[
						'label'     => 'Editor Bold',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-break'              =>
					[
						'label'     => 'Editor Break',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-code'               =>
					[
						'label'     => 'Editor Code',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-code-duplicate'     =>
					[
						'label'     => 'Editor Code Duplicate',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-contract'           =>
					[
						'label'     => 'Editor Contract',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-customchar'         =>
					[
						'label'     => 'Editor Customchar',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-expand'             =>
					[
						'label'     => 'Editor Expand',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-help'               =>
					[
						'label'     => 'Editor Help',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-indent'             =>
					[
						'label'     => 'Editor Indent',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-insertmore'         =>
					[
						'label'     => 'Editor Insertmore',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-italic'             =>
					[
						'label'     => 'Editor Italic',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-justify'            =>
					[
						'label'     => 'Editor Justify',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-kitchensink'        =>
					[
						'label'     => 'Editor Kitchensink',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-ltr'                =>
					[
						'label'     => 'Editor Ltr',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-ol'                 =>
					[
						'label'     => 'Editor Ol',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-ol-rtl'             =>
					[
						'label'     => 'Editor Ol Rtl',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-outdent'            =>
					[
						'label'     => 'Editor Outdent',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-paragraph'          =>
					[
						'label'     => 'Editor Paragraph',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-paste-text'         =>
					[
						'label'     => 'Editor Paste Text',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-paste-word'         =>
					[
						'label'     => 'Editor Paste Word',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-quote'              =>
					[
						'label'     => 'Editor Quote',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-removeformatting'   =>
					[
						'label'     => 'Editor Removeformatting',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-rtl'                =>
					[
						'label'     => 'Editor Rtl',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-spellcheck'         =>
					[
						'label'     => 'Editor Spellcheck',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-strikethrough'      =>
					[
						'label'     => 'Editor Strikethrough',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-table'              =>
					[
						'label'     => 'Editor Table',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-textcolor'          =>
					[
						'label'     => 'Editor Textcolor',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-ul'                 =>
					[
						'label'     => 'Editor Ul',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-underline'          =>
					[
						'label'     => 'Editor Underline',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-unlink'             =>
					[
						'label'     => 'Editor Unlink',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-editor-video'              =>
					[
						'label'     => 'Editor Video',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-edit-page'                 =>
					[
						'label'     => 'Edit Page',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-ellipsis'                  =>
					[
						'label'     => 'Ellipsis',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-email'                     =>
					[
						'label'     => 'Email',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-email-alt'                 =>
					[
						'label'     => 'Email Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-email-alt2'                =>
					[
						'label'     => 'Email Alt2',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-embed-audio'               =>
					[
						'label'     => 'Embed Audio',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-embed-generic'             =>
					[
						'label'     => 'Embed Generic',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-embed-photo'               =>
					[
						'label'     => 'Embed Photo',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-embed-post'                =>
					[
						'label'     => 'Embed Post',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-embed-video'               =>
					[
						'label'     => 'Embed Video',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-excerpt-view'              =>
					[
						'label'     => 'Excerpt View',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-exit'                      =>
					[
						'label'     => 'Exit',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-external'                  =>
					[
						'label'     => 'External',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-facebook'                  =>
					[
						'label'     => 'Facebook',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-facebook-alt'              =>
					[
						'label'     => 'Facebook Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-feedback'                  =>
					[
						'label'     => 'Feedback',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-filter'                    =>
					[
						'label'     => 'Filter',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-flag'                      =>
					[
						'label'     => 'Flag',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-food'                      =>
					[
						'label'     => 'Food',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-format-aside'              =>
					[
						'label'     => 'Format Aside',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-format-audio'              =>
					[
						'label'     => 'Format Audio',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-format-chat'               =>
					[
						'label'     => 'Format Chat',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-format-gallery'            =>
					[
						'label'     => 'Format Gallery',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-format-image'              =>
					[
						'label'     => 'Format Image',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-format-quote'              =>
					[
						'label'     => 'Format Quote',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-format-status'             =>
					[
						'label'     => 'Format Status',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-format-video'              =>
					[
						'label'     => 'Format Video',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-forms'                     =>
					[
						'label'     => 'Forms',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-fullscreen-alt'            =>
					[
						'label'     => 'Fullscreen Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-fullscreen-exit-alt'       =>
					[
						'label'     => 'Fullscreen Exit Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-games'                     =>
					[
						'label'     => 'Games',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-google'                    =>
					[
						'label'     => 'Google',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-googleplus'                =>
					[
						'label'     => 'Googleplus',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-grid-view'                 =>
					[
						'label'     => 'Grid View',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-groups'                    =>
					[
						'label'     => 'Groups',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-hammer'                    =>
					[
						'label'     => 'Hammer',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-heading'                   =>
					[
						'label'     => 'Heading',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-heart'                     =>
					[
						'label'     => 'Heart',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-hidden'                    =>
					[
						'label'     => 'Hidden',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-hourglass'                 =>
					[
						'label'     => 'Hourglass',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-html'                      =>
					[
						'label'     => 'Html',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-id'                        =>
					[
						'label'     => 'Id',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-id-alt'                    =>
					[
						'label'     => 'Id Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-image-crop'                =>
					[
						'label'     => 'Image Crop',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-image-filter'              =>
					[
						'label'     => 'Image Filter',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-image-flip-horizontal'     =>
					[
						'label'     => 'Image Flip Horizontal',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-image-flip-vertical'       =>
					[
						'label'     => 'Image Flip Vertical',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-image-rotate'              =>
					[
						'label'     => 'Image Rotate',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-image-rotate-left'         =>
					[
						'label'     => 'Image Rotate Left',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-image-rotate-right'        =>
					[
						'label'     => 'Image Rotate Right',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-images-alt'                =>
					[
						'label'     => 'Images Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-images-alt2'               =>
					[
						'label'     => 'Images Alt2',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-index-card'                =>
					[
						'label'     => 'Index Card',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-info'                      =>
					[
						'label'     => 'Info',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-info-outline'              =>
					[
						'label'     => 'Info Outline',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-insert'                    =>
					[
						'label'     => 'Insert',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-insert-after'              =>
					[
						'label'     => 'Insert After',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-insert-before'             =>
					[
						'label'     => 'Insert Before',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-instagram'                 =>
					[
						'label'     => 'Instagram',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-laptop'                    =>
					[
						'label'     => 'Laptop',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-layout'                    =>
					[
						'label'     => 'Layout',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-leftright'                 =>
					[
						'label'     => 'Leftright',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-lightbulb'                 =>
					[
						'label'     => 'Lightbulb',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-linkedin'                  =>
					[
						'label'     => 'Linkedin',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-list-view'                 =>
					[
						'label'     => 'List View',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-location'                  =>
					[
						'label'     => 'Location',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-location-alt'              =>
					[
						'label'     => 'Location Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-lock'                      =>
					[
						'label'     => 'Lock',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-lock-duplicate'            =>
					[
						'label'     => 'Lock Duplicate',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-marker'                    =>
					[
						'label'     => 'Marker',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-media-archive'             =>
					[
						'label'     => 'Media Archive',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-media-audio'               =>
					[
						'label'     => 'Media Audio',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-media-code'                =>
					[
						'label'     => 'Media Code',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-media-default'             =>
					[
						'label'     => 'Media Default',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-media-document'            =>
					[
						'label'     => 'Media Document',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-media-interactive'         =>
					[
						'label'     => 'Media Interactive',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-media-spreadsheet'         =>
					[
						'label'     => 'Media Spreadsheet',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-media-text'                =>
					[
						'label'     => 'Media Text',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-media-video'               =>
					[
						'label'     => 'Media Video',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-megaphone'                 =>
					[
						'label'     => 'Megaphone',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-menu'                      =>
					[
						'label'     => 'Menu',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-menu-alt'                  =>
					[
						'label'     => 'Menu Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-menu-alt2'                 =>
					[
						'label'     => 'Menu Alt2',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-menu-alt3'                 =>
					[
						'label'     => 'Menu Alt3',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-microphone'                =>
					[
						'label'     => 'Microphone',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-migrate'                   =>
					[
						'label'     => 'Migrate',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-minus'                     =>
					[
						'label'     => 'Minus',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-money'                     =>
					[
						'label'     => 'Money',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-money-alt'                 =>
					[
						'label'     => 'Money Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-move'                      =>
					[
						'label'     => 'Move',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-nametag'                   =>
					[
						'label'     => 'Nametag',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-networking'                =>
					[
						'label'     => 'Networking',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-no'                        =>
					[
						'label'     => 'No',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-no-alt'                    =>
					[
						'label'     => 'No Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-open-folder'               =>
					[
						'label'     => 'Open Folder',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-palmtree'                  =>
					[
						'label'     => 'Palmtree',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-paperclip'                 =>
					[
						'label'     => 'Paperclip',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-pdf'                       =>
					[
						'label'     => 'Pdf',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-performance'               =>
					[
						'label'     => 'Performance',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-pets'                      =>
					[
						'label'     => 'Pets',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-phone'                     =>
					[
						'label'     => 'Phone',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-pinterest'                 =>
					[
						'label'     => 'Pinterest',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-playlist-audio'            =>
					[
						'label'     => 'Playlist Audio',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-playlist-video'            =>
					[
						'label'     => 'Playlist Video',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-plugins-checked'           =>
					[
						'label'     => 'Plugins Checked',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-plus'                      =>
					[
						'label'     => 'Plus',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-plus-alt'                  =>
					[
						'label'     => 'Plus Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-plus-alt2'                 =>
					[
						'label'     => 'Plus Alt2',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-podio'                     =>
					[
						'label'     => 'Podio',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-portfolio'                 =>
					[
						'label'     => 'Portfolio',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-post-status'               =>
					[
						'label'     => 'Post Status',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-pressthis'                 =>
					[
						'label'     => 'Pressthis',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-printer'                   =>
					[
						'label'     => 'Printer',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-privacy'                   =>
					[
						'label'     => 'Privacy',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-products'                  =>
					[
						'label'     => 'Products',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-randomize'                 =>
					[
						'label'     => 'Randomize',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-reddit'                    =>
					[
						'label'     => 'Reddit',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-redo'                      =>
					[
						'label'     => 'Redo',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-remove'                    =>
					[
						'label'     => 'Remove',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-rest-api'                  =>
					[
						'label'     => 'Rest Api',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-rss'                       =>
					[
						'label'     => 'Rss',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-saved'                     =>
					[
						'label'     => 'Saved',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-schedule'                  =>
					[
						'label'     => 'Schedule',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-screenoptions'             =>
					[
						'label'     => 'Screenoptions',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-search'                    =>
					[
						'label'     => 'Search',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-share'                     =>
					[
						'label'     => 'Share',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-share-alt'                 =>
					[
						'label'     => 'Share Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-share-alt2'                =>
					[
						'label'     => 'Share Alt2',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-shield'                    =>
					[
						'label'     => 'Shield',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-shield-alt'                =>
					[
						'label'     => 'Shield Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-shortcode'                 =>
					[
						'label'     => 'Shortcode',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-slides'                    =>
					[
						'label'     => 'Slides',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-smartphone'                =>
					[
						'label'     => 'Smartphone',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-smiley'                    =>
					[
						'label'     => 'Smiley',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-sort'                      =>
					[
						'label'     => 'Sort',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-sos'                       =>
					[
						'label'     => 'Sos',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-spotify'                   =>
					[
						'label'     => 'Spotify',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-star-empty'                =>
					[
						'label'     => 'Star Empty',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-star-filled'               =>
					[
						'label'     => 'Star Filled',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-star-half'                 =>
					[
						'label'     => 'Star Half',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-sticky'                    =>
					[
						'label'     => 'Sticky',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-store'                     =>
					[
						'label'     => 'Store',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-superhero'                 =>
					[
						'label'     => 'Superhero',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-superhero-alt'             =>
					[
						'label'     => 'Superhero Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-table-col-after'           =>
					[
						'label'     => 'Table Col After',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-table-col-before'          =>
					[
						'label'     => 'Table Col Before',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-table-col-delete'          =>
					[
						'label'     => 'Table Col Delete',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-table-row-after'           =>
					[
						'label'     => 'Table Row After',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-table-row-before'          =>
					[
						'label'     => 'Table Row Before',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-table-row-delete'          =>
					[
						'label'     => 'Table Row Delete',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-tablet'                    =>
					[
						'label'     => 'Tablet',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-tag'                       =>
					[
						'label'     => 'Tag',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-tagcloud'                  =>
					[
						'label'     => 'Tagcloud',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-testimonial'               =>
					[
						'label'     => 'Testimonial',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-text'                      =>
					[
						'label'     => 'Text',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-text-page'                 =>
					[
						'label'     => 'Text Page',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-thumbs-down'               =>
					[
						'label'     => 'Thumbs Down',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-thumbs-up'                 =>
					[
						'label'     => 'Thumbs Up',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-tickets'                   =>
					[
						'label'     => 'Tickets',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-tickets-alt'               =>
					[
						'label'     => 'Tickets Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-tide'                      =>
					[
						'label'     => 'Tide',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-translation'               =>
					[
						'label'     => 'Translation',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-trash'                     =>
					[
						'label'     => 'Trash',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-twitch'                    =>
					[
						'label'     => 'Twitch',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-twitter'                   =>
					[
						'label'     => 'Twitter',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-twitter-alt'               =>
					[
						'label'     => 'Twitter Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-undo'                      =>
					[
						'label'     => 'Undo',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-universal-access'          =>
					[
						'label'     => 'Universal Access',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-universal-access-alt'      =>
					[
						'label'     => 'Universal Access Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-unlock'                    =>
					[
						'label'     => 'Unlock',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-update'                    =>
					[
						'label'     => 'Update',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-update-alt'                =>
					[
						'label'     => 'Update Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-upload'                    =>
					[
						'label'     => 'Upload',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-vault'                     =>
					[
						'label'     => 'Vault',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-video-alt'                 =>
					[
						'label'     => 'Video Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-video-alt2'                =>
					[
						'label'     => 'Video Alt2',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-video-alt3'                =>
					[
						'label'     => 'Video Alt3',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-visibility'                =>
					[
						'label'     => 'Visibility',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-warning'                   =>
					[
						'label'     => 'Warning',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-welcome-add-page'          =>
					[
						'label'     => 'Welcome Add Page',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-welcome-comments'          =>
					[
						'label'     => 'Welcome Comments',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-welcome-learn-more'        =>
					[
						'label'     => 'Welcome Learn More',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-welcome-view-site'         =>
					[
						'label'     => 'Welcome View Site',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-welcome-widgets-menus'     =>
					[
						'label'     => 'Welcome Widgets Menus',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-welcome-write-blog'        =>
					[
						'label'     => 'Welcome Write Blog',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-whatsapp'                  =>
					[
						'label'     => 'Whatsapp',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-wordpress'                 =>
					[
						'label'     => 'Wordpress',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-wordpress-alt'             =>
					[
						'label'     => 'Wordpress Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-xing'                      =>
					[
						'label'     => 'Xing',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-yes'                       =>
					[
						'label'     => 'Yes',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-yes-alt'                   =>
					[
						'label'     => 'Yes Alt',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-youtube'                   =>
					[
						'label'     => 'Youtube',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-appearance'          =>
					[
						'label'     => 'Admin Appearance',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-collapse'            =>
					[
						'label'     => 'Admin Collapse',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-comments'            =>
					[
						'label'     => 'Admin Comments',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-customizer'          =>
					[
						'label'     => 'Admin Customizer',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-generic'             =>
					[
						'label'     => 'Admin Generic',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-home'                =>
					[
						'label'     => 'Admin Home',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-links'               =>
					[
						'label'     => 'Admin Links',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-media'               =>
					[
						'label'     => 'Admin Media',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-multisite'           =>
					[
						'label'     => 'Admin Multisite',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-network'             =>
					[
						'label'     => 'Admin Network',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-page'                =>
					[
						'label'     => 'Admin Page',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-plugins'             =>
					[
						'label'     => 'Admin Plugins',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
				'dashicons-admin-post'                =>
					[
						'label'     => 'Admin Post',
						'category'  => [ 'dashicons-cat-1' ],
						'font_code' => '\b000',
					],
			];

		// Count each category icons
		$this->countCategoriesIcons();

	}


	/**
	 * Counts icons in each category
	 */
	function countCategoriesIcons() {

		foreach ( (array) $this->icons as $icon ) {

			if ( isset( $icon['category'] ) && bf_count( $icon['category'] ) ) {

				foreach ( $icon['category'] as $key => $category ) {

					if ( ! isset( $this->categories[ $category ] ) ) {
						continue;
					}

					if ( isset( $this->categories[ $category ]['counts'] ) ) {
						$this->categories[ $category ]['counts'] = intval( $this->categories[ $category ]['counts'] ) + 1;
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
	function getIconTag( $icon_key, $classes = '' ) {

		$classes = apply_filters( 'better_dashicons_classes', $classes );

		if ( ! isset( $this->icons[ $icon_key ] ) ) {
			return '';
		}

		return bf_get_icon_tag( $icon_key, $classes );
	}
}
