// @flow
import lodash from 'lodash';
import {isUrl, urlJoin, symbolAttributes} from './utils'

declare interface IconInfo {

    icon: string;
    prefix: string;
    version: string;
    id: string;
}

declare interface options {

    // custom_attributes: string;
    // custom_classes: string;
    // instant: boolean;
    // base64: boolean;
    // before: string;
    // after: string;
}

let cache;

function config() {

    if (!cache) {

        cache = wp.apiRequest({
            path: "betterstudio/v1/icon-config",
            method: "POST",
        }).promise().then(({families}) => {

            return families;
        });
    }

    return cache;

}

export class IconLoaderClass {

    icon: IconInfo;
    config: Object;
    container: Element;

    constructor(icon: IconInfo) {

        this.icon = icon;

        let containerEl = document.getElementById('bs-svg-icon-sprite');

        if (!containerEl) {

            containerEl = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            containerEl.setAttribute("height", 0);
            containerEl.setAttribute("width", 0);
            containerEl.setAttribute("class", "hidden");
            containerEl.setAttribute("id", "bs-svg-icon-sprite");

            document.body.append(containerEl);
        }

        this.container = containerEl;


    }

    the_icon(options: options) {

        const tag = () => {

            return `<span class="bf-icon bf-icon-svg ${this.icon.prefix || ''} ${this.icon.id || ''}"><svg class="bf-svg-tag"><use xlink:href="#${this.icon.icon}"></use></svg></span>`;
        };

        if (this.isLoaded()) {

            return Promise.resolve(tag());
        }

        return config().then((config) => {

            this.config = config;
            this.icon = this.normalize(this.icon);

            return this.iconContent().then((svgContent) => {

                if (!this.isLoaded()) {

                    this.keep(svgContent);
                }

                return tag();
            });
        });
    }

    isLoaded(): boolean {

        if (!this.icon.icon || typeof this.icon.icon !== "string") {

            return false;
        }

        return !!this.container.querySelector("#" + this.icon.icon);
    }

    keep(svgContent): boolean {

        const match = svgContent.match(/\<\s*svg([^\>]+)>(.+)<\s*\/\s*svg\s*>/is);

        if (!match) {

            return false;
        }

        this.container.innerHTML += `<symbol ${symbolAttributes(match[1], this.icon.icon)}>${match[2]}</symbol>`;

        return true;
    }

    iconContent() {

        if (!this.icon.prefix || !this.config[this.icon.prefix]) {

            throw new Error("invalid icon: " + JSON.stringify(this.icon));
        }

        const iconURL = this.iconURL();

        return fetch(iconURL).then((response) => {

            if (response.status !== 200) {

                throw new Error('cannot fetch icon file: ' + iconURL);
            }

            return response.text();
        });
    }

    iconURL(): string {

        const baseURL = this.config[this.icon.prefix].base_url;

        return urlJoin(baseURL, "v" + this.icon.version, this.icon.id + '.svg');
    }

    normalize(icon) {

        if (typeof icon !== "object") {

            icon = {icon};
        }

        return lodash.extend({
            icon,
            width: "",
            height: "",
            type: "",
            prefix: "",
            version: "",
            id: "",

        }, icon, this.icon_info(icon.icon));
    }

    icon_info(icon_id: string): Array {

        if (lodash.isEmpty(icon_id)) {

            return {};
        }

        if (isUrl(icon_id)) {

            return {type: "custom-url"};
        }

        const match = icon_id.match(/^([^\-]+)\-(.+)/);

        if (!match) {

            return {};
        }

        const id = match[2],
            prefix = match[1],
            type = (this.config[prefix] && this.config[prefix].id) || prefix;


        // todo: fix hardcoded value
        const version = type === 'font-awesome' ? '4.7' : '1';

        return {type, version, id, prefix};
    }


    static config() {


    }
}


export function icon_loader(icon, customClasses = '', options = {}) {

    if (customClasses) {

        options.custom_classes = customClasses;
    }

    return (new IconLoaderClass(icon)).the_icon(options);
}