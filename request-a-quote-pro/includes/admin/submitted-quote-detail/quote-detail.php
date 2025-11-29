<?php
if (!defined('WPINC')) {
	die;
}

add_action('add_meta_boxes', 'ct_rfq_quote_detail_add_meta_boxes');
add_action('save_post', 'save_post_quote');

function ct_rfq_quote_detail_add_meta_boxes()
{
	add_meta_box(
		'ct-rfq-quote-status',
		esc_html__('Quote Setting', 'cloud_tech_rfq'),
		'ct_rfq_quote_status',
		'ct-rfq-submit-quote',
		'side',
		'high'
	);

	add_meta_box(
		'ct-rfq-quote-details',
		esc_html__('Quote Detials', 'cloud_tech_rfq'),
		'ct_rfq_quote_details',
		'ct-rfq-submit-quote',
		'normal',
		'high'
	);
	add_meta_box(
		'ct-rfq-quote-cart-details',
		esc_html__('Quote Cart Detials', 'cloud_tech_rfq'),
		'ct_rfq_quote_cart_details',
		'ct-rfq-submit-quote',
		'normal',
		'high'
	);

	add_meta_box(
		'ct-rfq-quote-chays',
		esc_html__('Quote Chat', 'cloud_tech_rfq'),
		'ct_rfq_quote_chat',
		'ct-rfq-submit-quote',
		'normal',
		'high'
	);


}

function ct_rfq_quote_details()
{

	$added_products_to_quote = (array) get_post_meta(get_the_ID(), 'cloud_tech_quote_cart', true);

	$added_products_to_quote = ct_rfq_custom_array_filter($added_products_to_quote);
	if (!empty($added_products_to_quote)) {
		$check_product_detail_array = apply_filters('cloud_tech_item_detail', get_the_ID(), 'show_text');
		echo wp_kses_post($check_product_detail_array);
	}
	?>
	<i class="ct-rfq-add-new-product button button-primary button-large">
		<?php echo esc_html__('Add New Product', 'woocommerce'); ?>
	</i>
	<div class="ct-rfq-add-new-product-main-div" style="display:none;">
		<div class="ct-rfq-add-new-product-content">
			<i class="fa fa-close ct-rfq-close-popup"></i>
			<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">
				<tbody>
					<tr>
						<td><select style="width:100%;" name="ct_rfq_add_selected_product[]"
								class="ct-rfq-add-selected-product ct-rfq-product-search" multiple></select></td>
						<td><i class="ct-rfq-add-selected-product-to-quote button button-primary button-large">
								<?php echo esc_html__('Add New Product', 'woocommerce'); ?>
							</i></td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>
	<?php

}

function ct_rfq_quote_cart_details()
{

	$billing_details = (array) get_post_meta(get_the_ID(), 'cloud_tech_quote_billing_details', true);

	?>
	<?php if (empty(get_post_meta(get_the_ID(), 'current_user_email', true))) { ?>
		<input type="email" name="current_user_email" required>
	<?php } ?>

	<div class="cart-collaterals">
		<div class="cart_totals ">

			<h2>
				<?php echo esc_html__('Cart totals', 'woocommerce'); ?>
			</h2>

			<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">

				<tr class="cart-subtotal">
					<th>
						<?php echo esc_html__('Subtotal', 'woocommerce'); ?>
					</th>
					<td data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
						<?php echo wp_kses_post(wc_price(wc_get_quote_subtotal_ct(get_the_ID()))); ?>
					</td>
				</tr>


				<tr class="tax-total">
					<th>
						<?php echo esc_html(WC()->countries->tax_or_vat()); ?>
					</th>
					<td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>">
						<?php echo wp_kses_post(wc_price(wc_get_quote_tax_ct(get_the_ID()))); ?>
					</td>
				</tr>

				<tr class="order-total">
					<th>
						<?php echo esc_html__('Total', 'woocommerce'); ?>
					</th>
					<td data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>" data-total="">
						<?php echo wp_kses_post(wc_price(wc_get_quote_total_ct(get_the_ID()))); ?>
					</td>
				</tr>

			</table>

		</div>
	</div>
	<?php

	$check_product_detail_array = apply_filters('cloud_tech_quote_billing_detail', get_the_ID());
	echo wp_kses_post($check_product_detail_array);
	return;
}

function ct_rfq_quote_chat()
{

}
function ct_rfq_quote_status()
{

	?>
	<select name="quote_status">
		<?php foreach (wc_get_order_statuses() as $key => $value) { ?>
			<option value="<?php echo $key; ?>" <?php if ($key == get_post_meta(get_the_ID(), 'quote_status', true)) { ?> selected
				<?php } ?>>
				<?php echo $value; ?>
			</option>
		<?php } ?>
	</select>
	<hr>
	<i class="ct-rfq-instant-notify fa fa-notify button button-primary button-large"
		data-current_post="<?php echo esc_attr(get_the_ID()); ?>">
		<?php echo esc_html__('Instant Notify', 'cloud_tech_rfq'); ?>
	</i>

	<i class="ct-create-order button button-primary button-large" data-current_post="<?php echo esc_attr(get_the_ID()); ?>">
		<?php echo esc_html__('Create Order', 'cloud_tech_rfq'); ?>
	</i>


	<hr>



	<?php

	if (get_option('ct_rfq_enable_pdf')) {

		?>
		<a href="?download_pdf=<?php echo esc_attr(get_the_ID()); ?>"
			class="button button-primary button-large generate-pdf">Generate
			PDF</a>

		<?php
	}

	if (get_option('ct_rfq_enable_csv')) {

		?>
		<a href="?download_csv=<?php echo esc_attr(get_the_ID()); ?>"
			class="button button-primary button-large generate-pdf">Generate
			CSV</a>

		<?php
	}

	$order_created_for_this_quote = get_posts(['post_type' => 'shop_order', 'post_parent' => get_the_ID(), 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids']);

	if (count($order_created_for_this_quote) >= 1) {

		?>
		<h4>
			<?php echo esc_html__('Created Order For Current Quote', 'cloud_tech_rfq'); ?>
		</h4>
		<ul style="list-style-type: style type none;">
			<?php
			foreach ($order_created_for_this_quote as $key => $current_post_id) {
				?>
				<li><a href="<?php echo esc_url(get_edit_post_link($current_post_id)); ?>" target="_blank">#
						<?php echo esc_attr($current_post_id); ?>
					</a></li>
				<?php
			}
			?>
		</ul>
		<?php
	}
}

function save_post_quote($post_id)
{
	$status = isset($_POST['quote_status']) ? $_POST['quote_status'] : '';

	update_post_meta($post_id, 'quote_status', $status);


	$added_products_to_quote = (array) get_post_meta($post_id, 'cloud_tech_quote_cart', true);

	$added_products_to_quote = ct_rfq_custom_array_filter($added_products_to_quote);

	if (!empty($added_products_to_quote)) {
		foreach ($added_products_to_quote as $quote_key => $current_product_quote_detail) {

			if (!is_array($current_product_quote_detail)) {
				continue;
			}

			if (!isset($current_product_quote_detail['product_id']) || empty($current_product_quote_detail['product_id'])) {
				continue;
			}

			$custom_price = isset($_POST['custom_amount_' . $quote_key]) ? sanitize_text_field($_POST['custom_amount_' . $quote_key]) : '';
			$custom_qty = isset($_POST['quantity_' . $quote_key]) ? sanitize_text_field($_POST['quantity_' . $quote_key]) : 1;

			$added_products_to_quote[$quote_key]['custom_price'] = $custom_price;
			$added_products_to_quote[$quote_key]['quantity'] = $custom_qty;

		}

	}

	update_post_meta($post_id, 'cloud_tech_quote_cart', $added_products_to_quote);


}


// Add custom columns
function ct_rfq_submit_quote_custom_columns_head($defaults)
{
	unset($defaults['date']);
	$defaults['total_items'] = 'Total Items';
	$defaults['subtotal'] = 'Subtotal';
	$defaults['tax_total'] = WC()->countries->tax_or_vat();
	$defaults['total'] = 'Total';
	$defaults['status'] = 'Status';
	$defaults['notify'] = 'Notify';
	$defaults['date'] = 'Date';

	return $defaults;
}

add_filter('manage_ct-rfq-submit-quote_posts_columns', 'ct_rfq_submit_quote_custom_columns_head');

// Display custom column data
function ct_rfq_submit_quote_custom_columns_content($column_name, $post_id)
{



	if ('total_items' == $column_name) {

		$added_products_to_quote = (array) get_post_meta($post_id, 'cloud_tech_quote_cart', true);
		$added_products_to_quote = ct_rfq_custom_array_filter($added_products_to_quote);

		echo esc_attr(count($added_products_to_quote));
	}
	if ('subtotal' == $column_name) {

		echo wp_kses_post(wc_price(wc_get_quote_subtotal_ct($post_id)));
	}
	if ('status' == $column_name) {
		$ct_rfq_qoute_status = get_post_meta($post_id, 'quote_status', true) ? get_post_meta($post_id, 'quote_status', true) : current(array_keys(wc_get_order_statuses()));
		$ct_rfq_qoute_status = wc_get_order_status_name($ct_rfq_qoute_status);
		echo esc_attr($ct_rfq_qoute_status);

	}
	if ('notify' == $column_name) {
		?><i class="ct-rfq-instant-notify fa fa-notify button button-primary button-large"
			data-current_post="<?php echo esc_attr($post_id); ?>">
			<?php echo esc_html__('Instant Notify', 'cloud_tech_rfq'); ?>
		</i>
		<?php
	}
	if ('tax_total' == $column_name) {
		echo wp_kses_post(wc_price(wc_get_quote_tax_ct($post_id)));
	}
	if ('total' == $column_name) {
		echo wp_kses_post(wc_price(wc_get_quote_total_ct($post_id)));
	}

}

add_action('manage_ct-rfq-submit-quote_posts_custom_column', 'ct_rfq_submit_quote_custom_columns_content', 10, 2);


/**
 * Adds 'Profit' column header to 'Orders' page immediately after 'Total' column.
 *
 * @param string[] $columns
 * @return string[] $new_columns
 */
function sv_wc_cogs_add_order_profit_column_header($columns)
{

	unset($columns['date']);
	$columns['quote_button'] = 'Quote Detail';
	$columns['date'] = 'Date';

	return $columns;
}
add_filter('manage_edit-shop_order_columns', 'sv_wc_cogs_add_order_profit_column_header', 20);



function ct_shop_order_custom_columns_content($column_name, $post_id)
{
	// Check if the current post is a child post
	if ('quote_button' == $column_name) {
		$order = wc_get_order($post_id);
		echo $parent_id = $order->get_parent_id();

		// Check if the parent post is of the custom post type 'ct-rfq-submit-quote'
		if ($parent_id && 'ct-rfq-submit-quote' == get_post_type($parent_id)) {
			?>
			<a href="<?php echo esc_url(get_edit_post_link($parent_id)); ?>" class="button button-primary button-large">
				<?php echo esc_html__('View Quote Detail', 'cloud_tech_rfq'); ?>
			</a>
			<?php
		}
	}
}

add_action('manage_shop_order_posts_custom_column', 'ct_shop_order_custom_columns_content', 10, 2);

