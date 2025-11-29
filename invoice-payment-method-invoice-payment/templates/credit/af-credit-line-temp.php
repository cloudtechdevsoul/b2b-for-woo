<?php
defined( 'ABSPATH' ) || die();
// validation Visible columns PDF.
$af_ips_company_logo                   = get_option( 'af_ips_company_logo' );
$af_ips_company_details_chk_box        = get_option( 'af_ips_company_details_chk_box' );
$af_ips_company_name                   = get_option( 'af_ips_company_name' );
$af_ips_cstm_ship_details              = get_option( 'af_ips_cstm_ship_details' );
$af_customer_invice_details            = get_option( 'af_customer_invice_details' );
$af_credit_note_product_name           = get_option( 'af_ips_show_credit_note_product_name' );
$af_imge                               = get_option( 'af_ips_show_credit_note_product_img' );
$ips_company_name                      = get_option( 'af_ips_template_content_field' );
$ips_company_detail                    = get_option( 'af_ips_company_details' );
$af_sku                                = get_option( 'af_ips_show_credit_note_product_sku' );
$af_discrption                         = get_option( 'af_ips_show_short_description' );
$af_quantity                           = get_option( 'af_ips_show_quantity' );
$af_regular_price                      = get_option( 'af_ips_show_regular_price' );
$af_on_sale                            = get_option( 'af_ips_show_onsale_price' );
$af_price                              = get_option( 'af_ips_show_product_price' );
$af_total                              = get_option( 'af_ips_show_product_total' );
$af_tax                                = get_option( 'af_ips_show_tax' );
$af_ips_credit_notes_show_sub_total    = get_option( 'af_ips_credit_notes_show_sub_total' );
$af_toatl_inc_taxt                     = get_option( 'af_ips_show_total_inc_tax' );
$af_line_total                         = get_option( 'af_ips_show_line_total' );
$af_discount_pernt                     = get_option( 'af_ips_show_discount_percentage' );
$af_ips_show_note                      = get_option( 'af_ips_show_note' );
$af_ips_show_credit_notes              = get_option( 'af_ips_show_credit_notes' );
$af_ips_template_bg_color              = get_option( 'af_ips_template_bg_color' );
$af_ips_template_header_color          = get_option( 'af_ips_template_header_color' );
$af_ips_template_header_text_color     = get_option( 'af_ips_template_header_text_color' );
$af_ips_customer_data_back_color       = get_option( 'af_ips_customer_data_back_color' );
$af_ips_table_customer_info_font_color = get_option( 'af_ips_table_customer_info_font_color' );
$af_ips_template_total_section_color   = get_option( 'af_ips_template_total_section_color' );
$af_ips_total_section_font_color       = get_option( 'af_ips_total_section_font_color' );
$af_ips_footer_back_color              = get_option( 'af_ips_footer_back_color' );
$af_ips_footer_font_color              = get_option( 'af_ips_footer_font_color' );
$af_ips_show_credit_note_footer        = get_option( 'af_ips_show_credit_note_footer' );
$ips_order_subtotal                    = $order->get_subtotal();
$ips_total                             = $order->get_total();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<div class="af-pdf-wrapper">
			<section class="af-pdf-header">
				<div class="af-store-info-box">
					<ul>
						<li>
							<?php if ( $af_ips_company_logo ) { ?>
							<img src="<?php echo esc_url( $af_log_url ); ?>">
							<?php } ?>
							<?php if ( $af_ips_company_name ) { ?>
							<p><?php echo esc_attr( $ips_company_name ); ?></p>
							<?php } ?>
							<?php if ( $af_ips_company_details_chk_box ) { ?>
							<p><?php echo esc_attr( $ips_company_detail ); ?></p>
							<?php } ?>
						</li>
					</ul>
				</div>
				<div class="af-invoice-info-box">
					<h2><?php esc_html_e( 'Credit Note', 'af_ips_invoices' ); ?></h2>
					<p><label><?php echo esc_html__( 'Date:', 'af_ips_invoices' ); ?></label> <span><?php echo esc_attr( $af_invoice_date ); ?></span></p>
				</div>
			</section>

			<section class="af-line-temp-mid-section">
				<?php if ( $af_customer_invice_details ) { ?>
				<div class="af-line-temp-mid-sec-left">
					<h4><?php esc_html_e( 'Customer Details', 'af_ips_invoices' ); ?></h4>
					<ul>
						<li><p><label><?php echo esc_html__( 'Company Name:', 'af_ips_invoices' ); ?></label> <span><?php echo esc_attr( $ips_billing_company ); ?></span></p></li>
						<li><p><label><?php echo esc_html__( 'Address:', 'af_ips_invoices' ); ?></label><span><?php echo esc_attr( $ips_billing_address ); ?></span></p></li>
						<li><p><label><?php esc_html_e( 'Phone:', 'af_ips_invoices' ); ?></label><span><?php echo esc_attr( $ips_billing_phone ); ?></span></p></li>
						<li><p><label><?php esc_html_e( 'E-Mail:', 'af_ips_invoices' ); ?></label><span><?php echo esc_attr( $billing_email ); ?></span></p></li>
					</ul>
				</div>
				<?php } ?>
			</section>

			<section class="af-pdf-content">
				<table>
					<thead>
						<tr>
							<?php if ( $af_imge ) { ?>
							<th><?php echo esc_html__( 'Image', 'af_ips_invoices' ); ?></th>	 
							<?php } ?>
							<?php if ( $af_credit_note_product_name ) { ?>
							<th><?php echo esc_html__( 'Product', 'af_ips_invoices' ); ?></th>
							<?php } ?>
							<?php if ( $af_sku ) { ?>
							<th><?php echo esc_html__( 'Description', 'af_ips_invoices' ); ?></th>
							<?php } ?>	
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( $order->get_items() as $item_id => $item ) {
							$product_id     = $item->get_product_id();
							$_product       = wc_get_product( $product_id );
							$product        = $item->get_product();
							$thumbnail      = $product->get_image( array( 100, 100 ) );
							$sku            = $product->get_sku();
							$product_name   = $item->get_name();
							$quantity       = $item->get_quantity();
							$subtotal       = $item->get_subtotal();
							$price_html     = $product->get_price();
							$regular_price  = $_product->get_regular_price();
							$sale_price     = $_product->get_sale_price();
							$af_tax         = $item->get_subtotal_tax();
							$tax_percentage = $af_tax / 100;
							$total          = $item->get_total();
							$total_inc_taxt = $af_tax + $total;
							$discription    = $product->get_short_description();
							?>
						<tr>
							<?php if ( $af_imge ) { ?>
							<td class="af-ip-pro-img">
								<?php echo wp_kses_post( $thumbnail ); ?>
							</td>
							<?php } ?>	
							<?php if ( $af_credit_note_product_name ) { ?>
							<td class="product_sku_td"><span><?php echo wp_kses_post( $product_name ); ?></td><?php } ?>
							<?php if ( $af_sku ) { ?>
							<td><?php echo wp_kses_post( 'Product Fully Refunded' ); ?></td>
							<?php } ?>
						</tr>
							<?php
						}//end foreach
						?>
						
					</tbody>
				</table>
			</section>

			<section class="af-subtotal-section">
				<div class="af-pdf-logo"></div>
				<div class="af-invoice-subtotal-box">
					<ul>
					<?php if ( get_option( 'af_ips_credit_notes_show_sub_total' ) ) { ?>
							
						<li><p><label><?php esc_html_e( 'Subtotal', 'af_ips_invoices' ); ?></label><font><?php echo wp_kses_post( wc_price( $af_refund_subtotal ) ); ?></font></p></li>
					<?php } ?>	
					<?php if ( get_option( 'af_ips_credit_notes_show_total_tax' ) ) { ?>	
						<li><p class="af-total-label"><label><?php esc_html_e( 'Refunded Amount', 'af_ips_invoices' ); ?></label><font><?php echo wp_kses_post( wc_price( $af_order_total ) ); ?></font></p></li>
					<?php } ?>	
					</ul>
				</div> 
			</section>

		<?php if ( $af_ips_show_credit_notes ) { ?>
				<section id="invoice_note">
					<h3><?php echo esc_html__( 'Note :', 'af_ips_invoices' ); ?></h3>
					<p><?php echo esc_attr( get_option( 'af_ips_show_credit_note_notes' ) ); ?></p>
				</section>
		<?php } ?>
		<?php if ( $af_ips_show_credit_note_footer ) { ?>
			<div class="af-footer-bar"><?php echo esc_attr( get_option( 'af_ips_show_credit_note_footer_text' ) ); ?></div> 
		<?php } ?>
	</div>

</body>
</html>
