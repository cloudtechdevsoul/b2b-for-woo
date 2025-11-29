<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
// validation Visible columns PDF.
$af_imge                     = get_option( 'af_ips_show_credit_note_product_img' );
$ips_company_name            = get_option( 'af_ips_template_content_field' );
$ips_company_detail          = get_option( 'af_ips_company_details' );
$af_credit_note_product_name = get_option( 'af_ips_show_credit_note_product_name' );
$af_discrption               = get_option( 'af_ips_show_credit_note_product_sku' );
// $af_discrption                         = get_option( 'af_ips_show_short_description' );
$af_ips_show_credit_notes              = get_option( 'af_ips_show_credit_notes' );
$af_quantity                           = get_option( 'af_ips_show_quantity' );
$af_regular_price                      = get_option( 'af_ips_show_regular_price' );
$af_on_sale                            = get_option( 'af_ips_show_onsale_price' );
$af_price                              = get_option( 'af_ips_show_product_price' );
$af_total                              = get_option( 'af_ips_show_product_total' );
$af_tax                                = get_option( 'af_ips_show_tax' );
$af_toatl_inc_taxt                     = get_option( 'af_ips_show_total_inc_tax' );
$af_line_total                         = get_option( 'af_ips_show_line_total' );
$af_discount_pernt                     = get_option( 'af_ips_show_discount_percentage' );
$af_ips_show_note                      = get_option( 'af_ips_show_note' );
$af_ips_show_credit_note_footer        = get_option( 'af_ips_show_credit_note_footer' );
$af_ips_template_bg_color              = get_option( 'af_ips_template_bg_color' );
$af_ips_template_header_color          = get_option( 'af_ips_template_header_color' );
$af_ips_template_header_text_color     = get_option( 'af_ips_template_header_text_color' );
$af_ips_customer_data_back_color       = get_option( 'af_ips_customer_data_back_color' );
$af_ips_table_customer_info_font_color = get_option( 'af_ips_table_customer_info_font_color' );
$af_ips_template_total_section_color   = get_option( 'af_ips_template_total_section_color' );
$af_ips_total_section_font_color       = get_option( 'af_ips_total_section_font_color' );
$af_ips_footer_back_color              = get_option( 'af_ips_footer_back_color' );
$af_ips_footer_font_color              = get_option( 'af_ips_footer_font_color' );
$af_ips_footer_content                 = get_option( 'af_ips_footer_content' );
// $af_refund_subtotal                    = $order->get_subtotal();
// $af_order_total                             = $order->get_total();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<style>
	.af-pdf-header{
			background-color: <?php echo esc_attr( $af_ips_template_header_color ); ?> !important;
			color: <?php echo esc_attr( $af_ips_template_header_text_color ); ?> !important;
		}
		.af-pdf-footer {
			background-color: <?php echo esc_attr( $af_ips_customer_data_back_color ); ?> !important;
			color: <?php echo esc_attr( $af_ips_table_customer_info_font_color ); ?> !important;
		}
		.af-subtotal-section {
			background-color: <?php echo esc_attr( $af_ips_template_total_section_color ); ?> !important;
			color: <?php echo esc_attr( $af_ips_total_section_font_color ); ?> !important;
		}
		.af-footer-bar {
			background-color: <?php echo esc_attr( $af_ips_footer_back_color ); ?> !important;
			color: <?php echo esc_attr( $af_ips_footer_font_color ); ?> !important;
		}
		/*haris rehman */
		.af-subtotal-section{
			margin-top: 30px !important;
		}
		.af-invoice-info-content {
			margin-top:20px !important;
		}
</style>
<body>
	<div class="af-pdf-wrapper">
		<section class="af-pdf-header">
			<div class="af-invoice-info">
				<div class="af-pdf-logo">
					<img src="<?php echo esc_url( $af_log_url ); ?>">
				</div>
				<div class="af-invoice-info-content">
					<h1><?php esc_html_e( 'Credit Note:', 'af_ips_invoices' ); ?></h1>
					<ul>
						<li><p><label><?php esc_html_e( 'Credit Note No:', 'af_ips_invoices' ); ?></label><span><?php echo esc_attr( $af_crdit_number_final ); ?></span></p></li>
						<li><p><label><?php esc_html_e( 'Oder NO:', 'af_ips_invoices' ); ?></label><span><?php esc_attr_e( $order_id ); ?></span></p></li>
						<li><p><label><?php esc_html_e( 'Date:', 'af_ips_invoices' ); ?></label><span><?php esc_attr_e( $af_invoice_date ); ?></span></p></li>
						<li><p><label><?php esc_html_e( 'Refunded amount:', 'af_ips_invoices' ); ?></label><span><?php esc_attr_e( $af_order_total ); ?></span></p></li>
					</ul>
				</div>
			</div>
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
						<?php if ( $af_discrption ) { ?>
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
							<?php if ( $af_discrption ) { ?>
							<td><?php echo esc_html__( 'Product Fully Refunded' ); ?></td>
							<?php } ?>
						</tr>
						<?php
					}//end foreach
					?>
						
				</tbody>
			</table>
		</section>
		<section class="af-pdf-content">
			<div class="af-subtotal-section">
				<table>
					<div class="af-pdf-logo"></div>
					<div class="af-invoice-info-content">
						<ul>
							<?php if ( get_option( 'af_ips_credit_notes_show_sub_total' ) ) { ?>
								<li><p><label><?php esc_html_e( 'Subtotal', 'af_ips_invoices' ); ?></label><font><?php echo wp_kses_post( wc_price( $af_refund_subtotal ) ); ?></font></p></li>
							<?php } ?>
							<?php if ( get_option( 'af_ips_credit_notes_show_total_tax' ) ) { ?>
								<li><p class="af-total-label"><label><?php esc_html_e( 'Refunded Amount', 'af_ips_invoices' ); ?></label><font><?php echo wp_kses_post( wc_price( $af_order_total ) ); ?></font></p></li>
							<?php } ?>	
						</ul>
					</div>
				</table>
			</div>
		</section>	
		<section class="af-pdf-footer">
			<h4><?php echo esc_html__( 'Customer Details', 'af_ips_invoices' ); ?></h4>
			<ul>
				<li><p><label><?php echo esc_html__( 'Customer Name:', 'af_ips_invoices' ); ?></label> <span><?php esc_attr_e( $ips_username ); ?></span></p></li>
				<li><p><label><?php echo esc_html__( 'Postcode / ZIP:', 'af_ips_invoices' ); ?></label><span><?php esc_attr_e( $billing_postcode ); ?></span></p></li>
				<li><p><label><?php esc_html_e( 'Shipping Country:', 'af_ips_invoices' ); ?></label><span><?php esc_attr_e( $shipping_country ); ?></span></p></li>
				<li><p><label><?php esc_html_e( 'E-Mail:', 'af_ips_invoices' ); ?></label><span><?php esc_attr_e( $billing_email ); ?></span></p></li>
			</ul>
		</section>
			<?php if ( $af_ips_show_credit_notes ) { ?>
				<section id="invoice_note">
					<h3><?php echo esc_html__( 'Note:', 'af_ips_invoices' ); ?></h3>
					<p><?php echo esc_attr( get_option( 'af_ips_show_credit_note_notes' ) ); ?></p>
				</section>
			<?php	} ?>
			<?php if ( $af_ips_show_credit_note_footer ) { ?>
				<div class="af-footer-bar">
					<p><?php echo esc_attr( get_option( 'af_ips_show_credit_note_footer_text' ) ); ?></p>
				</div> 
			<?php } ?>
	</div>

</body>
</html>
