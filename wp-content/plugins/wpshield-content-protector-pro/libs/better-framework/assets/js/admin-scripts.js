/***
 *  BetterFramework is BetterStudio framework for themes and plugins.
 *
 *  ______      _   _             ______                                           _
 *  | ___ \    | | | |            |  ___|                                         | |
 *  | |_/ / ___| |_| |_ ___ _ __  | |_ _ __ __ _ _ __ ___   _____      _____  _ __| | __
 *  | ___ \/ _ \ __| __/ _ \ '__| |  _| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
 *  | |_/ /  __/ |_| ||  __/ |    | | | | | (_| | | | | | |  __/\ V  V / (_) | |  |   <
 *  \____/ \___|\__|\__\___|_|    \_| |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\
 *
 *  Copyright © 2017 Better Studio
 *
 *
 *  Our portfolio is here: https://betterstudio.com/
 *
 *  \--> BetterStudio, 2017 <--/
 */




var bf_ignore_reload_notice = false,
    Better_Framework = (function ($) {
        "use strict";

        /**
         * @file A WordPress-like hook system for JavaScript.
         *
         * This file demonstrates a simple hook system for JavaScript based on the hook
         * system in WordPress. The purpose of this is to make your code extensible and
         * allowing other developers to hook into your code with their own callbacks.
         *
         * There are other ways to do this, but this will feel right at home for
         * WordPress developers.
         *
         * @author Rheinard Korf
         * @license GPL2 (https://www.gnu.org/licenses/gpl-2.0.html)
         *
         * @requires underscore.js (http://underscorejs.org/)
         */
        var Hooks = {};

        Hooks.actions = Hooks.actions || {}; // Registered actions
        Hooks.filters = Hooks.filters || {}; // Registered filters

        /**
         * Add a new Action callback to Hooks.actions
         *
         * @param tag The tag specified by do_action()
         * @param callback The callback function to call when do_action() is called
         * @param priority The order in which to call the callbacks. Default: 10 (like WordPress)
         */
        Hooks.add_action = function (tag, callback, priority) {

            if (typeof priority === "undefined") {
                priority = 10;
            }

            // If the tag doesn't exist, create it.
            Hooks.actions[tag] = Hooks.actions[tag] || [];
            Hooks.actions[tag].push({priority: priority, callback: callback});

        }

        /**
         * Add a new Filter callback to Hooks.filters
         *
         * @param tag The tag specified by apply_filters()
         * @param callback The callback function to call when apply_filters() is called
         * @param priority Priority of filter to apply. Default: 10 (like WordPress)
         */
        Hooks.add_filter = function (tag, callback, priority) {

            if (typeof priority === "undefined") {
                priority = 10;
            }

            // If the tag doesn't exist, create it.
            Hooks.filters[tag] = Hooks.filters[tag] || [];
            Hooks.filters[tag].push({priority: priority, callback: callback});

        }

        /**
         * Remove an Anction callback from Hooks.actions
         *
         * Must be the exact same callback signature.
         * Warning: Anonymous functions can not be removed.
         * @param tag The tag specified by do_action()
         * @param callback The callback function to remove
         */
        Hooks.remove_action = function (tag, callback) {

            Hooks.actions[tag] = Hooks.actions[tag] || [];

            Hooks.actions[tag].forEach(function (filter, i) {
                if (filter.callback === callback) {
                    Hooks.actions[tag].splice(i, 1);
                }
            });
        }

        /**
         * Remove a Filter callback from Hooks.filters
         *
         * Must be the exact same callback signature.
         * Warning: Anonymous functions can not be removed.
         * @param tag The tag specified by apply_filters()
         * @param callback The callback function to remove
         */
        Hooks.remove_filter = function (tag, callback) {

            Hooks.filters[tag] = Hooks.filters[tag] || [];

            Hooks.filters[tag].forEach(function (filter, i) {
                if (filter.callback === callback) {
                    Hooks.filters[tag].splice(i, 1);
                }
            });
        }

        /**
         * Calls actions that are stored in Hooks.actions for a specific tag or nothing
         * if there are no actions to call.
         *
         * @param tag A registered tag in Hook.actions
         * @options Optional JavaScript object to pass to the callbacks
         */
        Hooks.do_action = function (tag, options) {

            var actions = [];

            if (typeof Hooks.actions[tag] !== "undefined" && Hooks.actions[tag].length > 0) {

                Hooks.actions[tag].forEach(function (hook) {

                    actions[hook.priority] = actions[hook.priority] || [];
                    actions[hook.priority].push(hook.callback);

                });

                actions.forEach(function (hooks) {

                    hooks.forEach(function (callback) {
                        callback(options);
                    });

                });
            }

        }

        /**
         * Calls filters that are stored in Hooks.filters for a specific tag or return
         * original value if no filters exist.
         *
         * @param tag A registered tag in Hook.filters
         * @options Optional JavaScript object to pass to the callbacks
         */
        Hooks.apply_filters = function (tag, value, options) {

            var filters = [];

            if (typeof Hooks.filters[tag] !== "undefined" && Hooks.filters[tag].length > 0) {

                Hooks.filters[tag].forEach(function (hook) {

                    filters[hook.priority] = filters[hook.priority] || [];
                    filters[hook.priority].push(hook.callback);
                });

                filters.forEach(function (hooks) {

                    hooks.forEach(function (callback) {
                        value = callback(value, options);
                    });

                });
            }

            return value;
        }

        // module
        return {
            Hooks: Hooks,
            loaded: false,
            _doingAjax: false,
            _cuurentAjax: false,
            radioCheckboxInitialized: false,

            init: function () {

                if (this.loaded) {

                    return;
                }

                var self = this;

                this.handle_notices();

                this.error_copy();

                this.admin_notice_fix();

                this.vc_modifications();

                this.customizePage();

                this.setup_show_on();


                switch (better_framework_loc.type) {

                    // Setup Widgets
                    case 'widgets':

                        // Setup fields after ajax request on widgets page
                        this.setup_widget_fields();
                        this.sort_widgets();
                        break;

                    // Setup Panel
                    case 'panel':

                        this.panel_save_action();
                        this.panel_reset_action();
                        this.panel_sticky_header();
                        this.panel_import_export();
                        this.change_panel_data_notice();
                        break;

                    // Setup Meta Boxes
                    case 'metabox':

                        this.change_metabox_data_notice();

                        break;

                    // Setup Taxonomy Meta Boxes
                    case 'taxonomy':

                        this.taxonomy_page_reload_status();

                        this.change_metabox_data_notice();

                        break;

                    // Setup User Meta Boxes
                    case 'users':

                        break;

                    // Setup Menus
                    case 'menus':

                        this.menus_collect_fields_before_save();

                        setTimeout(function () {
                            self.init_mega_menus();
                        });

                        break;

                }

                Better_Framework.panel.deprecated.init();
                Better_Framework.panel.init();

                $(document).trigger('bf-loaded');

                this.loaded = true;
            },

            /**
             *
             * @param {function} successCallback Callback function for success ajax response
             * @param {Object} args settings object
             * @private
             * @return {jqXHR}
             */
            _ajax: function (successCallback, data, args, failCallback) {
                var self = this;
                self._doingAjax = true;

                var ajaxParams = $.extend(
                    {
                        action: 'bf_ajax',
                        nonce: better_framework_loc.nonce,
                        panelID: $('#bf-panel-id').val()
                    },
                    data
                );

                return $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: ajaxParams,
                })
                    .done(function (response) {

                        if (response.result && response.result.is_error) {
                            response.result.error_message += "\n\n";
                            response.result.error_message += JSON.stringify(ajaxParams);
                            console.error(response.result.error_message, response.result.error_code);
                            var modal = self._show_error(response.result.error_message, response.result.error_code),
                                $info = modal.$modal.find('.bs-pages-error-section textarea');


                            $info.height($info[0].scrollHeight);
                            modal.make_vertical_center();

                            response.status = 'error';
                        }


                        self._doingAjax = false;
                        successCallback.apply(this, arguments);
                    }).fail(function (e, code, msg) {

                        self._show_error(msg, code);

                        if (failCallback)
                            failCallback.call(this, arguments);
                    });
            },

            _show_error: function (error_message, error_code) {
                var self = this,
                    loc = jQuery.extend({}, better_framework_loc.on_error);

                if (error_message && error_code) {
                    loc.body = loc.display_error
                        .replace('%ERROR_CODE%', error_code)
                        .replace('%ERROR_MSG%', error_message);
                }

                return $.bs_modal({
                    content: loc,

                    buttons: {
                        close_modal: {
                            label: loc.button_ok,
                            type: 'primary',
                            action: 'close'
                        },
                    }
                });
            },
            /**
             * Panel
             ******************************************/
            _get_metabox_data: function () {
                return $('.bf-metabox-container').bf_serialize();
            },
            change_metabox_data_notice: function () {
                var self = this,
                    default_values = this._get_metabox_data();

                $('.bf-metabox-container').on('change bf-changed', ':input', function () {
                    var changed = default_values !== self._get_metabox_data();
                    if (changed) {
                        bf_ignore_reload_notice = false;
                        $(window).on('beforeunload.bs-admin', function (e) {
                            if (!bf_ignore_reload_notice)
                                return true;
                        });
                    } else {
                        self.turn_refresh_notice_off();
                    }
                });

                $("#post,#edittag").on('submit', function () {
                    self.turn_refresh_notice_off();
                });
            },
            /**
             *
             * @private
             */
            _get_panel_data: function () {
                var _serialized = $('#bf-content').bf_serialize();

                return _serialized;
            },
            turn_refresh_notice_off: function () {
                $(window).off('beforeunload.bs-admin');
            },
            change_panel_data_notice: function () {
                var self = this,
                    default_values = this._get_panel_data();

                $('#bf-content').on('change bf-changed', ':input', function () {
                    var changed = default_values !== self._get_panel_data();

                    $("#bf-panel .bf-options-change-notice")
                        [changed ? 'addClass' : 'removeClass']('bf-option-changed');

                    if (changed) {
                        bf_ignore_reload_notice = false;
                        $(window).on('beforeunload.bs-admin', function (e) {
                            if (!bf_ignore_reload_notice)
                                return true;
                        });
                    } else {
                        self.turn_refresh_notice_off();
                    }
                });
            },

            taxonomy_page_reload_status: function () {
                $(document).ajaxSuccess(function (e, xhr, settings) {
                    var data = $.unserialize(settings.data);
                    if (data.action === 'add-tag') {
                        bf_ignore_reload_notice = true;
                    }
                });
            },
            _init_editor: function (context) {

                $('.bf-editor-wrapper', context).each(function () {
                    var $wrapper = $(this),
                        $editor = $wrapper.find('.bf-editor'),
                        $textarea = $wrapper.find('.bf-editor-field'),
                        have_ace = typeof ace === "object";

                    if (have_ace) {

                        $textarea.hide();

                        $editor.css('min-height', '100px');
                        var
                            lang = $editor.data('lang'),
                            max_lines = $editor.data('max-lines'),
                            min_lines = $editor.data('min-lines'),
                            theme = $editor.data('theme'),
                            editor = ace.edit($editor[0]),
                            session = editor.getSession();

                        editor.setOptions({
                            maxLines: max_lines,
                            minLines: min_lines,
                            mode: "ace/mode/" + lang
                        });

                        if (theme)
                            editor.setTheme("ace/theme/" + theme);

                        session.setUseWorker(false);

                        editor.getSession().setValue($textarea.val());

                        session.on('change', function (e, EditSession) {
                            $textarea
                                .val(editor.getSession().getValue())
                                .trigger('bf-changed');

                            $textarea[0].dispatchEvent(new Event('change', {bubbles: true}));
                        });

                    } else {
                        $editor.remove();
                        $textarea.show();
                    }
                });
            },
            handle_notices: function () {
                var wrapper = '.bf-notice-wrapper';
                $(wrapper).on('click', '.bf-notice-dismiss', function () {
                    var $this = $(this),
                        $wrapper = $this.closest(wrapper),
                        data = $this.data();

                    $wrapper.slideUp(300);

                    setTimeout(function () {
                        $wrapper.remove();
                    }, 300);

                    if (data) {
                        $.ajax({
                            url: ajaxurl,
                            type: 'post',
                            data: $.extend({action: 'bf-notice-dismiss'}, data)
                        });
                    }
                });
            },

            _getVar: function (varName) {

                if (!varName) {
                    return;
                }

                if (varName.indexOf('.') == -1) {

                    return window[varName];
                }

                var _v = varName.split('.');
                var current = window[_v[0]];
                _v = _v.splice(1);

                var len = _v.length - 1;

                for (var i = 0; i <= len; i++) {

                    if (typeof current[_v[i]] !== 'object' && i !== len) {
                        return;
                    }

                    current = current[_v[i]];
                }

                return current;
            },

            // Panel save ajax action
            panel_save_action: function () {

                var self = this;
                $(document).on('click', '.bf-save-button', function (e) {

                    e.preventDefault();

                    var $this = $(this);

                    if ($this.data('confirm') != '' && !confirm($this.data('confirm')))
                        return false;

                    Better_Framework.panel_loader('loading');

                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: better_framework_loc.bf_ajax_url,
                        data: {
                            action: 'bf_ajax',
                            reqID: 'save_admin_panel_options',
                            type: better_framework_loc.type,
                            panelID: $('#bf-panel-id').val(),
                            nonce: better_framework_loc.nonce,
                            lang: better_framework_loc.lang,
                            data: self._get_panel_data()
                        },
                        success: function (data, textStatus, XMLHttpRequest) {

                            var event = $this.data('event');

                            if (event) {
                                $(document).trigger(event, [data, $this]);
                            }

                            if (data.status == 'succeed') {

                                $("#bf-panel .bf-options-change-notice")
                                    .removeClass('bf-option-changed')
                                    .slideUp();
                                self.turn_refresh_notice_off();

                                if (typeof data.msg != 'undefined') {
                                    Better_Framework.panel_loader('succeed', data.msg);
                                } else {
                                    Better_Framework.panel_loader('succeed');
                                }
                            } else {
                                if (typeof data.msg != 'undefined') {
                                    Better_Framework.panel_loader('error', data.msg);
                                } else {
                                    Better_Framework.panel_loader('error');
                                }
                                Better_Framework.panel_loader('error', data.msg);
                            }

                            if (typeof data.refresh != 'undefined' && data.refresh) {

                                if (data.status == 'succeed') {
                                    self.reload_location(1000);
                                } else {
                                    self.reload_location(1500);
                                }

                            }
                        },
                        error: function (MLHttpRequest, textStatus, errorThrown) {
                            Better_Framework.panel_loader('error');
                        }
                    });

                });
            },


            // Panel Options Import & Export
            panel_import_export: function () {

                var self = this;
                // Export Button
                $(document).on('click', '#bf-download-export-btn', function () {

                    var _go = $(this).attr('href');

                    var _file_name = $(this).data('file_name');
                    var _panel_id = $(this).data('panel_id');

                    $().redirect(_go, {
                        'bf-export': 1,
                        'nonce': better_framework_loc.nonce,
                        'file_name': _file_name,
                        'panel_id': _panel_id,
                        lang: better_framework_loc.lang
                    });

                    return false;

                });

                // Import
                var bf_import_submit;
                $('.bf-import-file-input').fileupload({
                    limitMultiFileUploads: 1,
                    url: better_framework_loc.bf_ajax_url,
                    autoUpload: false,
                    replaceFileInput: false,
                    formData: {
                        nonce: better_framework_loc.nonce,
                        action: 'bf_ajax',
                        type: better_framework_loc.type,
                        reqID: 'import',
                        'panel-id': $('.bf-import-file-input').data('panel_id'),
                        lang: better_framework_loc.lang
                    },
                    add: function (e, data) {
                        bf_import_submit = function () {
                            return data.submit();
                        };
                    },
                    start: function (e) {
                        Better_Framework.panel_loader('loading');
                    },
                    done: function (e, data) {

                        var result = JSON.parse(data.result);

                        if (result.status == 'succeed') {
                            if (typeof result.msg != 'undefined') {
                                Better_Framework.panel_loader('succeed', result.msg);
                            } else {
                                Better_Framework.panel_loader('succeed');
                            }
                        } else {
                            if (typeof result.msg != 'undefined') {
                                Better_Framework.panel_loader('error', result.msg);
                            } else {
                                Better_Framework.panel_loader('error');
                            }
                        }

                        if (typeof result.refresh != 'undefined' && result.refresh) {

                            if (data.status == 'succeed') {
                                self.reload_location(1000);
                            } else {
                                self.reload_location(1500);
                            }

                        }

                    },
                    error: function (MLHttpRequest, textStatus, errorThrown) {
                        Better_Framework.panel_loader('error');
                    },
                    progressall: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                    },
                    drop: function (e, data) {
                        return false;
                    }
                });

                $('.bf-import-upload-btn').click(function () {

                    if (typeof bf_import_submit != "undefined") {

                        if (confirm(better_framework_loc.translation.import_panel.prompt) == true) {
                            bf_import_submit();
                        }

                    }

                    return false;
                });

            },

            /**
             * Refresh page without unload notice
             *
             * @param delay
             */
            reload_location: function (delay) {
                this.turn_refresh_notice_off();
                if (delay) {
                    setTimeout(function () {
                        location.reload();
                    }, delay);
                } else {
                    location.reload();
                }
            },
            // Panel Ajax Reset Action
            panel_reset_action: function () {

                var _this = this;
                $(document).on('click', '.bf-reset-button', function () {

                    $.bs_modal({
                        button_position: 'left',
                        content: {
                            header: better_framework_loc.translation.reset_panel.header,
                            title: better_framework_loc.translation.reset_panel.title,
                            body: better_framework_loc.translation.reset_panel.body
                        },
                        styles: {
                            container: 'overflow:visible;max-width: 460px;'
                        },
                        buttons: {
                            custom_event: {
                                label: better_framework_loc.translation.reset_panel.button_yes,
                                type: 'primary',
                                clicked: function () {
                                    var self = this;
                                    self.change_skin({
                                        skin: 'loading',
                                        animations: {
                                            body: 'bs-animate bs-fadeInLeft'
                                        },
                                        content: {
                                            loading_heading: better_framework_loc.translation.reset_panel.resetting
                                        }
                                    });

                                    $.ajax({
                                        type: 'POST',
                                        dataType: 'json',
                                        url: better_framework_loc.bf_ajax_url,
                                        data: {
                                            action: 'bf_ajax',
                                            reqID: 'reset_options_panel',
                                            panelID: $('#bf-panel-id').val(),
                                            lang: better_framework_loc.lang,
                                            type: 'panel',
                                            nonce: better_framework_loc.nonce,
                                            to_reset: $('.bf-reset-options-frame-tabs').bf_serialize()
                                        },
                                        success: function (data, textStatus, XMLHttpRequest) {

                                            if (data.status == 'succeed') {
                                                self.change_skin({
                                                    skin: 'success',
                                                    animations: {
                                                        body: 'bs-animate bs-fadeInLeft'
                                                    },
                                                    content: {
                                                        success_heading: data.msg
                                                    },
                                                    timer: {
                                                        delay: 2000,
                                                        callback: function () {
                                                            this.close_modal();
                                                        }
                                                    }
                                                });
                                                _this.reload_location(1000);
                                            } else {
                                                if (typeof data.msg != 'undefined') {
                                                    Better_Framework.panel_loader('error', data.msg);
                                                } else {
                                                    Better_Framework.panel_loader('error');
                                                }
                                            }

                                        },
                                        error: function (MLHttpRequest, textStatus, errorThrown) {
                                            alert('An error occurred!');
                                        }
                                    });
                                }
                            },
                            close_modal: {
                                btn_classes: 'button button-danger bs-modal-button-aside',
                                type: 'secondary',
                                action: 'close',
                                label: better_framework_loc.translation.reset_panel.button_no,
                                focus: true
                            }
                        }
                    });
                });

            },


            // Panel loader
            // status: loading, succeed, error
            panel_loader: function (status, message) {

                var $bf_loading = $('.bf-loading');

                if ($bf_loading.length === 0) {

                    $(document.body)
                        .append('<div class="bf-loading">\n    <div class="loader">\n        <div class="loader-icon in-loading-icon "><i class="dashicons dashicons-update"></i></div>\n        <div class="loader-icon loaded-icon"><i class="dashicons dashicons-yes"></i></div>\n        <div class="loader-icon not-loaded-icon"><i class="dashicons dashicons-no-alt"></i></div>\n        <div class="message">An Error Occurred!</div>\n    </div>\n</div>')
                        .append('<style>\n    .bf-loading {\n        position: fixed;\n        top: 0;\n        left: 0;\n        width: 100%;\n        height: 100%;\n        background-color: #636363;\n        background-color: rgba(0, 0, 0, 0.41);\n        display: none;\n        z-index: 99999;\n    }\n\n    .bf-loading .loader {\n        width: 300px;\n        height: 180px;\n        position: absolute;\n        top: 50%;\n        left: 50%;\n        margin-top: -90px;\n        margin-left: -150px;\n        text-align: center;\n    }\n\n    .bf-loading.not-loaded,\n    .bf-loading.loaded,\n    .bf-loading.in-loading {\n        display: block;\n    }\n\n    .bf-loading.in-loading .loader {\n        color: white;\n    }\n\n    .bf-loading.loaded .loader {\n        color: #27c55a;\n    }\n\n    .bf-loading.not-loaded .loader {\n        color: #ff0000;\n    }\n\n    .bf-loading .loader .loader-icon {\n        font-size: 30px;\n        -webkit-transition: all 0.2s ease;\n        -moz-transition: all 0.2s ease;\n        -ms-transition: all 0.2s ease;\n        -o-transition: all 0.2s ease;\n        transition: all .2s ease;\n        opacity: 0;\n        border-radius: 10px;\n        background-color: #333;\n        background-color: rgba(51, 51, 51, 0.86);\n        width: 60px;\n        height: 60px;\n        line-height: 60px;\n        margin-top: 20px;\n        display: none;\n        position: absolute;\n        left: 50%;\n        margin-left: -30px;\n    }\n\n    .bf-loading .loader .loader-icon .dashicons,\n    .bf-loading .loader .loader-icon .dashicons-before:before {\n        font-size: 55px;\n        line-height: 60px;\n        width: 60px;\n        height: 60px;\n        text-align: center;\n    }\n\n    .bf-loading.in-loading .loader .loader-icon.in-loading-icon,\n    .bf-loading.in-loading.loader .loader-icon.in-loading-icon {\n        opacity: 1;\n        display: inline-block;\n    }\n\n    .bf-loading.in-loading .loader .loader-icon.in-loading-icon .dashicons,\n    .bf-loading.in-loading .loader .loader-icon.in-loading-icon .dashicons-before:before {\n        -webkit-animation: spin 1.15s linear infinite;\n        -moz-animation: spin 1.15s linear infinite;\n        animation: spin 1.15s linear infinite;\n        font-size: 30px;\n    }\n\n    .bf-loading.loaded .loader .loader-icon.loaded-icon {\n        opacity: 1;\n        display: inline-block;\n        font-size: 50px;\n    }\n\n    .bf-loading.loaded .loader .loader-icon.loaded .dashicons,\n    .bf-loading.loaded .loader .loader-icon.loaded .dashicons-before:before {\n        width: 57px;\n    }\n\n    .bf-loading.not-loaded .loader .loader-icon.not-loaded-icon {\n        opacity: 1;\n        display: inline-block;\n    }\n\n    .bf-loading.not-loaded .loader .loader-icon.not-loaded-icon .dashicons,\n    .bf-loading.not-loaded .loader .loader-icon.not-loaded-icon .dashicons-before:before {\n        font-size: 50px;\n        line-height: 62px;\n    }\n\n    .bf-loading .loader .message {\n        display: none;\n        color: #ff0000;\n        font-size: 12px;\n        line-height: 24px;\n        min-width: 100px;\n        max-width: 300px;\n        left: auto;\n        right: auto;\n        text-align: center;\n        background-color: #333;\n        background-color: rgba(51, 51, 51, 0.86);\n        border-radius: 5px;\n        padding: 4px 20px;\n        margin-top: 90px;\n    }\n\n    .bf-loading.with-message .loader .message {\n        display: inline-block;\n    }\n\n    .bf-loading.loaded .loader .message {\n        color: #27c55a;\n    }\n\n    .bf-loading.in-loading .loader .message {\n        color: #fff;\n    }\n\n    @-moz-keyframes spin {\n        100% {\n            -moz-transform: rotate(360deg);\n        }\n    }\n\n    @-webkit-keyframes spin {\n        100% {\n            -webkit-transform: rotate(360deg);\n        }\n    }\n\n    @keyframes spin {\n        100% {\n            -webkit-transform: rotate(360deg);\n            transform: rotate(360deg);\n        }\n    }\n</style>');

                    $bf_loading = $('.bf-loading');
                }

                message = typeof message !== 'undefined' ? message : '';

                if (status == 'loading') {

                    $bf_loading.removeClass().addClass('bf-loading in-loading');

                    if (message != '') {
                        $bf_loading.find('.message').html(message);
                        $bf_loading.addClass('with-message');
                    }

            }
            else if(status == 'error'){

                    $bf_loading.removeClass().addClass('bf-loading not-loaded');

                    if (message != '') {
                        $bf_loading.find('.message').html(message);
                        $bf_loading.addClass('with-message');
                    }

                    setTimeout(function () {
                        $bf_loading.removeClass('not-loaded');
                        $bf_loading.find('.message').html('');
                        $bf_loading.removeClass('with-message');
                    }, 1500);

                } else if (status == 'succeed') {

                    $bf_loading.removeClass().addClass('bf-loading loaded');

                    if (message != '') {
                        $bf_loading.find('.message').html(message);
                        $bf_loading.addClass('with-message');
                    }

                    setTimeout(function () {
                        $bf_loading.removeClass('loaded');
                        $bf_loading.find('.message').html('');
                        $bf_loading.removeClass('with-message');
                    }, 1000);

                } else if (status == 'hide') {

                    setTimeout(function () {
                        $bf_loading.removeClass(' in-loading');
                        $bf_loading.find('.message').html('');
                        $bf_loading.removeClass('with-message');
                    }, 500);
                }

            },


            // Setup sticky header
            panel_sticky_header: function () {

                var $main_menu = $('#bf-panel .bf-page-header, .panel-wrapper .bf-page-header'),
                    $wrapper = $main_menu.closest('.bf-panel');

                var main_menu_offset_top = 100;

                $wrapper.css('--bf-panel-sticky-offset', main_menu_offset_top + 'px');

                var sticky_func = function () {

                    if (($(window).scrollTop() - 32) > main_menu_offset_top) {
                        $main_menu.addClass('sticky');
                        $wrapper.addClass('sticky');
                    }
                    else{
                        $main_menu.removeClass('sticky');
                        $wrapper.removeClass('sticky');
                    }
                };

                sticky_func();

                $(window).scroll(function () {
                    sticky_func();
                });
            },


            process_filter_field: function (field_id, field_value) {

                $('.bf-section-container[data-filter-field=\'' + field_id + '\']').each(function () {

                    if ($(this).data('filter-field-value') == field_value) {
                        $(this).fadeIn('200');
                    } else {
                        $(this).css({'display': 'none'});
                    }

                });

            },

            attachVcEditShortcodeEvent: function (callback) {

                if (typeof vc !== "object" || !vc.events) {

                    return false;
                }
                var attach = function () {
                    vc.edit_element_block_view
                    &&
                    vc.edit_element_block_view.on("afterRender", function () {
                        callback(this);
                    })
                }

                if (vc.edit_element_block_view) {

                    attach();

                } else {

                    vc.events.on("app.render", attach)
                }

                return true;
            },

            _length: function (object) {
                if (!object) {
                    return 0;
                }

                if (Object.keys) {
                    return Object.keys(object).length;
                }

                var count = 0, i;

                for (i in object) {
                    if (object.hasOwnProperty(i)) {
                        count++;
                    }
                }

                return count;
            },

            setup_show_on: function () {

                var showOnInstance = function (wrapperEl, options) {

                    if (typeof BetterStudio !== "object" || !BetterStudio.Libs || !BetterStudio.Libs.showOn) {

                        return;
                    }

                    options.onRefresh = function (element, matched, options) {

                        if (!element || !element.children || !element.parentElement) {

                            return false;
                        }

                        var isProFeature = element.parentElement.classList.contains('bs-pro-feature-control');

                        if (!isProFeature) {

                            return false;
                        }

                        // var isRepeaterItem = element.children[0] && element.children[0].getAttribute('class').match(/bf-repeater/);
                        var show_on_type = options.show_on_type && options.show_on_type[options.matchedRuleIndex] || '';

                        var proFeatureContainer = show_on_type === "disable" ? element.parentElement : element.closest('.bs-pro-feature');

                        if (matched) {

                            if (show_on_type === "disable") {

                                proFeatureContainer.style.removeProperty('opacity');

                            } else {

                                proFeatureContainer.style.removeProperty('display');
                            }

                        } else {

                            setTimeout(function () {

                                if (show_on_type === "disable") {

                                    proFeatureContainer.style.opacity = 1;

                                } else {

                                    proFeatureContainer.style.display = 'none'
                                }

                            }, options.animationDuration);
                        }
                    };

                    return new BetterStudio.Libs.showOn(wrapperEl, options);
                };

                var instances = {};

                var showOnModule = {

                    init: function (name) {

                        var submodule = this.submodule(name);

                        if (!submodule) {

                            return false;
                        }

                        this.initContainers(
                            submodule.containers()
                        );

                        submodule.setup && submodule.setup();

                        Hooks.add_action("panel/tabs/loaded-deferred", this.onTabLoaded.bind(this));
                        Hooks.add_action("panel/tabs/switching", this.onTabSwitching.bind(this));

                        return true;
                    },

                    initContainers: function (containers) {

                        if (!containers) {

                            return;
                        }

                        for (var id in containers) {

                            instances[id] = showOnInstance(containers[id].element, containers[id].options);

                            instances[id] && this.attachEvents(instances[id]);
                        }
                    },

                    attachEvents: function (showOn) {

                        this.repeaterSupport(showOn);
                    },

                    repeaterSupport: function (showOn) {

                        $(showOn.wrapper()).on("repeater_item_added", ".bf-section", function (e, $repeater) {

                            showOn.setup($repeater.find(".bf-repeater-item:last")[0]);
                        });
                    },

                    onTabLoaded: function (container) {

                        var submodule = this.submodule(),
                            showOn = submodule.find(container);

                        showOn && showOn.setup(container);
                    },

                    onTabSwitching: function (container) {

                        var submodule = this.submodule(),
                            showOn = submodule.find(container);

                        showOn && showOn.setup(container);
                    },

                    submodule: function (name) {

                        if (!name) {
                            switch (better_framework_loc.type) {

                                case "taxonomy":
                                case "metabox":
                                case "users":
                                case "elementor":

                                    name = "general";

                                    break;

                                case "menus":

                                    name = "menu";

                                    break;

                                case "widgets":

                                    name = "widget";

                                    break;

                                default:
                                    name = better_framework_loc.type;

                            }
                        }

                        return name && this[name] || false;
                    },

                    general: {

                        wrapperSelector: '.bf-metabox-wrap',
                        overrideOptions: {
                            groupSelector: '.bf-group-inner,.group,.bf-metabox-wrap',
                        },

                        setup: function () {

                            showOnModule.gutenberg.setup();
                            showOnModule.elementor.setup();
                            showOnModule.vc.setup();
                            showOnModule.mce.setup();

                        },
                        find: function (element) {

                            if (!element) {
                                return;
                            }

                            var wrapper = element.closest(this.wrapperSelector);

                            if (!wrapper) {

                                return;
                            }

                            var id = wrapper.dataset.metaboxId || wrapper.id || wrapper.dataset.id;

                            return instances[id];
                        },

                        containers: function () {

                            var containers = {};
                            var self = this;

                            document.querySelectorAll(this.wrapperSelector)
                                .forEach(function (metabox) {

                                    var id = metabox.dataset.metaboxId || metabox.id || metabox.dataset.id;

                                    containers[id] = {
                                        element: metabox,
                                        options: self.overrideOptions
                                    };
                                });

                            return containers;
                        },
                    },

                    panel: {

                        find: function () {

                            return instances.panel;
                        },

                        containers: function () {

                            var panelElement = document.getElementById('bf-panel');

                            if (panelElement) {

                                return {
                                    panel: {
                                        element: panelElement,
                                        options: {}
                                    }
                                }
                            }
                        }
                    },

                    menu: {

                        menuItemSelector: ".menu-item-settings",
                        wrapperSelector: '.fields-group',
                        overrideOptions: {
                            showOnLocation: {
                                selector: ".bf-menu-custom-field",
                                optionsDataset: "paramSettings",
                                nameDataset: "paramName",
                            },
                            cacheValues: false,
                            dynamicInputWrapper: ".bf-group-inner"
                        },

                        setup: function () {

                            $(document).on('menu-item-added', this.onMenuItemAdded.bind(this));

                            if (better_framework_loc.use_widgets_block) {

                                showOnModule.gutenberg.setup();
                            }
                        },

                        onMenuItemAdded: function (e, $wrapper) {

                            var containers = {};

                            this.showOnContainers($wrapper[0], containers);

                            showOnModule.initContainers(
                                containers
                            );
                        },

                        find: function (element) {

                            if (!element) {
                                return;
                            }

                            var wrapper = element.closest(this.wrapperSelector);

                            if (!wrapper) {

                                return;
                            }
                            var menuItem = wrapper.closest(this.menuItemSelector);

                            if (!menuItem) {

                                return;
                            }

                            var menuId = menuItem.querySelector(".menu-item-data-db-id").value;
                            var groupID = wrapper.dataset.paramName || wrapper.id;

                            return instances[menuId + "-" + groupID];
                        },

                        containers: function () {

                            var self = this;
                            var containers = {};

                            document.querySelectorAll(this.menuItemSelector)
                                .forEach(function (menuItem) {
                                    self.showOnContainers(menuItem, containers)
                                });

                            return containers;
                        },

                        showOnContainers: function (menuItemEl, containers) {

                            var menuId = menuItemEl.id.match(/\-(\d+)$/)[1];
                            var self = this;

                            menuItemEl.querySelectorAll(this.wrapperSelector)
                                .forEach(function (group) {

                                    var groupID = group.dataset.paramName || group.id;

                                    containers[menuId + "-" + groupID] = {
                                        element: group,
                                        options: self.overrideOptions
                                    };
                                });
                        }
                    },

                    widget: {

                        widgetSelector: "#widgets-right .widget",
                        wrapperSelector: '.fields-group',
                        overrideOptions: {
                            groupSelector: '.bf-group-inner,.group,.widget',
                            showOnLocation: {
                                selector: ".bf-widgets",
                                optionsDataset: "paramSettings",
                                nameDataset: "paramName",
                            },
                            cacheValues: false,
                            dynamicInputWrapper: ".bf-group-inner"
                        },

                        setup: function () {

                            $(document).on('widget-added widget-updated', this.onWidgetChanged.bind(this));

                            if (better_framework_loc.use_widgets_block)
                                showOnModule.init('general');
                        },

                        onWidgetChanged: function (e, $widget) {

                            var containers = {};

                            containers[$widget[0].id] = {
                                element: $widget[0],
                                options: this.overrideOptions
                            };

                            showOnModule.initContainers(
                                containers
                            );
                        },

                        find: function (element) {

                            if (!element) {
                                return;
                            }

                            var wrapper = element.closest(this.wrapperSelector);

                            if (!wrapper) {

                                return;
                            }
                            var widgetEl = wrapper.closest(this.widgetSelector);

                            if (!widgetEl) {

                                return;
                            }

                            var groupID = wrapper.dataset.paramName || wrapper.id;

                            return instances[widgetEl.id + "-" + groupID];
                        },

                        containers: function () {

                            var self = this;
                            var containers = {};

                            document.querySelectorAll(this.widgetSelector)
                                .forEach(function (widget) {

                                    containers[widget.id] = {
                                        element: widget,
                                        options: self.overrideOptions
                                    };
                                });

                            return containers;
                        }
                    },

                    vc: {
                        setup: function () {
                            Better_Framework.attachVcEditShortcodeEvent(this.onRenderedEdit)
                        },
                        onRenderedEdit: function (view) {


                            var instance = showOnInstance(view.$el[0], {
                                groupSelector: ".vc_edit-form-tab",
                                showOnLocation: {
                                    selector: ".vc_column",
                                    optionsDataset: "param_settings",
                                    nameDataset: "vcShortcodeParamName",
                                },
                            });

                            instance && showOnModule.repeaterSupport(instance);
                        }
                    },
                    mce: {
                        setup: function () {

                            $(document).on("mce-view-fields-loaded", this.onMceFieldsLoaded.bind(this));
                        },

                        onMceFieldsLoaded: function (e, modal) {

                            var instance = showOnInstance(modal.$modal[0]);

                            instance && showOnModule.repeaterSupport(instance);
                        }
                    },

                    gutenberg: {
                        setup: function () {

                            $(document).on('bf-component-did-mount', Better_Framework.debounce(this.onPanelClicked.bind(this)));
                        },

                        onPanelClicked: function (event) {

                            var wrapperClass = '.components-panel__body,.bf-edit-gutenberg-block';

                            var panel = event.detail.wrapper.closest(wrapperClass);
                            if (panel) {
                                var instance = showOnInstance(panel, {
                                    groupSelector: wrapperClass,
                                });
                                instance && showOnModule.repeaterSupport(instance);
                            }
                        }
                    },

                    elementor: {
                        setup: function () {

                            if (typeof elementor === "object" && elementor.on) {

                                elementor.on('panel:init', this.attachEvents.bind(this));
                            }
                        },

                        attachEvents: function () {
                            var self = this;

                            elementor.hooks.addAction('panel/open_editor/widget', this.onWidgetOpened.bind(this));
                            elementor.channels && elementor.channels.editor && elementor.channels.editor.bind('section:activated', this.onWidgetSectionOpened.bind(this));

                            $e && $e.commands && $e.commands.on('run:after', function (component, command, args) {

                                if ("document/repeater/insert" !== command) {

                                    return false;
                                }
                                var repeaterControl = self.context.querySelector('.elementor-control-' + args.name);

                                if (repeaterControl) {

                                    self.onRepeaterChange(repeaterControl);
                                }

                                return true;
                            })
                        },

                        onWidgetOpened: function ($scope, model) {

                            var self = this;

                            var widgetId = model.get('widgetType'),
                                element = $scope.$el[0],
                                showOn = model.get('bf-show-on');

                            if (!showOn) {

                                showOn = showOnInstance(element, {
                                    bindChangeEvent: false,
                                    groupSelector: ".elementor-controls-stack",
                                    dynamicInputWrapper: ".elementor-repeater-fields",
                                    showOnLocation: {
                                        selector: ".elementor-control",
                                        optionsDataset: false,
                                        nameDataset: "setting",
                                    },

                                    isRepeaterControl: function (inputElement, inputWrapper) {

                                        var element = inputWrapper || inputElement;
                                        var classNames = element && element.parentElement && element.parentElement.getAttribute('class');
                                        var result = Boolean(classNames && classNames.match(/elementor\-repeater/));

                                        return result;
                                    }
                                });

                                var rules = this.generateRules(widgetId);

                                showOn.rules(rules);
                                showOn.staticValues(model.get('settings').toJSON());
                                model.set('bf-show-on', showOn);
                            } else {

                                showOn.refresh(false);
                            }

                            self.context = element;

                            // handle repeater changes
                            element.querySelectorAll('.elementor-control-type-repeater').forEach(function (repeaterControl) {

                                var addElement = repeaterControl.querySelector('.elementor-repeater-add');
                                var duplicateElement = repeaterControl.querySelector('.elementor-repeater-tool-duplicate');

                                repeaterControl.addEventListener('change', function (event) {

                                    self.onSettingChanged(event.target.closest('.elementor-repeater-row-controls'), model, model.get('settings'))
                                });

                                addElement && addElement.addEventListener('click', function (event) {

                                    console.log("Added");
                                });
                                duplicateElement && duplicateElement.addEventListener('click', function (event) {

                                    console.log("duplicate");
                                });
                            });

                            model.get('settings').on('change', Better_Framework.debounce(function (settings) {

                                self.onSettingChanged(element, model, settings)
                            }));
                        },
                        onWidgetSectionOpened: function (section_id, view) {

                            if (!section_id) {

                                return false;
                            }

                            var showOn = view.model.get('bf-show-on');
                            showOn && showOn.refresh(false);

                            return true;
                        },
                        onRepeaterChange: function (repeaterControl) {

                            var lastItem = repeaterControl.querySelector('.elementor-repeater-fields:last-child');

                            if (!lastItem) {

                                return false;
                            }

                            var anElement = lastItem.querySelector('input,select,textarea');

                            // refresh show-on
                            anElement && anElement.dispatchEvent(new Event('change', {bubbles: true}));
                        },

                        onSettingChanged: function (element, model, settings) {

                            var showOn = model.get('bf-show-on');

                            showOn.staticValues(settings.toJSON());
                            showOn.shortInit(element, true);
                        },

                        generateRules: function (widget_id) {

                            var rules = {}

                            function generator(controls) {

                                if (!controls) {

                                    return false;
                                }

                                for (var controlId in controls) {

                                    var control = controls[controlId] || {};

                                    if (control.show_on && Array.isArray(control.show_on)) {

                                        rules[controlId] = control.show_on;
                                    }

                                    if (control.type === "repeater") {

                                        generator(control.fields || {});
                                    }
                                }

                                return true;
                            };


                            generator(
                                ElementorConfig.widgets[widget_id] && ElementorConfig.widgets[widget_id].controls
                            );

                            return rules;
                        }
                    }
                };

                showOnModule.init();
            },


            /**
             * Widgets
             ******************************************/

            // Setup widgets fields after ajax submit
            setup_widget_fields: function () {

                /**
                 * Keep Widget Group State After Widget Settings Saved
                 */
                function saveGroupStatus($context) {
                    $(".widget-content", $context).on('click', '.fields-group-title-container', function () {
                        var $this = $(this),
                            $_group = $this.closest('.fields-group'),
                            groupID = $_group.attr('id').match('^fields\-group\-(.+)$')[1],
                            isOpen = !$_group.hasClass('open');

                        var $form = $this.closest('form'),
                            inoutVal = isOpen ? 'open' : 'close',
                            $input = $('input[name="_group_status[' + groupID + ']"]', $form);

                        if ($input.length) {
                            $input.val(inoutVal);
                        } else {
                            $('<input />', {
                                type: 'hidden',
                                name: '_group_status[' + groupID + ']',
                            }).val(inoutVal).appendTo($form);
                        }
                    });
                }

                jQuery(document).ajaxSuccess(function (e, xhr, settings) {

                    var _data = $.unserialize(settings.data);

                    if (_data.action == "save-widget") {
                        var wID = _data['widget-id'],
                            $widget = $("input.widget-id[value='" + wID + "']").closest('.widget');

                        saveGroupStatus($widget);
                    }
                });

                var $document = $(document);

                $document.on('widget-updated widget-added', function (e, $widget) {

                });

                // Clone Repeater Item by click
                // TODO refactor this
                $(document).on('click', '.bf-widget-clone-repeater-item', function (e) {


                    e.preventDefault();


                    var //name_format = undefined === $(this).data( 'name-format' ) ? '$1[$2][$3][$4][$5]' : $(this).data( 'name-format'),
                        _html = $(this).siblings('script').html();

                    if (!_html) {
                        return;
                    }
                    var $inside = $(this).closest('.widget-inside'),
                        widgetBaseName = '';

                    if ($inside.length) {

                        var widgetIdBase = $inside.find('input.id_base').val(),
                            widgetNumber = $inside.find('input.multi_number').val();

                        widgetBaseName = "widget-" + widgetIdBase + "[" + widgetNumber + "]";

                    } else {


                        var $wrapper = $(this).closest('.bf-controls'),
                            inputName = $(":input:first", $wrapper).attr('name');

                        if (inputName) {

                            var match = inputName.match(/(.*?)\[\d+\]\[.*?\]$/);

                            if (match && match[1]) {
                                widgetBaseName = match[1];
                            }
                        }
                    }

                    if (!widgetBaseName) {
                        return;
                    }

                    var _new = $(this).siblings('.bf-repeater-items-container').find('>*').size();
                    var new_num = _new + 1;

                    $(this).siblings('.bf-repeater-items-container').append(
                        _html.replace(/\|_to_clone_(.*?)-num-(.*?)\|/g, widgetBaseName + '[$1][' + new_num + '][$2]')
                    );
                    // bf_color_picker_plugin( $(this).siblings('.bf-repeater-items-container').find('.bf-color-picker') );
                    // bf_date_picker_plugin( $(this).siblings('.bf-repeater-items-container').find('.bf-date-picker-input') );
                    // bf_image_upload_plugin( $(this).siblings('.bf-repeater-items-container').find('.bf-image-upload-choose-file') );
                });

                saveGroupStatus();
            },
            sort_widgets: function () {

                return;
                var $widgets = $("#widget-list"),
                    widgets_count = $widgets.find('.widget').length - 1;

                $widgets.find(".bf-widget-position").sort(function (a, b) {
                    if (parseInt(a.value) <= parseInt(b.value)) {
                        return -1;
                    }
                    return 1;
                }).each(function (index) {
                    var $this = $(this),
                        $widget = $this.closest('.widget'),
                        position = Math.min($this.val(), widgets_count) + 1,
                        $target = $widgets.find('.widget:nth-child(' + position + ')');


                    $widget.insertAfter($target);
                });
            },

            /**
             * Menus
             ******************************************/

            // Advanced trick for sending all extra fields inside one field for enabling user to add huge bunch of menu items
            // and our to add menu fields without worry about variable limitation
            menus_collect_fields_before_save: function () {

                // Temp variable for collecting all fields to one place.
                var betterMenuItems = {};

                $('form#update-nav-menu').submit(function (e) {

                    // disable extra fields for preventing send them to server
                    $('*[name^="bf-m-i["]').attr("disabled", "disabled");

                    // Iterate all extra fields
                    $('*[name^="bf-m-i["]').each(function () {

                        var raw_name = $(this).attr('name'),
                            type = '',
                            post_id = '',
                            field_id = '';

                        if (raw_name.indexOf('[img]') > 0) {
                            post_id = raw_name.replace(/(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$4");
                            field_id = raw_name.replace(/(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$2");
                            type = 'img';
                    }
                    else if( raw_name.indexOf('[icon]') > 0 ){
                            post_id = raw_name.replace(/(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$4");
                            field_id = raw_name.replace(/(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$2");
                            type = 'icon';
                    }
                    else if( raw_name.indexOf('[type]') > 0 ){
                            post_id = raw_name.replace(/(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$4");
                            field_id = raw_name.replace(/(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$2");
                            type = 'type';
                    }
                    else if( raw_name.indexOf('[width]') > 0 ){
                            post_id = raw_name.replace(/(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$4");
                            field_id = raw_name.replace(/(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$2");
                            type = 'width';
                    }
                    else if( raw_name.indexOf('[height]') > 0 ){
                            post_id = raw_name.replace(/(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$4");
                            field_id = raw_name.replace(/(bf-m-i\[)(.*)(\]\[)([0-9]*)(\]\[)(.*)(\])/g, "$2");
                            type = 'height';
                    }
                    else{
                            post_id = raw_name.replace(/(bf-m-i\[)(.*)(\]\[)([0-9]*)(\])/g, "$4");
                            field_id = raw_name.replace(/(bf-m-i\[)(.*)(\]\[)([0-9]*)(\])/g, "$2");
                            type = 'normal';
                        }

                        if (typeof betterMenuItems[post_id] == "undefined") {
                            betterMenuItems[post_id] = {};
                        }

                        if (type == 'img' || type == 'type' || type == 'icon' || type == 'width' || type == 'height') {

                            if (typeof betterMenuItems[post_id][field_id] == "undefined") {
                                betterMenuItems[post_id][field_id] = {};
                            }
                            betterMenuItems[post_id][field_id][type] = $(this).val();

                        } else {
                            betterMenuItems[post_id][field_id] = $(this).val();
                        }

                    });

                    $(this).append('<input type="hidden" name="bf-m-i" value="' + encodeURI(JSON.stringify(betterMenuItems)) + '" />');
                });

            },

            once: function (callback) {

                var fired = false;

                return function () {

                    if (fired) {
                        return;
                    }

                    fired = true;
                    return callback.call(this, arguments);
                }
            },
            debounce: function (func, wait, immediate) {
                var timeout;
                return function () {
                    var context = this, args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(function () {
                        timeout = null;
                        if (!immediate) func.apply(context, args);
                    }, wait);
                    if (immediate && !timeout) func.apply(context, args);
                };
            },

            error_copy: function () {

                $(document).on('click', '.bs-pages-error-copy', function (e) {
                    e.preventDefault();
                    var $this = $(this),
                        $modal = $this.closest('.bs-modal');

                    $('.bs-pages-error-section textarea', $modal).focus().select();

                    if (document.execCommand('copy')) {
                        var orgLabel = $this.html();
                        $this.html($this.data('copied'));
                        $this.delay(750).queue(function (n) {
                            $this.html(orgLabel);
                            n();
                        });
                    }
                });
            },

            init_mega_menus: function () {

                function sanitizeDepthValue(value) {
                    if (typeof value === 'string') {
                        value = parseInt(value);
                    }
                    var t = typeof value;
                    if (t === 'number' || t === 'object') {
                        return value;
                    }
                }

                function getSelectPopupData($el) {
                    var data = $(".select-popup-data", $el).text();

                    if (data)
                        return JSON.parse(data);

                    return false;
                }
                function wpMenuItemTrigger($item) {
                    var currentDepth = parseInt($item.menuItemDepth());

                    $(".better-select-popup-mega-menu", $item).each(function () {
                        var $select = $(this),
                            currentValue = $(".select-value", $select).val(),
                            data = getSelectPopupData($select);


                        if (typeof data[currentValue] !== 'undefined' && typeof data[currentValue].depth !== 'undefined') {
                            var supportedDepth = sanitizeDepthValue(data[currentValue].depth);

                            if (supportedDepth === -1) { // any depth supported!
                                return;
                            }

                            var showNotice = false;
                            if (typeof supportedDepth === 'object') {
                                showNotice = !(supportedDepth[0] <= currentDepth &&
                                    supportedDepth[1] >= currentDepth);
                            } else {
                                showNotice = supportedDepth !== currentDepth;
                            }

                            $select.next('.mega-menu-depth-notice')[showNotice ? 'show' : 'hide']();

                        }
                    });
                }

                function wpMenuItemsTrigger($item) {
                    wpMenuItemTrigger($item); // sorted item

                    var children = $item.childMenuItems();

                    if (children && children.length) {
                        children.each(function () {
                            wpMenuItemTrigger($(this));
                        });
                    }
                }

                // Register an event for after item sorted
                wpNavMenu.menuList.on('sortstop', function (e, ui) {
                    setTimeout(function () { // low priority
                        wpMenuItemsTrigger(ui.item);
                    }, 100);
                });


                // Init WP Menu Items
                wpNavMenu.menuList.children('.menu-item-depth-0').each(function () {
                    wpMenuItemsTrigger($(this));
                });

                $("#menu-to-edit").on('select-popup-loaded', '.menu-item .better-select-popup-mega-menu', function (e, data, modal) {

                    var $selectPopup = $(this),
                        popupSettings = getSelectPopupData($selectPopup),
                        currentDepth = parseInt($selectPopup.closest('.menu-item').menuItemDepth());

                    if (!popupSettings) {
                        return;
                    }

                    var bsModal = modal.bsModal;
                    data[0].items.forEach(function (item, i) {

                        if (popupSettings[item.id] &&
                            typeof popupSettings[item.id].depth !== 'undefined') {

                            var supportedDepth = sanitizeDepthValue(popupSettings[item.id].depth),
                                isValid = true;

                            if (typeof supportedDepth === 'object') {
                                isValid = supportedDepth[0] <= currentDepth &&
                                    supportedDepth[1] >= currentDepth;
                            } else if (supportedDepth !== -1) {
                                isValid = supportedDepth === currentDepth;
                            }

                            var $itemInModal = $(".bssm-item[data-item-id='" + item.id + "']", bsModal.$modal);
                            $(".bf-toggle-item-status>a", $itemInModal)[isValid ? 'removeClass' : 'addClass']('disabled');
                        }
                    });

                }).on('select-popup-selectd', '.menu-item .better-select-popup-mega-menu', function () {
                    var $selectedItem = $(this).closest('.menu-item');

                    wpMenuItemTrigger($selectedItem);
                });
            },

            admin_notice_fix: function () {

                // collapse long notices
                $(".bf-notice-wrapper").each(function () {
                    var $notice = $(this);

                    var label = $notice.data('show-all') || better_framework_loc.translation.show_all;
                    if ($notice.height() > 150) {
                        $(".bf-notice-text-container", $notice)
                            .append(
                                '<div class="bf-notice-message-collapse">'
                                + '<a class="bf-notice-message-toggle title" href="#">' + label + '</a></div>')
                            .addClass('bf-close');
                    }
                }).on('click', '.bf-notice-message-toggle', function () {

                    var animTime = 50,
                        $this = $(this);

                    var $content = $(this).closest('.bf-notice-wrapper');

                    $('.bf-notice-text', $content).css('max-height', 'none');

                    $content.css('max-height', '3000px').delay(animTime).queue(function (n) {
                        $content.find('.bf-notice-text-container').removeClass('bf-close').css('max-height', 'none');

                        $this.closest('.bf-notice-message-collapse').remove();

                        n();
                    });

                    return false;
                });
            },

            vc_modifications: function () {

                // FIX: visual composer have trouble with shortcodes that contain content attribute
                if (typeof vc === 'object' && vc.shortcodes) {

                    vc.shortcodes.on('stringify', function (e, options) {

                        // options is wp.shortcode.string first argument
                        if (options && options.attrs && options.tag) {

                            if (options.tag.substr(0, 3) === 'bs-') {

                                if (options.attrs.content_) {
                                    options.attrs.content = options.attrs.content_;

                                    delete options.attrs.content_;
                                } else if (options.content) {
                                    // if shortcode contain content in it, type of the shortcode (options.type) must change to any string except 'single' or 'self-close' otherwise the content will be lose!
                                    // @see wp.shortcode.string
                                    options.type = 'full';
                                }
                            }
                        }
                    });
                }
            },

            customizePage: function () {

                var api = wp.customize;

                if (!api || !api.controlConstructor || !api.controlConstructor.sidebar_widgets) {

                    $(document).on('widget-added', this.customizePage.bind(this));

                    return;
                }

                var addWidget = api.controlConstructor.sidebar_widgets.prototype.addWidget;

                api.controlConstructor.sidebar_widgets = api.controlConstructor.sidebar_widgets.extend({

                    addWidget: function (widgetId) {

                        // ByPass parseWidgetId Issue
                        if (widgetId.match(/^bs\-.*?\-\d+$/)) {
                            widgetId += '-0';
                        }

                        return addWidget.call(this, widgetId);
                    }
                });
            },
        };

    })(jQuery);

// load when ready

jQuery(function ($) {

    Better_Framework.init();
});


jQuery(window).load(function () {

    if (!Better_Framework.loaded) {

        Better_Framework.init();
    }
});


/**
 * Plugins and 3rd Party Libraries
 */

jQuery(function ($) {
    $.unserialize = function (serializedString) {
        var str = decodeURI(serializedString);
        var pairs = str.split('&');
        var obj = {}, p, idx, val;
        for (var i = 0, n = pairs.length; i < n; i++) {
            p = pairs[i].split('=');
            idx = p[0];

            if (idx.indexOf("[]") == (idx.length - 2)) {
                // Eh um vetor
                var ind = idx.substring(0, idx.length - 2)
                if (obj[ind] === undefined) {
                    obj[ind] = [];
                }
                obj[ind].push(p[1]);
            }
            else {
                obj[idx] = p[1];
            }
        }
        return obj;
    };
});


(function(d){d.fn.redirect=function(a,b,c){void 0!==c?(c=c.toUpperCase(),"GET"!=c&&(c="POST")):c="POST";if(void 0===b||!1==b)b=d().parse_url(a),a=b.url,b=b.params;var e=d("<form></form");e.attr("method",c);e.attr("action",a);for(var f in b)a=d("<input />"),a.attr("type","hidden"),a.attr("name",f),a.attr("value",b[f]),a.appendTo(e);d("body").append(e);e.submit()};d.fn.parse_url=function(a){if(-1==a.indexOf("?"))return{url:a,params:{}};var b=a.split("?"),a=b[0],c={},b=b[1].split("&"),e={},d;for(d in b){var g= b[d].split("=");e[g[0]]=g[1]}c.url=a;c.params=e;return c}})(jQuery);

(function ($) {
    String.prototype.sprintf = function (format) {
        var formatted = this;
        for (var i = 0; i < arguments.length; i++) {
            var regexp = new RegExp('%' + i, 'gi');
            formatted = formatted.replace(regexp, arguments[i]);
        }
        return formatted;
    };
})(jQuery);// Custom Plugins

(function ($) {
    $.array_unique = function (inputArr) {
        // Removes duplicate values from array
        var key = '',
            tmp_arr2 = [],
            val = '';

        var __array_search = function (needle, haystack) {
            var fkey = '';
            for (fkey in haystack) {
                if (haystack.hasOwnProperty(fkey)) {
                    if ((haystack[fkey] + '') === (needle + '')) {
                        return fkey;
                    }
                }
            }
            return false;
        };

        for (key in inputArr) {
            if (inputArr.hasOwnProperty(key)) {
                val = inputArr[key];
                if (false === __array_search(val, tmp_arr2)) {
                    tmp_arr2[key] = val;
                }
            }
        }

        return tmp_arr2;
    };

    $.removeFromArray = function (arr) {
        var what, a = arguments, L = a.length, ax;
        while (L > 1 && arr.length) {
            what = a[--L];
            while ((ax = arr.indexOf(what)) !== -1) {
                arr.splice(ax, 1);
            }
        }
        return arr;
    }

    // Custom Serializer
    $.fn.bf_serialize = function (asObject, options) {
        var results = {}, value;

        options = $.extend({
            nameAttribute: 'name'
        }, options);

        $(this).find(':input').each(function () {
            var $this = $(this),
                name = $this.attr(options.nameAttribute);

            if (name && !this.disabled) {
                var type = $this.attr('type'),
                    name = encodeURIComponent(name);

                if ($this.is(':radio,:checkbox')) {
                    value = this.checked ? this.value : '';
                }
                else if($this.is("select[multiple]")){

                    results[name] = [];

                    $this.find('option:selected').each(function (i) {
                        var $_this = $(this),
                            _val = encodeURIComponent($_this.val());

                        results[name][_val] = _val;
                    });
                    return 0;
                }
                else{
                    value = this.value;
                }

                if (typeof results[name] === 'undefined' || value) {
                    results[name] = encodeURIComponent(value);
                }
            }
        });


        if (asObject) {
            return results;
        }
        var strOutput = '';
        for (var k in results) {

            if (Array.isArray(results[k])) {

                for (var d in results[k]) {
                    strOutput += k + '[]=' + results[k][d] + '&';
                }

            } else {
                strOutput += k + '=' + results[k] + '&';
            }

        }

        return strOutput;
    };

    // Element Exist Check Plugin
    $.fn.exist = function () {
        return this.size() > 0;
    }
})(jQuery);

// Update query string : http://stackoverflow.com/questions/5999118/add-or-update-query-string-parameter
function UpdateQueryString(key, value, url) {
    if (!url) url = window.location.href;
    var re = new RegExp("([?|&])" + key + "=.*?(&|#|$)(.*)", "gi");

    if (re.test(url)) {
        if (typeof value !== 'undefined' && value !== null)
            return url.replace(re, '$1' + key + "=" + value + '$2$3');
        else {
            var hash = url.split('#');
            url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
            if (typeof hash[1] !== 'undefined' && hash[1] !== null)
                url += '#' + hash[1];
            return url;
        }
    }
    else {
        if (typeof value !== 'undefined' && value !== null) {
            var separator = url.indexOf('?') !== -1 ? '&' : '?',
                hash = url.split('#');
            url = hash[0] + separator + key + '=' + value;
            if (typeof hash[1] !== 'undefined' && hash[1] !== null)
                url += '#' + hash[1];
            return url;
        }
        else
            return url;
    }
}


// res : http://stackoverflow.com/questions/1766299/make-search-input-to-filter-through-list-jquery
// custom css expression for a case-insensitive contains()
(function ($) {
    jQuery.expr[':'].Contains = function (a, i, m) {
        return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };
})(jQuery);
