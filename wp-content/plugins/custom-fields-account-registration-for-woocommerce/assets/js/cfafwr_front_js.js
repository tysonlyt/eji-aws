jQuery(document).ready(function(){

	jQuery(".color_sepctrum").spectrum({
		color: "",
		preferredFormat: "hex",
	});

	// jQuery('.cfafwr_timepicker').timepicker();

    if(jQuery(".cfafwr_multiselect").length > 0){
    	jQuery('.cfafwr_multiselect').select2({
            ajax: {
                url: ajax_postajax.ajaxurl,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        action: 'cfafwr_multiselect_ajax',
                        selectid: jQuery(this).data('id'),
                        nonce: ajax_postajax.ajaxnonce
                    };
                },
                processResults: function( data ) {
                    var options = [];
                    if ( data ) {
                        jQuery.each( data, function( index, text ) {
                            options.push( { id: text[0], text: text[1]  } );
                        });
                    }
                    return {
                        results: options
                    };
                },
                cache: true
            },
            minimumInputLength: 0
        });
    };
});
