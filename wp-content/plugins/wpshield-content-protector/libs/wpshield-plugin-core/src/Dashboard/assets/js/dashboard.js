class Dashboard {

    /**
     * Localization php handler data.
     *
     * @since 1.0.0
     */
    l10n = {
        nonce: "",
        endpoint: "",
        referrer: "",
        adminPage: "",
        settingsIcon: ""
    };

    constructor() {

        if ('undefined' !== DashboardL10n) {

            this.l10n = DashboardL10n;
        }

        this.handleCurrentMenu();

        this.handleActions();
    }

    handleCurrentMenu() {

        var menu = document.querySelector('#toplevel_page_bs-product-pages-wpshield-settings');

        if (!menu) return false;

        menu.classList.add('wp-has-current-submenu');
        menu.classList.remove('wp-not-current-submenu');

        var link = menu.querySelectorAll('a');

        link && link.forEach(item => {

            if ('Settings' === item.textContent.trim()) {

                item.classList.add('current');
                item.parentElement.classList.add('current');

                return false;
            }

            if ('WP Shield' !== item.textContent.trim()) {

                return false;
            }

            item.classList.add('wp-has-current-submenu');
        });
    }

    handleActions() {

        let installButton = document.querySelectorAll('.product-button-install');
        installButton && installButton.forEach(button => button.addEventListener('click', event => {
            this.handleInstall(event);
        }));

        let updateButton = document.querySelectorAll('.product-button-update');
        updateButton && updateButton.forEach(button => button.addEventListener('click', event => {
            this.handleInstall(event, 'update_plugin');
        }));
    }

    async fetchData(url = '', data = {}) {

        var formData = new FormData();

        Object.values(data).forEach((value, index) => {
            Object.keys(data).forEach((param, num) => {
                if (index !== num) return false;
                formData.append(param, value);
            });
        });

        formData.append('nonce', this.l10n.nonce);

        const response = await fetch(url, {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            },
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
            body: new URLSearchParams(formData),
        });

        return response.json();
    }

    handleInstall(event, actionType = 'install_plugin') {

        var parent;

        event.preventDefault();

        var isLinkTag = 'A' === event.target.tagName;

        var slug = !isLinkTag ?
            event.target.parentElement.getAttribute('data-slug') :
            event.target.getAttribute('data-slug');

        var loaderBall = document.createElement('div');
        loaderBall.classList.add('loader-ball');

        if (isLinkTag) {

            parent = event.target.parentElement;
            parent.after(loaderBall);

        } else {

            parent = event.target.parentElement.parentElement;
            parent.after(loaderBall);
        }

        parent && parent.parentElement.classList.add('plugin-core-fade');

        this.fetchData(this.l10n.endpoint, {slug, action: actionType, page: this.l10n.referrer}).then(response => {

            if (!response || !response.success) {

                alert(response.data.message || `Install ${slug} is failed!`);

                document.querySelector('.loader-ball').remove();
                parent && parent.parentElement.classList.remove('plugin-core-fade');

                return false;
            }

            document.querySelector('.loader-ball').remove();
            parent && parent.parentElement.classList.remove('plugin-core-fade');

            let activatesWrapper = document.querySelector('.products-active');

            if ('install_plugin' === actionType) {

                let button = event.target;

                if ('A' !== button.tagName){
                    button.parentElement();
                }

                button.setAttribute('href' , `${this.l10n.adminPage}?page=wpshield/${slug}`);
                button.innerHTML = `${this.l10n.settingsIcon}Settings Panel`;
                let wrapper = event.target.closest('.product-item');
                if (wrapper && activatesWrapper) {
                    activatesWrapper.innerHTML += wrapper.outerHTML;
                    wrapper.remove();
                }
            } else {
                event.target.textContent = 'Updated!';
            }
        });
    }
}

new Dashboard();