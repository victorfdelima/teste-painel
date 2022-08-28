
function adicionarParadaInput() {
   var cont = $('.paradas.hide').length;
 
   if (cont>0) {
    var elemento = $('.paradas.hide')[0];
    $(elemento).removeClass('hide');
   }
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
    this.waypts = [];
    this.travelMode = 'DRIVING';

    var modeSelector = document.getElementById('mode-selector');

    this.directionsService = new google.maps.DirectionsService;
    this.directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: false});
    this.directionsDisplay.setMap(map);

    var modeSelectorAutocomplete = new google.maps.places.Autocomplete(
        modeSelector);

    modeSelectorAutocomplete.addListener('place_changed', function(event) {
        var place = modeSelectorAutocomplete.getPlace();        
    });

    adicionarAutoComplete('origin-input', this, 'ORIG', 'origin_latitude', 'origin_longitude');
    adicionarAutoComplete('destination-input', this, 'DEST', 'destination_latitude', 'destination_longitude');
   //Paradas
   adicionarAutoComplete('parada-input-1', this, 'PARA1', 'pp_lat_1', 'pp_long_1');
   adicionarAutoComplete('parada-input-2', this, 'PARA2', 'pp_lat_2', 'pp_long_2');
   adicionarAutoComplete('parada-input-3', this, 'PARA3', 'pp_lat_3', 'pp_long_3');
   adicionarAutoComplete('parada-input-4', this, 'PARA4', 'pp_lat_4', 'pp_long_4');
   adicionarAutoComplete('parada-input-5', this, 'PARA5', 'pp_lat_5', 'pp_long_5');

}

function adicionarAutoComplete(originInput, instance, mode,lat,long) {

    originInput = document.getElementById(originInput);
    lat = document.getElementById(lat);
    long = document.getElementById(long);

    var originAutocomplete = new google.maps.places.Autocomplete(
        originInput);

    originAutocomplete.addListener('place_changed', function(event) {
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
                }, function(results, status) {
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
AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function(autocomplete, mode) {
    var me = this;
    autocomplete.bindTo('bounds', this.map);
    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.place_id) {
            // window.alert("Please select an option from the dropdown list.");
            return;
        }
        console.log(place.geometry.location.lat());
        console.log(place.geometry.location.lng())
        if (mode === 'ORIG') {
            me.originPlaceId = place.place_id;
        } else if(mode == 'DEST'){
            me.destinationPlaceId = place.place_id;
        } else {
            me.waypts[mode] = adicionarParada(place);
        }
        me.route();
    });

};

function adicionarParada(place){//Adicionar nova parada
    var local = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
    return {location:local , stopover: true};
}

//Calcular rota
AutocompleteDirectionsHandler.prototype.route = function() {
    if (!this.originPlaceId || !this.destinationPlaceId) {
        return;
    }

    var paradas = [];

    for (index in this.waypts) {
        paradas.push(this.waypts[index]);
    }

    var me = this;
    this.directionsService.route({
        origin: {'placeId': this.originPlaceId},
        destination: {'placeId': this.destinationPlaceId},
        waypoints: paradas,
        travelMode: google.maps.TravelMode.DRIVING,
    }, function(response, status) {
        console.log(response);
        if (status === 'OK') {
            me.directionsDisplay.setDirections(response);
        } else {

        }
    });
};