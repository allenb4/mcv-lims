(function($){

    "use strict";
     
    //active
    $('#accounting').addClass('menu-open');
    $('#accounting_link').addClass('active');
    $('#expenses').addClass('active');

    //expenses datatable
    table=$('#expenses_table').DataTable( {
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
        "ajax": {
          url: url("admin/get_expenses")
        },
        // orderCellsTop: true,
        fixedHeader: true,
        "columns": [
           {data:"bulk_checkbox",searchable:false,sortable:false,orderable:false},
           {data:"id",sortable:true,orderable:true},
           {data:"category.name",sortable:false,orderable:false},
           {data:"date",sortable:true,orderable:true},
           {data:"amount",sortable:true,orderable:true},//amount
           {data:"payment_method.name",sortable:false,orderable:false},
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

    //select2
    $('#category').select2({
        width:'100%',
        placeholder:trans("Select expense category")
    });

    //datepicker
    $('#date').datepicker({
        dateFormat: 'yy-mm-dd' 
    });

    //expense id
    var expense_category_id=$('#expense_category_id').val();
    $('#category').val(expense_category_id).trigger('change');

   
    //save category
    $('#create_category_form').on('submit',function(e){
        e.preventDefault();
        //category name
        var category=$(this).find('input[name="name"]').val();

        if(category.length)
        {
            //ajax
            $.ajax({
                type:'post',
                url:ajax_url('add_expense_category'),
                data:{name:category},
                beforeSend:function(){
                    $('.preloader').show();
                    $('.loader').show();
                },
                success:function(data)
                {
                    $('#category').append(`
                        <option value="`+data.id+`" selected>`+data.name+`</option>
                    `).trigger('change');

                    toastr.success(trans('Category added successfully'),trans('Success'));

                    $('#category_modal').modal('hide');

                },
                complete:function(){
                    $('#category_name').val('');
                    $('.preloader').hide();
                    $('.loader').hide();
                },
                error:function()
                {
                    toastr.error(trans('Something went wrong'),trans('Failed'));
                }
            });
        }
    });

    $('#notes').summernote({
        height:'300px'
    });

    //delete expense
    $(document).on('click','.delete_expense',function(e){
        e.preventDefault();
        var el=$(this);
        swal({
            title: trans("Are you sure to delete expense ?"),
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

    //select2 doctor
    $('#doctor').select2({
        allowClear:true,
        width:"100%",
        placeholder:trans("Doctor"),
        ajax: {
           beforeSend:function()
           {
              $('.preloader').show();
              $('.loader').show();
           },
           url: ajax_url('get_doctors'),
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
    $('#payment_method').select2({
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


})(jQuery);

