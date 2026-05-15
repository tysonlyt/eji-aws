<?php
$is_active = bf_is_product_registered();
?>
<script type="text/html" id="tmpl-install-demo-modal">

    <div class="bs-modal-default" {{#inline_style}} style="{{inline_style}}" {{/inline_style}}>
    {{#close_button}}
    <a href="#" class="bs-close-modal">
        <i class="fa fa-times" aria-hidden="true"></i>
    </a>
    {{/close_button}}
    <div class="bs-modal-header-wrapper bs-modal-clearfix">
        <h2 class="bs-modal-header">
            {{#icon}}
            <i class="fa {{icon}}"></i>
            {{/icon}}

            {{header}}
        </h2>
    </div>

    <div class="bs-modal-body bf-clearfix">

        <div class="bs-modal-image bf-clearfix" {{#image_align}} style="float:{{image_align}}" {{
        /image_align}}>

        <img src="{{image_src}}" {{#image_style}} style="{{image_style}}" {{/image_style}}/>

        {{#image_caption}}
        <div class="bs-modal-image-caption">
            {{image_caption}}
        </div>
        {{/image_caption}}
    </div>
    {{{bs_body}}}

	<?php if ( ! $is_active ) : ?>
        <span class="active-error"><?php _e( 'Please register your theme', 'better-studio' ); ?></span>
	<?php endif ?>
    </div>

    {{#bs_buttons}}
    <div class="bs-modal-bottom bs-modal-buttons-left bs-modal-clearfix">
        {{{bs_buttons}}}

        {{#isMultiLingual}}

        <div class="bs-modal-dropdown">
            <select name="demo_lang" class="demo_lang">
                {{#languages}}
                <option value="{{id}}">{{name}}</option>
                {{/languages}}
            </select>
        </div>
        {{/isMultiLingual}}

        {{#checkbox}}
        <div class="bs-modal-checkbox">
            <input type="checkbox" name="include_content" class="toggle-content" value="1" checked="checked"> <label
                    class="checkbox-label"><?php _e( 'Include content', 'better-studio' ) ?></label>
        </div>
        {{/checkbox}}
    </div>
    {{/bs_buttons}}
    </div>
</script>
