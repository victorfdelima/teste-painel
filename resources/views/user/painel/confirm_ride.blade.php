@extends('user.components.template')

<!----------------------------Conteúdo------------------------------------------>

@section('content')

<style>
    .map-static {
        height: 300px
        
    }
</style>


<div class="container-scroller">
  @include('user.components.navbar')
  <div class="container-fluid page-body-wrapper ">
    @include('user.components.chat_entregador')
    <div id="right-sidebar" class="settings-panel">
      @include('user.components.chat')
    </div>
    @include('user.components.sidebar')
    <div class="main-panel">
      <div class="content-wrapper ">
        <div class="col-md-9 border d-flex justify-content-center  container shadow-sm rounded-sm bg-white">
          <div class="dash-content ">
            <div class="row no-margin">
              <div class="col-md-12  ">
                <h3 class="page-title font-weight-bold"style="margin-top:30px;color:#ff5e00; margin-left:190px; ">@lang('user.ride.ride_now')</h3>
              </div>
            </div>
            @include('common.notify')
            <div class="row no-margin ">
              <div class="col-md-6">
                <form action="{{url('create/ride')}}" method="POST" id="create_ride">

                  {{ csrf_field() }}
                  <dl class="dl-horizontal left-right "style="margin-top:15px;">
                    <dt style="font-weight-bold">@lang('user.type')</dt>
                    <dd>{{$service->name}}</dd>
                    <dt>@lang('user.total_distance')</dt>
                    <dd>{{distance($fare->distance)}}</dd>
                    <dt>@lang('user.eta')</dt>
                    <dd>{{$fare->time}}</dd>
                    <dt>@lang('user.estimated_fare')</dt>
                    <dd>{{currency($fare->estimated_fare)}}</dd>
                    <dt>@lang('user.promocode')</dt>
                    <dd id="promo_amount">{{currency()}}</dd>
                    <hr>
                    <dt>@lang('user.total')</dt>
                    <dd id="total_amount">{{currency($fare->estimated_fare - 0)}}</dd>
                    <hr>
                    @if(Auth::user()->wallet_balance > 0)

                    <input type="checkbox" name="use_wallet" value="1" style="margin-right:5px;"><span >@lang('user.use_wallet_balance')</span>
                    
                    <dt>@lang('user.available_wallet_balance')</dt>
                    <dd>{{currency(Auth::user()->wallet_balance)}}</dd>
                    @endif
                  </dl>

                  @if(Config::get('constants.braintree') == 1)
                  <input type="hidden" name="braintree_nonce" value="" />
                  @endif
                  <input type="hidden" name="s_address" value="{{Request::get('s_address')}}">
                  <input type="hidden" name="d_address" value="{{Request::get('d_address')}}">
                  <input type="hidden" name="s_latitude" value="{{Request::get('s_latitude')}}">
                  <input type="hidden" name="s_longitude" value="{{Request::get('s_longitude')}}">
                  <input type="hidden" name="d_latitude" value="{{Request::get('d_latitude')}}">
                  <input type="hidden" name="d_longitude" value="{{Request::get('d_longitude')}}">

                  <input type="hidden" name="staticmap" value="{{Request::get('staticmap')}}">

                  <input type="hidden" name="cliente_volta" value="{{Request::get('cliente_volta')}}">

                  <input type="hidden" name="paradas" value="{{Request::get('paradasText')}}">
                  <input type="hidden" name="service_type" value="{{Request::get('service_type')}}">
                  <input type="hidden" name="distance" value="{{$fare->distance}}">
                  @if(Request::get('rental_hours') != '')
                  <input type="hidden" name="rental_hours" value="{{Request::get('rental_hours')}}">
                  @endif
                  <div class="cod_promocional font-weight-bold"style="margin-left:10px;margin-botton:20px;">
                  <p>@lang('user.promocode')</p>
                  </div>
                  <select class="form-select" name="promocode_id" id="promocode"style="margin-left:10px;">
                    <option value=""  data-percent="0" data-max="0">@lang('user.promocode_select')</option>
                    @foreach($promolist as $promo)
                    <option value="{{$promo->id}}" data-percent="{{$promo->percentage}}" data-max="{{$promo->max_amount}}">{{$promo->promo_code}}</option>
                    @endforeach
                  </select>
                 
                  <div class=" font-weight-bold"style="margin-left:10px; margin-top:5px;">
                  <p>@lang('user.payment_method')</p>
                  </div>
                  <select class="form-select" style="margin-left:10px; margin-bottom:13px; name="payment_mode" id="payment_mode" onchange="card(this.value);">
                    @if(Config::get('constants.cash') == 1)
                    <option value="CASH">DINHEIRO</option>
                    @endif
                    @if(Config::get('constants.contract') == 1)
                    <option value="CONTRACT">CONTRATO</option>
                    @endif
                    @if(Config::get('constants.card') == 1)
                    @if($cards->count() > 0)
                    <option value="CARD">CARTÃO</option>
                    @endif
                    @if(Config::get('constants.braintree') == 1)
                    <option value="BRAINTREE">BRAINTREE</option>
                    @endif
                    @endif
                    @if(Config::get('constants.payumoney') == 1)
                    <option value="PAYUMONEY">PAYUMONEY</option>
                    @endif
                    @if(Config::get('constants.paypal') == 1)
                    <option value="PAYPAL">PAYPAL</option>
                    @endif
                    @if(Config::get('constants.paypal_adaptive') == 1)
                    <option value="PAYPAL-ADAPTIVE">PAYPAL-ADAPTIVE</option>
                    @endif
                    @if(Config::get('constants.paytm') == 1)
                    <option value="PAYTM">PAYTM</option>
                    @endif
                  </select>
                  

                  @if(Config::get('constants.card') == 1)
                  @if($cards->count() > 0)
                  <div>
                  <select class="form-control" name="card_id" style="display: none;" id="card_id">
                    <option value="">Select Card</option>
                    
                    @foreach($cards as $card)
                    <option value="{{$card->card_id}}">{{$card->brand}} **** **** **** {{$card->last_four}}</option>
                    @endforeach
                  </select>
                  @endif
                  @endif

                  @if(Config::get('constants.braintree') == 1)
                  <div style="display: none;" id="braintree">
                    <div id="dropin-container"></div>
                  </div>
                  @endif

                  @if($fare->surge == 1)

                  <span><em>@lang('user.demand_node')</em></span>
                  <div class="surge-block" style="background-color: red;">
                    <span class="surge-text">{{$fare->surge_value}}</span>
                  </div>

                  @endif
                  <div class="container btn btn-sm rounded "style="background-color:#ff5e00; margin-top:20px; margin-right:50px; margin-bottom:10px; margin-left:5px; color:white; bold ">
                  <button type="submit" id="submit-button" class="btn btn-sm" >@lang('user.ride.ride_now')</button>
                  <!--<button type="button" class="half-secondary-btn fare-btn" data-toggle="modal" data-target="#schedule_modal">@lang('user.schedule')</button>!-->
                  </div>
                </form>
              </div>
              <?php
              $paradas = [];
              if ($request->paradas != null)
                $paradas = unserialize($request->paradasText);
              ?>
              <div class="col-md-6" style="margin-top: 15px;">
                <div class="user-request-map">
                  <div class="map-static" style="background-image: url({{$staticmap}});"></div>
                  <div class="from-to row no-margin">
                    <div class="from "style="margin-top:30px; margin-left:20px;">
                      <h5>@lang('De:')</h5>
                      <p>{{$request->s_address}}</p>
                    </div>
                    <?php foreach ($paradas as $key => $parada) : ?>
                      <div class="from">
                        <h5 style="text-transform: capitalize;">{{$key+1}}º @lang('parada')</h5>
                        <p>{{$parada['descricao']}} - <small>Cliente: {{@$parada['cliente']}}</small> </p>
                      </div>
                    <?php endforeach ?>
                    <div class="to "style="margin-top:30px; margin-left:50px;">
                      <h5>Para:</h5>
                      <p>{{$request->d_address}} - <small>Cliente: {{$request['cliente_volta']}}</small></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>



      <!-- Schedule Modal -->
      <div id="schedule_modal" class="modal fade schedule-modal" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">@lang('user.schedule_title')</h4>
            </div>
            <form>
              <div class="modal-body">

                <label>@lang('user.schedule_date')</label>
                <input value="{{date('m/d/Y')}}" type="text" id="datepicker" placeholder="Date" name="schedule_date">
                <label>@lang('user.schedule_time')</label>
                <input value="{{date('H:i')}}" type="text" id="timepicker" placeholder="Time" name="schedule_time">

              </div>
              <div class="modal-footer">
                <button type="button" id="schedule_button" class="btn btn-default" data-dismiss="modal">@lang('user.schedule_ride')</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('user.components.footer')
</div>
@endsection

<script>
  $(document).ready(function(e) {

    $.ajax({
      url: "https://maps.googleapis.com/maps/api/directions/json?origin={{$request->s_latitude}},{{$request->s_longitude}}&destination={{$request->d_latitude}},{{$request->d_longitude}}&mode=driving&key={{config('constants.map_key')}}",
      type: "GET",
      dataType: 'jsonp',
      cache: false,
      success: function(response) {
        alert("S");
        console.log(response.routes[0].overview_polyline.points);
      }
    });
  });
</script>

@if(Config::get('constants.braintree') == 1)
<script src="https://js.braintreegateway.com/web/dropin/1.14.1/js/dropin.min.js"></script>

<script>
  var button = document.querySelector('#submit-button');
  var form = document.querySelector('#create_ride');
  braintree.dropin.create({
    authorization: '{{$clientToken}}',
    container: '#dropin-container',
    //Here you can hide paypal
    paypal: {
      flow: 'vault'
    }
  }, function(createErr, instance) {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      if (document.querySelector('select[name="payment_mode"]').value == "BRAINTREE") {
        instance.requestPaymentMethod(function(requestPaymentMethodErr, payload) {
          document.querySelector('input[name="braintree_nonce"]').value = payload.nonce;
          console.log(payload.nonce);
          form.submit();
        });
      } else {
        form.submit();
      }
    });
  });
</script>

@endif


<script type="text/javascript">
  $(document).ready(function() {
    $('#schedule_button').click(function() {
      $("#datepicker").clone().attr('type', 'hidden').appendTo($('#create_ride'));
      $("#timepicker").clone().attr('type', 'hidden').appendTo($('#create_ride'));
      document.getElementById('create_ride').submit();
    });
  });
</script>
<script type="text/javascript">
  var date = new Date();
  date.setDate(date.getDate());
  $('#datepicker').datepicker({
    startDate: date
  });
  $('#timepicker').timepicker({
    showMeridian: false
  });
</script>
<script type="text/javascript">
  @if(Config::get('constants.cash') == 0)
  card('CARD');
  @endif

  function card(value) {
    $('#card_id, #braintree').fadeOut(300);
    if (value == 'CARD') {
      $('#card_id').fadeIn(300);
    } else if (value == 'BRAINTREE') {
      $('#braintree').fadeIn(300);
    }
  }

  $('#promocode').on('change', function() {

    var estimate = {
      {
        $fare - > estimated_fare
      }
    };
    var percentage = $('option:selected', this).attr('data-percent');
    var max_amount = $('option:selected', this).attr('data-max');
    var percent_total = estimate * percentage / 100;
    if (percent_total > max_amount) {
      promo = parseFloat(max_amount);
    } else {
      promo = parseFloat(percent_total);
    }
    $("#promo_amount").html("{{config('constants.currency')}}" + promo.toFixed(2));
    $("#total_amount").html("{{config('constants.currency')}}" + (estimate - promo).toFixed(2));
  });
</script>