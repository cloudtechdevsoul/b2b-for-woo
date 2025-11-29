jQuery(document).ready(function ($) {


    $(document).on('click', '.ct_user_all_users_ids', function () {

        let checked_or_not = $(this).prop('checked');
        $('.ct_user_ids').each(function () {
            $(this).prop('checked', checked_or_not);
        });

    });


    $(document).on('click', '.ct-caadu-view-users', function () {
        var userList = $(this).next('.ct-caadu-user-list');
        userList.toggle();
    });

    $('.ct-caadu-live-search').select2({
        multiple: true,
    });

    jQuery(document).on('click', '.ct-caadu-show-popup', function (e) {
        e.preventDefault();
        let show_class = $(this).data('show_class');
        $('.' + show_class).hide();

        $('.' + show_class).show();

    });
    jQuery(document).on('click', '.ct-caadu-close-send-bulk-email-popup-main-container', function () {
        $(this).closest('div').hide();
    });


    jQuery(document).on('click', '.ct-caadu-edit-or-view-capabilities', function (e) {
        e.preventDefault();
        let popup_class = $(this).data('popup_class');

        $('.ct-caadu-user-and-customer-detail').each(function () {
            $(this).hide();
        });

        $(this).closest('tr').find('.' + popup_class).show();


    });


    capabilitites_type();
    jQuery(document).on('change', '.capabilitites_type', function () {
        capabilitites_type();
    });

    function capabilitites_type() {

        if ('select_custom_capabilities' == $('.capabilitites_type').val()) {

            $('.af-select-custom-capabilities').show();
            $('.af-select-user-role-for-capabilities').hide();

        } else {

            $('.af-select-custom-capabilities').hide();
            $('.af-select-user-role-for-capabilities').show();

        }

    }
    jQuery(document).on('submit', 'form.ct-caadu-create-new-user-role', function (e) {

        e.preventDefault();

        var current_btn = $(this);
        jQuery.ajax({
            url: php_var.ajaxurl,
            type: 'POST',
            data: {
                action: 'ct_caadu_create_new_user_role',
                form_data: $(this).serialize(),
                nonce: php_var.nonce,
                selected_capabilites: $('.selected_capabilites').val(),
            },
            success: function (response) {

                if (response && response.data && response.data['success_message']) {
                    $('.form.ct-caadu-create-new-user-role').find('.message').remove();
                    $('.af-cm-create-new-user-role').after(response.data['success_message']);

                    setTimeout(function () {

                        window.location.reload();

                    }, 2000);

                }
                if (response && response.data && response.data['error']) {
                    $('.form.ct-caadu-create-new-user-role').find('.message').remove();
                    $('.af-cm-create-new-user-role').after(response.data['error']);

                }


            }
        });

    });

    jQuery(document).on('submit', 'form.ct-caadu-create-edit-user-role-capabilities-form', function (e) {

        e.preventDefault();

        var current_btn = $(this);

        jQuery.ajax({
            url: php_var.ajaxurl,
            type: 'POST',
            data: {
                action: 'ct_caadu_update_capabilities',
                form_data: $(this).serialize(),
                nonce: php_var.nonce,
            },
            success: function (response) {

                if (response.data && response.data['success_message']) {

                    current_btn.before(response.data['success_message']);

                    setTimeout(function () {

                        window.location.reload();

                    }, 2000);

                }

            }
        });

    });

    $(document).on('click', '.ct-caadu-view-capabilities', function () {
        var capabilitiesList = $(this).next('.ct-caadu-capabilities-list');
        capabilitiesList.toggle();
    });

    $(document).on('submit', 'form.ct-all-user-assign-form', function (e) {
        e.preventDefault();
        let ct_user_ids = [];
        $('.ct_user_ids:checked').each(function () {

            ct_user_ids.push($(this).val());

        });


        if (!ct_user_ids.length) {
            return;
        }
        jQuery.ajax({
            url: php_var.ajaxurl,
            type: 'POST',
            data: {
                action: 'ct_assign_new_user_role',
                nonce: php_var.nonce,
                form: $(this).serialize(),
                ct_user_ids: ct_user_ids,
            },
            success: function (response) {

                if (response['success']) {
                    window.location.reload(true);

                }
            },
        });
    });
});