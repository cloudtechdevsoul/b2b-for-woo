
jQuery.noConflict();

jQuery(document).ready(function($) {
    $('.ct-quote-table').DataTable();
    for (let index = 1; index <= 100 ; index++) {
        if( index * 1000 % 10000 == 0 ){
         
        let option  = '<option value="'+index * 1000 +'">'+index * 1000 +'</option>';
        jQuery('.ct-quote-table-div select[name=DataTables_Table_0_length]').append(option);

        }
    }
});