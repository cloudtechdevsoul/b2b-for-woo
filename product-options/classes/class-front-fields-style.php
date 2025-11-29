<?php

defined('ABSPATH') || exit;

/**
 * Styling
 */
class Front_Fields_Styling {

	public static function ck_front_fields_styling( $rule_id ) {

		$title_display   = get_option('title_display');
		$heading_type    = get_option('heading_type');
		$title_font_size = get_option('title_font_size');
		if (empty($title_font_size)) {
			$title_font_size = 12;
		}
		$title_color               = get_option('title_color');
		$title_bg                  = get_option('title_bg');
		$bg_color                  = get_option('bg_color');
		$title_top_left_radius     = get_option('bg_radius')['top_left'];
		$title_top_right_radius    = get_option('bg_radius')['top_right'];
		$title_bottom_left_radius  = get_option('bg_radius')['btm_left'];
		$title_bottom_right_radius = get_option('bg_radius')['btm_right'];
		$title_top_padding         = get_option('bg_padding')['top'];
		$title_bottom_padding      = get_option('bg_padding')['bottom'];
		$title_left_padding        = get_option('bg_padding')['left'];
		$title_right_padding       = get_option('bg_padding')['right'];
		$option_title_font_size    = get_option('option_title_font_size');
		if (empty($option_title_font_size)) {
			$option_title_font_size = 12;
		}
		$option_title_font_color = get_option('optn_title_font_color');
		$seperator_checkbox      = get_option('seperator_checkbox');
		$title_seperator         = get_option('field_seperator');
		$field_border            = get_option('field_border_checkbox');
		$field_border_pixels     = get_option('field_border_pixels');
		if (empty($field_border_pixels)) {
			$field_border_pixels = 1;
		}
		$field_border_color               = get_option('field_border_color');
		$field_border_top_left_radius     = get_option('field_border_radius')['top_left'];
		$field_border_top_right_radius    = get_option('field_border_radius')['top_right'];
		$field_border_bottom_left_radius  = get_option('field_border_radius')['bottom_left'];
		$field_border_bottom_right_radius = get_option('field_border_radius')['bottom_right'];
		$field_border_top_padding         = get_option('field_inside_padding')['top'];
		$field_border_bottom_padding      = get_option('field_inside_padding')['bottom'];
		$field_border_left_padding        = get_option('field_inside_padding')['left'];
		$field_border_right_padding       = get_option('field_inside_padding')['right'];
		$ck_field_title_position          = get_option('field_title_position');
		$title_position                   = get_option('field_title_border_position');
		$desc_font_size                   = get_option('desc_font_size');
		if (empty($desc_font_size)) {
			$desc_font_size = 12;
		}
		$tooltip_background_color = get_option('tool_tip_bg_color');
		$tooltip_text_color       = get_option('tool_tip_text_color');
		$tooltip_font_size        = get_option('tool_tip_font_size');
		if (empty($tooltip_font_size)) {
			$tooltip_font_size = 12;
		}
		?>
		<style>
			.add_on_description_style_<?php echo esc_attr($rule_id); ?>{
				font-size: <?php echo esc_attr($desc_font_size); ?>px;
				margin: 0;
			}

			.addon_field_border_<?php echo esc_attr($rule_id); ?>{
				<?php
				if ('yes' == $field_border) {
					?>
					border: <?php echo esc_attr($field_border_pixels); ?>px solid <?php echo esc_attr($field_border_color); ?>;
					<?php
					if (!empty($field_border_top_left_radius)) {
						?>
						border-top-left-radius: <?php echo esc_attr($field_border_top_left_radius); ?>px;
						<?php
					}
					if (!empty($field_border_top_right_radius)) {
						?>
						border-top-right-radius: <?php echo esc_attr($field_border_top_right_radius); ?>px;
						<?php
					}
					if (!empty($field_border_bottom_left_radius)) {
						?>
						border-bottom-left-radius: <?php echo esc_attr($field_border_bottom_left_radius); ?>px;
						<?php
					}
					if (!empty($field_border_bottom_right_radius)) {
						?>
						border-bottom-right-radius: <?php echo esc_attr($field_border_bottom_right_radius); ?>px;
						<?php
					}
					if (!empty($field_border_top_padding)) {
						?>
						padding-top: <?php echo esc_attr($field_border_top_padding); ?>px;
						<?php
					}
					if (!empty($field_border_bottom_padding)) {
						?>
						padding-bottom: <?php echo esc_attr($field_border_bottom_padding); ?>px;
						<?php
					}
					if (!empty($field_border_left_padding)) {
						?>
						padding-left: <?php echo esc_attr($field_border_left_padding); ?>px;
						<?php
					}
					if (!empty($field_border_right_padding)) {
						?>
						padding-right: <?php echo esc_attr($field_border_right_padding); ?>px;
						<?php
					}
				}
				?>
				width: 100%;
			}

			.ck_option_font_<?php echo esc_attr($rule_id); ?>{
				font-size: <?php echo esc_attr($option_title_font_size); ?>px;
				color: <?php echo esc_attr($option_title_font_color); ?>;
			}

			.addon_heading_styling_<?php echo esc_attr($rule_id); ?>{
				<?php
				if ('yes' == $title_bg) {
					?>
					background-color: <?php echo esc_attr($bg_color); ?>;
					<?php
				}
				
				?>
				color: <?php echo esc_attr($title_color); ?>;
			}

			.af_addon_front_ck_field_title_div_<?php echo esc_attr($rule_id); ?>{
				<?php
				if ('yes' == $title_bg) {
					?>
					background-color: <?php echo esc_attr($bg_color); ?>;
					<?php
				}
				if (!empty($title_top_left_radius)) {
					?>
					border-top-left-radius: <?php echo esc_attr($title_top_left_radius); ?>px;
					<?php
				}
				if (!empty($title_top_right_radius)) {
					?>
					border-top-right-radius: <?php echo esc_attr($title_top_right_radius); ?>px;
					<?php
				}
				if (!empty($title_bottom_left_radius)) {
					?>
					border-bottom-left-radius: <?php echo esc_attr($title_bottom_left_radius); ?>px;
					<?php
				}
				if (!empty($title_bottom_right_radius)) {
					?>
					border-bottom-right-radius: <?php echo esc_attr($title_bottom_right_radius); ?>px;
					<?php
				}

				if (!empty($title_top_padding)) {
					?>
					padding-top: <?php echo esc_attr($title_top_padding); ?>px;
					<?php
				}
				if (!empty($title_bottom_padding)) {
					?>
					padding-bottom: <?php echo esc_attr($title_bottom_padding); ?>px;
					<?php
				}
				if (!empty($title_left_padding)) {
					?>
					padding-left: <?php echo esc_attr($title_left_padding); ?>px;
					<?php
				}
				if (!empty($title_right_padding)) {
					?>
					padding-right: <?php echo esc_attr($title_right_padding); ?>px;
					<?php
				}
				?>
				color: <?php echo esc_attr($title_color); ?>;
				width: 100%;
				<?php
				if ('text' == $title_display) {
					?>
					font-size: <?php echo esc_attr($title_font_size); ?>px;
					<?php
				}
				if ('right' == $ck_field_title_position) {
					?>
					text-align: right;
					<?php
				} elseif ('center' == $ck_field_title_position) {
					?>
					text-align: center;
					<?php
				} else {
					?>
					text-align: left;
					<?php
				}
				?>
				 
			}

			.tooltip_<?php echo esc_attr($rule_id); ?> {
			  position: relative;
			  display: inline-block;
			  border-bottom: none !important;
			  color: black !important;
			  font-size: 18px;
			  vertical-align: middle;
			}

			.tooltip_<?php echo esc_attr($rule_id); ?> .tooltiptext_<?php echo esc_attr($rule_id); ?> {
				visibility: hidden;
				width: 110px;
				background-color: <?php echo esc_attr($tooltip_background_color); ?>;
				color: <?php echo esc_attr($tooltip_text_color); ?>;
				font-size: <?php echo esc_attr($tooltip_font_size); ?>px;
				text-align: center;
				border-radius: 4px;
				padding: 5px 0;

				/* Position the tooltip */
				position: absolute;
				z-index: 1;
				left: -104px;
				top: -30px;
				height: auto;
			}

			.tooltip_<?php echo esc_attr($rule_id); ?>:hover .tooltiptext_<?php echo esc_attr($rule_id); ?> {
			  visibility: visible;
			}
		</style>
		<?php
	}
}

new Front_Fields_Styling();
