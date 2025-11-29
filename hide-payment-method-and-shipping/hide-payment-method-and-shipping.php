<?php
if (!defined('ABSPATH')) {
	exit;
}

class Hide_Payment_Method_And_Shipping
{

	public function __construct()
	{

		$this->city_hide_global_constents_vars();
		add_action('wp_loaded', array($this, 'city_hide_domain'));

		include CPS_PLUGIN_DIR . 'includes/admin/ajax-controller/city_hps_product_search_ajax.php';

		if (is_admin()) {
			include CPS_PLUGIN_DIR . 'includes/admin/class_city_h_s_p_admin.php';
		}
		include CPS_PLUGIN_DIR . 'includes/front/class_city_h_s_p_front.php';
	}


	public function city_hide_global_constents_vars()
	{

		if (!defined('CPS_URL')) {
			define('CPS_URL', plugin_dir_url(__FILE__));
		}
		if (!defined('CPS_BASENAME')) {
			define('CPS_BASENAME', plugin_basename(__FILE__));
		}

		if (!defined('CPS_PLUGIN_DIR')) {
			define('CPS_PLUGIN_DIR', plugin_dir_path(__FILE__));
		}
	}

	public function city_hide_domain()
	{

		if (function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain('make_an_offer', false, dirname(plugin_basename(__FILE__)) . '/languages/');
		}
	}
}
if (class_exists('Hide_Payment_Method_And_Shipping')) {
	new Hide_Payment_Method_And_Shipping();
}