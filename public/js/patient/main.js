(function($){

    "use strict";

    $.widget.bridge('uibutton', $.ui.button);

    //initialize select2
    $('.select2').select2({
      width:"100%"
    });

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

    //change theme
    $(document).on('click','.change_theme',function(e){
      $.ajax({
        beforeSend:function(){
          $('.preloader').show();
          $('.loader').show();
        },
        url:ajax_url('change_patient_theme'),
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

          income_chart_statistics();
          best_packages();
          best_tests();
          best_cultures();
        },
      });

      setTimeout(function(){
        $('.preloader').hide();
        $('.loader').hide();
      },1200);
    });

    //prevent submiting form more than one
    $(document).on('submit','form:not(.modal form)',function(){
      $('.preloader').show();
      $('.loader').show();
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