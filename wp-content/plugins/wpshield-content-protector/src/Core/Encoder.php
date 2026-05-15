<?php

namespace WPShield\Plugin\ContentProtector\Core;

use BetterStudio\Core\Module\Singleton;

/**
 * Class Encoder
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Core
 */
class Encoder {

	use Singleton;

	/**
	 * Retrieve email regular expression pattern.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_email_regex(): string {

		return apply_filters(
			'wpshield/content-protector/core/encoder/email-regexp',
			//          '([_A-Za-z0-9-]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9-]+)*(\\.[A-Za-z]{2,}))'
			'((mailto:|)[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})'
		);
	}

	/**
	 * Retrieve phone number regular expression pattern.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_phone_number_regex(): string {

		$regex_arr = [
			'(\+|)([0-9]{12}|[0-9]{11}|[0-9]{10})',
			'[0-9]{4}[\s|-][0-9]{7}',
			'[0-9]{4}[\s|-][0-9]{3}[\s|-][0-9]{2}[\s|-][0-9]{2}',
			'[0-9]{4}[\s|-][0-9]{3}[\s|-][0-9]{4}',
			'[0-9](.|\s)[0-9]{3}(.|\s)[0-9]{3}(.|\s)[0-9]{4}',
			'[0-9]{2}(\s|.)[0-9]{3}(\s|.)[0-9]{4}',
		];

		return apply_filters(
			'wpshield/content-protector/core/encoder/phone-number-regexp',
			'/' . implode( '|', $regex_arr ) . '/'
		);
	}

	/**
	 * Encoding any value with characters encoding method.
	 *
	 * @param array $matches
	 *
	 * @since 1.0.0
	 * @return false|mixed|string
	 */
	public function char_encoding( array $matches ) {

		if ( ! isset( $matches[0] ) ) {

			return false;
		}

		// workaround to skip responsive image names containing @
		$extention     = ! isset( $matches[4] ) ? '' : strtolower( $matches[4] );
		$excluded_list = [ '.jpg', '.jpeg', '.png', '.gif', '.svg' ];
		$excluded_list = apply_filters( 'wpshield/content-protector/core/encoder/validate/exclude-image-urls', $excluded_list );

		if ( in_array( $extention, $excluded_list, true ) ) {

			return $matches[0];
		}

		$encoded_value = antispambot( $matches[0] );

		return $encoded_value ?? $matches[0] ?? false;
	}

	/**
	 * Dynamic value (Like: email, phone number or etc) encoding with certain javascript methods.
	 *
	 * @param array $matches
	 *
	 * @return string
	 */
	public function dynamic_js_encoding( array $matches ): string {

		$protection_text = apply_filters(
			'wpshield/content-protector/core/encoder/protection-text',
			__( '***Protected String***', 'wpshield-content-protector' )
		);

		$rand = apply_filters( 'wpshield/content-protector/core/encoder/validate/random-encoding', wp_rand( 0, 2 ) );

		if ( 2 === $rand ) {
			$encoded_value = $this->encode_escape( $matches[0], $protection_text );
		} else {
			$encoded_value = $this->encode_ascii( $matches[0], $protection_text );
		}

		return $encoded_value;
	}

	/**
	 * Filter telephone link.
	 *
	 * @param string $content
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function filter_telephone_link( string $content ): string {

		$regexp = '/<a[\s+]*(([^>]*)href=["\']tel\:([^>]*)["\' ])>(.*?)<\/a[\s+]*>/is';

		return preg_replace_callback( $regexp, [ $this, 'tel_encoder_callback' ], $content );
	}

	/**
	 * Filter mailto link.
	 *
	 * @param string $content
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function filter_mailto_link( string $content ): string {

		$regexp = '/<a[\s+]*(([^>]*)href=["\']mailto\:([^>]*)["\' ])>(.*?)<\/a[\s+]*>/is';

		return preg_replace_callback( $regexp, [ $this, 'mailto_encoder_callback' ], $content );
	}

	/**
	 * Encode mailto link.
	 *
	 * @param array $match
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function mailto_encoder_callback( array $match ): string {

		$attrs = wp_parse_args(
			[
				'data-type' => 'mailto',
			],
			shortcode_parse_atts( $match[1] )
		);

		return $this->create_protected_value( $match[4], $attrs );
	}

	/**
	 * Encode telephone link.
	 *
	 * @param array $match
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function tel_encoder_callback( array $match ): string {

		$attrs = wp_parse_args(
			[
				'data-type' => 'tel',
			],
			shortcode_parse_atts( $match[1] )
		);

		return $this->create_protected_value( $match[4], $attrs );
	}

	/**
	 * Create a protected value
	 *
	 * @param string $display
	 * @param array  $attrs Optional
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function create_protected_value( string $display, array $attrs = array() ): string {

		if ( ! isset( $attrs['data-type'] ) || empty( $attrs['data-type'] ) ) {

			return __( '***Protected Value***', 'content-protector' );
		}

		$type         = $attrs['data-type'];
		$class_ori    = ( empty( $attrs['class'] ) ) ? '' : $attrs['class'];
		$custom_class = wp_sprintf( 'cp-%s-link', $type );

		// set user-defined class
		if ( $custom_class && false === strpos( $class_ori, $custom_class ) ) {
			$attrs['class'] = ( empty( $attrs['class'] ) ) ? $custom_class : $attrs['class'] . ' ' . $custom_class;
		}

		// check title for email address
		if ( ! empty( $attrs['title'] ) ) {
			$attrs['title'] = $this->filter_plain_value( $attrs['title'] );
		}

		// set ignore to data-attribute to prevent being processed by WPEL plugin
		$attrs['data-wpel-link'] = 'ignore';

		// create element code
		$link = '<a ';

		foreach ( $attrs as $key => $value ) {
			if ( 'href' === strtolower( $key ) ) {

				if ( 'mailto' === $type ) {
					// get email from href
					$_value        = substr( $value, 7 );
					$encoded_value = $this->get_encoded_value( $_value );
				} else {
					$encoded_value = $this->char_encoding( [ $value ] );
				}

				// set attrs
				$link .= 'href="javascript:;" ';

				if ( 'mailto' === $type ) {

					$link .= 'data-enc-email="' . $encoded_value . '" ';
				} else {
					$link .= 'data-enc-phone="' . $encoded_value . '" ';
				}
			} elseif ( 'data-id' === $key || false !== strpos( $value, 'mailto:' ) || false !== strpos( $value, 'tel:' ) ) {
				$link .= $key . '="' . $this->char_encoding( [ $value ] ) . '" ';
			} else {
				$link .= $key . '="' . $value . '" ';
			}
		}

		// remove last space
		$link = substr( $link, 0, - 1 );

		// Apply filter
		$link .= apply_filters(
		//phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
			wp_sprintf( 'wpshield/content-protector/core/encoder/%s', $type ),
			wp_sprintf(
				'>%s</a>',
				$display
			)
		);

		// just in case there are still email addresses f.e. within title-tag
		return $this->filter_plain_value( $link );
	}

	/**
	 * Emails will be replaced by '*protected email*'
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function filter_plain_value( string $content ): string {

		return preg_replace_callback( $this->get_email_regex(), [ $this, 'dynamic_js_encoding' ], $content );
	}

	/**
	 * Get encoded value, used for data-attribute (translate by javascript)
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public function get_encoded_value( string $value ): string {

		// decode entities
		$value = html_entity_decode( $value );

		// rot13 encoding
		//phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_str_rot13
		$value = str_rot13( $value );

		// replace @
		return str_replace( '@', '[at]', $value );
	}

	/**
	 * Escape encoding method
	 *
	 * @param string $value
	 * @param string $protection_text
	 *
	 * @return string
	 */
	public function encode_escape( string $value, string $protection_text ): string {

		$element_id = wp_sprintf( 'cp-%d-%d', wp_rand( 0, 1000000 ), wp_rand( 0, 1000000 ) );
		$string     = wp_sprintf( '\%s\\', $value );

		//Validate escape sequences
		$string = preg_replace( '/\s+/S', ' ', $string );

		// break string into array of characters, we can't use string_split because its php5 only
		$split = preg_split( '||', $string );

		$output = wp_sprintf(
			'<span id="%s"></span>
					<script type="text/javascript">
					var element = document.getElementById("%s");
					if (element){
					element.innerHTML = decodeURIComponent("',
			$element_id,
			$element_id
		);

		foreach ( $split as $c ) {
			// preg split will return empty first and last characters, check for them and ignore
			if ( ! empty( $c ) || '0' === $c ) {
				$output .= '%' . dechex( ord( $c ) );
			}
		}

		$output .= wp_sprintf(
			'").replace(/\\\/g,"");}</script><noscript>%s</noscript>',
			$protection_text
		);

		return $output;
	}

	/**
	 * ASCII method
	 *
	 * @param string $value
	 * @param string $protection_text
	 *
	 * @return string
	 */
	public function encode_ascii( string $value, string $protection_text ): string {

		$mail_link = $value;

		// first encode, so special chars can be supported
		$mail_link = Utils::encode_uri_components( $mail_link );

		$mail_letters = '';

		$mail_length = strlen( $mail_link );

		for ( $i = 0; $i < $mail_length; $i ++ ) {

			if ( ! isset( $mail_link[ $i ] ) ) {

				continue;
			}

			$l = $mail_link[ $i ];

			if ( false === strpos( $mail_letters, $l ) ) {

				$p = wp_rand( 0, strlen( $mail_letters ) );

				$mail_letters = wp_sprintf(
					'%s%s%s',
					substr( $mail_letters, 0, $p ),
					$l,
					substr( $mail_letters, $p, strlen( $mail_letters ) )
				);
			}
		}

		$mail_letters_enc = str_replace( '\\', '\\\\', $mail_letters );
		$mail_letters_enc = str_replace( '"', '\\"', $mail_letters_enc );

		$mail_indices = '';

		for ( $i = 0; $i < $mail_length; $i ++ ) {

			if ( ! isset( $mail_link[ $i ] ) ) {

				continue;
			}

			$index        = strpos( $mail_letters, $mail_link[ $i ] );
			$index        += 48;
			$mail_indices .= chr( $index );
		}

		$mail_indices = str_replace( '\\', '\\\\', $mail_indices );
		$mail_indices = str_replace( '"', '\\"', $mail_indices );

		$element_id = 'cp-' . wp_rand( 0, 1000000 ) . '-' . wp_rand( 0, 1000000 );

		$script = wp_sprintf(
			'(function(){
						var ml="%s",mi="%s",o="";
						for(var j=0,l=mi.length;j<l;j++){
							o+=ml.charAt(mi.charCodeAt(j)-48);
						}
						var element = document.getElementById("%s");
						if(element){
						element.innerHTML = decodeURIComponent(o);// decode at the end, this way special chars can be supported
						}
						}());',
			$mail_letters_enc,
			$mail_indices,
			$element_id,
		);

		return wp_sprintf(
			'<span id="%s"></span><script type="text/javascript">%s</script><noscript>%s</noscript>',
			$element_id,
			$script,
			$protection_text
		);
	}
}
