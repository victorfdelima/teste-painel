@extends('user.layout.base')

@section('title', 'Dashboard ')

@section('content')

<div class="col-md-9">
  <div class="dash-content">
    <div class="row no-margin">
      <div class="col-md-12">
        <h4 class="page-title">@lang('user.ride.ride_now')</h4>
      </div>
    </div>
    @include('common.notify')
    <div class="row no-margin">
      <div class="col-md-6">
        <form action="{{url('confirm/ride')}}" method="GET" onkeypress="return disableEnterKey(event);">
          <div class="input-group dash-form">
            <input type="text" class="form-control" id="origin-input" name="s_address"  placeholder="Local de partida">
          </div>
          <div class="input-group dash-form">
            <input type="text" class="form-control" id="primeira-parada" name="p_parada"  placeholder="Parada">
          </div>
          <div class="input-group dash-form">
            <input type="text" class="form-control" id="destination-input" name="d_address"  placeholder="Local de chegada" >
          </div>

          <input type="hidden" name="s_latitude" id="origin_latitude">
          <input type="hidden" name="s_longitude" id="origin_longitude">
          <input type="hidden" name="d_latitude" id="destination_latitude">
          <input type="hidden" name="d_longitude" id="destination_longitude">
          <input type="hidden" name="current_longitude" id="long">
          <input type="hidden" name="current_latitude" id="lat">

          <div class="car-detail"  style="direction: ltr !important;">

            @foreach($services as $service)
            <div class="car-radio">
              <input type="radio" 
              name="service_type"
              value="{{$service->id}}"
              id="service_{{$service->id}}"
              @if ($loop->first) @endif>

              <label for="service_{{$service->id}}">
                <div class="car-radio-inner" style="width: 250px;height: 10px;">
                  <div class="img"><img src="{{image($service->image)}}"></div>
                  <div class="name"><span>{{$service->name}}<p style="font-size: 10px; color:#ffff">(1-{{$service->capacity}})</p></span>
                  </div>
                </div>
              </label>
            </div>
            @endforeach


          </div>

          <div class="input-group dash-form" id="hours" >
            <input type="number" class="form-control" id="rental_hours" name="rental_hours"  placeholder="(Horas de aluguel) Quantas horas?" >
          </div>
          <button type="submit"  class="full-primary-btn fare-btn" style="background-color: #ff5e00; border-radius: 40px;">@lang('user.ride.ride_now')</button>


        </form>
      </div>

      <div class="col-md-6">
        <div class="map-responsive">
          <div id="map" style="width: 100%; height: 450px;"></div>
        </div> 
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')    
<script type="text/javascript">
  var current_latitude = 13.0574400;
  var current_longitude = 80.2482605;
</script>

<script type="text/javascript">
  $(".drp1").hide();
  $("#drplocat").click(function(){
    $(".drplocat").hide();
    $(".drp1").show()
  });


  if( navigator.geolocation ) {
   navigator.geolocation.getCurrentPosition( success, fail );
 } else {
  console.log('Desculpe, seu navegador não suporta serviços de geolocalização');
  initMap();
}

function success(position)
{
  document.getElementById('long').value = position.coords.longitude;
  document.getElementById('lat').value = position.coords.latitude

  if(position.coords.longitude != "" && position.coords.latitude != ""){
    current_longitude = position.coords.longitude;
    current_latitude = position.coords.latitude;
  }
  initMap();
}

function fail()
{
        // Could not obtain location
        console.log('incapaz de obter a sua localização');
        initMap();
      }
    </script> 

    <script type="text/javascript" src="{{ asset('asset/js/map.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ Config::get('constants.map_key') }}&libraries=places&callback=initMap" async defer></script>

    <script type="text/javascript">
      function disableEnterKey(e)
      {
        var key;
        if(window.e)
            key = window.e.keyCode; // IE
          else
            key = e.which; // Firefox

          if(key == 13)
            return e.preventDefault();
        }
      </script>
      <script type="text/javascript">
        $(document).ready(function(){
          $("#hours").hide();

          $('input[name=service_type]').change(function(){

            var id =     $('input[name=service_type]:checked').val();

            $.ajax({url: "{{ url('hour') }}/"+id,dataType: "json",
             success: function(data){
                    //console.log(data['calculator']);

                       /*if (data['calculator'] == 'DISTANCEHOUR')
                       $("#hours").show();  
                       else
                         $("#hours").hide(); */
                     }});
          });
        }); 

        setInterval("checkstatus()",3000); 

        function checkstatus(){
          $.ajax({
            url: '/user/incoming',
            dataType: "JSON",
            data:'',
            type: "GET",
            success: function(data){
              if(data.status==1){
                window.location.replace("/dashboard");
              }
            }
          });
        }

        function initMap() {

          var map = new google.maps.Map(document.getElementById('map'), {
            mapTypeControl: false,
            zoomControl: true,
            center: {lat: current_latitude, lng: current_longitude},
            zoom: 12,
            styles : [{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#e4e8e9"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#7de843"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#9bd0e8"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]
          });

          new AutocompleteDirectionsHandler(map);
        }

/**
 * @constructor
 */

 function AutocompleteDirectionsHandler(map) {
  this.map = map;
  this.originPlaceId = null;
  this.destinationPlaceId = null;
  this.travelMode = 'DRIVING';
  var originInput = document.getElementById('origin-input');
  var primeiraParada = document.getElementById('primeira-parada');

  var destinationInput = document.getElementById('destination-input');
  var modeSelector = document.getElementById('mode-selector');
  var originLatitude = document.getElementById('origin_latitude');
  var originLongitude = document.getElementById('origin_longitude');
  var destinationLatitude = document.getElementById('destination_latitude');
  var destinationLongitude = document.getElementById('destination_longitude');

  var polylineOptionsActual = new google.maps.Polyline({
    strokeColor: '#111',
    strokeOpacity: 0.8,
    strokeWeight: 4
  });

  this.directionsService = new google.maps.DirectionsService;
  this.directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: false, polylineOptions: polylineOptionsActual});
  this.directionsDisplay.setMap(map);

  var originAutocomplete = new google.maps.places.Autocomplete(
    originInput);

  var primeiraParadaAutocomplete = new google.maps.places.Autocomplete(
    primeiraParada);

  
  var destinationAutocomplete = new google.maps.places.Autocomplete(
    destinationInput);

  var modeSelectorAutocomplete = new google.maps.places.Autocomplete(
    modeSelector);

  modeSelectorAutocomplete.addListener('place_changed', function(event) {
    var place = modeSelectorAutocomplete.getPlace();        
  });

  primeiraParadaAutocomplete.addListener('place_changed', function(event) {
    var place = primeiraParadaAutocomplete.getPlace();

    if (place.hasOwnProperty('place_id')) {
      if (!place.geometry) {
                    // window.alert("Autocomplete's returned place contains no geometry");
                    return;
                  }
                  originLatitude.value = place.geometry.location.lat();
                  originLongitude.value = place.geometry.location.lng();
                } else {
                  service.textSearch({
                    query: place.name
                  }, function(results, status) {
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                      originLatitude.value = results[0].geometry.location.lat();
                      originLongitude.value = results[0].geometry.location.lng();
                    }
                  });
                }
              });

  originAutocomplete.addListener('place_changed', function(event) {
    var place = originAutocomplete.getPlace();

    if (place.hasOwnProperty('place_id')) {
      if (!place.geometry) {
                    // window.alert("Autocomplete's returned place contains no geometry");
                    return;
                  }
                  originLatitude.value = place.geometry.location.lat();
                  originLongitude.value = place.geometry.location.lng();
                } else {
                  service.textSearch({
                    query: place.name
                  }, function(results, status) {
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                      originLatitude.value = results[0].geometry.location.lat();
                      originLongitude.value = results[0].geometry.location.lng();
                    }
                  });
                }
              });


  destinationAutocomplete.addListener('place_changed', function(event) {
    var place = destinationAutocomplete.getPlace();

    if (place.hasOwnProperty('place_id')) {
      if (!place.geometry) {
                // window.alert("Autocomplete's returned place contains no geometry");
                return;
              }
              destinationLatitude.value = place.geometry.location.lat();
              destinationLongitude.value = place.geometry.location.lng();
            } else {
              service.textSearch({
                query: place.name
              }, function(results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                  destinationLatitude.value = results[0].geometry.location.lat();
                  destinationLongitude.value = results[0].geometry.location.lng();
                }
              });
            }
          });

  this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
  this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');

  this.setupPlaceChangedListener(primeiraParadaAutocomplete, 'PARA');
}

// Sets a listener on a radio button to change the filter type on Places
// Autocomplete.

AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function(autocomplete, mode) {
  var me = this;
  autocomplete.bindTo('bounds', this.map);
  autocomplete.addListener('place_changed', function() {
    var place = autocomplete.getPlace();
    if (!place.place_id) {
            // window.alert("Please select an option from the dropdown list.");
            return;
          }
          if (mode === 'ORIG') {
            me.originPlaceId = place.place_id;
          } else if(mode == 'DEST'){
            me.destinationPlaceId = place.place_id;
          } else {
            //me.paradaid = place.geometry.location;
            me.paradaid = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
           // me.paradaid = place.formatted_address;
         }
         me.route();
       });

};
/*
geometry: {…}
​​
location: {…}
​​​
lat: function lat()​​​
lng: lng()
*/
AutocompleteDirectionsHandler.prototype.route = function() {
  if (!this.originPlaceId || !this.destinationPlaceId) {
    return;
  }
  
  var me = this;

  this.directionsService.route({
//this.paradaid
origin: {'placeId': this.originPlaceId},
destination: {'placeId': this.destinationPlaceId},
waypoints:[{location:this.paradaid , stopover: true}],
optimizeWaypoints: true,
travelMode: google.maps.TravelMode.DRIVING,


}, function(response, status) {
  console.log(response);
  if (status === 'OK') {
    me.directionsDisplay.setDirections(response);
  } else {
            // window.alert('Directions request failed due to ' + status);
          }
        });
};

</script>

@endsection