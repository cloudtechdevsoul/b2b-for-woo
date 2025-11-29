<?php
if (! defined('WPINC') ) {
	die;
}

class Ct_Tepfw_Ajax {

	public function __construct(){

		add_action( 'wp_ajax_product_search', array( $this, 'ct_tepfw_product_search' ) );
		add_action( 'wp_ajax_category_search', array( $this, 'ct_tepfw_category_search' ) );
		add_action( 'wp_ajax_ct_tepfw_add_new_customer_or_role_base_rbp',[$this,'ct_tepfw_add_new_customer_or_role_base_rbp']);
		add_action( 'wp_ajax_ct_tepfw_delete_post',[$this,'ct_tepfw_delete_post']);
	}

	public function ct_tepfw_product_search() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;
		
		if ( ! wp_verify_nonce( $nonce, 'cloud-tech-tepfw-nonce' ) ) {

			wp_die( esc_html__('Failed Security check!' , 'af_cogload_files') );
		}
		$pro 		= isset( $_POST['q'] ) && '' !== $_POST['q']  ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '';

		$data_array = array();
		$args       = array(
			'post_type'   => array( 'product' ,'product_variation' ),
			'post_status' => 'publish',
			'numberposts' => 100,
			's'           => $pro,
			'type'    => array( 'simple', 'variation' ), 

		);
		$pros 		= wc_get_products( $args );

		if ( ! empty( $pros ) ) {
			foreach ( $pros as $proo ) {
				$title        = ( mb_strlen( $proo->get_name() ) > 50 ) ? mb_substr( $proo->get_name(), 0, 49 ) . '...' : $proo->get_name();
				$data_array[] = array( $proo->get_id(), $title ); // array( Post ID, Post Title ).
			}
		}
		echo wp_json_encode( $data_array );
		die();
	}

	public function ct_tepfw_category_search() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'cloud-tech-tepfw-nonce' ) ) {

			wp_die( esc_html__('Failed Security check!' , 'af_cogload_files') );
		}

		$pro = isset( $_POST['q'] ) && '' !== $_POST['q']  ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '';

		$data_array         = array();
		$orderby            = 'name';
		$order              = 'asc';
		$hide_empty         = false;
		$cat_args           = array(
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $hide_empty,
			'name__like' => $pro,
		);
		$product_categories = get_terms( 'product_cat', $cat_args );
		if ( ! empty( $product_categories ) ) {
			foreach ( $product_categories as $proo ) {
				$pro_front_post = ( mb_strlen( $proo->name ) > 50 ) ? mb_substr( $proo->name, 0, 49 ) . '...' : $proo->name;
				$data_array[]   = array( $proo->term_id, $pro_front_post ); // array( Post ID, Post Title ).
			}
		}
		echo wp_json_encode( $data_array );
		die();
	}

	public function ct_tepfw_delete_post() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'cloud-tech-tepfw-nonce' ) ) {

			wp_die( esc_html__('Failed Security check!' , 'af_cogload_files') );
		}

		if ( isset( $_POST['post_id'] ) ) {
			

			wp_delete_post( sanitize_text_field( $_POST['post_id'] ) );


			wp_send_json(['delete' => true]);

			wp_die();

		}	
	}
}

new Ct_Tepfw_Ajax();