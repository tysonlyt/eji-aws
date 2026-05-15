
declare type DETECTOR_TYPE = -1 | 0 | 1 | 2 | 3 | 4;
declare interface optionStatic {
    md5?: string; // Bypass the disabled md5 value, see 3.2 for details, the bypass disable is not enabled by default
    url?: string; // Jump to the page when closing the page fails, the default value is localhost
    tkName?: string; // Bypass the url parameter name when disabled, the default is ddtk
    onDevToolOpen?(type: DETECTOR_TYPE, next: Function): void; // Callback for opening the developer panel, the url parameter is invalid when it is enabled, and the type is the monitoring mode, see 3.5 for details
    onDevToolClose?(): void;
    interval?: number; // Timer interval is 200ms by default
    disableMenu?: boolean; // Whether to disable the right-click menu The default is true
    stopIntervalTime?: number;
    clearIntervalWhenDevOpenTrigger?: boolean; // Whether to stop monitoring after triggering The default is false. This parameter is invalid when using ondevtoolclose
    detectors: Array<DETECTOR_TYPE>;  // Enabled detectors For details of detectors, see 3.5. The default is all, it is recommended to use all
    clearLog?: boolean; // Whether to clear the log every time
    disableSelect?: boolean; // Whether to disable select text The default is true
    disableCopy?: boolean; // Whether to disable copy text The default is true
    disableCut?: boolean; // Whether to disable cut text The default is true
}
declare interface DDTStatic {
    (option?: optionStatic): void;
    md5(text?: string): string;
    DETECTOR_TYPE: {
        UNKONW: -1;
        REG_TO_STRING: 0;
        DEFINE_ID: 1;
        SIZE: 2;
        DATE_TO_STRING: 3;
        FUNC_TO_STRING: 4;
        DEBUGGER: 5;
        // LOG_TIME: 6;
    }
    version: string;
    isDevToolOpened(): boolean;
}

declare const ddt: DDTStatic;

export default ddt;