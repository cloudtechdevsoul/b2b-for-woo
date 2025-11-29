<?php
if (!defined('WPINC')) {
	die;
}
require CT_RFQ_PLUGIN_DIR . 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;


if (!function_exists('get_set_roles')) {

	function get_set_roles()
	{

		global $wp_roles;

		$all_role = $wp_roles->get_names();

		$all_role['guest'] = 'Guest';

		foreach ((array) get_option('ct_tepfw_excludes_rule') as $role_key) {

			if (!empty($role_key) && $all_role[$role_key]) {

				unset($all_role[$role_key]);

			}

		}

		return $all_role;

	}

}



function ct_rfq_get_current_user_country()
{
	$geo_data = WC_Geolocation::geolocate_ip();
	return $geo_data['country'];
}

function ct_rfq_get_current_user_role()
{
	return is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';
}



function ct_rfq_mmq_get_current_user_role()
{
	return is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';
}

function ct_rfq_convertToLower($value)
{
	return strtolower($value);
}

function ct_rfq_custom_array_filter($filters = [])
{
	$filters = array_filter((array) $filters, function ($current_value, $current_key) {
		return ('' !== $current_value && '' !== $current_key);
	}, ARRAY_FILTER_USE_BOTH);

	return $filters;
}


function ct_rfq_is_this_product_is_aplicable($product)
{

	$all_rule = get_posts(['post_type' => 'ct-rfq-quote-rule', 'post_status' => ['publish', 'published'], 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC', 'fields' => 'ids']);


	$is_product_match = false;
	foreach ($all_rule as $current_rule_id) {

		$selected_products = (array) get_post_meta($current_rule_id, 'ct_rfq_inlcuded_products', true);
		$selected_products = ct_rfq_custom_array_filter($selected_products);

		$selected_category = (array) get_post_meta($current_rule_id, 'ct_rfq_inlcuded_category', true);
		$selected_category = ct_rfq_custom_array_filter($selected_category);

		$selected_tag = (array) get_post_meta($current_rule_id, 'ct_rfq_inlcuded_tag', true);
		$selected_tag = ct_rfq_custom_array_filter($selected_tag);

		$selected_country = (array) get_post_meta($current_rule_id, 'ct_rfq_inlcuded_country', true);
		$selected_country = ct_rfq_custom_array_filter($selected_country);

		$selected_customers = (array) get_post_meta($current_rule_id, 'ct_rfq_inlcuded_customers', true);
		$selected_customers = ct_rfq_custom_array_filter($selected_customers);

		$selected_role = (array) get_post_meta($current_rule_id, 'ct_rfq_inlcuded_role', true);
		$selected_role = ct_rfq_custom_array_filter($selected_role);

		if (count($selected_role) >= 1 && !in_array(ct_rfq_mmq_get_current_user_role(), $selected_role)) {
			continue;
		}

		if (count($selected_customers) >= 1 && !in_array(get_current_user_id(), $selected_customers)) {
			continue;
		}
		if (count($selected_country) >= 1 && !in_array(ct_rfq_get_current_user_country(), $selected_country)) {
			continue;
		}

		if (count($selected_products) < 1 && count($selected_category) < 1 && count($selected_tag) < 1) {
			$is_product_match = true;
		}

		if (count($selected_products) >= 1 && in_array($product->get_id(), $selected_products)) {
			$is_product_match = true;

		}
		if (count($selected_category) >= 1 && has_term($selected_category, 'product_cat', $product->get_id())) {
			$is_product_match = true;

		}
		if (count($selected_tag) >= 1 && has_term($selected_tag, 'product_tag', $product->get_id())) {
			$is_product_match = true;

		}

		if ($is_product_match) {
			return $current_rule_id;
		}


	}

	return $is_product_match;
}


function cloud_tech_add_quote($product_detail_array = [], $request_type = '')
{


	$rfq_page_id = get_rfq_pge_id();

	$added_products_to_quote = (array) wc()->session->get('cloud_tech_quote_cart');
	if ($product_detail_array['product_id']) {

		$product_id = $product_detail_array['product_id'];
		$variation_id = $product_detail_array['variation_id'];
		$quantity = $product_detail_array['quantity'];
		$product = wc_get_product($variation_id > 1 ? $variation_id : $product_id);
		$product_detail_array['available_qty'] = apply_filters('cloud_tech_product_available_qty', $product);
		$product_detail_array['data'] = $product;
		$cart_key = uniqid(true);
		$is_product_already_added = false;
		$product_detail_array['custom_price'] = isset($product_detail_array['custom_price']) ? $product_detail_array['custom_price'] : '';


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

		}

		if ($quantity <= 1) {
			$quantity = 1;
		}

		if (!$is_product_already_added) {

			if (!empty($product_detail_array['available_qty']) && $product_detail_array['available_qty'] >= 1 && $quantity >= $product_detail_array['available_qty']) {
				$quantity = $product_detail_array['available_qty'];
				$product_detail_array['quantity'] = $quantity;
			}

			$added_products_to_quote[$cart_key] = $product_detail_array;
		}

		wc()->session->set('cloud_tech_quote_cart', $added_products_to_quote);
		// wc()->session->set('cloud_tech_quote_cart',[]);


		$notice = !empty(get_option('ct_rfq_add_to_quote_message')) ? get_option('ct_rfq_add_to_quote_message') : '“ ' . $product->get_name() . ' ” has been added to your quote';
		$notice = str_replace('{product_name}', $product->get_name(), $notice);

		if ('ajax' === $request_type) {

			wp_send_json([

				'success_message' => wc_add_notice($notice . '<a href="' . get_page_link($rfq_page_id) . '" class="button wc-forward"> View Quote </a>', 'success'),
				'quote_page_url' => '<a href="' . get_page_link($rfq_page_id) . '" class="button wc-forward"> View Quote </a>',
			]);

		} else {

			wc_add_notice($notice . '<a href="' . get_page_link($rfq_page_id) . '" class="button wc-forward"> View Quote </a>', 'success');

		}

	}
}



function wc_get_quote_subtotal_ct($post_id = 0)
{

	$added_products_to_quote = [];
	if ($post_id > 1) {

		$added_products_to_quote = (array) get_post_meta($post_id, 'cloud_tech_quote_cart', true);

	} else if (!is_admin()) {

		$added_products_to_quote = (array) wc()->session->get('cloud_tech_quote_cart');
	}
	$sub_total = 0;
	foreach ($added_products_to_quote as $quote_key => $current_product_quote_detail) {

		if (!is_array($current_product_quote_detail)) {
			continue;
		}

		if (!isset($current_product_quote_detail['product_id']) || empty($current_product_quote_detail['product_id'])) {
			continue;
		}

		$product_id = ($current_product_quote_detail['product_id']);

		$variation_id = isset($current_product_quote_detail['variation_id']) ? ($current_product_quote_detail['variation_id']) : 0;

		$quantity = isset($current_product_quote_detail['quantity']) && $current_product_quote_detail['quantity'] >= 1 ? ($current_product_quote_detail['quantity']) : 1;

		$custom_price = isset($current_product_quote_detail['cloud_tech_custom_price' . $quote_key]) && !empty($current_product_quote_detail['cloud_tech_custom_price' . $quote_key]) && (float) $current_product_quote_detail['cloud_tech_custom_price' . $quote_key] >= 0.1 ? ($current_product_quote_detail['cloud_tech_custom_price' . $quote_key]) : 0;


		$product = wc_get_product($variation_id > 1 ? $variation_id : $product_id);

		if ($custom_price >= 0.1) {
			$sub_total += $custom_price * $quantity;
		} else {
			$sub_total += $product->get_price() * $quantity;

		}

	}

	return $sub_total;

}

function wc_get_quote_tax_ct($post_id = 0)
{

	$added_products_to_quote = [];

	if ($post_id > 1) {
		$added_products_to_quote = (array) get_post_meta($post_id, 'cloud_tech_quote_cart', true);
	} else if (!is_admin()) {

		$added_products_to_quote = (array) wc()->session->get('cloud_tech_quote_cart');
	}

	$tax_total = 0;
	foreach ($added_products_to_quote as $quote_key => $current_product_quote_detail) {

		if (!is_array($current_product_quote_detail)) {
			continue;
		}

		if (!isset($current_product_quote_detail['product_id']) || empty($current_product_quote_detail['product_id'])) {
			continue;
		}

		$product_id = ($current_product_quote_detail['product_id']);

		$variation_id = isset($current_product_quote_detail['variation_id']) ? ($current_product_quote_detail['variation_id']) : 0;

		$quantity = isset($current_product_quote_detail['quantity']) && $current_product_quote_detail['quantity'] >= 1 ? ($current_product_quote_detail['quantity']) : 1;

		$custom_price = isset($current_product_quote_detail['cloud_tech_custom_price' . $quote_key]) && !empty($current_product_quote_detail['cloud_tech_custom_price' . $quote_key]) && (float) $current_product_quote_detail['cloud_tech_custom_price' . $quote_key] >= 0.1 ? ($current_product_quote_detail['cloud_tech_custom_price' . $quote_key]) : 0;


		$product = wc_get_product($variation_id > 1 ? $variation_id : $product_id);

		// Calculate the tax amount

		if ($custom_price >= 0.1) {
			$product_price = $custom_price * $quantity;
		} else {
			$product_price = $product->get_price() * $quantity;

		}
		$price_including_tax = wc_get_price_including_tax($product, ['price' => $product_price]);
		$price_excluding_tax = wc_get_price_excluding_tax($product, ['price' => $product_price]);
		$tax_total += $price_including_tax - $price_excluding_tax;

	}

	return $tax_total;

}

function wc_get_quote_total_ct($post_id = 0)
{

	$total = 0;

	if ($post_id > 1) {

		$total += wc_get_quote_subtotal_ct($post_id);
		$total += wc_get_quote_tax_ct($post_id);
		$total += wc_get_dates_total($post_id);

	} else if (!is_admin()) {

		$total += wc_get_quote_subtotal_ct() + wc_get_quote_tax_ct();

	}

	return $total;

}

function wc_get_dates_total($post_id)
{

	$billing_details = (array) get_post_meta($post_id, 'cloud_tech_quote_billing_details', true);
	$total = 0;

	if (isset($billing_details['end-date']) && isset($billing_details['start-date'])) {

		// Create DateTime objects for each date
		$date1 = new DateTime($billing_details['start-date']);
		$date2 = new DateTime($billing_details['end-date']);

		// Calculate the difference in days
		$interval = $date1->diff($date2);
		$total += $interval->days * wc_get_quote_subtotal_ct($post_id);
	}

	return $total;
}

function get_rfq_pge_id()
{

	$rfq_page_id = current(
		get_posts(
			array(
				'post_type' => 'page',
				'post_title' => 'Request a Quote Cart',
				'post_status' => 'all',
				'numberposts' => -1,
				'fields' => 'ids',
				'meta_key' => 'cloud_tech_request_a_quote',
				'meta_value' => 'yes',
			)
		)
	);

	if ('make_auto_page' === (string) get_option('ct_rfq_request_a_quote_page_type') && !empty(get_option('ct_rfq_request_a_quote_page_id')) && get_post(get_option('ct_rfq_request_a_quote_page_id')) && 'publish' === (string) get_post_status(get_option('ct_rfq_request_a_quote_page_id'))) {

		return get_option('ct_rfq_request_a_quote_page_id');

	} else if (empty($rfq_page_id)) {

		$my_post = array(
			'post_title' => 'Request a Quote Cart',
			'post_content' => '[cloud_tech_request_a_quote]',
			'post_status' => 'publish',
			'post_type' => 'page',

		);

		$rfq_page_id = wp_insert_post($my_post);

		update_post_meta($rfq_page_id, 'cloud_tech_request_a_quote', 'yes');

	}

	return $rfq_page_id;
}

function ct_rfq_shortcodes()
{
	?>
		<table class="wp-list-table widefat fixed striped table-view-list af-purchased-product-detail-table">
			<thead>
				<th><?php echo esc_html__('Short Code', 'cloud_tech_rfq'); ?>
				<th>
				<th><?php echo esc_html__('Deatail', 'cloud_tech_rfq'); ?>
				<th>
			</thead>
			<tbody>
				<tr>
					<th><?php echo esc_html__('[whole_sale_request_a_quote_button]', 'cloud_tech_rfq'); ?>
					<th>
					<td><?php echo esc_html__('Set text of button ,this button will show with checkboxes', 'cloud_tech_rfq'); ?>
					</td>
				</tr>
				<tr>
					<th><?php echo esc_html__('[cloud_tech_request_a_quote]', 'cloud_tech_rfq'); ?>
					<th>
					<td><?php echo esc_html__('Show quote detail on any page', 'cloud_tech_rfq'); ?></td>
				</tr>
				<tr>
					<th><?php echo esc_html__('[cloud_tech_quote_table]', 'cloud_tech_rfq'); ?>
					<th>
					<td><?php echo esc_html__('Show quote table on any page', 'cloud_tech_rfq'); ?></td>
				</tr>

				<tr>
					<th><?php echo esc_html__('[cloud_tech_request_a_quote_mini_cart]', 'cloud_tech_rfq'); ?>
					<th>
					<td><?php echo esc_html__('Show mini quote on any page', 'cloud_tech_rfq'); ?></td>
				</tr>
			</tbody>
		</table>
		<?php
}

function ct_get_product_of_cat($category_ids = [])
{

	$args = array(
		'status' => 'publish',
		'limit' => -1,
		'return' => 'ids',
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => $category_ids,
				'operator' => 'IN',
			),
		),
	);

	$products = wc_get_products($args);

	return is_array($products) ? $products : array();

}


function ct_get_product_of_tag($tag_ids = [])
{

	$args = array(
		'status' => 'publish',
		'limit' => -1,
		'return' => 'ids',
		'tax_query' => array(
			array(
				'taxonomy' => 'product_tag',
				'field' => 'term_id',
				'terms' => $tag_ids,
				'operator' => 'IN',
			),
		),
	);

	$products = wc_get_products($args);

	return is_array($products) ? $products : array();

}


function ct_rfq_quote_details_with_post_id($post_id)
{
	$added_products_to_quote = (array) get_post_meta($post_id, 'cloud_tech_quote_cart', true);

	$added_products_to_quote = ct_rfq_custom_array_filter($added_products_to_quote);

	if (empty($added_products_to_quote)) {
		echo esc_html__('Quote Empty', 'woocommerce');
		return;
	}
	?>

		<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">
			<thead>
				<tr>
					<th class="product-thumbnail"><?php echo esc_html__('Thumbnail image', 'woocommerce'); ?></th>
					<th class="product-name"><?php echo esc_html__('Product', 'woocommerce'); ?></th>
					<th class="product-quantity"><?php echo esc_html__('Quantity', 'woocommerce'); ?></th>
					<th class="product-subtotal"><?php echo esc_html__('Subtotal', 'woocommerce'); ?></th>
				</tr>
			</thead>
			<tbody>

				<?php foreach ($added_products_to_quote as $quote_key => $current_product_quote_detail) {

					if (!is_array($current_product_quote_detail)) {
						continue;
					}

					if (!isset($current_product_quote_detail['product_id']) || empty($current_product_quote_detail['product_id'])) {
						continue;
					}

					$current_rule_id = isset($current_product_quote_detail['current_rule_id']) ? ($current_product_quote_detail['current_rule_id']) : 0;

					$product_id = ($current_product_quote_detail['product_id']);

					$variation_id = isset($current_product_quote_detail['variation_id']) ? ($current_product_quote_detail['variation_id']) : 0;

					$quantity = isset($current_product_quote_detail['quantity']) && $current_product_quote_detail['quantity'] >= 1 ? ($current_product_quote_detail['quantity']) : 1;

					$available_qty = isset($current_product_quote_detail['available_qty']) ? ($current_product_quote_detail['available_qty']) : '';

					if ('-1' === (string) $current_product_quote_detail['available_qty']) {
						$available_qty = '';
					}

					$custom_price = isset($current_product_quote_detail['custom_price']) ? ($current_product_quote_detail['custom_price']) : '';

					$product = wc_get_product($variation_id > 1 ? $variation_id : $product_id);

					?>
						<tr>

							<td>
								<?php

								$product_permalink = wc_placeholder_img_src();

								if (!empty(get_the_post_thumbnail_url($variation_id, 'post-thumbnail'))) {

									$product_permalink = get_the_post_thumbnail_url($variation_id, 'post-thumbnail');

								} else if (!empty(get_the_post_thumbnail_url($product_id, 'post-thumbnail'))) {

									$product_permalink = get_the_post_thumbnail_url($product_id, 'post-thumbnail');

								}
								?>
								<img fetchpriority="high" decoding="async" width="100" height="100"
									src="<?php echo esc_url($product_permalink); ?>"
									class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt=""
									srcset="<?php echo esc_url($product_permalink); ?>" sizes="(max-width: 324px) 100vw, 324px">

							</td>

							<td>
								<a
									href="<?php echo get_permalink($product_id); ?>"><?php echo esc_attr($variation_id > 1 ? get_the_title($variation_id) : get_the_title($product_id)); ?></a>
							</td>
							<td><?php echo esc_attr($quantity); ?></td>
							<td>
								<?php
								if (!empty($custom_price) && $custom_price >= 0.1) {
									echo wp_kses_post(wc_price($custom_price * $quantity));
								} else {
									echo wp_kses_post(wc_price($product->get_price() * $quantity));

								}
								?>
							</td>
						</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php
}


function ct_rfq_quote_cart_details_with_post_id($post_id)
{

	$billing_details = (array) get_post_meta($post_id, 'cloud_tech_quote_billing_details', true);

	?>
		<div class="cart-collaterals">
			<div class="cart_totals ">

				<h2><?php echo esc_html__('Cart totals', 'woocommerce'); ?></h2>

				<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">

					<tr class="cart-subtotal">
						<th><?php echo esc_html__('Subtotal', 'woocommerce'); ?></th>
						<td data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>"><?php
						   echo wp_kses_post(wc_price(wc_get_quote_subtotal_ct($post_id))); ?></td>
					</tr>


					<tr class="tax-total">
						<th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
						<td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>">
							<?php echo wp_kses_post(wc_price(wc_get_quote_tax_ct($post_id))); ?></td>
					</tr>

					<tr class="tax-total">
						<th><?php echo esc_html__('Price On Days', 'woocommerce'); ?></th>
						<td><?php echo wp_kses_post(wc_price(wc_get_dates_total($post_id))); ?></td>
					</tr>

					<tr class="order-total">
						<th><?php echo esc_html__('Total', 'woocommerce'); ?></th>
						<td data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>" data-total="">
							<?php echo wp_kses_post(wc_price(wc_get_quote_total_ct($post_id))); ?></td>
					</tr>

				</table>

			</div>

		</div>

		<h4> <?php echo esc_html__('Billing Details', 'cloud_tech_rfq'); ?> </h4>
		<?php

		$billing_fields = get_posts([
			'post_type' => 'ct-rfq-quote-fields',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'meta_query' => [
				[
					'key' => 'ct_rfq_quote_fields_show_field_with',
					'value' => 'in_billing_fields',
				]
			]
		]);

		$shipping_fields = get_posts([
			'post_type' => 'ct-rfq-quote-fields',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'meta_query' => [
				[
					'key' => 'ct_rfq_quote_fields_show_field_with',
					'value' => 'in_shipping_fields',
				]
			]
		]);
		?>
		<section class="af-rfq-custom-form">
			<div class="container">
				<div class="af-rfq-checkout-wrap">
					<h2>Checkout</h2>
					<?php if (count($billing_fields) >= 1) { ?>
							<div class="af-rfq-checkout-form">
								<div class="af-rfq-profile-checkout">
									<i class="fa fa-solid fa fa-user"></i>
									<h3><?php echo esc_html__('Profile', 'cloud_tech_rfq'); ?></h3>
									<div class="af-rfq-profile-mode">
										<label><input type="radio" name="billing-profile"
												class="billing-profile billing-profile-private" value="private"
												required=""><?php echo esc_html__('Private', 'cloud_tech_rfq'); ?></label>
										<label><input type="radio" name="billing-profile"
												class="billing-profile billing-profile-company" value="company"
												required=""><?php echo esc_html__('Company', 'cloud_tech_rfq'); ?></label>
									</div>
								</div>
								<table>
									<?php foreach ($billing_fields as $current_billing_post_id) {
										$show_with = '';
										if ('company' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_show_field_in_private_company', true)) {
											$show_with = 'ct-rfq-billing-fields-with-company';
										}
										if ('private' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_show_field_in_private_company', true)) {
											$show_with = 'ct-rfq-billing-fields-with-private';
										}
										$default_value = get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_default_value', true);
										$placeholder_holder_value = get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_placeholder', true);
										$additonal_classes = get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_additonal_class', true);
										?>
											<tr class="<?php echo esc_attr($show_with); ?>">
												<th><label
														for="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php echo esc_attr(get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_label', true)); ?></label>
												</th>
												<td>
													<?php
													if ('date' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?>
															<input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="date"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('time' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?>
															<input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="time"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('file_upload' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?>
															<input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="file"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('select' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {

														?><select class="ct-rfq-live-search <?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php

																   foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
																	   if (is_array($value)) {
																		   ?><option <?php selected(isset($value['option_value']) ? $value['option_value'] : '', $default_value) ?>
																					value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>">
																					<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																				</option><?php
																	   }
																   }
																   ?></select><?php


													}
													if ('multi_select' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><select class="ct-rfq-live-search <?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field[]"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php

																   foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
																	   if (is_array($value)) {
																		   ?><option <?php selected(isset($value['option_value']) ? $value['option_value'] : '', $default_value) ?>
																					value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>">
																					<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																				</option><?php
																	   }
																   }
																   ?></select><?php
													}
													if ('radio' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {

														foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
															if (is_array($value)) {
																?>
																			<input class="<?php echo esc_attr($additonal_classes); ?>"
																				placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																				<?php checked($default_value, isset($value['option_value']) ? $value['option_value'] : ''); ?>
																				type="radio"
																				name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																				value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>">
																			<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																			<?php
															}
														}

													}
													if ('checkbox' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?>
															<input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>" type="checkbox"
																value="<?php echo esc_attr($current_billing_post_id); ?>"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('multi_checkbox' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {

														foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
															if (is_array($value)) {
																?>
																			<input class="<?php echo esc_attr($additonal_classes); ?>"
																				placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																				<?php checked($default_value, isset($value['option_value']) ? $value['option_value'] : ''); ?>
																				type="checkbox"
																				value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>"
																				name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field[]"
																				id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																			<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																			<br>
																			<?php
															}
														}
													}
													if ('countries' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														$countries = new WC_Countries();
														?>
															<select class="ct-rfq-live-search <?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																class="ct-rfq-live-search"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																<?php foreach ($countries->get_countries() as $key => $value) { ?>

																		<option
																			<?php selected($default_value, isset($value['option_value']) ? $value['option_value'] : ''); ?>
																			value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value); ?></option>

																<?php } ?>
															</select>
															<?php
													}
													if ('input' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="text"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php
													}
													if ('textarea' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><textarea class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																cols="30" rows="10"><?php echo esc_attr($default_value); ?></textarea><?php
													}
													if ('number' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="number" min="1"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('color' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="color"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('email' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="email"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('password' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="password"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('telephone' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="tel"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													?>
												</td>
											</tr>

									<?php } ?>
								</table>

							</div>
					<?php } ?>
				</div>
				<div class="af-rfq-checkout-wrap">
					<?php if (count($shipping_fields) >= 1) { ?>

							<div class="af-rfq-checkout-form">
								<div class="af-rfq-profile-checkout">
									<i class="fa-solid fa-truck-fast"></i>
									<h3><?php echo esc_html__('Shipping', 'cloud_tech_rfq'); ?></h3>
									<div class="af-rfq-profile-mode">
										<label><input type="radio" name="shipping-profile"
												class="shipping-profile shipping-profile-private"
												value="private"><?php echo esc_html__('Private', 'cloud_tech_rfq'); ?></label>
										<label><input type="radio" name="shipping-profile"
												class="shipping-profile shipping-profile-company"
												value="company"><?php echo esc_html__('Delivery', 'cloud_tech_rfq'); ?></label>
									</div>
								</div>
								<table>
									<?php foreach ($shipping_fields as $current_billing_post_id) {
										$show_with = '';
										if ('company' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_show_field_in_private_company', true)) {
											$show_with = 'ct-rfq-shipping-fields-with-company';
										}
										if ('private' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_show_field_in_private_company', true)) {
											$show_with = 'ct-rfq-shipping-fields-with-private';
										}
										$default_value = get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_default_value', true);
										$placeholder_holder_value = get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_placeholder', true);
										$additonal_classes = get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_additonal_class', true);

										?>
											<tr class="<?php echo esc_attr($show_with); ?>">
												<th>
													<label
														for="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php echo esc_attr(get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_label', true)); ?></label>
												</th>
												<td>
													<?php
													if ('date' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?>
															<input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="date"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('time' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?>
															<input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="time"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('file_upload' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?>
															<input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="file"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('select' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {

														?><select class="ct-rfq-live-search <?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php

																   foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
																	   if (is_array($value)) {
																		   ?><option <?php selected(isset($value['option_value']) ? $value['option_value'] : '', $default_value) ?>
																					value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>">
																					<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																				</option><?php
																	   }
																   }
																   ?></select><?php


													}
													if ('multi_select' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><select class="ct-rfq-live-search <?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field[]"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php

																   foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
																	   if (is_array($value)) {
																		   ?><option <?php selected(isset($value['option_value']) ? $value['option_value'] : '', $default_value) ?>
																					value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>">
																					<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																				</option><?php
																	   }
																   }
																   ?></select><?php
													}
													if ('radio' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {

														foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
															if (is_array($value)) {
																?>
																			<input class="<?php echo esc_attr($additonal_classes); ?>"
																				placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																				<?php checked($default_value, isset($value['option_value']) ? $value['option_value'] : ''); ?>
																				type="radio"
																				name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																				value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>">
																			<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																			<?php
															}
														}

													}
													if ('checkbox' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?>
															<input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>" type="checkbox"
																value="<?php echo esc_attr($current_billing_post_id); ?>"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('multi_checkbox' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {

														foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
															if (is_array($value)) {
																?>
																			<input class="<?php echo esc_attr($additonal_classes); ?>"
																				placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																				<?php checked($default_value, isset($value['option_value']) ? $value['option_value'] : ''); ?>
																				type="checkbox"
																				value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>"
																				name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field[]"
																				id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																			<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																			<br>
																			<?php
															}
														}
													}
													if ('countries' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														$countries = new WC_Countries();
														?>
															<select class=" ct-rfq-live-search <?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																class="ct-rfq-live-search"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																<?php foreach ($countries->get_countries() as $key => $value) { ?>

																		<option
																			<?php selected($default_value, isset($value['option_value']) ? $value['option_value'] : ''); ?>
																			value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value); ?></option>

																<?php } ?>
															</select>
															<?php
													}
													if ('input' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="text"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php
													}
													if ('textarea' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><textarea class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																cols="30" rows="10"><?php echo esc_attr($default_value); ?></textarea><?php
													}
													if ('number' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="number" min="1"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('color' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="color"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('email' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="email"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('password' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="password"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													if ('telephone' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
														?><input class="<?php echo esc_attr($additonal_classes); ?>"
																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																value="<?php echo esc_attr($default_value); ?>" type="tel"
																name="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
															<?php
													}
													?>
												</td>
											</tr>

									<?php } ?>
								</table>

							</div>
					<?php } ?>

				</div>
			</div>
		</section>
		<?php
}

function ct_rfq_create_pdf($qoute_id_arr = [], $download_all = false, $admin_setting = false)
{
	$qoute_id_arr = (array) $qoute_id_arr;
	$site_name = get_option('ct_rfq_enable_company_name') ? get_option('ct_rfq_enable_company_name') : get_bloginfo('name');
	$site_url = get_option('ct_rfq_company_logos');
	$admin_email = get_option('admin_email');
	$address = get_option('ct_rfq_enable_company_address') ? get_option('ct_rfq_enable_company_address') : get_option('woocommerce_email_from_address');
	$afrfq_text_color_for_background = get_option('ct_rfq_text_background_color');
	$afrfq_backrgound_color = get_option('ct_rfq_layout_background_color');
	$psd_csv_coulmn = ['thumbnail', 'item', 'quantity', 'price', 'subtotal', 'offered_price', 'offered_subtotal', 'quote_subtotal', 'quote_offered_total', 'quote_total_tax', 'quote_total_total'];

	$psd_csv_coulmn = empty(get_option('ct_rfq_allowed_pdf')) || $admin_setting ? array_keys($psd_csv_coulmn) : (array) get_option('ct_rfq_allowed_pdf');
	$template_type = get_option('ct_rfq_enable_select_template') ? get_option('ct_rfq_enable_select_template') : '1st-template';

	if ($download_all) {
		ob_start();
	}



	foreach ($qoute_id_arr as $qoute_id) {

		if (!$download_all) {
			ob_start();
		}

		$quote_contents = (array) get_post_meta($qoute_id, 'cloud_tech_quote_cart', true);
		$quote_contents = ct_rfq_custom_array_filter($quote_contents);
		$afrfq_get_qoute_by_id = get_post($qoute_id);
		$ct_rfq_qoute_status = get_post_meta($qoute_id, 'quote_status', true) ? get_post_meta($qoute_id, 'quote_status', true) : current(array_keys(wc_get_order_statuses()));
		$ct_rfq_qoute_status = wc_get_order_status_name($ct_rfq_qoute_status);

		include CT_RFQ_PLUGIN_DIR . 'templates/pdf/' . $template_type . '.php';

		if (!$download_all) {

			$html = ob_get_clean();
			if (file_exists(CT_RFQ_UPLOAD_DIR . 'user/quote-pdf-' . $qoute_id . 'pdf')) {
				unlink(CT_RFQ_UPLOAD_DIR . 'user/quote-pdf-' . $qoute_id . 'pdf');
			}

			$options = new Options();
			$options->set('isRemoteEnabled', true);
			$dompdf = new Dompdf($options);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			$pdf_content = $dompdf->output();

			$dompdf->stream('quote-pdf-' . $qoute_id, array('Attachment' => 1));
			file_put_contents(CT_RFQ_UPLOAD_DIR . 'user/quote-pdf-' . $qoute_id . '.pdf', $pdf_content);

		}

	}

	if ($download_all) {

		$html = ob_get_clean();

		if (file_exists(CT_RFQ_UPLOAD_DIR . 'admin/quote-pdf')) {
			unlink(CT_RFQ_UPLOAD_DIR . 'admin/quote-pdf');
		}

		$options = new Options();
		$options->set('isRemoteEnabled', true);
		$dompdf = new Dompdf($options);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$pdf_content = $dompdf->output();

		$dompdf->stream('all-quote', array('Attachment' => 1));
		file_put_contents(CT_RFQ_UPLOAD_DIR . 'admin/all-quote.pdf', $pdf_content);

	}


}

function ct_rfq_create_csv($qoute_id_arr = [], $admin_setting = false)
{
	$qoute_id_arr = (array) $qoute_id_arr;
	$required_content = ['quote_id' => 'Quote ID', 'quote_status' => 'Quote Status', 'quote_date' => 'Quote Date', 'item' => 'Item', 'quantity' => 'Item QTY', 'price' => 'Item Price', 'subtotal' => 'subtotal', 'offered_price' => 'Item Offered Price', 'offered_subtotal' => 'Offered Subtotal',];
	$allowed_column = (array) get_option('ct_rfq_allowed_pdf');
	$allowed_column = ct_rfq_custom_array_filter($allowed_column);

	if (count($allowed_column) >= 1) {

		foreach ($required_content as $key => $value) {

			if (!isset($allowed_column[$key])) {
				unset($required_content[$value]);

			}
		}

	}



	header('Pragma: public');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Cache-Control: private', false);
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="addify-customer-manager.csv";');
	header('Content-Transfer-Encoding: binary');

	$file = fopen(CT_RFQ_PLUGIN_DIR . 'assets/export-csv/export.csv', 'w+');

	fputcsv($file, $required_content);


	foreach ($qoute_id_arr as $qoute_id) {

		$quote_contents = (array) get_post_meta($qoute_id, 'cloud_tech_quote_cart', true);
		$quote_contents = ct_rfq_custom_array_filter($quote_contents);
		$ct_rfq_qoute_status = get_post_meta($qoute_id, 'quote_status', true) ? get_post_meta($qoute_id, 'quote_status', true) : current(array_keys(wc_get_order_statuses()));
		$ct_rfq_qoute_status = wc_get_order_status_name($ct_rfq_qoute_status);


		$offered_total = 0;
		foreach ($quote_contents as $quote_key => $current_product_quote_detail) {

			if (!is_array($current_product_quote_detail)) {
				continue;
			}

			if (!isset($current_product_quote_detail['product_id']) || empty($current_product_quote_detail['product_id'])) {
				continue;
			}

			$product_id = $current_product_quote_detail['product_id'];

			$variation_id = isset($current_product_quote_detail['variation_id']) ? ($current_product_quote_detail['variation_id']) : 0;

			$quantity = isset($current_product_quote_detail['quantity']) && $current_product_quote_detail['quantity'] >= 1 ? ($current_product_quote_detail['quantity']) : 1;

			$custom_price = isset($current_product_quote_detail['custom_price']) ? (int) ($current_product_quote_detail['custom_price']) : 0;

			$product = wc_get_product($variation_id > 1 ? $variation_id : $product_id);

			$product_permalink = wc_placeholder_img_src();

			if (!empty(get_the_post_thumbnail_url($variation_id, 'post-thumbnail'))) {

				$product_permalink = get_the_post_thumbnail_url($variation_id, 'post-thumbnail');

			} else if (!empty(get_the_post_thumbnail_url($product_id, 'post-thumbnail'))) {
				$product_permalink = get_the_post_thumbnail_url($product_id, 'post-thumbnail');
			}
			$price = $product->get_price();

			$offered_total += $custom_price >= 1 ? $custom_price * $quantity : $price * $quantity;

			$required_content['quote_id'] = '';
			$required_content['quote_date'] = '';
			$required_content['quote_status'] = '';


			if (current(array_keys($quote_contents)) == $quote_key) {

				$required_content['quote_id'] = $qoute_id;
				$required_content['quote_date'] = gmdate('Y-m-d', strtotime(get_post_field('post_date', $qoute_id)));
				$required_content['quote_status'] = $ct_rfq_qoute_status;

			}

			if (isset($required_content['item'])) {
				$required_content['item'] = $product->get_name();
			}
			if (isset($required_content['quantity'])) {
				$required_content['quantity'] = $quantity;
			}
			if (isset($required_content['price'])) {
				$required_content['price'] = ($price);
			}
			if (isset($required_content['subtotal'])) {
				$required_content['subtotal'] = ($price * $quantity);
			}
			if (isset($required_content['offered_price'])) {
				$required_content['offered_price'] = ($custom_price);
			}
			if (isset($required_content['offered_subtotal'])) {
				$required_content['offered_subtotal'] = ($custom_price * $quantity);
			}
			fputcsv($file, $required_content);


		}

		$cart_details = [];

		if (in_array('quote_subtotal', (array) $allowed_column)) {
			$cart_details[] = 'Subtotal';
			$cart_details[] = (wc_get_quote_subtotal_ct($qoute_id));

		}

		if (in_array('quote_offered_total', (array) $allowed_column)) {
			$cart_details[] = 'Offered Subtotal';
			$cart_details[] = ($offered_total);

		}

		if (in_array('quote_total_tax', (array) $allowed_column)) {
			$cart_details[] = 'Quote Tax Total';
			$cart_details[] = (wc_get_quote_tax_ct($qoute_id));

		}

		if (in_array('quote_total_total', (array) $allowed_column)) {
			$cart_details[] = 'Total';
			$cart_details[] = (wc_get_quote_total_ct($qoute_id));

		}


		fputcsv($file, $cart_details);

		fputcsv($file, ['','']);


	}

	echo wp_kses_post(file_get_contents(CT_RFQ_PLUGIN_DIR . 'assets/export-csv/export.csv'));

	fclose($file);

	exit;


}

function ct_rfq_handle_file_upload($file = [])
{

	$file = (array) $file;
	$upload_dir = wp_upload_dir(); // Get upload directory
	$upload_path = $upload_dir['path'] . '/';
	$file_name = sanitize_file_name($file['name']);

	$file_path = $upload_path . $file_name;

	move_uploaded_file($file['tmp_name'], $file_path);

	$attachment_id = wp_insert_attachment(
		array(
			'post_title' => $file_name,
			'post_content' => '',
			'post_status' => 'inherit'
		),
		$file_path
	);

	require_once(ABSPATH . 'wp-admin/includes/image.php');
	$attach_data = wp_generate_attachment_metadata($attachment_id, $file_path);
	wp_update_attachment_metadata($attachment_id, $attach_data);

	return $attachment_id;
}