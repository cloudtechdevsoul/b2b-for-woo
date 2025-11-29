<?php

/**
 * General Settings of plugin
 */

add_settings_section(
	'ct-rfq-general-sec',         // ID used to identify this section and with which to register options.
	esc_html__( '', 'cloud_tech_rfq' ),   // Title to be displayed on the administration page.
	'', // Callback used to render the description of the section.
	'ct_rfq_general_setting_section' // Page on which to add this section of options.
);

add_settings_field(
	'ct_rfq_request_a_quote_billing_field_template',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Billing/Shipping Fields Template', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_request_a_quote_billing_field_template',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_request_a_quote_billing_field_template'
);

function ct_rfq_request_a_quote_billing_field_template( ) {

	$postion 	= 	['1st-template','2nd-template','3rd-template'];
	?>
	<select name="ct_rfq_request_a_quote_billing_field_template" class="ct_rfq_request_a_quote_billing_field_template">
		<?php foreach ($postion as $hook) {  ?>
			
			<option value="<?php echo esc_attr( $hook ); ?>" <?php if ( $hook == get_option('ct_rfq_request_a_quote_billing_field_template') ) {  ?>selected <?php } ?> >
				<?php echo esc_attr( ucwords ( str_replace('_', ' ',  str_replace('-', ' ',  $hook ) ) ) ) ?>
			</option>
		<?php } ?>
	</select>
	<?php foreach ($postion as $hook) {  ?>
		<div class="ct-rfq-checkout-template-images ct-rfq-checkout-<?php echo esc_attr( $hook ); ?>"  >
			<a target="_blank" href="<?php echo esc_url( CT_RFQ_PLUGIN_URL . 'assets/images/checkout/'.$hook.'.png' ); ?>" class="fa fa-eye"></a>
			<img style="width: 400px;height: 400px;" src="<?php echo esc_url( CT_RFQ_PLUGIN_URL . 'assets/images/checkout/'.$hook.'.png' ); ?>"  >
		</div>
	<?php } ?>

	<?php
}

add_settings_field(
	'ct_rfq_request_a_quote_button_postion_prd_pg',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Request a Button Position', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_request_a_quote_button_postion_prd_pg',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_request_a_quote_button_postion_prd_pg'
);

function ct_rfq_request_a_quote_button_postion_prd_pg( ) {

	$postion 	= 	['woocommerce_before_add_to_cart_quantity','woocommerce_after_add_to_cart_button']
	?>
	<select name="ct_rfq_request_a_quote_button_postion_prd_pg" class="ct_rfq_request_a_quote_button_postion_prd_pg">
		<?php foreach ($postion as $hook) {  ?>
			
			<option value="<?php echo esc_attr( $hook ); ?>" <?php if ( $hook == get_option('ct_rfq_request_a_quote_button_postion_prd_pg') ) {  ?>selected <?php } ?> >
				<?php echo esc_attr( ucfirst( str_replace('_', ' ',  $hook ) ) ) ?>
			</option>
		<?php } ?>
	</select>
	<?php
}

add_settings_field(
	'ct_rfq_request_a_quote_page_type',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Request a Page Type', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_request_a_quote_page_type',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_request_a_quote_page_type'
);




function ct_rfq_request_a_quote_page_type( ) {

	$postion 	= 	['make_auto_page','make_your_own_page'];
	?>
	<select name="ct_rfq_request_a_quote_page_type" class="ct_rfq_request_a_quote_page_type">
		<?php foreach ($postion as $hook) {  ?>
			
			<option value="<?php echo esc_attr( $hook ); ?>" <?php if ( $hook == get_option('ct_rfq_request_a_quote_page_type') ) {  ?>selected <?php } ?> >
				<?php echo esc_attr( ucfirst( str_replace('_', ' ',  $hook ) ) ) ?>
			</option>
		<?php } ?>
	</select>
	<?php
}

add_settings_field(
	'ct_rfq_request_a_quote_page_id',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Request a Page Type', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_request_a_quote_page_id',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_request_a_quote_page_id'
);




function ct_rfq_request_a_quote_page_id( ) {

	?>
	<input type="number" min="1" name="ct_rfq_request_a_quote_page_id" class="ct_rfq_request_a_quote_page_id" value="<?php echo esc_attr( get_option('ct_rfq_request_a_quote_page_id') ); ?>">
	<?php
}

//Enable or Disable show checkboxes on cartegory , acheive and cart page 
add_settings_field(
	'ct_rfq_request_a_quote_btn_on_cart_page',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Show Quote Button On Cart Page', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_request_a_quote_btn_on_cart_page',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_request_a_quote_btn_on_cart_page'
);




function ct_rfq_request_a_quote_btn_on_cart_page( ) {

	?>
	<input type="checkbox" min="1" name="ct_rfq_request_a_quote_btn_on_cart_page" class="ct_rfq_request_a_quote_btn_on_cart_page" value="yes"<?php echo esc_attr( get_option('ct_rfq_request_a_quote_btn_on_cart_page') ? 'checked' : '' ); ?>>
	<br>
	<i><?php echo esc_html__('Enable checkbox to show Add To Quote button on Cart page wit cart item','cloud_tech_rfq'); ?></i>
	<?php

}

//Enable or Disable show checkboxes on cartegory , acheive and cart page 
add_settings_field(
	'ct_rfq_request_a_quote_checkboxes',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Show Checkboxes with Quote Button', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_request_a_quote_checkboxes',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_request_a_quote_checkboxes'
);




function ct_rfq_request_a_quote_checkboxes( ) {

	?>
	<input type="checkbox" min="1" name="ct_rfq_request_a_quote_checkboxes" class="ct_rfq_request_a_quote_checkboxes" value="yes"<?php echo esc_attr( get_option('ct_rfq_request_a_quote_checkboxes') ? 'checked' : '' ); ?>>
	<br>
	<i><?php echo esc_html__('Enable checkbox to show checkboxes on cartegory,archeive and cart page','cloud_tech_rfq'); ?></i>
	<?php

}


//Enable or Disable show checkboxes on cartegory , acheive and cart page 
add_settings_field(
	'ct_rfq_request_a_quote_whole_button_text',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Whole Add to Quote Button text', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_request_a_quote_whole_button_text',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_request_a_quote_whole_button_text'
);

function ct_rfq_request_a_quote_whole_button_text( ) {

	?>
	<input type="text" name="ct_rfq_request_a_quote_whole_button_text" class="ct_rfq_request_a_quote_whole_button_text" value="<?php echo esc_attr( get_option('ct_rfq_request_a_quote_whole_button_text') ); ?>">
	<br>
	<i><?php echo esc_html__('Set text of button ,this button will show with checkboxes,use short code , ','cloud_tech_rfq'); ?></i>
	<b><?php echo esc_html__(' [whole_sale_request_a_quote_button], ','cloud_tech_rfq'); ?></b>
	<i><?php echo esc_html__(' to show button., ','cloud_tech_rfq'); ?></i>

	<?php

}

//Enable or Disable show checkboxes on cartegory , acheive and cart page 
add_settings_field(
	'ct_rfq_place_quote_button_text',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Place Quote Button text', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_place_quote_button_text',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_place_quote_button_text'
);

function ct_rfq_place_quote_button_text( ) {

	?>
	<input type="text" name="ct_rfq_place_quote_button_text" class="ct_rfq_place_quote_button_text" value="<?php echo esc_attr( get_option('ct_rfq_place_quote_button_text') ? get_option('ct_rfq_place_quote_button_text') : 'Place Quote' ); ?>">
	
	<?php

}


add_settings_section(
	'ct-rfq-general-message-sec',         // ID used to identify this section and with which to register options.
	esc_html__( 'Message', 'cloud_tech_rfq' ),   // Title to be displayed on the administration page.
	'', // Callback used to render the description of the section.
	'ct_rfq_general_setting_section' // Page on which to add this section of options.
);

add_settings_field(
	'ct_rfq_add_to_quote_message',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Add to Quote Message', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_add_to_quote_message',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-message-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_add_to_quote_message'
);

function ct_rfq_add_to_quote_message(){
	?><textarea name="ct_rfq_add_to_quote_message" id="" cols="80" rows="5"><?php echo esc_attr(get_option('ct_rfq_add_to_quote_message')); ?></textarea>
	<br><i><?php echo esc_html__( 'Add to quote success message . use variable {product_name} to print product name.', 'cloud_tech_rfq' ); ?></i>
	<?php
}

add_settings_field(
	'ct_rfq_quote_update_message',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Update Quote Message', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_quote_update_message',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-message-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_quote_update_message'
);

function ct_rfq_quote_update_message(){
	?><textarea name="ct_rfq_quote_update_message" id="" cols="80" rows="5"><?php echo esc_attr(get_option('ct_rfq_quote_update_message')); ?></textarea>
	<br><i><?php echo esc_html__( 'Update quote success message.', 'cloud_tech_rfq' ); ?></i>
	<?php
}


add_settings_field(
	'ct_rfq_quote_submit_message',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Quote Submit Message', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_quote_submit_message',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-message-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_quote_submit_message'
);

function ct_rfq_quote_submit_message(){
	?><textarea name="ct_rfq_quote_submit_message" id="" cols="80" rows="5"><?php echo esc_attr(get_option('ct_rfq_quote_submit_message')); ?></textarea><?php
}



add_settings_section(
	'ct-rfq-general-redirect-sec',         // ID used to identify this section and with which to register options.
	esc_html__( 'Redirect Setting', 'cloud_tech_rfq' ),   // Title to be displayed on the administration page.
	'', // Callback used to render the description of the section.
	'ct_rfq_general_setting_section' // Page on which to add this section of options.
);

add_settings_field(
	'ct_rfq_enable_redirect_from_shop_and_archive_page',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Enable Redirect From Shop/Archeive Page', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_enable_redirect_from_shop_and_archive_page',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-redirect-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_enable_redirect_from_shop_and_archive_page'
);

function ct_rfq_enable_redirect_from_shop_and_archive_page(){
	?><input type="checkbox" value="checked" name="ct_rfq_enable_redirect_from_shop_and_archive_page" <?php echo esc_attr(get_option('ct_rfq_enable_redirect_from_shop_and_archive_page')); ?>><?php
}


add_settings_field(
	'ct_rfq_enable_redirect_from_product_page',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Enable Redirect From Product Page', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_enable_redirect_from_product_page',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-redirect-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_enable_redirect_from_product_page'
);

function ct_rfq_enable_redirect_from_product_page(){
	?><input type="checkbox" value="checked" name="ct_rfq_enable_redirect_from_product_page" <?php echo esc_attr(get_option('ct_rfq_enable_redirect_from_product_page')); ?>><?php
}

add_settings_field(
	'ct_rfq_enable_redirect_from_quote_table_pg',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Enable Redirect From Quote Table Page', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_enable_redirect_from_quote_table_pg',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-redirect-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_enable_redirect_from_quote_table_pg'
);

function ct_rfq_enable_redirect_from_quote_table_pg(){
	?><input type="checkbox" value="checked" name="ct_rfq_enable_redirect_from_quote_table_pg" <?php echo esc_attr(get_option('ct_rfq_enable_redirect_from_quote_table_pg')); ?>><?php
}

add_settings_field(
	'ct_rfq_enable_redirect_from_cart_and_checkout_pge',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Enable Redirect From Cart/Checkout Page', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_enable_redirect_from_cart_and_checkout_pge',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_general_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-general-redirect-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_general_setting_fields',
	'ct_rfq_enable_redirect_from_cart_and_checkout_pge'
);

function ct_rfq_enable_redirect_from_cart_and_checkout_pge(){
	?><input type="checkbox" value="checked" name="ct_rfq_enable_redirect_from_cart_and_checkout_pge" <?php echo esc_attr(get_option('ct_rfq_enable_redirect_from_cart_and_checkout_pge')); ?>><?php
}