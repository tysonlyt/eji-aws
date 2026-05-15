import ExtensionsManager, {FilterAndCondition} from "../../../../assets/js/extensions-manager";
import {AttachEvent} from "../../../../libs/wpshield-plugin-core/assets/js/helper";

class RightClick {

    extensionsConfig = {
        audioAlert: {
            enable: this.l10nOptions('right-click/audio-alert'),
            protectionType: this.l10nOptions('right-click/type'),
            sound: this.l10nOptions('right-click/audio-alert/sound'),
            volume: this.l10nOptions('right-click/audio-alert/volume'),
        },
        popupMessage: {
            enable: this.l10nOptions('right-click/alert-popup'),
            protectionType: this.l10nOptions('right-click/type'),
            text: this.l10nOptions('right-click/alert-popup/text'),
            title: this.l10nOptions('right-click/alert-popup/title'),
            template: this.l10nOptions('right-click/alert-popup/template'),
        },
    };

    constructor() {

        let self = this;

        self.l10nOptions('right-click') && 'enable' === self.l10nOptions('right-click') && self.disableRightClick();
    }

    availableProFeatures() {

        let pro = this.l10n('available-pro');

        return (pro && pro['mode']) ?? false;
    }

    l10n(param) {

        if ('undefined' === typeof RightClickL10n || !RightClickL10n[param]) {

            return false;
        }

        return RightClickL10n[param];
    }

    l10nOptions(param) {

        if ('undefined' === typeof RightClickL10n || !RightClickL10n.options || !RightClickL10n.options[param]) {

            return false;
        }

        return RightClickL10n.options[param];
    }

    disableRightClick() {

        new AttachEvent('contextmenu', this.handlerContextMenu.bind(this));
    }

    handlerContextMenu(e) {

        let protectionType = this.l10nOptions('right-click/type');
        let internalLinks = this.l10nOptions('right-click/internal-links');
        let inputFieldsEnable = this.l10nOptions('right-click/input-fields');

        switch (true) {

            case ['INPUT', 'TEXTAREA'].includes(e.target.tagName) && 'enable' === inputFieldsEnable && this.availableProFeatures():

                const inputsContextMenu = new CustomEvent(
                    'inputs-contextmenu',
                    {
                        detail: {
                            rc: this,
                            contextmenu: e,
                            options: this.extensionsConfig,
                            protectionType,
                            inputFieldsEnable,
                        }
                    }
                );

                window.dispatchEvent(inputsContextMenu);

                break;

            case 'A' === e.target.tagName && 'enable' === internalLinks && this.availableProFeatures():

                const internalLinksCX = new CustomEvent(
                    'internal-links-contextmenu',
                    {
                        detail: {
                            rc: this,
                            contextmenu: e,
                            options: this.extensionsConfig,
                            protectionType,
                            internalLinks,
                        }
                    }
                );

                window.dispatchEvent(internalLinksCX);

                break;

            default:

                if (!this.availableProFeatures() || 'disable' === this.l10nOptions('right-click/type')) {

                    this.protection(e);

                    return;
                }

                const rcEvent = new CustomEvent(
                    'cp-rc-contextmenu',
                    {
                        detail: {
                            rc: this,
                            contextmenu: e,
                            options: this.extensionsConfig,
                            protectionType,
                        }
                    }
                );

                window.dispatchEvent(rcEvent);

                break;
        }
    }

    getHost(url) {

        let matches = url.match(/^https?:\/\/(?:w{3}\.)?([^\/?#]+)(?:[\/?#]|$)/i);

        if (matches) {
            return matches[1];
        }
    }

    protection(event) {

        let frontFilterCondition = FilterAndCondition('right-click', event, this.l10nOptions('right-click/filters'));

        if (!frontFilterCondition) {

            return false;
        }

        return !this.l10n('is-filter') && ExtensionsManager('right-click', event, this.extensionsConfig);
    }
}

export default RightClick;
