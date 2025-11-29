jQuery(document).ready( function($){

	$('.ct-rbpaqp-live-search').select2();

	$(document).ajaxComplete(function(event, xhr, settings) {
		if ( settings.data && settings.data.toLowerCase().includes ( 'woocommerce_load_variations' ) ) {

			$('.ct-rbpaqp-live-search').select2();
		}
	});

	jQuery( '.ct_rbpaqp_product_search ' ).select2({
		ajax: {
			url: ct_rbpaqp_var.ajaxurl,
			dataType: 'json',
			type: 'POST',
			delay: 20, 
			data: function (params) {
				return {
					q: params.term,
					action: 'product_search',
					nonce: ct_rbpaqp_var.nonce,
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

	jQuery( '.ct-rbpaqp-cat-search' ).select2({
		ajax: {
			url: ct_rbpaqp_var.ajaxurl,
			dataType: 'json',
			type: 'POST',
			delay: 20, 
			data: function (params) {
				return {
					q: params.term,
					action: 'category_search',
					nonce: ct_rbpaqp_var.nonce,
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




	$(document).on('click','.ct-rbpaqp-add-new-customer-or-role-base-rbp',function(){

		let add_with  		= $(this).data('add_with');
		let post_id 	 	= $(this).data('post_id');

		jQuery.ajax({
			url:ct_rbpaqp_var.ajaxurl,
			type: 'POST',
			data : {
				action 		: 'ct_rbpaqp_add_new_customer_or_role_base_rbp',
				form 		: $(this).closest('form').serialize(),
				add_with 	: add_with,
				post_id 	: post_id,
				nonce 		: ct_rbpaqp_var.nonce
			},
			success: function (response) {

				console.log( response );

				if ( response['html'] ) {

					$('.'+post_id+'ct-rbpaqp-' + add_with +' table tbody').append(response['html']);
					$('.ct-rbpaqp-live-search').select2();

				}

			},
		});

	});

	$(document).on('click','.ct-rbpaqp-delete-post-id',function(){

		let add_with  		= $(this).data('add_with');
		let current_btn 	= $(this); 
		jQuery.ajax({
			url:ct_rbpaqp_var.ajaxurl,
			type: 'POST',
			data : {
				action 		: 'ct_rbpaqp_delete_post',
				post_id 	: $(this).data('post_id'),
				add_with 	: $(this).data('add_with'),
				nonce 		: ct_rbpaqp_var.nonce
			},
			success: function (response) {

				console.log( response );

				if ( response['delete'] ) {
					current_btn.closest('tr').remove();
				}

			},
		});

	});


	// hide Price and add to cart button.

	ct_rbpaqp_hpaatcb_add_to_cart_btn();

	jQuery(document).on('change click','.ct_rbpaqp_hpaatcb_price_setting , .ct_rbpaqp_hpaatcb_add_to_cart_btn , .ct_rbpaqp_hpav_hide_product , .ct_rbpaqp_hpav_hide_product_base_on ',function(){
		ct_rbpaqp_hpaatcb_add_to_cart_btn();
	});

	function ct_rbpaqp_hpaatcb_add_to_cart_btn() {


		if ( $('.ct_rbpaqp_hpaatcb_hide_product').is(':checked') ) {

			$('.ct_rbpaqp_hpaatcb_dependable').each(function(){
				$(this).closest('tr').fadeOut('fast');
			});



		} else {

			let ct_rbpaqp_hpaatcb_add_to_cart_btn 	= $('.ct_rbpaqp_hpaatcb_add_to_cart_btn option:selected').val();
			let ct_rbpaqp_hpaatcb_price_setting 	= $('.ct_rbpaqp_hpaatcb_price_setting option:selected').val();

			$('.ct_rbpaqp_hpaatcb_add_to_cart_btn').closest('tr').fadeIn('slow');
			$('.ct_rbpaqp_hpaatcb_price_setting').closest('tr').fadeIn('slow');

			if ( 'replace_text_and_link' == ct_rbpaqp_hpaatcb_add_to_cart_btn ) {
				$('.ct_rbpaqp_hpaatcb_button_text , .ct_rbpaqp_hpaatcb_custom_link').closest('tr').fadeIn('slow');
				$('.ct_rbpaqp_hpaatcb_make_your_on_button').closest('tr').fadeOut('fast');

			} 	else if( 'make_your_on_button' == ct_rbpaqp_hpaatcb_add_to_cart_btn ) {
				$('.ct_rbpaqp_hpaatcb_button_text , .ct_rbpaqp_hpaatcb_custom_link ').closest('tr').fadeOut('fast');
				$('.ct_rbpaqp_hpaatcb_make_your_on_button').closest('tr').fadeIn('fast');

			}	else {
				$('.ct_rbpaqp_hpaatcb_button_text , .ct_rbpaqp_hpaatcb_custom_link , .ct_rbpaqp_hpaatcb_make_your_on_button').closest('tr').fadeOut('fast');

			}


			if ( 'replace_price_text' == ct_rbpaqp_hpaatcb_price_setting ) {
				$('.ct_rbpaqp_hpaatcb_price_text').closest('tr').fadeIn('slow');
			} 	else {
				$('.ct_rbpaqp_hpaatcb_price_text').closest('tr').fadeOut('fast');

			}
		}



		if ( $('.ct_rbpaqp_hpav_hide_product').is(':checked') ) {
			$('.ct_rbpaqp_hpav_hide_product_base_on').closest('tr').show();
			$('.ct_rbpaqp_hpav_hide_product_on_min_quantity').closest('tr').hide();

			if ( 'on_specific_quantity' == $('.ct_rbpaqp_hpav_hide_product_base_on').val() ) {
				$('.ct_rbpaqp_hpav_hide_product_on_min_quantity').closest('tr').show();
			}
		}else{

			$('.ct_rbpaqp_hpav_hide_product_base_on , .ct_rbpaqp_hpav_hide_product_on_min_quantity').closest('tr').hide();

		}
	}

	ct_rbpaqp_mmq_add_purchas_item_qty_or_not()
	jQuery(document).on('click','.ct_rbpaqp_mmq_add_purchas_item_qty_or_not',function(){
		ct_rbpaqp_mmq_add_purchas_item_qty_or_not();
	});

	function ct_rbpaqp_mmq_add_purchas_item_qty_or_not() {

		if ( jQuery('.ct_rbpaqp_mmq_add_purchas_item_qty_or_not').is(':checked') ) {
			jQuery('.ct_rbpaqp_mmq_order_status_purchas_item_qty').closest('tr').show();
		} else {
			jQuery('.ct_rbpaqp_mmq_order_status_purchas_item_qty').closest('tr').hide();
		}

	}


});

