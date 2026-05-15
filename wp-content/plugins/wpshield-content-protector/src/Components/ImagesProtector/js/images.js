import ExtensionsManager, {FilterAndCondition} from "../../../../assets/js/extensions-manager";
import {AttachEvent} from "../../../../libs/wpshield-plugin-core/assets/js/helper";

class Images {

    extensionsParams = {
        audioAlert: {
            enable: this.l10nOptions('images/audio-alert'),
            protectionType: this.l10nOptions('images/type'),
            sound: this.l10nOptions('images/audio-alert/sound'),
            volume: this.l10nOptions('images/audio-alert/volume'),
        },
        popupMessage: {
            enable: this.l10nOptions('images/alert-popup'),
            protectionType: this.l10nOptions('images/type'),
            text: this.l10nOptions('images/alert-popup/text'),
            title: this.l10nOptions('images/alert-popup/title'),
            template: this.l10nOptions('images/alert-popup/template'),
        }
    };

    constructor() {

        //Disable text copy.
        let enableImagesProtector = 'enable' === this.l10nOptions('images');
        let enableImagesRC = 'enable' === this.l10nOptions('images/disable-right-click');
        let enableImagesDD = 'enable' === this.l10nOptions('images/disable-drag');

        enableImagesProtector && enableImagesRC && this.disableImageRightClick();
        enableImagesProtector && enableImagesDD && this.disableImageDragDrop();
    }

    /**
     * Get localization param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10n(param) {

        if ('undefined' === typeof ImagesL10n || !ImagesL10n[param]) {

            return false;
        }

        return ImagesL10n[param];
    }

    /**
     * Get localization options param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10nOptions(param) {

        if ('undefined' === typeof ImagesL10n || !ImagesL10n.options || !ImagesL10n.options[param]) {

            return false;
        }

        return ImagesL10n.options[param];
    }

    /**
     * Disable drag and drop image event.
     */
    disableImageDragDrop() {

        new AttachEvent('dragstart', this.returnFalse.bind(this));
    }

    /**
     * Disable right click on contextmenu event occurred for just image target.
     */
    disableImageRightClick() {

        new AttachEvent('contextmenu', e => {
            if ('IMG' === e.target.tagName) {

                if (!FilterAndCondition('images', e, this.l10nOptions('images/filters'))) {

                    return false;
                }

                if (this.l10n('is-filter')) {

                    return false;
                }

                new ExtensionsManager('images', e, this.extensionsParams);

                return false;
            }
        });
    }

    /**
     * Retrieve false value with prevent default event when exists event!
     *
     * @param event
     * @returns {boolean}
     */
    returnFalse(event) {

        if (event && event.preventDefault) {

            if (!FilterAndCondition('images', event, this.l10nOptions('images/filters'))) {

                return false;
            }

            if (this.l10n('is-filter')) {

                return false;
            }

            new ExtensionsManager('images', event, this.extensionsParams)
        }

        return false;
    }
}

export default Images;
