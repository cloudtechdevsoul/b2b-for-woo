<?php
if (! defined('WPINC') ) {
	die;
}

add_action( 'woocommerce_product_query', 'cloud_hpaatcb_hpav_custom_pre_get_posts_query', PHP_INT_MAX);
add_filter( 'woocommerce_product_is_visible', 'cloud_hpaatcb_hpav_check_visibility_rules', 10, 2);
add_filter( 'woocommerce_variation_is_active', 'cloud_hpaatcb_hpav_roles_variation_is_active' , 1, 2 );
add_filter( 'woocommerce_variation_is_visible', 'cloud_hpaatcb_hpav_roles_variation_is_visible', 1, 2 );
add_filter('woocommerce_hide_invisible_variations','cloud_hpaatcb_hpav_hide_variations_from_swatches', 1, 3);

function cloud_hpaatcb_hpav_custom_pre_get_posts_query( $q) {

	if ($q->is_main_query() && is_post_type_archive('product')) {

		$all_products   = wc_get_products([
			'type'              => array_merge( ['product_variation' => 'product_variation'] , array_keys( wc_get_product_types() ) ),
			'return'            => 'ids',
			'status'            => 'publish',
			'posts_per_page'    => -1,
		]);
		foreach ( $all_products as $key => $product_id ) {
			$hide_product        = ct_rbpaqp_hpav_hide_product_and_variation( $product_id);
			if ( ! $hide_product ) {	
				unset( $all_products[ $key ] );
			}
		}
		$q->set('post__not_in', $all_products );
	}

}

function cloud_hpaatcb_hpav_check_visibility_rules( $visible, $product_id) {

	$hide_product        = ct_rbpaqp_hpav_hide_product_and_variation( $product_id);
	if ( $hide_product ) {
		$visible = false;
	}

	return $visible;

}

function cloud_hpaatcb_hpav_hide_variations_from_swatches($visible, $ID, $variation) {

	$hide_product        = ct_rbpaqp_hpav_hide_product_and_variation( $variation->get_id());
	if ( $hide_product ) {
		$visible = true;
	}
	return $visible;
}
function cloud_hpaatcb_hpav_roles_variation_is_active( $active, $variation ) {

	$hide_product        = ct_rbpaqp_hpav_hide_product_and_variation( $variation->get_id());
	if ( $hide_product ) {
		$active = false;
	}

	return $active;
}

function cloud_hpaatcb_hpav_roles_variation_is_visible( $visible, $variation_id ) {

	$hide_product        = ct_rbpaqp_hpav_hide_product_and_variation( $variation_id );
	if ( $hide_product ) {
		$visible = false;
	}

	return $visible;
}

function ct_rbpaqp_hpav_hide_product_and_variation( $prod_id ) {
	$product 		= 	wc_get_product( $prod_id );
	$hide_product 	= 	false;
	$current_day 	= 	strtolower(gmdate('l'));
	$current_time 	= 	time();
	$current_date 	= 	strtotime(gmdate('d-m-Y'));


	// product level setting.

	if (  !empty(get_post_meta( $prod_id ,'ct_rbpaqp_hpav_hide_product',true)) ) {

		$country 		= 	get_post_meta( $prod_id,'ct_rbpaqp_hpav_inlcuded_country',true ) ? get_post_meta( $prod_id,'ct_rbpaqp_hpav_inlcuded_country',true ) : [ct_rbpaqp_hpandv_get_current_user_country()];
		
		$roles 			= 	get_post_meta( $prod_id,'ct_rbpaqp_hpav_inlcuded_role',true ) ? array_map('ct_rbpaqp_hpandv_convertToLower',  get_post_meta( $prod_id,'ct_rbpaqp_hpav_inlcuded_role',true ) ) : [ct_rbpaqp_hpandv_get_current_user_role()];


		$start_date 	= 	get_post_meta( $prod_id,'ct_rbpaqp_hpav_start_date',true ) ? strtotime(get_post_meta( $prod_id,'ct_rbpaqp_hpav_start_date',true )) : $current_date;
		$end_date 		= 	get_post_meta( $prod_id,'ct_rbpaqp_hpav_end_date',true ) ? strtotime( get_post_meta( $prod_id,'ct_rbpaqp_hpav_end_date',true ) ) : $current_date;


		$start_time 	= 	get_post_meta( $prod_id,'ct_rbpaqp_hpav_start_time',true ) ? strtotime( get_post_meta( $prod_id,'ct_rbpaqp_hpav_start_time',true ) ) : $current_time;
		$end_time 		= 	get_post_meta( $prod_id,'ct_rbpaqp_hpav_end_time',true ) ? get_post_meta( $prod_id,'ct_rbpaqp_hpav_end_time',true ) : $current_time;

		$inlcuded_days 	= 	get_post_meta( $prod_id,'ct_rbpaqp_hpav_inlcuded_days',true ) ? array_map('ct_rbpaqp_hpandv_convertToLower', (array) get_post_meta( $prod_id,'ct_rbpaqp_hpav_inlcuded_days',true ) ) : [$current_day];


		if ( !empty( ct_rbpaqp_hpandv_get_current_user_country() ) && ! in_array( ct_rbpaqp_hpandv_get_current_user_country() , $country ) ) {
			
			return $hide_product;
		}



		// roles.
		if ( !empty( $roles ) && ! in_array( ct_rbpaqp_hpandv_get_current_user_role() , $roles ) ) {
			

			return $hide_product;
		}

		if ( $current_date < $start_date || $end_date > $current_date ) {
			
			return $hide_product;
		}

		if ( $current_time < $start_time || $end_time > $current_time ) {
			
			return $hide_product;
		}

		if ( ! in_array( strtolower( $current_day ) , $inlcuded_days ) ) {

			
			return $hide_product;
		}


		if ( !empty( get_post_meta( $prod_id ,'ct_rbpaqp_hpav_hide_product',true) ) ) {


			$hide_product_base_on 	= get_post_meta( $prod_id ,'ct_rbpaqp_hpav_hide_product_base_on', true) ? get_post_meta( $prod_id ,'ct_rbpaqp_hpav_hide_product_base_on', true) : 'immigiatly' ;

			$hide_product_base_on_quantity 	= get_post_meta( $prod_id ,'ct_rbpaqp_hpav_hide_product_on_min_quantity', true) ? get_post_meta( $prod_id ,'ct_rbpaqp_hpav_hide_product_on_min_quantity', true) : 1 ;


			if ( 'immigiatly' == $hide_product_base_on ) {
				$hide_product 	= true;
			}

			if ( 'out_of_stock' == $hide_product_base_on && !$product->is_in_stock() ) {
				$hide_product 	= true;
			}

			if ( 'on_back_order' == $hide_product_base_on && 'onbackorder' == $product->get_stock_status() ) {
				$hide_product 	= true;
			}

			if ( 'on_specific_quantity' == $hide_product_base_on && !empty( get_post_meta( $prod_id ,'_manage_stock' ,true ) ) && $product->get_stock_quantity() < $hide_product_base_on_quantity ) {
				$hide_product 	= true;
			}

			return $hide_product;
		}
	}

	// Rule Base.
	$get_all_rule 	= 	get_posts([ 'post_type' => 'ct_hide_prdct_nd_var', 'post_status' => 'publish','posts_per_page' => -1,'fields' => 'ids' ]);
	
	foreach ( $get_all_rule as $current_post_id ) {

		if ( empty( $current_post_id ) ) {
			continue;
		}

		$country 		= 	get_post_meta( $current_post_id,'ct_rbpaqp_hpav_inlcuded_country',true ) ? get_post_meta( $current_post_id,'ct_rbpaqp_hpav_inlcuded_country',true ) : [ct_rbpaqp_hpandv_get_current_user_country()];
		
		$roles 			= 	get_post_meta( $current_post_id,'ct_rbpaqp_hpav_inlcuded_role',true ) ? array_map('ct_rbpaqp_hpandv_convertToLower',  get_post_meta( $current_post_id,'ct_rbpaqp_hpav_inlcuded_role',true ) ) : [ct_rbpaqp_hpandv_get_current_user_role()];


		$start_date 	= 	get_post_meta( $current_post_id,'ct_rbpaqp_hpav_start_date',true ) ? strtotime(get_post_meta( $current_post_id,'ct_rbpaqp_hpav_start_date',true )) : $current_date;
		$end_date 		= 	get_post_meta( $current_post_id,'ct_rbpaqp_hpav_end_date',true ) ? strtotime( get_post_meta( $current_post_id,'ct_rbpaqp_hpav_end_date',true ) ) : $current_date;


		$start_time 	= 	get_post_meta( $current_post_id,'ct_rbpaqp_hpav_start_time',true ) ? strtotime( get_post_meta( $current_post_id,'ct_rbpaqp_hpav_start_time',true ) ) : $current_time;
		$end_time 		= 	get_post_meta( $current_post_id,'ct_rbpaqp_hpav_end_time',true ) ? get_post_meta( $current_post_id,'ct_rbpaqp_hpav_end_time',true ) : $current_time;

		$inlcuded_days 	= 	get_post_meta( $current_post_id,'ct_rbpaqp_hpav_inlcuded_days',true ) ? array_map('ct_rbpaqp_hpandv_convertToLower', (array) get_post_meta( $current_post_id,'ct_rbpaqp_hpav_inlcuded_days',true ) ) : [$current_day];

		// country.
		if ( !empty( ct_rbpaqp_hpandv_get_current_user_country() ) && ! in_array( ct_rbpaqp_hpandv_get_current_user_country() , $country ) ) {
			continue;
		}


		// roles.
		if ( !empty( $roles ) && ! in_array( ct_rbpaqp_hpandv_get_current_user_role() , $roles ) ) {

			continue;
		}

		if ( $current_date < $start_date || $end_date > $current_date ) {
			continue;
		}

		if ( $current_time < $start_time || $end_time > $current_time ) {

			continue;
		}

		if ( ! in_array( strtolower( $current_day ) , $inlcuded_days ) ) {
			
			continue;
		}

		$products 				= 	(array) get_post_meta( $current_post_id,'ct_rbpaqp_hpav_inlcuded_products',true );
		$category 				= 	(array) get_post_meta( $current_post_id,'ct_rbpaqp_hpav_inlcuded_category',true );
		$tags 					= 	(array) get_post_meta( $current_post_id,'ct_rbpaqp_hpav_inlcuded_tag',true );

		$is_product_match 		= false; 
		if ( empty( $products )  && empty( $category ) && empty( $tags ) ) {
			$is_product_match 	= true;
		}

		if ( ! empty( $products ) && in_array( $prod_id , $products ) ) {
			$is_product_match 	= true;
		}

		if ( ! empty( $category ) && has_term( $category ,'product_cat' ,$prod_id ) ) {
			$is_product_match 	= true;
		}

		if ( ! empty( $tags ) && has_term( $tags ,'product_tag' ,$prod_id ) ) {
			$is_product_match 	= true;
		}
		

		if ( $is_product_match &&  !empty( get_post_meta( $current_post_id ,'ct_rbpaqp_hpav_hide_product',true) ) ) {

			$hide_product_base_on 	= get_post_meta( $current_post_id ,'ct_rbpaqp_hpav_hide_product_base_on', true) ? get_post_meta( $current_post_id ,'ct_rbpaqp_hpav_hide_product_base_on', true) : 'immigiatly' ;

			$hide_product_base_on_quantity 	= get_post_meta( $current_post_id ,'ct_rbpaqp_hpav_hide_product_on_min_quantity', true) ? get_post_meta( $current_post_id ,'ct_rbpaqp_hpav_hide_product_on_min_quantity', true) : 1 ;


			if ( 'immigiatly' == $hide_product_base_on ) {
				$hide_product 	= true;
				break;
			}

			if ( 'out_of_stock' == $hide_product_base_on && !$product->is_in_stock() ) {
				$hide_product 	= true;
				break;
			}

			if ( 'on_back_order' == $hide_product_base_on && 'onbackorder' == $product->get_stock_status() ) {
				$hide_product 	= true;
				break;
			}

			if ( 'on_specific_quantity' == $hide_product_base_on && !empty( get_post_meta( $current_post_id ,'_manage_stock' ,true ) ) && $product->get_stock_quantity() < $hide_product_base_on_quantity ) {
				$hide_product 	= true;
				break;
			}

			
		}

	}

	return $hide_product;
}


function ct_rbpaqp_hpandv_get_current_user_country() {
	$geo_data         	= WC_Geolocation::geolocate_ip();
	return $geo_data['country'];
}

function ct_rbpaqp_hpandv_get_current_user_role() {
	return is_user_logged_in() ? current( wp_get_current_user()->roles ) : 'guest';
}

function ct_rbpaqp_hpandv_convertToLower($value) {
	return strtolower($value);
}