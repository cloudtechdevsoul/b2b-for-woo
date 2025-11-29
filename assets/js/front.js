jQuery(document).ready( function($){

	var button_text = jQuery( '.single_add_to_cart_button' ).text();

	jQuery( '.single_add_to_cart_button' ).html( button_text );

	setInterval(function(){

		if ( jQuery('.cloud_hpaatcb_add_to_cart_button_text').length && jQuery('.cloud_hpaatcb_add_to_cart_button_text').closest('button.single_add_to_cart_button').length ) {

			let single_button_html = jQuery('.cloud_hpaatcb_add_to_cart_button_text').html();

			jQuery('button.single_add_to_cart_button').before(single_button_html);
			jQuery('button.single_add_to_cart_button').remove();
		}

	},1000);


	$(document).on('change','.variation_id',function(){

		$('.cloud_hpaatcb_var_product_qty_table_html').each( function() {
			$(this).hide();
		});

		let variation_id = $(this).val();

		if ( variation_id) {

			$('.cloud_hpaatcb_var_product_qty_table_html'+variation_id).fadeIn('slow');


		}

		let step 	= $('input[name=ct_rbpaqp_get_product_min_max_qty'+variation_id+']').val() ? $('input[name=ct_rbpaqp_get_product_min_max_qty'+variation_id+']').val() : 1;

		$('form.variations_form').find('input[name=quantity]').prop('step', step );

		// let form_data 	= $(this).closest('form').serialize();

		// jQuery.ajax(
		// 			{
		// 				url: ct_rbpaqp_var.ajaxurl,
		// 				type: 'POST',
		// 				data: {
		// 					action 			: 'ct_rbpaq_get_product_min_max_qty',
		// 					nonce 			: ct_rbpaqp_var.nonce,
		// 					form_data 		: form_data,
		// 				},
		// 				success: function(response){

		// 					if ( response['product_detail'] ) {
		// 						  if ( response['product_detail']['step'] ) {

		// 						  	$('form.variations_form').find('input[name=quantity]').prop('step', response['product_detail']['step'] );
		// 						  }
		// 					}
		// 					if ( response['product_qty_table_html'] ) {

		// 						jQuery('.cloud_hpaatcb_product_qty_table_html').html( response['product_qty_table_html'] );
		// 					}

		// 				}

		// 			}
		// 		);  
	});

});