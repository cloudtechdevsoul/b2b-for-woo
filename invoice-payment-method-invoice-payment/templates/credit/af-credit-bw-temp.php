<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
// validation Visible columns PDF.
$af_ips_company_name         = get_option( 'af_ips_template_content_field' );
$af_ips_company_logo         = get_option( 'af_ips_company_logo' );
$af_credit_note_product_name = get_option( 'af_ips_show_credit_note_product_name' );
$af_imge                     = get_option( 'af_ips_show_credit_note_product_img' );
$af_sku                      = get_option( 'af_ips_show_credit_note_product_sku' );
// $af_discrption                         = get_option( 'af_ips_show_credit_note_product_sku' );
$af_ips_credit_notes_show_sub_total    = get_option( 'af_ips_credit_notes_show_sub_total' );
$af_quantity                           = get_option( 'af_ips_show_quantity' );
$af_regular_price                      = get_option( 'af_ips_show_regular_price' );
$af_on_sale                            = get_option( 'af_ips_show_onsale_price' );
$af_price                              = get_option( 'af_ips_show_product_price' );
$af_total                              = get_option( 'af_ips_show_product_total' );
$af_tax                                = get_option( 'af_ips_show_tax' );
$af_toatl_inc_taxt                     = get_option( 'af_ips_show_total_inc_tax' );
$af_line_total                         = get_option( 'af_ips_show_line_total' );
$af_discount_pernt                     = get_option( 'af_ips_show_discount_percentage' );
$af_ips_show_credit_notes              = get_option( 'af_ips_show_credit_notes' );
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
// $af_refund_subtotal                    = $order->get_subtotal();
// $ips_total                             = $order->get_total();
// validation Visible columns PDF.
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<div class="af-main-container">
		<section class="af_tem_heade_title">
			<div class="af-ip-container">
				<div class="af_ip_row">
					<center><h3 class="af_title_tmp"><?php echo esc_html__( 'Credit Note', 'af_ips_invoices' ); ?></h3></center>
				</div>
			</div>
		</section>
		<section class="af-pdf-header">
			<div class="af-invoice-info-box">
				<?php if ( get_option( 'af_crdit_comp_name' ) ) { ?>
					<p><?php echo esc_attr( $af_ips_company_name ); ?></p>
				<?php } ?>
				<?php if ( get_option( 'af_crdit_comp_detail' ) ) { ?>
					<p><?php echo wp_kses_post( $af_ip_comp_details ); ?></p>
				<?php } ?>
				
			</div>
			<div class="af-store-info-box">
				<img src="<?php echo esc_url( $af_log_url ); ?>">
			</div>	
		</section>
		<hr>
		<section class="af-ip-section">
			<div class="af-ip-container">
				<div class=" af_ip_dpf" style="height:25%;">
					<div class="af_ips_cus_det">
						<table>
							<?php if ( get_option( 'af_crdit_detail' ) ) { ?>
							<tr>
								<td><?php echo esc_attr( $ips_username ); ?></td>
							</tr>
							<tr>
								<td><?php echo esc_attr( $ips_user_email ); ?></td>
							</tr>
							<tr>
								<td><?php echo esc_attr( $ips_billing_address ); ?></td>
							</tr>
							<?php } ?>
						</table>
					</div>
					<div class="af_inv_data">
						<table>
							<tr>
								<th><?php echo esc_html__( 'Order Number', 'af_ips_invoices' ); ?></th>
								<td><?php echo esc_attr( $order_id ); ?></td>
							</tr>
							<tr>
								<th><?php echo esc_html__( 'Date', 'af_ips_invoices' ); ?></th>
								<td><?php echo esc_attr( $af_invoice_date ); ?></td>
							</tr>
							<tr>
								<th><?php echo esc_html__( 'Amount', 'af_ips_invoices' ); ?></th>
								<td>
									<?php
									echo esc_attr( get_woocommerce_currency_symbol() );
									echo esc_attr( ' ' . $af_order_total );
									?>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<hr>
		</section>
			<section id="af_ip_products_table">
						<div class="af-ip-container">
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
									}// end for each.
									?>
								</tbody>
							</table>
						</div>
					</section>
			<section id="af_api_cart_totl">
				<div class="af-ip-container">
					<div class="af_ip_cart_total">
						<table class="af_ip_cart_table">
						<?php	if ( $af_ips_credit_notes_show_sub_total ) { ?>
							<tr>
								<th><b><?php echo esc_html__( 'Subtotal', 'af_ips_invoices' ); ?></b></th>
								<td><?php echo wp_kses_post( wc_price( $af_refund_subtotal ) ); ?></td>
							</tr>
						<?php } ?>
						<?php if ( get_option( 'af_ips_credit_notes_show_total_tax' ) ) { ?>
							<tr>
								<th><strong><?php echo esc_html__( 'Refunded Amount', 'af_ips_invoices' ); ?></strong></th>
								<td><?php echo wp_kses_post( wc_price( $af_order_total ) ); ?></td>
							</tr>
						<?php } ?>	
						</table>
					</div>
				</div>
			</section>
			<?php if ( $af_ips_show_credit_notes ) { ?>
			<section id="invoice_note">
				<h3><?php echo esc_html__( 'Note:', 'af_ips_invoices' ); ?></h3>
				<p><?php echo esc_attr( get_option( 'af_ips_show_credit_note_notes' ) ); ?></p>
			</section>
			<?php	} ?>
			<?php if ( $af_ips_show_credit_note_footer ) { ?>
				<div class="af-footer-bar"><?php echo esc_attr( get_option( 'af_ips_show_credit_note_footer_text' ) ); ?></div> 
			<?php } ?>
		</div>
	</body>
	</html>
	<?php
