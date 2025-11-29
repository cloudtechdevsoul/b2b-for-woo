<?php

if ( ! defined('ABSPATH') ) {
	exit;
}


add_settings_section(
			'ct_rbpaqp_customer_table', // ID used to identify this section and with which to register options.
			'',  // Title to be displayed on the administration page.
			'', // Callback used to render the description of the section.
			'ct_rbpaqp_min_max_quantity_by_role_page'   // Page on which to add this section of options.
		);

add_settings_field(
			'ct_rbpaqp_mmq_min_qty_error_message', // ID used to identify the field throughout the theme.
			esc_html__( 'Minimum Quantity Error Messages', ' cloud_tech_rbpaqpfw' ), // The label to the left of the option interface element.
			'ct_rbpaqp_mmq_min_qty_error_message',   // The name of the function responsible for rendering the option interface.
			'ct_rbpaqp_min_max_quantity_by_role_page', // The page on which this option will be displayed.
			'ct_rbpaqp_customer_table' // The name of the section to which this field belongs.
		);
register_setting(
	'ct_rbpaqp_min_max_quantity_by_role',
	'ct_rbpaqp_mmq_min_qty_error_message'
);

function ct_rbpaqp_mmq_min_qty_error_message() {

	if ( empty( get_option('ct_rbpaqp_mmq_min_qty_error_message') ) ) {
		update_option('ct_rbpaqp_mmq_min_qty_error_message','Please select atleast {min_qty} quantity to add product in your cart');
	}

	$min_error_message 	= get_option('ct_rbpaqp_mmq_min_qty_error_message');

	?>
	
	<textarea cols="60" rows="6" name="ct_rbpaqp_mmq_min_qty_error_message"><?php echo esc_attr( $min_error_message ); ?></textarea>
	<p><?php echo esc_html__('Set minimum quantity error message..Use variable {min_qty} to show user Minimum quantity limit' , 'cloud_tech_rbpaqpfw'); ?></p>

	<?php
}


add_settings_field(
			'ct_rbpaqp_mmq_max_qty_error_message', // ID used to identify the field throughout the theme.
			esc_html__( 'Maximum Quantity Error Messages', ' cloud_tech_rbpaqpfw' ), // The label to the left of the option interface element.
			'ct_rbpaqp_mmq_max_qty_error_message',   // The name of the function responsible for rendering the option interface.
			'ct_rbpaqp_min_max_quantity_by_role_page', // The page on which this option will be displayed.
			'ct_rbpaqp_customer_table' // The name of the section to which this field belongs.
		);
register_setting(
	'ct_rbpaqp_min_max_quantity_by_role',
	'ct_rbpaqp_mmq_max_qty_error_message'
);

function ct_rbpaqp_mmq_max_qty_error_message() {

	if ( empty( get_option('ct_rbpaqp_mmq_max_qty_error_message') ) ) {
		update_option('ct_rbpaqp_mmq_max_qty_error_message','Please select atleast {max_qty} quantity to add product in your cart');
	}

	$min_error_message 	= get_option('ct_rbpaqp_mmq_max_qty_error_message');

	?>
	
	<textarea cols="60" rows="6" name="ct_rbpaqp_mmq_max_qty_error_message"><?php echo esc_attr( $min_error_message ); ?></textarea>
	<p><?php echo esc_html__('Set maximum quantity error message..Use variable {max_qty} to show user maximum quantity limit' , 'cloud_tech_rbpaqpfw'); ?></p>

	<?php
}


add_settings_section(
			'ct_rbpaqp_customer_table', // ID used to identify this section and with which to register options.
			'',  // Title to be displayed on the administration page.
			'', // Callback used to render the description of the section.
			'ct_rbpaqp_min_max_quantity_by_role_page'   // Page on which to add this section of options.
		);

add_settings_field(
			'ct_rbpaqp_mmq_min_qty_error_message_crt_pg', // ID used to identify the field throughout the theme.
			esc_html__( 'Cart Page Minimum Quantity Error Messages', ' cloud_tech_rbpaqpfw' ), // The label to the left of the option interface element.
			'ct_rbpaqp_mmq_min_qty_error_message_crt_pg',   // The name of the function responsible for rendering the option interface.
			'ct_rbpaqp_min_max_quantity_by_role_page', // The page on which this option will be displayed.
			'ct_rbpaqp_customer_table' // The name of the section to which this field belongs.
		);
register_setting(
	'ct_rbpaqp_min_max_quantity_by_role',
	'ct_rbpaqp_mmq_min_qty_error_message_crt_pg'
);

function ct_rbpaqp_mmq_min_qty_error_message_crt_pg() {

	if ( empty( get_option('ct_rbpaqp_mmq_min_qty_error_message_crt_pg') ) ) {
		update_option('ct_rbpaqp_mmq_min_qty_error_message_crt_pg','Please select atleast {min_qty}  quantity of {product_name} to update product quantity');
	}

	$min_error_message 	= get_option('ct_rbpaqp_mmq_min_qty_error_message_crt_pg');

	?>
	
	<textarea cols="60" rows="6" name="ct_rbpaqp_mmq_min_qty_error_message_crt_pg"><?php echo esc_attr( $min_error_message ); ?></textarea>
	<p><?php echo esc_html__('Set minimum quantity error message..Use variable {min_qty} to show user Minimum quantity limit' , 'cloud_tech_rbpaqpfw'); ?></p>

	<?php
}


add_settings_field(
			'ct_rbpaqp_mmq_max_qty_error_message_crt_pg', // ID used to identify the field throughout the theme.
			esc_html__( 'Cart Page Maximum Quantity Error Messages', ' cloud_tech_rbpaqpfw' ), // The label to the left of the option interface element.
			'ct_rbpaqp_mmq_max_qty_error_message_crt_pg',   // The name of the function responsible for rendering the option interface.
			'ct_rbpaqp_min_max_quantity_by_role_page', // The page on which this option will be displayed.
			'ct_rbpaqp_customer_table' // The name of the section to which this field belongs.
		);
register_setting(
	'ct_rbpaqp_min_max_quantity_by_role',
	'ct_rbpaqp_mmq_max_qty_error_message_crt_pg'
);

function ct_rbpaqp_mmq_max_qty_error_message_crt_pg() {

	if ( empty( get_option('ct_rbpaqp_mmq_max_qty_error_message_crt_pg') ) ) {
		update_option('ct_rbpaqp_mmq_max_qty_error_message_crt_pg','Please select atleast {miax_qty}  quantity of {product_name} to update product quantity');
	}

	$min_error_message 	= get_option('ct_rbpaqp_mmq_max_qty_error_message_crt_pg');

	?>
	
	<textarea cols="60" rows="6" name="ct_rbpaqp_mmq_max_qty_error_message_crt_pg"><?php echo esc_attr( $min_error_message ); ?></textarea>
	<p><?php echo esc_html__('Set maximum quantity error message.Use variable {max_qty} to show user maximum quantity limit. ' , 'cloud_tech_rbpaqpfw'); ?></p>

	<?php
}