<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
// validation Visible columns PDF.
$af_ips_company_name                   = get_option( 'af_ips_template_content_field' );
$af_ips_show_order_total               = get_option( 'af_ips_show_order_total' );
$af_ips_show_packing_slip_show_footer  = get_option( 'af_ips_show_packing_slip_show_footer' );
$af_ips_company_logo                   = get_option( 'af_ips_company_logo' );
$af_imge                               = get_option( 'af_ips_show_product_picture' );
$af_sku                                = get_option( 'af_ips_show_product_sku' );
$af_discrption                         = get_option( 'af_ips_show_short_description' );
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
$af_ips_show_footer                    = get_option( 'af_ips_show_footer' );
$af_ips_template_bg_color              = get_option( 'af_ips_template_bg_color' );
$af_ips_template_header_color          = get_option( 'af_ips_template_header_color' );
$af_ips_template_header_text_color     = get_option( 'af_ips_template_header_text_color' );
$af_ips_customer_data_back_color       = get_option( 'af_ips_customer_data_back_color' );
$af_ips_table_customer_info_font_color = get_option( 'af_ips_table_customer_info_font_color' );
$af_ips_template_total_section_color   = get_option( 'af_ips_template_total_section_color' );
$af_ips_total_section_font_color       = get_option( 'af_ips_total_section_font_color' );
$af_ips_footer_back_color              = get_option( 'af_ips_footer_back_color' );
$af_ips_footer_font_color              = get_option( 'af_ips_footer_font_color' );
$af_ips_show_packing_notes             = get_option( 'af_ips_show_packing_notes' );

$af_customer_invice_details = get_option( 'af_customer_invice_details' );
$af_ips_cstm_ship_details   = get_option( 'af_ips_cstm_ship_details' );
// validation Visible columns PDF.
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
		ul li{
			list-style-type: none !important;
			line-height: 10px !important;
			font-size: 10px !important;
			padding: 0px;
			margin: 0px;
		}
		.af-line-temp-mid-section ul li p{ 
			line-height: 20px !important;
			font-size: 10px !important;
			padding: 0px;
			margin: 0px;
		}
</style>
<body >
	<div class="af-main-container" >
		<section class="af_tem_heade_title">
			<div class="af-ip-container">
				<div class="af_ip_row">
					<center><h3 class="af_title_tmp"><?php echo esc_html__( 'Packing Slip', 'af_ips_invoices' ); ?></h3></center>
				</div>
			</div>
		</section>
		<section class="af-pdf-header">
			<div class="af-invoice-info-box">
				<?php if ( get_option( 'af_slip_comp_name' ) ) { ?>
					<p><?php echo esc_attr( $af_ips_company_name ); ?></p>
				<?php } ?>
				<?php if ( get_option( 'af_slip_comp_detail' ) ) { ?>
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
					<div class="af_inv_data">
						<span class="af_ips_inv_num"><?php echo esc_attr( $af_invoice_number ); ?></span>
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
									echo esc_attr( ' ' . $af_cart_total );
									?>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<hr>
		</section>
		<section class="af-line-temp-mid-section" style="width:100%;">
				<?php if ( $af_customer_invice_details ) { ?>
				<div class="af-line-temp-mid-sec-left" style="width:50%;display:inline-block;">
					<h4><?php esc_html_e( 'Bill To', 'af_ips_invoices' ); ?></h4>
					<ul>
						<li><p><label><?php echo esc_html__( 'Company Name:', 'af_ips_invoices' ); ?></label> <span><?php echo esc_attr( $ips_billing_company ); ?></span></p></li>
						<li><p><label><?php echo esc_html__( 'Address:', 'af_ips_invoices' ); ?></label><span><?php echo esc_attr( $ips_billing_address ); ?></span></p></li>
						<li><p><label><?php esc_html_e( 'Phone:', 'af_ips_invoices' ); ?></label><span><?php echo esc_attr( $ips_billing_phone ); ?></span></p></li>
						<li><p><label><?php esc_html_e( 'E-Mail:', 'af_ips_invoices' ); ?></label><span><?php echo esc_attr( $billing_email ); ?></span></p></li>
					</ul>
				</div>
				<?php } ?>
				<?php if ( $af_ips_cstm_ship_details ) { ?>
				<div class="af-line-temp-mid-sec-right" style="width:40%;float:right;">
					<h4><?php esc_html_e( 'Ship To', 'af_ips_invoices' ); ?></h4>
					<ul>
						<li><p><label><?php echo esc_html__( 'Name:', 'af_ips_invoices' ); ?></label> <span><?php echo esc_attr( $ips_shipping_first_name ); ?></span></p></li>
						<li><p><label><?php echo esc_html__( 'Company Name:', 'af_ips_invoices' ); ?></label> <span><?php echo esc_attr( $ips_shipping_company ); ?></span></p></li>
						<li><p><label><?php echo esc_html__( 'Address:', 'af_ips_invoices' ); ?></label><span><?php echo esc_attr( $ips_shipping_address ); ?></span></p></li>
						<li><p><label><?php esc_html_e( 'Phone:', 'af_ips_invoices' ); ?></label><span><?php echo esc_attr( $ips_shipping_phone ); ?></span></p></li>
						<li><p><label><?php esc_html_e( 'Country Name:', 'af_ips_invoices' ); ?></label><span><?php echo esc_attr( $ips_shipping_country ); ?></span></p></li>
					</ul>
				</div>
				<?php } ?>
			</section>
			<section id="af_ip_products_table">
						<div class="af-ip-container">
							<table style="">
								<thead>
									<tr>
										<?php if ( $af_imge ) { ?>
										<th>&nbsp;</th>
										<?php } ?>
										<th style="width:80px !important;"><?php echo esc_html__( 'Product', 'af_ips_invoices' ); ?></th>
										<?php if ( $af_quantity ) { ?>
										<th><?php echo esc_html__( 'QTY', 'af_ips_invoices' ); ?></th>
										<?php } ?>
										<?php if ( $af_price ) { ?>
										<th><?php echo esc_html__( 'Price', 'af_ips_invoices' ); ?></th>
										<?php } ?>
										<?php if ( $af_regular_price ) { ?>
										<th><?php echo esc_html__( 'Regular Price', 'af_ips_invoices' ); ?></th>
										<?php } ?>
										<?php if ( $af_on_sale ) { ?>
										<th><?php echo esc_html__( 'Sale Price', 'af_ips_invoices' ); ?></th>
										<?php } ?>
										<?php if ( $af_total ) { ?>
										<th><?php echo esc_html__( 'Total', 'af_ips_invoices' ); ?></th>
										<?php } ?>
										<?php if ( $af_tax ) { ?>
										<th><?php echo esc_html__( 'Tax', 'af_ips_invoices' ); ?></th>
										<?php } ?>
										<?php if ( $af_toatl_inc_taxt ) { ?>
										<th><?php echo esc_html__( 'Total inc tax', 'af_ips_invoices' ); ?></th>
										<?php } ?>
										<?php if ( $af_line_total ) { ?>
										<th><?php echo esc_html__( 'line Total', 'af_ips_invoices' ); ?></th>
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
										$price_incl_tax = wc_get_price_including_tax( $_product );
										$price_excl_tax = wc_get_price_excluding_tax( $_product );
										$af_tax1        = $price_incl_tax - $price_excl_tax;
										$tax_percentage = $af_tax1 / 100;
										$total          = $item->get_total();
										$total_inc_taxt = $af_tax1 + $total;
										$discription    = $product->get_short_description();
										?>
										<tr>
										<?php if ( $af_imge ) { ?>
											<td class="af-ip-pro-img">
												<?php echo wp_kses_post( $thumbnail ); ?>
											</td>
										<?php } ?>
											
											<td  class="af-ip-pro-title" style="width:80px !important;">
												<span><?php echo wp_kses_post( $product_name ); ?></span><br>
												<?php
												if ( $af_sku ) {
													?>
												<span><?php echo esc_attr( $sku ); ?></span><br>
														<?php
												}
												if ( $af_discrption ) {
													?>
												<span><?php echo esc_attr( $discription ); ?></span>
														<?php
												}
												?>
											 
											</td>
										<?php if ( $af_quantity ) { ?>
											<td class="af-ip-pro-qty">
												<?php echo wp_kses_post( $quantity ); ?>
											</td>
										<?php } ?>

										<?php if ( $af_price ) { ?>
											<td class="af-ip-pro-qty">
												<?php echo wp_kses_post( wc_price( $price_html ) ); ?>
											</td>
										<?php } ?>
										<?php if ( $af_regular_price ) { ?>
											<td class="af-ip-pro-qty">
												<?php echo wp_kses_post( wc_price( $regular_price ) ); ?>
											</td>
										<?php } ?>
										<?php if ( $af_on_sale ) { ?>
											<td class="af-ip-pro-qty">
												<?php echo wp_kses_post( wc_price( $sale_price ) ); ?>
											</td>
										<?php } ?>
										<?php if ( $af_total ) { ?>
											<td class="af-ip-pro-qty">
												<?php echo wp_kses_post( wc_price( $total ) ); ?>
											</td>
										<?php } ?>
										<?php if ( $af_tax ) { ?>
											<td class="af-ip-pro-qty">
												<?php echo wp_kses_post( wc_price( $af_tax1 ) ); ?>
											</td>
										<?php } ?>
										<?php if ( $af_toatl_inc_taxt ) { ?>
											<td class="af-ip-pro-qty">
												<?php echo wp_kses_post( wc_price( $total_inc_taxt ) ); ?>
											</td>
										<?php } ?>
										<?php if ( $af_line_total ) { ?>
											<td class="af-ip-pro-qty">
												<?php echo wp_kses_post( wc_price( $af_line_total ) ); ?>
											</td>
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
							<tr>
								<th><b><?php echo esc_html__( 'Subtotal', 'af_ips_invoices' ); ?></b></th>
								<td><?php echo wp_kses_post( wc_price( $af_subtotal ) ); ?></td>
							</tr> 
							<tr>
								<th><?php echo esc_html__( 'Discount', 'af_ips_invoices' ); ?></th>
								<td><?php echo wp_kses_post( wc_price( $af_discount ) ); ?></td>
							</tr>
							<?php if ( $order->get_shipping_total() ) { ?>
								<tr>
									<th><?php echo esc_attr( $order->get_shipping_method() ); ?></th>
									<td><?php echo wp_kses_post( wc_price( $order->get_shipping_total() ) ); ?></td>
								</tr>
							<?php } ?>
							<?php if ( $af_ips_show_order_total ) { ?>
								<tr>
								<th><strong><?php echo esc_html__( 'Total', 'af_ips_invoices' ); ?></strong></th>
								<td><?php echo wp_kses_post( wc_price( $af_cart_total ) ); ?></td>
							</tr>
							<?php	} ?>
						</table>
					</div>
				</div>
			</section>
			<?php if ( get_option( 'af_ips_show_packing_notes' ) ) { ?>
				<section id="invoice_note">
					<h3><?php echo esc_html__( 'Note :', 'af_ips_invoices' ); ?></h3>
					<p><?php echo esc_attr( get_option( 'af_ips_show_packing_slip_notes' ) ); ?></p>
				</section>
				
			<?php } ?>
			<hr>
			<?php if ( $af_ips_show_packing_slip_show_footer ) { ?>
				<div class="af-footer-bar"><?php echo esc_attr( get_option( 'af_ips_text_packing_slip_footer' ) ); ?></div> 
			<?php } ?>
		</div>	
	</body> 
</html>
	
