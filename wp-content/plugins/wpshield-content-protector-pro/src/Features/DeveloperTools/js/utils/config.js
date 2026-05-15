import {closeWindow} from './close-window';

export const config = {
    md5: '',
    onDevToolOpen: closeWindow,
    onDevToolClose: null,
    url: '',
    tkName: 'ddtk',
    interval: 200,
    disableMenu: true,
    stopIntervalTime: 5000,
    clearIntervalWhenDevOpenTrigger: false,
    detectors: 'all',
    clearLog: true,
    disableSelect: false,
    disableCopy: false,
    disableCut: false,
};

const MultiTypeKeys = ['detectors', 'onDevToolClose'];

export function mergeConfig (opts = {}) {
    for (const k in config) {
        if (
            typeof opts[k] !== 'undefined' &&
            (typeof config[k] === typeof opts[k] || MultiTypeKeys.indexOf(k) !== -1)
        ) {
            config[k] = opts[k];
        }
    }
    checkConfig();
}

function checkConfig () {
    if (
        typeof config.onDevToolClose === 'function' &&
        config.clearIntervalWhenDevOpenTrigger === true
    ) {
        config.clearIntervalWhenDevOpenTrigger = false;
        console.warn('【DISABLE-DEVTOOL】clearIntervalWhenDevOpenTrigger currently in use onDevToolClose void');
    }
}