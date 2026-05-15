import {checkOnDevClose} from '../detector/detector';
import {config} from './config';
import {clearLog} from './log';
import {clearDevToolOpenState} from './open-state';
import {hackAlert, isPC, onPageShowHide} from './util';

let interval = null, timer = null;
const calls = [];
let time = 0;

export function initInterval () {
    let _pause = false;
    const pause = () => {_pause = true;};
    const goon = () => {_pause = false;};
    hackAlert(pause, goon);
    onPageShowHide(goon, pause);

    interval = window.setInterval(() => {
        if (_pause) return;
        calls.forEach(({type, handle}) => {
            clearDevToolOpenState(type);
            handle(time++);
        });
        clearLog();
        checkOnDevClose();
    }, config.interval);

    timer = setTimeout(() => {
        if (!isPC()) {
            clearDDInterval();
        }
    }, config.stopIntervalTime);
}

export function registInterval (type, handle) {
    calls.push({type, handle});
}

export function clearDDInterval () {
    window.clearInterval(interval);
}

export function clearDDTimeout () {
    window.clearTimeout(timer);
}