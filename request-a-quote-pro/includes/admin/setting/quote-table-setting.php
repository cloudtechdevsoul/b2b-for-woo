<?php

/**
 * quote_table Settings of plugin
 */

add_settings_section(
	'ct-rfq-quote_table-sec',         // ID used to identify this section and with which to register options.
	esc_html__( 'Quote Table Settings', 'cloud_tech_rfq' ),   // Title to be displayed on the administration page.
	'', // Callback used to render the description of the section.
	'ct_rfq_quote_table_setting_section' // Page on which to add this section of options.
);


add_settings_field(
	'ct_rfq_qts_show_categories',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Show Product Categories ', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_qts_show_categories',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_quote_table_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-quote_table-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_quote_table_setting_fields',
	'ct_rfq_qts_show_categories'
);

function ct_rfq_qts_show_categories( ) {

	?>
	<input type="checkbox" value="checked" name="ct_rfq_qts_show_categories" <?php echo esc_attr( get_option('ct_rfq_qts_show_categories') ); ?> >
	<?php
}


add_settings_field(
	'ct_rfq_qts_show_description',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Show Product Short Description ', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_qts_show_description',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_quote_table_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-quote_table-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_quote_table_setting_fields',
	'ct_rfq_qts_show_description'
);

function ct_rfq_qts_show_description( ) {

	?>
	<input type="checkbox" value="checked" name="ct_rfq_qts_show_description" <?php echo esc_attr( get_option('ct_rfq_qts_show_description') ); ?> >
	<?php
}

add_settings_field(
	'ct_rfq_qts_description_total_words',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Description Total Words ', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_qts_description_total_words',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_quote_table_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-quote_table-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_quote_table_setting_fields',
	'ct_rfq_qts_description_total_words'
);

function ct_rfq_qts_description_total_words( ) {

	?>
	<input type="number" min="1"  name="ct_rfq_qts_description_total_words" value="<?php echo esc_attr( get_option('ct_rfq_qts_description_total_words') ? get_option('ct_rfq_qts_description_total_words') : 500 ); ?>" >
	<?php
}


add_settings_field(
	'ct_rfq_qts_product_sku',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Show Product SKU ', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_qts_product_sku',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_quote_table_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-quote_table-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_quote_table_setting_fields',
	'ct_rfq_qts_product_sku'
);

function ct_rfq_qts_product_sku( ) {

	?><input type="checkbox" value="checked" name="ct_rfq_qts_product_sku" <?php echo esc_attr( get_option('ct_rfq_qts_product_sku') ); ?> ><?php
}

add_settings_field(
	'ct_rfq_qts_show_out_of_stock_product_stock_status',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Show Out Of Stock Product Stock Status', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_qts_show_out_of_stock_product_stock_status',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_quote_table_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-quote_table-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_quote_table_setting_fields',
	'ct_rfq_qts_show_out_of_stock_product_stock_status'
);

function ct_rfq_qts_show_out_of_stock_product_stock_status( ) {

	?><input type="checkbox" value="checked" name="ct_rfq_qts_show_out_of_stock_product_stock_status" <?php echo esc_attr( get_option('ct_rfq_qts_show_out_of_stock_product_stock_status') ); ?> ><?php
}

add_settings_field(
	'ct_rfq_qts_product_qty_box',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Show Quantity Box', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_qts_product_qty_box',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_quote_table_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-quote_table-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_quote_table_setting_fields',
	'ct_rfq_qts_product_stock_status'
);

function ct_rfq_qts_product_qty_box( ) {

	?><input type="checkbox" value="checked" name="ct_rfq_qts_product_qty_box" <?php echo esc_attr( get_option('ct_rfq_qts_product_qty_box') ); ?> ><?php
}



add_settings_field(
	'ct_rfq_qts_show_out_of_stock_product',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Show Out of Stock Product', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_qts_show_out_of_stock_product',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_quote_table_setting_section',   // The page on which this option will be displayed.
	'ct-rfq-quote_table-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_quote_table_setting_fields',
	'ct_rfq_qts_product_stock_status'
);

function ct_rfq_qts_show_out_of_stock_product( ) {

	?><input type="checkbox" value="checked" name="ct_rfq_qts_show_out_of_stock_product" <?php echo esc_attr( get_option('ct_rfq_qts_show_out_of_stock_product') ); ?> ><?php
}