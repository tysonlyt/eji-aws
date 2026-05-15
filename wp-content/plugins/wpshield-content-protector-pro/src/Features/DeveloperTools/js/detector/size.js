import {DETECTOR_TYPE} from '../utils/constant';
import {clearDevToolOpenState} from '../utils/open-state';
import {triggerOnDevOpen} from './detector';

function checkWindowSizeUneven () {
    const screenRatio = countScreenZoomRatio();
    if (screenRatio === false) { // Scaling size Detector
        return true;
    }
    const type = DETECTOR_TYPE.SIZE;
    const widthUneven = window.outerWidth - window.innerWidth > 200; // One-point prevention
    const heightUneven = window.outerHeight - window.innerHeight > 300; // One-point prevention
    if (widthUneven || heightUneven) {
        triggerOnDevOpen(type);
        return false;
    }
    clearDevToolOpenState(type);
    return true;
}

function countScreenZoomRatio () {
    if (checkExist(window.devicePixelRatio)) {
        return window.devicePixelRatio;
    }
    const screen = window.screen;
    if (checkExist(screen)) {
        return false;
    }
    if (screen.deviceXDPI && screen.logicalXDPI) {
        return screen.deviceXDPI / screen.logicalXDPI;
    }
    return false;
};

export default function detector () {
    checkWindowSizeUneven();
    window.addEventListener('resize', () => {
        setTimeout(checkWindowSizeUneven, 100);
    }, true);
}

function checkExist (v) {
    return typeof v !== 'undefined' && v !== null;
}