<?php
    function CFAFWR_submenu_page() {
        add_submenu_page('edit.php?post_type=wporg_custom_field',__( 'woocommerce Custom Fields Registration', __('Custom Fields Registration','custom-fields-account-for-woocommerce-registration') ),__('Settings','custom-fields-account-for-woocommerce-registration'),'manage_options','custom-fields-registration-settings','CFAFWR_callback');
    }

    function CFAFWR_callback(){
    	global $cfafwr_comman;
    	?>
    	<div class="wrap">
        	<h2>Custom Fields For Woocommerce Registration</h2>	
        	<div class="card cfafw_notice">
	            <h2>Please help us spread the word & keep the plugin up-to-date</h2>
	            <p>
	            	<a class="button-primary button" title="Support Custom Fields Account Registration" target="_blank" href="https://www.plugin999.com/support/">Support</a>
	                <a class="button-primary button" title="Rate Custom Fields Account Registration" target="_blank" href="https://wordpress.org/support/plugin/custom-fields-account-registration-for-woocommerce/reviews/?filter=5">Rate the plugin ★★★★★</a>
	            </p>
	        </div>
        	<?php 
                if(isset($_REQUEST['message'])){
                    if($_REQUEST['message'] == 'success'){ 
                        ?>
                        <div class="notice notice-success is-dismissible"> 
                            <p><strong>Your Settings Updated Successfully...!</strong></p>
                        </div>
                        <?php 
                    }elseif($_REQUEST['message'] == 'delete'){ 
                        ?>
                        <div class="notice notice-success is-dismissible"> 
                            <p><strong>Your Field Deleted Successfully...!</strong></p>
                        </div>
                        <?php 
                    }
                }
            ?>            		
        	<div class="cfafwr-container">
	            <form method="post">
	            	<?php wp_nonce_field( 'cfafwr_nonce_action', 'cfafwr_nonce_field' ); ?>
	                <ul class="nav-tab-wrapper woo-nav-tab-wrapper">
	                    <li class="nav-tab nav-tab-active" data-tab="cfafwr-tab-general">General Setting</li>
                    	<li class="nav-tab" data-tab="cfafwr-tab-registration-fields">Registration Fields</li>
	                </ul>
	                <div id="cfafwr-tab-general" class="tab-content current">
	                	<div class="postbox">
	                		<div class="inside">
			                	<table class="data_table">
			                        <tbody>
			                            <tr>
			                                <th>
			                                    <label>Enable Authentication</label>
			                                </th>
			                                <td>
			                                    <input type="checkbox" name="cfafwr_comman[cfafwr_enable_plugin]" value="yes"<?php if($cfafwr_comman['cfafwr_enable_plugin'] == 'yes'){echo "checked";}?>>
			                                </td>
			                            </tr>
			                            <tr>
			                                <th>
			                                    <label>Enable User Registration Email</label>
			                                </th>
			                                <td>
			                                    <input type="checkbox" name="cfafwr_comman[cfafwr_user_email_sent]" class="enable_email_section" value="yes"<?php if($cfafwr_comman['cfafwr_user_email_sent'] == 'yes'){echo "checked";}?>>
			                                </td>
			                            </tr>
			                            <tr class="email_subject_and_body_message">
			                                <th>
			                                    <label>User Registration Email Subject Message</label>
			                                </th>
			                                <td>
			                                	<input type="text" class="regular-text" name="cfafwr_comman[cfafwr_user_email_subject_msg]" value="<?php echo esc_attr($cfafwr_comman['cfafwr_user_email_subject_msg']); ?>" disabled>
												<label class="fcpfw_comman_link">This Option Available in  <a href="https://www.plugin999.com/plugin/custom-fields-account-registration-for-woocommerce/" target="_blank">Pro Version</a></label>
			                                </td>
			                            </tr>
			                            <tr class="email_subject_and_body_message">
			                                <th>
			                                    <label>User Registration Email Body Message</label>
			                                </th>
			                                <td>
			                                    <textarea name="cfafwr_comman[cfafwr_user_email_body_msg]" class="regular-text" rows="5" disabled><?php echo esc_attr($cfafwr_comman['cfafwr_user_email_body_msg']); ?></textarea>
												<label class="fcpfw_comman_link">This Option Available in  <a href="https://www.plugin999.com/plugin/custom-fields-account-registration-for-woocommerce/" target="_blank">Pro Version</a></label>
			                                    <p class="cfafwr_description"><strong>Note : </strong> <code>{site_name}</code> = <?php echo get_bloginfo( 'name' );?></p>
			                                </td>
			                            </tr>
			                            <tr>
			                                <th>
			                                    <label>Hide Field labels</label>
			                                </th>
			                                <td>
			                                   	<select name="cfafwr_comman[cfafwr_hide_field_labels]" class="regular-text">
			                                   		<option value="yes" <?php if($cfafwr_comman['cfafwr_hide_field_labels'] == 'yes'){echo "selected";}?>>Yes</option>
			                                   		<option value="no" <?php if($cfafwr_comman['cfafwr_hide_field_labels'] == 'no'){echo "selected";}?>>No</option>
			                                   	</select>
			                                </td>
			                            </tr>
			                            <tr>
			                                <th>
			                                    <label>Change Login/Register Title Text</label>
			                                </th>
			                                <td>
			                                   	<input type="checkbox" class="cfafwr_login_reg_change_text" name="cfafwr_comman[cfafwr_login_reg_change_text]" value="yes" <?php if($cfafwr_comman['cfafwr_login_reg_change_text'] == 'yes'){echo "checked";}?>>
			                                   	<label>Enable/Disable</label>
			                                </td>
			                            </tr>
			                            <tr class="cfafwr_log_reg">
			                                <th>
			                                    <label>Change Login Title Text</label>
			                                </th>
			                                <td>
			                                   	<input type="text" class="regular-text" name="cfafwr_comman[cfafwr_login_change_text]" value="<?php echo esc_attr($cfafwr_comman['cfafwr_login_change_text']); ?>">
			                                </td>
			                            </tr>
			                            <tr class="cfafwr_log_reg">
			                                <th>
			                                    <label>Change Register Title Text</label>
			                                </th>
			                                <td>
			                                	<input type="text" class="regular-text" name="cfafwr_comman[cfafwr_reg_change_text]" value="<?php echo esc_attr($cfafwr_comman['cfafwr_reg_change_text']); ?>">
			                                </td>
			                            </tr>
			                            <tr>
			                                <th>
			                                    <label>Field Required Message Text</label>
			                                </th>
			                                <td>
			                                	<input type="text" class="regular-text" name="cfafwr_comman[cfafwr_field_label_require_text]" value="<?php echo esc_attr($cfafwr_comman['cfafwr_field_label_require_text']);?>">
			                                	<p class="cfafwr_description"><strong>Note : </strong> <code>{field_label}</code> = Register field labels..</p>
			                                </td>
			                            </tr>
			                            <tr>
			                                <th>
			                                    <label>Change My Account Custom Tab Title Text</label>
			                                </th>
			                                <td>
			                                	<input type="text" class="regular-text" name="cfafwr_comman[cfafwr_myac_tab_title]" value="<?php echo esc_attr($cfafwr_comman['cfafwr_myac_tab_title']);?>">
			                                </td>
			                            </tr>
			                            <tr>
			                                <th>
			                                    <label>Change My Account Custom Tab Form Heading Text</label>
			                                </th>
			                                <td>
			                                	<input type="text" class="regular-text" name="cfafwr_comman[cfafwr_myac_tab_form_head]" value="<?php echo esc_attr($cfafwr_comman['cfafwr_myac_tab_form_head']);?>">
			                                </td>
			                            </tr>
			                            <tr>
			                                <th>
			                                    <label>Show Custom Field</label>
			                                </th>
			                                <td>
			                                   	<select name="cfafwr_comman[cfafwr_show_field_register]" class="regular-text">
			                                   		<option value="register_form_start" <?php if($cfafwr_comman['cfafwr_show_field_register'] == 'register_form_start'){echo "selected";}?>>Register Form Start</option>
			                                   		<option value="before_register_form" <?php if($cfafwr_comman['cfafwr_show_field_register'] == 'before_register_form'){echo "selected";}?>>Before Register Button</option>
			                                   	</select>
			                                </td>
			                            </tr>
			                        </tbody>
			                    </table>
			                </div>
		                </div>
	                </div>
	                <div id="cfafwr-tab-registration-fields" class="tab-content">
	                	<div class="postbox">
	    					<div class="cfafwr_add_new_fields">
	    						<div class="postbox-header">
									<span><h2>Registration Fields</h2></span>
								</div>
								<?php 
								$myargs = array(
						           'post_type' => 'wporg_custom_field', 
						           'posts_per_page' => -1, 
						           'meta_key' => 'cfafwr_field_ajax_id', 
						           'orderby' => 'meta_value_num', 
						           'order' => 'ASC'
						        );
						        $posts = query_posts($myargs );
						        if (!empty($posts)) {
									?>
									<ul class="cfafwr_dl_data">
										<?php
								        foreach ($posts as $key => $post) {
											$custom_register_field_type = get_post_meta($post->ID,'custom_register_field_type',true);
											$cfafwr_field_ajax_id = get_post_meta($post->ID,'cfafwr_field_ajax_id',true);
											$custom_field_label = get_the_title($post->ID);
											$custom_field_checkbox = get_post_meta($post->ID,'custom_field_checkbox',true);
			                        		?>
				                        	<li>
				                        		<div class="cfafwr_add_new_fields_inner" value="<?php echo esc_attr($post->ID);?>" id="<?php echo 'custom_field_checkbox'. esc_attr($post->ID);?>">
				                        			<span class="cfafwr_label">
				                        				<?php echo __($custom_field_label,'custom-fields-account-for-woocommerce-registration');?>
				                        			</span>
				                        			<span class="cfafwr_checkbox">
				                        				<?php
				                        					$link = admin_url() . "post.php?post=" . $post->ID . "&action=delete";
															$delLink = wp_nonce_url($link);
  															$delete_nonce = wp_create_nonce('cfafwr_delete_post');
															$edit_link = get_edit_post_link($post->ID);
				                        				?>
				                        				<a href="<?php echo esc_attr($edit_link);?>" target="_blank" title="Edit Field"><img class="remove_field" data-id="<?php echo esc_attr($post->ID);?>" src="<?php echo CFAFWR_PLUGIN_DIR.'/assets/images/edit_icon.png';?>"></a>
				                        				<a href="<?php echo admin_url( '/admin.php?page=custom-fields-registration-settings' ); ?>&action=delete_post&post_id=<?php echo esc_attr($post->ID); ?>&nonce=<?php echo esc_attr($delete_nonce); ?>" title="Delete Field"><img class="remove_field" data-id="<?php echo esc_attr($post->ID);?>" src="<?php echo CFAFWR_PLUGIN_DIR.'/assets/images/remove.png';?>"></a>
				                        			</span>
				                        		</div>
					                        </li>
				                        	<?php 
				                        } ?>
									</ul>
									<?php

						        }else{
						        	echo "<div class='register_empty_fields'>";
						        	echo "<p class='empty_register_fields'>Registration fields is not set....</p>";
						        	echo "<a href='".esc_url(admin_url())."post-new.php?post_type=wporg_custom_field' target='_blank' class='add_field_button button-primary'>".__('Add registration fields','custom-fields-account-for-woocommerce-registration')."</a>";
						        	echo "</div>";
						        } ?>
							</div>
						</div>	
	                </div>
	                <div class="submit_button">
	                    <input type="hidden" name="cfafwr_form_submit" value="cfafwr_save_option">
	                    <input type="submit" value="Save changes" name="submit" class="button-primary" id="cfafwr-btn-space">
	                </div>
               	</form>
           	</div>
       	</div>
    	<?php
    }

    function CFAFWR_filed_sortable(){
        if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'cfafwr_ajax_nonce' ) ) {
            wp_die( 'Security check failed. Nonce is invalid.' );
        }
        
    	foreach ($_REQUEST['post_meta'] as $keypost_meta => $valuepost_meta) {
    		update_post_meta($valuepost_meta,'cfafwr_field_ajax_id',(int)($keypost_meta));
    	}
		exit();
	}

	function add_support_link_metabox() {
	    add_meta_box(
	        'support_metabox', // ID
	        'Support Us', // Title
	        'CFAFWR_display_support_metabox', // Callback function
	        'wporg_custom_field', // Post type
	        'side', // Context (side, normal, advanced)
	    );
	}
	add_action('add_meta_boxes', 'add_support_link_metabox');

	function CFAFWR_display_support_metabox($post) {
		?>  
		<h2>Please help us spread the word & keep the plugin up-to-date</h2>
        <p>
        	<a class="button-primary button" title="Support Custom Fields Account Registration" target="_blank" href="https://www.plugin999.com/support/">Support</a>
            <a class="button-primary button" title="Rate Custom Fields Account Registration" target="_blank" href="https://wordpress.org/support/plugin/custom-fields-account-registration-for-woocommerce/reviews/?filter=5">Rate the plugin ★★★★★</a>
        </p>
	    <?php
	}

	function CFAFWR_global_notice_meta_box() {
	    add_meta_box(
	        'cusrom_regster_field_id',
	        __( 'Custom Register Fields', 'Custom_Register' ),
	        'cusrom_field_meta_box_callback',
	        'wporg_custom_field'
	    );
	}

	function cusrom_field_meta_box_callback($post){
		remove_meta_box( 'slugdiv', 'wporg_custom_field', 'normal' );
		?>
		<form method="post">
			<table class="meta_box_table">
				<tbody>
					<tr >
						<th>
							<label>Field Enable</label>
						</th>
						<td>
							<?php $custom_field_checkbox = get_post_meta($post->ID,'custom_field_checkbox',true); ?>
							<select name="custom_field_checkbox" class="regular-text">
								<option value="yes" <?php if($custom_field_checkbox == 'yes'){echo "selected";}?>>Yes</option>
								<option value="no" <?php if($custom_field_checkbox == 'no'){echo "selected";}?>>No</option>
							</select>
							
							<p class="cfafwr_description"><strong>Note:</strong> You do compulsory add this field's value.</p>
						</td>
					</tr>
					<tr>
						<th>
							<label>Custom Registration Field Type</label>
						</th>
						<td>
							<?php $custom_register_field_type = get_post_meta($post->ID,'custom_register_field_type',true); ?>
							<select name="custom_register_field_type" class="regular-text custom_field_type">
								<optgroup label="Billing Address Fields">
									<?php
									$billaddress_fields = wc()->countries->get_address_fields(get_user_meta(get_current_user_id(), 'billing_country', true));
		                            foreach ($billaddress_fields as $key => $field) {
		                            	?>
								    	<option value="<?php echo esc_attr($key);?>" <?php if($custom_register_field_type == $key){echo "selected";}?>><?php echo esc_html($field['label']);?></option>
								    	<?php
		                            }
									?>
							  	</optgroup>
							  	<optgroup label="Shipping Address Fields">
								    <?php
									$countries = new WC_Countries();
		                            if ( ! isset( $country ) ) {
		                                $country = $countries->get_base_country();
		                            }
		                            $shipaddress_fields = WC()->countries->get_address_fields( $country, 'shipping_' );
		                            foreach ($shipaddress_fields as $key => $field) {
		                                ?>
								    	<option value="<?php echo esc_attr($key);?>" <?php if($custom_register_field_type == $key){echo "selected";}?>><?php echo esc_html($field['label']);?></option>
								    	<?php
		                            }
									?>
							  	</optgroup>
							  	<optgroup label="Other Fields">
									<option value="text" <?php if($custom_register_field_type == 'text'){echo "selected";} ?> > Text </option>
									<option value="number" <?php if($custom_register_field_type == 'number'){echo "selected";} ?> > Number </option>
									<option value="tel" <?php if($custom_register_field_type == 'tel'){echo "selected";} ?> > Phone </option>
									<option value="email" <?php if($custom_register_field_type == 'email'){echo "selected";} ?> > Email </option>
									<option value="url" <?php if($custom_register_field_type == 'url'){echo "selected";} ?> > Url </option>
									<option value="checkbox" <?php if($custom_register_field_type == 'checkbox'){echo "selected";} ?> > Checkbox </option>
									<option value="image" <?php if($custom_register_field_type == 'image'){echo "selected";} ?> disabled > File Upload </option>
									<option value="password" <?php if($custom_register_field_type == 'password'){echo "selected";} ?> disabled > Password </option>
									<option value="textarea" <?php if($custom_register_field_type == 'textarea'){echo "selected";} ?> disabled > Textarea </option>
									<option value="html" <?php if($custom_register_field_type == 'html'){echo "selected";} ?> disabled > Custom Html </option>
									<option value="color" <?php if($custom_register_field_type == 'color'){echo "selected";} ?> disabled > Color Picker </option>
									<option value="time" <?php if($custom_register_field_type == 'time'){echo "selected";} ?> disabled > Time Picker </option>
									<option value="date" <?php if($custom_register_field_type == 'date'){echo "selected";} ?> disabled > Date Picker </option>
									<option value="radio" <?php if($custom_register_field_type == 'radio'){echo "selected";} ?> disabled > Radio </option>
									<option value="multicheckbox" <?php if($custom_register_field_type == 'multicheckbox'){echo "selected";} ?> disabled > Multiple Checkbox </option>
									<option value="select" <?php if($custom_register_field_type == 'select'){echo "selected";} ?> disabled > Select </option>
									<option value="multiselect" <?php if($custom_register_field_type == 'multiselect'){echo "selected";} ?> disabled > Multi Select </option>
									<option value="hidden" <?php if($custom_register_field_type == 'hidden'){echo "selected";} ?> disabled > Hidden </option>
								</optgroup>
							</select>
							<label class="cfafw_common_link">Some Types Only available in <a href="https://www.plugin999.com/plugin/custom-fields-account-registration-for-woocommerce/" target="_blank">pro version</a></label>
						</td>
					</tr>


					<tr class="custom_html">
						<th>
							<label>Field Label</label>
						</th>
						<td>
							<?php $custom_field_label = get_post_meta($post->ID,'custom_field_label',true); ?>
							<input type="text" class="regular-text" name="custom_field_label" value="<?php echo esc_attr($custom_field_label);?>">
							<p class="cfafwr_description"><strong>Note:</strong> You do compulsory add this field's value.</p>
						</td>
					</tr>
					<tr class="custom_html">
						<th>
							<label>Field Slug Name</label>
						</th>
						<td>
							<?php
								if(!empty( get_post_meta($post->ID,'custom_field_slug_name',true))){
									$custom_field_slug_name = get_post_meta($post->ID,'custom_field_slug_name',true);
								}else{
									$custom_field_slug_name = get_post_meta($post->ID,'custom_field_label',true);
								}
							?>
							<input type="text" class="regular-text" name="custom_field_slug_name" value="<?php echo esc_attr($custom_field_slug_name);?>">
							<p class="cfafwr_description"><strong>Note:</strong> You do compulsory add this field's value. <code>Ex: Your Slug formate is <strong>slug_name</strong></code></p>
						</td>
					</tr>

					<tr class="custom_html cusrequired">
						<th>
							<label>Field Required?</label>
						</th>
						<td>
							<?php $custom_field_required = get_post_meta($post->ID,'custom_field_required',true); ?>
							<input type="checkbox" class="regular-text" name="custom_field_required" value="yes"<?php if($custom_field_required == 'yes'){echo "checked";}?>>
						</td>
					</tr>
					<tr class="custom_html">
						<th>
							<label>Field Size</label>
						</th>
						<td>
							<?php $custom_field_size = get_post_meta($post->ID,'custom_field_size',true); ?>
							<select name="custom_field_size" class="regular-text">
								<option value="full_width"<?php if($custom_field_size == 'full_width'){echo "selected";}?>>Full Width</option>
								<option value="half_width"<?php if($custom_field_size == 'half_width'){echo "selected";}?>>Half Width</option>
							</select>
						</td>
					</tr>
					<tr class="multiple_options">
						<th>
							<label>Field Options</label>
						</th>
						<td>
							<div class="custom_field_option_inner">
								<table>
									<thead>
										<tr class="custom_field_option_Label_main">
											<td>
												<label class="cfafwr_add_field_options">Add Field Options</label>
											</td>
											<td>
												
											</td>
											<td>
												<span class="custom_add_options"><img src="<?php echo CFAFWR_PLUGIN_DIR.'/assets/images/add_icon.png';?>"></span>
											</td>
										</tr>
									</thead>
									<tbody class="custom_field_option_body">
										<?php
										$custom_field_option_value = get_post_meta($post->ID,'custom_field_option_value',true);
										$custom_field_option_label = get_post_meta($post->ID,'custom_field_option_label',true);
										if(!empty($custom_field_option_value) && $custom_field_option_value['0'] != ''){
											foreach ($custom_field_option_value as $key => $value) {
												?>
												<tr class="custom_field_option_tr">
													<td>
														<input type="text" name="custom_field_option_value[]" placeholder="value" value="<?php echo esc_attr($value);?>">
													</td>
													<td>
														<input type="text" name="custom_field_option_label[]" placeholder="label" value="<?php echo esc_attr($custom_field_option_label[$key]);?>">
													</td>
													<td>
														<span class="custom_remove_options"><img src="<?php echo CFAFWR_PLUGIN_DIR.'/assets/images/remove_icon.png';?>"></span>
													</td>
												</tr>
												<?php
											}
										}else{
											?>
											<tr class="custom_field_option_tr">
												<td>
													<input type="text" name="custom_field_option_value[]" placeholder="value" value="">
												</td>
												<td>
													<input type="text" name="custom_field_option_label[]" placeholder="label" value="">
												</td>
												<td>
													<span class="custom_remove_options"><img src="<?php echo CFAFWR_PLUGIN_DIR.'/assets/images/remove_icon.png';?>"></span>
												</td>
											</tr>
											<?php
										} ?>
									</tbody>
								</table>
							</div>
						</td>
					</tr>
					<tr class="field_placeholder">
						<th>
							<label>Field Placeholder</label>
						</th>
						<td>
							<?php $custom_field_placeholder = get_post_meta($post->ID,'custom_field_placeholder',true); ?>
							<input type="text" class="regular-text" name="custom_field_placeholder" value="<?php echo esc_attr($custom_field_placeholder);?>">
						</td>
					</tr>
					<tr class="custom_html_sec">
						<th>
							<label>Custom Html</label>
						</th>
						<td>
							<?php $cfafwr_custom_html = get_post_meta($post->ID,'cfafwr_custom_html',true); ?>
							<textarea name="cfafwr_custom_html" class="regular-text" rows="8"><?php echo esc_attr($cfafwr_custom_html);?></textarea>
						</td>
					</tr>
					<tr class="cfafwr_custom_class">
						<th>
							<label>Add Custom Class</label>
						</th>
						<td>
							<?php $cfafwr_add_custom_class = get_post_meta($post->ID,'cfafwr_add_custom_class',true); ?>
							<input type="text" name="cfafwr_add_custom_class" class="regular-text" value="<?php echo esc_attr($cfafwr_add_custom_class);?>">
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		<?php
	}

    function CFAFWR_recursive_sanitize_text_field( $array ) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = CFAFWR_recursive_sanitize_text_field($value);
            }else{
                $value = sanitize_text_field( $value );
            }
        }
        return $array;
    
    }   


	function CFAFWR_custom_meta_box_field_save($post_id)
	{
		// Avoid auto-save and verify permissions
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
		if (!current_user_can('edit_post', $post_id)) return $post_id;


		if (isset($_POST['custom_field_checkbox'])) {
			update_post_meta($post_id, 'custom_field_checkbox', sanitize_text_field($_POST['custom_field_checkbox']));
		} else {
			delete_post_meta($post_id, 'custom_field_checkbox');
		}

		if (isset($_REQUEST["custom_register_field_type"])) {
			$custom_register_field_type = sanitize_text_field($_REQUEST["custom_register_field_type"]);
			update_post_meta(get_the_ID(), 'custom_register_field_type', $custom_register_field_type);
		}

		if (isset($_REQUEST["cfafwr_add_custom_class"])) {
			$cfafwr_add_custom_class = str_replace(' ', '_', sanitize_text_field($_REQUEST["cfafwr_add_custom_class"]));
			update_post_meta(get_the_ID(), 'cfafwr_add_custom_class', $cfafwr_add_custom_class);
		}

		$custom_field_required = (!empty($_REQUEST['custom_field_required'])) ? sanitize_text_field($_REQUEST['custom_field_required']) : '';
		update_post_meta(get_the_ID(), 'custom_field_required', $custom_field_required);

		if (isset($_REQUEST["custom_field_size"])) {
			$custom_field_size = sanitize_text_field($_REQUEST["custom_field_size"]);
			update_post_meta(get_the_ID(), 'custom_field_size', $custom_field_size);
		}

		if (isset($_REQUEST["custom_field_checkbox"])) {
			$custom_field_checkbox = sanitize_text_field($_REQUEST["custom_field_checkbox"]);
			update_post_meta(get_the_ID(), 'custom_field_checkbox', $custom_field_checkbox);
		}

		if (isset($_REQUEST["custom_field_placeholder"])) {
			$custom_field_placeholder = sanitize_text_field($_REQUEST["custom_field_placeholder"]);
			update_post_meta(get_the_ID(), 'custom_field_placeholder', $custom_field_placeholder);
		}
		
		if (isset($_REQUEST["cfafwr_custom_html"])) {
			$cfafwr_custom_html = sanitize_text_field($_REQUEST["cfafwr_custom_html"]);
			update_post_meta(get_the_ID(), 'cfafwr_custom_html', $cfafwr_custom_html);
		}

		// Check if the fields are being set in the form
		if (!isset($_POST['custom_field_label']) || !isset($_POST['custom_register_field_type'])) return $post_id;


		// Sanitize input fields
		// $custom_field_label = sanitize_text_field($_POST['custom_field_label']);
		$custom_register_field_type = sanitize_text_field($_POST['custom_register_field_type']);
		$custom_field_slug_name = isset($_POST['custom_field_slug_name']) ? sanitize_text_field($_POST['custom_field_slug_name']) : '';
		$custom_field_slug_name = str_replace(' ', '_', $custom_field_slug_name); // Replace spaces with underscores
		$custom_field_label = isset($_POST['custom_field_label']) ? sanitize_text_field($_POST['custom_field_label']) : '';
		// $custom_field_label = str_replace(' ', '_', $custom_field_label); // Replace spaces with underscores

		// Validate "Slug Name" and "Label Name" only if the current field type requires them
		$fields_that_require_slug_and_label = [
			'text',
			'number',
			'email',
			'url',
			'tel',
			'image',
			'password',
			'textarea',
			'color',
			'time',
			'date',
			'radio',
			'checkbox',
			'multicheckbox',
			'select',
			'multiselect',
			'hidden'
		];

		// Add field types that require a slug and label
		if (in_array($custom_register_field_type, $fields_that_require_slug_and_label)) {
			// Validate Label Name
			if (empty($custom_field_label)) {
				set_transient('cfafwr_meta_box_error', 'The "Field Label Name" is required for this field type.', 30);
				revert_to_draft($post_id);
				return $post_id;
			}

			// Check for duplicate labels
			$existing_posts_label = get_posts([
				'post_type'   => 'wporg_custom_field',
				'meta_query'  => [
					[
						'key'     => 'custom_field_label_name',
						'value'   => $custom_field_label,
						'compare' => '=',
					],
				],
				'fields'      => 'ids',
				'exclude'     => [$post_id], // Exclude the current post
				'numberposts' => 1,
			]);

			// Validate Slug Name
			if (empty($custom_field_slug_name)) {
				set_transient('cfafwr_meta_box_error', 'The "Field Slug Name" is required for this field type.', 30);
				revert_to_draft($post_id);
				return $post_id;
			}

			// Restrict "wp_capabilities" and "wp_user_level" slugs
			if ($custom_field_slug_name === 'wp_capabilities' || $custom_field_slug_name === 'wp_user_level') {
				set_transient('cfafwr_meta_box_error', 'The slug "' . esc_html($custom_field_slug_name) . '" is not allowed due to security reasons.', 30);
				revert_to_draft($post_id);
				return $post_id;
			}

			// Check for duplicate slugs
			$existing_posts_slug = get_posts([
				'post_type'   => 'wporg_custom_field',
				'meta_query'  => [
					[
						'key'     => 'custom_field_slug_name',
						'value'   => $custom_field_slug_name,
						'compare' => '=',
					],
				],
				'fields'      => 'ids',
				'exclude'     => [$post_id], // Exclude the current post
				'numberposts' => 1,
			]);
		}



		// Proceed to save meta fields if validation passes
		update_post_meta($post_id, 'custom_field_label', $custom_field_label);
		update_post_meta($post_id, 'custom_register_field_type', $custom_register_field_type);
		update_post_meta($post_id, 'custom_field_slug_name', $custom_field_slug_name);

		// Handle array fields if applicable
		$fields_to_save = [
			'custom_field_option_value',
			'custom_field_option_label',
		];

		foreach ($fields_to_save as $field) {
			if (isset($_POST[$field])) {
				$values = array_map('sanitize_text_field', (array)$_POST[$field]);
				update_post_meta($post_id, $field, $values);
			}
		}

		// Update field count (for your custom logic)
		$all_post_ids = get_posts([
			'fields' => 'ids',
			'posts_per_page' => -1,
			'post_type' => 'wporg_custom_field',
		]);
		update_post_meta($post_id, 'cfafwr_field_ajax_id', count($all_post_ids));
	}

	/**
	 * Revert post to draft and prevent save.
	 */
	function revert_to_draft($post_id)
	{
		remove_action('save_post', 'CFAFWR_custom_meta_box_field_save'); // Temporarily remove the save action
		wp_update_post(['ID' => $post_id, 'post_status' => 'draft']); // Set the post back to 'draft'
		add_action('save_post', 'CFAFWR_custom_meta_box_field_save'); // Re-add the save action
	}

	// Display admin notice for validation errors
	function CFAFWR_display_admin_notice()
	{
		// Check if the error transient is set
		$error = get_transient('cfafwr_meta_box_error');
		if ($error) {
			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($error) . '</p></div>';
			delete_transient('cfafwr_meta_box_error'); // Remove the transient after displaying the notice
		}
	}
	add_action('admin_notices', 'CFAFWR_display_admin_notice');
		

	function CFAFWR_save_option(){

		$post_type = 'wporg_custom_field';
	    $singular_name = 'Custom Register Field';
	    $plural_name = 'Register Fields';
	    $slug = 'wporg_custom_field';
	    $labels = array(
	        'name'               => $plural_name, 'post type general name',
	        'singular_name'      => $singular_name, 'post type singular name',
	        'menu_name'          => $singular_name, 'admin menu name',
	        'name_admin_bar'     => $singular_name, 'add new name on admin bar',
	        'add_new'            => 'Add New Field',
	        'add_new_item'       => 'Add New Field '.$singular_name,
	        'new_item'           => 'New '.$singular_name,
	        'edit_item'          => 'Edit '.$singular_name,
	        'view_item'          => 'View '.$singular_name,
	        'all_items'          => 'All '.$plural_name,
	        'search_items'       => 'Search '.$plural_name,
	        'parent_item_colon'  => 'Parent '.$plural_name.':',
	        'not_found'          => 'No Register Field found.',
	        'not_found_in_trash' => 'No Register Field found in Trash.'
	    );

		$args = array(
			'labels'             => $labels,
			'description'        => 'Description',
			'public'             => false,  // not visible to the public
			'publicly_queryable' => false,  // can't be queried on the front end
			'show_ui'            => current_user_can( 'administrator' ), 
    		'show_in_menu'       => current_user_can( 'administrator' ),
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
			'menu_icon'          => 'dashicons-media-text',
			'show_in_rest'       => false,  // hides from Gutenberg/REST API
		);
		register_post_type( $post_type, $args );
		

		if( current_user_can('administrator') ) {
		    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_post'){
		    	if(!isset( $_REQUEST['nonce'] ) || !wp_verify_nonce( $_REQUEST['nonce'], 'cfafwr_delete_post' ) ){
	                wp_die( 'Security check failed. Nonce is invalid.' );
	                exit;
	            }else{
			        wp_delete_post($_REQUEST['post_id']);
			        wp_redirect(admin_url('/edit.php?post_type=wporg_custom_field&page=custom-fields-registration-settings&message=delete'));
			        exit;    
			    } 
		    }
	         
		    if(isset($_REQUEST['cfafwr_form_submit']) && $_REQUEST['cfafwr_form_submit'] == 'cfafwr_save_option'){
	            if(!isset( $_POST['cfafwr_nonce_field'] ) || !wp_verify_nonce( $_POST['cfafwr_nonce_field'], 'cfafwr_nonce_action' ) ){
	                wp_die( 'Security check failed. Nonce is invalid.' );
	                exit;
	            }else{
			        $isecheckbox = array(
			            'cfafwr_enable_plugin',
			            'cfafwr_user_email_sent',
			            'cfafwr_user_email_sub_body_enable',
			            'cfafwr_login_reg_change_text',
			            'cfafwr_state_field',
			            'cfafwr_state_field_required',
			        );

			        foreach ($isecheckbox as $key_isecheckbox => $value_isecheckbox) {
			            if(!isset($_REQUEST['cfafwr_comman'][$value_isecheckbox])){
			                $_REQUEST['cfafwr_comman'][$value_isecheckbox] = 'no';
			            }
			        }  

			        foreach ($_REQUEST['cfafwr_comman'] as $key_cfafwr_comman => $value_cfafwr_comman) {
			            update_option($key_cfafwr_comman, sanitize_text_field($value_cfafwr_comman), 'yes');
			        }   

			        wp_redirect(admin_url('/edit.php?post_type=wporg_custom_field&page=custom-fields-registration-settings&message=success'));
			        exit;   
			    }  
		    }
		}

	} 

	function CFAFWR_custom_user_profile_fields( $user ){
		global $cfafwr_comman;
	    $user_id = $user->ID;
	    echo '<h3 class="heading">'.__('Custom Fields','custom-fields-account-for-woocommerce-registration').'</h3>';
	    $myargs = array(
	       'post_type' => 'wporg_custom_field', 
	       'posts_per_page' => -1, 
	       'meta_key' => 'cfafwr_field_ajax_id', 
	       'orderby' => 'meta_value_num', 
	       'order' => 'ASC'
	    );
	    $posts = get_posts($myargs );
	    ?>
	    <table class="form-table">
		    <?php
		    if(!empty($posts)){
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

		            if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes' && $custom_register_field_type != 'radio' && $custom_register_field_type != 'select' && $custom_register_field_type != 'textarea' && $custom_register_field_type != 'checkbox' && $custom_register_field_type != 'image' && $custom_register_field_type != 'password' && $custom_register_field_type != 'country' && $custom_register_field_type != 'html' && $custom_register_field_type != 'color' && $custom_register_field_type != 'multicheckbox' && $custom_register_field_type != 'multiselect' && $custom_register_field_type != 'address_billing' && $custom_register_field_type != 'address_shipping' && $bill_ship[0] !== 'billing' && $bill_ship[0] !== 'shipping' ){
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
		                    		value="<?php echo !empty($custom_field_value) && !is_array($custom_field_value) ? esc_attr($custom_field_value) : ''; ?>" 
		                    		style="width: 25em;"
		                    	/>
		                    </td>
		                </tr>
		                </p>
		                <?php
		            }elseif($custom_register_field_type == 'radio'){
		                if( get_post_meta($post_id->ID, "custom_field_checkbox", true) == 'yes'){
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
		                        	<select name="<?php echo esc_attr($custom_field_slug_name);?>" 
		                        		class="woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>" 
		                        		style="width: 25em;" >
			                            <option>select option</option>
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
		                        	<textarea name="<?php echo esc_attr($custom_field_slug_name);?>" placeholder="<?php echo esc_attr($custom_field_placeholder);?>" id="reg_<?php echo esc_attr($custom_field_slug_name);?>" class="woocommerce-Input woocommerce-Input--<?php echo esc_attr($custom_register_field_type);?> input-<?php echo esc_attr($custom_register_field_type);?>" style="width: 25em;"><?php echo !empty($custom_field_value) && !is_array($custom_field_value) ? esc_html($custom_field_value) : ''; ?></textarea>
		                        </td>
		                    </tr>
		                    <?php
		                }
		            }elseif($custom_register_field_type == 'checkbox'){
		            	// Pending from here..
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
			                                    <a href="<?php echo esc_url($attechment_url);?>" class="dodument_files" target="_blank">
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
										value="<?php echo esc_attr($attachment_id); ?>" 
		                            	style="width: 25em;" 
		                            />
		                        </td>
		                    </tr>
		                	<?php 
		                }
		            }elseif($custom_register_field_type == 'html'){
		            	if( get_post_meta($post_id->ID,"custom_field_checkbox",true) == 'yes'){
		                    echo html_entity_decode($cfafwr_custom_html);
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
		                            	value="<?php echo !empty($custom_field_value) && !is_array($custom_field_value) ? esc_attr($custom_field_value) : ''; ?>" 
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
		                        		style="width: 25em;" 
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
		                                }
		                                ?>
		                            </select>
		                        </td>
		                    </tr>
		                    <?php
		                }
		            }
		        }
		    }
		    ?>
	    </table>
	    <?php
	}	


	function CFAFWR_save_custom_user_profile_fields( $user_id ) {
		global $cfafwr_comman;
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
	                    update_user_meta( $user_id, $custom_field_slug_name, sanitize_text_field( $_POST[$custom_field_slug_name] ) );
	                }elseif ($custom_register_field_type == 'multicheckbox' || $custom_register_field_type == 'multiselect') {
	                    update_user_meta( $user_id, $custom_field_slug_name, sanitize_text_field($_POST[$custom_field_slug_name]) );
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

	// add enctype for file upload in profile-edit form
	function add_multipart_enctype_to_profile_form() {
	    echo ' enctype="multipart/form-data"';
	}

	function CFAFWR_filter_posts_columns( $wporg_custom_field_columns ) {
		$wporg_custom_field_columns['enable']   = '<strong>'.__('Enable','custom-fields-account-for-woocommerce-registration').'</strong>';
		return $wporg_custom_field_columns;
	}

	function CFAFWR_smashing_realestate_column( $column, $post_id ) {
	  	if ( $column == 'enable' ) {
	  		$custom_field_checkbox = get_post_meta($post_id,'custom_field_checkbox',true);
	  		if ($custom_field_checkbox == 'yes') {
	  			echo "<strong>".__('Yes','custom-fields-account-for-woocommerce-registration')."</strong>";
	  		}else{
	  			echo "<strong>".__('No','custom-fields-account-for-woocommerce-registration')."</strong>";
	  		}
	  	}	
	}

	function CFAFWR_save_custom_field_messages( $messages ) {
		if(isset($_REQUEST['post']) && !empty($_REQUEST['post'])){
	    	$messages['wporg_custom_field'] = array(
	    		1  => __( get_the_title( $_REQUEST['post'] ).' Field Saved Successfully...!', 'custom-fields-account-for-woocommerce-registration' ),
	    		6 => __( get_the_title( $_REQUEST['post'] ).' Field Added Successfully...!', 'custom-fields-account-for-woocommerce-registration' )
	    	);
		}
	    return $messages;
	}


	add_action( 'admin_menu','CFAFWR_submenu_page');
	add_action( 'init','CFAFWR_save_option');
	add_action( 'add_meta_boxes','CFAFWR_global_notice_meta_box');
	add_action( 'save_post','CFAFWR_custom_meta_box_field_save');
	add_action( 'wp_ajax_cfafwr_filed_sortable','CFAFWR_filed_sortable');
	add_action( 'wp_ajax_nopriv_cfafwr_filed_sortable','CFAFWR_filed_sortable');
	add_action( 'edit_user_profile','CFAFWR_custom_user_profile_fields',100 );
	add_action( 'show_user_profile','CFAFWR_custom_user_profile_fields',100 );
	add_action( 'personal_options_update','CFAFWR_save_custom_user_profile_fields');
	add_action( 'edit_user_profile_update','CFAFWR_save_custom_user_profile_fields');
	add_filter( 'post_updated_messages','CFAFWR_save_custom_field_messages');
	add_action( 'user_edit_form_tag', 'add_multipart_enctype_to_profile_form');
	add_filter( 'manage_wporg_custom_field_posts_columns','CFAFWR_filter_posts_columns');
	add_action( 'manage_wporg_custom_field_posts_custom_column','CFAFWR_smashing_realestate_column', 10, 2);