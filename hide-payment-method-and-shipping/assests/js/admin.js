jQuery(document).ready(function($){
jQuery('.ct_wo_bid_user_role').select2({});

jQuery('.ct_hspm_product_live_search').select2(
{
    ajax: {
        url: cthspm_bidd_data.admin_url, // AJAX URL is predefined in WordPress admin.
        dataType: 'json',
        type: 'POST',
        delay: 20, // Delay in ms while typing when to perform a AJAX search.
        data: function (params) {
            return {
                q: params.term, // search query
                action: 'af_rfd_prod_search', // AJAX action for admin-ajax.php.//aftaxsearchUsers(is function name which isused in adminn file)
                nonce: cthspm_bidd_data.nonce // AJAX nonce for admin-ajax.php.
            };
        },
        processResults: function ( data ) {
            var options = [];
            if (data ) {
                     // data is the array of arrays, and each of them contains ID and the Label of the option.
                    $.each(
                        data, function ( index, text ) {
                            // do not forget that "index" is just auto incremented value.
                            options.push({ id: text[0], text: text[1]  });
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
    // minimumInputLength: 3 // the minimum of symbols to input before perform a search.
  });
jQuery(document).on('click','.ct_bidding_send_email_data',function(e){

        // e.preventDefault();
        // Send data via AJAX
        jQuery.ajax({
            type: 'POST',
            url: cthspm_bidd_data.admin_url, 
            data: { 
                action              : 'ct_email_data_user',
                type                : $(this).data('type'),
                current_post_id     : $(this).data('current_post_id'),

            },
            success: function(response){
                // window.location.reload( true );
            }

        });
    });


jQuery(document).ready(function ($) {
    customer_login();

    $(document).on('change', '.city_payment_or_shipping', function (e) {
        customer_login();
    });
});

function customer_login() {
    var login = jQuery('.city_payment_or_shipping').val();
    console.log(login);

    // Hide all tr elements initially
    $('.ct_hspm_payment_method, .ct_hspm_shipping_method').closest('tr').hide();

    if ('city_both' === login) {
        // Show both payment and shipping tr elements
        $('.ct_hspm_payment_method, .ct_hspm_shipping_method').closest('tr').show();
    } else if ('city_shipping' === login) {
        // Show only shipping tr elements
        $('.ct_hspm_shipping_method').closest('tr').show();
    } else if ('city_payment_method' === login) {
        // Show only payment tr elements
        $('.ct_hspm_payment_method').closest('tr').show();
    }
}


jQuery('.ct_hspm_categroy_live_search').select2(
{
    ajax: {
        url: cthspm_bidd_data.admin_url, // AJAX URL is predefined in WordPress admin.
        dataType: 'json',
        type: 'POST',
        delay: 20, // Delay in ms while typing when to perform a AJAX search.
        data: function (params) {
            return {
                q: params.term, // search query
                action: 'category_search', // AJAX action for admin-ajax.php.//aftaxsearchUsers(is function name which isused in adminn file)
                nonce: cthspm_bidd_data.nonce // AJAX nonce for admin-ajax.php.
            };
        },
        processResults: function ( data ) {
            var options = [];
            if (data ) {
                     // data is the array of arrays, and each of them contains ID and the Label of the option.
                    $.each(
                        data, function ( index, text ) {
                            // do not forget that "index" is just auto incremented value.
                            options.push({ id: text[0], text: text[1]  });
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
    // minimumInputLength: 3 // the minimum of symbols to input before perform a search.
  });
jQuery('.ct_hspm_country_live_search').select2(
{
    ajax: {
        url: cthspm_bidd_data.admin_url,
        dataType: 'json',
        type: 'POST',
        delay: 20,
        data: function (params) {
            return {
                q: params.term,
                action: 'country_search', // Update the action for country search.
                nonce: cthspm_bidd_data.nonce
            };
        },
        processResults: function ( data ) {
            var options = [];
            if (data ) {
                $.each(
                    data, function ( index, text ) {
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
    placeholder: 'Choose country',
});
jQuery('.ct_hspm_user_live_search').select2({
    ajax: {
        url: ajaxurl, // Use the WordPress AJAX URL
        dataType: 'json',
        type: 'POST',
        delay: 20,
        data: function (params) {
            return {
                q: params.term,
                action: 'user_search',
                 nonce: cthspm_bidd_data.nonce, // Replace with the nonce value
            };
        },
        processResults: function (data) {
            var options = [];
            if (data) {
                $.each(data, function (index, user) {
                    options.push({ id: user.ID, text: user.display_name });
                });
            }
            return { results: options };
        },
        cache: true
    },
    multiple: true,
    placeholder: 'Choose users',
});

jQuery('.cthspm_live_search').select2();








// user role
jQuery('.ct_hide_shipping_payment_serch_user').select2();
jQuery(document).on('click','.ct_bidding_type',function(e){
      
     ct_bidding_bid_type();

   });
jQuery(document).on('click','.ct_bidding_type_man',function(e){
      
   jQuery('.ct_bidding_inc').on('tr').css('display','none');

   });
});



function ct_bidding_bid_type(){

    if ( jQuery ('.ct_bidding_type').is(':checked') ){
        jQuery('.ct_bidding_inc').closest('tr').css('display', 'revert');   
    }
}


