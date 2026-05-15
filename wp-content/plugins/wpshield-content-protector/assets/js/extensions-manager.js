import PopupMessage from "../../src/Components/Addons/PopupMessage/js/popup-message";

export default function ExtensionsManager(protector, event, params) {

    //This statement is excluded from extensions functionalities and working standalone!
    event && event.preventDefault();

    new AlertExtensionsHandler(protector, params);

    return true;
}

export function AlertExtensionsHandler(protector, params) {

    if ('undefined' !== typeof AudioAlert) {

        new AudioAlert(protector, params.audioAlert ?? []);
    }

    new PopupMessage(protector, params.popupMessage ?? []);
}

export function FilterAndCondition(protector, event, filters) {

    if (!filters) {

        return true;
    }

    if ('undefined' === typeof FilterObjects) {

        return true;
    }

    let filterObjects = new FilterObjects({protector, event});

    let result = {};

    Object.values(filters).map(filter => {

        if (!filter || !filter.in) {

            return false;
        }

        if (!filterObjects[filter.in]) {

            return false;
        }

        result[filter.in] = filterObjects[filter.in] && filterObjects[filter.in].init();
    });

    return !Object.values(result).includes(false);
}
