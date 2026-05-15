<?php

namespace BetterFrameworkPackage\Component\Standard\Control;

use BetterFrameworkPackage\Asset\Enqueue;

class ControlAssets {

	/**
	 * Store Enqueue JS instance
	 *
	 * @var Enqueue\EnqueueInterface
	 */
	protected $enqueue_js;

	/**
	 * Store Enqueue CSS instance
	 *
	 * @var Enqueue\EnqueueInterface
	 *
	 * @since 1.0.0
	 */
	protected $enqueue_css;

	/**
	 * @param Enqueue\EnqueueInterface $enqueue_js
	 * @param Enqueue\EnqueueInterface $enqueue_css
	 *
	 * @since 1.0.0
	 */
	public function __construct( \BetterFrameworkPackage\Asset\Enqueue\EnqueueInterface $enqueue_js, \BetterFrameworkPackage\Asset\Enqueue\EnqueueInterface $enqueue_css ) {

		$this->enqueue_js  = $enqueue_js;
		$this->enqueue_css = $enqueue_css;
	}

	/**
	 * Enqueue the control JS file.
	 *
	 * @param StandardControl $control
	 *
	 * @since 1.0.0
	 * @return bool true on success
	 */
	public function enqueue_js( \BetterFrameworkPackage\Component\Standard\Control\StandardControl $control ): bool {

		if ( ! $control instanceof \BetterFrameworkPackage\Component\Standard\Control\HaveScripts ) {

			return false;
		}

		if ( $scripts = $control->scripts_list() ) {

			$handles = $this->add_all( $scripts, $control->control_type() . '-', $this->enqueue_js, 'js' );
		}

		return $this->enqueue_js->enqueue( $handles ?? [] );
	}

	/**
	 * Enqueue the control css file.
	 *
	 * @param StandardControl $control
	 *
	 * @since 1.0.0
	 * @return bool true on success
	 */
	public function enqueue_css( \BetterFrameworkPackage\Component\Standard\Control\StandardControl $control ): bool {

		if ( ! $control instanceof \BetterFrameworkPackage\Component\Standard\Control\HaveStyles ) {

			return false;
		}

		if ( $styles = $control->styles_list() ) {

			$handles = $this->add_all( $styles, $control->control_type() . '-', $this->enqueue_css, 'css' );
		}

		return $this->enqueue_css->enqueue( $handles ?? [] );
	}


	/**
	 * @param array                    $list
	 * @param string                   $handle_prefix
	 * @param Enqueue\EnqueueInterface $enqueue
	 *
	 * @since 1.0.0
	 * @retun string[]
	 */
	protected function add_all( array $list, string $handle_prefix, \BetterFrameworkPackage\Asset\Enqueue\EnqueueInterface $enqueue, string $asset_type = '' ): array {

		$handles = $this->normalize_all( $list, $handle_prefix, $asset_type );

		foreach ( $handles as $item ) {

			$this->add( $item, $enqueue );
			$handled[] = $item['id'];
		}

		return $handled ?? [];
	}

	protected function normalize_all( array &$handles, string $handle_prefix, string $asset_type ): array {

		$new_handles = [];

		foreach ( $handles as $handle ) {

			$new_handles[] = $this->normalize( $handle, $handle_prefix, $asset_type );
		}

		return array_merge( ...$new_handles );
	}

	protected function normalize( array $handle, string $handle_prefix, string $asset_type ): ?array {

		if ( ! isset( $handle['id'] ) ) {

			return null;
		}

		if ( ! empty( $handle['url'] ) && empty( $handle['is_wp'] ) ) {

			$handle['id'] = $handle_prefix . $handle['id'];

			return [ $handle ];
		}

		if ( $asset_type === 'js' ) {

			$asset_files = \BetterFrameworkPackage\Component\Standard\Control\Utils::find_wp_js( $handle['id'] );

		} else if ( $asset_type === 'css' ) {

			$asset_files = \BetterFrameworkPackage\Component\Standard\Control\Utils::find_wp_css( $handle['id'] );
		}

		return $asset_files ?? [];
	}

	/**
	 * @param array                    $item
	 * @param Enqueue\EnqueueInterface $enqueue
	 *
	 * @since 1.0.0
	 * @retun bool
	 */
	protected function add( array $item, \BetterFrameworkPackage\Asset\Enqueue\EnqueueInterface $enqueue ): bool {

		if ( ! isset( $item['url'] ) ) {

			return false;
		}

		return isset( $item['id'] ) && $enqueue->add(
				$item['id'],
				$item['url'] ?? false,
				$item['path'] ?? false,
				$item['deps'] ?? [],
				$item['ver'] ?? false
			);
	}
}
