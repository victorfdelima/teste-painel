var map;
var users;
var providers;
var ajaxMarkers = [];
var googleMarkers = [];



function initMap() {
  const latitute = document.getElementById('lat').value || -8
  const longitude = document.getElementById('long').value || -35
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 11,
    center: new google.maps.LatLng(latitute, longitude),
    minZoom: 1,
    mapTypeId: "roadmap",
  });
  new AutocompleteDirectionsHandler(map);

  const iconBase = "https://vamo.app.br";
  const icons = {
    user: {
      name: "User",
      icon: '{{ asset("storage/.$marker-user.png") }}',
    },
    car: {
      name: "Car",
      icon: '{{ asset("storage/.$marker-car.png") }}',
    },


  };

  const features = [{
    position: new google.maps.LatLng(-33.91721, 151.2263),
    type: "user",
  },
  {
    position: new google.maps.LatLng(-33.91539, 151.2282),
    type: "car",
  },
  {
    position: new google.maps.LatLng(-33.91747, 151.22912),
    type: "Parking",
  },

  ];
  features.forEach((feature) => {
    new google.maps.Marker({
      position: feature.position,
      icon: icons[feature.type].icon,
      map: map,
    });
  });
  const legend = document.getElementById("legend");

  for (const key in icons) {
    const type = icons[key];
    const name = type.name;
    const icon = type.icon;
    const div = document.createElement("div");
    div.innerHTML = '<img src="' + icon + '"> ' + name;
    legend.appendChild(div);



  }
  map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);
}


function AutocompleteDirectionsHandler(map) {
  this.map = map;
  this.originPlaceId = null;
  this.destinationPlaceId = null;
  this.waypts = [];
  this.travelMode = 'DRIVING';
  document.googleMapsInstance = this;

  var modeSelector = document.getElementById('mode-selector');

  this.directionsService = new google.maps.DirectionsService;
  this.directionsDisplay = new google.maps.DirectionsRenderer({ suppressMarkers: false });
  this.directionsDisplay.setMap(map);

  var modeSelectorAutocomplete = new google.maps.places.Autocomplete(
    modeSelector);

  modeSelectorAutocomplete.addListener('place_changed', function (event) {
    var place = modeSelectorAutocomplete.getPlace();
  });

  
  //Paradas


}

function adicionarAutoComplete(originInput, instance, mode, lat, long) {

  originInput = document.getElementById(originInput);
  lat = document.getElementById(lat);
  long = document.getElementById(long);

  var originAutocomplete = new google.maps.places.Autocomplete(
    originInput);

  originAutocomplete.addListener('place_changed', function (event) {
    var place = originAutocomplete.getPlace();

    if (place.hasOwnProperty('place_id')) {
      if (!place.geometry) {
        // window.alert("Autocomplete's returned place contains no geometry");
        return;
      }
      lat.value = place.geometry.location.lat();
      long.value = place.geometry.location.lng();
    } else {
      service.textSearch({
        query: place.name
      }, function (results, status) {
        if (status == google.maps.places.PlacesServiceStatus.OK) {
          lat.value = results[0].geometry.location.lat();
          long.value = results[0].geometry.location.lng();
        }
      });
    }
  });

  instance.setupPlaceChangedListener(originAutocomplete, mode);

}

// Sets a listener on a radio button to change the filter type on Places
// Autocomplete.
AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function (autocomplete, mode) {
  var me = this;
  autocomplete.bindTo('bounds', this.map);
  autocomplete.addListener('place_changed', function () {
    var place = autocomplete.getPlace();
    if (!place.place_id) {
      // window.alert("Please select an option from the dropdown list.");
      return;
    }
    console.log(place.geometry.location.lat());
    console.log(place.geometry.location.lng())
    if (mode === 'ORIG') {
      me.originPlaceId = place.place_id;
    } else if (mode == 'DEST') {
      me.destinationPlaceId = place.place_id;
    } else {
      me.waypts[mode] = adicionarParada(place);
    }
    me.route();
  });

};

function adicionarParada(place) {//Adicionar nova parada
  var local = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
  return { location: local, stopover: true };
}

//Calcular rota
AutocompleteDirectionsHandler.prototype.route = function () {
  if (!this.originPlaceId || !this.destinationPlaceId) {
    return;
  }

  var paradas = [];

  for (index in this.waypts) {
    paradas.push(this.waypts[index]);
  }

  var me = this;
  this.directionsService.route({
    origin: { 'placeId': this.originPlaceId },
    destination: { 'placeId': this.destinationPlaceId },
    waypoints: paradas,
    travelMode: google.maps.TravelMode.DRIVING,
  }, function (response, status) {
    console.log(response);
    if (status === 'OK') {
      me.directionsDisplay.setDirections(response);
    } else {

    }
  });
};