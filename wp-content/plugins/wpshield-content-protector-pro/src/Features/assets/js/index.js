import RightClickAddons from "../../RightClick/js/right-click-addons";
import TextCopyAddons from "../../TextCopy/js/text-copy-addons";
import DeveloperToolsAddons from "../../DeveloperTools/js/developer-tools-addons";
import IframeAddons from "../../Iframe/js/iframe-addons";
import ExtensionsAddons from "../../Extensions/js/extensions-addons";
import Helper from "./Helper";
import PrintAddons from "../../PrintAddon/js/print-addons";
import PopupMessage from "../../../../../wpshield-content-protector/src/Components/Addons/PopupMessage/js/popup-message";
import IDMExtensionAddons from "../../IDMExtension/js/idm-extension-addons";

class ContentProtectorPro {

    // Note: order is important
    activeKeys = {
        cmd: false,
        ctrl: false,
        alt: false,
        shift: false
    };

    constructor() {

        new RightClickAddons();
        new TextCopyAddons();
        new DeveloperToolsAddons();
        new IframeAddons();
        new PrintAddons();

        window.addEventListener('extensions-protector', this.disableAntiProtector.bind(this), false);

        window.addEventListener(
            'extensions-protector-popup-alert',
            (event) => this.handleAlert(event,'extensions'),
            false
        );
        window.addEventListener(
            'idm-extension-protector-popup-alert',
            (event) => this.handleAlert(event,'idm-extension'),
            false
        );

        new ExtensionsAddons();

        new IDMExtensionAddons();
    }

    disableAntiProtector(event) {

        if (!event || !event.detail || !event.detail.event) return false;
        if (!ExtensionsL10n.options || !ExtensionsL10n['disabled-shortcuts']) return false;

        let helper = new Helper();

        if (!helper.filter('extensions', event.detail.event, ExtensionsL10n.options['extensions/filters'])) {

            return false;
        }

        //inputs and textarea fields are include in protection!
        let inputTags = document.querySelectorAll('input[type="text"],textarea');

        inputTags && inputTags.forEach(input => {

            if ('select' === event.detail.event.type) {

                event.detail.event.preventDefault();

                input.classList.add('cp-no-select');
                input.selectionStart = input.selectionEnd;
            }
        });

        let code;
        let _event = event.detail.event;

        switch (_event.type) {
            case 'keydown':
                code = _event.keyCode ? _event.keyCode : _event.which;

                if (code === 16) {

                    this.activeKeys.shift = true;

                } else if (code === 17) {

                    this.activeKeys.ctrl = true;

                } else if (code === 18) {

                    this.activeKeys.alt = true;

                } else if (code === 91) {

                    this.activeKeys.cmd = true;

                } else {

                    let current = '';

                    for (let key in this.activeKeys) {

                        if (this.activeKeys[key]) {

                            current += key;
                            current += '_';
                        }
                    }

                    current += String.fromCharCode(code);
                    current = current.toLowerCase();

                    if (ExtensionsL10n['disabled-shortcuts'].includes(current)) {

                        _event.preventDefault();
                    }
                }
                break;
            case 'keyup':
                code = event.keyCode ? event.keyCode : event.which;

                if (code === 16) {
                    this.activeKeys.shift = false;
                }

                if (code === 17) {
                    this.activeKeys.ctrl = false;
                }

                if (code === 18) {
                    this.activeKeys.alt = false;
                }

                if (code === 91) {
                    this.activeKeys.cmd = false;
                }
                break;
            case 'selectstart':
            //Disable text selection.
            case 'copy':
            //Disable text copy.
            case 'cut':
                //Disable text move.

                _event.preventDefault();

                break;
        }
    }

    addUserSelectCSS() {

        let cssSelectTextCSS = document.createElement("style");

        cssSelectTextCSS.type = 'text/css';
        cssSelectTextCSS.innerText = `* {
		    -webkit-user-select: none !important;
		    -moz-user-select: none !important;
		    -ms-user-select: none !important;
		    user-select: none !important;
	    }
	    textarea:selection{
	        background-color: transparent !important;
	    }
	`;

        if (-1 === document.head.innerHTML.indexOf(cssSelectTextCSS.outerHTML)) {
            document.head.appendChild(cssSelectTextCSS);
        }
    }

    handleCssProtector() {

        let head = document.head;

        if (-1 !== head.innerHTML.indexOf('user-select: none !important;')) {

            return false;
        }

        this.addUserSelectCSS();
    }

    /**
     * Handle popup alert.
     *
     * @param event
     * @return {boolean}
     */
    handleAlert(event,id) {

        const instance = 'idm-extension' === id ? IDMExtensionL10n : ExtensionsL10n;

        if ('undefined' === typeof instance || !instance.options || !Object.keys(instance.options).length) return false;

        new PopupMessage(id,
            {
                event: event.detail.event,
                enable: instance.options[`${id}/alert-popup`],
                protectionType: instance.options[`${id}/type`],
                text: instance.options[`${id}/alert-popup/text`],
                title: instance.options[`${id}/alert-popup/title`],
                template: instance.options[`${id}/alert-popup/template`],
                closeDelay: event.detail.delay ?? 0,
            }
        );
    }
}

new ContentProtectorPro();
