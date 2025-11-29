jQuery( document ).on(
	'click',
	'li.wc_payment_method',
	function () {
		jQuery( document.body ).trigger( "update_checkout" );
	}
);
