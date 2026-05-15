export default class iframeAddons {

    constructor() {

        if (!this.isIframe() || !this.l10nOptions('iframe')) {
            return;
        }

        // Ignore internal iframes such as customizer page

        try {

            if (this.getHost(window.parent.location.href) === this.getHost(window.location.href)) {

                return;
            }

        } catch (Err) {

        }

        let redirectUrl = this.l10n('redirect_url');
        let iframeType = this.l10nOptions('iframe/type');

        if ('redirect' === iframeType && redirectUrl) {

            if (window.location.href !== redirectUrl) {

                window.location = redirectUrl;
            }

        } else if ('watermark' === iframeType) {


            let customStyles = document.createElement('style');
            customStyles.innerHTML = `body{
            background-image:url("${this.l10n('watermark')}");
            opacity: ${this.l10nOptions('iframe/watermark/opacity')}%;
            }`;

            document.head.appendChild(customStyles);
        }
    }

    isIframe() {

        return window.parent !== window.self;
    }

    getHost(url) {

        let matches = url.match(/^https?\:\/\/(?:w{3}\.)?([^\/?#]+)(?:[\/?#]|$)/i);

        if (matches) {

            return matches[1];
        }
    }

    /**
     * Get localization param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10n(param) {

        if ('undefined' === typeof IframeL10n || !IframeL10n[param]) {

            return false;
        }

        return IframeL10n[param];
    }

    /**
     * Get localization options param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10nOptions(param) {

        if ('undefined' === typeof IframeL10n || !IframeL10n.options || !IframeL10n.options[param]) {

            return false;
        }

        return IframeL10n.options[param];
    }
}