@extends('admin.layout.base')

@section('title', 'Mapa Geral ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        
        <div class="box box-block bg-white">
            <h5 class="mb-1">Mapa Geral</h5>
            <div class="row">
                <div class="col-xs-12">
                    <div id="map"></div>
                    <div id="legend"><h3>Status: </h3></div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('styles')
<style type="text/css">
    #map {
        height: 100%;
        min-height: 500px;
    }
    
    #legend {
        font-family: Arial, sans-serif;
        background: rgba(255,255,255,0.8);
        padding: 10px;
        margin: 10px;
        border: 2px solid #f3f3f3;
    }

    #legend h3 {
        margin-top: 0;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
    }

    #legend img {
        vertical-align: middle;
        margin-bottom: 5px;
    }
</style>
@endsection

@section('scripts')
<script>
    var map;
    var users;
    var providers;
    var ajaxMarkers = [];
    var googleMarkers = [];
    var mapIcons = {
        user: '{{ asset("asset/img/marker-user.png") }}',
        active: '{{ asset("asset/img/marker-car.png") }}',
        riding: '{{ asset("asset/img/marker-car.png") }}',
        offline: '{{ asset("asset/img/marker-home.png") }}',
        unactivated: '{{ asset("asset/img/marker-plus.png") }}'
    }

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 0, lng: 0},
            zoom: 4,
            minZoom: 1
        });

        setInterval(ajaxMapData, 1000);

        var legend = document.getElementById('legend');

        var div = document.createElement('div');
        div.innerHTML = '<img src="' + mapIcons['user'] +  '"> ' + 'Passageiro';
        legend.appendChild(div);

        var div = document.createElement('div');
        div.innerHTML = '<img src="' + mapIcons['offline'] + '"> ' + 'Motorista em Descanso';
        legend.appendChild(div);
        
        var div = document.createElement('div');
        div.innerHTML = '<img src="' + mapIcons['active'] + '"> ' + 'Motorista Disponível';
        legend.appendChild(div);
        
        var div = document.createElement('div');
        div.innerHTML = '<img src="' + mapIcons['unactivated'] + '"> ' + 'Motorista Inativo';
        legend.appendChild(div);
        map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);
        
        google.maps.Map.prototype.clearOverlays = function() {
            for (var i = 0; i < googleMarkers.length; i++ ) {
                googleMarkers[i].setMap(null);
            }
            googleMarkers.length = 0;
        }

    }

    function ajaxMapData() {
        map.clearOverlays();
        $.ajax({
            url: '/admin/map/ajax',
            dataType: "JSON",
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            type: "GET",
            success: function(data) {
                console.log('Ajax Response', data);
                ajaxMarkers = data;
            }
        });

        ajaxMarkers ? ajaxMarkers.forEach(addMarkerToMap) : '';
    }

    function addMarkerToMap(element, index) {
        
        marker = new google.maps.Marker({
            position: {
                lat: element.latitude,
                lng: element.longitude
            },
            id: element.id,
            map: map,
            title: element.first_name + " " +element.last_name +""+element.mobile,
            icon : mapIcons[element.service ? element.service.status : element.status],
        });

        googleMarkers.push(marker);

        google.maps.event.addListener(marker, 'click', function() {
            window.location.href = "tel:element.mobile";
        });
    }
</script>
<script src="//maps.googleapis.com/maps/api/js?key={{ Config::get('constants.map_key') }}&libraries=places&callback=initMap" async defer></script>
@endsection