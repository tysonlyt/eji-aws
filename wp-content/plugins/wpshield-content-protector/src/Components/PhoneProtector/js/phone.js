'use strict';

export default class Phone {

    /**
     * EMAIL RELATED LOGIC
     */
    constructor() {

        // set tel click
        document.querySelectorAll('a[data-enc-phone]').forEach(node => {

            node.addEventListener('click', event => {

                let element = event.target;

                if ('SPAN' === event.target.tagName) {

                    element = element.parentElement;
                }

                // parse tel link
                this.tel(element);
                // parse title attribute
                this.parseTitle(element);
                // parse input fields
                this.setInputValue(element);
            });
        });
    }

    // fetch phone from data attribute
    fetchEmail(el) {
        let phone = el.getAttribute('data-enc-phone');

        if (!phone) {
            return null;
        }

        // replace [at] sign
        phone = phone.replace(/\[at\]/g, '@');

        // encode
        phone = this.rot13(phone);

        return phone;
    }

    // encoding method
    rot13(s) {
        // source: http://jsfromhell.com/string/rot13
        return s.replace(/[a-zA-Z]/g, function (c) {
            return String.fromCharCode((c <= 'Z' ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
        });
    }

    // replace phone in title attribute
    parseTitle(el) {
        let title = el.getAttribute('title');
        let phone = this.fetchEmail(el);

        if (title && phone) {
            title = title.replace('{{phone}}', phone);
            el.setAttribute('title', title);
        }
    }

    // set input value attribute
    setInputValue(el) {
        let phone = this.fetchEmail(el);

        if (phone) {
            el.setAttribute('value', phone);
        }
    }

    // open tel link
    tel(el) {
        let phone = this.fetchEmail(el);

        if (phone) {
            window.location.href = 'tel:' + phone;
        }
    }

    // revert
    revert(el, rtl) {
        let phone = this.fetchEmail(el);

        if (phone) {
            rtl.text(phone);
            rtl.removeClass('eeb-rtl');
        }
    }
}