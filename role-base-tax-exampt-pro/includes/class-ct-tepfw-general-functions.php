<?php
if (!defined('WPINC')) {
	die;
}


if (!function_exists('get_set_roles')) {


	function get_set_roles()
	{

		global $wp_roles;

		$all_role = $wp_roles->get_names();

		$all_role['guest'] = 'Guest';

		foreach ((array) get_option('ct_tepfw_excludes_rule') as $role_key) {

			if (!empty($role_key) && $all_role[$role_key]) {

				unset($all_role[$role_key]);

			}

		}

		return $all_role;

	}

}



function ct_tepfw_get_current_user_country()
{
	$geo_data = WC_Geolocation::geolocate_ip();
	return $geo_data['country'];
}

function ct_tepfw_get_current_user_role()
{
	return is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';
}

function convertToLower($value)
{
	return strtolower($value);
}

function ct_tepfw_mmq_get_current_user_country()
{
	$geo_data = WC_Geolocation::geolocate_ip();
	return $geo_data['country'];
}

function ct_tepfw_mmq_get_current_user_role()
{
	return is_user_logged_in() ? current(wp_get_current_user()->roles) : 'guest';
}


function chek_user_is_tax_exemptes($check_from_car_or_my_account = '')
{


	$is_user_aplicable = '';
	if ('my-account' == $check_from_car_or_my_account) {

		$get_post = get_posts(['post_type' => 'ct_tax_exempt_pro', 'post_status' => 'publish', 'post_per_page' => 1, 'fields' => 'ids']);

		foreach ($get_post as $current_post_id) {

			$selected_country = (array) get_post_meta($current_post_id, 'ct_tepfw_inlcuded_country', true);
			$selected_customer = (array) get_post_meta($current_post_id, 'ct_tepfw_inlcuded_customers', true);
			$selected_role = (array) get_post_meta($current_post_id, 'ct_tepfw_inlcuded_role', true);

			if (!empty($selected_country) && !empty(ct_tepfw_mmq_get_current_user_country()) && !in_array(ct_tepfw_mmq_get_current_user_country(), $selected_country)) {
				continue;
			}

			if (!empty($selected_customer) && !empty(get_current_user_id()) && !in_array(get_current_user_id(), $selected_customer)) {
				continue;
			}

			if (!empty($selected_role) && !empty(ct_tepfw_mmq_get_current_user_role()) && !in_array(ct_tepfw_mmq_get_current_user_role(), $selected_role)) {

				continue;
			}
			$is_user_aplicable = $current_post_id;

		}
	}

	if ('checkout' == $check_from_car_or_my_account) {

		$get_post = get_posts(['post_type' => 'ct_tax_exempt_pro', 'post_status' => 'publish', 'post_per_page' => 1, 'fields' => 'ids']);

		foreach ($get_post as $current_post_id) {

			$selected_product = (array) get_post_meta($current_post_id, 'ct_tepfw_inlcuded_products', true);
			$selected_category = (array) get_post_meta($current_post_id, 'ct_tepfw_inlcuded_category', true);
			$selected_tag = (array) get_post_meta($current_post_id, 'ct_tepfw_inlcuded_tag', true);
			$selected_country = (array) get_post_meta($current_post_id, 'ct_tepfw_inlcuded_country', true);
			$selected_customer = (array) get_post_meta($current_post_id, 'ct_tepfw_inlcuded_customers', true);
			$selected_role = (array) get_post_meta($current_post_id, 'ct_tepfw_inlcuded_role', true);


			if (!empty($selected_country) && !empty(ct_tepfw_mmq_get_current_user_country()) && !in_array(ct_tepfw_mmq_get_current_user_country(), $selected_country)) {
				continue;
			}

			if (!empty($selected_customer) && !empty(get_current_user_id()) && !in_array(get_current_user_id(), $selected_customer)) {
				continue;
			}

			if (!empty($selected_role) && !empty(ct_tepfw_mmq_get_current_user_role()) && !in_array(ct_tepfw_mmq_get_current_user_role(), $selected_role)) {

				continue;
			}


			if (isset(wc()->cart)) {

				foreach (wc()->cart->get_cart() as $key => $value) {

					if (isset($value['product_id'])) {

						$product_id = $value['product_id'];

						if (empty($selected_product) && empty($selected_category) && empty($selected_tag)) {
							return $current_post_id;

						}

						if (!empty($selected_product) && in_array($product_id, $selected_product)) {
							return $current_post_id;

						}

						if (!empty($selected_category) && has_term($selected_category, 'product_cat', $product_id)) {
							return $current_post_id;

						}


						if (!empty($selected_tag) && has_term($selected_tag, 'product_tag', $product_id)) {
							return $current_post_id;

						}

					}
				}

			}

		}
	}

	return $is_user_aplicable;

}



function notify_to_customer($user_id)
{


	$pending_request_email_Setting = get_option('ct_tepfw_pending_request_email');
	$accepted_request_email_Setting = get_option('ct_tepfw_accepted_request_email');
	$rejected_request_email_Setting = get_option('ct_tepfw_rejected_request_email');

	$accepted_request_email_content = get_option('ct_tepfw_accepted_request_email_content');
	$rejected_request_email_content = get_option('ct_tepfw_rejected_request_email_content');
	$pending_request_email_content = get_option('ct_tepfw_pending_request_email_content');

	$user_data = get_userdata($user_id);
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;
	$display_name = $user_data->display_name;



	if ('pending' == get_user_meta(get_current_user_id(), 'ct_tepfw_is_tax_exempted', true) && isset($pending_request_email_Setting['enable_setting'])) {

		$subject = isset($pending_request_email_Setting['subject']) ? $pending_request_email_Setting['subject'] : 'Your Request For Pending Status Detail !';
		$message = ct_tepfw_replace_mail_text($user_data, $pending_request_email_content);


		wp_mail($user_email, $subject, $message, '', );
	}

	if ('approved' == get_user_meta(get_current_user_id(), 'ct_tepfw_is_tax_exempted', true) && isset($accepted_request_email_Setting['enable_setting'])) {

		$subject = isset($accepted_request_email_Setting['subject']) ? $accepted_request_email_Setting['subject'] : 'Your Request For Pending Status Detail !';
		$message = ct_tepfw_replace_mail_text($user_data, $accepted_request_email_content);
		wp_mail($user_email, $subject, $message, '', );
	}

	if ('cancelled' == get_user_meta(get_current_user_id(), 'ct_tepfw_is_tax_exempted', true) && isset($rejected_request_email_Setting['enable_setting'])) {

		$subject = isset($rejected_request_email_Setting['subject']) ? $rejected_request_email_Setting['subject'] : 'Your Request For Pending Status Detail !';
		$message = ct_tepfw_replace_mail_text($user_data, $rejected_request_email_content);
		wp_mail($user_email, $subject, $message, '', );
	}

}

function ct_tepfw_replace_mail_text($user_data, $content)
{

	$content_data = ['{user_my_account_link}' => '<a href="' . wc_get_account_endpoint_url('ct-tax-exempt-pro') . '">Your Tax Exempt Detail Page</a>', '{user_email}' => $user_data->user_email, '{display_name}' => $user_data->display_name, '{user_status}' => ucfirst(get_user_meta($user_data->ID, 'ct_tepfw_is_tax_exempted', true))];

	foreach ($content_data as $key => $value) {
		$content = str_replace($key, $value, $content);
	}

	return $content;

}