<?php

if ( ! function_exists( 'bf_show_on_attributes' ) ) {

	/**
	 * Generate required attributes for fields wrapper to handle 'show_on' feature
	 *
	 * @param array $options
	 *
	 * @since 2.8.7
	 *
	 * @return string
	 */
	function bf_show_on_attributes( $options ) {

		bf_convert_filter_field_to_show_on( $options );

		$unique_id    = $options['id'] ?? '';
		$control_type = $options['type'] ?? '';

		if ( 'group' === $control_type && ! empty( $options['level'] ) ) {

			$unique_id .= $options['level'];
		}

		$attributes = sprintf( ' data-param-name="%s" data-param-type="%s"', esc_attr( $unique_id ), esc_attr( $control_type ) );

		if ( $settings = bf_show_on_settings( $options ) ) {
			$attributes .= ' data-param-settings="';
			$attributes .= esc_attr( json_encode( $settings ) );
			$attributes .= '"';
		}

		return $attributes;
	}
}

if ( ! function_exists( 'bf_show_on_settings' ) ) {

	/**
	 * @param array $options
	 *
	 * @return array
	 */
	function bf_show_on_settings( $options ) {

		return array_intersect_key( $options, BF_Admin_Fields::$public_options );
	}
}

if ( ! function_exists( 'bf_convert_filter_field_to_show_on' ) ) {

	/**
	 * filter-field attribute backward compatibility
	 *
	 * @param array $options
	 *
	 * @since 2.8.7
	 */
	function bf_convert_filter_field_to_show_on( &$options ) {

		if ( ! empty( $options['filter-field'] ) && isset( $options['filter-field-value'] ) ) {

			$options['show_on'] = [
				[ sprintf( '%s=%s', $options['filter-field'], $options['filter-field-value'] ) ],
			];

			unset( $options['filter-field'] );
			unset( $options['filter-field-value'] );
		}
	}
}

if ( ! function_exists( 'bf_field_extra_options' ) ) {

	/**
	 * Get extra options name available for field
	 *
	 * @param string $field_type
	 *
	 * @since 3.0.0
	 * @return array
	 */
	function bf_field_extra_options( $field_type ) {

		$options = [];

		switch ( $field_type ) {
			case 'select_popup':
				$options = [ 'texts', 'confirm_texts', 'column_class', 'confirm_changes' ];
				break;
		}

		return $options;
	}
}
