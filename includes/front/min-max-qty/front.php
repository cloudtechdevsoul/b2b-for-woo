<?php
if (! defined('WPINC') ) {
	die;
}

add_filter( 'woocommerce_quantity_input_args', 'cloud_mmq_min_max_quantity_changes', 1, 2 );
add_action( 'woocommerce_before_add_to_cart_quantity', 'cloud_mmq_product_qty_table_html' );
add_filter( 'woocommerce_available_variation', 'cloud_mmq_customize_variation_quantity_limits', 10, 3);
add_filter( 'woocommerce_add_to_cart_validation', 'cloud_mmq_custom_quantity_validation', 10, 4);
add_filter( 'woocommerce_update_cart_validation', 'cloud_mmq_woocommerce_update_cart_validation', 10, 4 );

function cloud_mmq_product_qty_table_html(){
	global $product;

	$sold_individually              = get_post_meta( $product->get_id(), '_sold_individually', true);
	if ( 'yes' == $sold_individually ) {
		return;
	}
	
	if ( 'variable' == $product->get_type() ) {

		foreach ($product->get_children() as $current_variation_id ) {
			?>
			<div class="cloud_hpaatcb_var_product_qty_table_html cloud_hpaatcb_var_product_qty_table_html<?php echo esc_attr( $current_variation_id ); ?>" style="display: none;">
				<?php 

				$product_detail 	= (array) ct_rbpaqp_get_product_min_max_quantity( $current_variation_id );
				$variation 			= wc_get_product( $current_variation_id );

				if ( count( $product_detail ) > 1 ) {
					echo wp_kses_post( ct_rbpaqp_get_product_min_max_quantity_template( $product_detail , $current_variation_id ) ); 
				}else {
					$step  			= 1;
					if (is_a($variation, 'WC_Product_Variation')) {
						$step = $variation->get_attribute('step');
					}
					?>

					<input type="hidden" name="ct_rbpaqp_get_product_min_max_qty<?php echo esc_attr( $current_variation_id ); ?>" data-step="<?php echo esc_attr($step); ?>" value="<?php echo esc_attr($step); ?>">

				<?php } ?>

			</div>
			<?php
		}
	} else {

		?>
		<div class="cloud_hpaatcb_product_qty_table_html">
			<?php 
			$product_id     = $product->get_id();
			$product_detail = (array) ct_rbpaqp_get_product_min_max_quantity($product_id);

			if ( count( $product_detail ) > 1 ) {
				echo wp_kses_post( ct_rbpaqp_get_product_min_max_quantity_template( $product_detail ) ); 
			}
			?>
		</div>
		<?php

	}
}
function cloud_mmq_min_max_quantity_changes( $args, $product ) {

	$sold_individually              = get_post_meta( $product->get_id(), '_sold_individually', true);
	if ( 'variation' == $product->get_type() && 'yes' == get_post_meta( $product->get_parent_id(), '_sold_individually', true) ) { 
		return $args;
	}
	if ( 'yes' == $sold_individually ) {
		return $args;
	}

	$product_detail = (array) ct_rbpaqp_get_product_min_max_quantity($product->get_id());
	if ( count( $product_detail ) > 1 ) {

		$args 	= array_merge( $args,$product_detail );

	}

	return $args;
}


function cloud_mmq_customize_variation_quantity_limits($variation_data, $product, $variation) {

	$product_detail = (array) ct_rbpaqp_get_product_min_max_quantity($variation->get_id());
	if ( count( $product_detail ) > 1 ) {

		$variation_data['min_qty'] 		=	isset($product_detail['min_value']) ? $product_detail['min_value'] : 1;
		$variation_data['max_qty'] 		=	isset($product_detail['max_value']) ? $product_detail['max_value'] : '';
		$variation_data['step'] 		=	isset($product_detail['step']) ? $product_detail['step'] : 1;

	}
	
	return $variation_data;

}



function cloud_mmq_custom_quantity_validation($passed, $product_id, $quantity, $variation_id = 0 ) {


	$sold_individually 					= get_post_meta( $product_id, '_sold_individually', true);
	if ( 'yes' == $sold_individually ) { 
		return $passed;
	}

	$product_or_variation_id 			= $variation_id >=1 ? $variation_id : $product_id; 
	$product_detail 					= (array) ct_rbpaqp_get_product_min_max_quantity( $product_or_variation_id );
	$added_product_with_qty 			= [ $product_or_variation_id => $quantity];

	if ( isset( $product_detail['active_rule_id'] ) ) {
		$active_rule_id 				= $product_detail['active_rule_id'];
		$add_purchas_item_qty_or_not  	= get_post_meta( $active_rule_id,'ct_rbpaqp_mmq_add_purchas_item_qty_or_not' , true );
		
		if ( !empty( $add_purchas_item_qty_or_not ) ) {

			$order_status_purchas_item_qty  	= !empty( get_post_meta( $active_rule_id,'ct_rbpaqp_mmq_order_status_purchas_item_qty' , true ) ) ? (array) get_post_meta( $active_rule_id,'ct_rbpaqp_mmq_order_status_purchas_item_qty' , true ) : ['any'];

			$product_id = $product_or_variation_id;

			$order_ids 	= array();

			$args 		= array(
				'post_type'         => 'shop_order',
				'post_status'       => $order_status_purchas_item_qty,
				'meta_query'        => array(
					array(
			            'key'     	=> '_customer_user', // This is the customer user key
			            'value'   	=> get_current_user_id(), // Current user's ID
			            'compare' 	=> '=',
			        ),
				),
				'fields' 			=> 'ids',
			);

			$orders = get_posts($args);


			foreach ($orders as $order_id) {

				$order 		= wc_get_order($order_id);

				foreach ($order->get_items() as $item) {

					$order_prod_or_var_id 		= $item->get_variation_id() && $item->get_variation_id() >= 1 ? $item->get_variation_id() : $item->get_product_id();

					if ( $order_prod_or_var_id == $product_or_variation_id ) {			            

						$current_prd_qty 		=  (float) $item->get_quantity();

						$added_product_with_qty[ $order_prod_or_var_id ] = isset( $added_product_with_qty[ $order_prod_or_var_id ] ) ? (float) $added_product_with_qty[ $order_prod_or_var_id ] + $current_prd_qty : $current_prd_qty;
					}
				}
			}
		}
	}


	foreach ( WC()->cart->get_cart() as $key => $value ) {


		$crt_prd_or_var_id 		= isset( $value['variation_id'] ) && $value['variation_id'] >= 1 ? $value['variation_id'] :  $value['product_id'];
		$current_prd_qty 		=  (float) isset( $value['quantity'] ) ? (float) $value['quantity'] : 1;

		$added_product_with_qty[ $crt_prd_or_var_id ] = isset( $added_product_with_qty[ $crt_prd_or_var_id ] ) ? (float) $added_product_with_qty[ $crt_prd_or_var_id ] + $current_prd_qty : $current_prd_qty;

	}

	$main_prd_qty 				= (float) $added_product_with_qty[ $product_or_variation_id ];

	if ( count( $product_detail ) > 1 ) {

		if ( isset( $product_detail['min_value'] ) && ! empty( $product_detail['min_value'] ) &&  $main_prd_qty < $product_detail['min_value'] ) {

			if ( empty( get_option('ct_rbpaqp_mmq_min_qty_error_message') ) ) {
				update_option('ct_rbpaqp_mmq_min_qty_error_message','Please select atleast {min_qty} quantity to add product in your cart');
			}

			$min_error_message 	= str_replace('{min_qty}', $product_detail['min_value'] ,get_option('ct_rbpaqp_mmq_min_qty_error_message'));
			wc_add_notice($min_error_message,'error');
			$passed 			= false;

		}

		if ( isset( $product_detail['max_value'] ) && ! empty( $product_detail['max_value'] ) &&  $main_prd_qty > $product_detail['max_value'] ) {

			if ( empty( get_option('ct_rbpaqp_mmq_max_qty_error_message') ) ) {
				update_option('ct_rbpaqp_mmq_max_qty_error_message','Please select atleast {max_qty} quantity to add product in your cart');
			}
			$max_error_message 	= str_replace('{max_qty}', $product_detail['max_value'] ,get_option('ct_rbpaqp_mmq_max_qty_error_message'));
			wc_add_notice($max_error_message,'error');
			$passed 			= false;

		}


	}

	return $passed;
}

function cloud_mmq_woocommerce_update_cart_validation( $passed, $cart_item_key, $values, $quantity ) {

	if ( empty( get_option('ct_rbpaqp_mmq_min_qty_error_message_crt_pg') ) ) {

		update_option('ct_rbpaqp_mmq_min_qty_error_message_crt_pg','Please select atleast {min_qty} quantity of {product_name} to update product quantity');
	}

	if ( empty( get_option('ct_rbpaqp_mmq_max_qty_error_message_crt_pg') ) ) {

		update_option('ct_rbpaqp_mmq_max_qty_error_message_crt_pg','Please select atleast {max_qty} quantity of {product_name} to update product quantity');
	}

	foreach ( WC()->cart->get_cart() as $key => $value ) {

		if ( $value['key'] == $cart_item_key ) {

			$product_or_variation_id= isset( $value['variation_id'] ) && $value['variation_id'] >= 1 ? $value['variation_id'] :  $value['product_id'];

			$sold_individually 		= get_post_meta( $value['product_id'], '_sold_individually', true);
			if ( 'yes' == $sold_individually ) { 
				continue;
			}

			$product_detail 		= (array) ct_rbpaqp_get_product_min_max_quantity( $product_or_variation_id );

			if ( isset( $product_detail['active_rule_id'] ) ) {
				$active_rule_id 				= $product_detail['active_rule_id'];
				$add_purchas_item_qty_or_not  	= get_post_meta( $active_rule_id,'ct_rbpaqp_mmq_add_purchas_item_qty_or_not' , true );

				if ( !empty( $add_purchas_item_qty_or_not ) ) {

					$order_status_purchas_item_qty  	= !empty( get_post_meta( $active_rule_id,'ct_rbpaqp_mmq_order_status_purchas_item_qty' , true ) ) ? (array) get_post_meta( $active_rule_id,'ct_rbpaqp_mmq_order_status_purchas_item_qty' , true ) : ['any'];

					$product_id = $product_or_variation_id;

					$order_ids 	= array();

					$args 		= array(
						'post_type'         => 'shop_order',
						'post_status'       => $order_status_purchas_item_qty,
						'meta_query'        => array(
							array(
			            'key'     	=> '_customer_user', // This is the customer user key
			            'value'   	=> get_current_user_id(), // Current user's ID
			            'compare' 	=> '=',
			        ),
						),
						'fields' 			=> 'ids',
					);

					$orders = get_posts($args);


					foreach ($orders as $order_id) {

						$order 		= wc_get_order($order_id);

						foreach ($order->get_items() as $item) {

							$order_prod_or_var_id 		= $item->get_variation_id() && $item->get_variation_id() >= 1 ? $item->get_variation_id() : $item->get_product_id();

							if ( $order_prod_or_var_id == $product_or_variation_id ) {			            

								$quantity 				+=  (float) $item->get_quantity();

							}
						}
					}
				}
			}

			if ( isset( $product_detail['min_value'] ) && ! empty( $product_detail['min_value'] ) &&  $quantity < (float) $product_detail['min_value'] ) {

				$product 			= wc_get_product( $product_or_variation_id );

				$min_error_message 	= str_replace( '{min_qty}', $product_detail['min_value'] ,get_option('ct_rbpaqp_mmq_min_qty_error_message_crt_pg') );
				$min_error_message 	= str_replace('{product_name}', $product->get_name() , $min_error_message);

				wc_add_notice($min_error_message,'error');
				$passed 			= false;
				break;
			}

			if ( isset( $product_detail['max_value'] ) && ! empty( $product_detail['max_value'] ) &&  $quantity > (float) $product_detail['max_value'] ) {

				$product 			= wc_get_product( $product_or_variation_id );
				$max_error_message 	= str_replace('{max_qty}',$product_detail['max_value'],get_option('ct_rbpaqp_mmq_max_qty_error_message_crt_pg'));
				$max_error_message 	= str_replace('{product_name}', $product->get_name() , $max_error_message);

				wc_add_notice($max_error_message,'error');
				$passed 			= false;
				break;
			}


		}
	}

	return $passed;
}
