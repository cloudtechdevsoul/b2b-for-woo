<?php
if (! defined('WPINC') ) {
	die;
}

class Ct_Rbpaqp_Ajax {

	public function __construct(){

		add_action( 'wp_ajax_product_search', array( $this, 'product_search' ) );
		add_action( 'wp_ajax_category_search', array( $this, 'category_search' ) );
		add_action( 'wp_ajax_ct_rbpaqp_add_new_customer_or_role_base_rbp',[$this,'ct_rbpaqp_add_new_customer_or_role_base_rbp']);
		add_action( 'wp_ajax_ct_rbpaqp_delete_post',[$this,'ct_rbpaqp_delete_post']);


		// get product min max _quantity.
		add_action( 'wp_ajax_ct_rbpaq_get_product_min_max_qty',[$this,'ct_rbpaq_get_product_min_max_qty']);
		add_action( 'wp_ajax_nopriv_ct_rbpaq_get_product_min_max_qty',[$this,'ct_rbpaq_get_product_min_max_qty']);


	}

	public function product_search() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;
		
		if ( ! wp_verify_nonce( $nonce, 'cloud-tech-rbpaqpfw-nonce' ) ) {

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

	public function category_search() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'cloud-tech-rbpaqpfw-nonce' ) ) {

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

	public function ct_rbpaqp_add_new_customer_or_role_base_rbp() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'cloud-tech-rbpaqpfw-nonce' ) ) {

			wp_die( esc_html__('Failed Security check!' , 'af_cogload_files') );
		}

		if ( isset( $_POST['add_with'] ) && isset( $_POST['form'] ) && isset( $_POST['post_id'] ) ) {
			

			$add_with 				= sanitize_text_field( $_POST['add_with'] );

			parse_str( sanitize_text_field($_POST['form']) , $form );
			$form['post_id']  		= sanitize_text_field( $_POST['post_id'] );	

			if ( 'customers-base' == $add_with ) {

				$current_post_id 	= wp_insert_post([
					'post_type' 	=> 'ct_set_role_for_cbp',
					'post_status' 	=> 'publish',
					'post_parent' 	=> $form['post_id'],
				]);
				
				ob_start();
				get_role_base_and_customer_base_pricing( $current_post_id , $discount_for = 'customer' );
				$result 	= ob_get_clean();


				wp_send_json( [ 'html' => $result ] ); 

			}

			if ( 'role-base' == $add_with ) {

				$current_post_id 	= wp_insert_post([
					'post_type' 	=> 'ct_set_role_for_rbp',
					'post_status' 	=> 'publish',
					'post_parent' 	=> $form['post_id'],
				]);

				ob_start();
				get_role_base_and_customer_base_pricing( $current_post_id , 'user_role' );
				$result 	= ob_get_clean();

				wp_send_json( [ 'html' => $result ] ); 

			}


		}

		wp_die();
	}

	public function ct_rbpaqp_delete_post() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'cloud-tech-rbpaqpfw-nonce' ) ) {

			wp_die( esc_html__('Failed Security check!' , 'af_cogload_files') );
		}

		if ( isset( $_POST['post_id'] ) ) {
			

			wp_delete_post( sanitize_text_field( $_POST['post_id'] ) );


			wp_send_json(['delete' => true]);

			wp_die();

		}	
	}

	public function ct_rbpaq_get_product_min_max_qty() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'cloud-tech-rbpaqpfw-nonce' ) ) {

			wp_die( esc_html__('Failed Security check!' , 'af_cogload_files') );
		}

		if ( isset( $_POST['form_data'] ) ) {
			
			parse_str( $_POST['form_data'], $form_data );

			$product_id 	= isset( $form_data['variation_id'] ) && $form_data['variation_id'] >= 1 ? $form_data['variation_id'] : $form_data['product_id'];
			$check_product_detail = (array) ct_rbpaqp_get_product_min_max_quantity( $product_id );

			$product 		= wc_get_product( $product_id ); 
			$product_detail = [];
			$product_detail['min_value'] 		= $product->get_min_purchase_quantity();
			$product_detail['max_value'] 		= $product->get_max_purchase_quantity() < 0 ? '' : $product->get_max_purchase_quantity();
			$product_detail['step'] 			= 1;
			
			  if (is_a($product, 'WC_Product_Variation')) {
		        // Get the quantity step for the variation
		        $product_detail['step'] = $product->get_attribute('step');
		        
		    }

			if ( count( $check_product_detail ) >= 1 ) {

				if ( isset( $check_product_detail['min_value'] ) && !empty( $check_product_detail['min_value'] ) ) {
					$product_detail['min_value'] = $check_product_detail['min_value'];
				}

				if ( isset( $check_product_detail['max_value'] ) && !empty( $check_product_detail['max_value'] ) ) {
					$product_detail['max_value'] = $check_product_detail['max_value'];
				}
				if ( isset( $check_product_detail['step'] ) && !empty( $check_product_detail['step'] ) ) {
					$product_detail['step'] = $check_product_detail['step'];
				}
			}


			$html 			= ob_start();
			if ( count( $product_detail ) >1   ) {
				ct_rbpaqp_get_product_min_max_quantity_template( $product_detail );
			}
			$html 			= ob_get_clean();


			wp_send_json( [ 'product_qty_table_html' => $html,'product_detail' => $product_detail ,'product_id' => $product_id ] );

			wp_die();

		}
	}
}

new Ct_Rbpaqp_Ajax();