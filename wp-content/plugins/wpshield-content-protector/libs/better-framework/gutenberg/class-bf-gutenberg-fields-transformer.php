<?php


class BF_Gutenberg_Fields_Transformer {

	/**
	 * @var array
	 */
	protected $sticky_fields;

	public static function instance() {

		$instance = new self();
		$instance->init();

		return $instance;
	}


	/**
	 *
	 */
	public function init() {

		if ( is_admin() ) {
			add_action( 'better-framework/shortcodes/gutenberg-fields', [ $this, 'ajax_prepare_fields' ] );
		}
	}


	public function ajax_prepare_fields( $blocks ) {

		wp_send_json_success( $this->prepare_blocks_fields( $blocks ) );
	}


	/**
	 * @param array $blocks
	 *
	 * @return array
	 */
	public function prepare_blocks_fields( $blocks ) {

		$results = [];

		if ( empty( $blocks ) ) {

			return $results;
		}

		foreach ( $blocks as $block ) {

			$shortcode = BF_Shortcodes_Manager::factory( $block, [], true );

			if ( ! $shortcode ) {
				continue;
			}

			if ( ! $block_fields = $shortcode->get_fields() ) {
				continue;
			}
			$converter = new BF_Fields_To_Gutenberg(
				$block_fields,
				$shortcode->defaults
			);

			$results[ $block ] = $converter->transform();
		}

		return $results;
	}


	/**
	 * @param array $blocks
	 *
	 * @return array<int|string, mixed[]>
	 */
	public function prepare_blocks_attributes( $blocks ): array {

		$results = [];

		foreach ( $blocks as $block ) {

			if ( $fields = $this->block_attributes( $block ) ) {

				$results[ $block ] = $fields;
			}
		}

		return $results;
	}

	public function sticky_fields() {

		if ( ! isset( $this->sticky_fields ) ) {

			list( $this->sticky_fields, ) = BF_Gutenberg_Shortcode_Wrapper::the_sticky_fields();
		}

		return $this->sticky_fields;
	}

	/**
	 * Get the block gutenberg attributes.
	 *
	 * @param string $block_id The block id
	 *
	 * @since 3.11.1
	 * @return array
	 */
	public function block_attributes( $block_id ) {

		$fields = $this->block_fields( $block_id );

		if ( ! $fields ) {

			return [];
		}

		return $this->transform_fields( $fields );
	}


	protected function transform_fields( &$fields ) {

		$converter = new BF_Fields_To_Gutenberg(
			array_merge( $fields, $this->sticky_fields() )
		);

		return $converter->list_attributes();
	}

	/**
	 * Get the block fields array
	 *
	 * @param string $block_id The block id
	 *
	 * @since 3.11.1
	 * @return array
	 */
	public function block_fields( $block_id ) {

		$shortcode = BF_Shortcodes_Manager::factory( $block_id, [], true );

		if ( ! $shortcode ) {

			return [];
		}

		return $shortcode->get_fields();
	}
}
