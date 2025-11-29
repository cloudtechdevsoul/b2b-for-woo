<?php


$post_id = get_the_ID();
$af_hspm_review_products = (array) get_post_meta(get_the_ID(), 'af_hspm_review_products', true);
$af_hspm_category_review = (array) get_post_meta(get_the_ID(), 'af_hspm_category_review', true);
$ct_hsp_user_role = (array) get_post_meta(get_the_ID(), 'ct_hsp_user_role', true);
$cthspm_country_review = (array) get_post_meta(get_the_ID(), 'cthspm_country_review', true);
$af_hspm_user_review = (array) get_post_meta(get_the_ID(), 'af_hspm_user_review', true);
$city_cart_minmum_amount = get_post_meta(get_the_ID(), 'city_cart_minmum_amount', true);
$city_cart_maximum_amount = get_post_meta(get_the_ID(), 'city_cart_maximum_amount', true);
$city_restrict_cart_total = (array) get_post_meta(get_the_ID(), 'city_restrict_cart_total', true);
$city_payment_or_shipping = (array) get_post_meta(get_the_ID(), 'city_payment_or_shipping', true);

$cthspm_payment_method_review = (array) get_post_meta(get_the_ID(), 'cthspm_payment_method_review', true);
$cthspm_shipping_methods = (array) get_post_meta(get_the_ID(), 'cthspm_shipping_methods', true);

$ct_q_t = get_post_meta(get_the_ID(), 'ct_q_t', true);


$countries = new WC_Countries();
$countries_name = $countries->get_countries();
function get_available_payment_methods()
{
	$payment_gateways = WC_Payment_Gateways::instance()->get_available_payment_gateways();

	$payment_methods = array();
	foreach ($payment_gateways as $gateway) {
		$payment_methods[] = $gateway;
	}

	return $payment_methods;
}

function get_all_shipping_methods()
{
	$shipping_methods = array();

	// Assuming you are in a WooCommerce environment
	if (class_exists('WC_Shipping_Zones')) {
		// Get all shipping zones
		$shipping_zones = WC_Shipping_Zones::get_zones();

		foreach ($shipping_zones as $zone) {
			// Get all shipping methods in the zone
			$zone_shipping_methods = $zone['shipping_methods'];

			foreach ($zone_shipping_methods as $shipping_method) {
				$shipping_methods[] = $shipping_method;
			}
		}
	}

	return $shipping_methods;
}
?>
<div class="af_rfd_main">
	<table>
		<tr>
			<td>
				<label>
					<?php
					wp_nonce_field('ct_hpasm', 'ct_hpasm');
					echo esc_html__('Select User role', 'hide_payment_method'); ?>
				</label>
			</td>
			<td>
				<select class="ct_hide_shipping_payment_serch_user wc-enhanced-select sel2" name="ct_hsp_user_role[]"
					id="ct_hsp_user_role" multiple style="width: 100%;">
					<?php
					global $wp_roles;
					$roles = $wp_roles->get_names();
					$roles['guest'] = 'Guest';

					foreach ($roles as $key => $value) {
						?>
						<option value="<?php echo esc_attr($key); ?>" <?php echo !empty($ct_hsp_user_role) && in_array($key, $ct_hsp_user_role) ? 'selected' : ''; ?>>
							<?php echo esc_attr($value); ?>
						</option>
					<?php } ?>
				</select>
			</td>
		</tr>

		<tr>
			<td>
				<label>
					<?php echo esc_html__('Products', 'hide_payment_method'); ?>
				</label>
			</td>
			<td>
				<select
					class="af_cp_included_product_list_section ct_hspm_product_live_search af_cp_included_product_list af_cp_select_prd af-cp-prd-scroll"
					name="af_hspm_review_products[]" multiple style="width: 100%;">
					<?php
					foreach ((array) $af_hspm_review_products as $product_id) {
						if ($product_id) {
							$product = wc_get_product($product_id);
							if (!$product) {
								continue;
							}
							?>
							<option value="<?php echo esc_attr($product_id); ?>" selected>
								<?php echo esc_attr(wc_get_product($product_id)->get_name()); ?>
							</option>
							<?php
						}
					}
					?>
				</select>
			</td>
		</tr>

		<tr>
			<td>
				<label for="product_categories">
					<?php esc_html_e('categories', 'hide_payment_method'); ?>
			</td>
			<td>
				<select class="ct_hspm_categroy_live_search af-cp-prd-scroll" name="af_hspm_category_review[]" multiple
					style="width:100%;">
					<?php
					foreach ((array) $af_hspm_category_review as $value) {
						if ($value) {
							$term_name = get_term($value)->name;
							?>
							<option value="<?php echo esc_attr($value); ?>" selected>
								<?php echo esc_attr($term_name); ?>
							</option>
							<?php
						}
					}
					?>
				</select>
			</td>
		</tr>

		<tr>
			<td>
				<?php echo esc_html__('Select User', 'hide_payment_method'); ?>
			</td>
			<td>
				<select class="ct_hspm_user_live_search af-cp-prd-scroll" name="af_hspm_user_review[]" multiple
					style="width:100%;">
					<?php
					foreach ($af_hspm_user_review as $user_id) {
						$user_data = get_userdata($user_id);
						if ($user_data) {
							$user_display_name = $user_data->display_name;
							?>
							<option value="<?php echo esc_attr($user_id); ?>" selected>
								<?php echo esc_attr($user_display_name); ?>
							</option>
							<?php
						}
					}
					?>
				</select>
			</td>
		</tr>

		<tr>
			<td>
				<?php echo esc_html__('Select Coutry', 'hide_payment_method'); ?>
			</td>
			<td>
				<select class="ct_hspm_country_live_search af-cp-prd-scroll" name="cthspm_country_review[]" multiple
					style="width:100%;">
					<?php
					foreach ($countries_name as $country_key => $country_name) {
						if ($country_name) {
							?>
							<option value="<?php echo esc_attr($country_key); ?>" <?php echo esc_attr(in_array($country_key, $cthspm_country_review) ? 'selected' : ''); ?>>
								<?php echo esc_attr($country_name); ?>
							</option>
							<?php
						}
					}
					?>
				</select>
			</td>
		</tr>

		<tr>
			<td>
				<?php echo esc_html__('Restrication on cart total and cart Quantity', 'hide_payment_method'); ?>
			</td>
			<td>
				<select class="city_restriction_cart_total" name="city_restrict_cart_total" style="width: 50%;">
					<option value="city_cart_total" <?php
					if (in_array('city_cart_total', $city_restrict_cart_total)) {
						echo 'selected';
					}
					?>>
						<?php echo esc_html__('Cart total', 'hide_payment_method'); ?>
					</option>
					<option value="city_cart_quantity" <?php
					if (in_array('city_cart_quantity', $city_restrict_cart_total)) {
						echo 'selected';
					}
					?>>
						<?php echo esc_html__('Cart Quantity', 'hide_payment_method'); ?>
					</option>
				</select>
			</td>
		</tr>

		<tr>
			<td>
				<?php echo esc_html__('Minimum', 'hide_payment_method'); ?>
			</td>
			<td>
				<input type="number" min="1" name="city_cart_minmum_amount"
					value="<?php echo esc_attr($city_cart_minmum_amount); ?>">
			</td>
		</tr>

		<tr>
			<td>
				<?php echo esc_html__('Maximum', 'hide_payment_method'); ?>
			</td>
			<td>
				<input type="number" min="1" name="city_cart_maximum_amount"
					value="<?php echo esc_attr($city_cart_maximum_amount); ?>">
			</td>
		</tr>

		<tr>
			<td>
				<?php echo esc_html__('Select Payment method or shipping', 'hide_payment_method'); ?>
			</td>
			<td>
				<select class="city_payment_or_shipping" name="city_payment_or_shipping" style="width: 50%;">
					<option value="city_both" <?php
					if (in_array('city_both', $city_payment_or_shipping)) {
						echo 'selected';
					}
					?>>
						<?php echo esc_html__('Both', 'hide_payment_method'); ?>
					</option>
					<option value="city_shipping" <?php
					if (in_array('city_shipping', $city_payment_or_shipping)) {
						echo 'selected';
					}
					?>>
						<?php echo esc_html__('Shipping Method', 'hide_payment_method'); ?>
					</option>
					<option value="city_payment_method" <?php
					if (in_array('city_payment_method', $city_payment_or_shipping)) {
						echo 'selected';
					}
					?>>
						<?php echo esc_html__('Payment Method', 'hide_payment_method'); ?>
					</option>
				</select>
			</td>
		</tr>

		<tr class="ct_hspm_payment_method">
			<td>
				<?php echo esc_html__('Select Payment method', 'hide_payment_method'); ?>
			</td>
			<td>
				<select class="cthspm_live_search af-cp-prd-scroll" name="cthspm_payment_method_review[]" multiple
					style="width:100%;">
					<?php
					$available_payment_methods = get_available_payment_methods();
					foreach ($available_payment_methods as $payment_gateway) {
						?>
						<option value="<?php echo esc_attr($payment_gateway->id); ?>" <?php echo in_array($payment_gateway->id, $cthspm_payment_method_review) ? 'selected="selected"' : ''; ?>>
							<?php echo esc_attr($payment_gateway->get_title()); ?>
						</option>
						<?php
					}
					?>
				</select>
			</td>
		</tr>

		<tr class="ct_hspm_shipping_method">
			<td>
				<?php
				echo esc_html__('Select Shipping Method', 'hide_payment_method');

				$all_shipping_methods = get_all_shipping_methods();

				?>
			</td>
			<td>
				<select class="cthspm_live_search" name="cthspm_shipping_methods[]" multiple style="width:100%;">
					<?php

					foreach ($all_shipping_methods as $shipping_method) {
						$shipping_method_id = $shipping_method->id;
						$shipping_method_title = $shipping_method->get_title();
						if (!empty($shipping_method_id)) {
							?>
							<option value="<?php echo esc_attr($shipping_method_id); ?>" <?php echo in_array($shipping_method_id, $cthspm_shipping_methods) ? 'selected="selected"' : ''; ?>>
								<?php echo esc_attr($shipping_method_title); ?>
							</option>


							<?php
						}
					}
					?>
				</select>
			</td>
		</tr>
	</table>
</div>