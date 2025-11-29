<?php



// Add a custom endpoint to the My Account page
add_request_quote_detail_endpoint();
function add_request_quote_detail_endpoint()
{

    add_rewrite_endpoint('request-quote-detail', EP_ROOT | EP_PAGES);
    flush_rewrite_rules();

}

// Add content to the custom endpoint
function request_quote_detail_content()
{

    $ct_rfq_current_user_email = wp_get_current_user()->user_email;

    $all_quote_of_current_user = get_posts([
        'post_type' => 'ct-rfq-submit-quote',
        'fields' => 'ids',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => 'current_user_email',
                'value' => $ct_rfq_current_user_email,
            ]
        ],
    ]);

    $request_quote_detail_url = wc_get_account_endpoint_url('request-quote-detail');


    if (isset($_GET['quote_id']) && !empty($_GET['quote_id'])) {

        $quote_id = sanitize_text_field($_GET['quote_id']);

        if (get_option('ct_rfq_enable_pdf')) { ?>
            <a href="<?php echo esc_url($request_quote_detail_url . '?quote_id=' . $quote_id . '&download_pdf=' . $quote_id); ?>"
                class="woocommerce-button button fa fa-download"> Download PDF</a>

        <?php } ?>
        <?php if (get_option('ct_rfq_enable_csv')) { ?>
            <a href="<?php echo esc_url($request_quote_detail_url . '?quote_id=' . $quote_id . '&download_csv=' . $quote_id); ?>"
                class="woocommerce-button button fa fa-download"> Download CSV</a>

        <?php }

        ct_rfq_quote_details_with_post_id($quote_id);
        $check_product_detail_array = apply_filters('cloud_tech_quote_billing_detail', $quote_id);
        echo wp_kses_post($check_product_detail_array);


    } else {


        ?>
        <div class="ct-quote-table-div">
            <table
                class="ct-quote-table woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
                <thead>
                    <tr>
                        <th>
                            <?php echo esc_html__('Quote', 'cloud_tech_rfq'); ?>
                        </th>
                        <th>
                            <?php echo esc_html__('Date', 'cloud_tech_rfq'); ?>
                        </th>
                        <th>
                            <?php echo esc_html__('Status For Conatct', 'cloud_tech_rfq'); ?>
                        </th>
                        <th>
                            <?php echo esc_html__('Total', 'cloud_tech_rfq'); ?>
                        </th>
                        <th>
                            <?php echo esc_html__('Action', 'cloud_tech_rfq'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_quote_of_current_user as $key => $current_quote_id) {

                        if (empty($current_quote_id)) {
                            continue;
                        }

                        $current_quote_detail = get_post($current_quote_id);
                        $added_products_to_quote = (array) get_post_meta($current_quote_id, 'cloud_tech_quote_cart', true);

                        $added_products_to_quote = ct_rfq_custom_array_filter($added_products_to_quote);
                        $quote_status = get_post_meta($current_quote_id, 'quote_status', true) ? get_post_meta($current_quote_id, 'quote_status', true) : 'Pending';
                        $quote_status = ucfirst(str_replace('_', ' ', $quote_status));

                        ?>
                        <tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-on-hold order">
                            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-number"
                                data-title="Order"><a
                                    href="<?php echo esc_url($request_quote_detail_url . '?quote_id=' . $current_quote_id); ?>">
                                    <?php echo esc_attr($current_quote_id); ?>
                                </a> </td>
                            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-date" data-title="Date">
                                <time datetime="">
                                    <?php echo esc_attr(gmdate('F-j-Y', strtotime($current_quote_detail->post_date))); ?>
                                </time>
                            </td>
                            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-status"
                                data-title="Status">
                                <?php echo esc_attr($quote_status); ?>
                            </td>
                            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-total"
                                data-title="Total">
                                <?php echo wp_kses_post(wc_price(wc_get_quote_total_ct($current_quote_id))); ?> for
                                <?php echo esc_attr(count($added_products_to_quote)); ?>
                            </td>
                            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-actions"
                                data-title="Actions">
                                <a href="<?php echo esc_url($request_quote_detail_url . '?quote_id=' . $current_quote_id); ?>"
                                    class="woocommerce-button button view">View</a>
                                <?php if (get_option('ct_rfq_enable_pdf')) { ?>
                                    <a href="<?php echo esc_url($request_quote_detail_url . '?download_pdf=' . $current_quote_id); ?>"
                                        class="woocommerce-button button fa fa-download"> PDF</a>

                                <?php } ?>
                                <?php if (get_option('ct_rfq_enable_csv')) { ?>
                                    <a href="<?php echo esc_url($request_quote_detail_url . '?download_csv=' . $current_quote_id); ?>"
                                        class="woocommerce-button button fa fa-download"> CSV</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
        <?php
    }

    ?>
    <h4>
        <?php echo esc_html__('Quote Chat', 'cloud_tech_rfq'); ?>
    </h4>
    <?php
}

add_action('woocommerce_account_request-quote-detail_endpoint', 'request_quote_detail_content');
function add_request_quote_detail_link($items)
{
    $items['request-quote-detail'] = __('Request a Quote Detail', 'your-text-domain');
    return $items;
}

add_filter('woocommerce_account_menu_items', 'add_request_quote_detail_link');