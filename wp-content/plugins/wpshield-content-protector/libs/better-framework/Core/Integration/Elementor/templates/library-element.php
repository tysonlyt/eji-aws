<?php // phpcs:ignoreFile ?>
<script type="text/template" id="tmpl-elementor-element-library-element">
	<#
	var isURL = function (string) {
        var url;

        try {
            url = new URL(string);
        } catch (_) {
            return false;
        }

        return url.protocol === "http:" || url.protocol === "https:";
	};

    var isBetterFrameworkWidget = typeof widgetType !== 'undefined' && typeof  elementor.widgetsCache !== 'undefined'
    && elementor.widgetsCache[widgetType] &&  elementor.widgetsCache[widgetType].bf_widget;
    #>
	<div class="elementor-element {{ isBetterFrameworkWidget ? "bf-elementor-element "+widgetType : "" }}">
		<# if ( false === obj.editable ) { #>
		<i class="eicon-lock"></i>
		<# } #>
		<div class="icon">
			<# if( isURL(icon) ) { #>
			    <img src="{{ icon }}" width="28">
			<# } else { #>
			    <i class="{{ icon }}" aria-hidden="true"></i>
			<# } #>
		</div>
		<div class="elementor-element-title-wrapper">
			<div class="title">{{{ title }}}</div>
		</div>
	</div>
</script>