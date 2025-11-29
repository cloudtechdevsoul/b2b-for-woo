<?php

/**
 * Define class.
 */
if (!defined('ABSPATH')) {
	die;
}

/**
 * Class start.
 */
class Invoice_Method_Methhod_and_Invoice_Pdf
{

	public function __construct()
	{
		$this->cloudtech_wipo_global_constents_vars();

		add_action('init', [$this, 'cloudtech_wipo_register_custom_post']);
		add_filter('woocommerce_payment_gateways', array($this, 'cloudtech_wipo_creat_object_gateway'), 999999);
		add_filter('woocommerce_available_payment_gateways', array($this, 'cloudtech_wipo_woocs_filter_payment_gateways'), 999999);
		add_action('woocommerce_loaded', array($this, 'af_if_include_gateway_class'), 999999);

		if (is_admin()) {
			include CLOUDTECH_WIPOAP_PLUGIN_DIR . 'class-cloudtech-wipoap-admin.php';
		}

		include CLOUDTECH_WIPOAP_PLUGIN_DIR . 'include/ct-general-functions.php';
		include CLOUDTECH_WIPOAP_PLUGIN_DIR . 'include/class-cloudtech-wipoap-shipping-virtual.php';
		include CLOUDTECH_WIPOAP_PLUGIN_DIR . 'include/class-cloudtech-wipoap-cart-items-check.php';
		include CLOUDTECH_WIPOAP_PLUGIN_DIR . 'include/class-cloudtech-wipoap-location-check.php';
	}




	public function cloudtech_wipo_register_custom_post()
	{
		$labels = array(
			'name' => __('Invoice Payment Option', 'cloudtech_wipo'),
			'singular_name' => __('Invoice Payment Option', 'cloudtech_wipo'),
			'menu_name' => __('Invoice Payment Option', 'cloudtech_wipo'),
			'name_admin_bar' => __('Invoice Payment Option', 'cloudtech_wipo'),
			'add_new' => __('Add New', 'cloudtech_wipo'),
			'add_new_item' => __('Add New Rule', 'cloudtech_wipo'),
			'new_item' => __('New Rule', 'cloudtech_wipo'),
			'edit_item' => __('Edit Rule', 'cloudtech_wipo'),
			'view_item' => __('View Rule', 'cloudtech_wipo'),
			'all_items' => __('Invoice Payment Option And PDF', 'cloudtech_wipo'),
			'search_items' => __('Search Rules', 'cloudtech_wipo'),
			'not_found' => __('No Rule created.', 'cloudtech_wipo'),
		);
		$args = array(
			'supports' => array('title'),
			'labels' => $labels,
			'public' => true,
			'show_in_menu' => 'b2bking',
			'query_var' => true,
			'rewrite' => array('slug' => 'payment_gateway'),
			'has_archive' => true,
			'hierarchical' => false,
			'menu_icon' => plugins_url('include/images/addifylogo.png', __FILE__),
			'show_ui' => true,
			'can_export' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'capability_type' => 'post',
		);
		register_post_type('payment_gateway', $args);
	}

	public function af_if_include_gateway_class()
	{
	
		include_once CLOUDTECH_WIPOAP_PLUGIN_DIR . 'class-cloudtech-wipoap-invoice-payment-gateway.php';
	}

	public function cloudtech_wipo_woocs_filter_payment_gateways($gateway_list)
	{
		if (!is_checkout()) {
			return $gateway_list;
		}
		if (isset($_POST['woocommerce-process-checkout-nonce'])) {

			return $gateway_list;
		}

		if (isset($_POST['security'])) {
			check_ajax_referer('update-order-review', 'security');
		}

		$gateways = WC()->payment_gateways->payment_gateways();
		$enabled_gateways = array();
		$flag = false;

		if ($gateways) {
			foreach ($gateways as $gateway) {

				if ('yes' == $gateway->enabled) {
					if (
						'Invoice Payments' == $gateway->method_title
					) {
						$flag = true;

					}
				}
			}
		}

		if (true == $flag) {

			$form_data = $_POST;
			$af_check_res = new Cloudtech_Wipoap_Shipping_Virtual();
			$payment_rule = $af_check_res->get_py_ig_all_rules($form_data);
			if (!$payment_rule) {
				unset($gateway_list['invoice']);
				return $gateway_list;
			} else {
				if (!isset($gateway_list['invoice'])) {
					$gateway_list['invoice'] = new Cloudtech_Wipoap_Inovice_Pyament_Gateway();
				}
				return $gateway_list;
			}
		} else {
			return $gateway_list;
		}
	}
	public function cloudtech_wipo_creat_object_gateway($methods)
	{

		$methods[] = 'Cloudtech_Wipoap_Inovice_Pyament_Gateway';
		return $methods;
	}

	public function cloudtech_wipo_global_constents_vars()
	{
		if (!defined('CLOUDTECH_WIPOAP_URL')) {
			define('CLOUDTECH_WIPOAP_URL', plugin_dir_url(__FILE__));
		}
		if (!defined('CLOUDTECH_WIPOAP_BASENAME')) {
			define('CLOUDTECH_WIPOAP_BASENAME', plugin_basename(__FILE__));
		}
		if (!defined('CLOUDTECH_WIPOAP_PLUGIN_DIR')) {
			define('CLOUDTECH_WIPOAP_PLUGIN_DIR', plugin_dir_path(__FILE__));
		}
	}

}

if (class_exists('Invoice_Method_Methhod_and_Invoice_Pdf')) {
	new Invoice_Method_Methhod_and_Invoice_Pdf();
}


// Add a custom column to the WooCommerce order table
add_filter('manage_edit-shop_order_columns', 'add_send_pdf_column');
function add_send_pdf_column($columns)
{
	$columns['send_pdf'] = __('Send PDF', 'text-domain');
	return $columns;
}

// Populate the custom column with the "Send PDF" button
add_action('manage_shop_order_posts_custom_column', 'render_send_pdf_column_content', 10, 2);
function render_send_pdf_column_content($column, $post_id)
{
	if ($column == 'send_pdf') {
		echo '<i class="button button-primary ct_send_invoice_current_order" data-order_id="' . esc_attr($post_id) . '">Send PDF</i>';
	}
}

// Add "Send PDF" to bulk actions
add_filter('bulk_actions-edit-shop_order', 'add_send_pdf_bulk_action');
function add_send_pdf_bulk_action($bulk_actions)
{
	$bulk_actions['send_pdf'] = __('Send PDF', 'text-domain');
	return $bulk_actions;
}

// Handle the bulk action when "Send PDF" is selected
add_filter('handle_bulk_actions-edit-shop_order', 'handle_send_pdf_bulk_action', 10, 3);
function handle_send_pdf_bulk_action($redirect_to, $doaction, $post_ids)
{
	if ($doaction == 'send_pdf' && !empty($post_ids)) {
		foreach ($post_ids as $order_id) {
			ct_ipmaip_send_invoice_pdf($order_id);

		}
		$redirect_to = add_query_arg('send_pdf_success', count($post_ids), $redirect_to);
	}
	return $redirect_to;
}