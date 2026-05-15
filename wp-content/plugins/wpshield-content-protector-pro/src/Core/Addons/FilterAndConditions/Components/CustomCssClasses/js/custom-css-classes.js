class CustomCssClasses {

    event;
    isInclude;
    protector;

    constructor(protector, event) {

        this.event = event;
        this.protector = protector;
    }

    /**
     * @since 1.0.0
     * @returns {boolean} true on success protector, false when otherwise (in other words, the protector is disabled!).
     */
    init() {

        let isProtected = this.l10nFilter('is-protected');

        if ('disable' !== this.l10nFilter('css-class')) {

            for (let selector of this.l10nFilter('css-class')) {

                if (!selector) {

                    continue;
                }

                let targetElement = document.querySelectorAll(selector);

                targetElement && targetElement.forEach(this.detectTargetNode.bind(this));

                //Protector is enabled!
                if (this.isInclude && isProtected) {

                    return true;
                }
                //Protector is disabled!
                if (this.isInclude && !isProtected) {

                    return false;
                }
            }
        }

        //Protector is enabled!
        return true;
    }

    l10nFilter(param) {

        if ('undefined' !== typeof CssClassRightClickL10n && 'undefined' !== typeof CssClassRightClickL10n.filter[param] && 'right-click' === this.protector) {

            return CssClassRightClickL10n.filter[param];
        }
        if ('undefined' !== typeof CssClassTextCopyL10n && 'undefined' !== typeof CssClassTextCopyL10n.filter[param] && 'text-copy' === this.protector) {

            return CssClassTextCopyL10n.filter[param];
        }
        if ('undefined' !== typeof CssClassImagesL10n && 'undefined' !== typeof CssClassImagesL10n.filter[param] && 'images' === this.protector) {

            return CssClassImagesL10n.filter[param];
        }
        if ('undefined' !== typeof CssClassVideosL10n && 'undefined' !== typeof CssClassVideosL10n.filter[param] && 'videos' === this.protector) {

            return CssClassVideosL10n.filter[param];
        }
        if ('undefined' !== typeof CssClassAudiosL10n && 'undefined' !== typeof CssClassAudiosL10n.filter[param] && 'audios' === this.protector) {

            return CssClassAudiosL10n.filter[param];
        }

        return 'disable';
    }

    findAscendingTag(node) {
        var el = this.event.target;

        while (el && el.parentNode) {

            if (el === node) {

                return el;
            }

            el = el.parentNode;

            return el === node ? el : null;
        }
    }

    detectTargetNode(node, number) {

        if (this.isInclude) {

            return true;
        }

        if (node === this.event.target) {

            return this.isInclude = true;
        }

        if (node === this.findAscendingTag(node)) {

            return this.isInclude = true;
        }

        return this.isInclude = false;
    }
}
