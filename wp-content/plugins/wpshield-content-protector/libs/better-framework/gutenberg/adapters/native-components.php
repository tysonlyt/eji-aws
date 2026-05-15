<?php

return [
	'AlignmentMatrixControl' => [

		'data_type' => 'string',
	],
	'AnglePickerControl'     => [

		'data_type' => 'number',
	],
	'BorderBoxControl'       => [

		'data_type' => 'object',
		'items'     => [
			'type' => 'object',
		],
	],
	'BoxControl'             => [

		'data_type'  => 'object',
		'value_prop' => 'values',
	],
	'CheckboxControl'        => [

		'data_type'  => 'boolean',
		'value_prop' => 'checked',
	],
	'FontSizePicker'         => [

		'data_type'  => 'string',
		'value_prop' => 'value',
	],
];
