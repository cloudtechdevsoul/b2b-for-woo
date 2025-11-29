<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>

    <div id="addify_quote_items_container" style="font-family: sans-serif!important;">

        <div class="afrfq_info_div" style="display:flex;">

            <div class="af_rfq_company_information " style="display:inline-block; width:68%; vertical-align:top;">
                <div class="afrfq_company_logo_preview">
                    <?php if ($site_url): ?>
                        <img src="<?php echo esc_url($site_url); ?>" alt="Company Logo"
                            style="width: 60px; margin-right: 10px; display: inline-block; vertical-align:middle;" />
                    <?php endif; ?>
                </div>
                <div class="afrfq_company_info_sub_details"
                    style="font-size:13px;width:73%; display:inline-block; vertical-align:top;">

                    <p><strong style="margin-right: 12px;">
                            <?php echo esc_html('Company:', 'cloud_tech_rfq'); ?>
                        </strong>
                        <?php echo esc_attr($site_name); ?>
                    </p>
                    <p><strong style="margin-right: 12px;">
                            <?php echo esc_html('Address:', 'cloud_tech_rfq'); ?>
                        </strong>
                        <?php echo esc_attr($address); ?>
                    </p>
                    <p><strong style="margin-right: 12px;">
                            <?php echo esc_html('Email:', 'cloud_tech_rfq'); ?>
                        </strong>
                        <?php
                        $af_rfq_email_array_pdf = explode(',', $admin_email);
                        $iteration = 1; // Initialize an iteration counter.
                        
                        foreach ($af_rfq_email_array_pdf as $email) {
                            if ($iteration > 1) {
                                // Apply margin-left to the email address starting from the second iteration.
                                echo '<span style="margin-top:31px; margin-left: 54px;">' . esc_attr($email) . '</span><br>';
                            } else {
                                echo esc_attr($email) . '<br>'; // No margin-left for the first iteration.
                            }

                            $iteration++; // Increment the iteration counter.
                        }
                        ?>
                    </p>
                </div>
            </div>

            <div class="afrfq-quote-detail" style="display:inline-block;  vertical-align:top; width:30%;">
                <div style="background:<?php echo esc_attr($afrfq_backrgound_color); ?>;padding: 5px;">
                    <h1
                        style="font-size:16px; margin-bottom:5px; font-size:20px; line-height:10px; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>;">
                        <?php echo esc_html('Qoute', 'cloud_tech_rfq'); ?>
                    </h1>
                </div>
                <p style="font-size:13px; "><strong style="margin-right: 12px;">
                        <?php echo esc_html('Qoute ID:', 'cloud_tech_rfq'); ?>
                    </strong> <span style="display:inline-block; width:64%; text-align: right; ">
                        <?php echo esc_attr($qoute_id); ?>
                    </span></p>
                <p style="font-size:13px;"><strong style="margin-right: 12px;">
                        <?php echo esc_html('Qoute Status:', 'cloud_tech_rfq'); ?>
                    </strong> <span style="display:inline-block; width:50%; text-align: right; ">
                        <?php echo esc_attr($ct_rfq_qoute_status); ?>
                    </span></p>
                <p style="font-size:13px;"><strong style="margin-right: 12px;">
                        <?php echo esc_html('Qoute Date:', 'cloud_tech_rfq'); ?>
                    </strong> <span style="display:inline-block; width:57%; text-align: right; ">
                        <?php echo esc_attr(gmdate('Y-m-d', strtotime(get_post_field('post_date', $qoute_id)))); ?>
                    </span></p>
            </div>

        </div>
        <table cellpadding="0" cellspacing="0"
            style="margin-top:10px; border-collapse: collapse; width:100%; border:1px solid <?php echo esc_attr($afrfq_backrgound_color); ?>;"
            id="addify_quote_items_table" class="woocommerce_order_items addify_quote_items">
            <thead style="background:<?php echo esc_attr($afrfq_backrgound_color); ?>;">

                <tr style="border-bottom:1px solid #d3d3d3a3;">
                    <?php if (in_array('thumbnail', (array) $psd_csv_coulmn)) { ?>

                        <th style="padding:10px 20px; font-family:sans-serif; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>; font-size: 14px; width: 20%;"
                            class="thumb sortable" data-sort="string-ins">
                            <?php esc_html_e('Image', 'cloud_tech_rfq'); ?>
                        </th>
                    <?php }

                    if (in_array('item', (array) $psd_csv_coulmn)) { ?>

                        <th style="padding:10px 20px; font-family:sans-serif; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>; font-size: 14px; text-align: left; width:35%;"
                            class="item sortable" data-sort="string-ins">
                            <?php esc_html_e('Item', 'cloud_tech_rfq'); ?>
                        </th>
                    <?php }

                    if (in_array('price', (array) $psd_csv_coulmn)) { ?>
                        <th style="padding:10px 20px; font-family:sans-serif; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>; font-size: 14px; width: 20%;"
                            class="item_cost sortable" data-sort="float">
                            <?php esc_html_e('Item Price', 'cloud_tech_rfq'); ?>
                        </th>
                    <?php }


                    if (in_array('quantity', (array) $psd_csv_coulmn)) { ?>
                        <th style="padding:10px 20px; font-family:sans-serif; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>; font-size: 14px; width:10%;"
                            class="quantity sortable" data-sort="int">
                            <?php esc_html_e('Quantity', 'cloud_tech_rfq'); ?>
                        </th>
                    <?php }

                    if (in_array('quantity', (array) $psd_csv_coulmn)) { ?>
                        <th style="padding:10px 20px; font-family:sans-serif; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>; font-size: 14px; width:15%;"
                            class="line_cost sortable" data-sort="float">
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
                <tr>
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
                    <tr class="item" data-order_item_id="<?php echo esc_attr($item_id); ?>">

                        <?php if (in_array('thumbnail', (array) $psd_csv_coulmn)) { ?>
                            <td class="thumb" style="padding: 15px 20x; text-align:center; width: 20%;">
                                <img width="40px; " src="<?php echo esc_url($product_permalink); ?>" alt="Product Image" />
                            </td>
                        <?php } ?>
                        <?php if (in_array('item', (array) $psd_csv_coulmn)) { ?>
                            <td class="woocommerce-table__product-name product-name"
                                style=" padding:15px 20px!important; text-align:left; font-size: 13px; color:#000; width:35%;overflow-wrap: anywhere; word-break: break-word; ">
                                <?php echo esc_attr($product->get_name()); ?>
                                <br>
                                <?php echo wp_kses_post('<div class="wc-quote-item-sku" style="font-size:12x; margin-top:10px;"><strong style="font-size:12px;">' . esc_html__('SKU:', 'cloud_tech_rfq') . '</strong> ' . esc_attr($product->get_sku()) . '</div>'); ?>
                            </td>

                        <?php } ?>
                        <?php if (in_array('price', (array) $psd_csv_coulmn)) { ?>
                            <td class="woocommerce-table__product-total product-total"
                                style="padding: 15px 20x; text-align:center; color:#000; width: 20%; font-size: 13px;">
                                <?php echo wp_kses_post(get_woocommerce_currency_symbol() . ' ' . wc_format_decimal($price, wc_get_price_decimals())); ?>
                            </td>

                        <?php } ?>
                        <?php if (in_array('quantity', (array) $psd_csv_coulmn)) { ?>
                            <td style="padding: 15px 20x; text-align:center; font-size: 13px; color:#000; width: 10%;">
                                <?php echo esc_attr($quantity); ?>
                            </td>
                        <?php } ?>

                        <?php if (in_array('subtotal', (array) $psd_csv_coulmn)) { ?>
                            <td class="woocommerce-table__product-total product-total"
                                style="padding: 15px 20x; text-align:center; color:#000; font-size: 13px; width: 15%;">
                                <?php echo wp_kses_post(get_woocommerce_currency_symbol() . ' ' . wc_format_decimal($price * $quantity, wc_get_price_decimals())); ?>
                            </td>
                        <?php } ?>
                        <?php if (in_array('offered_price', (array) $psd_csv_coulmn)) { ?>
                            <td style="padding: 15px 20x; text-align:center; color:#000; font-size: 13px; width: 15%;"
                                data-title="<?php esc_attr_e('Offered Price', 'cloud_tech_rfq'); ?>">
                                <?php echo wp_kses_post(get_woocommerce_currency_symbol() . ' ' . wc_format_decimal($custom_price, wc_get_price_decimals())); ?>
                            </td>
                        <?php } ?>
                        <?php if (in_array('offered_subtotal', (array) $psd_csv_coulmn)) { ?>
                            <td style="padding: 15px 20x; text-align:center; color:#000; font-size: 13px; width: 15%;"
                                data-title="<?php esc_attr_e('Offered Price', 'cloud_tech_rfq'); ?>">
                                <?php echo wp_kses_post(get_woocommerce_currency_symbol() . ' ' . wc_format_decimal($custom_price * $quantity, wc_get_price_decimals())); ?>
                            </td>
                        <?php } ?>


                    </tr>

                <?php } ?>
            </tbody>
        </table>
        <div>
            <?php if (get_option('ct_rfq_enable_term_and_conditions')) { ?>
                <div class="afrfq_client_info_details"
                    style="width:64%;display:inline-block;margin-top:15px; vertical-align:top;">
                    <h1
                        style="margin-bottom:20px;padding:7px 10px;  background-color:<?php echo esc_attr($afrfq_backrgound_color); ?>; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>; font-size:16px;  font-weight: 700;">
                        <?php echo esc_html('Terms & Conditions', 'cloud_tech_rfq'); ?>
                    </h1>
                    <ul style="padding-left:13px; font-size:13px;  line-height: 10px;">
                        <li>
                            <?php echo wp_kses_post(get_option('ct_rfq_term_and_condition_data')); ?>
                        </li>
                    </ul>
                </div>
                <?php
                print_r($psd_csv_coulmn);

                ?>
                <div
                    style="margin-left:20px; margin-right:0px; width:32%;display:inline-block; vertical-align:top;margin-top:8px; float:right;">
                <?php } else { ?>
                    <div
                        style="margin-left:470px; margin-right:0px; width:32%;display:inline-block; vertical-align:top;margin-top:8px;">
                    <?php } ?>
                    <table style="">
                        <tbody>
                            <?php if (in_array('quote_subtotal', (array) $psd_csv_coulmn)) { ?>
                                <tr
                                    style="border-top:1px solid <?php echo esc_attr($afrfq_backrgound_color); ?>; margin-top:15px; font-family: sans-serif;">
                                    <td
                                        style="padding-top:10px; padding-bottom:10px; padding-left:7px; padding-right:20px;  color:#000; font-size: 14px; text-align:left; font-weight:bold; font-family:sans-serif;">
                                        <?php echo esc_html('Subtotal', 'cloud_tech_rfq'); ?>
                                    </td>
                                    <td
                                        style="padding-top:10px; padding-bottom:10px;padding-left:10px; padding-right:20px; color:#000; font-size: 14px; text-align:left;">
                                        <?php echo wp_kses_post(get_woocommerce_currency_symbol() . ' ' . wc_format_decimal(wc_get_quote_subtotal_ct($qoute_id), wc_get_price_decimals())); ?>
                                    </td>
                                </tr>
                                <?php
                            } ?>
                            <?php if (in_array('quote_offered_total', (array) $psd_csv_coulmn)) { ?>

                                <tr>
                                    <td
                                        style="padding-top:10px; padding-bottom:10px; padding-left:7px; padding-right:20px; color:#000; font-size: 14px; text-align:left; font-weight:bold; font-family:sans-serif;">
                                        <?php echo esc_html('Offered Total', 'cloud_tech_rfq'); ?>
                                    </td>
                                    <td
                                        style="padding-top:10px; padding-bottom:10px;padding-left:10px; padding-right:20px;  color:#000; font-size: 14px; text-align:left;">
                                        <?php echo wp_kses_post(get_woocommerce_currency_symbol() . ' ' . wc_format_decimal($offered_total, wc_get_price_decimals())); ?>
                                    </td>
                                </tr>

                                <?php
                            } ?>

                            <?php if (in_array('quote_total_tax', (array) $psd_csv_coulmn)) { ?>

                                <tr>
                                    <td
                                        style="padding-top:10px; padding-bottom:10px;padding-left:7px; padding-right:20px;  color:#000; font-size: 14px; text-align:left; font-weight:bold; font-family:sans-serif;">
                                        <?php echo esc_html('Tax Total', 'cloud_tech_rfq'); ?>
                                    </td>
                                    <td
                                        style="padding-top:10px; padding-bottom:10px;padding-left:10px; padding-right:20px; color:#000; font-size: 14px; text-align:left;">
                                        <?php echo wp_kses_post(get_woocommerce_currency_symbol() . ' ' . wc_format_decimal(wc_get_quote_tax_ct($qoute_id), wc_get_price_decimals())); ?>

                                    </td>
                                </tr>
                                <?php
                            } ?>

                            <?php if (in_array('quote_total_total', (array) $psd_csv_coulmn)) { ?>
                                <tr style="background-color:<?php echo esc_attr($afrfq_backrgound_color); ?>;">
                                    <td
                                        style="padding-top:10px; padding-bottom:10px; padding-left:7px; padding-right:20px; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>; font-size: 14px; text-align:left;  font-weight:bold; font-family:sans-serif;">
                                        <?php echo esc_html('Qoute Total', 'cloud_tech_rfq'); ?>
                                    </td>
                                    <td
                                        style="padding-top:10px;   padding-bottom:10px;padding-left:10px; padding-right:20px; color:<?php echo esc_attr($afrfq_text_color_for_background); ?>; font-size: 14px;  margin:0;">
                                        <?php echo wp_kses_post(get_woocommerce_currency_symbol() . ' ' . wc_format_decimal(wc_get_quote_total_ct($qoute_id), wc_get_price_decimals())); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
</body>

</html>
<?php
