<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

add_action( 'init',  'ct_tepfw_endpoints', 0 );
add_filter( 'query_vars',  'ct_tepfw_query_vars', 0 );

add_action('woocommerce_before_account_navigation',  'ct_tepwf_flush_rewrite_rule');
add_filter( 'the_title',  'ct_tepwfendpoint_title', 10, 2 );

add_filter( 'woocommerce_account_menu_items',  'ct_tepwfnew_menu_items');
add_action( 'woocommerce_account_ct-tax-exempt-pro_endpoint',  'ct_tepwfendpoint_content');
add_action( 'wp_loaded','cloud_tech_save_form_data');

$checkout_page  = !empty( get_option('ct_tepfw_tax_exempt_position_on_checkout') ) ? get_option('ct_tepfw_tax_exempt_position_on_checkout') : 'woocommerce_before_checkout_billing_form';

add_action( $checkout_page ,'cloud_tech_show_tax_exempt_checkbox');

function ct_tepwf_flush_rewrite_rule() {
    flush_rewrite_rules();
}

function ct_tepfw_endpoints() {


   $is_user_aplicable  = chek_user_is_tax_exemptes( $check_from_car_or_my_account = 'my-account' );

   if ( ! empty( $is_user_aplicable ) ) {

    add_rewrite_endpoint( 'ct-tax-exempt-pro', EP_ROOT | EP_PAGES );
}


flush_rewrite_rules();

}

function ct_tepfw_query_vars( $vars ) {
    $vars[] = 'ct-tax-exempt-pro';
    return $vars;
}

function ct_tepwfendpoint_title( $title, $id ) {
    global $wp_query;
    $is_endpoint = isset( $wp_query->query_vars['ct-tax-exempt-pro'] );
    if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
        $title = esc_html__( 'Tax Exemption', 'addify_tax_exempt' );

        remove_filter( 'the_title', 'ct_tepwfendpoint_title' );
    }

    return $title;
}



function ct_tepwfnew_menu_items( $items ) {
    $is_user_aplicable  = chek_user_is_tax_exemptes( $check_from_car_or_my_account = 'my-account' );

    if ( ! empty( $is_user_aplicable ) ) {

        $items['ct-tax-exempt-pro'] = esc_html__( 'Tax Exemption PRo', 'addify_tax_exempt' );
    }

    return $items;
}

function ct_tepwfendpoint_content() {

 $text_field_setting        = ( array ) get_option('ct_tepfw_enable_text_field');
 $textarea_field_setting    = ( array ) get_option('ct_tepfw_enable_textarea_field');
 $fileupload_field          = ( array ) get_option('ct_tepfw_enable_fileupload_field');
 $can_user_submit_again     =  !empty( get_user_meta( get_current_user_id() ,'ct_tepfw_is_tax_exempted' , true ) ) && empty( get_user_meta( get_current_user_id() ,'ct_tepfw_enable_resubmit_for_request_again' , true ) ) ? 'readonly' : '' ;

 $status_color              = '';

 if ( 'pending' == get_user_meta( get_current_user_id() ,'ct_tepfw_is_tax_exempted' , true ) ) {
    $status_color = 'yellow';
 }

 if ( 'approved' == get_user_meta( get_current_user_id() ,'ct_tepfw_is_tax_exempted' , true ) ) {
    $status_color = 'green';
 }

 if ( 'cancelled' == get_user_meta( get_current_user_id() ,'ct_tepfw_is_tax_exempted' , true ) ) {
    $status_color = 'red';
 }
 ?>
 <form method="post" enctype="multipart/form-data" >
    <h4><?php echo esc_html__('Tax Exempt Detail',''); ?></h4>
    <table class="wp-list-table widefat fixed striped table-view-list af-purchased-product-detail-table" >

        <tbody>
            <tr>
                <th><?php echo esc_html__( 'Status' , 'cloud_tech_tepfw' ); ?></th>
                <td>
                    <i style="background: <?php echo esc_attr( $status_color ); ?>;" class ="button primary-button" >
                    <?php echo esc_attr( ucwords( get_user_meta( get_current_user_id() ,'ct_tepfw_is_tax_exempted' , true ) ) ); ?>
                    </i>
                </td>
            </tr>
            <?php if ( isset( $text_field_setting['enable'] ) ) {  ?>
                <tr>
                    <th><?php echo esc_attr( get_option('ct_tepfw_text_field_label') ); ?></th>
                    <td>
                        <input type="text" <?php echo esc_attr( $can_user_submit_again ); ?> name="ct_tepfw_text_field_label" value="<?php echo esc_attr( get_user_meta( get_current_user_id() ,'ct_tepfw_text_field_data' , true ) ); ?>" <?php echo esc_attr( isset( $text_field_setting['required']) && ! empty( $text_field_setting['required'] )  ? 'required' : '' ); ?> >
                    </td>
                </tr>
            <?php }

            if ( isset( $textarea_field_setting['enable'] ) ) {  ?>
                <tr>
                    <th><?php echo esc_attr( get_option('ct_tepfw_textarea_field_label') ); ?></th>
                    <td>
                        <textarea <?php echo esc_attr( $can_user_submit_again ); ?> name="ct_tepfw_textarea_field_label" <?php echo esc_attr( isset( $textarea_field_setting['required']) && ! empty( $textarea_field_setting['required'] )  ? 'required' : '' ); ?>><?php echo esc_attr( get_user_meta( get_current_user_id() ,'ct_tepfw_textarea_field_data' , true ) ); ?></textarea>
                    </td>
                </tr>
            <?php }

            if ( isset( $fileupload_field['enable'] ) ) {  ?>
                <tr>
                    <th><?php echo esc_attr( get_option('ct_tepfw_fileupload_field_label') ); 
                ?></th>
                <td>
                    <?php if ( empty( get_user_meta( get_current_user_id() ,'ct_tepfw_fileupload_field_data' , true ) ) ) { ?>
                        <input type="file" name="ct_tepfw_fileupload_field_label" <?php echo esc_attr( isset( $fileupload_field['required']) && ! empty( $fileupload_field['required'] )  ? 'required' : '' ); ?> accept=".<?php echo esc_attr( str_replace(',',',.',get_option('ct_tepfw_allowed_file_types')) ); ?>" >

                    <?php } else { ?>

                        <a target="_blank" href="<?php echo esc_url( wp_get_attachment_url( get_user_meta( get_current_user_id() ,'ct_tepfw_fileupload_field_data' , true ) ) ); ?>" class="fa fa-eye"></a>
                    <?php } ?>

                </td>
            </tr>
        <?php }

        if ( ! empty( get_user_meta( get_current_user_id() ,'ct_tepfw_additional_message',true) ) ){ ?>
            <tr>
                <th><?php echo esc_html__('Additional Message','cloud_tech_tepfw'); ?></th>
                <td>
                    <?php echo wp_kses_post( get_user_meta( get_current_user_id() ,'ct_tepfw_additional_message',true) ); ?>
                </td>
            </tr>
        <?php }

        if ( 'pending' == ( get_user_meta( get_current_user_id() ,'ct_tepfw_is_tax_exempted',true) ) && ! empty( get_option( 'ct_tepfw_pendind_request_message') ) ){ ?>
            <tr>
                <th><?php echo esc_html__('Pending Request Message','cloud_tech_tepfw'); ?></th>
                <td>
                    <?php echo wp_kses_post( get_option( 'ct_tepfw_pendind_request_message') ); ?>
                </td>
            </tr>
        <?php } 

        if ( 'approved' == ( get_user_meta( get_current_user_id() ,'ct_tepfw_is_tax_exempted',true) ) && ! empty( get_option( 'ct_tepfw_accepted_request_message') ) ){ ?>
            <tr>
                <th><?php echo esc_html__('Approved Request Message','cloud_tech_tepfw'); ?></th>
                <td>
                    <?php echo wp_kses_post( get_option( 'ct_tepfw_accepted_request_message') ); ?>
                </td>
            </tr>
        <?php } 

        if ( 'cancelled' == ( get_user_meta( get_current_user_id() ,'ct_tepfw_is_tax_exempted',true) ) && ! empty( get_option( 'ct_tepfw_rejected_request_message') ) ){ ?>
            <tr>
                <th><?php echo esc_html__('Cancell Request Message','cloud_tech_tepfw'); ?></th>
                <td>
                    <?php echo wp_kses_post( get_option( 'ct_tepfw_rejected_request_message') ); ?>
                </td>
            </tr>
        <?php } ?>

    </tbody>

</table>
<?php wp_nonce_field('cloud_tech_tepfw_nonce','cloud_tech_tepfw_nonce');

if ( empty( $can_user_submit_again ) ) {  ?>
    <input type="submit" name="ct_tepfw_submit_ct_tax_exempt_detail" value="Save Changes">
<?php } ?>
<input type="hidden" name="ct_tepfw_current_user_id" value="<?php echo esc_attr( get_current_user_id() ); ?>">
</form>
<?php

}

function cloud_tech_save_form_data() {

    $nonce      = isset( $_POST['cloud_tech_tepfw_nonce'] ) ? sanitize_text_field( $_POST['cloud_tech_tepfw_nonce'] ) : '';


    if ( isset( $_POST['ct_tepfw_submit_ct_tax_exempt_detail'] ) && ! wp_verify_nonce( $nonce , 'cloud_tech_tepfw_nonce' ) ) {
        wp_die( esc_html__('Security Violated !' , 'cloud_tech_tepfw') );
    }

    if ( isset( $_POST['ct_tepfw_submit_ct_tax_exempt_detail'] ) ) {


        $user_id                    = sanitize_text_field( $_POST['ct_tepfw_current_user_id'] );
        $text_field_setting         = ( array ) get_option('ct_tepfw_enable_text_field');
        $textarea_field_setting     = ( array ) get_option('ct_tepfw_enable_textarea_field');
        $fileupload_field           = ( array ) get_option('ct_tepfw_enable_fileupload_field');

        $can_user_submit_again   =  !empty( get_user_meta( $user_id ,'ct_tepfw_is_tax_exempted' , true ) ) && empty( get_user_meta( $user_id ,'ct_tepfw_enable_resubmit_for_request_again' , true ) ) ? 'readonly' : '' ;

        if ( ! empty( $can_user_submit_again ) ) {
         return;
     }

     if ( isset( $text_field_setting['required'] ) && !empty( $text_field_setting['required'] ) ) {

        wc_add_notice( get_option('ct_tepfw_text_field_label') .' is required . Please Fill Field First.'  ,'error');
        return;

    }

    if ( isset( $textarea_field_setting['required'] ) && !empty( $textarea_field_setting['required'] ) ) {

        wc_add_notice( get_option('ct_tepfw_textarea_field_label') .' is required . Please Fill Field First.'  ,'error');
        return;

    }

    if ( isset( $text_field_setting['required'] ) && !empty( $text_field_setting['required'] ) && empty(  get_user_meta( get_current_user_id() ,'ct_tepfw_fileupload_field_data' , true ) ) ) {

        wc_add_notice( get_option('ct_tepfw_text_field_label') .' is required . Please Fill Field First.'  ,'error');
        return;

    }





    if ( empty( get_user_meta( get_current_user_id() ,'ct_tepfw_fileupload_field_data' , true ) ) && isset( $_FILES['ct_tepfw_fileupload_field_label'] ) ) {

        $file_data      = sanitize_meta('',$_FILES['ct_tepfw_fileupload_field_label'],'');
        $file_type      = isset( $file_data['type'] ) ?  explode( '/' , $file_data['type'] ) : [''];
        $file_type      = end( $file_type );

        if ( !empty( get_option('ct_tepfw_allowed_file_types') ) && ! empty( $file_type ) && ! str_contains( get_option('ct_tepfw_allowed_file_types'), $file_type ) ) {

            wc_add_notice( __(' File Type Not Allowed ','cloud_tech_tepfw') ,'error');
            return;

        }


        $file_name      = sanitize_text_field( wp_unslash( $file_data['name'] ) );

        include_once ABSPATH . 'wp-admin/includes/image.php';
        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/media.php';

        $uploaddir      = wp_upload_dir();
        $file           = sanitize_text_field( wp_unslash( $file_data['name'] ) );
        $uploadimgfile  = $uploaddir['path'] . '/' . basename( $file );

        if ( isset( $file_data['tmp_name'] ) ) {
            $tempname   = sanitize_text_field( $file_data['tmp_name'] );

            copy( $tempname, $uploadimgfile );

            $filename       = basename( $uploadimgfile );

            $wp_filetype    = wp_check_filetype( basename( $filename ), null );

            $attachment     = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_status'    => 'inherit',
            );

            $attach_id      = wp_insert_attachment( $attachment, $uploadimgfile );

            update_user_meta( $user_id ,'ct_tepfw_fileupload_field_data' , $attach_id );

        }

    }

    $text_field_label       = isset( $_POST['ct_tepfw_text_field_label'] ) ? sanitize_text_field( $_POST['ct_tepfw_text_field_label'] ) : '';

    update_user_meta( $user_id ,'ct_tepfw_text_field_data', $text_field_label );
    $textarea_field         = isset( $_POST['ct_tepfw_textarea_field_label'] ) ? sanitize_text_field( $_POST['ct_tepfw_textarea_field_label'] ) : ''; 
    update_user_meta( $user_id ,'ct_tepfw_textarea_field_data', $textarea_field );

    update_user_meta( $user_id, 'ct_tepfw_enable_resubmit_for_request_again','' ); 

    update_user_meta( $user_id, 'ct_tepfw_is_tax_exempted','cancel' );


    $is_user_aplicable  = chek_user_is_tax_exemptes( $check_from_car_or_my_account = 'my-account' );

    if ( ! empty( $is_user_aplicable ) && ! empty( get_user_meta( $is_user_aplicable ,'ct_tepfw_auto_approve_tax_exempt_request',true ) ) ) {


        wc_add_notice( __(' Congratulations your Tax Exemption request is accepted. ','cloud_tech_tepfw') ,'success');
        update_user_meta( $user_id, 'ct_tepfw_is_tax_exempted','approved' );


    }


    notify_to_customer( $user_id );

}

}


function cloud_tech_show_tax_exempt_checkbox() {

    $is_user_aplicable      = chek_user_is_tax_exemptes( $check_from_car_or_my_account = 'checkout' );

    if ( ! empty( $is_user_aplicable ) ) {

        $additional_text    = 'custom_message' === (string) get_post_meta( $is_user_aplicable,'ct_tepfw_show_default_or_custom_msg_with_checkbox',true ) ? get_post_meta( $is_user_aplicable,'ct_tepfw_custom_message',true ) : 'claim for tax exempt {tax_exempt_link}';

        $account_page_url   = '<a href="'.wc_get_account_endpoint_url('ct-tax-exempt-pro').'">Click Here</a>';

        $additional_text    = str_replace( '{tax_exempt_link}' , $account_page_url, $additional_text );

        if ( 'approved' === (string) get_user_meta( get_current_user_id() ,'ct_tepfw_is_tax_exempted', true) && !empty( get_post_meta( $is_user_aplicable ,'ct_tepfw_remove_tax_automatically', true) ) ) {
            return;
        }
        
        wp_nonce_field('cloud_tech_tepfw_nonce','cloud_tech_tepfw_nonce'); ?>

        <table class="form-data">
            <tr>
                <th><?php echo esc_html__('Tax Exempt','cloud_tech_tepfw'); ?></th>
                <td>
                    <?php if ( 'approved' === (string) get_user_meta( get_current_user_id() ,'ct_tepfw_is_tax_exempted', true)  ): ?>
                    <input type="checkbox" name="cloud_tech_tax_exempt_checkbox">
                <?php endif ?>
                <p>
                    <?php echo wp_kses_post( $additional_text );?>
                </p>
            </td>
        </tr>
    </table>
    <?php
}
}

function cloud_tech_tepfw_remove_tax_from_cart( ) {
    WC()->customer->set_is_vat_exempt( false );

    if ( is_admin() ) {
        return;
    }


    $is_user_aplicable          = chek_user_is_tax_exemptes( 'checkout' );

    if ( !empty( $is_user_aplicable ) && 'approved' === (string) get_user_meta( get_current_user_id() ,'ct_tepfw_is_tax_exempted', true) ) {

        $nonce                  = isset( $_POST['cloud_tech_tepfw_nonce'] ) ? sanitize_text_field( $_POST['cloud_tech_tepfw_nonce'] ) : '';


        if ( isset( $_POST['cloud_tech_tax_exempt_checkbox'] ) && ! wp_verify_nonce( $nonce , 'cloud_tech_tepfw_nonce' ) ) {
            wp_die( esc_html__('Security Violated !' , 'cloud_tech_tepfw') );
        }

        $remove_tax_exempt      = false;

        if (  !empty( get_post_meta( $is_user_aplicable ,'ct_tepfw_remove_tax_automatically', true) ) ) {
            $remove_tax_exempt  = true; 
        } else if ( isset( $_POST['cloud_tech_tax_exempt_checkbox'] ) ) {
            $remove_tax_exempt  = true; 
        }

        if ( ! $remove_tax_exempt ) {
            return;
        }
        $tax_type               =   get_post_meta( $is_user_aplicable ,'ct_tepfw_remove_tax_exempt_type', true);
        if ( 'product_tax_only' == $tax_type ) {

            foreach ( wc()->cart->get_cart() as $cart_item ) {

                $cart_item['data']->set_tax_class('Zero Rate');

            }

        } else if ( 'shipping_tax_only' != $tax_type ) {
            WC()->customer->set_is_vat_exempt( true );
        }
    }
}
add_action( 'woocommerce_before_calculate_totals', 'cloud_tech_tepfw_remove_tax_from_cart', 10, );

add_filter('woocommerce_product_get_tax_class', 'cloud_tech_tepfw_remove_vat_exempt_for_product_tax', 10,2);
function cloud_tech_tepfw_remove_vat_exempt_for_product_tax($tax_class,$product) {

    if ( is_admin() ) {
        return;
    }


    $is_user_aplicable          = chek_user_is_tax_exemptes( 'checkout' );

    if ( !empty( $is_user_aplicable ) && 'approved' === (string) get_user_meta( get_current_user_id() ,'ct_tepfw_is_tax_exempted', true) ) {

        $nonce                  = isset( $_POST['cloud_tech_tepfw_nonce'] ) ? sanitize_text_field( $_POST['cloud_tech_tepfw_nonce'] ) : '';


        if ( isset( $_POST['cloud_tech_tax_exempt_checkbox'] ) && ! wp_verify_nonce( $nonce , 'cloud_tech_tepfw_nonce' ) ) {
            wp_die( esc_html__('Security Violated !' , 'cloud_tech_tepfw') );
        }

        $remove_tax_exempt      = false;

        if (  !empty( get_post_meta( $is_user_aplicable ,'ct_tepfw_remove_tax_automatically', true) ) ) {
            $remove_tax_exempt  = true; 
        } else if ( isset( $_POST['cloud_tech_tax_exempt_checkbox'] ) ) {
            $remove_tax_exempt  = true; 
        }


        $tax_type               =   get_post_meta( $is_user_aplicable ,'ct_tepfw_remove_tax_exempt_type', true);
        if ( 'product_tax_only' == $tax_type &&  !WC()->customer->is_vat_exempt() && $remove_tax_exempt ) {

            return 'zero-rated';
        }
    }


    return $tax_class;
}


add_filter( 'woocommerce_package_rates', 'adjust_shipping_rate', 10, 2 );
function adjust_shipping_rate( $rates, $package ) {

    $is_user_aplicable          = chek_user_is_tax_exemptes( 'checkout' );

    if ( !empty( $is_user_aplicable ) && 'approved' === (string) get_user_meta( get_current_user_id() ,'ct_tepfw_is_tax_exempted', true) ) {

        $nonce                  = isset( $_POST['cloud_tech_tepfw_nonce'] ) ? sanitize_text_field( $_POST['cloud_tech_tepfw_nonce'] ) : '';


        if ( isset( $_POST['cloud_tech_tax_exempt_checkbox'] ) && ! wp_verify_nonce( $nonce , 'cloud_tech_tepfw_nonce' ) ) {
            wp_die( esc_html__('Security Violated !' , 'cloud_tech_tepfw') );
        }

        $remove_tax_exempt      = false;

        if (  !empty( get_post_meta( $is_user_aplicable ,'ct_tepfw_remove_tax_automatically', true) ) ) {
            $remove_tax_exempt  = true; 
        } else if ( isset( $_POST['cloud_tech_tax_exempt_checkbox'] ) ) {
            $remove_tax_exempt  = true; 
        }

        if ( ! $remove_tax_exempt ) {
            return;
        }
        $tax_type               =   get_post_meta( $is_user_aplicable ,'ct_tepfw_remove_tax_exempt_type', true);
        if ( 'shipping_tax_only' == $tax_type ) {

            foreach ( $rates as $rate_key => $rate ) {

                $taxes = $rate->get_taxes();

                // Set each tax to zero
                foreach ($taxes as $tax_key => $tax){
                    $taxes[$tax_key] = 0;
                }

                // Set modified taxes back
                $rates[$rate_key]->set_taxes( $taxes );
            }

        }
    }
    
    return $rates;
}