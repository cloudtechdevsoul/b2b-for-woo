<?php
if (!defined('WPINC')) {
	die;
}


class Ct_Rfq_Admin
{

	public function __construct()
	{

		add_filter('bulk_actions-edit-ct-rfq-submit-quote', [$this, 'ct_rfq_bulk_actions_edit_ct_rfq_submit_quote']);
		add_filter('handle_bulk_actions-edit-ct-rfq-submit-quote', [$this, 'ct_rfq_rudr_bulk_action_handler'], 10, 3);
		add_filter('post_row_actions', [$this, 'custom_post_row_actions'], 10, 2);
		add_action('admin_menu', [$this, 'ct_tepfw_admin_menu']);
		add_action('admin_init', [$this, 'ct_tepfw_admin_init']);
		add_action('all_admin_notices', array($this, 'ct_tepfw_cog_tabs'), 5);
		add_action('wp_loaded', [$this, 'ct_tepfw_wp_loaded']);
	}

	// process the action
	public function ct_rfq_rudr_bulk_action_handler($redirect, $doaction, $object_ids)
	{

		if ('ct_rfq_generate_pdf' == $doaction) {

			ct_rfq_create_pdf($object_ids, true);

		}
		if ('ct_rfq_generate_csv' == $doaction) {
			ct_rfq_create_csv($object_ids);

		}
		return $redirect;

	}

	public function ct_rfq_bulk_actions_edit_ct_rfq_submit_quote($bulk_actions)
	{
		$bulk_actions['ct_rfq_generate_pdf'] = __('Generate PDF', 'cloud_tech_rfq');
		$bulk_actions['ct_rfq_generate_csv'] = __('Generate CSV', 'cloud_tech_rfq');

		return $bulk_actions;
	}

	// Add custom row action
	public function custom_post_row_actions($actions, $post)
	{
		if ($post->post_type === 'ct-rfq-submit-quote') {
			$actions['ct_rfq_generate_pdf'] = '<a href="?download_pdf=' . esc_attr($post->ID) . '" data-post-id="' . esc_attr($post->ID) . '" class="generate-pdf">Generate PDF</a>';
			$actions['ct_rfq_generate_csv'] = '<a href="?download_csv=' . esc_attr($post->ID) . '" data-post-id="' . esc_attr($post->ID) . '" class="generate-pdf">Generate CSV</a>';

		}
		return $actions;
	}
	public function ct_tepfw_admin_menu()
	{
		add_submenu_page(
			'b2bking', // define post type.
			'Request A Quote Pro', // Page title.
			esc_html__('Request A Quote Pro', 'cloud_tech_rfq '), // Title.
			'manage_options', // Capability.
			'ct_submenu_rfq', // slug.
			array(
				$this,
				'ct_tepfw_tab_callback',
			) // callback
		);
		global $pagenow, $typenow;


		if (
			('edit.php' === $pagenow && 'ct-rfq-quote-rule' === $typenow)
			|| ('post.php' === $pagenow && isset($_GET['post']) && 'ct-rfq-quote-rule' === get_post_type(sanitize_text_field($_GET['post'])))
		) {
			remove_submenu_page('b2bking', 'ct_submenu_rfq');
			remove_submenu_page('b2bking', 'edit.php?post_type=ct-rfq-submit-quote');
			remove_submenu_page('b2bking', 'edit.php?post_type=ct-rfq-quote-fields');

		} elseif (
			('edit.php' === $pagenow && 'ct-rfq-submit-quote' === $typenow)
			|| ('post.php' === $pagenow && isset($_GET['post']) && 'ct-rfq-submit-quote' === get_post_type(sanitize_text_field($_GET['post'])))
		) {

			remove_submenu_page('b2bking', 'ct_submenu_rfq');
			remove_submenu_page('b2bking', 'edit.php?post_type=ct-rfq-quote-fields');
			remove_submenu_page('b2bking', 'edit.php?post_type=ct-rfq-quote-rule');

		} elseif (
			('edit.php' === $pagenow && 'ct-rfq-quote-fields' === $typenow)
			|| ('post.php' === $pagenow && isset($_GET['post']) && 'ct-rfq-quote-fields' === get_post_type(sanitize_text_field($_GET['post'])))
		) {


			remove_submenu_page('b2bking', 'ct_submenu_rfq');
			remove_submenu_page('b2bking', 'edit.php?post_type=ct-rfq-submit-quote');
			remove_submenu_page('b2bking', 'edit.php?post_type=ct-rfq-quote-rule');

		} elseif (('admin.php' === $pagenow && isset($_GET['page']) && 'ct_submenu_rfq' === sanitize_text_field($_GET['page']))) {


			remove_submenu_page('b2bking', 'edit.php?post_type=ct-rfq-submit-quote');
			remove_submenu_page('b2bking', 'edit.php?post_type=ct-rfq-quote-fields');
			remove_submenu_page('b2bking', 'edit.php?post_type=ct-rfq-quote-rule');

		} else {

			remove_submenu_page('b2bking', 'edit.php?post_type=ct-rfq-submit-quote');
			remove_submenu_page('b2bking', 'edit.php?post_type=ct-rfq-quote-fields');
			remove_submenu_page('b2bking', 'edit.php?post_type=ct-rfq-quote-rule');

		}
	}

	public function ct_tepfw_admin_init()
	{

		include CT_RFQ_PLUGIN_DIR . 'includes/admin/setting/general-setting.php';
		include CT_RFQ_PLUGIN_DIR . 'includes/admin/setting/quote-table-setting.php';
		include CT_RFQ_PLUGIN_DIR . 'includes/admin/setting/attribute.php';
		include CT_RFQ_PLUGIN_DIR . 'includes/admin/setting/logos.php';
		include CT_RFQ_PLUGIN_DIR . 'includes/admin/setting/pdf-csv-setting.php';


	}

	public function ct_tepfw_tab_callback()
	{
		global $active_tab;

		$nonce = isset($_GET['cloud_tech_rfq']) ? sanitize_text_field($_GET['cloud_tech_rfq']) : '';

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

				settings_fields('ct_rfq_general_setting_fields');
				do_settings_sections('ct_rfq_general_setting_section');
			}
			if ('quote_table' === $active_tab) {

				settings_fields('ct_rfq_quote_table_setting_fields');
				do_settings_sections('ct_rfq_quote_table_setting_section');
			}
			if ('attribute' === $active_tab) {

				settings_fields('ct_rfq_attribute_fields');
				do_settings_sections('ct_rfq_attribute_section');
			}

			if ('Logos' === $active_tab) {

				settings_fields('ct_rfq_logos_fields');
				do_settings_sections('ct_rfq_logos_section');
			}
			if ('pdf_and_csv' === $active_tab) {

				settings_fields('ct_rfq_pdf_and_csv_fields');
				do_settings_sections('ct_rfq_pdf_and_csv_section');
			}

			if ('shortcodes' === $active_tab) {
				ct_rfq_shortcodes();
			}

			if (str_contains('general_settings pdf_and_csv Logos attribute quote_table', $active_tab)) {
				echo '<div class= "div2">';
				submit_button(esc_html__('Save Settings', 'cloud_tech_rfq'), 'primary');
				echo '</div>';
			}
			?>
		</form>

		<?php

		if ('emai_setting' === $active_tab) {
			include CT_RFQ_PLUGIN_DIR . 'includes/admin/setting/email-setting.php';
		}

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
					'url' => admin_url('admin.php?page=ct_submenu_rfq&tab=general_settings'),
				),
				'quote_table' => array(
					'title' => __(' Quote table Settings', 'addify_cog'),
					'url' => admin_url('admin.php?page=ct_submenu_rfq&tab=quote_table'),
				),
				'attribute' => array(
					'title' => __(' Attribute ', 'addify_cog'),
					'url' => admin_url('admin.php?page=ct_submenu_rfq&tab=attribute'),
				),
				'Logos' => array(
					'title' => __(' Logos ', 'addify_cog'),
					'url' => admin_url('admin.php?page=ct_submenu_rfq&tab=Logos'),
				),
				'pdf_and_csv' => array(
					'title' => __(' PDF/CSV ', 'addify_cog'),
					'url' => admin_url('admin.php?page=ct_submenu_rfq&tab=pdf_and_csv'),
				),
				'emai_setting' => array(
					'title' => __(' Email Setting ', 'addify_cog'),
					'url' => admin_url('admin.php?page=ct_submenu_rfq&tab=emai_setting'),
				),
				'shortcodes' => array(
					'title' => __(' All Short Codes ', 'addify_cog'),
					'url' => admin_url('admin.php?page=ct_submenu_rfq&tab=shortcodes'),
				),
				'ct_rfq_submit_quote' => array(
					'title' => __('Submitted Quote', 'addify_cog'),
					'url' => admin_url('edit.php?post_type=ct-rfq-submit-quote'),
				),
				'ct_rfq_quote_rule' => array(
					'title' => __('Quote Rules', 'addify_cog'),
					'url' => admin_url('edit.php?post_type=ct-rfq-quote-rule'),
				),
				'ct_rfq_quote_fields' => array(
					'title' => __('Quote Fields', 'addify_cog'),
					'url' => admin_url('edit.php?post_type=ct-rfq-quote-fields'),
				),
			);
			// unset( $tabs['ct_rfq_quote_fields'] );

			if (is_array($tabs)) {
				?>
				<div class="wrap woocommerce">
					<h2>
						<?php echo esc_html__('Request A Quote Pro Setting', 'cloud_tech_rfq'); ?>
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

			case 'b2b-king_page_ct_submenu_rfq':
			case 'general_settings':
				return 'general_settings';

			case 'ct-rfq-quote-rule':
			case 'edit-ct-rfq-quote-rule':
				return 'ct_rfq_quote_rule';


			case 'ct-rfq-quote-fields':
			case 'edit-ct-rfq-quote-fields':
				return 'ct_rfq_quote_fields';


			case 'ct-rfq-submit-quote':
			case 'edit-ct-rfq-submit-quote':
				return 'ct_rfq_submit_quote';

		}
	}

	public function get_tab_screen_ids()
	{
		$tabs_screens = array(
			'b2b-king_page_ct_submenu_rfq',
			'edit-ct-rfq-quote-rule',
			'ct-rfq-quote-rule',

			'edit-ct-rfq-quote-fields',
			'ct-rfq-quote-fields',

			'edit-ct-rfq-submit-quote',
			'ct-rfq-submit-quote',
		);

		return $tabs_screens;
	}
	public function ct_tepfw_wp_loaded()
	{

		$nonce = isset($_POST['cloud_tech_rfq_nonce']) ? sanitize_text_field($_POST['cloud_tech_rfq_nonce']) : '';


		if (isset($_POST['ct_tepfw_update_email']) && !wp_verify_nonce($nonce, 'cloud_tech_rfq_nonce')) {
			wp_die(esc_html__('Security Violated !', 'cloud_tech_rfq'));
		}

		if (isset($_POST['ct_tepfw_update_email'])) {

			$accepted_request_email_Setting = isset($_POST['ct_tepfw_accepted_request_email']) ? sanitize_meta('', $_POST['ct_tepfw_accepted_request_email'], '') : [];
			$rejected_request_email_Setting = isset($_POST['ct_tepfw_rejected_request_email']) ? sanitize_meta('', $_POST['ct_tepfw_rejected_request_email'], '') : [];

			update_option('ct_tepfw_accepted_request_email', $accepted_request_email_Setting);
			update_option('ct_tepfw_rejected_request_email', $rejected_request_email_Setting);


			$accepted_request_email_content = isset($_POST['ct_tepfw_accepted_request_email_content']) ? sanitize_text_field($_POST['ct_tepfw_accepted_request_email_content']) : '';
			$rejected_request_email_content = isset($_POST['ct_tepfw_rejected_request_email']) ? sanitize_text_field($_POST['ct_tepfw_rejected_request_email']) : '';

			update_option('ct_tepfw_accepted_request_email_content', $accepted_request_email_content);
			update_option('ct_tepfw_rejected_request_email_content', $rejected_request_email_content);

		}
		if (isset($_POST['save_email_setting'])) {
			foreach (wc_get_order_statuses() as $order_status_key => $order_status_value) {

				$enable_setting = isset($_POST['ct_rfq_enable_setting_for_' . $order_status_key]) ? ($_POST['ct_rfq_enable_setting_for_' . $order_status_key]) : '';
				$subject = isset($_POST['ct_rfq_email_subject_for_' . $order_status_key]) ? ($_POST['ct_rfq_email_subject_for_' . $order_status_key]) : '';
				$additional_content = isset($_POST['ct_rfq_email_additional_content_for_' . $order_status_key]) ? ($_POST['ct_rfq_email_additional_content_for_' . $order_status_key]) : '';

				update_option('ct_rfq_enable_setting_for_' . $order_status_key, $enable_setting);
				update_option('ct_rfq_email_subject_for_' . $order_status_key, $subject);
				update_option('ct_rfq_email_additional_content_for_' . $order_status_key, $additional_content);

			}
		}
		if (isset($_GET['download_pdf']) && !empty($_GET['download_pdf'])) {

			$download_pdf_id = sanitize_text_field($_GET['download_pdf']);
			ct_rfq_create_pdf([$download_pdf_id], );
		}
		if (isset($_GET['download_csv']) && !empty($_GET['download_csv'])) {
			$download_csv = sanitize_text_field($_GET['download_csv']);
			ct_rfq_create_csv([$download_csv]);

		}
	}
}

new Ct_Rfq_Admin();
