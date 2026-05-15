jQuery(document).ready(function(){

    jQuery('.color_sepctrum').wpColorPicker();

    jQuery('ul.nav-tab-wrapper li').click(function(){
        var tab_id = jQuery(this).attr('data-tab');
        jQuery('ul.nav-tab-wrapper li').removeClass('nav-tab-active');
        jQuery('.tab-content').removeClass('current');
        jQuery(this).addClass('nav-tab-active');
        jQuery("#"+tab_id).addClass('current');
    });

    jQuery('form#your-profile').attr('enctype', 'multipart/form-data');

    var addButton = jQuery('.custom_add_options'); //Add button selector
    var wrapper = jQuery('.custom_field_option_body'); //Input field wrapper
    var fieldHTML = '<tr class="custom_field_option_tr"><td><input type="text" name="custom_field_option_value[]" placeholder="value" value=""></td><td><input type="text" name="custom_field_option_label[]" placeholder="label" value=""></td><td><span class="custom_remove_options"><img src="'+remove_icon.icon+'"></span></td></tr>';
    
    //Once add button is clicked
    jQuery(addButton).click(function(){
        jQuery(wrapper).append(fieldHTML);
    });
    
    //Once remove button is clicked
    jQuery(wrapper).on('click', '.custom_remove_options', function(e){
        e.preventDefault();
        jQuery(this).parent().parent().remove();
    });

    if(jQuery(".enable_email_section").is(":checked")){ 
        jQuery(".email_subject_and_body_message").show();
    }else{
        jQuery(".email_subject_and_body_message").hide();
    }

    jQuery(".enable_email_section").click(function() {
        if(jQuery(this).is(":checked")) {
            jQuery(".email_subject_and_body_message").fadeIn(300);
        } else {
            jQuery(".email_subject_and_body_message").fadeOut(200);
        }
    });

    if(jQuery(".cfafwr_login_reg_change_text").is(":checked")){ 
        jQuery(".cfafwr_log_reg").show();
    }else{
        jQuery(".cfafwr_log_reg").hide();
    }

    jQuery(".cfafwr_login_reg_change_text").click(function() {
        if(jQuery(this).is(":checked")) {
            jQuery(".cfafwr_log_reg").fadeIn(300);
        } else {
            jQuery(".cfafwr_log_reg").fadeOut(200);
        }
    });

    
    jQuery('.custom_field_type').on('change', function() {
        if (jQuery(this).val() !== null && jQuery(this).val() !== undefined && jQuery(this).val() !== '') {
            if ( jQuery(this).val() == 'radio' || jQuery(this).val() == 'select' || jQuery(this).val() == 'multiselect' || jQuery(this).val() == 'multicheckbox' ) {
                jQuery(".custom_html_sec").fadeOut(300);
                jQuery(".cfafwr_custom_class").fadeIn(300);
                jQuery(".multiple_options").fadeIn(300);
                jQuery(".custom_html").fadeIn(300);
                jQuery(".field_placeholder").fadeOut(300);
            } else if ( jQuery(this).val() == 'html' ) {
                jQuery(".custom_html_sec").fadeIn(300);
                jQuery(".cfafwr_custom_class").fadeIn(300);
                jQuery(".custom_html").fadeOut(300);
                jQuery(".multiple_options").fadeOut(300);
                jQuery(".field_placeholder").fadeOut(300);
            } else if ( jQuery(this).val().indexOf('billing') !== -1 || jQuery(this).val().indexOf('shipping') !== -1 ) {
                jQuery(".custom_html_sec").fadeOut(300);
                jQuery(".cfafwr_custom_class").fadeOut(300);
                jQuery(".custom_html").fadeOut(300);
                jQuery(".multiple_options").fadeOut(300);
                jQuery(".field_placeholder").fadeOut(300);
            } else if ( jQuery(this).val() == 'image' || jQuery(this).val() == 'color' || jQuery(this).val() == 'time' || jQuery(this).val() == 'date' || jQuery(this).val() == 'checkbox' ) {
                jQuery(".custom_html_sec").fadeOut(300);
                jQuery(".cfafwr_custom_class").fadeIn(300);
                jQuery(".custom_html").fadeIn(300);
                jQuery(".multiple_options").fadeOut(300);
                jQuery(".field_placeholder").fadeOut(300);
            } else if ( jQuery(this).val() == 'hidden' ) {
                jQuery(".custom_html_sec").fadeOut();
                jQuery(".cfafwr_custom_class").fadeIn();
                jQuery(".custom_html").fadeIn();
                jQuery(".multiple_options").fadeOut();
                jQuery(".field_placeholder").fadeOut();
                jQuery(".cusrequired").fadeOut();
            } else {
                jQuery(".custom_html_sec").fadeOut(300);
                jQuery(".cfafwr_custom_class").fadeIn(300);
                jQuery(".custom_html").fadeIn(300);
                jQuery(".multiple_options").fadeOut(300);
                jQuery(".field_placeholder").fadeIn(300);
            }
        } else {
            jQuery(".custom_html_sec").fadeOut(300);
            jQuery(".cfafwr_custom_class").fadeIn(300);
            jQuery(".custom_html").fadeIn(300);
            jQuery(".multiple_options").fadeOut(300);
            jQuery(".field_placeholder").fadeIn(300);
        }
    });

    jQuery('.custom_field_type').each(function(){
        var custom_field_type = jQuery(this).val();
        if (custom_field_type !== null && custom_field_type !== undefined && custom_field_type !== '') {
            if ( custom_field_type == 'radio' || custom_field_type == 'select' || custom_field_type == 'multicheckbox' || custom_field_type == 'multiselect' ) {
                jQuery(".custom_html_sec").hide();
                jQuery(".cfafwr_custom_class").show();
                jQuery(".multiple_options").show();
                jQuery(".custom_html").show();
                jQuery(".field_placeholder").hide();
            } else if ( custom_field_type == 'html' ) {
                jQuery(".custom_html_sec").show();
                jQuery(".cfafwr_custom_class").show();
                jQuery(".custom_html").hide();
                jQuery(".multiple_options").hide();
                jQuery(".field_placeholder").hide();
            } else if ( custom_field_type.indexOf('billing') !== -1 || custom_field_type.indexOf('shipping') !== -1 ) {
                jQuery(".custom_html_sec").hide();
                jQuery(".cfafwr_custom_class").hide();
                jQuery(".custom_html").hide();
                jQuery(".multiple_options").hide();
                jQuery(".field_placeholder").hide();
            } else if ( custom_field_type == 'image' || custom_field_type == 'color' || custom_field_type == 'time' || custom_field_type == 'date' || custom_field_type == 'checkbox' ) {
                jQuery(".custom_html_sec").hide();
                jQuery(".cfafwr_custom_class").show();
                jQuery(".custom_html").show();
                jQuery(".multiple_options").hide();
                jQuery(".field_placeholder").hide();
            } else if ( custom_field_type == 'hidden' ) {
                jQuery(".custom_html_sec").hide();
                jQuery(".cfafwr_custom_class").show();
                jQuery(".custom_html").show();
                jQuery(".multiple_options").hide();
                jQuery(".field_placeholder").hide();
                jQuery(".cusrequired").hide();
            } else {
                jQuery(".custom_html_sec").hide();
                jQuery(".cfafwr_custom_class").show();
                jQuery(".custom_html").show();
                jQuery(".multiple_options").hide();
                jQuery(".field_placeholder").show();
            }        
        } else {
            jQuery(".custom_html_sec").hide();
            jQuery(".cfafwr_custom_class").show();
            jQuery(".custom_html").show();
            jQuery(".multiple_options").hide();
            jQuery(".field_placeholder").show();
        }
    });


    jQuery('.cfafwr_dl_data').sortable({
        update: function( event, ui ) {
            var value = new Array();
            jQuery('ul.cfafwr_dl_data li').each(function() {
                value.push(jQuery(this).find(".cfafwr_add_new_fields_inner").attr('value'));
            });

            var cfafwr_drop_index = new Array();
            jQuery('ul.cfafwr_dl_data li').each(function() {
                cfafwr_drop_index.push(jQuery(this).find('.cfafwr_add_new_fields_inner').attr("id"));
            });
            
            jQuery.ajax({
                type :'POST',       
                url  : ajaxurl,
                data :{
                    'action'  : 'cfafwr_filed_sortable',
                    'post_meta'    : value,
                    'nonce': remove_icon.ajaxnonce
                },
                success: function(result){

                }
            });
        }
    });

    jQuery('.cfafwr_multiselect').select2({
        ajax: {
            url: ajaxurl,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    action: 'cfafwr_multiselect_ajax',
                    selectid: jQuery(this).data('id'),
                    nonce: remove_icon.ajaxnonce
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
    
});