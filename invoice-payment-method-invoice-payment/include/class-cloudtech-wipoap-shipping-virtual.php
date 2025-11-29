<?php
/**
 *
 * Manage all settings and actions of invoice-payment.
 *
 * @package  addify-invoice-gateway/settings
 * @version  1.1.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


if (!class_exists('Cloudtech_Wipoap_Shipping_Virtual')) {
	/**
	 * Class Payment Availability to apply the restriction on ig.
	 */
	class Cloudtech_Wipoap_Shipping_Virtual
	{

		/**
		 * Load all rules for invoice-gateway
		 *
		 * @var array
		 */
		private $all_payment_rules;
		/**
		 * Object of cart item check class
		 *
		 * @var array
		 */
		private $ob_cart_items_check;
		/**
		 * Object of location check class
		 *
		 * @var array
		 */
		private $ob_location_check;

		/**
		 * Constructor of class Payment Availability.
		 */
		public function __construct()
		{
			$this->load_payment_rules();
			$this->ob_cart_items_check = new Cloudtech_Wipoap_Cart_Items_Check();
			$this->ob_location_check = new Cloudtech_Wipoap_Location_Check();
		}
		/**
		 * Get payment invoice invoice-gateway rule id.
		 *
		 * @param array() $form_data Array of checkout form data.
		 *
		 * @return bool Return true or false for Cash of Delivery availability.
		 */
		public function get_py_ig_all_rules($form_data)
		{

			if (empty($this->all_payment_rules)) {
				return true;
			}

			foreach ($this->all_payment_rules as $rule_id) {
				// Check user requirements.
				if (!$this->check_user_restrictions($rule_id)) {
					continue;
				}

				if (!$this->check_cart_restrictions($rule_id)) {
					continue;
				}

				if (!$this->check_location_restrictions($rule_id, $form_data)) {
					continue;
				}

				if (!$this->check_shipping_restrictions($rule_id, $form_data)) {
					continue;
				}

				return $rule_id;
			}

			return null;
		}
		/**
		 * Restrict payment invoice invoice-gateway for Location.
		 *
		 * @param int $rule_id   Id of rule for invoice-gateway.
		 * @param int $form_data Checkout form data.
		 *
		 * @return bool Return true or false for Cash of Delivery invoice-gateway availability.
		 */
		public function check_shipping_restrictions($rule_id, $form_data)
		{
			$data = (get_post_meta($rule_id, 'cloudtech_wipo_shipping', true));
			if (!empty($data)) {
				if (!$this->ob_location_check->match_shipping($data)) {
					return false;
				} else {
					return true;
				}
			}
			return true;
		}


		/**
		 * Restrict payment invoice invoice-gateway for Location.
		 *
		 * @param int $rule_id   Id of rule for invoice-gateway.
		 * @param int $form_data Checkout form data.
		 *
		 * @return bool Return true or false for Cash of Delivery invoice-gateway availability.
		 */
		public function check_location_restrictions($rule_id, $form_data)
		{

			// Set form data to class variable.
			$this->ob_location_check->checkout_form_data = $form_data;
			$values = (get_post_meta($rule_id, 'cloudtech_wipo_countries', true));
			$sel_countries = is_array($values) ? $values : array();

			if (!$this->ob_location_check->match_country($sel_countries)) {
				return false;
			}

			// States.
			$values = (get_post_meta($rule_id, 'cloudtech_wipo_states', true));
			$sel_states = is_array($values) ? $values : array();

			if (!$this->ob_location_check->match_state($sel_states)) {
				return false;
			}

			// Cities.
			$values = get_post_meta($rule_id, 'cloudtech_wipo_cities', true);
			$sel_cities = isset($values) ? $this->ob_location_check->addify_purify_explode_data($values) : array();

			if (!$this->ob_location_check->match_city($sel_cities)) {
				return false;
			}

			// Zip Codes.
			$values = get_post_meta($rule_id, 'cloudtech_wipo_zip_codes', true);
			$sel_zips = isset($values) ? $this->ob_location_check->addify_purify_explode_data($values) : '';

			if (!$this->ob_location_check->af_match_zip_code($sel_zips)) {
				return false;
			}

			return true;
		}


		/**
		 * Restrict payment invoice-gateway for users.
		 *
		 * @param int $rule_id Id of rule for invoice-gateway.
		 *
		 * @return bool Return true or false for Cash of Delivery invoice-gateway availability.
		 */
		public function check_cart_restrictions($rule_id)
		{

			$flag = false;
			// Cart Total amount Check.
			$cart_amount = (get_post_meta($rule_id, 'cloudtech_wipo_cart_amount', true));
			$cart_quantity = (get_post_meta($rule_id, 'cloudtech_wipo_cart_quantity', true));
			$cart_quantity = is_array($cart_quantity) ? $cart_quantity : array();
			$cart_amount = is_array($cart_amount) ? $cart_amount : array();
			$cart_amount[0] = isset($cart_amount[0]) ? floatval($cart_amount[0]) : 0.0;
			$cart_amount[1] = isset($cart_amount[1]) ? floatval($cart_amount[1]) : 0.0;
			$cart_quantity[0] = isset($cart_quantity[0]) ? floatval($cart_quantity[0]) : 0;
			$cart_quantity[1] = isset($cart_quantity[1]) ? floatval($cart_quantity[1]) : 0;
			if (!WC()->cart) {
				return false;
			} else {
				$cart_total = WC()->cart->get_subtotal();
				$total_quantity = WC()->cart->get_cart_contents_count();
			}

			if (!$this->ob_cart_items_check->addify_match_cart_amount($cart_total, $cart_amount[0], $cart_amount[1])) {
				return false;
			}
			if (!$this->ob_cart_items_check->addify_match_cart_quantity($total_quantity, $cart_quantity[0], $cart_quantity[1])) {
				return false;
			}

			// Check Product in Cart.
			$sel_products = (get_post_meta($rule_id, 'cloudtech_wipo_cart_products', true));
			$sel_tags = (get_post_meta($rule_id, 'cloudtech_wipo_cart_products_tag', true));
			$sel_cat = (get_post_meta($rule_id, 'cloudtech_wipo_cart_products_cat', true));
			$sel_products = is_array($sel_products) ? $sel_products : array();
			$check_type = isset($sel_products[0]) ? $sel_products[0] : 'all';
			$sel_products = isset($sel_products[1]) ? $sel_products[1] : array();
			$af_enab_virt = get_post_meta($rule_id, 'cloudtech_wipo_enable_tax', true);
			$cloudtech_wipo_enable_products = get_post_meta($rule_id, 'cloudtech_wipo_enable_products', true);
			// Cart item check for virtual.
			if ('yes' === (string) $af_enab_virt) {
				global $woocommerce;
				$items = $woocommerce->cart->get_cart();
				foreach ($items as $item => $values) {
					$_product = wc_get_product($values['data']->get_id());
					if ($_product->is_virtual('yes')) {
						$flag = true;
					}
				}
			}
			if ('yes' === (string) $cloudtech_wipo_enable_products) {

				$flag = true;

			} elseif (!empty($sel_products) || !empty($sel_cat) || !empty($sel_tags)) {

				if ($this->ob_cart_items_check->addify_match_product_cateories_in_cart($sel_products, $sel_cat, $check_type, $sel_tags)) {
					$flag = true;
				}
			}

			return $flag;
		}

		/**
		 * Restrict payment invoice invoice-gateway for users.
		 *
		 * @param int $rule_id Id of rule for invoice-gateway.
		 *
		 * @return bool Return true or false for Cash of Delivery invoice-gateway availability.
		 */
		public function check_user_restrictions($rule_id)
		{

			$sel_customers = (get_post_meta($rule_id, 'cloudtech_wipo_customer_select', true));
			$sel_roles = (get_post_meta($rule_id, 'cloudtech_wipo_customer_roles', true));

			// Variable Values check.
			$sel_customers = is_array($sel_customers) ? $sel_customers : array();
			$sel_roles = is_array($sel_roles) ? $sel_roles : array();

			// Return true if user selection is empty.
			if (empty($sel_customers) && empty($sel_roles)) {
				return true;
			}

			if (!is_user_logged_in()) {

				if (in_array('guest', $sel_roles, true)) {

					return true;
				} else {
					return false;
				}
			} else {

				$user = wp_get_current_user();
				$roles = $user->roles;

				if (in_array((string) $user->ID, $sel_customers, true)) {

					return true;
				} elseif (in_array((string) $roles[0], $sel_roles, true)) {

					return true;
				} else {
					return false;
				}
			}
		}

		/**
		 * Restrict payment invoice Method.
		 *
		 * @return void
		 */
		public function load_payment_rules()
		{

			$args = array(
				'post_type' => 'payment_gateway',
				'post_status' => 'publish',
				'fields' => 'ids',
			);
			$the_query = new WP_Query($args);
			$this->all_payment_rules = $the_query->get_posts();
		}
	}
}
