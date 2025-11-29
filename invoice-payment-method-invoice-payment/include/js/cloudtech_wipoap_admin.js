var ajaxurl = cloudtech_wipo_ajax_var.admin_url;
jQuery('document').ready(function ($) {

	'use strict';

	$('.af-ig-select2').select2();

	$(document).on('click', '.ct_ipoaip_select_all_ids', function () {

		let checked_or_not = $(this).prop('checked');
		$('.ct_ipoaip_order_ids').each(function () {
			$(this).prop('checked', checked_or_not);
		});

	});
});

(function ($) {
	'use strict';
	$(
		function () {
			var ajaxurl = cloudtech_wipo_ajax_var.admin_url;
			var nonce = cloudtech_wipo_ajax_var.nonce;


			$('.cloudtech_wipo_ajax_customer_search').select2(
				{
					ajax: {
						url: ajaxurl,
						dataType: 'json',
						type: 'POST',
						delay: 250,
						data: function (params) {
							return {
								q: params.term,
								action: 'cloudtech_wipo_search_users',
								nonce: nonce
							};
						},
						processResults: function (data) {
							var options = [];
							if (data) {

								$.each(
									data, function (index, text) {

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
					placeholder: 'Choose Users',
					minimumInputLength: 3

				});


			jQuery('.af-ig-ajax-products-search').select2(
				{
					ajax: {
						url: ajaxurl,
						dataType: 'json',
						type: 'POST',
						delay: 250,
						data: function (params) {
							return {
								q: params.term,
								action: 'cloudtech_wipo_search_products',
								nonce: nonce
							};
						},
						processResults: function (data) {
							var options = [];
							if (data) {


								$.each(
									data, function (index, text) {

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
					minimumInputLength: 3

				}
			);

		}
	);

})(jQuery);

jQuery(document).ready(
	function ($) {
		"use strict";
		cloudtech_wipo_products();
		jQuery(document).on('click', '.cloudtech_wipo_products', function (e) {

			cloudtech_wipo_products();
		});
	});


function cloudtech_wipo_products() {
	jQuery('.hide_ig_setting_pro').each(function () {
		jQuery(this).closest('tr').show();
	});
	if (jQuery('.cloudtech_wipo_products').is(":checked")) {
		jQuery('.hide_ig_setting_pro').each(function () {
			jQuery(this).closest('tr').hide();
		});
	}
}
jQuery(document).ready(function ($) {
	$(document).on('click', ".open-popup-button", function (e) {
		e.preventDefault();
		$("#custom-popup").dialog({
			modal: true,
			width: 500,
			buttons: {
				Send: function () {
					// Handle sending the editor content
					var editorContent = $('textarea[name="custom_editor"]').val();
					var billing_name = $('input[name="billing_name"]').val();
					var email = $('input[name="user_email"]').val();
					var company_code = $('input[name="company_code"]').val();
					var vat_code = $('input[name="vat_code"]').val();
					var inv_date = $('input[name="inv_date"]').val();
					var order_number = $('input[name="order_number"]').val();
					var order_date = $('input[name="order_date"]').val();
					var payement_method = $('input[name="payement_method"]').val();
					var trip_title = $('input[name="trip_title"]').val();
					var people_qty = $('input[name="people_qty"]').val();
					var trip_total = $('input[name="trip_total"]').val();
					var user_address = $('input[name="user_address"]').val();
					var comapny_name = $('input[name="comapny_name"]').val();
					var company_code = $('input[name="company_code"]').val();
					var company_vat = $('input[name="company_vat"]').val();
					var inv_number = $('input[name="inv_number"]').val();
					console.log(billing_name);
					$.post(ajaxurl, {
						action: "generate_and_send_invoice",
						editorContent: editorContent,
						billing_name: billing_name,
						email: email,
						company_code: company_code,
						vat_code: vat_code,
						inv_date: inv_date,
						order_number: order_number,
						order_date: order_date,
						payement_method: payement_method,
						trip_title: trip_title,
						people_qty: people_qty,
						trip_total: trip_total,
						user_address: user_address,
						comapny_name: comapny_name,
						company_code: company_code,
						company_vat: company_vat,
						inv_number: inv_number
					}, function (response) {
						// Handle response (e.g., show success message)
						alert(response);
					});
					$(this).dialog("close");
				},
				Cancel: function () {
					$(this).dialog("close");
				},
			},
		});
	});
})

jQuery(document).ready(function ($) {
	$(document).on('click', '#ka_gw_upload_gift_wrapper_img', function (e) {
		e.preventDefault();
		upload_files_fo_card_wrapper = wp.media(
			{
				title: 'Upload Image',

				multiple: true,
				multiple: 'false',
			}
		);
		upload_files_fo_card_wrapper.on('select', function () {
			var attachments = upload_files_fo_card_wrapper.state().get('selection').map(
				function (attachment) {
					attachment.toJSON();
					return attachment;
				});
			var i;
			var array_of_img_links = [];
			var ul = document.createElement('ul');
			for (i = 0; i < attachments.length; ++i) {
				var li_no = i + 1;
				array_of_img_links.push(attachments[i].attributes.url + "gift_wrapper_selected_files");
				var li = document.createElement('li');
				var image = document.createElement('img');
				image.setAttribute('src', attachments[i].attributes.url);
				$('img[name="img-url"]').attr('src', attachments[i].attributes.url);
				$('input[name="cloudtect_invoice_logo_url"]').val(attachments[i].attributes.url);
			}
		});
		upload_files_fo_card_wrapper.open();
	});
})
