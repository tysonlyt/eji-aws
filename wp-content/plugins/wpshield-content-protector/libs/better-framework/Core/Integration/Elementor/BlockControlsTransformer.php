<?php

namespace BetterFrameworkPackage\Framework\Core\Integration\Elementor;

// use integration APIs
use \BetterFrameworkPackage\Component\Standard\{
	Block as BlockStandards
};

// use integration APIs
use \BetterFrameworkPackage\Component\Integration\{
	Control as ControlIntegration
};

// use controls lists
use BetterFrameworkPackage\Component\Control;

use Elementor;

/**
 * Transform publisher extended blocks panel controls to elementor widget controls.
 *
 * Iterate through controls array and fire register controls methods.
 *
 * @link    https://developers.elementor.com/register-widget-controls/
 *
 * @since   4.0.0
 */
class BlockControlsTransformer {

	/**
	 * Store elementor widget instance.
	 *
	 * @var Elementor\Widget_Base
	 * @since 4.0.0
	 */
	protected $widget;

	/**
	 * Store the block instance.
	 *
	 * @var BlockStandards\BlockInterface
	 *
	 * @since 4.0.0
	 */
	protected $block;

	/**
	 * Store the transformer setting.
	 *
	 * @var array
	 *
	 * @since 4.0.0
	 */
	protected $settings;

	/**
	 * Store active elementor tab name.
	 *
	 * @var string
	 *
	 * @since 4.0.0
	 */
	protected $current_tab;

	/**
	 * Store the controls default value..
	 *
	 * @var string
	 *
	 * @since 4.0.0
	 */
	protected $default_values;

	/**
	 * Store list of fields type that save data as an array.
	 *
	 * @var array
	 *
	 * @since 4.0.0
	 */
	protected $multiple_controls = [ 'image_dimensions', 'dimensions', 'url', 'media', 'icons', 'repeater', 'gallery' ];

	/**
	 * @var array
	 *
	 * @since 4.0.0
	 */
	protected $group_control_type = [];

	/**
	 * Storage to keep data while transforming.
	 *
	 * @var array
	 * @since 4.0.0
	 */
	protected $stack = [];

	/**
	 * Transformer constructor.
	 *
	 * @param Elementor\Controls_Stack    $widget
	 * @param BlockStandards\HaveControls $block
	 * @param array                       $settings
	 */
	public function __construct( Elementor\Controls_Stack $widget, \BetterFrameworkPackage\Component\Standard\Block\HaveControls $block, array $settings = [] ) {

		$this->widget         = $widget;
		$this->block          = $block;
		$this->settings       = $settings;
		$this->default_values = $block->defaults();

		$this->group_control_type = [
			'text_shadow' => Elementor\Group_Control_Text_Shadow::get_type(),
			// 'typography'  => Elementor\Group_Control_Typography::get_type(),
			'image_size'  => Elementor\Group_Control_Image_Size::get_type(),
			'box_shadow'  => Elementor\Group_Control_Box_Shadow::get_type(),
			'border'      => Elementor\Group_Control_Border::get_type(),
			'background'  => Elementor\Group_Control_Background::get_type(),
		];
	}

	/**
	 * @since 4.0.0
	 */
	public function transform_widget_controls(): void {

		$this->transform();
	}

	/**
	 * Transform panel fields to elementor.
	 *
	 * @since 4.0.0
	 * @return bool true on success or false failure.
	 */
	public function transform(): bool {

		if ( ! $this->block ) {

			return false;
		}

		$this->current_tab = Elementor\Controls_Manager::TAB_CONTENT;

		foreach ( $this->block->fields() as $_control_id => $control ) {

			if ( ! $control ) {

				continue;
			}

			$control_id = $control['id'] ?? $_control_id;

			$this->normalize( $control, $control_id );

			$this->transform_control( $control, $control_id );
		}

		return true;
	}


	/**
	 * Transform control to elementor version.
	 *
	 * @param array  $control
	 * @param string $control_id
	 *
	 * FIXME: Refactor
	 *
	 * @since 4.0.0
	 * @return bool true on success
	 */
	public function transform_control( array $control, string $control_id ): bool {

		switch ( $control['type'] ) {

			case 'section':
			case 'tabs':
			case 'tab':
				$control_id = 'section_' . $control_id;

				if ( isset( $this->stack['current_section'] ) && $this->stack['current_section'] !== '' ) {

					$this->transform_control( [ 'type' => 'section_end' ], $control_id . '_end' );
				}

				$this->widget->start_controls_section(
					$control_id,
					[
						'label' => $control['label'] ?? 'General',
						'tab'   => $this->current_tab,
					]
				);
				$this->stack['current_section'] = $control_id;

				break;

			case 'section_end':
			case 'tabs_end':
			case 'tab_end':
				$this->stack['current_section'] = '';

				$this->widget->end_controls_section();

				break;

			/**
			 * Elementor Group Fields
			 */

			default:
				if ( ! isset( $this->stack['current_section'] ) || $this->stack['current_section'] === '' ) {

					$this->transform_control( [ 'type' => 'section' ], $control_id . '_start' );
				}

				unset( $control['tab'] );
				if ( $group = $this->group_control_type( $control['type'] ) ) {

					unset( $control['type'] );

					$this->widget->add_group_control(
						$group,
						$control
					);

				} elseif ( ! empty( $control['responsive'] ) ) {

					$this->widget->add_responsive_control(
						$control_id,
						$this->responsive_control_args( $control )
					);

				} else {

					$this->widget->add_control(
						$control_id,
						$this->control_props( $control )
					);
				}
		}

		return true;
	}

	/**
	 * @param array $props
	 *
	 * @return array
	 */
	protected function control_props( array $props ): array {

		if ( ! $control_instance = \BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::factory( $props['type'] ) ) {

			return $props;
		}

		$props = $control_instance->props_init( $props, false );

		if ( $props['type'] === 'repeater' ) {

			unset( $props['name'] );

		} elseif ( \BetterFrameworkPackage\Component\Control\control_exists( $props['type'] ) ) {

			$props['type'] = 'bf-' . $props['type'];
		}

		if ( ! isset( $props['label'] ) && isset( $props['name'] ) ) {

			$props['label'] = $props['name'];
		}

		// if ( isset( $props['show_on'] ) ) {
		//
		// $props['conditions'] = ( new ElementorConditionTransformer() )->transform( $props['show_on'] );
		//
		// unset( $props['show_on'] );
		// }

		return $props;
	}

	/**
	 * @param array  $control
	 * @param string $control_id
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public function normalize( array &$control, string $control_id ): array {

		if ( isset( $control['override-elementor'] ) ) {

			$control = array_merge( $control, $control['override-elementor'] );

			unset( $control['override-elementor'] );
		}

		if ( isset( $control['desc'] ) ) {

			$control['description'] = $control['desc'];
		}

		if ( ! isset( $control['name'] ) ) {

			$control['name'] = $control_id;
		}
		if ( ! isset( $control['label'] ) ) {
			$control['label'] = $control['name'] ?? $control_id;
		}

		$control['default'] = $this->default_value( $control, $control_id );
		$control['tab']     = $this->current_tab;

		if ( 'repeater' === $control['type'] ) {

			$repeater = new Elementor\Repeater();

			foreach ( $control['options'] ?? [] as $repeater_control_id => $repeater_control ) {

				$repeater->add_control( $repeater_control_id, $this->control_props( $repeater_control ) );
			}

			$control['fields'] = $repeater->get_controls();

			unset( $control['options'] );
		}

		return $control;
	}

	/**
	 * Get control default value.
	 *
	 * @param array $control
	 *
	 * @since 4.0.0
	 * @return mixed
	 */
	public function default_value( array &$control, string $control_id ) {

		if ( isset( $this->default_values[ $control_id ] ) ) {

			return $this->default_values[ $control_id ];
		}

		if ( \in_array( $control['type'], $this->multiple_controls, true ) ) {

			return [];
		}

		return $control['std'] ?? '';
	}


	/**
	 * @param string $control_type
	 *
	 * @since 4.0.0
	 * @return string
	 */
	public function group_control_type( string $control_type ): string {

		return $this->group_control_type[ $control_type ] ?? '';
	}

	/**
	 * @param array $controls
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public function responsive_control_args( array $controls ): array {

		$id = $controls['name'];

		unset(
			$controls['responsive'],
			$controls['name'],
			$controls['default']
		);

		foreach ( [ 'desktop', 'tablet', 'mobile' ] as $device ) {

			if ( empty( $controls[ 'hide_' . $device ] ) ) {

				$controls['devices'][] = $device;
			}

			if ( isset( $this->default_values[ $id ][ $device ] ) ) {

				$controls[ $device . '_default' ] = $this->default_values[ $id ][ $device ];
			}

			unset( $controls[ 'hide_' . $device ] );
		}

		return $controls;
	}
}
