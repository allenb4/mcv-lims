(function($){

    "use strict";
    

    //active
    $('#reports').addClass('active menu-open');
    $('#reports_link').addClass('active');
    $('#accounting_report').addClass('active');

    //get doctor select2 intialize
    $('#doctor').select2({
      width:"100%",
      placeholder:trans("Doctor"),
      multiple: true,
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

    //get test select2 intialize
    $('#test').select2({
        width:"100%",
        placeholder:trans("Test"),
        multiple: true,
        ajax: {
           beforeSend:function()
           {
              $('.preloader').show();
              $('.loader').show();
           },
           url:ajax_url('get_tests_select2'),
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

    //get culture select2 intialize
    $('#culture').select2({
        width:"100%",
        placeholder:trans("Culture"),
        multiple: true,
        ajax: {
           beforeSend:function()
           {
              $('.preloader').show();
              $('.loader').show();
           },
           url:ajax_url('get_cultures_select2'),
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

    //get culture select2 intialize
    $('#package').select2({
         width:"100%",
         placeholder:trans("Package"),
         multiple: true,
         ajax: {
            beforeSend:function()
            {
               $('.preloader').show();
               $('.loader').show();
            },
            url:ajax_url('get_packages_select2'),
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

    //get culture select2 intialize
    $('#branch').select2({
      width:"100%",
      placeholder:trans("Branch"),
      multiple: true,
      ajax: {
         beforeSend:function()
         {
            $('.preloader').show();
            $('.loader').show();
         },
         url:ajax_url('get_branches_select2'),
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

    //get culture select2 intialize
    $('#contract').select2({
      width:"100%",
      placeholder:trans("Contract"),
      multiple: true,
      ajax: {
         beforeSend:function()
         {
            $('.preloader').show();
            $('.loader').show();
         },
         url:ajax_url('get_contracts'),
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

})(jQuery);