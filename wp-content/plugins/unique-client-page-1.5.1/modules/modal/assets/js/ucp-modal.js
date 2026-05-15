/**
 * UCP Modal - 通用模态框组件
 * 
 * 提供一个简单但功能完整的模态框系统，不依赖其他外部库
 */
(function($) {
    'use strict';
    
    // 定义全局UCPModal类
    window.UCPModal = function(options) {
        var self = this;
        
        // 默认选项
        this.defaults = {
            title: '模态框',
            content: '',
            size: 'medium', // small, medium, large
            closeOnEsc: true,
            closeOnBackdrop: true,
            showCloseButton: true
        };
        
        // 合并选项
        this.options = $.extend({}, this.defaults, options || {});
        
        // 创建模态框DOM
        this.createModal();
        
        // 绑定事件
        this.bindEvents();
        
        // 返回实例
        return this;
    };
    
    // 原型方法
    UCPModal.prototype = {
        /**
         * 创建模态框DOM结构
         */
        createModal: function() {
            var self = this;
            var sizeClass = 'ucp-modal-' + this.options.size;
            
            // 模态框HTML
            var modalHTML = '<div class="ucp-modal">' + 
                '<div class="ucp-modal-backdrop"></div>' +
                '<div class="ucp-modal-container ' + sizeClass + '">' + 
                    '<div class="ucp-modal-header">' + 
                        '<h3 class="ucp-modal-title">' + this.options.title + '</h3>' +
                        (this.options.showCloseButton ? '<button type="button" class="ucp-modal-close">&times;</button>' : '') +
                    '</div>' +
                    '<div class="ucp-modal-body">' + this.options.content + '</div>' +
                '</div>' +
            '</div>';
            
            // 添加到DOM
            this.$modal = $(modalHTML).appendTo('body');
            
            // 存储元素引用
            this.$backdrop = this.$modal.find('.ucp-modal-backdrop');
            this.$container = this.$modal.find('.ucp-modal-container');
            this.$header = this.$modal.find('.ucp-modal-header');
            this.$title = this.$modal.find('.ucp-modal-title');
            this.$body = this.$modal.find('.ucp-modal-body');
            this.$close = this.$modal.find('.ucp-modal-close');
            
            // 默认隐藏
            this.$modal.hide();
        },
        
        /**
         * 绑定事件处理
         */
        bindEvents: function() {
            var self = this;
            
            // 关闭按钮点击
            if (this.options.showCloseButton) {
                this.$close.on('click', function(e) {
                    e.preventDefault();
                    self.close();
                });
            }
            
            // 点击背景关闭
            if (this.options.closeOnBackdrop) {
                this.$backdrop.on('click', function(e) {
                    if ($(e.target).is(self.$backdrop)) {
                        self.close();
                    }
                });
            }
            
            // ESC键关闭
            if (this.options.closeOnEsc) {
                $(document).on('keydown.ucpmodal', function(e) {
                    if (e.keyCode === 27) { // ESC键
                        self.close();
                    }
                });
            }
        },
        
        /**
         * 打开模态框
         */
        open: function() {
            var self = this;
            
            // 显示模态框
            this.$modal.show();
            
            // 添加激活类
            setTimeout(function() {
                self.$modal.addClass('active');
            }, 10);
            
            // 触发事件
            $(document).trigger('ucpmodal:open', [this]);
            
            return this;
        },
        
        /**
         * 关闭模态框
         */
        close: function() {
            var self = this;
            
            // 移除激活类
            this.$modal.removeClass('active');
            
            // 延迟移除DOM
            setTimeout(function() {
                self.$modal.hide();
                self.$modal.remove();
            }, 300);
            
            // 解绑ESC事件
            $(document).off('keydown.ucpmodal');
            
            // 触发事件
            $(document).trigger('ucpmodal:close', [this]);
            
            return this;
        },
        
        /**
         * 设置标题
         */
        setTitle: function(title) {
            this.$title.html(title);
            return this;
        },
        
        /**
         * 设置内容
         */
        setContent: function(content) {
            this.$body.html(content);
            return this;
        },
        
        /**
         * 添加内容
         */
        appendContent: function(content) {
            this.$body.append(content);
            return this;
        },
        
        /**
         * 移除内容
         */
        clearContent: function() {
            this.$body.empty();
            return this;
        },
        
        /**
         * 设置加载状态
         */
        setLoading: function(isLoading) {
            if (isLoading) {
                this.$body.html('<div class="ucp-modal-loading"><span>加载中...</span></div>');
            }
            return this;
        }
    };
    
    // 添加到jQuery
    $.ucpModal = function(options) {
        return new UCPModal(options).open();
    };
    
    // 注册模态框相关的事件处理
    $(document).ready(function() {
        // console.log('UCP Modal 已加载');
    });
    
})(jQuery);
