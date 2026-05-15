export default class PopupMessage {

    params;
    protector;
    alertWrapper;
    alertTemplate;
    excludedProtections = [
        'right-click/simulate',
        'text-copy/append'
    ];

    constructor(protector, params) {

        this.params = params;
        this.protector = protector;

        let searchProtectionType = [this.protector, this.params.protectionType ?? ''].join('/');
        let isExcludedProtection = this.excludedProtections.includes(searchProtectionType);

        if (!this.params.enable || 'disable' === this.params.enable || isExcludedProtection) {

            return;
        }

        let excludeEvents = ['select', 'selectstart'];

        let isExcludedEvent = this.params.event && this.params.event.type && excludeEvents.includes(this.params.event.type);
        let isExcludedTarget = 'undefined' !== typeof this.params.excludedTarget && this.params.excludedTarget;

        if (isExcludedEvent || isExcludedTarget) {

            return;
        }

        this.alertWrapper = document.createElement('div');
        this.alertWrapper.style.display = 'none';

        this.render();
    }

    getTemplateData() {

        if (!this.params || !this.params.template) {

            return '';
        }

        let template = this.l10n(`${this.protector}/${this.params.template}`);

        return template ? template : '';
    }

    render() {

        if (!this.alertTemplate) {

            this.alertTemplate = this.getTemplateData();
        }

        let popup = document.querySelector('.cp-alert-popup');

        if (popup) {

            popup.style.display = 'block'
            return false;
        }

        this.alertWrapper.setAttribute('class', 'cp-alert-popup');
        this.alertWrapper.innerHTML = this.alertTemplate;
        document.body.appendChild(this.alertWrapper);
        this.alertWrapper.style.display = 'block';

        document.addEventListener('keydown', event => {

            ///When pressed Esc key.
            if (event.keyCode && [27, 53].includes(event.keyCode)) {
                this.alertWrapper.style.display = 'none';
                this.params?.onClose && this.params.onClose();
            }
        });

        //When Clicked on close button.
        document.addEventListener('click', e => {
            if (!e.target.classList.contains('cp-close') && !e.target.closest('.cp-close')) {
                return false;
            }
            e.preventDefault();
            this.alertWrapper.style.display = 'none';

            this.params?.onClose && this.params.onClose();
        });
    }


    /**
     * Get localization params value by param name.
     *
     * @param param
     * @returns {boolean|*}
     */
    l10n(param) {

        if ('undefined' === typeof PopupMessageL10n || !PopupMessageL10n.templates || !PopupMessageL10n.templates[param]) {

            return false;
        }

        return PopupMessageL10n.templates[param];
    }
}
