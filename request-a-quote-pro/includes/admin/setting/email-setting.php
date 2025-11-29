<form method="post">
    <?php wp_nonce_field('cloud_tech_rfq','cloud_tech_rfq'); ?>
    <h2><?php echo esc_html__('Supportive Variable','cloud_tech_rfq'); ?></h2>
    <p><?php echo esc_html__('use variable {quote_id} to print Quote ID.','cloud_tech_rfq'); ?></p>
    <p><?php echo esc_html__('use variable {quote_status} to print Quote status.','cloud_tech_rfq'); ?></p>
    <p><?php echo esc_html__('use variable {quote_date} to print Quote Date.','cloud_tech_rfq'); ?></p>
    <p><?php echo esc_html__('use variable {quote_item_table} to print Quote item table.','cloud_tech_rfq'); ?></p>
    <p><?php echo esc_html__('use variable {quote_cart_subtotal} to print Quote Subtotal.','cloud_tech_rfq'); ?></p>
    <p><?php echo esc_html__('use variable {quote_cart_tax} to print Quote Tax.','cloud_tech_rfq'); ?></p>
    <p><?php echo esc_html__('use variable {quote_cart_total} to print Quote Total.','cloud_tech_rfq'); ?></p>
    <p><?php echo esc_html__('use variable {quote_cart_billing_shipping_detail} to print Quote Billing Detail.','cloud_tech_rfq'); ?></p>
	<table class="form-table wp-list-table widefat fixed striped table-view-list ct-quote-table">
        <thead>
            <tr>
                <th><?php echo esc_html__('Enable Setting','cloud_tech_rfq'); ?></th>
                <th><?php echo esc_html__('Email Content','cloud_tech_rfq'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach( wc_get_order_statuses() as $order_status_key => $order_status_value ){

            $enable_setting         =   get_option('ct_rfq_enable_setting_for_'. $order_status_key);
            $subject                =   get_option('ct_rfq_email_subject_for_'. $order_status_key);

            ?>
            <tr>
                <th><input type="checkbox" name="ct_rfq_enable_setting_for_<?php echo esc_attr( $order_status_key );  ?>" value="checked" <?php echo esc_attr( $enable_setting ); ?> ><i style="margin-left:10px;" ><?php echo esc_attr( $order_status_value ); ?></i></th>
                <td>
                    <table>
                        <tbody>
                            <tr>
                                <th><?php echo esc_html__('Subject','cloud_tech_rfq'); ?></th>
                                <td><input type="text" name="ct_rfq_email_subject_for_<?php echo esc_attr( $order_status_key );  ?>" value="<?php echo esc_attr( $subject ); ?>"></td>
                            </tr>
                            <tr>
                                <th><?php echo esc_html__('Additional Content','cloud_tech_rfq'); ?></th>
                                <td><?php  $editor_name    = 'ct_rfq_email_additional_content_for_'. $order_status_key;  wp_editor( get_option( $editor_name )  ,$editor_name, ); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        <?php } ?>

        </tbody>
</table>
<div>
    
</div>
<div class="div2"><p class="submit"><input type="submit" name="save_email_setting" id="submit" class="button button-primary" value="Save Settings"></p></div>
</form>
