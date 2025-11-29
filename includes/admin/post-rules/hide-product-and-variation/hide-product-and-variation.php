<?php
if (! defined('WPINC') ) {
	die;
}


add_action('add_meta_boxes','ct_rbpaqp_hide_product_And_variation');

function ct_rbpaqp_hide_product_And_variation() {

	add_meta_box(
		'ct-rbp-product-cat-restrictions',
		esc_html__('Restrictions', 'cloud_tech_rbpaqpfw'),
		'ct_rbpaqp_hpav_product_cat_restrictionssss',
		'ct_hide_prdct_nd_var',
		'normal',
		'high'
	);

	add_meta_box(
		'ct-rbp-quantity-restrictions',
		esc_html__('Quantity Restrictions', 'cloud_tech_rbpaqpfw'),
		'ct_rbpaqp_hpav_restrictionsss',
		'ct_hide_prdct_nd_var',
		'normal',
		'high'
	);

}

function ct_rbpaqp_hpav_product_cat_restrictionssss() {
	ct_rbpaqp_hpav_product_cat_restrictions( get_the_ID() );	
}

function ct_rbpaqp_hpav_restrictionsss() {

	ct_rbpaqp_hpav_restriction( get_the_ID() );	
}

function ct_rbpaqp_hpav_product_cat_restrictions($post_id) {

	$country_obj 	= new WC_Countries();
	$tag 			= get_terms('product_tag');

	?>
	<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">

		<?php if ( ! wc_get_product( $post_id )  ){ ?>

			<tr>
				<th>
					<?php echo esc_html__('Product', 'cloud_tech_rbpaqpfw'); ?>
				</th>
				<td>
					<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpav_inlcuded_products[]"  multiple style="width: 40%;" class="ct_rbpaqp_hpav_inlcuded_products ct_rbpaqp_product_search">

						<?php foreach ((array) get_post_meta( $post_id , 'ct_rbpaqp_hpav_inlcuded_products' , true ) as $current_product_id){ 

							$product = wc_get_product( $current_product_id );
							if ( $product  ) {  ?>
								<option value="<?php echo esc_attr( $current_product_id ); ?>" selected >
									<?php echo esc_attr( $product->get_name() ); ?>
								</option>
								<?php 
							}
						} ?>

					</select>
				</td>
			</tr>

			<tr>
				<th>
					<?php echo esc_html__('Categories', 'cloud_tech_rbpaqpfw'); ?>
				</th>
				<td>
					<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpav_inlcuded_category[]"  multiple   style="width: 40%;" class="ct-rbpaqp-cat-search">

						<?php foreach ((array) get_post_meta( $post_id , 'ct_rbpaqp_hpav_inlcuded_category' , true ) as $current_cat_id): 
						if ( ! $current_cat_id ) {
								continue;
							}
							
						$cat = get_term( $current_cat_id );
						if ( $cat  ): ?>
							<option value="<?php echo esc_attr( $current_cat_id ); ?>" selected >
								<?php echo esc_attr(  $cat->name ); ?>
							</option>
						<?php endif ?>

					<?php endforeach ?>
				</select>
			</td>
		</tr>

		<tr>
			<th>
				<?php echo esc_html__('Tag', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpav_inlcuded_tag[]" multiple style="width: 40%;" class="ct-rbpaqp-live-search">

					<?php foreach ($tag as $tag_obj): ?>
						<?php if ( is_object( $tag_obj ) && $tag_obj->term_id ): ?>
							<option value="<?php echo esc_attr( $tag_obj->term_id ); ?>"  <?php if (  in_array(  $tag_obj->term_id , (array) get_post_meta( $post_id , 'ct_rbpaqp_hpav_inlcuded_tag' , true )) ): ?>
							selected						
							<?php endif ?> >
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
		<?php echo esc_html__('Country', 'cloud_tech_rbpaqpfw'); ?>
	</th>
	<td>
		<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpav_inlcuded_country[]" multiple style="width: 40%;" class="ct-rbpaqp-live-search">

			<?php foreach ($country_obj->get_countries() as $country_key => $country): ?>

				<option value="<?php echo esc_attr( $country_key ); ?>" <?php if (  in_array( $country_key , (array) get_post_meta( $post_id , 'ct_rbpaqp_hpav_inlcuded_country' , true )) ): ?>
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
		<?php echo esc_html__('Roles', 'cloud_tech_rbpaqpfw'); ?>
	</th>
	<td>
		<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpav_inlcuded_role[]" multiple style="width: 40%;" class="ct-rbpaqp-live-search">

			<?php foreach (ct_b2b_get_set_roles() as $role_key => $role): ?>

				<option value="<?php echo esc_attr( $role_key ); ?>"  <?php if (  in_array( $role_key , (array) get_post_meta( $post_id , 'ct_rbpaqp_hpav_inlcuded_role' , true )) ): ?>
				selected						
				<?php endif ?> >
				<?php echo esc_attr( $role ); ?>
			</option>

		<?php endforeach ?>

	</select>
</td>
</tr>

<tr>
	<th>
		<?php echo esc_html__('Date Range', 'cloud_tech_rbpaqpfw'); ?>
	</th>
	<td>
		<ul style="list-style-type:none;display: inline-flex;margin: 2px;">
			<li>
				<?php echo esc_html__('Start Date', 'cloud_tech_rbpaqpfw'); ?>
			</li>
			<li>
				<input type="date" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpav_start_date" value="<?php echo esc_attr( get_post_meta( $post_id , 'ct_rbpaqp_hpav_start_date' , true ) ); ?>">
			</li>
			<li>
				<?php echo esc_html__('End Date', 'cloud_tech_rbpaqpfw'); ?>
			</li>
			<li>
				<input type="date" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpav_end_date" value="<?php echo esc_attr( get_post_meta( $post_id , 'ct_rbpaqp_hpav_end_date' , true ) ); ?>">
			</li>
		</ul>
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
				<input type="time" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpav_start_time" value="<?php echo esc_attr( get_post_meta( $post_id , 'ct_rbpaqp_hpav_start_time' , true ) ); ?>">
			</li>
			<li>
				<?php echo esc_html__('End Time', 'cloud_tech_rbpaqpfw'); ?>
			</li>
			<li>
				<input type="time" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpav_end_time" value="<?php echo esc_attr( get_post_meta( $post_id , 'ct_rbpaqp_hpav_end_time' , true ) ); ?>">
			</li>
		</ul>
	</td>
</tr>

<tr>
	<th>
		<?php echo esc_html__('Select Days', 'cloud_tech_rbpaqpfw'); ?>
	</th>
	<td>
		<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpav_inlcuded_days[]" multiple style="width: 40%;" class="ct-rbpaqp-live-search">

			<?php 
			$daysOfWeek = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday',);
			foreach ($daysOfWeek as $days_name): ?>

				<option value="<?php echo esc_attr( $days_name ); ?>" <?php if (  in_array( $days_name , (array) get_post_meta( $post_id , 'ct_rbpaqp_hpav_inlcuded_days' , true )) ): ?>
				selected						
				<?php endif ?>>
				<?php echo esc_attr( $days_name ); ?>
			</option>

		<?php endforeach ?>

	</select>
</td>
</tr>

</table>
<?php



}

function ct_rbpaqp_hpav_restriction($post_id) {

	?>
	<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">

		<tr>
			<th>
				<?php echo esc_html__('Hide Product', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<input type="checkbox" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpav_hide_product" class="ct_rbpaqp_hpav_hide_product" value="yes" <?php if ( get_post_meta( $post_id , 'ct_rbpaqp_hpav_hide_product' , true ) ): ?>
				checked
				<?php endif ?>>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Base On', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpav_hide_product_base_on" class="ct_rbpaqp_hpav_hide_product_base_on">
					<option value="immigiatly" <?php echo esc_attr( 'immigiatly' == get_post_meta(get_the_ID() ,'ct_rbpaqp_hpav_hide_product_base_on',true) ?'selected' : '' ); ?>>
						<?php echo esc_html__('Immigiatly','ct_rbpaqp_hpav_hide_product'); ?>
					</option>
					<option value="out_of_stock" <?php echo esc_attr( 'out_of_stock' == get_post_meta(get_the_ID() ,'ct_rbpaqp_hpav_hide_product_base_on',true) ?'selected' : '' ); ?>>
						<?php echo esc_html__('Out Of Stock','ct_rbpaqp_hpav_hide_product'); ?>
					</option>
					<option value="on_back_order" <?php echo esc_attr( 'on_back_order' == get_post_meta(get_the_ID() ,'ct_rbpaqp_hpav_hide_product_base_on',true) ?'selected' : '' ); ?>>
						<?php echo esc_html__('On Backorder','ct_rbpaqp_hpav_hide_product'); ?>
					</option>
					<option value="on_specific_quantity" <?php echo esc_attr( 'on_specific_quantity' == get_post_meta(get_the_ID() ,'ct_rbpaqp_hpav_hide_product_base_on',true) ?'selected' : '' ); ?>>
						<?php echo esc_html__('On Specific Quantity','ct_rbpaqp_hpav_hide_product'); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Select Quantity', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<input type="number"  min="" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpav_hide_product_on_min_quantity" class="ct_rbpaqp_hpav_hide_product_on_min_quantity" value="<?php echo esc_attr( get_post_meta(get_the_ID() ,'ct_rbpaqp_hpav_hide_product_on_min_quantity',true) ); ?>">

				<?php echo esc_html__('Note when quantity is less then specific values product will hide', 'cloud_tech_rbpaqpfw'); ?>

			</td>
		</tr>
	</table>
	<?php


}


