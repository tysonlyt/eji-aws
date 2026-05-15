import {registInterval} from '../utils/interval';
import {isFirefox, isQQBrowser} from '../utils/util';
import {log} from '../utils/log';
import {triggerOnDevOpen} from './detector';
import {DETECTOR_TYPE} from '../utils/constant';
 
// This individual method is in chrome.
export default function detector () {
    const type = DETECTOR_TYPE.REG_TO_STRING;
    let lastTime = 0;
    const reg = /./;
    log(reg);
    reg.toString = function () {
        if (isQQBrowser) { // ! qq The control table is dead, and the breakthrough time is felt.
            const time = new Date().getTime();
            if (lastTime && time - lastTime < 100) {
                triggerOnDevOpen(type);
            } else {
                lastTime = time;
            }
        } else if (isFirefox) {
            triggerOnDevOpen(type);
        }
        return '';
    };

    registInterval(type, () => {
        log(reg);
    });
}