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

});