  
(function($){

    "use strict";
    
    //active
    $('#settings').addClass('active');

    //select2
    $('.select2').select2();

    //Colorpicker for email header
    $('#header_color').colorpicker();
    
    $('#header_color').on('colorpickerChange', function(event) {
            $('.header_color .fa-square').css('color', event.color.toString());
    });


    //Colorpicker for email footer
    $('#footer_color').colorpicker();
    
    $('#footer_color').on('colorpickerChange', function(event) {
            $('.footer_color .fa-square').css('color', event.color.toString());
    });

    //color picker
    $('.color_input').each(function(){
        $(this).colorpicker();
    });

    $('.color_input').on('colorpickerChange', function(event) {
        $(this).next('.color_tool').find('.fa-square').css('color', event.color.toString());
    });

    $('form').each(function () {
        if ($(this).data('validator'))
            $(this).data('validator').settings.ignore = ".note-editor *";
    });

    // Summernote
    $('.summernote').summernote({
        heigt:400,
        tooltip: false,
        dialogsFade: true,
        toolbar: []
    });

    
    //get patient by code
    $('#timezone').select2({
        width:"90%",
        placeholder:trans("Timezone"),
        ajax: {
        beforeSend:function()
        {
            $('.preloader').show();
            $('.loader').show();
        },
        url: ajax_url('get_timezones_select2'),
        processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.timezone
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

    //get patient by code
    $('#language').select2({
        width:"90%",
        placeholder:trans("Default language"),
        ajax: {
        beforeSend:function()
        {
            $('.preloader').show();
            $('.loader').show();
        },
        url: ajax_url('get_languages'),
        processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.iso,
                            id: item.iso
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

    $('#sms_gateway').on('change',function(){
        var gateway=$(this).val();
        $('.sms-gateways-tabs .nav-link').removeClass('active');
        $('.sms-gateways-tabs .tab-pane').removeClass('active');
        $('.sms-gateways-tabs .tab-pane').removeClass('show');
        $('.sms-gateways-tabs .tab-pane input').removeClass('is-invalid');
        $('.sms-gateways-tabs .tab-pane input').removeAttr('required');
        $('#sms-'+gateway+'-card').addClass('active show');
        $('#sms-'+gateway+'-tab').addClass('active');
        $('#sms-'+gateway+'-card input').attr('required',true);
        $("#sms_settings").validate().resetForm();
    });

    $('#sms_settings button').on('click',function(){
        var gateway=$('#sms_gateway').val();
        $('.sms-gateways-tabs .nav-link').removeClass('active');
        $('.sms-gateways-tabs .tab-pane').removeClass('active');
        $('.sms-gateways-tabs .tab-pane').removeClass('show');
        $('#sms-'+gateway+'-card').addClass('active show');
        $('#sms-'+gateway+'-tab').addClass('active');
    });


})(jQuery);
 