<?php

if (class_exists('CT_H_P_S_Front')) {
	new CT_H_P_S_Front();
}

if (!defined('ABSPATH')) {
	exit(); // Exit if accessed directly.
}

if (!class_exists('CT_H_P_S_Front')) {

	class CT_H_P_S_Front
	{

		public function __construct()
		{

			add_filter('woocommerce_available_payment_gateways', array($this, 'ct_b2b_remove_payment_gateway'));
			add_filter('woocommerce_package_rates', array($this, 'ct_b2b_remove_shipping_method'), 10, 2);
		}




		public function ct_b2b_remove_shipping_method($shipping_methods, $package)
		{

			if (!WC()->cart || is_admin()) {
				return $shipping_methods;
			}
			$cart = WC()->cart;
			$cart_items = $cart->get_cart();
			$cart_total = $cart->get_cart_contents_total();
			$user_cart_quantity = $cart->get_cart_contents_count();
			$roles = is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';

			$location = WC_Geolocation::geolocate_ip();
			$cor_visitor_current_country = $location['country'];
			$user = wp_get_current_user();
			$user_id = $user->ID;

			$arg = get_posts(
				array(
					'numberposts' => -1,
					'post_type' => 'city_hide',
					'post_status' => 'publish',
					'fields' => 'ids',
				)
			);
			foreach ($arg as $rule_id) {
				$ct_hsp_user_role = (array) get_post_meta($rule_id, 'ct_hsp_user_role', true);
				$af_hspm_review_products = (array) get_post_meta($rule_id, 'af_hspm_review_products', true);
				$af_hspm_category_review = (array) get_post_meta($rule_id, 'af_hspm_category_review', true);
				$af_hspm_user_review = (array) get_post_meta($rule_id, 'af_hspm_user_review', true);
				$cthspm_country_review = (array) get_post_meta($rule_id, 'cthspm_country_review', true);

				$city_payment_or_shipping = get_post_meta($rule_id, 'city_payment_or_shipping', true);
				$cthspm_payment_method_review = (array) get_post_meta($rule_id, 'cthspm_payment_method_review', true);


				$cthspm_shipping_methods = (array) get_post_meta($rule_id, 'cthspm_shipping_methods', true);
				$city_restriction_cart_total = get_post_meta($rule_id, 'city_restriction_cart_total', true);


				$flag = true;


				if (!empty($ct_hsp_user_role) && !in_array($roles, $ct_hsp_user_role, )) {
					continue;
				}
				if (!empty($af_hspm_user_review) && !in_array($user_id, $af_hspm_user_review)) {
					continue;
				}
				if (!empty($cthspm_country_review) && !in_array($cor_visitor_current_country, $cthspm_country_review)) {
					continue;
				}

				foreach ($cart_items as $cart_item_key => $cart_item) {

					$product_id = $cart_item['product_id'];
					if ((!empty($af_hspm_review_products) && in_array($product_id, $af_hspm_review_products)) || (!empty($af_hspm_category_review) && has_term($af_hspm_category_review, 'product_cat', $product_id))) {
						$flag = false;
					}
				}

				if ((empty($af_hspm_review_products)) || (empty($af_hspm_category_review))) {

					$flag = false;
				}


				if ($flag) {
					continue;
				}
				if ('city_cart_quantity' == $city_restriction_cart_total) {


					$city_cart_minmum_amount = !empty(get_post_meta($rule_id, 'city_cart_minmum_amount', true)) ? (float) get_post_meta($rule_id, 'city_cart_minmum_amount', true) : $user_cart_quantity;
					$city_cart_maximum_amount = !empty(get_post_meta($rule_id, 'city_cart_maximum_amount', true)) ? (float) get_post_meta($rule_id, 'city_cart_maximum_amount', true) : $user_cart_quantity;
					$cart_total_or_qty = $user_cart_quantity;

				} else {
					$city_cart_minmum_amount = !empty(get_post_meta($rule_id, 'city_cart_minmum_amount', true)) ? (float) get_post_meta($rule_id, 'city_cart_minmum_amount', true) : $cart_total;
					$city_cart_maximum_amount = !empty(get_post_meta($rule_id, 'city_cart_maximum_amount', true)) ? (float) get_post_meta($rule_id, 'city_cart_maximum_amount', true) : $cart_total;
					$cart_total_or_qty = $cart_total;
				}

				if ($cart_total_or_qty >= $city_cart_minmum_amount && $cart_total_or_qty <= $city_cart_maximum_amount) {

					$flag = false;

				}

				if ('city_payment_method' != $city_payment_or_shipping && !$flag) {
					foreach ($shipping_methods as $key => $method) {
						if (in_array($method->get_method_id(), $cthspm_shipping_methods)) {
							unset($shipping_methods[$key]);
						}
					}

				}


			}

			return $shipping_methods;
		}



		public function ct_b2b_remove_payment_gateway($gateways)
		{


			if (!WC()->cart || is_admin()) {
				return $gateways;
			}
			$cart = WC()->cart;
			$cart_items = $cart->get_cart();
			$cart_total = $cart->get_cart_contents_total();
			$user_cart_quantity = $cart->get_cart_contents_count();
			$roles = is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';

			$location = WC_Geolocation::geolocate_ip();
			$cor_visitor_current_country = $location['country'];
			$user = wp_get_current_user();
			$user_id = $user->ID;

			$arg = get_posts(
				array(
					'numberposts' => -1,
					'post_type' => 'city_hide',
					'post_status' => 'publish',
					'fields' => 'ids',
				)
			);
			foreach ($arg as $rule_id) {
				$ct_hsp_user_role = (array) get_post_meta($rule_id, 'ct_hsp_user_role', true);
				$af_hspm_review_products = (array) get_post_meta($rule_id, 'af_hspm_review_products', true);
				$af_hspm_category_review = (array) get_post_meta($rule_id, 'af_hspm_category_review', true);
				$af_hspm_user_review = (array) get_post_meta($rule_id, 'af_hspm_user_review', true);
				$cthspm_country_review = (array) get_post_meta($rule_id, 'cthspm_country_review', true);

				$city_payment_or_shipping = get_post_meta($rule_id, 'city_payment_or_shipping', true);
				$cthspm_payment_method_review = (array) get_post_meta($rule_id, 'cthspm_payment_method_review', true);


				$cthspm_shipping_methods = (array) get_post_meta($rule_id, 'cthspm_shipping_methods', true);
				$city_restriction_cart_total = get_post_meta($rule_id, 'city_restriction_cart_total', true);



				$flag = true;


				if (!empty($ct_hsp_user_role) && !in_array($roles, $ct_hsp_user_role, )) {
					continue;
				}
				if (!empty($af_hspm_user_review) && !in_array($user_id, $af_hspm_user_review)) {
					continue;
				}
				if (!empty($cthspm_country_review) && !in_array($cor_visitor_current_country, $cthspm_country_review)) {
					continue;
				}

				foreach ($cart_items as $cart_item_key => $cart_item) {

					$product_id = $cart_item['product_id'];
					if ((!empty($af_hspm_review_products) && in_array($product_id, $af_hspm_review_products)) || (!empty($af_hspm_category_review) && has_term($af_hspm_category_review, 'product_cat', $product_id))) {
						$flag = false;
					}
				}

				if ((empty($af_hspm_review_products)) || (empty($af_hspm_category_review))) {

					$flag = false;
				}

				if ($flag) {
					continue;
				}
				if ('city_cart_quantity' == $city_restriction_cart_total) {


					$city_cart_minmum_amount = !empty(get_post_meta($rule_id, 'city_cart_minmum_amount', true)) ? (float) get_post_meta($rule_id, 'city_cart_minmum_amount', true) : $user_cart_quantity;
					$city_cart_maximum_amount = !empty(get_post_meta($rule_id, 'city_cart_maximum_amount', true)) ? (float) get_post_meta($rule_id, 'city_cart_maximum_amount', true) : $user_cart_quantity;
					$cart_total_or_qty = $user_cart_quantity;

				} else {
					$city_cart_minmum_amount = !empty(get_post_meta($rule_id, 'city_cart_minmum_amount', true)) ? (float) get_post_meta($rule_id, 'city_cart_minmum_amount', true) : $cart_total;
					$city_cart_maximum_amount = !empty(get_post_meta($rule_id, 'city_cart_maximum_amount', true)) ? (float) get_post_meta($rule_id, 'city_cart_maximum_amount', true) : $cart_total;
					$cart_total_or_qty = $cart_total;
				}

				if ($cart_total_or_qty >= $city_cart_minmum_amount && $cart_total_or_qty <= $city_cart_maximum_amount) {

					$flag = false;

				}

				if ('city_shipping' != $city_payment_or_shipping && !$flag) {
					foreach ($cthspm_payment_method_review as $payment_method) {
						if (isset($gateways[$payment_method])) {
							unset($gateways[$payment_method]);
						}
					}
				}


			}

			return $gateways;
		}
	}

	new CT_H_P_S_Front();
}
