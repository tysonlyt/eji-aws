<?php

namespace WPShield\Plugin\ContentProtectorPro\Features\ViewSource;

use voku\helper\HtmlMin;
use WPShield\Core\PluginCore\Core\Contracts\Installable;
use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Core\PluginCore\Core\ComponentBase;
use WPShield\Plugin\ContentProtector\Core\Component;

use Povils\Figlet\Figlet;
use WPShield\Plugin\ContentProtectorPro\Features\Feature;

/**
 * Class Module
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Modules\ViewSource
 */
class Handler extends Component implements Module, Installable {

	/**
	 * @implements Base structure for component module.
	 */
	use ComponentBase, Feature;

	/**
	 * Store instance of images creator object.
	 *
	 * @var \WPShield\Plugin\ContentProtectorPro\Features\ViewSource\Creator
	 */
	protected $creator;

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function id(): string {

		return 'view-source';
	}

	/**
	 * @inheritDoc
	 *
	 * @return bool
	 */
	public function operation(): bool {

		if ( ! $this->allow_access() ) {

			return false;
		}

		if ( 'disable' === wpshield_cp_option( $this->id() ) ) {

			return false;
		}

		if ( $this->is_filter() ) {

			return false;
		}

		if ( 'comment' !== wpshield_cp_option( sprintf( '%s/type', $this->id() ) ) ) {

			return false;
		}

		add_filter( 'wpshield/content-protector-pro/buffer/end/content', [ $this, 'source_code_protection' ] );

		return true;
	}

	/**
	 * Retrieve source code via copyright.
	 *
	 * @param string $buffer
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function source_code_protection( string $buffer ): string {

		if ( is_admin() ) {

			return $buffer;
		}

		$heading = wpshield_cp_option( 'view-source/message/title' );

		$transient = sprintf( '%s-copyright-%s', $this->id(), $this->sanitize_title( $heading ) );
		$copyright = get_transient( $transient );

		if ( ! $copyright ) {

			return $buffer;
		}

		$html_min = new HtmlMin();
		$subject  = $html_min->minify( $buffer );

		$preg_match = preg_match_all( '/(\s*)<script(?![ \t\r\n]+type\s*=\s*"\s*text\/x\-template\s*").*?(\b[^>]*?>)([\s\S]*?)<\/script>(\s*)/m', $subject, $matches );

		if ( $preg_match ) {

			foreach ( $matches[3] ?? [] as $m ) {

				if ( empty( $m ) ) {

					continue;
				}

				$factory = \WyriHaximus\JsCompress\Factory::construct();

				$subject = str_replace( $m, $factory->compress( $m ), $subject );
			}
		}

		return str_replace( '%BUFFER_CONTENTS%', $subject, $copyright );
	}

	/**
	 * Create Copyright for source code.
	 *
	 * @param array $panel
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function create_copyright( array $panel ): void {

		$data = $panel['data'] ?? [];

		if ( 'comment' !== $data['view-source/type'] || 'disable' === $data['view-source'] ) {

			return;
		}

		$title = $data['view-source/message/title'] ?? '';
		$text  = $data['view-source/message/text'] ?? '';
		$text  = str_replace(
			[
				'%SITENAME%',
				'%SITELINK%',
			],
			[
				get_bloginfo( 'name' ),
				home_url(),
			],
			$text
		);

		#Create copyright transient with copyright heading!
		$transient = sprintf( '%s-copyright-%s', $this->id(), $this->sanitize_title( $title ) );

		// Default font is "big"
		$figlet = new Figlet();

		//Big
		$figlet->setFont( 'big' );

		//Returns rendered string.
		$big_title = $figlet->render( $title );

		//Small
		$figlet->setFont( 'small' );

		ob_start();

		$filename = __DIR__ . '/template/copyright-template.php';

		if ( ! file_exists( $filename ) ) {

			return;
		}

		include $filename;

		$warning = ob_get_clean();

		$warning = str_replace(
			[
				'%HEADING%',
				'%MESSAGE%',
				'%SITENAME%',
			],
			[
				$big_title,
				$text,
				//Returns rendered string.
				$figlet->render( get_bloginfo( 'name' ) ),
			],
			$warning
		);

		//Checking cache data.
		if ( get_transient( $transient ) === $warning ) {

			return;
		}

		set_transient( $transient, $warning );
	}

	/**
	 * Retrieve sanitized title.
	 *
	 * @param string $title
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function sanitize_title( string $title ): string {

		return str_replace( ' ', '-', strtolower( $title ) );
	}

	public function clear_data(): bool {

		return true;
	}

	public function assets(): array {

		return [];
	}

	public function active(): bool {

		return true;
	}

}
