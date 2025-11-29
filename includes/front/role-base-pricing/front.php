<?php

add_action('woocommerce_before_add_to_cart_button', 'cloud_tech_rbpaqpfw_show_pricing_table');
function cloud_tech_rbpaqpfw_show_pricing_table()
{

	global $product;


	if (!empty(get_option('ct_rbpaqp_show_role_pricing_on_product_page'))) {

		$is_prod_id_matching = $product->get_id();

		$all_rule_id_and_detail_on_customer_base = (array) ct_rbpaqp_rbp_check_for_specific_customer($is_prod_id_matching);
		$all_rule_id_and_detail_on_role_base = (array) ct_rbpaqp_rbp_check_for_specific_user_role($is_prod_id_matching);
		$all_rule_id_and_detail_on_customer_base = array_merge($all_rule_id_and_detail_on_customer_base, $all_rule_id_and_detail_on_role_base);

		$all_rule_id_and_detail_on_customer_base = ct_b2b_custom_array_filter($all_rule_id_and_detail_on_customer_base);
		?>
		<h3>
			<?php echo esc_attr(get_option('ct_rbpaqp_set_role_pricing_on_title')); ?>
		</h3>
		<?php
		if (count($all_rule_id_and_detail_on_customer_base) >= 1) {
			?>
			<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">
				<thead>
					<tr>
						<th>
							<?php echo esc_html__('Min Qty', 'cloud_tech_rbpaqpfw'); ?>
						</th>
						<th>
							<?php echo esc_html__('Max Qty', 'cloud_tech_rbpaqpfw'); ?>
						</th>
						<th>
							<?php echo esc_html__('Product Price', 'cloud_tech_rbpaqpfw'); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($all_rule_id_and_detail_on_customer_base as $current_post_id) {

						$discount_type = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_type', true);
						$discount_value = (float) get_post_meta($current_post_id, 'ct_role_base_pricing_discount_value', true);
						$discount_min = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_min', true) ? get_post_meta($current_post_id, 'ct_role_base_pricing_discount_min', true) : 1;
						$discount_max = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_max', true);

						if (empty($discount_value) || $discount_value <= 0.1) {
							continue;
						}

						$product_price = cloud_tech_rbpaqpfw_get_discount_from_current_rule($product->get_price(), $current_post_id);

						?>
						<tr>
							<td>
								<?php echo esc_attr($discount_min); ?>
							</td>
							<td>
								<?php echo esc_attr($discount_max); ?>
							</td>
							<td>
								<?php echo wp_kses_post(wc_price($product_price)); ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php
		}

	}

}


function ct_rbpaqp_rbp_get_current_user_country()
{
	$geo_data = WC_Geolocation::geolocate_ip();
	return $geo_data['country'];
}

function ct_rbpaqp_rbp_get_current_user_role()
{
	return is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';
}

function ct_rbpaqp_rbp_convertToLower($value)
{
	return strtolower($value);
}

function ct_rbpaqp_rbp_check_for_specific_customer($is_prod_matching, $current_product_qty = 1)
{
	$all_cutomers_pricing_detail = [];
	if (is_user_logged_in()) {

		if (!empty(get_post_meta($is_prod_matching, 'role_base_price_product_level', true))) {

			$is_any_customer_base_pricing = (array) ct_rbpaqp_rbp_check_for_specific_customer_on_this_post_id($is_prod_matching);
			if (count($is_any_customer_base_pricing) >= 1) {
				$all_cutomers_pricing_detail = array_merge($all_cutomers_pricing_detail, $is_any_customer_base_pricing);
			}

		} else {

			$all_post_id = get_posts([
				'post_type' => 'ct_role_base_pricing',
				'post_status' => 'publish',
				'fields' => 'ids',
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC',
			]);

			foreach ($all_post_id as $current_post_id) {

				$is_any_customer_base_pricing = (array) ct_rbpaqp_rbp_check_for_specific_customer_on_this_post_id($current_post_id, $is_prod_matching);
				if (count($is_any_customer_base_pricing) >= 1) {
					$all_cutomers_pricing_detail = array_merge($all_cutomers_pricing_detail, $is_any_customer_base_pricing);
					break;
				}

			}

		}
	}

	return $all_cutomers_pricing_detail;


}

function ct_rbpaqp_rbp_check_for_specific_customer_on_this_post_id($main_post_id, $product_id_for_checking = 0)
{


	$product = wc_get_product($main_post_id);

	$qty_and_price_array = [];

	$current_day = strtolower(gmdate('l'));
	$current_time = time();
	$current_date = strtotime(gmdate('d-m-Y'));


	$country = get_post_meta($main_post_id, 'ct_rbpaqp_rbp_inlcuded_country', true) ? get_post_meta($main_post_id, 'ct_rbpaqp_rbp_inlcuded_country', true) : [ct_rbpaqp_rbp_get_current_user_country()];

	$start_time = get_post_meta($main_post_id, 'ct_rbpaqp_rbp_start_time', true) ? strtotime(get_post_meta($main_post_id, 'ct_rbpaqp_rbp_start_time', true)) : $current_time;
	$end_time = get_post_meta($main_post_id, 'ct_rbpaqp_rbp_end_time', true) ? get_post_meta($main_post_id, 'ct_rbpaqp_rbp_end_time', true) : $current_time;


	if (!empty(ct_rbpaqp_rbp_get_current_user_country()) && !in_array(ct_rbpaqp_rbp_get_current_user_country(), $country)) {

		return $qty_and_price_array;
	}


	if ($current_time < $start_time || $end_time > $current_time) {

		return $qty_and_price_array;
	}

	if (!$product && $product_id_for_checking >= 1) {

		$selected_products = (array) get_post_meta($main_post_id, 'ct_rbpaqp_rbp_inlcuded_products', true);
		$selected_cat = (array) get_post_meta($main_post_id, 'ct_rbpaqp_rbp_inlcuded_category', true);
		$selected_tag = (array) get_post_meta($main_post_id, 'ct_rbpaqp_rbp_inlcuded_tag', true);
		$is_product_match = false;

		if (empty($selected_products) && empty($selected_cat) && empty($selected_tag)) {
			$is_product_match = true;
		}

		if (!empty($selected_products) && in_array($product_id_for_checking, $selected_products)) {
			$is_product_match = true;
		}
		if (!empty($selected_cat) && has_term($selected_cat, 'product_cat', $product_id_for_checking)) {
			$is_product_match = true;
		}
		if (!empty($selected_tag) && has_term($selected_tag, 'product_tag', $product_id_for_checking)) {
			$is_product_match = true;
		}

		if (!$is_product_match) {
			return $qty_and_price_array;
		}
	}

	$qty_and_price_array = [];
	$all_post_id = get_posts([
		'post_type' => 'ct_set_role_for_cbp',
		'post_status' => 'publish',
		'post_parent' => $main_post_id,
		'fields' => 'ids',
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
	]);

	$current_day = strtolower(gmdate('l'));

	foreach ($all_post_id as $current_post_id) {


		$start_date = get_post_meta($current_post_id, 'ct_rbpaqp_hpav_start_date', true) ? strtotime(get_post_meta($current_post_id, 'ct_rbpaqp_hpav_start_date', true)) : $current_date;
		$end_date = get_post_meta($current_post_id, 'ct_rbpaqp_hpav_end_date', true) ? strtotime(get_post_meta($current_post_id, 'ct_rbpaqp_hpav_end_date', true)) : $current_date;


		$inlcuded_days = get_post_meta($current_post_id, 'ct_rbpaqp_rbp_inlcuded_days', true) ? array_map('ct_rbpaqp_rbp_convertToLower', (array) get_post_meta($current_post_id, 'ct_rbpaqp_rbp_inlcuded_days', true)) : [$current_day];

		$discount_value = (float) get_post_meta($current_post_id, 'ct_role_base_pricing_discount_value', true);
		$discount_on = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_on', true);
		$discount_min = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_min', true);
		$discount_min = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_max', true);

		if ($current_date < $start_date || $end_date > $current_date) {
			continue;
		}

		if (!in_array(strtolower($current_day), $inlcuded_days)) {
			continue;
		}

		if (empty($discount_value)) {
			continue;
		}

		if ('do_not_apply_if_sale_price_exsist' == $discount_on) {

			if (!$product) {
				$product = wc_get_product($product_id_for_checking);
			}
			if ($product && $product->is_on_sale()) {
				continue;
			}
		}

		$selected_user = (array) get_post_meta($current_post_id, 'ct_role_base_pricing_selected_customer', true);
		if (!in_array(get_current_user_id(), $selected_user)) {
			continue;
		}
		$qty_and_price_array[$current_post_id] = $current_post_id;

	}
	return $qty_and_price_array;
}



// checking for user role .



function ct_rbpaqp_rbp_check_for_specific_user_role($is_prod_matching, $current_product_qty = 1)
{
	$all_cutomers_pricing_detail = [];
	if (is_user_logged_in()) {

		if (!empty(get_post_meta($is_prod_matching, 'role_base_price_product_level', true))) {

			$is_any_customer_base_pricing = (array) ct_rbpaqp_rbp_check_for_specific_user_role_on_this_post_id($is_prod_matching, 0, $current_product_qty);
			if (count($is_any_customer_base_pricing) >= 1) {
				$all_cutomers_pricing_detail = array_merge($all_cutomers_pricing_detail, $is_any_customer_base_pricing);
			}

		} else {

			$all_post_id = get_posts([
				'post_type' => 'ct_role_base_pricing',
				'post_status' => 'publish',
				'fields' => 'ids',
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC',
			]);

			foreach ($all_post_id as $current_post_id) {

				$is_any_customer_base_pricing = (array) ct_rbpaqp_rbp_check_for_specific_user_role_on_this_post_id($current_post_id, $is_prod_matching, $current_product_qty);
				if (count($is_any_customer_base_pricing) >= 1) {
					$all_cutomers_pricing_detail = array_merge($all_cutomers_pricing_detail, $is_any_customer_base_pricing);
					break;
				}

			}

		}
	}

	return $all_cutomers_pricing_detail;


}

function ct_rbpaqp_rbp_check_for_specific_user_role_on_this_post_id($main_post_id, $product_id_for_checking = 0, $current_product_qty = 1)
{


	$current_day = strtolower(gmdate('l'));
	$current_time = time();
	$current_date = strtotime(gmdate('d-m-Y'));
	$product = wc_get_product($main_post_id);
	$qty_and_price_array = [];
	$country = get_post_meta($main_post_id, 'ct_rbpaqp_rbp_inlcuded_country', true) ? get_post_meta($main_post_id, 'ct_rbpaqp_rbp_inlcuded_country', true) : [ct_rbpaqp_rbp_get_current_user_country()];
	$start_time = get_post_meta($main_post_id, 'ct_rbpaqp_rbp_start_time', true) ? strtotime(get_post_meta($main_post_id, 'ct_rbpaqp_rbp_start_time', true)) : $current_time;
	$end_time = get_post_meta($main_post_id, 'ct_rbpaqp_rbp_end_time', true) ? get_post_meta($main_post_id, 'ct_rbpaqp_rbp_end_time', true) : $current_time;

	if (!empty(ct_rbpaqp_rbp_get_current_user_country()) && !in_array(ct_rbpaqp_rbp_get_current_user_country(), $country)) {

		return $qty_and_price_array;
	}


	if ($current_time < $start_time || $end_time > $current_time) {

		return $qty_and_price_array;
	}

	if (!$product && $product_id_for_checking >= 1) {

		$selected_products = (array) get_post_meta($main_post_id, 'ct_rbpaqp_rbp_inlcuded_products', true);
		$selected_cat = (array) get_post_meta($main_post_id, 'ct_rbpaqp_rbp_inlcuded_category', true);
		$selected_tag = (array) get_post_meta($main_post_id, 'ct_rbpaqp_rbp_inlcuded_tag', true);
		$is_product_match = false;

		if (empty($selected_products) && empty($selected_cat) && empty($selected_tag)) {
			$is_product_match = true;
		}

		if (!empty($selected_products) && in_array($product_id_for_checking, $selected_products)) {
			$is_product_match = true;
		}
		if (!empty($selected_cat) && has_term($selected_cat, 'product_cat', $product_id_for_checking)) {
			$is_product_match = true;
		}
		if (!empty($selected_tag) && has_term($selected_tag, 'product_tag', $product_id_for_checking)) {
			$is_product_match = true;
		}

		if (!$is_product_match) {
			return $qty_and_price_array;
		}
	}

	$qty_and_price_array = [];
	$all_post_id = get_posts([
		'post_type' => 'ct_set_role_for_rbp',
		'post_status' => 'publish',
		'post_parent' => $main_post_id,
		'fields' => 'ids',
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
	]);

	$current_day = strtolower(gmdate('l'));

	foreach ($all_post_id as $current_post_id) {


		$start_date = get_post_meta($current_post_id, 'ct_rbpaqp_hpav_start_date', true) ? strtotime(get_post_meta($current_post_id, 'ct_rbpaqp_hpav_start_date', true)) : $current_date;
		$end_date = get_post_meta($current_post_id, 'ct_rbpaqp_hpav_end_date', true) ? strtotime(get_post_meta($current_post_id, 'ct_rbpaqp_hpav_end_date', true)) : $current_date;


		$inlcuded_days = get_post_meta($current_post_id, 'ct_rbpaqp_rbp_inlcuded_days', true) ? array_map('ct_rbpaqp_rbp_convertToLower', (array) get_post_meta($current_post_id, 'ct_rbpaqp_rbp_inlcuded_days', true)) : [$current_day];

		$discount_value = (float) get_post_meta($current_post_id, 'ct_role_base_pricing_discount_value', true);
		$discount_on = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_on', true);
		$discount_min = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_min', true) ? get_post_meta($current_post_id, 'ct_role_base_pricing_discount_min', true) : $current_product_qty;
		$discount_max = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_max', true) ? get_post_meta($current_post_id, 'ct_role_base_pricing_discount_max', true) : $current_product_qty;

		if ($current_date < $start_date || $end_date > $current_date) {
			continue;
		}
		if ($current_product_qty < $discount_min || $current_product_qty > $discount_max) {
			continue;
		}

		if (!in_array(strtolower($current_day), $inlcuded_days)) {
			continue;
		}

		if (empty($discount_value)) {
			continue;
		}

		if ('do_not_apply_if_sale_price_exsist' == $discount_on) {
			if (!$product) {
				$product = wc_get_product($product_id_for_checking);
			}
			if ($product && $product->is_on_sale()) {
				continue;
			}
		}

		$selected_user_role = get_post_meta($current_post_id, 'ct_role_base_pricing_selected_user_role', true) ? (array) get_post_meta($current_post_id, 'ct_role_base_pricing_selected_user_role', true) : [ct_rbpaqp_rbp_get_current_user_role()];
		if (!in_array(ct_rbpaqp_rbp_get_current_user_role(), $selected_user_role)) {
			continue;
		}
		$qty_and_price_array[$current_post_id] = $current_post_id;
	}
	return $qty_and_price_array;
}


// Regular Price Hook
add_filter('woocommerce_product_variation_get_price', 'cloud_tech_rbpaqpfw_wholeseller_regular_price', 10, 2);

add_filter('woocommerce_product_get_price', 'cloud_tech_rbpaqpfw_wholeseller_regular_price', 10, 2);
function cloud_tech_rbpaqpfw_wholeseller_regular_price($price, $product)
{

	$product_price = $price;


	if (is_cart()) {
		global $woocommerce;
		$cart_items = $woocommerce->cart->get_cart();

		foreach ($cart_items as $item) {

			$product_or_variation_id = isset($item['variation_id']) && $item['variation_id'] >= 1 ? $item['variation_id'] : $item['product_id'];

			if ($product_or_variation_id == $product->get_id()) {

				$quantity = isset($item['quantity']) && $item['quantity'] >= 1 ? $item['quantity'] : 1;

				$all_rule_id_and_detail_on_customer_base = (array) ct_rbpaqp_rbp_check_for_specific_customer($product->get_id(), $quantity);
				$all_rule_id_and_detail_on_role_base = (array) ct_rbpaqp_rbp_check_for_specific_user_role($product->get_id(), $quantity);
				$check_role_base_pricing_is_available = array_merge($all_rule_id_and_detail_on_customer_base, $all_rule_id_and_detail_on_role_base);


				$check_role_base_pricing_is_available = ct_b2b_custom_array_filter($check_role_base_pricing_is_available);

				foreach ($check_role_base_pricing_is_available as $current_post_id) {

					$discount_on = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_on', true);
					$discount_type = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_type', true);
					$discount_value = (float) get_post_meta($current_post_id, 'ct_role_base_pricing_discount_value', true);

					if ('regular_price' == $discount_on && $product->is_on_sale() && !empty($product->get_sale_price())) {
						continue;
					}

					if ('sale_price' == $discount_on && (!$product->is_on_sale() || !empty($product->get_sale_price()))) {
						continue;
					}

					if (empty($discount_value) || $discount_value <= 0.1) {
						continue;
					}

					$new_product_price = cloud_tech_rbpaqpfw_get_discount_from_current_rule($product_price, $current_post_id);
					$price = $new_product_price;

					break;
				}
			}
		}
	} else {


		$all_rule_id_and_detail_on_customer_base = (array) ct_rbpaqp_rbp_check_for_specific_customer($product->get_id());
		$all_rule_id_and_detail_on_role_base = (array) ct_rbpaqp_rbp_check_for_specific_user_role($product->get_id());
		$check_role_base_pricing_is_available = array_merge($all_rule_id_and_detail_on_customer_base, $all_rule_id_and_detail_on_role_base);

		$check_role_base_pricing_is_available = ct_b2b_custom_array_filter($check_role_base_pricing_is_available);

		foreach ($check_role_base_pricing_is_available as $current_post_id) {

			$discount_on = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_on', true);
			$discount_type = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_type', true);
			$discount_value = (float) get_post_meta($current_post_id, 'ct_role_base_pricing_discount_value', true);

			if ('regular_price' == $discount_on && $product->is_on_sale() && !empty($product->get_sale_price())) {
				continue;
			}

			if ('sale_price' == $discount_on && (!$product->is_on_sale() || !empty($product->get_sale_price()))) {
				continue;
			}

			if (empty($discount_value) || $discount_value <= 0.1) {
				continue;
			}

			$new_product_price = cloud_tech_rbpaqpfw_get_discount_from_current_rule($product_price, $current_post_id);
			$price = $new_product_price;

			if ('both_regular_and_sale_price' == $discount_on && $product->is_on_sale() && !empty($product->get_sale_price())) {

				$regular_price = cloud_tech_rbpaqpfw_get_discount_from_current_rule($product->get_regular_price(), $current_post_id);
				$product->set_regular_price($regular_price);

			}


			break;
		}
	}



	return $price;
}


function cloud_tech_rbpaqpfw_get_discount_from_current_rule($product_price, $current_post_id)
{
	$discount_on = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_on', true);
	$discount_type = get_post_meta($current_post_id, 'ct_role_base_pricing_discount_type', true);
	$discount_value = (float) get_post_meta($current_post_id, 'ct_role_base_pricing_discount_value', true);
	if ('fix_price' == $discount_type) {
		$product_price = $discount_value;
	}
	if ('fixed_increase' == $discount_type) {
		$product_price += $discount_value;

	}
	if ('fixed_decrease' == $discount_type) {
		$product_price -= $discount_value;

	}
	if ('percentage_increase' == $discount_type) {

		$product_price += ($discount_value / 100) * $product_price;

	}

	if ('percentage_decrease' == $discount_type) {
		$product_price -= ($discount_value / 100) * $product_price;
	}


	return $product_price;
}