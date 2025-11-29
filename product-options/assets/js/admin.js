var ajax_url = prod_options_url.admin_url;
var nonce = prod_options_url.nonce;
var bg_radius = prod_options_url.bg_radius;
var bg_padding = prod_options_url.bg_padding;
var field_border_radius = prod_options_url.field_border_radius;
var field_inside_padding = prod_options_url.field_inside_padding;

jQuery( document).ready(
	function(e){

		jQuery('#bg_radius').closest('td').html(bg_radius);
		jQuery('#bg_padding').closest('td').html(bg_padding);
		jQuery('#field_border_radius').closest('td').html(field_border_radius);
		jQuery('#field_inside_padding').closest('td').html(field_inside_padding);


		if (jQuery('.prd_opt_csv_upload').length) {
			jQuery('.prd_opt_csv_upload').closest('form').prop('enctype',"multipart/form-data");
		}

		jQuery(
			function($){
				$( document ).ajaxComplete(function(event, xhr, settings){
					if ( settings.data && settings.data.toLowerCase().includes ( 'woocommerce_load_variations' ) ) {

						$( '.field_open_btn' ).show();
						$( '.field_close_btn' ).hide();
						$( '.prod_opt_depend_main_div' ).hide();
						$( '.field_type_selector_main_div' ).hide();
						$( '.desc_tooltip_main_div' ).hide();
						$( '.prod_opt_req_main_div' ).hide();
						$( '.prod_opt_field_price_main_div' ).hide();
						$( '.prod_opt_limit_range_main_div' ).hide();
						$( '.prod_opt_file_extention_main_div' ).hide();
						$( '.prod_opt_options_table_main_div').hide();
						$( '.prod_opt_add_optn_btn_div' ).hide();

						$('.prod_lvl_style_open_btn').show();
						$('.prod_lvl_style_close_btn').hide();
						$('.prod_lvl_style_fields_div').slideUp();
					}

				});

				jQuery('.user_roles').select2({
					multiple: true,
					placeholder: 'User Roles',
				});

				jQuery( '.prd_opt_prd_search_class' ).select2(
					{
						multiple: true,
						minimumInputLength: 3
					}
				);

				jQuery( '.prd_opt_cat_search_class' ).select2(
					{
						multiple: true,
						minimumInputLength: 3
					}
				);

				jQuery( '.prd_opt_tag' ).select2(
					{
						multiple: true,
						minimumInputLength: 3
					}
				);

				jQuery( '.option_selector' ).select2(
					{
						multiple: true,
						placeholder: 'Select Option',
					}
				);
			}
		);
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'change',
			"#title_display",
			function (){
				
				var selector_val = $(this).val();

				selected_val(selector_val, $);
			}
		);
		$(document).ready(function(){

			var selector_val = $('#title_display').val();

			selected_val(selector_val, $);
		});
	}
);

function selected_val($selected_value, $){

	if ($selected_value == "heading") {

		$('#heading_type').closest( 'tr' ).fadeIn('fast');
		$('#title_font_size').closest( 'tr' ).fadeOut('fast');
		$('#title_color').closest( 'tr' ).fadeIn('fast');
		$('#title_bg').closest( 'tr' ).fadeIn('fast');
		$('#seperator_checkbox').closest( 'tr' ).fadeIn('fast');

	}else if ($selected_value == "text") {

		$('#heading_type').closest( 'tr' ).fadeOut('fast');
		$('#title_font_size').closest( 'tr' ).fadeIn('fast');
		$('#title_color').closest( 'tr' ).fadeIn('fast');
		$('#title_bg').closest( 'tr' ).fadeIn('fast');
		$('#seperator_checkbox').closest( 'tr' ).fadeIn('fast');

	}else{

		$('#heading_type').closest( 'tr' ).fadeOut('fast');
		$('#title_font_size').closest( 'tr' ).fadeOut('fast');
		$('#title_color').closest( 'tr' ).fadeOut('fast');
		$('#title_bg').closest( 'tr' ).fadeOut('fast');
		$('#seperator_checkbox').closest( 'tr' ).fadeOut('fast');
		$('#title_seperator').closest( 'tr' ).fadeOut('fast');
		$('#bg_color').closest( 'tr' ).fadeOut('fast');
		$('.title_bg_radius').closest( 'tr' ).fadeOut('fast');
		$('.title_bg_padding').closest( 'tr' ).fadeOut('fast');
	}
}

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			"#seperator_checkbox",
			function (){
				if ($(this).prop('checked') == true) {
					$('#field_seperator').closest('tr').fadeIn('fast');
				}else{
					$('#field_seperator').closest('tr').fadeOut('fast');
				}
			}
		);
		$(document).ready(function(){
			if ($('#seperator_checkbox').prop('checked') == true) {
				$('#field_seperator').closest('tr').fadeIn('fast');
			}else{
				$('#field_seperator').closest('tr').fadeOut('fast');
			}
		});
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			"#title_bg",
			function (){
				if ($(this).prop('checked') == true) {
					$('#bg_color').closest('tr').fadeIn('fast');
					$('.title_bg_radius').closest('tr').fadeIn('fast');
					$('.title_bg_padding').closest('tr').fadeIn('fast');
				}else{
					$('#bg_color').closest('tr').fadeOut('fast');
					$('.title_bg_radius').closest('tr').fadeOut('fast');
					$('.title_bg_padding').closest('tr').fadeOut('fast');
				}
			}
		);
		$(document).ready(function(){
			if ($('#title_bg').prop('checked') == true) {
				$('#bg_color').closest('tr').fadeIn('fast');
				$('.title_bg_radius').closest('tr').fadeIn('fast');
				$('.title_bg_padding').closest('tr').fadeIn('fast');
			}else{
				$('#bg_color').closest('tr').fadeOut('fast');
				$('.title_bg_radius').closest('tr').fadeOut('fast');
				$('.title_bg_padding').closest('tr').fadeOut('fast');
			}
		});
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			"#field_border_checkbox",
			function (){
				if ($(this).prop('checked') == true) {

					$('#field_border_color').closest('tr').fadeIn('fast');
					$('#field_title_position').closest('tr').fadeIn('fast');
					$('#field_title_border_position').closest('tr').fadeIn('fast');
					$('#field_border_pixels').closest('tr').fadeIn('fast');
					$('.field_border_radius').closest('tr').fadeIn('fast');
					$('.field_inside_padding').closest('tr').fadeIn('fast');

				}else{

					$('#field_border_color').closest('tr').fadeOut('fast');
					$('#field_title_position').closest('tr').fadeOut('fast');
					$('#field_title_border_position').closest('tr').fadeOut('fast');
					$('#field_border_pixels').closest('tr').fadeOut('fast');
					$('.field_border_radius').closest('tr').fadeOut('fast');
					$('.field_inside_padding').closest('tr').fadeOut('fast');
				}
			}
		);
		$(document).ready(function(){
			if ($('#field_border_checkbox').prop('checked') == true) {

				$('#field_border_color').closest('tr').fadeIn('fast');
				$('#field_title_position').closest('tr').fadeIn('fast');
				$('#field_title_border_position').closest('tr').fadeIn('fast');
				$('#field_border_pixels').closest('tr').fadeIn('fast');
				$('.field_border_radius').closest('tr').fadeIn('fast');
				$('.field_inside_padding').closest('tr').fadeIn('fast');

			}else{

				$('#field_border_color').closest('tr').fadeOut('fast');
				$('#field_title_position').closest('tr').fadeOut('fast');
				$('#field_title_border_position').closest('tr').fadeOut('fast');
				$('#field_border_pixels').closest('tr').fadeOut('fast');
				$('.field_border_radius').closest('tr').fadeOut('fast');
				$('.field_inside_padding').closest('tr').fadeOut('fast');
			}
		});
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			".all_prod_select",
			function (){
				if ($(this).prop('checked') == true) {

					$('.pord_optn_spec_product_select_div').fadeIn('fast');
					$('.pord_optn_spec_category_select_div').fadeIn('fast');
					$('.pord_optn_spec_tag_select_div').fadeIn('fast');

				}else{

					$('.pord_optn_spec_product_select_div').fadeOut('fast');
					$('.pord_optn_spec_category_select_div').fadeOut('fast');
					$('.pord_optn_spec_tag_select_div').fadeOut('fast');
				}
			}
		);
		$(document).ready(function(){
			if ($('.all_prod_select').prop('checked') == true) {

				$('.pord_optn_spec_product_select_div').fadeIn('fast');
				$('.pord_optn_spec_category_select_div').fadeIn('fast');
				$('.pord_optn_spec_tag_select_div').fadeIn('fast');

			}else{

				$('.pord_optn_spec_product_select_div').fadeOut('fast');
				$('.pord_optn_spec_category_select_div').fadeOut('fast');
				$('.pord_optn_spec_tag_select_div').fadeOut('fast');
			}
		});
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'change',
			".ck_fields_type",
			function (){
				var field_id = $(this).data('field_id');

				var value   = $( '.' + field_id + '_ck_fields_type').children( "option:selected" ).val();

				prod_opt_hide_field_on_type_selected($, value, field_id);
			}
		);
		$(document).ready(function(){

			$('.ck_fields_type').each(function(){

				var field_id = $(this).data('field_id');

				var value   = $( '.' + field_id + '_ck_fields_type').children( "option:selected" ).val();

				prod_opt_hide_field_on_type_selected($, value, field_id);
			});

		});
	}
);

function prod_opt_hide_field_on_type_selected($, value, field_id){

	if (value == "drop_down") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').hide();
		$('.'+field_id+'_prod_opt_limit_range_main_div').hide();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').show();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').show();
		$('.'+field_id+'_prod_opt_color_div').hide();
		$('.'+field_id+'_prod_opt_img_div').hide();
		$('.'+field_id+'_prod_opt_optn_name_div').css('width','100%');

	} else if (value == "multi_select") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').hide();
		$('.'+field_id+'_prod_opt_limit_range_main_div').hide();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').show();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').show();
		$('.'+field_id+'_prod_opt_color_div').hide();
		$('.'+field_id+'_prod_opt_img_div').hide();
		$('.'+field_id+'_prod_opt_optn_name_div').css('width','100%');

	}else if (value == "check_boxes") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').hide();
		$('.'+field_id+'_prod_opt_limit_range_main_div').hide();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').show();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').show();
		$('.'+field_id+'_prod_opt_color_div').hide();
		$('.'+field_id+'_prod_opt_img_div').hide();
		$('.'+field_id+'_prod_opt_optn_name_div').css('width','100%');

	}else if (value == "input_text") {	

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').show();
		$('.'+field_id+'_prod_opt_limit_range_main_div').show();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').hide();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').hide();
		if ($('.' + field_id + '_ck_field_price_checkbox').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideUp('slow');
		}
		if ($('.' + field_id + '_ck_field_limit_range').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideUp('slow');
		}

	}else if (value == "textarea") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').show();
		$('.'+field_id+'_prod_opt_limit_range_main_div').show();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').hide();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').hide();
		if ($('.' + field_id + '_ck_field_price_checkbox').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideUp('slow');
		}
		if ($('.' + field_id + '_ck_field_limit_range').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideUp('slow');
		}

	}else if (value == "file_upload") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').show();
		$('.'+field_id+'_prod_opt_limit_range_main_div').hide();
		$('.'+field_id+'_prod_opt_file_extention_main_div').show();
		$('.'+field_id+'_prod_opt_options_table_main_div').hide();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').hide();
		if ($('.' + field_id + '_ck_field_price_checkbox').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideUp('slow');
		}

	}else if (value == "number") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').show();
		$('.'+field_id+'_prod_opt_limit_range_main_div').show();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').hide();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').hide();
		if ($('.' + field_id + '_ck_field_price_checkbox').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideUp('slow');
		}
		if ($('.' + field_id + '_ck_field_limit_range').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideUp('slow');
		}

	}else if (value == "quantity") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').show();
		$('.'+field_id+'_prod_opt_limit_range_main_div').show();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').hide();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').hide();
		if ($('.' + field_id + '_ck_field_price_checkbox').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideUp('slow');
		}
		if ($('.' + field_id + '_ck_field_limit_range').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideUp('slow');
		}

	}else if (value == "radio") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').hide();
		$('.'+field_id+'_prod_opt_limit_range_main_div').hide();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').show();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').show();
		$('.'+field_id+'_prod_opt_color_div').hide();
		$('.'+field_id+'_prod_opt_img_div').hide();
		$('.'+field_id+'_prod_opt_optn_name_div').css('width','100%');

	}else if (value == "color") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').hide();
		$('.'+field_id+'_prod_opt_limit_range_main_div').hide();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').show();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').show();
		$('.'+field_id+'_prod_opt_color_div').show();
		$('.'+field_id+'_prod_opt_img_div').hide();
		$('.'+field_id+'_prod_opt_optn_name_div').css('width','80%');

	}else if (value == "image_swatcher") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').hide();
		$('.'+field_id+'_prod_opt_limit_range_main_div').hide();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').show();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').show();
		$('.'+field_id+'_prod_opt_color_div').hide();
		$('.'+field_id+'_prod_opt_img_div').show();
		$('.'+field_id+'_prod_opt_optn_name_div').css('width','80%');

	}else if (value == "image") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').hide();
		$('.'+field_id+'_prod_opt_limit_range_main_div').hide();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').show();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').show();
		$('.'+field_id+'_prod_opt_color_div').hide();
		$('.'+field_id+'_prod_opt_img_div').show();
		$('.'+field_id+'_prod_opt_optn_name_div').css('width','80%');

	}else if (value == "date_picker") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').show();
		$('.'+field_id+'_prod_opt_limit_range_main_div').hide();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').hide();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').hide();
		if ($('.' + field_id + '_ck_field_price_checkbox').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideUp('slow');
		}		

	}else if (value == "email") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').show();
		$('.'+field_id+'_prod_opt_limit_range_main_div').hide();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').hide();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').hide();
		if ($('.' + field_id + '_ck_field_price_checkbox').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideUp('slow');
		}

	}else if (value == "password") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').show();
		$('.'+field_id+'_prod_opt_limit_range_main_div').show();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').hide();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').hide();
		if ($('.' + field_id + '_ck_field_price_checkbox').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideUp('slow');
		}
		if ($('.' + field_id + '_ck_field_limit_range').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideUp('slow');
		}

	}else if (value == "time_picker") {

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').show();
		$('.'+field_id+'_prod_opt_limit_range_main_div').hide();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').hide();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').hide();
		if ($('.' + field_id + '_ck_field_price_checkbox').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideUp('slow');
		}

	}else{

		$('.'+field_id+'_prod_opt_depend_main_div').show();
		$('.'+field_id+'_field_type_selector_main_div').show();
		$('.'+field_id+'_desc_tooltip_main_div').show();
		$('.'+field_id+'_prod_opt_req_main_div').show();
		$('.'+field_id+'_prod_opt_field_price_main_div').show();
		$('.'+field_id+'_prod_opt_limit_range_main_div').show();
		$('.'+field_id+'_prod_opt_file_extention_main_div').hide();
		$('.'+field_id+'_prod_opt_options_table_main_div').hide();
		$('.'+field_id+'_prod_opt_add_optn_btn_div').hide();
		if ($('.' + field_id + '_ck_field_price_checkbox').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideUp('slow');
		}
		if ($('.' + field_id + '_ck_field_limit_range').is(':checked')) {
			$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideDown('slow');
		}else{
			$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideUp('slow');
		}

	}
}

jQuery( document ).ready(
	function($){
		$( document ).on(
			'change',
			".ck_field_dependable_selector",
			function (){

				var field_id = $(this).data('field_id');

				var value1 = $( '.'+ field_id +'_ck_field_dependable_selector').children( "option:selected" ).val();

				if (value1 == "depend") {
					$('.'+ field_id +'_field_selector_div').fadeIn('fast');
					$('.'+ field_id +'_option_selector_div').fadeIn('fast');

				}else{
					$('.'+ field_id +'_field_selector_div').fadeOut('fast');
					$('.'+ field_id +'_option_selector_div').fadeOut('fast');

				}
			}
		);
		$(document).ready(function(){
			$('.ck_field_dependable_selector').each(function(){
				var field_id = $(this).data('field_id');
				var value1 = $( '.'+ field_id +'_ck_field_dependable_selector').children( "option:selected" ).val();

				if (value1 == "depend") {
					$('.'+ field_id +'_field_selector_div').fadeIn('fast');
					$('.'+ field_id +'_option_selector_div').fadeIn('fast');

				}else{
					$('.'+ field_id +'_field_selector_div').fadeOut('fast');
					$('.'+ field_id +'_option_selector_div').fadeOut('fast');

				}	
			})
		});
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			".ck_add_desc",
			function (){
				var field_id = $(this).data('field_id');
				if ($(this).prop('checked') == true) {
					$('.' + field_id +'_desc_textarea').fadeIn('fast');
				}else{
					$('.' + field_id +'_desc_textarea').fadeOut('fast');	
				}
			}
		);
		$('.ck_add_desc').each(function(){

			var field_id = $(this).data('field_id');
			if ($(this).prop('checked') == true) {
				$('.' + field_id +'_desc_textarea').fadeIn('fast');
			}else{
				$('.' + field_id +'_desc_textarea').fadeOut('fast');	
			}
		});
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			".ck_add_tool_tip",
			function (){
				var field_id = $(this).data('field_id');
				if ($(this).prop('checked') == true) {
					$('.' + field_id +'_ck_field_tool_tip').fadeIn('fast');
				}else{
					$('.' + field_id +'_ck_field_tool_tip').fadeOut('fast');	
				}
			}
		);
		$('.ck_add_tool_tip').each(function(){

			var field_id = $(this).data('field_id');
			if ($(this).prop('checked') == true) {
				$('.' + field_id +'_ck_field_tool_tip').fadeIn('fast');
			}else{
				$('.' + field_id +'_ck_field_tool_tip').fadeOut('fast');	
			}
		});
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			".field_open_btn",
			function (e){
				e.preventDefault();
				var field_id = $(this).data('field_id');

				if ($('.' + field_id + '_field_close_btn').data('clicked', true)) {

					$( '.' + field_id + '_field_open_btn' ).hide();
					$( '.' + field_id + '_field_close_btn' ).show();
					$( '.' + field_id + '_prod_opt_depend_main_div' ).slideDown('slow');
					$( '.' + field_id + '_field_type_selector_main_div' ).slideDown('slow');
					$( '.' + field_id + '_desc_tooltip_main_div' ).slideDown('slow');
					$( '.' + field_id + '_prod_opt_req_main_div' ).slideDown('slow');

					var value   = $( '.' + field_id + '_ck_fields_type').children( "option:selected" ).val();

					prod_opt_hide_field_on_type_selected($, value, field_id);
				}
			}
		);
	}
);

jQuery( document ).ready(
	function($){
		$(document).ready(function(){
			$( '.field_open_btn' ).show();
			$( '.field_close_btn' ).hide();
			$( '.prod_opt_depend_main_div' ).hide();
			$( '.field_type_selector_main_div' ).hide();
			$( '.desc_tooltip_main_div' ).hide();
			$( '.prod_opt_req_main_div' ).hide();
			$( '.prod_opt_field_price_main_div' ).hide();
			$( '.prod_opt_limit_range_main_div' ).hide();
			$( '.prod_opt_file_extention_main_div' ).hide();
			$( '.prod_opt_options_table_main_div').hide();
			$( '.prod_opt_add_optn_btn_div' ).hide();
		});
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			".field_close_btn",
			function (e){
				e.preventDefault();
				var field_id = $(this).data('field_id');

				if ($('.' + field_id + '_field_open_btn').data('clicked', true)) {

					$( '.' + field_id + '_field_open_btn' ).show();
					$( '.' + field_id + '_field_close_btn' ).hide();
					$( '.' + field_id + '_prod_opt_depend_main_div' ).slideUp('slow');
					$( '.' + field_id + '_field_type_selector_main_div' ).slideUp('slow');
					$( '.' + field_id + '_desc_tooltip_main_div' ).slideUp('slow');
					$( '.' + field_id + '_prod_opt_req_main_div' ).slideUp('slow');
					$( '.' + field_id + '_prod_opt_field_price_main_div' ).slideUp('slow');
					$( '.' + field_id + '_prod_opt_limit_range_main_div' ).slideUp('slow');
					$( '.' + field_id + '_prod_opt_file_extention_main_div' ).slideUp('slow');
					$( '.' + field_id + '_prod_opt_options_table_main_div').slideUp('slow');
					$( '.' + field_id + '_prod_opt_add_optn_btn_div' ).slideUp('slow');

				}
			}
		);
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			".expand_all_btn",
			function (e){
				e.preventDefault();
				$( '.field_open_btn' ).hide();
				$( '.field_close_btn' ).show();
				$( '.prod_opt_depend_main_div' ).slideDown('slow');
				$( '.field_type_selector_main_div' ).slideDown('slow');
				$( '.desc_tooltip_main_div' ).slideDown('slow');
				$( '.prod_opt_req_main_div' ).slideDown('slow');
				$('.ck_fields_type').each(function(){

					var field_id = $(this).data('field_id');

					var value   = $( '.' + field_id + '_ck_fields_type').children( "option:selected" ).val();

					prod_opt_hide_field_on_type_selected($, value, field_id);
				});
			}
		);
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			".close_all_btn",
			function (e){
				e.preventDefault();
				$( '.field_open_btn' ).show();
				$( '.field_close_btn' ).hide();
				$( '.prod_opt_depend_main_div' ).slideUp('slow');
				$( '.field_type_selector_main_div' ).slideUp('slow');
				$( '.desc_tooltip_main_div' ).slideUp('slow');
				$( '.prod_opt_req_main_div' ).slideUp('slow');
				$( '.prod_opt_field_price_main_div' ).slideUp('slow');
				$( '.prod_opt_limit_range_main_div' ).slideUp('slow');
				$( '.prod_opt_file_extention_main_div' ).slideUp('slow');
				$( '.prod_opt_options_table_main_div').slideUp('slow');
				$( '.prod_opt_add_optn_btn_div' ).slideUp('slow');
			}
		);
	}
);

jQuery( document ).ready(
	function($){
		$(document).on('click','.prod_opt_add_img_btn',function(e){
			e.preventDefault();
			var id=$( this ).closest( "tr.prod_optn_option_tr" ).find( '.hidden_optn_id' ).val();
			var fid=$( this ).closest( "tr.prod_optn_option_tr" ).find( '.hidden_field_id' ).val();
			"use strict";
			var image = wp.media(
				{
					title: 'Upload Image',
					multiple: false
				}
			).open()
			.on(
				'select',
				function(){

					console.log(id);
					console.log(fid);

					var uploaded_image = image.state().get( 'selection' ).first();

					var image_url = uploaded_image.toJSON().url;

					jQuery( '#'+fid+'image_upload'+id ).val( image_url );

					jQuery( '.'+fid+'_option_image_'+id ).attr( "src", image_url );

					jQuery('.'+fid+'_prod_opt_add_img_btn_'+id).css('display','none');

					jQuery('#remove_option_image'+id).css('display','revert');

					jQuery('.'+fid+'_option_image_'+id).show();

					$('.options_image['+fid+']['+id+']').val(image_url);
				}
			);

		})
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			'.remove_option_image' ,
			function (e){
				e.preventDefault();
				var id=$( this ).closest( "tr.prod_optn_option_tr" ).find( '.hidden_optn_id' ).val();
				var fid=$( this ).closest( "tr.prod_optn_option_tr" ).find( '.hidden_field_id' ).val();

				jQuery( '#'+fid+'image_upload'+id ).val( '' );

				jQuery( '.'+fid+'_option_image_'+id ).attr( 'src', '' );

				jQuery( '.'+fid+'_option_image_'+id ).css( 'display', 'none' );

				jQuery('.'+fid+'_prod_opt_add_img_btn_'+id).css('display','revert');

				jQuery('#remove_option_image'+id).css('display','none');
			}
		);
		$(document).ready(
			function(){
				$("tr.prod_optn_option_tr").each(function(){

					var id=$( this ).data("option_id");

					var fid=$( this ).data( "field_id");

					var img_src=jQuery( '.'+fid+'_option_image_'+id ).attr('src');

					if(img_src){

						jQuery('.'+fid+'_prod_opt_add_img_btn_'+id).css('display','none');

					}else{

						jQuery('#remove_option_image'+id).css('display','none');

						jQuery( '.'+fid+'_option_image_'+id ).hide();

					}

				})
			}
		);
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			'.option_add_btn',
			function (e){
				e.preventDefault();
				var parent_id = $(this).data('parent_id');
				var type = $(this).data('type');
				console.log(type);
				jQuery.ajax({
					url: ajaxurl,
					// dataType: 'json',
					type: 'POST',
					data: {
						parent_id 		: parent_id,
						type 			: type,
						action          : 'add_new_option',
						nonce 			: nonce,
					},
					success: function(data){
						$('.' + parent_id + '_prod_opt_table').append(data);

						var value   = $( '.' + parent_id + '_ck_fields_type').children( "option:selected" ).val();

						prod_opt_hide_field_on_type_selected($, value, parent_id);
					}

				});
			}
		);
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			".ck_field_price_checkbox",
			function (){
				var field_id = $(this).data('field_id');

				if ($('.' + field_id + '_ck_field_price_checkbox').is(':checked')) {

					$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideDown('slow');

				}else{

					$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideUp('slow');

				}
			}
		);

		$(document).ready(function(){
			var field_id = $('.prod_optn_field_id').val();

			if ($('.' + field_id + '_ck_field_price_checkbox').is(':checked')) {

				$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideDown('slow');

			}else{

				$( '.' + field_id + '_prod_opt_field_price_type_price_div' ).slideUp('slow');

			}
		});
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			".ck_field_limit_range",
			function (){
				var field_id = $(this).data('field_id');

				if ($('.' + field_id + '_ck_field_limit_range').is(':checked')) {

					$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideDown('slow');

				}else{

					$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideUp('slow');

				}
			}
		);

		$(document).ready(function(){
			var field_id = $('.prod_optn_field_id').val();

			if ($('.' + field_id + '_ck_field_limit_range').is(':checked')) {

				$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideDown('slow');

			}else{

				$( '.' + field_id + '_prod_opt_field_limit_to_div' ).slideUp('slow');

			}
		});
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			'.field_add_btn',
			function (e){
				e.preventDefault();
				var parent_id = $(this).data('rule_id');
				var type = $(this).data('type');
				jQuery.ajax({
					url: ajaxurl,
					// dataType: 'json',
					type: 'POST',
					data: {
						rule_id : parent_id,
						type            : type,
						action          : 'add_new_field',
						nonce 			: nonce,
					},
					success: function(data){
						$('.fields_main_div').append(data);

						var field_id = $('.new_field_id').val();

						$('.' + field_id + '_option_selector').select2();
						$( '.' + field_id + '_field_open_btn' ).hide();
						$( '.' + field_id + '_field_close_btn' ).show();
						$( '.' + field_id + '_field_selector_div' ).hide();
						$( '.' + field_id + '_option_selector_div' ).hide();
						$( '.' + field_id + '_desc_textarea' ).hide();
						$( '.' + field_id + '_ck_field_tool_tip' ).hide();

						var value   = $( '.' + field_id + '_ck_fields_type').children( "option:selected" ).val();

						prod_opt_hide_field_on_type_selected($, value, field_id);
					}

				});
			}
		);
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			".field_dlte_btn",
			function (e){
				e.preventDefault();

				if (confirm("Are You Sure About this?")) {

					var field_id = $(this).data('field_id');
					var type = $(this).data('type');
					console.log(type);
					var button = $(this);
					jQuery.ajax({
						url: ajaxurl,
						type: 'POST',
						data: {
							field_id : field_id,
							type : type,
							action  : 'remove_field',
							nonce 	: nonce,
						},
						success: function(data){
							button.closest('.field_div').remove();
							$( '.field_open_btn' ).hide();
							$( '.field_close_btn' ).show();
						}

					});
				}
			}
		);
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'click',
			".optn_remove_btn",
			function (e){
				e.preventDefault();

				if (confirm("Are You Sure About this?")) {

					var option_id = $(this).data('option_id');
					var button = $(this);
					jQuery.ajax({
						url: ajaxurl,
						type: 'POST',
						data: {
							option_id : option_id,
							action  : 'remove_option',
							nonce 	: nonce,
						},
						success: function(data){
							button.closest('tr').remove();
							$('.field_open_btn').hide();
						}

					});
				}
			}
		);
	}
);

jQuery( document ).ready(
	function($){
		$( document ).on(
			'change',
			".field_selector",
			function (){
				
				var field_id = $(this).data('field_id');
				var field_value 	 = $(this).children("option:selected").val();
				var option_value     = $('.'+field_id+'_option_selector').val();

				jQuery.ajax({
					url: ajaxurl,
					// dataType: 'json',
					type: 'POST',
					data: {
						field_id : field_id,
						field_value  	 : field_value,
						option_value  	 : JSON.stringify(option_value),
						action           : 'dependable_option',
						nonce 			 : nonce,
					},
					success: function(data){
						
						$('.'+field_id+'_option_selector').html(data);
						$('.'+field_id+'_option_selector').select2();
					}
				});
			}
		);
	}
);

jQuery( document ).ready(
	function($){
		$(document).on(
			'click',
			'.csv_upload_btn',
			function(e){

				e.preventDefault();
				var field_id 	= $('input[name=current_rule_id]').val();
				var file_data 	= $( ".prd_opt_csv_upload" ).prop( "files" )[0];
				var form_data 	= new FormData();
				form_data.append( "prd_opt_csv_upload", file_data );
				form_data.append( 'action', 'af_import_csv' );
				form_data.append( 'nonce' , nonce );
				form_data.append( 'current_rule_id' , field_id );

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					nonce : nonce,
					contentType: false,
					processData: false,
					data: form_data,
					success: function(data){
						window.location.reload();
					}
				});
			}
		);
	}
);