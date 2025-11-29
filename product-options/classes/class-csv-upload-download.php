<?php
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Import Export CSV
 */
class Csv_Upload_Download {


	public static function import_csv( $post_data, $file_data) {

		// Allowed filetypes for import.
		$rule_id = isset($post_data['current_rule_id']) ? sanitize_text_field($post_data['current_rule_id']) : '';

		$valid_file_types = array(
			'csv' => 'text/csv',
		);

		// Retrieve the file type from the file name.
		if (isset($file_data['prd_opt_csv_upload']['name'])) {
			$filetype = wp_check_filetype(sanitize_text_field(wp_unslash($file_data['prd_opt_csv_upload']['name'])) , $valid_file_types);
		}

		// Check if file type is valid.
		if (in_array($filetype['type'], $valid_file_types, true)) {

			$target_dir = PRO_OP_PLUGIN_DIR . 'includes/admin/uploaded_csv/';

			if (isset($file_data['prd_opt_csv_upload']['name'])) {
				$target_dir = $target_dir . basename(sanitize_text_field(isset($file_data['prd_opt_csv_upload']['name'])));
			}

			$uploadOk = true;
			if (isset($file_data['prd_opt_csv_upload']['tmp_name'])) {
				$tempo_name = sanitize_text_field($file_data['prd_opt_csv_upload']['tmp_name']);
			}

			if (move_uploaded_file($tempo_name, $target_dir)) {

				$handle = fopen($target_dir, 'r');
				if (false !== $handle) {
					$data2 = fgetcsv($handle, 1000, ',');
					$row   = 1;
					while (( $data = fgetcsv($handle, 1000, ',') ) !== false) {

						if ($row >= 1) {

							$field_id = ck_import_csv($data[0]);

							$create_new_field_flag = false;

							if (!empty($field_id)) {

								if ('product_add_field' == get_post_type($field_id)) {

									if ( wp_get_post_parent_id($field_id) != $rule_id) {
										$create_new_field_flag = true;
									} else {
										$field_id = $field_id;
									}

								} else {
									$create_new_field_flag = true;
								}
							} else {
								$create_new_field_flag = true;
							}

							if ($create_new_field_flag) {

								$field_id = wp_insert_post(
									array(
										'post_type' 	=> 'product_add_field',
										'post_status' 	=> 'publish',
										'post_parent' 	=> $rule_id,
									)
								);
							}

							$field_id =	 (int) $field_id;

							$field_priority           	= ck_import_csv($data[1]);
							$field_type               	= ck_import_csv($data[2]);
							$field_title             	= ck_import_csv($data[3]);
							$field_enable_tooltip     	= ck_import_csv($data[4]);
							$field_tooltip            	= ck_import_csv($data[5]);
							$field_enable_description 	= ck_import_csv($data[6]);
							$field_desc               	= ck_import_csv($data[7]);
							$field_enable_required    	= ck_import_csv($data[8]);
							$field_enable_limit_range 	= ck_import_csv($data[9]);
							$field_min_limit_range    	= ck_import_csv($data[10]);
							$field_max_limit_range    	= ck_import_csv($data[11]);
							$field_field_price_range  	= ck_import_csv($data[12]);
							$field_field_price_type   	= ck_import_csv($data[13]);
							$field_field_price        	= ck_import_csv($data[14]);
							$field_field_file_extention = ck_import_csv($data[15]);
							$field_option_id          	= ck_import_csv($data[16]);
							$field_option_image       	= ck_import_csv($data[17]);
							$field_option_color       	= ck_import_csv($data[18]);
							$field_option_title       	= ck_import_csv($data[19]);
							$field_option_price_type  	= ck_import_csv($data[20]);
							$field_option_price       	= ck_import_csv($data[21]);
							$field_option_priority    	= ck_import_csv($data[22]);

							$option_id_array         = explode(',', $field_option_id);
							$option_image_array      = explode('/optn_img/', $field_option_image);
							$option_name_array       = explode(',', $field_option_title);
							$option_color_array      = explode(',', $field_option_color);
							$option_price_type_array = explode(',', $field_option_price_type);
							$option_price_array      = explode(',', $field_option_price);
							$option_priority_array   = explode(',', $field_option_priority);

							$option_name_count = count($option_name_array);

							$field_type_array = array('drop_down', 'multi_select', 'check_boxes', 'radio', 'color', 'image_swatcher', 'image');

							if (in_array($field_type, $field_type_array)) {

								if (!empty(array_filter($option_id_array))) {

									foreach ($option_id_array as $index => $option_id) {

										$create_new_option_flag = false;

										if ('product_add_option' == get_post_type($option_id)) {

											if ( wp_get_post_parent_id($option_id) != $field_id) {
												$create_new_option_flag = true;
											} else {
												$new_insert_option = $option_id;
											}

										} else {
											$create_new_option_flag = true;
										}

										if ($create_new_option_flag) {

											$new_insert_option = wp_insert_post(
												array(
													'post_type' => 'product_add_option',
													'post_status' => 'publish',
													'post_parent' => $field_id,
												)
											);

										}
										
										if (!empty($option_image_array[$index])) {

											update_post_meta($new_insert_option, 'ck_options_image', $option_image_array[$index]);
											$option_image_name          = explode('/', $option_image_array[$index]);
											$option_image_extention_get = explode('.', end($option_image_name));
											$option_image_extention     = end($option_image_extention_get);

											$wp_image_extentions_arrays = array('jpg', 'jpeg', 'png', 'gif', 'ico' );

											if ( !empty($option_image_extention) && ( in_array($option_image_extention, $wp_image_extentions_arrays) ) ) {

												$output = wp_remote_get( $option_image_array[$index], array( 'timeout' => 45 ) );

												$exploded_img_name = explode('/', $option_image_array[$index]);
												$exploded_img_name = end($exploded_img_name);

												$target_img_dir = PRO_OP_MEDIA_PATH . $exploded_img_name;

												$img_file = fopen($target_img_dir, 'w+');

												if (false != $img_file && !empty( $output['body'] )) {

													$file_write = fwrite($img_file, $output['body']);

													$file_name    = 'product-options/' . $exploded_img_name;
													$img_filetype = wp_check_filetype($target_img_dir, null );
													$mime_type    = $img_filetype['type'];

													$attachment = array(
														'post_mime_type' => $mime_type,
														'post_title' 	 => preg_replace('/\.[^.]+$/', '', basename($file_name)),
														'post_name' 	 => preg_replace('/\.[^.]+$/', '', basename($file_name)),
														'post_type' 	 => 'product_add_option',
														'post_status'	 => 'publish',
														'post_parent' 	 => $new_insert_option,
														'file'           => $target_img_dir,
													);

													$attachment_id = wp_insert_attachment( $attachment );

													if (! function_exists('wp_generate_attachment_metadata')) {
														include ABSPATH . 'wp-admin/includes/image.php';
													}

													$attach_data = wp_generate_attachment_metadata( $attachment_id, $target_img_dir );
													wp_update_attachment_metadata( $attachment_id, $attach_data );
													set_post_thumbnail( $new_insert_option, $attachment_id );

													set_post_thumbnail( $new_insert_option, $attachment_id );
												}
											}
										}

										if (!empty($option_color_array[$index])) {

											update_post_meta($new_insert_option, 'ck_option_color', $option_color_array[$index]);
										}

										if (!empty($option_name_array[$index])) {

											update_post_meta($new_insert_option, 'ck_option_name', $option_name_array[$index]);
											wp_update_post(
												array(
													'post_type' 	=> 'product_add_option',
													'numberposts' 	=> -1,
													'post_status'	=> 'publish',
													'ID'			=> $option_id,
													'post_title' 	=> $option_name_array[$index], 
												)
											);
										}

										if (!empty($option_price_type_array[$index])) {

											update_post_meta($new_insert_option, 'ck_option_price_type', $option_price_type_array[$index]);
										}

										if (!empty($option_price_array[$index])) {

											update_post_meta($new_insert_option, 'ck_option_price', $option_price_array[$index]);
										}

										if (!empty($option_priority_array[$index])) {

											update_post_meta($new_insert_option, 'ck_option_priority', $option_priority_array[$index]);

											wp_update_post(
												array(
													'post_type' 	=> 'product_add_option',
													'numberposts' 	=> -1,
													'post_status'	=> 'publish',
													'ID'			=> $new_insert_option,
													'menu_order' 	=> $option_priority_array[$index],
												)
											);
										}
									}
								} else {

									foreach ($option_name_array as $i => $value) {

										$new_insert_option = wp_insert_post(
											array(
												'post_type' 	=> 'product_add_option',
												'post_status' 	=> 'publish',
												'post_parent' 	=> $field_id,
											)
										);

										if (!empty($option_image_array[$i])) {

											update_post_meta($new_insert_option, 'ck_options_image', $option_image_array[$i]);
											$option_image_name          = explode('/', $option_image_array[$i]);
											$option_image_extention_get = explode('.', end($option_image_name));
											$option_image_extention     = end($option_image_extention_get);

											$wp_image_extentions_arrays = array('jpg', 'jpeg', 'png', 'gif', 'ico' );

											if ( !empty($option_image_extention) && ( in_array($option_image_extention, $wp_image_extentions_arrays) ) ) {

												$output = wp_remote_get( $option_image_array[$i], array( 'timeout' => 45 ) );

												$exploded_img_name = explode('/', $option_image_array[$i]);
												$exploded_img_name = end($exploded_img_name);

												$target_img_dir = PRO_OP_MEDIA_PATH . $exploded_img_name;

												$img_file = fopen($target_img_dir, 'w+');

												if (false != $img_file && !empty( $output['body'] )) {

													$file_write = fwrite($img_file, $output['body']);

													$file_name    = 'product-options/' . $exploded_img_name;
													$img_filetype = wp_check_filetype($target_img_dir, null );
													$mime_type    = $img_filetype['type'];

													$attachment = array(
														'post_mime_type' => $mime_type,
														'post_title' 	 => preg_replace('/\.[^.]+$/', '', basename($file_name)),
														'post_name' 	 => preg_replace('/\.[^.]+$/', '', basename($file_name)),
														'post_type' 	 => 'product_add_option',
														'post_status'	 => 'publish',
														'post_parent' 	 => $new_insert_option,
														'file'           => $target_img_dir,
													);

													$attachment_id = wp_insert_attachment( $attachment );

													if (! function_exists('wp_generate_attachment_metadata')) {
														include ABSPATH . 'wp-admin/includes/image.php';
													}

													$attach_data = wp_generate_attachment_metadata( $attachment_id, $target_img_dir );
													wp_update_attachment_metadata( $attachment_id, $attach_data );
													set_post_thumbnail( $new_insert_option, $attachment_id );

													set_post_thumbnail( $new_insert_option, $attachment_id );
												}
											}
										}

										if (!empty($option_color_array[$i])) {

											update_post_meta($new_insert_option, 'ck_option_color', $option_color_array[$i]);

										}

										if (!empty($value)) {

											update_post_meta($new_insert_option, 'ck_option_name', $value);
										}

										if (!empty($option_price_type_array[$i])) {

											update_post_meta($new_insert_option, 'ck_option_price_type', $option_price_type_array[$i]);

										}

										if (!empty($option_price_array[$i])) {

											update_post_meta($new_insert_option, 'ck_option_price', $option_price_array[$i]);

										}

										if (!empty($option_priority_array[$i])) {

											update_post_meta($new_insert_option, 'ck_option_priority', $option_priority_array[$i]);

											wp_update_post(
												array(
													'post_type' 	=> 'product_add_option',
													'post_status'	=> 'publish',
													'ID'			=> $new_insert_option,
													'menu_order' 	=> $option_priority_array[$index],
												)
											);
										}
									}
								}
							}

							update_post_meta($field_id, 'ck_field_priority', $field_priority);

							wp_update_post(
								array(
									'post_type' 	=> 'product_add_field',
									'post_status'	=> 'publish',
									'ID'			=> $field_id,
									'menu_order' 	=> $field_priority,
								)
							);

							update_post_meta($field_id, 'ck_fields_type', $field_type);
							
							update_post_meta($field_id, 'ck_field_title', $field_title);

							wp_update_post(
								array(
									'post_type' 	=> 'Product_add_field',
									'numberposts' 	=> -1,
									'post_status'	=> 'publish',
									'ID'			=> $field_id,
									'post_title' 	=> $field_title, 
								)
							);

							if ('Checked' == $field_enable_tooltip) {
								update_post_meta($field_id, 'ck_add_tool_tip', 'yes');
							} else {
								update_post_meta($field_id, 'ck_add_tool_tip', 'no');
							}

							update_post_meta($field_id, 'ck_field_tool_tip', $field_tooltip);

							if ('Checked' == $field_enable_description) {
								update_post_meta($field_id, 'ck_add_desc', 'yes');
							} else {
								update_post_meta($field_id, 'ck_add_desc', 'no');
							}

							update_post_meta($field_id, 'ck_field_decs', $field_desc);

							if ('Checked' == $field_enable_required) {
								update_post_meta($field_id, 'ck_req_field', 'yes');
							} else {
								update_post_meta($field_id, 'ck_req_field', 'no');
							}

							if ('Checked' == $field_enable_limit_range) {
								update_post_meta($field_id, 'ck_field_limit_range', 'yes');
							} else {
								update_post_meta($field_id, 'ck_field_limit_range', 'no');
							}

							update_post_meta($field_id, 'ck_field_min_limit', $field_min_limit_range);

							update_post_meta($field_id, 'ck_field_max_limit', $field_max_limit_range);

							if ('Checked' == $field_field_price_range) {
								update_post_meta($field_id, 'ck_field_price_checkbox', 'yes');
							} else {
								update_post_meta($field_id, 'ck_field_price_checkbox', 'no');
							}

							update_post_meta($field_id, 'ck_field_pricing_type', $field_field_price_type);

							update_post_meta($field_id, 'ck_field_price', $field_field_price);
							
							update_post_meta($field_id, 'ck_field_file_extention', $field_field_file_extention);
						}
						$row++;
					}
					fclose($handle);
				}
			}
		}
	}

	public static function export_csv( $post_data ) {

		$fields = array(
			'Field id',
			'Field Priority',
			'Field Type',
			'Title',
			'Enable Tooltip',
			'Tooltip',
			'Enable Description',
			'Description',
			'Enable Required Field',
			'Enable Limit Range',
			'Minimum Limit Range',
			'Maximum Limit Range',
			'Enable Price Range',
			'Price Type',
			'Price',
			'File Type',
			'Option id',
			'Option Image',
			'Option Color',
			'Option Title',
			'Option Price Type',
			'Option Price',
			'Option Priority'
		);

		$field_id             = '';
		$field_priority       = '';
		$field_type           = '';
		$ck_field_title       = '';
		$tool_tip_checkbox    = '';
		$field_tooltip        = '';
		$desc_checkbox        = '';
		$desc_text            = '';
		$required_checkbox    = '';
		$field_limit_checkbox = '';
		$field_min_limit      = '';
		$field_max_limit      = '';
		$field_price_checkbox = '';
		$field_price_type     = '';
		$field_price          = '';
		$field_file_format    = '';
		$ck_option_id         = '';
		$ck_option_image      = '';
		$ck_option_title      = '';
		$ck_option_color      = '';
		$ck_option_price_type = '';
		$ck_option_price      = '';
		$ck_option_priority   = '';

		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: application/octet-stream');
		$filename = 'Product-Options-csv ' . gmdate('Y-m-d') . '.csv';
		header('Content-Disposition: attachment; filename="' . $filename . '";' );
		header('Content-Transfer-Encoding: binary');

		$file = fopen( PRO_OP_PLUGIN_DIR . 'assets/export/export.csv', 'w+');

		fputcsv($file, $fields);  

		$delimiterr = ',';
		$filename   = 'Product-Options-csv ' . gmdate('Y-m-d') . '.csv';
	

		$rule_id = '';
		if (isset($post_data['current_rule_id'])) {
			$rule_id = sanitize_text_field($post_data['current_rule_id']);
		}

		$args = array(
			'post_type' => 'product_add_field',
			'post_status' => 'publish',
			'post_parent' => $rule_id,
			'numberposts' => - 1,
			'fields' => 'ids',
			'order' => 'ASC',
		);

		$fields = get_posts($args);

		foreach ($fields as $field_id) {
			if (empty($field_id)) {
				continue;
			}

			$field_id = $field_id;

			$field_priority = get_post_meta($field_id, 'ck_field_priority', true);

			$field_type = get_post_meta($field_id, 'ck_fields_type', true);

			$ck_field_title = get_post_meta($field_id, 'ck_field_title', true);

			if ('yes' == get_post_meta($field_id, 'ck_add_tool_tip', true)) {
				$tool_tip_checkbox = 'Checked';
			} else {
				$tool_tip_checkbox = 'Not Checked';
			}

			$field_tooltip = get_post_meta($field_id, 'ck_field_tool_tip', true);

			if ('yes' == get_post_meta($field_id, 'ck_add_desc', true)) {
				$desc_checkbox = 'Checked';
			} else {
				$desc_checkbox = 'Not Checked';
			}

			$desc_text = get_post_meta($field_id, 'ck_field_decs', true);

			if ('yes' == get_post_meta($field_id, 'ck_field_tool_tip', true)) {
				$required_checkbox = 'Checked';
			} else {
				$required_checkbox = 'Not Checked';
			}

			if ('yes' == get_post_meta($field_id, 'ck_field_limit_range', true)) {
				$field_limit_checkbox = 'Checked';
			} else {
				$field_limit_checkbox = 'Not Checked';
			}

			$field_min_limit = get_post_meta($field_id, 'ck_field_min_limit', true);

			$field_max_limit = get_post_meta($field_id, 'ck_field_max_limit', true);

			if ('yes' == get_post_meta($field_id, 'ck_field_price', true)) {
				$field_price_checkbox = 'Checked';
			} else {
				$field_price_checkbox = 'Not Checked';
			}

			$field_price_type = get_post_meta($field_id, 'ck_field_pricing_type', true);

			$field_price = get_post_meta($field_id, 'ck_field_price', true);

			$field_file_format = get_post_meta($field_id, 'ck_field_file_extention', true);

			$option_id_array  = array();
			$image_array      = array();
			$name_array       = array();
			$color_array      = array();
			$price_type_array = array();
			$price_array      = array();
			$priority_array   = array();

			$args = array(
				'post_type' => 'product_add_option',
				'post_status' => 'publish',
				'numberposts' => - 1,
				'post_parent' => $field_id,
				'fields' => 'ids',
			);

			$options = get_posts($args);

			foreach ($options as $option_id) {

				if (empty($option_id)) {
					continue;
				}

				$option_id         = $option_id;
				$option_id_array[] = $option_id;

				$image         = get_post_meta($option_id, 'ck_options_image', true);
				$image_array[] = $image;

				$option_name  = get_post_meta($option_id, 'ck_option_name', true);
				$name_array[] = $option_name;

				$option_color  = get_post_meta($option_id, 'ck_option_color', true);
				$color_array[] = $option_color;

				$option_price_type  = get_post_meta($option_id, 'ck_option_price_type', true);
				$price_type_array[] = $option_price_type;

				$option_price  = get_post_meta($option_id, 'ck_option_price', true);
				$price_array[] = $option_price;

				$option_priority  = get_post_meta($option_id, 'ck_option_priority', true);
				$priority_array[] = $option_priority;

			}

			if (!empty($option_id_array)) {
				$ck_option_id = implode(',', $option_id_array);
			} else {
				$ck_option_id = '';
			}

			$image_array = array_filter($image_array); 
			if (!empty($image_array)) {
				$ck_option_image = implode('/optn_img/', $image_array);
			} else {
				$ck_option_image = '';
			}

			if (!empty($name_array)) {
				$ck_option_title = implode(',', $name_array);
			} else {
				$ck_option_title = '';
			}

			if (!empty($color_array)) {
				$ck_option_color = implode(',', $color_array);
			} else {
				$ck_option_color = '';
			}

			if (!empty($price_type_array)) {
				$ck_option_price_type = implode(',', $price_type_array);
			} else {
				$ck_option_price_type = '';
			}

			if (!empty($price_array)) {
				$ck_option_price = implode(',', $price_array);
			} else {
				$ck_option_price = '';
			}

			if (!empty($priority_array)) {
				$ck_option_priority = implode(',', $priority_array);
			} else {
				$ck_option_priority = '';
			}

			$fields = array(
				$field_id,
				$field_priority,
				$field_type,
				$ck_field_title,
				$tool_tip_checkbox,
				$field_tooltip,
				$desc_checkbox,
				$desc_text,
				$required_checkbox,
				$field_limit_checkbox,
				$field_min_limit,
				$field_max_limit,
				$field_price_checkbox,
				$field_price_type,
				$field_price,
				$field_file_format,
				$ck_option_id,
				$ck_option_image,
				$ck_option_color,
				$ck_option_title,
				$ck_option_price_type,
				$ck_option_price,
				$ck_option_priority
			);

			fputcsv($file, $fields);  

		}

		echo wp_kses_post( file_get_contents( PRO_OP_PLUGIN_DIR . 'assets/export/export.csv' ) );

		fclose($file);
		exit;
	}
}

function ck_import_csv( $s ) {
	if (preg_match('#[\x80-\x{1FF}\x{2000}-\x{3FFF}]#u', $s)) {
		return $s;
	}

	if (preg_match('#[\x7F-\x9F\xBC]#', $s)) {
		return iconv('WINDOWS-1250', 'UTF-8', $s);
	}

	return iconv('ISO-8859-2', 'UTF-8', $s);
}

new Csv_Upload_Download();
