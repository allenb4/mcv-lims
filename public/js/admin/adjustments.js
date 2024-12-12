var products_count=parseInt($('#products_count').val());
var current_date=$('#system_date').val();//system date
//datepicker
var date=new Date();
var current_year=date.getFullYear();
(function($){

    "use strict";
 
    //active
    $('#adjustments').addClass('active');
    $('#inventory_link').addClass('active');
    $('#inventory').addClass('menu-open');

    //adjustments datatable
    table=$('#adjustments_table').DataTable( {
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
             url: url("admin/inventory/adjustments")
         },
         fixedHeader: true,
         "columns": [
            {data:"bulk_checkbox",searchable:false,sortable:false,orderable:false},
            {data:"id",sortable:true,orderable:true},
            {data:'date',sortable:true,orderable:true},
            {data:"branch.name",sortable:false,orderable:false},
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
    $(document).on('click','.delete_adjustment',function(e){
        e.preventDefault();
        var el=$(this);
        swal({
            title: trans("Are you sure to delete adjustment ?"),
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


    //add product
    $(document).on('click','.add_product',function(){
        products_count++;
        $('#products_table tbody').append(`
        <tr>
            <td>
                <div class="form-group">
                    <select name="products[`+products_count+`][id]" id="product_name_`+products_count+`" class="form-control product_id" required>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" name="products[`+products_count+`][quantity]" class="form-control quantity"  id="product_quantity_`+products_count+`"  value="0" min="0" required>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <select class="form-control" name="products[`+products_count+`][type]" id="product_type_`+products_count+`" required>
                        <option value="" disabled selected>`+trans('Type')+`</option>
                        <option value="1">`+trans('In')+`</option>
                        <option value="2">`+trans('Out')+`</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <textarea name="products[`+products_count+`][note]" class="form-control"  id="product_note_`+products_count+`" rows="2" placeholder="`+trans('note')+`"></textarea>
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-danger delete_product">
                    <i class="fa fa-times"></i>
                </button>
            </td>
        </tr>
        `);

        //select2 product
        $('.product_id').select2({
            width:"100%",
            placeholder:trans("Select product"),
            ajax: {
            beforeSend:function()
            {
                $('.preloader').show();
                $('.loader').show();
            },
            url: ajax_url('get_products_select2'),
            processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                complete:function()
                {
                    $('.preloader').hide();
                    $('.loader').hide();
                }
            }
        });
    });

    //delete product
    $(document).on('click','.delete_product',function(){
        $(this).closest('tr').remove();
    
        calculate_order();
    });

    //select2 branch
    $('#branch_id').select2({
        width:"100%",
        placeholder:trans("Select branch"),
        ajax: {
        beforeSend:function()
        {
            $('.preloader').show();
            $('.loader').show();
        },
        url: ajax_url('get_branches_select2'),
        processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            complete:function()
            {
                $('.preloader').hide();
                $('.loader').hide();
            }
        }
    });


    //select2 product
    $('.product_id').select2({
        width:"100%",
        placeholder:trans("Select product"),
        ajax: {
        beforeSend:function()
        {
            $('.preloader').show();
            $('.loader').show();
        },
        url: ajax_url('get_products_select2'),
        processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            complete:function()
            {
                $('.preloader').hide();
                $('.loader').hide();
            }
        }
    });

    //validate form has products
    // $(document).on('submit','#adjustments_form',function(e){
    //     var count_submited_products=$('.product_id').length;
    //     if(!count_submited_products)
    //     {
    //         toastr.error(trans('Please add at least one product'),trans('Failed'));
    //         return false;
    //     }
    // });

})(jQuery);

 