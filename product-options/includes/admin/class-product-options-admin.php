<?php

/**
 * Admin Class
 *
 * @package : product-options 
 */

defined( 'ABSPATH' ) || exit();

class Prd_Options_Admin {

	public function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'prd_opt_admin_enqueue_file' ) );

		add_action( 'add_meta_boxes', array( $this, 'prd_opt_add_meta_boxes' ) );

		add_action( 'save_post_product_options', array( $this, 'prd_opt_save_metabox_values' ), 1 );

		add_action( 'wp_ajax_add_new_field', array( $this, 'add_new_field' ) );

		add_action( 'wp_ajax_remove_field', array( $this, 'remove_field' ) );

		add_action( 'wp_ajax_add_new_option', array( $this, 'add_new_option' ) );

		add_action( 'wp_ajax_remove_option', array( $this, 'remove_option' ) );

		add_action( 'wp_ajax_dependable_option', array( $this, 'dependable_option' ) );

		add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_tab_in_prod' ), 98, 1 );

		add_filter( 'woocommerce_product_data_panels', array( $this, 'product_options_data_panels' ), 98 );

		add_action( 'save_post_product', array( $this, 'prod_settings_save_options' ), 2 );

		add_action('woocommerce_product_after_variable_attributes', array($this, 'add_prod_optns_in_variation'), 100, 3);

		add_action( 'woocommerce_save_product_variation', array($this, 'save_optns_variation'), 10, 2 );

		add_action( 'wp_loaded', array($this, 'csv_upload') );

		add_action( 'woocommerce_after_order_itemmeta', array( $this, 'show_option_name_in_order_detail' ), 10 , 3);
	}

	public function prd_opt_admin_enqueue_file() {

		wp_enqueue_media();
		wp_enqueue_style( 'admin_css', plugins_url( '../../assets/css/admin.css', __FILE__ ), false, '1.0.0' );
		wp_enqueue_style( 'select_css', plugins_url( '../../assets/css/select2.css', __FILE__ ), false, '1.0.0' );
		wp_enqueue_script( 'admin_js', plugins_url( '../../assets/js/admin.js', __FILE__ ), array( 'jquery' ), '1.0.0', false );
		wp_enqueue_script( 'select_js', plugins_url( '../../assets/js/select2.js', __FILE__ ), array( 'jquery' ), '1.0.0', false );

		ob_start();
		$this->bg_radius();
		$bg_radius = ob_get_clean();

		ob_start();
		$this->bg_padding();
		$bg_padding = ob_get_clean();

		ob_start();
		$this->field_border_radius();
		$field_border_radius = ob_get_clean();

		ob_start();
		$this->field_inside_padding();
		$field_inside_padding = ob_get_clean();

		$product_options_ajax_data = array(
			'admin_url' => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( 'prod_optn_nonce' ),
			'bg_radius' => $bg_radius,
			'bg_padding' => $bg_padding,
			'field_border_radius' => $field_border_radius,
			'field_inside_padding' => $field_inside_padding
		);
		wp_localize_script( 'admin_js', 'prod_options_url', $product_options_ajax_data );
	}

	public function bg_radius() {

		$bg_radius      = (array) get_option('bg_radius');
		$bg_r_top_left  = isset($bg_radius['top_left']) ? $bg_radius['top_left'] 	:0;
		$bg_r_top_right = isset($bg_radius['top_right'])? $bg_radius['top_right'] 	:0;
		$bg_r_btm_left  = isset($bg_radius['btm_left']) ? $bg_radius['btm_left'] 	:0;
		$bg_r_btm_right = isset($bg_radius['btm_right'])? $bg_radius['btm_right'] 	:0;
		?>
		<div style="display: inline-flex; width: 40%;" class="title_bg_radius">
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="bg_radius[top_left]" class="bg_radius" style="width: 100%;" value="<?php echo esc_attr($bg_r_top_left); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Top left ' ); ?></i></span>
			</div>
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="bg_radius[top_right]" class="bg_radius" style="width: 100%;" value="<?php echo esc_attr($bg_r_top_right); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Top right' ); ?></i></span>
			</div>
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="bg_radius[btm_left]" class="bg_radius" style="width: 100%;" value="<?php echo esc_attr($bg_r_btm_left); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Bottom left' ); ?></i></span>
			</div>
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="bg_radius[btm_right]" class="bg_radius" style="width: 100%;" value="<?php echo esc_attr($bg_r_btm_right); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Bottom right' ); ?></i></span>
			</div>
		</div>
		<?php
	}

	public function bg_padding() {

		$bg_padding = (array) get_option('bg_padding');
		$bg_p_top  	= isset($bg_padding['top']) ? $bg_padding['top'] 	:0;
		$bg_p_btm   = isset($bg_padding['bottom'])? $bg_padding['bottom'] 	:0;
		$bg_p_left  = isset($bg_padding['left']) ? $bg_padding['left'] 	:0;
		$bg_p_right = isset($bg_padding['right'])? $bg_padding['right'] 	:0;
		?>
		<div style="display: inline-flex; width: 40%;" class="title_bg_padding">
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="bg_padding[top]" style="width: 100%;" value="<?php echo esc_attr($bg_p_top); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Top' ); ?></i></span>
			</div>
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="bg_padding[bottom]" style="width: 100%;" value="<?php echo esc_attr($bg_p_btm); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Bottom' ); ?></i></span>
			</div>
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="bg_padding[left]" style="width: 100%;" value="<?php echo esc_attr($bg_p_left); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Left' ); ?></i></span>
			</div>
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="bg_padding[right]" style="width: 100%;" value="<?php echo esc_attr($bg_p_right); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Right' ); ?></i></span>
			</div>
		</div>
		<?php
	}

	public function field_border_radius() {
		$field_border_radius = (array) get_option('field_border_radius');
		$f_b_r_top_left      = isset($field_border_radius['top_left']) ? $field_border_radius['top_left'] 	:0;
		$f_b_r_top_right     = isset($field_border_radius['top_right'])? $field_border_radius['top_right'] 	:0;
		$f_b_r_bottom_left   = isset($field_border_radius['bottom_left']) ? $field_border_radius['bottom_left'] 	:0;
		$f_b_r_bottom_right  = isset($field_border_radius['bottom_right'])? $field_border_radius['bottom_right'] 	:0;
		?>
		<div style="display: inline-flex; width: 40%;" class="field_border_radius">
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="field_border_radius[top_left]" style="width: 100%;" value="<?php echo esc_attr($f_b_r_top_left); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Top left' ); ?></i></span>
			</div>
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="field_border_radius[top_right]" style="width: 100%;" value="<?php echo esc_attr($f_b_r_top_right); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Top right' ); ?></i></span>
			</div>
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="field_border_radius[bottom_left]" style="width: 100%;" value="<?php echo esc_attr($f_b_r_bottom_left); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Bottom left' ); ?></i></span>
			</div>
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="field_border_radius[bottom_right]" style="width: 100%;" value="<?php echo esc_attr($f_b_r_bottom_right); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Bottom right' ); ?></i></span>
			</div>
		</div>
		<?php
	}

	public function field_inside_padding() {

		$field_inside_padding = (array) get_option('field_inside_padding');
		$f_i_p_top            = isset($field_inside_padding['top']) ? $field_inside_padding['top'] 	:0;
		$f_i_p_bottom         = isset($field_inside_padding['bottom'])? $field_inside_padding['bottom'] 	:0;
		$f_i_p_left           = isset($field_inside_padding['left']) ? $field_inside_padding['left'] 	:0;
		$f_i_p_right          = isset($field_inside_padding['right'])? $field_inside_padding['right'] 	:0;
		?>
		<div style="display: inline-flex; width: 40%;" class="field_inside_padding">
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="field_inside_padding[top]" style="width: 100%;" value="<?php echo esc_attr($f_i_p_top); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Top' ); ?></i></span>
			</div>
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="field_inside_padding[bottom]" style="width: 100%;" value="<?php echo esc_attr($f_i_p_bottom); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Bottom' ); ?></i></span>
			</div>
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="field_inside_padding[left]" style="width: 100%;" value="<?php echo esc_attr($f_i_p_left); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Left' ); ?></i></span>
			</div>
			<div style="width: 25%; padding: 5px; text-align: center;">
				<input type="number" name="field_inside_padding[right]" style="width: 100%;" value="<?php echo esc_attr($f_i_p_right); ?>" min="0">
				<span class="span_class"><i><?php echo esc_html( 'Right' ); ?></i></span>
			</div>
		</div>
		<?php
	}

	public function prd_opt_add_meta_boxes() {

		add_meta_box(
			'prd_optn_user_role',
			esc_html__('User Roles', 'prod_options'),
			array( $this, 'prd_optn_user_role'),
			'product_options'
		);

		add_meta_box(
			'prd_optn_products',
			esc_html__('Products', 'prod_options'),
			array( $this, 'prd_optn_products'),
			'product_options'
		);

		add_meta_box(
			'prd_optn_fields',
			esc_html__('Fields', 'prod_options'),
			array( $this, 'prd_optn_fields'),
			'product_options'
		);
	}

	public function prd_optn_user_role() {

		wp_nonce_field('rule_nonce', 'rule_nonce_field');
		$rule_id = get_the_ID();
		Product_Options_Rule_Fields::ck_rule_user_role($rule_id);
	}

	public function prd_optn_products() {

		$rule_id = get_the_ID();
		Product_Options_Rule_Fields::ck_rule_prod_select($rule_id);
	}

	public function prd_optn_fields() {

		$rule_id = get_the_ID();
		Product_Options_Rule_Fields::ck_rule_fields($rule_id);
	}

	public function prd_opt_save_metabox_values( $rule_id ) {

		if ( 'auto-draft' != get_post_status( $rule_id ) && 'trash' != get_post_status( $rule_id ) ) {

			$nonce = isset($_POST['rule_nonce_field']) ? sanitize_text_field($_POST['rule_nonce_field']) : 0;

			if (!wp_verify_nonce($nonce, 'rule_nonce')) {
				die('Failed Security Check');
			}

			Product_Options_Update_Value::ck_update_rule_values( $_POST, $rule_id );

			$field_ids = Prd_General_Functions::get_posts_ids_of_current_post('Product_add_field', $rule_id);

			foreach ($field_ids as $field_id) {
				if (empty($field_id)) {
					continue;
				}

				Product_Options_Update_Value::ck_update_field_values( $_POST, $field_id );

				$option_idss = Prd_General_Functions::get_posts_ids_of_current_post('product_add_option', $field_id);
	
				foreach ( $option_idss as $option_id ) {

					if (empty($option_id)) {
						continue;
					}

					Product_Options_Update_Value::ck_update_option_values( $_POST, $field_id, $option_id );
				}

			}
		}
	}

	public function add_new_field() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'prod_optn_nonce' ) ) {
			die( 'Failed security check' );
		}

		if (isset($_POST['rule_id']) && isset($_POST['type'])) {

			$post_parent_id = sanitize_text_field($_POST['rule_id']);
			$type           = sanitize_text_field($_POST['type']);

			$create_field = array(
				'post_type' 	=> 'product_add_field',
				'post_status'	=> 'publish',
				'post_parent'   => $post_parent_id,
			);
			$insert_field = wp_insert_post($create_field);
			$field_id     = $insert_field;
			?>
			<input type="hidden" name="new_field_id" class="new_field_id" value="<?php echo esc_attr($field_id); ?>">
			<?php

			$rule_id = $post_parent_id;

			if ('product' == $type) {

				// include PRO_OP_PLUGIN_DIR . 'includes/admin/metaboxes/ajax/product/prod_field_ajax_template.php';
				Product_Options_Ajax_Fields::ajax_product_field_template( $field_id, $post_parent_id );

			} elseif ('variation' == $type) {
				
				// include PRO_OP_PLUGIN_DIR . 'includes/admin/metaboxes/ajax/variation/var_field_ajax_template.php';
				Product_Options_Ajax_Fields::ajax_variation_field_template( $field_id, $post_parent_id );

			} else {

				Product_Options_Ajax_Fields::ajax_rule_field_template( $field_id, $post_parent_id );
				// include PRO_OP_PLUGIN_DIR . 'includes/admin/metaboxes/ajax/rule/field_ajax_template.php';
			}
		}
		die('');
	}

	public function remove_field() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'prod_optn_nonce' ) ) {
			die( 'Failed security check' );
		}

		if (isset($_POST['field_id']) && isset($_POST['type'])) {

			$field_id = isset( $_POST['field_id'] ) ? sanitize_text_field( $_POST['field_id'] ) : '';
			$type     = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';

			wp_delete_post($field_id);

			// include PRO_OP_PLUGIN_DIR . 'includes/admin/metaboxes/prod_optn_fields.php';

			if ('product' == $type) {

				// include PRO_OP_PLUGIN_DIR . 'includes/admin/metaboxes/ajax/product/prod_field_ajax_template.php';
				$rule_id = wp_get_post_parent_id($field_id);
				Product_Options_Product_Fields::ck_product_level_fields( $rule_id );

			} elseif ('variation' == $type) {
				
				// include PRO_OP_PLUGIN_DIR . 'includes/admin/metaboxes/ajax/variation/var_field_ajax_template.php';
				$rule_id = wp_get_post_parent_id($field_id);
				Product_Options_Variation_Fields::ck_variation_level_fields( $field_id );

			} else {

				$rule_id = wp_get_post_parent_id($field_id);
				Product_Options_Rule_Fields::ck_rule_fields( $rule_id );
				// include PRO_OP_PLUGIN_DIR . 'includes/admin/metaboxes/ajax/rule/field_ajax_template.php';
			}

		}

		die();
	}

	public function add_new_option() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'prod_optn_nonce' ) ) {
			die( 'Failed security check' );
		}

		if (isset($_POST['parent_id']) && isset($_POST['type'])) {

			$option_parent_id = sanitize_text_field($_POST['parent_id']);
			$type             = sanitize_text_field($_POST['type']);

			$create_option = array(
				'post_type' 	=> 'product_add_option',
				'post_status'	=> 'publish',
				'post_parent'   => $option_parent_id,
			);
			$option_id     = wp_insert_post($create_option);
			$field_id      = $option_parent_id;

			if ('product' == $type) {

				// include PRO_OP_PLUGIN_DIR . 'includes/admin/metaboxes/ajax/product/prod_option_ajax_template.php';
				Product_Options_Ajax_Fields::ajax_product_row_template( $option_id, $option_parent_id );

			} elseif ('variation' == $type) {
				
				// include PRO_OP_PLUGIN_DIR . 'includes/admin/metaboxes/ajax/variation/var_option_ajax_template.php';
				Product_Options_Ajax_Fields::ajax_variation_row_template( $option_id, $option_parent_id );

			} else {

				// include PRO_OP_PLUGIN_DIR . 'includes/admin/metaboxes/ajax/rule/option_ajax_template.php';
				Product_Options_Ajax_Fields::ajax_rule_row_template( $option_id, $option_parent_id );
			}

		}

		die();
	}

	public function remove_option() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'prod_optn_nonce' ) ) {
			die( 'Failed security check' );
		}

		if (isset($_POST['option_id'])) {

			$option_id = isset( $_POST['option_id'] ) ? sanitize_text_field( $_POST['option_id'] ) : '';
			wp_delete_post($option_id);
		}

		die();
	}

	public function dependable_option() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'prod_optn_nonce' ) ) {
			die( 'Failed security check' );
		}

		if (isset($_POST['field_id']) && isset($_POST['field_value']) && isset($_POST['option_value'])) {

			$field_id           = sanitize_text_field( $_POST['field_id'] );
			$field_value        = sanitize_text_field( $_POST['field_value'] );
			$option_value_array = sanitize_text_field( $_POST['option_value'] );
			$option_value       = json_decode(stripslashes($option_value_array));
			$field_value        = json_decode(stripslashes($field_value));
			$field_array        = array();

			$options = Prd_General_Functions::get_posts_ids_of_current_post('product_add_option', $field_value);

			foreach ($options as $option_id ) {

				$field_array[$option_id ] = $option_id ;
				$addon_option_name        = get_post_meta($option_id, 'ck_option_name', true);
				?>
				<option value="<?php echo esc_attr( $option_id  ); ?>"
					<?php if (in_array($option_id, $option_value)) : ?>
						selected
					<?php endif ?>
					><?php echo esc_html( $addon_option_name ); ?>
				</option>
				<?php
			}
		}

		die('');
	}

	public function add_tab_in_prod( $tabs ) {

		$tabs['add_tab_in_prod'] = array(
			'label'     => __( 'Extra Options', 'prod_options' ), //Navigation Label Name
			'target'    => 'add_tab_in_prod', //The HTML ID of the tab content wrapper
			'class'    => array(),
			'priority' => 100,
		);
		 
		return $tabs;
	}

	public function product_options_data_panels() {
		?>
		<div id="add_tab_in_prod" class='panel woocommerce_options_panel' style="padding: 15px;">
			<?php
			$prd_id = get_the_ID();
			wp_nonce_field('prod_lvl_nonce', 'prod_lvl_nonce_field');
			Product_Options_Product_Fields::ck_product_level_fields( $prd_id );
			?>
		</div>
		<?php
	}

	public function prod_settings_save_options( $prd_id ) {

		// include PRO_OP_PLUGIN_DIR . 'includes/admin/metaboxes/product_level_settings/prod_lvl_fields_update.php';

		if ( 'auto-draft' != get_post_status( $prd_id ) && 'trash' != get_post_status( $prd_id ) ) {

			$nonce = isset($_POST['prod_lvl_nonce_field']) ? sanitize_text_field( $_POST['prod_lvl_nonce_field'] ) : 0;

			if ( !wp_verify_nonce($nonce, 'prod_lvl_nonce') ) {

				die('Failed Security Check');
			}

			$field_ids = Prd_General_Functions::get_posts_ids_of_current_post('Product_add_field', $prd_id);

			foreach ($field_ids as $field_id) {
				if (empty($field_id)) {
					continue;
				}

				Product_Options_Update_Value::ck_update_field_values( $field_id );

				$option_idss = Prd_General_Functions::get_posts_ids_of_current_post('product_add_option', $field_id);

				foreach ( $option_idss as $option_id ) {

					if (empty($option_id)) {
						continue;
					}

					Product_Options_Update_Value::ck_update_option_values( $field_id, $option_id );
				}

			}
		}
	}

	public function add_prod_optns_in_variation( $loop, $variation_data, $variation) {

		global $post;
		$prod_id = $variation->ID;
		Product_Options_Variation_Fields::ck_variation_level_fields( $prod_id );

	}

	public function save_optns_variation( $variation_id, $i ) {

		// include PRO_OP_PLUGIN_DIR . 'includes/admin/metaboxes/product_level_settings/prod_lvl_fields_update.php';

		$nonce = isset($_POST['prod_lvl_nonce_field']) ? sanitize_text_field( $_POST['prod_lvl_nonce_field'] ) : 0;

		if ( !wp_verify_nonce($nonce, 'prod_lvl_nonce') ) {

			die('Failed Security Check');
		}

		$field_ids = Prd_General_Functions::get_posts_ids_of_current_post('Product_add_field', $variation_id);

		foreach ($field_ids as $field_id) {
			if (empty($field_id)) {
				continue;
			}

			Product_Options_Update_Value::ck_update_field_values( $field_id );

			$option_idss = Prd_General_Functions::get_posts_ids_of_current_post('product_add_option', $field_id);

			foreach ( $option_idss as $option_id ) {

				if (empty($option_id)) {
					continue;
				}

				Product_Options_Update_Value::ck_update_option_values( $field_id, $option_id );
			}

		}
	}

	public function csv_upload() {

		if ( isset($_POST['current_rule_id']) && isset($_POST['csv_download_btn']) ) {

			$nonce = isset( $_POST['csv_form_nonce_field'] ) ? sanitize_text_field( $_POST['csv_form_nonce_field'] ) : 0;
			if ( ! wp_verify_nonce( $nonce, 'csv_form_nonce' ) ) {
				die( 'Failed security check' );
			}

			Csv_Upload_Download::export_csv( $_POST );
		}
	}

	public function show_option_name_in_order_detail( $item_id, $item, $order ) {

		foreach ($item->get_meta_data() as $item_data) {

			$item_data_array = $item_data->get_data();

			if (in_array('selected_field_data', $item_data_array) && is_array($item_data_array['value'])) {

				$get_added_data = $item_data_array['value'];

				foreach ($get_added_data as $option_field_id_array) {

					$selected_field_price = '';

					if ( in_array('field_id', array_keys($option_field_id_array) ) ) {
						$field_id_value = $option_field_id_array['field_id'];
						$field_type     = $option_field_id_array['field_type'];

						$ck_field_title = get_post_meta($field_id_value, 'ck_field_title', true);

						if ($ck_field_title) {
							?>
							<br>
							<b> <?php echo esc_attr($ck_field_title . ':'); ?> </b>
							<br>
							<?php
						}

						$field_type = $option_field_id_array['field_type'];

						if ( 'multi_select' == $field_type || 'check_boxes' == $field_type ) {

							$get_multi_select_val = (array) $option_field_id_array['value'];

							foreach ($get_multi_select_val as $option_id_value) {

								$option_name  = get_post_meta($option_id_value, 'ck_option_name', true);
								$option_price = (float) get_post_meta( $option_id_value , 'ck_option_price', true);
								$price_type   = get_post_meta($option_id_value, 'ck_option_price_type' , true);

								if ('free' == $price_type) {

									$selected_field_price = $option_name;
								} elseif ('flat_fixed_fee' == $price_type || 'fixed_fee_based_on_quantity' == $price_type ) {

									$selected_field_price = $option_name . ' (+' . get_woocommerce_currency_symbol() . ' ' . number_format($option_price, 2, '.', '') . ')';
								} elseif ('flat_percentage_fee' == $price_type || 'percentage_fee_based_on_quantity' == $price_type) {

									$selected_field_price = $option_name . ' (+' . number_format($option_price, 2, '.', '') . '%)';
								}

								echo esc_attr($selected_field_price);

								if (end($get_multi_select_val) != $option_id_value) {
									echo '<br>';
								}
							}

						} else {

							$field_id = $option_field_id_array['field_id'];

							if ('file_upload' == $option_field_id_array['field_type'] ) {

								$upload = wp_upload_dir();
								$url    = $upload['baseurl'] . '/product-options/uploaded-files/' . $option_field_id_array['value'];
								?>
								<a target="_blank" href="<?php echo esc_attr($url); ?>">
									<i class="fa fa-eye">
										<?php echo esc_attr($option_field_id_array['value']); ?>
									</i>
								</a>
								<?php
							}

							if ( 'drop_down' == $field_type || 'image' == $field_type || 'image_swatcher' == $field_type || 'color_swatcher' == $field_type || 'radio' == $field_type) {

								$option_value = $option_field_id_array['value'];
								$option_name  = get_post_meta($option_value, 'ck_option_name', true);
								$option_price = (float) get_post_meta( $option_value , 'ck_option_price', true);
								$price_type   = get_post_meta($option_value, 'ck_option_price_type' , true);

								if ('free' == $price_type) {

									$selected_field_price = $option_name;
								} elseif ('flat_fixed_fee' == $price_type || 'fixed_fee_based_on_quantity' == $price_type ) {

									$selected_field_price = $option_name . ' (+' . get_woocommerce_currency_symbol() . ' ' . number_format($option_price, 2, '.', '') . ')';
								} elseif ('flat_percentage_fee' == $price_type || 'percentage_fee_based_on_quantity' == $price_type) {

									$selected_field_price = $option_name . ' (+' . number_format($option_price, 2, '.', '') . '%)';
								}

								echo esc_attr($selected_field_price);

							} else {

								$option_price = (float) get_post_meta( $field_id , 'ck_field_price', true);
								$price_type   = get_post_meta($field_id, 'ck_field_pricing_type' , true);
								
								if ('flat_fixed_fee' == $price_type || 'fixed_fee_based_on_quantity' == $price_type ) {

									$selected_field_price = ' (+' . get_woocommerce_currency_symbol() . ' ' . number_format($option_price, 2, '.', '') . ')';
								} elseif ('flat_percentage_fee' == $price_type || 'percentage_fee_based_on_quantity' == $price_type) {

									$selected_field_price = ' (+' . number_format($option_price, 2, '.', '') . '%)';
								}
								if ('file_upload' != $option_field_id_array['field_type']) {
									?>
									<b><?php echo esc_attr($option_field_id_array['value'] . ' ' . $selected_field_price); ?></b>
									<?php
								} else {
									?>
									<b><?php echo esc_attr($selected_field_price); ?></b>
									<?php
								}

							}

							$selected_field_price = '';
						}
					}
				}
			}
		}
	}
}

new Prd_Options_Admin();
