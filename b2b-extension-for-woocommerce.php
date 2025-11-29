<?php
if (!defined('WPINC')) {
	die;
}

/**
 * Plugin Name: Wholesale for woocommerce - B2B & B2C- B2b King - B2B For Woocommerce
 * Description: Get your whole store ready with B2B functionalities!
 * Plugin URI: https://b2bextension.store/b2b-for-woo/
 * Version: 1.0.0
 * Author: b2bextension
 * Developed By: b2bextension
 * Author URI: https://b2bextension.store/
 * Support: https://b2bextension.store/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 * Text Domain: b2b-for-woo
 * WC requires at least: 3.0.9
 * WC tested up to: 9.*.*
 * Requires Plugins: woocommerce
 * @package b2bextension
 */




if (!class_exists('b2b_extension_for_woocommerce')) {

	class b2b_extension_for_woocommerce
	{

		public function __construct()
		{
			add_action('woocommerce_init', function () {

				if (is_user_logged_in() || is_admin()) {
					return;
				}

				if (isset(WC()->session)) {
					if (!WC()->session->has_session()) {
						WC()->session->set_customer_session_cookie(true);
					}
				}

			});



			$this->afreg_global_constents_vars();

			// Hook the function to the admin_menu action
			add_action('admin_menu', [$this, 'b2bking_add_submenu']);

			add_action('after_setup_theme', array($this, 'afreg_init'));
			add_action('init', array($this, 'afreg_custom_post_type'));

			//HOPS compatibility
			add_action('before_woocommerce_init', array($this, 'afcf__HOPS_Compatibility'));

			add_action('wp_enqueue_scripts', [$this, 'ct_rbpaqp_enqueue_scripts']);
			add_action('admin_enqueue_scripts', [$this, 'ct_rbpaqp_enqueue_scripts']);


			include_once CT_RBPAQP_PLUGIN_DIR . 'includes/class-ct-rbpaqp-hpaatcb-general-functions.php';
			include_once CT_RBPAQP_PLUGIN_DIR . 'includes/class-ct-rbpaqp-ajax.php';

			// including role base tax exampt.
			include_once CT_RBPAQP_PLUGIN_DIR . 'role-base-tax-exampt-pro/tax-exampt-pro-for-woocommerce.php';
			// including request a quote.
			include_once CT_RBPAQP_PLUGIN_DIR . 'request-a-quote-pro/request-a-quote-pro-for-woocommerce.php';
			// including hide payment and shipping method.
			include_once CT_RBPAQP_PLUGIN_DIR . 'hide-payment-method-and-shipping/hide-payment-method-and-shipping.php';
			// including Order Line Item status.
			include_once CT_RBPAQP_PLUGIN_DIR . 'order-line-item-status-for-woocommerce/order-line-item-status-for-woocommerce.php';
			// including Sales Report.
			include_once CT_RBPAQP_PLUGIN_DIR . 'sales-report-by-state-city-and-country/class-product-sales-report.php';
			// product options.
			include_once CT_RBPAQP_PLUGIN_DIR . 'product-options/class-product-options.php';
			// create assign and delete user role.
			include_once CT_RBPAQP_PLUGIN_DIR . 'create-assign-delete-user-role/create-assign-delete-user-role.php';
			// Invoice Packing Slip.
			include_once CT_RBPAQP_PLUGIN_DIR . 'invoice-payment-method-invoice-payment/class-invoice-payment-method-and-invoice-pdf.php';

			if (is_admin()) {

				include_once CT_RBPAQP_PLUGIN_DIR . 'includes/admin/class-ct-rbpaqp-admin.php';

			} else {

				include CT_RBPAQP_PLUGIN_DIR . 'includes/front/hide-price-product-and-add-to-cart-button/front.php';
				include CT_RBPAQP_PLUGIN_DIR . 'includes/front/hide-product-and-variation/front.php';
				include CT_RBPAQP_PLUGIN_DIR . 'includes/front/min-max-qty/front.php';
				include CT_RBPAQP_PLUGIN_DIR . 'includes/front/role-base-pricing/front.php';

			}
		}
		public function b2bking_menu_callback()
		{

		}


		public function afcf__HOPS_Compatibility()
		{

			if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
			}
		}

		public function afreg_global_constents_vars()
		{

			if (!defined('CT_RBPAQP_URL')) {
				define('CT_RBPAQP_URL', plugin_dir_url(__FILE__));
			}

			if (!defined('CT_RBPAQP_BASENAME')) {
				define('CT_RBPAQP_BASENAME', plugin_basename(__FILE__));
			}

			if (!defined('CT_RBPAQP_PLUGIN_DIR')) {
				define('CT_RBPAQP_PLUGIN_DIR', plugin_dir_path(__FILE__));
			}

			if (!defined('CT_RBPAQP_UPLOAD_DIR')) {

				$upload_dir = wp_upload_dir();

				define('CT_RBPAQP_UPLOAD_DIR', $upload_dir['basedir'] . '/addify-custom-fields/');

				if (!is_dir(CT_RBPAQP_UPLOAD_DIR)) {
					mkdir(CT_RBPAQP_UPLOAD_DIR);
				}
			}

			if (!defined('AF_CF_UPLOAD_URL')) {

				$upload_dir = wp_upload_dir();

				define('AF_CF_UPLOAD_URL', $upload_dir['baseurl'] . '/addify-custom-fields/');
			}
		} //end afreg_global_constents_vars()

		public function afreg_init()
		{

			if (function_exists('load_plugin_textdomain')) {

				load_plugin_textdomain('cloud_tech_rbpaqpfw', false, dirname(plugin_basename(__FILE__)) . '/languages/');
			}
		} //end afreg_init()
		public function b2bking_add_submenu()
		{
			add_menu_page(
				'B2b King',
				'B2b King',
				'manage_options',
				'b2bking',
				[$this, 'b2bking_menu_callback'],
				'dashicons-admin-generic'
			);
		}

		public function afreg_custom_post_type()
		{

			$labels = array(
				'name' => esc_html__('Role Base Pricing', 'cloud_tech_rbpaqpfw'),
				'singular_name' => esc_html__('Role Base Pricing', 'cloud_tech_rbpaqpfw'),
				'add_new' => esc_html__('Add New Field', 'cloud_tech_rbpaqpfw'),
				'add_new_item' => esc_html__('Add New Field', 'cloud_tech_rbpaqpfw'),
				'edit_item' => esc_html__('Edit Role Base Pricing', 'cloud_tech_rbpaqpfw'),
				'new_item' => esc_html__('New Role Base Pricing', 'cloud_tech_rbpaqpfw'),
				'view_item' => esc_html__('View Role Base Pricing', 'cloud_tech_rbpaqpfw'),
				'search_items' => esc_html__('Search Role Base Pricing', 'cloud_tech_rbpaqpfw'),
				'exclude_from_search' => true,
				'not_found' => esc_html__('No Role Base Pricing found', 'cloud_tech_rbpaqpfw'),
				'not_found_in_trash' => esc_html__('No Role Base Pricing found in trash', 'cloud_tech_rbpaqpfw'),
				'parent_item_colon' => '',
				'all_items' => esc_html__('Role Base Pricing', 'cloud_tech_rbpaqpfw'),
				'menu_name' => esc_html__('Role Base Pricing', 'cloud_tech_rbpaqpfw'),
			);

			$args = array(
				'labels' => $labels,
				'public' => false,
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_menu' => 'b2bking',
				'query_var' => true,
				'capability_type' => 'post',
				'menu_position' => 4,
				'has_archive' => true,
				'hierarchical' => false,
				'rewrite' => array('slug' => 'ct_role_base_pricing', 'with_front' => false),
				'supports' => array('title', 'page-attributes')
			);

			register_post_type('ct_role_base_pricing', $args);



			// Customer base pricing hidden post.
			$args = array(
				'labels' => $labels,
				'public' => false,
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_menu' => false,
				'query_var' => true,
				'capability_type' => 'post',
				'has_archive' => true,
				'hierarchical' => false,
				'rewrite' => array('slug' => 'ct_set_role_for_cbp', 'with_front' => false),
				'supports' => array('title', 'page-attributes')
			);

			register_post_type('ct_set_role_for_cbp', $args);

			// Role base pricing hidden post.
			$args = array(
				'labels' => $labels,
				'public' => false,
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_menu' => false,
				'query_var' => true,
				'capability_type' => 'post',
				'has_archive' => true,
				'hierarchical' => false,
				'rewrite' => array('slug' => 'ct_set_role_for_rbp', 'with_front' => false),
				'supports' => array('title', 'page-attributes')
			);

			register_post_type('ct_set_role_for_rbp', $args);

			// ----------------------------------------------

			$def_labels = array(
				'name' => esc_html__('Default Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
				'singular_name' => esc_html__('Default Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
				'edit_item' => esc_html__('Edit Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
				'new_item' => esc_html__('New Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
				'view_item' => esc_html__('View Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
				'search_items' => esc_html__('Search Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
				'exclude_from_search' => true,
				'not_found' => esc_html__('No Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
				'not_found_in_trash' => esc_html__('No Role Base Min Max Quantity in trash', 'cloud_tech_rbpaqpfw'),
				'parent_item_colon' => '',
				'all_items' => esc_html__('Min Max Quantity', 'cloud_tech_rbpaqpfw'),
				'menu_name' => esc_html__('Default Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
			);

			$args = array(
				'labels' => $def_labels,
				'public' => false,
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_menu' => 'b2bking',
				'query_var' => true,
				'capability_type' => 'post',
				'has_archive' => true,
				'hierarchical' => false,
				'rewrite' => array('slug' => 'ct_min_max_qty_role', 'with_front' => false),
				'supports' => array('title')
			);

			register_post_type('ct_min_max_qty_role', $args);


			// Hide Price  And Add To Cart Button.

			$labels = array(
				'name' => esc_html__('Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
				'singular_name' => esc_html__('Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
				'add_new' => esc_html__('Add New Field', 'cloud_tech_rbpaqpfw'),
				'add_new_item' => esc_html__('Add New Field', 'cloud_tech_rbpaqpfw'),
				'edit_item' => esc_html__('Edit Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
				'new_item' => esc_html__('New Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
				'view_item' => esc_html__('View Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
				'search_items' => esc_html__('Search Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
				'exclude_from_search' => true,
				'not_found' => esc_html__('No Hide Price And To Cart Button found', 'cloud_tech_rbpaqpfw'),
				'not_found_in_trash' => esc_html__('No Hide Price And To Cart Button found in trash', 'cloud_tech_rbpaqpfw'),
				'parent_item_colon' => '',
				'all_items' => esc_html__('Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
				'menu_name' => esc_html__('Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
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
				'rewrite' => array('slug' => 'ct_hide_p_nd_a_t_c_b', 'with_front' => false),
				'supports' => array('title', 'page-attributes')
			);

			register_post_type('ct_hide_p_nd_a_t_c_b', $args);


			// Hide Price  And Add To Cart Button.

			$labels = array(
				'name' => esc_html__('Hide products and variation', 'cloud_tech_rbpaqpfw'),
				'singular_name' => esc_html__('Hide products and variation', 'cloud_tech_rbpaqpfw'),
				'add_new' => esc_html__('Add New Field', 'cloud_tech_rbpaqpfw'),
				'add_new_item' => esc_html__('Add New Field', 'cloud_tech_rbpaqpfw'),
				'edit_item' => esc_html__('Edit Hide products and variation', 'cloud_tech_rbpaqpfw'),
				'new_item' => esc_html__('New Hide products and variation', 'cloud_tech_rbpaqpfw'),
				'view_item' => esc_html__('View Hide products and variation', 'cloud_tech_rbpaqpfw'),
				'search_items' => esc_html__('Search Hide products and variation', 'cloud_tech_rbpaqpfw'),
				'exclude_from_search' => true,
				'not_found' => esc_html__('No Hide products and variation found', 'cloud_tech_rbpaqpfw'),
				'not_found_in_trash' => esc_html__('No Hide products and variation found in trash', 'cloud_tech_rbpaqpfw'),
				'parent_item_colon' => '',
				'all_items' => esc_html__('Hide Products and Variation', 'cloud_tech_rbpaqpfw'),
				'menu_name' => esc_html__('Hide products and variation', 'cloud_tech_rbpaqpfw'),
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
				'rewrite' => array('slug' => 'ct_hide_prdct_nd_var', 'with_front' => false),
				'supports' => array('title', 'page-attributes')
			);
			register_post_type('ct_hide_prdct_nd_var', $args);

			// hide payment and shipping method 
			$labels = array(
				'name' => esc_html__('Hide Shipping and Payment Method', 'hide_payment_method'),
				'singular_name' => esc_html__('Hide Shipping and Payment Method', 'hide_payment_method'),
				'edit_item' => esc_html__('Edit Hide Shipping and Payment Method ', 'hide_payment_method'),
				'new_item' => esc_html__('Hide Shipping and Payment Method', 'hide_payment_method'),
				'view_item' => esc_html__('View Hide Shipping and Payment Method Cart', 'hide_payment_method'),
				'search_items' => esc_html__('Search Hide Shipping and Payment Method', 'hide_payment_method'),
				'not_found' => esc_html__('No Hide Shipping and Payment Method found', 'hide_payment_method'),
				'not_found_in_trash' => esc_html__('No bestprice found in trash', 'hide_payment_method'),
				'menu_name' => esc_html__('Hide Shipping and Payment Method', 'hide_payment_method'),
				'item_published' => esc_html__('Hide Shipping and Payment Method published', 'hide_payment_method'),
				'item_updated' => esc_html__('Hide Shipping and Payment Method updated', 'hide_payment_method'),
			);
			$supports = array(
				'title',
			);
			$options = array(
				'supports' => $supports,
				'labels' => $labels,
				'public' => true,
				'publicly_querable' => false,
				'query_var' => true,
				'capability_type' => 'post',
				'can_export' => true,
				'show_ui' => true,
				'show_in_admin_bar' => true,
				'exclude_from_search' => true,
				'show_in_menu' => 'b2bking',
				'has_archive' => true,
				'rewrite' => array(
					'slug',
					'mao_makeoffer',
					'with_front' => false,
				),
				'show_in_rest' => true,
			);
			register_post_type('city_hide', $options);

		} //end afdef_custom_post_type()

		public function ct_rbpaqp_enqueue_scripts()
		{

			if (is_admin()) {

				wp_enqueue_script('ct-rbpaqp', CT_RBPAQP_URL . 'assets/js/admin.js', array('jquery'), '1.0.0', false);
				wp_enqueue_style('admin-css', CT_RBPAQP_URL . 'assets/css/admin.css', array(), '1.0.0');

			} else {

				wp_enqueue_script('ct-rbpaqp', CT_RBPAQP_URL . 'assets/js/front.js', array('jquery'), '1.0.0', false);
				wp_enqueue_style('front-css', CT_RBPAQP_URL . 'assets/css/front.css', array(), '1.0.0');
			}

			wp_enqueue_style('font-awesome-lib', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0', false);

			wp_enqueue_script('jquery-ui-accordion');
			wp_enqueue_script('jquery-ui-sortable');

			wp_enqueue_style('wc-select2-css', plugins_url('assets/css/select2.css', WC_PLUGIN_FILE), array(), '5.7.2');
			wp_enqueue_script('wc-select2-js', plugins_url('assets/js/select2/select2.min.js', WC_PLUGIN_FILE), array('jquery'), '4.0.3', false);

			$af_c_f_data = array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('cloud-tech-rbpaqpfw-nonce'),
			);
			wp_localize_script('ct-rbpaqp', 'ct_rbpaqp_var', $af_c_f_data);

		}


	} //end class
	new b2b_extension_for_woocommerce();
}

