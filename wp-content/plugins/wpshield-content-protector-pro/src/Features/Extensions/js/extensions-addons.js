export default class ExtensionsAddons {

    /**
     * Store the timer number.
     */
    timer;

    /**
     * Store instance of ExtensionsProtector
     *
     */
    protector;

    /**
     * Store activate status of this protector.
     *
     * @type {boolean}
     */
    isActive = false;

    constructor() {

        window.onload = () => {

			if ('enable' !== this.l10nOptions('extensions')) {

				return;
			}

			this.timer = setInterval(this.disableExtensions.bind(this), 500);
		};
	}

    /**
     * Get localization param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10n(param) {

        if ('undefined' === typeof ExtensionsL10n || !ExtensionsL10n[param]) {

            return false;
        }

        return ExtensionsL10n[param];
    }

    /**
     * Get localization options param value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10nOptions(param) {

        if ('undefined' === typeof ExtensionsL10n || !ExtensionsL10n.options || !ExtensionsL10n.options[param]) {

            return false;
        }

        return ExtensionsL10n.options[param];
    }

    disableExtensions() {

        if (-1 !== document.head.outerHTML.indexOf('user-select: text !important;')) {

            let relatedEvents = [
                'cut',
                'copy',
                'keyup',
                'select',
                'keydown',
                'selectstart',
                'contextmenu',
            ];

            relatedEvents.forEach(eventType => {

                document.addEventListener(eventType, (event) => {

                    let extensionsHook = new CustomEvent('extensions-protector', {detail: {event, preventDefault: true}});

                    window.dispatchEvent(extensionsHook);
                }, true);
            });

            if (!this.isActive) {

                ['click', 'contextmenu'].forEach(e => window.addEventListener(e, this.alert.bind(this), {once: true}));

                this.isActive = true;
            }

            //Clear timer.
            setTimeout(() => {
                clearInterval(this.timer);
            }, 5000);
        }
    }

    alert(event) {

        event.preventDefault();

        new AudioAlert('extensions',
            {
                event: null,
                enable: this.l10nOptions('extensions/audio-alert'),
                protectionType: this.l10nOptions('extensions/type'),
                sound: this.l10nOptions('extensions/audio-alert/sound'),
                volume: this.l10nOptions('extensions/audio-alert/volume')
            }
        );

        let exAddonsPopup = new CustomEvent('extensions-protector-popup-alert', {detail: {event: null}});

        window.dispatchEvent(exAddonsPopup);
    }
}
