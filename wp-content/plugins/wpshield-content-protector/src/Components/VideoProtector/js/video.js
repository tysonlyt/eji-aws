import {AttachEvent} from "../../../../libs/wpshield-plugin-core/assets/js/helper";
import ExtensionsManager, {FilterAndCondition} from "../../../../assets/js/extensions-manager";

export default class Videos {

    options = {
        audioAlert: {
            event: null,
            excluded: null,
            enable: this.l10nOptions('videos/audio-alert'),
            sound: this.l10nOptions('videos/audio-alert/sound'),
            volume: this.l10nOptions('videos/audio-alert/volume'),
        },
        popupMessage: {
            event: null,
            excluded: null,
            enable: this.l10nOptions('videos/alert-popup'),
            text: this.l10nOptions('videos/alert-popup/text'),
            title: this.l10nOptions('videos/alert-popup/title'),
            template: this.l10nOptions('videos/alert-popup/template'),
        }
    };

    constructor() {

        //Disable text copy.
        'enable' === this.l10nOptions('videos') && this.videosProtector();
    }

    /**
     * Videos protector enable.
     */
    videosProtector() {

        if ('disable' === this.l10nOptions('videos/disable-right-click')) {

            return false;
        }

        new AttachEvent('contextmenu', e => {
            if ('VIDEO' === e.target.tagName) {

                if (!FilterAndCondition('videos', e, this.l10nOptions('videos/filters'))) {

                    return false;
                }

                if (this.l10n('is-filter')) {

                    return false;
                }

                new ExtensionsManager('videos', e, this.options);

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

        if ('undefined' === typeof VideosL10n || !VideosL10n[param]) {

            return false;
        }

        return VideosL10n[param];
    }

    /**
     * Get localization options param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10nOptions(param) {

        if ('undefined' === typeof VideosL10n || !VideosL10n.options || !VideosL10n.options[param]) {

            return false;
        }

        return VideosL10n.options[param];
    }

    availableProFeatures() {

        return this.l10n('available-pro');
    }
}
