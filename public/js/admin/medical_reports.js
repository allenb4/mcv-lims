    var count=0;
    var antibiotic_count=$('#antibiotic_count').val();

    (function($){
        
        "use strict";

        //active
        $('#medical_reports').addClass('active');

        //Medical reports datatables
        table=$('#medical_reports_table').DataTable( {
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
            "order": [[ 1, "desc" ]],
            "ajax": {
                url: url("admin/medical_reports"),
                data:function(data)
                {
                    data.filter_status=$('#filter_status').val();
                    data.filter_barcode=$('#filter_barcode').val();
                    data.filter_date=$('#filter_date').val();
                    data.filter_created_by=$('#filter_created_by').val();
                    data.filter_signed_by=$('#filter_signed_by').val();
                    data.filter_contract=$('#filter_contract').val();
                }
            },
            fixedHeader: true,
            "columns": [
                {data:"bulk_checkbox",orderable:false,sortable:false},
                {data:"id",orderable:true,sortable:true},
                {data:"created_by_user.name",orderable:false,sortable:false},
                {data:"contract.title",orderable:false,sortable:false},
                {data:"barcode",orderable:false,sortable:false},
                {data:"patient.code",orderable:false,sortable:false},
                {data:"patient.name",orderable:false,sortable:false},
                {data:"patient.gender",orderable:false,sortable:false},
                {data:"patient.age",searchable:false,orderable:false,sortable:false},
                {data:"patient.phone",orderable:false,sortable:false},
                {data:"tests",searchable:false,orderable:false,sortable:false},
                {data:"created_at",searchable:false,orderable:true,sortable:true},
                {data:"done",searchable:false,sortable:false,orderable:false},
                {data:"signed",searchable:false,sortable:false,orderable:false},
                {data:"signed_by_user.name",orderable:false,sortable:false},
                {data:"action",searchable:false,sortable:false,orderable:false},
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

        $('#filter_status').on('change',function(){
            table.draw();
        });

        $('#filter_barcode').on('input',function(){
            table.draw();
        });

        // filter date
        var ranges={};
        ranges[trans('Today')]=[moment(), moment()];
        ranges[trans('Yesterday')]=[moment().subtract('days', 1), moment().subtract('days', 1)];
        ranges[trans('Last 7 Days')]=[moment().subtract('days', 6), moment()];
        ranges[trans('Last 30 Days')]=[moment().subtract('days', 29), moment()];
        ranges[trans('This Month')]=[moment().startOf('month'), moment().endOf('month')];
        ranges[trans('Last Month')]=[moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')];
        ranges[trans('This Year')]=[moment().startOf('year'), moment().endOf('year')];
        ranges[trans('Last Year')]=[moment().subtract(1,'year').startOf('year'), moment().subtract(1,'year').endOf('year')];

        $('#filter_date').daterangepicker({
            locale:{
                format: 'YYYY/MM/DD',
                "applyLabel": trans("Save"),
                "cancelLabel": trans("Cancel"),
            },
            ranges,
            startDate: moment(),
            endDate: moment()
            },
            function(start, end) {
                $('#dateRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            });

            $('#filter_date').on( 'cancel.daterangepicker', function(){
            $(this).val('');
            });

        $('#filter_date').on( 'cancel.daterangepicker', function(){
            $(this).val('');
            table.draw();
        });
        
        $('#filter_date').on('apply.daterangepicker',function(){
            table.draw();
        });

        $('#filter_date').val('');

        $('#filter_created_by').on('change',function(){
            table.draw();
        });

        $('#filter_signed_by').on('change',function(){
            table.draw();
        });

        //get users select2 intialize
        $('.user_id').select2({
            allowClear:true,
            multiple:true,
            width:"100%",
            placeholder:trans("User"),
            ajax: {
            beforeSend:function()
            {
                $('.preloader').show();
                $('.loader').show();
            },
            url: ajax_url('get_users'),
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

        $('#filter_contract').select2({
            multiple:true,
            width:"100%",
            placeholder:trans("Contract"),
            ajax: {
               beforeSend:function()
               {
                  $('.preloader').show();
                  $('.loader').show();
               },
               url: ajax_url('get_contracts'),
               processResults: function (data) {
                     return {
                           results: $.map(data, function (item) {
                              return {
                                 text: item.title,
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
      
         //filter datatable by contract
         $('#filter_contract').on('change',function(){
            table.draw();
         });

        var patient_code=$('#patient_code').val();

        //QRCode
        $(".patient_qrcode").ClassyQR({
            create: true,
            size: '180',
            type: 'url',
            url:url('login/'+patient_code)
        });  

      
        //intialize antibiotics select2
        $('.antibiotic').select2({
            placeholder:trans('Select antibiotic'),
            width:'100%'
        });

       $('.sensitivity').select2({
            placeholder:trans('Select sensitivity'),
            width:'100%'
        });

        $('.select_result').select2({
            width:"100%",
            tags: true
        });
  
      //delete row
      $(document).on('click','.delete_row',function(){
        $(this).closest('tr').remove();
      });
      
      //validate printing
      $('#print_form').on('submit',function(){
        var count_tests=$('input[type=checkbox]:checked').length;
        if(count_tests==0)
        {
        toastr.error(trans('Please select at least one test'),trans('Failed'));

        return false;
        }
      });

      //validate culture result
      $('.culture_form').on('submit',function(){
       var count_antibiotics=$(this).find('.antibiotics tr').length;
      
       if(count_antibiotics==0)
       {
           toastr.error(trans('Please select at least one antibiotic'),trans('Failed'));

           return false;
       }

      });

      $('input[type=checkbox]').prop('checked',true);

      //select all
      $('.select_all').on('click',function(){
        $('input[type=checkbox]').prop('checked',true);
      });

      $('.deselect_all').on('click',function(){
        $('input[type=checkbox]').prop('checked',false);
      });

    //print barcode
    $(document).on('click','.print_barcode',function(){
        var group_id=$(this).attr('group_id');
        $('#print_barcode_form').attr('action',url('admin/groups/print_barcode/'+group_id));
    });

    $(document).on('submit','#print_barcode_form',function(){
        $('#print_barcode_modal').modal('toggle');
    });

    //active tabs
    $('.nav-tabs').each(function(){
        $(this).find('.nav-link').first().addClass('active');
    });

    $('.tab-content').each(function(){
        $(this).find('.tab-pane').first().addClass('active');
    });

    $(document).on('keyup','.test_result',function(){
        var result=$(this).val();
        var normal_from=parseFloat($(this).attr('normal_from'));
        var normal_to=parseFloat($(this).attr('normal_to'));
        var critical_high_from=parseFloat($(this).attr('critical_high_from'));
        var critical_low_from=parseFloat($(this).attr('critical_low_from'));
        if(result>=normal_from&&result<=normal_to)
        {
            $(this).closest('tr').find('.status').val('Normal').trigger('change');
        }
        else if(result>normal_to&&result<critical_high_from)
        {
            $(this).closest('tr').find('.status').val('High').trigger('change');
        }
        else if(result<normal_from&&result>critical_low_from)
        {
            $(this).closest('tr').find('.status').val('Low').trigger('change');
        }
        else if(result>=critical_high_from)
        {
            $(this).closest('tr').find('.status').val('Critical high').trigger('change');
        }
        else if(result<=critical_low_from)
        {
            $(this).closest('tr').find('.status').val('Critical low').trigger('change');
        }
    });

    //select comment
    $('.select_comment').select2({
        width:"100%"
    });
    
    $('.select_comment').on('select2:select',function(){
        var comment = $(this).select2('data');
        $(this).prev('.comment').val(comment[0]['text']);
    });

    //delete medical report
    $(document).on('click','.delete_medical_report',function(e){
        e.preventDefault();
        var el=$(this);
        swal({
            title: trans("Are you sure to delete medical report ?"),
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


     //analyses functions
     function check_analyses_all(el)
     {
         var checked=$(el).prop('checked');

         if(checked)
         {
             $('.analyses_select').each(function(){
               $(this).prop('checked',true);
             });
         }
         else{
           $('.analyses_select').each(function(){
               $(this).prop('checked',false);
             });
         }

     }
     
     //print all analyses
     function print_analyses_all()
     {
       $('.analyses_select').prop('checked',true);

       $('#check_analyses_all').prop('checked',true);

       var html='';

       $('.analyses_select:checked').parent().parent().find('.card-body').each(function(){
          
          html+=$(this).html();
          
      });

       print_analyses(html);
     }

     //print selected analyses
     function print_analyses_selected()
     {
       var html='';

       $('.analyses_select:checked').parent().parent().find('.card-body').each(function(){
          
          html+=$(this).html();
       });

       print_analyses(html);

     }

    //make all cultures selected
    function check_cultures_all(el)
    {
        var checked=$(el).prop('checked');

        if(checked)
        {
            $('.cultures_select').each(function(){
                $(this).prop('checked',true);
            });
        }
        else{
            $('.cultures_select').each(function(){
                $(this).prop('checked',false);
            });
        }
    }

    //print all cultures
    function print_cultures_all()
    {
        var print_header=`<table class="printable_content" width="100%;">`+$('.page-header').html()+`<tbody><tr><td width="3%"></td><td><div class="content">`;
        var print_footer=`</div></td><td width="3%"></td></tr></tbody>`+$('.page-footer').html()+'</table>';  
        
        $('.cultures_select').prop('checked',true);

        $('#check_cultures_all').prop('checked',true);

        var html='';

        $('.cultures_select:checked').parent().parent().find('.card-body').each(function(){

            html+=print_header;
            
            html+=$(this).html();

            html+=print_footer;
            
        });

        print_cultures(html);
    }
    
    //print selected cultures
    function print_cultures_selected()
    {
        var print_header=`<table class="printable_content" width="100%;">`+$('.page-header').html()+`<tbody><tr><td width="3%"></td><td><div class="content">`;
        var print_footer=`</div></td><td width="3%"></td></tr></tbody>`+$('.page-footer').html()+'</table>';  
        
        var html='';

        $('.cultures_select:checked').parent().parent().find('.card-body').each(function(){
            html+=print_header;
            
            html+=$(this).html();

            html+=print_footer;

        });

        print_cultures(html);

    }


    //print cultures that have result
    function print_cultures_has_result()
    {
        var print_header=`<table class="printable_content" width="100%;">`+$('.page-header').html()+`<tbody><tr><td width="3%"></td><td><div class="content">`;
        var print_footer=`</div></td><td width="3%"></td></tr></tbody>`+$('.page-footer').html()+'</table>';  
        
        var html='';

        $('.cultures_select[value="has_entered"]').parent().parent().find('.card-body').each(function(){
            html+=print_header;

            html+=$(this).html();

            html+=print_footer;

        });

        print_cultures(html);
    }

    //add antibiotic
    function add_antibiotic(antibiotics,el)
    {
        var antibiotics=JSON.parse(antibiotics);

        var antibiotics_options=`
            <option value="" disabled selected>`+trans("Select Antibiotic")+`</option>
        `;

        antibiotics.forEach(function(antibiotic){
            antibiotics_options+=`
                <option value="`+antibiotic.id+`">`+antibiotic.name+`</option>
            `;
        });

        antibiotic_count++;

        $(el).closest('table').find('tbody').append(`
            <tr>
                <td>
                   <div class="form-group">
                    <select class="form-control antibiotic" name="antibiotic[`+antibiotic_count+`][antibiotic]" required>
                    `+antibiotics_options+`
                    </select>
                   </div>
                </td>
                <td>
                    <div class="form-group">
                        <select class="form-control sensitivity" name="antibiotic[`+antibiotic_count+`][sensitivity]" required>
                            <option value="" disabled selected>`+trans("Select Sensitivity")+`</option>
                            <option value="High">`+trans("High")+`</option>
                            <option value="Moderate">`+trans("Moderate")+`</option>
                            <option value="Resident">`+trans("Resident")+`</option>
                        </select>
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete_row">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
  
        //setup select2
        $('.antibiotic').select2({
            placeholder:trans('Select antibiotic'),
            width:'100%'
        });

        $('.sensitivity').select2({
            placeholder:trans('Select sensitivity'),
            width:'100%'
        });

    }

   