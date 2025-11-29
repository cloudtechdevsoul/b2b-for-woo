<?php

add_settings_section(
	'ct-rfq-general-sec',         // ID used to identify this section and with which to register options.
	esc_html__('Place Quote Setting', 'cloud_tech_rfq'), // Title to be displayed on the administration page.
	'', // Callback used to render the description of the section.
	'ct_rfq_pdf_and_csv_section' // Page on which to add this section of options.
);

add_settings_field(
	'ct_rfq_enable_csv',                      // ID used to identify the field throughout the theme.
	esc_html__('Enable CSV', 'cloud_tech_rfq'),    // The label to the left of the option interface element.
	'ct_rfq_enable_csv',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_pdf_and_csv_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_pdf_and_csv_fields',
	'ct_rfq_enable_csv'
);



function ct_rfq_enable_csv()
{
	?><input type="checkbox" name="ct_rfq_enable_csv" value="checked" <?php echo esc_attr(get_option('ct_rfq_enable_csv')) ?>>
	<?php
}


add_settings_field(
	'ct_rfq_enable_pdf',                      // ID used to identify the field throughout the theme.
	esc_html__('Enable PDF', 'cloud_tech_rfq'),    // The label to the left of the option interface element.
	'ct_rfq_enable_pdf',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_pdf_and_csv_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_pdf_and_csv_fields',
	'ct_rfq_enable_pdf'
);



function ct_rfq_enable_pdf()
{
	?><input type="checkbox" name="ct_rfq_enable_pdf" value="checked" <?php echo esc_attr(get_option('ct_rfq_enable_pdf')) ?>>
	<?php
}


add_settings_field(
	'ct_rfq_enable_company_name',                      // ID used to identify the field throughout the theme.
	esc_html__('Company Name', 'cloud_tech_rfq'),    // The label to the left of the option interface element.
	'ct_rfq_enable_company_name',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_pdf_and_csv_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_pdf_and_csv_fields',
	'ct_rfq_enable_company_name'
);



function ct_rfq_enable_company_name()
{
	?><input type="text" name="ct_rfq_enable_company_name"
		value="<?php echo esc_attr(get_option('ct_rfq_enable_company_name')) ?>">
	<?php
}






add_settings_field(
	'ct_rfq_enable_select_template',                      // ID used to identify the field throughout the theme.
	esc_html__('Select Template', 'cloud_tech_rfq'),    // The label to the left of the option interface element.
	'ct_rfq_enable_select_template',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_pdf_and_csv_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_pdf_and_csv_fields',
	'ct_rfq_enable_select_template'
);



function ct_rfq_enable_select_template()
{
	$templateds = ['template-1st', 'template-2nd'];

	?>
	<select name="ct_rfq_enable_select_template" id="ct_rfq_enable_select_template">

		<?php foreach ($templateds as $value) { ?>
			<option value="<?php echo esc_attr($value); ?>" <?php selected($value, get_option('ct_rfq_enable_select_template')) ?>>
				<?php echo esc_attr(ucfirst(str_replace('-', ' ', $value))); ?>
			</option>
		<?php } ?>

	</select>

	<?php foreach ($templateds as $value) { ?>
		<div class="ct-rfq-template-image ct-rfq-<?php echo esc_attr($value); ?>"><img style="width:200px;height:200px;"
				src="<?php echo esc_url(CT_RFQ_PLUGIN_URL . 'assets/images/pdf/' . $value . '.png'); ?>" alt=""></div>
	<?php } ?>

	<?php
}

add_settings_field(
	'ct_rfq_enable_term_and_conditions',                      // ID used to identify the field throughout the theme.
	esc_html__('Enable Term And Conditions', 'cloud_tech_rfq'),    // The label to the left of the option interface element.
	'ct_rfq_enable_term_and_conditions',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_pdf_and_csv_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_pdf_and_csv_fields',
	'ct_rfq_enable_term_and_conditions'
);
function ct_rfq_enable_term_and_conditions()
{
	?><input type="checkbox" name="ct_rfq_enable_term_and_conditions" class="ct_rfq_enable_term_and_conditions"
		value="checked" <?php echo esc_attr(get_option('ct_rfq_enable_term_and_conditions')) ?>>
	<?php
}



add_settings_field(
	'ct_rfq_term_and_condition_data',                      // ID used to identify the field throughout the theme.
	esc_html__('Term And Conditions', 'cloud_tech_rfq'),    // The label to the left of the option interface element.
	'ct_rfq_term_and_condition_data',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_pdf_and_csv_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_pdf_and_csv_fields',
	'ct_rfq_term_and_condition_data'
);
function ct_rfq_term_and_condition_data()
{

	wp_editor(get_option('ct_rfq_term_and_condition_data'), 'ct_rfq_term_and_condition_data', );
}


add_settings_field(
	'ct_rfq_layout_background_color',                      // ID used to identify the field throughout the theme.
	esc_html__('Layout Background Color', 'cloud_tech_rfq'),    // The label to the left of the option interface element.
	'ct_rfq_layout_background_color',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_pdf_and_csv_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_pdf_and_csv_fields',
	'ct_rfq_layout_background_color'
);



function ct_rfq_layout_background_color()
{
	?><input type="color" name="ct_rfq_layout_background_color"
		value="<?php echo esc_attr(get_option('ct_rfq_enable_company_address')) ?>">
	<?php
}

add_settings_field(
	'ct_rfq_text_background_color',                      // ID used to identify the field throughout the theme.
	esc_html__('Text Background Color', 'cloud_tech_rfq'),    // The label to the left of the option interface element.
	'ct_rfq_text_background_color',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_pdf_and_csv_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_pdf_and_csv_fields',
	'ct_rfq_text_background_color'
);



function ct_rfq_text_background_color()
{
	?><input type="color" name="ct_rfq_text_background_color"
		value="<?php echo esc_attr(get_option('ct_rfq_text_background_color')) ?>">
	<?php
}

add_settings_field(
	'ct_rfq_allowed_pdf',                      // ID used to identify the field throughout the theme.
	esc_html__('Show Columns in PDF/CSV', 'cloud_tech_rfq'),    // The label to the left of the option interface element.
	'ct_rfq_allowed_pdf',   // The name of the function responsible for rendering the option interface.
	'ct_rfq_pdf_and_csv_section',   // The page on which this option will be displayed.
	'ct-rfq-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_rfq_pdf_and_csv_fields',
	'ct_rfq_allowed_pdf'
);


function ct_rfq_allowed_pdf()
{
	$psd_csv_coulmn = ['thumbnail', 'item', 'quantity', 'price', 'subtotal', 'offered_price', 'offered_subtotal', 'quote_subtotal', 'quote_offered_total', 'quote_total_tax', 'quote_total_total', 'billing_shipping_detail'];
	?>
	<select style="width:50%;" name="ct_rfq_allowed_pdf[]" multiple id="" class="ct-rfq-live-search">

		<?php foreach ($psd_csv_coulmn as $column_name) { ?>
			<option value="<?php echo esc_attr($column_name); ?>" <?php echo esc_attr(in_array($column_name, (array) get_option('ct_rfq_allowed_pdf')) ? 'selected' : ''); ?>>
				<?php echo esc_attr(ucwords(str_replace('_', ' ', $column_name))); ?>
			</option>
		<?php } ?>

	</select>
	<?php
}