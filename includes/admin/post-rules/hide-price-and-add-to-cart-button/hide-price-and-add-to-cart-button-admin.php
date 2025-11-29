<?php
if (! defined('WPINC') ) {
	die;
}


add_action('add_meta_boxes','ct_rbpaqp_hide_price_and_add_to_cart_add_meta_boxes');

function ct_rbpaqp_hide_price_and_add_to_cart_add_meta_boxes() {

	add_meta_box(
		'ct-rbp-product-cat-restrictions',
		esc_html__('Restrictions', 'cloud_tech_rbpaqpfw'),
		'ct_rbpaqp_hpaatcb_product_cat_restrictionssss',
		'ct_hide_p_nd_a_t_c_b',
		'normal',
		'high'
	);

	add_meta_box(
		'ct-rbp-quantity-restrictions',
		esc_html__('Quantity Restrictions', 'cloud_tech_rbpaqpfw'),
		'ct_rbpaqp_hpaatcb_restrictionsss',
		'ct_hide_p_nd_a_t_c_b',
		'normal',
		'high'
	);

}

function ct_rbpaqp_hpaatcb_product_cat_restrictionssss(){
	ct_rbpaqp_hpaatcb_product_cat_restrictions( get_the_ID() );
}


function ct_rbpaqp_hpaatcb_restrictionsss(){
	ct_rbpaqp_hpaatcb_restriction( get_the_ID() );
}

function ct_rbpaqp_hpaatcb_product_cat_restrictions($post_id) {
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
					<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_inlcuded_products[]"  multiple style="width: 40%;" class="ct_rbpaqp_hpaatcb_inlcuded_products ct_rbpaqp_product_search">

						<?php foreach ((array) get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_inlcuded_products' , true ) as $current_product_id): ?>
						<?php 
						$product = wc_get_product( $current_product_id );
						if ( $product  ): ?>
							<option value="<?php echo esc_attr( $current_product_id ); ?>" selected >
								<?php echo esc_attr( $product->get_name() ); ?>
							</option>
						<?php endif ?>

					<?php endforeach ?>
				</select>
			</td>
		</tr>

		<tr>
			<th>
				<?php echo esc_html__('Categories', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_inlcuded_category[]"  multiple   style="width: 40%;" class="ct-rbpaqp-cat-search">
					
					<?php foreach ((array) get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_inlcuded_category' , true ) as $current_cat_id): 
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
			<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_inlcuded_tag[]" multiple style="width: 40%;" class="ct-rbpaqp-live-search">

				<?php foreach ($tag as $tag_obj): ?>
					<?php if ( is_object( $tag_obj ) && $tag_obj->term_id ): ?>
						<option value="<?php echo esc_attr( $tag_obj->term_id ); ?>"  <?php if (  in_array(  $tag_obj->term_id , (array) get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_inlcuded_tag' , true )) ): ?>
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
		<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_inlcuded_country[]" multiple style="width: 40%;" class="ct-rbpaqp-live-search">

			<?php foreach ($country_obj->get_countries() as $country_key => $country): ?>

				<option value="<?php echo esc_attr( $country_key ); ?>" <?php if (  in_array( $country_key , (array) get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_inlcuded_country' , true )) ): ?>
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
		<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_inlcuded_role[]" multiple style="width: 40%;" class="ct-rbpaqp-live-search">

			<?php foreach (ct_b2b_get_set_roles() as $role_key => $role): ?>

				<option value="<?php echo esc_attr( $role_key ); ?>"  <?php if (  in_array( $role_key , (array) get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_inlcuded_role' , true )) ): ?>
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
				<input type="date" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_start_date" value="<?php echo esc_attr( get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_start_date' , true ) ); ?>">
			</li>
			<li>
				<?php echo esc_html__('End Date', 'cloud_tech_rbpaqpfw'); ?>
			</li>
			<li>
				<input type="date" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_end_date" value="<?php echo esc_attr( get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_end_date' , true ) ); ?>">
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
		<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_inlcuded_days[]" multiple style="width: 40%;" class="ct-rbpaqp-live-search">

			<?php 
			$daysOfWeek = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday',);
			foreach ($daysOfWeek as $days_name): ?>

				<option value="<?php echo esc_attr( $days_name ); ?>" <?php if (  in_array( $days_name , (array) get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_inlcuded_days' , true )) ): ?>
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

function ct_rbpaqp_hpaatcb_restriction($post_id) {
	global $post;

	?>
	<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">

		<tr>
			<th>
				<?php echo esc_html__('Price', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_price_setting" class="ct_rbpaqp_hpaatcb_dependable ct_rbpaqp_hpaatcb_price_setting">
					<option value="none" <?php if ( 'none' == get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_price_setting' , true ) ){ ?> selected <?php } ?> >
						<?php echo esc_html__('Show Default Price', 'cloud_tech_rbpaqpfw'); ?>
					</option>
					<option value="hide_price" <?php if ( 'hide_price' == get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_price_setting' , true ) ){ ?> selected <?php } ?> >
						<?php echo esc_html__('Hide Price', 'cloud_tech_rbpaqpfw'); ?>
					</option>
					<option value="replace_price_text" <?php if ( 'replace_price_text' == get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_price_setting' , true ) ){ ?> selected <?php } ?> >
						<?php echo esc_html__('Replace Text', 'cloud_tech_rbpaqpfw'); ?>
					</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Price Text', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<input type="text" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_price_text" class="ct_rbpaqp_hpaatcb_dependable ct_rbpaqp_hpaatcb_price_text" value="<?php echo esc_attr( get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_price_text' , true ) ); ?>">
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Add To Cart Button', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<select name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_add_to_cart_btn" class="ct_rbpaqp_hpaatcb_dependable ct_rbpaqp_hpaatcb_add_to_cart_btn">
					<option value="none" <?php if ( 'none' == get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_add_to_cart_btn' , true ) ){  ?> selected<?php } ?> >
						<?php echo esc_html__('Show Default Button', 'cloud_tech_rbpaqpfw'); ?>
					</option>
					<option value="hide_button" <?php if ( 'hide_button' == get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_add_to_cart_btn' , true ) ){  ?> selected<?php } ?> >
						<?php echo esc_html__('Hide', 'cloud_tech_rbpaqpfw'); ?>
					</option>
					<option value="replace_text_and_link" <?php if ( 'replace_text_and_link' == get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_add_to_cart_btn' , true ) ){  ?>selected<?php } ?> >
						<?php echo esc_html__('Replace Text And Link', 'cloud_tech_rbpaqpfw'); ?>
					</option>

					<option value="make_your_on_button" <?php if ( 'make_your_on_button' == get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_add_to_cart_btn' , true ) ){  ?>selected<?php } ?> >
						<?php echo esc_html__('Make Custom Button', 'cloud_tech_rbpaqpfw'); ?>
					</option>

				</select>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Button Text', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<input type="text" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_button_text" class="ct_rbpaqp_hpaatcb_dependable ct_rbpaqp_hpaatcb_button_text" value="<?php echo esc_attr( get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_button_text' , true ) ); ?>">
			</td>
		</tr>

		<tr>
			<th>
				<?php echo esc_html__('Make your Custom Button', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td class="ct_rbpaqp_hpaatcb_make_your_on_button">
				<?php
				$content   = get_post_meta( $post_id, 'ct_rbpaqp_hpaatcb_make_your_on_button', true ) ;
				$settings  = array(
					'wpautop'       => false,
					'tinymce'       => true,
					'media_buttons' => false,
					'textarea_rows' => 10,
					'quicktags'     => array( 'buttons' => 'em,strong,link' ),
					'quicktags'     => true,
					'tinymce'       => array(
						'toolbar1' => 'bold,italic,link,unlink,undo,redo',
					),
				);
				wp_editor( $content, $post_id.'ct_rbpaqp_hpaatcb_make_your_on_button', $settings );
				?>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Custom Link', 'cloud_tech_rbpaqpfw'); ?>
			</th>
			<td>
				<input type="url" name="<?php echo esc_attr($post_id); ?>ct_rbpaqp_hpaatcb_custom_link" class="ct_rbpaqp_hpaatcb_dependable ct_rbpaqp_hpaatcb_custom_link" value="<?php echo esc_attr( get_post_meta( $post_id , 'ct_rbpaqp_hpaatcb_custom_link' , true ) ); ?>">
			</td>
		</tr>
	</table>
	<?php


}


