<?php
/**
 * Check the location of shipping and meets the restrictions
 *
 * @package  addify-invoice-gateway/settings
 * @version  1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( ! class_exists( 'Cloudtech_Wipoap_Location_Check' ) ) {
	/**
	 * Class Addify location check to meet the restriction of location
	 */
	class Cloudtech_Wipoap_Location_Check {


		/**
		 * Checkout form data.
		 *
		 * @var array
		 */
		public $checkout_form_data;

		/**
		 * Constructor of class payment Availability on location.
		 */
		public function __construct() {
		}

		/**
		 * Match the shipping Zone
		 *
		 * @param array() $shipping_zones Array of shipping zones ids.
		 * @param array() $enable         Match on basis of enable or disable.
		 *
		 * @return bool Return true or false for payment-invoice availability.
		 */
		public function match_shipping_zone( $shipping_zones, $enable = 'enable' ) {

			// Get Current Shipping Zone.
			$_country  = isset( $this->checkout_form_data['s_country'] ) ? $this->checkout_form_data['s_country'] : '';
			$_state    = isset( $this->checkout_form_data['s_state'] ) ? $this->checkout_form_data['s_state'] : '';
			$_zip_code = isset( $this->checkout_form_data['s_postcode'] ) ? $this->checkout_form_data['s_postcode'] : '';

			$cache_key = WC_Cache_Helper::get_cache_prefix( 'shipping_zones' ) . 'wc_shipping_zone_' . md5( sprintf( '%s+%s+%s', $_country, $_state, $_zip_code ) );
			$zone_id   = wp_cache_get( $cache_key, 'shipping_zones' );

			if ( empty( $zone_id ) ) {
				$shipping_packages = WC()->cart->get_shipping_packages();
				$shipping_zone     = wc_get_shipping_zone( $shipping_packages[0] );
				$zone_id           = $shipping_zone->get_id();
			}

			if ( ! empty( $shipping_zones ) ) {
				// Check enable and disable for Zones.

				switch ( $enable ) {
					// Check for Enable.
					case 'enable':
						if ( empty( $zone_id ) || ! in_array( (string) $zone_id, $shipping_zones, true ) ) {
							return false;
						}
						break;
					case 'disable':
						if ( in_array( (string) $zone_id, $shipping_zones, true ) ) {
							return false;
						}
						break;
				}
			}
			return true;
		}

		/**
		 * Match the Country
		 *
		 * @param array() $countries Array of countries id.
		 * @param array() $enable    Match on basis of enable or disable.
		 *
		 * @return bool Return true or false for payment-invoice availability.
		 */
		public function match_country( $countries, $enable = 'enable' ) {

			$_country = isset( $this->checkout_form_data['s_country'] ) ? $this->checkout_form_data['s_country'] : '';
			if ( ! empty( $_country ) ) {
				// Check enable and disable for countries.
				switch ( $enable ) {
					// Check for Enable.
					case 'enable':
						if ( ! empty( $countries ) && ! in_array( $_country, $countries, true ) ) {
							return false;
						}
						break;
					case 'disable':
						if ( ! empty( $countries ) && in_array( $_country, $countries, true ) ) {
							return false;
						}
						break;
				}
			}
			return true;
		}
		/**
		 * Match the Country
		 *
		 * @param array() $countries Array of countries id.
		 * @param array() $enable    Match on basis of enable or disable.
		 *
		 * @return bool Return true or false for payment-invoice availability.
		 */
		public function match_shipping( $data ) {
				$order          = null;
				$needs_shipping = false;

				// Test if shipping is needed first
			if ( WC()->cart && WC()->cart->needs_shipping() ) {
				$needs_shipping = true;
			} elseif ( is_page( wc_get_page_id( 'checkout' ) ) && 0 < get_query_var( 'order-pay' ) ) {
				$order_id = absint( get_query_var( 'order-pay' ) );
				$order    = wc_get_order( $order_id );

				// Test if order needs shipping used count insted of sizeof.
				if ( 0 < count( $order->get_items() ) ) {
					foreach ( $order->get_items() as $item ) {
						$_product = $item->get_product();
						if ( $_product && $_product->needs_shipping() ) {
							$needs_shipping = true;
							break;
						}
					}
				}
			}
			if ( ! $needs_shipping ) {
				return false;
			} else {
					$chosen_shipping_methods = array();
				if ( is_object( $order ) ) {
						$chosen_shipping_methods = array_unique( array_map( 'wc_get_string_before_colon', $order->get_shipping_methods() ) );
				} else {
					$chosen_shipping_methods_session = WC()->session->get( 'chosen_shipping_methods' );
							$chosen_shipping_methods = array_unique( array_map( 'wc_get_string_before_colon', $chosen_shipping_methods_session ) );
				}
								$test = $chosen_shipping_methods[0];
				if ( in_array( $test, $data ) ) {
					return true;
				} else {
					return false;
				}
			}
		}

		/**
		 * Match the shipping Zone
		 *
		 * @param array() $states Array of states id.
		 * @param array() $enable Match on basis of enable or disable.
		 *
		 * @return bool Return true or false for payment-invoice availability.
		 */
		public function match_state( $states, $enable = 'enable' ) {
			$_country = isset( $this->checkout_form_data['s_country'] ) ? $this->checkout_form_data['s_country'] : '';
			$_state   = isset( $this->checkout_form_data['s_state'] ) ? $this->checkout_form_data['s_state'] : '';
			$_state   = $_country . ':' . $_state;
			if ( ! empty( $_state ) ) {
				// Check enable and disable for countries.
				switch ( $enable ) {
					// Check for Enable.
					case 'enable':
						if ( ! empty( $states ) && ! in_array( $_state, $states, true ) ) {
							return false;
						}
						break;
					case 'disable':
						if ( ! empty( $states ) && in_array( $_state, $states, true ) ) {
							return false;
						}
						break;
				}
			}
			return true;
		}

		/**
		 * Match the City
		 *
		 * @param array() $cities Array of cities.
		 * @param array() $enable Match on basis of enable or disable.
		 *
		 * @return bool Return true or false for payment-invoice availability.
		 */
		public function match_city( $cities, $enable = 'enable' ) {

			$_city  = isset( $this->checkout_form_data['s_city'] ) ? strtolower( trim( preg_replace( '/[\t\n\r\s]+/', ' ', $this->checkout_form_data['s_city'] ) ) ) : '';
			$cities = isset( $cities[0] ) ? $cities[0] : array();
			if ( ! empty( $_city ) ) {
				// Check enable and disable for countries.
				switch ( $enable ) {
					// Check for Enable.
					case 'enable':
						if ( ! empty( $cities ) && ! in_array( $_city, $cities, true ) ) {
							return false;
						}
						break;
					case 'disable':
						if ( ! empty( $cities ) && in_array( $_city, $cities, true ) ) {
							return false;
						}
						break;
				}
			}
			return true;
		}

		/**
		 * Match the Zip Codes
		 *
		 * @param array() $zip_codes Array of Zip Codes.
		 * @param array() $enable    Match on basis of enable or disable.
		 *
		 * @return bool Return true or false for payment-invoice availability.
		 */
		public function af_match_zip_code( $zip_codes, $enable = 'enable' ) {

			$_zip_code = isset( $this->checkout_form_data['s_postcode'] ) ? strtolower( trim( preg_replace( '/[\t\n\r\s]+/', ' ', $this->checkout_form_data['s_postcode'] ) ) ) : '';

			$nor_zip_codes   = isset( $zip_codes[0] ) ? $zip_codes[0] : array();
			$range_zip_codes = isset( $zip_codes[1] ) ? $zip_codes[1] : array();

			if ( empty( $nor_zip_codes ) && empty( $range_zip_codes ) ) {
				return true;
			}

			if ( ! empty( $_zip_code ) ) {

				// Check enable and disable for countries.
				switch ( $enable ) {
					// Check for Enable.
					case 'enable':
						if ( ! empty( $nor_zip_codes ) && in_array( (string) $_zip_code, (array) $nor_zip_codes, true ) ) {
							return true;
						}

						foreach ( $range_zip_codes as $value ) {

							$_data = explode( '-', $value );

							if ( 2 === count( $_data ) ) {
								if ( intval( $_data[0] ) <= intval( $_zip_code ) && intval( $_zip_code ) <= intval( $_data[1] ) ) {
									return true;
								}
							}
						}
						return false;
					case 'disable':
						if ( ! empty( $nor_zip_codes ) && in_array( (string) $_zip_code, (array) $nor_zip_codes, true ) ) {
							return false;
						}

						foreach ( $range_zip_codes as $value ) {

							$_data = explode( '-', $value );

							if ( 2 === count( $_data ) ) {
								if ( intval( $_data[0] ) <= intval( $_zip_code ) && intval( $_zip_code ) <= intval( $_data[1] ) ) {
									return false;
								}
							}
						}
						return true;
				}
			}
			return true;
		}

		/**
		 * Explode data and remove all empty index and add range of zip codes in array.
		 *
		 * @param string $_text     Text to explode.
		 * @param string $delimiter delimiter to explode.
		 *
		 * @return bool Return true or false for Product in Cart.
		 */
		public function addify_purify_explode_data( $_text = '', $delimiter = ',' ) {
			// Removes white spaces form text.
			$_text          = trim( preg_replace( '/[\t\n\r\s]+/', ' ', $_text ) );
			$explode_data   = explode( $delimiter, $_text );
			$purified_array = array();

			foreach ( $explode_data as $key => $value ) {
				// Remove empty indexes.
				if ( ! empty( $value ) ) {
					$purified_array[] = strtolower( trim( preg_replace( '/[\t\n\r\s]+/', ' ', $value ) ) );

				}
			}

			$range_array = array();
			$nor_array   = array();

			// Check for Range of Zip Codes.
			foreach ( $purified_array as $key => $value ) {

				$_data = explode( '-', $value );

				if ( 2 === count( $_data ) ) {

					$range_array[] = $value;

				} else {

					$nor_array[] = $value;

				}
			}
			$purified_array   = array();
			$purified_array[] = $nor_array;
			$purified_array[] = $range_array;

			return $purified_array;
		}
	}
}
