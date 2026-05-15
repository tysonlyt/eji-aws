import {DevToolsHelper, CheckTK, tk} from "./helper";
import {initDetectors} from "./detector/detector";
import { __ } from '@wordpress/i18n'

export default class DeveloperToolsAddons {

    props = {
        url: null,
        clearLog: false,
        disableMenu: false,
    };

    constructor() {

        this.pageCleaner = this.pageCleaner.bind(this);

        if ('disable' === this.l10nOptions('developer-tools')) {

            return;
        }

        switch (this.l10nOptions('developer-tools/type')) {

            case 'blank':

                this.clearPageContent();

                break;

            case 'redirect':

                this.redirect();

                break;

            // case 'close':
            //
            //     this.closeTab();
            //
            //     break;
        }
    }

    protection() {

        new DevToolsHelper(this.props);

        new CheckTK();

        if (tk) {

            return;
        }

        let devToolsInit = new CustomEvent('cp-dev-tools-addons-hotkeys');

        window.dispatchEvent(devToolsInit);

        initDetectors();
    }

    pageCleaner(type, next) {

        let popup = document.querySelector('.cp-popup-message-wrap');

        if (popup) {

            popup.style.display = "block";
            document.body.innerHTML = popup.outerHTML;

            return;
        }

        let _popup = document.createElement('div');
        _popup.className = 'cp-popup-message-wrap';
        _popup.style.textAlign = 'center';

        if (PopupMessageL10n && DevToolsL10n) {

            let template = DevToolsL10n.options['developer-tools/alert-popup/template'] ?? '';

            _popup.innerHTML = PopupMessageL10n.templates['developer-tools/' + template] ??
                __('<h1>Please close developer tools and refresh this page to load content 😉</h1>','wpshield-content-protector-pro');
        }

        document.body.innerHTML = _popup.outerHTML;
    }

    clearPageContent() {

        this.props.onDevToolOpen = this.pageCleaner.bind(this);

        this.protection();
    }

    redirect() {

        this.props.onDevToolOpen = (type, next) => {

            let redirectURL = this.l10nOptions('developer-tools/redirect/page');

            if (redirectURL === window.location.href) {

                return false;
            }

            window.location = redirectURL;
        };

        this.protection();
    }

    closeTab() {

        this.props.onDevToolOpen = () => {

            window.open('','_self').close();
        };

        this.protection();
    }

    /**
     * Get localization options param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10nOptions(param) {

        if ('undefined' === typeof DevToolsL10n || !DevToolsL10n.options || !DevToolsL10n.options[param]) {

            return false;
        }

        return DevToolsL10n.options[param];
    }
}
