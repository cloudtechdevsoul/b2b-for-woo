<?php


if (!defined('WPINC')) {
	die;
}


if (!class_exists('Request_A_Quote_Pro_For_Woocommerce')) {

	class Request_A_Quote_Pro_For_Woocommerce
	{

		public function __construct()
		{

			$this->afreg_global_constents_vars();


			//HOPS compatibility
			add_action('before_woocommerce_init', array($this, 'afcmfw__HOPS_Compatibility'));

			add_action('init', array($this, 'af_cm_check_woocommerce_is_defined_or_not'));
			add_action('plugin_loaded', array($this, 'af_cmfw_wc_check'));

		}

		public function afcmfw__HOPS_Compatibility()
		{

			if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
			}
		}
		public function af_cmfw_wc_check()
		{
			if (!is_multisite() && (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true))) {
				add_action('admin_notices', array($this, 'af_min_max_wc_active'));

			}
		}
		public function af_min_max_wc_active()
		{
			deactivate_plugins(__FILE__);
			?>
			<div id="message" class="error">
				<p>
					<strong>
						<?php echo esc_html__('Request A Quote Pro For Woocommerce plugin is inactive. WooCommerce plugin must be active in order to activate it.', 'addify_min_max_q_r'); ?>
					</strong>
				</p>
			</div>
			<?php
		}

		public function af_cm_check_woocommerce_is_defined_or_not()
		{
			if (defined('WC_PLUGIN_FILE')) {

				add_action('after_setup_theme', array($this, 'ct_rfq_init'));
				$this->ct_rfq_custom_post_type();

				add_action('wp_enqueue_scripts', [$this, 'ct_tepfw_enqueue_scripts']);
				add_action('admin_enqueue_scripts', [$this, 'ct_tepfw_enqueue_scripts']);


				include CT_RFQ_PLUGIN_DIR . 'includes/class-ct-rfq-general-functions.php';
				include CT_RFQ_PLUGIN_DIR . 'includes/class-ct-rfq-ajax.php';
				include CT_RFQ_PLUGIN_DIR . 'includes/front/custom-action-filters.php';
				include CT_RFQ_PLUGIN_DIR . 'includes/front/quote-detail-in-my-account-page.php';

				if (is_admin()) {

					include CT_RFQ_PLUGIN_DIR . 'includes/admin/rules/ct-rfq-quote-fields.php';
					include CT_RFQ_PLUGIN_DIR . 'includes/admin/class-ct-rfq-admin.php';
					include CT_RFQ_PLUGIN_DIR . 'includes/admin/rules/ct-rfq-post-data.php';
					include CT_RFQ_PLUGIN_DIR . 'includes/admin/submitted-quote-detail/quote-detail.php';


				} else {
					include CT_RFQ_PLUGIN_DIR . 'includes/front/class-ct-rfq-front.php';
					include CT_RFQ_PLUGIN_DIR . 'includes/front/request-a-quote-table.php';
					include CT_RFQ_PLUGIN_DIR . 'includes/front/request-a-quote-cart.php';

				}
				get_rfq_pge_id();

			}
		}

		public function afreg_global_constents_vars()
		{

			if (!defined('CT_RFQ_PLUGIN_URL')) {
				define('CT_RFQ_PLUGIN_URL', plugin_dir_url(__FILE__));
			}

			if (!defined('CT_RFQ_PLUGIN_BASENAME')) {
				define('CT_RFQ_PLUGIN_BASENAME', plugin_basename(__FILE__));
			}

			if (!defined('CT_RFQ_PLUGIN_DIR')) {
				define('CT_RFQ_PLUGIN_DIR', plugin_dir_path(__FILE__));
			}


			$upload_dir = wp_upload_dir();

			$upload_path = $upload_dir['basedir'] . '/cloudtech-rfq/';

			if (!is_dir($upload_path)) {
				mkdir($upload_path);
			}

			if (!is_dir($upload_path . 'admin')) {
				mkdir($upload_path . 'admin');
			}

			if (!is_dir($upload_path . 'user')) {
				mkdir($upload_path . 'user');
			}

			$upload_url = $upload_dir['baseurl'] . '/cloudtech-rfq/';

			define('CT_RFQ_UPLOAD_DIR', $upload_path);
			define('CT_RFQ_UPLOAD_URL', $upload_url);

		}


		public function ct_rfq_init()
		{

			if (function_exists('load_plugin_textdomain')) {

				load_plugin_textdomain('cloud_tech_rfq', false, dirname(plugin_basename(__FILE__)) . '/languages/');
			}


		}


		public function ct_rfq_custom_post_type()
		{

			$ct_rfqcustom_post_type = ['ct-rfq-quote-rule', 'ct-rfq-quote-fields', 'ct-rfq-submit-quote'];
			foreach ($ct_rfqcustom_post_type as $quote_slug) {

				$labels = array(
					'name' => esc_html__('Request A Quote Pro', 'cloud_tech_rfq'),
					'singular_name' => esc_html__('Request A Quote Pro', 'cloud_tech_rfq'),
					'add_new' => esc_html__('Add New Field', 'cloud_tech_rfq'),
					'add_new_item' => esc_html__('Add New Field', 'cloud_tech_rfq'),
					'edit_item' => esc_html__('Edit Request A Quote Pro', 'cloud_tech_rfq'),
					'new_item' => esc_html__('New Request A Quote Pro', 'cloud_tech_rfq'),
					'view_item' => esc_html__('View Request A Quote Pro', 'cloud_tech_rfq'),
					'search_items' => esc_html__('Search Request A Quote Pro', 'cloud_tech_rfq'),
					'exclude_from_search' => true,
					'not_found' => esc_html__('No Request A Quote Pro found', 'cloud_tech_rfq'),
					'not_found_in_trash' => esc_html__('No Request A Quote Pro found in trash', 'cloud_tech_rfq'),
					'parent_item_colon' => '',
					'all_items' => esc_html__('Request A Quote Pro', 'cloud_tech_rfq'),
					'menu_name' => esc_html__('Request A Quote Pro', 'cloud_tech_rfq'),
				);

				$args = array(
					'labels' => $labels,
					'public' => false,
					'publicly_queryable' => true,
					'show_ui' => true,
					'show_in_menu' => 'b2bking',
					'query_var' => true,
					'capability_type' => 'post',
					'has_archive' => true,
					'hierarchical' => false,
					'rewrite' => array('slug' => $quote_slug, 'with_front' => false),
					'supports' => array('title', 'page-attributes'),
				);

				register_post_type($quote_slug, $args);
			}


		}


		public function ct_tepfw_enqueue_scripts()
		{

			if (is_admin()) {

				wp_enqueue_script('ct-tepfw', CT_RFQ_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), '1.0.0', false);
				wp_enqueue_style('admin-css', CT_RFQ_PLUGIN_URL . 'assets/css/admin.css', array(), '1.0.0');

			} else {

				wp_enqueue_script('datatablejs', CT_RFQ_PLUGIN_URL . 'assets/js/datatablejs.js', array('jquery'), '1.0.0', false);
				wp_enqueue_script('ct-tepfw', CT_RFQ_PLUGIN_URL . 'assets/js/front.js', array('jquery'), '1.0.0', false);
				wp_enqueue_style('front-css', CT_RFQ_PLUGIN_URL . 'assets/css/front.css', array(), '1.0.0');

			}

			wp_enqueue_script('jquery');

			wp_enqueue_style('dataTables-style', 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css', array(), '1.11.5');
			wp_enqueue_script('dataTablesjs', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js', array('jquery'), '1.11.5', true);
			wp_localize_script('dataTablesjs', 'myDataTable', array('ajaxurl' => admin_url('admin-ajax.php')));


			wp_enqueue_style('font-awesome-lib', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0', false);

			wp_enqueue_script('jquery-ui-accordion');
			wp_enqueue_script('jquery-ui-sortable');

			wp_enqueue_style('wc-select2-css', plugins_url('assets/css/select2.css', WC_PLUGIN_FILE), array(), '5.7.2');
			wp_enqueue_script('wc-select2-js', plugins_url('assets/js/select2/select2.min.js', WC_PLUGIN_FILE), array('jquery'), '4.0.3', false);

			$af_c_f_data = array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('cloud-tech-tepfw-nonce'),
			);
			wp_localize_script('ct-tepfw', 'ct_tepfw_var', $af_c_f_data);

		}


	}

	new Request_A_Quote_Pro_For_Woocommerce();
}