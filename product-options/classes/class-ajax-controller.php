<?php
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Ajax Controller.
 */

class Ajax_Controller {

	public function __construct() {

		add_action('wp_ajax_product_options_on_with_variations', array($this,'product_option_on_variation'));
		add_action('wp_ajax_nopriv_product_options_on_with_variations', array($this,'product_option_on_variation'));
		add_action('wp_ajax_af_import_csv', array($this,'af_import_csv'));

	}

	public function product_option_on_variation() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'prod_optn_nonce' ) ) {
			die( 'Failed security check' );
		}
		
		if (!empty($_POST['variation_id'])) {

			$product_id = sanitize_text_field( $_POST['variation_id'] );

			ob_start();

			Prd_General_Functions::get_product_options( $product_id );

			$html = ob_get_clean();


			wp_send_json( array( 'new_html' => $html ) );

		}

	}

	public function af_import_csv() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'prod_optn_nonce' ) ) {
			die( 'Failed security check' );
		}

		Csv_Upload_Download::import_csv( $_POST, $_FILES );
	}

}

new Ajax_Controller();
