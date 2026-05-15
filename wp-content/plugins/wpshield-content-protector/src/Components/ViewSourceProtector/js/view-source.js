import {AttachEvent} from "../../../../libs/wpshield-plugin-core/assets/js/helper";
import {KeyboardHelper} from "../../../../libs/wpshield-plugin-core/assets/js/keyboard-helper";
import ExtensionsManager from "../../../../assets/js/extensions-manager";

export default class ViewSource {

    options = {
        audioAlert: {
            event: null,
            excluded: null,
            enable: this.l10nOptions('view-source/audio-alert'),
            protectionType: this.l10nOptions('view-source/type'),
            sound: this.l10nOptions('view-source/audio-alert/sound'),
            volume: this.l10nOptions('view-source/audio-alert/volume'),
        },
        popupMessage: {
            event: null,
            excluded: null,
            enable: this.l10nOptions('view-source/alert-popup'),
            protectionType: this.l10nOptions('view-source/type'),
            text: this.l10nOptions('view-source/alert-popup/text'),
            title: this.l10nOptions('view-source/alert-popup/title'),
            template: this.l10nOptions('view-source/alert-popup/template'),
        }
    };

    constructor() {

        let protectorStatus = this.l10nOptions('view-source');
        let isProtected = 'enable' === protectorStatus;

        //Disable developer tools accessibility.
        isProtected && this.devToolsProtector();
    }

    /**
     * developer tools protector enable.
     */
    devToolsProtector() {

        'hotkeys' === this.l10nOptions('view-source/type') && this.disableShortcuts();
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
                    protector: 'view-source',
                    options: this.options,
                    hotKeys: this.l10n('disabled-shortcuts'),
                    filters: this.l10nOptions('view-source/filters')
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

        if ('undefined' === typeof ViewSourceL10n || !ViewSourceL10n[param]) {

            return false;
        }

        return ViewSourceL10n[param];
    }

    /**
     * Get localization options param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10nOptions(param) {

        if ('undefined' === typeof ViewSourceL10n || !ViewSourceL10n.options || !ViewSourceL10n.options[param]) {

            return false;
        }

        return ViewSourceL10n.options[param];
    }

    availableProFeatures() {

        return this.l10n('available-pro');
    }
}
