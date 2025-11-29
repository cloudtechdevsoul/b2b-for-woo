<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
</head>

<body>

	<div id="addify_quote_items_container" style="">

		<div class="afrfq_info_div" style="font-family: sans-serif!important;">
			<div class="af_rfq_company_information"
				style="border-bottom:2px solid <?php echo esc_attr($afrfq_backrgound_color); ?>;">
				<div class="Qoute"
					style="font-size:22px; display:inline-block;width:70%; line-height:32px; vertical-align:middle;">

					<h1 style="font-size:19px;">
						<?php echo esc_attr($site_name); ?>
					</h1>
				</div>
				<div class="afrfq_company_logo_preview"
					style="text-align:right; display:inline-block;width:25%;vertical-align:middle;">
					<?php if ($site_url): ?>
							<img src="<?php echo esc_url($site_url); ?>" alt="Company Logo"
								style="width: 60px; margin-right: 10px; display: inline-block; vertical-align:middle;" />
					<?php endif; ?>
				</div>
			</div>

			<div class="afrfq_company_info_sub_details" style="">
				<p style="font-size:13px;"><strong style="font-size:13px;">
						<?php echo esc_html('Address:', 'addify_rfq'); ?>
					</strong>
					<?php echo esc_attr($address); ?>
				</p>
				<p style="font-size:13px;"><strong style="font-size:13px;">
						<?php echo esc_html('Email:', 'addify_rfq'); ?>
					</strong>
					<?php
					$af_rfq_email_array_pdf = explode(',', $admin_email);
					$iteration = 1; // Initialize an iteration counter.
					
					foreach ($af_rfq_email_array_pdf as $email) {
						if ($iteration > 1) {
							// Apply margin-left to the email address starting from the second iteration.
							echo '<span style="margin-top:8px; margin-left: 43px;">' . esc_attr($email) . '</span><br>';
						} else {
							echo esc_attr($email) . '<br>'; // No margin-left for the first iteration.
						}
						$iteration++; // Increment the iteration counter.
					}
					?>
				</p>
			</div>
			<br>
			<br>
			<div class=""
				style="margin-top:35px; border-bottom:2px solid <?php echo esc_attr($afrfq_backrgound_color); ?>;">

				<div class="afrfq-quote-detail" style="display:inline-block; width:30%">
					<h1 style="font-size:19px;">
						<?php echo esc_html('Quote', 'addify_rfq'); ?>
					</h1>
					<p style="font-size:13px;"><strong style="margin-right:8px; font-size:13px;">
							<?php echo esc_html('Qoute ID:', 'addify_rfq'); ?>
						</strong> <span style="">
							<?php echo esc_attr($qoute_id); ?>
						</span></p>
					<p style="font-size:13px;"><strong style="margin-right:8px; font-size:13px;">
							<?php echo esc_html('Qoute Status:', 'addify_rfq'); ?>
						</strong> <span style="">
							<?php echo esc_attr($ct_rfq_qoute_status); ?>
						</span></p>
					<p style="font-size:13px;"><strong style="margin-right:8px; font-size:13px;">
							<?php echo esc_html('Qoute Date:', 'addify_rfq'); ?>
						</strong> <span style=" ">
							<?php echo esc_attr(gmdate('Y-m-d', strtotime(get_post_field('post_date', $qoute_id)))); ?>
						</span></p>
				</div>
			</div>
		</div>

		<table cellpadding="0" cellspacing="0"
			style="margin-top:10px; border-collapse: collapse; width:100%; font-family: sans-serif!important;"
			id="addify_quote_items_table" class="woocommerce_order_items addify_quote_items">
			<thead style="">
				<tr style="border-bottom:1px solid #d3d3d3a3;">

					<?php if (in_array('thumbnail', (array) $psd_csv_coulmn)) { ?>

							<th style="padding:7px 20px 15px;  font-size: 14px; font-family:sans-serif; width: 20%;"
								class="item_cost sortable" data-sort="float">
								<?php esc_html_e('Image', 'cloud_tech_rfq'); ?>
							</th>
					<?php }

					if (in_array('item', (array) $psd_csv_coulmn)) { ?>

							<th style="padding:7px 20px 15px;  font-size: 14px; font-family:sans-serif; width: 20%;"
								class="item_cost sortable" data-sort="float">
								<?php esc_html_e('Item', 'cloud_tech_rfq'); ?>
							</th>
					<?php }

					if (in_array('price', (array) $psd_csv_coulmn)) { ?>
							<th style="padding:7px 20px 15px;  font-size: 14px; font-family:sans-serif; width: 20%;"
								class="item_cost sortable" data-sort="float">
								<?php esc_html_e('Item Price', 'cloud_tech_rfq'); ?>
							</th>
					<?php }


					if (in_array('quantity', (array) $psd_csv_coulmn)) { ?>
							<th style="padding:7px 20px 15px;  font-size: 14px; font-family:sans-serif; width: 20%;"
								class="item_cost sortable" data-sort="float">
								<?php esc_html_e('Quantity', 'cloud_tech_rfq'); ?>
							</th>
					<?php }

					if (in_array('quantity', (array) $psd_csv_coulmn)) { ?>
							<th style="padding:7px 20px 15px;  font-size: 14px; font-family:sans-serif; width: 20%;"
								class="item_cost sortable" data-sort="float">
								<?php esc_html_e('Subtotal', 'cloud_tech_rfq'); ?>
							</th>
					<?php }
					if (in_array('offered_price', (array) $psd_csv_coulmn)) { ?>
							<th style="padding:10px 20px; font-family:sans-serif; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>; font-size: 14px; width:10%;"
								class="quantity sortable" data-sort="int">
								<?php esc_html_e('Offered price', 'cloud_tech_rfq'); ?>
							</th>
					<?php }
					if (in_array('offered_subtotal', (array) $psd_csv_coulmn)) { ?>
							<th style="padding:10px 20px; font-family:sans-serif; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>; font-size: 14px; width:10%;"
								class="quantity sortable" data-sort="int">
								<?php esc_html_e('Offered Subtotal', 'cloud_tech_rfq'); ?>
							</th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php
				$offered_total = 0;
				foreach ($quote_contents as $quote_key => $current_product_quote_detail) {

					if (!is_array($current_product_quote_detail)) {
						continue;
					}

					if (!isset($current_product_quote_detail['product_id']) || empty($current_product_quote_detail['product_id'])) {
						continue;
					}

					$product_id = ($current_product_quote_detail['product_id']);

					$variation_id = isset($current_product_quote_detail['variation_id']) ? ($current_product_quote_detail['variation_id']) : 0;

					$quantity = isset($current_product_quote_detail['quantity']) && $current_product_quote_detail['quantity'] >= 1 ? ($current_product_quote_detail['quantity']) : 1;

					$custom_price = isset($current_product_quote_detail['custom_price']) ? (int) ($current_product_quote_detail['custom_price']) : 0;

					$product = wc_get_product($variation_id > 1 ? $variation_id : $product_id);

					$product_permalink = wc_placeholder_img_src();

					if (!empty(get_the_post_thumbnail_url($variation_id, 'post-thumbnail'))) {

						$product_permalink = get_the_post_thumbnail_url($variation_id, 'post-thumbnail');

					} else if (!empty(get_the_post_thumbnail_url($product_id, 'post-thumbnail'))) {
						$product_permalink = get_the_post_thumbnail_url($product_id, 'post-thumbnail');
					}
					$price = $product->get_price();

					$offered_total += $custom_price >= 1 ? $custom_price * $quantity : $price * $quantity;

					?>
						<tr style="border-bottom:1px solid #d3d3d3a3;" class="item"
							data-order_item_id="<?php echo esc_attr($item_id); ?>">

							<?php if (in_array('thumbnail', (array) $psd_csv_coulmn)) { ?>
									<td class="thumb" style="text-align:left; padding: 15px 20px; width: 20%;">
										<img width="40px; " src="<?php echo esc_url($product_permalink); ?>" alt="Product Image" />
									</td>
							<?php } ?>
							<?php if (in_array('item', (array) $psd_csv_coulmn)) { ?>
									<td class="thumb" style="text-align:left; padding: 15px 20px; width: 20%;">
										<?php echo esc_attr($product->get_name()); ?>
										<br>
										<?php echo wp_kses_post('<div class="wc-quote-item-sku" style="font-size:12x; margin-top:10px;"><strong style="font-size:12px;">' . esc_html__('SKU:', 'cloud_tech_rfq') . '</strong> ' . esc_attr($product->get_sku()) . '</div>'); ?>
									</td>

							<?php } ?>
							<?php if (in_array('price', (array) $psd_csv_coulmn)) { ?>
									<td class="thumb" style="text-align:left; padding: 15px 20px; width: 20%;">
										<?php echo wp_kses_post(get_woocommerce_currency_symbol() . ' ' . wc_format_decimal($price, wc_get_price_decimals())); ?>
									</td>

							<?php } ?>
							<?php if (in_array('quantity', (array) $psd_csv_coulmn)) { ?>
									<td class="thumb" style="text-align:left; padding: 15px 20px; width: 20%;">
										<?php echo esc_attr($quantity); ?>
									</td>
							<?php } ?>

							<?php if (in_array('subtotal', (array) $psd_csv_coulmn)) { ?>
									<td class="thumb" style="text-align:left; padding: 15px 20px; width: 20%;">
										<?php echo wp_kses_post(get_woocommerce_currency_symbol() . ' ' . wc_format_decimal($price * $quantity, wc_get_price_decimals())); ?>
									</td>
							<?php } ?>
							<?php if (in_array('offered_price', (array) $psd_csv_coulmn)) { ?>
									<td class="thumb" style="text-align:left; padding: 15px 20px; width: 20%;">
										<?php echo wp_kses_post(get_woocommerce_currency_symbol() . ' ' . wc_format_decimal($custom_price, wc_get_price_decimals())); ?>
									</td>
							<?php } ?>
							<?php if (in_array('offered_subtotal', (array) $psd_csv_coulmn)) { ?>
									<td class="thumb" style="text-align:left; padding: 15px 20px; width: 20%;">
										<?php echo wp_kses_post(get_woocommerce_currency_symbol() . ' ' . wc_format_decimal($custom_price * $quantity, wc_get_price_decimals())); ?>
									</td>
							<?php } ?>


						</tr>

				<?php } ?>
			</tbody>
		</table>
		<?php
		?>
		<table cellpadding="0" cellspacing="0"
			style=" border-collapse: collapse; width:44%; font-family: sans-serif!important; margin:auto 0 auto auto	;"
			id="addify_quote_items_table" class="woocommerce_order_items addify_quote_items">
			<tbody>
				<?php if (in_array('quote_subtotal', (array) $psd_csv_coulmn)) { ?>
						<tr style="font-family:sans-serif;">

							<td></td>
							<td></td>
							<td></td>
							<td
								style="padding: 10px 25px; padding-bottom:30px;    font-size: 14px; text-align:left;  font-weight:bold;  border: none!important; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>;">
								<?php echo esc_html('Qoute Subtotal', 'addify_rfq'); ?>
							</td>
							<td
								style="padding: 5px; padding-bottom:30px;   font-size: 14px;  text-align:right; border: none!important; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>;">
								<?php echo esc_attr(get_woocommerce_currency_symbol() . ' ' . wc_get_quote_subtotal_ct($qoute_id)); ?>
							</td>
						</tr>
				<?php } ?>


				<?php if (in_array('quote_offered_total', (array) $psd_csv_coulmn)) {
					?>
						<tr style="font-family:sans-serif;">

							<td></td>
							<td></td>
							<td></td>
							<td
								style="padding: 10px 25px; padding-bottom:30px;    font-size: 14px; text-align:left;  font-weight:bold;  border: none!important; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>;">
								<?php echo esc_html('Qoute Offered Total', 'addify_rfq'); ?>
							</td>
							<td
								style="padding: 5px; padding-bottom:30px;   font-size: 14px;  text-align:right; border: none!important; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>;">
								<?php echo esc_attr(get_woocommerce_currency_symbol() . ' ' . $offered_total); ?>
							</td>
						</tr>
				<?php } ?>


				<?php if (in_array('quote_total_tax', (array) $psd_csv_coulmn)) {
					?>
						<tr style="font-family:sans-serif;">

							<td></td>
							<td></td>
							<td></td>
							<td
								style="padding: 10px 25px; padding-bottom:30px;    font-size: 14px; text-align:left;  font-weight:bold;  border: none!important; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>;">
								<?php echo esc_html('Qoute Total Tax', 'addify_rfq'); ?>
							</td>
							<td
								style="padding: 5px; padding-bottom:30px;   font-size: 14px;  text-align:right; border: none!important; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>;">
								<?php echo esc_attr(get_woocommerce_currency_symbol() . ' ' . wc_get_quote_tax_ct($qoute_id)); ?>
							</td>
						</tr>
				<?php } ?>


				<?php if (in_array('quote_total_total', (array) $psd_csv_coulmn)) {
					?>
						<tr style="font-family:sans-serif;">

							<td></td>
							<td></td>
							<td></td>
							<td
								style="padding: 10px 25px; padding-bottom:30px;    font-size: 14px; text-align:left;  font-weight:bold;  border: none!important; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>;">
								<?php echo esc_html('Qoute Total', 'addify_rfq'); ?>
							</td>
							<td
								style="padding: 5px; padding-bottom:30px;   font-size: 14px;  text-align:right; border: none!important; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>;">
								<?php echo esc_attr(get_woocommerce_currency_symbol() . ' ' . wc_get_quote_total_ct($qoute_id)); ?>
							</td>
						</tr>
				<?php } ?>

			</tbody>
		</table>
		<?php if (get_option('ct_rfq_enable_term_and_conditions')) { ?>
				<div class="afrfq_client_info_details"
					style="font-family:sans-serif; border-top:2px solid <?php echo esc_attr($afrfq_backrgound_color); ?>;">
					<h1 style="font-family:sans-serif; margin-bottom:0px; font-size:18px; line-height:28px;">
						<?php echo esc_html('Terms & Conditions', 'addify_rfq'); ?>
					</h1>
					<ul style="font-family:sans-serif; margin:0px; padding:15px;">
						<li>
							<?php echo wp_kses_post(get_option('ct_rfq_term_and_condition_data')); ?>
						</li>
					</ul>
				</div>
		<?php } ?>
	</div>

	</div>
</body>

</html>