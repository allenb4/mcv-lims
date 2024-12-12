var products_count=parseInt($('#products_count').val());
var payments_count=parseInt($('#payments_count').val());
var current_date=$('#system_date').val();//system date
//datepicker
var date=new Date();
var current_year=date.getFullYear();
(function($){

    "use strict";
 
    //active
    $('#purchases').addClass('active');
    $('#inventory_link').addClass('active');
    $('#inventory').addClass('menu-open');

    //purchases datatable
    table=$('#purchases_table').DataTable( {
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
             url: url("admin/inventory/purchases")
         },
         // orderCellsTop: true,
         fixedHeader: true,
         "columns": [
            {data:"bulk_checkbox",searchable:false,sortable:false,orderable:false},
            {data:"id",sortable:true,orderable:true},
            {data:"date",sortable:true,orderable:true},
            {data:"branch.name",sortable:false,orderable:false},
            {data:"supplier.name",sortable:false,orderable:false},
            {data:"total",sortable:false,orderable:false},
            {data:"paid",sortable:false,orderable:false},
            {data:"due",sortable:false,orderable:false},
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
    $(document).on('click','.delete_purchase',function(e){
        e.preventDefault();
        var el=$(this);
        swal({
            title: trans("Are you sure to delete purchase ?"),
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
                    <input type="number" name="products[`+products_count+`][price]" class="form-control price"  id="product_price_`+products_count+`"  value="0" min="0" required>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" name="products[`+products_count+`][quantity]" class="form-control quantity"  id="product_quantity_`+products_count+`"  value="0" min="0" required>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" name="products[`+products_count+`][total_price]" class="form-control total_price"  id="product_total_price_`+products_count+`"  value="0" min="0" readonly required>
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


    //add payment
    $(document).on('click','.add_payment',function(){
        payments_count++;
        $('#payments_table tbody').append(`
        <tr>
            <td>
                <input type="text" class="form-control new_datepicker" name="payments[`+payments_count+`][date]" placeholder="`+trans('Date')+`" value="`+current_date+`" required>
            </td>
            <td>
                <input type="number" class="form-control amount" name="payments[`+payments_count+`][amount]" placeholder="`+trans('Amount')+`" value="0" required>
            </td>
            <td> 
                <div class="form-group">
                    <select name="payments[`+payments_count+`][payment_method_id]" class="form-control payment_method_id" required>
                    <option value="" disabled selected>`+trans('Select payment method')+`</option>
                    </select>
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-danger delete_payment">
                    <i class="fa fa-times"></i>
                </button>
            </td>
        </tr>`);

        $('.new_datepicker').datepicker({
            dateFormat:"yy-mm-dd",
            changeYear: true,
            changeMonth: true,
            yearRange:"1900:"+current_year
        });

        //select2 payment methods 
        $('.payment_method_id').select2({
        width:"100%",
        placeholder:trans("Select payment method"),
        ajax: {
            beforeSend:function()
            {
                $('.preloader').show();
                $('.loader').show();
            },
            url: ajax_url('get_payment_methods'),
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


 //delete payment
 $(document).on('click','.delete_payment',function(){
    $(this).closest('tr').remove();
   
    calculate_purchase();
 });

  //delete payment
  $(document).on('click','.delete_product',function(){
    $(this).closest('tr').remove();
   
    calculate_purchase();
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

 //select2 supplier
 $('#supplier_id').select2({
    allowClear:true,
    width:"100%",
    placeholder:trans("Select supplier"),
    ajax: {
       beforeSend:function()
       {
          $('.preloader').show();
          $('.loader').show();
       },
       url: ajax_url('get_suppliers_select2'),
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

 //select2 payment methods 
 $('.payment_method_id').select2({
    width:"100%",
    placeholder:trans("Select payment method"),
    ajax: {
       beforeSend:function()
       {
          $('.preloader').show();
          $('.loader').show();
       },
       url: ajax_url('get_payment_methods'),
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

//validate order form has products
// $(document).on('submit','#purchases_form',function(e){
//     var count_submited_products=$('.product_id').length;
//     if(!count_submited_products)
//     {
//         toastr.error(trans('Please add at least one product'),trans('Failed'));
//         return false;
//     }

//     var due=$('#due').val();
//     if(due==''||parseFloat(due)<0)
//     {
//         toastr.error(trans('Sorry , due amount can\'t be less than 0'),trans('Failed'));
//         return false;
//     }
// });

//change amount
$(document).on('change','.amount',function(){
    calculate_purchase();
});

//change quantity
$(document).on('change','.quantity',function(){
    calculate_purchase();
});
 
//change price
$(document).on('change','.price',function(){
    calculate_purchase();
});

//change tax
$(document).on('change','#tax',function(){
    calculate_purchase();
});

calculate_purchase();

})(jQuery);

function calculate_purchase()
{
    var subtotal=0;
    var paid=0;
    var total=0;
    var due=0;
    var tax=$('#tax').val();

    $('#products_table tbody tr').each(function(){
        var price=$(this).find('.price').val();
        var quantity=$(this).find('.quantity').val();

        if(!isNaN(price)&&!isNaN(quantity)&&price!==''&&quantity!=='')
        {
            var price=parseFloat(price);
            var quantity=parseFloat(quantity);
            var product_price=price*quantity;
            $(this).find('.total_price').val(product_price);
            subtotal+=product_price;
        }
    });

    $('#payments_table tbody .amount').each(function(){
        var amount=$(this).val();

        if(!isNaN(amount)&&amount!=='')
        {
            var amount=parseFloat(amount);
            paid+=amount;
        }
    });

    if(!isNaN(tax)&&tax!=='')
    {
        tax=parseFloat(tax);
        total=subtotal+tax;
    }

    due=total-paid;

    $('#subtotal').val(subtotal);
    $('#total').val(total);
    $('#paid').val(paid);
    $('#due').val(due);
}
 
 