<?php
if (! defined('WPINC') ) {
	die;
}


function ct_b2b_get_set_roles() {

	global $wp_roles;

	$all_role 			= $wp_roles->get_names();

	$all_role['guest'] 	= 'Guest'; 

	foreach ( (array) get_option('ct_rbpaqp_excludes_rule') as $role_key) {
		
		if ( ! empty( $role_key ) &&  $all_role[$role_key] ) {

			unset( $all_role[$role_key] );

		}

	}

	return $all_role;

}

function get_role_base_and_customer_base_pricing( $current_post_id , $discount_for = 'user_role' ) {

	$discount_on 	= ['regular_price','sale_price','both_regular_and_sale_price','do_not_apply_if_sale_price_exsist'];
	$discount_type 	= ['fix_price','fixed_increase','fixed_decrease','percentage_decrease','percentage_increase',];

	?>
	<tr>

		<td>

			<?php if ( 'customer' == $discount_for  ) {  ?>
				<select required style="width:100%" name="<?php echo esc_attr($current_post_id); ?>ct_role_base_pricing_selected_customer[]" class="ct-rbpaqp-live-search" multiple>
					<?php foreach ( get_users () as $user_object) {

						if ( ! is_object( $user_object ) ) {
							continue;
						}
						?>
						<option value="<?php echo esc_attr( $user_object->ID ); ?>" <?php if ( in_array($user_object->ID , (array) get_post_meta( $current_post_id ,'ct_role_base_pricing_selected_customer', true ) ) ) {  ?>
							selected
							<?php } ?> >
							<?php echo esc_attr( $user_object->display_name ); ?>
						</option>
					<?php } ?>
				</select>
			<?php } else {  ?>
				<select style="width:100%" name="<?php echo esc_attr($current_post_id); ?>ct_role_base_pricing_selected_user_role[]" class="ct-rbpaqp-live-search" multiple>
					<?php foreach ( ct_b2b_get_set_roles() as $role_key => $role_value): ?>
						<option value="<?php echo esc_attr( $role_key ); ?>" <?php if ( in_array($role_key , (array) get_post_meta( $current_post_id ,'ct_role_base_pricing_selected_user_role', true ) ) ) {  ?>
							selected
							<?php } ?> >
							<?php echo esc_attr( $role_value ); ?>
						</option>
					<?php endforeach ?>
				</select>
			<?php }  ?>
		</td>
		<td>
			<select name="<?php echo esc_attr($current_post_id); ?>ct_role_base_pricing_discount_on" class="ct-rbpaqp-live-search">
				<?php foreach ( $discount_on as $discount_on_value): ?>
					<option value="<?php echo esc_attr( $discount_on_value ); ?>" <?php if ( $discount_on_value == get_post_meta( $current_post_id ,'ct_role_base_pricing_discount_on', true ) ) {  ?>
						selected
						<?php } ?> >
						<?php echo esc_attr( ucfirst( str_replace( '_',' ' , $discount_on_value ) ) ); ?>
					</option>
				<?php endforeach ?>
			</select>
		</td>
		<td>
			<select name="<?php echo esc_attr($current_post_id); ?>ct_role_base_pricing_discount_type" class="ct-rbpaqp-live-search">
				<?php foreach ( $discount_type as $discount_type_value): ?>
					<option value="<?php echo esc_attr( $discount_type_value ); ?>" <?php if ( $discount_type_value == get_post_meta( $current_post_id ,'ct_role_base_pricing_discount_type', true ) ) {  ?>
						selected
						<?php } ?> >
						<?php echo esc_attr( ucfirst( str_replace( '_',' ' , $discount_type_value ) ) ); ?>
					</option>
				<?php endforeach ?>
			</select>
		</td>
		<td>
			<input style="width: 100%" type="number" min="1" required name="<?php echo esc_attr($current_post_id); ?>ct_role_base_pricing_discount_value" value="<?php echo esc_attr( get_post_meta( $current_post_id,'ct_role_base_pricing_discount_value',true ) ); ?>">
		</td>
		<td>
			<input style="width: 100%" type="number" min="1" required name="<?php echo esc_attr($current_post_id); ?>ct_role_base_pricing_discount_min" value="<?php echo esc_attr( get_post_meta( $current_post_id,'ct_role_base_pricing_discount_min',true ) ); ?>">
		</td>
		<td>
			<input style="width: 100%" type="number" min="1" name="<?php echo esc_attr($current_post_id); ?>ct_role_base_pricing_discount_max" value="<?php echo esc_attr( get_post_meta( $current_post_id,'ct_role_base_pricing_discount_max',true ) ); ?>">
		</td>
		<td>
			<input style="width: 100%" type="date" name="<?php echo esc_attr($current_post_id); ?>ct_role_base_pricing_discount_start_date" value="<?php echo esc_attr( get_post_meta( $current_post_id,'ct_role_base_pricing_discount_start_date',true ) ); ?>">
			<input style="width: 100%" type="date" name="<?php echo esc_attr($current_post_id); ?>ct_role_base_pricing_discount_end_date" value="<?php echo esc_attr( get_post_meta( $current_post_id,'ct_role_base_pricing_discount_end_date',true ) ); ?>">
		</td>
		<td>
			<select name="<?php echo esc_attr($current_post_id); ?>ct_rbpaqp_rbp_inlcuded_days[]" multiple style="width: 100%;" class="ct-rbpaqp-live-search">
				
				<?php 
				$daysOfWeek = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday',);
				foreach ($daysOfWeek as $days_name): ?>

					<option value="<?php echo esc_attr( $days_name ); ?>" <?php if (  in_array( $days_name , (array) get_post_meta( $current_post_id , 'ct_rbpaqp_rbp_inlcuded_days' , true )) ): ?>
					selected						
					<?php endif ?>>
					<?php echo esc_attr( $days_name ); ?>
				</option>

			<?php endforeach ?>

		</select>
	</td>
	<td style="display:inline-flex;" >

		<input style="width: 100%" type="number" min="1" name="<?php echo esc_attr($current_post_id); ?>ct_role_base_pricing_post_priority" value="<?php echo esc_attr( get_post_field('menu_order', $current_post_id) ); ?>">
		<i class="ct-rbpaqp-delete-post-id fa fa-trash" data-post_id="<?php echo esc_attr( $current_post_id ); ?>"></i>

	</td>

</tr>
<?php

}


function check_product_detail_availability_and_txt_and_price( $prod_id , $type_of_checks = '') {

	$product 		= 	wc_get_product( $prod_id );
	$hide_product 	= 	false;
	$current_day 	= 	strtolower(gmdate('l'));
	$current_time 	= 	time();
	$current_date 	= 	strtotime(gmdate('d-m-Y'));


	// product level setting.

	if (  !empty(get_post_meta( $prod_id ,'ct_rbpaqp_hpaatcb_restriction_product_level',true)) ) {

		$country 		= 	get_post_meta( $prod_id,'ct_rbpaqp_hpaatcb_inlcuded_country',true ) ? get_post_meta( $prod_id,'ct_rbpaqp_hpaatcb_inlcuded_country',true ) : [ct_rbpaqp_get_current_user_country()];
		
		$roles 			= 	get_post_meta( $prod_id,'ct_rbpaqp_hpaatcb_inlcuded_role',true ) ? array_map('ct_b2b_converttolower',  get_post_meta( $prod_id,'ct_rbpaqp_hpaatcb_inlcuded_role',true ) ) : [ct_rbpaqp_get_current_user_role()];


		$start_date 	= 	get_post_meta( $prod_id,'ct_rbpaqp_hpaatcb_start_date',true ) ? strtotime(get_post_meta( $prod_id,'ct_rbpaqp_hpaatcb_start_date',true )) : $current_date;
		$end_date 		= 	get_post_meta( $prod_id,'ct_rbpaqp_hpaatcb_end_date',true ) ? strtotime( get_post_meta( $prod_id,'ct_rbpaqp_hpaatcb_end_date',true ) ) : $current_date;


		$start_time 	= 	get_post_meta( $prod_id,'ct_rbpaqp_hpaatcb_start_time',true ) ? strtotime( get_post_meta( $prod_id,'ct_rbpaqp_hpaatcb_start_time',true ) ) : $current_time;
		$end_time 		= 	get_post_meta( $prod_id,'ct_rbpaqp_hpaatcb_end_time',true ) ? get_post_meta( $prod_id,'ct_rbpaqp_hpaatcb_end_time',true ) : $current_time;

		$inlcuded_days 	= 	get_post_meta( $prod_id,'ct_rbpaqp_hpaatcb_inlcuded_days',true ) ? array_map('ct_b2b_converttolower', (array) get_post_meta( $prod_id,'ct_rbpaqp_hpaatcb_inlcuded_days',true ) ) : [$current_day];

		// echo "Testing on Product Level";
		// country.
		if ( !empty( ct_rbpaqp_get_current_user_country() ) && ! in_array( ct_rbpaqp_get_current_user_country() , $country ) ) {
			
			// echo "country";
			return $hide_product;
		}


		// roles.
		if ( !empty( $roles ) && ! in_array( ct_rbpaqp_get_current_user_role() , $roles ) ) {
			
			// echo "roles";
			
			return $hide_product;
		}

		if ( $current_date < $start_date || $end_date > $current_date ) {
			
			// echo "date";
			
			return $hide_product;
		}

		if ( $current_time < $start_time || $end_time > $current_time ) {
			
			// echo "time";
			
			return $hide_product;
		}

		if ( ! in_array( strtolower( $current_day ) , $inlcuded_days ) ) {
			
			// echo "days";
			
			return $hide_product;
		}


		if ( 'hide_product' == $type_of_checks && !empty( get_post_meta( $prod_id ,'ct_rbpaqp_hpaatcb_hide_product',true) ) ) {


			$hide_product_base_on 	= get_post_meta( $prod_id ,'ct_rbpaqp_hpaatcb_hide_product_base_on', true) ? get_post_meta( $prod_id ,'ct_rbpaqp_hpaatcb_hide_product_base_on', true) : 'immigiatly' ;

			$hide_product_base_on_quantity 	= get_post_meta( $prod_id ,'ct_rbpaqp_hpaatcb_hide_product_on_min_quantity', true) ? get_post_meta( $prod_id ,'ct_rbpaqp_hpaatcb_hide_product_on_min_quantity', true) : 1 ;


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

		if ( 'price' == $type_of_checks ) {
			$ct_rbpaqp_hpaatcb_price_setting   = get_post_meta( $prod_id ,'ct_rbpaqp_hpaatcb_price_setting', true) ? get_post_meta( $prod_id ,'ct_rbpaqp_hpaatcb_price_setting', true) : '';

			$ct_rbpaqp_hpaatcb_price_text  = get_post_meta( $prod_id ,'ct_rbpaqp_hpaatcb_price_text', true) ? get_post_meta( $prod_id ,'ct_rbpaqp_hpaatcb_price_text', true) : '';

			if ( 'hide_price' == $ct_rbpaqp_hpaatcb_price_setting ) {
				$price          = '';
				return ['new_price' =>  $price ];

			}
			if ( 'replace_price_text' == $ct_rbpaqp_hpaatcb_price_setting && $ct_rbpaqp_hpaatcb_price_text ) {
				$price   = $ct_rbpaqp_hpaatcb_price_text;
				return ['new_price' =>  $price ];
			}

		}

		if ( 'hide_add_to_cart_button' == $type_of_checks ) {
			
			return ['replace_text_button_or_hide_button' => $prod_id];

		}

	}

	// Rule Base.
	$get_all_rule 	= 	get_posts([ 'post_type' => 'ct_hide_p_nd_a_t_c_b', 'post_status' => 'publish','posts_per_page' => -1,'fields' => 'ids' ]);

	// echo "testing on rule Level";

	foreach ( $get_all_rule as $current_post_id ) {

		if ( empty( $current_post_id ) ) {
			continue;
		}

		$country 		= 	get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_inlcuded_country',true ) ? get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_inlcuded_country',true ) : [ct_rbpaqp_get_current_user_country()];
		
		$roles 			= 	get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_inlcuded_role',true ) ? array_map('ct_b2b_converttolower',  get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_inlcuded_role',true ) ) : [ct_rbpaqp_get_current_user_role()];


		$start_date 	= 	get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_start_date',true ) ? strtotime(get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_start_date',true )) : $current_date;
		$end_date 		= 	get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_end_date',true ) ? strtotime( get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_end_date',true ) ) : $current_date;


		$start_time 	= 	get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_start_time',true ) ? strtotime( get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_start_time',true ) ) : $current_time;
		$end_time 		= 	get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_end_time',true ) ? get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_end_time',true ) : $current_time;

		$inlcuded_days 	= 	get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_inlcuded_days',true ) ? array_map('ct_b2b_converttolower', (array) get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_inlcuded_days',true ) ) : [$current_day];

		// country.
		if ( !empty( ct_rbpaqp_get_current_user_country() ) && ! in_array( ct_rbpaqp_get_current_user_country() , $country ) ) {
			continue;
		}


		// roles.s
		if ( !empty( $roles ) && ! in_array( ct_rbpaqp_get_current_user_role() , $roles ) ) {

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

		$products 				= 	(array) get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_inlcuded_products',true );
		$category 				= 	(array) get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_inlcuded_category',true );
		$tags 					= 	(array) get_post_meta( $current_post_id,'ct_rbpaqp_hpaatcb_inlcuded_tag',true );

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

		if ( $is_product_match ) {

			

			if ( 'hide_product' == $type_of_checks && !empty( get_post_meta( $current_post_id ,'ct_rbpaqp_hpaatcb_hide_product',true) ) ) {

				$hide_product_base_on 	= get_post_meta( $current_post_id ,'ct_rbpaqp_hpaatcb_hide_product_base_on', true) ? get_post_meta( $current_post_id ,'ct_rbpaqp_hpaatcb_hide_product_base_on', true) : 'immigiatly' ;

				$hide_product_base_on_quantity 	= get_post_meta( $current_post_id ,'ct_rbpaqp_hpaatcb_hide_product_on_min_quantity', true) ? get_post_meta( $current_post_id ,'ct_rbpaqp_hpaatcb_hide_product_on_min_quantity', true) : 1 ;


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

			if ( 'price' == $type_of_checks ) {
				$ct_rbpaqp_hpaatcb_price_setting   = get_post_meta( $current_post_id ,'ct_rbpaqp_hpaatcb_price_setting', true) ? get_post_meta( $current_post_id ,'ct_rbpaqp_hpaatcb_price_setting', true) : '' ;

				$ct_rbpaqp_hpaatcb_price_text  = get_post_meta( $current_post_id ,'ct_rbpaqp_hpaatcb_price_text', true) ? get_post_meta( $current_post_id ,'ct_rbpaqp_hpaatcb_price_text', true) : '' ;


				if ( 'hide_price' == $ct_rbpaqp_hpaatcb_price_setting ) {
					$price          = '';
					return ['new_price' =>  $price ];

				}
				if ( 'replace_price_text' == $ct_rbpaqp_hpaatcb_price_setting && $ct_rbpaqp_hpaatcb_price_text ) {
					$price   = $ct_rbpaqp_hpaatcb_price_text;
					return ['new_price' =>  $price ];
				}

			}
			if ( 'hide_add_to_cart_button' == $type_of_checks ) {
				
				return ['replace_text_button_or_hide_button' => $current_post_id];

			}
		}

	}

	return $hide_product;
}


function ct_rbpaqp_get_current_user_country() {
	$geo_data         	= WC_Geolocation::geolocate_ip();
	return $geo_data['country'];
}

function ct_rbpaqp_get_current_user_role() {
	return is_user_logged_in() ? current( wp_get_current_user()->roles ) : 'guest';
}

function ct_b2b_converttolower($value) {
	return strtolower($value);
}





// min max quantity setting and functions .
function ct_rbpaqp_get_product_min_max_quantity( $prod_id ) {

	$product 		= 	wc_get_product( $prod_id );
	$hide_product 	= 	false;
	$current_day 	= 	strtolower(gmdate('l'));
	$current_time 	= 	time();
	$current_date 	= 	strtotime(gmdate('d-m-Y'));
	$user_role 		= 	ct_rbpaqp_mmq_get_current_user_role();

	// product level setting.

	if (  !empty(get_post_meta( $prod_id ,'ct_rbpaqp_mmq_restriction_product_level',true)) ) {

		$start_date 	= 	get_post_meta( $prod_id,'ct_rbpaqp_mmq_start_date',true ) ? strtotime(get_post_meta( $prod_id,'ct_rbpaqp_mmq_start_date',true )) : $current_date;
		$end_date 		= 	get_post_meta( $prod_id,'ct_rbpaqp_mmq_end_date',true ) ? strtotime( get_post_meta( $prod_id,'ct_rbpaqp_mmq_end_date',true ) ) : $current_date;


		$start_time 	= 	get_post_meta( $prod_id,'ct_rbpaqp_mmq_start_time',true ) ? strtotime( get_post_meta( $prod_id,'ct_rbpaqp_mmq_start_time',true ) ) : $current_time;
		$end_time 		= 	get_post_meta( $prod_id,'ct_rbpaqp_mmq_end_time',true ) ? get_post_meta( $prod_id,'ct_rbpaqp_mmq_end_time',true ) : $current_time;

		$inlcuded_days 	= 	get_post_meta( $prod_id,'ct_rbpaqp_mmq_inlcuded_days',true ) ? array_map('ct_hpandv_convertToLower', (array) get_post_meta( $prod_id,'ct_rbpaqp_mmq_inlcuded_days',true ) ) : [$current_day];


		if ( $current_date < $start_date || $end_date > $current_date ) {
			
			return $hide_product;
		}

		if ( $current_time < $start_time || $end_time > $current_time ) {
			
			return $hide_product;
		}

		if ( ! in_array( strtolower( $current_day ) , $inlcuded_days ) ) {

			
			return $hide_product;
		}


		$min_max_qty_array 		= (array) get_post_meta( $prod_id ,'ct_rbpaqp_mmq_rules',true );

		if ( isset( $min_max_qty_array[$user_role] ) && isset( $min_max_qty_array[$user_role]['enable_setting'] ) && !empty( $min_max_qty_array[$user_role]['enable_setting'] ) ) {

			$country 			= isset( $min_max_qty_array[$user_role]['countries'] ) && !empty( $min_max_qty_array[$user_role]['countries'] )  ? (array) $min_max_qty_array[$user_role]['countries'] : [ct_rbpaqp_mmq_get_current_user_country()];

		// Country.
			if ( !empty( $country ) && !empty( ct_rbpaqp_mmq_get_current_user_country() ) && ! in_array( ct_rbpaqp_mmq_get_current_user_country() , $country ) ) {
				return $hide_product;
			}
			
			$final_array 	= 	$min_max_qty_array[$user_role];


			$min_value 		=	isset($final_array['min_qty']) && !empty( $final_array['min_qty'] ) ? $final_array['min_qty'] : $product->get_min_purchase_quantity();
			$max_value 		=	isset($final_array['max_qty']) && !empty( $final_array['max_qty'] ) ? $final_array['max_qty'] : $product->get_max_purchase_quantity();
			$step 			=	isset($final_array['step']) && !empty( $final_array['step'] ) ? $final_array['step'] : $product->get_step_quantity();

			return [ 'min_value' => $min_value ,'max_value' => $max_value, 'step' => $step,'active_rule_id' => $prod_id ];

		}


	}



	// Rule Base.
	$get_all_rule 	= 	get_posts([ 'post_type' => 'ct_min_max_qty_role', 'post_status' => 'publish','posts_per_page' => -1,'fields' => 'ids' ]);

	foreach ( $get_all_rule as $current_post_id ) {

		if ( empty( $current_post_id ) ) {
			continue;
		}

		$start_date 	= 	get_post_meta( $current_post_id,'ct_rbpaqp_mmq_start_date',true ) ? strtotime(get_post_meta( $current_post_id,'ct_rbpaqp_mmq_start_date',true )) : $current_date;
		$end_date 		= 	get_post_meta( $current_post_id,'ct_rbpaqp_mmq_end_date',true ) ? strtotime( get_post_meta( $current_post_id,'ct_rbpaqp_mmq_end_date',true ) ) : $current_date;


		$start_time 	= 	get_post_meta( $current_post_id,'ct_rbpaqp_mmq_start_time',true ) ? strtotime( get_post_meta( $current_post_id,'ct_rbpaqp_mmq_start_time',true ) ) : $current_time;
		$end_time 		= 	get_post_meta( $current_post_id,'ct_rbpaqp_mmq_end_time',true ) ? get_post_meta( $current_post_id,'ct_rbpaqp_mmq_end_time',true ) : $current_time;

		$inlcuded_days 	= 	get_post_meta( $current_post_id,'ct_rbpaqp_mmq_inlcuded_days',true ) ? array_map('ct_hpandv_convertToLower', (array) get_post_meta( $current_post_id,'ct_rbpaqp_mmq_inlcuded_days',true ) ) : [$current_day];

		
		// Date.
		if ( $current_date < $start_date || $end_date > $current_date ) {
			continue;
		}

		// Time.
		if ( $current_time < $start_time || $end_time > $current_time ) {

			continue;
		}

		// days.
		if ( ! in_array( strtolower( $current_day ) , $inlcuded_days ) ) {
			
			continue;
		}

		$products 				= 	(array) get_post_meta( $current_post_id,'ct_rbpaqp_mmq_inlcuded_products',true );
		$category 				= 	(array) get_post_meta( $current_post_id,'ct_rbpaqp_mmq_inlcuded_category',true );
		$tags 					= 	(array) get_post_meta( $current_post_id,'ct_rbpaqp_mmq_inlcuded_tag',true );

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

		$min_max_qty_array 		= (array) get_post_meta( $current_post_id ,'ct_rbpaqp_mmq_rules',true );

		if ( $is_product_match &&  isset( $min_max_qty_array[$user_role] ) && isset( $min_max_qty_array[$user_role]['enable_setting'] ) && !empty( $min_max_qty_array[$user_role]['enable_setting'] ) ) {

			$country 			= isset( $min_max_qty_array[$user_role]['countries'] ) && !empty( $min_max_qty_array[$user_role]['countries'] )  ? (array) $min_max_qty_array[$user_role]['countries'] : [];

			// Country.

			if ( !empty( $country ) && !empty( ct_rbpaqp_mmq_get_current_user_country() ) && ! in_array( ct_rbpaqp_mmq_get_current_user_country() , $country ) ) {
				continue;
			}
			
			$final_array 	= 	$min_max_qty_array[$user_role];


			$min_value 		=	isset($final_array['min_qty']) && !empty( $final_array['min_qty'] ) ? $final_array['min_qty'] : $product->get_min_purchase_quantity();
			$max_value 		=	isset($final_array['max_qty']) && !empty( $final_array['max_qty'] ) ? $final_array['max_qty'] : $product->get_max_purchase_quantity();
			$step 			=	isset($final_array['step']) && !empty( $final_array['step'] ) ? $final_array['step'] : $product->get_step_quantity();

			return [ 'min_value' => $min_value ,'max_value' => $max_value, 'step' => $step ,'active_rule_id' => $current_post_id];

		}

	}

	return $hide_product;
}

function ct_rbpaqp_get_product_min_max_quantity_template( $product_detail , $product_id = 0 ) {

	$min_value 	=   isset($product_detail['min_value']) && !empty( $product_detail['min_value'] ) ? $product_detail['min_value'] : 1;
	$max_value 	=   isset($product_detail['max_value']) && !empty( $product_detail['max_value'] ) ? $product_detail['max_value'] : '';
	$step 		=   isset($product_detail['step']) && !empty( $product_detail['step'] ) ? $product_detail['step'] : 1;

	?>
	<h3><?php echo esc_attr( get_option('ct_rbpaqp_set_min_max_table_title') ); ?></h3> 

	<table>
		<thead>
			<th><?php echo esc_html__('Min Quantity' ,'cloud_tech_rbpaqpfw'); ?></th>
			<th><?php echo esc_html__('Max Quantity' ,'cloud_tech_rbpaqpfw'); ?></th>
		</thead>
		<tbody>
			<tr>
				<td>
					<?php echo esc_attr( $min_value ); ?>
				</td>
				<td>
					<?php echo esc_attr( $max_value); ?>
					<input type="hidden" name="ct_rbpaqp_get_product_min_max_qty<?php echo esc_attr( $product_id ); ?>" data-step="<?php echo esc_attr($step); ?>" data-min_value="<?php echo esc_attr($min_value); ?>" data-max_value="<?php echo esc_attr($max_value); ?>" value="<?php echo esc_attr($step); ?>">
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

function ct_rbpaqp_mmq_get_current_user_country() {
	$geo_data         	= WC_Geolocation::geolocate_ip();
	return $geo_data['country'];
}

function ct_rbpaqp_mmq_get_current_user_role() {
	return is_user_logged_in() ? current( wp_get_current_user()->roles ) : 'guest';
}

function ct_hpandv_convertToLower($value) {
	return strtolower($value);
}


function ct_b2b_custom_array_filter( $filters = [] ) {
	$filters 			= array_filter( (array) $filters, function( $current_value, $current_key) {
		return ( '' !== $current_value  && '' !== $current_key );
	}, ARRAY_FILTER_USE_BOTH);

	return  $filters;
}
