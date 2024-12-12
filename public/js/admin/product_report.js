(function($){

    "use strict";
    
    //active
    $('#reports').addClass('active menu-open');
    $('#reports_link').addClass('active');
    $('#product_report').addClass('active');

    //get branch select2
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

     //get product select2
     $('#product').select2({
        width:"100%",
        placeholder:trans("Product"),
        multiple: true,
        ajax: {
           beforeSend:function()
           {
              $('.preloader').show();
              $('.loader').show();
           },
           url:ajax_url('get_products_select2'),
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
