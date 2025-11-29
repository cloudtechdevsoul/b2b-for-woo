<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Define Class.
 *
 */

class Prod_Optns_Woo_Options {

	public function __construct() {
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'filter_woocommerce_settings_tabs_array' ), 99 );
		add_action( 'woocommerce_sections_ck_prod_optn', array( $this, 'action_woocommerce_sections_my_custom_tab' ), 10 );
		add_action( 'woocommerce_settings_ck_prod_optn', array( $this, 'action_woocommerce_settings_my_custom_tab' ), 10 );
		add_action( 'woocommerce_settings_save_ck_prod_optn', array( $this, 'action_woocommerce_settings_save_my_custom_tab' ), 10 );
	}
	public function filter_woocommerce_settings_tabs_array( $settings_tabs ) {
		$settings_tabs['ck_prod_optn'] = __( 'Product Options Settings', 'woocommerce' );

		return $settings_tabs;
	}

	public function action_woocommerce_sections_my_custom_tab() {

		global $current_section;
		$tab_id = 'ck_prod_optn';

		$sections = array(
			'general-setting' => __( 'General Settings', 'woocommerce' ),
			'style_tab'       => __( 'Styling', 'woocommerce' ),
		);

		$array_keys      = array_keys( $sections );
		$current_section = empty( $current_section ) ? 'general-setting' : $current_section;
		?>
		<ul class="subsubsub">
			<?php
			foreach ( $sections as $id => $label ) {
				echo wp_kses_post( '<li><a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . $tab_id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>' );
			}

			?>
		</ul>
		<br class="clear" />
		<?php
	}

	public function get_custom_settings() {

		global $current_section;
		$settings = array();

		if ( 'general-setting' == $current_section ) {

			$settings = array(

				array(
					'title' => __( 'General', 'woocommerce' ),
					'type'  => 'title',
				),
				array(
					'title'    	=> __( 'Run Product Options', 'prod_options' ),
					'type'     	=> 'select',
					'desc'     	=> __( 'select according to which you want to show options.', 'woocommerce' ),
					'desc_tip' 	=> true,
					'id'       	=> 'ck_prod_optns_run',
					'options' 	=> array(
						'both' 		=> 'Both',
						'product' 	=> 'Only Product Level',
						'rule' 		=> 'Only Rule Level'
					),
				),

				array(
					'type' => 'sectionend',
					'id'   => 'general_settings',
				),
			);
		} elseif ( 'style_tab' == $current_section ) {

			$settings = array(

				array(
					'title' => __( 'Styling', 'woocommerce' ),
					'type'  => 'title',
				),

				array(
					'title'    	=> __( 'Title Display as', 'prod_options' ),
					'type'     	=> 'select',
					'desc'     	=> __( 'Select the type of which you want the title to be displayed.', 'woocommerce' ),
					'desc_tip' 	=> true,
					'id'       	=> 'title_display',
					'options' 	=> array(
						'none' 		=> 'None',
						'heading' 	=> 'Heading',
						'text' 		=> 'Text'
					),
				),

				array(
					'title'    	=> __( 'Heading Type', 'prod_options' ),
					'type'     	=> 'select',
					'desc'     	=> __( 'Select the type of which you want the title to be displayed.', 'woocommerce' ),
					'desc_tip' 	=> true,
					'id'       	=> 'heading_type',
					'options' 	=> array(
						'h1' 	=> 'H1',
						'h2' 	=> 'H2',
						'h3' 	=> 'H3',
						'h4' 	=> 'H4',
						'h5' 	=> 'H5',
						'h6' 	=> 'H6'
					),
				),

				array(
					'title'    	=> __( 'Title Font Size', 'prod_options' ),
					'type'     	=> 'number',
					'desc'     	=> __( 'Size will be in px. Minimum value will be 1 px. In case of empty, default value will be 12 px.', 'woocommerce' ),
					'desc_tip' 	=> true,
					'id'       	=> 'title_font_size',
					'custom_attributes' => array( 'min'=>1 )
				),

				array(
					'title'    	=> __( 'Title Color', 'prod_options' ),
					'type'     	=> 'color',
					'desc'     	=> __( 'Select Title text color of your choice.', 'woocommerce' ),
					'desc_tip' 	=> true,
					'id'       	=> 'title_color',
				),

				array(
					'title'    	=> __( 'Title Background', 'prod_options' ),
					'type'     	=> 'checkbox',
					'desc'     	=> __( 'Select if you want to add background in title.', 'woocommerce' ),
					'desc_tip' 	=> true,
					'id'       	=> 'title_bg',
				),

				array(
					'title'    	=> __( 'Background Color', 'prod_options' ),
					'type'     	=> 'color',
					'desc'     	=> __( 'Select background color of your choice.', 'woocommerce' ),
					'desc_tip' 	=> true,
					'id'       	=> 'bg_color',
				),

				array(
					'title'             => __( 'Backgroud Radius', 'prod_options' ),
					'type'              => 'text',
					'desc'              => __( 'Size will be in px.', 'woocommerce' ),
					'desc_tip'          => true,
					'id'                => 'bg_radius',
				),

				array(
					'title'             => __( 'Backgroud Padding', 'prod_options' ),
					'type'              => 'text',
					'desc'              => __( 'Size will be in px.', 'woocommerce' ),
					'desc_tip'          => true,
					'id'                => 'bg_padding',
				),

				array(
					'title'             => __( 'Option title Font Size', 'prod_options' ),
					'type'              => 'number',
					'desc'              => __( 'Size will be in px. in case of empty, default font size will be 12px', 'woocommerce' ),
					'desc_tip'          => true,
					'id'                => 'option_title_font_size',
					'custom_attributes' => array( 'min'=>1 )
				),

				array(
					'title'             => __( 'Option title Font Color', 'prod_options' ),
					'type'              => 'color',
					'desc'              => __( 'This color will only apply on checkboxes, radio buttons & drop down fields.', 'woocommerce' ),
					'desc_tip'          => true,
					'id'                => 'optn_title_font_color',
				),

				array(
					'title'             => __( 'Add Seperator', 'prod_options' ),
					'type'              => 'checkbox',
					'desc'              => __( 'Select if you want to add separator after title.', 'woocommerce' ),
					'desc_tip'          => true,
					'id'                => 'seperator_checkbox',
				),

				array(
					'title'    	=> __( 'Seperator', 'prod_options' ),
					'type'     	=> 'select',
					'desc'     	=> __( 'Select the separator. By default, the separator will be single line break.', 'woocommerce' ),
					'desc_tip' 	=> true,
					'id'       	=> 'field_seperator',
					'options' 	=> array(
						'br' 	=> 'Single line break',
						'hr' 	=> 'Thematic break'
					),
				),

				array(
					'title'             => __( 'Field Border', 'prod_options' ),
					'type'              => 'checkbox',
					'desc'              => __( 'Select if you want to set the border on field.', 'woocommerce' ),
					'desc_tip'          => true,
					'id'                => 'field_border_checkbox',
				),

				array(
					'title'             => __( 'Field Border Pixels', 'prod_options' ),
					'type'              => 'number',
					'desc'              => __( 'Select if you want to set the border pixels field. In case of empty, default pixel will be 1px', 'woocommerce' ),
					'desc_tip'          => true,
					'id'                => 'field_border_pixels',
					'custom_attributes' => array( 'min'=>1 )
				),

				array(
					'title'             => __( 'Field Border Color', 'prod_options' ),
					'type'              => 'color',
					'desc'              => __( 'Select field border color.', 'woocommerce' ),
					'desc_tip'          => true,
					'id'                => 'field_border_color',
				),

				array(
					'title'             => __( 'Field Border Radius', 'prod_options' ),
					'type'              => 'text',
					'desc'              => __( 'Enter field border radius.', 'woocommerce' ),
					'desc_tip'          => true,
					'id'                => 'field_border_radius',
				),

				array(
					'title'             => __( 'Field Inside Padding', 'prod_options' ),
					'type'              => 'text',
					'desc'              => __( 'Enter field inside padding.', 'woocommerce' ),
					'desc_tip'          => true,
					'id'                => 'field_inside_padding',
				),

				array(
					'title'    	=> __( 'Title Position', 'prod_options' ),
					'type'     	=> 'select',
					'desc'     	=> __( 'Select title position.', 'woocommerce' ),
					'desc_tip' 	=> true,
					'id'       	=> 'field_title_position',
					'options' 	=> array(
						'left' 		=> 'Left',
						'right' 	=> 'Right',
						'center' 	=> 'Center'
					),
				),

				array(
					'title'    	=> __( 'Field border title position', 'prod_options' ),
					'type'     	=> 'select',
					'desc'     	=> __( 'Select field title positoon of border.', 'woocommerce' ),
					'desc_tip' 	=> true,
					'id'       	=> 'field_title_border_position',
					'options' 	=> array(
						'inside_border' 	=> 'Inside border',
						'outside_border' 	=> 'Outside border'
					),
				),

				array(
					'title'    	=> __( 'Description font size', 'prod_options' ),
					'type'     	=> 'number',
					'desc'     	=> __( 'Enter description font size. In case of empty, default font size will be 12px', 'woocommerce' ),
					'desc_tip' 	=> true,
					'id'       	=> 'desc_font_size',
					'custom_attributes' => array( 'min'=>1 )
				),

				array(
					'title'    	=> __( 'Tooltip background color', 'prod_options' ),
					'type'     	=> 'color',
					'desc'     	=> __( 'Select tool tip background color.', 'woocommerce' ),
					'desc_tip' 	=> true,
					'id'       	=> 'tool_tip_bg_color',
				),

				array(
					'title'    	=> __( 'Tooltip text color', 'prod_options' ),
					'type'     	=> 'color',
					'desc'     	=> __( 'Select tool tip text color.', 'woocommerce' ),
					'desc_tip' 	=> true,
					'id'       	=> 'tool_tip_text_color',
				),

				array(
					'title'    	=> __( 'Tooltip font size', 'prod_options' ),
					'type'     	=> 'number',
					'desc'     	=> __( 'Enter tool tip font size. In case of empty, default font size will be 12px', 'woocommerce' ),
					'desc_tip' 	=> true,
					'id'       	=> 'tool_tip_font_size',
					'custom_attributes' => array( 'min'=>1 )
				),

				array(
					'type' => 'sectionend',
					'id'   => 'product_page',
				),
			);
		}

		return $settings;
	}

	public function action_woocommerce_settings_my_custom_tab() {
		// Call settings function
		$settings = $this->get_custom_settings();

		WC_Admin_Settings::output_fields( $settings );
	}

	public function action_woocommerce_settings_save_my_custom_tab() {
		global $current_section;
		$tab_id          = 'ck_prod_optn';
		$current_section = $current_section ? $current_section : 'general-setting';

		// Call settings function
		$settings = $this->get_custom_settings();
		if ( $current_section ) {
			WC_Admin_Settings::save_fields( $settings );
			

			/**
			* Saving options values
			*
			* @since 1.0.0
			* 
			*/
			do_action( 'woocommerce_update_options_' . $tab_id . '_' . $current_section );

		}
	}
}
new Prod_Optns_Woo_Options();
