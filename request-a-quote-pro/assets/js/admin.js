jQuery(document).ready(function ($) {

	$('.ct-rfq-live-search').select2();

	$(document).ajaxComplete(function (event, xhr, settings) {
		if (settings.data && settings.data.toLowerCase().includes('woocommerce_load_variations')) {

			$('.ct-rfq-live-search').select2();
		}
	});
	ct_rfq_request_a_quote_billing_field_template();
	$(document).on('change', '.ct_rfq_request_a_quote_billing_field_template', ct_rfq_request_a_quote_billing_field_template);
	function ct_rfq_request_a_quote_billing_field_template() {

		let template = $('.ct_rfq_request_a_quote_billing_field_template').children('option:selected').val();

		$('.ct-rfq-checkout-template-images').hide();
		$('.ct-rfq-checkout-' + template).show();

	}

	ct_rfq_request_a_quote_checkboxes();

	$(document).on('click', '.ct_rfq_request_a_quote_checkboxes', ct_rfq_request_a_quote_checkboxes);
	function ct_rfq_request_a_quote_checkboxes() {
		if ($('.ct_rfq_request_a_quote_checkboxes').is(':checked')) {
			$('.ct_rfq_request_a_quote_whole_button_text').closest('tr').show('slow');
		} else {
			$('.ct_rfq_request_a_quote_whole_button_text').closest('tr').hide('fast');

		}
	}

	check_dependable();
	$(document).on('click', '.billing-profile, .shipping-profile', check_dependable);

	function check_dependable() {


		let billing_selected_val = $('.billing-profile:checked').val() ? $('.billing-profile:checked').val() : 'private';

		if ('company' == billing_selected_val) {

			$('.ct-rfq-billing-fields-with-private').each(function () {
				$(this).hide();
			});
			$('.ct-rfq-billing-fields-with-company').each(function () {
				$(this).show();
			});

		} else {

			$('.ct-rfq-billing-fields-with-company').each(function () {
				$(this).hide();
			});
			$('.ct-rfq-billing-fields-with-private').each(function () {
				$(this).show();
			});

		}
		$('.billing-profile').closest('label').css('background', 'transparent');

		$('.billing-profile-' + billing_selected_val).closest('label').css('background', '#FFDD44');

		let shipping_selected_val = $('.shipping-profile:checked').val() ? $('.shipping-profile:checked').val() : 'private';


		$('.shipping-profile').closest('label').css('background', 'transparent');

		$('.shipping-profile-' + shipping_selected_val).closest('label').css('background', '#FFDD44');


		if ('company' == shipping_selected_val) {

			$('.ct-rfq-shipping-fields-with-private').each(function () {
				$(this).hide();
			});
			$('.ct-rfq-shipping-fields-with-company').each(function () {
				$(this).show();
			});


		} else {
			$('.ct-rfq-shipping-fields-with-company').each(function () {
				$(this).hide();
			});
			$('.ct-rfq-shipping-fields-with-private').each(function () {
				$(this).show();
			});

		}

	}


	ct_rfq_hide_price_or_replace_text();

	$(document).on('change', '.ct_rfq_hide_price_or_replace_text', ct_rfq_hide_price_or_replace_text);

	function ct_rfq_hide_price_or_replace_text() {
		// Your original string
		if ($('.ct_rfq_hide_price_or_replace_text').length) {

			var originalString = $('.ct_rfq_hide_price_or_replace_text').val();

			var stringWithSpaces = originalString.replace('_', ' ');

			var wordToCheck = "replace";

			if (stringWithSpaces.indexOf(wordToCheck) !== -1) {
				$('.ct_rfq_replace_price_text').closest('tr').show('slow');
			} else {
				$('.ct_rfq_replace_price_text').closest('tr').hide();
			}
		}
	}


	jQuery('.ct-rfq-product-search').select2({
		ajax: {
			url: ct_tepfw_var.ajaxurl,
			dataType: 'json',
			type: 'POST',
			delay: 20,
			data: function (params) {
				return {
					q: params.term,
					action: 'product_search',
					nonce: ct_tepfw_var.nonce,
				};
			},
			processResults: function (data) {
				var options = [];
				if (data) {
					$.each(
						data,
						function (index, text) {
							options.push({ id: text[0], text: text[1] });
						}
					);
				}
				return {
					results: options
				};
			},
			cache: true
		},
		multiple: true,
		placeholder: 'Choose Products',
	});

	jQuery('.ct-rfq-cat-search').select2({
		ajax: {
			url: ct_tepfw_var.ajaxurl,
			dataType: 'json',
			type: 'POST',
			delay: 20,
			data: function (params) {
				return {
					q: params.term,
					action: 'category_search',
					nonce: ct_tepfw_var.nonce,
				};
			},
			processResults: function (data) {
				var options = [];
				if (data) {
					$.each(
						data,
						function (index, text) {
							options.push({ id: text[0], text: text[1] });
						}
					);
				}
				return {
					results: options
				};
			},
			cache: true
		},
		multiple: true,
		placeholder: 'Choose category',
	});


	ct_tepfw_show_default_or_custom_msg_with_checkbox();

	$(document).on('change', '.ct_tepfw_show_default_or_custom_msg_with_checkbox', function () {
		ct_tepfw_show_default_or_custom_msg_with_checkbox();
	});

	function ct_tepfw_show_default_or_custom_msg_with_checkbox() {

		if ('custom_message' == $('.ct_tepfw_show_default_or_custom_msg_with_checkbox').val()) {
			$('.ct_tepfw_custom_message').closest('tr').show();
		} else {
			$('.ct_tepfw_custom_message').closest('tr').hide();
		}
	}

	$(document).on('click', '.ct-rfq-quote-fields-add-new-options', function () {

		let last_index = $('.ct-rfq-quote-fields-add-options-table tbody').find('tr').length;

		last_index++;


		let tr = '<tr index="' + last_index + '"><td><input type="text" name="ct_rfq_request_a_quote_options_value_and_label[' + last_index + '][option_value]"></td><td><input type="text" name="ct_rfq_request_a_quote_options_value_and_label[' + last_index + '][option_label]"></td><td><i class="fa fa-trash ct-rfq-quote-remove-quote-options"></i></td></tr>';
		$('.ct-rfq-quote-fields-add-options-table tbody').append(tr);

	});

	$(document).on('click', '.ct-rfq-quote-remove-quote-options', function () {
		$(this).closest('tr').remove();
	});

	ct_rfq_quote_fields_add_options();

	$(document).on('click change', '.ct-rfq-quote-fields-field-type', ct_rfq_quote_fields_add_options)

	function ct_rfq_quote_fields_add_options() {

		if (!$('.ct-rfq-quote-fields-add-options-table tbody').find('tr').length) {
			$('.ct-rfq-quote-fields-add-options').hide();
		}

		var whole_string = "multi_select radio multi_checkbox";
		var targetWord = $('.ct-rfq-quote-fields-field-type').val();

		if ('multi_select' == targetWord || 'radio' == targetWord || 'multi_checkbox' == targetWord) {
			$('.ct-rfq-quote-fields-add-options').show();
		} else {
			$('.ct-rfq-quote-fields-add-options').hide();
		}

	}

	ct_rfq_quote_fields_select_dependent_field_checkbox();
	$(document).on('click', '#ct_rfq_quote_fields_select_dependent_field_checkbox', ct_rfq_quote_fields_select_dependent_field_checkbox);
	function ct_rfq_quote_fields_select_dependent_field_checkbox() {

		if ($('#ct_rfq_quote_fields_select_dependent_field_checkbox').is(':checked')) {

			$('.ct_rfq_quote_fields_select_dependent_field').closest('tr').show();

		} else {
			$('.ct_rfq_quote_fields_select_dependent_field').closest('tr').hide();

		}
	}

	ct_rfq_quote_fields_select_dependent_field();
	$(document).on('change', '.ct_rfq_quote_fields_select_dependent_field', ct_rfq_quote_fields_select_dependent_field);

	function ct_rfq_quote_fields_select_dependent_field() {

		let selected_field = $('.ct_rfq_quote_fields_select_dependent_field').val();

		$('select.ct_rfq_quote_fields_selected_dependent_field_value').hide();
		$('select.ct_rfq_quote_fields_selected_dependent_field_value' + selected_field).show();

	}

	$(document).on('click', '.ct-rfq-instant-notify', function () {

		jQuery.ajax({
			url: ct_tepfw_var.ajaxurl,
			type: 'POST',
			data: {
				action: 'ct_rfq_instant_notify',
				post_ID: $(this).data('current_post'),
				nonce: ct_tepfw_var.nonce
			},
			success: function (response) {

				if (response) {
					window.location.reload(true);
				}

			},
		});

	});
	$(document).on('click', '.ct-create-order', function () {

		jQuery.ajax({
			url: ct_tepfw_var.ajaxurl,
			type: 'POST',
			data: {
				action: 'ct_create_order',
				form: $('form').serialize(),
				nonce: ct_tepfw_var.nonce
			},
			success: function (response) {


				if (response['success']) {
					window.location.reload(true);
				}


			},
		});

	});
	$(document).on('click', '.ct-rfq-add-selected-product-to-quote', function () {

		jQuery.ajax({
			url: ct_tepfw_var.ajaxurl,
			type: 'POST',
			data: {
				action: 'ct_rfq_add_selected_product_to_quote',
				selected_product: $('.ct-rfq-add-selected-product').val(),
				post_id: $('#post_ID').val(),
				nonce: ct_tepfw_var.nonce
			},
			success: function (response) {
				if (response['success']) {
					window.location.reload(true);

				}
			},
		});

	});

	$(document).on('click', '.ct-rfq-add-new-product', function () {
		$('.ct-rfq-add-new-product-main-div').show();
	});
	$(document).on('click', '.ct-rfq-close-popup', function () {
		$('.ct-rfq-add-new-product-main-div').hide();
	});

	ct_rfq_enable_select_template();
	$(document).on('change', '#ct_rfq_enable_select_template', ct_rfq_enable_select_template);

	function ct_rfq_enable_select_template() {
		let selected_optio = $('#ct_rfq_enable_select_template').children('option:selected').val();
		$('.ct-rfq-template-image').hide();
		$('.ct-rfq-' + selected_optio).show();

	}
	ct_rfq_enable_term_and_conditions();
	$(document).on('change', '.ct_rfq_enable_term_and_conditions', ct_rfq_enable_term_and_conditions);

	function ct_rfq_enable_term_and_conditions() {

		if ($('.ct_rfq_enable_term_and_conditions').is(':checked')) {
			$('#wp-ct_rfq_term_and_condition_data-wrap').closest('tr').show();

		} else {
			$('#wp-ct_rfq_term_and_condition_data-wrap').closest('tr').hide();

		}

	}


});

