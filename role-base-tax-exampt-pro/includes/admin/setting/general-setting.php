<?php

/**
 * General Settings of plugin
 */

add_settings_section(
	'ct-tepfw-general-sec',         // ID used to identify this section and with which to register options.
	esc_html__('General Settings', 'cloud_tech_tepfw'),   // Title to be displayed on the administration page.
	'ct_tepfw_general_section_callback', // Callback used to render the description of the section.
	'ct_tepfw_general_setting_section' // Page on which to add this section of options.
);

//Enable or Disable auto tax exemption
add_settings_field(
	'ct_tepfw_tax_exempt_position_on_checkout',                      // ID used to identify the field throughout the theme.
	esc_html__('Tax Exemption On Checkout', 'cloud_tech_tepfw'),    // The label to the left of the option interface element.
	'ct_tepfw_tax_exempt_position_on_checkout',   // The name of the function responsible for rendering the option interface.
	'ct_tepfw_general_setting_section',   // The page on which this option will be displayed.
	'ct-tepfw-general-sec',         // The name of the section to which this field belongs.
);
register_setting(
	'ct_tepfw_general_setting_fields',
	'ct_tepfw_tax_exempt_position_on_checkout'
);
//Enable or Disable auto tax exemption
add_settings_field(
	'ct_tepfw_enable_auto_tax_exempttion',                      // ID used to identify the field throughout the theme.
	esc_html__('Remove Tax Automatically', 'cloud_tech_tepfw'),    // The label to the left of the option interface element.
	'simple_ct_tepfw_enable_auto_tax_exempttion_callback',   // The name of the function responsible for rendering the option interface.
	'ct_tepfw_general_setting_section',   // The page on which this option will be displayed.
	'ct-tepfw-general-sec',         // The name of the section to which this field belongs.
	array(
		'description' => esc_html__('Automatically remove tax from checkout. Keep this unchecked if you want to show a checkbox on checkout page to let customers manually remove tax.', 'cloud_tech_tepfw')
	)
);

register_setting(
	'ct_tepfw_general_setting_fields',
	'ct_tepfw_enable_auto_tax_exempttion'
);


//Enable Text field
add_settings_field(
	'ct_tepfw_enable_text_field[]',                      // ID used to identify the field throughout the theme.
	esc_html__('Enable Text Field', 'cloud_tech_tepfw'),    // The label to the left of the option interface element.
	'ct_tepfw_enable_text_field_callback',   // The name of the function responsible for rendering the option interface.
	'ct_tepfw_general_setting_section',   // The page on which this option will be displayed.
	'ct-tepfw-general-sec',         // The name of the section to which this field belongs.
	array(
		'description' => esc_html__('This text field will be shown in tax form in user my account page. This field can be used to collect name, tax id etc.', 'cloud_tech_tepfw')
	)
);

register_setting(
	'ct_tepfw_general_setting_fields',
	'ct_tepfw_enable_text_field'
);


//Label for Text field
add_settings_field(
	'ct_tepfw_text_field_label',                      // ID used to identify the field throughout the theme.
	esc_html__('Text Field Label', 'cloud_tech_tepfw'),    // The label to the left of the option interface element.
	'ct_tepfw_text_field_label_callback',   // The name of the function responsible for rendering the option interface.
	'ct_tepfw_general_setting_section',   // The page on which this option will be displayed.
	'ct-tepfw-general-sec',         // The name of the section to which this field belongs.
	array(
		'description' => esc_html__('Label for text field.', 'cloud_tech_tepfw')
	)
);

register_setting(
	'ct_tepfw_general_setting_fields',
	'ct_tepfw_text_field_label'
);


//Enable Textarea field
add_settings_field(
	'ct_tepfw_enable_textarea_field',                      // ID used to identify the field throughout the theme.
	esc_html__('Enable Textarea Field', 'cloud_tech_tepfw'),    // The label to the left of the option interface element.
	'ct_tepfw_enable_textarea_field_callback',   // The name of the function responsible for rendering the option interface.
	'ct_tepfw_general_setting_section',   // The page on which this option will be displayed.
	'ct-tepfw-general-sec',         // The name of the section to which this field belongs.
	array(
		'description' => esc_html__('This textarea field will be shown in tax form in user my account page. This field can be used to collect additional info etc.', 'cloud_tech_tepfw')
	)
);

register_setting(
	'ct_tepfw_general_setting_fields',
	'ct_tepfw_enable_textarea_field'
);

//Label for Textarea field
add_settings_field(
	'ct_tepfw_textarea_field_label',                      // ID used to identify the field throughout the theme.
	esc_html__('Textarea Field Label', 'cloud_tech_tepfw'),    // The label to the left of the option interface element.
	'ct_tepfw_textarea_field_label_callback',   // The name of the function responsible for rendering the option interface.
	'ct_tepfw_general_setting_section',   // The page on which this option will be displayed.
	'ct-tepfw-general-sec',         // The name of the section to which this field belongs.
	array(
		'description' => esc_html__('Label for textarea field.', 'cloud_tech_tepfw')
	)
);

register_setting(
	'ct_tepfw_general_setting_fields',
	'ct_tepfw_textarea_field_label'
);

//Enable File upload field
add_settings_field(
	'ct_tepfw_enable_fileupload_field',                      // ID used to identify the field throughout the theme.
	esc_html__('Enable Fileupload Field', 'cloud_tech_tepfw'),    // The label to the left of the option interface element.
	'ct_tepfw_enable_fileupload_field_callback',   // The name of the function responsible for rendering the option interface.
	'ct_tepfw_general_setting_section',   // The page on which this option will be displayed.
	'ct-tepfw-general-sec',         // The name of the section to which this field belongs.
	array(
		'description' => esc_html__('This file upload field will be shown in tax form in user my account page. This field can be used to collect tax certificate etc.', 'cloud_tech_tepfw')
	)
);

register_setting(
	'ct_tepfw_general_setting_fields',
	'ct_tepfw_enable_fileupload_field'
);

//Label for Textarea field
add_settings_field(
	'ct_tepfw_fileupload_field_label',                      // ID used to identify the field throughout the theme.
	esc_html__('File Upload Field Label', 'cloud_tech_tepfw'),    // The label to the left of the option interface element.
	'ct_tepfw_fileupload_field_label_callback',   // The name of the function responsible for rendering the option interface.
	'ct_tepfw_general_setting_section',   // The page on which this option will be displayed.
	'ct-tepfw-general-sec',         // The name of the section to which this field belongs.
	array(
		'description' => esc_html__('Label for fileupload field.', 'cloud_tech_tepfw')
	)
);

register_setting(
	'ct_tepfw_general_setting_fields',
	'ct_tepfw_fileupload_field_label'
);

//Allowed upload file types
add_settings_field(
	'ct_tepfw_allowed_file_types',                      // ID used to identify the field throughout the theme.
	esc_html__('Allowed Upload File Types', 'cloud_tech_tepfw'),    // The label to the left of the option interface element.
	'ct_tepfw_allowed_file_types_callback',   // The name of the function responsible for rendering the option interface.
	'ct_tepfw_general_setting_section',   // The page on which this option will be displayed.
	'ct-tepfw-general-sec',         // The name of the section to which this field belongs.
	array(
		'description' => esc_html__('Specify allowed upload file types. Add comma(,) separated values like doc,pdf , etc to allow multiple file types.', 'cloud_tech_tepfw')
	)
);

register_setting(
	'ct_tepfw_general_setting_fields',
	'ct_tepfw_allowed_file_types'
);

/**
 * Heading of general settings.
 */
function ct_tepfw_general_section_callback()
{ ?>
	<p>
		<?php esc_html_e('In general settings you can set auto/manual tax exemption choose which field(s) you want to show on the tax exemption request form.', 'cloud_tech_tepfw'); ?>
	</p>
	<?php
}

function simple_ct_tepfw_enable_auto_tax_exempttion_callback($args)
{

	?>


	<input type="checkbox" name="ct_tepfw_enable_auto_tax_exempttion" id="ct_tepfw_enable_auto_tax_exempttion" value="yes"
		<?php echo checked('yes', esc_attr(get_option('ct_tepfw_enable_auto_tax_exempttion'))); ?> />
	<p class="ct_tepfw_des">
		<?php echo wp_kses_post($args['description']); ?>
	</p>
	<?php
}

function ct_tepfw_enable_text_field_callback($args)
{

	$values = (array) (get_option('ct_tepfw_enable_text_field'));

	?>


	<input class="ct_tepfw_checkbox" type="checkbox" name="ct_tepfw_enable_text_field[enable]"
		id="ct_tepfw_enable_text_field" value="enable" <?php
		if (in_array('enable', $values)) {
			echo 'checked';
		}
		?> />
	<?php echo esc_html__('Enable', 'cloud_tech_tepfw'); ?>

	<input class="ct_tepfw_checkbox" type="checkbox" name="ct_tepfw_enable_text_field[required]"
		id="ct_tepfw_enable_text_field_required" value="required" <?php
		if (in_array('required', $values)) {
			echo 'checked';
		}
		?> />
	<?php echo esc_html__('Required', 'cloud_tech_tepfw'); ?>
	<p class="ct_tepfw_des">
		<?php echo wp_kses_post($args['description']); ?>
	</p>
	<?php
}

function ct_tepfw_text_field_label_callback($args)
{

	?>


	<input type="text" name="ct_tepfw_text_field_label" id="ct_tepfw_text_field_label"
		value="<?php echo esc_attr(get_option('ct_tepfw_text_field_label')); ?>" />
	<p class="ct_tepfw_des">
		<?php echo wp_kses_post($args['description']); ?>
	</p>
	<?php
}

function ct_tepfw_enable_textarea_field_callback($args)
{

	$values = (array) (get_option('ct_tepfw_enable_textarea_field'));

	?>


	<input class="ct_tepfw_checkbox" type="checkbox" name="ct_tepfw_enable_textarea_field[enable]"
		id="ct_tepfw_enable_textarea_field" value="enable" <?php
		if (in_array('enable', $values)) {
			echo 'checked';
		}
		?> />
	<?php echo esc_html__('Enable', 'cloud_tech_tepfw'); ?>

	<input class="ct_tepfw_checkbox" type="checkbox" name="ct_tepfw_enable_textarea_field[required]"
		id="ct_tepfw_enable_textarea_field_required" value="required" <?php
		if (in_array('required', $values)) {
			echo 'checked';
		}
		?> />
	<?php echo esc_html__('Required', 'cloud_tech_tepfw'); ?>
	<p class="ct_tepfw_des">
		<?php echo wp_kses_post($args['description']); ?>
	</p>
	<?php
}

function ct_tepfw_textarea_field_label_callback($args)
{

	?>


	<input type="text" name="ct_tepfw_textarea_field_label" id="ct_tepfw_textarea_field_label"
		value="<?php echo esc_attr(get_option('ct_tepfw_textarea_field_label')); ?>" />
	<p class="ct_tepfw_des">
		<?php echo wp_kses_post($args['description']); ?>
	</p>
	<?php
}

function ct_tepfw_enable_fileupload_field_callback($args)
{

	$values = (array) (get_option('ct_tepfw_enable_fileupload_field'));

	?>


	<input class="ct_tepfw_checkbox" type="checkbox" name="ct_tepfw_enable_fileupload_field[enable]"
		id="ct_tepfw_enable_fileupload_field" value="enable" <?php
		if (in_array('enable', $values)) {
			echo 'checked';
		}
		?> />
	<?php echo esc_html__('Enable', 'cloud_tech_tepfw'); ?>

	<input class="ct_tepfw_checkbox" type="checkbox" name="ct_tepfw_enable_fileupload_field[required]"
		id="ct_tepfw_enable_fileupload_field_required" value="required" <?php
		if (in_array('required', $values)) {
			echo 'checked';
		}
		?> />
	<?php echo esc_html__('Required', 'cloud_tech_tepfw'); ?>
	<p class="ct_tepfw_des">
		<?php echo wp_kses_post($args['description']); ?>
	</p>
	<?php
}

function ct_tepfw_fileupload_field_label_callback($args)
{

	?>


	<input type="text" name="ct_tepfw_fileupload_field_label" id="ct_tepfw_fileupload_field_label"
		value="<?php echo esc_attr(get_option('ct_tepfw_fileupload_field_label')); ?>" />
	<p class="ct_tepfw_des">
		<?php echo wp_kses_post($args['description']); ?>
	</p>
	<?php
}

function ct_tepfw_allowed_file_types_callback($args)
{

	?>


	<input type="text" name="ct_tepfw_allowed_file_types" id="ct_tepfw_allowed_file_types"
		value="<?php echo esc_attr(get_option('ct_tepfw_allowed_file_types')); ?>" />
	<p class="ct_tepfw_des">
		<?php echo wp_kses_post($args['description']); ?>
	</p>
	<?php
}

function ct_tepfw_tax_exempt_position_on_checkout()
{

	$different_position = [
		'woocommerce_before_checkout_billing_form',
		'woocommerce_after_checkout_billing_form',
		'woocommerce_before_checkout_shipping_form',
		'woocommerce_after_checkout_shipping_form',
		'woocommerce_before_order_notes',
		'woocommerce_after_order_notes',
		'woocommerce_checkout_after_customer_details',
		'woocommerce_checkout_before_order_review',
		'woocommerce_review_order_before_cart_contents',
		'woocommerce_review_order_before_payment',
		'woocommerce_review_order_before_submit',
		'woocommerce_review_order_after_submit'
	];

	?>
	<select name="ct_tepfw_tax_exempt_position_on_checkout">
		<?php foreach ($different_position as $key => $value): ?>
			<option value="<?php echo esc_attr($value); ?>" <?php if ($value === get_option('ct_tepfw_tax_exempt_position_on_checkout')): ?> selected <?php endif ?>>
				<?php echo esc_attr(ucwords(str_replace('_', ' ', $value))); ?>
			</option>
		<?php endforeach ?>
	</select>
	<?php



}