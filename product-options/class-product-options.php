<?php

defined('ABSPATH') || exit();


/**
 * Class start.
 */
class Prd_Options_Main
{

	public function __construct()
	{

		$this->prd_op_global_constents_vars();

		add_action('wp_loaded', array($this, 'prd_op_lang_load'));

		add_action('init', array($this, 'prod_opt_custom_post_type'));

		include PRO_OP_PLUGIN_DIR . 'classes/class-prd-general-functions.php';
		include PRO_OP_PLUGIN_DIR . 'classes/class-product-options-ajax-fields.php';
		include PRO_OP_PLUGIN_DIR . 'classes/class-product-options-product-fields.php';
		include PRO_OP_PLUGIN_DIR . 'classes/class-product-options-rule-fields.php';
		include PRO_OP_PLUGIN_DIR . 'classes/class-product-options-update-values.php';
		include PRO_OP_PLUGIN_DIR . 'classes/class-product-options-variation-fields.php';
		include PRO_OP_PLUGIN_DIR . 'classes/class-csv-upload-download.php';
		include PRO_OP_PLUGIN_DIR . 'classes/class-front-fields-show.php';
		include PRO_OP_PLUGIN_DIR . 'classes/class-front-fields-style.php';
		include PRO_OP_PLUGIN_DIR . 'classes/class-ajax-controller.php';
		include PRO_OP_PLUGIN_DIR . 'classes/class-prod-optns-woo-options.php';

		if (is_admin()) {

			include_once PRO_OP_PLUGIN_DIR . 'includes/admin/class-product-options-admin.php';

		} else {

			include_once PRO_OP_PLUGIN_DIR . 'includes/front/class-product-options-front.php';

		}
	}

	public function prd_op_global_constents_vars()
	{

		if (!defined('PRO_OP_URL')) {
			define('PRO_OP_URL', plugin_dir_url(__FILE__));
		}
		if (!defined('PRO_OP_BASENAME')) {
			define('PRO_OP_BASENAME', plugin_basename(__FILE__));
		}
		if (!defined('PRO_OP_PLUGIN_DIR')) {
			define('PRO_OP_PLUGIN_DIR', plugin_dir_path(__FILE__));
		}

		$upload_dir = wp_upload_dir();

		$upload_path = $upload_dir['basedir'] . '/product-options/';

		if (!is_dir($upload_path)) {
			mkdir($upload_path);
		}

		if (!defined('PRO_OP_MEDIA_PATH')) {
			define('PRO_OP_MEDIA_PATH', $upload_path);
		}

		$upload_url = $upload_dir['baseurl'] . '/product-options/';

		if (!defined('PRO_OP_MEDIA_URL')) {
			define('PRO_OP_MEDIA_URL', $upload_url);
		}
	}

	public function prd_op_lang_load()
	{

		if (function_exists('load_plugin_textdomain')) {

			load_plugin_textdomain('prod_options', false, dirname(plugin_basename(__FILE__)) . '/languages/');
		}
	}

	public function prod_opt_custom_post_type()
	{

		$supports = array(
			'title', // post title
			'page-attributes'
		);

		$labels = array(
			'name' => __('Product Options', 'prod_options'),
			'singular_name' => __('Product Option', 'prod_options'),
			'menu_name' => __('Product Options', 'prod_options'),
			'add_new' => __('Add New Product Option', 'prod_options'),
			'name_admin_bar' => __('Product Options', 'prod_options'),
			'edit_item' => __('Edit Options', 'prod_options'),
			'view_item' => __('View Product Options', 'prod_options'),
			'all_items' => __('Product Options', 'prod_options'),
			'search_items' => __('Search Product Options', 'prod_options'),
			'not_found' => __('No Product Options found.', 'prod_options'),
			'add_new_item' => __('Add New Product Options', 'prod_options'),
			'attributes' => __('Rule Priority', 'prod_options'),
		);

		$args = array(
			'supports' => $supports,
			'labels' => $labels,
			'description' => 'Product Options',
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => true,
			'show_in_menu' => 'b2bking',
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => false,
			'menu_position' => 30,
			'can_export' => true,
			'capability_type' => 'post',
			'show_in_rest' => true,
			'exclude_from_search' => true,
			'query_var' => true,
			'menu_icon' => 'dashicons-archive',
			'rewrite' => array(
				'slug' => 'product_options',
			),
			'has_archive' => true,
			'hierarchical' => false,
		);

		register_post_type('product_options', $args); // Register Post type

		$supports = array(
			'title', // post title
			'page-attributes'
		);

		$labels = array(
			'name' => __('Add Fields', 'prod_options'),
			'singular_name' => __('Add Field', 'prod_options'),
			'menu_name' => __('Add Fields', 'prod_options'),
			'add_new' => __('Add New Field', 'prod_options'),
			'name_admin_bar' => __('Field', 'prod_options'),
			'edit_item' => __('Edit Field', 'prod_options'),
			'view_item' => __('View Field', 'prod_options'),
			'all_items' => __('All Fields', 'prod_options'),
			'search_items' => __('Search Field', 'prod_options'),
			'not_found' => __('No Field found.', 'prod_options'),
			'add_new_item' => __('Add New Field', 'prod_options'),
			'attributes' => __('Field Priority', 'prod_options'),
		);

		$args = array(
			'supports' => $supports,
			'labels' => $labels,
			'description' => 'Add Fields',
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => false,
			'show_in_menu' => false,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'show_in_admin_bar' => false,
			'menu_position' => 30,
			'can_export' => true,
			'capability_type' => 'post',
			'show_in_rest' => true,
			'query_var' => true,
			'menu_icon' => 'dashicons-archive',
			'rewrite' => array(
				'slug' => 'product_field',
			),
			'has_archive' => true,
			'hierarchical' => false,
		);

		register_post_type('product_add_field', $args); // Register Post type

		$supports = array(
			'title', // post title
			'page-attributes'
		);

		$labels = array(
			'name' => __('Add Options', 'prod_options'),
			'singular_name' => __('Add Option', 'prod_options'),
			'menu_name' => __('Add Options', 'prod_options'),
			'add_new' => __('Add New Option', 'prod_options'),
			'name_admin_bar' => __('Options', 'prod_options'),
			'edit_item' => __('Edit Option', 'prod_options'),
			'view_item' => __('View Options', 'prod_options'),
			'all_items' => __('All Options', 'prod_options'),
			'search_items' => __('Search Option', 'prod_options'),
			'not_found' => __('No Option found.', 'prod_options'),
			'add_new_item' => __('Add New Option', 'prod_options'),
			'attributes' => __('Options Priority', 'prod_options'),
		);

		$args = array(
			'supports' => $supports,
			'labels' => $labels,
			'description' => 'Add Options',
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => false,
			'show_in_menu' => false,
			'exclude_from_search' => true,
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => false,
			'menu_position' => 30,
			'can_export' => true,
			'capability_type' => 'post',
			'show_in_rest' => true,
			'query_var' => true,
			'menu_icon' => 'dashicons-archive',
			'rewrite' => array(
				'slug' => 'product_option',
			),
			'has_archive' => true,
			'hierarchical' => false,
		);

		register_post_type('product_add_option', $args); // Register Post type
	}
}
if (class_exists('Prd_Options_Main')) {
	new Prd_Options_Main();
}