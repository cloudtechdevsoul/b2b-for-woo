<?php

if (!defined('ABSPATH')) {
	exit();
}

/**
 * Class exist Check.
 *
 * @since 1.0.0
 */
if (!class_exists('Product_Sales_Report')) {


	/**
	 * Main class
	 */
	class Product_Sales_Report {
	


		/**
		 * Class constructor starts
		 */
		public function __construct() {
			// Define Global Constants.
			$this->devsoul_psbsp_global_constents_vars();
			add_action('init', array( $this, 'devsoul_psbsp_include_file' ));
			add_action('plugins_loaded', array( $this, 'devsoul_psbsp_init' ));
			add_action('admin_head', array( $this, 'devsoul_psbsp_maybe_flush_w3tc_cache' ));
			add_action('before_woocommerce_init', array( $this, 'devsoul_psbsp_hops_compatibility' ));
		}

		public function devsoul_psbsp_maybe_flush_w3tc_cache() {
			wp_cache_flush();

			global $wp_object_cache;

			return $wp_object_cache->flush();
		}
		public function devsoul_psbsp_hops_compatibility() {
			if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
			}
		}

		/**
		 * Woocommerce enable check.
		 *
		 * @since 1.0.0
		 */
		public function devsoul_psbsp_init() {

			// Check the installation of WooCommerce module if it is not a multi site.
			if (!is_multisite() && !in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true)) {

				add_action('admin_notices', array( $this, 'devsoul_psbsp_check_wocommerce' ));
			}
		}

		/**
		 * Deactivate check.
		 *
		 * @since 1.0.0
		 */
		public function devsoul_psbsp_check_wocommerce() {
			// Deactivate the plugin.
			deactivate_plugins(__FILE__);
			?>
			<div id="message" class="error">
				<p>
					<strong>
						<?php esc_html_e('Sales Report By State , City and Country plugin is inactive. WooCommerce plugin must be active in order to activate it.', 'product-sales-report'); ?>
					</strong>
				</p>
			</div>
			<?php
		}

		/**
		 * Includes Files.
		 *
		 * @since 1.0.0
		 */
		public function devsoul_psbsp_include_file() {

			if (defined('WC_PLUGIN_FILE')) {

				add_action('wp_loaded', array( $this, 'devsoul_psbsp_register_text_domain' ));

				include DEVS_PSBSCCP_PLUGIN_DIR . 'includes/class-devsoul-psbsp-general-functions.php';

				include DEVS_PSBSCCP_PLUGIN_DIR . 'includes/class-devsoul-psbsp-ajax.php';

				if (is_admin()) {

					include DEVS_PSBSCCP_PLUGIN_DIR . 'includes/admin/class-devsoul-psbsp-admin.php';
					include DEVS_PSBSCCP_PLUGIN_DIR . 'includes/admin/views/column-in-table.php';

				}
				add_action('wp', array( $this, 'devsoul_psbs_schedule_email_cron' ));
				add_action('devsoul_psbsp_send_email_event', array( $this, 'devsoul_psbs_send_email_with_order_data' ));
				register_deactivation_hook(__FILE__, array( $this, 'devsoul_psbs_clear_email_cron' ));

			}
		}


		/**
		 * Define constant variables.
		 *
		 * @since 1.0.0
		 */
		public function devsoul_psbsp_global_constents_vars() {

			if (!defined('DEVS_PSBSCCP_URL')) {
				define('DEVS_PSBSCCP_URL', plugin_dir_url(__FILE__));
			}
			if (!defined('DEVS_SRBSCAC_BASENAME')) {
				define('DEVS_SRBSCAC_BASENAME', plugin_basename(__FILE__));
			}
			if (!defined('DEVS_PSBSCCP_PLUGIN_DIR')) {
				define('DEVS_PSBSCCP_PLUGIN_DIR', plugin_dir_path(__FILE__));
			}
		}

		/**
		 * Register text Domain.
		 *
		 * @since 1.0.0
		 */
		public function devsoul_psbsp_register_text_domain() {

			if (function_exists('load_plugin_textdomain')) {
				load_plugin_textdomain('product-sales-report', false, dirname(plugin_basename(__FILE__)) . '/languages/');
			}
		}
		public function devsoul_psbs_schedule_email_cron() {
			if (!wp_next_scheduled('devsoul_psbsp_send_email_event')) {
				$duration_number = get_option('devsoul_psbsp_auto_send_stats_on_mail_duration_number');
				$duration = get_option('devsoul_psbsp_auto_send_stats_on_mail_duration');

				$interval = $this->devsoul_psbs_get_interval($duration, $duration_number);

				wp_schedule_event(time(), $interval, 'devsoul_psbsp_send_email_event');
			}
		}

		private function devsoul_psbs_get_interval( $duration, $number ) {
			switch ($duration) {
				case 'hourly':
					return $number * HOUR_IN_SECONDS;
				case 'daily':
					return $number * DAY_IN_SECONDS;
				case 'weekly':
					return $number * WEEK_IN_SECONDS;
				default:
					return DAY_IN_SECONDS; // Default to daily
			}
		}

		public function devsoul_psbs_send_email_with_order_data() {
			$enabled = get_option('devsoul_psbsp_auto_send_stats_on_mail_enable');

			if ($enabled) {
				$countries = get_option('devsoul_psbsp_auto_send_stats_on_mail_selected_countries');
				$order_status = get_option('devsoul_psbsp_auto_send_stats_on_mail_order_status');

				$args = array(
					'status' => $order_status ? $order_status : array_keys(wc_get_order_statuses()),
				);
				if (!empty($countries)) {
					$args['country'] = $countries;
				}
				$orders = wc_get_orders($args);
				// Start HTML content
				$email_content = '<html><body>';
				$email_content .= '<h1>WooCommerce Order Data</h1>';
				$email_content .= '<table border="1" cellspacing="0" cellpadding="5" style="width:100%; border-collapse:collapse;">';
				$email_content .= '<thead><tr>';
				$email_content .= '<th>Order ID</th>';
				$email_content .= '<th>Order Status</th>';
				$email_content .= '<th>Order Country</th>';
				$email_content .= '<th>Order Total</th>';
				$email_content .= '</tr></thead>';
				$email_content .= '<tbody>';

				foreach ($orders as $order) {
					$email_content .= '<tr>';
					$email_content .= '<td>' . esc_html($order->get_id()) . '</td>';
					$email_content .= '<td>' . esc_html($order->get_status()) . '</td>';
					$email_content .= '<td>' . esc_html($order->get_billing_country()) . '</td>';
					$email_content .= '<td>' . esc_html($order->get_total()) . '</td>';
					$email_content .= '</tr>';
				}

				$email_content .= '</tbody>';
				$email_content .= '</table>';
				$email_content .= '</body></html>';

				// Send email using SMTP plugin configuration
				wp_mail(
					get_option('admin_email'),
					'WooCommerce Order Data',
					$email_content,
					array(
						'Content-Type: text/html; charset=UTF-8',
						'From: Your Plugin Name <no-reply@yourdomain.com>',
						'Reply-To: support@yourdomain.com',
					)
				);
			}
		}

		public function devsoul_psbs_clear_email_cron() {
			$timestamp = wp_next_scheduled('devsoul_psbsp_send_email_event');
			wp_unschedule_event($timestamp, 'devsoul_psbsp_send_email_event');
		}
	}

	/**
	 * Class object
	 */
	new Product_Sales_Report();

}
