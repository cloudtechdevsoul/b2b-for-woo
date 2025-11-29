<?php
if (!defined('ABSPATH')) {
	exit;
}
if(class_exists('WC_Payment_Gateway')){
class Cloudtech_Wipoap_Inovice_Pyament_Gateway extends WC_Payment_Gateway
{

	public function __construct()
	{
		$this->setup_properties();
		$this->init_form_fields();
		$this->title = $this->get_option('title');
		$this->description = $this->get_option('description');
		$this->instructions = $this->get_option('instructions');
		$this->order_status = $this->get_option('order_status');
		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		add_action('woocommerce_thankyou_invoice', array($this, 'cloudtech_wipo_thankyou_page'));
	}

	protected function setup_properties()
	{
		$this->id = 'invoice';
		$this->method_title = __('Invoice Payments', 'cloudtech_wipo');
		$this->method_description = __('Allows invoice payments.', 'cloudtech_wipo');
		$this->has_fields = false;
	}

	public function init_form_fields()
	{
		$this->form_fields = array(
			'enabled' => array(
				'title' => __('Enable/Disable', 'cloudtech_wipo'),
				'type' => 'checkbox',
				'label' => __('Enable Invoice Payment', 'cloudtech_wipo'),
				'default' => 'yes',
			),
			'title' => array(
				'title' => __('Title', 'cloudtech_wipo'),
				'type' => 'text',
				'description' => __('Title during checkout.', 'cloudtech_wipo'),
				'default' => __('Invoice Payment', 'cloudtech_wipo'),
				'desc_tip' => true,
			),
			'description' => array(
				'title' => __('Description', 'cloudtech_wipo'),
				'type' => 'textarea',
				'description' => __('Payment method description during checkout.', 'cloudtech_wipo'),
				'default' => __('Thank you for your order.', 'cloudtech_wipo'),
				'desc_tip' => true,
			),
			'instructions' => array(
				'title' => __('Instructions', 'cloudtech_wipo'),
				'type' => 'textarea',
				'description' => __('Instructions that will be added to the thank you page.', 'cloudtech_wipo'),
				'default' => __('you will be invoiced soon with regards to payment.', 'cloudtech_wipo'),
				'desc_tip' => true,
			),
			'order_status' => array(
				'title' => __('Choose an order status', 'cloudtech_wipo'),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'default' => 'on-hold',
				'description' => __('Choose the order status that will be set after checkout', 'cloudtech_wipo'),
				'options' => $this->cloudtech_wipo_get_order_status(),
				'desc_tip' => true,
				'custom_attributes' => array(
					'data-placeholder' => __('Select order status', 'cloudtech_wipo'),
				),
			),
		);
	}

	protected function cloudtech_wipo_get_order_status()
	{
		$cloudtech_wipo_all_order = wc_get_order_statuses();
		$keys = array_map(
			function ($key) {
				return str_replace('wc-', '', $key);
			},
			array_keys($cloudtech_wipo_all_order)
		);
		$status = array_combine($keys, $cloudtech_wipo_all_order);
		unset($status['cancelled']);
		unset($status['refunded']);
		unset($status['failed']);
		return $status;
	}

	public function process_payment($order_id)
	{

		$order = wc_get_order($order_id);
		$order->update_status(apply_filters('wc_invoice_gateway_process_payment_order_status', $this->order_status), __('Awaiting invoice payment', 'cloudtech_wipo'));
		wc_reduce_stock_levels($order_id);
		WC()->cart->empty_cart();
		return array(
			'result' => 'success',
			'redirect' => $this->get_return_url($order),
		);
	}

	public function cloudtech_wipo_thankyou_page()
	{
		if ($this->instructions) {
			echo wp_kses_post($this->instructions);
		}
	}
}
}
