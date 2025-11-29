<?php

add_shortcode('cloud_tech_quote_table','cloud_tech_quote_table');

function cloud_tech_quote_table() {
	?>
	<div class="ct-quote-table-div">

		<table class="wp-list-table widefat fixed striped table-view-list ct-quote-table" >
				<thead>
					<tr>
						<th><input type="checkbox" class="ct_rfq_select_all_checkbox"></th>
						<th><?php echo esc_html__('Thumbnail', 'cloud_tech_rfq');?></th>
						<th><?php echo esc_html__('Product', 'cloud_tech_rfq');?></th>
						<?php if( ! empty( get_option('ct_rfq_qts_show_categories') ) ){ ?>
							<th><?php echo esc_html__('Category', 'cloud_tech_rfq');?></th>
						<?php }
						if( ! empty( get_option('ct_rfq_qts_product_sku') ) ){ ?>
							<th><?php echo esc_html__('Sku', 'cloud_tech_rfq');?></th>
						<?php } ?>
						<th><?php echo esc_html__('Price', 'cloud_tech_rfq');?></th>
						<?php if( ! empty( get_option('ct_rfq_qts_product_qty_box') ) ){ ?>
							<th><?php echo esc_html__('Quantity', 'cloud_tech_rfq');?></th>
						<?php }
						if( ! empty( get_option('ct_rfq_qts_show_description') ) ){ ?>
							<th><?php echo esc_html__('Description', 'cloud_tech_rfq');?></th>
						<?php }?>

						<th><?php echo esc_html__('Stock Status', 'cloud_tech_rfq');?></th>

						<th><?php echo esc_html__('Action', 'cloud_tech_rfq');?></th>
					</tr>

				</thead>
				<tbody>
					
						<?php

						$ct_rfq_quote_rule = get_posts( ['post_type' => 'ct-rfq-quote-rule','post_status' => 'publish','post_per_page' => -1,'fields' => 'ids',] );
						
						foreach ( $ct_rfq_quote_rule as $current_rule_id) {
							
							$selected_products 		= 	(array) get_post_meta( $current_rule_id , 'ct_rfq_inlcuded_products',true);
							$selected_products 		= 	ct_rfq_custom_array_filter( $selected_products );

							$selected_category 		= 	(array) get_post_meta( $current_rule_id , 'ct_rfq_inlcuded_category',true);
							$selected_category 		= 	ct_rfq_custom_array_filter( $selected_category );

							$selected_tag 			= 	(array) get_post_meta( $current_rule_id , 'ct_rfq_inlcuded_tag',true);
							$selected_tag 			= 	ct_rfq_custom_array_filter( $selected_tag );

							$selected_country 		= 	(array) get_post_meta( $current_rule_id , 'ct_rfq_inlcuded_country',true);
							$selected_country 		= 	ct_rfq_custom_array_filter( $selected_country );

							$selected_customers 	= 	(array) get_post_meta( $current_rule_id , 'ct_rfq_inlcuded_customers',true);
							$selected_customers 	= 	ct_rfq_custom_array_filter( $selected_customers );

							$selected_role 			= 	(array) get_post_meta( $current_rule_id , 'ct_rfq_inlcuded_role',true);
							$selected_role 			= 	ct_rfq_custom_array_filter( $selected_role );

							if ( count( $selected_role ) 		>=1   && !in_array( ct_rfq_mmq_get_current_user_role() , $selected_role ) ) {
								continue;	
							}

							if ( count( $selected_customers ) 	>=1   && !in_array( get_current_user_id() , $selected_customers ) ) {
								continue;	
							}

							if ( count( $selected_country ) 	>=1   && !in_array( ct_rfq_get_current_user_country() , $selected_country ) ) {
								continue;	
							}

							if( ( count( $selected_products  ) < 1 || empty(  $selected_products ) ) && ( count( $selected_category ) < 1 || empty( $selected_category ) ) && ( count( $selected_tag ) < 1 || empty( $selected_tag ) ) ){
								$selected_products 				= wc_get_products( [ 'status'=>'publish' , 'limit' => -1,'return' => 'ids' ]);
							}

							if( count( $selected_category ) >= 1 ){
								$category_product 	= ct_get_product_of_cat( $selected_category ); 
								$selected_products 	= array_merge( $selected_products , ct_get_product_of_cat( $selected_category ) );
							}

							if( count( $selected_tag ) 		>= 1 ){
								$selected_products 	= array_merge( $selected_products , ct_get_product_of_tag( $selected_tag ) );
							}

							
							foreach ($selected_products as $current_product_id) {
								
								$product 						= wc_get_product( $current_product_id );
								if( ! $product || ! $product->is_purchasable() ) {
									continue;
								}
								$product_permalink 				= wc_placeholder_img_src();

								if ( ! empty( get_the_post_thumbnail_url($current_product_id, 'post-thumbnail') ) ) {
									$product_permalink 			= get_the_post_thumbnail_url($current_product_id, 'post-thumbnail');
								}

								$stock_status 				= wc_get_stock_html($product);	 
								if( ! $product->is_in_stock() && empty( get_option('ct_rfq_qts_show_out_of_stock_product_stock_status') ) ){
									$stock_status 			= '';
								}

								if( ! $product->is_in_stock() && ! empty( get_post_meta( $current_rule_id, 'ct_rfq_show_request_a_quote_btn_with_outofstock',true) ) ){
									continue;
								
								}
								
								if ( str_contains (get_post_meta( $current_rule_id , 'ct_rfq_hide_price_or_replace_text' ,true ) , 'hide')  ) {
									$price                  = '';

								}else if ( str_contains (get_post_meta( $current_rule_id , 'ct_rfq_hide_price_or_replace_text' ,true ) , 'replace_text') ) {
									$price                  = get_post_meta( $current_rule_id , 'ct_rfq_replace_price_text' ,true );

								} else {
									$price 					= wc_price($product->get_price());
								}

								?>
								<tr>
									<td><input type="checkbox" data-rule_id="<?php echo esc_attr( $current_rule_id ); ?>" name="ct_rfq_cart_page_button" class="ct_rfq_cart_page_button" data-product_id="<?php echo esc_attr( $current_product_id); ?>" /></td>
									<td class="product-thumbnail"><img style="width:50px;height:50px;" src="<?php echo esc_url( $product_permalink ); ?>"></td>
									<td class="product-name"><a href="<?php echo esc_url( get_permalink( $current_product_id ) ); ?>" target="_black" ><?php echo esc_attr( $product->get_name() );?></a></td>
									<?php if( ! empty( get_option('ct_rfq_qts_show_categories') ) ){
										
										$product_categories = get_the_terms($product->get_id(), 'product_cat');

										?><td><?php 
										
											foreach ($product_categories as $cat_obj) {
												if ( is_object($cat_obj) && $cat_obj->term_id ) {
													?><a href="<?php echo esc_url( get_term_link ( $cat_obj ,'product_cat' ) );  ?>"><?php echo esc_attr( $cat_obj->name ); ?></a><?php
												}
											}
										
										?></td>
									<?php }?>
									<?php if( ! empty( get_option('ct_rfq_qts_product_sku') ) ){ ?>
										<td><?php echo wp_kses_post($product->get_sku());?></td>
									<?php }?>

									<td><?php echo wp_kses_post( $price );?></td>

									<?php if( ! empty( get_option('ct_rfq_qts_product_qty_box') ) ){ ?>
										<td><div class="quantity"><input type="number" name="qty" class="qty qty<?php echo esc_attr( $current_product_id ); ?>" /></div></td>
									<?php }
									if( ! empty( get_option('ct_rfq_qts_show_description') ) ){ ?>
										<td><?php echo wp_kses_post(  substr($product->get_short_description(),0, get_option('ct_rfq_qts_description_total_words') ? : 500 ));?></td>
									<?php } ?>
									<td><?php echo wp_kses_post( $stock_status );?></td>
										
									<td><i class="button primary-button ct-rfq-cart-page-button" data-rule_id="<?php echo esc_attr( $current_rule_id ); ?>" data-product_id="<?php echo esc_attr( $current_product_id ); ?>" ><?php echo esc_attr (get_post_meta($current_rule_id ,'ct_rfq_request_a_quote_button_text' , true ) ? get_post_meta($current_rule_id ,'ct_rfq_request_a_quote_button_text' , true ) : 'Add to Quote') ?> </i></td>	
								</tr>

								<?php
							}
						}
						?>
				</tbody>
		</table>
	</div>
	<?php
}