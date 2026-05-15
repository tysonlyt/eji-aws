/**
 * Wishlist Queue Management System
 * Optimized solution for handling multiple wishlist operations
 * 
 * Addresses performance issues when adding/removing multiple products simultaneously
 */

(function($) {
    'use strict';

    // Wishlist queue management system
    var UCP_WishlistQueue = {
        queue: [],                // Operation queue
        processing: false,        // Flag to prevent concurrent processing
        batchSize: 5,             // Reduced batch size for more frequent updates
        processingDelay: 100,     // Increased delay between batches
        maxRetries: 3,            // Maximum retry attempts for failed requests
        pageId: 0,                // Current page ID
        nonce: '',                // Security nonce
        ajaxUrl: '',              // AJAX endpoint
        debug: true,              // Enable detailed logging

        /**
         * 初始化队列系统
         * 设置参数并初始化队列
         */
        init: function(ajaxUrl, nonce, pageId) {
            this.ajaxUrl = ajaxUrl;
            this.nonce = nonce;
            this.pageId = pageId;

            // 重置队列和状态
            this.queue = [];
            this.processing = false;
            
            // 添加页面卸载事件处理，确保在页面关闭前处理未完成的队列
            var self = this;
            $(window).on('beforeunload', function() {
                if (self.queue.length > 0) {
                    self.flushQueue();
                }
            });

            if (this.debug) {
                console.log('Wishlist Queue System initialized with parameters:', {
                    pageId: this.pageId,
                    batchSize: this.batchSize,
                    delay: this.processingDelay
                });
            }
        },

        /**
         * 添加操作到队列
         * 通用方法，用于处理所有队列项添加
         */
        addOperation: function(productId, button, action) {
            if (this.debug) {
                console.log('Adding operation to queue:', { productId: productId, action: action });
            }
            
            // 检查是否已存在同样的操作
            var existingOpIndex = -1;
            for (var i = 0; i < this.queue.length; i++) {
                if (this.queue[i].productId == productId) {
                    existingOpIndex = i;
                    break;
                }
            }
            
            // 如果已存在同一产品的操作，则替换
            if (existingOpIndex >= 0) {
                if (this.debug) {
                    console.log('Product already in queue, updating operation', { productId: productId, newAction: action });
                }
                this.queue[existingOpIndex].action = action;
                this.queue[existingOpIndex].button = button;
            } else {
                // 添加新操作到队列
                this.queue.push({
                    productId: productId,
                    button: button,
                    action: action,
                    timestamp: new Date().getTime(),
                    retries: 0
                });
            }

            // 更新按钮状态
            if (button) {
                button.prop('disabled', true);
                button.find('.wishlist-text').text('Queued...');
            }

            // 如果队列未在处理中，开始处理
            if (!this.processing) {
                this.processQueue();
            }
        },

        /**
         * 添加产品到队列
         */
        addToQueue: function(productId, button) {
            this.addOperation(productId, button, 'add');
        },

        /**
         * 从队列中移除产品
         */
        removeFromQueue: function(productId, button) {
            this.addOperation(productId, button, 'remove');
        },

        /**
         * 立即处理所有队列项
         */
        flushQueue: function() {
            if (this.queue.length > 0 && !this.processing) {
                this.processQueue(true);
            }
        },

        /**
         * 处理队列
         * @param {boolean} flush - 是否立即处理全部队列
         */
        processQueue: function(flush) {
            var self = this;
            var currentBatch = [];
            var addBatch = [];
            var removeBatch = [];

            // 防止重复处理
            if (this.processing) {
                if (this.debug) {
                    console.log('Queue already processing, waiting...');
                }
                return;
            }

            // 设置处理状态
            this.processing = true;

            // 如果队列为空，停止处理
            if (this.queue.length === 0) {
                if (this.debug) {
                    console.log('Queue empty, processing complete.');
                }
                this.processing = false;
                return;
            }

            // 如果需要刷新所有队列，取所有项目，否则只取一批
            var batchSize = flush ? this.queue.length : this.batchSize;
            
            // 将队列按时间戳排序，优先处理早到的操作
            this.queue.sort(function(a, b) {
                return (a.timestamp || 0) - (b.timestamp || 0);
            });
            
            if (this.debug) {
                console.log('Processing batch of ' + batchSize + ' items, ' + this.queue.length + ' items in queue');
            }
            
            // 从队列中获取一批
            currentBatch = this.queue.splice(0, batchSize);

            // 分离添加和删除操作
            currentBatch.forEach(function(item) {
                if (item.action === 'add') {
                    addBatch.push(item);
                } else {
                    removeBatch.push(item);
                }
            });

            // 使用Promise处理批次，确保可以听取到完成状态
            var batchPromises = [];
            
            // 处理添加批次
            if (addBatch.length > 0) {
                batchPromises.push(this.processBatchPromise(addBatch, 'add'));
            }

            // 处理删除批次
            if (removeBatch.length > 0) {
                batchPromises.push(this.processBatchPromise(removeBatch, 'remove'));
            }
            
            // 同时处理所有批次，完成后继续处理队列
            Promise.all(batchPromises).finally(function() {
                // 批次处理完成，检查是否还有项目需要处理
                if (self.queue.length > 0) {
                    if (flush) {
                        // 立即处理下一批
                        self.processQueue(true);
                    } else {
                        // 设置延时处理下一批
                        setTimeout(function() {
                            self.processQueue();
                        }, self.processingDelay);
                    }
                } else {
                    // 所有队列处理完成
                    self.processing = false;
                    if (self.debug) {
                        console.log('All queue items processed');
                    }
                }
            });
        },

        /**
         * Promise-based 批处理
         * 返回一个Promise，完成后解析
         */
        processBatchPromise: function(batch, action) {
            var self = this;
            return new Promise(function(resolve, reject) {
                self.processBatch(batch, action, resolve, reject);
            });
        },
        
        /**
         * 处理批次
         * @param {Array} batch - 要处理的批次
         * @param {string} action - 操作类型 ('add' 或 'remove')
         * @param {function} resolve - Promise解析函数
         * @param {function} reject - Promise拒绝函数
         */
        processBatch: function(batch, action, resolve, reject) {
            var self = this;
            var productIds = [];
            var buttonMap = {};
            
            if (this.debug) {
                console.log('Processing ' + action + ' batch: ', batch.length + ' items');
                console.time('batch-' + action);
            }

            // 提取产品ID并创建按钮映射
            batch.forEach(function(item) {
                productIds.push(item.productId);
                if (item.button) {
                    buttonMap[item.productId] = item.button;
                }
            });
            
            // 再次检查批次是否为空
            if (productIds.length === 0) {
                if (resolve) resolve();
                return;
            }

            // 发送AJAX请求
            $.ajax({
                url: this.ajaxUrl,
                type: 'POST',
                dataType: 'json',
                timeout: 15000, // 设置15秒超时，防止长时间挂起
                data: {
                    action: 'ucp_update_wishlist',  // 修改为正确的AJAX处理器名称
                    wishlist_action: action,
                    product_ids: productIds,
                    page_id: this.pageId,
                    nonce: this.nonce,
                    _cache_buster: new Date().getTime() // 防止缓存
                },
                success: function(response) {
                    if (response.success) {
                        if (self.debug) {
                            console.log('Batch ' + action + ' successful', response.data);
                            console.timeEnd('batch-' + action);
                        }
                        
                        // 更新所有按钮
                        if (response.data && response.data.in_wishlist) {
                            for (var productId in response.data.in_wishlist) {
                                var button = buttonMap[productId];
                                var inWishlist = response.data.in_wishlist[productId];
                                
                                if (button) {
                                    self.updateButtonStatus(button, inWishlist);
                                }
                            }
                        }
                        
                        // 成功完成批处理
                        if (resolve) resolve(response);
                    } else {
                        // 请求成功但处理失败
                        console.error('Error processing batch:', response.data ? response.data.message : 'Unknown error');
                        
                        // 重新添加失败的项目到队列，注意比较与最大重试次数
                        self.handleRetry(batch, 'Server error: ' + (response.data ? response.data.message : 'Unknown error'));
                        
                        // 通知Promise
                        if (reject) reject(response);
                        else if (resolve) resolve(); // 如果没有reject函数，仍然当作完成处理
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = 'AJAX error: ' + status + ' - ' + error;
                    console.error(errorMessage);
                    if (self.debug) {
                        console.timeEnd('batch-' + action);
                        console.log('XHR status:', xhr.status);
                        console.log('Response text:', xhr.responseText);
                    }
                    
                    // 使用改进的重试逻辑
                    self.handleRetry(batch, errorMessage);
                    
                    // 通知Promise
                    if (reject) reject(xhr);
                    else if (resolve) resolve(); // 如果没有reject函数，仍然当作完成处理
                },
                complete: function() {
                    // 无论成功失败都会执行的代码
                }
            });
        },

        /**
         * 处理失败重试
         * @param {Array} batch - 要重试的批次
         * @param {string} errorMessage - 错误信息
         */
        handleRetry: function(batch, errorMessage) {
            var self = this;
            var retriedCount = 0;
            var failedCount = 0;
            var now = new Date().getTime();
            
            // 处理每个失败的项目
            batch.forEach(function(item) {
                // 初始化重试计数器
                if (item.retries === undefined) {
                    item.retries = 0;
                    item.firstErrorTime = now;
                }
                
                // 如果重试次数小于最大重试次数，并且错误时间不超过2分钟
                if (item.retries < self.maxRetries && (now - item.firstErrorTime) < 120000) {
                    item.retries++;
                    // 按重试次数增加等待时间
                    var delay = Math.min(item.retries * 1000, 5000); // 最多等待5秒
                    item.timestamp = now + delay; // 在将来处理
                    
                    // 添加到队列中进行重试
                    self.queue.push(item);
                    retriedCount++;
                    
                    if (self.debug) {
                        console.log('Scheduling retry ' + item.retries + ' of ' + self.maxRetries + ' for product ' + item.productId + ' after ' + delay + 'ms');
                    }
                    
                    // 更新按钮状态为重试中
                    if (item.button) {
                        item.button.prop('disabled', true);
                        item.button.find('.wishlist-text').text('Retrying...');
                    }
                } else {
                    failedCount++;
                    // 超过重试次数，恢复按钮原状态
                    if (item.button) {
                        item.button.prop('disabled', false);
                        // 选择合适的状态文本
                        var statusText = item.action === 'add' ? 'Add to Wishlist' : 'Remove from Wishlist';
                        item.button.find('.wishlist-text').text(statusText);
                    }
                    
                    if (self.debug) {
                        console.error('Failed to process product ' + item.productId + ' after ' + self.maxRetries + ' attempts');
                    }
                }
            });
            
            if (self.debug && (retriedCount > 0 || failedCount > 0)) {
                console.log('Retry summary:', {
                    retriedItems: retriedCount,
                    failedItems: failedCount, 
                    error: errorMessage
                });
            }
        },

        /**
         * 更新按钮状态
         * @param {Object} button - jQuery按钮对象
         * @param {boolean} inWishlist - 是否在愿望清单中
         */
        updateButtonStatus: function(button, inWishlist) {
            if (!button || !button.length) {
                if (this.debug) console.warn('Invalid button element');
                return;
            }
            
            // 恢复按钮状态
            button.prop('disabled', false);
            
            if (inWishlist) {
                button.removeClass('add-to-wishlist').addClass('remove-from-wishlist');
                button.find('.wishlist-text').text(typeof UCP_WishlistData !== 'undefined' ? UCP_WishlistData.remove_text : 'Remove from Wishlist');
            } else {
                button.removeClass('remove-from-wishlist').addClass('add-to-wishlist');
                button.find('.wishlist-text').text(typeof UCP_WishlistData !== 'undefined' ? UCP_WishlistData.add_text : 'Add to Wishlist');
            }
            
            // 触发状态更新事件，允许其他代码响应
            $(document).trigger('ucp_wishlist_button_updated', [button, inWishlist]);
        }
    };

    // 将队列系统附加到全局对象
    window.UCP_WishlistQueue = UCP_WishlistQueue;
    
    // 初始化后自动处理所有页面加载时的请求
    $(document).ready(function() {
        // 如果数据已经可用，自动初始化
        if (typeof UCP_WishlistData !== 'undefined') {
            UCP_WishlistQueue.init(
                UCP_WishlistData.ajax_url,
                UCP_WishlistData.nonce,
                UCP_WishlistData.page_id
            );
        }
    });

})(jQuery);
