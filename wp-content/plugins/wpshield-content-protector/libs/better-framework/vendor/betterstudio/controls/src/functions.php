<?php

namespace BetterFrameworkPackage\Component\Control;

// use core modules
use \BetterFrameworkPackage\Core\{
	Module\Exception
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// use integration APIs
use \BetterFrameworkPackage\Component\Integration\{
	Control as ControlIntegration
};

// use icons-loader API
use \BetterFrameworkPackage\Utils\{
	Icons,
	HTMLUtil,
};


/**
 *
 * Handy function for translation wp_kses when we need it for descriptions and help HTMLs
 */
function allowed_html(): array {

	return [
		'a'      => [
			'href'   => [],
			'target' => [],
			'id'     => [],
			'class'  => [],
			'rel'    => [],
			'style'  => [],
		],
		'span'   => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'p'      => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'strong' => [
			'class' => [],
			'style' => [],
		],
		'hr'     => [
			'class' => [],
		],
		'br'     => '',
		'b'      => '',
		'h6'     => [
			'class' => [],
			'id'    => [],
		],
		'h5'     => [
			'class' => [],
			'id'    => [],
		],
		'h4'     => [
			'class' => [],
			'id'    => [],
		],
		'h3'     => [
			'class' => [],
			'id'    => [],
		],
		'h2'     => [
			'class' => [],
			'id'    => [],
		],
		'h1'     => [
			'class' => [],
			'id'    => [],
		],
		'code'   => [
			'class' => [],
			'id'    => [],
		],
		'em'     => [
			'class' => [],
		],
		'i'      => [
			'class' => [],
		],
		'img'    => [
			'class' => [],
			'style' => [],
			'src'   => [],
			'width' => [],
		],
		'label'  => [
			'for'   => [],
			'style' => [],
		],
		'ol'     => [
			'class' => [],
		],
		'ul'     => [
			'class' => [],
		],
		'li'     => [
			'class' => [],
		],
	];
}


/**
 * @param array  $controls
 * @param string $input_format
 *
 * @return \Generator
 */
function render_controls_list( array $controls, string $input_format, array $options = [] ): \Generator {

	foreach ( $controls as $control ) {

		try {
			$control['input_name'] = strtr( $input_format, [ '{{control_id}}' => $control['id'] ] );

			yield \BetterFrameworkPackage\Component\Control\render_control_array( $control, $options );

		} catch ( \BetterFrameworkPackage\Core\Module\Exception $e ) {
		}
	}
}

/**
 * @param array $control
 * @param array $options
 *
 * @throws Exception
 * @since 1.0.0
 * @return string
 */
function render_control_array( array $control, array $options = [] ): string {

	if ( ! $control_instance = \BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::factory( $control['type'] ?? '' ) ) {

		throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid control type: ' . $control['type'] ?? '' );
	}

	return \BetterFrameworkPackage\Component\Control\render_control( $control_instance, $control, $options );
}

/**
 * @param ControlStandard\StandardControl $control
 * @param array                           $props
 * @param array                           $options
 *
 * @throws Exception
 * @return string
 */
function render_control( \BetterFrameworkPackage\Component\Standard\Control\StandardControl $control, array $props, array $options = [] ): string {

	if ( ! $control instanceof \BetterFrameworkPackage\Component\Standard\Control\HaveRenderDynamic ) {

		throw new \BetterFrameworkPackage\Core\Module\Exception( 'cannot render control: ' . $control->control_type() );
	}

	$return     = $control->render( $props, $options );
	$wrapper_id = $options['wrapper_id'] ?? 'default';

	if ( $wrapper = \BetterFrameworkPackage\Component\Control\Setup::wrapper( $wrapper_id ) ) {

		$return = $wrapper( $return, $props, $options );
	}

	/**
	 * FIXME: Hard coded
	 */
	$wrapper = \BetterFrameworkPackage\Component\Control\Setup::wrapper( 'pro-feature' );

	return $wrapper( $return, $props, $options );
}

/**
 * @param string $control_type
 *
 * @since 1.0.0
 * @return bool
 */
function control_exists( string $control_type ): bool {

	return \BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::exists( $control_type );
}

/**
 * @param string $string
 *
 * @since 1.0.0
 * @return mixed
 */
function json_decode( string $string ) {

	$decode = \json_decode( $string, true );

	if ( JSON_ERROR_NONE !== json_last_error() ) {

		$decode = \json_decode( wp_unslash( $string ), true );
	}

	return $decode;
}

/**
 * @param array|string $icon
 * @param string       $custom_classes
 * @param array        $options
 *
 * @since 1.0.4
 * @return string
 */
function the_icon( $icon, string $custom_classes = '', array $options = [] ): string {

	$icon_id = $icon['icon'] ?? $icon;

	$options['custom_classes'] = $custom_classes;

	return \BetterFrameworkPackage\Utils\Icons\IconManager::render( $icon_id, $options );
}


/**
 * @param array|string $icon
 * @param string       $custom_classes
 * @param array        $options
 *
 * @since 1.0.4
 */
function print_icon( $icon, string $custom_classes = '', array $options = [] ) {

	echo \BetterFrameworkPackage\Component\Control\the_icon( $icon, $custom_classes, $options );
}


/**
 * @param array        $props
 * @param string|array $class_name
 * @param array        $custom_attributes
 * @param bool         $return
 *
 * @return string|null
 */
function container_attributes( array $props, $class_name = '', array $custom_attributes = [], bool $return = false ): ?string {

	$attributes          = array_merge( $props['container_attributes'] ?? [], $custom_attributes );
	$attributes['class'] = array_merge(
		isset( $attributes['class'] ) ? (array) $attributes['class'] : [],
		isset( $props['classes'] ) ? (array) $props['classes'] : [],
		(array) $class_name
	);

	return \BetterFrameworkPackage\Utils\HTMLUtil\RenderUtil::render_attributes( $attributes, $return );
}

/**
 * @param string     $control_type
 * @param mixed      $control_value
 * @param array|null $props
 *
 * @since 1.0.0
 * @return mixed null when value is illegal
 */
function filter_control_value( string $control_type, $control_value, ?array $props = [] ) {

	if ( empty( $control_type ) ) {

		return $control_value;
	}

	if ( ! $control_instance = \BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::factory( $control_type ) ) {

		return $control_value;
	}

	if ( ! $control_instance instanceof \BetterFrameworkPackage\Component\Standard\Control\WillModifySaveValue ) {

		return $control_value;
	}

	return $control_instance->modify_save_value( $control_value, $props ?? [] );
}

function expand_group_fields( array $fields ): array {

	foreach ( $fields as $field ) {

		if ( ! isset( $field['type'] ) || 'multiple_controls' !== $field['type'] ) {

			continue;
		}

		foreach ( ( $field['controls'] ?? [] ) as $_id => $child_control ) {

			if ( ! isset( $child_control['id'] ) ) {

				$child_control['id'] = $_id;
			}

			$fields[ $child_control['id'] ] = $child_control;
		}
	}

	return $fields;
}