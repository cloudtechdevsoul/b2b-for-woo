<?php
if (!defined('ABSPATH')) {
	exit;
}
add_settings_section(
	'devsoul_psbsp_general_settings_fields',
	'General Options ', // Title to be displayed on the administration page.
	'', // Callback used to render the description of the section.
	'devsoul_psbsp_general_settings_sections'
);
add_settings_field(
	'devsoul_psbsp_product_per_page_for_product_sales',
	esc_html__('Set products per page in product sales tab', ' product-sales-report'), // The label.
	function () {
		?>

	<input type="number" min="10" name="devsoul_psbsp_product_per_page_for_product_sales"
		value="<?php echo esc_attr(!empty(get_option('devsoul_psbsp_product_per_page_for_product_sales')) ? get_option('devsoul_psbsp_product_per_page_for_product_sales') : 10); ?>">

	<p><?php echo esc_html__('Specify the number of products to display per page in the Product Sales tab.', 'product-sales-report'); ?>
	</p>
	<?php
	},
	'devsoul_psbsp_general_settings_sections', // The page on which this option will be displayed.
	'devsoul_psbsp_general_settings_fields'
);
register_setting(
	'devsoul_psbsp_general_settings_fields',
	'devsoul_psbsp_product_per_page_for_product_sales'
);
add_settings_field(
	'devsoul_psbsp_product_per_page_for_order_sales',
	esc_html__('Set orders per page in order sales tab	', ' product-sales-report'), // The label.
	function () {
		?>

	<input type="number" min="10" name="devsoul_psbsp_product_per_page_for_order_sales"
		value="<?php echo esc_attr(!empty(get_option('devsoul_psbsp_product_per_page_for_order_sales')) ? get_option('devsoul_psbsp_product_per_page_for_order_sales') : 10); ?>">
	<p><?php echo esc_html__('Specify the number of orders to display per page in the Order Sales tab.', 'product-sales-report'); ?>

		<?php
	},
	'devsoul_psbsp_general_settings_sections', // The page on which this option will be displayed.
	'devsoul_psbsp_general_settings_fields'
);
register_setting(
	'devsoul_psbsp_general_settings_fields',
	'devsoul_psbsp_product_per_page_for_order_sales'
);




add_settings_section(
	'devsoul_psbsp_column_setting',
	'Column Settings', // Title to be displayed on the administration page.
	'', // Callback used to render the description of the section.
	'devsoul_psbsp_general_settings_sections'
);
add_settings_field(
	'devsoul_psbsp_stats_product_in_product_columns',
	esc_html__('Show order button in product table', ' product-sales-report'), // The label.
	function () {
		?>

		<input type="checkbox" name="devsoul_psbsp_stats_product_in_product_columns" value="checked" <?php echo esc_attr(!empty(get_option('devsoul_psbsp_stats_product_in_product_columns')) ? get_option('devsoul_psbsp_stats_product_in_product_columns') : 10); ?>>
		<?php
	},
	'devsoul_psbsp_general_settings_sections', // The page on which this option will be displayed.
	'devsoul_psbsp_column_setting'
);
register_setting(
	'devsoul_psbsp_general_settings_fields',
	'devsoul_psbsp_stats_product_in_product_columns'
);

add_settings_field(
	'devsoul_psbsp_show_total_sales_in_user_table',
	esc_html__('Display spent amount column in user table', ' product-sales-report'), // The label.
	function () {
		?>
		<input type="checkbox" name="devsoul_psbsp_show_total_sales_in_user_table" value="checked" <?php echo esc_attr(!empty(get_option('devsoul_psbsp_show_total_sales_in_user_table')) ? get_option('devsoul_psbsp_show_total_sales_in_user_table') : 10); ?>>
		<?php
	},
	'devsoul_psbsp_general_settings_sections', // The page on which this option will be displayed.
	'devsoul_psbsp_column_setting'
);
register_setting(
	'devsoul_psbsp_general_settings_fields',
	'devsoul_psbsp_show_total_sales_in_user_table'
);