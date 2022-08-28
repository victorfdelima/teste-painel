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

      @if($services instanceof Illuminate\Database\Eloquent\Collection)
      <div class="col-md-6">
        <div class="text-right col-12">
          <button class="btn btn-sm fare-btn" style="background-color: #ff5e00; border-radius: 40px;" onclick="adicionarParadaInput()"> @lang('user.ride.add_stop') <i class="fa fa-plus-circle"></i></button>
        </div>
        <form action="{{url('confirm/ride')}}" method="GET" onkeypress="return disableEnterKey(event);">
          <div class="input-group dash-form">
            <input type="text" class="form-control" id="origin-input" name="s_address" placeholder="Local de partida">
          </div>
          <?php foreach ([1, 2, 3, 4, 5] as $key => $pa) : ?>
            <div class="input-group dash-form paradas hide" id="parada-div-{{$pa}}">
              <input type="text" class="form-control " id="parada-input-{{$pa}}" name="paradas[{{$pa}}][descricao]" placeholder="{{$pa}}º parada ">
              <input type="text" class="form-control form-control-sm" id="parada-input-{{$pa}}" name="paradas[{{$pa}}][cliente]" placeholder="Cliente ">
            </div>
          <?php endforeach ?>
          <div class="input-group dash-form">
            <input type="text" class="form-control" id="destination-input" name="d_address" placeholder="Local de chegada">

            <input type="text" style="height: 40px;" class="form-control form-control-sm" id="cliente-input" name="cliente_volta" placeholder="Cliente ou voltar para o estabelecimento">

          </div>

          <input type="hidden" name="s_latitude" id="origin_latitude">
          <input type="hidden" name="s_longitude" id="origin_longitude">
          <input type="hidden" name="d_latitude" id="destination_latitude">
          <input type="hidden" name="d_longitude" id="destination_longitude">

          <?php foreach ([1, 2, 3, 4, 5] as $key => $pa) : ?>
            <input type="hidden" name="paradas[{{$pa}}][latitude]" id="pp_lat_{{$pa}}">
            <input type="hidden" name="paradas[{{$pa}}][longitude]" id="pp_long_{{$pa}}">
          <?php endforeach ?>

          <input type="hidden" name="current_longitude" id="long">
          <input type="hidden" name="current_latitude" id="lat">


          <div class="car-detail w-100" style="direction: ltr !important;">
            @foreach($services as $service)
            <div class="col-6 col-md-3">
              <div class="car-radio">
                <input type="radio" name="service_type" value="{{$service->id}}" id="service_{{$service->id}}" @if ($loop->first) @endif>
                <label for="service_{{$service->id}}">
                  <div class="car-radio-inner" style="height: 10px;">
                    <div class="img"><img src="{{image($service->image)}}"></div>
                    <div class="name"><span>{{$service->name}}
                        <p style="font-size: 10px; color:#ffff">(1-{{$service->capacity}})</p>
                      </span>
                    </div>
                  </div>
                </label>
              </div>
            </div>
            @endforeach

          </div>
          <div class="input-group dash-form" id="hours">
            <input type="number" class="form-control" id="rental_hours" name="rental_hours" placeholder="(Horas de aluguel) Quantas horas?">
          </div>
          <button type="submit" class="full-primary-btn fare-btn" style="background-color: #ff5e00; border-radius: 40px;">@lang('user.ride.ride_now')</button>


        </form>

      </div>
      @else
      <div class="alert-wrapper col-6">
        <div class="alert alert-warning">
          Pedimos desculpas, mas não temos serviços disponíveis para sua região.
        </div>
      </div>
      @endif
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
  $(".drp1").hide();
  $("#drplocat").click(function() {
    $(".drplocat").hide();
    $(".drp1").show()
  });


  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(success, fail);
  } else {
    console.log('Desculpe, seu navegador não suporta serviços de geolocalização');
    initMap();
  }

  function success(position) {
    document.getElementById('long').value = position.coords.longitude;
    document.getElementById('lat').value = position.coords.latitude

    if (position.coords.longitude != "" && position.coords.latitude != "") {
      current_longitude = position.coords.longitude;
      current_latitude = position.coords.latitude;
    }
    initMap();
  }

  function fail() {
    // Could not obtain location
    console.log('incapaz de obter a sua localização');
    initMap();
  }
</script>


<script type="text/javascript" src="{{ asset('asset/js/map.js') }}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ Config::get('constants.map_key') }}&libraries=places&callback=initMap" defer="" async=""></script>

<script type="text/javascript">
  function disableEnterKey(e) {
    var key;
    if (window.e)
      key = window.e.keyCode; // IE
    else
      key = e.which; // Firefox

    if (key == 13)
      return e.preventDefault();
  }
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#hours").hide();

    $('input[name=service_type]').change(function() {

      var id = $('input[name=service_type]:checked').val();

      $.ajax({
        url: "{{ url('hour') }}/" + id,
        dataType: "json",
        success: function(data) {
          //console.log(data['calculator']);

          /*if (data['calculator'] == 'DISTANCEHOUR')
          $("#hours").show();  
          else
            $("#hours").hide(); */
        }
      });
    });
  });

  setInterval("checkstatus()", 3000);

  function checkstatus() {
    $.ajax({
      url: '/user/incoming',
      dataType: "JSON",
      data: '',
      type: "GET",
      success: function(data) {
        if (data.status == 1) {
          window.location.replace("/dashboard");
        }
      }
    });
  }
</script>

@endsection