<?php

namespace BetterFrameworkPackage\Component\Standard\Control;

abstract class StandardControl {

	/**
	 * Store the options of the control.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected $options;

	/**
	 * The unique ID / type for the control.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	abstract public function control_type(): string;

	/**
	 * Initialize the standard control with the given options array*
	 *
	 * @param array $options
	 *
	 * @since   1.0.0
	 */
	public function __construct( array $options = [] ) {

		$this->load_options( $options );
	}

	/**
	 * Load the control options array.
	 *
	 * @param array $options
	 *
	 * @example [
	 * 'label'     =>  'The control name' ,
	 * 'id'       => 'the-unique-id',
	 * 'type'     => 'the-control-type',
	 * 'options'  => [
	 * 'show'       =>  'Show - Top' ,
	 * 'bottom'     =>  'Show - Bottom' ,
	 * 'top-bottom' =>  'Show - Top & Bottom' ,
	 * 'hide'       =>  'Hide' ,
	 * ],
	 */
	public function load_options( array $options ): void {

		$this->options = $options;
	}

	/**
	 * Get the control options array.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function options(): array {

		return $this->options ?? [];
	}

	/**
	 * Get the control label if it's available.
	 *
	 * @since 1.0.0
	 * @return string|null
	 */
	public function control_label(): ?string {

		return $this->options['label'] ?? null;
	}

	/**
	 * Load the control media assets when needs.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function load_media_assets(): void {

		if ( did_action( 'wp_enqueue_scripts' ) ) {

			wp_enqueue_media();

		} else {

			add_action( 'wp_enqueue_scripts', 'wp_enqueue_media' );
		}
	}

	/**
	 * @param array $props
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function props_init( array $props, bool $use_dynamic_props = false ): array {

		if ( $this instanceof \BetterFrameworkPackage\Component\Standard\Control\HaveSecureProps && $this->secure_props_needed( $props, $use_dynamic_props ) ) {

			if ( $use_dynamic_props ) {

				$props = $this->secure_props( $props );

			} else {

				$props['_lazy_loading'] = true;
				$props['_token']        = $this->secure_props_token( $props );
			}
		}

		if ( $this instanceof \BetterFrameworkPackage\Component\Standard\Control\WillModifyProps ) {

			$props = $this->modify_props( $props );
		}

		return $props;
	}
}
