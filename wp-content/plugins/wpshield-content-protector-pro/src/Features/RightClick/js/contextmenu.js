import Helper from "../../assets/js/Helper";

export default class Contextmenu {

    props;
    helper = new Helper();

    constructor(props) {

        if ('undefined' === typeof RightClickAddonsL10n) {

            return;
        }

        this.props = props;

        this.init();
    }

    init() {

        if (!this.props || !this.props.target) {

            return;
        }

        let selection = window.getSelection();
        let textSelected = selection && selection.toString();
        let inputTypes = ['text', 'url', 'tel', 'search', 'number', 'email', 'password'];
        let inType = 'undefined' !== typeof this.props.target.type && inputTypes.includes(this.props.target.type);

        if (textSelected && !['INPUT', 'TEXTAREA'].includes(this.props.target.tagName)) {

            this.render('onSelectedElement');

        } else if ('A' === this.props.target.tagName || this.props.target.closest('a')) {

            this.render('onAnchorLink');

        } else if ('IMG' === this.props.target.tagName) {

            let imgOpt = ImagesL10n && ImagesL10n.options;

            if (imgOpt && imgOpt['images'] && imgOpt['images/disable-right-click'] && 'enable' === imgOpt['images'] && 'enable' === imgOpt['images/disable-right-click']) {

                return;
            }

            this.render('onImage');

        } else if ('TEXTAREA' === this.props.target.tagName) {

            this.render('onInputs');

        } else if ('INPUT' === this.props.target.tagName && inType) {

            this.render('onInputs');

        } else {

            this.render('default');
        }
    }

    render(theme) {

        if (!navigator || !navigator.platform) {

            return;
        }

        let platform = this.helper.platformDetect();
        let platformBrowser = platform + '-' + this.helper.browserDetect();

        let views = RightClickAddonsL10n.views;
        let template = views && views[platformBrowser] && views[platformBrowser] && views[platformBrowser][theme];

        let cxMenu = document.querySelector('.cp-context-menu');
        let cxWrapper = document.createElement('div');

        if (cxMenu && cxMenu.closest('body')) {

            cxMenu.innerHTML = template;

            cxMenu.className = this.getCXCssClasses();

            this.attachEvents();

            if (document.body.clientWidth - this.props.x < cxMenu.clientWidth) {

                this.props.x = document.body.clientWidth - cxMenu.clientWidth;
            }

            if (window.innerHeight - this.props.y < cxMenu.clientHeight) {

                this.props.y = window.innerHeight - cxMenu.clientHeight;
            }

            cxMenu.style.left = this.props.x + 'px';
            cxMenu.style.top = this.props.y + 'px';
            cxMenu.style.display = 'block';

            return;
        }

        cxWrapper.className = this.getCXCssClasses();
        cxWrapper.innerHTML = template;
        cxWrapper.style.position = 'fixed';
        cxWrapper.style.display = 'block';
        cxWrapper.style.left = this.props.x + 'px';
        cxWrapper.style.top = this.props.y + 'px';
        cxWrapper.style.zIndex = 99999999;

        document && document.body && document.body.appendChild(cxWrapper);

        this.attachEvents();
    }

    getCXCssClasses() {

        return `cp-context-menu ${this.helper.getTheme(this.helper.browserDetect())} ` +
            `cp-platform-${this.helper.platformDetect()} ` + `cp-color-${this.helper.colorModeDetect()} ` +
            `cp-type-${this.helper.getCXMenuType(this.props.target.tagName)}`;
    }

    prepare() {

        let selectedText = this.helper.getSelectionText();
        let cxMenu = document.querySelector('.cp-context-menu');
        let excludeInputTypes = ['text', 'url', 'tel', 'search', 'number', 'email', 'password'];
        let textCopy = 'enable' === this.helper.getOption('text-copy');
        let copyRightAppender = 'append' === this.helper.getOption('text-copy', 'type');
        let excludeInputs = 'enable' === this.helper.getOption('text-copy', 'exclude-inputs');
        let target = this.props && this.props.target;

        if (!target || !target.tagName) {

            return false;
        }

        cxMenu && cxMenu.childNodes.forEach(node => {

            //Search For "..."
            if (node.textContent && node.textContent.match(/\"\.\.\.\"/)) {

                if (!selectedText) {

                    selectedText = this.getExcerptText(this.props.target.textContent);
                }

                node.textContent = node.textContent.replace('"..."', `"${this.getExcerptText(selectedText)}"`);
            }

            let nodeContent = node.textContent && node.textContent.toLowerCase().trim();

            if (!nodeContent) {

                return false;
            }

            //All targets other than inputs(text,email,number,...) and textarea
            if (!['INPUT', 'TEXTAREA'].includes(target.tagName)) {

                if (-1 !== nodeContent.indexOf('copy') && textCopy && !copyRightAppender) {

                    node.classList.add('cp-disable');
                }

                return false;
            }

            //Exclude input types.
            if ('INPUT' === target.tagName && target.type && !excludeInputTypes.includes(target.type)) {

                return false;
            }

            //Text Copy Protector is ON when not excluded input fields
            if (textCopy && !excludeInputs) {

                if (-1 !== nodeContent.indexOf('paste')) {
                    node.classList.add('cp-disable');
                } else if (-1 !== nodeContent.indexOf('paste as plaintext')) {
                    node.classList.add('cp-disable');
                } else if (-1 !== nodeContent.indexOf('undo')) {
                    node.classList.add('cp-disable');
                } else if (-1 !== nodeContent.indexOf('redo')) {
                    node.classList.add('cp-disable');
                }
            }

            //When selected element or empty contextmenu item and TextCopy Protector and excluded input fields!
            //TODO: Firefox is not supported window.getSelection() on inputs. Double check to make sure!
            if ((selectedText || !node.textContent) && textCopy && excludeInputs && 'firefox' !== this.helper.browserDetect()) {

                return false;
            }

            //When selected element or empty contextmenu item and TextCopy Protector and CopyRight Appender feature is turn ON!
            //TODO: Firefox is not supported window.getSelection() on inputs. Double check to make sure!
            if ((selectedText || !node.textContent) && textCopy && copyRightAppender && 'firefox' !== this.helper.browserDetect()) {

                return false;
            }

            if (-1 !== nodeContent.indexOf('cut')) {
                node.classList.add('cp-disable');
            } else if (-1 !== nodeContent.indexOf('copy')) {
                node.classList.add('cp-disable');
            } else if (-1 !== nodeContent.indexOf('select all') && !target.value) {
                node.classList.add('cp-disable');
            }
        });
    }

    attachEvents() {

        let cxMenu = document.querySelector('.cp-context-menu');

        this.prepare();

        cxMenu && document.addEventListener('click', (event) => {

            let isClickInsideElement = cxMenu.contains(event.target);

            if (!isClickInsideElement) {

                cxMenu.style.display = 'none';

                delete this.props;
            }
        });

        cxMenu && cxMenu.addEventListener('click', event => {

            event.preventDefault();

            let cb = event.target.parentElement.getAttribute('data-callback');

            if (!cb) {

                cb = event.target.getAttribute('data-callback');
            }

            cb && this[cb]();
        });
    }

    openLinkInNewTab() {

        if (this.props && this.props.target && 'A' === this.props.target.tagName) {

            window.open(this.props.target.getAttribute('href'), '_blank');

            this.hidden();

            return;
        }

        let el = this.props && this.props.target;

        while (el && el.parentNode && 'A' === el.parentNode.tagName) {

            el = el.parentNode;

            window.open(el.getAttribute('href'), '_blank');
            this.hidden();
        }
    }

    openLinkInNewWindow() {

        let features = `screenX=0,screenY=0,location=yes,height=${window.innerHeight},width=${window.innerWidth},scrollbars=yes,status=yes`;

        if (this.props && this.props.target && 'A' === this.props.target.tagName) {

            window.open(this.props.target.getAttribute('href'), '_blank', features);

            this.hidden();

            return;
        }

        let el = this.props && this.props.target;

        while (el && el.parentNode && 'A' === el.parentNode.tagName) {

            el = el.parentNode;

            window.open(el.getAttribute('href'), '_blank', features);

            this.hidden();
        }
    }

    capitalizeFirstLetter(s) {

        return s[0].toUpperCase() + s.slice(1);
    };

    getExcerptText(text) {

        text = text.trim();

        let lengthLimit = 27;
        let suffix = '';

        if (text.length > 30) {

            suffix = '...';
        }

        return text.substring(0, lengthLimit) + suffix;
    }

    undo() {

        if ('firefox' === this.helper.browserDetect()) {

            this.props && this.props.target.select();
        }

        document.execCommand('undo', false, null);

        this.hidden();
    }

    redo() {

        if ('firefox' === this.helper.browserDetect()) {

            this.props && this.props.target.select();
        }

        document.execCommand('redo', false, null);

        this.hidden();
    }

    emoji() {

        this.hidden();
    }

    selectTempInput(value) {

        const elem = document.createElement('input');
        elem.id = 'cp-temp-input';
        elem.value = value;
        document.body.appendChild(elem);
        elem.type = 'text';
        elem.select();
        elem.style.top = 0;
        elem.style.opacity = 0;
        elem.style.zIndex = -9999;
        elem.style.position = "absolute";
    }

    copyAddress() {

        this.selectTempInput(window.location.toString());

        this.copyCommand();
    }

    copyLink() {

        if (!this.props) return false;

        let value = this.props.target.getAttribute('href');

        if ('A' !== this.props.target.tagName || !value) {

            value = this.props.target.parentNode && this.props.target.parentNode.getAttribute('href');
        }

        this.selectTempInput(value);

        this.copyCommand();
    }

    copyCommand() {

        document.execCommand('copy');

        this.hidden();
    }

    cutCommand() {

        document.execCommand('cut');

        this.hidden();
    }

    async PasteAsHTMLCommand() {

        try {

            this.props.target.focus();

            await navigator.clipboard.readText()
                //Optional
                .then(copiedText => {

                    document.execCommand('insertHTML', false, copiedText);
                });

        } catch (e) {

            console.log('Clipboard permission is blocked!');
        }

        this.hidden();
    }

    async PasteAsPlainTextCommand() {

        try {

            this.props.target.focus();

            await navigator.clipboard.readText()
                //Optional
                .then(copiedText => {

                    document.execCommand('insertText', false, copiedText);
                });

        } catch (e) {

            console.log('Clipboard permission is blocked!');
        }

        this.hidden();
    }

    backPage() {

        history.back();

        this.hidden();
    }

    reloadPage() {

        location.reload();

        this.hidden();
    }

    selectAll() {

        let specificTags = ['A', 'INPUT', 'TEXTAREA', 'IMG'];

        if (this.props && !specificTags.includes(this.props.target.tagName)) {

            document.body.focus();
            document.body && 'function' === typeof document.body.select && document.body.select();

        } else {

            this.props && this.props.target && this.props.target.select();
        }

        this.hidden();
    }

    hidden() {

        let cxMenu = document.querySelector('.cp-context-menu');

        if (!cxMenu) {

            return false;
        }

        cxMenu.style.display = 'none';

        delete this.props;

        return true;
    }

    addToFavorites() {

        if (!this.props || !this.props.target) {

            this.hidden();

            return false;
        }

        let title = this.props.target.textContent;
        let url = this.props.target.getAttribute('href');

        if (window.sidebar && window.sidebar.addPanel) { // Firefox <23

            window.sidebar.addPanel(title, url, '');

        } else if (window.external && ('AddFavorite' in window.external)) { // Internet Explorer

            window.external.AddFavorite(url, title);

        } else if (window.opera && window.print || window.sidebar && !(window.sidebar instanceof Node)) { // Opera <15 and Firefox >23

            let triggerBookmark = document.createElement('a');
            triggerBookmark.className = 'cp-cx-opera-bookmark';
            triggerBookmark.style.display = 'none';
            document.body.appendChild(triggerBookmark);

            /**
             * For Firefox <23 and Opera <15, no need for JS to add to bookmarks
             * The only thing needed is a `title` and a `rel="sidebar"`
             * To ensure that the bookmarked URL doesn't have a complementary `#` from our trigger's href
             * we force the current URL
             */
            triggerBookmark.attr('rel', 'sidebar').attr('title', title).attr('href', url);

            let triggerBookmarkElement = document.querySelector('.' + triggerBookmark.className);
            triggerBookmarkElement && triggerBookmark.remove();

        } else { // For the other browsers (mainly WebKit) we use a simple alert to inform users that they can add to bookmarks with ctrl+D/cmd+D

            let platformShortcut = 'mac' === this.helper.platformDetect() ? 'Command/Cmd' : 'CTRL';

            if (this.hidden()) {

                alert('You can add this page to your bookmarks by pressing ' + platformShortcut + ' + D on your keyboard.');
            }

            return false;
        }
        // If you have something in the `href` of your trigger

        this.hidden();
    }

    toggleFullScreen() {

        if (!document.fullscreenElement) {
            'function' === typeof document.documentElement.requestFullscreen && document.documentElement.requestFullscreen();
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }

        this.hidden();
    }
}
