(function($){

    "use strict";
 
    //active
    $('#products').addClass('active');
    $('#inventory_link').addClass('active');
    $('#inventory').addClass('menu-open');

    //products datatable
    table=$('#products_table').DataTable( {
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
             url: url("admin/inventory/products")
         },
         // orderCellsTop: true,
         fixedHeader: true,
         "columns": [
            {data:"bulk_checkbox",searchable:false,sortable:false,orderable:false},
            {data:"id",sortable:true,orderable:true},
            {data:"name",sortable:true,orderable:true},
            {data:"sku",sortable:true,orderable:true},
            {data:"initial_quantity",sortable:false,orderable:false},
            {data:"purchase_quantity",sortable:false,orderable:false},
            {data:"in_quantity",sortable:false,orderable:false},
            {data:"out_quantity",sortable:false,orderable:false},
            {data:"consumption_quantity",sortable:false,orderable:false},
            {data:"stock_quantity",sortable:false,orderable:false},
            {data:"action",searchable:false,orderable:false,sortable:false}//action
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

 
    //delete patient
    $(document).on('click','.delete_product',function(e){
        e.preventDefault();
        var el=$(this);
        swal({
            title: trans("Are you sure to delete product ?"),
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: trans("Delete"),
            cancelButtonText: trans("Cancel"),
            closeOnConfirm: false
        },
        function(){
            $(el).parent().submit();
        });
    });

     
 
 })(jQuery);
 
 