/**
 * Wishlist Admin Management JavaScript
 */
jQuery(document).ready(function($) {
    // 不使用任何模态框逻辑，因为我们已经在wishlist-manage-view.php中实现了无AJAX模态框
    // 这个文件现在只保留一些辅助函数
    
    /**
     * 渲染产品列表HTML
     * @param {Array} products - 产品对象数组
     * @return {string} HTML字符串
     */
    function renderProducts(products) {
        if (!products || !products.length) {
            return '<p>无产品数据</p>';
        }
        
        var html = '<ul class="ucp-product-list">';
        $.each(products, function(i, product) {
            html += '<li class="ucp-product-item">' +
                   '<div class="product-thumbnail">' +
                   (product.thumbnail ? '<img src="' + product.thumbnail + '" alt="' + product.name + '">' : '<span class="no-image">无图片</span>') +
                   '</div>' +
                   '<div class="product-info">' +
                   '<h4 class="product-title">' + product.name + '</h4>' +
                   '<div class="product-meta">' +
                   '<span class="product-sku">SKU: ' + (product.sku || 'N/A') + '</span>' +
                   '<span class="product-price">价格: ' + (product.price || 'N/A') + '</span>' +
                   '</div>' +
                   '</div>' +
                   '</li>';
        });
        html += '</ul>';
        return html;
    }
    
    // 此函数已移至前面，移除重复定义
});
