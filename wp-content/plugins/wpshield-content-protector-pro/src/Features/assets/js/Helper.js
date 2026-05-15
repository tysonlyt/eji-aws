export default class Helper {

    getTheme(cx) {

        cx = cx.replace(' ', '-');

        return `cp-browser-${cx}`;
    }

    platformDetect() {

        if (!navigator || !navigator.platform) {

            return;
        }

        let platform = navigator.platform;

        if (platform.match(/win/i)) {
            platform = "windows";
        } else if (platform.match(/mac/i)) {
            platform = "mac";
        } else if (platform.match(/linux|Debian|red hat|Xubuntu|Ubuntu|Ubuntu|SuSE|Kubuntu|Fedora/i)) {
            platform = "linux";
        } else {
            platform = "No browser detection";
        }

        return platform;
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

    colorModeDetect() {

        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark'
        }

        return 'light';
    }

    getCXMenuType(tag) {

        if ('A' === tag) {

            return 'anchor';

        } else if (['INPUT', 'TEXTAREA'].includes(tag)) {

            return 'input';

        } else if ('IMG' === tag) {

            return 'img';

        } else if (window.getSelection().toString()) {

            return 'selected';

        } else {

            return 'normal';
        }
    }

    getOption(id, key) {

        switch (id) {

            case 'text-copy':

                if (!key) {

                    return TextCopyL10n && TextCopyL10n.options && TextCopyL10n.options[id];
                }

                return TextCopyL10n && TextCopyL10n.options && TextCopyL10n.options[`${id}/${key}`];
        }
    }

    getSelectionText() {

        if (window.getSelection) {

            try {

                let activeElement = document.activeElement;

                if (activeElement && activeElement.value) {

                    // firefox bug https://bugzilla.mozilla.org/show_bug.cgi?id=85686
                    return activeElement.value.substring(activeElement.selectionStart, activeElement.selectionEnd);

                } else {

                    return window.getSelection().toString();
                }

            } catch (e) {

            }

        } else if (document.selection && document.selection.type !== "Control") {

            // For IE
            return document.selection.createRange().text;
        }
    }

    filter(protector, event , filters) {

        if (!filters){

            return true;
        }

        if ('undefined' === typeof FilterObjects) {

            return true;
        }

        let filterObjects = new FilterObjects({protector, event});

        let result = {};

        Object.values(filters).map(filter => {

            if (!filter || !filter.in){

                return false;
            }

            result[filter.in] = filterObjects[filter.in] && filterObjects[filter.in].init();
        });

        return !Object.values(result).includes(false);
    }
}
