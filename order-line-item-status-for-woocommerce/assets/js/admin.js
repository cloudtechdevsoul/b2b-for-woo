jQuery(document).ready( function($) {


	ct_enable_mail_on_line_status();

	jQuery(document).on('click','.ct_enable_mail_on_line_status',function(){
		ct_enable_mail_on_line_status();
	});

	function ct_enable_mail_on_line_status() {

		jQuery('.ct_enable_mail_on_line_status').each(function(){

			var status 		= '.ct-olisfw-'+$(this).data('order_status_key');

			if ( $(this).is(':checked') ) {
				jQuery(status).each(function(){
					$(this).show();
				});
			}else {
				jQuery(status).each(function(){
					$(this).hide();
				});
			}

		});

	}

} );