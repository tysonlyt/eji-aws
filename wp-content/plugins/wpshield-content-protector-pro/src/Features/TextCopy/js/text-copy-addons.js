import Helper from "../../assets/js/Helper";

export default class TextCopyAddons {

    /**
     * Store instance of RightClickProtector
     *
     */
    protector;
    helper = new Helper();

    constructor() {

        window.addEventListener('cp-text-copy-protect', this.protection.bind(this));
    }

    protection(event) {

        if (!event || !event.detail || !event.detail.protectionType || !event.detail.origin) {

            return false;
        }

        if ('append' === event.detail.protectionType && this.copyrightAppender(event.detail)) {

            //Appended copyright text.
            return false;

        } else if ('select' === event.detail.protectionType && 'selectstart' === event.detail.origin.type) {

            return false;
        }

        event.detail.textCopy.protect(event.detail.origin);
    }

    copyrightAppender(eventDetail) {

        if ('selectstart' === eventDetail.origin.type) {

            return true;
        }

        let clipBoardData = eventDetail.origin.clipboardData || window.clipboardData;

        if (!clipBoardData) {

            return;
        }

        let text = this.helper.getSelectionText();
        let lengthLimit = eventDetail.copyrightLength ? eventDetail.copyrightLength : 0;

        if (lengthLimit > 0 && text.length > lengthLimit) {
            text = text.substring(0, lengthLimit) + '...';
        }

        if (0 === Number(lengthLimit)) {

            text = eventDetail.copyrightText ? eventDetail.copyrightText : '';

            eventDetail.origin.preventDefault();
            clipBoardData.setData('Text', text);

            return true;
        }

        eventDetail.origin.preventDefault();

        let data = eventDetail.copyrightText ? eventDetail.copyrightText : '';

        data = data.replace('%TEXT%',text);
        data = data.replace('%POSTLINK%',this.l10nOptions('post-link') ?? window.location.href);
        data = data.replace('%POSTTITLE%',this.l10nOptions('post-title') ?? document.title);
        data = data.replace('%SITETITLE%',this.l10nOptions('site-title') ?? document.title);
        data = data.replace('%SITELINK%',this.l10nOptions('site-link') ?? window.location.origin);

        clipBoardData.setData('Text', data);

        return true;
    }

    /**
     * Get localization options param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10nOptions(param) {

        if ('undefined' === typeof TextCopyAddonL10n || !TextCopyAddonL10n.options || !TextCopyAddonL10n.options[param]) {

            return null;
        }

        return TextCopyAddonL10n.options[param];
    }
}
