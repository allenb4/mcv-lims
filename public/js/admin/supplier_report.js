(function($){

    "use strict";
    
    //active
    $('#reports').addClass('active menu-open');
    $('#reports_link').addClass('active');
    $('#supplier_report').addClass('active');

    //get supplier select2 intialize
    $('#supplier').select2({
      width:"100%",
      placeholder:trans("Supplier"),
      ajax: {
       beforeSend:function()
       {
          $('.preloader').show();
          $('.loader').show();
       },
       url:ajax_url('get_suppliers_select2'),
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
