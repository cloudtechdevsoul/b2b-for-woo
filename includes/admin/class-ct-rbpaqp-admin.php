<?php
if (! defined('WPINC') ) {
	die;
}


class Ct_Rbpaqp_Admin {

	public function __construct() {
		
		add_action( 'admin_menu' , [$this,'ct_rbpaqp_admin_menu']);
		add_action( 'admin_init' , [$this,'ct_rbpaqp_admin_init']);
		// add_action( 'all_admin_notices', array($this, 'af_cog_tabs'), 5);

		include CT_RBPAQP_PLUGIN_DIR . 'includes/admin/post-rules/role-base-pricing/role-base-pricing-admin.php';
		include CT_RBPAQP_PLUGIN_DIR . 'includes/admin/post-rules/min-max-qty/min-max-qty-admin.php';
		include CT_RBPAQP_PLUGIN_DIR . 'includes/admin/post-rules/hide-price-and-add-to-cart-button/hide-price-and-add-to-cart-button-admin.php';
		include CT_RBPAQP_PLUGIN_DIR . 'includes/admin/post-rules/hide-product-and-variation/hide-product-and-variation.php';
		include CT_RBPAQP_PLUGIN_DIR . 'includes/admin/post-rules/ct-rbpaqp-save-post-data.php';
		include CT_RBPAQP_PLUGIN_DIR . 'includes/admin/post-rules/product-level-setting.php';
	}
	public function ct_rbpaqp_admin_menu() {
		add_submenu_page(
			'b2bking', // define post type.
			'B2B Setting', // Page title.
			esc_html__( 'B2B Setting', 'cloud_tech_rbpaqpfw ' ), // Title.
			'manage_options', // Capability.
			'ct_rbpaqp', // slug.
			array(
				$this,
				'ct_rbpaqp_tab_callback',
			), // callback
			0, //postions
		);
		global $pagenow, $typenow;

	}

	public function ct_rbpaqp_admin_init() {

		include CT_RBPAQP_PLUGIN_DIR . 'includes/admin/settings/general-setting.php';

		$global_setting_section 	= ['min_max_quantity_by_role','hide_price_and_add_to_cart_button'];
		foreach ($global_setting_section as  $value) {

			include CT_RBPAQP_PLUGIN_DIR . 'includes/admin/settings/error-messages/'. str_replace('_','-',$value) .'.php';
		}

	}

	public function ct_rbpaqp_tab_callback() {
		global $active_tab;

		$nonce 			= isset( $_GET['cloud_tech_rbpaqpfw'] ) ? sanitize_text_field( $_GET['cloud_tech_rbpaqpfw'] ) : '';

		if ( isset( $_GET['tab'] ) ) {
			$active_tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
		} else {
			$active_tab = 'general_settings';
		}
		
			$tabs =  array(
				'general_settings' => array(
					'title' => __(' General Settings', 'addify_cog'),
					'url' => admin_url('admin.php?page=ct_rbpaqp&tab=general_settings'),
				),
				'error_message' => array(
					'title' => __(' Error Messages', 'addify_cog'),
					'url' => admin_url('admin.php?page=ct_rbpaqp&tab=error_message'),
				),
				'role_base_pricing_global_setting' => array(
					'title' => __('Global Settings', 'addify_cog'),
					'url' => admin_url('admin.php?page=ct_rbpaqp_global_setting&tab=role_base_pricing'),
				),
			);

			unset( $tabs['role_base_pricing_global_setting'] );


		if (is_array($tabs)) { 
			?>
			<div class="wrap woocommerce">
				<h2>
					<?php echo esc_html__('B2B  Setting', 'cloud_tech_rbpaqpfw');?>
				</h2>
				<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
					<?php
					
					$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) :'general_settings';

					foreach ($tabs as $id => $tab_data) {


						$class = $id === $current_tab ? array('nav-tab', 'nav-tab-active') : array('nav-tab');

						printf('<a href="%1$s" class="%2$s">%3$s</a>', esc_url($tab_data['url']), implode(' ', array_map('sanitize_html_class', $class)), esc_html($tab_data['title'] ));
					}
					?>
				</h2>
			</div>
			<?php
		}

		if ( 'error_message' == $active_tab ) {

			$global_setting_section 	= ['min_max_quantity_by_role'];
			$active_section 			= isset( $_GET['section'] ) ? sanitize_text_field( $_GET['section'] ) : 'min_max_quantity_by_role'; 
			?>
			<ul class="subsubsub">
				<?php foreach ( $global_setting_section as $section_url ) { 
					$url = admin_url( 'admin.php?page=ct_rbpaqp&tab=' . $active_tab . '&section=' . $section_url );
					?>
					<li>
						<a href="<?php echo esc_url( $url); ?>" class="  <?php echo esc_attr( $active_section ) === $section_url ? 'current' : ''; ?>" >
							<?php 
							echo esc_attr( ucwords( str_replace( '_',' ',$section_url ) ) ); 
							echo esc_attr( end($global_setting_section) == $section_url ? '' : ' |' );
							?>
						</a>
					</li>
					<?php
				} ?>
			</ul>
			<?php
		}

		?>


		<form method="post" action="options.php">
			<?php settings_errors();

			wp_nonce_field('cloud-tech-rbpaqpfw-nonce', 'cloud-tech-rbpaqpfw-nonce');

			if ( 'general_settings' === $active_tab ) {
				settings_fields( 'ct_rbpaqp_general_settings' );
				do_settings_sections( 'ct_rbpaqp_general_settings_page' );
			}

			if ( 'error_message' === $active_tab ) {

				$active_section 	= isset( $_GET['section'] ) ? sanitize_text_field( $_GET['section'] ) : 'min_max_quantity_by_role';

				settings_fields( 'ct_rbpaqp_'.$active_section );
				do_settings_sections( 'ct_rbpaqp_'.$active_section.'_page' );

			}


			if ( str_contains( 'general_settings error_message' , $active_tab ) ) {
				echo '<div class= "div2">';
				submit_button( esc_html__( 'Save Settings', 'cloud_tech_rbpaqpfw' ), 'primary' );
				echo '</div>';
			}
			?>
		</form>

		<?php

	}

}

new Ct_Rbpaqp_Admin();