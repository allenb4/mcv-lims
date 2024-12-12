(function($){

    "use strict";

    //patients datatable
    table=$('#patients_table').DataTable( {
        "lengthMenu": [[10, 25, 50,100,500,1000, -1], [10, 25, 50,100,500,1000 ,"All"]],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
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
        "serverSide": true,
        "order": [[ 1, "desc" ]],
        fixedHeader: true,
        "ajax": {
            url: url("admin/get_patients")
        },
        fixedHeader: true,
        "columns": [
            {data:"bulk_checkbox",searchable:false,sortable:false,orderable:false},
            {data:"id",orderable:true,sortable:true},
            {data:"code",orderable:false,sortable:false},
            {data:"name",orderable:false,sortable:false},
            {data:"phone",orderable:false,sortable:false},
            {data:"email",orderable:false,sortable:false},
            {data:"contract.title",orderable:false,sortable:false},
            {data:"total",searchable:false,orderable:false,sortable:false},
            {data:"paid",searchable:false,orderable:false,sortable:false},
            {data:"due",searchable:false,orderable:false,sortable:false},
            {data:"action",searchable:false,orderable:false,sortable:false}//action
        ],
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
   
   //active
   $('#patients').addClass('active');


   $('#patient_form').validate({
       rules:{
           name:{
               required:true
           },
           email:{
               required:true,
               email:true
           },
           phone:{
               required:true
           },
           address:{
               required:true
           },
           gender:{
               required:true
           },
           age:{
               required:true
           },
           age_unit:{
               required:true
           },

       },
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

   //delete patient
    $(document).on('click','.delete_patient',function(e){
        e.preventDefault();
        var el=$(this);
        swal({
            title: trans("Are you sure to delete patient ?"),
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: trans("Delete"),
            cancelButtonText: trans("Cancel"),
            closeOnConfirm: false
        },
        function(){
            $(el).parent().submit();
        });
    });

    //get contracts select2 intialize
    $('#contract_id').select2({
        width:"85%",
        allowClear:true,
        placeholder:trans("Select contract"),
        ajax: {
        beforeSend:function()
        {
            $('.preloader').show();
            $('.loader').show();
        },
        url: ajax_url('get_contracts'),
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

    //get age from dob
    $('#dob').on('change',function(){
        var dob=$(this).val();
        
        if(dob!='')
        {
            $.ajax({
                url:ajax_url('get_age/'+dob),
                beforeSend:function()
                {
                    $('.preloader').show();
                    $('.loader').show();
                },
                success:function(age)
                {
                    $('#age').val(age.age);
                    $('#age_unit').val(age.unit);
                },
                complete:function(){
                    $('.preloader').hide();
                    $('.loader').hide();
                }
            })
        }
    });

    //get dob from age
    $('#age').on('change',function(){
        var age_number=$('#age').val();
        var age_unit=$('#age_unit').val();
        var age=age_number+' '+age_unit;

        if(age_number!==''&&age_unit!=='')
        {
            $.ajax({
                url:ajax_url('get_dob/'+age),
                beforeSend:function()
                {
                    $('.preloader').show();
                    $('.loader').show();
                },
                success:function(dob)
                {
                   $('#dob').val(dob);
                },
                complete:function(){
                    $('.preloader').hide();
                    $('.loader').hide();
                }
            })
        }
    });

    //get dob from age
    $('#age_unit').on('change',function(){
        var age_number=$('#age').val();
        var age_unit=$('#age_unit').val();
        var age=age_number+' '+age_unit;

        if(age_number!==''&&age_unit!=='')
        {
            $.ajax({
                url:ajax_url('get_dob/'+age),
                beforeSend:function()
                {
                    $('.preloader').show();
                    $('.loader').show();
                },
                success:function(dob)
                {
                   $('#dob').val(dob);
                },
                complete:function(){
                    $('.preloader').hide();
                    $('.loader').hide();
                }
            })
        }
    });

    //get age from dob
    if($('#dob').val()!=='')
    {
        $('#dob').trigger('change');
    }

    //country select2
    $('#country_id').select2({
        allowClear:true,
        placeholder:trans("Select nationality"),
        ajax: {
           beforeSend:function()
           {
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
              complete:function()
              {
                 $('.preloader').hide();
                 $('.loader').hide();
              }
           }
    });

    //change avatar
    $(document).on('change','#avatar',function(){
        var file=document.getElementById('avatar').files[0];
        getBase64(file);
    });

    //change avatar
    $(document).on('click','#delete_avatar',function(){
        var patient_id=$(this).attr('patient_id');
        $('#avatar').val(null);
        $('#patient_avatar').attr('src',url('img/avatar.png'));
        $('#patient_avatar').parent('a').attr('href',url('img/avatar.png'));

        if(patient_id!==null)
        {
            $.ajax({
                url:ajax_url('delete_patient_avatar/'+patient_id),
                beforeSend:function()
                {
                    $('.preloader').show();
                    $('.loader').show();
                },
                success:function()
                {
                   toastr_success(trans('Avatar deleted successfully'));
                },
                complete:function(){
                    $('.preloader').hide();
                    $('.loader').hide();
                }
            });
        }
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
