<?php
if (! defined('WPINC') ) {
	die;
}


add_action('add_meta_boxes','ct_rbpaqp_min_max_qty_add_meta_boxes');

function ct_rbpaqp_min_max_qty_add_meta_boxes() {

	add_meta_box(
		'ct-rbp-product-cat-restrictions',
		esc_html__('Restrictions', 'cloud_tech_rbpaqpfw'),
		'ct_rbpaqp_mmq_product_cat_restrictionssss',
		'ct_min_max_qty_role',
		'normal',
		'high'
	);
	add_meta_box(
		'ct-rbp-quantity-restrictions',
		esc_html__('Quantity Restrictions', 'cloud_tech_rbpaqpfw'),
		'ct_rbpaqp_mmq_restrictionss',
		'ct_min_max_qty_role',
		'normal',
		'high'
	);
}

function ct_rbpaqp_mmq_product_cat_restrictionssss( ) {
	ct_rbpaqp_mmq_product_cat_restrictions( get_the_ID() );
}
function ct_rbpaqp_mmq_restrictionss( ) {
	ct_rbpaqp_mmq_restriction( get_the_ID() );

}

function ct_rbpaqp_mmq_product_cat_restrictions($post_id) { 
	global $post;

	$tag 			= get_terms('product_tag');

	?>
	<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">
		<?php if ( ! wc_get_product( $post_id )  ){  ?>
			<tr>
				<th>
					<?php echo esc_html__('Product', 'cloud_tech_rbpaqpfw'); ?>
				</th>
				<td>
					<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_mmq_inlcuded_products[]"  multiple style="width: 40%;" class=" ct_rbpaqp_mmq_inlcuded_products ct_rbpaqp_product_search">

						<?php foreach ((array) get_post_meta( $post_id , 'ct_rbpaqp_mmq_inlcuded_products' , true ) as $current_product_id) { ?>
							<?php 
							$product = wc_get_product( $current_product_id );
							if ( $product  ) { ?>
								<option value="<?php echo esc_attr( $current_product_id ); ?>" selected >
									<?php echo esc_attr( $product->get_name() ); ?>
								</option>
							<?php }
						} ?>
					</select>
				</td>
			</tr>

			<tr>
				<th>
					<?php echo esc_html__('Categories', 'cloud_tech_rbpaqpfw'); ?>
				</th>
				<td>
					<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_mmq_inlcuded_category[]"  multiple   style="width: 40%;" class="ct-rbpaqp-cat-search">
						
						<?php foreach ((array) get_post_meta( $post_id , 'ct_rbpaqp_mmq_inlcuded_category' , true ) as $current_cat_id) {  

							if ( ! $current_cat_id ) {
								continue;
							}

							$cat = get_term( $current_cat_id );

							if ( $cat  ) { ?>
								<option value="<?php echo esc_attr( $current_cat_id ); ?>" selected >
									<?php echo esc_attr(  $cat->name ); ?>
								</option>
							<?php } ?>

						<?php } ?>
					</select>
				</td>
			</tr>

			<tr>
				<th>
					<?php echo esc_html__('Tag', 'cloud_tech_rbpaqpfw'); ?>
				</th>
				<td>
					<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_mmq_inlcuded_tag[]" multiple style="width: 40%;" class="ct-rbpaqp-live-search">

						<?php foreach ($tag as $tag_obj): ?>
							<?php if ( is_object( $tag_obj ) && $tag_obj->term_id ): ?>
								<option value="<?php echo esc_attr( $tag_obj->term_id ); ?>"  <?php if (  in_array(  $tag_obj->term_id , (array) get_post_meta( $post_id , 'ct_rbpaqp_mmq_inlcuded_tag' , true )) ){  ?> selected <?php } ?> >
									<?php echo esc_attr( $tag_obj->name ); ?>
								</option>
							<?php endif ?>
						<?php endforeach ?>

					</select>
				</td>
			</tr>

		<?php } ?>
		<tr>
			<th>
				<?php echo esc_html__('Purchas Item Quantity Restrictions', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<input type="checkbox" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_mmq_add_purchas_item_qty_or_not" class="ct_rbpaqp_mmq_add_purchas_item_qty_or_not" value="yes" <?php if ( !empty( get_post_meta( $post_id ,'ct_rbpaqp_mmq_add_purchas_item_qty_or_not',true ) ) ): ?>
					checked
				<?php endif ?>>
				<p>
					<i>
						<?php echo esc_html__('Durring Update cart and add to cart any product do you want to sum purchase item quantity for this checking max quantity limit.If yes enable checkbox', 'cloud_tech_rbpaqpfw'); ?>
					</i>
				</p>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Order Statuses', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_mmq_order_status_purchas_item_qty[]" multiple class="ct-rbpaqp-live-search ct_rbpaqp_mmq_order_status_purchas_item_qty" style="width: 100%;" >
					<?php foreach ( wc_get_order_statuses() as $key => $value): ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php if ( in_array( $key , (array) get_post_meta( $post_id ,'ct_rbpaqp_mmq_order_status_purchas_item_qty',true) ) ){ ?> selected <?php } ?> >
							<?php echo esc_attr( $value ); ?>
						</option>
					<?php endforeach ?>
				</select>
				<p>
					<i>
						<?php echo esc_html__('Select which order statuses for which you want to sum product quantity during cart update and add to cart product.', 'cloud_tech_rbpaqpfw'); ?>
					</i>
				</p>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Date Range', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<table>
					<tr>
						<td>
						<?php echo esc_html__('Start Date', 'cloud_tech_rbpaqpfw'); ?>
						</td>
						<td>
						<input type="date" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_mmq_start_date" value="<?php echo esc_attr( get_post_meta( $post_id , 'ct_rbpaqp_mmq_start_date' , true ) ); ?>">
						</td>
						<td>
						<?php echo esc_html__('End Date', 'cloud_tech_rbpaqpfw'); ?>
						</td>
						<td>
						<input type="date" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_mmq_end_date" value="<?php echo esc_attr( get_post_meta( $post_id , 'ct_rbpaqp_mmq_end_date' , true ) ); ?>">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Time Range', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<ul style="list-style-type:none;display: inline-flex;margin: 2px;">
					<li>
						<?php echo esc_html__('Start Time', 'cloud_tech_rbpaqpfw'); ?>
					</li>
					<li>
						<input type="time" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_start_time" value="<?php echo esc_attr( get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_start_time' , true ) ); ?>">
					</li>
					<li>
						<?php echo esc_html__('End Time', 'cloud_tech_rbpaqpfw'); ?>
					</li>
					<li>
						<input type="time" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_end_time" value="<?php echo esc_attr( get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_end_time' , true ) ); ?>">
					</li>
				</ul>
			</td>
		</tr>

		<tr>
			<th>
				<?php echo esc_html__('Select Days', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_mmq_inlcuded_days[]" multiple style="width: 40%;" class="ct-rbpaqp-live-search">

					<?php 
					$daysOfWeek = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday',);
					foreach ($daysOfWeek as $days_name): ?>

						<option value="<?php echo esc_attr( $days_name ); ?>" <?php if (  in_array( $days_name , (array) get_post_meta( $post_id , 'ct_rbpaqp_mmq_inlcuded_days' , true )) ): ?>selected<?php endif ?>>
							<?php echo esc_attr( $days_name ); ?>
						</option>

					<?php endforeach ?>

				</select>
			</td>
		</tr>

	</table>
	<?php

}

function ct_rbpaqp_mmq_restriction($post_id) {
	$country_obj 			= new WC_Countries();

	$min_max_rules  		= (array) get_post_meta( $post_id, 'ct_rbpaqp_mmq_rules' ,true );

	?>
	<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">

		<thead>
			<th><?php echo esc_html__('Enable Setting For Role','cloud_tech_rbpaqpfw'); ?></th>
			<th><?php echo esc_html__('Min Qty','cloud_tech_rbpaqpfw'); ?></th>
			<th><?php echo esc_html__('Max Qty','cloud_tech_rbpaqpfw'); ?></th>
			<th><?php echo esc_html__('Step','cloud_tech_rbpaqpfw'); ?></th>
			<th><?php echo esc_html__('Select Countries','cloud_tech_rbpaqpfw'); ?></th>
		</thead>
		<tbody>

			<?php foreach (ct_b2b_get_set_roles() as $role_key => $role) {  

				$setting 					= isset($min_max_rules[$role_key]) && isset($min_max_rules[$role_key]['enable_setting']) && !empty($min_max_rules[$role_key]['enable_setting'] ) ?'yes' : '' ;
				$selected_min_qty 			= isset($min_max_rules[$role_key]) && isset($min_max_rules[$role_key]['min_qty']) && !empty($min_max_rules[$role_key]['min_qty'] ) ? $min_max_rules[$role_key]['min_qty'] : '';
				$selected_max_qty 			= isset($min_max_rules[$role_key]) && isset($min_max_rules[$role_key]['max_qty']) && !empty($min_max_rules[$role_key]['max_qty'] ) ? $min_max_rules[$role_key]['max_qty'] : '';
				$selected_step 				= isset($min_max_rules[$role_key]) && isset($min_max_rules[$role_key]['step']) && !empty($min_max_rules[$role_key]['step'] ) ? $min_max_rules[$role_key]['step'] : '';
				$selected_country 			= isset($min_max_rules[$role_key]) && isset($min_max_rules[$role_key]['countries']) && !empty($min_max_rules[$role_key]['countries'] ) ? (array) $min_max_rules[$role_key]['countries'] :[];


				?>

				<tr>

					<th>
						<input type="checkbox" value="yes" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_mmq_rules[<?php echo esc_attr($role_key); ?>][enable_setting]" <?php if ( !empty( $setting ) ): ?>
						checked<?php endif ?> >
						<?php echo esc_attr( $role ); ?>
					</th>
					<td>
						<input type="number" min="0.1" step="any" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_mmq_rules[<?php echo esc_attr($role_key); ?>][min_qty]" value="<?php echo esc_attr( $selected_min_qty ); ?>">
					</td>
					<td>
						<input type="number" min="0.1" step="any" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_mmq_rules[<?php echo esc_attr($role_key); ?>][max_qty]" value="<?php echo esc_attr( $selected_max_qty ); ?>">
					</td>
					<td>
						<input type="number" min="0.1" step="any" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_mmq_rules[<?php echo esc_attr($role_key); ?>][step]" value="<?php echo esc_attr( $selected_step ); ?>">
					</td>


					<td>
						<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_mmq_rules[<?php echo esc_attr($role_key); ?>][countries][]" multiple style="width: 100%;" class="ct-rbpaqp-live-search">

							<?php foreach ($country_obj->get_countries() as $country_key => $country): ?>

								<option value="<?php echo esc_attr( $country_key ); ?>" <?php if (  in_array( $country_key , (array) 
								$selected_country ) ): ?>selected<?php endif ?> >
								<?php echo esc_attr( $country ); ?>
							</option>

						<?php endforeach ?>

					</select>
				</td>

			</tr>


		<?php } ?>

	</tbody>


</table>
<?php


}