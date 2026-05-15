// @flow
import _ from "lodash"

type ControlProps = {
    onChange: Function;
};

export interface ControlInterface {

    init(element: HTMLElement): boolean;

    controlType(): string;

    destroy(): void;
}

export interface ManageControlData {

    dynamicValuesIndexes(): Array<string>;
}

export interface HaveDeprecation {

    transformData(prevData: any): any;
}

export interface ControlHaveData {

    dataType(): string;

    valueGet(): any;

    valueSet(value: mixed): boolean
}

export class ControlBase {

    _props: ControlProps;

    constructor(props: ?ControlProps) {

        this._props = _.extend({
            onChange: () => {
            }
        }, props);
    }

    propsSet(options: ControlProps) {

        this._props = _.extend(this._props, options);
    }

    props(index: ?string): ?ControlProps {

        if (typeof index === "undefined" || index === null) {

            return this._props;
        }

        if (typeof this._props[index] !== "undefined") {

            return this._props[index];
        }
    }

    onChange(value: mixed): boolean {

        this._props.onChange(value, this);

        return true;
    }
}
