(function($){

    "use strict";
    

    //active
    $('#reports').addClass('active menu-open');
    $('#reports_link').addClass('active');
    $('#purchase_report').addClass('active');

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
    $('#supplier').select2({
        width:"100%",
        placeholder:trans("Supplier"),
        multiple: true,
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