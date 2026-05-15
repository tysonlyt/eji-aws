<?php

/**
 * @var array $props
 */

?>

<div {{{ utils.container_attributes(props, "bs-control-slider") }}}>

    <div class="bf-slider-slider"
         data-dimension="{{ props.dimension }}"
         data-animation="{{ _.isEmpty( props.animation ) ? 'disable' : 'enable' }}"
         data-val="{{Number( props.value||0 )}}"
         data-min="{{Number( props.min )}}"
         data-max="{{Number( props.max||100 )}}"
         data-step="{{Number( props.step||1 )}}"
    ></div>
    <input type="hidden" value="{{Number( props.value )}}"
           data-setting="{{ props.input_name }}"
           class="bf-slider-input {{props.input_class}}"
    >
</div>
