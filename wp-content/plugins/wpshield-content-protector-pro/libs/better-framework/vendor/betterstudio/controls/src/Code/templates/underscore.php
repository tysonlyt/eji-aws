<?php // phpcs:ignoreFile ?>
<#
    var esc_textarea = function(text) {

        text = text.toString();
        text = text.replace(/</g,'&lt;').replace(/>/g,'&gt;');
        text = text.replace(/"/g,'&quot;').replace(/'/g,'&#039;');

        return text;
    };

    var language_attr = function(language){

        switch ( language ) {

            case 'javascript' :
            case  'json' :
            case  'js' :
                return 'text/javascript';
            break;

            case 'php':
                return 'application/x-httpd-php';
            break;

            case 'css':
                return 'text/css';
            break;

            case 'sql':
                return 'text/x-sql';
            break;

            case 'xml' :
            case  'html' :
            default:
                return 'text/html';
            break;
        }
    };
#>
<div {{{ utils.container_attributes(props, "bs-control-code") }}}>
<textarea class="bf-code-editor {{ props.input_class }}"
          data-lang="{{ language_attr(props.language) }}"
          data-setting="{{ props.input_name }}"
          placeholder="{{ props.placeholder }}"
          data-line-numbers="{{ !_.isEmpty(props.line_numbers) ? 'enable' : 'disable' }}"
          data-auto-close-brackets="{{ !_.isEmpty(props.auto_close_brackets) ? 'enable' : 'disable' }}"
          data-auto-close-tags="{{ !_.isEmpty(props.auto_close_tags) ? 'enable' : 'disable' }}"
>{{ esc_textarea(props.value || "") }}</textarea>
</div>