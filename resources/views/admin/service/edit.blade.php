@extends('admin.layout.base')

@section('title', 'Atualizar Tipo de Serviço ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{ URL::previous() }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.back')</a>

            <h5 style="margin-bottom: 2em;">@lang('admin.service.Update_Service_Type')</h5>

            <form class="form-horizontal" action="{{route('admin.service.update', $service->id )}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <input type="hidden" name="_method" value="PATCH">

                <div class="form-group row">
                    <label for="promo_code" class="col-xs-2 col-form-label">Franquia</label>
                    <div class="col-xs-10">
                        <select name="fleet_id" class="form-control" required>
                            <option value="">Selecione a franquia</option>
                            @foreach($fleets as $fleet)
                                <option value="{{ $fleet->id }}"{{ !empty($service->fleet_id) && $fleet->id==$service->fleet_id?'selected':'' }}>{{ $fleet->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-xs-2 col-form-label">@lang('admin.service.Service_Name')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $service->name }}" name="name" required id="name" placeholder="@lang('admin.service.Service_Name')">
                    </div>
                </div>

                <div class="form-group row">

                    <label for="image" class="col-xs-2 col-form-label">@lang('admin.picture')</label>
                    <div class="col-xs-10">
                        @if(isset($service->image))
                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{ $service->image }}">
                        @endif
                        <input type="file" accept="image/*" name="image" class="dropify form-control-file" id="image" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">

                    <label for="marker" class="col-xs-2 col-form-label">@lang('admin.service.Service_marker_Image')</label>
                    <div class="col-xs-10">
                        @if(isset($service->marker))
                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{ $service->marker }}">
                        @endif
                        <input type="file" accept="image/*" name="marker" class="dropify form-control-file" id="marker" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="calculator" class="col-xs-2 col-form-label">@lang('admin.service.Pricing_Logic')</label>
                    <div class="col-xs-5">
                        <select class="form-control" id="calculator" name="calculator">
                            <option value="MIN" @if($service->calculator =='MIN') selected @endif>@lang('servicetypes.MIN')</option>
                            <option value="HOUR" @if($service->calculator =='HOUR') selected @endif>@lang('servicetypes.HOUR')</option>
                            <option value="DISTANCE" @if($service->calculator =='DISTANCE') selected @endif>@lang('servicetypes.DISTANCE')</option>
                            <option value="DISTANCEMIN" @if($service->calculator =='DISTANCEMIN') selected @endif>@lang('servicetypes.DISTANCEMIN')</option>
                            <option value="DISTANCEHOUR" @if($service->calculator =='DISTANCEHOUR') selected @endif>@lang('servicetypes.DISTANCEHOUR')</option>
                        </select>
                    </div>
                    <div class="col-xs-5">
                        <span class="showcal"><i><b>Cálculo de Preço: <span id="changecal"></span></b></i></span>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="min_price" class="col-xs-2 col-form-label">Tarifa Mínima ({{ currency('') }})</label>
                    <div class="col-xs-5">
                        <input class="form-control" type="text" id="currency_min_price" data-thousands="." data-decimal="," value="{{ $service->min_price }}" name="min_price" required id="min_price" placeholder="Tarifa mínima" min="0">
                    </div>
                    <div class="col-xs-5">
                        <span class="showcal"><i><b>TM (Valor cobrado em viagens curtas)</b></i></span>
                    </div>
                </div>

                <div class="form-group row" >
                    <label for="fixed" class="col-xs-2 col-form-label">@lang('admin.service.hourly_Price') ({{ currency('') }})</label>
                    <div class="col-xs-5">
                        <input class="form-control" type="number" value="{{ $service->hour }}" name="hour" id="hourly_price" placeholder="Definir preço por hora ( Apenas para DISTÂNCIA POR PREÇO )" min="0">
                    </div>
                    <div class="col-xs-5">
                        <span class="showcal"><i><b>PH (@lang('admin.service.per_hour')), TH (@lang('admin.service.total_hour'))</b></i></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="fixed" class="col-xs-2 col-form-label">@lang('admin.service.Base_Price') ({{ currency('') }})</label>
                    <div class="col-xs-5">
                        <input class="form-control" type="text" id="currency_fixed" data-thousands="." data-decimal="," value="{{ $service->fixed }}" name="fixed" required id="fixed" placeholder="@lang('admin.service.Base_Price')" min="0">
                    </div>
                    <div class="col-xs-5">
                        <span class="showcal"><i><b>PB (@lang('admin.service.Base_Price'))</b></i></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="distance" class="col-xs-2 col-form-label">@lang('admin.service.Base_Distance') ({{ distance('') }})</label>
                    <div class="col-xs-5">
                        <input class="form-control" type="number" value="{{ $service->distance }}" name="distance" id="distance" placeholder="@lang('admin.service.Base_Distance')" min="0">
                    </div>
                    <div class="col-xs-5">
                        <span class="showcal"><i><b>DB (@lang('admin.service.Base_Distance')) </b></i></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="minute" class="col-xs-2 col-form-label">@lang('admin.service.unit_time') ({{ currency() }})</label>
                    <div class="col-xs-5">
                        <input class="form-control" type="text" id="currency_minute" data-thousands="." data-decimal="," value="{{ $service->minute }}" name="minute" id="minute" placeholder="@lang('admin.service.unit_time')" min="0">
                    </div>
                    <div class="col-xs-5">
                        <span class="showcal"><i><b>PM (@lang('admin.service.per_minute')), TM(@lang('admin.service.total_minute'))</b></i></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="price" class="col-xs-2 col-form-label">@lang('admin.service.unit') ({{ distance() }})</label>
                    <div class="col-xs-5">
                        <input class="form-control" type="text" id="currency_price" data-thousands="." data-decimal="," value="{{ $service->price }}" name="price" id="price" placeholder="@lang('admin.service.unit')" min="0">
                    </div>
                    <div class="col-xs-5">
                        <span class="showcal"><i><b>P{{config('constants.distance')}} (@lang('admin.service.per') {{config('constants.distance')}}), T{{config('constants.distance')}} (@lang('admin.service.total') {{config('constants.distance')}})</b></i></span>
                    </div>
                </div>
                <!-- Capacidade -->
                <div class="form-group row">
                    <label for="capacity" class="col-xs-12 col-form-label">@lang('admin.service.Seat_Capacity')</label>
                    <div class="col-xs-5">
                        <input class="form-control" type="text" value="{{ old('capacity') }}" name="capacity" required id="capacity" placeholder="@lang('admin.service.Seat_Capacity')">
                    </div>
                </div>

                <div class="form-group row">
                     <label for="description" class="col-xs-12 col-form-label" style="color: black;font-size: 25px;">@lang('admin.service.peak_title')</label>

                     <!-- Set Peak Time -->
                    <div class="col-xs-12">
                        <table class="table table-striped table-bordered dataTable" id="table-2">
                            <thead>
                                <tr>
                                    <th>@lang('admin.service.peak_id')</th>
                                    <th>@lang('admin.service.peak_time')</th>
                                    <th>@lang('admin.service.peak_price')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Peakhour as $index => $w)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{ date('h:i A', strtotime($w->start_time)) }} - {{date('h:i A', strtotime($w->end_time))}}</td>
                                        <td> <input type="text" id="peak_price" name="peak_price[{{$w->id}}][id]" value="@if($w->servicetimes){{ $w->servicetimes->min_price }}@endif"  min="1">
                                        <input type="hidden" name="peak_price[{{$w->id}}][status]" value="@if($w->servicetimes)1 @else 0 @endif"> </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>@lang('admin.service.peak_id')</th>
                                    <th>@lang('admin.service.peak_time')</th>
                                    <th>@lang('admin.service.peak_price')</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>



                 <div class="form-group row">
                    <label for="" class="col-xs-12 col-form-label" style="color: black;font-size: 25px;">@lang('admin.service.waiting_title')</label>
                    <label for="waiting_free_mins" class="col-xs-5 col-form-label">@lang('admin.service.waiting_wave')</label>
                    <label for="waiting_min_charge" class="col-xs-5 col-form-label">@lang('admin.service.waiting_charge')</label>
                    <div class="col-xs-5">
                        <input class="form-control" type="number" value="{{ $service->waiting_free_mins }}" name="waiting_free_mins" id="waiting_free_mins" placeholder="@lang('admin.service.waiting_wave')" min="0">
                    </div>
                    <div class="col-xs-5">
                        <input class="form-control" type="number" value="{{ $service->waiting_min_charge }}" name="waiting_min_charge" id="waiting_min_charge" placeholder="@lang('admin.service.waiting_charge')" min="0">
                    </div>
                </div>
                <br>
                <div class="form-group row">
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <button type="submit" class="btn btn-primary btn-block">@lang('admin.service.Update_Service_Type')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    var cal='DISTANCE';
    priceInputs('{{$service->calculator}}');
    $("#calculator").on('change', function(){
        cal=$(this).val();
        priceInputs(cal);
    });

    function priceInputs(cal){
        if(cal=='MIN'){
            $("#hourly_price,#distance,#price").attr('value','');
            $("#minute").prop('disabled', false);
            $("#minute").prop('required', true);
            $("#hourly_price,#distance,#price").prop('disabled', true);
            $("#hourly_price,#distance,#price").prop('required', false);
            $("#changecal").text('PB + (TM*PM)');
        }
        else if(cal=='HOUR'){
            $("#minute,#distance,#price").attr('value','');
            $("#hourly_price").prop('disabled', false);
            $("#hourly_price").prop('required', true);
            $("#minute,#distance,#price").prop('disabled', true);
            $("#minute,#distance,#price").prop('required', false);
            $("#changecal").text('PB + (TH*PH)');
        }
        else if(cal=='DISTANCE'){
            $("#minute,#hourly_price").attr('value','');
            $("#price,#distance").prop('disabled', false);
            $("#price,#distance").prop('required', true);
            $("#minute,#hourly_price").prop('disabled', true);
            $("#minute,#hourly_price").prop('required', false);
            $("#changecal").text('PB + (T{{config("constants.distance")}}-DB*P{{config("constants.distance")}})');
        }
        else if(cal=='DISTANCEMIN'){
            $("#hourly_price").attr('value','');
            $("#price,#distance,#minute").prop('disabled', false);
            $("#price,#distance,#minute").prop('required', true);
            $("#hourly_price").prop('disabled', true);
            $("#hourly_price").prop('required', false);
            $("#changecal").text('PB + (T{{config("constants.distance")}}-DB*P{{config("constants.distance")}}) + (TM*PM)');
        }
        else if(cal=='DISTANCEHOUR'){
            $("#minute").attr('value','');
            $("#price,#distance,#hourly_price").prop('disabled', false);
            $("#price,#distance,#hourly_price").prop('required', true);
            $("#minute").prop('disabled', true);
            $("#minute").prop('required', false);
            $("#changecal").text('PB + ((T{{config("constants.distance")}}-DB)*P{{config("constants.distance")}}) + (TH*PH)');
        }
        else{
            $("#minute,#hourly_price").attr('value','');
            $("#price,#distance").prop('disabled', false);
            $("#price,#distance").prop('required', true);
            $("#minute,#hourly_price").prop('disabled', true);
            $("#minute,#hourly_price").prop('required', false);
            $("#changecal").text('PB + (T{{config("constants.distance")}}-DB*P{{config("constants.distance")}})');
        }
    }

</script>
@endsection
