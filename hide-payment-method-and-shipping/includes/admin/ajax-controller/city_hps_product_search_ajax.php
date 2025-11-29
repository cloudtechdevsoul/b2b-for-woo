<?php

class Ct_Bid_Ajax_Controller {

	public function __construct() {
		add_action('wp_ajax_af_rfd_prod_search', array( $this, 'af_rfd_prod_search' ));
		add_action('wp_ajax_category_search', array( $this, 'category_search' ));
		add_action('wp_ajax_country_search', array( $this, 'country_search' ));
		add_action('wp_ajax_user_search', array( $this, 'user_search_function' ));
	}

	public function af_rfd_prod_search() {

		$nonce = isset( $_POST['nonce'] ) && '' !== $_POST['nonce'] ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;
		if ( ! wp_verify_nonce( $nonce, 'cthspm_nonce' ) ) {
			die( 'Failed ajax security check!' );
		}

		if (isset($_POST['q'])) {

			$pro = sanitize_text_field(wp_unslash($_POST['q']));
		} else {
			$pro = '';
		}

		$data_array = array();
		$args = array(
			'post_type' => array( 'product' ),
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids',
			's' => $pro,
		);

		$pros = get_posts($args);


		if (!empty($pros)) {
			foreach ($pros as $proo_ID) {
				$product_detail = wc_get_product($proo_ID);
				$title = ( mb_strlen($product_detail->get_name()) > 50 ) ? mb_substr($product_detail->get_name(), 0, 49) . '...' : $product_detail->get_name();
				$data_array[] = array( $proo_ID, $title ); // array( Post ID, Post Title ).
			}
		}
		echo wp_json_encode($data_array);
		die();
	}

	public function category_search() {
	
		$nonce = isset( $_POST['nonce'] ) && '' !== $_POST['nonce'] ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;
		if ( ! wp_verify_nonce( $nonce, 'cthspm_nonce' ) ) {
			die( 'Failed ajax security check!' );
		}

		if (isset($_POST['q']) && '' !== $_POST['q']) {
			if (!wp_verify_nonce($nonce, 'ka_gift_wrapper_nonce')) {
				die('Failed ajax security check!');
			}
			$pro = sanitize_text_field(wp_unslash($_POST['q']));
		} else {
			$pro = '';
		}
		$data_array = array();
		$orderby = 'name';
		$order = 'asc';
		$hide_empty = false;
		$cat_args = array(
			'orderby' => $orderby,
			'order' => $order,
			'hide_empty' => $hide_empty,
			'name__like' => $pro,
		);

		$product_categories = get_terms('product_cat', $cat_args);

		if (!empty($product_categories)) {
			foreach ($product_categories as $proo) {

				$pro_front_post = ( mb_strlen($proo->name) > 50 ) ? mb_substr($proo->name, 0, 49) . '...' : $proo->name;
				$data_array[] = array( $proo->term_id, $pro_front_post ); // array( Post ID, Post Title ).
			}
		}

		echo wp_json_encode($data_array);
		die();
	}
	public function country_search() {
		$nonce = isset($_POST['nonce']) && '' !== $_POST['nonce'] ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;

		if ( ! wp_verify_nonce( $nonce, 'cthspm_nonce' ) ) {
			die( 'Failed ajax security check!' );
		}

		$country_codes = isset($_POST['q']) && '' !== $_POST['q'] ? sanitize_text_field(wp_unslash($_POST['q'])) : array();


		$all_countries = WC()->countries->get_countries();

		$data_array = array();
		foreach ($all_countries as $code => $name) {
			// If no specific country code is provided or if the code matches the search term
			if (empty($country_codes) || in_array($code, $country_codes)) {
				$data_array[] = array( $code, $name );
			}
		}

		echo wp_json_encode($data_array);
		die();
	}
	function user_search_function() {

		$nonce = isset( $_POST['nonce'] ) && '' !== $_POST['nonce'] ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;
		if ( ! wp_verify_nonce( $nonce, 'cthspm_nonce' ) ) {
			die( 'Failed ajax security check!' );
		}
		

		$search_query = isset($_POST['q']) ? sanitize_text_field(wp_unslash($_POST['q'])) : '';

		$users = get_users(array(
			'search' => '*' . $search_query . '*',
			'search_columns' => array( 'user_login', 'user_nicename', 'user_email', 'display_name' ),
			'number' => 10, // Adjust as needed
		));

		$user_data = array();
		foreach ($users as $user) {
			$user_data[] = array(
				'ID' => $user->ID,
				'display_name' => $user->display_name,
			);
		}

		wp_send_json($user_data);
		die();
	}
}
new Ct_Bid_Ajax_Controller();
