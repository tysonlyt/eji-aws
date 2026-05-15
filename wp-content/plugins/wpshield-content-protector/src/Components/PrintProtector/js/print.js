import {AttachEvent} from "../../../../libs/wpshield-plugin-core/assets/js/helper";
import {KeyboardHelper} from "../../../../libs/wpshield-plugin-core/assets/js/keyboard-helper";
import ExtensionsManager from "../../../../assets/js/extensions-manager";

export default class Print {

    options = {
        audioAlert: {
            event: null,
            excluded: null,
            enable: this.l10nOptions('print/audio-alert'),
            sound: this.l10nOptions('print/audio-alert/sound'),
            volume: this.l10nOptions('print/audio-alert/volume'),
        },
        popupMessage: {
            event: null,
            excluded: null,
            enable: this.l10nOptions('print/alert-popup'),
            text: this.l10nOptions('print/alert-popup/text'),
            title: this.l10nOptions('print/alert-popup/title'),
            template: this.l10nOptions('print/alert-popup/template'),
        }
    };

    constructor() {

        let protectorStatus = this.l10nOptions('print');
        let isProtected = 'enable' === protectorStatus;

        //Disable print with this protector.
        isProtected && this.disablePrint();
    }

    /**
     * print protector enable.
     */
    disablePrint() {

        'hotkeys' === this.l10nOptions('print/type') && this.disableShortcuts();
    }

    /**
     * Disable HotKeys for text copy.
     */
    disableShortcuts() {

        let keyHandler = new KeyboardHelper(ExtensionsManager);

        new AttachEvent('keydown', event => {

            this.options.audioAlert.event = event;
            this.options.popupMessage.event = event;

            keyHandler.down(
                {
                    event,
                    protector: 'print',
                    options: this.options,
                    hotKeys: this.l10n('disabled-shortcuts'),
                    filters: this.l10nOptions('print/filters')
                }
            );
        });
        new AttachEvent('keyup', event => {
            keyHandler.up(event);
        });
    }

    /**
     * Get localization param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10n(param) {

        if ('undefined' === typeof PrintL10n || !PrintL10n[param]) {

            return false;
        }

        return PrintL10n[param];
    }

    /**
     * Get localization options param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10nOptions(param) {

        if ('undefined' === typeof PrintL10n || !PrintL10n.options || !PrintL10n.options[param]) {

            return false;
        }

        return PrintL10n.options[param];
    }

    availableProFeatures() {

        return this.l10n('available-pro');
    }
}
