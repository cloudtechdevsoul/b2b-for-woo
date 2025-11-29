<?php
/**
 * Theme functions and definitions.
 */
function immidox_child_enqueue_styles()
{

    // Enqueue parent theme style.css
    if (SCRIPT_DEBUG) {
        wp_enqueue_style('immidox-style', get_template_directory_uri() . '/style.css');
    } else {
        wp_enqueue_style('immidox-minified-style', get_template_directory_uri() . '/style.min.css');
    }

    // Enqueue child theme style.css
    wp_enqueue_style(
        'immidox-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('immidox-style'),
        wp_get_theme()->get('Version')
    );

    // Enqueue child theme JavaScript file fron.js
    wp_enqueue_script('immidox-child-js', get_stylesheet_directory_uri() . '/front.js', array('jquery'), null, true);

    // Localize script with custom data
    $fron_data = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'some_variable' => 'some value', // Add more variables as needed
    );
    wp_localize_script('immidox-child-js', 'fronData', $fron_data);
}

add_action('wp_enqueue_scripts', 'immidox_child_enqueue_styles');

add_shortcode('show-custom-cart', 'show_custom_cart_callback');
function show_custom_cart_callback()
{
    ob_start();
    ?>

    <div style="max-width: 100% !important; width: 100% !important;height: 100% !important;"
        class="elementor-element elementor-element-8ba07f1 elementor-widget__width-initial elementor-widget-mobile__width-inherit elementor-widget elementor-widget-price-table"
        data-id="8ba07f1" data-element_type="widget" data-widget_type="price-table.default">
        <div class="elementor-widget-container">
            <div class="elementor-price-table">
                <div class="elementor-price-table__header"
                    style="padding: 15px 10px 15px 10px;background: #45772d !important;">
                    <h3 class="elementor-price-table__heading">Your Choice</h3>
                </div>
                <div class="elementor-price-table__price" style='    color: #1C2448;
                font-family: "Poppins", Sans-serif;
                font-size: 36px;
                font-weight: 600;
                text-transform: none;
                font-style: normal;
                text-decoration: none;
                line-height: 1.2em;
                letter-spacing: 0px;
                word-spacing: 0em;'>
                    <span class="elementor-price-table__currency" style="font-size: calc(100em/100);">$</span>
                    <span class="elementor-price-table__integer-part" id="totalPrice">250</span>
                    <div class="elementor-price-table__after-price">
                        <span class="elementor-price-table__fractional-part"></span>
                        <span class="elementor-price-table__period elementor-typo-excluded">each</span>
                    </div>
                </div>
                <ul class="elementor-price-table__features-list">
                    <!-- Use checkboxes instead of tick icons -->
                    <li class="elementor-repeater-item-d2f1189">
                        <div class="elementor-price-table__feature-inner">
                            <input type="checkbox" name="paynow" id="housing" onchange="updatePrice(this, 250)">
                            <label for="housing"><span>Housing</span></label>
                        </div>
                    </li>
                    <li class="elementor-repeater-item-d2f1189">
                        <div class="elementor-price-table__feature-inner">
                            <input type="checkbox" name="paynow" id="Job" onchange="updatePrice(this, 250)">
                            <label for="Job"><span>Job</span></label>
                        </div>
                    </li>
                    <li class="elementor-repeater-item-d2f1189">
                        <div class="elementor-price-table__feature-inner">
                            <input type="checkbox" name="paynow" id="Transportation" onchange="updatePrice(this, 250)">
                            <label for="Transportation"><span>Transportation</span></label>
                        </div>
                    </li>
                    <li class="elementor-repeater-item-d2f1189">
                        <div class="elementor-price-table__feature-inner">
                            <input type="checkbox" name="paynow" id="bankaccount" onchange="updatePrice(this, 250)">
                            <label for="bankaccount"><span>Creating a bank account</span></label>
                        </div>
                    </li>
                    <li class="elementor-repeater-item-d2f1189">
                        <div class="elementor-price-table__feature-inner">
                            <input type="checkbox" name="paynow" id="Buiding" onchange="updatePrice(this, 250)">
                            <label for="Buiding"><span>Credit Buiding</span></label>
                        </div>
                    </li>
                    <li class="elementor-repeater-item-d2f1189">
                        <div class="elementor-price-table__feature-inner">
                            <input type="checkbox" name="paynow" id="Services" onchange="updatePrice(this, 250)">
                            <label for="Services"><span>Notary Services</span></label>
                        </div>
                    </li>
                    <li class="elementor-repeater-item-d2f1189">
                        <div class="elementor-price-table__feature-inner">
                            <input type="checkbox" name="paynow" id="NYS" onchange="updatePrice(this, 250)">
                            <label for="NYS"><span>Obtaining a NYS ID/DL</span></label>
                        </div>
                    </li>
                    <!-- Repeat similar structure for other services -->
                </ul>
                <style type="text/css">
                    .elementor-price-table__button {
                        color: #FFFFFF !important;
                        font-family: "Poppins", Sans-serif !important;
                        font-size: 16px !important;
                        font-weight: 400 !important;
                        text-transform: capitalize !important;
                        font-style: normal !important;
                        text-decoration: none !important;
                        line-height: 1em !important;
                        letter-spacing: 0px !important;
                        word-spacing: 0em !important;
                        background-color: var(--e-global-color-de39ff8) !important;
                        border-style: solid !important;
                        border-width: 1px 1px 1px 1px !important;
                        border-color: var(--e-global-color-de39ff8) !important;
                        border-radius: 100px 100px 100px 100px !important;
                        padding: 16px 55px 16px 55px !important;
                    }
                </style>
                <div class="elementor-price-table__footer">
                    <a class="elementor-price-table__button elementor-button elementor-size-md donatenow" href="#">Get
                        Started <span id="updatepri">0</span><span>$</span></a>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_action('wp_ajax_add_product_to_cart', 'add_product_to_cart_callback');
add_action('wp_ajax_nopriv_add_product_to_cart', 'add_product_to_cart_callback');

function add_product_to_cart_callback()
{
    if (isset($_POST['product_id']) && isset($_POST['labels'])) {
        $product_id = absint($_POST['product_id']);
        $labels = $_POST['labels'];
        global $woocommerce;
        $servicetypes = array();
        foreach ($labels as $label) {
            $servicetypes[$label] = 250;
        }
        WC()->cart->add_to_cart($product_id, 1, 0, array(), $servicetypes);
        echo 'success';
    } else {
        echo 'Error: Missing parameters.';
    }

    wp_die();
}
add_action('woocommerce_checkout_create_order_line_item', 'save_custom_order_item_meta', 10, 4);
function save_custom_order_item_meta($item, $cart_item_key, $values, $order)
{
    if (isset($values['service'])) {
        $item->add_meta_data('Service', $values['service'], true);
    }
}
add_action('woocommerce_cart_calculate_fees', 'add_fee_based_on_cart_meta');
function add_fee_based_on_cart_meta($cart)
{
    if (WC()->cart->is_empty()) {
        return;
    }
    $fee_added = false;

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if (isset($cart_item['Housing'])) {
            $setup_fee = 250;
            $name = 'Housing';
            $amount = $setup_fee;
            $taxable = true;
            $tax_class = '';
            $cart->add_fee($name, $amount, $taxable, $tax_class);

        }
        if (isset($cart_item['Job'])) {
            $setup_fee = 250;
            $name = 'Job';
            $amount = $setup_fee;
            $taxable = true;
            $tax_class = '';
            $cart->add_fee($name, $amount, $taxable, $tax_class);

        }
        if (isset($cart_item['Transportation'])) {
            $setup_fee = 250;
            $name = 'Transportation';
            $amount = $setup_fee;
            $taxable = true;
            $tax_class = '';
            $cart->add_fee($name, $amount, $taxable, $tax_class);

        }
        if (isset($cart_item['Creating a bank account'])) {
            $setup_fee = 250;
            $name = 'Creating a bank account';
            $amount = $setup_fee;
            $taxable = true;
            $tax_class = '';
            $cart->add_fee($name, $amount, $taxable, $tax_class);

        }
        if (isset($cart_item['Credit Buiding'])) {
            $setup_fee = 250;
            $name = 'Credit Buiding';
            $amount = $setup_fee;
            $taxable = true;
            $tax_class = '';
            $cart->add_fee($name, $amount, $taxable, $tax_class);

        }
        if (isset($cart_item['Notary Services'])) {
            $setup_fee = 250;
            $name = 'Notary Services';
            $amount = $setup_fee;
            $taxable = true;
            $tax_class = '';
            $cart->add_fee($name, $amount, $taxable, $tax_class);

        }
        if (isset($cart_item['Obtaining a NYS ID/DL'])) {
            $setup_fee = 250;
            $name = 'Obtaining a NYS ID/DL';
            $amount = $setup_fee;
            $taxable = true;
            $tax_class = '';
            $cart->add_fee($name, $amount, $taxable, $tax_class);

        }
    }

    if ($fee_added) {
        WC()->cart->calculate_totals();
    }
}

