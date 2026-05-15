<?php // phpcs:ignoreFile ?>
<#
	var htmlSpecialCharsDecode = function (str) {

		if (str == null) return '';

		return String(str).
			replace('&amp;', '&').
			replace('&lt;', '<').
			replace('&gt;', '>').
			replace('&quot;', '"').
			replace('&#039;', '\'').
			replace('&lsqb;', '[').
			replace('&rsqb;', ']').
			replace('&Hat;', '^').
			replace('&sol;', '/').
			replace('&lpar;', '(').
			replace('&rpar;', ')').
			replace('&plus;', '+').
			replace('&nbsp;', ' ').
			replace('&copy;', '©');
	};

	var input_class = props.input_class || "";

	if( props.rtl ) {

		input_class += " rtl";
	}

	if( props.ltr ) {

		input_class += " ltr";
	}

	var value = props["special-chars"] ? htmlSpecialCharsDecode( props.value ) : props.value;
	var wrapper_classes = ["bs-control-text", input_class];

	if( props.prefix ) {
		wrapper_classes.push("bf-field-with-prefix");
	}

	if( props.suffix ) {

		wrapper_classes.push("bf-field-with-suffix");
	}

	var input_type = props.input_type ? props.input_type : "text";
#>

<div {{{ utils.container_attributes(props, wrapper_classes) }}}>

	<# if(! _.isEmpty( props.prefix ) ) { #>
		<span class='bf-prefix-suffix'>{{{ props.prefix }}}</span>
	<# } #>

	<input type="{{ input_type }}" data-setting="{{ props.input_name }}"
	       class="{{ input_class }}"
	       placeholder="{{ props.placeholder }}"
	       value="{{ value }}"
	>

	<# if(! _.isEmpty( props.suffix ) ) { #>
		<span class='bf-prefix-suffix'>{{{ props.suffix }}}</span>
	<# } #>

</div>

