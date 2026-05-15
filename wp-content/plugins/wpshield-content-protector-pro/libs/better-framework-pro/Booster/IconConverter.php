<?php

namespace BetterStudio\Framework\Pro\Booster;

use BetterStudio\Utils\Icons;

class IconConverter {

	/**
	 * Store html markup.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected $html = '';

	/**
	 * Load the html markup.
	 *
	 * @param string $html
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function load_html( string $html ): bool {

		$this->html = $html;

		return true;
	}

	/**
	 * Get the new html markup.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function output_html(): string {

		return $this->html;
	}

	/**
	 * Convert the loaded html markup icons to new svg sprite format.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function convert(): bool {

		$patterns = "'<\s*i\s.*?class\s*=\s*			# find <i class=
						([\"\'])?						# find single or double quote
						(?(1) (.*?)\\1 | ([^\s\>]+))	# if quote found, match up to next matching
														# quote, otherwise match up to next space
						(.*?)>							# capture the other attributes
						(.*?) <\s*\/\s*i\s*> 			# capture content between tags.
						'isx";

		$this->html = preg_replace_callback( $patterns, [ $this, 'replace_icon' ], $this->html );

		return true;
	}

	/**
	 * @param array $match
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function replace_icon( array $match ): string {

		$classes   = $match[2];
		$attibutes = $match[4];
		$content   = $match[5];
		//
		$icon = $this->detect_icon( $classes );

		return Icons\IconManager::render( $icon, [
			'custom_classes'    => $classes,
			'custom_attributes' => $attibutes,
			'after'             => $content,

		] );
	}

	/**
	 * Detect icon by class name.
	 *
	 * @param string $classes
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function detect_icon( string $classes ): string {

		preg_match( '/(fa-[^\s]+)/', $classes, $match );

		return $match[1] ?? '';
	}
}
