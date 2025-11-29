jQuery('document').ready(
	function ($) {
		'use strict';

		$('.ct-psbs-live-search').select2();

		jQuery(document).on('click', '.ct-psbs-filter-btn', function () {

			filter_for_sales();

		});



		jQuery('input[name="ct_select_date"]:checked').closest('li').addClass('active');

		select_date_type();
		jQuery(document).on('click', 'input[name="ct_select_date"]', function () {

			jQuery('input[name="ct_select_date"]').removeAttr('checked');

			jQuery('input[name="ct_select_date"]').each(function () {
				$(this).closest('li').removeClass('active');
				$(this).removeAttr('checked');
			});

			jQuery(this).prop('checked', true);

			$(this).closest('li').addClass('active');
			select_date_type();

		});

		function select_date_type() {

			var select_date_type = jQuery('input[name="ct_select_date"]:checked').val() ? jQuery('input[name="ct_select_date"]:checked').val() : 'this_year';

			jQuery('.ct-psbs-start-date').closest('li').hide();
			if ('custom_Date' == select_date_type) {
				jQuery('.ct-psbs-start-date').closest('li').show();

			}
		}


		function filter_for_sales() {

			var select_date_type = jQuery('input[name="ct_select_date"]:checked').val() ? jQuery('input[name="ct_select_date"]:checked').val() : 'this_year';
			var url = cloudtech_wipo_ajax_var.order_table_filter_url + '&date_type=' + select_date_type;

			if (jQuery('.ct-psbs-select-roles').val()) {
				url = url + '&roles=' + jQuery('.ct-psbs-select-roles').val();
			}
			if (jQuery('.ct-psbs-select-user').val()) {
				url = url + '&customer=' + jQuery('.ct-psbs-select-user').val();
			}
			if (jQuery('.ct-psbs-select-order-status').val()) {
				url = url + '&order_status=' + jQuery('.ct-psbs-select-order-status').val();
			}

			if (jQuery('.ct_psbs_product_live_search').val()) {
				url = url + '&selected_product=' + jQuery('.ct_psbs_product_live_search').val();
			}

			if (jQuery('.ct_psbs_category_live_search').val()) {
				url = url + '&selected_cat=' + jQuery('.ct_psbs_category_live_search').val();
			}

			if (jQuery('.ct-psbs-select-countries').val()) {
				url = url + '&selected_countries=' + jQuery('.ct-psbs-select-countries').val();
			}
			if (jQuery('.ct-psbs-select-state').val()) {
				url = url + '&selected_state=' + jQuery('.ct-psbs-select-state').val();
			}
			if (jQuery('.ct-psbs-enter-city').val()) {
				url = url + '&selected_cities=' + jQuery('.ct-psbs-enter-city').val();
			}
			if (jQuery('.ct-psbs-enter-post-code').val()) {
				url = url + '&postcode=' + jQuery('.ct-psbs-enter-post-code').val();
			}

			if ('custom_Date' == select_date_type) {


				if (!jQuery('.ct-psbs-start-date').val() && !jQuery('.ct-psbs-end-date').val()) {

					jQuery('.ct-psbs-start-date').focus();
					return;

				}

				if (jQuery('.ct-psbs-start-date').val()) {

					url = url + '&start_date=' + jQuery('.ct-psbs-start-date').val();

				}

				if (jQuery('.ct-psbs-end-date').val()) {

					url = url + '&end_date=' + jQuery('.ct-psbs-end-date').val();

				}


			}
			window.location = url;
		}

		jQuery(document).on('click', '.ct_send_invoice', function () {

			$('.ct_ipoaip_order_ids').each(function () {

				if ($(this).is(':checked')) {
					devsoul_ipmaip_send_invoice($(this).val());
				}

			});
			// window.location.reload(true);

		});
		jQuery(document).on('click', '.ct_send_invoice_current_order', function () {

			devsoul_ipmaip_send_invoice($(this).data('order_id'));
			// window.location.reload(true);

		});

		function devsoul_ipmaip_send_invoice(order_id) {
			jQuery.ajax({
				url: cloudtech_wipo_ajax_var.admin_url,
				type: 'POST',
				data: {
					action: 'devsoul_ipmaip_send_invoice',
					nonce: cloudtech_wipo_ajax_var.nonce,
					order_id: order_id,
				},
				success: function (response) {
					console.log(response);

				},
			});
		}


		jQuery('.ct_psbs_customer_search').select2(
			{
				ajax: {
					url: cloudtech_wipo_ajax_var.admin_url,
					dataType: 'json',
					type: 'POST',
					data: function (params) {
						return {
							q: params.term,
							action: 'devsoul_ipmaip_customer_search',
							nonce: cloudtech_wipo_ajax_var.nonce,
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
				placeholder: 'Choose Customer',
				// minimumInputLength: 3 // the minimum of symbols to input before perform a search.
			}
		);

	}
);