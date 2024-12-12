(function ($) {

    "use strict";

    //active
    $('#profile').addClass('active');

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

    //change avatar
    $(document).on('change', '#avatar', function () {
        var file = document.getElementById('avatar').files[0];
        getBase64(file);
    });

    //delete avatar
    $(document).on('click', '#delete_avatar', function () {
        $('#avatar').val(null);
        $('#patient_avatar').attr('src', url('img/avatar.png'));
        $('#patient_avatar').parent('a').attr('href', url('img/avatar.png'));

        $.ajax({
            url: ajax_url('delete_patient_avatar_by_patient/'),
            beforeSend: function () {
                $('.preloader').show();
                $('.loader').show();
            },
            success: function () {
                toastr_success(trans('Avatar deleted successfully'));
            },
            complete: function () {
                $('.preloader').hide();
                $('.loader').hide();
            }
        });
    });

})(jQuery);


function getBase64(file) {
  var reader = new FileReader();
  reader.readAsDataURL(file);
  data=reader.onload = function () {
      $('#patient_avatar').attr('src',reader.result);
      $('#patient_avatar').parent('a').attr('href',reader.result);
  };
  reader.onerror = function (error) {
    console.log('Error: ', error);
  };
}
