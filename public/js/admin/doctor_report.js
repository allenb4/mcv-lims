(function($){

    "use strict";
    
    //active
    $('#reports').addClass('active menu-open');
    $('#reports_link').addClass('active');
    $('#doctor_report').addClass('active');

    //get doctor select2 intialize
    $('#doctor').select2({
      width:"100%",
      placeholder:trans("Doctor"),
      ajax: {
       beforeSend:function()
       {
          $('.preloader').show();
          $('.loader').show();
       },
       url:ajax_url('get_doctors'),
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
