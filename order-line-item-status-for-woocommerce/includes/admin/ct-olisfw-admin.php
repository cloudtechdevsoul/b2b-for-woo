<?php
if (! defined('ABSPATH') ) {
	die;
}

class Ct_Olisfw_Admin {

	public function __construct() {

		add_action( 'admin_menu' , [$this,'admin_menu'] );
		add_action( 'admin_enqueue_scripts',[$this,'af_cmfw_enqueue_scripts'] );
		add_action( 'wp_loaded' ,[$this,'ct_olisfw_wp_loaded'] );
		add_action( 'woocommerce_after_order_itemmeta', array( $this, 'ka_up_prd_files_with_line_item' ), 10, 3 );
		add_action( 'save_post_shop_order' , [$this,'save_post_shop_order'],PHP_INT_MAX);

	}

	public function admin_menu() {
		add_submenu_page(
			'b2bking', // define post type.
			'Order Line Item Status', // Page title.
			esc_html__( 'Order Line Item Status', 'cloud_tech_olisfw ' ), // Title.
			'manage_options', // Capability.
			'ct_olisfw', // slug.
			array(
				$this,
				'ct_olisfw_tab_callback',
			) // callback.
		);
	}

	public function ct_olisfw_tab_callback() {
		global $active_tab;
		if ( isset( $_GET['tab'] ) ) {
			$active_tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
		} else {
			$active_tab = 'status_setting';
		}
		?>
		<h2 class="nav-tab-wrapper">
			<?php settings_errors(); ?>
			<!-- General Setting Tab -->
			<a href="?page=ct_olisfw&tab=status_setting" class="nav-tab  <?php echo esc_attr( $active_tab ) === 'status_setting' ? ' nav-tab-active' : ''; ?>" ><?php esc_html_e( 'Status Settings', 'cloud_tech_olisfw' ); ?></a>

		</h2>
		<br class="clear">
		<div class="wrap">
			<form method="post">
				<?php

				if ( 'status_setting' === $active_tab ) {

					$this->order_line_item_statuses_setting();
					?>
					<div class= "div2">
						<input class="button button-primary " type="submit" name="ct_olisfw_save_data">
					</div>
					<?php

				}

				?>
			</form>
			<?php

		}
		public function af_cmfw_enqueue_scripts() {

			wp_enqueue_style( 'ct_olisfw_link_ty', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0', false );

			wp_enqueue_style( 'ct_olisfw_admin_side_stylee', CT_OLISFW_URL . '/assets/css/admin.css', false, '1.0', false );

			wp_enqueue_script( 'ct_olisfw_ajax_func_file', CT_OLISFW_URL . '/assets/js/admin.js', array( 'jquery' ), '1.0.1', false );
		}

		public function order_line_item_statuses_setting() {

			$get_data 	= ( array ) get_option('ct_enable_mail_on_line_status');

			?>
			<table class="form-table">
				<?php foreach ( wc_get_order_statuses()  as $key => $label ) { ?>
					<tr>
						<th><?php echo esc_html__($label .' Status','cloud_tech_olisfw'); ?></th>
						<td>
							<input type="checkbox" name="ct_enable_mail_on_line_status[enable][<?php echo esc_attr($key ); ?>]" class="ct_enable_mail_on_line_status" data-order_status_key="<?php echo esc_attr($key ); ?>"
							<?php if ( isset( $get_data['enable'] ) && isset( $get_data['enable'][ $key ] ) && !empty( $get_data['enable'][ $key ] ) ){ ?>
								checked
							<?php } ?>

							>
						</td>
						<tr class="ct-olisfw-<?php echo esc_attr($key ); ?>" >

							<th><?php echo esc_html__('Subject', 'cloud_tech_olisfw'); ?></th>
							<td>
								<input type="" name="ct_enable_mail_on_line_status[subject][<?php echo esc_attr($key ); ?>]" <?php if ( isset( $get_data['subject'] ) && isset( $get_data['subject'][ $key ] ) && !empty( $get_data['subject'][ $key ] ) ){ ?>
									value="<?php echo esc_attr( $get_data['subject'][ $key ] ); ?>"
								<?php } ?>
								>
							</td>
						</tr>
						<tr class="ct-olisfw-<?php echo esc_attr($key ); ?>" >
							<th><?php echo esc_html__('Additional Content', 'cloud_tech_olisfw'); ?></th>
							<td>
								<?php 

								$content   =  get_option( 'ct_additional_content' . $key ) ? get_option( 'ct_additional_content' . $key ) : ' Your product {product_name} with quantity {product_quantity} has current status {item_status}' ;

								$editor_id = 'ct_additional_content'.$key;

								$settings  = array(
									'wpautop'       => false,
									'tinymce'       => true,
									'media_buttons' => false,
									'textarea_rows' => 10,
									'quicktags'     => array( 'buttons' => 'em,strong,link' ),
									'quicktags'     => true,
								);

								wp_editor( $content, $editor_id, $settings );

								?>
							</td>
						</tr>

					<?php } ?>

				</table>
				<div>
					<p>
						<?php echo esc_html__( 'Use variable {product_name} to print product name,  use variable {product_quantity} to print quantity and use variable {item_status} for item status.' , 'cloud_tech_olisfw' ); ?>
					</p>
				</div>
				<?php
			}

			public function ct_olisfw_wp_loaded() {
				if ( isset($_POST['ct_olisfw_save_data']) ) {

					$selected_data =   isset( $_POST['ct_enable_mail_on_line_status'] ) ? sanitize_meta('',$_POST['ct_enable_mail_on_line_status'],'') : [];
					update_option('ct_enable_mail_on_line_status' , $selected_data );

				}
			}
			public function ka_up_prd_files_with_line_item( $item_id, $item, $product ) {
				if ( ! is_admin() || ! $product ) {
					return;
				}
				$ct_old_item_status = wc_get_order_item_meta($item_id, 'ct_order_item_status', true);
				$ct_old_item_date = wc_get_order_item_meta($item_id, 'ct_order_item_date', true);
				$ct_old_item_time = wc_get_order_item_meta($item_id, 'ct_order_item_time', true);

				?>
				<table class="woocommerce_order_items">
					<thead>

						<th><?php echo esc_html__('Item Status','cloud_tech_olisfw'); ?></th>
						<th><?php echo esc_html__('Date','cloud_tech_olisfw'); ?></th>
						<th><?php echo esc_html__('Time','cloud_tech_olisfw'); ?></th>
					</thead>
					<tbody>
						<tr>
							<td>
								<select name="ct_order_item_status[<?php echo esc_attr( $item_id ); ?>]">
									<?php foreach (wc_get_order_statuses()  as $key => $label): ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php if ( $key == $ct_old_item_status ) { ?>
											selected
											<?php } ?> >

											<?php echo esc_html__($label,'cloud_tech_olisfw'); ?>
										</option>
									<?php endforeach ?>
								</select>
							</td>
							<td>
								<input type="date" name="ct_order_item_date[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr($ct_old_item_date); ?>">
							</td>
							<td>
								<input type="time" name="ct_order_item_time[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr($ct_old_item_time); ?>">
							</td>

						</tr>
					</tbody>
				</table>

				<?php
			}

			public function save_post_shop_order($post_id) {
				$all_order_status = wc_get_order_statuses();
				$get_data = (array) get_option('ct_enable_mail_on_line_status');
				$order = wc_get_order($post_id);
				
				foreach ($order->get_items() as $item_id => $item) {
					$ct_old_item_status = wc_get_order_item_meta($item_id, 'ct_order_item_status', true);

					$ct_new_item_status = isset($_POST['ct_order_item_status'][$item_id]) ? sanitize_text_field($_POST['ct_order_item_status'][$item_id]) : '';

					wc_update_order_item_meta($item_id, 'ct_order_item_status', $ct_new_item_status);


					$ct_new_item_date = isset($_POST['ct_order_item_date'][$item_id]) ? sanitize_text_field($_POST['ct_order_item_date'][$item_id]) : '';

					wc_update_order_item_meta($item_id, 'ct_order_item_date', $ct_new_item_date);


					$ct_new_item_time = isset($_POST['ct_order_item_time'][$item_id]) ? sanitize_text_field($_POST['ct_order_item_time'][$item_id]) : '';

					wc_update_order_item_meta($item_id, 'ct_order_item_time', $ct_new_item_time);

					if ($ct_old_item_status != $ct_new_item_status) {
						if (isset($get_data['enable']) && isset($get_data['enable'][$ct_new_item_status]) && !empty($get_data['enable'][$ct_new_item_status])) {
							$subject = isset($get_data['subject']) && isset($get_data['subject'][$ct_new_item_status]) && !empty($get_data['subject'][$ct_new_item_status]) ? $get_data['subject'][$ct_new_item_status] : 'Item Status Change ' . $all_order_status[$ct_new_item_status];

							$message = get_option('ct_additional_content' . $ct_new_item_status) ? get_option('ct_additional_content' . $ct_new_item_status) : 'Your product {product_name} with quantity {product_quantity} has current status {item_status}';

							$message = str_replace('{product_name}', $item->get_name(), $message);
							$message = str_replace('{product_quantity}', $item->get_quantity(), $message);
							$message = str_replace('{item_status}', $all_order_status[$ct_new_item_status], $message);

                // wp_mail($order->get_billing_email(), $subject, $message);
						}
					}
				}

				$order->save();
    // wp_die();
			}
		}
		new Ct_Olisfw_Admin(); 