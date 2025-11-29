<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
add_settings_section(
	'af_ips_template',
	esc_html__( 'Documents Template', 'af_ips_invoices' ),
	'af_ips_template_sec',
	'cloudtech_invoice_template_file_setting_section'
);
	add_settings_field(
		'af_ips_template_field',
		esc_html__( 'Choose documents template', 'af_ips_invoices' ),
		'af_ips_template_field_clbk',
		'cloudtech_invoice_template_file_setting_section',
		'af_ips_template'
	);
		register_setting(
			'cloudtech_invoice_template_file_setting_field',
			'af_ips_template_field'
		);
		add_settings_section(
			'af_ips_template_style_colors',
			esc_html__( 'Document colors', 'af_ips_invoices' ),
			'af_ips_template_sec',
			'cloudtech_invoice_template_file_setting_section'
		);
		add_settings_field(
			'af_ips_template_bg_color',
			esc_html__( 'Background color', 'af_ips_invoices' ),
			'af_ips_template_bg_color_clbk',
			'cloudtech_invoice_template_file_setting_section',
			'af_ips_template_style_colors'
		);
		register_setting(
			'cloudtech_invoice_template_file_setting_field',
			'af_ips_template_bg_color'
		);
		add_settings_field(
			'af_ips_template_header_color',
			esc_html__( 'Table header color', 'af_ips_invoices' ),
			'af_ips_template_header_color_clbk',
			'cloudtech_invoice_template_file_setting_section',
			'af_ips_template_style_colors'
		);
		register_setting(
			'cloudtech_invoice_template_file_setting_field',
			'af_ips_template_header_color'
		);
		add_settings_field(
			'af_ips_template_header_text_color',
			esc_html__( 'Table header font color', 'af_ips_invoices' ),
			'af_ips_template_header_text_color_clbk',
			'cloudtech_invoice_template_file_setting_section',
			'af_ips_template_style_colors'
		);
		register_setting(
			'cloudtech_invoice_template_file_setting_field',
			'af_ips_template_header_text_color'
		);
		add_settings_field(
			'af_ips_customer_data_back_color',
			esc_html__( 'PDF Customer Info background color', 'af_ips_invoices' ),
			'af_ips_customer_data_back_color_clbk',
			'cloudtech_invoice_template_file_setting_section',
			'af_ips_template_style_colors'
		);
		register_setting(
			'cloudtech_invoice_template_file_setting_field',
			'af_ips_customer_data_back_color'
		);
		add_settings_field(
			'af_ips_table_customer_info_font_color',
			esc_html__( 'PDF Customer Info section font color', 'af_ips_invoices' ),
			'af_ips_af_ips_table_customer_info_font_color_clbk',
			'cloudtech_invoice_template_file_setting_section',
			'af_ips_template_style_colors'
		);
		register_setting(
			'cloudtech_invoice_template_file_setting_field',
			'af_ips_table_customer_info_font_color'
		);
		add_settings_field(
			'af_ips_template_total_section_color',
			esc_html__( 'Table total section background  color', 'af_ips_invoices' ),
			'af_ips_template_total_section_color_clbk',
			'cloudtech_invoice_template_file_setting_section',
			'af_ips_template_style_colors'
		);
		register_setting(
			'cloudtech_invoice_template_file_setting_field',
			'af_ips_template_total_section_color'
		);
		add_settings_field(
			'af_ips_total_section_font_color',
			esc_html__( 'Table total section font color', 'af_ips_invoices' ),
			'af_ips_total_section_font_color_clbk',
			'cloudtech_invoice_template_file_setting_section',
			'af_ips_template_style_colors'
		);
		register_setting(
			'cloudtech_invoice_template_file_setting_field',
			'af_ips_total_section_font_color'
		);
		add_settings_field(
			'af_ips_footer_back_color',
			esc_html__( 'Table footer section background color', 'af_ips_invoices' ),
			'af_ips_footer_back_color_clbk',
			'cloudtech_invoice_template_file_setting_section',
			'af_ips_template_style_colors'
		);
		register_setting(
			'cloudtech_invoice_template_file_setting_field',
			'af_ips_footer_back_color'
		);
		add_settings_field(
			'af_ips_footer_font_color',
			esc_html__( 'Table footer section font color', 'af_ips_invoices' ),
			'af_ips_footer_font_color_clbk',
			'cloudtech_invoice_template_file_setting_section',
			'af_ips_template_style_colors'
		);
		register_setting(
			'cloudtech_invoice_template_file_setting_field',
			'af_ips_footer_font_color'
		);

		if ( ! function_exists( 'af_ips_template_field_clbk' ) ) {
			function af_ips_template_field_clbk() {
				?>
		<div class="af_apstemplate_images_div">

			<div class="af_ips_first_template af_ips_template">
				<a target="_blank" href="<?php echo esc_attr( CLOUDTECH_WIPOAP_URL . 'include\images\ct_ip_bw.png' ); ?>">
					<i class="fa fa-eye"></i>
				</a>
				<input type="radio" name="af_ips_template_field"  class="input-hidden" value="af_df_temp"<?php checked( get_option( 'af_ips_template_field' ), 'af_df_temp' ); ?>>
				<div class="af_tmp_lable">
					<img  width="150" height="150" src="<?php echo esc_attr( CLOUDTECH_WIPOAP_URL . 'include\images\ct_ip_default.png' ); ?>" >
				</div>
				<span class="af_ip_tmp_header"><?php echo esc_html__( 'Temlate 1', 'af_ips_invoices' ); ?></span>
			</div>
			<div class="af_ips_second_template af_ips_template">
				<a target="_blank" href="<?php echo esc_attr( CLOUDTECH_WIPOAP_URL . 'include\images\ct_ip_bw.png' ); ?>">
					<i class="fa fa-eye"></i>
				</a>
				<input type="radio" name="af_ips_template_field"  class="input-hidden" value="af_bw_temp"<?php checked( get_option( 'af_ips_template_field' ), 'af_bw_temp' ); ?>>
				<div class="af_tmp_lable">
					<img  width="150" height="150" src="<?php echo esc_attr( CLOUDTECH_WIPOAP_URL . 'include\images\ct_ip_bw.png' ); ?>" >
				</div>
				<span class="af_ip_tmp_header"><?php echo esc_html__( 'Template 2', 'af_ips_invoices' ); ?></span>
			</div>
			<div class="af_ips_third_template af_ips_template">
				<a target="_blank" href="<?php echo esc_attr( CLOUDTECH_WIPOAP_URL . 'include\images\ct_ip_bw.png' ); ?>">
					<i class="fa fa-eye"></i>
				</a>
				<input type="radio" name="af_ips_template_field"  class="input-hidden" value="af_line_temp"<?php checked( get_option( 'af_ips_template_field' ), 'af_line_temp' ); ?>>
				<div class="af_tmp_lable">
					<img  width="150" height="150" src="<?php echo esc_attr( CLOUDTECH_WIPOAP_URL . 'include\images\ct_ip_lines.png' ); ?>" >
				</div>
				<span class="af_ip_tmp_header"><?php echo esc_html__( 'Template 3', 'af_ips_invoices' ); ?></span>
			</div>
		</div>

		
		<label class="description"><?php echo esc_html__( 'Choose which template to use for your documents. You can customize the colors of each template.', 'af_ips_invoices' ); ?></label>
				<?php
			}
		}
		if ( ! function_exists( 'af_ips_template_bg_color_clbk' ) ) {
			function af_ips_template_bg_color_clbk() {
				?>
		<input type="color" name="af_ips_template_bg_color" class="af_ips_template_bg_color" value="<?php echo esc_attr( get_option( 'af_ips_template_bg_color' ) ); ?>"/>
		</br>
		<label class="description"><?php echo esc_html__( 'Select the color of the template.', 'af_ips_invoices' ); ?></label>
				<?php
			}
		}
		if ( ! function_exists( 'af_ips_template_header_color_clbk' ) ) {
			function af_ips_template_header_color_clbk() {
				?>
		<input type="color" name="af_ips_template_header_color" class="af_ips_template_bg_color" value="<?php echo esc_attr( get_option( 'af_ips_template_header_color' ) ); ?>"/>
		</br>
		<label class="description"><?php echo esc_html__( 'Select the color of the table header section.', 'af_ips_invoices' ); ?></label>
				<?php
			}
		}
		if ( ! function_exists( 'af_ips_template_header_text_color_clbk' ) ) {
			function af_ips_template_header_text_color_clbk() {
				?>
		<input type="color" name="af_ips_template_header_text_color" class="af_ips_template_bg_color" value="<?php echo esc_attr( get_option( 'af_ips_template_header_text_color' ) ); ?>"/>
		</br>
		<label class="description"><?php echo esc_html__( 'Select the color of the table header text.', 'af_ips_invoices' ); ?></label>
				<?php
			}
		}
		if ( ! function_exists( 'af_ips_customer_data_back_color_clbk' ) ) {
			function af_ips_customer_data_back_color_clbk() {
				?>
		<input type="color" name="af_ips_customer_data_back_color" class="af_ips_template_bg_color" value="<?php echo esc_attr( get_option( 'af_ips_customer_data_back_color' ) ); ?>"/>
		</br>
		<label class="description"><?php echo esc_html__( 'Select the background color customer info in the table.', 'af_ips_invoices' ); ?></label>
				<?php
			}
		}
		if ( ! function_exists( 'af_ips_af_ips_table_customer_info_font_color_clbk' ) ) {
			function af_ips_af_ips_table_customer_info_font_color_clbk() {
				?>
		<input type="color" name="af_ips_table_customer_info_font_color" class="af_ips_template_bg_color" value="<?php echo esc_attr( get_option( 'af_ips_table_customer_info_font_color' ) ); ?>"/>
		</br>
		<label class="description"><?php echo esc_html__( 'Select the color of font for customer info table.', 'af_ips_invoices' ); ?></label>
				<?php
			}
		}
		if ( ! function_exists( 'af_ips_template_total_section_color_clbk' ) ) {
			function af_ips_template_total_section_color_clbk() {
				?>
			<input type="color" name="af_ips_template_total_section_color" class="af_ips_template_bg_color" value="<?php echo esc_attr( get_option( 'af_ips_template_total_section_color' ) ); ?>"/>
			</br>
			<label class="description"><?php echo esc_html__( 'Select the color of the total section in the table.', 'af_ips_invoices' ); ?></label>
				<?php
			}
		}
		if ( ! function_exists( 'af_ips_total_section_font_color_clbk' ) ) {
			function af_ips_total_section_font_color_clbk() {
				?>
				<input type="color" name="af_ips_total_section_font_color" class="af_ips_template_bg_color" value="<?php echo esc_attr( get_option( 'af_ips_total_section_font_color' ) ); ?>"/>
				</br>
				<label class="description"><?php echo esc_html__( 'Select the font color of the total section in the table.', 'af_ips_invoices' ); ?></label>
				<?php
			}
		}
		if ( ! function_exists( 'af_ips_footer_back_color_clbk' ) ) {
			function af_ips_footer_back_color_clbk() {
				?>
				<input type="color" name="af_ips_footer_back_color" class="af_ips_template_bg_color" value="<?php echo esc_attr( get_option( 'af_ips_footer_back_color' ) ); ?>"/>
				</br>
				<label class="description"><?php echo esc_html__( 'Select the background color of the footer section in PDF.', 'af_ips_invoices' ); ?></label>
				<?php
			}
		}

		if ( ! function_exists( 'af_ips_footer_font_color_clbk' ) ) {
			function af_ips_footer_font_color_clbk() {
				?>
				<input type="color" name="af_ips_footer_font_color" class="af_ips_template_bg_color" value="<?php echo esc_attr( get_option( 'af_ips_footer_font_color' ) ); ?>"/>
				</br>
				<label class="description"><?php echo esc_html__( 'Select the font color of the footer section in PDF.', 'af_ips_invoices' ); ?></label>
				<?php
			}
		}

		if ( ! function_exists( 'af_ips_template_sec' ) ) {
			function af_ips_template_sec() {
			}
		}
