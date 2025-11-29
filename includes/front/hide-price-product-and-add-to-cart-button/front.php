<?php


add_filter( 'woocommerce_get_price_html', 'cloud_hpaatcb_custom_modify_product_price_html', 10, 2);
add_filter( 'woocommerce_product_single_add_to_cart_text', 'cloud_hpaatcb_single_pge_add_to_cart_button_text', 10, 2 );
add_filter( 'woocommerce_loop_add_to_cart_link', 'cloud_hpaatcb_filter_loop_add_to_cart_link', 10, 2 );
add_filter( 'woocommerce_is_purchasable', 'cloud_hpaatcb_is_purchasable', 10, 2);



add_action('wp_footer','ct_wp_footer');

function ct_wp_footer() {


    // $product_id     = 981;

    // $result         = check_product_detail_availability_and_txt_and_price( $product_id );
    // var_dump( $result );

}

function cloud_hpaatcb_custom_modify_product_price_html($price, $product) {

    $check_new_price        = (array) check_product_detail_availability_and_txt_and_price( $product->get_id() , 'price');

    if ( isset( $check_new_price['new_price'] ) ) {
        $price    = $check_new_price['new_price'];

    }

    return $price;
}


function cloud_hpaatcb_single_pge_add_to_cart_button_text( $text , $product ) {
    $add_to_cart_btn_txt        = (array) check_product_detail_availability_and_txt_and_price( $product->get_id() , 'hide_add_to_cart_button');
    if ( isset( $add_to_cart_btn_txt['replace_text_button_or_hide_button'] ) ) {
        $rule_id                = $add_to_cart_btn_txt['replace_text_button_or_hide_button'];
        $hide_or_replace_txt    = get_post_meta($rule_id,'ct_rbpaqp_hpaatcb_add_to_cart_btn',true);
        $admin_selected_btn_txt = get_post_meta($rule_id,'ct_rbpaqp_hpaatcb_button_text',true);
        $admin_selected_btn_lnk = get_post_meta($rule_id,'ct_rbpaqp_hpaatcb_custom_link',true);
        $admin_custom_btn       = get_post_meta($rule_id,'ct_rbpaqp_hpaatcb_make_your_on_button',true);

        if ( 'replace_text_and_link' == $hide_or_replace_txt ) {
            if ( !empty( $admin_selected_btn_lnk ) && !empty( $admin_selected_btn_txt ) ) {

                $text = '<span class="cloud_hpaatcb_add_to_cart_button_text"><a href="'. esc_url($admin_selected_btn_lnk) . '" class=" button alt">' . esc_attr( $admin_selected_btn_txt ) . '</a></span>';

            } else if ( !empty( $admin_selected_btn_txt ) ) {

                $text               = $admin_selected_btn_txt;
            }
        } 
        if ( 'make_your_on_button' == $hide_or_replace_txt ) {
            $text               = '<span class="cloud_hpaatcb_add_to_cart_button_text">'.$admin_custom_btn.'</span>';
            
        } 
    }
    return $text;
}

function cloud_hpaatcb_filter_loop_add_to_cart_link( $button, $product ) {
    $add_to_cart_btn_txt        = (array) check_product_detail_availability_and_txt_and_price( $product->get_id() , 'hide_add_to_cart_button');
    if ( isset( $add_to_cart_btn_txt['replace_text_button_or_hide_button'] ) ) {

        $rule_id                = $add_to_cart_btn_txt['replace_text_button_or_hide_button'];
        $hide_or_replace_txt    = get_post_meta($rule_id,'ct_rbpaqp_hpaatcb_add_to_cart_btn',true);
        $admin_selected_btn_txt = get_post_meta($rule_id,'ct_rbpaqp_hpaatcb_button_text',true);
        $admin_selected_btn_lnk = get_post_meta($rule_id,'ct_rbpaqp_hpaatcb_custom_link',true);
        $admin_custom_btn       = get_post_meta($rule_id,'ct_rbpaqp_hpaatcb_make_your_on_button',true);


        if ( 'replace_text_and_link' == $hide_or_replace_txt ) {
            if ( !empty( $admin_selected_btn_lnk ) && !empty( $admin_selected_btn_txt ) ) {

                $button = '<a href="' . esc_url($admin_selected_btn_lnk) . '" class="button alt">' . esc_attr( $admin_selected_btn_txt ) . '</a>';

            } else if ( !empty( $admin_selected_btn_txt ) ) {

                $button = '<a href="?add-to-cart='. esc_attr( $product->get_id() ) .'" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="'. esc_attr( $product->get_id() ) .'" data-product_sku="'. esc_attr( $product->get_sku() ) .'" aria-label="Add “'. esc_attr( $product->get_name() ) .'” to your cart" aria-describedby="" rel="nofollow">'.$admin_selected_btn_txt.'</a>';

            }
        } 
        if ( 'make_your_on_button' == $hide_or_replace_txt ) {
            $button               = $admin_custom_btn;
            
        }
        if ( 'hide_button' == $hide_or_replace_txt ) {
            $button               = '';
            
        }

    }
    return $button;
}

function cloud_hpaatcb_is_purchasable($is_purchasable, $product) {
    $add_to_cart_btn_txt        = (array) check_product_detail_availability_and_txt_and_price( $product->get_id() , 'hide_add_to_cart_button');
    if ( isset( $add_to_cart_btn_txt['replace_text_button_or_hide_button'] ) ) {
        $rule_id                = $add_to_cart_btn_txt['replace_text_button_or_hide_button'];
        $hide_or_replace_txt    = get_post_meta($rule_id,'ct_rbpaqp_hpaatcb_add_to_cart_btn',true);
        if ( is_single() && 'hide_button' == $hide_or_replace_txt ) {
            $is_purchasable     = false; 
        }
    }
    return $is_purchasable;
}