jQuery(document).ready( function($){

	$('.ct-rfq-live-search').select2();

	var button_text = jQuery( '.single_add_to_cart_button' ).text();

	jQuery( '.single_add_to_cart_button' ).html( button_text );

	for (var i = 0; i < 5; i++) {

		setTimeout (function(){
			jQuery('.cloud_rfq_add_to_cart_button_text_single_pg').closest('button.single_add_to_cart_button').remove();
		},i * 1000);
	}

	$(document).on('click','.ct_rfq_select_all_checkbox',function(){

		if( $(this).is(':checked') ) {
			$('.ct_rfq_cart_page_button').prop('checked',true);
		} else {
			$('.ct_rfq_cart_page_button').prop('checked',false);
		}

	});

	$(document).on('click','.cloud-tech-remove-button',function() {

		jQuery.ajax({
			url:ct_tepfw_var.ajaxurl,
			type: 'POST',
			data : {
				action 		: 'ct_rfq_remove_cart_product',
				quote_key 	: $(this).data('quote_key'),
				nonce 		: ct_tepfw_var.nonce
			},
			success: function (response) {
				location.reload(true);


			},
		});
	});


	$(document).on('click','.ct-rfq-cart-page-button',function(){

		let product_ids_and_detail = [];

		let qty = $(this).closest('tr').find('input.qty').val() ? $(this).closest('tr').find('input.qty').val() : 1;

		let product_detail = {
			'rule_id' 		: $(this).data('rule_id'),
			'product_id'	: $(this).data('product_id'),
			'qty'			: qty
		};

		product_ids_and_detail.push(product_detail);

		add_product_to_cart( product_ids_and_detail );

	});
	$(document).on('click','.whole_sale_request_a_quote_button',function(){

		let product_ids_and_detail = [];


		$('.ct_rfq_cart_page_button').each(function(){

			if( $(this).is(':checked') ){

				let qty 			= $(this).closest('tr').find('input.qty').val() ? $(this).closest('tr').find('input.qty').val() : 1;

				let product_detail 	= {
					'rule_id' 		: $(this).data('rule_id'),
					'product_id'	: $(this).data('product_id'),
					'qty'			: qty
				};

				product_ids_and_detail.push(product_detail);
			}
		});

		add_product_to_cart( product_ids_and_detail );

	});

	function add_product_to_cart( product_ids_and_detail = [] ){

		jQuery.ajax({
			url:ct_tepfw_var.ajaxurl,
			type: 'POST',
			data : {
				action 		: 'ct_rfq_add_product_to_quote',
				product_ids_and_detail 	: product_ids_and_detail,
				nonce 		: ct_tepfw_var.nonce
			},
			success: function (response) {

				if( response['refresh'] ){

					location.reload(true);
				}

			},
		});

	}


	check_dependable();
	$(document).on('click','.billing-profile , .shipping-profile',check_dependable);

	function check_dependable(){


		let billing_selected_val 	= $('.billing-profile:checked').val() ? $('.billing-profile:checked').val() : 'private';
		let shipping_selected_val 	= $('.shipping-profile:checked').val() ? $('.shipping-profile:checked').val() : 'private';
		console.log(billing_selected_val);
		console.log(shipping_selected_val);


		if ( 'company' == billing_selected_val ) {

			$('.ct-rfq-billing-fields-with-private').hide();
			$('.ct-rfq-billing-fields-with-company').show();

		}else {

			$('.ct-rfq-billing-fields-with-private').show();
			$('.ct-rfq-billing-fields-with-company').hide();

		}
		if ( 'company' == shipping_selected_val ) {

		
			$('.ct-rfq-shipping-fields-with-private').hide();
			$('.ct-rfq-shipping-fields-with-company').show();

		}else {

			$('.ct-rfq-shipping-fields-with-private').show();
			$('.ct-rfq-shipping-fields-with-company').hide();

		}
	

	}
	
});