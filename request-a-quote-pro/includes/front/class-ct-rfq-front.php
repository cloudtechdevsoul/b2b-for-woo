<?php


add_filter('woocommerce_get_price_html', 'cloud_rfq_custom_modify_product_price_html', 10, 2);
add_filter('woocommerce_loop_add_to_cart_link', 'cloud_rfq_filter_loop_add_to_cart_link', 10, 2);
add_filter('woocommerce_product_single_add_to_cart_text', 'cloud_rfq_single_pge_add_to_cart_button_text', 10, 2);
add_filter('woocommerce_get_price_html', 'cloud_tech_rfq_price_html', 10, 2);


$button_display_position = !empty(get_option('ct_rfq_request_a_quote_button_postion_prd_pg')) ? get_option('ct_rfq_request_a_quote_button_postion_prd_pg') : 'woocommerce_before_add_to_cart_quantity';

add_action('woocommerce_product_meta_start', 'ct_rfq_button_display_position_for_out_of_stock');
add_action('woocommerce_after_shop_loop_item', 'ct_rfq_button_display_position');
add_action($button_display_position, 'ct_rfq_button_display_position');

add_action('wp_footer', 'ct_rfq_footer');

add_action('wp_loaded', 'ct_rfq_front_wp_loaded');

function ct_rfq_footer()
{
    // cloud_tech_request_a_quote_mini_cart();

}

function cloud_rfq_custom_modify_product_price_html($price, $product)
{

    $rule_id = ct_rfq_is_this_product_is_aplicable($product);


    if ($rule_id && 'hide' == get_post_meta($rule_id, 'ct_rfq_hide_price_or_replace_text', true)) {
        $price = '';

    }
    if ($rule_id && 'replace_text' == get_post_meta($rule_id, 'ct_rfq_hide_price_or_replace_text', true)) {
        $price = get_post_meta($rule_id, 'ct_rfq_replace_price_text', true);

    }

    return $price;
}

function cloud_rfq_filter_loop_add_to_cart_link($button, $product)
{
    $rule_id = ct_rfq_is_this_product_is_aplicable($product);
    if ($rule_id && !empty(get_post_meta($rule_id, 'ct_rfq_hide_add_to_cart_button', true))) {
        $button = '';
    }
    return $button;
}



function cloud_rfq_single_pge_add_to_cart_button_text($text, $product)
{
    $rule_id = ct_rfq_is_this_product_is_aplicable($product);
    if ($rule_id && !empty(get_post_meta($rule_id, 'ct_rfq_hide_add_to_cart_button', true))) {
        $text = '<span class="cloud_rfq_add_to_cart_button_text_single_pg"></span>';
    }
    return $text;
}


function ct_rfq_button_display_position()
{

    global $product;

    if (str_contains('group', $product->get_type())) {
        return;
    }

    if ($product->is_purchasable()) {

        $rule_id = ct_rfq_is_this_product_is_aplicable($product);
        if (!empty($rule_id)) {
            $button_text = get_post_meta($rule_id, 'ct_rfq_request_a_quote_button_text', true) ? get_post_meta($rule_id, 'ct_rfq_request_a_quote_button_text', true) : 'Request a Quote';

            if (!is_single() && 'simple' != $product->get_type()) {
                ?>

                <a href="<?php echo esc_url($product->get_permalink()); ?>" data-quantity="1" class="button product_type_variable"
                    data-product_id="209" data-product_sku="AL" aria-label="Select options for “Album”"
                    aria-describedby="This product has multiple variants. The options may be chosen on the product page"
                    rel="nofollow">Select options</a>
                <input type="hidden" name="current_rule_id" value="<?php echo esc_attr($rule_id); ?>">
                <?php

                return;
            }



            if (is_shop() || is_archive()) { ?>
                <form method="post">

                    <?php wp_nonce_field('cloud_tech_rfq_nonce', 'cloud_tech_rfq_nonce'); ?>

                    <input type="submit" name="cloud_tect_add_quote" value="<?php echo esc_attr($button_text); ?>"
                        class="button primary-button ct-add-product-into-quote <?php echo esc_attr(get_post_meta($rule_id, 'ct_rfq_request_a_quote_button_additonal_class', true)); ?>">
                    <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">
                    <input type="hidden" name="current_rule_id" value="<?php echo esc_attr($rule_id); ?>">

                </form>

            <?php } else {


                wp_nonce_field('cloud_tech_rfq_nonce', 'cloud_tech_rfq_nonce'); ?>

                <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">
                <input type="submit" name="cloud_tect_add_quote" value="<?php echo esc_attr($button_text); ?>"
                    class="button primary-button ct-add-product-into-quote <?php echo esc_attr(get_post_meta($rule_id, 'ct_rfq_request_a_quote_button_additonal_class', true)); ?>">
                <input type="hidden" name="current_rule_id" value="<?php echo esc_attr($rule_id); ?>">


                <?php

            }
            if (!empty(get_option('ct_rfq_request_a_quote_checkboxes'))) {
                ?>
                <input type="checkbox" name="ct_rfq_cart_page_button" data-rule_id="<?php echo esc_attr($rule_id); ?>"
                    class="ct_rfq_cart_page_button" data-product_id="<?php echo esc_attr($product->get_id()); ?>" />

                <?php
            }
        }
    }



}
function ct_rfq_button_display_position_for_out_of_stock()
{
    global $product;
    if (str_contains('group', $product->get_type())) {
        return;
    }

    $rule_id = ct_rfq_is_this_product_is_aplicable($product);

    if ($product->is_purchasable() && !empty($rule_id) && !$product->is_in_stock()) {
        $button_text = get_post_meta($rule_id, 'ct_rfq_request_a_quote_button_text', true) ? get_post_meta($rule_id, 'ct_rfq_request_a_quote_button_text', true) : 'Request a Quote';

        if (is_single() && !empty(get_post_meta($rule_id, 'ct_rfq_show_request_a_quote_btn_with_outofstock', true))) {
            ?>
            <form method="post">

                <?php wp_nonce_field('cloud_tech_rfq_nonce', 'cloud_tech_rfq_nonce'); ?>

                <input type="submit" name="cloud_tect_add_quote" value="<?php echo esc_attr($button_text); ?>"
                    class="button primary-button ct-add-product-into-quote <?php echo esc_attr(get_post_meta($rule_id, 'ct_rfq_request_a_quote_button_additonal_class', true)); ?>">
                <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">
                <input type="hidden" name="current_rule_id" value="<?php echo esc_attr($rule_id); ?>">
            </form>

        <?php }
    }
}

function ct_rfq_front_wp_loaded()
{

    $nonce = isset($_POST['cloud_tech_rfq_nonce']) ? sanitize_text_field($_POST['cloud_tech_rfq_nonce']) : 0;

    if (isset($_POST['cloud_tect_add_quote']) && !wp_verify_nonce($nonce, 'cloud_tech_rfq_nonce')) {
        wp_die(esc_html__('Security Voilated', 'cloud_tech_rfq'));
    }


    if (isset($_POST['cloud_tect_add_quote']) && isset($_POST['product_id'])) {

        $current_rule_id = isset($_POST['current_rule_id']) ? sanitize_text_field($_POST['current_rule_id']) : 0;
        $product_id = sanitize_text_field($_POST['product_id']);
        $variation_id = isset($_POST['variation_id']) ? sanitize_text_field($_POST['variation_id']) : 0;
        $quantity = isset($_POST['quantity']) ? sanitize_text_field($_POST['quantity']) : 1;
        $product_detail_array = ['current_rule_id' => $current_rule_id, 'product_id' => $product_id, 'variation_id' => $variation_id, 'quantity' => $quantity];

        cloud_tech_add_quote($product_detail_array);

        $post_content = get_post_field('post_content', get_the_ID());
        $ct_redirect_page_link = get_page_link(get_rfq_pge_id());

        if (is_single() && !empty(get_option('ct_rfq_enable_redirect_from_product_page'))) {
            wp_safe_redirect($ct_redirect_page_link);
        }


        if ((is_cart() || is_checkout()) && !empty(get_option('ct_rfq_enable_redirect_from_cart_and_checkout_pge'))) {
            wp_safe_redirect($ct_redirect_page_link);
        }


        if ((is_shop() || is_archive()) && !empty(get_option('ct_rfq_enable_redirect_from_shop_and_archive_page'))) {
            wp_safe_redirect($ct_redirect_page_link);

        }


        if (!empty(get_option('ct_rfq_enable_redirect_from_quote_table_pg')) && has_shortcode($post_content, 'cloud_tech_quote_table')) {
            wp_safe_redirect($ct_redirect_page_link);
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

// Add a filter to modify the price HTML
function cloud_tech_rfq_price_html($price, $product)
{

    if (str_contains('group', $product->get_type())) {
        return $price;
    }

    $rule_id = ct_rfq_is_this_product_is_aplicable($product);
    if ($product->is_purchasable() && !empty($rule_id)) {

        $price_text = get_post_meta($rule_id, 'ct_rfq_replace_price_text', true);
        $hide_or_replace = get_post_meta($rule_id, 'ct_rfq_hide_price_or_replace_text', true);

        if (str_contains($hide_or_replace, 'hide') && !str_contains($hide_or_replace, 'quote_page')) {
            $price = '';
        }
        if (str_contains($hide_or_replace, 'replace') && !str_contains($hide_or_replace, 'quote_page')) {
            $price = $price_text;

        }

    }
    return $price;
}

add_filter('woocommerce_get_item_data', 'ct_rfq_cart_product_name_hook', 10, 2);

function ct_rfq_cart_product_name_hook($item_data, $cart_item)
{

    $show_button_with_using_proudct_id = ct_rfq_is_this_product_is_aplicable($cart_item['data']);

    $product_or_var_id = $cart_item['variation_id'] > 1 ? $cart_item['variation_id'] : $cart_item['product_id'];


    if ($show_button_with_using_proudct_id && !empty(get_option('ct_rfq_request_a_quote_btn_on_cart_page'))) {

        ?> <i class="button primary-button ct-rfq-cart-page-button"
            data-rule_id="<?php echo esc_attr($show_button_with_using_proudct_id); ?>"
            data-product_id="<?php echo esc_attr($product_or_var_id); ?>">
            <?php echo esc_attr(get_post_meta($show_button_with_using_proudct_id, 'ct_rfq_request_a_quote_button_text', true) ? get_post_meta($show_button_with_using_proudct_id, 'ct_rfq_request_a_quote_button_text', true) : 'Add to Quote') ?>
        </i>
        <?php

        if (!empty(get_option('ct_rfq_request_a_quote_checkboxes'))) {
            ?>
            <input type="checkbox" name="ct_rfq_cart_page_button"
                data-rule_id="<?php echo esc_attr($show_button_with_using_proudct_id); ?>" class="ct_rfq_cart_page_button"
                data-product_id="<?php echo esc_attr($product_or_var_id); ?>" />

            <?php
        }


    } else if ((isset($cart_item['variation_id']) && $cart_item['variation_id'] >= 1 && ct_rfq_is_this_product_is_aplicable(wc_get_product($cart_item['variation_id']))) && !empty(get_option('ct_rfq_request_a_quote_btn_on_cart_page'))) {

        ?><i class="button primary-button ct-rfq-cart-page-button"
                data-rule_id="<?php echo esc_attr($show_button_with_using_proudct_id); ?>"
                data-product_id="<?php echo esc_attr($product_or_var_id); ?>">
            <?php echo esc_attr(get_post_meta($show_button_with_using_proudct_id, 'ct_rfq_request_a_quote_button_text', true) ? get_post_meta($show_button_with_using_proudct_id, 'ct_rfq_request_a_quote_button_text', true) : 'Add to Quote') ?>
            </i>
            <?php
            if (!empty(get_option('ct_rfq_request_a_quote_checkboxes'))) {
                ?>
                <input type="checkbox" name="ct_rfq_cart_page_button"
                    data-rule_id="<?php echo esc_attr($show_button_with_using_proudct_id); ?>" class="ct_rfq_cart_page_button"
                    data-product_id="<?php echo esc_attr($product_or_var_id); ?>" />
            <?php
            }
    }

    return $item_data;
}

add_shortcode('whole_sale_request_a_quote_button', 'whole_sale_request_a_quote_button');
function whole_sale_request_a_quote_button()
{
    ob_start();
    ?>
    <i class=" button primary-button whole_sale_request_a_quote_button">
        <?php echo esc_attr(!empty(get_option('ct_rfq_request_a_quote_whole_button_text')) ? get_option('ct_rfq_request_a_quote_whole_button_text') : 'Add all product to Quote'); ?>
    </i>
    <?php

    return ob_get_clean();
}

// Start session for guest user
function start_guest_session()
{
    if (!is_user_logged_in() || !session_id()) {
        session_start();
        if (function_exists('WC') && !WC()->session->has_session()) {
            WC()->session->set_customer_session_cookie(true);
        }
    }

}
add_action('wp_footer', 'start_guest_session');
