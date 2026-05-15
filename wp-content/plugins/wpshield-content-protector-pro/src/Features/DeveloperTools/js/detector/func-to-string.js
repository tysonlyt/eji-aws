// // ! Associate ios mobile chrome possible

import {registInterval} from '../utils/interval';
import {triggerOnDevOpen} from './detector';
import {log, clearLog} from '../utils/log';
import {DETECTOR_TYPE} from '../utils/constant';
 
export default function detector (isIOSChrome) {
    if (isIOSChrome) return;
    const type = DETECTOR_TYPE.FUNC_TO_STRING;
    let count = 0;
    const func = () => {};
    func.toString = () => {
        count ++;
        return '';
    };

    const checkIsOpen = () => {
        count = 0;
        log(func);
        clearLog();
        if (count >= 2) {
            triggerOnDevOpen(type);
        }
    };

    registInterval(type, checkIsOpen);
}