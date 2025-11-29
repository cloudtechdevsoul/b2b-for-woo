<?php
if (!defined('WPINC')) {
	die;
}

class Ct_Rfq_Ajax
{

	public function __construct()
	{

		add_action('wp_ajax_product_search', array($this, 'ct_tepfw_product_search'));
		add_action('wp_ajax_category_search', array($this, 'ct_tepfw_category_search'));
		add_action('wp_ajax_ct_tepfw_add_new_customer_or_role_base_rbp', [$this, 'ct_tepfw_add_new_customer_or_role_base_rbp']);
		add_action('wp_ajax_ct_tepfw_delete_post', [$this, 'ct_tepfw_delete_post']);
		add_action('wp_ajax_nopriv_ct_rfq_remove_cart_product', [$this, 'ct_rfq_remove_cart_product']);
		add_action('wp_ajax_ct_rfq_remove_cart_product', [$this, 'ct_rfq_remove_cart_product']);
		add_action('wp_ajax_nopriv_ct_rfq_add_product_to_quote', [$this, 'ct_rfq_add_product_to_quote']);
		add_action('wp_ajax_ct_rfq_add_product_to_quote', [$this, 'ct_rfq_add_product_to_quote']);
		add_action('wp_ajax_ct_rfq_add_selected_product_to_quote', [$this, 'ct_rfq_add_selected_product_to_quote']);
		add_action('wp_ajax_ct_create_order', [$this, 'ct_create_order']);
		add_action('wp_ajax_ct_rfq_instant_notify', [$this, 'ct_rfq_instant_notify']);


	}

	public function ct_tepfw_product_search()
	{
		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;

		if (!wp_verify_nonce($nonce, 'cloud-tech-tepfw-nonce')) {

			wp_die(esc_html__('Failed Security check!', 'af_cogload_files'));
		}
		$pro = isset($_POST['q']) && '' !== $_POST['q'] ? sanitize_text_field(wp_unslash($_POST['q'])) : '';

		$data_array = array();
		$args = array(
			'post_status' => 'publish',
			'numberposts' => 100,
			's' => $pro,
		);
		$pros = wc_get_products($args);

		if (!empty($pros)) {
			foreach ($pros as $proo) {
				$title = (mb_strlen($proo->get_name()) > 50) ? mb_substr($proo->get_name(), 0, 49) . '...' : $proo->get_name();
				$data_array[] = array($proo->get_id(), $title); // array( Post ID, Post Title ).
			}
		}
		echo wp_json_encode($data_array);
		die();
	}

	public function ct_tepfw_category_search()
	{
		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;

		if (!wp_verify_nonce($nonce, 'cloud-tech-tepfw-nonce')) {

			wp_die(esc_html__('Failed Security check!', 'af_cogload_files'));
		}

		$pro = isset($_POST['q']) && '' !== $_POST['q'] ? sanitize_text_field(wp_unslash($_POST['q'])) : '';

		$data_array = array();
		$orderby = 'name';
		$order = 'asc';
		$hide_empty = false;
		$cat_args = array(
			'orderby' => $orderby,
			'order' => $order,
			'hide_empty' => $hide_empty,
			'name__like' => $pro,
		);
		$product_categories = get_terms('product_cat', $cat_args);
		if (!empty($product_categories)) {
			foreach ($product_categories as $proo) {
				$pro_front_post = (mb_strlen($proo->name) > 50) ? mb_substr($proo->name, 0, 49) . '...' : $proo->name;
				$data_array[] = array($proo->term_id, $pro_front_post); // array( Post ID, Post Title ).
			}
		}
		echo wp_json_encode($data_array);
		die();
	}

	public function ct_tepfw_delete_post()
	{
		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;

		if (!wp_verify_nonce($nonce, 'cloud-tech-tepfw-nonce')) {

			wp_die(esc_html__('Failed Security check!', 'af_cogload_files'));
		}

		if (isset($_POST['post_id'])) {


			wp_delete_post(sanitize_text_field($_POST['post_id']));


			wp_send_json(['delete' => true]);

			wp_die();

		}
	}
	public function ct_rfq_remove_cart_product()
	{

		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;

		if (!wp_verify_nonce($nonce, 'cloud-tech-tepfw-nonce')) {

			wp_die(esc_html__('Failed Security check!', 'af_cogload_files'));
		}

		$added_products_to_quote = (array) wc()->session->get('cloud_tech_quote_cart');

		if (isset($_POST['quote_key'])) {

			$remove_quote_key = sanitize_text_field($_POST['quote_key']);

			foreach ($added_products_to_quote as $quote_key => $current_product_quote_detail) {

				if (!is_array($current_product_quote_detail)) {
					continue;
				}

				if (!isset($current_product_quote_detail['product_id']) || empty($current_product_quote_detail['product_id'])) {
					continue;
				}



				if ($quote_key == $remove_quote_key) {

					$product_id = $current_product_quote_detail['product_id'];
					$variation_id = isset($current_product_quote_detail['variation_id']) ? ($current_product_quote_detail['variation_id']) : 0;
					$product = wc_get_product($variation_id > 1 ? $variation_id : $product_id);

					wc_add_notice(esc_html__('"' . $product->get_name() . '" remove from quote', 'cloud_tech_rfq'));
					unset($added_products_to_quote[$quote_key]);
					break;
				}

			}

		}

		wc()->session->set('cloud_tech_quote_cart', $added_products_to_quote);
	}

	public function ct_rfq_add_product_to_quote()
	{
		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;

		if (!wp_verify_nonce($nonce, 'cloud-tech-tepfw-nonce')) {

			wp_die(esc_html__('Failed Security check!', 'af_cogload_files'));
		}


		if (isset($_POST['product_ids_and_detail'])) {

			$product_ids_and_detail = sanitize_meta('', $_POST['product_ids_and_detail'], true);

			foreach ($product_ids_and_detail as $key => $value) {

				if (is_array($value) && isset($value['product_id'])) {



					$current_rule_id = isset($value['rule_id']) ? $value['rule_id'] : 0;
					$product_id = $value['product_id'];
					$product = wc_get_product($product_id);

					$variation_id = isset($value['variation_id']) ? $value['variation_id'] : 0;
					$quantity = isset($value['qty']) ? $value['qty'] : 1;

					if ($product->get_parent_id() && $product->get_parent_id() >= 1) {

						$product_id = $product->get_parent_id();
						$variation_id = $product_id;

					}

					$product_detail_array = ['current_rule_id' => $current_rule_id, 'product_id' => $product_id, 'variation_id' => $variation_id, 'quantity' => $quantity];
					cloud_tech_add_quote($product_detail_array, 'whole_add_product_to_cart');

				}

			}

			$post_content = get_post_field('post_content', get_the_ID());

			$ct_redirect_page_link = get_page_link(get_rfq_pge_id());

			if (is_single() && !empty(get_option('ct_rfq_enable_redirect_from_product_page'))) {

				wp_safe_redirect($ct_redirect_page_link);
			}


			if ((is_cart() || is_checkout()) && !empty(get_option('ct_rfq_enable_redirect_from_cart_and_checkout_pge'))) {
				wp_safe_redirect($ct_redirect_page_link);

			}


			if ((is_shop() || is_archive()) && !empty(get_option('ct_rfq_enable_redirect_from_shop_and_archive_page'))) {
				wp_safe_redirect($ct_redirect_page_link);

			}


			if (!empty(get_option('ct_rfq_enable_redirect_from_quote_table_pg')) && has_shortcode($post_content, 'cloud_tech_quote_table')) {
				wp_safe_redirect($ct_redirect_page_link);

			}

			wp_send_json(['refresh' => true]);

		}

	}
	public function ct_rfq_add_selected_product_to_quote()
	{

		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;
		if (!wp_verify_nonce($nonce, 'cloud-tech-tepfw-nonce')) {

			wp_die(esc_html__('Failed Security check!', 'af_cogload_files'));
		}

		if (isset($_POST['selected_product']) && isset($_POST['post_id'])) {

			$post_ID = sanitize_text_field($_POST['post_id']);
			$selected_product = sanitize_meta('', $_POST['selected_product'], '');
			$quantity = 1;
			$added_products_to_quote = (array) get_post_meta($post_ID, 'cloud_tech_quote_cart', true);


			foreach ($selected_product as $key => $value) {

				if (!empty($value)) {

					$product_id = $value;
					$current_rule_id = 0;
					$product = wc_get_product($product_id);
					$variation_id = 0;

					if ($product->get_parent_id() && $product->get_parent_id() >= 1) {

						$product_id = $product->get_parent_id();
						$variation_id = $product_id;

					}

					$product_detail_array = ['current_rule_id' => $current_rule_id, 'product_id' => $product_id, 'variation_id' => $variation_id, 'quantity' => $quantity];

					$product = wc_get_product($variation_id > 1 ? $variation_id : $product_id);
					$product_detail_array['available_qty'] = apply_filters('cloud_tech_product_available_qty', $product);
					$product_detail_array['data'] = $product;
					$cart_key = uniqid(true);
					$is_product_already_added = false;
					$product_detail_array['custom_price'] = '';


					foreach ($added_products_to_quote as $quote_key => $current_product_quote_detail) {

						if (isset($current_product_quote_detail['variation_id']) && $current_product_quote_detail['variation_id'] > 1 && (int) $current_product_quote_detail['variation_id'] === (int) $variation_id) {

							$quantity += isset($current_product_quote_detail['quantity']) ? (float) $current_product_quote_detail['quantity'] : 0;

							if (!empty($product_detail_array['available_qty']) && $product_detail_array['available_qty'] >= 1 && $quantity >= $product_detail_array['available_qty']) {

								$quantity = $product_detail_array['available_qty'];

								if ($quantity <= 1) {
									$quantity = 1;
								}
								$is_product_already_added = true;
								$added_products_to_quote[$quote_key]['quantity'] = $quantity;
								$added_products_to_quote[$quote_key]['custom_price'] = isset($product_detail_array['custom_price']) ? $product_detail_array['custom_price'] : '';
							}

						}

						if (isset($current_product_quote_detail['product_id']) && (int) $current_product_quote_detail['product_id'] === (int) $product_id) {

							$quantity += isset($current_product_quote_detail['quantity']) ? (float) $current_product_quote_detail['quantity'] : 1;


							if (!empty($product_detail_array['available_qty']) && $product_detail_array['available_qty'] >= 1 && $quantity >= $product_detail_array['available_qty']) {

								$quantity = $product_detail_array['available_qty'];
								if ($quantity <= 1) {
									$quantity = 1;
								}

							}

							$is_product_already_added = true;
							$added_products_to_quote[$quote_key]['quantity'] = $quantity;
							$added_products_to_quote[$quote_key]['custom_price'] = isset($product_detail_array['custom_price']) ? $product_detail_array['custom_price'] : '';

						}

						if ($quantity <= 1) {
							$quantity = 1;
						}

					}

				}
				if (!$is_product_already_added) {

					if (!empty($product_detail_array['available_qty']) && $product_detail_array['available_qty'] >= 1 && $quantity >= $product_detail_array['available_qty']) {
						$quantity = $product_detail_array['available_qty'];
						$product_detail_array['quantity'] = $quantity;
					}

					$added_products_to_quote[$cart_key] = $product_detail_array;
				}

			}

			update_post_meta($post_ID, 'cloud_tech_quote_cart', $added_products_to_quote);
			wp_send_json(['success' => true]);
		}

	}
	public function ct_create_order()
	{

		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;
		if (!wp_verify_nonce($nonce, 'cloud-tech-tepfw-nonce')) {

			wp_die(esc_html__('Failed Security check!', 'af_cogload_files'));
		}

		if (isset($_POST['form'])) {
			parse_str(sanitize_text_field($_POST['form']), $form_data);

			if (isset($form_data['post_ID'])) {

				$post_ID = $form_data['post_ID'];
				$added_products_to_quote = (array) get_post_meta($post_ID, 'cloud_tech_quote_cart', true);
				$quote_status = get_post_meta($post_ID, 'quote_status', true) ? get_post_meta($post_ID, 'quote_status', true) : current(array_keys(wc_get_order_statuses()));
				$added_products_to_quote = ct_rfq_custom_array_filter($added_products_to_quote);

				if (empty($added_products_to_quote)) {
					echo esc_html__('Quote Empty', 'woocommerce');
					return;
				}

				$order = wc_create_order();
				foreach ($added_products_to_quote as $quote_key => $current_product_quote_detail) {

					if (!is_array($current_product_quote_detail)) {
						continue;
					}

					if (!isset($current_product_quote_detail['product_id']) || empty($current_product_quote_detail['product_id'])) {
						continue;
					}


					$current_rule_id = isset($current_product_quote_detail['current_rule_id']) ? ($current_product_quote_detail['current_rule_id']) : 0;

					$product_id = $current_product_quote_detail['product_id'];

					$variation_id = isset($current_product_quote_detail['variation_id']) ? ($current_product_quote_detail['variation_id']) : 0;

					$quantity = isset($current_product_quote_detail['quantity']) && $current_product_quote_detail['quantity'] >= 1 ? $current_product_quote_detail['quantity'] : 1;

					$custom_price = isset($current_product_quote_detail['custom_price']) ? ($current_product_quote_detail['custom_price']) : '';

					$product = wc_get_product($variation_id > 1 ? $variation_id : $product_id);

					if (!empty($custom_price) && $custom_price >= 0.1) {
						$final_price = $custom_price * $quantity;
					} else {
						$final_price = $product->get_price() * $quantity;
					}

					$product = wc_get_product($product_id);

					$order->add_product($product, $quantity, array('total' => $final_price));

				}

				wp_update_post(
					array(
						'ID' => $order->get_id(),
						'post_parent' => $post_ID,
					)
				);
				$order->update_status($quote_status);

				$billing_address = array(
					'email' => get_post_meta($post_ID, 'current_user_email', true),
				);
				$order->set_address($billing_address, 'billing');

				$order->calculate_totals();

				$order->save();

				WC()->mailer()->emails['WC_Email_New_Order']->trigger($order->get_id(), $order);


				wp_send_json(['success' => true]);

			}

		}


	}
	public function ct_rfq_instant_notify()
	{


		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;
		if (!wp_verify_nonce($nonce, 'cloud-tech-tepfw-nonce')) {

			wp_die(esc_html__('Failed Security check!', 'af_cogload_files'));
		}



		if (isset($_POST['post_ID'])) {

			$headers = array(
				'Content-Type: text/html; charset=UTF-8',
				'From:' . get_bloginfo('name'),
				'Reply-To:' . get_option('admin_email'), // Set the Reply-To address here
			);
			$post_ID = $_POST['post_ID'];
			$quote_status = get_post_meta($post_ID, 'quote_status', true) ? get_post_meta($post_ID, 'quote_status', true) : current(array_keys(wc_get_order_statuses()));
			$added_products_to_quote = (array) get_post_meta($post_ID, 'cloud_tech_quote_cart', true);
			$added_products_to_quote = ct_rfq_custom_array_filter($added_products_to_quote);
			$enable_setting = get_option('ct_rfq_enable_setting_for_' . $quote_status);
			$subject = get_option('ct_rfq_email_subject_for_' . $quote_status);
			$message = get_option('ct_rfq_email_additional_content_for_' . $quote_status);
			$message = str_replace('{quote_id}', $post_ID, $message);
			$message = str_replace('{quote_status}', wc_get_order_status_name($quote_status), $message);
			$message = str_replace('{quote_date}', ' ', get_the_date('F-j-Y', $post_ID));
			$message = str_replace('{quote_item_table}', ' ', apply_filters('cloud_tech_item_detail', $post_ID, 'show_text'));
			$message = str_replace('{quote_cart_subtotal}', wc_get_quote_subtotal_ct($post_ID), $message);
			$message = str_replace('{quote_cart_tax}', wc_get_quote_tax_ct($post_ID), $message);
			$message = str_replace('{quote_cart_total}', wc_get_quote_total_ct($post_ID), $message);
			$message = str_replace('{quote_cart_billing_shipping_detail}', apply_filters('cloud_tech_quote_billing_detail', $post_ID), $message);
			$email = get_post_meta($post_ID, 'current_user_email', true);

			ct_rfq_create_pdf([$post_ID]);

			$file_path = CT_RFQ_UPLOAD_DIR . 'user/quote-pdf-' . $post_ID . '.pdf';
			$attachments = array($file_path);

			if (!empty($enable_setting)) {
				wp_mail($email, $subject, $message, $headers, $attachments);

			}
		}

	}

}

new Ct_Rfq_Ajax();