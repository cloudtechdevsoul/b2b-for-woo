<?php

if ( ! defined('ABSPATH') ) {
	exit;
}

add_action('woocommerce_product_data_tabs', 'custom_woocommerce_product_custom_fields');

function custom_woocommerce_product_custom_fields($tabs) {
    
    $product = wc_get_product( get_the_ID() );
    if ( ! str_contains('variable grouped group', $product->get_type()) ) {

        $tabs['role_base_price_product_level'] = array(
                'label'  => __( 'Role Base Pricing', 'cloud_tech_rbpaqpfw' ), // Navigation Label Name
                'target' => 'role_base_price_product_level', // The HTML ID of the tab content wrapper
                'class'  => array(),
            );

        $tabs['ct_rbpaqp_mmq_restriction_product_level'] = array(
                'label'  => __( 'Min Max Quantity', 'cloud_tech_rbpaqpfw' ), // Navigation Label Name
                'target' => 'ct_rbpaqp_mmq_restriction_product_level', // The HTML ID of the tab content wrapper
                'class'  => array(),
            );
        $tabs['ct_rbpaqp_hpav_hide_prdct_nd_var'] = array(
                'label'  => __( 'Hide Product', 'cloud_tech_rbpaqpfw' ), // Navigation Label Name
                'target' => 'ct_rbpaqp_hpav_hide_prdct_nd_var', // The HTML ID of the tab content wrapper
                'class'  => array(),
            );

    }
    $tabs['ct_rbpaqp_hpaatcb_hide_product'] = array(
                'label'  => __( 'Hide Price And Add To Cart Button', 'cloud_tech_rbpaqpfw' ), // Navigation Label Name
                'target' => 'ct_rbpaqp_hpaatcb_hide_product', // The HTML ID of the tab content wrapper
                'class'  => array(),
            );

    return $tabs;

}

add_action('woocommerce_product_data_panels', 'ct_hpav_product_level');
function ct_hpav_product_level() {
    $post_id = get_the_ID();


    ?>
    <div id="role_base_price_product_level" class="panel woocommerce_options_panel">
        <h2> <?php echo esc_html__('Role Base Pricing Setting', 'cloud_tech_rbpaqpfw');?> </h2>

        <?php

        woocommerce_wp_checkbox(
            array(
                'id'          => get_the_ID().'role_base_price_product_level',
                'label'       => __('Enable Role Base Pricing Setting', 'cloud_tech_rbpaqpfw'),
                'desc_tip'    => 'true',
                'description' => __('Enable checkbox to set setting.', 'cloud_tech_rbpaqpfw'),
                'value'       => get_post_meta( get_the_ID(), 'role_base_price_product_level' ,true), 
            )
        );
        ct_rbpaqp_product_cat_restrictions($post_id);
        ct_rbpaqp_customer_base($post_id);
        ct_rbpaqp_role_base($post_id);

        ?>

    </div>
    <div id="ct_rbpaqp_mmq_restriction_product_level" class="panel woocommerce_options_panel">
        <h2> <?php echo esc_html__('Min Max Quantity Setting', 'cloud_tech_rbpaqpfw');?> </h2>

        <?php

        woocommerce_wp_checkbox(
            array(
                'id'          => get_the_ID().'ct_rbpaqp_mmq_restriction_product_level',
                'label'       => __('Enable Min Max Quantity Setting', 'cloud_tech_rbpaqpfw'),
                'desc_tip'    => 'true',
                'description' => __('Enable checkbox to set setting.', 'cloud_tech_rbpaqpfw'),
                'value'       => get_post_meta( get_the_ID(), 'ct_rbpaqp_mmq_restriction_product_level' ,true), 
            )
        );

        ct_rbpaqp_mmq_product_cat_restrictions($post_id);
        ct_rbpaqp_mmq_restriction($post_id);

        ?>

    </div>

    <div id="ct_rbpaqp_hpaatcb_hide_product" class="panel woocommerce_options_panel">
        <h2> <?php echo esc_html__('Hide Price And Add To Cart Button Setting', 'cloud_tech_rbpaqpfw');?> </h2>

        <?php

        woocommerce_wp_checkbox(
            array(
                'id'          => get_the_ID().'ct_rbpaqp_hpaatcb_hide_product',
                'label'       => __('Enable Hide Price And Add To Cart Button', 'cloud_tech_rbpaqpfw'),
                'desc_tip'    => 'true',
                'description' => __('Enable checkbox to set setting.', 'cloud_tech_rbpaqpfw'),
                'value'       => get_post_meta( get_the_ID(), 'ct_rbpaqp_hpaatcb_hide_product' ,true), 
            )
        );

        ct_rbpaqp_hpaatcb_product_cat_restrictions($post_id);
        ct_rbpaqp_hpaatcb_restriction($post_id);

        ?>

    </div>
    <div id="ct_rbpaqp_hpav_hide_prdct_nd_var" class="panel woocommerce_options_panel">
        <h2> <?php echo esc_html__('Hide Product And Variation Setting', 'cloud_tech_rbpaqpfw');?> </h2>

        <?php

        woocommerce_wp_checkbox(
            array(
                'id'          => get_the_ID().'ct_rbpaqp_hpav_hide_product',
                'label'       => __('Enable Hide Product ,And Variation Setting', 'cloud_tech_rbpaqpfw'),
                'desc_tip'    => 'true',
                'description' => __('Enable checkbox to set setting.', 'cloud_tech_rbpaqpfw'),
                'value'       => get_post_meta( get_the_ID(), 'ct_rbpaqp_hpav_hide_product' ,true), 
            )
        );

        ct_rbpaqp_hpav_product_cat_restrictions($post_id);
        ct_rbpaqp_hpav_restriction($post_id);

        ?>

    </div>
    <?php
}


add_action('woocommerce_variation_options_pricing', 'add_variation_custom_field', 10, 3);
// Add a custom field for variations.
function add_variation_custom_field($loop, $variation_data, $variation) {
    $post_id = $variation->ID;
 ?>
    <div id="role_base_price_product_level" class="panel woocommerce_options_panel">
        <h2> <?php echo esc_html__('Role Base Pricing Setting', 'cloud_tech_rbpaqpfw');?> </h2>

        <?php

        woocommerce_wp_checkbox(
            array(
                'id'          => $post_id.'role_base_price_product_level',
                'label'       => __('Enable Role Base Pricing Setting', 'cloud_tech_rbpaqpfw'),
                'desc_tip'    => 'true',
                'description' => __('Enable checkbox to set setting.', 'cloud_tech_rbpaqpfw'),
                'value'       => get_post_meta( $post_id, 'role_base_price_product_level' ,true), 
            )
        );
        ct_rbpaqp_product_cat_restrictions($post_id);
        ct_rbpaqp_customer_base($post_id);
        ct_rbpaqp_role_base($post_id);

        ?>

    </div>
    <div id="ct_rbpaqp_mmq_restriction_product_level" class="panel woocommerce_options_panel">
        <h2> <?php echo esc_html__('Min Max Quantity Setting', 'cloud_tech_rbpaqpfw');?> </h2>

        <?php

        woocommerce_wp_checkbox(
            array(
                'id'          => $post_id.'ct_rbpaqp_mmq_restriction_product_level',
                'label'       => __('Enable Min Max Quantity Setting', 'cloud_tech_rbpaqpfw'),
                'desc_tip'    => 'true',
                'description' => __('Enable checkbox to set setting.', 'cloud_tech_rbpaqpfw'),
                'value'       => get_post_meta( $post_id, 'ct_rbpaqp_mmq_restriction_product_level' ,true), 
            )
        );

        ct_rbpaqp_mmq_product_cat_restrictions($post_id);
        ct_rbpaqp_mmq_restriction($post_id);

        ?>

    </div>
    <div id="ct_rbpaqp_hpav_hide_prdct_nd_var" class="panel woocommerce_options_panel">
        <h2> <?php echo esc_html__('Hide Product And Variation Setting', 'cloud_tech_rbpaqpfw');?> </h2>

        <?php

        woocommerce_wp_checkbox(
            array(
                'id'          => $post_id.'ct_rbpaqp_hpav_hide_product',
                'label'       => __('Enable Hide Variation Setting', 'cloud_tech_rbpaqpfw'),
                'desc_tip'    => 'true',
                'description' => __('Enable checkbox to set setting.', 'cloud_tech_rbpaqpfw'),
                'value'       => get_post_meta( $post_id, 'ct_rbpaqp_hpav_hide_product' ,true), 
            )
        );

        ct_rbpaqp_hpav_product_cat_restrictions($post_id);
        ct_rbpaqp_hpav_restriction($post_id);

        ?>

    </div>
    <?php
}