<?php

// Register settings for "Email CSV Sale by Product"
register_setting('devsoul_psbsp_email_settings_fields', 'devsoul_psbsp_email_csv_sales_by_product_enable');
register_setting('devsoul_psbsp_email_settings_fields', 'devsoul_psbsp_email_csv_sales_by_product_recipient');
register_setting('devsoul_psbsp_email_settings_fields', 'devsoul_psbsp_email_csv_sales_by_product_subject');
register_setting('devsoul_psbsp_email_settings_fields', 'devsoul_psbsp_email_csv_sales_by_product_additional_content');

// Add settings section for "Email CSV Sale by Product"
add_settings_section(
	'devsoul_psbsp_email_csv_sales_by_product_section',
	esc_html__('Email CSV Sale by Product', 'product-sales-report'),
	'',
	'devsoul_psbsp_email_settings_sections'
);

// Add settings fields for "Email CSV Sale by Product"
add_settings_field(
	'devsoul_psbsp_email_csv_sales_by_product_enable',
	esc_html__('Enable Product CSV', 'product-sales-report'),
	'devsoul_psbsp_email_csv_sales_by_product_enable_render',
	'devsoul_psbsp_email_settings_sections',
	'devsoul_psbsp_email_csv_sales_by_product_section'
);
add_settings_field(
	'devsoul_psbsp_email_csv_sales_by_product_recipient',
	esc_html__('Recipient(s)', 'product-sales-report'),
	'devsoul_psbsp_email_csv_sales_by_product_recipient_render',
	'devsoul_psbsp_email_settings_sections',
	'devsoul_psbsp_email_csv_sales_by_product_section'
);
add_settings_field(
	'devsoul_psbsp_email_csv_sales_by_product_subject',
	esc_html__('Subject', 'product-sales-report'),
	'devsoul_psbsp_email_csv_sales_by_product_subject_render',
	'devsoul_psbsp_email_settings_sections',
	'devsoul_psbsp_email_csv_sales_by_product_section'
);
add_settings_field(
	'devsoul_psbsp_email_csv_sales_by_product_additional_content',
	esc_html__('Additional Content', 'product-sales-report'),
	'devsoul_psbsp_email_csv_sales_by_product_additional_content_render',
	'devsoul_psbsp_email_settings_sections',
	'devsoul_psbsp_email_csv_sales_by_product_section'
);

// Register settings for "Email CSV Sale by Order"
register_setting('devsoul_psbsp_email_settings_fields', 'devsoul_psbsp_email_csv_sales_by_order_enable');
register_setting('devsoul_psbsp_email_settings_fields', 'devsoul_psbsp_email_csv_sales_by_order_recipient');
register_setting('devsoul_psbsp_email_settings_fields', 'devsoul_psbsp_email_csv_sales_by_order_subject');
register_setting('devsoul_psbsp_email_settings_fields', 'devsoul_psbsp_email_csv_sales_by_order_additional_content');

// Add settings section for "Email CSV Sale by Order"
add_settings_section(
	'devsoul_psbsp_email_csv_sales_by_order_section',
	esc_html__('Email CSV Sale by Order', 'product-sales-report'),
	'',
	'devsoul_psbsp_email_settings_sections'
);

// Add settings fields for "Email CSV Sale by Order"
add_settings_field(
	'devsoul_psbsp_email_csv_sales_by_order_enable',
	esc_html__('Enable Order CSV', 'product-sales-report'),
	'devsoul_psbsp_email_csv_sales_by_order_enable_render',
	'devsoul_psbsp_email_settings_sections',
	'devsoul_psbsp_email_csv_sales_by_order_section'
);
add_settings_field(
	'devsoul_psbsp_email_csv_sales_by_order_recipient',
	esc_html__('Recipient(s)', 'product-sales-report'),
	'devsoul_psbsp_email_csv_sales_by_order_recipient_render',
	'devsoul_psbsp_email_settings_sections',
	'devsoul_psbsp_email_csv_sales_by_order_section'
);
add_settings_field(
	'devsoul_psbsp_email_csv_sales_by_order_subject',
	esc_html__('Subject', 'product-sales-report'),
	'devsoul_psbsp_email_csv_sales_by_order_subject_render',
	'devsoul_psbsp_email_settings_sections',
	'devsoul_psbsp_email_csv_sales_by_order_section'
);
add_settings_field(
	'devsoul_psbsp_email_csv_sales_by_order_additional_content',
	esc_html__('Additional Content', 'product-sales-report'),
	'devsoul_psbsp_email_csv_sales_by_order_additional_content_render',
	'devsoul_psbsp_email_settings_sections',
	'devsoul_psbsp_email_csv_sales_by_order_section'
);

// Register settings for "Auto Send Stats on Mail"
register_setting('devsoul_psbsp_email_settings_fields', 'devsoul_psbsp_auto_send_stats_on_mail_enable');
register_setting('devsoul_psbsp_email_settings_fields', 'devsoul_psbsp_auto_send_stats_on_mail_duration');
register_setting('devsoul_psbsp_email_settings_fields', 'devsoul_psbsp_auto_send_stats_on_mail_duration_number');
register_setting('devsoul_psbsp_email_settings_fields', 'devsoul_psbsp_auto_send_stats_on_mail_selected_countries');
register_setting('devsoul_psbsp_email_settings_fields', 'devsoul_psbsp_auto_send_stats_on_mail_order_status');

// Add settings section for "Auto Send Stats on Mail"
add_settings_section(
	'devsoul_psbsp_auto_send_stats_on_mail_section',
	esc_html__('Auto Send Stats on Mail', 'product-sales-report'),
	'',
	'devsoul_psbsp_email_settings_sections'
);

// Add settings fields for "Auto Send Stats on Mail"
add_settings_field(
	'devsoul_psbsp_auto_send_stats_on_mail_enable',
	esc_html__('Enable Checkbox', 'product-sales-report'),
	'devsoul_psbsp_auto_send_stats_on_mail_enable_render',
	'devsoul_psbsp_email_settings_sections',
	'devsoul_psbsp_auto_send_stats_on_mail_section'
);
add_settings_field(
	'devsoul_psbsp_auto_send_stats_on_mail_duration',
	esc_html__('Select Time Duration', 'product-sales-report'),
	'devsoul_psbsp_auto_send_stats_on_mail_duration_render',
	'devsoul_psbsp_email_settings_sections',
	'devsoul_psbsp_auto_send_stats_on_mail_section'
);
add_settings_field(
	'devsoul_psbsp_auto_send_stats_on_mail_duration_number',
	esc_html__('Time Duration Number', 'product-sales-report'),
	'devsoul_psbsp_auto_send_stats_on_mail_duration_number_render',
	'devsoul_psbsp_email_settings_sections',
	'devsoul_psbsp_auto_send_stats_on_mail_section'
);
add_settings_field(
	'devsoul_psbsp_auto_send_stats_on_mail_selected_countries',
	esc_html__('Select Countries', 'product-sales-report'),
	'devsoul_psbsp_auto_send_stats_on_mail_selected_countries_render',
	'devsoul_psbsp_email_settings_sections',
	'devsoul_psbsp_auto_send_stats_on_mail_section'
);
add_settings_field(
	'devsoul_psbsp_auto_send_stats_on_mail_order_status',
	esc_html__('Order Status', 'product-sales-report'),
	'devsoul_psbsp_auto_send_stats_on_mail_order_status_render',
	'devsoul_psbsp_email_settings_sections',
	'devsoul_psbsp_auto_send_stats_on_mail_section'
);

// Render function for "Enable Checkbox" in Email CSV Sale by Product
function devsoul_psbsp_email_csv_sales_by_product_enable_render() {
	$enable = get_option('devsoul_psbsp_email_csv_sales_by_product_enable');
	?>
	<input type="checkbox" name="devsoul_psbsp_email_csv_sales_by_product_enable" value="yes" <?php checked($enable, 'yes'); ?>>
	<p><?php esc_html_e('Enable checkbox to automatically send CSV reports of product sales via email.', 'product-sales-report'); ?>
	</p>
	<?php
}

// Render function for "Recipient(s)" in Email CSV Sale by Product
function devsoul_psbsp_email_csv_sales_by_product_recipient_render() {
	$recipient = get_option('devsoul_psbsp_email_csv_sales_by_product_recipient');
	?>
	<textarea name="devsoul_psbsp_email_csv_sales_by_product_recipient"><?php echo esc_textarea($recipient); ?></textarea>
	<p><?php esc_html_e('Enter the email addresses to which CSV reports should be sent. Separate multiple addresses with commas.', 'product-sales-report'); ?>
	</p>
	<?php
}

// Render function for "Subject" in Email CSV Sale by Product
function devsoul_psbsp_email_csv_sales_by_product_subject_render() {
	$subject = get_option('devsoul_psbsp_email_csv_sales_by_product_subject', 'Sale By Products');
	?>
	<input type="text" name="devsoul_psbsp_email_csv_sales_by_product_subject" value="<?php echo esc_attr($subject); ?>">
	<p><?php esc_html_e('Enter email subject line for CSV sales report.', 'product-sales-report'); ?></p>
	<?php
}

// Render function for "Additional Content" in Email CSV Sale by Product
function devsoul_psbsp_email_csv_sales_by_product_additional_content_render() {
	$additional_content = get_option('devsoul_psbsp_email_csv_sales_by_product_additional_content');
	?>
	<textarea
		name="devsoul_psbsp_email_csv_sales_by_product_additional_content"><?php echo esc_textarea($additional_content); ?></textarea>
	<p><?php esc_html_e('Add any extra content you want to include in the body of the email.', 'product-sales-report'); ?>
	</p>
	<?php
}

// Render function for "Enable Checkbox" in Email CSV Sale by Order
function devsoul_psbsp_email_csv_sales_by_order_enable_render() {
	$enable = get_option('devsoul_psbsp_email_csv_sales_by_order_enable');
	?>
	<input type="checkbox" name="devsoul_psbsp_email_csv_sales_by_order_enable" value="yes" <?php checked($enable, 'yes'); ?>>
	<p><?php esc_html_e('Enable checkbox to automatically send CSV reports of order sales via email.', 'product-sales-report'); ?>
	</p>
	<?php
}

// Render function for "Recipient(s)" in Email CSV Sale by Order
function devsoul_psbsp_email_csv_sales_by_order_recipient_render() {
	$recipient = get_option('devsoul_psbsp_email_csv_sales_by_order_recipient');
	?>
	<textarea name="devsoul_psbsp_email_csv_sales_by_order_recipient"><?php echo esc_textarea($recipient); ?></textarea>
	<p><?php esc_html_e('Enter the email addresses to which CSV reports should be sent. Separate multiple addresses with commas.', 'product-sales-report'); ?>
	</p>
	<?php
}

// Render function for "Subject" in Email CSV Sale by Order
function devsoul_psbsp_email_csv_sales_by_order_subject_render() {
	$subject = get_option('devsoul_psbsp_email_csv_sales_by_order_subject', 'Sale By Order');
	?>
	<input type="text" name="devsoul_psbsp_email_csv_sales_by_order_subject" value="<?php echo esc_attr($subject); ?>">
	<p><?php esc_html_e('Enter email subject line for CSV sales report.', 'product-sales-report'); ?></p>
	<?php
}

// Render function for "Additional Content" in Email CSV Sale by Order
function devsoul_psbsp_email_csv_sales_by_order_additional_content_render() {
	$additional_content = get_option('devsoul_psbsp_email_csv_sales_by_order_additional_content');
	?>
	<textarea
		name="devsoul_psbsp_email_csv_sales_by_order_additional_content"><?php echo esc_textarea($additional_content); ?></textarea>
	<p><?php esc_html_e('Add any extra content you want to include in the body of the email.', 'product-sales-report'); ?>
	</p>
	<?php
}

// Render function for "Enable Checkbox" in Auto Send Stats on Mail
function devsoul_psbsp_auto_send_stats_on_mail_enable_render() {
	$enable = get_option('devsoul_psbsp_auto_send_stats_on_mail_enable');
	?>
	<input type="checkbox" name="devsoul_psbsp_auto_send_stats_on_mail_enable" value="yes" <?php checked($enable, 'yes'); ?>>
	<p><?php esc_html_e('Enable checkbox to automatically send statistical reports via email at regular intervals.', 'product-sales-report'); ?>
	</p>
	<?php
}

// Render function for "Select Time Duration" in Auto Send Stats on Mail
function devsoul_psbsp_auto_send_stats_on_mail_duration_render() {
	$duration = get_option('devsoul_psbsp_auto_send_stats_on_mail_duration', 'hours');
	?>
	<select style="width:30%;" name="devsoul_psbsp_auto_send_stats_on_mail_duration">
		<option value="hours" <?php selected($duration, 'hours'); ?>>
			<?php esc_html_e('Hours', 'product-sales-report'); ?>
		</option>
		<option value="days" <?php selected($duration, 'days'); ?>>
			<?php esc_html_e('Days', 'product-sales-report'); ?>
		</option>
		<option value="weeks" <?php selected($duration, 'weeks'); ?>>
			<?php esc_html_e('Weeks', 'product-sales-report'); ?>
		</option>
	</select>
	<p><?php esc_html_e('Select the time duration for sending the statistical reports. Choose from hours, days, or weeks.', 'product-sales-report'); ?>
	</p>
	<?php
}

// Render function for "Time Duration Number" in Auto Send Stats on Mail
function devsoul_psbsp_auto_send_stats_on_mail_duration_number_render() {
	$duration_number = get_option('devsoul_psbsp_auto_send_stats_on_mail_duration_number', '1');
	?>
	<input type="number" name="devsoul_psbsp_auto_send_stats_on_mail_duration_number"
		value="<?php echo esc_attr($duration_number); ?>">
	<p><?php esc_html_e('Enter the number corresponding to the selected time duration for report frequency.', 'product-sales-report'); ?>
	</p>
	<?php
}

// Render function for "Select Countries" in Auto Send Stats on Mail
function devsoul_psbsp_auto_send_stats_on_mail_selected_countries_render() {
	$selected_countries = (array) get_option('devsoul_psbsp_auto_send_stats_on_mail_selected_countries');
	?>
	<select style="width:30%;" name="devsoul_psbsp_auto_send_stats_on_mail_selected_countries[]"
		class="ct-psbsp-live-search" multiple>
		<?php
		$obj_countries = new WC_Countries();
		foreach ($obj_countries->get_countries() as $key => $value) :
			?>
			<option value="<?php echo esc_attr($key); ?>" <?php selected(in_array($key, $selected_countries)); ?>>
				<?php echo esc_html($value); ?>
			</option>
		<?php endforeach ?>
	</select>
	<p><?php esc_html_e('Select the countries for which you want to receive statistical reports. Hold down the Ctrl key (Cmd key on Mac) to select multiple countries.', 'product-sales-report'); ?>
	</p>
	<?php
}

// Render function for "Order Status" in Auto Send Stats on Mail
function devsoul_psbsp_auto_send_stats_on_mail_order_status_render() {
	$order_statuses = (array) get_option('devsoul_psbsp_auto_send_stats_on_mail_order_status');
	?>
	<select style="width:30%;" name="devsoul_psbsp_auto_send_stats_on_mail_order_status[]" class="ct-psbsp-live-search"
		multiple>
		<?php foreach (wc_get_order_statuses() as $status => $label) : ?>
			<option value="<?php echo esc_attr($status); ?>" <?php selected(in_array($status, $order_statuses)); ?>>
				<?php echo esc_html($label); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<p><?php esc_html_e('Select the order statuses for which you want to receive statistical reports. Hold down the Ctrl key (Cmd key on Mac) to select multiple statuses.', 'product-sales-report'); ?>
	</p>
	<?php
}