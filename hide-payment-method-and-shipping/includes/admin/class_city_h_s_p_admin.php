<?php
if (!defined('ABSPATH')) {
	exit;
}
class CT_H_P_S_Admin
{


	public function __construct()
	{
		add_action('admin_enqueue_scripts', array($this, 'enque_files'));

		add_action('save_post_city_hide', array($this, 'city_save_meta_values'));

		add_action('add_meta_boxes', array($this, 'city_hide_metabox'));
	}

	public function city_save_meta_values($post_id)
	{

		if (!current_user_can('edit_posts')) {
			return;
		}

		$exclude_statuses = array(
			'auto-draft',
			'trash',
		);

		if (in_array(get_post_status($post_id), $exclude_statuses)) {
			return;
		}
		$nonce = isset($_POST['ct_hpasm']) ? sanitize_text_field(wp_unslash($_POST['ct_hpasm'])) : 0;

		if (!wp_verify_nonce($nonce, 'ct_hpasm')) {
			wp_die('Failed ajax security');
		}

		$af_bids_review_products = isset($_POST['af_hspm_review_products']) ? sanitize_meta('', $_POST['af_hspm_review_products'], '') : array();

		update_post_meta($post_id, 'af_hspm_review_products', $af_bids_review_products);

		$af_hspm_category_review = isset($_POST['af_hspm_category_review']) ? sanitize_meta('', $_POST['af_hspm_category_review'], '') : array();

		update_post_meta($post_id, 'af_hspm_category_review', $af_hspm_category_review);


		$ct_hsp_user_role = isset($_POST['ct_hsp_user_role']) ? sanitize_meta('', $_POST['ct_hsp_user_role'], '') : array();

		update_post_meta($post_id, 'ct_hsp_user_role', $ct_hsp_user_role);


		$cthspm_country_review = isset($_POST['cthspm_country_review']) ? sanitize_meta('', $_POST['cthspm_country_review'], '') : array();

		update_post_meta($post_id, 'cthspm_country_review', $cthspm_country_review);



		$af_hspm_user_review = isset($_POST['af_hspm_user_review']) ? sanitize_meta('', $_POST['af_hspm_user_review'], '') : array();

		update_post_meta($post_id, 'af_hspm_user_review', $af_hspm_user_review);


		$city_cart_minmum_amount = isset($_POST['city_cart_minmum_amount']) ? sanitize_text_field($_POST['city_cart_minmum_amount']) : '';
		update_post_meta($post_id, 'city_cart_minmum_amount', $city_cart_minmum_amount);


		$city_cart_maximum_amount = isset($_POST['city_cart_maximum_amount']) ? sanitize_text_field($_POST['city_cart_maximum_amount']) : '';
		update_post_meta($post_id, 'city_cart_maximum_amount', $city_cart_maximum_amount);




		$ct_q_t = isset($_POST['ct_q_t']) ? sanitize_text_field($_POST['ct_q_t']) : '';
		update_post_meta($post_id, 'ct_q_t', $ct_q_t);

		$city_restrict_cart_total = isset($_POST['city_restrict_cart_total']) ? sanitize_meta('', $_POST['city_restrict_cart_total'], '') : array();

		update_post_meta($post_id, 'city_restrict_cart_total', $city_restrict_cart_total);


		$city_payment_or_shipping = isset($_POST['city_payment_or_shipping']) ? sanitize_meta('', $_POST['city_payment_or_shipping'], '') : array();

		update_post_meta($post_id, 'city_payment_or_shipping', $city_payment_or_shipping);




		$cthspm_payment_method_review = isset($_POST['cthspm_payment_method_review']) ? sanitize_meta('', $_POST['cthspm_payment_method_review'], '') : array();

		update_post_meta($post_id, 'cthspm_payment_method_review', $cthspm_payment_method_review);




		$cthspm_shipping_methods = isset($_POST['cthspm_shipping_methods']) ? sanitize_meta('', $_POST['cthspm_shipping_methods'], '') : array();

		update_post_meta($post_id, 'cthspm_shipping_methods', $cthspm_shipping_methods);
	}



	public function city_hide_metabox()
	{

		add_meta_box(
			'citt_meta_box_id',          // Unique ID
			'Hide Payment and shipping method',   //Name
			array($this, 'city_hide_meta_box'),
			'city_hide' //post types here

		);
	}

	public function city_hide_meta_box()
	{
		$current_post_id = get_the_ID();
		wp_nonce_field('hspm_nonce', 'hspm_nonce_field');

		include_once CPS_PLUGIN_DIR . '/includes/admin/meta-box/city_hide_meta_fields.php';
	}
	public function enque_files()
	{

		wp_enqueue_style('hsm-admin', CPS_URL . '/assests/css/city_metabox_styling.css', false, '1.0.0');
		wp_enqueue_style('hsm_admin', CPS_URL . '/assests/css/select2.css', false, '1.0.0');
		wp_enqueue_script('hsm-admin', CPS_URL . '/assests/js/admin.js', false, '1.0.0');
		wp_enqueue_script('hsm-select2', CPS_URL . '/assests/js/select2.js', false, '1.0.0');
		$aurgs = array(
			'admin_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('cthspm_nonce'),

		);

		wp_localize_script('hsm-admin', 'cthspm_bidd_data', $aurgs);
	}
}


if (class_exists('CT_H_P_S_Admin')) {
	new CT_H_P_S_Admin();
}
