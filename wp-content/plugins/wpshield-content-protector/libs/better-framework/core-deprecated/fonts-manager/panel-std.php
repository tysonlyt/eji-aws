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


$fields['custom_fonts'] = [
	'default' => [
		[
			'id'    => __( 'Font %i%', 'better-studio' ),
			'woff2' => '',
			'woff'  => '',
			'ttf'   => '',
			'svg'   => '',
			'eot'   => '',
			'otf'   => '',
		],
	],
];

$font_stacks['id']                = [
	'std' => '',
];
$font_stacks['stack']             = [
	'std' => '',
];
$fields['font_stacks']            = [
	'default' => [
		[
			'id'    => 'Arial',
			'stack' => 'Arial,"Helvetica Neue",Helvetica,sans-serif',
		],
		[
			'id'    => 'Arial Black',
			'stack' => '"Arial Black","Arial Bold",Gadget,sans-serif',
		],
		[
			'id'    => 'Arial Narrow',
			'stack' => '"Arial Narrow",Arial,sans-serif',
		],
		[
			'id'    => 'Calibri',
			'stack' => 'Calibri,Candara,Segoe,"Segoe UI",Optima,Arial,sans-serif',
		],
		[
			'id'    => 'Gill Sans',
			'stack' => '"Gill Sans","Gill Sans MT",Calibri,sans-serif',
		],
		[
			'id'    => 'Helvetica',
			'stack' => '"Helvetica Neue",Helvetica,Arial,sans-serif',
		],
		[
			'id'    => 'Tahoma',
			'stack' => 'Tahoma,Verdana,Segoe,sans-serif',
		],
		[
			'id'    => 'Trebuchet MS',
			'stack' => '"Trebuchet MS","Lucida Grande","Lucida Sans Unicode","Lucida Sans",Tahoma,sans-serif',
		],
		[
			'id'    => 'Verdana',
			'stack' => 'Verdana,Geneva,sans-serif',
		],
		[
			'id'    => 'Georgia',
			'stack' => 'Georgia,Times,"Times New Roman",serif',
		],
		[
			'id'    => 'Palatino',
			'stack' => 'Palatino,"Palatino Linotype","Palatino LT STD","Book Antiqua",Georgia,serif',
		],
		[
			'id'    => 'Courier New',
			'stack' => '"Courier New",Courier,"Lucida Sans Typewriter","Lucida Typewriter",monospace',
		],
		[
			'id'    => 'Lucida Sans Typewriter',
			'stack' => '"Lucida Sans Typewriter","Lucida Console",monaco,"Bitstream Vera Sans Mono",monospace',
		],
		[
			'id'    => 'Copperplate',
			'stack' => 'Copperplate,"Copperplate Gothic Light",fantasy',
		],
		[
			'id'    => 'Papyrus',
			'stack' => 'Papyrus,fantasy',
		],
		[
			'id'    => 'Brush Script MT',
			'stack' => '"Brush Script MT",cursive',
		],
		[
			'id'    => 'System Font',
			'stack' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", "Open Sans", sans-serif',
		],
	],
];
$fields['typekit_code']           = [
	'std' => '',
];
$fields['typekit_fonts']          = [
	'default' => [
		[
			'name' => '',
			'id'   => '',
		],
	],
];
$fields['google_fonts_protocol']  = [
	'std' => 'http',
];
$fields['typo_text_heading']      = [
	'std' => __( 'This is a test heading text', 'better-studio' ),
];
$fields['typo_text_font_manager'] = [
	'std' => __( 'The face of the moon was in shadow', 'better-studio' ),
];
$fields['typo_text_paragraph']    = [
	'std' => __( 'Grumpy wizards make toxic brew for the evil Queen and Jack. One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin.', 'better-studio' ),
];
$fields['typo_text_divided']      = [
	'std' => __(
		'a b c d e f g h i j k l m n o p q r s t u v w x y z
A B C D E F G H I J K L M N O P Q R S T U V W X Y Z
0123456789 (!@#$%&.,?:;)',
		'better-studio'
	),
];

