import {AttachEvent} from "../../../../libs/wpshield-plugin-core/assets/js/helper";
import ExtensionsManager, {AlertExtensionsHandler, FilterAndCondition} from "../../../../assets/js/extensions-manager";
import {KeyboardHelper} from "../../../../libs/wpshield-plugin-core/assets/js/keyboard-helper";

class TextCopy {

    options = {
        audioAlert: {
            event: null,
            excludedTarget: null,
            enable: this.l10nOptions('text-copy/audio-alert'),
            protectionType: this.l10nOptions('text-copy/type'),
            sound: this.l10nOptions('text-copy/audio-alert/sound'),
            volume: this.l10nOptions('text-copy/audio-alert/volume'),
        },
        popupMessage: {
            event: null,
            excludedTarget: null,
            enable: this.l10nOptions('text-copy/alert-popup'),
            protectionType: this.l10nOptions('text-copy/type'),
            text: this.l10nOptions('text-copy/alert-popup/text'),
            title: this.l10nOptions('text-copy/alert-popup/title'),
            template: this.l10nOptions('text-copy/alert-popup/template'),
        }
    };
    availableAppender;

    /**
     * Text copy Constructor method.
     *
     * @since 1.0.0
     */
    constructor() {

        let is_active = this.l10nOptions('text-copy');

        //When disable text-copy protector option deactive this protector!
        if (!is_active || 'disable' === is_active) {

            return;
        }

        //Handler mouse up event. just for (microsoft edge) browser
        this.handleMouseup = this.handleMouseup.bind(this);

        //Disable text copy.
        this.disableTextCopy();
    }

    updateOptions(event) {

        let excluded = this.isExcludedInputs() && this.isInputTarget(event);

        this.options.audioAlert.event = event;
        this.options.popupMessage.event = event;
        this.options.audioAlert.excludedTarget = excluded;
        this.options.popupMessage.excludedTarget = excluded;
    }

    /**
     * Get localization param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10n(param) {

        if ('undefined' === typeof TextCopyL10n || !TextCopyL10n[param]) {

            return false;
        }

        return TextCopyL10n[param];
    }

    /**
     * Get localization options param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10nOptions(param) {

        if ('undefined' === typeof TextCopyL10n || !TextCopyL10n.options || !TextCopyL10n.options[param]) {

            return false;
        }

        return TextCopyL10n.options[param];
    }

    browserDetect() {

        let userAgent = navigator.userAgent;
        let browserName;


        if (userAgent.match(/Opera|OPR\//i)) {
            browserName = "opera";
        } else if (userAgent.match(/edg/i)) {
            browserName = "edge";
        } else if (userAgent.match(/chrome|chromium|crios/i)) {
            browserName = "chrome";
        } else if (userAgent.match(/firefox|fxios/i)) {
            browserName = "firefox";
        } else if (userAgent.match(/safari/i)) {
            browserName = "safari";
        } else {
            browserName = "chrome"; // default browser menu
        }

        return browserName;
    }

    /**
     * Text Copy protector enable.
     */
    disableTextCopy() {

        window.onmouseup = this.handleMouseup.bind(this);

        this.disableShortcuts();

        if ('disable' === this.l10nOptions('text-copy/type') || this.isExcludedInputs()) {

            //inputs and textarea fields are include in protection!
            document.querySelectorAll('input[type="text"],input[type="search"],textarea').forEach(this.inputsDisableSelection.bind(this));
        }

        //Disable Text-Selection, Copy, and Paste interactions!
        ['selectstart', 'copy', 'cut'].forEach(eventType => new AttachEvent(eventType, this.copyright.bind(this)));
    }

    /**
     * Prevent to search or copy short popup menu.
     * Fixme: in opera browser not preventing!!
     * @param event
     *
     * @return bool true on success, false when failure.
     */
    handleMouseup(event) {

        if ('edge' === this.browserDetect()) {

            event.preventDefault();

            return true;
        }

        return false;
    }

    /**
     * Is event target input field?
     *
     * @param event
     * @returns {boolean}
     */
    isInputTarget(event) {

        return ['INPUT', 'TEXTAREA'].includes(event.target.tagName);
    }

    /**
     * Is excluded input fields?
     *
     * @returns {boolean}
     */
    isExcludedInputs() {

        return 'enable' === this.l10nOptions('text-copy/exclude-inputs');
    }

    availableProFeatures() {

        let pro = this.l10n('available-pro');

        return (pro && pro['mode']) ?? false;
    }

    /**
     * Disable HotKeys for text copy.
     */
    disableShortcuts() {

        const keyHandler = new KeyboardHelper(ExtensionsManager);

        let hotKeys = this.l10n('disabled-shortcuts');
        let protectionType = this.l10nOptions('text-copy/type');

        if (this.availableProFeatures() && 'select' === protectionType) {

            hotKeys.forEach((hotKey, index) => {

                if ('ctrl_a' === hotKey || 'cmd_a' === hotKey) {

                    delete hotKeys[index];
                }
            });

        } else if ('append' === protectionType) {

            hotKeys = [];
        }

        if ('disable' === this.l10nOptions('text-copy/type') || this.isExcludedInputs()) {

            return false;
        }

        new AttachEvent('keydown', event => {

                this.updateOptions(event);

                keyHandler.down(
                    {
                        event,
                        protector: 'text-copy',
                        options: this.options,
                        hotKeys: hotKeys,
                        filters: this.l10nOptions('text-copy/filters')
                    }
                );
            }
        );
        new AttachEvent('keyup', event => keyHandler.up(event));
    }

    protect(event) {

        if (this.isExcludedInputs() && this.isInputTarget(event)) {

            return false;
        }

        //Prevent copyright event default working if event exists!
        if (event && event.preventDefault) {

            event.preventDefault();
        }
    }

    /**
     * Protection of content copyright!
     *
     * @param event
     * @returns {boolean}
     */
    copyright(event) {

        if (!FilterAndCondition('text-copy', event, this.l10nOptions('text-copy/filters'))) {

            return false;
        }

        if (this.l10n('is-filter')) {

            return false;
        }

        //When use pro features.
        if (this.availableProFeatures() && ['select', 'append'].includes(this.l10nOptions('text-copy/type'))) {

            const textCopyEvent = new CustomEvent('cp-text-copy-protect',
                {
                    detail: {
                        origin: event,
                        textCopy: this,
                        protectionType: this.l10nOptions('text-copy/type'),
                        allowSelect: this.l10nOptions('text-copy/select-texts'),
                        copyrightAppender: this.l10nOptions('text-copy/copy-appender'),
                        copyrightText: this.l10nOptions('text-copy/copy-appender/text'),
                        copyrightLength: this.l10nOptions('text-copy/copy-appender/max-text-length')
                    }
                }
            );

            window.dispatchEvent(textCopyEvent);

        } else {

            this.protect(event);
        }

        this.updateOptions(event);

        new AlertExtensionsHandler('text-copy', this.options);

        return true;
    }

    /**
     * Disable inputs and textarea elements text selection!
     *
     * @param node
     */
    inputsDisableSelection(node) {

        let customEvent = new Event('select');

        if (!FilterAndCondition('text-copy', customEvent, this.l10nOptions('text-copy/filters'))) {

            return false;
        }

        if (this.l10n('is-filter')) {

            return false;
        }

        if (!this.isExcludedInputs()) {
            //Disable with css classes.
            node.classList.add('cp-unselectable');

            //Javascript protection!
            node.addEventListener('select', event => {

                event.preventDefault();

                node.selectionStart = node.selectionEnd;

                this.updateOptions(event);

                new AlertExtensionsHandler('text-copy', this.options);
            });
        }
    }
}

export default TextCopy;
