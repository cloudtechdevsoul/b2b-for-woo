<?php
if (!defined('WPINC')) {
	die;
}


class Ct_Tepfw_Admin
{

	public function __construct()
	{

		add_action('admin_menu', [$this, 'ct_tepfw_admin_menu']);
		add_action('admin_init', [$this, 'ct_tepfw_admin_init']);
		add_action('all_admin_notices', array($this, 'ct_tepfw_cog_tabs'), 5);
		add_action('wp_loaded', [$this, 'ct_tepfw_wp_loaded']);
	}
	public function ct_tepfw_admin_menu()
	{
		add_submenu_page(
			'b2bking', // define post type.
			'Role Base Tax Exempt Pro', // Page title.
			esc_html__('Role Base Tax Exempt Pro', 'cloud_tech_tepfw '), // Title.
			'manage_options', // Capability.
			'ct_submenu_texpfw', // slug.
			array(
				$this,
				'ct_tepfw_tab_callback',
			) // callback
		);
		global $pagenow, $typenow;


		if (
			('edit.php' === $pagenow && 'ct_tax_exempt_pro' === $typenow)
			|| ('post.php' === $pagenow && isset($_GET['post']) && 'ct_tax_exempt_pro' === get_post_type(sanitize_text_field($_GET['post'])))
		) {
			remove_submenu_page('b2bking', 'ct_submenu_texpfw');

		} elseif (('admin.php' === $pagenow && isset($_GET['page']) && 'ct_submenu_texpfw' === sanitize_text_field($_GET['page']))) {

			remove_submenu_page('b2bking', 'edit.php?post_type=ct_tax_exempt_pro');

		} else {

			remove_submenu_page('b2bking', 'edit.php?post_type=ct_tax_exempt_pro');

		}
	}

	public function ct_tepfw_admin_init()
	{

		include CT_TEPFW_PLUGIN_DIR . 'includes/admin/setting/general-setting.php';
		include CT_TEPFW_PLUGIN_DIR . 'includes/admin/setting/additional-message.php';

	}

	public function ct_tepfw_tab_callback()
	{
		global $active_tab;

		$nonce = isset($_GET['cloud_tech_tepfw']) ? sanitize_text_field($_GET['cloud_tech_tepfw']) : '';

		if (isset($_GET['tab'])) {
			$active_tab = sanitize_text_field(wp_unslash($_GET['tab']));
		} else {
			$active_tab = 'general_settings';
		}

		?>


		<form method="post" action="options.php">
			<?php settings_errors();

			wp_nonce_field('cloud-tech-tepfw-nonce', 'cloud-tech-tepfw-nonce');

			if ('general_settings' === $active_tab) {

				settings_fields('ct_tepfw_general_setting_fields');
				do_settings_sections('ct_tepfw_general_setting_section');
			}
			if ('additional_message' === $active_tab) {

				settings_fields('ct_tepfw_additional_message_fields');
				do_settings_sections('ct_tepfw_additional_message_section');
			}



			if (str_contains('general_settings additional_message', $active_tab)) {
				echo '<div class= "div2">';
				submit_button(esc_html__('Save Settings', 'cloud_tech_tepfw'), 'primary');
				echo '</div>';
			}
			?>
		</form>

		<form method="post">
			<?php settings_errors();

			wp_nonce_field('cloud_tech_tepfw_nonce', 'cloud_tech_tepfw_nonce');

			if ('email' === $active_tab) {
				include CT_TEPFW_PLUGIN_DIR . 'includes/admin/setting/email.php';

				?>
				<input type="submit" class="button primary-button" name="ct_tepfw_update_email" value="Save Changes">
				<?php
			}
			?>
		</form>

		<?php

	}

	public function ct_tepfw_cog_tabs()
	{

		global $post, $typenow;
		$screen = get_current_screen();
		// handle tabs on the relevant WooCommerce pages
		if ($screen && in_array($screen->id, $this->get_tab_screen_ids(), true)) {

			$tabs = array(
				'general_settings' => array(
					'title' => __(' General Settings', 'addify_cog'),
					'url' => admin_url('admin.php?page=ct_submenu_texpfw&tab=general_settings'),
				),
				'additional_message' => array(
					'title' => __(' Additional Message', 'addify_cog'),
					'url' => admin_url('admin.php?page=ct_submenu_texpfw&tab=additional_message'),
				),
				'email' => array(
					'title' => __(' Email Settings', 'addify_cog'),
					'url' => admin_url('admin.php?page=ct_submenu_texpfw&tab=email'),
				),
				'ct_tax_exempt_pro' => array(
					'title' => __('Rules', 'addify_cog'),
					'url' => admin_url('edit.php?post_type=ct_tax_exempt_pro'),
				),
			);

			unset($tabs['role_base_pricing_global_setting']);


			if (is_array($tabs)) {
				?>
				<div class="wrap woocommerce">
					<h2>
						<?php echo esc_html__('Role Base Tax Exempt Pro Setting', 'cloud_tech_tepfw'); ?>
					</h2>
					<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
						<?php
						$current_tab = $this->get_current_tab();
						if ('general_settings' == $current_tab) {

							$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general_settings';

						}

						foreach ($tabs as $id => $tab_data) {


							$class = $id === $current_tab ? array('nav-tab', 'nav-tab-active') : array('nav-tab');

							printf('<a href="%1$s" class="%2$s">%3$s</a>', esc_url($tab_data['url']), implode(' ', array_map('sanitize_html_class', $class)), esc_html($tab_data['title']));
						}
						?>
					</h2>
				</div>
				<?php
			}
		}
	}

	public function get_current_tab()
	{

		$screen = get_current_screen();

		$active_tab = $screen->id;

		switch ($active_tab) {

			case 'b2b-king_page_ct_submenu_texpfw':
			case 'general_settings':
				return 'general_settings';
			case 'ct_tax_exempt_pro':
			case 'edit-ct_tax_exempt_pro':
				return 'ct_tax_exempt_pro';

		}
	}

	public function get_tab_screen_ids()
	{
		$tabs_screens = array(
			'b2b-king_page_ct_submenu_texpfw',
			'edit-ct_tax_exempt_pro',
			'ct_tax_exempt_pro',
		);

		return $tabs_screens;
	}
	public function ct_tepfw_wp_loaded()
	{

		$nonce = isset($_POST['cloud_tech_tepfw_nonce']) ? sanitize_text_field($_POST['cloud_tech_tepfw_nonce']) : '';


		if (isset($_POST['ct_tepfw_update_email']) && !wp_verify_nonce($nonce, 'cloud_tech_tepfw_nonce')) {
			wp_die(esc_html__('Security Violated !', 'cloud_tech_tepfw'));
		}

		if (isset($_POST['ct_tepfw_update_email'])) {

			$accepted_request_email_Setting = isset($_POST['ct_tepfw_accepted_request_email']) ? sanitize_meta('', $_POST['ct_tepfw_accepted_request_email'], '') : [];
			$rejected_request_email_Setting = isset($_POST['ct_tepfw_rejected_request_email']) ? sanitize_meta('', $_POST['ct_tepfw_rejected_request_email'], '') : [];
			$pending_request_email_Setting = isset($_POST['ct_tepfw_pending_request_email']) ? sanitize_meta('', $_POST['ct_tepfw_rejected_request_email'], '') : [];

			update_option('ct_tepfw_accepted_request_email', $accepted_request_email_Setting);
			update_option('ct_tepfw_rejected_request_email', $rejected_request_email_Setting);
			update_option('ct_tepfw_pending_request_email', $pending_request_email_Setting);


			$accepted_request_email_content = isset($_POST['ct_tepfw_accepted_request_email_content']) ? sanitize_text_field($_POST['ct_tepfw_accepted_request_email_content']) : '';
			$rejected_request_email_content = isset($_POST['ct_tepfw_rejected_request_email_content']) ? sanitize_text_field($_POST['ct_tepfw_rejected_request_email_content']) : '';
			$pending_request_email_content = isset($_POST['ct_tepfw_pending_request_email_content']) ? sanitize_text_field($_POST['ct_tepfw_pending_request_email_content']) : '';


			update_option('ct_tepfw_accepted_request_email_content', $accepted_request_email_content);
			update_option('ct_tepfw_rejected_request_email_content', $rejected_request_email_content);
			update_option('ct_tepfw_pending_request_email_content', $pending_request_email_content);


		}
	}
}

new Ct_Tepfw_Admin();