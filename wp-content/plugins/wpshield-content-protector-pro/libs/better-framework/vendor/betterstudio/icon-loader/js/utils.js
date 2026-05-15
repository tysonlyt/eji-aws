import lodash from 'lodash';

function normalize(strArray) {
    const resultArray = [];
    if (strArray.length === 0) {
        return '';
    }

    if (typeof strArray[0] !== 'string') {
        throw new TypeError('Url must be a string. Received ' + strArray[0]);
    }

    // If the first part is a plain protocol, we combine it with the next part.
    if (strArray[0].match(/^[^/:]+:\/*$/) && strArray.length > 1) {
        strArray[0] = strArray.shift() + strArray[0];
    }

    // There must be two or three slashes in the file protocol, two slashes in anything else.
    if (strArray[0].match(/^file:\/\/\//)) {
        strArray[0] = strArray[0].replace(/^([^/:]+):\/*/, '$1:///');
    } else {
        strArray[0] = strArray[0].replace(/^([^/:]+):\/*/, '$1://');
    }

    for (let i = 0; i < strArray.length; i++) {
        let component = strArray[i];

        if (typeof component !== 'string') {
            throw new TypeError('Url must be a string. Received ' + component);
        }

        if (component === '') {
            continue;
        }

        if (i > 0) {
            // Removing the starting slashes for each component but the first.
            component = component.replace(/^[\/]+/, '');
        }
        if (i < strArray.length - 1) {
            // Removing the ending slashes for each component but the last.
            component = component.replace(/[\/]+$/, '');
        } else {
            // For the last component we will combine multiple slashes to a single one.
            component = component.replace(/[\/]+$/, '/');
        }

        resultArray.push(component);

    }

    let str = resultArray.join('/');
    // Each input component is now separated by a single slash except the possible first plain protocol part.

    // remove trailing slash before parameters or hash
    str = str.replace(/\/(\?|&|#[^!])/g, '$1');

    // replace ? in parameters with &
    const parts = str.split('?');
    str = parts.shift() + (parts.length > 0 ? '?' : '') + parts.join('&');

    return str;
}

/**
 * @link https://github.com/jfromaniello/url-join
 *
 * @param args
 * @returns {string|string}
 */
export function urlJoin(...args) {
    const parts = Array.from(Array.isArray(args[0]) ? args[0] : args);
    return normalize(parts);
}

export function isUrl(string) {

    let url;

    try {
        url = new URL(string);
    } catch (_) {
        return false;
    }

    return ['http:', 'https:'].indexOf(url.protocol) !== -1;
}


export function parseAttributes(attributes) {

    const el = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    el.innerHTML = `<symbol ${attributes}></symbol>`;
    const symbol = el.children[0];

    const attributesObj = {};

    for(let attributeIndex =0;  attributeIndex < symbol.attributes.length ; attributeIndex++) {

        const attributeKey = symbol.attributes[attributeIndex].name;

        attributesObj[attributeKey] = symbol.getAttribute(attributeKey);
    }
    return attributesObj;
}

export function attributes2string(attributes){

    let out = '';

    for(const key in attributes) {

        out += `${key}="${attributes[key]}" `;
    }

    return out.trim();
}

export function symbolAttributes(attributesString, id) {

    const attributes = lodash.omit(
        parseAttributes(attributesString),
        ["width", "height"]
    );

    if(id) {

        attributes.id = id;
    }

    return attributes2string(attributes);
}