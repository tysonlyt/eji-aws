<?php

	function CFAFWR_registration_form_field() {
        global $cfafwr_comman,$wp_roles;

        $myargs = array(
           'post_type' => 'wporg_custom_field', 
           'posts_per_page' => -1, 
           'meta_key' => 'cfafwr_field_ajax_id', 
           'orderby' => 'meta_value_num', 
           'order' => 'ASC'
        );
        $posts = get_posts($myargs);
        
        if(!empty($posts)){
            foreach ($posts as $key => $post_id) {
                $custom_field_label = get_post_meta($post_id->ID,'custom_field_label',true);
                $custom_field_slug_name = get_post_meta($post_id->ID,'custom_field_slug_name',true);
                $custom_register_field_type = get_post_meta($post_id->ID,'custom_register_field_type',true);
                $custom_field_required = get_post_meta($post_id->ID,'custom_field_required',true);
                $custom_field_size = get_post_meta($post_id->ID,'custom_field_size',true);
                $cfafwr_custom_html = get_post_meta($post_id->ID,'cfafwr_custom_html',true);
                $custom_field_placeholder = get_post_meta($post_id->ID,'custom_field_placeholder',true);
                $cfafwr_add_custom_class = get_post_meta($post_id->ID,'cfafwr_add_custom_class',true);
                $bill_ship = explode('_', $custom_register_field_type);
                if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes' && $custom_register_field_type != 'radio' && $custom_register_field_type != 'select' && $custom_register_field_type != 'textarea' && $custom_register_field_type != 'checkbox' && $custom_register_field_type != 'image' && $custom_register_field_type != 'country' && $custom_register_field_type != 'html'  && $custom_register_field_type != 'color' && $custom_register_field_type != 'multicheckbox' && $custom_register_field_type != 'multiselect' && $bill_ship[0] !== 'billing' && $bill_ship[0] !== 'shipping' ){
                    $adclass = '';
                    if ($custom_register_field_type == 'time') {
                        $adclass = ' cfafwr_timepicker';
                        // $custom_register_field_type = 'text';
                    }
                    ?>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide custom_register_field<?php echo ' '.esc_attr($cfafwr_add_custom_class);?>" 
                        style="display: inline-block;width: <?php if($custom_field_size == 'full_width'){echo '100%';}elseif($custom_field_size == 'half_width'){echo '49%';}?>;">
                        <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>" style="<?php if($cfafwr_comman['cfafwr_hide_field_labels'] == 'yes'){echo 'display: none;';}?>">
                            <?php echo esc_html($custom_field_label); ?>
                            <?php if($custom_field_required == 'yes'){ ?>
                                <span class="required">*</span>
                            <?php } ?>
                        </label>
                        <input type="<?php echo esc_attr($custom_register_field_type);?>" 
                            class="woocommerce-Input woocommerce-Input--text input-text <?php echo esc_attr( $adclass ); ?>" 
                            placeholder="<?php echo esc_attr($custom_field_placeholder);?>" 
                            name="<?php echo esc_attr($custom_field_slug_name);?>" 
                            id="reg_<?php echo esc_attr($custom_field_slug_name);?>" 
                            value="<?php if ( ! empty( $_POST[$custom_field_slug_name] ) ) echo esc_attr( $_POST[$custom_field_slug_name] ); ?>" 
                        />
                    </p>
                    <?php
                }elseif($custom_register_field_type == 'radio'){
                    if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                        ?>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide custom_register_field<?php echo ' '.esc_attr($cfafwr_add_custom_class);?>" 
                            style="display: inline-block;width: <?php if($custom_field_size == 'full_width'){echo '100%';}elseif($custom_field_size == 'half_width'){echo '49%';}?>;">
                            <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>" style="<?php if($cfafwr_comman['cfafwr_hide_field_labels'] == 'yes'){echo 'display: none;';}?>">
                                <?php echo esc_html($custom_field_label); ?>
                                <?php if($custom_field_required == 'yes'){ ?>
                                    <span class="required">*</span>
                                <?php } ?>
                            </label>
                            <?php
                            $custom_field_option_value = get_post_meta($post_id->ID,'custom_field_option_value',true);
                            $custom_field_option_label = get_post_meta($post_id->ID,'custom_field_option_label',true);
                            foreach ($custom_field_option_value as $key => $value) {
                                ?>
                                <label for="reg_<?php echo esc_attr($value);?>">
                                    <input type="<?php echo esc_attr($custom_register_field_type);?>" 
                                        class="woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>" 
                                        name="<?php echo esc_attr($custom_field_slug_name);?>" 
                                        id="reg_<?php echo esc_attr($value);?>" 
                                        value="<?php echo esc_attr($value);?>"
                                        <?php if (isset($_POST[$custom_field_slug_name]) && $_POST[$custom_field_slug_name] == $value ) echo "checked"; ?> 
                                    />
                                    <?php echo esc_html($custom_field_option_label[$key]);?>
                                </label>
                                <?php
                            } ?>
                        </p>
                        <?php
                    }
                }elseif($custom_register_field_type == 'select'){
                    if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                        ?>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide custom_register_field<?php echo ' '.esc_attr($cfafwr_add_custom_class);?>" 
                            style="display: inline-block;width: <?php if($custom_field_size == 'full_width'){echo '100%';}elseif($custom_field_size == 'half_width'){echo '49%';}?>;">
                            <label style="<?php if($cfafwr_comman['cfafwr_hide_field_labels'] == 'yes'){echo 'display: none;';}?>">
                                <?php echo esc_html($custom_field_label); ?>
                                <?php if($custom_field_required == 'yes'){ ?>
                                    <span class="required">*</span>
                                <?php } ?>
                            </label>
                            <select name="<?php echo esc_attr($custom_field_slug_name);?>" class="woocommerce-Input woocommerce-Input--text input-text">
                                <option value="">Select Option</option>
                                <?php
                                $custom_field_option_value = get_post_meta($post_id->ID,'custom_field_option_value',true);
                                $custom_field_option_label = get_post_meta($post_id->ID,'custom_field_option_label',true);
                                foreach ($custom_field_option_value as $key => $value) {
                                    ?>
                                    <option value="<?php echo esc_attr($value);?>" <?php if (isset($_POST[$custom_field_slug_name]) && sanitize_text_field($_POST[$custom_field_slug_name]) == $value ) echo "selected"; ?>>
                                        <?php echo esc_html($custom_field_option_label[$key]);?> 
                                    </option>
                                    <?php
                                } ?>
                            </select>
                        </p>
                        <?php
                    }
                }elseif($custom_register_field_type == 'textarea'){
                    if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                        ?>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide custom_register_field<?php echo ' '.esc_attr($cfafwr_add_custom_class);?>" 
                            style="display: inline-block;width: <?php if($custom_field_size == 'full_width'){echo '100%';}elseif($custom_field_size == 'half_width'){echo '49%';}?>;">
                            <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>" style="<?php if($cfafwr_comman['cfafwr_hide_field_labels'] == 'yes'){echo 'display: none;';}?>">
                                <?php echo esc_html($custom_field_label); ?>    
                                <?php if($custom_field_required == 'yes'){ ?>
                                    <span class="required">*</span>
                                <?php } ?>
                            </label>
                            <textarea name="<?php echo esc_attr($custom_field_slug_name);?>" placeholder="<?php echo esc_attr($custom_field_placeholder);?>" id="reg_<?php echo esc_attr($custom_field_slug_name);?>" class="woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>"><?php if ( !empty( $_POST[$custom_field_slug_name] ) ) echo esc_html($_POST[$custom_field_slug_name]); ?></textarea>
                        </p>
                        <?php
                    }
                }elseif($custom_register_field_type == 'checkbox'){
                    if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                        ?>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide custom_register_field<?php echo ' '.esc_attr($cfafwr_add_custom_class);?>" 
                            style="display: inline-block;width: <?php if($custom_field_size == 'full_width'){echo '100%';}elseif($custom_field_size == 'half_width'){echo '49%';}?>;">
                            <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>" style="<?php if($cfafwr_comman['cfafwr_hide_field_labels'] == 'yes'){echo 'display: none;';}?>">
                                <?php echo esc_html($custom_field_label); ?>
                                <?php if($custom_field_required == 'yes'){ ?>
                                    <span class="required">*</span>
                                <?php } ?>
                            </label>
                            <input type="<?php echo esc_attr($custom_register_field_type);?>" 
                                class="woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>" placeholder="<?php echo esc_attr($custom_field_placeholder);?>" 
                                name="<?php echo esc_attr($custom_field_slug_name);?>" 
                                id="reg_<?php echo esc_attr($custom_field_slug_name);?>" 
                                value="yes"<?php if (isset($_POST[$custom_field_slug_name]) && $_POST[$custom_field_slug_name] == 'yes' ) echo "checked"; ?> 
                            />
                        </p>
                        <?php
                    }
                }elseif($custom_register_field_type == 'image'){
                    if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                        ?>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide custom_register_field<?php echo ' '.esc_attr($cfafwr_add_custom_class);?>" style="display: inline-block;width: <?php if($custom_field_size == 'full_width'){echo '100%';}elseif($custom_field_size == 'half_width'){echo '49%';}?>;">
                            <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>" style="<?php if($cfafwr_comman['cfafwr_hide_field_labels'] == 'yes'){echo 'display: none;';}?>"><?php echo esc_html($custom_field_label); ?>
                                <?php if($custom_field_required == 'yes'){ ?>
                                    <span class="required">*</span>
                                <?php } ?>
                            </label>
                            <input type="file" 
                                class="woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>" 
                                placeholder="<?php echo esc_attr($custom_field_placeholder);?>" 
                                name="<?php echo esc_attr($custom_field_slug_name);?>" 
                                id="reg_<?php echo esc_attr($custom_field_slug_name);?>" 
                                accept='image/*,.pdf, .doc, .docx, .ppt, .pptx, .pps, .ppsx, .odt, .xls, .xlsx, .PSD,audio/*,video/*' 
                                style="max-width: 100%;"
                            />
                        </p>
                        <?php 
                    }
                }elseif($custom_register_field_type == 'html'){
                    if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                        ?>
                        <div class="custom_register_field<?php echo ' '.esc_attr($cfafwr_add_custom_class);?>">
                            <?php echo wp_kses_post($cfafwr_custom_html);?>
                        </div>
                        <?php
                    }
                }elseif($custom_register_field_type == 'color'){
                    if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                        ?>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide custom_register_field<?php echo ' '.esc_attr($cfafwr_add_custom_class);?>" 
                            style="display: inline-block;width: <?php if($custom_field_size == 'full_width'){echo '100%';}elseif($custom_field_size == 'half_width'){echo '49%';}?>;">
                            <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>" style="<?php if($cfafwr_comman['cfafwr_hide_field_labels'] == 'yes'){echo 'display: none;';}?>">
                                <?php echo esc_html($custom_field_label); ?>
                                <?php if($custom_field_required == 'yes'){ ?>
                                    <span class="required">*</span>
                                <?php } ?>
                            </label>
                            <input type="text" 
                                class="color_sepctrum woocommerce-Input woocommerce-Input--text input-text" 
                                placeholder="<?php echo esc_attr($custom_field_placeholder);?>" 
                                name="<?php echo esc_attr($custom_field_slug_name);?>" 
                                id="reg_<?php echo esc_attr($custom_field_slug_name);?>" 
                                value="<?php if ( ! empty( $_POST[$custom_field_slug_name] ) ) echo esc_attr( $_POST[$custom_field_slug_name] ); ?>" 
                            />
                        </p>
                        <?php
                    }
                }elseif($custom_register_field_type == 'multicheckbox'){
                    if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                        ?>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide custom_register_field<?php echo ' '.esc_attr($cfafwr_add_custom_class);?>" 
                            style="display: inline-block;width: <?php if($custom_field_size == 'full_width'){echo '100%';}elseif($custom_field_size == 'half_width'){echo '49%';}?>;">
                            <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>" style="<?php if($cfafwr_comman['cfafwr_hide_field_labels'] == 'yes'){echo 'display: none;';}?>">
                                <?php echo esc_html($custom_field_label); ?>
                                <?php if($custom_field_required == 'yes'){ ?>
                                    <span class="required">*</span>
                                <?php } ?>
                            </label>
                            <?php
                            $custom_field_option_value = get_post_meta($post_id->ID,'custom_field_option_value',true);
                            $custom_field_option_label = get_post_meta($post_id->ID,'custom_field_option_label',true);
                            foreach ($custom_field_option_value as $key => $value) {
                                ?>
                                <span class="multi_checkboxes">
                                    <input type="checkbox" 
                                        class="woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type); ?> input-<?php echo esc_attr($custom_register_field_type); ?>" 
                                        placeholder="<?php echo esc_attr($custom_field_placeholder);?>" 
                                        name="<?php echo esc_attr($custom_field_slug_name);?>[]" 
                                        id="<?php echo esc_attr($value);?>" 
                                        value="<?php echo esc_attr($value);?>" 
                                        <?php if (isset($_POST[$custom_field_slug_name]) && in_array($value, $_POST[$custom_field_slug_name])) echo "checked"; ?> 
                                    />
                                    <label class="multi_checklabel" for="<?php echo esc_attr($value);?>">
                                        <?php echo esc_html($custom_field_option_label[$key]);?> 
                                    </label>
                                </span>
                                <?php
                            } ?>
                        </p>
                        <?php
                    }
                }elseif($custom_register_field_type == 'multiselect'){
                    if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                        ?>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide custom_register_field<?php echo ' '.esc_attr($cfafwr_add_custom_class);?>" 
                            style="display: inline-block;width: <?php if($custom_field_size == 'full_width'){echo '100%';}elseif($custom_field_size == 'half_width'){echo '49%';}?>;">
                            <label style="<?php if($cfafwr_comman['cfafwr_hide_field_labels'] == 'yes'){echo 'display: none;';}?>">
                                <?php echo esc_html($custom_field_label); ?>
                                <?php if($custom_field_required == 'yes'){ ?>
                                    <span class="required">*</span>
                                <?php } ?>
                            </label>
                            <select name="<?php echo esc_attr($custom_field_slug_name);?>[]" 
                                class="cfafwr_multiselect woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>" 
                                multiple="multiple" 
                                data-id="<?php echo esc_attr($post_id->ID);?>">
                                <?php
                                $custom_field_option_value = get_post_meta($post_id->ID,'custom_field_option_value',true);
                                $custom_field_option_label = get_post_meta($post_id->ID,'custom_field_option_label',true);
                                foreach ($custom_field_option_value as $key => $value) {
                                    $selected = '';
                                    if (!empty($_POST[$custom_field_slug_name]) && isset($_POST[$custom_field_slug_name])) {
                                        if (in_array($value, $_POST[$custom_field_slug_name])) {
                                            $selected = 'selected';
                                        }
                                    }
                                    ?>
                                    <option value="<?php echo esc_attr($value);?>" <?php echo esc_attr($selected);?>> 
                                        <?php echo esc_html($custom_field_option_label[$key]);?> 
                                    </option>
                                    <?php
                                } ?>
                            </select>
                        </p>
                        <?php
                    }
                }elseif($bill_ship[0] == 'billing'){
                    if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                        $billaddress_fields = wc()->countries->get_address_fields(get_user_meta(get_current_user_id(), 'billing_country', true));
                        foreach ($billaddress_fields as $key => $field) {
                            if ($key == $custom_register_field_type) {
                                woocommerce_form_field($key, $field, wc_get_post_data_by_key($key));
                            }
                        }
                    }
                }elseif($bill_ship[0] == 'shipping'){
                    if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                        $countries = new WC_Countries();
                        if ( ! isset( $country ) ) {
                            $country = $countries->get_base_country();
                        }
                        $shipaddress_fields = WC()->countries->get_address_fields( $country, 'shipping_' );
                        foreach ($shipaddress_fields as $key => $field) {
                            if ($key == $custom_register_field_type) {
                                woocommerce_form_field($key, $field, wc_get_post_data_by_key($key));
                            }
                        }
                    }
                }
            }
        }
	}

    function CFAFWR_enctype_custom_registration_forms() {
       echo 'enctype="multipart/form-data"';
    }

    function CFAFWR_wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
        global $cfafwr_comman;

        $all_post_ids = get_posts(array(
            'fields'          => 'ids',
            'posts_per_page'  => -1,
            'post_type' => 'wporg_custom_field'
        ));

        if(!empty($all_post_ids)){
            foreach ($all_post_ids as $key => $post_id) {
                $custom_field_label = get_post_meta($post_id,'custom_field_label',true);
                $custom_field_slug_name = get_post_meta($post_id,'custom_field_slug_name',true);
                $custom_register_field_type = get_post_meta($post_id,'custom_register_field_type',true);
                $custom_field_required = get_post_meta($post_id,'custom_field_required',true);
                $custom_field_size = get_post_meta($post_id,'custom_field_size',true);
                $custom_field_placeholder = get_post_meta($post_id,'custom_field_placeholder',true);
                $bill_ship = explode('_', $custom_register_field_type);
                if( get_post_meta($post_id,"custom_field_checkbox",true) == 'yes' ){                        
                   if($custom_register_field_type == "image"){
                        if ( empty($_FILES[$custom_field_slug_name]['name']) ) {
                            if($custom_field_required == 'yes'){
                                $req_msg = str_replace("{field_label}",$custom_field_label,$cfafwr_comman['cfafwr_field_label_require_text']);
                                $validation_errors->add( $custom_field_slug_name.'_error', __( $req_msg, 'woocommerce' ) );
                            } 
                        }
                    }elseif($custom_register_field_type == "multicheckbox"){
                        if ( empty($_POST[$custom_field_slug_name]) ) {
                            if($custom_field_required == 'yes'){
                                $req_msg = str_replace("{field_label}",$custom_field_label,$cfafwr_comman['cfafwr_field_label_require_text']);
                                $validation_errors->add( $custom_field_slug_name.'_error', __( $req_msg, 'woocommerce' ) );
                            } 
                        }
                    }elseif($bill_ship[0] == 'billing'){
                        $billaddress_fields = wc()->countries->get_address_fields(get_user_meta(get_current_user_id(), 'billing_country', true));
                        foreach ($billaddress_fields as $billingkey => $billvalue) {
                            if ($billingkey == $custom_register_field_type) {
                                if ( empty( $_POST[$billingkey] )){
                                    if ($billvalue['required'] == 1) {
                                        $req_msg = str_replace("{field_label}",$billvalue['label'],$cfafwr_comman['cfafwr_field_label_require_text']);
                                        $validation_errors->add( $billingkey.'_error', __( $req_msg, 'woocommerce' ) );
                                    }
                                }
                            }
                        }
                    }elseif($bill_ship[0] == 'shipping'){
                        $countries = new WC_Countries();
                        if ( ! isset( $country ) ) {
                            $country = $countries->get_base_country();
                        }
                        $shipaddress_fields = WC()->countries->get_address_fields( $country, 'shipping_' );
                        foreach ($shipaddress_fields as $shipkey => $shipvalue) {
                            if ($shipkey == $custom_register_field_type) {
                                if ( empty( $_POST[$shipkey] )){
                                    if ($shipvalue['required'] == 1) {
                                        $req_msg = str_replace("{field_label}",$shipvalue['label'],$cfafwr_comman['cfafwr_field_label_require_text']);
                                        $validation_errors->add( $shipkey.'_error', __( $req_msg, 'woocommerce' ) );
                                    }
                                }
                            }
                        }
                    }else{
                        if ( empty( $_POST[$custom_field_slug_name] )  ) {
                            if($custom_field_required == 'yes'){
                                $req_msg = str_replace("{field_label}",$custom_field_label,$cfafwr_comman['cfafwr_field_label_require_text']);
                                $validation_errors->add( $custom_field_slug_name.'_error', __( $req_msg, 'woocommerce' ) ); 
                            }
                        }
                    }
                }
            }
        }
        return $validation_errors;
    }

    // For Sanitize Text Field Or Array
    function cfafw_recursive_sanitize_text_field($input) {
    if (is_array($input)) {
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $input[$key] = cfafw_recursive_sanitize_text_field($value);
            } else {
                $input[$key] = sanitize_text_field($value);
            }
        }
    } else {
        $input = sanitize_text_field($input); 
    }
    return $input;
}


    function CFAFWR_wooc_save_extra_register_fields( $customer_id ) {
        global $cfafwr_comman;
        $all_post_ids = get_posts(array(
            'fields'          => 'ids',
            'posts_per_page'  => -1,
            'post_type' => 'wporg_custom_field'
        ));
        
        if(!empty($all_post_ids)){
            foreach ($all_post_ids as $key => $post_id) { 
                $custom_field_slug_name = get_post_meta($post_id,'custom_field_slug_name',true);           
                $custom_register_field_type = get_post_meta($post_id,'custom_register_field_type',true);
                $bill_ship = explode('_', $custom_register_field_type);
                $billaddress_fields = wc()->countries->get_address_fields(get_user_meta(get_current_user_id(), 'billing_country', true));
                if (get_post_meta($post_id,"custom_field_checkbox",true) == 'yes' && $bill_ship[0] == 'billing') {
                    foreach ($billaddress_fields as $billingkey => $billvalue) {
                        if ($billingkey == $custom_register_field_type) {
                            if ( isset( $_POST[$billingkey] ) && !empty( $_POST[$billingkey] )){
                                update_user_meta( $customer_id, $billingkey, sanitize_text_field($_POST[$billingkey]) );
                            }
                        }
                    }
                }

                $countries = new WC_Countries();
                if ( ! isset( $country ) ) {
                    $country = $countries->get_base_country();
                }
                $shipaddress_fields = WC()->countries->get_address_fields( $country, 'shipping_' );
                if (get_post_meta($post_id,"custom_field_checkbox",true) == 'yes' && $bill_ship[0] == 'shipping') {
                    foreach ($shipaddress_fields as $shipkey => $shipvalue) {
                        if ($shipkey == $custom_register_field_type) {
                            if ( isset( $_POST[$shipkey] ) && !empty( $_POST[$shipkey] )){
                                update_user_meta( $customer_id, $shipkey, sanitize_text_field($_POST[$shipkey]) );
                            }
                        }
                    }
                }

                if ( isset( $_POST[$custom_field_slug_name] ) && get_post_meta($post_id,"custom_field_checkbox",true) == 'yes' ) {
                    if ($custom_register_field_type != 'multicheckbox' && $custom_register_field_type != 'multiselect') {
                        update_user_meta( $customer_id, $custom_field_slug_name, cfafw_recursive_sanitize_text_field($_POST[$custom_field_slug_name]));
                    }elseif ($custom_register_field_type == 'multicheckbox' || $custom_register_field_type == 'multiselect') {
                        update_user_meta( $customer_id, $custom_field_slug_name, cfafw_recursive_sanitize_text_field( $_POST[$custom_field_slug_name]));
                    }
                }elseif ( !isset( $_POST[$custom_field_slug_name] ) && get_post_meta($post_id,"custom_field_checkbox",true) == 'yes' ) {
                    if ($custom_register_field_type == 'multicheckbox' || $custom_register_field_type == 'multiselect') {
                        update_user_meta( $customer_id, $custom_field_slug_name, '' );
                    }
                }
               
                if($custom_register_field_type == "image"){
                    if ( isset( $_FILES[$custom_field_slug_name] ) && $_FILES[$custom_field_slug_name]['error'] == 0) {
                        require_once( ABSPATH . 'wp-admin/includes/image.php' );
                        require_once( ABSPATH . 'wp-admin/includes/file.php' );
                        require_once( ABSPATH . 'wp-admin/includes/media.php' );
                        $attachment_id = media_handle_upload( $custom_field_slug_name, 0 );  
                        if (!empty($attachment_id) ) {
                            update_user_meta( $customer_id, $custom_field_slug_name, $attachment_id );
                        }else{
                            update_user_meta( $customer_id,  $custom_field_slug_name, NULL);
                        }
                    }
                }
            }
        }
    }

    function CFAFWR_send_welcome_email_to_new_user($user_id) {
        global $cfafwr_comman;
        $myargs = array(
           'post_type' => 'wporg_custom_field', 
           'posts_per_page' => -1, 
           'meta_key' => 'cfafwr_field_ajax_id', 
           'orderby' => 'meta_value_num', 
           'order' => 'ASC'
        );
        $posts = get_posts($myargs );
        if(!empty($posts)){
            foreach ($posts as $key => $post_id) {
                $custom_field_slug_name = get_post_meta($post_id->ID,'custom_field_slug_name',true);
                $custom_register_field_type = get_post_meta($post_id->ID,'custom_register_field_type',true);
                if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes' && $custom_register_field_type == 'password' ){
                    wp_set_password( sanitize_text_field($_POST[$custom_field_slug_name]), $user_id );
                }
            }
        }
        if ($cfafwr_comman['cfafwr_user_email_sent'] == 'yes') {
            $user = get_userdata($user_id);
            $user_email = $user->user_email;

            if (!empty($cfafwr_comman['cfafwr_user_email_subject_msg'])) {
                $sub_message = $cfafwr_comman['cfafwr_user_email_subject_msg'];
            }else{
                $sub_message = __('Your account has been created succefully.','custom-fields-account-for-woocommerce-registration');
            }
            if (!empty($cfafwr_comman['cfafwr_user_email_body_msg'])) {
                $msg_b = str_replace("{site_name}",get_bloginfo( 'name' ),$cfafwr_comman['cfafwr_user_email_body_msg']);
                $body_message = $msg_b;
            }else{
                $msg_b1 = str_replace("{site_name}",get_bloginfo( 'name' ),__('Thanks for creating an account on {site_name}.','custom-fields-account-for-woocommerce-registration'));
                $body_message = $msg_b1;
            }

            $to = $user_email;
            $subject = $sub_message;
            $body = $body_message;
            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail($to, $subject, $body, $headers);
        }
    }

    function CFAFWR_translate_woocommerce_strings( $translated ,$untranslated, $domain ) {
        global $cfafwr_comman;
        if($cfafwr_comman['cfafwr_login_reg_change_text'] == 'yes'){
            if ( ! is_admin() && 'woocommerce' === $domain ) {
                switch ( $translated ) {         
                    case 'Login':         
                        $translated = $cfafwr_comman['cfafwr_login_change_text'];
                    break;         
                    case 'Register':         
                        $translated = $cfafwr_comman['cfafwr_reg_change_text'];
                    break;
                }
            }                
        } 
         return $translated;     
    }

    function CFAFWR_oc_custom_fields_query_vars( $vars ) {
        $vars[] = 'oc-custom-fields';
        return $vars;
    }

    function CFAFWR_add_oc_custom_fields_link_my_account( $items ) {
        global $cfafwr_comman;
        $items['oc-custom-fields'] = $cfafwr_comman['cfafwr_myac_tab_title'];
        return $items;
    }          
      
    function CFAFWR_oc_custom_fields_content() {
        global $cfafwr_comman;
        $user_id = get_current_user_id();
        $myargs = array(
           'post_type' => 'wporg_custom_field', 
           'posts_per_page' => -1, 
           'meta_key' => 'cfafwr_field_ajax_id', 
           'orderby' => 'meta_value_num', 
           'order' => 'ASC'
        );
        $posts = get_posts($myargs);

        if(!empty($posts)){
            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'cfafwr_save_occf') {
                wc_add_notice(__('Your Fields Saved Successfully!', 'custom-fields-account-for-woocommerce-registration'), 'success');
            }
               
            // Display WooCommerce notices at the top of the form
            if (function_exists('wc_print_notices')) {
                wc_print_notices();     
            }                
            ?>
            <h3 class="heading"><?php echo esc_html($cfafwr_comman['cfafwr_myac_tab_form_head']);?></h3>
            <form method="POST" enctype="multipart/form-data">
                <?php wp_nonce_field( 'cfafwr_save_occf_nonce', 'nonce' ); ?>
                <table class="form-table oc-custom-class">
                    <!-- Pending from here... -->
                    <?php
                    foreach ($posts as $key => $post_id) {
                        $custom_field_label = get_post_meta($post_id->ID,'custom_field_label',true);
                        $custom_field_slug_name = get_post_meta($post_id->ID,'custom_field_slug_name',true);
                        $custom_register_field_type = get_post_meta($post_id->ID,'custom_register_field_type',true);
                        $custom_field_required = get_post_meta($post_id->ID,'custom_field_required',true);
                        $custom_field_size = get_post_meta($post_id->ID,'custom_field_size',true);
                        $cfafwr_custom_html = get_post_meta($post_id->ID,'cfafwr_custom_html',true);
                        $cfafwr_add_custom_class = get_post_meta($post_id->ID,'cfafwr_add_custom_class',true);
                        $custom_field_placeholder = get_post_meta($post_id->ID,'custom_field_placeholder',true);
                        $bill_ship = explode('_', $custom_register_field_type);

                        $custom_field_value = get_user_meta( $user_id, $custom_field_slug_name, true );
                        
                        if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes' && $custom_register_field_type != 'radio' && $custom_register_field_type != 'select' && $custom_register_field_type != 'textarea' && $custom_register_field_type != 'checkbox' && $custom_register_field_type != 'image' && $custom_register_field_type != 'password' && $custom_register_field_type != 'country' && $custom_register_field_type != 'html' && $custom_register_field_type != 'color' && $custom_register_field_type != 'multicheckbox' && $custom_register_field_type != 'multiselect' && $bill_ship[0] !== 'billing' && $bill_ship[0] !== 'shipping' ){
                            $adclass = '';                            
                            if ($custom_register_field_type == 'time') {
                                $adclass = ' cfafwr_timepicker';
                                // $custom_register_field_type = 'text';
                            }
                            ?>
                            <tr class="<?php echo esc_attr($cfafwr_add_custom_class);?>" >
                                <th>
                                    <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>">
                                        <?php echo esc_html($custom_field_label); ?> 
                                    </label>
                                </th>
                                <td>
                                    <input type="<?php echo esc_attr($custom_register_field_type);?>" 
                                        class="woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type.$adclass);?>" 
                                        placeholder="<?php echo esc_attr($custom_field_placeholder);?>" 
                                        name="<?php echo esc_attr($custom_field_slug_name);?>" 
                                        id="reg_<?php echo esc_attr($custom_field_slug_name);?>" 
                                        value="<?php echo !empty($custom_field_value) && !is_array($custom_field_value) ? esc_attr($custom_field_value) : ''; ?>" 
                                        style="width: 100%;" 
                                    />
                                </td>
                            </tr>
                            </p>
                            <?php
                        }elseif($custom_register_field_type == 'radio'){
                            if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes' ){
                                ?>
                                <tr class="<?php echo esc_attr($cfafwr_add_custom_class);?>">
                                    <th>
                                        <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>">
                                            <?php echo esc_html($custom_field_label); ?> 
                                        </label>
                                    </th>
                                    <td>
                                        <?php
                                        $custom_field_option_value = get_post_meta($post_id->ID,'custom_field_option_value',true);
                                        $custom_field_option_label = get_post_meta($post_id->ID,'custom_field_option_label',true);
                                        foreach ($custom_field_option_value as $key => $value) {
                                            ?>
                                            <label for="reg_<?php echo esc_attr($value);?>">
                                                <input type="<?php echo esc_attr($custom_register_field_type);?>" 
                                                    class="woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>" 
                                                    name="<?php echo esc_attr($custom_field_slug_name);?>" 
                                                    id="reg_<?php echo esc_attr($value);?>" 
                                                    value="<?php echo esc_attr($value);?>"
                                                    <?php if ( !empty($custom_field_value) && !is_array($custom_field_value) && $custom_field_value == $value ) echo "checked"; ?> 
                                                />
                                                <?php echo esc_html($custom_field_option_label[$key]);?> 
                                            </label>
                                            <?php
                                        } ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }elseif($custom_register_field_type == 'select'){
                            if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                                ?>
                                <tr class="<?php echo esc_attr($cfafwr_add_custom_class);?>">
                                    <th>
                                        <label><?php echo esc_html($custom_field_label); ?></label>
                                    </th>
                                    <td>
                                        <select name="<?php echo esc_attr($custom_field_slug_name); ?>" 
                                            class="woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>" 
                                            style="width: 100%;">
                                            <option>Select Option</option>
                                            <?php
                                            $custom_field_option_value = get_post_meta($post_id->ID,'custom_field_option_value',true);
                                            $custom_field_option_label = get_post_meta($post_id->ID,'custom_field_option_label',true);
                                            foreach ($custom_field_option_value as $key => $value) {
                                                ?>
                                                <option value="<?php echo esc_attr($value); ?>" 
                                                    <?php if ( !empty($custom_field_value) && !is_array($custom_field_value) && $custom_field_value == $value ) echo "selected"; ?>>
                                                    <?php echo esc_html($custom_field_option_label[$key]);?> 
                                                </option>
                                                <?php
                                            } ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php
                            }
                        }elseif($custom_register_field_type == 'textarea'){
                            if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                                ?>
                                <tr class="<?php echo esc_attr($cfafwr_add_custom_class);?>">
                                    <th>
                                        <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>">
                                            <?php echo esc_html($custom_field_label); ?> 
                                        </label>
                                    </th>
                                    <td>
                                        <textarea name="<?php echo esc_attr($custom_field_slug_name);?>" 
                                            placeholder="<?php echo esc_attr($custom_field_placeholder);?>" 
                                            id="reg_<?php echo esc_attr($custom_field_slug_name);?>" 
                                            class="woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>" 
                                            style="width: 100%;"><?php echo !empty($custom_field_value) && !is_array($custom_field_value) ? esc_html($custom_field_value) : ''; ?></textarea>
                                    </td>
                                </tr>
                                <?php
                            }
                        }elseif($custom_register_field_type == 'checkbox'){
                            if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                                ?>
                                <tr class="<?php echo esc_attr($cfafwr_add_custom_class);?>">
                                    <th>
                                        <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>">
                                            <?php echo esc_html($custom_field_label); ?> 
                                        </label>
                                    </th>
                                    <td>
                                        <input type="<?php echo esc_attr($custom_register_field_type);?>" 
                                            class="woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>" 
                                            placeholder="<?php echo esc_attr($custom_field_placeholder);?>" 
                                            name="<?php echo esc_attr($custom_field_slug_name);?>" 
                                            id="reg_<?php echo esc_attr($custom_field_slug_name);?>" 
                                            value="yes"
                                            <?php if ( !empty($custom_field_value) && !is_array($custom_field_value) && $custom_field_value == 'yes' ) echo "checked"; ?> 
                                        />
                                    </td>
                                </tr>
                                <?php
                            }
                        }elseif($custom_register_field_type == 'image'){
                            if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                                ?>
                                <tr class="<?php echo esc_attr($cfafwr_add_custom_class);?>">
                                    <th>
                                        <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>">
                                            <?php echo esc_html($custom_field_label); ?> 
                                        </label>
                                    </th>
                                    <td>
                                        <?php  
                                        $attechment_id = '';
                                        if(!empty($custom_field_value) && !is_array($custom_field_value)){
                                            $attechment_id = $custom_field_value;
                                            $type = get_post_mime_type($attechment_id);
                                            $typeee = explode('/', $type);
                                            $attechment_url = wp_get_attachment_url( $attechment_id ); 
                                            if(!empty($attechment_url)){
                                                echo "<div>";
                                                if ($typeee[0] == 'video') {
                                                    ?>
                                                    <video width="400" controls autoplay>
                                                        <source src="<?php echo esc_url($attechment_url);?>" type="<?php echo esc_attr($type);?>">
                                                    </video>
                                                    <?php
                                                }elseif ($typeee[0] == 'image') {
                                                    ?>
                                                    <img class="customimag" width="200" src="<?php echo esc_url($attechment_url);?> " >
                                                    <?php
                                                }elseif ($typeee[0] == 'audio') {
                                                    ?>
                                                    <audio controls>
                                                        <source src="<?php echo esc_url($attechment_url);?>" type="<?php echo esc_attr($type);?>">
                                                    </audio>
                                                    <?php
                                                }else{
                                                    ?>
                                                    <a href="<?php echo esc_url($attechment_url); ?>" class="dodument_files" target="_blank">
                                                        <img src="<?php echo CFAFWR_PLUGIN_DIR.'/assets/images/document.png';?>">View Attachment
                                                    </a>
                                                    <?php
                                                }
                                                echo "</div>";
                                            }
                                        }
                                        ?>
                                        <input type="file" 
                                            class="woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>" 
                                            placeholder="<?php echo esc_attr($custom_field_placeholder);?>" 
                                            name="<?php echo esc_attr($custom_field_slug_name);?>" 
                                            id="reg_<?php echo esc_attr($custom_field_slug_name);?>" 
                                            value="<?php echo esc_attr($attechment_id); ?>" 
                                            style="width: 100%;" 
                                        />
                                    </td>
                                </tr>
                                <?php
                            } 
                        }elseif($custom_register_field_type == 'html'){
                            if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                                echo wp_kses_post($cfafwr_custom_html);
                            }
                        }elseif($custom_register_field_type == 'color'){
                            if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                                ?>
                                <tr class="<?php echo esc_attr($cfafwr_add_custom_class);?>">
                                    <th>
                                        <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>">
                                            <?php echo esc_html($custom_field_label); ?> 
                                        </label>
                                    </th>
                                    <td>
                                        <input type="text" 
                                            class="color_sepctrum woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>" 
                                            placeholder="<?php echo esc_attr($custom_field_placeholder);?>" 
                                            name="<?php echo esc_attr($custom_field_slug_name);?>" 
                                            id="reg_<?php echo esc_attr($custom_field_slug_name);?>" 
                                            value="<?php echo !empty($custom_field_value) && !is_array($custom_field_value) ? esc_attr($custom_field_value) : ""; ?>" 
                                            style="width: 100%;" 
                                        />
                                    </td>
                                </tr>
                                <?php
                            }
                        }elseif($custom_register_field_type == 'multicheckbox'){
                            if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                                ?>
                                <tr class="<?php echo esc_attr($cfafwr_add_custom_class);?>">
                                    <th>
                                        <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>">
                                            <?php echo esc_html($custom_field_label); ?> 
                                        </label>
                                    </th>
                                    <td>
                                        <?php
                                        $custom_field_option_value = get_post_meta($post_id->ID,'custom_field_option_value',true);
                                        $custom_field_option_label = get_post_meta($post_id->ID,'custom_field_option_label',true);
                                        foreach ($custom_field_option_value as $key => $value) {
                                            ?>
                                            <span class="multi_checkboxes">
                                                <input type="checkbox" 
                                                    class="woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>" 
                                                    placeholder="<?php echo esc_attr($custom_field_placeholder);?>" 
                                                    name="<?php echo esc_attr($custom_field_slug_name);?>[]" 
                                                    id="<?php echo esc_attr($value);?>" 
                                                    value="<?php echo esc_attr($value);?>"
                                                    <?php if ( !empty($custom_field_value) && is_array($custom_field_value) && in_array($value, $custom_field_value) ) echo "checked"; ?> 
                                                />
                                                <label class="multi_checklabel" for="<?php echo esc_attr($value);?>">
                                                    <?php echo esc_html($custom_field_option_label[$key]);?> 
                                                </label>
                                            </span>
                                            <?php
                                        } ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }elseif($custom_register_field_type == 'multiselect'){
                            if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
                                ?>
                                <tr class="<?php echo esc_attr($cfafwr_add_custom_class);?>">
                                    <th>
                                        <label for="reg_<?php echo esc_attr($custom_field_slug_name);?>">
                                            <?php echo esc_html($custom_field_label); ?> 
                                        </label>
                                    </th>
                                    <td>
                                        <select name="<?php echo esc_attr($custom_field_slug_name);?>[]" 
                                            class="cfafwr_multiselect woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>" 
                                            style="width: 100%;" 
                                            data-id="<?php echo esc_attr($post_id->ID);?>" 
                                            multiple="multiple">
                                            <?php
                                            $custom_field_option_value = get_post_meta($post_id->ID,'custom_field_option_value',true);
                                            $custom_field_option_label = get_post_meta($post_id->ID,'custom_field_option_label',true);
                                            foreach ($custom_field_option_value as $key => $value) {
                                                $selected = '';
                                                if ( !empty($custom_field_value) && is_array($custom_field_value) && in_array($value, $custom_field_value) ) {
                                                    $selected = 'selected';
                                                }
                                                ?>
                                                <option value="<?php echo esc_attr($value);?>" <?php echo esc_attr($selected);?>>
                                                    <?php echo esc_html($custom_field_option_label[$key]);?> 
                                                </option>
                                                <?php
                                            } ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                </table>
                <div class="oc_cust_submit">
                    <input type="hidden" name="action" value="cfafwr_save_occf">
                    <input type="submit" name="submit" value="Save Fields" class="button button-primary wp-element-button" style="font-size: 13 px; padding: 5px 10px;">
                </div>
            </form>
            <?php
        }else{
            wc_add_notice(__('You have no data for custom fields!', 'custom-fields-account-for-woocommerce-registration'), 'error');
            // Display notices
            if (function_exists('wc_print_notices')) {
                wc_print_notices();
            }
        }
    }      

    function CFAFWR_add_oc_custom_fields_endpoint() {
        global $cfafwr_comman;

        add_rewrite_endpoint( 'oc-custom-fields', EP_ROOT | EP_PAGES );
        // check nonce here
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'cfafwr_save_occf') {
        
            if ( ! isset( $_REQUEST['nonce'] ) || !wp_verify_nonce( $_REQUEST['nonce'], 'cfafwr_save_occf_nonce' ) ) {
                wp_die( 'Security check failed. Nonce is invalid.' );
                exit();
            }

            $user_id = get_current_user_id();
            $all_post_ids = get_posts(array(
                'fields'          => 'ids',
                'posts_per_page'  => -1,
                'post_type' => 'wporg_custom_field'
            ));
            
            if(!empty($all_post_ids)){
                foreach ($all_post_ids as $key => $post_id) {   
                    $custom_register_field_type = get_post_meta($post_id,'custom_register_field_type',true);         
                    $custom_field_slug_name = get_post_meta($post_id,'custom_field_slug_name',true);

                    if ( isset( $_POST[$custom_field_slug_name] ) && get_post_meta($post_id,"custom_field_checkbox",true) == 'yes' ) {
                        if ($custom_register_field_type != 'multicheckbox' && $custom_register_field_type != 'multiselect') {
                            update_user_meta( $user_id, $custom_field_slug_name, cfafw_recursive_sanitize_text_field($_POST[$custom_field_slug_name]));
                        }elseif ($custom_register_field_type == 'multicheckbox' || $custom_register_field_type == 'multiselect') {
                            update_user_meta( $user_id, $custom_field_slug_name, cfafw_recursive_sanitize_text_field($_POST[$custom_field_slug_name]));
                        }
                    }elseif ( !isset( $_POST[$custom_field_slug_name] ) && get_post_meta($post_id,"custom_field_checkbox",true) == 'yes' ) {
                        if ($custom_register_field_type == 'multicheckbox' || $custom_register_field_type == 'multiselect') {
                            update_user_meta( $user_id, $custom_field_slug_name, '' );
                        }
                    }

                    if($custom_register_field_type == "image"){
                        if ( isset( $_FILES[$custom_field_slug_name] ) && get_post_meta($post_id,"custom_field_checkbox",true) == 'yes' && $_FILES[$custom_field_slug_name]['error'] == 0) {

                            require_once( ABSPATH . 'wp-admin/includes/image.php' );
                            require_once( ABSPATH . 'wp-admin/includes/file.php' );
                            require_once( ABSPATH . 'wp-admin/includes/media.php' );
                            $attachment_id = media_handle_upload( $custom_field_slug_name, 0 ); 
                            
                            if (!empty($attachment_id) ) {
                                update_user_meta( $user_id, $custom_field_slug_name, $attachment_id );
                            }else{
                                update_user_meta( $user_id,  $custom_field_slug_name, NULL);
                            }
                        }
                    }
                }
            }
        }
    }

    function CFAFWR_multiselect_ajax_fun() {
        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'cfafwr_ajax_nonce' ) ) {
            wp_die( 'Security check failed. Nonce is invalid.' );
        }
        
        $post_id = sanitize_text_field($_REQUEST['selectid']);
        $custom_register_field_type = get_post_meta($post_id,'custom_register_field_type',true);
        $multiselect = array();
        if ($custom_register_field_type == 'multiselect') {
            $custom_field_option_value = get_post_meta($post_id,'custom_field_option_value',true);
            $custom_field_option_label = get_post_meta($post_id,'custom_field_option_label',true);
            foreach ($custom_field_option_value as $key => $value) {
                if (strpos(strtolower($custom_field_option_label[$key]), strtolower(sanitize_text_field($_REQUEST['q']))) !== FALSE){
                    $multiselect[] = array( $value, $custom_field_option_label[$key] );
                }
            }
        }
        echo json_encode( $multiselect );
        die;
    }
    add_action('init','cfafwr_save_init_action');
    add_action( 'init','CFAFWR_add_oc_custom_fields_endpoint');
    function cfafwr_save_init_action() {
        global $cfafwr_comman;
        if ($cfafwr_comman['cfafwr_enable_plugin'] == 'yes') {
        	if($cfafwr_comman['cfafwr_show_field_register'] == 'register_form_start'){
                add_action( 'woocommerce_register_form_start','CFAFWR_registration_form_field'); 
            }else{
                add_action( 'woocommerce_register_form','CFAFWR_registration_form_field');
            }  
            add_action( 'woocommerce_register_post','CFAFWR_wooc_validate_extra_register_fields', 10, 3 );
            add_action( 'woocommerce_created_customer','CFAFWR_wooc_save_extra_register_fields');
            add_action( 'user_register','CFAFWR_send_welcome_email_to_new_user');
            add_action( 'woocommerce_register_form_tag','CFAFWR_enctype_custom_registration_forms');
            add_filter( 'gettext','CFAFWR_translate_woocommerce_strings', 999, 3 );
            add_filter( 'query_vars','CFAFWR_oc_custom_fields_query_vars' );
            add_filter( 'woocommerce_account_menu_items','CFAFWR_add_oc_custom_fields_link_my_account');
            add_action( 'woocommerce_account_oc-custom-fields_endpoint','CFAFWR_oc_custom_fields_content');
            
            add_action( 'wp_ajax_nopriv_cfafwr_multiselect_ajax','CFAFWR_multiselect_ajax_fun');
            add_action( 'wp_ajax_cfafwr_multiselect_ajax','CFAFWR_multiselect_ajax_fun');
        }    
    }

