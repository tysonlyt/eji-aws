<?php

namespace BetterFrameworkPackage\Component\Control\MediaImage;

use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class MediaImageControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements
	\BetterFrameworkPackage\Component\Standard\Control\HaveStyles,
	\BetterFrameworkPackage\Component\Standard\Control\HaveScripts,
	\BetterFrameworkPackage\Component\Standard\Control\HaveSecureProps,
	\BetterFrameworkPackage\Component\Standard\Control\WillModifyProps {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'media_image';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	/**
	 * @param array $props
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function modify_props( array $props ): array {

		if ( ! isset( $props['upload_button_class'] ) ) {

			$props['upload_button_class'] = '';
		}

		if ( isset( $props['data-type'] ) && $props['data-type'] === 'id' ) {

			$props['upload_button_class'] .= ' bf-media-type-id';
		}

		if ( empty( $props['preview_image_url'] ) && filter_var( $props['value'] ?? '', FILTER_VALIDATE_URL ) ) {

			$props['preview_image_url'] = $props['value'];
		}

		return $props;
	}

	/**
	 * @param int|string $attachment_id
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function attachment_image_src( $attachment_id, string $size = 'thumbnail' ): string {

		if ( empty( $attachment_id ) || ! filter_var( $attachment_id, FILTER_VALIDATE_INT ) ) {

			return '';
		}

		$source = wp_get_attachment_image_src( $attachment_id, $size );

		return $source ? $source[0] : '';
	}

	public function scripts_list(): array {

		$this->load_media_assets();

		return [];
	}

	public function styles_list(): array {

		return [
			[
				'id' => 'buttons',
			],
		];
	}

	public function secure_props( array $props ): array {

		$props['preview_image_url'] = $this->attachment_image_src(
			$props['value'] ?? '',
			$props['preview-size'] ?? 'thumbnail'
		);

		return $props;
	}

	public function secure_props_needed( array $props, bool $use_dynamic_props ): bool {

		if ( ! empty( $props['value'] ) && filter_var( $props['value'], FILTER_VALIDATE_INT ) ) {

			return true;
		}

		return ! empty( $props['data-type'] ) && $props['data-type'] === 'id';
	}

	public function secure_props_token( array $props ): string {

		// return wp_create_nonce( 'attachment:' . ($props['value'] ?? '') );
		return wp_create_nonce( 'attachment' );
	}

	public function data_type(): string {

		return 'string';
	}
}
