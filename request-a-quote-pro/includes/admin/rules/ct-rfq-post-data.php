<?php
if (! defined('WPINC') ) {
	die;
}

add_action('add_meta_boxes','ct_rfq_hide_price_and_add_to_cart_add_meta_boxes');

$ct_rfqcustom_post_type = ['ct-rfq-quote-rule','ct-rfq-quote-fields','ct-rfq-submit-quote'];
foreach ($ct_rfqcustom_post_type as $quote_slug) {

	add_action('save_post_'.$quote_slug,'ct_rfq_save_post_ct_tax_exempt_pro');
}

function ct_rfq_hide_price_and_add_to_cart_add_meta_boxes() {

	add_meta_box(
		'ct-rfq-cart-restrictions',
		esc_html__('Restrictions', 'cloud_tech_rfq'),
		'ct_rfq_cart_restrictions',
		'ct-rfq-quote-rule',
		'normal',
		'high'
	);

	add_meta_box(
		'ct-rfq-add-to-cart-and-quote-button-setting',
		esc_html__('Price, Add To Cart & Quote Button Setting', 'cloud_tech_rfq'),
		'ct_rfq_price_add_to_cart_and_quote_button_setting',
		'ct-rfq-quote-rule',
		'normal',
		'high'
	);


}

function ct_rfq_cart_restrictions() {
	$post_id 	= get_the_ID(); 

	$country_obj 	= new WC_Countries();
	$tag 			= get_terms('product_tag');

	?>
	<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">

		<tr>
			<th>
				<?php echo esc_html__('Product', 'cloud_tech_rfq');?>
			</th>
			<td>
				<select name="ct_rfq_inlcuded_products[]"  multiple style="width: 40%;" class="ct_rfq_inlcuded_products ct-rfq-product-search">

					<?php foreach ((array) get_post_meta( $post_id , 'ct_rfq_inlcuded_products' , true ) as $current_product_id){ ?>
						<?php 
						$product = wc_get_product( $current_product_id );
						if ( $product  ){ ?>
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
				<?php echo esc_html__('Categories', 'cloud_tech_rfq'); ?>
			</th>
			<td>
				<select name="ct_rfq_inlcuded_category[]"  multiple   style="width: 40%;" class="ct-rfq-cat-search">

					<?php foreach ((array) get_post_meta( $post_id , 'ct_rfq_inlcuded_category' , true ) as $current_cat_id){  
						if ( ! $current_cat_id ) {
							continue;
						} 
						$cat = get_term( $current_cat_id );
						if ( $cat  ){  ?>
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
				<?php echo esc_html__('Tag', 'cloud_tech_rfq'); ?>
			</th>
			<td>
				<select name="ct_rfq_inlcuded_tag[]" multiple style="width: 40%;" class="ct-rfq-live-search">

					<?php foreach ($tag as $tag_obj): ?>
						<?php if ( is_object( $tag_obj ) && $tag_obj->term_id ): ?>
							<option value="<?php echo esc_attr( $tag_obj->term_id ); ?>"  <?php if (  in_array(  $tag_obj->term_id , (array) get_post_meta( $post_id , 'ct_rfq_inlcuded_tag' , true )) ){  ?>
								selected						
								<?php } ?> >
								<?php echo esc_attr( $tag_obj->name ); ?>
							</option>
						<?php endif ?>
					<?php endforeach ?>

				</select>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Country', 'cloud_tech_rfq'); ?>
			</th>
			<td>
				<select name="ct_rfq_inlcuded_country[]" multiple style="width: 40%;" class="ct-rfq-live-search">

					<?php foreach ($country_obj->get_countries() as $country_key => $country): ?>

						<option value="<?php echo esc_attr( $country_key ); ?>" <?php if (  in_array( $country_key , (array) get_post_meta( $post_id , 'ct_rfq_inlcuded_country' , true )) ){  ?>
							selected						
							<?php } ?> >
							<?php echo esc_attr( $country ); ?>
						</option>

					<?php endforeach ?>

				</select>
			</td>
		</tr>

		<tr>
			<th>
				<?php echo esc_html__('Select Customer', 'cloud_tech_rfq'); ?>
			</th>
			<td>
				<select name="ct_rfq_inlcuded_customers[]" multiple style="width: 40%;" class="ct-rfq-live-search">

					<?php foreach (get_users() as $role_key => $customer_object){ ?>

						<option value="<?php echo esc_attr( $customer_object->ID ); ?>" <?php if (  in_array( $customer_object->ID , (array) get_post_meta( $post_id , 'ct_rfq_inlcuded_customers' , true )) ){ ?>
							selected						
							<?php } ?> >
							<?php echo esc_attr( $customer_object->display_name ); ?>
						</option>

					<?php } ?>

				</select>
			</td>
		</tr>

		<tr>
			<th>
				<?php echo esc_html__('Roles', 'cloud_tech_rfq'); ?>
			</th>
			<td>
				<select name="ct_rfq_inlcuded_role[]" multiple style="width: 40%;" class="ct-rfq-live-search">

					<?php foreach (get_set_roles() as $role_key => $role){ ?>

						<option value="<?php echo esc_attr( $role_key ); ?>"  <?php if (  in_array( $role_key , (array) get_post_meta( $post_id , 'ct_rfq_inlcuded_role' , true )) ){ ?>
							selected						
							<?php } ?> >
							<?php echo esc_attr( $role ); ?>
						</option>

					<?php } ?>

				</select>
			</td>

		</tr>


	</table>
	<?php
}

function ct_rfq_price_add_to_cart_and_quote_button_setting() {
	$post_id 	= get_the_ID(); 

	?>
	<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">
		<tr>
			<th>
				<?php echo esc_html__('Hide Add to Cart Button', 'cloud_tech_rfq');?>
			</th>
			<td>
				<input type="checkbox" name="ct_rfq_hide_add_to_cart_button" value="yes" <?php if ( get_post_meta( $post_id , 'ct_rfq_hide_add_to_cart_button' ,true )  ): ?>
				checked
				<?php endif ?> >
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Show Button with Out Of Stock Product ', 'cloud_tech_rfq');?>
			</th>
			<td>
				<input type="checkbox" name="ct_rfq_show_request_a_quote_btn_with_outofstock" value="yes" <?php if ( get_post_meta( $post_id , 'ct_rfq_show_request_a_quote_btn_with_outofstock' ,true )  ): ?>
				checked
				<?php endif ?> >
			</td>
		</tr>
		
		<tr>
			<th>
				<?php echo esc_html__('Request a Quote Button Text', 'cloud_tech_rfq');?>
			</th>
			<td>
				<input type="text" name="ct_rfq_request_a_quote_button_text" class="ct_rfq_request_a_quote_button_text" value="<?php echo esc_attr( get_post_meta($post_id,'ct_rfq_request_a_quote_button_text',true) ? get_post_meta($post_id,'ct_rfq_request_a_quote_button_text',true) : 'Request a Quote' ); ?>">
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Request a Quote Button Additional Class', 'cloud_tech_rfq');?>
			</th>
			<td>
				<input type="text" name="ct_rfq_request_a_quote_button_additonal_class" class="ct_rfq_request_a_quote_button_additonal_class" value="<?php echo esc_attr( get_post_meta($post_id,'ct_rfq_request_a_quote_button_additonal_class',true) ); ?>">

			</td>
		</tr>

		<tr>
			<th>
				<?php echo esc_html__('Can User Add Custom Amount', 'cloud_tech_rfq');?>
			</th>
			<td>
				<input type="checkbox" name="ct_rfq_can_user_add_custom_amount" value="yes" <?php if ( get_post_meta( $post_id , 'ct_rfq_can_user_add_custom_amount' ,true )  ): ?>
				checked
				<?php endif ?> >
				<i>
				<?php echo esc_html__('Enable checkbox to allow you customer to enter custom amount.', 'cloud_tech_rfq');?>
				</i>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Hide or Replace Price text', 'cloud_tech_rfq');?>
			</th>
			<td>
				<?php $hide_or_replace_price_text = ['none' ,'hide_from_shop_and_product_page','hide_from_quote_page','_hide_from_every_where','replace_text_for_shop_and_product_page','replace_text_for_quote_page','_replace_text_for_every_where']; ?>
				<select name="ct_rfq_hide_price_or_replace_text" class="ct_rfq_hide_price_or_replace_text">

					<?php foreach ($hide_or_replace_price_text as $value): ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php if ( $value === (string) get_post_meta( get_the_ID(),'ct_rfq_hide_price_or_replace_text',true)  ): ?>
							selected
						<?php endif ?> >
								<?php echo esc_attr( str_replace('_', ' ', $value) ); ?>
						</option>
					<?php endforeach ?>
					
				</select>

			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Replace Price Text', 'cloud_tech_rfq');?>
			</th>
			<td>
				<input type="text" name="ct_rfq_replace_price_text" class="ct_rfq_replace_price_text" value="<?php echo esc_attr( get_post_meta($post_id,'ct_rfq_replace_price_text',true) ); ?>">
			</td>
		</tr>

		<tr style="display: none;">
			<th>
				<?php echo esc_html__('Enable Add To Quote Button on Cart Page', 'cloud_tech_rfq');?>
			</th>
			<td>
				<input type="text" name="ct_rfq_enable_atq_btn_on_crt_pg" class="ct_rfq_enable_atq_btn_on_crt_pg" value="<?php echo esc_attr( get_post_meta($post_id,'ct_rfq_enable_atq_btn_on_crt_pg',true) ); ?>">
			</td>
		</tr>

	</table>
	<?php
}


function ct_rfq_save_post_ct_tax_exempt_pro( $post_id ){
	$array_keys = ['ct_rfq_inlcuded_products','ct_rfq_inlcuded_category','ct_rfq_inlcuded_tag','ct_rfq_inlcuded_country','ct_rfq_inlcuded_customers','ct_rfq_inlcuded_role','ct_rfq_request_a_quote_options_value_and_label'];

	foreach ($array_keys as $value) {

		$updated_value 	= isset( $_POST[ $value ] ) ? sanitize_meta('',$_POST[ $value ],'') : [];

		update_post_meta( $post_id , $value ,  $updated_value ); 

	}

	$string_keys = ['ct_rfq_enable_atq_btn_on_crt_pg','ct_rfq_replace_price_text','ct_rfq_hide_add_to_cart_button','ct_rfq_request_a_quote_button_text','ct_rfq_request_a_quote_button_additonal_class','ct_rfq_show_request_a_quote_btn_with_outofstock','ct_rfq_can_user_add_custom_amount','ct_rfq_hide_price_or_replace_text','ct_rfq_quote_fields_field_type','ct_rfq_quote_fields_field_label','ct_rfq_quote_fields_show_field_with','ct_rfq_quote_fields_is_this_dependent','ct_rfq_quote_fields_select_dependent_field','ct_rfq_quote_fields_field_default_value','ct_rfq_quote_fields_field_placeholder','ct_rfq_quote_fields_field_additonal_class','ct_rfq_quote_fields_show_field_in_private_company','current_user_email'];


	foreach ($string_keys as $value) {

		$updated_value 	= isset( $_POST[ $value ] ) ? sanitize_text_field( $_POST[ $value ] ) : '';

		update_post_meta( $post_id , $value ,  $updated_value ); 

	}
}

