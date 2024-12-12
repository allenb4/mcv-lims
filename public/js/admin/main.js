var table='';

(function($){

    "use strict";

    $.widget.bridge('uibutton', $.ui.button);
    $.fn.dataTable.ext.errMode = 'none';
    
    //datepicker
    var date=new Date();
    var current_year=date.getFullYear();
    $('.datepicker').datepicker({
      dateFormat:"yy-mm-dd",
      changeYear: true,
      changeMonth: true,
      yearRange:"1900:"+current_year
    });

    //flatpickr
    $('.flatpickr').flatpickr({
      enableTime: true,
      dateFormat: "Y-m-d H:i",
    });

    //Date range picker
    var ranges={};
    ranges[trans('Today')]=[moment(), moment()];
    ranges[trans('Yesterday')]=[moment().subtract('days', 1), moment().subtract('days', 1)];
    ranges[trans('Last 7 Days')]=[moment().subtract('days', 6), moment()];
    ranges[trans('Last 30 Days')]=[moment().subtract('days', 29), moment()];
    ranges[trans('This Month')]=[moment().startOf('month'), moment().endOf('month')];
    ranges[trans('Last Month')]=[moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')];
    ranges[trans('This Year')]=[moment().startOf('year'), moment().endOf('year')];
    ranges[trans('Last Year')]=[moment().subtract(1,'year').startOf('year'), moment().subtract(1,'year').endOf('year')];

    $('.datepickerrange').daterangepicker({
      locale:{
        format: 'YYYY/MM/DD',
        "applyLabel": trans("Save"),
        "cancelLabel": trans("Cancel"),
      },
      ranges,
    },
    function(start, end) {
        $('#dateRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    });

    $('.datepickerrange').on( 'cancel.daterangepicker', function(){
      $(this).val('');
    });

    //validation
    $('form').each(function() {  // attach to all form elements on page
      $(this).validate({       // initialize plugin on each form
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });
    });
    
    //inialize datatable
    $("#example1").DataTable({
      "lengthMenu": [[10, 25, 50,100,500,1000, -1], [10, 25, 50,100,500,1000 ,"All"]],
      dom: "<'row'<'col-sm-4'l><'col-sm-4'><'col-sm-4'f>>" +
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
      "serverSide": false,
      "bSort" : false,
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

    var custom_datatable=$(".custom-datatable").DataTable({
      "lengthMenu": [[10, 25, 50,100,500,1000, -1], [10, 25, 50,100,500,1000 ,"All"]],
      "ordering": false,
      "lengthMenu": [[5,10, 25, 50, -1],[10, 25, 50, "All"]],
      dom: "<'row'<'col-sm-2'l><'col-sm-10'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-4'i><'col-sm-8'p>>",
      "processing": true,
      "serverSide": false,
      "bSort" : false,
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

    var invoice_datatable=$(".invoice-datatable").DataTable({
      "lengthMenu": [[10, 25, 50,100,500,1000, -1], [10, 25, 50,100,500,1000 ,"All"]],
      "order":[[0,'asc']],
      "columnDefs": [
        {
            "targets": [ 0 ],
            "visible": false,
            "searchable": false
        },
      ],
      dom: "<'row'<'col-sm-2'l><'col-sm-10'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-4'i><'col-sm-8'p>>",
      "processing": true,
      "serverSide": false,
      "bSort" : true,
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

    $('.datatable').DataTable({
      "lengthMenu": [[10, 25, 50,100,500,1000, -1], [10, 25, 50,100,500,1000 ,"All"]],
      dom: "<'row'<'col-sm-2'l><'col-sm-10'f>>" +
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
        "serverSide": false,
        "bSort" : false,
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

    //initialize select2
    $('.select2').select2({
      width:"100%"
    });

    //intialize toastr
    toastr.options = {
      "debug": false,
      "positionClass": "toast-top-center",
      "onclick": null,
      "fadeIn": 300,
      "fadeOut": 1000,
      "timeOut": 5000,
      "extendedTimeOut": 1000
    }
    

    //initialize lightbox images
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true
      });
    });
    
    //get unread messages
    if(can_view_chat)
    {
      get_unread_messages();
    }
    
    //get new visits
    if(can_view_visit)
    {
      get_new_visits();
    }
    
    //get stock alerts
    if(can_view_product)
    {
      get_stock_alerts();
    }
  
    setInterval(function(){
      if(can_view_chat)
      {
        get_unread_messages();
      }

      if(can_view_visit)
      {
        get_new_visits();
      }

      if(can_view_product)
      {
        get_stock_alerts();
      }
    },60000);


    $(window).on('load',function() {
      $('.preloader').hide();
      $('.loader').hide();
    });

    //OverlayScrollbars
    document.addEventListener("DOMContentLoaded", function() {
      //The first argument are the elements to which the plugin shall be initialized
      //The second argument has to be at least a empty object or a object with your desired options
      // OverlayScrollbars(document.querySelectorAll('body'), { });
      OverlayScrollbars(document.querySelectorAll('.dropdown-menu'), { });
    });

    //navigation bar overflow
    $('.dropdown').on('show.bs.dropdown', function() {
        $('.ml-auto').css('overflow-x', 'visible');
    });
    
    $('.dropdown').on('hidden.bs.dropdown', function() {
        $('.ml-auto').css('overflow-x', 'auto');
    });

    $(document).on('click','.change_theme',function(e){
      $.ajax({
        beforeSend:function(){
          $('.preloader').show();
          $('.loader').show();
        },
        url:ajax_url('change_admin_theme'),
        success:function(theme){
          if(theme=='light')
          {
            $('#theme').html(`
              <link rel="stylesheet" href="`+url('dist/css/light-theme.css')+`">
            `);
          }
          else{
            $('#theme').html(`
              <link rel="stylesheet" href="`+url('dist/css/dark-theme.css')+`">
            `);
          }
          
        
          if($('#dashboard').hasClass('active'))
          {
            if(can_view_income_statistics)
            {
              income_chart_statistics();
            }
            if(can_view_best_income_packages)
            {
              best_packages();
            }
            if(can_view_best_income_tests)
            {
              best_tests();
            }
            if(can_view_best_income_cultures)
            {
              best_cultures();
            }
          }
          
        },
      });

      setTimeout(function(){
        $('.preloader').hide();
        $('.loader').hide();
      },1200);
    });

    //prevent submiting form more than one
    $(document).on('submit','form:not(.modal form,.chat-system form)',function(){
      if($(this).valid())
      {
        $('.preloader').show();
        $('.loader').show();
      }
    });

    //prevent submiting form after canceling
    $(document).on('click','.cancel_form',function(){
      $('.preloader').show();
      $('.loader').show();
    });

    
})(jQuery);

//toastr success message
function toastr_success(message)
{
    toastr.success(message,trans('Success'));
}

//toastr error message
function toastr_error(message)
{
    toastr.error(message,trans('Failed'));
}


//sumbmit delete form
function delete_submit(el)
{
   $(el).parent().submit();
}

//get unread messages
function get_unread_messages()
{
  $.ajax({
    url:ajax_url('get_unread_messages'),
    success:function(messages)
    {
       
      var html=``;
      
      if(messages.length>0)
      {
        messages.forEach(function(message){
          
          html+=`<a href="`+url('admin/chat')+`" class="dropdown-item">
                    <div class="media">
                      <div class="media-body">
                        <h3 class="dropdown-item-title">
                          `+message.from_user.name+`
                        </h3>
                        <p class="text-sm">`+message.message.substr(0,20)+`...</p>
                        <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i>`+message.since+`</p>
                      </div>
                    </div>
                  </a>
                  <div class="dropdown-divider"></div>`;
    
        });

        $('.unread_messages_count').text(messages.length);
        
      }
      else{
        html+=`<p class="text-center">`+trans("No new messages")+`</p>`;

        $('.unread_messages_count').text('');
      }
      
      $('.list_unread_messages').html(html);
  
    }
  });
}

//get new messages
function get_new_visits()
{
  $.ajax({
    url:ajax_url("get_new_visits"),
    success:function(visits)
    {
      var html=``;

      if(visits.length>0)
      {
        visits.forEach(function(visit){
          html+=`<a href="`+url('admin/visits/'+visit.id)+`" class="dropdown-item">
                  <i class="fas fa-home mr-2"></i>`+visit.patient.name+`
                  <span class="float-right text-muted text-sm">`+visit.since+`</span>
                </a>`;
        });
     
        $('.visits_count').text(visits.length);

        $('.list_visits').html(html);

      }
      else{
        $('.visits_count').text('');

        $('.list_visits').html(`<p class="text-center">`+trans("No new visits")+`</p>`);
      }
    
    }
  });
}

//get stock alerts
function get_stock_alerts()
{
  $.ajax({
    url:ajax_url("get_stock_alerts"),
    success:function(stock_alerts)
    {
      var html=``;

      if(stock_alerts.length>0)
      {
          html+=`<a href="`+url('admin/inventory/product_alerts')+`" class="dropdown-item">
                  <i class="fas fa-cubes mr-2"></i> <span class="badge badge-danger">`+stock_alerts.length+`</span> `+trans('Product alerts')+`
                </a>`;
     
          $('.stock_alerts_count').text(stock_alerts.length);

          $('.list_stock_alerts').html(html);
      }
      else{
          $('.stock_alerts_count').text('');

          $('.list_stock_alerts').html(`<p class="text-center">`+trans("No stock alerts")+`</p>`);
      }
    
    }
  });
}

//url
function url(url='')
{
  var base_url=location.origin;

  return base_url+'/'+url;
}

//ajax url
function ajax_url(url='')
{
  var base_url=location.origin;

  return base_url+'/ajax/'+url;
}


function formated_price(price)
{
  return price+' '+$('#system_currency').val();
}







