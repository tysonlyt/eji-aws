<?php
return ['messages'=>['<script type="text/javascript">
jQuery(document).ready(function($) {
    // When clicking on \'.filters-button\', apply \'left: 0\' to \'.filters-wrapper\'
    $(\'.filters-button\').on(\'click\', function() {
        $(\'.filters-wrapper\').addClass(\'open\');
        $(\'.wpc-filters-overlay\').addClass(\'overlay\'); // Add overlay class to the body
    });
    
    $(\'.filters-close\').on(\'click\', function() {
        $(\'.filters-wrapper\').removeClass(\'open\');
        $(\'.wpc-filters-overlay\').removeClass(\'overlay\');
    });
    
    $(document).on(\'click\', \'.wpc-filters-overlay.overlay\', function() {
        $(\'.filters-wrapper\').removeClass(\'open\');
        $(\'.wpc-filters-overlay\').removeClass(\'overlay\');
    });
    
    // function changeButtonHref() {
    //     var button = $(\'.e-loop__load-more.elementor-button-wrapper .elementor-button-link\');
    //     if (button.is(\':visible\')) {
    //         button.attr(\'href\', \'javascript:void(0)\'); // Set the new URL
    //     }
    // }

    // // Check for the button visibility initially
    // changeButtonHref();

    // // Use a MutationObserver to detect changes in the DOM
    // var observer = new MutationObserver(function(mutations) {
    //     mutations.forEach(function(mutation) {
    //         changeButtonHref(); // Check visibility whenever there\'s a change
    //     });
    // });

    // // Start observing the target node for configured mutations
    // var targetNode = document.querySelector(\'#eji_product_archive\');
    // if (targetNode) {
    //     observer.observe(targetNode, { childList: true, subtree: true });
    // }
    
    $(\'.btn-4cols\').on(\'click\', function() {
       $(\'#eji_product_archive\').removeClass(\'columns-2\');
    });
    
    $(\'.btn-2cols\').on(\'click\', function() {
       $(\'#eji_product_archive\').addClass(\'columns-2\');
    });
});
</script>

<style type="text/css">


.wpc-filters-overlay {
    background: rgba(0, 0, 0, .3);
}

.wpc-filters-overlay.overlay {
    top: 0;
    opacity: 1;
}

.filters-wrapper.open {
    left: 0;
}

.filters-wrapper {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 100%; /* Start off-screen */
    width: 360px;
    background-color: #fff;
    box-shadow: 0 0 3px 3px rgba(0, 0, 0, .3);
    transition: left 0.3s;
    z-index: 100000;
    height: 100%;
    overflow: auto;
}
</style>'=>'<script type="text/javascript">
jQuery(document).ready(function($) {
    // When clicking on \'.filters-button\', apply \'left: 0\' to \'.filters-wrapper\'
    $(\'.filters-button\').on(\'click\', function() {
        $(\'.filters-wrapper\').addClass(\'open\');
        $(\'.wpc-filters-overlay\').addClass(\'overlay\'); // Add overlay class to the body
    });
    
    $(\'.filters-close\').on(\'click\', function() {
        $(\'.filters-wrapper\').removeClass(\'open\');
        $(\'.wpc-filters-overlay\').removeClass(\'overlay\');
    });
    
    $(document).on(\'click\', \'.wpc-filters-overlay.overlay\', function() {
        $(\'.filters-wrapper\').removeClass(\'open\');
        $(\'.wpc-filters-overlay\').removeClass(\'overlay\');
    });
    
    // function changeButtonHref() {
    //     var button = $(\'.e-loop__load-more.elementor-button-wrapper .elementor-button-link\');
    //     if (button.is(\':visible\')) {
    //         button.attr(\'href\', \'javascript:void(0)\'); // Set the new URL
    //     }
    // }

    // // Check for the button visibility initially
    // changeButtonHref();

    // // Use a MutationObserver to detect changes in the DOM
    // var observer = new MutationObserver(function(mutations) {
    //     mutations.forEach(function(mutation) {
    //         changeButtonHref(); // Check visibility whenever there\'s a change
    //     });
    // });

    // // Start observing the target node for configured mutations
    // var targetNode = document.querySelector(\'#eji_product_archive\');
    // if (targetNode) {
    //     observer.observe(targetNode, { childList: true, subtree: true });
    // }
    
    $(\'.btn-4cols\').on(\'click\', function() {
       $(\'#eji_product_archive\').removeClass(\'columns-2\');
    });
    
    $(\'.btn-2cols\').on(\'click\', function() {
       $(\'#eji_product_archive\').addClass(\'columns-2\');
    });
});
</script>

<style type="text/css">


.wpc-filters-overlay {
    background: rgba(0, 0, 0, .3);
}

.wpc-filters-overlay.overlay {
    top: 0;
    opacity: 1;
}

.filters-wrapper.open {
    left: 0;
}

.filters-wrapper {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 100%; /* Start off-screen */
    width: 360px;
    background-color: #fff;
    box-shadow: 0 0 3px 3px rgba(0, 0, 0, .3);
    transition: left 0.3s;
    z-index: 100000;
    height: 100%;
    overflow: auto;
}
</style>','Back to Top'=>'返回页首','Filters'=>'筛选','It seems we can’t find what you’re looking for.'=>'看来我们找不到您要找的东西。','Next'=>'下一页','Previous'=>'上一页','View More'=>'查看更多','[fe_widget]'=>'[fe_widget]','html-html-45fc48f'=>'<script type="text/javascript">
jQuery(document).ready(function($) {
    // When clicking on \'.filters-button\', apply \'left: 0\' to \'.filters-wrapper\'
    $(\'.filters-button\').on(\'click\', function() {
        $(\'.filters-wrapper\').addClass(\'open\');
        $(\'.wpc-filters-overlay\').addClass(\'overlay\'); // Add overlay class to the body
    });
    
    $(\'.filters-close\').on(\'click\', function() {
        $(\'.filters-wrapper\').removeClass(\'open\');
        $(\'.wpc-filters-overlay\').removeClass(\'overlay\');
    });
    
    $(document).on(\'click\', \'.wpc-filters-overlay.overlay\', function() {
        $(\'.filters-wrapper\').removeClass(\'open\');
        $(\'.wpc-filters-overlay\').removeClass(\'overlay\');
    });
    
    // function changeButtonHref() {
    //     var button = $(\'.e-loop__load-more.elementor-button-wrapper .elementor-button-link\');
    //     if (button.is(\':visible\')) {
    //         button.attr(\'href\', \'javascript:void(0)\'); // Set the new URL
    //     }
    // }

    // // Check for the button visibility initially
    // changeButtonHref();

    // // Use a MutationObserver to detect changes in the DOM
    // var observer = new MutationObserver(function(mutations) {
    //     mutations.forEach(function(mutation) {
    //         changeButtonHref(); // Check visibility whenever there\'s a change
    //     });
    // });

    // // Start observing the target node for configured mutations
    // var targetNode = document.querySelector(\'#eji_product_archive\');
    // if (targetNode) {
    //     observer.observe(targetNode, { childList: true, subtree: true });
    // }
    
    $(\'.btn-4cols\').on(\'click\', function() {
       $(\'#eji_product_archive\').removeClass(\'columns-2\');
    });
    
    $(\'.btn-2cols\').on(\'click\', function() {
       $(\'#eji_product_archive\').addClass(\'columns-2\');
    });
});
</script>

<style type="text/css">


.wpc-filters-overlay {
    background: rgba(0, 0, 0, .3);
}

.wpc-filters-overlay.overlay {
    top: 0;
    opacity: 1;
}

.filters-wrapper.open {
    left: 0;
}

.filters-wrapper {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 100%; /* Start off-screen */
    width: 360px;
    background-color: #fff;
    box-shadow: 0 0 3px 3px rgba(0, 0, 0, .3);
    transition: left 0.3s;
    z-index: 100000;
    height: 100%;
    overflow: auto;
}
</style>','nothing_found_message_text-loop-grid-b25efd6'=>'看来我们找不到您要找的东西。','pagination_next_label-loop-grid-b25efd6'=>'下一页','pagination_prev_label-loop-grid-b25efd6'=>'上一页','shortcode-shortcode-c86a8ee'=>'[fe_widget]','text-button-7b35739'=>'返回页首','text-button-eadaa5b'=>'筛选','text-loop-grid-b25efd6'=>'查看更多']];
