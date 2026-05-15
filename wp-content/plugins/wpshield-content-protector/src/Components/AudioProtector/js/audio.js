import {AttachEvent} from "../../../../libs/wpshield-plugin-core/assets/js/helper";
import ExtensionsManager, {FilterAndCondition} from "../../../../assets/js/extensions-manager";

export default class Audios {

    options = {
        audioAlert: {
            event: null,
            excluded: null,
            enable: this.l10nOptions('audios/audio-alert'),
            sound: this.l10nOptions('audios/audio-alert/sound'),
            volume: this.l10nOptions('audios/audio-alert/volume'),
        },
        popupMessage: {
            event: null,
            excluded: null,
            enable: this.l10nOptions('audios/alert-popup'),
            text: this.l10nOptions('audios/alert-popup/text'),
            title: this.l10nOptions('audios/alert-popup/title'),
            template: this.l10nOptions('audios/alert-popup/template'),
        }
    };

    constructor() {

        //Disable text copy.
        'enable' === this.l10nOptions('audios') && this.audiosProtector();
    }

    /**
     * Audios protector enable.
     */
    audiosProtector() {

        if ('disable' === this.l10nOptions('audios/disable-right-click')) {

            return false;
        }

        new AttachEvent('contextmenu', e => {
            if ('AUDIO' === e.target.tagName) {

                if (!FilterAndCondition('audios', e, this.l10nOptions('audios/filters'))) {

                    return false;
                }

                if (this.l10n('is-filter')) {

                    return false;
                }

                new ExtensionsManager('audios', e, this.options);

                return false;
            }
        });
    }

    /**
     * Get localization param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10n(param) {

        if ('undefined' === typeof AudiosL10n || !AudiosL10n[param]) {

            return false;
        }

        return AudiosL10n[param];
    }

    /**
     * Get localization options param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10nOptions(param) {

        if ('undefined' === typeof AudiosL10n || !AudiosL10n.options || !AudiosL10n.options[param]) {

            return false;
        }

        return AudiosL10n.options[param];
    }

    availableProFeatures() {

        return this.l10n('active-pro-version');
    }
}
