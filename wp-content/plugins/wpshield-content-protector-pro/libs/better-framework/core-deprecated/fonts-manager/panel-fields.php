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


//
// Custom Fonts
//
$fields[]               = [
	'name' => __( 'Custom Fonts', 'better-studio' ),
	'id'   => 'custom_fonts_tab',
	'type' => 'tab',
	'icon' => 'bsfi-plus',
];
$custom_fonts           = [];
$custom_fonts['id']     = [
	'name'            => __( 'Custom Font Name', 'better-studio' ),
	'id'              => 'id',
	'std'             => '',
	'type'            => 'text',
	'container_class' => 'better-custom-fonts-id',
	'section_class'   => 'full-with-both',
	'repeater_item'   => true,
];
$custom_fonts['woff']   = [
	'name'            => __( 'Font .woff', 'better-studio' ),
	'button_text'     => __( 'Upload .woff', 'better-studio' ),
	'id'              => 'woff',
	'std'             => '',
	'type'            => 'media',
	'container_class' => 'better-custom-fonts-woff',
	'section_class'   => 'full-with-both',
	'repeater_item'   => true,
];
$custom_fonts['woff2']  = [
	'name'            => __( 'Font .woff2', 'better-studio' ),
	'button_text'     => __( 'Upload .woff2', 'better-studio' ),
	'id'              => 'woff2',
	'std'             => '',
	'type'            => 'media',
	'container_class' => 'better-custom-fonts-woff2',
	'section_class'   => 'full-with-both',
	'repeater_item'   => true,
];
$custom_fonts['ttf']    = [
	'name'            => __( 'Font .ttf', 'better-studio' ),
	'button_text'     => __( 'Upload .ttf', 'better-studio' ),
	'id'              => 'ttf',
	'std'             => '',
	'type'            => 'media',
	'container_class' => 'better-custom-fonts-ttf',
	'section_class'   => 'full-with-both',
	'repeater_item'   => true,
];
$custom_fonts['svg']    = [
	'name'            => __( 'Font .svg', 'better-studio' ),
	'button_text'     => __( 'Upload .svg', 'better-studio' ),
	'id'              => 'svg',
	'std'             => '',
	'type'            => 'media',
	'container_class' => 'better-custom-fonts-svg',
	'section_class'   => 'full-with-both',
	'repeater_item'   => true,
];
$custom_fonts['eot']    = [
	'name'            => __( 'Font .eot', 'better-studio' ),
	'button_text'     => __( 'Upload .eot', 'better-studio' ),
	'id'              => 'eot',
	'std'             => '',
	'type'            => 'media',
	'container_class' => 'better-custom-fonts-eot',
	'section_class'   => 'full-with-both',
	'repeater_item'   => true,
];
$custom_fonts['otf']    = [
	'name'            => __( 'Font .otf', 'better-studio' ),
	'button_text'     => __( 'Upload .otf', 'better-studio' ),
	'id'              => 'otf',
	'std'             => '',
	'type'            => 'media',
	'container_class' => 'better-custom-fonts-otf',
	'section_class'   => 'full-with-both',
	'repeater_item'   => true,
];
$fields['custom_fonts'] = [
	'name'          => __( 'Upload Custom Fonts', 'better-studio' ),
	'id'            => 'custom_fonts',
	'type'          => 'repeater',
	'save-std'      => true,
	'delete_label'  => __( 'Delete Font', 'better-studio' ),
	'item_title'    => __( 'Custom Font', 'better-studio' ),
	'add_label'     => bf_get_icon_tag( 'bsfi-plus' ) . __( 'Add New Custom Font', 'better-studio' ),
	'section_class' => 'full-with-both',
	'options'       => $custom_fonts,
];


//
// TypeKit Fonts
//
$fields[]                = [
	'name' => __( 'TypeKit Fonts', 'better-studio' ),
	'id'   => 'typekit_tab',
	'type' => 'tab',
	'icon' => 'bsai-typekit',
];
$fields[]                = [
	'name'          => __( 'What is TypeKit?', 'better-studio' ),
	'id'            => '_typekit-help',
	'type'          => 'info',
	'std'           => __( 'TypeKit offer a service that allows you to select from a range of hundreds of high quality fonts for your WordPress website. The fonts are applied using the font-face standard, so they are standards compliant, fully licensed and accessible.', 'better-studio' ),
	'state'         => 'open',
	'info-type'     => 'warning',
	'section_class' => 'widefat',
];
$fields[]                = [
	'name'          => __( 'How to setup TypeKit?', 'better-studio' ),
	'id'            => '_typekit-setup',
	'type'          => 'info',
	'std'           => __(
		'<ol>
                <li>Go To <a href="https://goo.gl/mKugDo" target="_blank">typekit.com</a> and register for an account</li>
                <li>Choose a few fonts to add to your account and Publish them.</li>
                <li>Go to the <strong>Kit Editor</strong> and get your <strong>Embed Code</strong> (link at the top right of the screen)</li>
                <li>Copy the whole 2 lines of your embed code into the following <strong>TypeKit Embed Code</strong> field.</li>
                <li>You have to add fonts of your TypeKit kit into following the <strong>Kit Fonts List</strong> field.</li>
                <li>Done, You can select TypeKit fonts from panels in typography field.</li>
            </ol>',
		'better-studio'
	),
	'state'         => 'open',
	'info-type'     => 'help',
	'section_class' => 'widefat',
];
$fields['typekit_code']  = [
	'name' => __( 'TypeKit Embed Code', 'better-studio' ),
	'desc' => __( 'Enter the whole 2 lines of your kit embed code into this field. Create new item for each font.', 'better-studio' ),
	'id'   => 'typekit_code',
	'type' => 'textarea',
];
$tk_font['name']         = [
	'name'            => __( 'Font Name', 'better-studio' ),
	'desc'            => __( 'The name you provide will appear in the field of the panel.', 'better-studio' ),
	'id'              => 'id',
	'std'             => '',
	'type'            => 'text',
	'container_class' => 'better-font-stack-name',
	'section_class'   => 'full-with-both',
	'repeater_item'   => true,
];
$tk_font['id']           = [
	'name'            => __( 'Font Family ID', 'better-studio' ),
	'desc'            => __( 'Font family ID of selected font in TypeKit. Ex: fair-sans', 'better-studio' ),
	'id'              => 'id',
	'std'             => '',
	'type'            => 'text',
	'container_class' => 'better-font-stack-stack',
	'section_class'   => 'full-with-both',
	'repeater_item'   => true,
];
$fields['typekit_fonts'] = [
	'name'          => __( 'Kit Fonts List', 'better-studio' ),
	'desc'          => __( 'Enter fonts of your kit into following fields to can select it from fonts selector popup in panels.', 'better-studio' ),
	'id'            => 'typekit_fonts',
	'type'          => 'repeater',
	'save-std'      => true,
	'add_label'     => bf_get_icon_tag( 'bsfi-plus' ) . __( 'Add New TypeKit Font', 'better-studio' ),
	'delete_label'  => __( 'Delete Font', 'better-studio' ),
	'item_title'    => __( 'TypeKit Fonts', 'better-studio' ),
	'section_class' => 'full-with-both',
	'options'       => $tk_font,
];


//
// Fonts Stacks
//
$fields[]              = [
	'name' => __( 'Font Stacks', 'better-studio' ),
	'id'   => 'font_stacks_tab',
	'type' => 'tab',
	'icon' => 'bsai-font',
];
$font_stacks['id']     = [
	'name'            => __( 'Font Name', 'better-studio' ),
	'id'              => 'id',
	'std'             => '',
	'type'            => 'text',
	'container_class' => 'better-font-stack-name',
	'section_class'   => 'full-with-both',
	'repeater_item'   => true,
];
$font_stacks['stack']  = [
	'name'            => __( 'Font Stack', 'better-studio' ),
	'id'              => 'stack',
	'std'             => '',
	'type'            => 'text',
	'container_class' => 'better-font-stack-stack',
	'section_class'   => 'full-with-both',
	'repeater_item'   => true,
];
$fields['font_stacks'] = [
	'name'          => __( 'Web Safe CSS Font Stacks', 'better-studio' ),
	'id'            => 'font_stacks',
	'type'          => 'repeater',
	'save-std'      => true,
	'add_label'     => bf_get_icon_tag( 'bsfi-plus' ) . __( 'Add New Font Stack', 'better-studio' ),
	'delete_label'  => __( 'Delete Font Stack', 'better-studio' ),
	'item_title'    => __( 'CSS Font Stack', 'better-studio' ),
	'section_class' => 'full-with-both',
	'options'       => $font_stacks,
];


//
// Advanced Options
//
$fields[] = [
	'name' => __( 'Advanced', 'better-studio' ),
	'id'   => 'typo_opt_tab',
	'type' => 'tab',
	'icon' => 'bsai-gear',
];
$fields[] = [
	'name'          => __( 'Google Fonts Protocol', 'better-studio' ),
	'id'            => 'google_fonts_protocol',
	'desc'          => __( 'Select protocol of fonts link for Google Fonts.', 'better-studio' ),
	'std'           => 'http',
	'type'          => 'advance_select',
	'vertical'      => true,
	'section_class' => 'style-floated-left',
	'options'       => [
		'http'     => __( 'HTTP', 'better-studio' ),
		'https'    => __( 'HTTPs', 'better-studio' ),
		'relative' => __( 'Relative to Site', 'better-studio' ),
	],
];
$fields[] = [
	'name'  => __( 'Typography Field Preview Texts', 'better-studio' ),
	'type'  => 'group',
	'state' => 'not',
];
$fields[] = [
	'name' => __( 'Font modal preview text', 'better-studio' ),
	'id'   => 'typo_text_font_manager',
	'type' => 'text',
];
$fields[] = [
	'name' => __( 'Heading Text', 'better-studio' ),
	'id'   => 'typo_text_heading',
	'type' => 'text',
];
$fields[] = [
	'name' => __( 'Paragraph Text', 'better-studio' ),
	'id'   => 'typo_text_paragraph',
	'type' => 'textarea',
];
$fields[] = [
	'name' => __( 'Divided Text', 'better-studio' ),
	'id'   => 'typo_text_divided',
	'type' => 'textarea',
];


//
// Backup & restore
//
$fields[] = [
	'name'       => __( 'Backup & Restore', 'better-studio' ),
	'id'         => 'backup_restore',
	'type'       => 'tab',
	'icon'       => 'bsai-export-import',
	'margin-top' => '30',
];
$fields[] = [
	'name'      => __( 'Backup / Export', 'better-studio' ),
	'id'        => 'backup_export_options',
	'type'      => 'export',
	'file_name' => 'custom-fonts-backup',
	'panel_id'  => $this->option_panel_id,
	'desc'      => __( 'This allows you to create a backup of your translation. Please note, it will not backup anything else.', 'better-studio' ),
];
$fields[] = [
	'name'     => __( 'Restore / Import', 'better-studio' ),
	'id'       => 'import_restore_options',
	'type'     => 'import',
	'panel_id' => $this->option_panel_id,
	'desc'     => __( '<strong>It will override your current translation!</strong> Please make sure to select a valid translation file.', 'better-studio' ),
];

