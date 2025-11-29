<?php

if (!defined('WPINC')) {
	die;
}

if (!class_exists('Order_Line_Item_Status_For_Woocommerce')) {

	class Order_Line_Item_Status_For_Woocommerce
	{

		public function __construct()
		{

			$this->ct_olisfw_global_constents_vars();

			add_action('after_setup_theme', array($this, 'ct_olisfw_init'));

			add_action('woocommerce_order_item_meta_start', [$this, 'ct_olisfw_item_meta'], 10, 3);

			register_activation_hook(__FILE__, array($this, 'ct_olisfw_installation'));

			//HOPS compatibility
			add_action('before_woocommerce_init', array($this, 'ct_olisfw__HOPS_Compatibility'));
			include CT_OLISFW_PLUGIN_DIR . 'includes/ct-olisfw-ajax-controller.php';
			include CT_OLISFW_PLUGIN_DIR . 'includes/ct-olisfw-general-functions.php';

			if (is_admin()) {
				include CT_OLISFW_PLUGIN_DIR . 'includes/admin/ct-olisfw-admin.php';
			}

		} //end __construct()

		public function ct_olisfw_installation()
		{
			// update option on plugin installation.

		}

		public function ct_olisfw__HOPS_Compatibility()
		{

			if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
			}
		}

		public function ct_olisfw_global_constents_vars()
		{

			if (!defined('CT_OLISFW_URL')) {
				define('CT_OLISFW_URL', plugin_dir_url(__FILE__));
			}

			if (!defined('CT_OLISFW_BASENAME')) {
				define('CT_OLISFW_BASENAME', plugin_basename(__FILE__));
			}

			if (!defined('CT_OLISFW_PLUGIN_DIR')) {
				define('CT_OLISFW_PLUGIN_DIR', plugin_dir_path(__FILE__));
			}

		}
		public function ct_olisfw_init()
		{

			if (function_exists('load_plugin_textdomain')) {

				load_plugin_textdomain('cloud_tech_olisfw', false, dirname(plugin_basename(__FILE__)) . '/languages/');
			}
		}

		public function ct_olisfw_item_meta($item_id, $item, $order)
		{
			$ct_old_item_status = wc_get_order_item_meta($item_id, 'ct_order_item_status', true);
			$ct_old_item_date = wc_get_order_item_meta($item_id, 'ct_order_item_date', true);
			$ct_old_item_time = wc_get_order_item_meta($item_id, 'ct_order_item_time', true);

			?>
			<table class="woocommerce_order_items">
				<thead>

					<th>
						<?php echo esc_html__('Item Status', 'cloud_tech_olisfw'); ?>
					</th>
					<th>
						<?php echo esc_html__('Date', 'cloud_tech_olisfw'); ?>
					</th>
					<th>
						<?php echo esc_html__('Time', 'cloud_tech_olisfw'); ?>
					</th>
				</thead>
				<tbody>
					<tr>
						<td>

							<?php foreach (wc_get_order_statuses() as $key => $label) {
								if ($key == $ct_old_item_status) {

									echo esc_html__($label, 'cloud_tech_olisfw');
								}
							} ?>

						</td>
						<td>
							<input type="date" name="ct_order_item_date[<?php echo esc_attr($item_id); ?>]"
								value="<?php echo esc_attr($ct_old_item_date); ?>">
						</td>
						<td>
							<input type="time" name="ct_order_item_time[<?php echo esc_attr($item_id); ?>]"
								value="<?php echo esc_attr($ct_old_item_time); ?>">
						</td>

					</tr>
				</tbody>
			</table>

			<?php
		}

	}

	new Order_Line_Item_Status_For_Woocommerce();
}
