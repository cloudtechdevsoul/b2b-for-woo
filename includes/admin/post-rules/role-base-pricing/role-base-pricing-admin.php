<?php
if (! defined('ABSPATH') ) {
	die;
}
add_action('add_meta_boxes','ct_rbpaqp_add_meta_boxes');

function ct_rbpaqp_add_meta_boxes() {

	add_meta_box(
		'ct-rbp-product-cat-restrictions',
		esc_html__('Restrictions', 'cloud_tech_rbpaqpfw'),
		'ct_rbpaqp_product_cat_restrictionsss',
		'ct_role_base_pricing',
		'normal',
		'high'
	);

	add_meta_box(
		'ct-rbp-customer-based',
		esc_html__('Customer Based', 'cloud_tech_rbpaqpfw'),
		'ct_rbpaqp_customer_baseee',
		'ct_role_base_pricing',
		'normal',
		'high'
	);

	add_meta_box(
		'ct-rbp-role-base',
		esc_html__('Role Base', 'cloud_tech_rbpaqpfw'),
		'ct_rbpaqp_role_baseee',
		'ct_role_base_pricing',
		'normal',
		'high'
	);


}

function ct_rbpaqp_product_cat_restrictionsss(){
	ct_rbpaqp_product_cat_restrictions(get_the_ID());
}

function ct_rbpaqp_customer_baseee(){
	ct_rbpaqp_customer_base(get_the_ID());
}

function ct_rbpaqp_role_baseee(){
	ct_rbpaqp_role_base(get_the_ID());
}

function ct_rbpaqp_product_cat_restrictions($post_id) {
	global $post;

	$country_obj 	= new WC_Countries();
	$tag 			= get_terms('product_tag');

	?>
	<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">

		<?php if ( ! wc_get_product( $post_id )  ): ?>

			<tr>
				<th>
					<?php echo esc_html__('Product', 'cloud_tech_rbpaqpfw'); ?>
				</th>
				<td>
					<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_rbp_inlcuded_products[]"  multiple style="width: 40%;" class="ct_rbpaqp_rbp_inlcuded_products ct_rbpaqp_product_search">

						<?php foreach ((array) get_post_meta( $post_id , 'ct_rbpaqp_rbp_inlcuded_products' , true ) as $current_product_id)
						{  ?>
							<?php 
							$product = wc_get_product( $current_product_id );
							if ( $product  ){  ?>
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
					<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_rbp_inlcuded_category[]"  multiple   style="width: 40%;" class="ct-rbpaqp-cat-search">

						<?php foreach ((array) get_post_meta( $post_id , 'ct_rbpaqp_rbp_inlcuded_category' , true ) as $current_cat_id) { 
							if ( ! $current_cat_id ) {
								continue;
							}
							$cat = get_term( $current_cat_id );
							if ( $cat  ) { ?>
								<option value="<?php echo esc_attr( $current_cat_id ); ?>" selected >
									<?php echo esc_attr(  $cat->name ); ?>
								</option>
							<?php } 
						} ?>
					</select>
				</td>
			</tr>

			<tr>
				<th>
					<?php echo esc_html__('Tag', 'cloud_tech_rbpaqpfw'); ?>
				</th>
				<td>
					<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_rbp_inlcuded_tag[]" multiple style="width: 40%;" class="ct-rbpaqp-live-search">

						<?php foreach ($tag as $tag_obj): ?>
							<?php if ( is_object( $tag_obj ) && $tag_obj->term_id ): ?>
								<option value="<?php echo esc_attr( $tag_obj->term_id ); ?>"  <?php if (  in_array(  $tag_obj->term_id , (array) get_post_meta( $post_id , 'ct_rbpaqp_rbp_inlcuded_tag' , true )) ): ?>
								selected						
								<?php endif ?> >
								<?php echo esc_attr( $tag_obj->name ); ?>
							</option>
						<?php endif ?>
					<?php endforeach ?>

				</select>
			</td>
		</tr>
	<?php endif ?>

	<tr>
		<th>
			<?php echo esc_html__('Country', 'cloud_tech_rbpaqpfw'); ?>
		</th>
		<td>
			<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_rbp_inlcuded_country[]" multiple style="width: 40%;" class="ct-rbpaqp-live-search">

				<?php foreach ($country_obj->get_countries() as $country_key => $country): ?>

					<option value="<?php echo esc_attr( $country_key ); ?>" <?php if (  in_array( $country_key , (array) get_post_meta( $post_id , 'ct_rbpaqp_rbp_inlcuded_country' , true )) ): ?>
					selected						
					<?php endif ?> >
					<?php echo esc_attr( $country ); ?>
				</option>

			<?php endforeach ?>

		</select>
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

</table>
<?php
}

function ct_rbpaqp_customer_base($post_id) {
	global $post;
	$all_post_id 	= get_posts([
		'post_type' 		=> 'ct_set_role_for_cbp',
		'post_status' 		=> 'publish',
		'post_parent' 		=> get_the_ID(),
		'fields' 			=> 'ids',
		'posts_per_page' 	=> -1,
		'orderby'			=> 'menu_order',
		'order' 			=> 'ASC',
	]);

	?>
	<div class="ct-rbpaqp-customers-base <?php echo esc_attr($post_id); ?>ct-rbpaqp-customers-base">
		<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">
			<thead>
				<tr>
					<th><?php echo esc_html__('Select Customer','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Discount On','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Discount Type','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Discount Value','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Min Qty','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Max Qty','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Start / End Date','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Days','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Priority','cloud_tech_rbpaqpfw'); ?></th>
				</tr>
			</thead>
			<tbody>

				<?php foreach ($all_post_id as $current_post_id) {

					echo wp_kses_post(get_role_base_and_customer_base_pricing( $current_post_id , 'customer' ));					
				} ?>
				
			</tbody>
		</table>
		<i data-add_with="customers-base" data-post_ID="<?php echo esc_attr( $post_id ); ?>" class="ct-rbpaqp-add-new-customer-or-role-base-rbp button button-primary button-large"><?php echo esc_html__('Add New','cloud_tech_rbpaqpfw'); ?></i>
	</div>
	<?php

}

function ct_rbpaqp_role_base($post_id) {
	global $post;


	$all_post_id 	= get_posts([
		'post_type' 		=> 'ct_set_role_for_rbp',
		'post_status' 		=> 'publish',
		'post_parent' 		=> get_the_ID(),
		'fields' 			=> 'ids',
		'posts_per_page' 	=> -1,
		'orderby'			=> 'menu_order',
		'order' 			=> 'ASC',
	]);
	

	?>
	<div class="ct-rbpaqp-role-base <?php echo esc_attr($post_id); ?>ct-rbpaqp-role-base">
		<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">
			<thead>
				<tr>
					<th><?php echo esc_html__('Select User Role','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Discount On','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Discount Type','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Discount Value','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Min Qty','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Max Qty','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Start / End Date','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Days','cloud_tech_rbpaqpfw'); ?></th>
					<th><?php echo esc_html__('Priority','cloud_tech_rbpaqpfw'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($all_post_id as $current_post_id) {

					echo wp_kses_post(get_role_base_and_customer_base_pricing( $current_post_id , 'user_role' ));					
				} ?>
			</tbody>
		</table>
		<i data-add_with="role-base" data-post_ID="<?php echo esc_attr( $post_id ); ?>" class="ct-rbpaqp-add-new-customer-or-role-base-rbp button button-primary button-large"><?php echo esc_html__('Add New','cloud_tech_rbpaqpfw'); ?></i>
	</div>
	<?php

}
