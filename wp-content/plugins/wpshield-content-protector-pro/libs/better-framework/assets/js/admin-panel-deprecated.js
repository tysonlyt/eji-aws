(function ($) {
    Better_Framework.panel = Better_Framework.panel || {};
    Better_Framework.panel.deprecated = Better_Framework.panel.deprecated || {};

    Better_Framework.panel.deprecated.deferred = {

        init: function () {

            var self = this;

            /**
             * Setup ajax group fields
             */

            var ajaxGroups = self.bind({

                activeDeferredClass: 'bf-ajax-group',
                loadDeferredClass: 'bf-ajax-group-loaded',
                clickEventClass: 'fields-group-title-container',

                allowMultipleAjax: true,

                getSectionID: function ($group) {

                    return $group.attr('id').replace(/^fields\-group\-/, '');
                },
                getSectionInner: function ($group) {
                    return $(".bf-group-inner", $group);
                },
                afterAjaxCallback: function ($inner) {
                    $inner.css('min-height', '0');
                    //         Better_Framework.panel.deprecated.deferred.init();

                    $inner.find(':input:first').trigger('force-change');

                    Better_Framework.Hooks.do_action('panel/tabs/loaded-deferred', $inner[0]);
                },
                beforeAjaxCallback: function ($inner) {
                    $inner.css('min-height', '100px');
                }
            }).init();

            // Auto load opened groups
            $('.bf-ajax-group.open').each(function() {
                ajaxGroups.fire($(this));
            });

            /**
             * Setup ajax tabs
             */

            self.bind({

                activeDeferredClass: 'bf-ajax-tab',
                loadDeferredClass: 'bf-ajax-tab-loaded',
                clickEventClass: 'bf-tab-item-a',

                getSectionID: function ($li) {
                    return $li.data('go');
                },
                getSectionInner: function ($li) {
                    var tab_id = $li.data('go');

                    if (better_framework_loc.type === 'panel') {

                        return $('#bf-group-' + tab_id);
                    } else {

                        var $metaboxWrapper = $li.closest('.bf-metabox-wrap'),
                            metaboxID       = $metaboxWrapper.data("id");

                        return $("#bf-metabox-" + metaboxID + "-" + tab_id, $metaboxWrapper);
                    }
                },
                beforeAjaxCallback: function ($inner, $li) {

                    $inner.parent().children('.group').hide(); // Hide Active tab content
                    $inner.fadeIn(500); // Display New tab content

                    $li.closest('ul').find('.active_tab').removeClass('active_tab'); // Remove active class
                    $li.find('.bf-tab-item-a').addClass('active_tab'); // Add active class

                },
                afterAjaxCallback: function ($inner, $li) {

                    $li.siblings().removeClass('active_tab'); // Remove active class
                    $li.addClass('active_tab'); // Add active class

                    Better_Framework.panel.deprecated.deferred.init();
                    Better_Framework.Hooks.do_action('panel/tabs/loaded-deferred', $inner[0]);
                }
            }).init();
        },

        bind: function (settings) {

            var config = $.extend({

                activeDeferredClass: 'bf-ajax-section',
                loadDeferredClass: 'bf-ajax-section-loaded',
                clickEventClass: '',

                allowMultipleAjax: false,

                getSectionID: function ($section) {

                    return $section.attr('id');
                },
                getSectionInner: function ($section) {
                    return $section.children('.container');
                },
                successAjaxCallback: function (res, $inner, $section, sectionID) {

                    $inner.html(res.out);

                    $(document).trigger(config.loadDeferredClass, [$inner, $section, sectionID]);

                    $section.addClass(config.loadDeferredClass);
                },

                beforeAjaxCallback: function () {

                },

                afterAjaxCallback: function ($inner) {

                    Better_Framework.Hooks.do_action('panel/tabs/loaded-deferred', $inner[0]);

                },
                errorAjaxCallback: function ($inner, $section, sectionID) {
                    $inner.html(better_framework_loc.on_error.again);
                },

                ajaxData: function ($inner, $section, sectionID) {

                    var data = {
                        reqID: 'fetch-deferred-field',
                        sectionID: sectionID
                    };

                    var objectID = 0,
                        haveMetabox = true;

                    switch (better_framework_loc.type) {

                        case 'metabox':

                            objectID = $('#post_ID').val();
                            break;

                        case 'taxonomy':

                            objectID = $('input[name="tag_ID"]').val();

                            if (!objectID) {
                                data.taxonomy = $('input[name="taxonomy"]').val();
                            }

                            break;

                        case 'users':

                            objectID = $('input[name="user_id"]').val();
                            break;

                        default:
                            haveMetabox = false;
                    }

                    if (haveMetabox) {

                        data.metabox = true;
                        data.object_id = objectID;
                        data.type = better_framework_loc.type;

                        var $mb = $section.closest(".bf-metabox-wrap");
                        data.metabox_id = $mb.data('metabox-id') || $mb.data('id');
                    }

                    return data;
                },

            }, settings);


            var xhr;


            return {

                init: function () {

                    var _ = this;

                    if (config.clickEventClass) {

                        var $els = $('.' + config.activeDeferredClass + ' .' + config.clickEventClass);
                        $els.off('click.bs-deferred').on('click.bs-deferred', function () {

                            var $section = $(this).closest('.' + config.activeDeferredClass);

                            _.fire($section);
                        });
                    }

                    return _;
                },

                fire: function ($section) {

                    var _ = this;

                    if (!$section.hasClass(config.activeDeferredClass)) {
                        return;
                    }

                    if ($section.hasClass(config.loadDeferredClass)) {
                        return;
                    }

                    var $inner = config.getSectionInner($section),
                        sectionID = config.getSectionID($section);

                    if (!$inner || !sectionID) {
                        throw new Error("invalid 'deferred panel field' config.");
                    }

                    $inner.html(better_framework_loc.loading).fadeIn(10);

                    if (!config.allowMultipleAjax && xhr) {
                        xhr.abort();
                    }

                    config.beforeAjaxCallback($inner, $section, sectionID);

                    xhr = Better_Framework._ajax(function (res) {
                            var result = config.successAjaxCallback(res, $inner, $section, sectionID)
                            config.afterAjaxCallback($inner, $section, sectionID);

                            return result;
                        },
                        config.ajaxData($inner, $section, sectionID), {},
                        function () {
                            return config.errorAjaxCallback($inner, $section, sectionID)
                        }
                    );

                    return _;
                }
            };
        },
    };

    Better_Framework.panel.deprecated.admin_panel = {

        init: function () {

            this.setup_panel_tabs();
        },

        // TODO: Vahid shit! Refactor this
        setup_panel_tabs: function () {


            $('#bf-main #bf-content').css('min-height', $('#bf-main #bf-nav').height() + 50);

            var panelID = $('#bf-panel-id').val();

            var _curret_ = $.cookie('bf_current_tab_of_' + panelID);

            function bf_show_first_tab() {
                $('#bf-nav').find('li:first').addClass("active_tab");
            }

            if (!_curret_) {

                _curret_ = $('#bf-nav').find('li:first').find('a:first').data('go');
            }

            if ($('#bf-nav').find("li[data-go='" + _curret_ + "']").hasClass('bf-ajax-tab')) {
                bf_show_first_tab();
            } else {

                if (!$('#bf_options_form').find('#bf-group-' + _curret_).exist() || !$('#bf-nav').find("a[data-go='" + _curret_ + "']").exist()) {
                    bf_show_first_tab();
                    jQuery.removeCookie('bf_current_tab_of_' + panelID);
                }

                $('#bf_options_form').find('#bf-group-' + _curret_).addClass('active-tab-group').show();
                $('#bf-nav').find("a[data-go='" + _curret_ + "']").parent().addClass("active_tab");
                if ($('#bf-nav').find("a[data-go='" + _curret_ + "']").is(".bf-tab-subitem-a")) {
                    $('#bf-nav').find("a[data-go='" + _curret_ + "']").closest(".has-children").addClass("child_active");
                }
            }

            $('#bf-nav').find('a').click(function (e) {
                e.preventDefault();
                if ($(this).parent().hasClass('active_tab'))
                    return false;

                var _this = $(this);
                var _hasNotGroup = ((_this.parent().hasClass('has-children')) && (!$('#bf_options_form').find('#bf-group-' + _this.data("go")).find(">*").exist()));

                if (_hasNotGroup) {
                    var _clicked = _this.siblings('ul.sub-nav').find('a:first');
                    var _target = $('#bf_options_form').find('#bf-group-' + _clicked.data("go"));
                } else {
                    var _clicked = _this;
                    var _target = $('#bf_options_form').find('#bf-group-' + _clicked.data("go"));
                }

                var $parent = _this.parent();

                function displayTab() {
                    $('#bf-nav').find('li').removeClass("active_tab");
                    $('#bf-nav').find('ul.sub-nav').find('li').removeClass("active_tab");
                    $('#bf-nav').find('li').removeClass("child_active");

                    $('#bf_options_form').find('>div').removeClass('active-tab-group').hide();
                    _target.addClass('active-tab-group').fadeIn(500);

                    _clicked.parent().addClass("active_tab");

                    if ($parent.hasClass('has-children') || $parent.parent().hasClass('sub-nav')) {
                        _clicked.closest('.has-children').addClass("child_active");
                    }

                    $('body,html').animate({
                        scrollTop: 0
                    }, 400);

                    Better_Framework.Hooks.do_action('panel/tabs/switching', _target[0]);
                }

                var isTabAjax = $parent.hasClass('bf-ajax-tab') &&
                    !$parent.hasClass('bf-ajax-tab-loaded');

                if (!isTabAjax) {

                    displayTab();
                    jQuery.cookie('bf_current_tab_of_' + panelID, _clicked.data("go"), {expires: 7});

                    return false;
                }
            });
        }
    };
    Better_Framework.panel.deprecated.meta_box = {

        init: function () {

            this.set_metabox();
            this.metabox_filter_postformat();
            this.metabox_field_filter_postformat();
        },

        set_metabox: function () {

            var $ = jQuery.noConflict();

            // todo refactor this and remove cookie
            $('.bf-metabox-wrap').each(function (i, o) {
                var $metaboxWrap = $(this);

                var _metabox__id = $metaboxWrap.data("id");

                var current_box_cookie = $.cookie('bf_metabox_current_tab_' + _metabox__id);

                function bf_show_first_tab(tab) {
                    tab.find('li:first').addClass("active_tab");

                    tab.siblings('.bf-metabox-container').find('#bf-metabox-' + _metabox__id + "-" + tab.find('li:first').data("go")).addClass('active-tab-group').fadeIn(500);
                }

                var isTabAjax = $('.bf-metabox-tabs', this).find("li[data-go='" + current_box_cookie + "']").hasClass('bf-ajax-tab');
                if (typeof current_box_cookie == 'undefined' || isTabAjax) {
                    bf_show_first_tab($(this).find('.bf-metabox-tabs'));
                } else {
                    if (!$(this).find('#bf-metabox-' + _metabox__id + "-" + current_box_cookie).exist() || !$(this).find(".bf-metabox-tabs").find("a[data-go='" + current_box_cookie + "']").exist()) {
                        bf_show_first_tab($(this).find('>.bf-metabox-tabs'));
                        $.removeCookie('bf_metabox_current_tab_' + _metabox__id);
                        return;
                    }

                    $(this).find('#bf-metabox-' + _metabox__id + "-" + current_box_cookie).addClass('active-tab-group').fadeIn();
                    $(this).find(".bf-metabox-tabs").find("a[data-go='" + current_box_cookie + "']").parent().addClass("active_tab");
                    if ($(this).find(".bf-metabox-tabs").find("a[data-go='" + current_box_cookie + "']").is(".bf-tab-subitem-a")) {
                        $(this).find(".bf-metabox-tabs").find("a[data-go='" + current_box_cookie + "']").closest(".has-children").addClass("child_active");
                    }
                }

                $(this).find('.bf-metabox-tabs').find('a').click(function (e) {
                    e.preventDefault();

                    var _this = $(this);

                    if (_this.parent().hasClass('active_tab')) {
                        return false;
                    }

                    var _metabox_wrap = $(this).closest(".bf-metabox-wrap");
                    var _metabox_nav = _metabox_wrap.find(".bf-metabox-tabs");
                    if (typeof _metabox__id == 'undefined')
                        var _metabox__id = _metabox_wrap.data("id");

                    var _hasNotGroup = (
                        (_this.parent().hasClass('has-children'))
                        &&
                        (!_metabox_wrap.find('#bf-metabox-' + _metabox__id + "-" + _this.data("go")).find(">*").exist())
                    );

                    //var _isChild = ! _this.parent().hasClass('has-children');

                    if (_hasNotGroup) {
                        var _clicked = _this.siblings('ul.sub-nav').find('a:first');
                        var _target = _metabox_wrap.find('#bf-metabox-' + _metabox__id + "-" + _clicked.data("go"));
                    } else {
                        var _clicked = _this;
                        var _target = _metabox_wrap.find('#bf-metabox-' + _metabox__id + "-" + _clicked.data("go"));
                    }

                    var $parent = _this.parent();

                    function displayTab() {
                        var $link = _metabox_nav.find('a');
                        $link.parent().removeClass("active_tab");
                        _metabox_nav.find('ul.sub-nav').find('li').removeClass("active_tab");
                        _metabox_nav.find('li').removeClass("child_active");

                        _metabox_wrap.find(".bf-metabox-container").find('>div').removeClass('active-tab-group').hide();
                        _target.addClass('active-tab-group').fadeIn(500);

                        Better_Framework.Hooks.do_action('metabox/tabs/switching', _target[0], $link.data('go'));

                        _clicked.parent().addClass("active_tab");

                        if ($parent.hasClass('has-children') || $parent.parent().hasClass('sub-nav')) {

                            _clicked.closest('.has-children').addClass("child_active");
                        }
                    }

                    var isTabAjax = $parent.hasClass('bf-ajax-tab') &&
                        !$parent.hasClass('bf-ajax-tab-loaded');

                    if (!isTabAjax) {

                        displayTab();
                        $.cookie('bf_metabox_current_tab_' + _metabox__id, _clicked.data("go"), {expires: 7});

                        return false;
                    }
                });

                $(this).find('.bf-metabox-container.bf-with-tabs').css('min-height', $(this).find('.bf-metabox-tabs').height() + 50);

            });
        },


        // Advanced filter for filter metaboxes for post format's
        metabox_filter_postformat: function () {

            var _current_format = $('#post-formats-select input[type=radio][name=post_format]:checked').attr('value');
            if (parseInt(_current_format) == 0)
                _current_format = 'standard';

            $('.bf-metabox-wrap').each(function () {
                if (typeof $(this).data('bf_pf_filter') == 'undefined' || $(this).data('bf_pf_filter') == '')
                    return 1;

                var _metabox_id = '#bf_' + $(this).data('id'),
                    __metabox_id = 'bf_' + $(this).data('id'),

                    _formats = $(this).data('bf_pf_filter').split(',');

                if ($.inArray(_current_format, _formats) == -1)
                    $(_metabox_id).hide();
                else
                    $(_metabox_id).show();
            });

            var _this = this;
            $('#post-formats-select input[type=radio][name=post_format]').change(function () {
                _this.metabox_filter_postformat();
            });
        },


        // Advanced filter for filter metabox fields for post format's
        metabox_field_filter_postformat: function () {

            var _current_format = $('#post-formats-select input[type=radio][name=post_format]:checked').attr('value');
            if (parseInt(_current_format) == 0)
                _current_format = 'standard';

            $('.bf-field-post-format-filter').each(function () {

                if (typeof $(this).data('bf_pf_filter') == 'undefined' || $(this).data('bf_pf_filter') == '')
                    return 1;

                var _formats = $(this).data('bf_pf_filter').split(',');

                if ($.inArray(_current_format, _formats) == -1)
                    $(this).hide();
                else
                    $(this).show();
            });


            $('#post-formats-select input[type=radio][name=post_format]').change(function () {
                Better_Framework.metabox_field_filter_postformat();
            });
        },
    }

    Better_Framework.panel.deprecated.init = function () {

        Better_Framework.panel.deprecated.meta_box.init();
        Better_Framework.panel.deprecated.admin_panel.init();

        Better_Framework.panel.deprecated.deferred.init();
    }

})(jQuery);