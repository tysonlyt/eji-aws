'use strict';

export default class Email {

    /**
     * EMAIL RELATED LOGIC
     */
    constructor() {

        // set mailto click
        document.querySelectorAll('a[data-enc-email]').forEach(node => {

            node.addEventListener('click', event => {

                let element = event.target;

                if ('SPAN' === event.target.tagName) {

                    element = element.parentElement;
                }

                // parse mailto link
                this.mailto(element);
                // parse title attribute
                this.parseTitle(element);
                // parse input fields
                this.setInputValue(element);
            });
        });
    }

    // fetch email from data attribute
    fetchEmail(el) {
        let email = el.getAttribute('data-enc-email');

        if (!email) {
            return null;
        }

        // replace [at] sign
        email = email.replace(/\[at\]/g, '@');

        // encode
        email = this.rot13(email);

        return email;
    }

    // encoding method
    rot13(s) {
        // source: http://jsfromhell.com/string/rot13
        return s.replace(/[a-zA-Z]/g, function (c) {
            return String.fromCharCode((c <= 'Z' ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
        });
    }

    // replace email in title attribute
    parseTitle(el) {
        let title = el.getAttribute('title');
        let email = this.fetchEmail(el);

        if (title && email) {
            title = title.replace('{{email}}', email);
            el.setAttribute('title', title);
        }
    }

    // set input value attribute
    setInputValue(el) {
        let email = this.fetchEmail(el);

        if (email) {
            el.setAttribute('value', email);
        }
    }

    // open mailto link
    mailto(el) {
        let email = this.fetchEmail(el);

        if (email) {
            window.location.href = 'mailto:' + email;
        }
    }

    // revert
    revert(el, rtl) {
        let email = this.fetchEmail(el);

        if (email) {
            rtl.text(email);
            rtl.removeClass('eeb-rtl');
        }
    }
}