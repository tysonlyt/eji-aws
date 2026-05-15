import {DETECTOR_TYPE} from './constant';

let isLastStateOpenedBool = false;

export function isLastStateOpened () {
    return isLastStateOpenedBool;
}

const OpenState = {};

for (const k in DETECTOR_TYPE) {
    OpenState[DETECTOR_TYPE[k]] = false;
}

export function markDevToolOpenState (type) {
    OpenState[type] = true;
}

export function clearDevToolOpenState (type) {
    OpenState[type] = false;
}

export function isDevToolOpened () {
    for (const key in OpenState) {
        if (OpenState[key]) {
            isLastStateOpenedBool = true;
            return true;
        }
    }
    isLastStateOpenedBool = false;
    return false;
}
