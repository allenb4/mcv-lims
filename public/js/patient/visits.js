(function($){

    "use strict";
    
    //active
    $('#visits').addClass('active');

    //change patient type
    $('input[type=radio]').on('change',function(){
        var type=$(this).val();
        if(type==1)
        {
           $('.select_patient').addClass('d-none');
           $("#name").val('');
           $('#name').prop('disabled',false);
           $("#phone").val('');
           $('#phone').prop('disabled',false);
           $("#email").val('');
           $('#email').prop('disabled',false);
           $("#gender").val('');
           $('#gender').prop('disabled',false);
           $("#dob").val('');
           $('#dob').prop('disabled',false);
           $("#address").val('');
           $('#address').prop('disabled',false);
           $('#patient_id').val('').trigger('change');
        }
        else{
           $('.select_patient').removeClass('d-none');
           //get patient
           $.ajax({
                beforeSend:function()
                {
                    $('.preloader').show();
                    $('.loader').show();
                },
                url:ajax_url('get_current_patient'),
                success:function(patient){
                   $('#name').val(patient.name);
                   $('#name').prop('disabled',true);
                   $('#phone').val(patient.phone);
                   $('#phone').prop('disabled',true);
                   $('#address').val(patient.address);
                   $('#address').prop('disabled',true);
                   $('#email').val(patient.email);
                   $('#email').prop('disabled',true);
                   $('#dob').val(patient.dob);
                   $('#dob').prop('disabled',true);
                   $('#gender').val(patient.gender);
                   $('#gender').prop('disabled',true);
                },
                complete:function()
                {
                    $('.preloader').hide();
                    $('.loader').hide();
                }
           });
            
        }
    });


    //select2 tests
    $('#tests').select2({
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
    $('#cultures').select2({
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
    $('#packages').select2({
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

    $('#visit_form').on('submit',function(e){
        var lat=$('#visit_lat').val();
        if(lat==''||lng=='')
        {
            e.preventDefault();
            toastr.error(trans('Please select home visit location on map'),trans('Failed'));
        }
    });

   //select current location
   $('.current_location').on('click',function(){
      getLocation();
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

function placeMarkerAndPanTo(latLng, map) {
    marker.setMap(null);
    marker = new google.maps.Marker({
        position: latLng,
        map: map
    });
    //set branch lat and lng
    $('#visit_lat').val(latLng.lat);
    $('#visit_lng').val(latLng.lng);
    $('#zoom_level').val(map.getZoom());

}

//set user lat and long
function getLocation() {
   if (navigator.geolocation) {
       navigator.geolocation.getCurrentPosition(showPosition);
   } else { 
       console.log("Geolocation is not supported by this browser.");
   }
}

//get current location
function showPosition(position)
{
    var latLng={lat:position.coords.latitude,lng:position.coords.longitude};

    map.setCenter(latLng);
    map.setZoom(18);
    
    placeMarkerAndPanTo(latLng,map);
}

function initAutocomplete() {
   const map = new google.maps.Map(document.getElementById("map"), {
     center: { lat: -33.8688, lng: 151.2195 },
     zoom: 13,
     mapTypeId: "roadmap",
   });
   // Create the search box and link it to the UI element.
   const input = document.getElementById("pac-input");
   const searchBox = new google.maps.places.SearchBox(input);
 
   map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
   // Bias the SearchBox results towards current map's viewport.
   map.addListener("bounds_changed", () => {
     searchBox.setBounds(map.getBounds());
   });
 
   let markers = [];
 
   // Listen for the event fired when the user selects a prediction and retrieve
   // more details for that place.
   searchBox.addListener("places_changed", () => {
     const places = searchBox.getPlaces();
 
     if (places.length == 0) {
       return;
     }
 
     // Clear out the old markers.
     markers.forEach((marker) => {
       marker.setMap(null);
     });
     markers = [];
 
     // For each place, get the icon, name and location.
     const bounds = new google.maps.LatLngBounds();
 
     places.forEach((place) => {
       if (!place.geometry || !place.geometry.location) {
         console.log("Returned place contains no geometry");
         return;
       }
 
       const icon = {
         url: place.icon,
         size: new google.maps.Size(71, 71),
         origin: new google.maps.Point(0, 0),
         anchor: new google.maps.Point(17, 34),
         scaledSize: new google.maps.Size(25, 25),
       };
 
       // Create a marker for each place.
       markers.push(
         new google.maps.Marker({
           map,
           icon,
           title: place.name,
           position: place.geometry.location,
         })
       );
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
 