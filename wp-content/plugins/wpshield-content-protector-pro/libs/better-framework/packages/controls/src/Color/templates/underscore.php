<?php // phpcs:ignoreFile ?>
<div {{{ utils.container_attributes(props, "bs-color-picker-wrapper") }}}>

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
                <span class="color-alpha"<# if(!_.isEmpty(props.value)) { #> style="background-color: {{ props.value }}" <# }#>></span>
            </span>
    </button>
</div>

<input type="text"
       value="{{ props.value }}"
       data-setting="{{ props.input_name }}"
       data-alpha-enabled="true" data-alpha-color-type="hex"
       class="bs-color-picker-value color-picker {{ props.input_class }}" />
</div>

