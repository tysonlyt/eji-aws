class AudioAlert {

    params;
    protector;

    /**
     * Store the excluded protection types.
     *
     * @type {string[]}
     */
    excludedProtections = [
        'right-click/simulate',
        'text-copy/append'
    ];

    constructor(protector, params) {

        this.params = params;
        this.protector = protector;

        this.init();
    }

    init() {

        let self = this;

        let searchProtectionType = [this.protector, this.params.protectionType ?? ''].join('/');
        let isExcludedProtection = this.excludedProtections.includes(searchProtectionType);

        if (!this.params.enable || 'disable' === this.params.enable || isExcludedProtection) {

            return false;
        }

        let excludeEvents = ['select', 'selectstart'];
        let isExcludedEvent = this.params.event && this.params.event.type && excludeEvents.includes(this.params.event.type);

        if (isExcludedEvent || this.params.excludedTarget) {

            return;
        }

        if (!AudioAlertL10n || !AudioAlertL10n['assets-url']) {

            return false;
        }

        //Content protection when the user interacts with the page and is active.
        self.play();
    }

    play() {

        var sound = AudioAlertL10n['assets-url'] + 'sounds/' + this.params.sound;

        const audio = new Audio(sound);

        if (this.params.volume) {
            audio.volume = this.params.volume / 100;
        }

        audio.play();
    }
}
