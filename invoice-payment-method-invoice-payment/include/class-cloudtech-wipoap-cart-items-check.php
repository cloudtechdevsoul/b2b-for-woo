<?php
/**
 * Deal the items of cart to check products in cart, categories in cart and shipping class of cart items
 *
 * Manage all actions of cart
 *
 * @package  addify-invoice-gateway/settings
 * @version  1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( ! class_exists( 'Cloudtech_Wipoap_Cart_Items_Check' ) ) {
	/**
	 * Class cart items to apply the restriction on pyament-invoice.
	 */
	class Cloudtech_Wipoap_Cart_Items_Check {


		/**
		 * Constructor of class Cart_Items_Check
		 */
		public function __construct() {
		}

		/**
		 * Find Products of specific Categories in Cart.
		 *
		 * @param float $cart_total  Array of Products Categories to find in cart.
		 * @param float $lower_limit Search type all or any.
		 * @param float $upper_limit Search type all or any.
		 *
		 * @return bool    Return true or false for amount of cart.
		 */
		public function addify_match_cart_amount( $cart_total, $lower_limit = 0.0, $upper_limit = 0.0 ) {

			$flag = false;

			if ( 0.0 === floatval( $lower_limit ) && 0.0 === floatval( $upper_limit ) ) {

				$flag = true;

			} elseif ( 0.0 === floatval( $lower_limit ) && $cart_total <= floatval( $upper_limit ) ) {

					$flag = true;

			} elseif ( 0.0 === floatval( $upper_limit ) && $cart_total >= $lower_limit ) {

				$flag = true;
			} elseif ( $lower_limit <= $cart_total && $cart_total <= $upper_limit ) {

				$flag = true;
			}
			return $flag;
		}

		/**
		 * Find Products of specific Categories in Cart.
		 *
		 * @param float $cart_total  Array of Products Categories to find in cart.
		 * @param float $lower_limit Search type all or any.
		 * @param float $upper_limit Search type all or any.
		 *
		 * @return bool    Return true or false for amount of cart.
		 */
		public function addify_match_cart_quantity( $cart_total, $lower_limit = 0.0, $upper_limit = 0.0 ) {

			$flag = false;

			if ( 0.0 === floatval( $lower_limit ) && 0.0 === floatval( $upper_limit ) ) {

				$flag = true;

			} elseif ( 0.0 === floatval( $lower_limit ) && $cart_total <= floatval( $upper_limit ) ) {

					$flag = true;

			} elseif ( 0.0 === floatval( $upper_limit ) && $cart_total >= $lower_limit ) {

				$flag = true;
			} elseif ( $lower_limit <= $cart_total && $cart_total <= $upper_limit ) {

				$flag = true;
			}
			return $flag;
		}

		/**
		 * Find Products of specific Categories in Cart.
		 *
		 * @param array  $sel_products   Array of Products Categories to find in cart.
		 * @param array  $sel_categories Search type all or any.
		 * @param string $checktype      Search type all or any.
		 *
		 * @return bool    Return true or false for product in cart
		 */
		public function addify_match_product_cateories_in_cart( $sel_products = array(), $sel_categories = array(), $checktype = 'only', $sel_tags = '' ) {
			if ( empty( $sel_products ) && empty( $sel_categories ) && empty( $sel_tags ) ) {
				return true;
			}
			$flag = false;
			switch ( $checktype ) {

				case 'only':
					$product_ids = array();

					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$product_ids[] = $cart_item['product_id'];
					}
					foreach ( $product_ids as $product_id ) {
						if ( ! empty( $sel_products ) && in_array( (string) $product_id, $sel_products, true ) ) {
							$flag = true;

						}

						if ( ! $flag && ! empty( $sel_categories ) ) {
							foreach ( $sel_categories as $category ) {
								if ( has_term( $category, 'product_cat', $product_id ) ) {
									$flag = true;
								}
							}
						}
						if ( ! $flag && ! empty( $sel_tags ) ) {
							foreach ( $sel_tags as $tag ) {
								if ( has_term( $tag, 'product_tag', $product_id ) ) {
									$flag = true;
								}
							}
						}

						if ( ! $flag ) {

							return $flag;
						}
					}
					return $flag;

				case 'disallow':
					$product_ids = array();
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$product_ids[] = $cart_item['product_id'];
					}
					$new_product_array = array();

					foreach ( $product_ids as $product_id ) {

						if ( ! empty( $sel_products ) && in_array( (string) $product_id, $sel_products, true ) ) {
							$new_product_array[ $product_id ] = $product_id;
						}

						if ( ! $flag && ! empty( $sel_categories ) ) {
							foreach ( $sel_categories as $category ) {
								if ( has_term( $category, 'product_cat', $product_id ) ) {
									$new_product_array[ $product_id ] = $product_id;

								}
							}
						}
						if ( ! $flag && ! empty( $sel_tags ) ) {
							foreach ( $sel_tags as $tag ) {
								if ( has_term( $tag, 'product_tag', $product_id ) ) {
									// $flag = true;
									$new_product_array[ $product_id ] = $product_id;

								}
							}
						}
					}

					$new_product_array = array_filter( $new_product_array );
					$product_ids       = array_filter( $product_ids );
					if ( count( $new_product_array ) >= count( $product_ids ) ) {

						$flag = true;
					}
					return $flag;

				case 'any':
					$product_ids = array();

					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$product_ids[] = $cart_item['product_id'];
					}
					foreach ( $product_ids as $product_id ) {
						if ( ! empty( $sel_products ) && in_array( (string) $product_id, $sel_products, true ) ) {
							$flag = true;
						}

						if ( ! $flag && ! empty( $sel_categories ) ) {

							foreach ( $sel_categories as $category ) {

								if ( has_term( $category, 'product_cat', $product_id ) ) {
									$flag = true;
								}
							}
						}
						if ( ! $flag && ! empty( $sel_tags ) ) {
							foreach ( $sel_tags as $tag ) {
								if ( has_term( $tag, 'product_tag', $product_id ) ) {
									$flag = true;
								}
							}
						}

						if ( $flag ) {

							return $flag;

						}
					}
					return $flag;

			}
		}
	}
}
