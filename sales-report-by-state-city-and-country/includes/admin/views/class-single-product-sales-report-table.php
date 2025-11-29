<?php

if (!class_exists('Single_Product_Sales_Report_Table')) {
	class Single_Product_Sales_Report_Table extends WP_List_Table {
	

		private $results_data;

		public function __construct( $results_data = array() ) {
			$this->results_data = $results_data;
			parent::__construct(array(
				'singular' => 'order',
				'plural' => 'orders',
				'ajax' => false,
			));
		}

		public function prepare_items() {
			$columns = $this->get_columns();
			$hidden = $this->get_hidden_columns();
			$sortable = $this->get_sortable_columns();

			$this->_column_headers = array( $columns, $hidden, $sortable );
			$this->items = $this->results_data;
		}

		public function get_columns() {
			return array(
				'order_id' => __('Order Id', 'dproduct-sales-report'),
				'date_created' => __('Order Date', 'dproduct-sales-report'),
				'order_status' => __('Order Status', 'dproduct-sales-report'),
				'quantity' => __('Quantity', 'dproduct-sales-report'),
				'total' => __('Total', 'dproduct-sales-report'),
			);
		}

		public function get_hidden_columns() {
			return array();
		}

		public function get_sortable_columns() {
			return array(
				'order_id' => array( 'order_id', true ),
				'date_created' => array( 'date_created', false ),
				'quantity' => array( 'quantity', false ),
				'total' => array( 'total', false ),
			);
		}

		public function column_default( $item, $column_name ) {
			switch ($column_name) {
				case 'order_id':
					return '<a href="' . esc_url(get_edit_post_link($item['order_id'])) . '">' . esc_attr($item['order_id']) . '</a>';
				case 'date_created':
					return esc_attr(gmdate(' j-F-Y  H:m:s ', strtotime($item['date_created'])));
				case 'order_status':
					return esc_attr(wc_get_order_status_name(get_post_status($item['order_id'])));
				case 'quantity':
					return esc_attr($item['product_qty']);
				case 'total':
					return wp_kses(wc_price($item['product_net_revenue']), wp_kses_allowed_html());
				default:
					return isset($item[ $column_name ]) ? $item[ $column_name ] : '';
			}
		}
	}
}
