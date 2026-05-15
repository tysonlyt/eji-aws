export class KeyboardHelper {
    callback = () => {};

    constructor(callback) {
        this.callback = callback;
    }

    // Note: order is important
    activeKeys = {
        cmd: false,
        ctrl: false,
        alt: false,
        shift: false
    };

    /**
     * Handle key down.
     *
     * @param params
     * @returns {boolean}
     */
    down(params) {

        let code = params.event.keyCode ? params.event.keyCode : params.event.which;

        if (code === 16) {

            this.activeKeys.shift = true;

        } else if (code === 17) {

            this.activeKeys.ctrl = true;

        } else if (code === 18) {

            this.activeKeys.alt = true;

        } else if (code === 224) {

            this.activeKeys.cmd = true;

        } else {

            let current = '';

            for (let key in this.activeKeys) {

                if (this.activeKeys[key]) {

                    current += key;
                    current += '_';
                }
            }

            current += String.fromCharCode(code);
            current = current.toLowerCase();

            if (params.hotKeys.includes(current)) {

                this.callback(params.protector, params.event, params.options);

                return false;
            }
        }

        return true;
    }

    /**
     * Handle key up.
     *
     * @param event
     * @returns {boolean}
     */
    up(event) {

        let code = event.keyCode ? event.keyCode : event.which;

        if (code === 16) {
            this.activeKeys.shift = false;
        }

        if (code === 17) {
            this.activeKeys.ctrl = false;
        }

        if (code === 18) {
            this.activeKeys.alt = false;
        }

        if (code === 91) {
            this.activeKeys.cmd = false;
        }
    }
}