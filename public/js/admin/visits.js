(function($){
    
    "use strict";

    var patient_id=$('#patient_id').val();
    
    //active
    $('#visits').addClass('active');

    //visits datatable
    table=$('#visits_table').DataTable( {
      "lengthMenu": [[10, 25, 50,100,500,1000, -1], [10, 25, 50,100,500,1000 ,"All"]],
      dom: "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-4'i><'col-sm-8'p>>",
      buttons: [
        {
            extend:    'copyHtml5',
            text:      '<i class="fas fa-copy"></i> Copy',
            titleAttr: 'Copy'
        },
        {
            extend:    'excelHtml5',
            text:      '<i class="fas fa-file-excel"></i> Excel',
            titleAttr: 'Excel'
        },
        {
            extend:    'csvHtml5',
            text:      '<i class="fas fa-file-csv"></i> CVS',
            titleAttr: 'CSV'
        },
        {
            extend:    'pdfHtml5',
            text:      '<i class="fas fa-file-pdf"></i> PDF',
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
      "bSort" : false,
        "ajax": {
            url:url("admin/get_visits"),
            data:function(data)
            {
              data.filter_status=$('#filter_status').val();
              data.filter_read=$('#filter_read').val();
            }
        },
        // orderCellsTop: true,
        fixedHeader: true,
        "columns": [
            {data:"bulk_checkbox",searchable:false,sortable:false,orderable:false},
            {data:"id",sortable:true,orderable:true},
            {data:"patient.name",sortable:false,orderable:false},
            {data:"patient.phone",sortable:false,orderable:false},
            {data:"patient.address",sortable:false,orderable:false},
            {data:"visit_date",sortable:false,orderable:false},
            {data:"read",sortable:false,searchable:false,orderable:false},
            {data:"status",sortable:false,searchable:false,orderable:false},
            {data:"action",sortable:false,searchable:false,orderable:false}
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

    $('#filter_status').on('change',function(){
       table.draw();
    });

    $('#filter_read').on('change',function(){
      table.draw();
    });

    //change status
    $(document).on('click','.netliva-switch label',function(){
       var id=$(this).prev('input').attr('visit-id');
       $.ajax({
           type:'post',
           url:ajax_url("change_visit_status/"+id),
           success:function(message)
           {
               toastr.success(message);
           }
       })
    });

    //change patient type
    $('#patient_id').select2({
        width:"100%",
        placeholder:trans("Patient Name"),
        ajax: {
        url: ajax_url('get_patient_by_name'),
        processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            }
        }
    });
    

    //selected patient
    $(document).on('select2:select','#patient_id', function (e) {
        var el=$(e.target);
        var data = e.params.data;
        $.ajax({
            url:ajax_url('get_patient'+'?id='+data.id),
            beforeSend:function()
            {
                $('.preloader').show();
                $('.loader').show();
            },
            success:function(patient)
            {
                $("#name").val(patient.name);
                $("#phone").val(patient.phone);
                $("#email").val(patient.email);
                $("#gender").val(patient.gender);
                $("#dob").val(patient.dob);
                $("#address").val(patient.address);
            },
            complete:function(){
                $('.preloader').hide();
                $('.loader').hide();
            }
        });
    });

    //delete visit
    $(document).on('click','.delete_visit',function(e){
        e.preventDefault();
        var el=$(this);
        swal({
            title: trans("Are you sure to delete visit ?"),
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

     //create patient
   $('#create_patient').on('submit',function(e){
    e.preventDefault();
    
    var data=$('#create_patient').serialize();

    var valid=$(this).valid();

    if(valid)
    {
       $.ajax({
         url:ajax_url("create_patient"),
         type:"post",
         data:data,
         beforeSend:function(){
             $('.preloader').show();
             $('.loader').show();
          },
         success:function(data){
              $('#patient_id').append(`<option value="`+data.id+`">`+data.name+`</option>`);
              $('#patient_id').val(data.id);
              $('#patient_id').trigger({
                  type: 'select2:select',
                  params: {
                      data:{
                          id:data.id,
                          text:data.name
                      }
                  }
              });
              $('#patient_modal').modal('hide');
              $('#patient_modal_error').html(``);
              $('#create_patient_inputs input').val(``);
              toastr.success('Patient saved successfully','Success');
         },
         error:function(xhr, status, error){
             toastr.error(trans('Something went wrong'),trans('Failed'));
             var errors=xhr.responseJSON.errors;
             var error_html=`<div class="callout callout-danger">
                               <h5 class="text-danger">
                                  <i class="fa fa-times"></i> `+trans("Failed")+`
                               </h5>
                               <ul>`;
             for (var key in errors){
                error_html+=`<li>`+errors[key]+`</li>`;
             }
             error_html+=`</ul></div>`;
             $('#patient_modal_error').html(error_html);
         },
         complete:function()
         {
            $('.preloader').hide();
            $('.loader').hide();
         }
     });

    }
    else{
       return false;
    }

    
    });

    //select2 tests
    $('#select_tests').select2({
        width:"100%",
        placeholder:trans("Select tests"),
        ajax: {
           beforeSend:function()
           {
              $('.preloader').show();
              $('.loader').show();
           },
           url: ajax_url('get_tests_select2'),
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

    //select2 cultures
    $('#select_cultures').select2({
        width:"100%",
        placeholder:trans("Select cultures"),
        ajax: {
           beforeSend:function()
           {
              $('.preloader').show();
              $('.loader').show();
           },
           url: ajax_url('get_cultures_select2'),
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

    //select2 packages
    $('#select_packages').select2({
        width:"100%",
        placeholder:trans("Select packages"),
        ajax: {
           beforeSend:function()
           {
              $('.preloader').show();
              $('.loader').show();
           },
           url: ajax_url('get_packages_select2'),
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


    //validate lat and lng
    $('#visit_form').on('submit',function(e){
        var lat=$('#visit_lat').val();
        if(lat==''||lng=='')
        {
            e.preventDefault();
            toastr.error(trans('Please select home visit location on map'),trans('Failed'));
        }
    });

    //country select2
    $('#create_country_id').select2({
    width:"85%",
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

    //get contracts select2 intialize
    $('#patient_contract_id').select2({
    width:"84%",
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

    //create avatar
    $(document).on('change','#create_avatar',function(){
        var avatar=document.getElementById('create_avatar').files[0];
        getBase64(avatar,'create');
    });


    //get age from dob
   $('#create_dob').on('change',function(){
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
                    $('#create_age').val(age.age);
                    $('#create_age_unit').val(age.unit);
                },
                complete:function(){
                    $('.preloader').hide();
                    $('.loader').hide();
                }
            })
        }
    });

    //get dob from age
    $('#create_age').on('change',function(){
       var age_number=$('#create_age').val();
       var age_unit=$('#create_age_unit').val();
       var age=age_number+' '+age_unit;

       if(age_number!==null&&age_unit!==null)
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
                $('#create_dob').val(dob);
             },
             complete:function(){
                   $('.preloader').hide();
                   $('.loader').hide();
             }
          })
       }
    });

    //get dob from age
    $('#create_age_unit').on('change',function(){
       var age_number=$('#create_age').val();
       var age_unit=$('#create_age_unit').val();
       var age=age_number+' '+age_unit;

       if(age_number!==null&&age_unit!==null)
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
                $('#create_dob').val(dob);
             },
             complete:function(){
                   $('.preloader').hide();
                   $('.loader').hide();
             }
          })
       }
    });


   

})(jQuery);


//location on map
let marker;
let map;
let visit_lat=parseFloat($('#visit_lat').val());
let visit_lng=parseFloat($('#visit_lng').val());
let zoom_level=parseInt($('#zoom_level').val());

if(isNaN(visit_lat)||isNaN(visit_lng)||isNaN(zoom_level))
{
    visit_lat=26.8206;
    visit_lng=30.8025;
    zoom_level=4;
}

function initMap() {
    const myLatlng = { lat: visit_lat, lng: visit_lng};

    //initiate map
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: zoom_level,
      center: myLatlng
    });

    //initiate marker
    marker = new google.maps.Marker();
    
    //change marker listener
    map.addListener('click', function(e) {
        placeMarkerAndPanTo(e.latLng, map);
    });

    //change zoom level listener
    google.maps.event.addListener(map, 'zoom_changed', function() {
        zoom_level = map.getZoom();
        $('#zoom_level').val(zoom_level);
    });

    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
      searchBox.setBounds(map.getBounds());
    });

    var markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
      var places = searchBox.getPlaces();

      if (places.length == 0) {
        return;
      }

      // For each place, get the icon, name and location.
      var bounds = new google.maps.LatLngBounds();
      places.forEach(function(place) {
        if (!place.geometry) {
          console.log("Returned place contains no geometry");
          return;
        }

        // Create a marker for each place.
        placeMarkerAndPanTo(place.geometry.location,map);

        if (place.geometry.viewport) {
          // Only geocodes have viewport.
          bounds.union(place.geometry.viewport);
        } else {
          bounds.extend(place.geometry.location);
        }
      });
      map.fitBounds(bounds);
    });
}

function initMapEdit() {
    const myLatlng = { lat: visit_lat, lng: visit_lng};

    //initiate map
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: zoom_level,
      center: myLatlng
    });

    //initiate marker
    marker = new google.maps.Marker({
        position: myLatlng,
        map,
        title: "Click to zoom"
    });
    
    //change marker listener
    map.addListener('click', function(e) {
        placeMarkerAndPanTo(e.latLng, map);
    });

    //change zoom level listener
    google.maps.event.addListener(map, 'zoom_changed', function() {
        zoom_level = map.getZoom();
        $('#zoom_level').val(zoom_level);
    });

    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
      searchBox.setBounds(map.getBounds());
    });

    var markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
      var places = searchBox.getPlaces();

      if (places.length == 0) {
        return;
      }

      // For each place, get the icon, name and location.
      var bounds = new google.maps.LatLngBounds();
      places.forEach(function(place) {
        if (!place.geometry) {
          console.log("Returned place contains no geometry");
          return;
        }

        // Create a marker for each place.
        placeMarkerAndPanTo(place.geometry.location,map);

        if (place.geometry.viewport) {
          // Only geocodes have viewport.
          bounds.union(place.geometry.viewport);
        } else {
          bounds.extend(place.geometry.location);
        }
      });
      map.fitBounds(bounds);
    });
}

function initMapShow()
{
    const myLatlng = { lat: visit_lat, lng: visit_lng};

    //initiate map
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: zoom_level,
      center: myLatlng
    });

    //initiate marker
    marker = new google.maps.Marker({
      position: myLatlng,
      map,
      title: "Click to zoom"
    });
}

function placeMarkerAndPanTo(latLng, map) {
    marker.setMap(null);
    marker = new google.maps.Marker({
    position: latLng,
    map: map
    });
    //set branch lat and lng
    $('#visit_lat').val(latLng.lat());
    $('#visit_lng').val(latLng.lng());
    $('#zoom_level').val(map.getZoom());
}

function getBase64(file,type) {
    if(file==undefined||file==null)
    {
       $('#'+type+'_patient_avatar_hidden').val('');
 
       return;
    }
    var reader = new FileReader();
    reader.readAsDataURL(file);
    data=reader.onload = function () {
       $('#'+type+'_patient_avatar_hidden').val(reader.result);
    };
    reader.onerror = function (error) {
      console.log('Error: ', error);
    };
 }

