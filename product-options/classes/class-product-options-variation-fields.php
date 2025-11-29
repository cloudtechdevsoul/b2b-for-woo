<?php

defined('ABSPATH') || exit;

/**
 * Product Level Fields
 */
class Product_Options_Variation_Fields {

	public static function ck_variation_level_fields( $prod_id ) {
		
		?>
		<div class="expand_close_btn_div ck_variation">

			<input type="submit" name="expand_all_btn" class="expand_all_btn" value="<?php echo esc_html('Expand all'); ?>">
			<span><?php echo esc_html('/'); ?></span>
			<input type="submit" name="close_all_btn" class="close_all_btn" value="<?php echo esc_html('Close all'); ?>">

		</div>

		<br>

		<div class="fields_main_div">
			<?php

			$field_ids = Prd_General_Functions::get_posts_ids_of_current_post('Product_add_field', $prod_id);
			
			foreach ( $field_ids as $field_id ) {

				if (empty($field_id)) {
					continue;
				}
				
				$field_priority       = get_post_meta($field_id, 'ck_field_priority', true);
				$field_dependable     = get_post_meta($field_id, 'ck_field_dependable_selector', true);
				$dependent_field      = get_post_meta($field_id, 'ck_fields_selector', true);
				$dependent_option     = (array) get_post_meta($field_id, 'ck_options_selector', true);
				$field_type           = get_post_meta($field_id, 'ck_fields_type', true);
				$ck_field_title       = get_post_meta($field_id, 'ck_field_title', true);
				$desc_checkbox        = get_post_meta($field_id, 'ck_add_desc', true);
				$desc_text            = get_post_meta($field_id, 'ck_add_desc', true);
				$tool_tip_checkbox    = get_post_meta($field_id, 'ck_add_desc', true);
				$tool_tip_text        = get_post_meta($field_id, 'ck_field_tool_tip', true);
				$required_checkbox    = get_post_meta($field_id, 'ck_field_tool_tip', true);
				$field_price_checkbox = get_post_meta($field_id, 'ck_field_price', true);
				$field_price_type     = get_post_meta($field_id, 'ck_field_pricing_type', true);
				$field_price          = get_post_meta($field_id, 'ck_field_price', true);
				$field_limit_checkbox = get_post_meta($field_id, 'ck_field_limit_range', true);
				$field_min_limit      = get_post_meta($field_id, 'ck_field_min_limit', true);
				$field_max_limit      = get_post_meta($field_id, 'ck_field_max_limit', true);
				$field_file_format    = get_post_meta($field_id, 'ck_field_file_extention', true);

				$field_type_array = array(
					'drop_down' 		=> 'Drop down',
					'multi_select'		=> 'Multi select',
					'check_boxes'		=> 'Check boxes',
					'input_text'		=> 'Input Text',
					'text_area'			=> 'Text area',
					'file_upload'		=> 'File upload',
					'number'			=> 'Number',
					'quantity'			=> 'Quantity',
					'radio'				=> 'Radio',
					'color'				=> 'Color',
					'image_swatcher'	=> 'Image Switcher',
					'image'				=> 'Image',
					'date'				=> 'Date Picker',
					'email'				=> 'Email',
					'password'			=> 'Password',
					'time'				=> 'Time Picker',
					'telephone'			=> 'Telephone',
				);

				$f_field_array = array( 'drop_down', 'multi_select', 'check_boxes', 'radio', 'color', 'image_swatcher', 'image' );

				$ck_field_pricing_type = array(
					'free' 								=> 'Free',
					'flat_fixed_fee'					=> 'Flat Fixed Fee',
					'flat_percentage_fee'				=> 'Flat percentage fee',
					'fixed_fee_based_on_quantity'		=> 'Fixed fee based on quantity',
					'percentage_fee_based_on_quantity'	=> 'Percentage fee based on quantity',
				);

				$ck_option_price_type = array(
					'free' 								=> 'Free',
					'flat_fixed_fee'					=> 'Flat Fixed Fee',
					'flat_percentage_fee'				=> 'Flat percentage fee',
					'fixed_fee_based_on_quantity'		=> 'Fixed fee based on quantity',
					'percentage_fee_based_on_quantity'	=> 'Percentage fee based on quantity',
				);
				?>

				<div class="field_div">

					<div class="prod_optn_remove_field_btn_main_div display_flex <?php echo esc_attr($field_id); ?>_prod_optn_remove_field_btn_main_div">

						<div class="prod_optn_type_title_show_div">
							<p>
								<b><i><?php echo esc_attr($ck_field_title); ?></i></b>
								
								<?php 
								if ($field_type) {
									?>
									<i style="opacity: 0.7"> <?php echo esc_attr($field_type_array[$field_type]); ?> </i>
									<?php
								}
								?>
							</p>
						</div>

						<div class="prod_optn_remove_field_btn_div">

							<input type="hidden" name="prod_optn_field_id" class="prod_optn_field_id" value="<?php echo esc_attr($field_id); ?>">

							<input type="hidden" name="prod_optn_rule_id" class="prod_optn_rule_id" value="<?php echo esc_attr($prod_id); ?>">

							<input type="number" name="ck_field_priority[<?php echo esc_attr($field_id); ?>]" style="height: 40px; width: 30%; vertical-align: top; float: revert;" placeholder="Field Priority" value="<?php echo esc_attr($field_priority); ?>" min="0">

							<button class="field_dlte_btn" title="Remove Field" data-field_id="<?php echo esc_attr($field_id); ?>" data-type="variation">
								<img width="30" height="30" src="<?php echo esc_url( PRO_OP_URL . 'assets/images/delete.png'); ?>"> 
							</button>

							<button class="field_close_btn <?php echo esc_attr($field_id); ?>_field_close_btn" title="Close Field" data-field_id="<?php echo esc_attr($field_id); ?>"> 
								<img width="15" height="15" src="<?php echo esc_url( PRO_OP_URL . 'assets/images/up.png'); ?>"> 
							</button>

							<button class="field_open_btn <?php echo esc_attr($field_id); ?>_field_open_btn" title="Expand Field" data-field_id="<?php echo esc_attr($field_id); ?>"> 
								<img width="15" height="15" src="<?php echo esc_url( PRO_OP_URL . 'assets/images/down.png'); ?>"> 
							</button>

						</div>

					</div>

					<div class="display_flex prod_opt_depend_main_div <?php echo esc_attr($field_id); ?>_prod_opt_depend_main_div ">

						<div class="dependent_selector_div <?php echo esc_attr($field_id); ?>_dependent_selector_div">

							<p><?php echo esc_html('Field Dependable?'); ?></p>

							<select name="ck_field_dependable_selector[<?php echo esc_attr($field_id); ?>]" class="ck_field_dependable_selector <?php echo esc_attr($field_id); ?>_ck_field_dependable_selector" data-field_id="<?php echo esc_attr($field_id); ?>">

								<option value="not_depend"
									<?php if ('not_depend' == $field_dependable) : ?>
										selected
									<?php endif ?>
								><?php echo esc_html('Not Dependable'); ?></option>

								<option value="depend"
									<?php if ('depend' == $field_dependable) : ?>
										selected
									<?php endif ?>
								><?php echo esc_html('Dependable'); ?></option>

							</select>

							<p><i><?php echo esc_html('Select if this field is depenable or not'); ?></i></p>
						</div>

						<div class="field_selector_div <?php echo esc_attr($field_id); ?>_field_selector_div">

							<p><?php echo esc_html('Select Field'); ?></p>

							<select name="ck_fields_selector[<?php echo esc_attr($field_id); ?>]" class="<?php echo esc_attr($field_id); ?>_field_selector field_selector" data-field_id="<?php echo esc_attr($field_id); ?>">
								<option value="0" selected disabled><?php echo esc_html('Select Field'); ?></option>

								<?php
								$current_field_id = $field_id;
			
								foreach ( $field_ids as $f_id ) {

									if (empty($f_id)) {
										continue;
									}

									if ( $current_field_id != $f_id ) {

										if (in_array($field_type, $f_field_array)) {

											?>
											<option value="<?php echo esc_attr($f_id); ?>"
												<?php if ($dependent_field == $f_id) : ?>
													selected
												<?php endif ?>
											>
												<?php echo esc_attr(get_post_meta($f_id, 'ck_field_title', true )); ?>
											</option>
											<?php
										}
									}
								}
								?>
							</select>

							<p><i><?php echo esc_html('Select the field'); ?></i></p>
						</div>

						<div class="option_selector_div <?php echo esc_attr($field_id); ?>_option_selector_div">

							<p><?php echo esc_html('Select Option'); ?></p>

							<select name="ck_options_selector[<?php echo esc_attr($field_id); ?>]" class="option_selector <?php echo esc_attr($field_id); ?>_option_selector" multiple style="height: 40px; width: 95%;">

								<?php
								foreach ($dependent_option as $option_ids) {
									if (empty($option_ids)) {
										continue;
									}
									?>
									<option value="<?php echo esc_attr($option_ids); ?>"
										<?php 
											echo in_array( $option_ids, $dependent_option ) ? esc_html( 'selected' ) : ''; 
										?>
										>
										<?php echo esc_attr(get_post_meta($option_ids, 'ck_option_name', true )); ?>
									</option>
									<?php
								}
								?>
							</select>

							<p><i><?php echo esc_html('Select the options'); ?></i></p>
						</div>

					</div>

					<div class="display_flex field_type_selector_main_div <?php echo esc_attr($field_id); ?>_field_type_selector_main_div">

						<div class="field_type_selector_div <?php echo esc_attr($field_id); ?>_field_type_selector_div">

							<p><?php echo esc_html('Field Type'); ?></p>

							<select name="ck_fields_type[<?php echo esc_attr($field_id); ?>]" class="ck_fields_type <?php echo esc_attr($field_id); ?>_ck_fields_type" data-field_id="<?php echo esc_attr($field_id); ?>">

								<?php foreach ($field_type_array as $fields_type_key => $fields_type_value) : ?>

									<option value="<?php echo esc_attr($fields_type_key); ?>"

										<?php if ($fields_type_key == $field_type) : ?>
											selected
										<?php endif ?>

										><?php echo esc_attr($fields_type_value); ?>
										
									</option>

								<?php endforeach ?>

							</select>

						</div>

						<div class="ck_field_title_div <?php echo esc_attr($field_id); ?>_ck_field_title_div">

							<p><?php echo esc_html('Field Title'); ?></p>

							<input type="text" name="ck_field_title[<?php echo esc_attr($field_id); ?>]" class="prd_opt_ck_field_title" required value="<?php echo esc_attr($ck_field_title); ?>" placeholder="Field Title">

						</div>

					</div>

					<div class="display_flex desc_tooltip_main_div <?php echo esc_attr($field_id); ?>_desc_tooltip_main_div">

						<div class="field_desc_div <?php echo esc_attr($field_id); ?>_field_desc_div">

							<input type="checkbox" name="ck_add_desc[<?php echo esc_attr($field_id); ?>]" value="yes" class="ck_add_desc <?php echo esc_attr($field_id); ?>_ck_add_desc" data-field_id="<?php echo esc_attr($field_id); ?>"
								<?php if ('yes' == $desc_checkbox) : ?>
									checked
								<?php endif ?>
							>

							<span><?php echo esc_html('Add Description'); ?></span>

							<br><br>

							<textarea name="ck_field_decs[<?php echo esc_attr($field_id); ?>]" class="desc_textarea <?php echo esc_attr($field_id); ?>_desc_textarea"><?php echo esc_attr($desc_text); ?></textarea>
						</div>

						<div class="field_tooltip_div <?php echo esc_attr($field_id); ?>_field_tooltip_div">

							<input type="checkbox" name="ck_add_tool_tip[<?php echo esc_attr($field_id); ?>]" value="yes" class="ck_add_tool_tip <?php echo esc_attr($field_id); ?>_ck_add_tool_tip" data-field_id="<?php echo esc_attr($field_id); ?>"
								<?php if ('yes' == $tool_tip_checkbox) : ?>
									checked
								<?php endif ?>
							>

							<span><?php echo esc_html('Add Tool Tip'); ?></span>

							<br><br>

							<textarea name="ck_field_tool_tip[<?php echo esc_attr($field_id); ?>]" class="ck_field_tool_tip <?php echo esc_attr($field_id); ?>_ck_field_tool_tip"><?php echo esc_attr($tool_tip_text); ?></textarea>

						</div>

					</div>

					<div class="display_flex prod_opt_req_main_div <?php echo esc_attr($field_id); ?>_prod_opt_req_main_div">

						<div class="prod_opt_req">

							<input type="checkbox" name="ck_req_field[<?php echo esc_attr($field_id); ?>]" value="yes"
								<?php if ('yes' == $required_checkbox) : ?>
									checked
								<?php endif ?>
							>

							<span><?php echo esc_html('Add Required Option ?'); ?></span>

						</div>

					</div>

					<div class="display_flex prod_opt_field_price_main_div <?php echo esc_attr($field_id); ?>_prod_opt_field_price_main_div">

						<div style="width: 100%; float: left; margin: 5px">

							<input type="checkbox" name="ck_field_price_checkbox[<?php echo esc_attr($field_id); ?>]" value="yes" class="<?php echo esc_attr($field_id); ?>_ck_field_price_checkbox ck_field_price_checkbox" data-field_id="<?php echo esc_attr($field_id); ?>"
								<?php if ('yes' == $field_price_checkbox) : ?>
									checked
								<?php endif ?>
							>

							<span><?php echo esc_html('Add Price ?'); ?></span>

							<br><br>

							<div class="prod_opt_field_price_type_price_div <?php echo esc_attr($field_id); ?>_prod_opt_field_price_type_price_div">
								<select name="ck_field_pricing_type[<?php echo esc_attr($field_id); ?>]" class="ck_field_pricing_type <?php echo esc_attr($field_id); ?>_ck_field_pricing_type">

									<?php foreach ($ck_field_pricing_type as $field_pricing_type_key => $field_pricing_type_value) : ?>

										<option value="<?php echo esc_attr($field_pricing_type_key); ?>"
											<?php if ($field_pricing_type_key == $field_price_type) : ?>
												selected
											<?php endif ?>
											>
											<?php echo esc_attr($field_pricing_type_value); ?>
										</option>

									<?php endforeach ?>

								</select>

								<input type="number" name="ck_field_price[<?php echo esc_attr($field_id); ?>]" value="<?php echo esc_attr($field_price); ?>" class="ck_field_price <?php echo esc_attr($field_id); ?>_ck_field_price" placeholder="Field Price" min="1">

								<p><i><?php echo esc_html('Select price type and price.'); ?></i></p>

							</div>

						</div>

					</div>

					<div class="display_flex prod_opt_limit_range_main_div <?php echo esc_attr($field_id); ?>_prod_opt_limit_range_main_div">

						<div style="width: 100%; float: left; margin: 5px">

							<input type="checkbox" name="ck_field_limit_range[<?php echo esc_attr($field_id); ?>]" value="yes" class="ck_field_limit_range <?php echo esc_attr($field_id); ?>_ck_field_limit_range" data-field_id="<?php echo esc_attr($field_id); ?>"
								<?php if ('yes' == $field_limit_checkbox) : ?>
									checked
								<?php endif ?>
							>

							<span><?php echo esc_html('Set Limit Range ?'); ?></span>

							<br><br>

							<div class="prod_opt_field_limit_to_div <?php echo esc_attr($field_id); ?>_prod_opt_field_limit_to_div">

								<input type="number" name="ck_field_min_limit[<?php echo esc_attr($field_id); ?>]" value="<?php echo esc_attr($field_min_limit); ?>" class="ck_field_min_limit <?php echo esc_attr($field_id); ?>_ck_field_min_limit" placeholder="Minimum Limit" style="float: none; height: 40px; width: 49% !important;" min="1">

								<span><?php echo esc_html('to'); ?></span>

								<input type="number" name="ck_field_max_limit[<?php echo esc_attr($field_id); ?>]" value="<?php echo esc_attr($field_max_limit); ?>" class="ck_field_max_limit <?php echo esc_attr($field_id); ?>_ck_field_max_limit" placeholder="Maximum Limit" style="float: none; height: 40px; width: 49% !important;" min="1">

								<p><i><?php echo esc_html('Select limit range.'); ?></i></p>

							</div>

						</div>

					</div>

					<div class="display_flex prod_opt_file_extention_main_div <?php echo esc_attr($field_id); ?>_prod_opt_file_extention_main_div">

						<div style="width: 100%; float: left; margin: 5px">

							<p><?php echo esc_html('Set File Format ?'); ?></p>

							<input type="text" name="ck_field_file_extention[<?php echo esc_attr($field_id); ?>]" value="<?php echo esc_attr($field_file_format); ?>" class="ck_field_file_extention">

							<p><i><?php echo esc_html('Enter file extention (Comma Seperated).'); ?></i></p>
							<p><i><?php echo esc_html('e.g, jpg, jpeg etc'); ?></i></p>

						</div>

					</div>

					<div class="display_flex prod_opt_options_table_main_div <?php echo esc_attr($field_id); ?>_prod_opt_options_table_main_div">

						<div style="width: 100%;">

							<table class="prod_opt_table <?php echo esc_attr($field_id); ?>_prod_opt_table">

								<thead>

									<tr>

										<th><?php echo esc_html('Option'); ?></th>
										<th><?php echo esc_html('Price'); ?></th>
										<th><?php echo esc_html('Priority'); ?></th>
										<th><?php echo esc_html('Remove'); ?></th>

									</tr>

								</thead>

								<tbody>

									<?php

									$option_idss = Prd_General_Functions::get_posts_ids_of_current_post('product_add_option', $field_id);
				
									foreach ( $option_idss as $option_id ) {

										if (empty($option_id)) {
											continue;
										}

										$image             = get_post_meta($option_id, 'ck_options_image', true);
										$option_color      = get_post_meta($option_id, 'ck_option_color', true);
										$option_name       = get_post_meta($option_id, 'ck_option_name', true);
										$option_price_type = get_post_meta($option_id, 'ck_option_price_type', true);
										$option_price      = get_post_meta($option_id, 'ck_option_price', true);
										$option_priority   = get_post_meta($option_id, 'ck_option_priority', true);

										?>

										<tr class="prod_optn_option_tr" data-field_id="<?php echo esc_attr($field_id); ?>" data-option_id="<?php echo esc_attr($option_id); ?>">
											
											<td>

												<div class="prod_opt_color_img_div <?php echo esc_attr($field_id); ?>_prod_opt_color_img_div">

													<div class="prod_opt_color_div <?php echo esc_attr($field_id); ?>_prod_opt_color_div">

														<input type="color" name="ck_option_color[<?php echo esc_attr($field_id); ?>][<?php echo esc_attr($option_id); ?>]" value="<?php echo esc_attr($option_color); ?>" style="padding: unset; min-width: auto; width: revert;">

													</div>

													<div class="prod_opt_img_div <?php echo esc_attr($field_id); ?>_prod_opt_img_div">

														<input type="hidden" name="hidden_optn_id" class="hidden_optn_id" value="<?php echo esc_attr($option_id); ?>">

														<input type="hidden" name="hidden_field_id" class="hidden_field_id" value="<?php echo esc_attr($field_id); ?>">

														<button class="prod_opt_add_img_btn <?php echo esc_attr($field_id); ?>_prod_opt_add_img_btn_<?php echo esc_attr($option_id); ?>" title="Select image"
															<?php if (!empty($image)) : ?>
																style = 'display: none;'
															<?php endif ?>
														>
															<img width="40" height="40" src="<?php echo esc_url( PRO_OP_URL . 'assets/images/add_image.png'); ?>">
														</button>

														<input type="hidden" class="options_image[<?php echo esc_attr($field_id); ?>][<?php echo esc_attr($option_id); ?>]" value="<?php echo esc_url( $image ); ?>" name="ck_options_image[<?php echo esc_attr($field_id); ?>][<?php echo esc_attr($option_id); ?>]" id="<?php echo esc_attr($field_id); ?>image_upload<?php echo esc_attr($option_id); ?>" class="login_title">

														<img class="<?php echo esc_attr($field_id); ?>_option_image_<?php echo esc_attr($option_id); ?> option_image"  <?php if (empty($image)) : ?>
															style = 'display: none;'
														<?php endif ?>  src="<?php echo esc_url( $image ); ?>"/>

														<button id="remove_option_image<?php echo esc_attr($option_id); ?>"  class="remove_option_image" <?php if (empty($image)) : ?>
															style = 'display: none;'
														<?php endif ?> title="Remove Image">
															<img width="15" height="13" src="<?php echo esc_url( PRO_OP_URL . 'assets/images/remove_image.png'); ?>">
														</button>

													</div>

													<div class="prod_opt_optn_name_div <?php echo esc_attr($field_id); ?>_prod_opt_optn_name_div">

														<input type="text" name="ck_option_name[<?php echo esc_attr($field_id); ?>][<?php echo esc_attr($option_id); ?>]" style="height: 40px; width: 100%" value="<?php echo esc_attr($option_name); ?>" class="ck_option_name <?php echo esc_attr($field_id); ?>_ck_option_name" placeholder="Option Name">

													</div>

												</div>

											</td>

											<td>
												<select name="ck_option_price_type[<?php echo esc_attr($field_id); ?>][<?php echo esc_attr($option_id); ?>]" class="ck_option_price_type" style="width: 49% !important;">

													<?php foreach ($ck_option_price_type as $option_pricing_type_key => $option_pricing_type_value) : ?>

														<option value="<?php echo esc_attr($option_pricing_type_key); ?>"
															<?php if ($option_pricing_type_key == $option_price_type) : ?>
																selected
															<?php endif ?>
															>
															<?php echo esc_attr($option_pricing_type_value); ?>
														</option>

													<?php endforeach ?>

												</select>

												<input type="number" name="ck_option_price[<?php echo esc_attr($field_id); ?>][<?php echo esc_attr($option_id); ?>]" value="<?php echo esc_attr($option_price); ?>" placeholder="Option Price" style="width: 48% !important;height: 40px; float: right !important; min-width: unset !important;">
											</td>

											<td>
												<input type="number" name="ck_option_priority[<?php echo esc_attr($field_id); ?>][<?php echo esc_attr($option_id); ?>]" class="ck_option_priority" value="<?php echo esc_attr($option_priority); ?>" placeholder="Option Priority" style="float: right;">
											</td>

											<td>
												<button class="optn_remove_btn" title="Remove Option" data-option_id="<?php echo esc_attr($option_id); ?>">
													<img width="30" height="30" src="<?php echo esc_url( PRO_OP_URL . 'assets/images/remove.png'); ?>">
												</button>
											</td>

										</tr>

										<?php

									}

									?>

								</tbody>

							</table>

						</div>

					</div>

					<br>

					<div class="prod_opt_add_optn_btn_div <?php echo esc_attr($field_id); ?>_prod_opt_add_optn_btn_div">

						<button class="option_add_btn" title="Add Option" data-parent_id="<?php echo esc_attr($field_id); ?>" data-type="variation">

							<img width="30" height="30" src="<?php echo esc_url( PRO_OP_URL . 'assets/images/add_option.png'); ?>"> 

						</button>
					</div>

				</div>

				<?php
			}
			?>

		</div>

		<br>

		<div class="prod_opt_add_field_btn_main_div">

			<div class="display_flex">

				<div class="prod_opt_add_field_btn">

					<button class="field_add_btn" title="Add Field" data-rule_id="<?php echo esc_attr($prod_id); ?>" data-type="rule">

						<img width="30" height="30" src="<?php echo esc_url( PRO_OP_URL . 'assets/images/add.png'); ?>"> 
					</button>
				</div>

				<div class="prod_opt_csv_main_div">

					<form method="POST" enctype="multipart/form-data">
						<?php wp_nonce_field('csv_form_nonce', 'csv_form_nonce_field'); ?>
						<input type="file" name="prd_opt_csv_upload" class="prd_opt_csv_upload">

						<button class="csv_upload_btn" title="Upload CSV" name="csv_upload_btn">
							<img width="30" height="30" src="<?php echo esc_url( PRO_OP_URL . 'assets/images/upload.png'); ?>"> 
						</button>

						<button class="csv_download_btn" title="Download CSV" name="csv_download_btn">
							<img width="30" height="30" src="<?php echo esc_url( PRO_OP_URL . 'assets/images/download.png'); ?>"> 
						</button>

						<input type="hidden" name="current_rule_id" value="<?php echo intval($prod_id); ?>">

					</form>
				</div>

			</div>

		</div>
		<?php
	}
}

new Product_Options_Variation_Fields();
