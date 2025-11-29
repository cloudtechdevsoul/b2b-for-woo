jQuery(document).ready(function ($) {

    jQuery('.ct-psbs-order-infor-table').DataTable();

    for (let index = 1; index <= 100; index++) {
        if (index * 1000 % 10000 == 0) {

            let option = '<option value="' + index * 1000 + '">' + index * 1000 + '</option>';
            jQuery('.ct-psbs-prouct-table select[name=DataTables_Table_0_length]').append(option);


        }
    }


    jQuery('.ct-psbs-prouct-table .dataTables_length').css('width', '40%');
    jQuery('.ct-psbs-prouct-table select[name=DataTables_Table_0_length]').css('width', '100%');

    $(document).on('click', 'input[name=ct_export_sales_csv]', function (e) {
        e.preventDefault();
        var csvContent = [];

        // Iterate through rows and cells to build CSV content
        $(".ct-psbs-order-sales").find("tr").each(function () {
            var row = [];
            $(this).find("td").each(function () {
                row.push($(this).text());

            });
            csvContent.push(row);
        });

        $(".ct-psbs-total-of-selected-table").find("tr").each(function () {
            var row = [];
            $(this).find("th , td").each(function () {
                row.push($(this).text());
            });
            csvContent.push(row);
        });


        let file_name = $(".ct-psbs-order-sales").data('file_name') ? $(".ct-psbs-order-sales").data('file_name') : 'exported_data.csv';

        // Convert CSV content to a blob


        var csvBlob = new Blob([csvContent.join("\n")], { type: "text/csv;charset=utf-8;" });

        // Trigger a download link
        var link = $("<a></a>")
            .attr("href", window.URL.createObjectURL(csvBlob))
            .attr("download", file_name)
            .appendTo("body")[0];
        link.click();
    });


    $(document).on('change , click', 'input, select, button, select[name=DataTables_Table_0_length]', sale_detail_table);
    sale_detail_table();
    function sale_detail_table() {

        if ($('.ct-psbs-order-complete-detail').length) {

            let subtotal = 0;
            let tax_total = 0;
            let refund_total = 0;
            let coupon_total = 0;
            let shipping_total = 0;
            let total = 0;
            let currency_symbol = $('.woocommerce-Price-currencySymbol').first().text();
            $('.ct-psbs-order-complete-detail').each(function () {
                subtotal += parseFloat($(this).data('subtotal'));
                tax_total += parseFloat($(this).data('tax_total'));
                shipping_total += parseFloat($(this).data('shipping_total'));
                total += parseFloat($(this).data('total'));
                refund_total += parseFloat($(this).data('refunded_amount'));
                coupon_total += parseFloat($(this).data('coupon_amount'));


            });

            $('.ct-psbs-subtotal-td').text(currency_symbol + subtotal);
            $('.ct-psbs-total-tax-td').text(currency_symbol + tax_total);
            $('.ct-psbs-shipping-total-td').text(currency_symbol + shipping_total);
            $('.ct-psbs-refunded-total-td').text(currency_symbol + refund_total);
            $('.ct-psbs-coupon-total-td').text(currency_symbol + coupon_total);
            $('.ct-psbs-total-td').text(currency_symbol + total);
        }
    }

});