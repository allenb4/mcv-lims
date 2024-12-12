(function($){

    "use strict";
    
    //active
    $('#prices').addClass('menu-open');
    $('#prices_link').addClass('active');
    $('#packages_prices').addClass('active');

    //change hidden packages
    $(document).on('change','.price',function(){
        var package_id=$(this).attr('package_id');
        var price=$(this).val();

        $('#package_'+package_id).val(price);
    });

    //datatable
    var packages_table=$('#packages_prices_table').DataTable( {
        "lengthMenu": [[10, 25, 50,100,500,1000, -1], [10, 25, 50,100,500,1000 ,"All"]],
         dom: "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
         "<'row'<'col-sm-12'tr>>" +
         "<'row'<'col-sm-4'i><'col-sm-8'p>>",
         buttons: [
             {
                 extend:    'copyHtml5',
                 text:      '<i class="fas fa-copy"></i> '+trans("Copy"),
                 titleAttr: 'Copy'
             },
             {
                 extend:    'excelHtml5',
                 text:      '<i class="fas fa-file-excel"></i> '+trans("Excel"),
                 titleAttr: 'Excel'
             },
             {
                 extend:    'csvHtml5',
                 text:      '<i class="fas fa-file-csv"></i> '+trans("CVS"),
                 titleAttr: 'CSV'
             },
             {
                 extend:    'pdfHtml5',
                 text:      '<i class="fas fa-file-pdf"></i> '+trans("PDF"),
                 titleAttr: 'PDF'
             },
             {
               extend:    'colvis',
               text:      '<i class="fas fa-eye"></i>',
               titleAttr: 'PDF'
             }
         ],
         "processing": true,
         "serverSide": true,
         fixedHeader: true,
         "ajax": {
             url: url("admin/prices/packages")
         },
         // orderCellsTop: true,
         fixedHeader: true,
         "columns": [
            {data:"id",sortable:true,orderable:true},
            {data:"package.name",sortable:false,orderable:false},
            {data:"price",sortable:false,orderable:false},
         ],
         "language": {
             "sEmptyTable":     trans("No data available in table"),
             "sInfo":           trans("Showing")+" _START_ "+trans("to")+" _END_ "+trans("of")+" _TOTAL_ "+trans("records"),
             "sInfoEmpty":      trans("Showing")+" 0 "+trans("to")+" 0 "+trans("of")+" 0 "+trans("records"),
             "sInfoFiltered":   "("+trans("filtered")+" "+trans("from")+" _MAX_ "+trans("total")+" "+trans("records")+")",
             "sInfoPostFix":    "",
             "sInfoThousands":  ",",
             "sLengthMenu":     trans("Show")+" _MENU_ "+trans("records"),
             "sLoadingRecords": trans("Loading..."),
             "sProcessing":     trans("Processing..."),
             "sSearch":         trans("Search")+":",
             "sZeroRecords":    trans("No matching records found"),
             "oPaginate": {
                 "sFirst":    trans("First"),
                 "sLast":     trans("Last"),
                 "sNext":     trans("Next"),
                 "sPrevious": trans("Previous")
             },
         }
     });

    $(document).on('change','.package_price',function(){
       var price=$(this).val(); 
       if(price<0)
       {
           $(this).val(0);
           price=0;
       }
       var package_id=$(this).attr('package_id');
       if($('#hidden_prices').find('#package_price_'+package_id).length>0)
       {
            $('#hidden_prices').find('#package_price_'+package_id).val(price);
       }
       else{
            $('#hidden_prices').append(`
                <input type="hidden" class="hidden_price" package_id="`+package_id+`" name="packages[`+package_id+`]" value="`+price+`" id="package_price_`+package_id+`">
            `);
       }
    });

    packages_table.on( 'draw', function () {
        $('#hidden_prices .hidden_price').each(function(){
            var package_id=$(this).attr('package_id');
            var price=$(this).val();

            $('#packages_prices_table #package_'+package_id).val(price);
        });
    });


})(jQuery);