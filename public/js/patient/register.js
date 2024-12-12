(function($){

    "use strict";

    //datepicker
    var date=new Date();
    var current_year=date.getFullYear();
    $('.datepicker').datepicker({
        dateFormat:"yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange:"1800:"+current_year
    });

    //get age from dob
    $('#dob').on('change', function () {
        var dob = $(this).val();

        if (dob != '') {
            $.ajax({
                url: ajax_url('get_age/' + dob),
                beforeSend: function () {
                    $('.preloader').show();
                    $('.loader').show();
                },
                success: function (age) {
                    $('#age').val(age.age);
                    $('#age_unit').val(age.unit);
                },
                complete: function () {
                    $('.preloader').hide();
                    $('.loader').hide();
                }
            })
        }
    });

    //get dob from age
    $('#age').on('change', function () {
        var age_number = $('#age').val();
        var age_unit = $('#age_unit').val();
        var age = age_number + ' ' + age_unit;

        if (age_number !== '' && age_unit !== '') {
            $.ajax({
                url: ajax_url('get_dob/' + age),
                beforeSend: function () {
                    $('.preloader').show();
                    $('.loader').show();
                },
                success: function (dob) {
                    $('#dob').val(dob);
                },
                complete: function () {
                    $('.preloader').hide();
                    $('.loader').hide();
                }
            })
        }
    });

    //get dob from age
    $('#age_unit').on('change', function () {
        var age_number = $('#age').val();
        var age_unit = $('#age_unit').val();
        var age = age_number + ' ' + age_unit;

        if (age_number !== '' && age_unit !== '') {
            $.ajax({
                url: ajax_url('get_dob/' + age),
                beforeSend: function () {
                    $('.preloader').show();
                    $('.loader').show();
                },
                success: function (dob) {
                    $('#dob').val(dob);
                },
                complete: function () {
                    $('.preloader').hide();
                    $('.loader').hide();
                }
            })
        }
    });

    //get age from dob
    if ($('#dob').val() !== '') {
        $('#dob').trigger('change');
    }

    //country select2
    $('#country_id').select2({
        allowClear: true,
        placeholder: trans("Select nationality"),
        ajax: {
            beforeSend: function () {
                $('.preloader').show();
                $('.loader').show();
            },
            url: ajax_url('get_countries'),
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.nationality,
                            id: item.id
                        }
                    })
                };
            },
            complete: function () {
                $('.preloader').hide();
                $('.loader').hide();
            }
        }
    });

})(jQuery);

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