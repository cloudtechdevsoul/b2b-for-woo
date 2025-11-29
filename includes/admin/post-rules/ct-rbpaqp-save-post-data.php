<?php

$post_types = ['product','ct_role_base_pricing','ct_min_max_qty_role','ct_hide_p_nd_a_t_c_b','ct_hide_prdct_nd_var'];
foreach ($post_types as $current_post_type) {
	add_action('save_post_'.$current_post_type,'ct_rbpaqp_arrange_data');
}

add_action('woocommerce_save_product_variation', 'ct_rbpaqp_arrange_data', 10, 1);

function ct_rbpaqp_arrange_data( $post_id ) {

	$form_data 	= [];

	if ( isset( $_POST ) ) {

		$form_data 	= sanitize_meta( '' ,$_POST , '');

	}
	ct_rbpaqp_save_data( $post_id , $form_data );

}

function ct_rbpaqp_save_data( $post_id , $form_data ) {

	$array_keys 	= 	['ct_rbpaqp_rbp_inlcuded_products','ct_rbpaqp_rbp_inlcuded_category','ct_rbpaqp_rbp_inlcuded_role','ct_rbpaqp_rbp_inlcuded_country','ct_rbpaqp_rbp_inlcuded_tag','ct_rbpaqp_rbp_inlcuded_days','ct_rbpaqp_rbp_recent_order_status','ct_rbpaqp_rbp_recent_order_limit','ct_rbpaqp_mmq_inlcuded_products','ct_rbpaqp_mmq_inlcuded_category','ct_rbpaqp_mmq_inlcuded_tag','ct_rbpaqp_mmq_rules','ct_rbpaqp_hpaatcb_inlcuded_role','ct_rbpaqp_mmq_inlcuded_days','ct_rbpaqp_mmq_order_status_purchas_item_qty','ct_rbpaqp_hpaatcb_inlcuded_products','ct_rbpaqp_hpaatcb_inlcuded_category','ct_rbpaqp_hpaatcb_inlcuded_role','ct_rbpaqp_hpaatcb_inlcuded_country','ct_rbpaqp_hpaatcb_inlcuded_tag','ct_rbpaqp_hpaatcb_inlcuded_days',
	'ct_rbpaqp_hpav_inlcuded_products','ct_rbpaqp_hpav_inlcuded_category','ct_rbpaqp_hpav_inlcuded_role','ct_rbpaqp_hpav_inlcuded_country','ct_rbpaqp_hpav_inlcuded_tag','ct_rbpaqp_hpav_inlcuded_days',
];

$string_keys 	= 	['ct_rbpaqp_rbp_start_date','ct_rbpaqp_rbp_end_date','ct_rbpaqp_rbp_start_time','ct_rbpaqp_rbp_end_time','ct_rbpaqp_rbp_recent_order_limit','ct_rbpaqp_mmq_start_date','ct_rbpaqp_mmq_end_date','ct_rbpaqp_mmq_start_time','ct_rbpaqp_mmq_end_time','ct_rbpaqp_mmq_min_qty','ct_rbpaqp_mmq_max_qty','ct_rbpaqp_mmq_add_purchas_item_qty_or_not','ct_rbpaqp_mmq_step','ct_rbpaqp_hpaatcb_start_date','ct_rbpaqp_hpaatcb_end_date','ct_rbpaqp_hpaatcb_start_time','ct_rbpaqp_hpaatcb_end_time','ct_rbpaqp_hpav_hide_product','ct_rbpaqp_hpaatcb_price_setting','ct_rbpaqp_hpaatcb_price_text','ct_rbpaqp_hpaatcb_add_to_cart_btn','ct_rbpaqp_hpaatcb_button_text','ct_rbpaqp_hpaatcb_custom_link','ct_rbpaqp_rbp_recent_minimum_spent','ct_rbpaqp_hpaatcb_restriction_product_level','ct_rbpaqp_mmq_restriction_product_level','role_base_price_product_level','ct_rbpaqp_hpav_hide_product_base_on','ct_rbpaqp_hpav_hide_product_on_min_quantity','ct_rbpaqp_hpaatcb_make_your_on_button','ct_rbpaqp_hpav_hide_prdct_nd_var','ct_rbpaqp_hpav_start_date','ct_rbpaqp_hpav_end_date','ct_rbpaqp_hpav_start_time','ct_rbpaqp_hpav_end_time',];


foreach ($array_keys as $meta_key) {

	$value  	= [];

	if ( isset( $form_data[$post_id.$meta_key] ) ) {

		$value = (array) $form_data[$post_id.$meta_key];

	}

	update_post_meta($post_id,$meta_key,$value);
}


foreach ($string_keys as $meta_key) {

	$value  	= '';

	if ( isset( $form_data[$post_id.$meta_key] ) ) {
		
		$value 	= $form_data[$post_id.$meta_key];
	}

	update_post_meta($post_id,$meta_key,$value);

}



$role_based_price 		= get_posts([
	'post_type' 		=> 'ct_set_role_for_rbp',
	'post_status' 		=> 'publish',
	'post_parent' 		=> $post_id,
	'fields' 			=> 'ids',
	'posts_per_page' 	=> -1,
]);


$customer_base_price 	= get_posts([
	'post_type' 		=> 'ct_set_role_for_cbp',
	'post_status' 		=> 'publish',
	'post_parent' 		=> $post_id,
	'fields' 			=> 'ids',
	'posts_per_page' 	=> -1,
]);

$role_base_and_customer_base_array 	= array_merge($customer_base_price,$role_based_price); 


foreach ( $role_base_and_customer_base_array as $current_post_id ) {
	
	$selected_customers 		= [];
	if ( isset( $form_data[$current_post_id.'ct_role_base_pricing_selected_customer'] ) ) {
		$selected_customers 	= (array) $form_data[$current_post_id.'ct_role_base_pricing_selected_customer'];
	}

	update_post_meta( $current_post_id ,'ct_role_base_pricing_selected_customer', $selected_customers );

	$user_role 					= [];
	if ( isset( $form_data[$current_post_id.'ct_role_base_pricing_selected_user_role'] ) ) {
		$user_role 				= (array) $form_data[$current_post_id.'ct_role_base_pricing_selected_user_role'];
	}

	update_post_meta( $current_post_id ,'ct_role_base_pricing_selected_user_role', $user_role );


	$discount_on 				= [];
	if ( isset( $form_data[$current_post_id.'ct_role_base_pricing_discount_on'] ) ) {
		$discount_on 			=  $form_data[$current_post_id.'ct_role_base_pricing_discount_on'];
	}

	update_post_meta( $current_post_id ,'ct_role_base_pricing_discount_on', $discount_on );

	$discount_type 				= [];
	if ( isset( $form_data[$current_post_id.'ct_role_base_pricing_discount_type'] ) ) {
		$discount_type 			=  $form_data[$current_post_id.'ct_role_base_pricing_discount_type'];
	}

	update_post_meta( $current_post_id ,'ct_role_base_pricing_discount_type', $discount_type );

	$discount_value 			= [];
	if ( isset( $form_data[$current_post_id.'ct_role_base_pricing_discount_value'] ) ) {
		$discount_value 		=  $form_data[$current_post_id.'ct_role_base_pricing_discount_value'];
	}

	update_post_meta( $current_post_id ,'ct_role_base_pricing_discount_value', $discount_value );

	$discount_min 				= [];
	if ( isset( $form_data[$current_post_id.'ct_role_base_pricing_discount_min'] ) ) {
		$discount_min 			=  $form_data[$current_post_id.'ct_role_base_pricing_discount_min'];
	}

	update_post_meta( $current_post_id ,'ct_role_base_pricing_discount_min', $discount_min );



	$discount_max 				= [];
	if ( isset( $form_data[$current_post_id.'ct_role_base_pricing_discount_max'] ) ) {
		$discount_max 			=  $form_data[$current_post_id.'ct_role_base_pricing_discount_max'];
	}

	update_post_meta( $current_post_id ,'ct_role_base_pricing_discount_max', $discount_max );


	$discount_start_date 		= [];
	if ( isset( $form_data[$current_post_id.'ct_role_base_pricing_discount_start_date'] ) ) {
		$discount_start_date 	=  $form_data[$current_post_id.'ct_role_base_pricing_discount_start_date'];
	}

	update_post_meta( $current_post_id ,'ct_role_base_pricing_discount_start_date', $discount_start_date );



	$discount_end_date 			= [];
	if ( isset( $form_data[$current_post_id.'ct_role_base_pricing_discount_end_date'] ) ) {
		$discount_end_date 		=  $form_data[$current_post_id.'ct_role_base_pricing_discount_end_date'];
	}

	update_post_meta( $current_post_id ,'ct_role_base_pricing_discount_end_date', $discount_end_date );

	$inlcuded_days 				= [];
	if ( isset( $form_data[$current_post_id.'ct_rbpaqp_rbp_inlcuded_days'] ) ) {
		$inlcuded_days 			=  $form_data[$current_post_id.'ct_rbpaqp_rbp_inlcuded_days'];
	}

	update_post_meta( $current_post_id ,'ct_rbpaqp_rbp_inlcuded_days', $inlcuded_days );


	$post_priority = isset($form_data[$current_post_id.'ct_role_base_pricing_post_priority']) ? $form_data[$current_post_id.'ct_role_base_pricing_post_priority'] : '';

	$update_option_priority = array(
		'ID' => $current_post_id,
		'menu_order' => $post_priority,
	);

	wp_update_post($update_option_priority);;


}


}