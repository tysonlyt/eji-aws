import {triggerOnDevOpen} from './detector';
import {registInterval} from '../utils/interval';
import {log} from '../utils/log';
import {DETECTOR_TYPE} from '../utils/constant';

export default function detector () {
    const type = DETECTOR_TYPE.DEFINE_ID;
    const div = document.createElement('div');
    div.__defineGetter__('id', function () {
        triggerOnDevOpen(type);
    });
    Object.defineProperty(div, 'id', {
        get: function () {
            triggerOnDevOpen(type);
        },
    });
    registInterval(type, () => {
        log(div);
    });
}