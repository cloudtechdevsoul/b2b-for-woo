<?php
if (!class_exists('Order_Sales_Report_Table')) {
	class Order_Sales_Report_Table extends WP_List_Table {
	

		private $orders_data;
		private $ignored_order_ids;

		public function __construct( $orders_data = array(), $ignored_order_ids = array() ) {
			$this->orders_data = $orders_data;
			$this->ignored_order_ids = $ignored_order_ids;
			parent::__construct(array(
				'singular' => 'order',
				'plural' => 'orders',
				'ajax' => false,
			));
		}

		private function get_filtered_orders_data() {
			$filtered_data = array();

			foreach ($this->orders_data as $current_order_id) {
				if (in_array($current_order_id, $this->ignored_order_ids)) {
					continue; // Skip ignored orders
				}

				$current_order = wc_get_order($current_order_id);

				if (!$current_order) {
					continue;
				}

				$country_full_name = method_exists($current_order, 'get_billing_country') ? devsoul_psbsp_get_country_full_name($current_order->get_billing_country()) : '';
				$state_full_name = method_exists($current_order, 'get_billing_country') ? devsoul_psbsp_get_state_full_name($current_order->get_billing_country(), $current_order->get_billing_state()) : '';
				$total_coupon_amount = 0;

				foreach ($current_order->get_coupon_codes() as $coupon_code) {
					$coupon = new WC_Coupon($coupon_code);
					$total_coupon_amount += $coupon->get_amount();
				}
				$billing_first_name = method_exists($current_order, 'get_billing_first_name') ? $current_order->get_billing_first_name() : '';
				$billing_last_name = method_exists($current_order, 'get_billing_last_name') ? $current_order->get_billing_last_name() : '';
				$billing_email = method_exists($current_order, 'get_billing_email') ? $current_order->get_billing_email() : '';
				$billing_phone = method_exists($current_order, 'get_billing_phone') ? $current_order->get_billing_phone() : '';
				$billing_address_1 = method_exists($current_order, 'get_billing_address_1') ? $current_order->get_billing_address_1() : '';
				$billing_address_2 = method_exists($current_order, 'get_billing_address_2') ? $current_order->get_billing_address_2() : '';
				$billing_city = method_exists($current_order, 'get_billing_city') ? $current_order->get_billing_city() : '';
				$billing_country = method_exists($current_order, 'get_billing_country') ? devsoul_psbsp_get_country_full_name($current_order->get_billing_country()) : '';
				$billing_postcode = method_exists($current_order, 'get_billing_postcode') ? $current_order->get_billing_postcode() : '';
				$billing_method = method_exists($current_order, 'get_payment_method') ? $current_order->get_payment_method() : '';

				// Get shipping details
				$shipping_first_name = method_exists($current_order, 'get_shipping_first_name') ? $current_order->get_shipping_first_name() : '';
				$shipping_last_name = method_exists($current_order, 'get_shipping_last_name') ? $current_order->get_shipping_last_name() : '';
				$shipping_address_1 = method_exists($current_order, 'get_shipping_address_1') ? $current_order->get_shipping_address_1() : '';
				$shipping_address_2 = method_exists($current_order, 'get_shipping_address_2') ? $current_order->get_shipping_address_2() : '';
				$shipping_city = method_exists($current_order, 'get_shipping_city') ? $current_order->get_shipping_city() : '';
				$shipping_country = method_exists($current_order, 'get_shipping_country') ? devsoul_psbsp_get_country_full_name($current_order->get_shipping_country()) : '';
				$shipping_postcode = method_exists($current_order, 'get_shipping_postcode') ? $current_order->get_shipping_postcode() : '';



				ob_start();
				?>
				<input type="hidden" class="ct-psbsp-order-complete-detail"
					data-coupon_amount="<?php echo esc_attr($total_coupon_amount); ?>"
					data-order_id="<?php echo esc_attr($current_order_id); ?>"
					data-shipping_method="<?php echo esc_attr($billing_first_name); ?>"
					data-billing_first_name="<?php echo esc_attr($billing_first_name); ?>"
					data-billing_last_name="<?php echo esc_attr($billing_last_name); ?>"
					data-billing_email="<?php echo esc_attr($billing_email); ?>"
					data-billing_phone="<?php echo esc_attr($billing_phone); ?>"
					data-billing_address_1="<?php echo esc_attr($billing_address_1); ?>"
					data-billing_address_2="<?php echo esc_attr($billing_address_2); ?>"
					data-billing_city="<?php echo esc_attr($billing_city); ?>"
					data-billing_country="<?php echo esc_attr($billing_country); ?>"
					data-billing_postcode="<?php echo esc_attr($billing_postcode); ?>"
					data-billing_method="<?php echo esc_attr($billing_method); ?>"
					data-shipping_first_name="<?php echo esc_attr($shipping_first_name); ?>"
					data-shipping_last_name="<?php echo esc_attr($shipping_last_name); ?>"
					data-shipping_address_1="<?php echo esc_attr($shipping_address_1); ?>"
					data-shipping_city="<?php echo esc_attr($shipping_city); ?>"
					data-shipping_country="<?php echo esc_attr($shipping_country); ?>"
					data-shipping_postcode="<?php echo esc_attr($shipping_postcode); ?>"
					data-refunded_amount="<?php echo esc_attr(method_exists($current_order, 'get_total_refunded') ? $current_order->get_total_refunded() : 0); ?>"
					data-subtotal="<?php echo esc_attr(method_exists($current_order, 'get_subtotal') ? $current_order->get_subtotal() : 0); ?>"
					data-tax_total="<?php echo esc_attr(method_exists($current_order, 'get_total_tax') ? $current_order->get_total_tax() : 0); ?>"
					data-shipping_total="<?php echo esc_attr(method_exists($current_order, 'get_shipping_total') ? $current_order->get_shipping_total() : 0); ?>"
					data-total="<?php echo esc_attr(method_exists($current_order, 'get_total') ? $current_order->get_total() : 0); ?>">
				<?php
				$hiddent_field_data = ob_get_clean();

				$filtered_data[] = array(
					'id' => $current_order->get_id(),
					'date' => gmdate('F j Y', strtotime($current_order->get_date_created())),
					'billing_name' => $current_order->get_billing_first_name() . ' ' . $current_order->get_billing_last_name(),
					'billing_country' => $country_full_name,
					'billing_state' => $state_full_name,
					'billing_city' => $current_order->get_billing_city(),
					'postcode' => $current_order->get_billing_postcode(),
					'subtotal' => wc_price($current_order->get_subtotal()),
					'tax' => wc_price($current_order->get_total_tax()),
					'shipping' => wc_price($current_order->get_shipping_total()),
					'refund' => wc_price($current_order->get_total_refunded()),
					'coupon' => wc_price($total_coupon_amount),
					'total' => wc_price($current_order->get_total()),
					'actions' => $hiddent_field_data . '<a href="' . esc_url(get_edit_post_link($current_order->get_id())) . '" class="af-tips"><i class="fa-solid fa fa-pencil"></i><span>' . esc_html__('Edit Order', 'cloud_tech_psbsp') . '</span></a>',
				);
			}

			return $filtered_data;
		}

		public function prepare_items() {
			$columns = $this->get_columns();
			$hidden = $this->get_hidden_columns();
			$sortable = $this->get_sortable_columns();

			$this->_column_headers = array( $columns, $hidden, $sortable );
			$this->items = $this->get_filtered_orders_data();
		}

		public function get_columns() {
			return array(
				'cb' => '<input type="checkbox" class="select_current_order" />',
				'id' => __('Order Id', 'cloud_tech_psbsp'),
				'date' => __('Order Date', 'cloud_tech_psbsp'),
				'billing_name' => __('Billing Name', 'cloud_tech_psbsp'),
				'billing_country' => __('Billing Country', 'cloud_tech_psbsp'),
				'billing_state' => __('Billing State', 'cloud_tech_psbsp'),
				'billing_city' => __('Billing City', 'cloud_tech_psbsp'),
				'postcode' => __('Zip Code', 'cloud_tech_psbsp'),
				'subtotal' => __('Subtotal', 'cloud_tech_psbsp'),
				'tax' => __('Tax', 'cloud_tech_psbsp'),
				'shipping' => __('Shipping', 'cloud_tech_psbsp'),
				'refund' => __('Refund', 'cloud_tech_psbsp'),
				'coupon' => __('Coupon', 'cloud_tech_psbsp'),
				'total' => __('Total', 'cloud_tech_psbsp'),
				'actions' => __('Action', 'cloud_tech_psbsp'),
			);
		}

		public function get_hidden_columns() {
			return array();
		}

		public function get_sortable_columns() {
			return array();
		}

		public function column_default( $item, $column_name ) {
			switch ($column_name) {
				case 'id':
				case 'date':
				case 'billing_name':
				case 'billing_country':
				case 'billing_state':
				case 'billing_city':
				case 'postcode':
				case 'subtotal':
				case 'tax':
				case 'shipping':
				case 'refund':
				case 'coupon':
				case 'total':
				case 'actions':
					return $item[ $column_name ];
				default:
					return '';
			}
		}

		public function column_cb( $item ) {
			return sprintf(
				'<input type="checkbox" name="order[]" value="%s" class="select_current_order" />',
				$item['id']
			);
		}
	}
}
?>