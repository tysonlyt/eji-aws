import {config} from './config';
import {clearDDInterval} from './interval';

export function closeWindow () {
    clearDDInterval();
    if (config.url) {
        window.location.href = config.url;
    } else {
        try {
            window.opener = null;
            window.open('', '_self');
            window.close();
            window.history.back();
        } catch (e) {
            console.log(e);
        }
        setTimeout(() => {
            window.location.href = `https://tackchen.gitee.io/404.html?h=${encodeURIComponent(location.host)}`;
        }, 500);
    }
}
