jQuery(function ($) {
    $(document).ready(function(){
        
       if($('.cdn-purge-all').length > 0){
            window.history.pushState('', '', $('.cdn-purge-all').val());
            $('.cdn-purge-all').remove();
       }
    });
});