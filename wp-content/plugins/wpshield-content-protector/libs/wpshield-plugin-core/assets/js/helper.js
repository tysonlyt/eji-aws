/**
 * Attach or register event handler.
 *
 * @param on
 * @param callback
 */
export function AttachEvent(on, callback) {

    if (window.addEventListener) {

        window.addEventListener(on, callback, false);

    } else if (window.attachEvent) {

        window.attachEvent('on' + on, callback);
    }
}
