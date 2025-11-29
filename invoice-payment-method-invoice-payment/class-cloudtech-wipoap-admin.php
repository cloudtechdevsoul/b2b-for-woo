<?php
if (!defined('ABSPATH')) {
	die;
}
class Cloudtech_Wipoap_Admin
{

	public function __construct()
	{
		add_action('add_meta_boxes', array($this, 'cloudtech_wipoap_payment_gateway_meta_boxes'));
		add_action('admin_enqueue_scripts', array($this, 'cloudtech_wipoap_admin_assests'), 10);
		add_action('save_post_payment_gateway', array($this, 'cloudtech_wipoap_save_metadata'), 10, 2);
		add_action('wp_ajax_cloudtech_wipo_search_users', array($this, 'cloudtech_wipoap_search_users'));
		add_action('wp_ajax_cloudtech_wipo_search_products', array($this, 'cloudtech_wipoap_search_products'));
		add_action('add_meta_boxes', array($this, 'add_custom_invoice_metabox'));
		add_action('wp_ajax_generate_and_send_invoice', array($this, 'generate_and_send_invoice_callback'));
		add_action('wp_ajax_nopriv_generate_and_send_invoice', array($this, 'generate_and_send_invoice_callback'));
		add_action('admin_menu', array($this, 'cloudtech_wipoap_invoice_settings'));
		add_action('admin_init', array($this, 'ha_gpt_add_admin_settings_files'));
		add_action('all_admin_notices', array($this, 'af_a_nd_s_m_tabs'), 5);
		add_action('wp_ajax_devsoul_ipmaip_customer_search', array($this, 'devsoul_ipmaip_customer_search'));
		add_action('wp_ajax_devsoul_ipmaip_send_invoice', array($this, 'devsoul_ipmaip_send_invoice'));


	}
	public function ha_gpt_add_admin_settings_files()
	{
		// include_once CLOUDTECH_WIPOAP_PLUGIN_DIR . 'include/cloudtech-invoice-invoice-template.php';
		include_once CLOUDTECH_WIPOAP_PLUGIN_DIR . 'include/cloudtech-invoice-payment-settings.php';
	}
	public function cloudtech_wipoap_invoice_settings()
	{
		add_submenu_page(
			'b2bking', // define post type.
			'Invoice Payment Option And PDF', // Page title.
			esc_html__('Invoice Payment Option And PDF', 'addify_customer_manager '), // Title.
			'manage_options', // Capability.
			'invoice-set', // slug.
			array($this, 'cloudtech_wipoap__settings_page', )
		);
		global $post, $typenow, $pagenow;


		if (
			(('edit.php' === $pagenow || 'post-new.php' == $pagenow) && 'payment_gateway' === $typenow)

			|| ('post.php' === $pagenow && isset($_GET['post']) && 'payment_gateway' === get_post_type(sanitize_text_field($_GET['post'])))
		) {
			remove_submenu_page('b2bking', 'invoice-set');

		} elseif ((('admin.php' === $pagenow) && isset($_GET['page']) && 'invoice-set' === sanitize_text_field($_GET['page']))) {

			remove_submenu_page('b2bking', 'edit.php?post_type=payment_gateway');

		} else {

			remove_submenu_page('b2bking', 'edit.php?post_type=payment_gateway');
		}
	}
	public function af_a_nd_s_m_tabs()
	{

		global $post, $typenow, $pagenow;

		$screen = get_current_screen();

		// handle tabs on the relevant WooCommerce pages

		if ($screen && in_array($screen->id, $this->get_tab_screen_ids(), true)) {

			$tabs = array(
				'cloudtech_invoice_setting' => array(
					'title' => __(' PDF Template', 'addify_avsm'),
					'url' => admin_url('admin.php?page=invoice-set&tab=cloudtech_invoice_setting'),
				),
				'order_table_filter' => array(
					'title' => __(' Order Table Filter', 'addify_avsm'),
					'url' => admin_url('admin.php?page=invoice-set&tab=order_table_filter'),
				),
				'rules' => array(
					'title' => __('Rules', 'addify_avsm'),
					'url' => admin_url('edit.php?post_type=payment_gateway'),
				),
			);
			if (isset($_GET['tab'])) {
				$active_tab = sanitize_text_field(wp_unslash($_GET['tab']));
			} else {
				$active_tab = 'cloudtech_invoice_setting';
			}

			global $typenow, $pagenow;
			if (is_array($tabs)) {
				?>
				<div class="wrap woocommerce">
					<h2>
						<?php echo esc_html__('Invoice Payments', 'ad_wc_free_gifts'); ?>
					</h2>
					<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
						<?php

						$current_tab = $this->get_current_tab();
						if ('cloudtech_invoice_setting' == $current_tab) {
							$current_tab = $active_tab;
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

	} //end af_a_nd_s_m_tabs()


	public function get_current_tab()
	{

		$screen = get_current_screen();
		$active_tab = $screen->id;

		switch ($active_tab) {
			case 'b2b-king_page_invoice-set':
				return 'cloudtech_invoice_setting';
			case 'payment_gateway':
			case 'edit-payment_gateway':
				return 'rules';
		}
	} //end get_current_tab()


	public function get_tab_screen_ids()
	{
		$tabs_screens = array(
			'b2b-king_page_invoice-set',
			'edit-payment_gateway',
			'payment_gateway',
		);

		return $tabs_screens;
	} //end get_tab_screen_ids()



	public function cloudtech_wipoap__settings_page()
	{
		if (isset($_GET['tab'])) {
			$active_tab = sanitize_text_field(wp_unslash($_GET['tab']));
		} else {
			$active_tab = 'cloudtech_invoice_setting';
		}


		?>

		<form method="post" action="options.php" class="afacr_options_form">
			<?php

			if ('cloudtech_invoice_setting' === $active_tab) {
				settings_fields('cloudtech_invoice_admin_file_setting_field');
				do_settings_sections('cloudtech_invoice_admin_file_setting_section');
				submit_button();
			}
			if ('cloudtech_invoice_template_setting' === $active_tab) {
				settings_fields('cloudtech_invoice_template_file_setting_field');
				do_settings_sections('cloudtech_invoice_template_file_setting_section');
				submit_button();
			}
			?>
		</form>
		<?php

		if ('order_table_filter' === $active_tab) {
			include CLOUDTECH_WIPOAP_PLUGIN_DIR . 'include/view/order-table-filters.php';
		}
	}
	public function generate_invoice_pdf($editorContent)
	{
		$editorContent = $_POST['editorContent'];
		$billing_name = $_POST['billing_name'];
		$email = $_POST['email'];
		$company_code = $_POST['company_code'];
		$vat_code = $_POST['vat_code'];
		$inv_date = $_POST['inv_date'];
		$order_number = $_POST['order_number'];
		$order_date = $_POST['order_date'];
		$payement_method = $_POST['payement_method'];
		$trip_title = $_POST['trip_title'];
		$people_qty = $_POST['people_qty'];
		$trip_total = $_POST['trip_total'];
		$order = wc_get_order($order_number);
		if (!$order) {
			return false;
		}
		$product_html = "";
		$items = $order->get_items();
		foreach ($items as $item_id => $item) {
			$product_name = $item->get_name();
			$quantity = $item->get_quantity();
			$price = $item->get_total();
			$product_html .= '<tr style="border-bottom:1px solid gray;">
			<td>' . $product_name . '</td>
			<td>' . $quantity . '</td>
			<td>' . wc_price($price) . '</td>
			</tr>';
		}


		$user_address = $_POST['user_address'];

		$comapny_name = $_POST['comapny_name'];

		$company_code = $_POST['company_code'];
		if (!empty($company_code)) {
			$comapny_code_text = '<p>Company Code: &nbsp;&nbsp;' . $company_code . '</p>';
		} else {
			$comapny_code_text = '';
		}
		$company_vat = $_POST['company_vat'];
		if (!empty($company_vat)) {
			$company_vat_text = '<p>Company VAT:&nbsp;&nbsp;' . $company_vat . '</p>';
		} else {
			$company_vat_text = '';
		}
		$inv_number = $_POST['inv_number'];
		require_once(CLOUDTECH_WIPOAP_PLUGIN_DIR . '/dompdf/vendor/autoload.php');

		$options = new Dompdf\Options();
		$options->set('isHtml5ParserEnabled', true);
		$options->set('isPhpEnabled', true);

		$dompdf = new Dompdf\Dompdf($options);
		$path = get_option('cloudtect_invoice_logo_url');
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		$discount_amount = $trip_total - ($trip_total / 1.21);
		$new_total = $trip_total - $discount_amount;
		$invoiceHtml = '
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<style>
		body { hyphens: auto; font-family: Arial, Helvetica, sans-serif ;color: black;
			font-family: Arial, sans-serif;
			font-family: DejaVu Sans; sans-serif;
			font-style: normal;
			font-weight: normal;
			text-decoration: none;
			font-size: 9pt;
			margin: 0pt; }
			.invoice-header { text-align: center; background-color: #333; color: #fff; padding: 10px; }
			.invoice-details { margin: 20px; }
			.invoice-table { width: 100%; border-collapse: collapse; }
			.invoice-table th{ background-color:black;color:white;text-align:left;}
			.invoice-table th, .invoice-table td { padding: 10px; }
			</style>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			</head>
			<body>
			<div class="invoice-details">
			<table style="width:100%">
			<tr>
			<td style="vertical-align: top;">
			<img src=' . $base64 . ' width="200" height="50"/>
			</td>
			<td style="text-align:right;vertical-align: top;">
			<p>Company Name</p>
			<p><strong>Code</strong> ' . $company_code . '</p>
			<p><strong>VAT NO</strong> ' . $vat_code . '</p>
			</td>
			</tr>
			</table>
			<h2>Billing Details</h2>
			<table style="width:100%">
			<tr>
			<td style="vertical-align: top;">
			<p>' . $billing_name . '</p>
			<p>' . $email . '</p>
			<p>' . $user_address . '</p>
			<p>' . $comapny_name . '</p>
			' . $comapny_code_text . '
			' . $company_vat_text . '
			</td>
			<td style="text-align:right;vertical-align: top;">
			<table style="width:100%;text-align:right;">
			<tr>
			<td>INVOICE NO</td>
			<td>' . $inv_number . '</td>
			</tr>
			<tr>
			<td>Invoice Date</td>
			<td>' . $inv_date . '</td>
			</tr>
			<tr>
			<td>Order No</td>
			<td>' . $order_number . '</td>
			</tr>
			<tr>
			<td>Order Date</td>
			<td>' . $order_date . '</td>
			</tr>
			<tr>
			<td>Payment Method</td>
			<td>' . $payement_method . '</td>
			</tr>
			</table>
			</td>
			</tr>
			</table>

			</div>
			<table class="invoice-table">
			<thead>
			<tr>
			<th>Product</th>
			<th>Quantity</th>
			<th>Price</th>
			</tr>
			</thead>
			<tbody>
			' . $product_html . '
			<tr>
			<td></td>
			<td style="border-bottom:2px solid black;border-top:1px solid gray;">Price</td>
			<td style="border-bottom:2px solid black;border-top:1px solid gray;">' . wc_price($new_total) . '</td>
			</tr>
			<tr>
			<td></td>
			<td style="border-bottom:2px solid black;">Tax</td>
			<td style="border-bottom:2px solid black;">' . wc_price($discount_amount) . '</td>
			</tr>
			<tr>
			<td></td>
			<td style="border-bottom:2px solid black;">Total price</td>
			<td style="border-bottom:2px solid black;">' . wc_price($trip_total) . '</td>
			</tr>
			</tbody>
			</table>
			<div><p> ' . wp_kses_post($editorContent) . ' </p></div>
			</body>
			</html>';
		$dompdf->loadHtml($invoiceHtml);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$pdfOutput = $dompdf->output();

		return $pdfOutput;
	}
	public function generate_and_send_invoice_callback()
	{
		$editorContent = sanitize_text_field($_POST['editorContent']);
		$pdf = $this->generate_invoice_pdf($editorContent);
		$to = $_POST['email'];
		$subject = get_option('cloudtect_invoice_email_subject');
		$message = get_option('cloudtect_invoice_email_message');
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$attachments = array(
			array(
				'name' => 'invoice.pdf',
				'data' => $pdf,
			)
		);
		foreach ($attachments as $attachment) {
			$file_path = sys_get_temp_dir() . '/' . $attachment['name'];
			file_put_contents($file_path, $attachment['data']);
			$attached = wp_mail($to, $subject, $message, $headers, $file_path);
			unlink($file_path);
		}
		if ($attached) {
			echo 'Email sent successfully';
		} else {
			echo 'Email sending failed';
		}
		wp_die();
	}
	public function custom_invoice_metabox_content($post)
	{
		$order_id = $post->ID;
		$order = wc_get_order($order_id);
		$billing_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
		$billing_email = $order->get_billing_email();
		$order_total = $order->get_total();
		$order_date = $order->get_date_created();
		$payment_method_name = $order->get_payment_method();
		$first_product_name = '';
		foreach ($order->get_items() as $item_id => $item_data) {
			$product = $item_data->get_product();
			$first_product_name = $product->get_name();
			break;
		}
		$total_order_quantity = 0;
		foreach ($order->get_items() as $item_id => $item_data) {
			$product = $item_data->get_product();
			$quantity = $item_data->get_quantity();
			$total_order_quantity += $quantity;
		}
		$billing_address = $order->get_billing_address_1();
		?>
		<button id="open-popup-button" class="button open-popup-button">Open Popup</button>
		<?php
		$order_id = $post->ID;
		?>
		<div id="custom-popup" title="Edit Invoice Content" style="display:none;">
			<p>
				<label for="inv_number">
					invoice number
					<input type="number" name="inv_number">
				</label>
			</p>
			<p>
				<label for="user name">
					User Name
					<input type="text" name="billing_name" value="<?php echo $billing_name; ?>">
				</label>
			</p>
			<label for="user_email">
				User Email
				<input type="text" name="user_email" value="<?php echo $billing_email; ?>">
			</label>
			</p>
			<p>
				<label for="user_address">
					User Address
					<input type="text" name="user_address" value="<?php echo $billing_address; ?>">
				</label>
			</p>
			<p>
			<p>
				<label for="comapny_name">
					Company name
					<input type="text" name="comapny_name">
				</label>
			</p>
			<p>
				<label for="company_code">
					Company code
					<input type="text" name="company_code">
				</label>
			</p>
			<p>
				<label for="company_vat">
					Company vat code
					<input type="text" name="company_vat">
				</label>
			</p>
			<input type="hidden" name="company_code" value="302645814">
			<input type="hidden" name="vat_code" value="LT100006371317">
			<p>
				<label for="invoice date">
					invoice date
					<input type="date" name="inv_date">
				</label>
			</p>
			<input type="hidden" name="order_number" value="<?php echo $order_id; ?>">
			<input type="hidden" name="order_date" value="<?php echo $order_date->format('Y-m-d H:i:s'); ?>">
			<input type="hidden" name="payement_method" value="<?php echo $payment_method_name; ?>">
			<input type="hidden" name="trip_title" value="<?php echo $first_product_name; ?>">
			<input type="hidden" name="people_qty" value="<?php echo $total_order_quantity; ?>">
			<input type="hidden" name="trip_total" value="<?php echo $order_total; ?>">
			<p>
				<label for="custom_test">
					Custom text
				</label>
			</p>
			<textarea name="custom_editor" cols="50"></textarea>
		</div>
		<?php
	}
	public function add_custom_invoice_metabox()
	{
		add_meta_box(
			'custom-invoice-metabox',
			'Custom Invoice',
			array($this, 'custom_invoice_metabox_content'),
			'shop_order',
			'side',
			'default'
		);
	}


	/**
	 * Search Products by Ajax.
	 *
	 * @return void
	 */
	public function cloudtech_wipoap_search_products()
	{

		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : 0;
		if (!wp_verify_nonce($nonce, 'af-ig-ajax-nonce')) {
			die('Failed security check!');
		}

		if (isset($_POST['q']) && '' !== $_POST['q']) {
			$pro = sanitize_text_field(wp_unslash($_POST['q']));
		} else {
			$pro = '';
		}

		$data_array = array();
		$args = array(
			'post_type' => array('product'),
			'post_status' => 'publish',
			'numberposts' => -1,
			's' => $pro,
		);
		$pros = get_posts($args);
		if (!empty($pros)) {
			foreach ($pros as $proo) {
				$title = (mb_strlen($proo->post_title) > 50) ? mb_substr($proo->post_title, 0, 49) . '...' : $proo->post_title;
				$data_array[] = array($proo->ID, $title); // array( Post ID, Post Title ).
			}
		}
		echo wp_json_encode($data_array);
		die();
	}



	/**
	 * Search users by Ajax.
	 */
	public function cloudtech_wipoap_search_users()
	{

		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : 0;
		if (!wp_verify_nonce($nonce, 'af-ig-ajax-nonce')) {
			die('Failed security check!');
		}

		if (isset($_POST['q']) && '' !== $_POST['q']) {
			$pro = sanitize_text_field(wp_unslash($_POST['q']));
		} else {
			$pro = '';
		}
		$data_array = array();
		$users = new WP_User_Query(
			array(
				'search' => '*' . esc_attr($pro) . '*',
				'search_columns' => array(
					'user_login',
					'user_nicename',
					'user_email',
					'user_url',
				),
			)
		);
		$users_found = $users->get_results();

		if (!empty($users_found)) {
			foreach ($users_found as $proo) {
				$title = $proo->display_name . '(' . $proo->user_email . ')';
				$data_array[] = array($proo->ID, $title); // array( User ID, User name and email ).
			}
		}

		echo wp_json_encode($data_array);
		die();
	}
	public function cloudtech_wipoap_admin_assests()
	{
		wp_enqueue_media();
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('dataTablesjs', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js', array('jquery'), '1.11.5', false);

		// Enqueue styles.
		wp_enqueue_style('order-table-filters', plugins_url('./include/css/order-table-filters.css', __FILE__), [], '1.0');
		wp_enqueue_style('af-ig-admin-css', plugins_url('./include/css/cloudtech_wipoap_admin.css', __FILE__), [], '1.0');
		wp_enqueue_style('wp-jquery-ui-dialog');
		wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0', false);
		wp_enqueue_style('dataTables-style', 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css', array(), '1.11.5');
		// Enqueue scripts.
		wp_enqueue_script('jquery');
		wp_enqueue_script('af-ig-admin-js', plugins_url('./include/js/cloudtech_wipoap_admin.js', __FILE__), array('jquery'), '1.0.0', false);
		wp_enqueue_style('select2css', plugins_url('assets/css/select2.css', WC_PLUGIN_FILE), array(), '5.7.2');
		wp_enqueue_script('select2js', plugins_url('assets/js/select2/select2.min.js', WC_PLUGIN_FILE), array('jquery'), '4.0.3', true);
		// Localize the variables.
		$cloudtech_wipo_data = array(
			'admin_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('af-ig-ajax-nonce'),
			'order_table_filter_url' => admin_url('admin.php?page=invoice-set&tab=order_table_filter'),
		);
		wp_localize_script('af-ig-admin-js', 'cloudtech_wipo_ajax_var', $cloudtech_wipo_data);


		wp_enqueue_script('ct-psbs-ajax-admin', plugins_url('./include/js/ct-psbs-ajax-admin.js', __FILE__), array('jquery'), '1.0.0', false);
		wp_enqueue_script('af-ig-admin-data-table', plugins_url('./include/js/data-table.js', __FILE__), array('jquery'), '1.0.0', false);

		$cloudtech_wipo_data = array(
			'admin_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('af-ig-ajax-nonce'),
			'order_table_filter_url' => admin_url('admin.php?page=invoice-set&tab=order_table_filter'),
		);
		wp_localize_script('ct-psbs-ajax-admin', 'cloudtech_wipo_ajax_var', $cloudtech_wipo_data);


	}
	/**
	 * Save Post meta for Ivnoice Payment.
	 *
	 * @param int     $post_id post id of current post.
	 * @param WP_Post $post    post object.
	 *
	 * @return void
	 */
	public function cloudtech_wipoap_save_metadata($post_id, $post = false)
	{

		// Return if not relevant post type.
		if ('payment_gateway' !== $post->post_type) {
			return;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (!current_user_can('edit_posts')) {
			return;
		}
		// For custom post type:
		$exclude_statuses = array(
			'auto-draft',
			'trash',
		);

		$post_action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';

		if (!in_array(get_post_status($post_id), $exclude_statuses) && !is_ajax() && 'untrash' != $post_action) {
			if (!empty($_POST['cloudtech_wipo_field_nonce'])) {

				$retrieved_nonce = sanitize_text_field($_POST['cloudtech_wipo_field_nonce']);
			} else {
				$retrieved_nonce = 0;
			}

			if (!wp_verify_nonce($retrieved_nonce, 'cloudtech_wipo_nonce_action')) {

				die('Failed Security Check!');
			}

			/**
			 * Shipping Meta Box.
			 *
			 * Save invoice-payment amount, invoice-payment Type, Enable Tax, Include in cart amount.
			 */

			// invoice-payment Amount.
			if (isset($_POST['cloudtech_wipo_fee_amount'])) {
				update_post_meta($post_id, 'cloudtech_wipo_fee_amount', sanitize_text_field(wp_unslash($_POST['cloudtech_wipo_fee_amount'])));
			}

			// invoice Type.
			if (isset($_POST['cloudtech_wipo_fee_type'])) {
				update_post_meta($post_id, 'cloudtech_wipo_fee_type', sanitize_text_field(wp_unslash($_POST['cloudtech_wipo_fee_type'])));
			}

			// Enable All Products.
			if (isset($_POST['cloudtech_wipo_enable_products'])) {
				update_post_meta($post_id, 'cloudtech_wipo_enable_products', sanitize_text_field(wp_unslash($_POST['cloudtech_wipo_enable_products'])));
			} else {
				update_post_meta($post_id, 'cloudtech_wipo_enable_products', 'no');
			}

			// Enable Tax.
			if (isset($_POST['cloudtech_wipo_enable_tax'])) {
				update_post_meta($post_id, 'cloudtech_wipo_enable_tax', sanitize_text_field(wp_unslash($_POST['cloudtech_wipo_enable_tax'])));
			} else {
				update_post_meta($post_id, 'cloudtech_wipo_enable_tax', 'no');
			}

			/**
			 * Location for invoice.
			 *
			 * Save Countries, States, Zip Codes, Cities.
			 */

			// invoice-payment on Shipping zones.
			if (isset($_POST['cloudtech_wipo_shipping_zone'])) {
				update_post_meta($post_id, 'cloudtech_wipo_shipping_zone', (sanitize_meta('', wp_unslash($_POST['cloudtech_wipo_shipping_zone']), '')));
			} else {
				update_post_meta($post_id, 'cloudtech_wipo_shipping_zone', (array()));
			}

			// invoice-payment Countries.
			if (isset($_POST['cloudtech_wipo_countries'])) {
				update_post_meta($post_id, 'cloudtech_wipo_countries', (sanitize_meta('', wp_unslash($_POST['cloudtech_wipo_countries']), '')));
			} else {
				update_post_meta($post_id, 'cloudtech_wipo_countries', (array()));
			}

			// invoice-payment States.
			if (isset($_POST['cloudtech_wipo_states'])) {
				update_post_meta($post_id, 'cloudtech_wipo_states', (sanitize_meta('', wp_unslash($_POST['cloudtech_wipo_states']), '')));
			} else {
				update_post_meta($post_id, 'cloudtech_wipo_states', (array()));
			}

			// Enable Zip Codes.
			if (isset($_POST['cloudtech_wipo_zip_codes'])) {
				update_post_meta($post_id, 'cloudtech_wipo_zip_codes', sanitize_text_field(wp_unslash($_POST['cloudtech_wipo_zip_codes'])));
			}

			// invoice-payment Cities.
			if (isset($_POST['cloudtech_wipo_cities'])) {
				update_post_meta($post_id, 'cloudtech_wipo_cities', sanitize_text_field(wp_unslash($_POST['cloudtech_wipo_cities'])));
			}

			// invoice-payment on Cart Amount.
			if (isset($_POST['cloudtech_wipo_cart_amount'])) {
				update_post_meta($post_id, 'cloudtech_wipo_cart_amount', (sanitize_meta('', wp_unslash($_POST['cloudtech_wipo_cart_amount']), '')));
			}
			// invoice-payment on Cart quantity.
			if (isset($_POST['cloudtech_wipo_cart_quantity'])) {
				update_post_meta($post_id, 'cloudtech_wipo_cart_quantity', (sanitize_meta('', wp_unslash($_POST['cloudtech_wipo_cart_quantity']), '')));
			}

			// invoice-payment for Products.
			if (isset($_POST['cloudtech_wipo_cart_products'])) {
				update_post_meta($post_id, 'cloudtech_wipo_cart_products', (sanitize_meta('', wp_unslash($_POST['cloudtech_wipo_cart_products']), '')));
			}

			// invoice-payment for product Categories.
			if (isset($_POST['cloudtech_wipo_cart_products_cat'])) {
				update_post_meta($post_id, 'cloudtech_wipo_cart_products_cat', (sanitize_meta('', wp_unslash($_POST['cloudtech_wipo_cart_products_cat']), '')));
			} else {

				update_post_meta($post_id, 'cloudtech_wipo_cart_products_cat', (array()));
			}
			// invoice-payment for product tags.
			if (isset($_POST['cloudtech_wipo_cart_products_tag'])) {
				update_post_meta($post_id, 'cloudtech_wipo_cart_products_tag', (sanitize_meta('', wp_unslash($_POST['cloudtech_wipo_cart_products_tag']), '')));
			} else {

				update_post_meta($post_id, 'cloudtech_wipo_cart_products_tag', (array()));
			}
			// invoice-payment for shipping.
			if (isset($_POST['cloudtech_wipo_shipping'])) {
				update_post_meta($post_id, 'cloudtech_wipo_shipping', (sanitize_meta('', wp_unslash($_POST['cloudtech_wipo_shipping']), '')));
			} else {

				update_post_meta($post_id, 'cloudtech_wipo_shipping', (array()));
			}
			// invoice-payment for user Shipping Classes.
			if (isset($_POST['cloudtech_wipo_shipping_classes'])) {
				update_post_meta($post_id, 'cloudtech_wipo_shipping_classes', (sanitize_meta('', wp_unslash($_POST['cloudtech_wipo_shipping_classes']), '')));
			}

			// invoice-payment on users.
			if (isset($_POST['cloudtech_wipo_customer_select'])) {
				update_post_meta($post_id, 'cloudtech_wipo_customer_select', (sanitize_meta('', wp_unslash($_POST['cloudtech_wipo_customer_select']), '')));
			} else {
				update_post_meta($post_id, 'cloudtech_wipo_customer_select', (array()));
			}

			// invoice-payment for user roles.
			if (isset($_POST['cloudtech_wipo_customer_roles'])) {
				update_post_meta($post_id, 'cloudtech_wipo_customer_roles', (sanitize_meta('', wp_unslash($_POST['cloudtech_wipo_customer_roles']), '')));
			} else {
				update_post_meta($post_id, 'cloudtech_wipo_customer_roles', (array()));
			}
		}
	}
	public function cloudtech_wipoap_payment_gateway_meta_boxes()
	{
		// Meta box for invoice payment.
		add_meta_box(
			'af-ig-shipping-method',
			esc_html__('Invoice based on Shipping', 'cloudtech_wipo'),
			array($this, 'cloudtech_wipo_virtual_meta'),
			'payment_gateway'
		);

		// Meta box for invoice payment restrictions for Users.
		add_meta_box(
			'af-ig-conditions-users',
			esc_html__('Users and Roles for Invoice', 'cloudtech_wipo'),
			array($this, 'cloudtech_wipo_conditions_users_meta'),
			'payment_gateway'
		);

		// Meta box for invoice payment restrictions for Cart.
		add_meta_box(
			'af-ig-conditions-cart',
			esc_html__('Cart Amount and Products for Invoice', 'cloudtech_wipo'),
			array($this, 'cloudtech_wipo_conditions_cart_meta'),
			'payment_gateway'
		);

		// Meta box for invoice payment restrictions for location.
		add_meta_box(
			'af-ig-conditions-location',
			esc_html__('Countries, States, Zip-Codes, Cities  for Invoice', 'cloudtech_wipo'),
			array($this, 'cloudtech_wipo_conditions_location_meta'),
			'payment_gateway'
		);
	}
	/**
	 * Ivnoice Payment meta box call back function.
	 *
	 * @return void
	 */
	public function cloudtech_wipo_virtual_meta()
	{
		wp_nonce_field('cloudtech_wipo_nonce_action', 'cloudtech_wipo_nonce_field');
		include_once CLOUDTECH_WIPOAP_PLUGIN_DIR . '/meta-boxes/cloudtech-wipoap-virtual.php';
	}

	/**
	 * Ivnoice Payment conditions for Location.
	 *
	 * @return void
	 */
	public function cloudtech_wipo_conditions_location_meta()
	{
		include_once CLOUDTECH_WIPOAP_PLUGIN_DIR . '/meta-boxes/cloudtech-wipoap-conditions-location.php';
	}

	/**
	 * Ivnoice Payment conditions for Cart.
	 *
	 * @return void
	 */
	public function cloudtech_wipo_conditions_cart_meta()
	{
		include_once CLOUDTECH_WIPOAP_PLUGIN_DIR . '/meta-boxes/cloudtech-wipoap-conditions-cart.php';
	}

	/**
	 * Ivnoice Payment conditions for Users.
	 *
	 * @return void
	 */
	public function cloudtech_wipo_conditions_users_meta()
	{
		include_once CLOUDTECH_WIPOAP_PLUGIN_DIR . '/meta-boxes/cloudtech-wipoap-conditions-users.php';
	}
	public function devsoul_ipmaip_customer_search()
	{
		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;

		if (!wp_verify_nonce($nonce, 'af-ig-ajax-nonce')) {

			wp_die(esc_html__('Failed Security check!', 'devsoul_psbscac_text_d'));
		}

		$pro = isset($_POST['q']) && !empty($_POST['q']) ? sanitize_text_field(wp_unslash($_POST['q'])) : '';
		$all_users = get_users([
			'search' => '*' . $pro . '*',
			'search_columns' => ['user_login', 'user_email', 'display_name'],
		]);
		foreach ($all_users as $user) {
			$pro_front_post = (mb_strlen($user->display_name) > 50) ? mb_substr($user->display_name, 0, 49) . '...' : $user->display_name;
			$data_array[] = array($user->ID, $pro_front_post); // array( Post ID, Post Title ).
		}

		echo wp_json_encode($data_array);
		die();
	}
	public function devsoul_ipmaip_send_invoice()
	{
		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : 0;

		if (!wp_verify_nonce($nonce, 'af-ig-ajax-nonce')) {

			wp_die(esc_html__('Failed Security check!', 'devsoul_psbscac_text_d'));
		}

		if (isset($_POST['order_id'])) {

			$order_id = sanitize_text_field($_POST['order_id']);
			ct_ipmaip_send_invoice_pdf($order_id);
		}
	}
}
if (class_exists('Cloudtech_Wipoap_Admin')) {
	new Cloudtech_Wipoap_Admin();
}
function generate_invoice_pdf_using_order_id($order_id)
{
	$inv_date = date('Y-m-d');
	$order_number = $order_id;
	$order = wc_get_order($order_number);
	if (!$order) {
		return false;
	}
	$product_html = "";
	$items = $order->get_items();
	foreach ($items as $item_id => $item) {
		$product_name = $item->get_name();
		$quantity = $item->get_quantity();
		$price = $item->get_total();
		$product_html .= '<tr style="border-bottom:1px solid gray;">
		<td>' . $product_name . '</td>
		<td>' . $quantity . '</td>
		<td>' . wc_price($price) . '</td>
		</tr>';
	}
	$payement_method = $order->get_payment_method_title();
	$billing_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
	$email = $order->get_billing_email();
	$order_total = $order->get_total();
	$created_date = $order->get_date_created();
	$order_date = $created_date ? $created_date->format('Y-m-d H:i:s') : '';
	require_once(CLOUDTECH_WIPOAP_PLUGIN_DIR . '/dompdf/vendor/autoload.php');
	$options = new Dompdf\Options();
	$options->set('isHtml5ParserEnabled', true);
	$options->set('isPhpEnabled', true);
	$dompdf = new Dompdf\Dompdf($options);
	$path = get_option('cloudtect_invoice_logo_url');
	$type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
	$new_total = $order_total;
	$invoiceHtml = '
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<style>
	body { hyphens: auto; font-family: Arial, Helvetica, sans-serif ;color: black;
		font-family: Arial, sans-serif;
		font-family: DejaVu Sans; sans-serif;
		font-style: normal;
		font-weight: normal;
		text-decoration: none;
		font-size: 9pt;
		margin: 0pt; }
		.invoice-header { text-align: center; background-color: #333; color: #fff; padding: 10px; }
		.invoice-details { margin: 20px; }
		.invoice-table { width: 100%; border-collapse: collapse; }
		.invoice-table th{ background-color:black;color:white;text-align:left;}
		.invoice-table th, .invoice-table td { padding: 10px; }
		</style>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		</head>
		<body>
		<div class="invoice-details">
		<table style="width:100%">
		<tr>
		<td style="vertical-align: top;">
		<img src=' . $base64 . ' width="200" height="50"/>
		</td>
		<td style="text-align:right;vertical-align: top;">
		<p>' . get_bloginfo('name') . '</p>
		</td>
		</tr>
		</table>
		<h2>Billing Details</h2>
		<table style="width:100%">
		<tr>
		<td style="vertical-align: top;">
		<p>' . $billing_name . '</p>
		<p>' . $email . '</p>
		</td>
		<td style="text-align:right;vertical-align: top;">
		<table style="width:100%;text-align:right;">
		<tr>
		<td>Invoice Date</td>
		<td>' . $inv_date . '</td>
		</tr>
		<tr>
		<td>Order No</td>
		<td>' . $order_number . '</td>
		</tr>
		<tr>
		<td>Order Date</td>
		<td>' . $order_date . '</td>
		</tr>
		<tr>
		<td>Payment Method</td>
		<td>' . $payement_method . '</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>

		</div>
		<table class="invoice-table">
		<thead>
		<tr>
		<th>Product</th>
		<th>Quantity</th>
		<th>Price</th>
		</tr>
		</thead>
		<tbody>
		' . $product_html . '
		<tr>
		<td></td>
		<td style="border-bottom:2px solid black;border-top:1px solid gray;">Price</td>
		<td style="border-bottom:2px solid black;border-top:1px solid gray;">' . wc_price($new_total) . '</td>
		</tr>
		<tr>
		<td></td>
		<td style="border-bottom:2px solid black;">Total price</td>
		<td style="border-bottom:2px solid black;">' . wc_price($order_total) . '</td>
		</tr>
		</tbody>
		</table>
		</body>
		</html>';
	$dompdf->loadHtml($invoiceHtml);
	$dompdf->setPaper('A4', 'portrait');
	$dompdf->render();
	$pdfOutput = $dompdf->output();

	return $pdfOutput;
}
