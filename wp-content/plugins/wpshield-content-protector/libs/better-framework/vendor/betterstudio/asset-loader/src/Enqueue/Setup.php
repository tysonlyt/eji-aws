<?php

namespace BetterFrameworkPackage\Asset\Enqueue;


use BetterFrameworkPackage\Asset\Setup as ModuleSetup;
use BetterFrameworkPackage\Core\Module;

class Setup extends \BetterFrameworkPackage\Core\Module\ModuleHandler {

	/**
	 * Initialize the sub-module.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function init(): bool {

		if ( \BetterFrameworkPackage\Asset\Setup::buffer_status() ) {

			add_filter( 'template_include', array( $this, 'buffer_start' ), 1 );
			add_action( 'wp_footer', array( $this, 'buffer_end' ), 999999 );

		} else {

			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 90 );
		}
		//
		add_filter( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 91 );

		return true;
	}

	/**
	 * Start buffering.
	 *
	 * @param string $template
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function buffer_start( string $template ): string {

		ob_start();

		return $template;
	}

	/**
	 * Append style tag to into <head> tag.
	 *
	 * @since 1.0.0
	 */
	public function buffer_end(): void {

		$content = ob_get_clean();
		$styles  = \BetterFrameworkPackage\Asset\Enqueue\BundleEnqueue::styles();

		if ( empty( $styles ) ) {

			echo $content;

			return;
		}

		if ( ! preg_match( '#(.*?)(<\s*head.*?>.+)(<\s*/\s*head\s*>.+)#is', $content, $match ) ) {

			echo $content;

			return;
		}

		echo $match[1], $match[2];
		$this->print_styles();
		echo $match[3];
	}


	/**
	 * @since 1.0.0
	 */
	public function print_styles(): void {

		foreach ( \BetterFrameworkPackage\Asset\Enqueue\BundleEnqueue::styles() as $module_id => $styles ) {

			if ( ! $info = \BetterFrameworkPackage\Asset\Setup::info( $module_id ) ) {

				continue;
			}

			$this->print_style( $styles, $info );
		}
	}

	/**
	 * @since 1.0.0
	 */
	public function enqueue_styles(): void {

		foreach ( \BetterFrameworkPackage\Asset\Enqueue\BundleEnqueue::styles() as $module_id => $styles ) {

			if ( ! $info = \BetterFrameworkPackage\Asset\Setup::info( $module_id ) ) {

				continue;
			}

			wp_enqueue_style( $module_id . '-assets', $this->load_styles_url( $styles, $info ) );
		}
	}


	/**
	 * @param array $styles
	 * @param array $module_info
	 *
	 * @since 1.0.0
	 */
	public function print_style( array $styles, array $module_info ): void {

		printf(
			"<link rel='stylesheet' id='%s-css' href='%s' media='all' />",
			$module_info['module_id'],
			$this->load_styles_url( $styles, $module_info )
		);
	}

	/**
	 * @param array $styles
	 * @param array $module_info
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function load_styles_url( array $styles, array $module_info ): string {

		return sprintf(
			'%s/load-styles.php?load=%s%s',
			untrailingslashit( $module_info['url'] ),
			implode( ',', array_keys( $styles ) ),
			$this->url_params()
		);
	}


	/**
	 * @since 1.0.0
	 */
	public function print_scripts(): void {

		foreach ( \BetterFrameworkPackage\Asset\Enqueue\BundleEnqueue::scripts() as $module_id => $scripts ) {

			if ( ! $info = \BetterFrameworkPackage\Asset\Setup::info( $module_id ) ) {

				continue;
			}

			$this->print_script( $scripts, $info );
		}
	}

	public function enqueue_scripts(): void {

		foreach ( \BetterFrameworkPackage\Asset\Enqueue\BundleEnqueue::scripts() as $module_id => $scripts ) {

			if ( ! $info = \BetterFrameworkPackage\Asset\Setup::info( $module_id ) ) {

				continue;
			}

			wp_enqueue_script( $module_id . '-assets', $this->load_scripts_url( $scripts, $info ), [], false, true );
		}
	}

	/**
	 * @param array $scripts
	 * @param array $module_info
	 *
	 * @since 1.0.0
	 */
	public function print_script( array $scripts, array $module_info ): void {

		printf(
			' <script type="text/javascript" src="%s" id="%s-js"></script>',
			$this->load_scripts_url( $scripts, $module_info ),
			$module_info['module_id']
		);
	}

	/**
	 * @param array $scripts
	 * @param array $module_info
	 *
	 * @return string
	 */
	protected function load_scripts_url( array $scripts, array $module_info ): string {

		return sprintf(
			'%s/load-scripts.php?load=%s%s',
			untrailingslashit( $module_info['url'] ),
			implode( ',', array_keys( $scripts ) ),
			$this->url_params()
		);
	}

	protected function url_params(): string {

		$params = [];

		if ( is_rtl() ) {

			$params['is_rtl'] = true;
		}

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

			$params['is_dev'] = true;
		}

		return $params ? '&' . http_build_query( $params ) : '';
	}
}
