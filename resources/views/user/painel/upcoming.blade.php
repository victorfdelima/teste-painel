@extends('user.components.template')

@section('title', 'Entregas Agendadas ')
<!----------------------------Conteúdo------------------------------------------>

@section('content')
<div class="container-scroller">
    @include('user.components.navbar')
    <div class="container-fluid page-body-wrapper">
        @include('user.components.chat_entregador')
        <div id="right-sidebar" class="settings-panel">
            @include('user.components.chat')
        </div>
        @include('user.components.sidebar')
        <div class="main-panel">
        <div class="content-wrapper container shadow-sm rounded-sm ">
        <div class="row no-margin">
            <div class="col-md-12">
            <h3  class="font-weight-bold">Entregas Agendadas</h3>
            </div>
        </div>

        <div class="row no-margin ride-detail">
            <div class="col-md-12">
            @if($trips->count() > 0)

                <table class="table table-condensed" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>@lang('user.booking_id')</th>
                            <th>@lang('user.schedule_date')</th>
                            <th>@lang('user.type')</th>
                            <th>@lang('user.payment')</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($trips as $trip)

                        <tr data-toggle="collapse" data-target="#trip_{{$trip->id}}" class="accordion-toggle collapsed">
                            <td><span class="arrow-icon fa fa-chevron-right"></span></td>
                            <td>{{$trip->booking_id}}</td>
                            <td>{{date('d-m-Y H:i:s',strtotime($trip->schedule_at))}}</td>
                            @if($trip->service_type)
                                 <td>{{$trip->service_type->name}}</td>
                            @else
                                <td>-</td>
                            @endif
                            <td>@lang('user.paid_via') {{$trip->payment_mode}}</td>
                        </tr>
                        <tr class="hiddenRow">
                            <td colspan="6">
                                <div class="accordian-body collapse row" id="trip_{{$trip->id}}">
                                    <div class="col-md-6">
                                        <div class="my-trip-left">
                                        <?php 
                                    $map_icon = asset('asset/img/marker-start.png');
                                    $static_map = "https://maps.googleapis.com/maps/api/staticmap?autoscale=1&size=600x450&maptype=terrain&format=png&visual_refresh=true&markers=icon:".$map_icon."%7C".$trip->s_latitude.",".$trip->s_longitude."&markers=icon:".$map_icon."%7C".$trip->d_latitude.",".$trip->d_longitude."&path=color:0x191919|weight:8|enc:".$trip->route_key."&key=".Config::get('constants.map_key'); ?>
                                            <div class="map-static" style="background-image: url({{$static_map}});">
                                                
                                            </div>
                                            <div class="from-to row no-margin">
                                                <div class="from">
                                                    <h5>@lang('De')</h5>
                                                    <p>{{$trip->s_address}}</p>
                                                </div>
                                                <div class="to">
                                                    <h5>@lang('user.to')</h5>
                                                    <p>{{$trip->d_address}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                        <div class="mytrip-right">
                                             <h5>@lang('user.provider_details')</h5>
                                             <div class="trip-user">
                                                <div class="user-img" style="background-image: url({{img($trip->provider->avatar)}});">
                                                </div>
                                                <div class="user-right">
                                                @if($trip->provider)
                                                    <h5>{{$trip->provider->first_name}} {{$trip->provider->last_name}}</h5>
                                                @endif
                                                    <p>{{$trip->status}}</p>
                                                </div>
                                            </div>

                                            <div class="fare-break">

                                               <form method="POST" action="{{url('cancel/ride')}}">
                                                  {{ csrf_field() }}
                                                     <input type="hidden" name="request_id" value="{{$trip->id}}" />
                                                   <button class="full-primary-btn fare-btn" style="background-color: #ff5e00; border-radius: 40px;" type="submit">@lang('user.ride_cancel')</button>
                                               </form>

                                            </div>


                                        </div>

                                    </div>

                                </div>
                            </td>
                        </tr>

                        @endforeach


                    </tbody>
                </table>
                @else
                    <hr>
                    <p style="text-align: center;">@lang('Nenhuma Entrega agendada')</p>
                @endif
            </div>
        </div>
        </div></div>
    </div>
</div>
@endsection