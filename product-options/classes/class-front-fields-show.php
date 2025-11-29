<?php

defined('ABSPATH') || exit;

/**
 * Front Fields Show Class
 */
class Front_Fields_Show {

	public static function prod_optn_rule_front_fields( $rule_id ) {

		$fields = Prd_General_Functions::get_posts_ids_of_current_post('product_add_field', $rule_id);

		foreach ( $fields as $field_id ) {

			if ( empty( $field_id ) ) {

				continue;

			}	

			$f_field_id        = 'prod_field_' . $field_id;
			$dependent_fields  = '';
			$dependent_options = '';
			$front_dep_class   = '';

			if ('depend' == get_post_meta($field_id, 'ck_field_dependable_selector', true)) {

				$dependent_fields  = get_post_meta( $field_id, 'ck_fields_selector', true );
				$dependent_options = (array) get_post_meta( $field_id, 'ck_options_selector', true );
				$dependent_options = implode(',', $dependent_options);
				$front_dep_class   = 'front_dep_class';

			}

			$type_selected_file_path = array(
				'drop_down' 		=> 'drop_down.php',
				'multi_select'		=> 'multi_select.php',
				'check_boxes'		=> 'check_box.php',
				'input_text'		=> 'input_text.php',
				'text_area'			=> 'text_area.php',
				'file_upload'		=> 'file_upload.php',
				'number'			=> 'number.php',
				'quantity'			=> 'quantity.php',
				'radio'				=> 'radio.php',
				'color'				=> 'color.php',
				'image_swatcher'	=> 'image_switcher.php',
				'image'				=> 'image.php',
				'date'				=> 'date_picker.php',
				'email'				=> 'email.php',
				'password'			=> 'password.php',
				'time'				=> 'time_picker.php',
				'telephone'			=> 'telephone.php',
			);

			$field_border            = get_option('field_border_checkbox');
			$ck_field_title_position = get_option('field_title_border_position');
			$title_display           = get_option('title_display');
			$heading_type            = get_option('heading_type');
			$ck_field_title          = get_post_meta($field_id, 'ck_field_title', true);
			$tool_tip_checkbox       = get_post_meta($field_id, 'ck_add_tool_tip', true);
			$tool_tip_text           = get_post_meta($field_id, 'ck_field_tool_tip', true);
			$type_selected_option    = get_post_meta($field_id, 'ck_fields_type', true);
			$desc_checkbox           = get_post_meta($field_id, 'ck_add_desc', true);
			$desc_text               = get_post_meta($field_id, 'ck_field_decs', true);
			$seperator_checkbox      = get_option('seperator_checkbox');
			$title_seperator         = get_option('field_seperator');
			?>
			<div class="prod_optn_field_class">
				<?php
				if ('heading' == $title_display) {

					if ('1' == $field_border && 'inside_border' == $ck_field_title_position) {

						?>
						<div class="addon_field_border_<?php echo intval($rule_id); ?>">

							<div class="af_addon_front_ck_field_title_div_<?php echo intval($rule_id); ?>">

								<?php
								if ('h1' == $heading_type) {

									?>
									<h1 class="addon_heading_styling_<?php echo intval($rule_id); ?>"><?php echo esc_attr($ck_field_title); ?>
										<?php
										if ('yes' == $tool_tip_checkbox) {
											?>
											<span class="tooltip_<?php echo esc_attr($rule_id); ?>">
												<i class="fa fa-question-circle"></i>
												<span class="tooltiptext_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr($tool_tip_text); ?></span>
											</span>
											<?php
										}
										?>
									</h1>
									<?php
								}
								if ('h2' == $heading_type) {
									?>
									<h2 class="addon_heading_styling_<?php echo intval($rule_id); ?>"><?php echo esc_attr($ck_field_title); ?>
										<?php
										if ('yes' == $tool_tip_checkbox) {
											?>
											<span class="tooltip_<?php echo esc_attr($rule_id); ?>">
												<i class="fa fa-question-circle"></i>
												<span class="tooltiptext_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr($tool_tip_text); ?></span>
											</span>
											<?php
										}
										?>
									</h2>
									<?php
								}
								if ('h3' == $heading_type) {
									?>
									<h3 class="addon_heading_styling_<?php echo intval($rule_id); ?>"><?php echo esc_attr($ck_field_title); ?>
										<?php
										if ('yes' == $tool_tip_checkbox) {
											?>
											<span class="tooltip_<?php echo esc_attr($rule_id); ?>">
												<i class="fa fa-question-circle"></i>
												<span class="tooltiptext_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr($tool_tip_text); ?></span>
											</span>
											<?php
										}
										?>
									</h3>
									<?php
								}
								if ('h4' == $heading_type) {
									?>
									<h4 class="addon_heading_styling_<?php echo intval($rule_id); ?>"><?php echo esc_attr($ck_field_title); ?>
										<?php
										if ('yes' == $tool_tip_checkbox) {
											?>
											<span class="tooltip_<?php echo esc_attr($rule_id); ?>">
												<i class="fa fa-question-circle"></i>
												<span class="tooltiptext_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr($tool_tip_text); ?></span>
											</span>
											<?php
										}
										?>
									</h4>
									<?php
								}
								if ('h5' == $heading_type) {
									?>
									<h5 class="addon_heading_styling_<?php echo intval($rule_id); ?>"><?php echo esc_attr($ck_field_title); ?>
										<?php
										if ('yes' == $tool_tip_checkbox) {
											?>
											<span class="tooltip_<?php echo esc_attr($rule_id); ?>">
												<i class="fa fa-question-circle"></i>
												<span class="tooltiptext_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr($tool_tip_text); ?></span>
											</span>
											<?php
										}
										?>
									</h5>
									<?php
								}
								if ('h6' == get_post_meta($rule_id, 'af_addon_heading_type_selector', true)) {
									?>
									<h6 class="addon_heading_styling_<?php echo intval($rule_id); ?>"><?php echo esc_attr($ck_field_title); ?>
										<?php
										if ('yes' == $tool_tip_checkbox) {
											?>
											<span class="tooltip_<?php echo esc_attr($rule_id); ?>">
												<i class="fa fa-question-circle"></i>
												<span class="tooltiptext_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr($tool_tip_text); ?></span>
											</span>
											<?php
										}
										?>
									</h6>
									<?php
								}	
								?>
							</div>
							<?php 
								include PRO_OP_PLUGIN_DIR . 'includes/front/fields/' . $type_selected_file_path[$type_selected_option];
							?>
						</div>
						<?php
					} else {
						?>
						<div class="af_addon_front_ck_field_title_div_<?php echo intval($rule_id); ?>">

							<?php
							if ('h1' == $heading_type) {

								?>
								<h1 class="addon_heading_styling_<?php echo intval($rule_id); ?>"><?php echo esc_attr($ck_field_title); ?>
									<?php
									if ('yes' == $tool_tip_checkbox) {
										?>
										<span class="tooltip_<?php echo esc_attr($rule_id); ?>">
											<i class="fa fa-question-circle"></i>
											<span class="tooltiptext_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr($tool_tip_text); ?></span>
										</span>
										<?php
									}
									?>
								</h1>
								<?php
							}
							if ('h2' == $heading_type) {
								?>
								<h2 class="addon_heading_styling_<?php echo intval($rule_id); ?>"><?php echo esc_attr($ck_field_title); ?>
									<?php
									if ('yes' == $tool_tip_checkbox) {
										?>
										<span class="tooltip_<?php echo esc_attr($rule_id); ?>">
											<i class="fa fa-question-circle"></i>
											<span class="tooltiptext_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr($tool_tip_text); ?></span>
										</span>
										<?php
									}
									?>
								</h2>
								<?php
							}
							if ('h3' == $heading_type) {
								?>
								<h3 class="addon_heading_styling_<?php echo intval($rule_id); ?>"><?php echo esc_attr($ck_field_title); ?>
									<?php
									if ('yes' == $tool_tip_checkbox) {
										?>
										<span class="tooltip_<?php echo esc_attr($rule_id); ?>">
											<i class="fa fa-question-circle"></i>
											<span class="tooltiptext_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr($tool_tip_text); ?></span>
										</span>
										<?php
									}
									?>
								</h3>
								<?php
							}
							if ('h4' == $heading_type) {
								?>
								<h4 class="addon_heading_styling_<?php echo intval($rule_id); ?>"><?php echo esc_attr($ck_field_title); ?>
									<?php
									if ('yes' == $tool_tip_checkbox) {
										?>
										<span class="tooltip_<?php echo esc_attr($rule_id); ?>">
											<i class="fa fa-question-circle"></i>
											<span class="tooltiptext_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr($tool_tip_text); ?></span>
										</span>
										<?php
									}
									?>
								</h4>
								<?php
							}
							if ('h5' == $heading_type) {
								?>
								<h5 class="addon_heading_styling_<?php echo intval($rule_id); ?>"><?php echo esc_attr($ck_field_title); ?>
									<?php
									if ('yes' == $tool_tip_checkbox) {
										?>
										<span class="tooltip_<?php echo esc_attr($rule_id); ?>">
											<i class="fa fa-question-circle"></i>
											<span class="tooltiptext_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr($tool_tip_text); ?></span>
										</span>
										<?php
									}
									?>
								</h5>
								<?php
							}
							if ('h6' == get_post_meta($rule_id, 'af_addon_heading_type_selector', true)) {
								?>
								<h6 class="addon_heading_styling_<?php echo intval($rule_id); ?>"><?php echo esc_attr($ck_field_title); ?>
									<?php
									if ('yes' == $tool_tip_checkbox) {
										?>
										<span class="tooltip_<?php echo esc_attr($rule_id); ?>">
											<i class="fa fa-question-circle"></i>
											<span class="tooltiptext_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr($tool_tip_text); ?></span>
										</span>
										<?php
									}
									?>
								</h6>
								<?php
							}	
							?>
						</div>
						<div class="addon_field_border_<?php echo intval($rule_id); ?>">
							<?php 
								include PRO_OP_PLUGIN_DIR . 'includes/front/fields/' . $type_selected_file_path[$type_selected_option];
							?>
						</div>
						<?php
					}
					?>
					<?php
				
				} elseif ('text' == $title_display) {

					if ('1' == $field_border && 'inside_border' == $ck_field_title_position) {

						?>
						<div class="addon_field_border_<?php echo intval($rule_id); ?>">

							<div class="af_addon_front_ck_field_title_div_<?php echo intval($rule_id); ?>">

								<span><?php echo esc_attr($ck_field_title); ?></span>

								<?php
								if ('yes' == $tool_tip_checkbox) {
									?>
									<span class="tooltip_<?php echo esc_attr($rule_id); ?>">
										<i class="fa fa-question-circle"></i>
										<span class="tooltiptext_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr($tool_tip_text); ?></span>
									</span>
									<?php
								}
								?>
							</div>
							<?php 
								include PRO_OP_PLUGIN_DIR . 'includes/front/fields/' . $type_selected_file_path[$type_selected_option];
							?>
						</div>
						<?php
					} else {
						?>
						<div class="af_addon_front_ck_field_title_div_<?php echo intval($rule_id); ?>">
							<span><?php echo esc_attr($ck_field_title); ?></span>
							<?php
							if ('yes' == $tool_tip_checkbox) {
								?>
								<span class="tooltip_<?php echo esc_attr($rule_id); ?>">
									<i class="fa fa-question-circle"></i>
									<span class="tooltiptext_<?php echo esc_attr($rule_id); ?>"><?php echo esc_attr($tool_tip_text); ?></span>
								</span>
								<?php
							}
							?>
						</div>
						<div class="addon_field_border_<?php echo intval($rule_id); ?>">
							
							<?php 
								include PRO_OP_PLUGIN_DIR . 'includes/front/fields/' . $type_selected_file_path[$type_selected_option];
							?>
						</div>
						<?php
					}

				} elseif ('none' == $title_display) {

					if ('1' == $field_border) {
						?>
						<div class="addon_field_border_<?php echo intval($rule_id); ?>">
							<?php 
								include PRO_OP_PLUGIN_DIR . 'includes/front/fields/' . $type_selected_file_path[$type_selected_option];
							?>
						</div>
						<?php
					} else {
						 
						include PRO_OP_PLUGIN_DIR . 'includes/front/fields/' . $type_selected_file_path[$type_selected_option];
					}
				}

				if ('yes' == $desc_checkbox) {
					?>
					<p class="add_on_description_style_<?php echo intval($rule_id); ?>"><?php echo esc_attr( $desc_text); ?> </p>
					<?php
				}
				?>
				<br>
				<?php
				if ('1' == $seperator_checkbox) {

					if ('br' == $title_seperator) {
						?>
						<br>
						<?php
					}
					if ('hr' == $title_seperator) {
						?>
						<hr>
						<?php	
					}
				}
				?>
			</div>
			<?php

		}
	}
}

new Front_Fields_Show();
