<?php

if (!defined('WPINC')) {
	die;
}


if (!class_exists('Tax_Exampt_Pro_For_Woocommerce')) {

	class Tax_Exampt_Pro_For_Woocommerce
	{

		public function __construct()
		{

			$this->afreg_global_constents_vars();

			add_action('after_setup_theme', array($this, 'afreg_init'));
			add_action('init', array($this, 'afreg_custom_post_type'));

			//HOPS compatibility
			add_action('before_woocommerce_init', array($this, 'ct_tepfw__HOPS_Compatibility'));

			add_action('wp_enqueue_scripts', [$this, 'ct_tepfw_enqueue_scripts']);
			add_action('admin_enqueue_scripts', [$this, 'ct_tepfw_enqueue_scripts']);


			include_once CT_TEPFW_PLUGIN_DIR . 'includes/class-ct-tepfw-general-functions.php';
			include_once CT_TEPFW_PLUGIN_DIR . 'includes/class-ct-tepfw-ajax.php';

			if (is_admin()) {

				include CT_TEPFW_PLUGIN_DIR . 'includes/admin/class-ct-tepfw-admin.php';
				include CT_TEPFW_PLUGIN_DIR . 'includes/admin/rules/ct-tepfw-post-data.php';

			} else {

				include CT_TEPFW_PLUGIN_DIR . 'includes/front/class-ct-tepfw-front.php';
			}
		}

		public function ct_tepfw__HOPS_Compatibility()
		{

			if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
			}
		}

		public function afreg_global_constents_vars()
		{

			if (!defined('CT_TEPFW_URL')) {
				define('CT_TEPFW_URL', plugin_dir_url(__FILE__));
			}

			if (!defined('CT_TEPFW_BASENAME')) {
				define('CT_TEPFW_BASENAME', plugin_basename(__FILE__));
			}

			if (!defined('CT_TEPFW_PLUGIN_DIR')) {
				define('CT_TEPFW_PLUGIN_DIR', plugin_dir_path(__FILE__));
			}

		} //end afreg_global_constents_vars()

		public function afreg_init()
		{

			if (function_exists('load_plugin_textdomain')) {

				load_plugin_textdomain('cloud_tech_tepfw', false, dirname(plugin_basename(__FILE__)) . '/languages/');
			}
		} //end afreg_init()

		public function afreg_custom_post_type()
		{

			$labels = array(
				'name' => esc_html__('Role Base Tax Exempt Pro', 'cloud_tech_tepfw'),
				'singular_name' => esc_html__('Role Base Tax Exempt Pro', 'cloud_tech_tepfw'),
				'add_new' => esc_html__('Add New Field', 'cloud_tech_tepfw'),
				'add_new_item' => esc_html__('Add New Field', 'cloud_tech_tepfw'),
				'edit_item' => esc_html__('Edit Role Base Tax Exempt Pro', 'cloud_tech_tepfw'),
				'new_item' => esc_html__('New Role Base Tax Exempt Pro', 'cloud_tech_tepfw'),
				'view_item' => esc_html__('View Role Base Tax Exempt Pro', 'cloud_tech_tepfw'),
				'search_items' => esc_html__('Search Role Base Tax Exempt Pro', 'cloud_tech_tepfw'),
				'exclude_from_search' => true,
				'not_found' => esc_html__('No Role Base Tax Exempt Pro found', 'cloud_tech_tepfw'),
				'not_found_in_trash' => esc_html__('No Role Base Tax Exempt Pro found in trash', 'cloud_tech_tepfw'),
				'parent_item_colon' => '',
				'all_items' => esc_html__('Role Base Tax Exempt Pro', 'cloud_tech_tepfw'),
				'menu_name' => esc_html__('Role Base Tax Exempt Pro', 'cloud_tech_tepfw'),
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
				'rewrite' => array('slug' => 'ct_tax_exempt_pro', 'with_front' => false),
				'supports' => array('title', 'page-attributes')
			);

			register_post_type('ct_tax_exempt_pro', $args);



		} //end afdef_custom_post_type()

		public function ct_tepfw_enqueue_scripts()
		{

			if (is_admin()) {

				wp_enqueue_script('ct-tepfw', CT_TEPFW_URL . 'assets/js/admin.js', array('jquery'), '1.0.0', false);
				wp_enqueue_style('admin-css', CT_TEPFW_URL . 'assets/css/admin.css', array(), '1.0.0');

			} else {

				wp_enqueue_script('ct-tepfw', CT_TEPFW_URL . 'assets/js/front.js', array('jquery'), '1.0.0', false);
				wp_enqueue_style('front-css', CT_TEPFW_URL . 'assets/css/front.css', array(), '1.0.0');
			}

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


	} //end class
	new Tax_Exampt_Pro_For_Woocommerce();
}