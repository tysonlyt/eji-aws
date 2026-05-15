import {AttachEvent} from "../../../../libs/wpshield-plugin-core/assets/js/helper";
import {KeyboardHelper} from "../../../../libs/wpshield-plugin-core/assets/js/keyboard-helper";
import ExtensionsManager, {AlertExtensionsHandler, FilterAndCondition} from "../../../../assets/js/extensions-manager";
import devTools from "devtools-detect";

export default class DeveloperTools {

    options = {
        audioAlert: {
            event: null,
            excluded: null,
            enable: this.l10nOptions('developer-tools/audio-alert'),
            sound: this.l10nOptions('developer-tools/audio-alert/sound'),
            volume: this.l10nOptions('developer-tools/audio-alert/volume'),
        },
        popupMessage: {
            event: null,
            excluded: null,
            enable: this.l10nOptions('developer-tools/alert-popup'),
            text: this.l10nOptions('developer-tools/alert-popup/text'),
            title: this.l10nOptions('developer-tools/alert-popup/title'),
            template: this.l10nOptions('developer-tools/alert-popup/template'),
        }
    };

    constructor() {

        let protectorStatus = this.l10nOptions('developer-tools');
        let isProtected = 'enable' === protectorStatus;

        //Disable developer tools accessibility.
        isProtected && this.devToolsProtector();
        isProtected && window.addEventListener('cp-dev-tools-addons-hotkeys', event => this.disableShortcuts());
    }

    /**
     * developer tools protector enable.
     */
    devToolsProtector() {

        let enableHotKeysProtector = 'hotkeys' === this.l10nOptions('developer-tools/type');

        enableHotKeysProtector && this.disableShortcuts();

        if (enableHotKeysProtector && devTools && devTools.isOpen) {

            this.alert(new Event(''));
        }

        // const alert = this.alert.bind(this);

        // Get notified when it's opened/closed or orientation changes
        // enableHotKeysProtector && window.addEventListener('devtoolschange', event => {
        //
        //     if (event.detail && event.detail.isOpen) {
        //
        //         this.alert();
        //
        //         window.addEventListener('load', alert, true);
        //         window.addEventListener('blur', alert, true);
        //         window.addEventListener('focus', alert, true);
        //         window.addEventListener('resize', alert, true);
        //         window.addEventListener('mousemove', alert, true);
        //
        //         return true;
        //     }
        //
        //     window.removeEventListener('blur', alert, false);
        //     window.removeEventListener('blur', alert, true);
        //     window.removeEventListener('load', alert, false);
        //     window.removeEventListener('load', alert, true);
        //     window.removeEventListener('focus', alert, false);
        //     window.removeEventListener('focus', alert, true);
        //     window.removeEventListener('resize', alert, false);
        //     window.removeEventListener('resize', alert, true);
        //     window.removeEventListener('mousemove', alert, false);
        //     window.removeEventListener('mousemove', alert, true);
        // });
    }

    filter(event) {

        return FilterAndCondition('developer-tools', event, this.l10nOptions('developer-tools/filters'));
    }

    alert(event) {

        if (this.filter(event)) {

            AlertExtensionsHandler('developer-tools', this.options);
        }
    }

    /**
     * Disable HotKeys for text copy.
     */
    disableShortcuts() {

        const keyHandler = new KeyboardHelper(ExtensionsManager);

        new AttachEvent('keydown', event => {
            keyHandler.down(
                {
                    event,
                    protector: 'developer-tools',
                    options: this.options,
                    hotKeys: this.l10n('disabled-shortcuts'),
                    filters: this.l10nOptions('developer-tools/filters'),
                }
            );
        });

        new AttachEvent('keyup', event => keyHandler.up(event));
    }

    /**
     * Get localization param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10n(param) {

        if ('undefined' === typeof DevToolsL10n || !DevToolsL10n[param]) {

            return false;
        }

        return DevToolsL10n[param];
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

    availableProFeatures() {

        return this.l10n('available-pro');
    }
}
