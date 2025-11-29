<?php

/**
 * Front Class
 *
 * @package : product-options 
 */

defined( 'ABSPATH' ) || exit();

class Prd_Options_Front {

	
	public function __construct() {

		add_action('wp_enqueue_scripts', array($this, 'front_enque_script'));

		add_action('woocommerce_before_add_to_cart_button', array($this, 'front_fields_show'), 20);

		add_action('woocommerce_before_add_to_cart_button', array($this, 'prd_page_total_table'), 25);

		add_filter('woocommerce_add_to_cart_validation', array($this, 'fields_required_validation'), 35, 4 );

		add_filter('woocommerce_add_cart_item_data', array($this, 'add_data_in_cart_menu'), 2);

		add_filter('woocommerce_add_cart_item', array( $this, 'add_cart_item' ), 20, 1);

		add_filter('woocommerce_get_item_data', array($this, 'get_item_data_filter'), 10, 2);

		add_filter('woocommerce_get_cart_item_from_session', array($this, 'get_cart_item_from_session'), 20, 2);

		add_action('woocommerce_checkout_create_order_line_item', array($this,'checkout_create_order_line_item'), 10, 4);

		add_action('woocommerce_order_item_meta_start', array($this, 'show_option_name_in_order_detail'), 10, 3);
	}

	public function front_enque_script() {
		wp_enqueue_script( 'front_js', plugins_url( '../../assets/js/front.js', __FILE__ ), array( 'jquery' ), '1.0.0', false );
		wp_enqueue_style( 'front_css', plugins_url( '../../assets/css/front.css', __FILE__ ), false, '1.0.0' );
		wp_enqueue_style( 'select_css', plugins_url( '../../assets/css/select2.css', __FILE__ ), false, '1.0.0' );
		wp_enqueue_script( 'select_js', plugins_url( '../../assets/js/select2.js', __FILE__ ), array( 'jquery' ), '1.0.0', false );
		wp_enqueue_style('af_pao_font', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false, '1.0', false);

		$product_options_ajax_data = array(
			'admin_url' => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( 'prod_optn_nonce' ),
		);
		wp_localize_script( 'front_js', 'prod_options_url', $product_options_ajax_data );
	}

	public function front_fields_show() {
		global $product;

		$product_id = $product->get_id();

		wp_nonce_field('prod_optn_front_nonce', 'prod_optn_front_nonce_field');

		

		if ( 'variable' == $product->get_type() ) {
			return;
		}

		echo wp_kses_post( Prd_General_Functions::get_product_options( $product_id ) );
	}

	public function prd_page_total_table() {
		global $product;
		$price = $product->get_price(); 
		?>
		<div class="product_extra_options"></div>
		
		<div style="margin-bottom: 15px;">

			<p class="real_time_product_sub_total_calculation" data-prod_title="<?php echo esc_attr($product->get_title()); ?>" data-prod_price="<?php echo esc_attr($price); ?>" data-currency_sym="<?php echo esc_attr(get_woocommerce_currency_symbol()); ?>"></p>

		</div>

		<table class="af_addon_total_price">

			<tbody id="addon-tbody">

				<tr>

					<td class="product-name-and-quantity"></td>

					<td class="product-sub-total-1st-tr"></td>

				</tr>

				<tr class="optn_name_price" style="display:none;">

					<td id="product_option_selected_name"><ul></ul></td>
					<td id="product_option_selected_price"><ul></ul></td>

				</tr>

			</tbody>

		</table>
		<?php
	}

	public function fields_required_validation( $validation, $product_id, $quantity, $variation_id = 0 ) {

		$nonce = isset($_POST['prod_optn_front_nonce_field']) ? sanitize_text_field($_POST['prod_optn_front_nonce_field']) : 0;

		if ( !wp_verify_nonce($nonce, 'prod_optn_front_nonce') ) {

			die('Failed Security check');
		}

		$field_error_messages = array();

		$file_error_messages = array();

		$validation_flag = false;

		$af_addon_use_roles = is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';
		if ( 'both' == get_option('ck_prod_optn_run_options') ) {

			$prod_rules = Prd_General_Functions::get_posts_ids_of_current_post('product_options');
			foreach ($prod_rules as $rule_id) {

				$user_roles = get_post_meta($rule_id, 'user_roles', true);


				if (!empty($user_roles) && !in_array((string) $af_addon_use_roles, (array) $user_roles, true)) {

					continue;
				}

				if (Prd_General_Functions::rule_product_check($product_id, $rule_id)) {

					$fields = Prd_General_Functions::get_posts_ids_of_current_post('product_add_field', $rule_id);

					foreach ( $fields as $field_id ) {

						if (empty($field_id)) {
							continue;
						}

						$field_error_messages[] = $this->ck_fields_validation( $_POST, $field_id );
						$file_error_messages[]  = $this->ck_file_format_validation_check( $field_id );
					}
				}
			}

			$fields = Prd_General_Functions::get_posts_ids_of_current_post('product_add_field', $product_id);
			foreach ( $fields as $field_id ) {

				if (empty($field_id)) {
					continue;
				}

				$field_error_messages[] = $this->ck_fields_validation( $_POST, $field_id );
				$file_error_messages[]  = $this->ck_file_format_validation_check( $field_id );
			}
		} elseif ('rule' == get_option('ck_prod_optn_run_options')) {
			
			$prod_rules = Prd_General_Functions::get_posts_ids_of_current_post('product_options');

			foreach ($prod_rules as $rule_id) {

				$user_roles = get_post_meta($rule_id, 'user_roles', true);

				$af_addon_use_roles = is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';

				if (!empty($user_roles) && !in_array((string) $af_addon_use_roles, (array) $user_roles, true)) {

					continue;
				}

				if (Prd_General_Functions::rule_product_check($product_id, $rule_id)) {

					$fields = Prd_General_Functions::get_posts_ids_of_current_post('product_add_field', $rule_id);

					foreach ( $fields as $field_id ) {

						if (empty($field_id)) {
							continue;
						}

						$field_error_messages[] = $this->ck_fields_validation( $_POST, $field_id );
						$file_error_messages[]  = $this->ck_file_format_validation_check( $field_id );
					}
				}
			}

		} else {

			$fields = Prd_General_Functions::get_posts_ids_of_current_post('product_add_field', $product_id);
			foreach ( $fields as $field_id ) {

				if (empty($field_id)) {
					continue;
				}

				$field_error_messages[] = $this->ck_fields_validation( $_POST, $field_id );
				$file_error_messages[]  = $this->ck_file_format_validation_check( $field_id );
			}
		}

		if ( !empty(array_filter($field_error_messages)) || !empty(array_filter($file_error_messages)) ) {
			foreach ($field_error_messages as $error_message) {
				wc_add_notice(__($error_message, 'prod_options'), 'error');
			}
			foreach ($file_error_messages as $file_error_message) {
				wc_add_notice(__($file_error_message, 'prod_options'), 'error');
			}
			return false;
		}

		return $validation;
	}

	public function ck_fields_validation( $post_data, $field_id ) {

		$required_msg = '';

		$field_opt    = 'ck_options_' . $field_id ;
		$depend_field = get_post_meta($field_id, 'ck_fields_selector', true);

		$depend_option = array();

		if (get_post_meta($field_id, 'ck_options_selector', true)) {

			$depend_option = (array) get_post_meta($field_id, 'ck_options_selector', true);
		}

		$required_value  = get_post_meta($field_id, 'ck_req_field', true);
		$depend_selector = get_post_meta($field_id, 'ck_field_dependable_selector', true);
		$field_type      = get_post_meta($field_id, 'ck_fields_type', true);

		if ('yes' == $required_value) {

			if ('depend' == $depend_selector) {

				if ('multi_select' == get_post_meta($depend_field, 'ck_fields_type', true) || 'check_boxes' == get_post_meta($depend_field, 'ck_fields_type', true)) {

					$options_array = isset(  $post_data['ck_options_' . $depend_field] ) ? sanitize_meta( '', $post_data['ck_options_' . $depend_field], '') : array();

					if (count(array_intersect($options_array, $depend_option)) >= 1) {

						if ('yes' == $required_value && empty($post_data[$field_opt])) {

							$ck_field_title = get_post_meta($field_id, 'ck_field_title', true);
							$required_msg   = $ck_field_title . ' is a required field.';

						}
					}
				} else {

					$options_array = isset( $post_data['ck_options_' . $depend_field] ) ? sanitize_text_field( $post_data['ck_options_' . $depend_field] ) : '';

					if (in_array($options_array, $depend_option, true) ) {

						if ('yes' == $required_value && empty($post_data[$field_opt])) {

							$ck_field_title = get_post_meta($field_id, 'ck_field_title', true);
							$required_msg   = $ck_field_title . ' is a required field.';

						}
					}
				}

			} else {

				if ('file_upload' == $field_type) {

					if (empty($_FILES[$field_opt])) {
						$ck_field_title = get_post_meta($field_id, 'ck_field_title', true);
						$required_msg   = $ck_field_title . ' is a required field.';
					}

				} else {
					if (empty($post_data[$field_opt])) {

						$ck_field_title = get_post_meta($field_id, 'ck_field_title', true);
						$required_msg   = $ck_field_title . ' is a required field.';

					}
				}
			}
		}

		return $required_msg;
	}

	public function ck_file_format_validation_check( $field_id ) {

		$file_filed_required_msg = '' ;

		$field_opt     = 'ck_options_' . $field_id ;
		$depend_field  = get_post_meta($field_id, 'ck_fields_selector', true);
		$depend_option = (array) get_post_meta($field_id, 'ck_options_selector', true);
		$field_type    = get_post_meta($field_id, 'ck_fields_type', true);

		if ('file_upload' == $field_type) {

			if (!empty($_FILES[$field_opt]['name'])) {

				$file_array              = sanitize_meta('', $_FILES[$field_opt], '');
				$file_name               = $file_array['name'];
				$file_name_array         = explode('.', $file_name);
				$file_extention          = end($file_name_array);
				$admin_file_extentions   = explode(', ', get_post_meta($field_id, 'ck_field_file_extention', true));
				$admin_file_extention    = get_post_meta($field_id, 'ck_field_file_extention', true);
				$admin_file_upload_title = get_post_meta($field_id, 'ck_field_title', true);

				if ( !in_array( $file_extention , $admin_file_extentions , true) ) {

					$file_filed_required_msg = 'Invalid file format in ' . $admin_file_upload_title . ', only "' . $admin_file_extention . '" is allowed';
				}
			}
		}

		return $file_filed_required_msg;
	}

	public function add_data_in_cart_menu( $cart_item_data ) {
		
		$nonce = isset($_POST['prod_optn_front_nonce_field']) ? sanitize_text_field($_POST['prod_optn_front_nonce_field']) : 0;

		if ( !wp_verify_nonce($nonce, 'prod_optn_front_nonce') ) {

			die('Failed Security check');
		}

		$_prod_data = $_POST;
		$_prod_id   = $_prod_data['add-to-cart'];

		$selected_field_data = array();
		$fields_ids_array    = array();
		if ( 'both' == get_option('ck_prod_optns_run') ) {

			$prod_rules = Prd_General_Functions::get_posts_ids_of_current_post('product_options');

			foreach ($prod_rules as $rule_id) {

				if (Prd_General_Functions::rule_product_check($_prod_id, $rule_id)) {

					$fields = Prd_General_Functions::get_posts_ids_of_current_post('product_add_field', $rule_id);

					foreach ( $fields as $field_id ) {

						if (empty($field_id)) {
							continue;
						}
					}

					$fields_ids_array = array_merge($fields_ids_array, $fields);
				}
			}

			$fields = Prd_General_Functions::get_posts_ids_of_current_post('product_add_field', $_prod_id);

			foreach ( $fields as $field_id ) {

				if (empty($field_id)) {
					continue;
				}

			}

			$fields_ids_array = array_merge($fields_ids_array, $fields);
		} elseif ('rule' == get_option('ck_prod_optns_run')) {
			
			$prod_rules = Prd_General_Functions::get_posts_ids_of_current_post('product_options');

			foreach ($prod_rules as $rule_id) {

				$fields = Prd_General_Functions::get_posts_ids_of_current_post('product_add_field', $rule_id);

				foreach ( $fields as $field_id ) {

					if (empty($field_id)) {
						continue;
					}
				}
			}

			$fields_ids_array = array_merge($fields_ids_array, $fields);

		} else {

			$fields = Prd_General_Functions::get_posts_ids_of_current_post('product_add_field', $_prod_id);
			foreach ( $fields as $field_id ) {

				if (empty($field_id)) {
					continue;
				}

			}

			$fields_ids_array = array_merge($fields_ids_array, $fields);
		}

		foreach (array_unique($fields_ids_array) as $field_id) {

			$ck_options = 'ck_options_' . $field_id ;

			$field_type = get_post_meta($field_id, 'ck_fields_type', true);

			if ('file_upload' == $field_type) {

				if (isset($_FILES[$ck_options])) {

					$upload = wp_upload_dir();

					$upload_dir = $upload['basedir'];

					$upload_dir = $upload_dir . '/product-options/uploaded-files';

					if (!is_dir($upload_dir)) {

						mkdir($upload_dir);
						chmod($upload_dir, 0777);
					}

					$uploaded_file = '';
					if (isset($_FILES[$ck_options]['tmp_name'])) {
						$uploaded_file = sanitize_text_field($_FILES[$ck_options]['tmp_name']);
					}

					$ck_file_filename = '';

					if (isset($_FILES[$ck_options]['name'])) {
						$ck_file_filename = ( basename(sanitize_text_field($_FILES[$ck_options]['name'])) );
					}


					$uploaded_file_FileType = strtolower(pathinfo($ck_file_filename, PATHINFO_EXTENSION));

					$uploaded_file_extention = get_post_meta($field_id, 'ck_field_file_extention', true);

					if (!empty($uploaded_file_extention)) {

						$uploaded_file_extention_array = explode(', ', strtolower($uploaded_file_extention));

						if (in_array($uploaded_file_FileType, $uploaded_file_extention_array, true)) {

							if (empty($_FILES[$ck_options]['error'])) {

								$target_dir  = $upload['basedir'] . '/producy-options/uploaded-files/';
								$target_file = $target_dir . basename($ck_file_filename);
								copy($uploaded_file, $target_file);

								$filename    = basename($ck_file_filename);
								$wp_filetype = wp_check_filetype(basename($filename), null);
								$url         = $upload_dir . '/' . $ck_file_filename;

								$selected_field_data[$field_id] =  array(
									'field_id' 	 		 	=> $field_id,
									'value'		  		 	=> $ck_file_filename,
									'field_type' 		 	=> 'file_upload',
								);
								continue;
							}
						}
					}
				}
			}

			$value = '';
			if (isset($_POST[$ck_options])) {
				$value = sanitize_text_field($_POST[$ck_options]);
			}

			if (!empty($_POST[$ck_options])) {

				if ('multi_select' == $field_type || 'check_boxes' == $field_type ) {

					$value = sanitize_meta('', $_POST[$ck_options], '');
				}

				$selected_field_data[$field_id] =  array(
					'field_id' 	 		 => $field_id,
					'value'		  		 => $value,
					'field_type' 		 => $field_type,
				);
			}

			$cart_item_data['selected_field_data'] = $selected_field_data;

		}

		return $cart_item_data;
	}

	public function add_cart_item( $cart_item_data ) {

		$product_price = $cart_item_data['data']->get_price();
		$quantity      = $cart_item_data['quantity'];
		$field_price   = 0;

		if (in_array('selected_field_data', array_keys($cart_item_data))) {

			$get_added_data = $cart_item_data['selected_field_data'];

			foreach ($get_added_data as $option_field_id_array) {

				if (empty($option_field_id_array) || !is_array($option_field_id_array)) {
					continue;
				}

				$field_type = $option_field_id_array['field_type'];

				if ('multi_select' == $field_type || 'check_boxes' == $field_type ) {

					$get_multi_select_val = (array) $option_field_id_array['value'];

					foreach ($get_multi_select_val as $option_id_value) {

						$field_price += $this->selected_fields_fees($option_id_value, $cart_item_data, $option_field_id_array['field_type']);
					}

				} else {

					if ('drop_down' == $field_type || 'image' == $field_type || 'image_swatcher' == $field_type || 'color' == $field_type || 'radio' == $field_type) {

						$option_id_value = $option_field_id_array['value'];

					} else {

						$option_id_value = $option_field_id_array['field_id'];
					}
					$field_price += $this->selected_fields_fees($option_id_value, $cart_item_data, $option_field_id_array['field_type']);
				}

				if ($field_price >= 0.1) {
					$cart_item_data['data']->set_price($field_price + $product_price);
				}
			}
		}
		
		return $cart_item_data;
	}

	public function selected_fields_fees( $option_id_value, $value_cart, $field_type) {

		$product_price    = $value_cart['data']->get_price();
		$product_quantity = $value_cart['quantity'];

		if ('multi_select' == $field_type || 'drop_down' == $field_type || 'check_boxes' == $field_type || 'image' == $field_type || 'image_swatcher' == $field_type || 'color' == $field_type || 'radio' == $field_type) {

			$price_type   = get_post_meta($option_id_value, 'ck_option_price_type', true);
			$option_price = (float) get_post_meta($option_id_value, 'ck_option_price', true);
		} else {

			if ('yes' == get_post_meta($option_id_value, 'ck_field_price_checkbox', true)) {

				$price_type   = get_post_meta($option_id_value, 'ck_field_pricing_type', true);
				$option_price = (float) get_post_meta($option_id_value, 'ck_field_price', true);

			} else {

				$price_type   = 0;
				$option_price = 0;
			}
		}

		$field_price = 0;

		if ('flat_fixed_fee' == $price_type) {

			$field_price = $option_price;

			$field_price = $field_price / $product_quantity;

		} elseif ('fixed_fee_based_on_quantity' == $price_type) {

			$field_price = $option_price;

		} elseif ('flat_percentage_fee' == $price_type) {

			$field_price = ( number_format($option_price) / 100 ) * $product_price;

			$field_price = $field_price / $product_quantity;

		} elseif ('percentage_fee_based_on_quantity' == $price_type) {

			$field_price = ( number_format($option_price) / 100 ) * $product_price ;
		}
		return $field_price;
	}

	public function get_item_data_filter( $item_data, $cart_item_data ) {

		$product_quantity = $cart_item_data['quantity'];
		$product_price    = $cart_item_data['data']->get_price();

		if (isset($cart_item_data['selected_field_data'])) {
			$get_added_data = $cart_item_data['selected_field_data'];
			foreach ($get_added_data as $option_field_id_array) {
				if (empty($option_field_id_array) || !is_array($option_field_id_array)) {
					continue;
				}

				if (in_array('field_id', array_keys($option_field_id_array))) {
					$field_id_value = $option_field_id_array['field_id'];
					$field_type     = $option_field_id_array['field_type'];

					$ck_field_title = get_post_meta($field_id_value, 'ck_field_title', true);

					if ($ck_field_title) {
						?>
						<br><b> <?php echo esc_attr($ck_field_title . ':'); ?> </b><br>
						<?php
					}

					$field_type = $option_field_id_array['field_type'];

					if ('multi_select' == $field_type || 'check_boxes' == $field_type ) {

						$selected_option_ids = (array) $option_field_id_array['value'];

						foreach ($selected_option_ids as $option_id) {

							$option_name  = get_post_meta($option_id, 'ck_option_name', true);
							$option_price = (float) get_post_meta($option_id, 'ck_option_price', true);
							$price_type   = get_post_meta($option_id, 'ck_option_price_type', true);

							$selected_field_price = '';

							if ('free' == $price_type) {

								$selected_field_price = $option_name;
							} elseif ('flat_fixed_fee' == $price_type || 'fixed_fee_based_on_quantity' == $price_type) {

								$selected_field_price = $option_name . ' (+' . get_woocommerce_currency_symbol() . ' ' . number_format($option_price, 2, '.', '') . ')';
							} elseif ('flat_percentage_fee' == $price_type || 'percentage_fee_based_on_quantity' == $price_type) {

								$selected_field_price = $option_name . ' (+' . number_format($option_price, 2, '.', '') . '%)';
							}

							echo esc_attr($selected_field_price);

							if (end($selected_option_ids) != $option_id) {
								echo '<br>';
							}
						}
					} elseif ('drop_down' == $field_type || 'radio' == $field_type || 'color' == $field_type || 'image_swatcher' == $field_type || 'image' == $field_type ) {

						$option_id    = $option_field_id_array['value'];
						$option_name  = get_post_meta($option_id, 'ck_option_name', true);
						$option_price = (float) get_post_meta($option_id, 'ck_option_price', true);
						$price_type   = get_post_meta($option_id, 'ck_option_price_type', true);

						$selected_field_price = '';

						if ('free' == $price_type) {

							$selected_field_price = $option_name;
						} elseif ('flat_fixed_fee' == $price_type || 'fixed_fee_based_on_quantity' == $price_type) {

							$selected_field_price = $option_name . ' (+' . get_woocommerce_currency_symbol() . ' ' . number_format($option_price, 2, '.', '') . ')';
						} elseif ('flat_percentage_fee' == $price_type || 'percentage_fee_based_on_quantity' == $price_type) {

							$selected_field_price = $option_name . ' (+' . number_format($option_price, 2, '.', '') . '%)';
						}

						echo esc_attr($selected_field_price);

					} else {

						$field_id = $option_field_id_array['field_id'];

						$value = $option_field_id_array['value'];

						if ('yes' == get_post_meta($field_id, 'ck_field_price_checkbox', true)) {
							$price_type   = get_post_meta($field_id, 'ck_field_pricing_type', true);
							$option_price = (float) get_post_meta($field_id, 'ck_field_price', true);
						} else {
							$price_type   = 0;
							$option_price = 0;
						}

						$selected_field_price = '';

						if ('free' == $price_type) {

							$selected_field_price = $value;
						} elseif ('flat_fixed_fee' == $price_type || 'fixed_fee_based_on_quantity' == $price_type) {

							$selected_field_price = $value . ' (+' . get_woocommerce_currency_symbol() . ' ' . number_format($option_price, 2, '.', '') . ')';
						} elseif ('flat_percentage_fee' == $price_type || 'percentage_fee_based_on_quantity' == $price_type) {

							$selected_field_price = $value . ' (+' . number_format($option_price, 2, '.', '') . '%)';
						}

						echo esc_attr($selected_field_price);
					}
				}
			}
		}
		return $item_data;
	}

	public function get_cart_item_from_session( $cart_item, $values ) {

		if (!empty($values['selected_field_data'])) {

			$cart_item['selected_field_data'] = $values['selected_field_data'];
			$cart_item                        = $this->add_cart_item($cart_item);
		}

		return $cart_item;
	}

	public function checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {

		$order_id = $order->get_id();

		foreach (WC()->cart->get_cart() as $item_key => $value_check) {

			$total_files_in_array = 0;

			if (!empty($value_check) && $item_key === $cart_item_key && array_key_exists('selected_field_data', $value_check)) {

				$get_data_of_files = $value_check['selected_field_data'];

				$item->add_meta_data(
					'selected_field_data',
					$get_data_of_files,
					true
				);
			}
		}
	}

	public function show_option_name_in_order_detail( $item_id, $item, $order ) {

		foreach ($item->get_meta_data() as $item_data) {

			$item_data_array = $item_data->get_data();

			if (in_array('selected_field_data', $item_data_array) && is_array($item_data_array['value'])) {

				$get_added_data = $item_data_array['value'];

				foreach ($get_added_data as $option_field_id_array) {

					$selected_field_price = '';

					if ( in_array('field_id', array_keys($option_field_id_array) ) ) {
						$field_id_value = $option_field_id_array['field_id'];
						$field_type     = $option_field_id_array['field_type'];

						$ck_field_title = get_post_meta($field_id_value, 'ck_field_title', true);

						if ($ck_field_title) {
							?>
							<br>
							<b> <?php echo esc_attr($ck_field_title . ':'); ?> </b>
							<br>
							<?php
						}

						$field_type = $option_field_id_array['field_type'];

						if ( 'multi_select' == $field_type || 'check_boxes' == $field_type ) {

							$get_multi_select_val = (array) $option_field_id_array['value'];

							foreach ($get_multi_select_val as $option_id_value) {

								$option_name  = get_post_meta($option_id_value, 'ck_option_name', true);
								$option_price = (float) get_post_meta( $option_id_value , 'ck_option_price', true);
								$price_type   = get_post_meta($option_id_value, 'ck_option_price_type' , true);

								if ('free' == $price_type) {

									$selected_field_price = $option_name;
								} elseif ('flat_fixed_fee' == $price_type || 'fixed_fee_based_on_quantity' == $price_type ) {

									$selected_field_price = $option_name . ' (+' . get_woocommerce_currency_symbol() . ' ' . number_format($option_price, 2, '.', '') . ')';
								} elseif ('flat_percentage_fee' == $price_type || 'percentage_fee_based_on_quantity' == $price_type) {

									$selected_field_price = $option_name . ' (+' . number_format($option_price, 2, '.', '') . '%)';
								}

								echo esc_attr($selected_field_price);

								if (end($get_multi_select_val) != $option_id_value) {
									echo '<br>';
								}
							}

						} else {

							$field_id = $option_field_id_array['field_id'];

							if ('file_upload' == $option_field_id_array['field_type'] ) {

								$upload = wp_upload_dir();
								$url    = $upload['baseurl'] . '/product-options/uploaded-files/' . $option_field_id_array['value'];
								?>
								<a target="_blank" href="<?php echo esc_attr($url); ?>">
									<i class="fa fa-eye">
										<?php echo esc_attr($option_field_id_array['value']); ?>
									</i>
								</a>
								<?php
							}

							if ( 'drop_down' == $field_type || 'image' == $field_type || 'image_swatcher' == $field_type || 'color_swatcher' == $field_type || 'radio' == $field_type) {

								$option_value = $option_field_id_array['value'];
								$option_name  = get_post_meta($option_value, 'ck_option_name', true);
								$option_price = (float) get_post_meta( $option_value , 'ck_option_price', true);
								$price_type   = get_post_meta($option_value, 'ck_option_price_type' , true);

								if ('free' == $price_type) {

									$selected_field_price = $option_name;
								} elseif ('flat_fixed_fee' == $price_type || 'fixed_fee_based_on_quantity' == $price_type ) {

									$selected_field_price = $option_name . ' (+' . get_woocommerce_currency_symbol() . ' ' . number_format($option_price, 2, '.', '') . ')';
								} elseif ('flat_percentage_fee' == $price_type || 'percentage_fee_based_on_quantity' == $price_type) {

									$selected_field_price = $option_name . ' (+' . number_format($option_price, 2, '.', '') . '%)';
								}

								echo esc_attr($selected_field_price);

							} else {

								$option_price = (float) get_post_meta( $field_id , 'ck_field_price', true);
								$price_type   = get_post_meta($field_id, 'ck_field_pricing_type' , true);
								
								if ('flat_fixed_fee' == $price_type || 'fixed_fee_based_on_quantity' == $price_type ) {

									$selected_field_price = ' (+' . get_woocommerce_currency_symbol() . ' ' . number_format($option_price, 2, '.', '') . ')';
								} elseif ('flat_percentage_fee' == $price_type || 'percentage_fee_based_on_quantity' == $price_type) {

									$selected_field_price = ' (+' . number_format($option_price, 2, '.', '') . '%)';
								}
								if ('file_upload' != $option_field_id_array['field_type']) {
									?>
									<b><?php echo esc_attr($option_field_id_array['value'] . ' ' . $selected_field_price); ?></b>
									<?php
								} else {
									?>
									<b><?php echo esc_attr($selected_field_price); ?></b>
									<?php
								}

							}

							$selected_field_price = '';
						}
					}
				}
			}
		}
	}
}

new Prd_Options_Front();
