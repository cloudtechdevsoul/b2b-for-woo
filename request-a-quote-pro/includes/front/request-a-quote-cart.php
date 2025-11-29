<?php
add_shortcode('cloud_tech_request_a_quote', 'cloud_tech_request_a_quote_cart');
add_shortcode('cloud_tech_request_a_quote_mini_cart', 'cloud_tech_request_a_quote_mini_cart');
function cloud_tech_request_a_quote_mini_cart()
{


	$added_products_to_quote = (array) wc()->session->get('cloud_tech_quote_cart');

	$added_products_to_quote = ct_rfq_custom_array_filter($added_products_to_quote);
	?>
	<div class="mini-cart-wrap">
		<?php

		$icon_or_image = !empty(get_option('ct_rfq_mini_cart_logo')) ? '<img class="ct-mini-quote-icon" style="width:40px;height:40px;" src="' . esc_url(get_option('ct_rfq_mini_cart_logo')) . '" srcset="' . esc_url(get_option('ct_rfq_billing_logos')) . '" />' : '<i class="ct-mini-quote-icon fa fa-solid fa fa-cart-plus"></i>';
		echo wp_kses_post($icon_or_image);

		if (empty($added_products_to_quote)) {

			wc_print_notice(esc_html__('Your quote is currently empty.', 'cloud_tech_rfq'), 'notice');

			?><a class="button wc-backward" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
				<?php echo esc_html__('Return to Shop', 'cloud_tech_rfq'); ?>
			</a>
			<?php

			return;
		}
		?>

		<div class="mini-cart">
			<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
				<thead>
					<tr>
						<th class="product-name">
							<?php echo esc_html__('Product', 'woocommerce'); ?>
						</th>
						<th class="product-subtotal">
							<?php echo esc_html__('Subtotal', 'woocommerce'); ?>
						</th>
					</tr>
				</thead>
				<tbody>

					<?php foreach ($added_products_to_quote as $quote_key => $current_product_quote_detail) {

						if (!is_array($current_product_quote_detail)) {
							continue;
						}

						if (!isset($current_product_quote_detail['product_id']) || empty($current_product_quote_detail['product_id'])) {
							continue;
						}

						$current_rule_id = isset($current_product_quote_detail['current_rule_id']) ? ($current_product_quote_detail['current_rule_id']) : 0;
						$product_id = $current_product_quote_detail['product_id'];
						$variation_id = isset($current_product_quote_detail['variation_id']) ? ($current_product_quote_detail['variation_id']) : 0;
						$quantity = isset($current_product_quote_detail['quantity']) && $current_product_quote_detail['quantity'] >= 1 ? ($current_product_quote_detail['quantity']) : 1;
						$available_qty = isset($current_product_quote_detail['available_qty']) ? ($current_product_quote_detail['available_qty']) : '';

						if ('-1' === (string) $current_product_quote_detail['available_qty']) {
							$available_qty = '';
						}

						$custom_price = isset($current_product_quote_detail['custom_price']) ? ($current_product_quote_detail['custom_price']) : '';
						$price_text = get_post_meta($current_rule_id, 'ct_rfq_replace_price_text', true);
						$hide_or_replace = get_post_meta($current_rule_id, 'ct_rfq_hide_price_or_replace_text', true);
						$product = wc_get_product($variation_id > 1 ? $variation_id : $product_id);

						?>
						<tr class="woocommerce-cart-form__cart-item">

							<td class="product-name" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
								<?php
								$product_permalink = wc_placeholder_img_src();

								if (!empty(get_the_post_thumbnail_url($variation_id, 'post-thumbnail'))) {

									$product_permalink = get_the_post_thumbnail_url($variation_id, 'post-thumbnail');

								} else if (!empty(get_the_post_thumbnail_url($product_id, 'post-thumbnail'))) {

									$product_permalink = get_the_post_thumbnail_url($product_id, 'post-thumbnail');

								}
								?>
								<a href="<?php echo esc_url($product_id); ?>"><img fetchpriority="high" decoding="async"
										width="100" height="100" src="<?php echo esc_url($product_permalink); ?>"
										class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt=""
										srcset="<?php echo esc_url($product_permalink); ?>"
										sizes="(max-width: 324px) 100vw, 324px"></a>
								<a href="<?php echo get_permalink($product_id); ?>">
									<?php echo esc_attr($variation_id > 1 ? get_the_title($variation_id) : get_the_title($product_id)); ?>
								</a>X
								<?php echo esc_attr($quantity); ?>
							</td>
							<td class="product-subtotal" data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
								<?php

								$new_sub_total = '';
								if (str_contains($hide_or_replace, 'hide') && !str_contains($hide_or_replace, 'product_page')) {
									$new_sub_total = '';

								} else if (str_contains($hide_or_replace, 'replace') && !str_contains($hide_or_replace, 'product_page')) {
									$new_sub_total = $price_text;

								} else if (!empty(get_post_meta($current_rule_id, 'ct_rfq_can_user_add_custom_amount', true))) {
									if (!empty($custom_price) && $custom_price >= 0.1) {
										$new_sub_total = wc_price($custom_price * $quantity);
									} else {
										$new_sub_total = wc_price($product->get_price() * $quantity);

									}
								}

								echo wp_kses_post($new_sub_total);

								?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="">
				<div class="cart_totals ">
					<?php if (!empty(get_option('ct_rfq_hide_cart_sub_total')) && !empty(get_option('ct_rfq_hide_cart_tax')) && !empty(get_option('ct_rfq_hide_cart_total'))): ?>
						<h2>
							<?php esc_html_e('Cart totals', 'woocommerce'); ?>
						</h2>
					<?php endif ?>

					<table cellspacing="0" class="shop_table shop_table_responsive">

						<?php if (!empty(get_option('ct_rfq_hide_cart_sub_total'))): ?>

							<tr class="cart-subtotal">
								<th>
									<?php esc_html_e('Subtotal', 'woocommerce'); ?>
								</th>
								<td data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
									<?php
									echo wp_kses_post(wc_price(wc_get_quote_subtotal_ct())); ?>
								</td>
							</tr>
						<?php endif ?>

						<?php if (!empty(get_option('ct_rfq_hide_cart_tax'))): ?>


							<tr class="tax-total">
								<th>
									<?php echo esc_html(WC()->countries->tax_or_vat()); ?>
								</th>
								<td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>">
									<?php echo wp_kses_post(wc_price(wc_get_quote_tax_ct())); ?>
								</td>
							</tr>

						<?php endif ?>
						<?php if (!empty(get_option('ct_rfq_hide_cart_total'))): ?>

							<tr class="order-total">
								<th>
									<?php esc_html_e('Total', 'woocommerce'); ?>
								</th>
								<td data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>">
									<?php echo wp_kses_post(wc_price(wc_get_quote_total_ct())); ?>
								</td>
							</tr>

						<?php endif ?>

					</table>
					<a href="<?php echo esc_url(get_page_link(get_rfq_pge_id())); ?>" class="button wc-forward">
						<?php esc_html_e('View Quote', 'woocommerce'); ?>
					</a>
				</div>

			</div>

		</div>
	</div>



	<?php

}

function cloud_tech_request_a_quote_cart()
{
	$added_products_to_quote = (array) wc()->session->get('cloud_tech_quote_cart');
	$added_products_to_quote = ct_rfq_custom_array_filter($added_products_to_quote);

	if (empty($added_products_to_quote)) {

		wc_print_notice(esc_html__('Your quote is currently empty.', 'cloud_tech_rfq'), 'notice');

		?>
		<a class="button wc-backward" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
			<?php echo esc_html__('Return to Shop', 'cloud_tech_rfq'); ?>
		</a>
		<?php

		return;
	}

	?>
	<div class="cloud-tech-quote-details-cart-items">
		<form class="woocommerce-cart-form cloud-tech-quote-cart" method="post">
			<?php

			$check_product_detail_array = apply_filters('cloud_tech_item_detail', 0, '');
			echo wp_kses_post($check_product_detail_array);
			wp_nonce_field('cloud_tech_rfq_nonce', 'cloud_tech_rfq_nonce'); ?>
			<input type="submit" class="button wc-forward" name="cloud_tect_update_quote_cart" value="Update Quote">
		</form>
		<div class="cart-collaterals">
			<div class="cart_totals ">

				<h2>
					<?php esc_html_e('Cart totals', 'woocommerce'); ?>
				</h2>

				<table cellspacing="0" class="shop_table shop_table_responsive">

					<?php if (!empty(get_option('ct_rfq_hide_cart_sub_total'))): ?>

						<tr class="cart-subtotal">
							<th>
								<?php esc_html_e('Subtotal', 'woocommerce'); ?>
							</th>
							<td data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
								<?php
								echo wp_kses_post(wc_price(wc_get_quote_subtotal_ct())); ?>
							</td>
						</tr>
					<?php endif ?>

					<?php if (!empty(get_option('ct_rfq_hide_cart_tax'))): ?>


						<tr class="tax-total">
							<th>
								<?php echo esc_html(WC()->countries->tax_or_vat()); ?>
							</th>
							<td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>">
								<?php echo wp_kses_post(wc_price(wc_get_quote_tax_ct())); ?>
							</td>
						</tr>

					<?php endif ?>
					<?php if (!empty(get_option('ct_rfq_hide_cart_total'))): ?>

						<tr class="order-total">
							<th>
								<?php esc_html_e('Total', 'woocommerce'); ?>
							</th>
							<td data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>">
								<?php echo wp_kses_post(wc_price(wc_get_quote_total_ct())); ?>
							</td>
						</tr>

					<?php endif ?>
				</table>

			</div>

		</div>

	</div>

	<input type="hidden" class="ct_rfq_cart_total" data-sub_total="<?php echo esc_attr(wc_get_quote_subtotal_ct()); ?>"
		data-total="<?php echo esc_attr(wc_get_quote_total_ct()); ?>"
		data-currency="<?php echo wp_kses_post(get_woocommerce_currency_symbol()); ?>">
	<?php

	$template_type = get_option('ct_rfq_request_a_quote_billing_field_template') ? get_option('ct_rfq_request_a_quote_billing_field_template') : '1st-template';

	include CT_RFQ_PLUGIN_DIR . 'templates/checkout-fields/' . $template_type . '.php';

}


add_action('wp_loaded', 'cloud_tech_update_cart_detail');
function cloud_tech_update_cart_detail()
{

	$nonce = isset($_POST['cloud_tech_rfq_nonce']) ? sanitize_text_field($_POST['cloud_tech_rfq_nonce']) : 0;

	if (isset($_POST['cloud_tect_update_quote_cart']) && !wp_verify_nonce($nonce, 'cloud_tech_rfq_nonce')) {
		wp_die(esc_html__('Security Voilated', 'cloud_tech_rfq'));
	}

	if (isset($_POST['cloud_tect_update_quote_cart'])) {

		$added_products_to_quote = (array) wc()->session->get('cloud_tech_quote_cart');
		$added_products_to_quote = ct_rfq_custom_array_filter($added_products_to_quote);
		foreach ($added_products_to_quote as $quote_key => $current_product_quote_detail) {

			if (!is_array($current_product_quote_detail)) {
				continue;
			}

			if (!isset($current_product_quote_detail['product_id']) || empty($current_product_quote_detail['product_id'])) {
				continue;
			}

			$current_rule_id = isset($current_product_quote_detail['current_rule_id']) ? ($current_product_quote_detail['current_rule_id']) : 0;

			$product_id = $current_product_quote_detail['product_id'];

			$variation_id = isset($current_product_quote_detail['variation_id']) ? ($current_product_quote_detail['variation_id']) : 0;


			$product = wc_get_product($variation_id > 1 ? $variation_id : $product_id);

			$custom_price = isset($_POST['cloud_tech_custom_price' . $quote_key]) && !empty($_POST['cloud_tech_custom_price' . $quote_key]) && (float) $_POST['cloud_tech_custom_price' . $quote_key] >= 0.1 ? ($_POST['cloud_tech_custom_price' . $quote_key]) : 0;

			$quantity = isset($_POST['cloud_tech_quote_qty' . $quote_key]) && $_POST['cloud_tech_quote_qty' . $quote_key] >= 1 ? sanitize_text_field($_POST['cloud_tech_quote_qty' . $quote_key]) : 1;


			$product_detail_array['available_qty'] = apply_filters('cloud_tech_product_available_qty', $product);


			if (!empty($product_detail_array['available_qty']) && $product_detail_array['available_qty'] >= 1 && $quantity >= $product_detail_array['available_qty']) {

				$quantity = $product_detail_array['available_qty'];
			}

			if ($quantity <= 1) {
				$quantity = 1;
			}

			$added_products_to_quote[$quote_key]['quantity'] = $quantity;
			$added_products_to_quote[$quote_key]['custom_price'] = $custom_price;

		}

		$notice = !empty(get_option('ct_rfq_quote_update_message')) ? get_option('ct_rfq_quote_update_message') : 'Quote cart Update successfully';

		wc_add_notice($notice, 'success');

		wc()->session->set('cloud_tech_quote_cart', $added_products_to_quote);
	}


	if (isset($_POST['palce_quote'])) {

		$added_products_to_quote = (array) wc()->session->get('cloud_tech_quote_cart');
		$added_products_to_quote = ct_rfq_custom_array_filter($added_products_to_quote);
		$new_post_id = wp_insert_post(['post_type' => 'ct-rfq-submit-quote', 'post_status' => 'publish',]);

		$post_data = array(
			'ID' => $new_post_id,
			'post_title' => '#' . $new_post_id,
		);

		wp_update_post($post_data);

		update_post_meta($new_post_id, 'cloud_tech_quote_billing_details', $_POST);
		update_post_meta($new_post_id, 'cloud_tech_quote_cart', $added_products_to_quote);
		$notice = !empty(get_option('ct_rfq_quote_submit_message')) ? get_option('ct_rfq_quote_submit_message') : 'Quote Place Success fully';

		$subject = $notice;
		$headers = array('Content-Type: text/html; charset=UTF-8');
		if (isset($_POST['billing-email'])) {

			$to = sanitize_email($_POST['billing-email']);
			$message = 'Your quote is place successfully we will inform you shortly.Your Quote Id is ' . $new_post_id;

			ob_start();

			?>

			<div marginwidth="0" marginheight="0" style="background-color:#f7f7f7;padding:0;text-align:center" bgcolor="#f7f7f7">
				<table width="100%" id="m_-2524256812401980191m_-7523361011986018400outer_wrapper" style="background-color:#f7f7f7"
					bgcolor="#f7f7f7">
					<tbody>
						<tr>
							<td></td>
							<td width="600">
								<div id="m_-2524256812401980191m_-7523361011986018400wrapper" dir="ltr"
									style="margin:0 auto;padding:70px 0;width:100%;max-width:600px" width="100%">
									<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
										<tbody>
											<tr>
												<td align="center" valign="top">
													<div id="m_-2524256812401980191m_-7523361011986018400template_header_image">
													</div>
													<table border="0" cellpadding="0" cellspacing="0" width="100%"
														id="m_-2524256812401980191m_-7523361011986018400template_container"
														style="background-color:#fff;border:1px solid #dedede;border-radius:3px"
														bgcolor="#fff">
														<tbody>
															<tr>
																<td align="center" valign="top">

																	<table border="0" cellpadding="0" cellspacing="0" width="100%"
																		id="m_-2524256812401980191m_-7523361011986018400template_header"
																		style="background-color:#7f54b3;color:#fff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0"
																		bgcolor="#7f54b3">
																		<tbody>
																			<tr>
																				<td id="m_-2524256812401980191m_-7523361011986018400header_wrapper"
																					style="padding:36px 48px;display:block">
																					<h1 style="font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;color:#fff;background-color:inherit"
																						bgcolor="inherit">
																					</h1>
																				</td>
																			</tr>
																		</tbody>
																	</table>

																</td>
															</tr>
															<tr>
																<td align="center" valign="top">

																	<table border="0" cellpadding="0" cellspacing="0" width="100%"
																		id="m_-2524256812401980191m_-7523361011986018400template_body">
																		<tbody>
																			<tr>
																				<td valign="top"
																					id="m_-2524256812401980191m_-7523361011986018400body_content"
																					style="background-color:#fff" bgcolor="#fff">

																					<table border="0" cellpadding="20"
																						cellspacing="0" width="100%">
																						<tbody>
																							<tr>
																								<td valign="top"
																									style="padding:48px 48px 32px">
																									<div id="m_-2524256812401980191m_-7523361011986018400body_content_inner"
																										style="color:#636363;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left"
																										align="left">


																										<h2
																											style="color:#7f54b3;display:block;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">
																											<?php echo wp_kses_post($message); ?>
																										</h2>

																										<div
																											style="margin-bottom:40px">
																											<table cellspacing="0"
																												cellpadding="6"
																												border="1"
																												style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif"
																												width="100%">
																												<thead>
																													<tr>
																														<th scope="col"
																															style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
																															align="left">
																															<?php echo esc_html__('Product', 'woocommerce') ?>
																														</th>
																														<th scope="col"
																															style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
																															align="left">
																															<?php echo esc_html__('Quantity', 'woocommerce') ?>
																														</th>
																														<th scope="col"
																															style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
																															align="left">
																															<?php echo esc_html__('Price', 'woocommerce') ?>
																														</th>
																													</tr>
																												</thead>
																												<tbody>
																													<?php foreach ($added_products_to_quote as $quote_key => $current_product_quote_detail) {

																														if (!is_array($current_product_quote_detail)) {
																															continue;
																														}

																														if (!isset($current_product_quote_detail['product_id']) || empty($current_product_quote_detail['product_id'])) {
																															continue;
																														}



																														$current_rule_id = isset($current_product_quote_detail['current_rule_id']) ? ($current_product_quote_detail['current_rule_id']) : 0;

																														$product_id = ($current_product_quote_detail['product_id']);

																														$variation_id = isset($current_product_quote_detail['variation_id']) ? ($current_product_quote_detail['variation_id']) : 0;

																														$quantity = isset($current_product_quote_detail['quantity']) && $current_product_quote_detail['quantity'] >= 1 ? ($current_product_quote_detail['quantity']) : 1;

																														$available_qty = isset($current_product_quote_detail['available_qty']) ? ($current_product_quote_detail['available_qty']) : '';

																														if ('-1' === (string) $current_product_quote_detail['available_qty']) {
																															$available_qty = '';
																														}

																														$custom_price = isset($current_product_quote_detail['custom_price']) ? ($current_product_quote_detail['custom_price']) : '';

																														$product = wc_get_product($variation_id > 1 ? $variation_id : $product_id);

																														$product_permalink = wc_placeholder_img_src();

																														if (!empty(get_the_post_thumbnail_url($variation_id, 'post-thumbnail'))) {

																															$product_permalink = get_the_post_thumbnail_url($variation_id, 'post-thumbnail');

																														} else if (!empty(get_the_post_thumbnail_url($product_id, 'post-thumbnail'))) {

																															$product_permalink = get_the_post_thumbnail_url($product_id, 'post-thumbnail');

																														}

																														?>


																														<tr>
																															<td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word"
																																align="left">
																																<a
																																	href="<?php echo get_permalink($product_id); ?>">
																																	<?php echo esc_attr($variation_id > 1 ? get_the_title($variation_id) : get_the_title($product_id)); ?>
																																</a>
																															</td>
																															<td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif"
																																align="left">
																																<?php echo esc_attr($quantity); ?>
																															</td>
																															<td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif"
																																align="left">
																															</td>
																														</tr>

																													<?php } ?>

																												</tbody>
																												<tfoot>

																												</tfoot>
																											</table>
																										</div>

																									</div>
																								</td>
																							</tr>
																						</tbody>
																					</table>

																				</td>
																			</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
							<td></td>
						</tr>
					</tbody>
				</table>
				<div class="yj6qo"></div>
				<div class="adL"></div>
				<div class="adL"></div>
			</div>
			<?php
			$customer_order_content_for_message = ob_get_clean();
			wp_mail($to, $subject, $customer_order_content_for_message, $headers);

		}
		$current_user_email = isset($_POST['current_user_email']) ? sanitize_email($_POST['current_user_email']) : '';
		update_post_meta($new_post_id, 'current_user_email', $current_user_email);

		$to = get_option('woocommerce_email_from_address');
		$admin_h2_message = 'A quote is place successfully here is Quote Id.' . $new_post_id . '<a href=' . get_permalink($new_post_id) . '/>Click Here For Details </a>';
		$added_products_to_quote = (array) get_post_meta($new_post_id, 'cloud_tech_quote_cart', true);

		$added_products_to_quote = ct_rfq_custom_array_filter($added_products_to_quote);
		ob_start();

		?>
		<div marginwidth="0" marginheight="0" style="background-color:#f7f7f7;padding:0;text-align:center" bgcolor="#f7f7f7">
			<table width="100%" id="m_-2524256812401980191m_-7523361011986018400outer_wrapper" style="background-color:#f7f7f7"
				bgcolor="#f7f7f7">
				<tbody>
					<tr>
						<td></td>
						<td width="600">
							<div id="m_-2524256812401980191m_-7523361011986018400wrapper" dir="ltr"
								style="margin:0 auto;padding:70px 0;width:100%;max-width:600px" width="100%">
								<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
									<tbody>
										<tr>
											<td align="center" valign="top">
												<div id="m_-2524256812401980191m_-7523361011986018400template_header_image">
												</div>
												<table border="0" cellpadding="0" cellspacing="0" width="100%"
													id="m_-2524256812401980191m_-7523361011986018400template_container"
													style="background-color:#fff;border:1px solid #dedede;border-radius:3px"
													bgcolor="#fff">
													<tbody>
														<tr>
															<td align="center" valign="top">

																<table border="0" cellpadding="0" cellspacing="0" width="100%"
																	id="m_-2524256812401980191m_-7523361011986018400template_header"
																	style="background-color:#7f54b3;color:#fff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0"
																	bgcolor="#7f54b3">
																	<tbody>
																		<tr>
																			<td id="m_-2524256812401980191m_-7523361011986018400header_wrapper"
																				style="padding:36px 48px;display:block">
																				<h1 style="font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;color:#fff;background-color:inherit"
																					bgcolor="inherit">
																				</h1>
																			</td>
																		</tr>
																	</tbody>
																</table>

															</td>
														</tr>
														<tr>
															<td align="center" valign="top">

																<table border="0" cellpadding="0" cellspacing="0" width="100%"
																	id="m_-2524256812401980191m_-7523361011986018400template_body">
																	<tbody>
																		<tr>
																			<td valign="top"
																				id="m_-2524256812401980191m_-7523361011986018400body_content"
																				style="background-color:#fff" bgcolor="#fff">

																				<table border="0" cellpadding="20"
																					cellspacing="0" width="100%">
																					<tbody>
																						<tr>
																							<td valign="top"
																								style="padding:48px 48px 32px">
																								<div id="m_-2524256812401980191m_-7523361011986018400body_content_inner"
																									style="color:#636363;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left"
																									align="left">


																									<h2
																										style="color:#7f54b3;display:block;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">
																										<?php echo wp_kses_post($admin_h2_message); ?>
																									</h2>

																									<div
																										style="margin-bottom:40px">
																										<table cellspacing="0"
																											cellpadding="6"
																											border="1"
																											style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif"
																											width="100%">
																											<thead>
																												<tr>
																													<th scope="col"
																														style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
																														align="left">
																														<?php echo esc_html__('Product', 'woocommerce') ?>
																													</th>
																													<th scope="col"
																														style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
																														align="left">
																														<?php echo esc_html__('Quantity', 'woocommerce') ?>
																													</th>
																													<th scope="col"
																														style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
																														align="left">
																														<?php echo esc_html__('Price', 'woocommerce') ?>
																													</th>
																												</tr>
																											</thead>
																											<tbody>
																												<?php foreach ($added_products_to_quote as $quote_key => $current_product_quote_detail) {

																													if (!is_array($current_product_quote_detail)) {
																														continue;
																													}

																													if (!isset($current_product_quote_detail['product_id']) || empty($current_product_quote_detail['product_id'])) {
																														continue;
																													}



																													$current_rule_id = isset($current_product_quote_detail['current_rule_id']) ? ($current_product_quote_detail['current_rule_id']) : 0;

																													$product_id = ($current_product_quote_detail['product_id']);

																													$variation_id = isset($current_product_quote_detail['variation_id']) ? ($current_product_quote_detail['variation_id']) : 0;

																													$quantity = isset($current_product_quote_detail['quantity']) && $current_product_quote_detail['quantity'] >= 1 ? ($current_product_quote_detail['quantity']) : 1;

																													$available_qty = isset($current_product_quote_detail['available_qty']) ? ($current_product_quote_detail['available_qty']) : '';

																													if ('-1' === (string) $current_product_quote_detail['available_qty']) {
																														$available_qty = '';
																													}

																													$custom_price = isset($current_product_quote_detail['custom_price']) ? ($current_product_quote_detail['custom_price']) : '';

																													$product = wc_get_product($variation_id > 1 ? $variation_id : $product_id);

																													$product_permalink = wc_placeholder_img_src();

																													if (!empty(get_the_post_thumbnail_url($variation_id, 'post-thumbnail'))) {

																														$product_permalink = get_the_post_thumbnail_url($variation_id, 'post-thumbnail');

																													} else if (!empty(get_the_post_thumbnail_url($product_id, 'post-thumbnail'))) {

																														$product_permalink = get_the_post_thumbnail_url($product_id, 'post-thumbnail');

																													}

																													?>
																													<tr>
																														<td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word"
																															align="left">
																															<a
																																href="<?php echo get_permalink($product_id); ?>">
																																<?php echo esc_attr($variation_id > 1 ? get_the_title($variation_id) : get_the_title($product_id)); ?>
																															</a>
																														</td>
																														<td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif"
																															align="left">
																															<?php echo esc_attr($quantity); ?>
																														</td>
																														<td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif"
																															align="left">
																															<?php
																															if (!empty($custom_price) && $custom_price >= 0.1) {
																																echo wp_kses_post(wc_price($custom_price * $quantity));
																															} else {
																																echo wp_kses_post(wc_price($product->get_price() * $quantity));

																															}
																															?>
																														</td>
																													</tr>

																												<?php } ?>

																											</tbody>
																											<tfoot>
																												<tr>
																													<th scope="row"
																														colspan="2"
																														style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px"
																														align="left">
																														<?php echo esc_html__('Subtotal:', 'woocommerce'); ?>
																													</th>
																													<td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px"
																														align="left">
																														<?php echo wp_kses_post(wc_price(wc_get_quote_subtotal_ct($new_post_id))); ?>
																													</td>
																												</tr>
																												<tr>
																													<th scope="row"
																														colspan="2"
																														style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
																														align="left">
																														<?php echo esc_html__('Total:', 'woocommerce'); ?>
																													</th>
																													<td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
																														align="left">
																														<?php echo wp_kses_post(wc_price(wc_get_quote_total_ct($new_post_id))); ?>
																													</td>
																												</tr>
																											</tfoot>
																										</table>
																									</div>

																								</div>
																							</td>
																						</tr>
																					</tbody>
																				</table>

																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<div class="yj6qo"></div>
			<div class="adL"></div>
			<div class="adL">
			</div>
		</div>
		<?php


		$admin_order_content_for_message = ob_get_clean();

		wp_mail($to, $subject, $admin_order_content_for_message, $headers);


		$billing_fields = get_posts([
			'post_type' => 'ct-rfq-quote-fields',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'meta_query' => [
				[
					'key' => 'ct_rfq_quote_fields_show_field_with',
					'value' => 'in_billing_fields',
				]
			]
		]);

		$shipping_fields = get_posts([
			'post_type' => 'ct-rfq-quote-fields',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'meta_query' => [
				[
					'key' => 'ct_rfq_quote_fields_show_field_with',
					'value' => 'in_shipping_fields',
				]
			]
		]);

		$shipping_fields = array_merge($shipping_fields, $billing_fields);
		$billing_profile = isset($_POST['billing-profile']) ? sanitize_text_field($_POST['billing-profile']) : '';
		$billing_profile = isset($_POST['shipping-profile']) ? sanitize_text_field($_POST['shipping-profile']) : '';

		update_post_meta($new_post_id, 'billing-profile', $billing_profile);
		update_post_meta($new_post_id, 'shipping-profile', $billing_profile);
		foreach ($shipping_fields as $current_billing_post_id) {

			$post_data = isset($_POST[$current_billing_post_id]) ? sanitize_text_field($_POST[$current_billing_post_id]) : '';

			if ('file_upload' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
				if (isset($_FILES[$current_billing_post_id]) && !empty($_FILES[$current_billing_post_id]['name'])) {
					$file = sanitize_meta('', $_FILES[$current_billing_post_id], '');
					$post_data = ct_rfq_handle_file_upload($file);
				}
			}

			if ('multi_select' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true) || 'multi_checkbox' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {

				$post_data = isset($_POST[$current_billing_post_id]) ? sanitize_meta('', $_POST[$current_billing_post_id], '') : [''];

			}

			update_post_meta($new_post_id, $current_billing_post_id, $post_data);

		}

		ct_rfq_create_pdf([$new_post_id]);



		CT_RFQ_UPLOAD_DIR . 'user/quote-pdf-' . $new_post_id . 'pdf';

		// wc_print_notice($notice,'success');
		// wc()->session->set('cloud_tech_quote_cart',[]);

	}

}


// Start session for guest user
function cloud_tech_request_a_quote_start_guest_session()
{
	if (!is_user_logged_in() && !session_id()) {
		session_start();
	}
	if (function_exists('WC') && !WC()->session->has_session()) {
		WC()->session->set_customer_session_cookie(true);
	}
}
add_action('wp_footer', 'cloud_tech_request_a_quote_start_guest_session');