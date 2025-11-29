jQuery(document).ready( function($){

	$('.ct-tepfw-live-search').select2();

	$(document).ajaxComplete(function(event, xhr, settings) {
		if ( settings.data && settings.data.toLowerCase().includes ( 'woocommerce_load_variations' ) ) {

			$('.ct-tepfw-live-search').select2();
		}
	});

	jQuery( '.ct-tepfw-product-search' ).select2({
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
			processResults: function ( data ) {
				var options = [];
				if (data ) {
					$.each(
						data,
						function ( index, text ) {
							options.push( { id: text[0], text: text[1]  } );
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

	jQuery( '.ct-tepfw-cat-search' ).select2({
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
			processResults: function ( data ) {
				var options = [];
				if (data ) {
					$.each(
						data,
						function ( index, text ) {
							options.push( { id: text[0], text: text[1]  } );
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

	$(document).on('change','.ct_tepfw_show_default_or_custom_msg_with_checkbox',function(){
		ct_tepfw_show_default_or_custom_msg_with_checkbox();
	});

	function ct_tepfw_show_default_or_custom_msg_with_checkbox() {

		if ( 'custom_message' == $('.ct_tepfw_show_default_or_custom_msg_with_checkbox').val() ) {
			$('.ct_tepfw_custom_message').closest('tr').show();
		} else {
			$('.ct_tepfw_custom_message').closest('tr').hide();
		}
	}

});

