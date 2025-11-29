<?php
if (! defined('WPINC') ) {
	die;
}


add_action('add_meta_boxes','ct_tepfw_hide_price_and_add_to_cart_add_meta_boxes');
add_action('save_post_ct_tax_exempt_pro','save_post_ct_tax_exempt_pro');

function ct_tepfw_hide_price_and_add_to_cart_add_meta_boxes() {

	add_meta_box(
		'ct-tepfw-cart-restrictions',
		esc_html__('Cart Restrictions', 'cloud_tech_tepfw'),
		'ct_tepfw_cart_restrictions',
		'ct_tax_exempt_pro',
		'normal',
		'high'
	);

	add_meta_box(
		'ct-tepfw-product-cat-restrictions',
		esc_html__('Restrictions', 'cloud_tech_tepfw'),
		'ct_tepfw_product_cat_restrictions',
		'ct_tax_exempt_pro',
		'normal',
		'high'
	);

	add_meta_box(
		'ct-tepfw-quantity-restrictions',
		esc_html__('Quantity Restrictions', 'cloud_tech_tepfw'),
		'ct_tepfw_restriction',
		'ct_tax_exempt_pro',
		'normal',
		'high'
	);

}

function ct_tepfw_cart_restrictions() {
	?>
	<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">

		<tr>
			<th>
				<?php echo esc_html__('Product', 'cloud_tech_tepfw');?>
			</th>
			<td>
				<select name="ct_tepfw_inlcuded_products[]"  multiple style="width: 40%;" class="ct_tepfw_inlcuded_products ct-tepfw-product-search">

					<?php foreach ((array) get_post_meta( $post_id , 'ct_tepfw_inlcuded_products' , true ) as $current_product_id){ ?>
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
				<?php echo esc_html__('Categories', 'cloud_tech_tepfw'); ?>
			</th>
			<td>
				<select name="ct_tepfw_inlcuded_category[]"  multiple   style="width: 40%;" class="ct-tepfw-cat-search">

					<?php foreach ((array) get_post_meta( $post_id , 'ct_tepfw_inlcuded_category' , true ) as $current_cat_id){  
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
				<?php echo esc_html__('Tag', 'cloud_tech_tepfw'); ?>
			</th>
			<td>
				<select name="ct_tepfw_inlcuded_tag[]" multiple style="width: 40%;" class="ct-tepfw-live-search">

					<?php foreach ($tag as $tag_obj): ?>
						<?php if ( is_object( $tag_obj ) && $tag_obj->term_id ): ?>
							<option value="<?php echo esc_attr( $tag_obj->term_id ); ?>"  <?php if (  in_array(  $tag_obj->term_id , (array) get_post_meta( $post_id , 'ct_tepfw_inlcuded_tag' , true )) ): ?>
							selected						
							<?php endif ?> >
							<?php echo esc_attr( $tag_obj->name ); ?>
						</option>
					<?php endif ?>
				<?php endforeach ?>

			</select>
		</td>
	</tr>
</table>
<?php
}

function ct_tepfw_product_cat_restrictions() {
	global $post;
	$post_id 	= get_the_ID(); 

	$country_obj 	= new WC_Countries();
	$tag 			= get_terms('product_tag');


	?>
	<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">

		<tr>
			<th>
				<?php echo esc_html__('Country', 'cloud_tech_tepfw'); ?>
			</th>
			<td>
				<select name="ct_tepfw_inlcuded_country[]" multiple style="width: 40%;" class="ct-tepfw-live-search">

					<?php foreach ($country_obj->get_countries() as $country_key => $country): ?>

						<option value="<?php echo esc_attr( $country_key ); ?>" <?php if (  in_array( $country_key , (array) get_post_meta( $post_id , 'ct_tepfw_inlcuded_country' , true )) ): ?>
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
			<?php echo esc_html__('Select Customer', 'cloud_tech_tepfw'); ?>
		</th>
		<td>
			<select name="ct_tepfw_inlcuded_customers[]" multiple style="width: 40%;" class="ct-tepfw-live-search">

				<?php foreach (get_users() as $role_key => $customer_object){ ?>

					<option value="<?php echo esc_attr( $customer_object->ID ); ?>" <?php if (  in_array( $customer_object->ID , (array) get_post_meta( $post_id , 'ct_tepfw_inlcuded_customers' , true )) ){ ?>
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
			<?php echo esc_html__('Roles', 'cloud_tech_tepfw'); ?>
		</th>
		<td>
			<select name="ct_tepfw_inlcuded_role[]" multiple style="width: 40%;" class="ct-tepfw-live-search">

				<?php foreach (get_set_roles() as $role_key => $role){ ?>

					<option value="<?php echo esc_attr( $role_key ); ?>"  <?php if (  in_array( $role_key , (array) get_post_meta( $post_id , 'ct_tepfw_inlcuded_role' , true )) ){ ?>
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

function ct_tepfw_restriction() {
	global $post;
	$post_id 	= get_the_ID(); 

	?>
	<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">
		<tr>
			<th>
				<?php echo esc_html__('Remove Tax Automatically', 'cloud_tech_tepfw'); ?>
			</th>
			<td>
				<input type="checkbox" name="ct_tepfw_remove_tax_automatically" value="yes" <?php if ( !empty( get_post_meta( $post_id , 'ct_tepfw_remove_tax_automatically' , true ) ) ) { ?> checked <?php } ?>>
				<i><?php echo esc_html__('Enable checkbox to remove tax automatically when user tax request status is approved.', 'cloud_tech_tepfw'); ?></i>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Auto Approve Tax Exempt Request', 'cloud_tech_tepfw'); ?>
			</th>
			<td>
				<input type="checkbox" name="ct_tepfw_auto_approve_tax_exempt_request" value="yes" <?php if ( !empty( get_post_meta( $post_id , 'ct_tepfw_auto_approve_tax_exempt_request' , true ) ) ) { ?> checked <?php } ?>>
				<i><?php echo esc_html__('Enable checkbox to approve auto tax exempt requests.Remeber Request will auto accept when user apply from my account page its depend on user role, country , or specific user.', 'cloud_tech_tepfw'); ?></i>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo esc_html__('Show Tax Exemption Message on Checkout Page', 'cloud_tech_tepfw'); ?>
			</th>

			<td>
				<select name="ct_tepfw_show_default_or_custom_msg_with_checkbox" class="ct_tepfw_dependable ct_tepfw_show_default_or_custom_msg_with_checkbox">
					<option value="none" <?php if ( 'none' == get_post_meta( $post_id , 'ct_tepfw_show_default_or_custom_msg_with_checkbox' , true ) ){  ?> selected<?php } ?> >
						<?php echo esc_html__('Show Default Text ', 'cloud_tech_tepfw'); ?>
					</option>

					<option value="custom_message" <?php if ( 'custom_message' == get_post_meta( $post_id , 'ct_tepfw_show_default_or_custom_msg_with_checkbox' , true ) ){  ?> selected <?php } ?> >
						<?php echo esc_html__('Make Custom Message', 'cloud_tech_tepfw'); ?>
					</option>
				</select>
				<i><?php echo esc_html__('Default message is claim for tax exempt {tax_exempt_link}. use {tax_exempt_link} in custom message to give my show my account tax exempt page link. ', 'cloud_tech_tepfw'); ?></i>

			</td>
		</tr>
		<tr>
			<th><?php echo esc_html__('Make your Custom Message', 'cloud_tech_tepfw'); ?></th>
			<td class="ct_tepfw_custom_message">
				<?php
				$content   = get_post_meta( $post_id, 'ct_tepfw_custom_message', true ) ;
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
				wp_editor( $content, 'ct_tepfw_custom_message', $settings );
				?>
			</td>
		</tr>
		<tr>
			<th><?php echo esc_html__('Remove Tax Exemption Type', 'cloud_tech_tepfw'); ?></th>
			<?php $tax_exempt_type = ['all_cart_tax','product_tax_only','shipping_tax_only'] ?>
			<td>
				<select name="ct_tepfw_remove_tax_exempt_type" class="ct_tepfw_dependable ct_tepfw_remove_tax_exempt_type">

					<?php foreach ($tax_exempt_type as $key => $value){   ?>
						
						<option value="<?php echo esc_attr( $value ); ?>" <?php if ( $value == get_post_meta( $post_id , 'ct_tepfw_remove_tax_exempt_type' , true ) ){  ?> selected<?php } ?> >
							<?php echo esc_attr( ucwords( str_replace( '_',' ', $value ) ) ); ?>
						</option>

					<?php } ?>

				</select>
			</td>
		</tr>

	</table>
	<?php

}


function save_post_ct_tax_exempt_pro( $post_id ){
	$array_keys = ['ct_tepfw_inlcuded_products','ct_tepfw_inlcuded_category','ct_tepfw_inlcuded_tag','ct_tepfw_inlcuded_country','ct_tepfw_inlcuded_customers','ct_tepfw_inlcuded_role','ct_tepfw_inlcuded_payment_method'];

	foreach ($array_keys as $value) {

		$updated_value 	= isset( $_POST[ $value ] ) ? sanitize_meta('',$_POST[ $value ],'') : [];

		update_post_meta( $post_id , $value ,  $updated_value ); 

	}

	$string_keys = ['ct_tepfw_remove_tax_automatically','ct_tepfw_auto_approve_tax_exempt_request','ct_tepfw_show_default_or_custom_msg_with_checkbox','ct_tepfw_custom_message','ct_tepfw_remove_tax_exempt_type'];


	foreach ($string_keys as $value) {

		$updated_value 	= isset( $_POST[ $value ] ) ? sanitize_meta('',$_POST[ $value ],'') : [];

		update_post_meta( $post_id , $value ,  $updated_value ); 

	}
}


add_action( 'show_user_profile', 'ct_tepfw_show_status_field_profile' );
add_action( 'edit_user_profile', 'ct_tepfw_show_status_field_profile' );
add_action( 'personal_options_update', 'ct_tepfw_save_status_field_profile' );
add_action( 'edit_user_profile_update', 'ct_tepfw_save_status_field_profile' );

function ct_tepfw_show_status_field_profile( $user ) {
	?>
	<h2><?php echo esc_html__('Tax Exempt Detail',''); ?></h2>
	<table class="form-table">
		<tr>
			<th><?php echo esc_html__( 'Status' , 'cloud_tech_tepfw' ); ?></th>
			<td>
				<select name="ct_tepfw_is_tax_exempted">

					<?php  $tax_exampty_status 	= ['pending','approved','cancelled'];
					foreach( $tax_exampty_status as $val ){
						?>
						<option value="<?php echo esc_attr( $val ); ?>" <?php if ( $val == get_user_meta( $user->ID ,'ct_tepfw_is_tax_exempted' , true ) ){  ?> selected <?php } ?> >
							<?php echo esc_attr( $val ); ?>
						</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th><?php echo esc_html__( 'Enable Resubmit Request Form To User' , 'cloud_tech_tepfw' ); ?></th>
			<td>
				<input type="checkbox" value="yes" name="ct_tepfw_enable_resubmit_for_request_again" <?php echo esc_attr( !empty( get_user_meta( $user->ID ,'ct_tepfw_enable_resubmit_for_request_again' , true ) ) ? 'checked' : '' ); ?>>
				<i><?php echo esc_html__( 'Enable checkbox to allow user to resubmit tax exempt request again.' , 'cloud_tech_tepfw' ); ?></i>

			</td>
		</tr>
		<tr>
			<th><?php echo esc_attr( get_option('ct_tepfw_text_field_label') ); ?></th>
			<td>
				<input type="text" name="ct_tepfw_text_field_label" value="<?php echo esc_attr( get_user_meta( $user->ID ,'ct_tepfw_text_field_data' , true ) ); ?>">
			</td>
		</tr>

		<tr>
			<th><?php echo esc_attr( get_option('ct_tepfw_textarea_field_label') ); ?></th>
			<td>
				<textarea name="ct_tepfw_textarea_field_label"><?php echo esc_attr( get_user_meta( $user->ID ,'ct_tepfw_textarea_field_data' , true ) ); ?></textarea>
			</td>
		</tr>

		<tr>
			<th><?php echo esc_attr( get_option('ct_tepfw_fileupload_field_label') ); ?></th>
			<td>
				<input type="file" name="ct_tepfw_fileupload_field_label" />

				<a target="_blank" href="<?php echo esc_url( wp_get_attachment_url( get_user_meta( $user->ID ,'ct_tepfw_fileupload_field_data' , true ) ) ); ?>" class="fa fa-eye"></a>

			</td>
		</tr>


		<tr>
			<th><?php echo esc_html__( 'Additional Message For User' , 'cloud_tech_tepfw' ); ?></th>
			<td>
				<?php
				$content   = get_user_meta( $user->ID , 'ct_tepfw_additional_message', true ) ;
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
				wp_editor( $content, 'ct_tepfw_additional_message', $settings );
				?>
			</td>
		</tr>

		<?php wp_nonce_field('cloud_tech_tepfw_nonce','cloud_tech_tepfw_nonce'); ?>
	</table>
	<?php
}


function ct_tepfw_save_status_field_profile( $user_id ) {

	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}


	$nonce      = isset( $_POST['cloud_tech_tepfw_nonce'] ) ? sanitize_text_field( $_POST['cloud_tech_tepfw_nonce'] ) : '';


	if ( isset( $_POST['ct_tepfw_text_field_label'] ) && ! wp_verify_nonce( $nonce , 'cloud_tech_tepfw_nonce' ) ) {
		wp_die( esc_html__('Security Violated !' , 'cloud_tech_tepfw') );
	}
	

	$text_field_label   = isset( $_POST['ct_tepfw_text_field_label'] ) ? sanitize_text_field( $_POST['ct_tepfw_text_field_label'] ) : '';

	update_user_meta( $user_id ,'ct_tepfw_text_field_data', $text_field_label );
	$textarea_field     = isset( $_POST['ct_tepfw_textarea_field_label'] ) ? sanitize_text_field( $_POST['ct_tepfw_textarea_field_label'] ) : ''; 
	update_user_meta( $user_id ,'ct_tepfw_textarea_field_data', $textarea_field );

	$resubmit_form 		= isset( $_POST['ct_tepfw_enable_resubmit_for_request_again'] ) ? sanitize_text_field( $_POST['ct_tepfw_enable_resubmit_for_request_again'] ) : '';
	update_user_meta( $user_id ,'ct_tepfw_enable_resubmit_for_request_again', $resubmit_form );

	$is_tax_exempted 	= isset( $_POST['ct_tepfw_is_tax_exempted'] ) ? sanitize_text_field( $_POST['ct_tepfw_is_tax_exempted'] ) : '';
	update_user_meta( $user_id ,'ct_tepfw_is_tax_exempted', $is_tax_exempted );

	$additional_message = isset( $_POST['ct_tepfw_additional_message'] ) ? sanitize_text_field( $_POST['ct_tepfw_additional_message'] ) : '';
	update_user_meta( $user_id ,'ct_tepfw_additional_message', $additional_message );

	if ( empty( get_user_meta( get_current_user_id() ,'ct_tepfw_fileupload_field_data' , true ) ) && isset( $_FILES['ct_tepfw_fileupload_field_label'] ) ) {

		$file_data      	= sanitize_meta('',$_FILES['ct_tepfw_fileupload_field_label'],'');
		$file_type      	= isset( $file_data['type'] ) ?  explode( '/' , $file_data['type'] ) : [''];
		$file_type      	= end( $file_type );

		$file_name      	= sanitize_text_field( wp_unslash( $file_data['name'] ) );

		include_once ABSPATH . 'wp-admin/includes/image.php';
		include_once ABSPATH . 'wp-admin/includes/file.php';
		include_once ABSPATH . 'wp-admin/includes/media.php';

		$uploaddir      	= wp_upload_dir();
		$file           	= sanitize_text_field( wp_unslash( $file_data['name'] ) );
		$uploadimgfile  	= $uploaddir['path'] . '/' . basename( $file );

		if ( isset( $file_data['tmp_name'] ) ) {
			$tempname   	= sanitize_text_field( $file_data['tmp_name'] );

			copy( $tempname, $uploadimgfile );

			$filename       = basename( $uploadimgfile );

			$wp_filetype    = wp_check_filetype( basename( $filename ), null );

			$attachment     = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_status'    => 'inherit',
			);

			$attach_id      = wp_insert_attachment( $attachment, $uploadimgfile );

			update_user_meta( $user_id ,'ct_tepfw_fileupload_field_data' , $attach_id );

		}

	}

}




function cloud_tect_tepfw_add_custom_user_column($columns) {
    $columns['tax_exempt_request_status'] = 'Tax Exempt Request Status';
    return $columns;
}
add_filter('manage_users_columns', 'cloud_tect_tepfw_add_custom_user_column');


function cloud_tect_tepfw_display_custom_user_column_value($value, $column_name, $user_id) {
    if ($column_name == 'tax_exempt_request_status') {
        return ucfirst( get_user_meta( $user_id ,'ct_tepfw_is_tax_exempted',true) );
    }
    return $value;
}
add_filter('manage_users_custom_column', 'cloud_tect_tepfw_display_custom_user_column_value', 10, 3);
