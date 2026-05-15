import {isEdge, isFirefox, isInIframe, isIOSChrome, isIOSEdge, isQQBrowser} from '../utils/util';
import {DETECTOR_TYPE} from '../utils/constant';

const EnableMap = {
    [DETECTOR_TYPE.REG_TO_STRING]: (isQQBrowser || isFirefox),
    [DETECTOR_TYPE.DEFINE_ID]: true,
    [DETECTOR_TYPE.SIZE]: (!isInIframe && !isEdge),

    // ! Judgment right or wrong ios chrome or edge true Forbidden date func detector, cause meeting. Debugger detector helmet bottom
    [DETECTOR_TYPE.DATE_TO_STRING]: (!isIOSChrome && !isIOSEdge),
    [DETECTOR_TYPE.FUNC_TO_STRING]: (!isIOSChrome && !isIOSEdge),
    [DETECTOR_TYPE.DEBUGGER]: (isIOSChrome || isIOSEdge),
};

export function processDetectorEnableStatus (name, detector) {
    if (typeof detector !== 'function') return;

    const value = EnableMap[name];

    if (typeof value === 'undefined') {
        value = true;
    } else if (typeof value === 'function') {
        value = value();
    }

    if (value === true) {
        detector();
    }
}