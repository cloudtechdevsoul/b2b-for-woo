<?php

defined('ABSPATH') || exit;

/**
 *  Updating Options Values
 */
class Product_Options_Update_Value {

	
	public static function ck_update_rule_values( $post_data, $rule_id ) {

		$user_roles = isset($post_data['user_roles']) ? sanitize_meta( '', $post_data['user_roles'], '') : array();
		update_post_meta($rule_id, 'user_roles', $user_roles); 

		$all_prod_select = isset($post_data['all_prod_select']) ? sanitize_text_field($post_data['all_prod_select']) : 'no';
		update_post_meta($rule_id, 'all_prod_select', $all_prod_select); 

		$prd_opt_prd_search = isset($post_data['prd_opt_prd_search']) ? sanitize_meta( '', $post_data['prd_opt_prd_search'], '') : array();
		update_post_meta($rule_id, 'prd_opt_prd_search', $prd_opt_prd_search); 

		$prd_opt_cat_search = isset($post_data['prd_opt_cat_search']) ? sanitize_meta( '', $post_data['prd_opt_cat_search'], '') : array();
		update_post_meta($rule_id, 'prd_opt_cat_search', $prd_opt_cat_search); 

		$prd_opt_tag_search = isset($post_data['prd_opt_tag_search']) ? sanitize_meta( '', $post_data['prd_opt_tag_search'], '') : array();
		update_post_meta($rule_id, 'prd_opt_tag_search', $prd_opt_tag_search);
	}

	public static function ck_update_field_values( $post_data, $field_id ) {
		
		$ck_field_priority = isset( $post_data['ck_field_priority'][$field_id] ) ? sanitize_text_field($post_data['ck_field_priority'][$field_id]) : '';
		update_post_meta($field_id, 'ck_field_priority', $ck_field_priority); 

		wp_update_post(
			array(
				'post_type' 	=> 'Product_add_field',
				'numberposts' 	=> -1,
				'post_status'	=> 'publish',
				'ID'			=> $field_id,
				'menu_order' 	=> $ck_field_priority, 
			)
		);

		$ck_field_dependable_selector = isset($post_data['ck_field_dependable_selector'][$field_id]) ? sanitize_text_field($post_data['ck_field_dependable_selector'][$field_id]) : '';
		update_post_meta($field_id, 'ck_field_dependable_selector', $ck_field_dependable_selector);

		$ck_fields_selector = isset($post_data['ck_fields_selector'][$field_id]) ? sanitize_text_field($post_data['ck_fields_selector'][$field_id]) : '';
		update_post_meta($field_id, 'ck_fields_selector', $ck_fields_selector);

		$ck_options_selector = isset($post_data['ck_options_selector'][$field_id]) ? sanitize_meta( '', $post_data['ck_options_selector'][$field_id], '' ) : array();
		update_post_meta($field_id, 'ck_options_selector', $ck_options_selector);

		$ck_fields_type = isset($post_data['ck_fields_type'][$field_id]) ? sanitize_text_field($post_data['ck_fields_type'][$field_id]) : '';
		update_post_meta($field_id, 'ck_fields_type', $ck_fields_type);

		$ck_field_title = isset($post_data['ck_field_title'][$field_id]) ? sanitize_text_field($post_data['ck_field_title'][$field_id]) : '';
		update_post_meta($field_id, 'ck_field_title', $ck_field_title); 

		wp_update_post(
			array(
				'post_type' 	=> 'Product_add_field',
				'numberposts' 	=> -1,
				'post_status'	=> 'publish',
				'ID'			=> $field_id,
				'post_title' 	=> $ck_field_title, 
			)
		);

		$ck_add_desc = isset($post_data['ck_add_desc'][$field_id]) ? sanitize_text_field($post_data['ck_add_desc'][$field_id]) : '';
		update_post_meta($field_id, 'ck_add_desc', $ck_add_desc); 

		$ck_field_decs = isset($post_data['ck_field_decs'][$field_id]) ? sanitize_text_field($post_data['ck_field_decs'][$field_id]) : '';
		update_post_meta($field_id, 'ck_field_decs', $ck_field_decs); 

		$ck_add_tool_tip = isset($post_data['ck_add_tool_tip'][$field_id]) ? sanitize_text_field($post_data['ck_add_tool_tip'][$field_id]) : '';
		update_post_meta($field_id, 'ck_add_tool_tip', $ck_add_tool_tip); 

		$ck_field_tool_tip = isset($post_data['ck_field_tool_tip'][$field_id]) ? sanitize_text_field($post_data['ck_field_tool_tip'][$field_id]) : '';
		update_post_meta($field_id, 'ck_field_tool_tip', $ck_field_tool_tip); 

		$ck_req_field = isset($post_data['ck_req_field'][$field_id]) ? sanitize_text_field($post_data['ck_req_field'][$field_id]) : '';
		update_post_meta($field_id, 'ck_req_field', $ck_req_field); 

		$ck_field_price_checkbox = isset($post_data['ck_field_price_checkbox'][$field_id]) ? sanitize_text_field($post_data['ck_field_price_checkbox'][$field_id]) : '';
		update_post_meta($field_id, 'ck_field_price_checkbox', $ck_field_price_checkbox); 

		$ck_field_pricing_type = isset($post_data['ck_field_pricing_type'][$field_id]) ? sanitize_text_field($post_data['ck_field_pricing_type'][$field_id]) : '';
		update_post_meta($field_id, 'ck_field_pricing_type', $ck_field_pricing_type); 

		$ck_field_price = isset($post_data['ck_field_price'][$field_id]) ? sanitize_text_field($post_data['ck_field_price'][$field_id]) : '';
		update_post_meta($field_id, 'ck_field_price', $ck_field_price); 

		$ck_field_limit_range = isset($post_data['ck_field_limit_range'][$field_id]) ? sanitize_text_field($post_data['ck_field_limit_range'][$field_id]) : '';
		update_post_meta($field_id, 'ck_field_limit_range', $ck_field_limit_range); 

		$ck_field_min_limit = isset($post_data['ck_field_min_limit'][$field_id]) ? sanitize_text_field($post_data['ck_field_min_limit'][$field_id]) : '';
		update_post_meta($field_id, 'ck_field_min_limit', $ck_field_min_limit); 

		$ck_field_max_limit = isset($post_data['ck_field_max_limit'][$field_id]) ? sanitize_text_field($post_data['ck_field_max_limit'][$field_id]) : '';
		update_post_meta($field_id, 'ck_field_max_limit', $ck_field_max_limit);

		$ck_field_max_limit = isset($post_data['ck_field_max_limit'][$field_id]) ? sanitize_text_field($post_data['ck_field_max_limit'][$field_id]) : '';
		update_post_meta($field_id, 'ck_field_max_limit', $ck_field_max_limit); 
	}

	public static function ck_update_option_values( $post_data, $field_id, $option_id ) {
		
		$ck_options_image = isset($post_data['ck_options_image'][$field_id][$option_id]) ? sanitize_text_field($post_data['ck_options_image'][$field_id][$option_id]) : '';
		update_post_meta($option_id, 'ck_options_image', $ck_options_image);

		$ck_option_color = isset($post_data['ck_option_color'][$field_id][$option_id]) ? sanitize_text_field($post_data['ck_option_color'][$field_id][$option_id]) : '';
		update_post_meta($option_id, 'ck_option_color', $ck_option_color);

		$ck_option_name = isset($post_data['ck_option_name'][$field_id][$option_id]) ? sanitize_text_field($post_data['ck_option_name'][$field_id][$option_id]) : '';
		update_post_meta($option_id, 'ck_option_name', $ck_option_name);

		wp_update_post(
			array(
				'post_type' 	=> 'product_add_option',
				'numberposts' 	=> -1,
				'post_status'	=> 'publish',
				'ID'			=> $option_id,
				'post_title' 	=> $ck_option_name, 
			)
		);

		$ck_option_price_type = isset($post_data['ck_option_price_type'][$field_id][$option_id]) ? sanitize_text_field($post_data['ck_option_price_type'][$field_id][$option_id]) : '';
		update_post_meta($option_id, 'ck_option_price_type', $ck_option_price_type);

		$ck_option_price = isset($post_data['ck_option_price'][$field_id][$option_id]) ? sanitize_text_field($post_data['ck_option_price'][$field_id][$option_id]) : '';
		update_post_meta($option_id, 'ck_option_price', $ck_option_price);

		$ck_option_priority = isset($post_data['ck_option_priority'][$field_id][$option_id]) ? sanitize_text_field($post_data['ck_option_priority'][$field_id][$option_id]) : '';
		update_post_meta($option_id, 'ck_option_priority', $ck_option_priority);

		wp_update_post(
			array(
				'post_type' 	=> 'product_add_option',
				'numberposts' 	=> -1,
				'post_status'	=> 'publish',
				'ID'			=> $option_id,
				'menu_order' 	=> $ck_option_priority, 
			)
		);
	}
}

new Product_Options_Update_Value();
