(function ($) {

    Better_Framework.panel = Better_Framework.panel || {};

    var controlsStorage = new WeakMap();

    Better_Framework.panel.controlInstance = function (element) {

        return element && controlsStorage.get(element);
    };

    Better_Framework.panel.controlInit = function (element, props) {

        var instance = Better_Framework.panel.controlInstance(element);

        if (instance) {

            return instance;
        }

        var controlType = element.dataset && element.dataset.paramType || '';

        if (!controlType || !BetterStudio || !BetterStudio.Controls) {

            return;
        }

        if (BetterStudio.Controls.Instances && BetterStudio.Controls.Instances[controlType]) {

            var control = new BetterStudio.Controls.Instances[controlType](props || {});
            control.init(element);

            controlsStorage.set(element, control);

            return control;
        }
    };

    Better_Framework.panel.general = {

        init: function () {

            this.setup_groups();

            if (typeof BetterStudio === "object" && BetterStudio.Controls) {

                BetterStudio.Controls.Init && BetterStudio.Controls.Init();
            }
        },

        setup_groups: function () {

            jQuery(document)
                .off('click.bf-group')
                .on('click.bf-group', '.fields-group-title-container', function () {

                    var $_group = jQuery(this).closest('.fields-group'),
                        $_button = $_group.find('.collapse-button');

                    if ($_group.hasClass('open')) {

                        $_group.children('.bf-group-inner').slideUp(400);

                        $_group.removeClass('open').addClass('close');
                        $_button.find('.fa').removeClass('fa-minus').addClass('fa-plus');

                        Better_Framework.Hooks.do_action('panel/group/closed', $_group[0]);

                    } else {

                        $_group.removeClass('close').addClass('open');
                        $_button.find('.fa').removeClass('fa-plus').addClass('fa-minus');

                        $_group.children('.bf-group-inner').slideDown(400);

                        Better_Framework.Hooks.do_action('panel/group/opened', $_group[0]);
                    }
                });
        }
    };

    Better_Framework.panel.controls = {

        init: function (context) {

            this.context = context || document.body;

            Better_Framework.Hooks.add_action('controls/loaded', Better_Framework.panel.controls.renderControlsContainer);

            this.panels().forEach(this.panel.init.bind(this));
            this.meta_box.init(this.context);
            this.gutenberg.init();
            this.widget.init();
            this.menu.init();
            this.mce.init();
            this.vc.init();
        },

        renderControlsContainer: function (context, attachOnce) {

            attachOnce = typeof attachOnce === "boolean" ? attachOnce : true;

            if (!context || (context.dataset.bsControls && attachOnce)) {

                return;
            }

            if(!context.querySelector('.bf-section-container'))  {

                return ;
            }

            /// iterate through fields
            context.querySelectorAll('.bf-section-container').forEach(function (element) {

                Better_Framework.panel.controls.renderControl(element);
            });

            context.dataset.bsControls = true;
        },

        renderControl: function (element, props) {

            element && Better_Framework.panel.controlInit(element, props);
        },

        panels: function () {

            return this.context.querySelectorAll('#bf-panel');
        },

        panel: {

            init: function (element) {

                var activeTab = element.querySelectorAll('.active-tab-group');

                if (activeTab.length) {

                    activeTab.forEach(Better_Framework.panel.controls.renderControlsContainer);

                } else {

                    Better_Framework.panel.controls.renderControlsContainer(element);
                }

                Better_Framework.Hooks.add_action('panel/tabs/loaded-deferred', Better_Framework.panel.controls.renderControlsContainer);
                Better_Framework.Hooks.add_action('panel/tabs/switching', Better_Framework.panel.controls.renderControlsContainer);

                if (typeof BetterStudio === "object" && typeof BetterStudio.Controls === "object") {

                    BetterStudio.Controls.Hooks.add_action('repeater/item/added', Better_Framework.panel.controls.renderControlsContainer);
                }
            },
        },
        meta_box: {

            init: function (context) {

                // init visible tabs
                context.querySelectorAll(".bf-metabox-wrap").forEach(function (metabox) {

                    var activeElement = metabox.querySelector(".active-tab-group") || metabox;

                    Better_Framework.panel.controls.renderControlsContainer(activeElement);
                });

                Better_Framework.Hooks.add_action('metabox/tabs/switching', Better_Framework.panel.controls.renderControlsContainer);
                Better_Framework.Hooks.add_action('panel/tabs/loaded-deferred', Better_Framework.panel.controls.renderControlsContainer);

                if (typeof BetterStudio === "object" && typeof BetterStudio.Controls === "object") {

                    BetterStudio.Controls.Hooks.add_action('repeater/item/added', Better_Framework.panel.controls.renderControlsContainer);
                }

            }
        },

        widget: {

            init: function () {

                var currentWidgets = document.getElementById('widgets-right');

                if (!currentWidgets) {

                    return;
                }

                currentWidgets.querySelectorAll(".widget-content").forEach(Better_Framework.panel.controls.renderControlsContainer);

                $(document).on('widget-updated widget-added', this.onWidgetLoaded.bind(this));
            },

            onWidgetLoaded: function (e, $widget) {

                Better_Framework.panel.controls.renderControlsContainer($widget[0], false);
            }
        },

        menu: {

            init: function () {

                if (!document.getElementById('menu-to-edit')) { // is in menu page

                    return;
                }

                Better_Framework.Hooks.add_action('panel/group/opened', Better_Framework.panel.controls.renderControlsContainer);

                $(document).on('menu-item-added', this.onMenuItemAdded.bind(this));
            },

            onMenuItemAdded: function (e, $wrapper) {

                Better_Framework.panel.controls.renderControlsContainer($wrapper[0]);
            }
        },

        mce: {

            init: function () {

                $(document).on("mce-view-fields-loaded", this.onMceFieldsLoaded.bind(this));
            },

            onMceFieldsLoaded: function (e, modal) {

                Better_Framework.panel.controls.renderControlsContainer(modal.$modal[0]);
            }
        },

        vc: {

            init: function () {

                this.attachVcEditShortcodeEvent(this.onVcMapEdit.bind(this));
            },

            onVcMapEdit: function (view) {

                this.setupVcParams(view.el);
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

            setupVcParams: function (context) {

                // always append bf-fields-style class
                context.classList.add('bf-fields-style');

                context.querySelectorAll('.vc_column').forEach(this.setupVcColumn.bind(this))
            },

            setupVcColumn: function (column) {

                var settings = column.dataset.param_settings;

                if (typeof settings === "string") {
                    settings = JSON.parse(settings);
                }

                if (!settings) {

                    return false;
                }

                var classes = "";

                if (settings.section_class) {
                    classes += " " + settings.section_class;
                }
                if (settings.container_class) {
                    classes += " " + settings.container_class;
                }

                classes.split(' ').forEach(function (className) {
                    className && column.classList.add(className);
                });
            }
        },

        gutenberg: {

            init: function () {

                document.addEventListener("bf-component-did-mount", this.onComponentDidMount.bind(this));
            },

            onComponentDidMount: function (event) {

                var props = event.detail.props,
                    element = event.detail.wrapper;

                var control = Better_Framework.panel.controls.renderControl(element, props);

                element.addEventListener("bf-component-did-update", function () {

                    if (control) {

                        control.destroy();
                        control.init(element);
                    }
                });
            }
        }
    };

    Better_Framework.panel.init = function () {

        if (!Better_Framework.panel.loaded) {

            Better_Framework.panel.general.init();
            Better_Framework.panel.controls.init();
            Better_Framework.panel.loaded = true;
        }
    }
})(jQuery);