import { AttachEvent } from "../../../../libs/wpshield-plugin-core/assets/js/helper";
import { KeyboardHelper } from "../../../../libs/wpshield-plugin-core/assets/js/keyboard-helper";

export default class PrintAddons {

	imageLayer;

	constructor() {

		this.initProtector();
	}

	initProtector() {

		if ('undefined' !== typeof PrintAddonsL10n && 'blank' === PrintAddonsL10n['type']) {

			if (wpshieldCP?.default?.ExtensionsManager) {

				let keyHandler = new KeyboardHelper(wpshieldCP?.default?.ExtensionsManager);

				new AttachEvent('keydown', event => {

					keyHandler.down(
						{
							event,
							protector: 'print',
							options: {
								audioAlert: {
									event
								},
								popupMessage: {
									event,
									onClose: () => {
										document.body.innerHTML = '';
									},
									enable: PrintAddonsL10n['type'],
									template: PrintAddonsL10n['popup-template'],
								},
							},
							hotKeys: PrintAddonsL10n['disabled-shortcuts'],
							filters: PrintAddonsL10n['print/filters']
						}
					);
				});
				new AttachEvent('keyup', event => {
					keyHandler.up(event);
				});
			}

			return;
		}

		if ('undefined' !== typeof PrintAddonsL10n && 'watermark' !== PrintAddonsL10n['type']) {

			return;
		}

		let beforePrint = this.beforePrint.bind(this);
		let afterPrint = this.afterPrint.bind(this);

		if (window.matchMedia) {
			let mediaQueryList = window.matchMedia('print');
			mediaQueryList.addListener((mql) => {
				if (mql.matches) {
					beforePrint();
				} else {
					afterPrint();
				}
			});
		}

		window.onbeforeprint = beforePrint;
		window.onafterprint = afterPrint;
	}

	beforePrint() {

		if ('undefined' === typeof PrintAddonsL10n || !PrintAddonsL10n['watermark'] || !PrintAddonsL10n['opacity']) return false;

		this.imageLayer = new Image();
		this.imageLayer.className = 'cp-image-layer';
		this.imageLayer.options = String(PrintAddonsL10n['opacity'] / 100);

		this.imageLayer.onload = this.onloadImageLayer.bind(this);

		this.imageLayer.src = PrintAddonsL10n['watermark'];
	}

	onloadImageLayer() {

		if (!this.imageLayer || !this.imageLayer.width || !this.imageLayer.height) return false;

		let layer = document.createElement('div');

		layer.id = 'cp-foreground-layer';
		layer.style.width = '100%';
		layer.style.height = '100%';
		layer.style.position = 'fixed';
		layer.style.top = '0';
		layer.style.left = '0';
		layer.style.opacity = String(PrintAddonsL10n['opacity'] / 100);

		let width = document.body.clientWidth;
		let height = document.body.clientHeight;

		let numOfCols = Math.round(width / this.imageLayer.width);
		let numOfRows = Math.round(height / this.imageLayer.height);
		let totalLimit = numOfCols * numOfRows;

		let start = new Date().getTime();
		let images = '';

		for (let i = 0; i <= totalLimit - 1; i++) {

			images += this.imageLayer.outerHTML;
		}

		let end = new Date().getTime();

		setTimeout(() => {

			layer.innerHTML = images;

			document.body.appendChild(layer);
		}, end - start);
	}

	afterPrint() {

		let layer = document.querySelector('#cp-foreground-layer');

		layer && layer.remove();
	}
}
