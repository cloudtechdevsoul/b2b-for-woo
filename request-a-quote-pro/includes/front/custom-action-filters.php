<?php

if (!defined('WPINC')) {
	exit();
}


add_filter('cloud_tech_product_available_qty', 'cloud_tech_product_available_qty_func', 10, 1);

function cloud_tech_product_available_qty_func($current_product)
{

	$prd_qty = '';

	if (!empty($current_product->get_stock_quantity())) {
		$prd_qty = $current_product->get_stock_quantity();
	}
	if (!empty($current_product->get_max_purchase_quantity())) {
		$prd_qty = $current_product->get_max_purchase_quantity();
	}
	if (!empty($current_product->get_sold_individually())) {
		$prd_qty = $current_product->get_sold_individually();
	}

	return $prd_qty;

}


add_filter('cloud_tech_item_detail', 'cloud_tech_item_detail', 10, 2);

function cloud_tech_item_detail($post_id = 0, $show_data = '')
{


	if ($post_id >= 1) {
		$added_products_to_quote = (array) get_post_meta($post_id, 'cloud_tech_quote_cart', true);
		$added_products_to_quote = ct_rfq_custom_array_filter($added_products_to_quote);
		?>
																<table class="wp-list-table widefat fixed striped table-view-list customers dataTable">
																	<thead>
																		<tr>
																			<th class="product-thumbnail"><?php echo esc_html__('Thumbnail image', 'woocommerce'); ?></th>
																			<th class="product-name"><?php echo esc_html__('Product', 'woocommerce'); ?></th>
																			<th class="product-price"><?php echo esc_html__('Price', 'woocommerce'); ?></th>
																			<th class="product-price"><?php echo esc_html__('Custom Price', 'woocommerce'); ?></th>
																			<th class="product-quantity"><?php echo esc_html__('Quantity', 'woocommerce'); ?></th>
																			<th class="product-subtotal"><?php echo esc_html__('Subtotal', 'woocommerce'); ?></th>
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

																			?>
																										<tr>

																											<td>
																												<?php

																												$product_permalink = wc_placeholder_img_src();

																												if (!empty(get_the_post_thumbnail_url($variation_id, 'post-thumbnail'))) {

																													$product_permalink = get_the_post_thumbnail_url($variation_id, 'post-thumbnail');

																												} else if (!empty(get_the_post_thumbnail_url($product_id, 'post-thumbnail'))) {

																													$product_permalink = get_the_post_thumbnail_url($product_id, 'post-thumbnail');

																												}
																												?>
																												<img fetchpriority="high" decoding="async" width="100" height="100"
																												src="<?php echo esc_url($product_permalink); ?>"
																												class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt=""
																												srcset="<?php echo esc_url($product_permalink); ?>" sizes="(max-width: 324px) 100vw, 324px">

																											</td>

																											<td>
																												<a
																												href="<?php echo get_permalink($product_id); ?>"><?php echo esc_attr($variation_id > 1 ? get_the_title($variation_id) : get_the_title($product_id)); ?></a>
																											</td>

																											<td><?php echo wp_kses_post(wc_price($product->get_price())); ?></td>
																											<td>

																												<div class="quantity">
																													<input min="0" style="width:100px;" type="number" class="qty"
																													name="custom_amount_<?php echo esc_attr($quote_key); ?>"
																													value="<?php echo esc_attr($custom_price); ?>">
																												</div>
																											</td>
																											<td>
																												<div class="quantity">
																													<input min="1" style="width:100px;" type="number" class="qty"
																													name="quantity_<?php echo esc_attr($quote_key); ?>" value="<?php echo esc_attr($quantity); ?>">
																												</div>
																											</td>
																											<td>
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
																</table>
																<?php

	} else {
		$added_products_to_quote = (array) wc()->session->get('cloud_tech_quote_cart');
		$added_products_to_quote = ct_rfq_custom_array_filter($added_products_to_quote);
		?>
																<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
																	<thead>
																		<tr>
																			<th class="product-remove"><span
																				class="screen-reader-text"><?php echo esc_html__('Remove item', 'woocommerce'); ?></span></th>
																				<th class="product-thumbnail"><span
																					class="screen-reader-text"><?php echo esc_html__('Thumbnail image', 'woocommerce'); ?></span></th>
																					<th class="product-name"><?php echo esc_html__('Product', 'woocommerce'); ?></th>
																					<th class="product-price"><?php echo esc_html__('Price', 'woocommerce'); ?></th>
																					<th class="product-price"><?php echo esc_html__('Custom Price', 'woocommerce'); ?></th>
																					<th class="product-quantity"><?php echo esc_html__('Quantity', 'woocommerce'); ?></th>
																					<th class="product-subtotal"><?php echo esc_html__('Subtotal', 'woocommerce'); ?></th>
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


																					$price_text = get_post_meta($current_rule_id, 'ct_rfq_replace_price_text', true);
																					$hide_or_replace = get_post_meta($current_rule_id, 'ct_rfq_hide_price_or_replace_text', true);
																					$product = wc_get_product($variation_id > 1 ? $variation_id : $product_id);

																					?>
																												<tr class="woocommerce-cart-form__cart-item">

																													<td class="product-remove"> <span class="fa fa-close cloud-tech-remove-button"
																														data-quote_key="<?php echo esc_attr($quote_key); ?>"></span> </td>

																														<td class="product-thumbnail">
																															<?php

																															$product_permalink = wc_placeholder_img_src();

																															if (!empty(get_the_post_thumbnail_url($variation_id, 'post-thumbnail'))) {

																																$product_permalink = get_the_post_thumbnail_url($variation_id, 'post-thumbnail');

																															} else if (!empty(get_the_post_thumbnail_url($product_id, 'post-thumbnail'))) {

																																$product_permalink = get_the_post_thumbnail_url($product_id, 'post-thumbnail');

																															}
																															?>
																															<a href="<?php echo esc_url($product_id); ?>"><img fetchpriority="high" decoding="async" width="324"
																																height="324" src="<?php echo esc_url($product_permalink); ?>"
																																class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt=""
																																srcset="<?php echo esc_url($product_permalink); ?>" sizes="(max-width: 324px) 100vw, 324px"></a>
																															</td>

																															<td class="product-name" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
																																<a
																																href="<?php echo get_permalink($product_id); ?>"><?php echo esc_attr($variation_id > 1 ? get_the_title($variation_id) : get_the_title($product_id)); ?></a>
																															</td>

																															<td class="product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
																																<?php
																																$price = wc_price($product->get_price());

																																if (str_contains($hide_or_replace, 'hide') && !str_contains($hide_or_replace, 'product_page')) {
																																	$price = '';
																																} else if (str_contains($hide_or_replace, 'replace') && !str_contains($hide_or_replace, 'product_page')) {
																																	$price = $price_text;

																																}

																																echo wp_kses_post($price);

																																?>
																															</td>
																															<td class="product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
																																<?php if (!empty(get_post_meta($current_rule_id, 'ct_rfq_can_user_add_custom_amount', true))) { ?>
																																								<input type="number" class="input-text qty text"
																																								name="cloud_tech_custom_price<?php echo esc_attr($quote_key); ?>"
																																								value="<?php echo esc_attr($custom_price); ?>" min="0"> <?php } ?>
																																</td>
																																<td>
																																	<input type="number" min="1" class="input-text qty text"
																																	name="cloud_tech_quote_qty<?php echo esc_attr($quote_key); ?>"
																																	value="<?php echo esc_attr($quantity); ?>" max="<?php echo esc_attr($available_qty); ?>">
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
																					<?php
	}





}

add_filter('cloud_tech_quote_billing_detail', 'cloud_tech_quote_billing_detail', 10, 1);

function cloud_tech_quote_billing_detail($post_id = 0)
{


	if ($post_id >= 1) { ?>
																					<h4> <?php echo esc_html__('Billing Details', 'cloud_tech_rfq'); ?> </h4>
																					<style>
																						.second-template .af-rfq-profile-checkout img {
																							width: 48px !important;
																							height: 38px !important;
																							object-fit: cover;
																							margin: 0px auto !important;
																						}

																						.af-heading-pos i {
																							margin-right: 16px;
																							margin-bottom: 0;
																						}

																						.af-rfq-profile-mode label:first-child {
																							border-top-left-radius: 10px;
																							border-bottom-left-radius: 10px;
																						}

																						.af-rfq-profile-mode label:last-child {
																							border-top-right-radius: 10px;
																							border-bottom-right-radius: 10px;
																						}

																						.af-heading-pos {
																							display: flex;
																							align-items: center;
																							padding: 10px;
																							background: #ffdd44;
																						}

																						.rqs-second-template {
																							display: flex;
																							justify-content: space-between;
																						}

																						.af-rfq-checkout-wrap {
																							width: 48%;
																						}

																						.second-template table tr {
																							display: flex;
																							flex-wrap: wrap;
																							justify-content: space-between;
																						}

																						.second-template table tr td {
																							width: 100%;
																							padding: 0 0px 5px !important;
																						}

																						.second-template table tr td:nth-child(5),
																						.second-template table tr td:nth-child(6),
																						.second-template table tr td:nth-child(7) {
																							width: 100%;
																						}

																						.second-template table tr td label {
																							display: block;
																							font-size: 13px;
																							line-height: 22px;
																							font-weight: 600;
																							color: #000;
																							margin-bottom: 8px;
																						}

																						.second-template table tr td input,
																						.second-template table tr td select,
																						.second-template table tr td .selection .select2-selection.select2-selection--single {
																							width: 100%;
																							height: 46px !important;
																							box-shadow: none;
																							background: #fff !important;
																							border-radius: 10px !important;
																							padding: 7px 10px !important;
																							font-size: 14px !important;
																							border: 1px solid #d3d3d345 !important;
																							margin-bottom: 20px !important;
																							line-height: 24px !important;
																						}

																						.second-template .select2-container--default .select2-selection--single .select2-selection__arrow {
																							top: 9px;
																							right: 6px;
																						}

																						.second-template table tr td textarea {
																							background: #fff;
																							border: 1px solid #d3d3d345;
																							border-radius: 10px !important;
																							box-shadow: none;
																							height: 190px;
																							font-size: 14px;
																							line-height: 24px;
																							margin-bottom: 25px;
																						}

																						.second-template table tr td label.radio {
																							border: 1px solid #d3d3d345 !important;
																							padding: 10px;
																							position: relative;
																							text-align: center;
																							width: 46%;
																						}

																						.second-template table tr td label input[type=radio] {
																							position: absolute;
																							width: 100%;
																							height: 100% !important;
																							top: 0;
																							opacity: 0;
																							left: 0;
																						}

																						.rsq-radio-btn-wrap {
																							display: flex;
																							justify-content: space-between;
																						}

																						.second-template .af-rfq-profile-mode {
																							justify-content: flex-start !important;
																							margin: 30px 0 !important;

																						}

																						.second-template .af-rfq-rent-period h3,
																						.second-template .af-rfq-profile-checkout h3 {
																							font-weight: 700;
																							color: #000;
																							font-size: 20px !important;
																							line-height: 30px !important;
																							margin: 0;
																						}

																						.second-template .af-rfq-profile-mode label {
																							border: 1px solid #d3d3d345;
																							padding: 9px;
																							display: block;
																							width: 120px;
																							font-size: 14px;
																							line-height: 21px;
																							font-weight: 600;
																							background: transparent;
																							color: #000;
																							position: relative;
																						}

																						.second-template {
																							padding: 20px 0;
																							border-bottom: 1px solid #d3d3d34d;
																						}

																						.rqs-shiping {
																							border-bottom: none;
																							padding-bottom: 0 !important;
																						}

																						.second-template-btn {
																							background: #ffdd44 !important;
																							font-size: 16px;
																							line-height: 24px;
																							padding: 9px 24px !important;
																							border-radius: 10px !important;
																							color: #000;
																						}

																						.second-template {
																							padding: 0 !important;
																							border: none !important;
																						}

																						.template-heading h2 {
																							font-size: 30px;
																							font-weight: 700;
																							line-height: 40px;
																							text-align: center;
																							margin-bottom: 30px;
																						}
																					</style>
																					<?php

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
																					if (!empty(get_post_meta($post_id, 'current_user_email', true))) { ?>
												<input type="email" style="display:none;" name="current_user_email"
													value="<?php echo esc_attr(get_post_meta($post_id, 'current_user_email', true)); ?>">
																					<?php } ?>

																				<section class="af-rfq-custom-form">
																					<div class="container">
																						<div class="template-heading">
																							<h2>Checkout</h2>
																						</div>
																						<div class="rqs-second-template">

																							<div class="af-rfq-checkout-wrap">

																								<?php if (count($billing_fields) >= 1) { ?>
																																<div class="af-rfq-checkout-form second-template">
																																	<div class="af-rfq-profile-checkout">
																																		<div class="af-heading-pos">
																																			<i class="af-rfq-billing-icon"><?php
																																			$icon_or_image = !empty(get_option('ct_rfq_billing_logos')) ? '<img style="width:40px;height:40px;" src="' . esc_url(get_option('ct_rfq_billing_logos')) . '" srcset="' . esc_url(get_option('ct_rfq_billing_logos')) . '" />' : '<i class="fa fa-solid fa fa-user"></i>';
																																			echo wp_kses_post($icon_or_image); ?></i>
																																			<h3><?php echo esc_html__('Billing Detail', 'cloud_tech_rfq'); ?></h3>
																																		</div>
																																		<div class="af-rfq-profile-mode">
																																			<label><input type="radio" name="billing-profile"
																																				<?php echo esc_attr('private' == get_post_meta($post_id, 'billing-profile', true) ? 'checked' : ''); ?>
																																				class="billing-profile billing-profile-private"
																																				value="private"><?php echo esc_html__('Private', 'cloud_tech_rfq'); ?></label>
																																				<label><input type="radio" name="billing-profile"
																																					<?php echo esc_attr('company' == get_post_meta($post_id, 'billing-profile', true) ? 'checked' : ''); ?>
																																					class="billing-profile billing-profile-company"
																																					value="company"><?php echo esc_html__('Company', 'cloud_tech_rfq'); ?></label>
																																				</div>
																																			</div>
																																			<table>
																																				<tr>
																																					<?php foreach ($billing_fields as $current_billing_post_id) {
																																						$show_with = '';
																																						if ('company' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_show_field_in_private_company', true)) {
																																							$show_with = 'ct-rfq-billing-fields-with-company';
																																						}
																																						if ('private' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_show_field_in_private_company', true)) {
																																							$show_with = 'ct-rfq-billing-fields-with-private';
																																						}
																																						$default_value = get_post_meta($post_id, $current_billing_post_id, true);
																																						$placeholder_holder_value = get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_placeholder', true);
																																						$additonal_classes = get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_additonal_class', true);
																																						?>

																																													<td class="<?php echo esc_attr($show_with); ?>">
																																														<label
																																														for="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php echo esc_attr(get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_label', true)); ?></label>
																																														<?php
																																														if ('date' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																															?><input class="<?php echo esc_attr($additonal_classes); ?>" placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"value="<?php echo esc_attr($default_value); ?>" type="date"name="<?php echo esc_attr($current_billing_post_id); ?>"><?php
																																														}
																																														if ('time' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																															?>
																																																						<input class="<?php echo esc_attr($additonal_classes); ?>"
																																																						placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																																						value="<?php echo esc_attr($default_value); ?>" type="time"name="<?php echo esc_attr($current_billing_post_id); ?>">
																																																						<?php
																																														}
																																														if ('file_upload' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {

																																															if (!empty($default_value)) {

																																																$attachment_url = wp_get_attachment_url($default_value);
																																																if ($attachment_url) {
																																																	?><a href="<?php echo esc_url($attachment_url); ?>" class="fa fa-eye"></a><?php
																																																}
																																															}
																																														}
																																														if ('select' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {

																																															?><select class="ct-rfq-live-search <?php echo esc_attr($additonal_classes); ?>"
																																																						placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																																						id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php

																																																						   foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
																																																							   if (is_array($value)) {
																																																								   ?><option <?php echo esc_attr(in_array(isset($value['option_value']) ? $value['option_value'] : '', (array) $default_value) ? 'selected' : ''); ?>
																																																																						value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>">
																																																																						<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																																																																						</option><?php
																																																							   }
																																																						   }
																																																						   ?></select><?php


																																														}
																																														if ('multi_select' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																															?><select multiple class="ct-rfq-live-search <?php echo esc_attr($additonal_classes); ?>"
																																																						placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"name="<?php echo esc_attr($current_billing_post_id); ?>[]"
																																																						id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php

																																																						   foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
																																																							   if (is_array($value)) {
																																																								   ?><option <?php echo esc_attr(in_array(isset($value['option_value']) ? $value['option_value'] : '', (array) $default_value) ? 'selected' : ''); ?>
																																																																						value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>">
																																																																						<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																																																																						</option><?php
																																																							   }
																																																						   }
																																																						   ?></select><?php
																																														}
																																														if ('radio' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) { ?>
																																																						<div class="rsq-radio-btn-wrap">
																																																							<?php
																																																							foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
																																																								if (is_array($value)) {
																																																									?>
																																																																							<label class="radio">
																																																																								<input class="<?php echo esc_attr($additonal_classes); ?>"
																																																																								placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																																																								<?php checked($default_value, isset($value['option_value']) ? $value['option_value'] : ''); ?>
																																																																								type="radio" name="<?php echo esc_attr($current_billing_post_id); ?>"
																																																																								value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>">
																																																																								<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																																																																							</label>
																																																																							<?php
																																																								}
																																																							} ?>
																																																						</div>
																																																						<?php
																																														}
																																														if ('checkbox' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																															?>
																																																						<input class="<?php echo esc_attr($additonal_classes); ?>"
																																																						placeholder="<?php echo esc_attr($placeholder_holder_value); ?>" type="checkbox"
																																																						value="<?php echo esc_attr($current_billing_post_id); ?>"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																																						id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																																						<?php
																																														}
																																														if ('multi_checkbox' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {

																																															foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
																																																if (is_array($value)) {
																																																	?>
																																																																						<input class="<?php echo esc_attr($additonal_classes); ?>"
																																																																						placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																																																						<?php checked($default_value, isset($value['option_value']) ? $value['option_value'] : ''); ?>
																																																																						type="checkbox"
																																																																						value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>"name="<?php echo esc_attr($current_billing_post_id); ?>[]"
																																																																						id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																																																						<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																																																																						<br>
																																																																						<?php
																																																}
																																															}
																																														}
																																														if ('countries' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																															$countries = new WC_Countries();
																																															?>
																																																						<select class="ct-rfq-live-search <?php echo esc_attr($additonal_classes); ?>"
																																																							placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																																							class="ct-rfq-live-search" name="<?php echo esc_attr($current_billing_post_id); ?>"
																																																							id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																																							<?php foreach ($countries->get_countries() as $key => $value) { ?>

																																																															<option
																																																															<?php echo esc_attr(in_array(isset($value['option_value']) ? $value['option_value'] : '', (array) $default_value) ? 'selected' : ''); ?>
																																																															value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value); ?>
																																																														</option>

																																																						<?php } ?>
																																																					</select>
																																																					<?php
																																														}
																																														if ('input' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																															?><input class="<?php echo esc_attr($additonal_classes); ?>"
																																																				placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																																				value="<?php echo esc_attr($default_value); ?>" type="text"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																																				id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php
																																														}
																																														if ('textarea' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																															?><textarea class="<?php echo esc_attr($additonal_classes); ?>"
																																																				placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																																				id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																																																				cols="30" rows="10"><?php echo esc_attr($default_value); ?></textarea><?php
																																														}
																																														if ('number' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																															?><input class="<?php echo esc_attr($additonal_classes); ?>"
																																																			placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																																			value="<?php echo esc_attr($default_value); ?>" type="number" min="1"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																																			id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																																			<?php
																																														}
																																														if ('color' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																															?><input class="<?php echo esc_attr($additonal_classes); ?>"
																																																		placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																																		value="<?php echo esc_attr($default_value); ?>" type="color"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																																		id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																																		<?php
																																														}
																																														if ('email' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																															?><input class="<?php echo esc_attr($additonal_classes); ?>"
																																																	placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																																	value="<?php echo esc_attr($default_value); ?>" type="email"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																																	id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																																	<?php
																																														}
																																														if ('password' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																															?><input class="<?php echo esc_attr($additonal_classes); ?>"
																																																placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																																value="<?php echo esc_attr($default_value); ?>" type="password"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																																id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																																<?php
																																														}
																																														if ('telephone' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																															?><input class="<?php echo esc_attr($additonal_classes); ?>"
																																															placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																															value="<?php echo esc_attr($default_value); ?>" type="tel"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																															id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																															<?php
																																														}
																																														?>
																																						</td>


																														<?php } ?>
																													</tr>
																												</table>

																											</div>
																			<?php } ?>
																		</div>
																		<div class="af-rfq-checkout-wrap">
																			<?php if (count($shipping_fields) >= 1) { ?>

																											<div class="af-rfq-checkout-form second-template rqs-shiping">
																												<div class="af-rfq-profile-checkout">
																													<div class="af-heading-pos">
																														<i class="af-rfq-shipping-icon"><?php
																														$icon_or_image = !empty(get_option('ct_rfq_shipping_logos')) ? '<img style="width:40px;height:40px;" src="' . esc_url(get_option('ct_rfq_shipping_logos')) . '" srcset="' . esc_url(get_option('ct_rfq_billing_logos')) . '" />' : '<i class="fa fa-solid fa fa-user"></i>';
																														echo wp_kses_post($icon_or_image); ?></i>
																														<h3><?php echo esc_html__('Shipping Detail', 'cloud_tech_rfq'); ?></h3>
																													</div>
																													<div class="af-rfq-profile-mode">
																														<label><input type="radio" name="shipping-profile"
																															<?php echo esc_attr('private' == get_post_meta($post_id, 'shipping-profile', true) ? 'checked' : ''); ?>
																															class="shipping-profile shipping-profile-private"
																															value="private"><?php echo esc_html__('Private', 'cloud_tech_rfq'); ?></label>
																															<label><input type="radio" name="shipping-profile"
																																<?php echo esc_attr('company' == get_post_meta($post_id, 'shipping-profile', true) ? 'checked' : ''); ?>
																																class="shipping-profile shipping-profile-company"
																																value="company"><?php echo esc_html__('Delivery', 'cloud_tech_rfq'); ?></label>
																															</div>
																														</div>
																														<table>
																															<tr>
																																<?php foreach ($shipping_fields as $current_billing_post_id) {
																																	$show_with = '';
																																	if ('company' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_show_field_in_private_company', true)) {
																																		$show_with = 'ct-rfq-shipping-fields-with-company';
																																	}
																																	if ('private' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_show_field_in_private_company', true)) {
																																		$show_with = 'ct-rfq-shipping-fields-with-private';
																																	}

																																	$default_value = get_post_meta($post_id, $current_billing_post_id, true);
																																	$placeholder_holder_value = get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_placeholder', true);
																																	$additonal_classes = get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_additonal_class', true);

																																	?>

																																								<td class="<?php echo esc_attr($show_with); ?>">
																																									<label
																																									for="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php echo esc_attr(get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_label', true)); ?></label>
																																									<?php
																																									if ('date' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										?>
																																																	<input class="<?php echo esc_attr($additonal_classes); ?>"
																																																	placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																																	value="<?php echo esc_attr($default_value); ?>" type="date"name="<?php echo esc_attr($current_billing_post_id); ?>">
																																																	<?php
																																									}
																																									if ('time' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										?>
																																																	<input class="<?php echo esc_attr($additonal_classes); ?>"
																																																	placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																																	value="<?php echo esc_attr($default_value); ?>" type="time"name="<?php echo esc_attr($current_billing_post_id); ?>">
																																																	<?php
																																									}
																																									if ('file_upload' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										$attachment_url = wp_get_attachment_url($default_value);
																																										if ($attachment_url) {
																																											?><a href="<?php echo esc_url($attachment_url); ?>" class="fa fa-eye"></a><?php
																																										}
																																									}
																																									if ('select' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {

																																										?><select class="ct-rfq-live-search <?php echo esc_attr($additonal_classes); ?>"
																																																	placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																																	id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php

																																																	   foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
																																																		   if (is_array($value)) {
																																																			   ?><option <?php echo esc_attr(in_array(isset($value['option_value']) ? $value['option_value'] : '', (array) $default_value) ? 'selected' : ''); ?>
																																																																	value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>">
																																																																	<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																																																																	</option><?php
																																																		   }
																																																	   }
																																																	   ?></select><?php


																																									}
																																									if ('multi_select' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										?><select multiple class="ct-rfq-live-search <?php echo esc_attr($additonal_classes); ?>"
																																																	placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"name="<?php echo esc_attr($current_billing_post_id); ?>[]"
																																																	id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php

																																																	   foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
																																																		   if (is_array($value)) {
																																																			   ?><option <?php echo esc_attr(in_array(isset($value['option_value']) ? $value['option_value'] : '', (array) $default_value) ? 'selected' : ''); ?>
																																																																	value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>">
																																																																	<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																																																																	</option><?php
																																																		   }
																																																	   }
																																																	   ?></select><?php
																																									}
																																									if ('radio' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {

																																										foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
																																											if (is_array($value)) {
																																												?>
																																																																	<input class="<?php echo esc_attr($additonal_classes); ?>"
																																																																	placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																																																	<?php checked($default_value, isset($value['option_value']) ? $value['option_value'] : ''); ?>
																																																																	type="radio" name="<?php echo esc_attr($current_billing_post_id); ?>"
																																																																	value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>">
																																																																	<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																																																																	<?php
																																											}
																																										}

																																									}
																																									if ('checkbox' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										?>
																																																	<input class="<?php echo esc_attr($additonal_classes); ?>"
																																																	placeholder="<?php echo esc_attr($placeholder_holder_value); ?>" type="checkbox"
																																																	value="<?php echo esc_attr($current_billing_post_id); ?>"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																																	id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																																	<?php
																																									}
																																									if ('multi_checkbox' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {

																																										foreach ((array) get_post_meta($current_billing_post_id, 'ct_rfq_request_a_quote_options_value_and_label', true) as $key => $value) {
																																											if (is_array($value)) {
																																												?>
																																																																	<input class="<?php echo esc_attr($additonal_classes); ?>"
																																																																	placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																																																	<?php checked($default_value, isset($value['option_value']) ? $value['option_value'] : ''); ?>
																																																																	type="checkbox"
																																																																	value="<?php echo esc_attr(isset($value['option_value']) ? $value['option_value'] : ''); ?>"name="<?php echo esc_attr($current_billing_post_id); ?>[]"
																																																																	id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																																																	<?php echo esc_attr(isset($value['option_label']) ? $value['option_label'] : ''); ?>
																																																																	<br>
																																																																	<?php
																																											}
																																										}
																																									}
																																									if ('countries' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										$countries = new WC_Countries();
																																										?>
																																																	<select class=" ct-rfq-live-search <?php echo esc_attr($additonal_classes); ?>"
																																																		placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																																		class="ct-rfq-live-search" name="<?php echo esc_attr($current_billing_post_id); ?>"
																																																		id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																																		<?php foreach ($countries->get_countries() as $key => $value) { ?>

																																																										<option
																																																										<?php echo esc_attr(in_array(isset($value['option_value']) ? $value['option_value'] : '', (array) $default_value) ? 'selected' : ''); ?>
																																																										value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value); ?>
																																																									</option>

																																																	<?php } ?>
																																																</select>
																																																<?php
																																									}
																																									if ('input' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										?><input class="<?php echo esc_attr($additonal_classes); ?>"
																																															placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																															value="<?php echo esc_attr($default_value); ?>" type="text"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																															id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php
																																									}
																																									if ('textarea' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										?><textarea class="<?php echo esc_attr($additonal_classes); ?>"
																																															placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																															id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"
																																															cols="30" rows="10"><?php echo esc_attr($default_value); ?></textarea><?php
																																									}
																																									if ('number' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										?><input class="<?php echo esc_attr($additonal_classes); ?>"
																																														placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																														value="<?php echo esc_attr($default_value); ?>" type="number" min="1"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																														id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																														<?php
																																									}
																																									if ('color' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										?><input class="<?php echo esc_attr($additonal_classes); ?>"
																																													placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																													value="<?php echo esc_attr($default_value); ?>" type="color"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																													id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																													<?php
																																									}
																																									if ('email' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										?><input class="<?php echo esc_attr($additonal_classes); ?>"
																																												placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																												value="<?php echo esc_attr($default_value); ?>" type="email"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																												id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																												<?php
																																									}
																																									if ('password' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										?><input class="<?php echo esc_attr($additonal_classes); ?>"
																																											placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																											value="<?php echo esc_attr($default_value); ?>" type="password"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																											id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																											<?php
																																									}
																																									if ('telephone' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										?><input class="<?php echo esc_attr($additonal_classes); ?>"
																																										placeholder="<?php echo esc_attr($placeholder_holder_value); ?>"
																																										value="<?php echo esc_attr($default_value); ?>" type="tel"name="<?php echo esc_attr($current_billing_post_id); ?>"
																																										id="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																										<?php
																																									}
																																									?>
																																	</td>


																									<?php } ?>
																								</tr>
																							</table>

																						</div>
														<?php } ?>

														</div>
														</div>
														</div>
														</section>
														<?php

	}
}

add_filter('cloud_tech_email_billing_detail', 'cloud_tech_email_billing_detail', 10, 1);

function cloud_tech_email_billing_detail($post_id = 0)
{


	if ($post_id >= 1) { ?>
																<h4> <?php echo esc_html__('Billing Details', 'cloud_tech_rfq'); ?> </h4>
																<style>
																	.second-template .af-rfq-profile-checkout img {
																		width: 48px !important;
																		height: 38px !important;
																		object-fit: cover;
																		margin: 0px auto !important;
																	}

																	.af-heading-pos i {
																		margin-right: 16px;
																		margin-bottom: 0;
																	}

																	.af-rfq-profile-mode label:first-child {
																		border-top-left-radius: 10px;
																		border-bottom-left-radius: 10px;
																	}

																	.af-rfq-profile-mode label:last-child {
																		border-top-right-radius: 10px;
																		border-bottom-right-radius: 10px;
																	}

																	.af-heading-pos {
																		display: flex;
																		align-items: center;
																		padding: 10px;
																		background: #ffdd44;
																	}

																	.rqs-second-template {
																		display: flex;
																		justify-content: space-between;
																	}

																	.af-rfq-checkout-wrap {
																		width: 48%;
																	}

																	.second-template table tr {
																		display: flex;
																		flex-wrap: wrap;
																		justify-content: space-between;
																	}

																	.second-template table tr td {
																		width: 100%;
																		padding: 0 0px 5px !important;
																	}

																	.second-template table tr td:nth-child(5),
																	.second-template table tr td:nth-child(6),
																	.second-template table tr td:nth-child(7) {
																		width: 100%;
																	}

																	.second-template table tr td label {
																		display: block;
																		font-size: 13px;
																		line-height: 22px;
																		font-weight: 600;
																		color: #000;
																		margin-bottom: 8px;
																	}

																	.second-template table tr td input,
																	.second-template table tr td select,
																	.second-template table tr td .selection .select2-selection.select2-selection--single {
																		width: 100%;
																		height: 46px !important;
																		box-shadow: none;
																		background: #fff !important;
																		border-radius: 10px !important;
																		padding: 7px 10px !important;
																		font-size: 14px !important;
																		border: 1px solid #d3d3d345 !important;
																		margin-bottom: 20px !important;
																		line-height: 24px !important;
																	}

																	.second-template .select2-container--default .select2-selection--single .select2-selection__arrow {
																		top: 9px;
																		right: 6px;
																	}

																	.second-template table tr td textarea {
																		background: #fff;
																		border: 1px solid #d3d3d345;
																		border-radius: 10px !important;
																		box-shadow: none;
																		height: 190px;
																		font-size: 14px;
																		line-height: 24px;
																		margin-bottom: 25px;
																	}

																	.second-template table tr td label.radio {
																		border: 1px solid #d3d3d345 !important;
																		padding: 10px;
																		position: relative;
																		text-align: center;
																		width: 46%;
																	}

																	.second-template table tr td label input[type=radio] {
																		position: absolute;
																		width: 100%;
																		height: 100% !important;
																		top: 0;
																		opacity: 0;
																		left: 0;
																	}

																	.rsq-radio-btn-wrap {
																		display: flex;
																		justify-content: space-between;
																	}

																	.second-template .af-rfq-profile-mode {
																		justify-content: flex-start !important;
																		margin: 30px 0 !important;

																	}

																	.second-template .af-rfq-rent-period h3,
																	.second-template .af-rfq-profile-checkout h3 {
																		font-weight: 700;
																		color: #000;
																		font-size: 20px !important;
																		line-height: 30px !important;
																		margin: 0;
																	}

																	.second-template .af-rfq-profile-mode label {
																		border: 1px solid #d3d3d345;
																		padding: 9px;
																		display: block;
																		width: 120px;
																		font-size: 14px;
																		line-height: 21px;
																		font-weight: 600;
																		background: transparent;
																		color: #000;
																		position: relative;
																	}

																	.second-template {
																		padding: 20px 0;
																		border-bottom: 1px solid #d3d3d34d;
																	}

																	.rqs-shiping {
																		border-bottom: none;
																		padding-bottom: 0 !important;
																	}

																	.second-template-btn {
																		background: #ffdd44 !important;
																		font-size: 16px;
																		line-height: 24px;
																		padding: 9px 24px !important;
																		border-radius: 10px !important;
																		color: #000;
																	}

																	.second-template {
																		padding: 0 !important;
																		border: none !important;
																	}

																	.template-heading h2 {
																		font-size: 30px;
																		font-weight: 700;
																		line-height: 40px;
																		text-align: center;
																		margin-bottom: 30px;
																	}
																</style>
																<?php

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
																if (!empty(get_post_meta($post_id, 'current_user_email', true))) { ?>
															<input type="email" style="display:none;" name="current_user_email"
																value="<?php echo esc_attr(get_post_meta($post_id, 'current_user_email', true)); ?>">
																							<?php }
																?>
															<section class="af-rfq-custom-form">
																<div class="container">
																	<div class="template-heading">
																		<h2>Checkout</h2>
																	</div>
																	<div class="rqs-second-template">

																		<div class="af-rfq-checkout-wrap">

																			<?php if (count($billing_fields) >= 1) { ?>
																											<div class="af-rfq-checkout-form second-template">
																												<div class="af-rfq-profile-checkout">
																													<div class="af-heading-pos">
																														<i class="af-rfq-billing-icon"><?php
																														$icon_or_image = !empty(get_option('ct_rfq_billing_logos')) ? '<img style="width:40px;height:40px;" src="' . esc_url(get_option('ct_rfq_billing_logos')) . '" srcset="' . esc_url(get_option('ct_rfq_billing_logos')) . '" />' : '<i class="fa fa-solid fa fa-user"></i>';
																														echo wp_kses_post($icon_or_image); ?></i>
																														<h3><?php echo esc_html__('Billing Detail', 'cloud_tech_rfq'); ?></h3>
																													</div>
																													<div class="af-rfq-profile-mode">
																														<label><input type="radio" name="billing-profile"
																															<?php echo esc_attr('private' == get_post_meta($post_id, 'billing-profile', true) ? 'checked' : ''); ?>
																															class="billing-profile billing-profile-private"
																															value="private"><?php echo esc_html__('Private', 'cloud_tech_rfq'); ?></label>
																															<label><input type="radio" name="billing-profile"
																																<?php echo esc_attr('company' == get_post_meta($post_id, 'billing-profile', true) ? 'checked' : ''); ?>
																																class="billing-profile billing-profile-company"
																																value="company"><?php echo esc_html__('Company', 'cloud_tech_rfq'); ?></label>
																															</div>
																														</div>
																														<table>
																															<tr>
																																<?php foreach ($billing_fields as $current_billing_post_id) {
																																	$default_value = get_post_meta($post_id, $current_billing_post_id, true);
																																	?>
																																								<td>
																																									<label for="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field">
																																										<?php echo esc_attr(get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_label', true)); ?>
																																									</label>
																																									<?php

																																									if ('file_upload' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										$attachment_url = wp_get_attachment_url($default_value);
																																										if ($attachment_url) {
																																											?><a href="<?php echo esc_url($attachment_url); ?>" class="fa fa-eye">Click to View File</a>
																																																								<?php
																																										}
																																									} elseif ('countries' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																										$countries = new WC_Countries();

																																										foreach ($countries->get_countries() as $key => $country_name) {
																																											if (in_array($key, (array) $default_value)) {
																																												echo esc_attr($country_name);
																																											}
																																										}

																																									} else {
																																										echo esc_attr($default_value);
																																									}

																																									?>
																																							</td>
																															<?php } ?>
																														</tr>
																													</table>

																												</div>
																				<?php } ?>
																			</div>
																			<div class="af-rfq-checkout-wrap">
																				<?php if (count($shipping_fields) >= 1) { ?>

																												<div class="af-rfq-checkout-form second-template rqs-shiping">
																													<div class="af-rfq-profile-checkout">
																														<div class="af-heading-pos">
																															<i class="af-rfq-shipping-icon"><?php
																															$icon_or_image = !empty(get_option('ct_rfq_shipping_logos')) ? '<img style="width:40px;height:40px;" src="' . esc_url(get_option('ct_rfq_shipping_logos')) . '" srcset="' . esc_url(get_option('ct_rfq_billing_logos')) . '" />' : '<i class="fa fa-solid fa fa-user"></i>';
																															echo wp_kses_post($icon_or_image); ?></i>
																															<h3><?php echo esc_html__('Shipping Detail', 'cloud_tech_rfq'); ?></h3>
																														</div>
																														<div class="af-rfq-profile-mode">
																															<label><input type="radio" name="shipping-profile"
																																<?php echo esc_attr('private' == get_post_meta($post_id, 'shipping-profile', true) ? 'checked' : ''); ?>
																																class="shipping-profile shipping-profile-private"
																																value="private"><?php echo esc_html__('Private', 'cloud_tech_rfq'); ?></label>
																																<label><input type="radio" name="shipping-profile"
																																	<?php echo esc_attr('company' == get_post_meta($post_id, 'shipping-profile', true) ? 'checked' : ''); ?>
																																	class="shipping-profile shipping-profile-company"
																																	value="company"><?php echo esc_html__('Delivery', 'cloud_tech_rfq'); ?></label>
																																</div>
																															</div>
																															<table>
																																<tr>
																																	<?php foreach ($shipping_fields as $current_billing_post_id) {


																																		$default_value = get_post_meta($post_id, $current_billing_post_id, true);

																																		?>
																																									<td>
																																										<label
																																										for="<?php echo esc_attr($current_billing_post_id); ?>ct_rfq_quote_fields_field"><?php echo esc_attr(get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_label', true)); ?></label>
																																										<?php

																																										if ('file_upload' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																											$attachment_url = wp_get_attachment_url($default_value);
																																											if ($attachment_url) {
																																												?><a href="<?php echo esc_url($attachment_url); ?>" class="fa fa-eye">Click to View File</a><?php
																																											}
																																										} elseif ('countries' == get_post_meta($current_billing_post_id, 'ct_rfq_quote_fields_field_type', true)) {
																																											$countries = new WC_Countries();

																																											foreach ($countries->get_countries() as $key => $country_name) {
																																												if (in_array($key, (array) $default_value)) {
																																													echo esc_attr($country_name);
																																												}
																																											}

																																										} else {
																																											echo esc_attr($default_value);
																																										}

																																										?>
																																									</td>

																																	<?php } ?>
																																</tr>
																															</table>

																														</div>
																						<?php } ?>

																					</div>
																				</div>
																			</div>
																		 </section>
																		<?php

	}
}