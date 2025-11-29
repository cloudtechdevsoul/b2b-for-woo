<?php

if (!defined('ABSPATH')) {
    exit;
}

$posts_per_page = !empty(get_option('ct_psbs_product_per_page_for_product_sales')) ? get_option('ct_psbs_product_per_page_for_product_sales') : 20;

$width = '75%';

$selected_cities = isset($_GET['selected_cities']) && !empty($_GET['selected_cities']) ? sanitize_text_field($_GET['selected_cities']) : '';
$postcode = isset($_GET['postcode']) && !empty($_GET['postcode']) ? sanitize_text_field($_GET['postcode']) : '';
$selected_roles = isset($_GET['roles']) && !empty($_GET['roles']) ? explode(',', sanitize_text_field($_GET['roles'])) : [];
$selected_customer = isset($_GET['customer']) && !empty($_GET['customer']) ? explode(',', sanitize_text_field($_GET['customer'])) : [];

$order_statuses = isset($_GET['order_status']) && !empty($_GET['order_status']) ? explode(',', sanitize_text_field($_GET['order_status'])) : ['any'];

$selected_cat = isset($_GET['selected_cat']) && !empty($_GET['selected_cat']) ? explode(',', sanitize_text_field($_GET['selected_cat'])) : [];

$selected_prod = isset($_GET['selected_product']) && !empty($_GET['selected_product']) ? explode(',', sanitize_text_field($_GET['selected_product'])) : [];

$selected_countries = isset($_GET['selected_countries']) && !empty($_GET['selected_countries']) ? explode(',', sanitize_text_field($_GET['selected_countries'])) : [];

$selected_state = isset($_GET['selected_state']) && !empty($_GET['selected_state']) ? explode(',', sanitize_text_field($_GET['selected_state'])) : [];


$date_type = isset($_GET['date_type']) && !empty($_GET['date_type']) ? sanitize_text_field($_GET['date_type']) : 'this_year';


$start_date = gmdate('Y-01-01');
$end_date = gmdate('Y-m-d');

if ('last_month' == $date_type) {
    $start_date = gmdate('Y-m-d', strtotime('first day of previous month', strtotime(gmdate('Y-m-d'))));
    $end_date = gmdate('Y-m-d', strtotime('Last day of previous month', strtotime(gmdate('Y-m-d'))));

}
if ('this_month' == $date_type) {

    $start_date = gmdate('Y-m-d', strtotime('first day of this month', strtotime(gmdate('Y-m-d'))));
    $end_date = gmdate('Y-m-d');

}

if ('last_7_days' == $date_type) {
    $start_date = gmdate('Y-m-d', strtotime('- 7 days', strtotime(gmdate('Y-m-d'))));
    $end_date = gmdate('Y-m-d');

}

if ('custom_Date' == $date_type) {

    $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '2000-01-01';

    $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : gmdate('Y-m-d');


}


$order_statuses = array_filter($order_statuses);
$selected_countries = array_filter($selected_countries);
$selected_state = array_filter($selected_state);
$current_page_num = isset($_GET['current_page_num']) && !empty($_GET['current_page_num']) ? sanitize_text_field($_GET['current_page_num']) : 1;

$orders_per_page = get_option('ct_psbs_product_per_page_for_order_sales') ? get_option('ct_psbs_product_per_page_for_order_sales') : 20;
$date_query = array(
    array(
        'after' => $start_date . ' 00:00:00',
        'before' => $end_date . ' 23:59:59',
        'inclusive' => true,
    ),
);
$all_roles_ids = new WP_User_Query(['role__in' => $selected_roles, 'fields' => 'ID']);

$customer_ids = !empty($selected_roles) ? $all_roles_ids->results : [];

$customer_ids = array_merge($customer_ids, $selected_customer);

$args = array(
    'status' => $order_statuses,
    'limit' => -1,
    'return' => 'ids',
    'date_query' => $date_query,
    'order' => 'ASC',
);


if (isset($selected_countries) && count($selected_countries) >= 1) {
    $args['billing_country'] = $selected_countries;
}
if (isset($selected_state) && count($selected_state) >= 1) {
    $args['billing_state'] = $selected_state;
}
if (isset($customer_ids) && count($customer_ids) >= 1) {
    $args['customer'] = $customer_ids;
}

$all_orders = wc_get_orders($args);

foreach ($all_orders as $key => $current_order_id) {
    $current_order = wc_get_order($current_order_id);

    if ($current_order) {


        if (!empty($selected_cities) && !str_contains(strtoupper($selected_cities), strtoupper($current_order->get_billing_city()))) {
            unset($all_orders[$key]);

        }
        if (!empty($postcode) && !str_contains(strtoupper($postcode), strtoupper($current_order->get_billing_postcode()))) {
            unset($all_orders[$key]);

        }

    }

}

$obj_countries = new WC_Countries();
wp_nonce_field('ct_psbs_nonce', 'ct_psbs_nonce');
?>
<div class="ct-psbs-main-sale-product">
    <div class="ct-psbs-tabs">
        <ul>
            <li>
                <font><input type="radio" name="ct_select_date" class="ct_select_date" value="this_year" <?php if ('this_year' == $date_type) { ?> checked <?php } ?>>
                    <span>
                        <?php echo esc_html__('Year', 'cloud_tech_psbs'); ?>
                    </span>
                </font>
            </li>
            <li>
                <font>
                    <input type="radio" name="ct_select_date" class="ct_select_date" value="last_month" <?php if ('last_month' == $date_type) { ?> checked <?php } ?>>
                    <span>
                        <?php echo esc_html__('Last Month', 'cloud_tech_psbs'); ?>
                    </span>
                </font>
            </li>
            <li>
                <font>
                    <input type="radio" name="ct_select_date" class="ct_select_date" value="this_month" <?php if ('this_month' == $date_type) { ?> checked <?php } ?>>
                    <span>
                        <?php echo esc_html__('This Month', 'cloud_tech_psbs'); ?>
                    </span>
                </font>
            </li>
            <li>
                <font>
                    <input type="radio" name="ct_select_date" class="ct_select_date last_7_days" value="last_7_days"
                        <?php if ('last_7_days' == $date_type) { ?> checked <?php } ?>>
                    <span>
                        <?php echo esc_html__('Last 7 Days', 'cloud_tech_psbs'); ?>
                    </span>
                </font>
            </li>
            <li class="select_date_start_end_li">
                <font>
                    <font>
                        <input type="radio" name="ct_select_date" value="custom_Date"
                            class="ct_select_date select_date_start_end" <?php if ('custom_Date' == $date_type) { ?>
                                checked <?php } ?>>
                        <span>
                            <?php echo esc_html__('Custom Date', 'cloud_tech_psbs'); ?>
                        </span>
                    </font>

                </font>
            </li>
            <li style="display:none;">
                <font>
                    <div>
                        <input type="date"
                            value="<?php echo esc_attr(isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : ''); ?>"
                            class="ct-psbs-start-date">
                        <font>
                            <?php echo esc_html__('-', 'cloud_tech_psbs'); ?>
                        </font>
                        <input type="date" name=""
                            value="<?php echo esc_attr(isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : ''); ?>"
                            class="ct-psbs-end-date">
                    </div>
                </font>
            </li>
            <li>
                <font>
                    <i class="ct-psbs-order-filter-btn ct-psbs-filter-btn ct-psbs-show">
                        <?php echo esc_html__('Filter', 'cloud_tech_psbs'); ?>
                    </i>
                </font>
            </li>

            <li>
                <font>

                    <i class="button button-primary ct_send_invoice">Send
                        All Invoice PDF</i>
                </font>
            </li>
        </ul>
    </div>

    <div class="ct-psbs-table-and-search-filed">
        <div class="ct-psbs-main-table-data">
            <div class="ct-psbs-form-field">
                <div>
                    <h5>
                        <?php echo esc_html__('Select Roles', 'cloud_tech_psbs');
                        global $wp_roles; ?>
                    </h5>
                    <select style="width: 100%;" class="ct-psbs-select-roles ct-psbs-live-search" multiple>
                        <?php foreach ($wp_roles->get_names() as $key => $value): ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php if (in_array($key, $selected_roles)): ?>
                                    selected <?php endif ?>>
                                <?php echo esc_attr($value); ?>
                            </option>
                        <?php endforeach ?>
                    </select>

                </div>
            </div>
            <div class="ct-psbs-form-field">
                <div>
                    <h5>
                        <?php echo esc_html__('Select Cutomers', 'cloud_tech_psbs');
                        global $wp_roles; ?>
                    </h5>
                    <select style="width: 100%;" class="ct-psbs-select-user ct_psbs_customer_search" multiple>
                        <?php foreach ($selected_customer as $user_id) {
                            if (!empty($user_id) && get_user_by('ID', $user_id)) {

                                $user = get_user_by('ID', $user_id);
                                ?>
                                <option value="<?php echo esc_attr($user->ID); ?>" selected>
                                    <?php echo esc_attr($user->display_name); ?>
                                </option>
                                <?php
                            }

                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="ct-psbs-form-field">
                <div>
                    <h5>
                        <?php echo esc_html__('Select Order Status', 'cloud_tech_psbs'); ?>
                    </h5>
                    <select style="width: 100%;" class="ct-psbs-select-order-status ct-psbs-live-search" multiple>
                        <?php foreach (wc_get_order_statuses() as $key => $value): ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php if (in_array($key, $order_statuses)): ?>
                                    selected <?php endif ?>>
                                <?php echo esc_attr($value); ?>
                            </option>
                        <?php endforeach ?>
                    </select>

                </div>
            </div>

            <div class="ct-psbs-form-field">
                <div>
                    <h5>
                        <?php echo esc_html__('Select Countries', 'cloud_tech_psbs'); ?>
                    </h5>
                    <select style="width: 100%;" class="ct-psbs-select-countries ct-psbs-live-search" multiple>

                        <?php foreach ($obj_countries->get_countries() as $key => $value): ?>

                            <option value="<?php echo esc_attr($key); ?>" <?php if (in_array($key, $selected_countries)) { ?>selected <?php } ?>>
                                <?php echo esc_attr($value); ?>
                            </option>

                        <?php endforeach ?>

                    </select>

                </div>
            </div>

            <?php if (count($selected_countries) >= 1) { ?>
                <div class="ct-psbs-form-field">
                    <div>
                        <h5>
                            <?php echo esc_html__('Select State', 'cloud_tech_psbs'); ?>
                        </h5>
                        <select style="width: 100%;" class="ct-psbs-select-state ct-psbs-live-search" multiple>

                            <?php foreach ($selected_countries as $current_country) {

                                if (!is_array($obj_countries->get_states($current_country))) {
                                    continue;
                                }

                                foreach ($obj_countries->get_states($current_country) as $key => $value) {

                                    if (empty($value)) {
                                        continue;
                                    }
                                    ?>
                                    <option value="<?php echo esc_attr($key); ?>" <?php if (in_array($key, $selected_state)) { ?>selected <?php } ?>>
                                        <?php echo esc_attr($value); ?>
                                    </option>
                                <?php }
                            } ?>
                        </select>

                    </div>
                </div>
            <?php } ?>
            <?php if (!empty($selected_state)) { ?>
                <div class="ct-psbs-form-field">
                    <div>
                        <h5>
                            <?php echo esc_html__('Enter City', 'cloud_tech_psbs'); ?>
                        </h5>
                        <textarea class="ct-psbs-enter-city"><?php echo esc_attr($selected_cities); ?></textarea>
                        <p>
                            <?php echo esc_html__('Enter multiple cities with comma separated'); ?>
                        </p>
                    </div>
                </div>
            <?php } ?>
            <?php if (!empty($selected_state)) { ?>
                <div class="ct-psbs-form-field">
                    <div>
                        <h5>
                            <?php echo esc_html__('Enter Postcode', 'cloud_tech_psbs'); ?>
                        </h5>
                        <textarea class="ct-psbs-enter-post-code"><?php echo esc_attr($postcode); ?></textarea>
                        <p>
                            <?php echo esc_html__('Enter multiple cities with comma separated'); ?>
                        </p>
                    </div>
                </div>
            <?php } ?>

        </div>
        <div class="ct-psbs-prouct-table" style="width: <?php echo esc_attr($width); ?>;">

            <?php

            $sum_of_subtotal = 0;
            $sum_of_tax_total = 0;
            $sum_of_shipping_tax = 0;
            $sum_of_shipping_total = 0;
            $sum_of_total = 0;
            $sum_of_refunded_amount = 0;
            $sum_of_all_coupon_amount = 0;
            $all_orders = array_filter($all_orders);

            ?>
            <table style="width: 100%;"
                class="wp-list-table widefat fixed striped table-view-list af-purchased-product-detail-table ct-psbs-order-infor-table ct-psbs-order-sales"
                data-file_name="order-sales-data<?php echo esc_attr(gmdate('F-j-Y') . current_time('mysql')); ?>.csv">
                <thead>
                    <tr>
                        <th><input type="checkbox" name="ct_ipoaip_select_all_ids" class="ct_ipoaip_select_all_ids">
                        </th>
                        <th>
                            <?php echo esc_html__('Order Id', 'cloud_tech_psbs'); ?>
                        </th>
                        <th>
                            <?php echo esc_html__('Order Date', 'cloud_tech_psbs'); ?>
                        </th>
                        <th>
                            <?php echo esc_html__('Billing Name', 'cloud_tech_psbs'); ?>
                        </th>
                        <th>
                            <?php echo esc_html__('Billing Country', 'cloud_tech_psbs'); ?>
                        </th>
                        <th>
                            <?php echo esc_html__('Billing State', 'cloud_tech_psbs'); ?>
                        </th>
                        <th>
                            <?php echo esc_html__('Billing City', 'cloud_tech_psbs'); ?>
                        </th>
                        <th>
                            <?php echo esc_html__('Zip Code', 'cloud_tech_psbs'); ?>
                        </th>
                        <th>
                            <?php echo esc_html__('Subtotal', 'cloud_tech_psbs'); ?>
                        </th>
                        <th>
                            <?php echo esc_html__('Total', 'cloud_tech_psbs'); ?>
                        </th>
                        <th>
                            <?php echo esc_html__('Action', 'cloud_tech_psbs'); ?>
                        </th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($all_orders as $order_id) {

                        $order = wc_get_order($order_id);

                        if (!$order) {
                            continue;
                        }

                        $sum_of_subtotal += $order->get_subtotal();
                        $sum_of_tax_total += (float) $order->get_total_tax();
                        $sum_of_shipping_total += $order->get_shipping_total();
                        $sum_of_total += $order->get_total();
                        $country_full_name = devsoul_ipmaip_get_country_full_name($order->get_billing_country());
                        $state_full_name = devsoul_ipmaip_get_state_full_name($order->get_billing_country(), $order->get_billing_state());
                        $sum_of_refunded_amount += $order->get_total_refunded();

                        // Get applied coupons for the order
                        $applied_coupons = $order->get_coupon_codes();

                        // Initialize a variable to store the total coupon amount
                        $total_coupon_amount = 0;

                        // Loop through applied coupons and get their amounts
                        foreach ($applied_coupons as $coupon_code) {
                            // Get the coupon object
                            $coupon = new WC_Coupon($coupon_code);

                            // Get the coupon amount
                            $coupon_amount = $coupon->get_amount();

                            // Add the coupon amount to the total
                            $total_coupon_amount += $coupon_amount;
                        }
                        $sum_of_all_coupon_amount += $total_coupon_amount;

                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="ct_ipoaip_order_ids" class="ct_ipoaip_order_ids"
                                    value="<?php echo esc_attr($order->get_id()); ?>">
                            </td>
                            <td>
                                <?php echo esc_attr($order->get_id()); ?>
                            </td>
                            <td>
                                <?php echo esc_attr(gmdate('F j Y', strtotime($order->get_date_created()))); ?>
                            </td>
                            <td>
                                <?php echo esc_attr($order->get_billing_first_name() . ' ' . $order->get_billing_first_name()); ?>
                            </td>
                            <td>
                                <?php echo esc_attr($country_full_name); ?>
                            </td>
                            <td>
                                <?php echo esc_attr($state_full_name); ?>
                            </td>
                            <td>
                                <?php echo esc_attr($order->get_billing_city()); ?>
                            </td>
                            <td>
                                <?php echo esc_attr($order->get_billing_postcode()); ?>
                            </td>
                            <td>
                                <?php echo wp_kses_post(str_replace(',', '', wc_price($order->get_subtotal()))); ?>
                            </td>


                            <td>
                                <?php echo wp_kses_post(str_replace(',', '', wc_price($order->get_total()))); ?> <input
                                    type="hidden" class="ct-psbs-order-complete-detail"
                                    data-coupon_amount="<?php echo esc_attr($total_coupon_amount); ?>"
                                    data-refunded_amount="<?php echo esc_attr($order->get_total_refunded()); ?>"
                                    data-subtotal="<?php echo esc_attr($order->get_subtotal()); ?>"
                                    data-tax_total="<?php echo esc_attr($order->get_total_tax()); ?>"
                                    data-shipping_total="<?php echo esc_attr($order->get_shipping_total()); ?>"
                                    data-total="<?php echo esc_attr($order->get_total()); ?>">
                            </td>
                            <td>
                                <a href="<?php echo esc_url(get_edit_post_link($order_id)); ?>" class="af-tips"><i
                                        class="fa-solid fa fa-pencil"></i><span>
                                        <?php echo esc_html__('Edit Product', 'cloud_tech_psbs'); ?>
                                    </span></a>


                                <i class="button button-primary ct_send_invoice_current_order"
                                    data-order_id="<?php echo esc_attr($order_id); ?>">Send PDF</i>
                            </td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>

            <div class="ct-psbs-total-of-selected-table">
                <table>

                    <tr class="ct-psbs-subtotal">
                        <th>
                            <?php echo esc_html__('Subtotal', 'cloud_tech_psbs'); ?>
                        </th>
                        <td class="ct-psbs-subtotal-td">
                            <?php echo wp_kses_post(str_replace(',', '', wc_price($sum_of_subtotal))); ?>
                        </td>
                    </tr>
                    <tr class="ct-psbs-total-tax">
                        <th>
                            <?php echo esc_html__('Total Tax', 'cloud_tech_psbs'); ?>
                        </th>
                        <td class="ct-psbs-total-tax-td">
                            <?php echo wp_kses_post(str_replace(',', '', wc_price($sum_of_tax_total))); ?>
                        </td>
                    </tr>
                    <tr class="ct-psbs-shipping-total">
                        <th>
                            <?php echo esc_html__('Order Shipping Total', 'cloud_tech_psbs'); ?>
                        </th>
                        <td class="ct-psbs-shipping-total-td">
                            <?php echo wp_kses_post(str_replace(',', '', wc_price($sum_of_shipping_total))); ?>
                        </td>
                    </tr>
                    <tr class="ct-psbs-coupon-total">
                        <th>
                            <?php echo esc_html__('Order Coupon Total', 'cloud_tech_psbs'); ?>
                        </th>
                        <td class="ct-psbs-coupon-total-td">
                            <?php echo wp_kses_post(str_replace(',', '', wc_price($sum_of_all_coupon_amount))); ?>
                        </td>
                    </tr>
                    <tr class="ct-psbs-refunded-total">
                        <th>
                            <?php echo esc_html__('Order Refund Total', 'cloud_tech_psbs'); ?>
                        </th>
                        <td class="ct-psbs-refunded-total-td">
                            <?php echo wp_kses_post(str_replace(',', '', wc_price($sum_of_refunded_amount))); ?>
                        </td>
                    </tr>
                    <tr class="ct-psbs-total">
                        <th>
                            <?php echo esc_html__('Total', 'cloud_tech_psbs'); ?>
                        </th>
                        <td class="ct-psbs-total-td">
                            <?php echo wp_kses_post(str_replace(',', '', wc_price($sum_of_total))); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php

function devsoul_ipmaip_get_country_full_name($country_code)
{

    $all_countries = new WC_Countries();
    $all_countries = (array) $all_countries->get_countries();

    return isset($all_countries[$country_code]) ? $all_countries[$country_code] : '';

}

function devsoul_ipmaip_get_state_full_name($country_code = '', $state_code = '')
{

    $wc_countries = new WC_Countries();
    $states = $wc_countries->get_states($country_code);
    return isset($states[$state_code]) ? $states[$state_code] : '';

}