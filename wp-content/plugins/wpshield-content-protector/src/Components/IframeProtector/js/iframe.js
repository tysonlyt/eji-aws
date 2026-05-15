import {AttachEvent} from "../../../../libs/wpshield-plugin-core/assets/js/helper";
import PopupMessage from "../../Addons/PopupMessage/js/popup-message";

export default class iframe {

    constructor() {

        if ('disable' === this.l10n('iframe')) return false;

        this.isIframe() && 'message' === this.l10nOptions('iframe/type') && this.protection();
    }

    protection() {

        // Ignore internal iframes such as customizer page
        try {

            if (this.getHost(window.parent.location.href) === this.getHost(window.location.href)) {

                return false;
            }

        } catch (error) {

            console.log(error);
        }

        let alert = new PopupMessage('iframe', {
                event: null,
                excluded: null,
                enable: this.l10nOptions('iframe/alert-popup'),
                text: this.l10nOptions('iframe/alert-popup/text'),
                title: this.l10nOptions('iframe/alert-popup/title'),
                template: this.l10nOptions('iframe/alert-popup/template'),
            }
        );

        new AttachEvent('DOMContentLoaded', () => {

            document.body.write('<div class="cp-popup-message-wrap">' + alert.alertTemplate + '</div>');
        });
    }

    getHost(url) {

        let matches = url.match(/^https?\:\/\/(?:w{3}\.)?([^\/?#]+)(?:[\/?#]|$)/i);

        if (matches) {

            return matches[1];
        }
    }

    isIframe() {

        return window.parent !== window.self;
    }

    l10n(param) {

        if ('undefined' === typeof IframeL10n || !IframeL10n[param]) {

            return false;
        }

        return IframeL10n[param];
    }

    l10nOptions(param) {

        if (!'undefined' === typeof IframeL10n || !IframeL10n.options || !IframeL10n.options[param]) {

            return false;
        }

        return IframeL10n.options[param];
    }
}
