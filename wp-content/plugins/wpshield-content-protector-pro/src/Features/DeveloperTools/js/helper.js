import './utils/log';
import {formatName, getUrlParam} from './utils/util';
import {mergeConfig, config} from './utils/config';
import md5 from './utils/md5';
import version from './version';
import {DETECTOR_TYPE} from './utils/constant';
import {isDevToolOpened} from './utils/open-state';
import {initInterval} from "./utils/interval";
import {disableKeyAndMenu} from "./utils/key-menu";
import {initDetectors} from "./detector/detector";

export function DevToolsHelper(options) {

    mergeConfig(options);
}

DevToolsHelper.md5 = md5;
DevToolsHelper.version = version;
DevToolsHelper.DETECTOR_TYPE = DETECTOR_TYPE;
DevToolsHelper.isDevToolOpened = isDevToolOpened;

export let tk = false;

export function CheckTK() {

    if (config.md5) {

        const TK = getUrlParam(config.tkName);

        if (md5(TK) === config.md5) {
            tk = true;
        }
    }

    tk = false;
}

export function checkScriptUse() {

    if (typeof document === 'undefined') {
        return;
    }

    const dom = document.querySelector('[disable-devtool-auto]');

    if (!dom) {
        return;
    }

    const json = {};

    ['md5', 'url', 'tk-name', 'interval', 'disable-menu', 'detectors'].forEach(name => {

        let value = dom.getAttribute(name);

        if (value !== null) {

            if (name === 'interval') {

                value = parseInt(value);

            } else if (name === 'disable-menu') {

                value = value === 'false' ? false : true;

            } else if (name === 'detector') {

                if (value !== 'all') {

                    value = value.split(' ');
                }
            }

            json[formatName(name)] = value;

        }
    });

    DevToolsHelper(json);

    CheckTK();

    if (tk) {

        return;
    }

    initInterval();
    disableKeyAndMenu();
    initDetectors();
}

checkScriptUse();