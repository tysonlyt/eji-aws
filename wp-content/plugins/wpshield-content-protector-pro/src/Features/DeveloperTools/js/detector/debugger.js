import {DETECTOR_TYPE} from '../utils/constant';
import {registInterval} from '../utils/interval';
import {triggerOnDevOpen} from './detector';

export default function detector (isIOSChrome) {
    if (isIOSChrome) {
        const type = DETECTOR_TYPE.DEBUGGER;
        // Residential ios chrome
        registInterval(type, () => {
            const date = Date.now();
            (() => {debugger;})();
            if (Date.now() - date > 100) {
                triggerOnDevOpen(type);
            }
        });
    }
}