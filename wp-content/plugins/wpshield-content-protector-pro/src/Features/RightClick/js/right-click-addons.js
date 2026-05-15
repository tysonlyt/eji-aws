import Contextmenu from "./contextmenu";

export default class RightClickAddons {

    constructor() {

        window.addEventListener('scroll', e => document.querySelector('body').click());
        window.addEventListener('inputs-contextmenu', this.inputFieldsHandler.bind(this));
        window.addEventListener('internal-links-contextmenu', this.linkHandler.bind(this));
        window.addEventListener('cp-rc-contextmenu', this.simulation.bind(this));
    }

    async getClipboardContents() {
        try {
            return await navigator.clipboard.readText();
        } catch (err) {
            console.error('Failed to read clipboard contents: ', err);
        }
    }

    simulation(event) {

        let detailNotExists = !event || !event.detail;

        if (detailNotExists || !event.detail.contextmenu || !event.detail.rc) {

            return false;
        }

        this.exclusiveContextMenu(event.detail.rc, event.detail.contextmenu);
    }

    exclusiveContextMenu(protectorInstance, event) {

        //Running extensions functionalities and prevent default right click (Best UX).
        let result = protectorInstance.protection(event);

        result && new Contextmenu(
            {
                x: event.x,
                y: event.y,
                target: event.target,
            }
        );
    }

    linkHandler(event) {

        let detailNotExists = !event || !event.detail;

        //When internal links protector is disable.
        if (detailNotExists || !event.detail.internalLinks || !event.detail.contextmenu || !event.detail.rc) {

            return false;
        }

        let detail = event.detail;

        if ('simulate' === detail.protectionType) {

            this.exclusiveContextMenu(event.detail.rc, event.detail.contextmenu);

            return false;

        } else if ('disable' === detail.internalLinks) {

            //
            detail.rc.protection(detail.contextmenu);

            return false;
        }

        let host = detail.rc.getHost(detail.contextmenu.target.href);
        let allowedHosts = detail.rc.l10n('exclude-hosts');

        if (!host || !allowedHosts) {
            //When incorrect host or allowed hosts is empty.

            detail.rc.protection(detail.contextmenu);

            return false;
        }

        if (-1 === allowedHosts.indexOf(host)) {
            //When host is external link.

            detail.rc.protection(detail.contextmenu);

            return false;
        }

        return false;
    }

    inputFieldsHandler(event) {

        let detailNotExists = !event || !event.detail;

        if (detailNotExists || !event.detail.inputFieldsEnable || !event.detail.contextmenu || !event.detail.rc) {

            return false;
        }

        let detail = event.detail;
        let enableInputProtection = 'enable' === detail.inputFieldsEnable;
        let isTextInput = 'text' === detail.contextmenu.target.getAttribute('type');
        let isTextArea = 'TEXTAREA' === detail.contextmenu.target.tagName;

        enableInputProtection = isTextInput || isTextArea && enableInputProtection;

        if ('simulate' === detail.protectionType && enableInputProtection) {

            //When is simulate enable and enabled Right Click On Input Fields! (BEST UX pro features)
            this.exclusiveContextMenu(detail.rc, detail.contextmenu);
        }
    }
}
