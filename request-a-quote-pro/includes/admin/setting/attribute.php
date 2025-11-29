<?php

/**
 * General Settings of plugin
 */

add_settings_section(
	'ct-rfq-general-sec',         // ID used to identify this section and with which to register options.
	esc_html__( 'Quote Cart Attribute', 'cloud_tech_rfq' ),   // Title to be displayed on the administration page.
	'', // Callback used to render the description of the section.
	'ct_rfq_attribute_section' // Page on which to add this section of options.
);

//Enable or Disable auto tax exemption
add_settings_field(
	'ct_rfq_hide_cart_sub_total',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Hide Cart Sub Total', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_hide_cart_sub_total',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_attribute_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_attribute_fields',
	'ct_rfq_hide_cart_sub_total'
);


function ct_rfq_hide_cart_sub_total( ) {
	?>
	<input type="checkbox" name="ct_rfq_hide_cart_sub_total" <?php if ( ! empty( get_option('ct_rfq_hide_cart_sub_total') ) ): ?>
	checked	
	<?php endif ?> >
	<?php
	
}


//Enable or Disable auto tax exemption
add_settings_field(
	'ct_rfq_hide_cart_tax',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Hide Cart Tax', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_hide_cart_tax',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_attribute_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_attribute_fields',
	'ct_rfq_hide_cart_tax'
);


function ct_rfq_hide_cart_tax( ) {
	?>
	<input type="checkbox" name="ct_rfq_hide_cart_tax" <?php if ( ! empty( get_option('ct_rfq_hide_cart_tax') ) ): ?>
	checked	
	<?php endif ?> >
	<?php
	
}


//Enable or Disable auto tax exemption
add_settings_field(
	'ct_rfq_hide_cart_total',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Hide Cart Sub Total', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_hide_cart_total',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_attribute_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_attribute_fields',
	'ct_rfq_hide_cart_total'
);


function ct_rfq_hide_cart_total( ) {
	?>
	<input type="checkbox" name="ct_rfq_hide_cart_total" <?php if ( ! empty( get_option('ct_rfq_hide_cart_total') ) ): ?>
	checked	
	<?php endif ?> >
	<?php
	
}