import {config} from './config';
import {isIE} from './util';

const console = window.console || {
    log: function () {
        return;
    }
};

export const log = (() => {
    // ie Non-supported cache use log etc.
    return isIE ? ((...args) => {return console.log(...args);}) : console.log;
})();

const clearLogFunc = (() => {
    // ie Non-supported cache use log etc.
    return isIE ? (() => {return console.clear();}) : console.clear;
})();

export function clearLog () {
    if (config.clearLog)
        clearLogFunc();
}
