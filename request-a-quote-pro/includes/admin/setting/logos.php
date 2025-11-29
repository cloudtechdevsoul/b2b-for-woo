<?php

add_settings_section(
	'ct-rfq-general-sec',         // ID used to identify this section and with which to register options.
	'',   // Title to be displayed on the administration page.
	'', // Callback used to render the description of the section.
	'ct_rfq_logos_section' // Page on which to add this section of options.
);

add_settings_field(
	'ct_rfq_company_logos',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Company logo', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_company_logos',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_logos_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_logos_fields',
	'ct_rfq_company_logos'
);



function ct_rfq_company_logos() {
    ?><input type="url" name="ct_rfq_company_logos" value="<?php echo esc_url( get_option('ct_rfq_company_logos') ) ?>" ><?php
}

add_settings_field(
	'ct_rfq_billing_logos',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Billing logo', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_billing_logos',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_logos_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_logos_fields',
	'ct_rfq_billing_logos'
);



function ct_rfq_billing_logos() {
    ?><input type="url" name="ct_rfq_billing_logos" value="<?php echo esc_url( get_option('ct_rfq_billing_logos') ) ?>" ><?php
}

add_settings_field(
	'ct_rfq_shipping_logos',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Shipping logo', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_shipping_logos',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_logos_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_logos_fields',
	'ct_rfq_shipping_logos'
);

function ct_rfq_shipping_logos() {
    ?><input type="url" name="ct_rfq_shipping_logos" value="<?php echo esc_url( get_option('ct_rfq_shipping_logos') ) ?>" ><?php
}


add_settings_field(
	'ct_rfq_mini_cart_logo',                      // ID used to identify the field throughout the theme.
	esc_html__( 'Mini Cart logo', 'cloud_tech_rfq' ),    // The label to the left of the option interface element.
	'ct_rfq_mini_cart_logo',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_logos_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_logos_fields',
	'ct_rfq_mini_cart_logo'
);

function ct_rfq_mini_cart_logo() {
    ?><input type="url" name="ct_rfq_mini_cart_logo" value="<?php echo esc_url( get_option('ct_rfq_mini_cart_logo') ) ?>" ><?php
}

