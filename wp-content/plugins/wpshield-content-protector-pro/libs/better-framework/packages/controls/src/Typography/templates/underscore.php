<#
	var value = props.value;

	if(_.isString(value)){

		value = {};
	}

	if(! value.family ) {

		_.extend(value, {
			family:"Lato",
			variant: "",
		});
	}

	//FIXME
	var _default = {};
	var std_id = '';
	var enabled = false;

	if(_default[std_id]) {

		if(_default[std_id].enable) {

			enabled = false;
		}
	} else if( _default.std ) {

		if( _default.std.enable ) {

			enabled = false;
		}
	}

	if( enabled && typeof value.enable === "undefined" ) {

		value.enable = _default.std && _default.std.enable;
	}

	if( typeof props.preview_tab === "undefined" ) {

		props.preview_tab = "title";
	}

    var deactivateFields = props['typography-deactivate-fields'] || [];
#>

<div {{{ utils.container_attributes(props, "bs-control-typography") }}}>

	<# if ( enabled ) { #>
		<div class="typo-fields-container bf-clearfix">
			<div class="typo-field-container">
				<div class="typo-enable-container">'
					<div class="bf-switc">
						<label
							class="cb-enable {{ value.enable ? 'selected' : '' }}"><span><?php esc_html_e( 'Enable', 'better-studio' ); ?></span></label>
						<label
							class="cb-disable {{ ! value.enable ? 'selected' : '' }}"><span><?php esc_html_e( 'Disable', 'better-studio' ); ?></span></label>

						<input type="hidden" data-setting="{{ props.input_name }}"
						       value="{{ Number( value.enable || 0 ) }}"
						       class="checkbox {{ props.input_class }}"/>
					</div>
				</div>
			</div>
		</div>
	<# } #>

	<div class="typo-fields-container bf-clearfix">
		<span class="enable-disable"></span>
		<div class="typo-field-container font-family-container bf-select-option-container">
			<label><?php esc_html_e( 'Font Family:', 'better-studio' ); ?></label>
			<div class="select-placeholder bf-font-selector">
				{{ value.family ||  ' - ' }}
			</div>
			<input type="hidden"  data-setting="{{ props.input_name }}"
			       value="{{ value.family }}"
			       class="bf-font-family {{ props.input_class }}">
		</div>

		<div class="bf-select-option-container typo-field-container">
			<label for="{{ props.input_name }}-variants"><?php esc_html_e( 'Font Weight:', 'better-studio' ); ?></label>
			<select data-setting="{{ props.input_name }}"
			        id="{{ props.input_name }}-variants"
			        class="font-variants {{ props.input_class }}">

                {{{ props.variants_options }}}
			</select>
		</div>

		<div class="bf-select-option-container typo-field-container">
			<label
				for="{{ props.input_name }}-subset"><?php esc_html_e( 'Font Character Set:', 'better-studio' ); ?></label>
			<select data-setting="{{ props.input_name }}"
			        id="{{ props.input_name }}-subset"
			        class="font-subsets {{ props.input_class }}">

			    {{{ props.subset_options }}}
			</select>
		</div>

		<#
			var align = false;

            if( deactivateFields.indexOf('align') === -1 ) {

                if( _default[std_id] ) {

                    if(_default[std_id].align) {

                        align = true;
                    }
                } else if(_default.std) {

                    if( _default.std.align ) {

                        align = true;
                    }
                }

                if( align && typeof value.align === "undefined"  ) {

                    value.align = _default.std && _default.std.align;
                }
            }
		#>

        <# if( align ) { #>

            <div class="bf-select-option-container  typo-field-container text-align-container">
                <label
                        for="{{ props.input_name }}-align"><?php esc_html_e( 'Text Align:', 'better-studio' ); ?></label>
                <#
                    var aligns = {
                        'inherit' : 'Inherit',
                        'left'    : 'Left',
                        'center'  : 'Center',
                        'right'   : 'Right',
                        'justify' : 'Justify',
                        'initial' : 'Initial',
                    };
                #>
                <select data-setting="{{ props.input_name }}"
                        class="{{ props.input_class }}"
                        id="{{ props.input_name }}-align">
                    <# for( var key in aligns ) {
                        var selected = key === value.align ? 'selected' : '';
                    #>
                        <option value="{{ key }}" {{ selected }}>{{ aligns[key] }}</option>
                    <# } #>
                </select>
            </div>
        <# } #>

        <#
            var transform = false;

            if( deactivateFields.indexOf('transform') === -1 ) {

                if( _default[std_id] ) {

                    if(_default[std_id].transform) {

                        transform = true;
                    }
                } else if(_default.std) {

                    if( _default.std.transform ) {

                        transform = true;
                    }
                }


                if( transform && typeof value.transform === "undefined" ) {

                    value.transform = _default.std && _default.std.transform;
                }
            }
        #>

        <# if( transform ) { #>

            <div class="bf-select-option-container typo-field-container text-transform-container">
                <label for="{{ props.input_name }}-transform"><?php esc_html_e( 'Text Transform:', 'better-studio' ); ?></label>

                <#
                    var transforms = {
                        'none' : 'None',
                        'capitalize' : 'Capitalize',
                        'lowercase' : 'Lowercase',
                        'uppercase' : 'Uppercase',
                        'initial' : 'Initial',
                        'inherit' : 'Inherit',
                    };
                #>

                <select data-setting="{{ props.input_name }}"
                        id="{{ props.input_name }}-transform"
                        class="text-transform {{ props.input_class }}">
                    <# for( var key in transforms ) {
                        var selected = key === value.transform ? 'selected' : '';
                    #>
                      <option value="{{ key }}" {{ selected }}>{{ transforms[key] }}</option>
                    <# } #>
                </select>
            </div>
        <# } #>


        <#
            var size = false;

            if( deactivateFields.indexOf('size') === -1 ) {

                if( _default[std_id] ) {

                    if( _default[std_id].size ) {

                        size = true;
                    }
                } else if( _default.std ) {

                    if( _default.std.size ) {

                        size = true;
                    }
                }

                if( size && typeof value.size === "undefined" ) {

                    value.size = _default.std && _default.std.size;
                }
            }
        #>

        <# if( size ) { #>
            <div class="typo-field-container text-size-container">
                <label for="{{ props.input_name }}-size"><?php esc_html_e( 'Font Size:', 'better-studio' ); ?></label>
                <div class="bf-field-with-suffix">
                    <input type="text" data-setting="{{ props.input_name }}"
                           value="{{ value.size }}"
                           class="font-size {{ props.input_class }}"/>
                    <span class='bf-prefix-suffix bf-suffix'><?php esc_html_e( 'Pixel', 'better-studio' ); ?></span>
                </div>
            </div>
        <# } #>


        <#
            // Line Height

            var line_height = false;
            var line_height_id = '';

            if( deactivateFields.indexOf('line-height') === -1 ) {

                if( _default[std_id] ) {

                    if( _default[std_id]['line-height'] ) {

                        line_height_id = 'line-height';
                        line_height = true;

                    } else if( _default[std_id]['line_height'] ) {

                        line_height_id = 'line_height';
                        line_height = true;
                    }
                } else if( _default.std ) {

                    if( _default.std['line-height'] ) {

                        line_height_id = 'line-height';
                        line_height = true;

                    }else if( _default.std['line_height'] ) {

                        line_height_id = 'line_height';
                        line_height = true;
                    }
                }

                if( line_height && typeof value[line_height_id] === "undefined" ) {

                    value[line_height_id] = _default.std && _default.std[line_height_id];
                }
            }
        #>

        <# if( line_height ) { #>
            <div class="typo-field-container text-height-container">
                <label for="{{ props.input_name }}-{{ line_height_id }}"><?php esc_html_e( 'Line Height:', 'better-studio' ); ?></label>
                <div class="bf-field-with-suffix">
                    <input type="text" id="{{ props.input_name }}-{{ line_height_id }}"
                           data-setting="{{ props.input_name }}"
                           value="{{ value[line_height_id] }}"
                           class="line-height {{ props.input_class }}"/>
                    <span class='bf-prefix-suffix bf-suffix'><?php esc_html_e( 'Pixel', 'better-studio' ); ?></span>
                </div>
            </div>
        <# } #>


        <#
            // Letter Spacing

            var letter_spacing = false;

            if( deactivateFields.indexOf('letter-spacing') === -1 ) {
                if( _default[std_id] ) {

                    if( _default[std_id]['letter-spacing'] ) {

                        letter_spacing = true;
                    }
                } else if( _default.std ) {

                    if( _default.std['letter-spacing'] ) {

                        letter_spacing = true;
                    }
                }

                if( letter_spacing && typeof value["letter-spacing"] === "undefined" ) {

                    value["letter-spacing"] = _default.std && _default.std["letter-spacing"];
                }
            }
        #>

        <# if( letter_spacing ) { #>

            <div class="typo-field-container text-height-container">
                <label><?php esc_html_e( 'Letter Spacing:', 'better-studio' ); ?></label>
                <input type="text" data-setting="{{ props.input_name }}"
                       value="{{ value['letter-spacing'] }}"
                       class="letter-spacing {{ props.input_class }}"
                       placeholder="<?php _e( 'Use unite px, em...' ); ?>"/>
            </div>
        <# } #>


        <#
            // Color field

            var color = false;

            if( deactivateFields.indexOf('color') === -1 ) {

                if( _default[std_id] ) {

                    if( _default[std_id].color ) {

                        color = true;
                    }
                } else if(_default.std) {

                    if( _default.std.color ) {

                        color = true;
                    }
                }

                if( color && typeof value.color === "undefined" ) {

                    value.color = _default.std && _default.std.color;
                }
            }
        #>

        <# if(color) { #>
            <div class="typo-field-container text-color-container">
                <label><?php esc_html_e( 'Color:', 'better-studio' ); ?></label>
                <div class="bs-color-picker-wrapper">

                    <div class="wp-picker-container bs-color-placeholder">
                        <button type="button" class="button wp-color-result" aria-expanded="false">
                            <span class="wp-color-result-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15.999" viewBox="0 0 16 15.999">
                                  <g id="noun-eyedropper-900243" transform="translate(-150.474 -80.472)">
                                    <path id="Path_93" data-name="Path 93"
                                          d="M166.474,84.124l-3.652-3.652-3.969,3.969-1.111-1.111-1.111,1.111,1.111,1.111-5.874,5.874-.318,2.223-.635.634.008.008a1.111,1.111,0,0,0-.088.071,1.235,1.235,0,1,0,1.746,1.746c.027-.027.047-.058.071-.088l.008.008.635-.635,2.222-.317,5.874-5.874,1.111,1.111,1.111-1.111L162.5,88.092Zm-9.123,6.9h-2.857l4.043-4.043,1.429,1.429Z"
                                          transform="translate(0)" fill="#2c3338"/>
                                  </g>
                                </svg>
                            </span>
                            <span class="color-alpha-wrapper" style="background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAAHnlligAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHJJREFUeNpi+P///4EDBxiAGMgCCCAGFB5AADGCRBgYDh48CCRZIJS9vT2QBAggFBkmBiSAogxFBiCAoHogAKIKAlBUYTELAiAmEtABEECk20G6BOmuIl0CIMBQ/IEMkO0myiSSraaaBhZcbkUOs0HuBwDplz5uFJ3Z4gAAAABJRU5ErkJggg==');">
                                    <span class="color-alpha"<# if(!_.isEmpty(value.color)) { #> style="background-color: {{ value.color }}" <# }#>></span>
                            </span>
                        </button>
                    </div>

                    <input type="text" data-setting="{{ props.input_name }}"
                           value="{{ value.color }}" class="bs-color-picker-value color-picker {{ props.input_class }}"
                           data-alpha-enabled="true" data-alpha-color-type="hex">
                </div>
            </div>
        <# } #>

	</div>

    <# if( props.preview ) { #>
		<div class="bf-explain bf-nonrepeater-explain bf-explain-typography-option bf-clearfix">
			<# if ( props.desc ) { #>
				<div class="typography-desc">
					{{{ props.desc }}}
				</div>
            <# } #>

			<a class="load-preview-texts"
			   href="javascript: void(0);"><?php esc_html_e( 'Load Preview', 'better-studio' ); ?></a>

			<?php if ( function_exists( 'bf_get_option' ) ) { ?>
                <div class="typography-preview">
                    <ul class="preview-tab bf-clearfix">
                        <li class="tab {{ props.preview_tab === 'title' ? 'current' : '' }}"
                            data-tab="title"><a
                                    href="javascript: void(0);"><?php esc_html_e( 'Heading', 'better-studio' ); ?></a>
                        </li>
                        <li class="tab {{ props.preview_tab === 'paragraph' ? 'current' : '' }}"
                            data-tab="paragraph"><a
                                    href="javascript: void(0);"><?php esc_html_e( 'Paragraph', 'better-studio' ); ?></a>
                        </li>
                        <li class="tab {{ props.preview_tab === 'divided' ? 'current' : '' }}"
                            data-tab="divided"><a
                                    href="javascript: void(0);"><?php esc_html_e( 'Divided', 'better-studio' ); ?></a>
                        </li>
                    </ul>

                    <p class="preview-text {{ props.preview_tab === 'title' ? 'current' : '' }} title">
                        <# if ( props.preview_text ) { #>
                        {{ props.preview_text }}
                        <# } else { #>
						<?php echo bf_get_option( 'typo_text_heading', 'better-framework-custom-fonts' ); ?>
                        <# } #>
                    </p>
                    <p class="preview-text paragraph {{ props.preview_tab === 'paragraph' ? 'current' : '' }}">

                        <# if ( props.preview_text ) { #>
                        {{ props.preview_text }}
                        <# } else { #>
						<?php echo bf_get_option( 'typo_text_paragraph', 'better-framework-custom-fonts' ); ?>
                        <# } #>

                    </p>

                    <p class="preview-text divided {{ props.preview_tab === 'divided' ? 'current' : '' }}">

                        <# if ( props.preview_text ) { #>
                        {{ props.preview_text }}
                        <# } else { #>
						<?php echo bf_get_option( 'typo_text_divided', 'better-framework-custom-fonts' ); ?>
                        <# } #>
                    </p>
                </div>
			<?php } ?>
        </div>
	<# } #>
</div>
