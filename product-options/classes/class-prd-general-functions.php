<?php

defined('ABSPATH') || exit();

/**
 * General Options.
 */
class Prd_General_Functions {

	public static function get_posts_ids_of_current_post ( $post_type, $post_parent = '' ) {

		$fields_args = array(
			'post_type'   => $post_type,
			'numberposts' => -1,
			'post_status' => 'publish',
			'post_parent' => $post_parent,
			'fields'      => 'ids',
			'orderby'     => 'menu_order',
			'order'       => 'ASC',
		);

		$field_ids = get_posts( $fields_args );
		return $field_ids;
	}


	public static function get_product_options( $product_id ) {

		$user_roles = is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';

		if ('both' == get_option('ck_prod_optn_run_options')) {

			$rule_arg_array = self::get_posts_ids_of_current_post('product_options');

			foreach ($rule_arg_array as $rule_id) {

				$user_roles_arr = (array) get_post_meta($rule_id, 'user_roles', true);


				if (!empty($user_roles) && !in_array((string) $user_roles, (array) $user_roles, true)) {

					continue;
				}

				if (self::rule_product_check($product_id, $rule_id)) {

					Front_Fields_Show::prod_optn_rule_front_fields( $rule_id );
					Front_Fields_Styling::ck_front_fields_styling( $rule_id );
				}
			}

			Front_Fields_Show::prod_optn_rule_front_fields( $product_id );

		} elseif ('product' == get_option('ck_prod_optn_run_options')) {

			Front_Fields_Show::prod_optn_rule_front_fields( $product_id );
			Front_Fields_Styling::ck_front_fields_styling( $rule_id );

		} else {

			$rule_arg_array = self::get_posts_ids_of_current_post('product_options');

			foreach ($rule_arg_array as $rule_id) {

				$user_roles_arr = (array) get_post_meta($rule_id, 'user_roles', true);


				if (!empty($user_roles) && !in_array((string) $user_roles, (array) $user_roles, true)) {

					continue;
				}

				if (self::rule_product_check($product_id, $rule_id)) {

					Front_Fields_Show::prod_optn_rule_front_fields( $rule_id );
					Front_Fields_Styling::ck_front_fields_styling( $rule_id );
				}
			}
		}
	}

	public static function rule_product_check( $prod_id, $rule_id) {

		$rule_selected_products = (array) get_post_meta($rule_id, 'prd_opt_prd_search', true);
		$rule_selected_category = (array) get_post_meta($rule_id, 'prd_opt_cat_search', true);
		$rule_selected_tags     = (array) get_post_meta($rule_id, 'prd_opt_tag_search', true);

		if (empty($rule_selected_products) && empty($rule_selected_category) && empty($rule_selected_tags)) {
			return true;
		}
		if (!empty($rule_selected_products)) {

			if (in_array((string) $prod_id, $rule_selected_products)) {
				return true;
			}
		}
		if (!empty($rule_selected_category)) {

			if (has_term($rule_selected_category, 'product_cat', $prod_id)) {
				return true;
			}
		}
		if (!empty($rule_selected_tags)) {

			if (has_term($rule_selected_tags, 'product_tag', $prod_id)) {
				return true;
			}
		}
		return false;
	}

}

new Prd_General_Functions();
