var count_comments=$('#count_comments').val();
var consumption_count=$('#consumption_count').val();

(function($){

    "use strict";
    
    //active
    $('#cultures').addClass('active');

    //cultures datatable
    table=$('#cultures_table').DataTable( {
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
      "bSort" : false,
      "ajax": {
        url: url("admin/get_cultures")
      },
      // orderCellsTop: true,
      fixedHeader: true,
      "columns": [
        {data:"bulk_checkbox",searchable:false,sortable:false,orderable:false},
        {data:"id",sortable:true,orderable:true},
        {data:"category.name",sortable:false,orderable:false},
        {data:"name",sortable:true,orderable:true},
        {data:"sample_type",sortable:false,orderable:false},
        {data:"price",sortable:true,orderable:true},
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

    //delete culture
    $(document).on('click','.delete_culture',function(e){
        e.preventDefault();
        var el=$(this);
        swal({
          title: trans("Are you sure to delete culture ?"),
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

    //get category select2 intialize
    $('#category').select2({
      width:"100%",
      placeholder:trans("Category"),
      ajax: {
        beforeSend:function()
        {
            $('.preloader').show();
            $('.loader').show();
        },
        url: ajax_url('get_categories'),
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

    //add comment
    $(document).on('click','.add_comment',function(){
      count_comments++;
      $('#comments_table tbody').append(`
      <tr>
          <td>
            <div class="form-group">
                <textarea name="comments[`+count_comments+`]" class="form-control" id="" cols="30" rows="3" required></textarea>
            </div>
          </td>
          <td>
              <button type="button" class="btn btn-danger btn-sm delete_comment">
                  <i class="fa fa-trash"></i>
              </button>
          </td>
      </tr>
      `)
    });

    //delete comment
    $(document).on('click','.delete_comment',function(){
      $(this).closest('tr').remove();
    });


    //consumptions
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

  //add consumption
  $(document).on('click','.add_consumption',function(){
    consumption_count++;
    $(this).parent().parent().find('.test_consumptions').append(`
      <tr class="consumption_row">
        <td>
          <div class="form-group">
            <select class="form-control" id="consumption_product_`+consumption_count+`" name="consumptions[`+consumption_count+`][product_id]" required>
            </select>
          </div>
        </td>
        <td>
          <div class="form-group">
            <input type="number" class="form-control" name="consumptions[`+consumption_count+`][quantity]" placeholder="`+trans("Quantity")+`" value="0" required>
          </div>
        </td>
        <td>
          <button type="button" class="btn btn-sm btn-danger delete_consumption">
            <i class="fa fa-trash"></i>
          </button>
        </td> 
      </tr>
    `);

    $('#consumption_product_'+consumption_count).select2({
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

  //delete consumption
  $(document).on('click','.delete_consumption',function(){
    $(this).closest('.consumption_row').remove();
  });

})(jQuery);

