<?php

use BetterFrameworkPackage\Component\Control as LibRoot;

/**
 * @var array $props
 * @var array $options
 */

if ( is_string( $props['value'] ) ) {

	$props['value'] = [];

}

if ( ! isset( $props['value']['family'] ) ) {

	$props['value']['family']  = 'Lato';
	$props['value']['variant'] = '';
}

$default     = $options['default'] ?? [];
$std_id      = $options['std_id'] ?? '';
$enabled     = false;
$input_name  = esc_attr( $props['input_name'] ?? '' );
$input_class = esc_attr( $props['input_class'] ?? '' );

if ( isset( $default[ $std_id ] ) ) {
	if ( isset( $default[ $std_id ]['enable'] ) ) {
		$enabled = true;
	}
} elseif ( isset( $default['std'] ) ) {
	if ( isset( $default['std']['enable'] ) ) {
		$enabled = true;
	}
}

if ( $enabled && ! isset( $props['value']['enable'] ) ) {
	$props['value']['enable'] = $default['std']['enable'];
}

if ( ! isset( $props['preview_tab'] ) ) {
	$props['preview_tab'] = 'title';
}

// Option to disable fields in future
if ( ! isset( $props['typography-deactivate-fields'] ) ) {
	$props['typography-deactivate-fields'] = [];
}
?>

<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bs-control-typography' ); ?>>

    <div class="typo-fields-container">
        <span class="enable-disable"></span>
		<?php if ( $enabled ) { ?>
            <div class="typo-field-container">
                <div class="typo-enable-container">
                    <div class="bf-switch">
                        <label class="cb-enable <?php echo esc_attr( $props['value']['enable'] ) ? 'selected' : ''; ?>"><span><?php esc_html_e( 'Enable', 'better-studio' ); ?></span></label>
                        <label class="cb-disable <?php echo ! $props['value']['enable'] ? 'selected' : ''; ?>"><span><?php esc_html_e( 'Disable', 'better-studio' ); ?></span></label>

                        <input type="hidden" name="<?php echo $input_name;  // escaped before ?>[enable]"
                               value="<?php echo (int) ( $props['value']['enable'] ?? 0 ); ?>"
                               class="checkbox <?php echo $input_class; // escaped before ?>"/>
                    </div>
                </div>
            </div>
<?php
		}
		?>

        <div class="typo-field-container font-family-container bf-select-option-container">
            <label><?php esc_html_e( 'Font Family', 'better-studio' ); ?></label>
            <div class="select-placeholder bf-font-selector">
				<?php

				if ( ! empty( $props['use_parent_font'] ) ) {

					printf( __( 'Parent Font (%s)', 'better-studio' ), $props['parent_typo_options']['family'] );

				} else {

					echo isset( $props['value']['family'] ) ? esc_html( $props['value']['family'] ) : ' - ';
				}
				?>
            </div>
            <input type="hidden" name="<?php echo $input_name; // escaped before ?>[family]"
                   value="<?php echo esc_attr( $props['family_input_value'] ?? $props['value']['family'] ?? '' ); ?>"
                   class="bf-font-family <?php echo $input_class; // escaped before ?>">
        </div>

        <div class="bf-select-option-container typo-field-container">
            <label for="<?php echo $input_name; // escaped before ?>-variants"><?php esc_html_e( 'Font Weight:', 'better-studio' ); ?></label>
            <select name="<?php echo $input_name; // escaped before ?>[variant]"
                    id="<?php echo $input_name; // escaped before ?>-variants"
                    class="font-variants <?php echo $input_class; // escaped before ?>">

				<?php echo $props['variants_options'] ?? ''; ?>
            </select>
        </div>

		<?php
		$size = false;

		if ( ! \in_array( 'size', $props['typography-deactivate-fields'], true ) ) {

			$size = ! empty( isset( $props['value']['size'] ) );

			if ( isset( $default[ $std_id ] ) ) {
				if ( isset( $default[ $std_id ]['size'] ) ) {
					$size = true;
				}
			} elseif ( isset( $default['std'] ) ) {
				if ( isset( $default['std']['size'] ) ) {
					$size = true;
				}
			}

			if ( $size && ! isset( $props['value']['size'] ) ) {
				$props['value']['size'] = $default['std']['size'];
			}
		}

		if ( $size ) {
			?>
            <div class="typo-field-container text-size-container">
                <label for="<?php echo $input_name; ?>-size"><?php esc_html_e( 'Font Size:', 'better-studio' ); ?></label>
                <div class="bf-field-with-suffix">
                    <input type="text" name="<?php echo $input_name; // escaped before ?>[size]"
                           value="<?php echo esc_attr( $props['value']['size'] ); ?>"
                           class="font-size <?php echo $input_class; // escaped before ?>"/><span
                            class='bf-prefix-suffix bf-suffix'><?php esc_html_e( 'Pixel', 'better-studio' ); ?></span>
                </div>
            </div>
<?php
        }

		$align = false;

		if ( ! \in_array( 'align', $props['typography-deactivate-fields'], true ) ) {

			$align = ! empty( isset( $props['value']['align'] ) );

			if ( isset( $default[ $std_id ] ) ) {
				if ( isset( $default[ $std_id ]['align'] ) ) {
					$align = true;
				}
			} elseif ( isset( $default['std'] ) ) {
				if ( isset( $default['std']['align'] ) ) {
					$align = true;
				}
			}

			if ( $align && ! isset( $props['value']['align'] ) ) {
				$props['value']['align'] = $default['std']['align'];
			}
		}

		if ( $align ) {
			?>
            <div class="bf-select-option-container typo-field-container text-align-container">
                <label
                        for="<?php echo $input_name; // escaped before ?>-align"><?php esc_html_e( 'Text Align:', 'better-studio' ); ?></label>
				<?php
				$aligns = [
					'inherit' => 'Inherit',
					'left'    => 'Left',
					'center'  => 'Center',
					'right'   => 'Right',
					'justify' => 'Justify',
					'initial' => 'Initial',
				];
				?>
                <select name="<?php echo $input_name; // escaped before ?>[align]"
                        class="<?php echo $input_class; // escaped before ?>"
                        id="<?php echo $input_name; ?>-align">
					<?php
                    foreach ( $aligns as $key => $align ) {
						echo '<option value="' . esc_attr( $key ) . '" ' . ( $key === $props['value']['align'] ? 'selected' : '' ) . '>' . esc_html( $align ) . '</option>';
					}
                    ?>
                </select>
            </div>
<?php } ?>

		<?php

		$transform = false;

		if ( ! \in_array( 'transform', $props['typography-deactivate-fields'], true ) ) {

			$transform = ! empty( isset( $props['value']['transform'] ) );

			if ( isset( $default[ $std_id ] ) ) {
				if ( isset( $default[ $std_id ]['transform'] ) ) {
					$transform = true;
				}
			} elseif ( isset( $default['std'] ) ) {
				if ( isset( $default['std']['transform'] ) ) {
					$transform = true;
				}
			}

			if ( $transform && ! isset( $props['value']['transform'] ) ) {
				$props['value']['transform'] = $default['std']['transform'];
			}
		}

		if ( $transform ) {
			?>
            <div class="bf-select-option-container typo-field-container text-transform-container">
                <label for="<?php echo $input_name; // escaped before ?>-transform"><?php esc_html_e( 'Text Transform:', 'better-studio' ); ?></label>
				<?php
				$transforms = [
					'none'       => 'None',
					'capitalize' => 'Capitalize',
					'lowercase'  => 'Lowercase',
					'uppercase'  => 'Uppercase',
					'initial'    => 'Initial',
					'inherit'    => 'Inherit',
				];
				?>
                <select name="<?php echo $input_name; // escaped before ?>[transform]"
                        id="<?php echo $input_name; // escaped before ?>-transform"
                        class="text-transform <?php echo $input_class; // escaped before ?>">
					<?php
                    foreach ( $transforms as $key => $transform ) {
						echo '<option value="' . esc_attr( $key ) . '" ' . ( $key == $props['value']['transform'] ? 'selected' : '' ) . '>' . esc_html( $transform ) . '</option>';
					}
                    ?>
                </select>
            </div>
<?php } ?>

		<?php

		//
		// Line Height
		//
		$line_height    = false;
		$line_height_id = '';

		if ( ! in_array( 'line-height', $props['typography-deactivate-fields'], true ) ) {

			if ( isset( $default[ $std_id ] ) ) {
				if ( isset( $default[ $std_id ]['line-height'] ) ) {
					$line_height_id = 'line-height';
					$line_height    = true;
				} elseif ( isset( $default[ $std_id ]['line_height'] ) ) {
					$line_height_id = 'line_height';
					$line_height    = true;
				}
			} elseif ( isset( $default['std'] ) ) {
				if ( isset( $default['std']['line-height'] ) ) {
					$line_height_id = 'line-height';
					$line_height    = true;
				} elseif ( isset( $default['std']['line_height'] ) ) {
					$line_height_id = 'line_height';
					$line_height    = true;
				}
			} elseif ( ! empty( isset( $props['value']['line-height'] ) ) ) {

				$line_height_id = 'line-height';
				$line_height    = true;

			} elseif ( ! empty( isset( $props['value']['line_height'] ) ) ) {
				$line_height_id = 'line_height';
				$line_height    = true;
			}

			if ( $line_height && ! isset( $props['value'][ $line_height_id ] ) ) {
				$props['value'][ $line_height_id ] = $default['std'][ $line_height_id ];
			}
		}

		if ( $line_height ) {
			?>
            <div class="typo-field-container text-height-container">
                <label for="<?php echo $input_name, '-', $line_height_id; ?>"><?php esc_html_e( 'Line Height:', 'better-studio' ); ?></label>
                <div class="bf-field-with-suffix ">
                    <input type="text"
                           id="<?php echo $input_name, '-', $line_height_id; ?>"
                           name="<?php echo $input_name; // escaped before ?>[<?php echo esc_attr( $line_height_id ); ?>]"
                           value="<?php echo esc_attr( $props['value'][ $line_height_id ] ); ?>"
                           class="line-height <?php echo $input_class; // escaped before ?>"/>
                    <span class='bf-prefix-suffix bf-suffix'><?php esc_html_e( 'Pixel', 'better-studio' ); ?></span>
                </div>
            </div>
<?php
        }

		//
		// Letter Spacing
		//

		$letter_spacing = false;

		if ( ! \in_array( 'letter-spacing', $props['typography-deactivate-fields'], true ) ) {

			$letter_spacing = ! empty( $props['value']['letter-spacing'] );

			if ( isset( $default[ $std_id ] ) ) {
				if ( isset( $default[ $std_id ]['letter-spacing'] ) ) {
					$letter_spacing = true;
				}
			} elseif ( isset( $default['std'] ) ) {
				if ( isset( $default['std']['letter-spacing'] ) ) {
					$letter_spacing = true;
				}
			}

			if ( $letter_spacing && ! isset( $props['value']['letter-spacing'] ) ) {
				$props['value']['letter-spacing'] = $default['std']['letter-spacing'];
			}
		}

		if ( $letter_spacing ) {
			?>
            <div class="typo-field-container text-height-container">
                <label><?php esc_html_e( 'Letter Spacing:', 'better-studio' ); ?></label>
                <input type="text" name="<?php echo $input_name; // escaped before ?>[letter-spacing]"
                       value="<?php echo esc_attr( $props['value']['letter-spacing'] ); ?>"
                       class="letter-spacing <?php echo $input_class; // escaped before ?>"
                       placeholder="<?php _e( 'Use unite px, em...' ); ?>"/>
            </div>
<?php
		}
		?>

        <div class="bf-select-option-container typo-field-container">
            <label
                    for="<?php echo $input_name; // escaped before ?>-subset"><?php esc_html_e( 'Font Character Set:', 'better-studio' ); ?></label>
            <select name="<?php echo $input_name; // escaped before ?>[subset]"
                    id="<?php echo $input_name; // escaped before ?>-subset"
                    class="font-subsets <?php echo $input_class; // escaped before ?>">

				<?php echo $props['subset_options'] ?? ''; ?>
            </select>
        </div>

		<?php

		//
		// Color field
		//
		$color = false;

		if ( ! \in_array( 'color', $props['typography-deactivate-fields'], true ) ) {

			$color = ! empty( $props['value']['color'] );

			if ( isset( $default[ $std_id ] ) ) {
				if ( isset( $default[ $std_id ]['color'] ) ) {
					$color = true;
				}
			} elseif ( isset( $default['std'] ) ) {
				if ( isset( $default['std']['color'] ) ) {
					$color = true;
				}
			}

			if ( $color && ! isset( $props['value']['color'] ) ) {
				$props['value']['color'] = $default['std']['color'];
			}
		}

		if ( $color ) {
			?>
            <div class="typo-field-container text-color-container">
                <label><?php esc_html_e( 'Color:', 'better-studio' ); ?></label>
                <div class="bs-color-picker-wrapper">

                    <div class="wp-picker-container bs-color-placeholder">
                        <button type="button" class="button wp-color-result" aria-expanded="false">
                              <span class="wp-color-result-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15.999"
                                     viewBox="0 0 16 15.999">
                                      <g id="noun-eyedropper-900243" transform="translate(-150.474 -80.472)">
                                        <path id="Path_93" data-name="Path 93"
                                              d="M166.474,84.124l-3.652-3.652-3.969,3.969-1.111-1.111-1.111,1.111,1.111,1.111-5.874,5.874-.318,2.223-.635.634.008.008a1.111,1.111,0,0,0-.088.071,1.235,1.235,0,1,0,1.746,1.746c.027-.027.047-.058.071-.088l.008.008.635-.635,2.222-.317,5.874-5.874,1.111,1.111,1.111-1.111L162.5,88.092Zm-9.123,6.9h-2.857l4.043-4.043,1.429,1.429Z"
                                              transform="translate(0)" fill="#2c3338"/>
                                      </g>
                                    </svg>
                                </span>

                            <span class="color-alpha-wrapper"
                                  style="background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAAHnlligAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHJJREFUeNpi+P///4EDBxiAGMgCCCAGFB5AADGCRBgYDh48CCRZIJS9vT2QBAggFBkmBiSAogxFBiCAoHogAKIKAlBUYTELAiAmEtABEECk20G6BOmuIl0CIMBQ/IEMkO0myiSSraaaBhZcbkUOs0HuBwDplz5uFJ3Z4gAAAABJRU5ErkJggg==');">
                            <span class="color-alpha"
                            <?php
                            if ( ! empty( $props['value']['color'] ) ) {
								?>
                                 style="background-color: <?php echo esc_attr( $props['value']['color'] ); ?>;"<?php } ?>></span>
                        </span>
                        </button>
                    </div>

                    <input type="text" name="<?php echo $input_name;  // escaped before ?>[color]" value="
                                                        <?php
														echo esc_attr( $props['value']['color'] ?? '' )
														?>
                    " class="bs-color-picker-value color-picker <?php echo $input_class; // escaped before ?>"
                           data-alpha-enabled="true" data-alpha-color-type="hex">
                </div>
            </div>
<?php } ?>
    </div>

	<?php if ( isset( $props['preview'] ) && $props['preview'] ) { ?>
        <div class="bf-explain bf-nonrepeater-explain bf-explain-typography-option bf-clearfix">
			<?php if ( isset( $props['desc'] ) && ! empty( $props['desc'] ) ) { ?>
                <div class="typography-desc">
					<?php echo wp_kses( $props['desc'], \BetterFrameworkPackage\Component\Control\allowed_html() ); ?>
                </div>
			<?php } ?>

            <a class="load-preview-texts" href="javascript: void(0);"><?php esc_html_e( 'Load Preview', 'better-studio' ); ?></a>

			<?php if ( function_exists( 'bf_get_option' ) ) { ?>
                <div class="typography-preview">
                    <ul class="preview-tab bf-clearfix">
                        <li class="tab <?php echo $props['preview_tab'] === 'title' ? 'current' : ''; ?>"
                            data-tab="title"><a
                                    href="javascript: void(0);"><?php esc_html_e( 'Heading', 'better-studio' ); ?></a>
                        </li>
                        <li class="tab <?php echo $props['preview_tab'] === 'paragraph' ? 'current' : ''; ?>"
                            data-tab="paragraph"><a
                                    href="javascript: void(0);"><?php esc_html_e( 'Paragraph', 'better-studio' ); ?></a>
                        </li>
                        <li class="tab <?php echo $props['preview_tab'] === 'divided' ? 'current' : ''; ?>"
                            data-tab="divided"><a
                                    href="javascript: void(0);"><?php esc_html_e( 'Divided', 'better-studio' ); ?></a>
                        </li>
                    </ul>

                    <p class="preview-text <?php echo $props['preview_tab'] === 'title' ? 'current' : ''; ?> title">
						<?php
                        if ( ! empty( $props['preview_text'] ) ) {
							echo esc_html( $props['preview_text'] );
						} else {
							echo bf_get_option( 'typo_text_heading', 'better-framework-custom-fonts' );
						}
                        ?>
                    </p>
                    <p class="preview-text paragraph <?php echo $props['preview_tab'] === 'paragraph' ? 'current' : ''; ?>">
						<?php
                        if ( ! empty( $props['preview_text'] ) ) {
							echo esc_html( $props['preview_text'] );
						} else {
							echo bf_get_option( 'typo_text_paragraph', 'better-framework-custom-fonts' );
						}
                        ?>
                    </p>

                    <p class="preview-text divided <?php echo $props['preview_tab'] === 'divided' ? 'current' : ''; ?>">
						<?php
                        if ( ! empty( $props['preview_text'] ) ) {
							echo esc_html( $props['preview_text'] );
						} else {
							echo bf_get_option( 'typo_text_divided', 'better-framework-custom-fonts' );

						}
                        ?>
                    </p>
                </div>
			<?php } ?>
        </div>
	<?php } ?>
</div>
