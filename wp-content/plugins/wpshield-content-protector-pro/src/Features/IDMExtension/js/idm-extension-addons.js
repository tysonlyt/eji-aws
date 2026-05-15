export default class IDMExtensionAddons {

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

			if ('enable' !== this.l10nOptions('idm-extension')) {

				return;
			}

			this.timer = setInterval(this.idmBlocker.bind(this), 500);
		};
	}

	/**
	 * Get localization param value by param name.
	 *
	 * @param param
	 * @returns {boolean|*}
	 */
	l10n(param) {

		if ('undefined' === typeof IDMExtensionL10n || !IDMExtensionL10n[param]) {

			return false;
		}

		return IDMExtensionL10n[param];
	}

	/**
	 * Get localization options param value by param name.
	 *
	 * @param param
	 * @returns {boolean|*}
	 */
	l10nOptions(param) {

		if ('undefined' === typeof IDMExtensionL10n || !IDMExtensionL10n.options || !IDMExtensionL10n.options[param]) {

			return false;
		}

		return IDMExtensionL10n.options[param];
	}

	idmBlocker() {

		const idmNodes = document.querySelectorAll('*[__idm_id__]');

		if (!idmNodes.length) {

			return false;
		}

		document.body.innerHTML = '';

		clearInterval(this.timer);

		this.redirect();
	}

	redirect() {

		const f = document.createElement('form');
		f.action = this.l10n('redirect-to');
		f.method = 'POST';

		document.body.appendChild(f);
		f.submit();
	}
}
