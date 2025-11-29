<?php
// Include WP_List_Table if not already included
if (!class_exists('WP_List_Table')) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
if (!class_exists('Product_Sales_Report_Table')) {
	class Product_Sales_Report_Table extends WP_List_Table {
	



		private $products;
		private $sum_of_subtotal = 0;
		private $sum_of_tax_total = 0;
		private $sum_of_total = 0;

		public $order_paramenters;

		public function __construct( $products, $order_paramenters ) {
			parent::__construct(array(
				'singular' => __('Product', 'cloud_tech_psbsp'), // Singular name of the listed records
				'plural' => __('Products', 'cloud_tech_psbsp'), // Plural name of the listed records
				'ajax' => false, // Does this table support ajax?
			));

			$this->products = $products->get_products();

			$this->order_paramenters = $order_paramenters;
		}

		public function get_columns() {
			$columns = array(
				'cb' => '<input type="checkbox" class="select_current_order" />',
				'product' => __('Product', 'cloud_tech_psbsp'),
				'purchase_qty' => __('Purchase Qty', 'cloud_tech_psbsp'),
				'price' => __('Price', 'cloud_tech_psbsp'),
				'total_tax' => __('Total Tax', 'cloud_tech_psbsp'),
				'subtotal' => __('Subtotal', 'cloud_tech_psbsp'),
				'total' => __('Total', 'cloud_tech_psbsp'),
				'action' => __('Action', 'cloud_tech_psbsp'),
			);
			return $columns;
		}
		public function column_cb( $product_id ) {
			return sprintf(
				'<input type="checkbox" name="product[]" value="%s" class="selected_product" />',
				$product_id
			);
		}
		public function prepare_items() {
			$columns = $this->get_columns();
			$hidden = $this->get_hidden_columns();
			$sortable = $this->get_sortable_columns();

			$this->_column_headers = array( $columns, $hidden, $sortable );
			$this->items = $this->products;
		}

		public function column_default( $product_id, $column_name ) {
			$product = wc_get_product($product_id);
			if (!$product) {
				return 'Invalid Product';
			}
			$product_id_or_var_id = 'variation' == $product->get_type() ? $product->get_parent_id() : $product->get_id();

			$product_sales_detail = devsoul_psbsp_get_product_sales($product_id, (array) $this->order_paramenters);

			$total = isset($product_sales_detail['total']) ? (int) $product_sales_detail['total'] : 0;
			$total_tax = isset($product_sales_detail['total_tax']) ? (int) $product_sales_detail['total_tax'] : 0;
			$purchase_qty = isset($product_sales_detail['purchase_qty']) ? (int) $product_sales_detail['purchase_qty'] : 0;
			$sub_total = isset($product_sales_detail['sub_total']) ? (int) $product_sales_detail['sub_total'] : 0;


			switch ($column_name) {
				case 'product':
					return esc_attr($product->get_name());
				case 'purchase_qty':
					return esc_attr($purchase_qty);
				case 'price':
					return wp_kses_post(str_replace(',', '', wc_price($product->get_price())));
				case 'total_tax':
					$this->sum_of_tax_total += $total_tax;
					return wp_kses_post(str_replace(',', '', wc_price($total_tax)));
				case 'subtotal':
					$this->sum_of_subtotal += $sub_total;
					return wp_kses_post(str_replace(',', '', wc_price($sub_total)));
				case 'total':
					$this->sum_of_total += $total;
					return wp_kses_post(str_replace(',', '', wc_price($total)));
				case 'action':
					ob_start();

					?>
					<input type="hidden" class="ct-psbsp-order-complete-detail" data-subtotal="<?php echo esc_attr($sub_total); ?>"
						data-tax_total="<?php echo esc_attr($total_tax); ?>" data-total="<?php echo esc_attr($total); ?>"
						data-product_id="<?php echo esc_attr($product_id); ?>"
						data-product_name="<?php echo esc_attr($product->get_name()); ?>"
						data-purchase_qty="<?php echo esc_attr($purchase_qty); ?>"
						data-price="<?php echo esc_attr($product->get_price()); ?>">
					<a href="<?php echo esc_url(get_edit_post_link($product_id_or_var_id)); ?>" class="af-tips"><i
							class="fa-solid fa fa-pencil"></i><span><?php echo esc_html__('Edit Product', 'cloud_tech_psbsp'); ?></span></a>
					<a href="<?php echo esc_url(get_post_permalink($product_id_or_var_id)); ?>" class="af-tips"><i
							class="fa fa-eye"></i><span><?php echo esc_html__('View Product', 'cloud_tech_psbsp'); ?></span></a>
					<?php
					return ob_get_clean();
				default:
					''; // Show the whole array for troubleshooting purposes
			}
		}

		public function get_hidden_columns() {
			return array();
		}

		public function get_sortable_columns() {
			return array();
		}
		public function display_totals() {
			echo '<div class="ct-psbsp-total-of-selected-table">
                <table>
                    <tr class="ct-psbsp-subtotal">
                        <th>' . esc_html__('Subtotal', 'cloud_tech_psbsp') . '</th>
                        <td class="ct-psbsp-subtotal-td">' . wp_kses_post(str_replace(',', '', wc_price($this->sum_of_subtotal))) . '</td>
                    </tr>
                    <tr class="ct-psbsp-total-tax">
                        <th>' . esc_html__('Total Tax', 'cloud_tech_psbsp') . '</th>
                        <td class="ct-psbsp-total-tax-td">' . wp_kses_post(str_replace(',', '', wc_price($this->sum_of_tax_total))) . '</td>
                    </tr>
                    <tr class="ct-psbsp-total">
                        <th>' . esc_html__('Total', 'cloud_tech_psbsp') . '</th>
                        <td class="ct-psbsp-total-td">' . wp_kses_post(str_replace(',', '', wc_price($this->sum_of_total))) . '</td>
                    </tr>
                </table>
            </div>';
		}
	}
}
