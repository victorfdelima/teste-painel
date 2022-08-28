@extends('admin.layout.base')

@section('title', 'Adicionar Cupom Promocional ')

@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('asset/css/bootstrap-datetimepicker.min.css')}}">

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{ URL::previous() }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.back')</a>

            <h5 style="margin-bottom: 2em;">@lang('admin.promocode.add_promocode')</h5>
            
            <p style="color: #cc0033;"><b>Nota:</b> Cada usuário poderá usar este cupom uma vez até a data de expiração.</p>
            <br>

            <form class="form-horizontal" action="{{route('admin.promocode.store')}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}

                <div class="form-group row">
                    <label for="promo_code" class="col-xs-2 col-form-label">Franquia</label>
                    <div class="col-xs-10">
                        <select name="fleet_id" class="form-control" required>
                            <option value="">Selecione a franquia</option>
                            @foreach($fleets as $fleet)
                                <option value="{{ $fleet->id }}">{{ $fleet->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="promo_code" class="col-xs-2 col-form-label">@lang('admin.promocode.promocode')</label>
                    <div class="col-xs-10">
                        <input class="form-control" autocomplete="off"  type="text" value="{{ old('promo_code') }}" name="promo_code" required id="promo_code" placeholder="@lang('admin.promocode.promocode')">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="percentage" class="col-xs-2 col-form-label">@lang('admin.promocode.percentage')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="number" value="{{ old('percentage') }}" name="percentage" required id="percentage" placeholder="@lang('admin.promocode.percentage')">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="max_amount" class="col-xs-2 col-form-label">@lang('admin.promocode.max_amount')</label>
                    <div class="col-xs-10">
                        <input type="number" class="form-control" name="max_amount" required id="max_amount" placeholder="@lang('admin.promocode.max_amount')" value="{{old('max_amount')}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="expiration" class="col-xs-2 col-form-label">@lang('admin.promocode.expiration')</label>
                    <div class="col-xs-10">
                        <input class="form-control datetimepicker" type="text" value="{{old('expiration')}}" name="expiration" required id="expiration" placeholder="@lang('admin.promocode.expiration')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="promo_description" class="col-xs-2 col-form-label">@lang('admin.promocode.promo_description')</label>
                    <div class="col-xs-10">
                        <textarea id="promo_description" placeholder="Description" class="form-control" name="promo_description">0% off, Máximo desconto de 0{{old('promo_description')}}</textarea>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="zipcode" class="col-xs-2 col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-primary">@lang('admin.promocode.add_promocode')</button>
                        <a href="{{route('admin.promocode.index')}}" class="btn btn-default">@lang('admin.cancel')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@section('scripts')
<script type="text/javascript" src="{{asset('asset/js/bootstrap-datetimepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('asset/js/moment-with-locales.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function () {
    //your code here
    $(function () {
        $('.datetimepicker').datetimepicker({defaultDate: moment(), minDate: moment(), format: 'YYYY-MM-DD HH:mm'});
    });
});

$("#percentage").on('keyup', function () {
    var per = $(this).val() || 0;
    var max = $("#max_amount").val() || 0;
    $("#promo_description").val(per + '% off! Valor máximo de desconto R$' + max);
});

$("#max_amount").on('keyup', function () {
    var max = $(this).val() || 0;
    var per = $("#percentage").val() || 0;
    $("#promo_description").val(per + '% off! Valor máximo de desconto R$' + max);
});


</script>
@endsection
