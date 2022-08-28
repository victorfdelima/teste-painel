@extends('fleet.layout.base')

@section('title', $page)

@section('content')
<style type="text/css">

</style>
<!-- //TODO ALLAN - Alterações débito na máquina e voucher -->
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h3>{{$page}}</h3>
            @if(isset($statement_for) && $statement_for =="provider")
            <p>Nome: <b>{{$Provider->first_name}} {{$Provider->last_name}}</b></p>
            <p>Telefone: <b>{{$Provider->mobile}}</b></p>
            <p>E-mail: <b>{{$Provider->email}}</b></p>
            @endif
            <div class="datemenu">
                <span>
                    <a style="cursor:pointer" id="tday" class="showdate">Hoje</a>
                    <a style="cursor:pointer" id="yday" class="showdate">Ontem</a>
                    <a style="cursor:pointer" id="cweek" class="showdate">Esta Semana</a>
                    <a style="cursor:pointer" id="pweek" class="showdate">Semana Passada</a>
                    <a style="cursor:pointer" id="cmonth" class="showdate">Este Mês</a>
                    <a style="cursor:pointer" id="pmonth" class="showdate">Mês Passado</a>
                    <a style="cursor:pointer" id="cyear" class="showdate">Este Ano</a>
                    <a style="cursor:pointer" id="pyear" class="showdate">Ano Passado</a>
                </span>
            </div>	
            <div class="clearfix" style="margin-top: 15px;">

                <form class="form-horizontal" action="{{route('admin.ride.statement.range')}}" method="GET" enctype="multipart/form-data" role="form">

                    <div class="form-group row col-md-3">
                        <label for="name" class="col-xs-1 col-form-label">De</label>
                        <div class="col-xs-8">
                            @if(isset($statement_for) && $statement_for =="provider")
                            <input type="hidden" name="provider_id" id="provider_id" value="{{$id}}">
                            @elseif(isset($statement_for) && $statement_for =="user")
                            <input type="hidden" name="user_id" id="user_id" value="{{$id}}">
                            @elseif(isset($statement_for) && $statement_for =="fleet")
                            <input type="hidden" name="fleet_id" id="fleet_id" value="{{$id}}">
                            @endif
                            <input class="form-control" type="date" name="from_date" id="from_date" required placeholder="Data de">
                        </div>
                    </div>

                    <div class="form-group row col-md-3">
                        <label for="email" class="col-xs-1 col-form-label">Até</label>
                        <div class="col-xs-8">
                            <input class="form-control" type="date" required name="to_date" id="to_date" placeholder="Data até">
                        </div>
                    </div>
                    
                    <div class="form-group row col-md-3">
                        <label for="email" class="col-xs-4 col-form-label">Status</label>
                        <div class="col-xs-8">
                            <select class="form-control" name="payment_status">
                                <option value="all">Selecione</option>
                                <option value="paid">Pago</option>
                                <option value="not_paid">Não Pago</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row col-md-3">
                        <label for="email" class="col-xs-4 col-form-label">Pagamento</label>
                        <div class="col-xs-8">
                            <select class="form-control" name="payment_mode">
                                <option value="all">Selecione</option>
                                <option value="cash">Dinheiro</option>
                                <option value="card">Cartão de Crédito</option>
                                <option value="debit_machine">Débito na Máquina</option>
                                <option value="voucher">Voucher</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row col-md-1">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </form>
            </div>

            <div style="text-align: center;padding: 20px;color: blue;font-size: 24px;">
                @if(isset($statement_for) && $statement_for =="provider")
                <p><strong>
                        <span>@lang('admin.dashboard.over_earning') : {{currency($revenue[0]->overall)}}</span>
                        <br>
                        <span>@lang('admin.dashboard.over_commission') : {{currency($revenue[0]->commission)}}</span>
                    </strong></p>
                @elseif(isset($statement_for) && $statement_for !="provider")
                <span>@lang('admin.dashboard.over_commission') : {{currency($revenue[0]->commission)}}</span>
                @endif
            </div>

            <div class="row">

                <div class="col-lg-4 col-md-6 col-xs-12">
                    <div class="box box-block bg-white tile tile-1 mb-2"  style="border-top-color: #3e70c9 !important;">
                        <div class="t-icon right"><span class="bg-danger" style="background-color: #3e70c9 !important;"></span><i class="ti-rocket"></i></div>
                        <div class="t-content">
                            <h6 class="text-uppercase mb-1">@lang('admin.dashboard.Rides')</h6>
                            <h1 class="mb-1">{{$pagination->total}}</h1>
                            <i class="fa fa-caret-up text-success mr-0-5"></i><span>Viagens iniciadas</span>
                        </div>
                    </div>
                </div>

                @if(isset($statement_for) && $statement_for !="user")
                <div class="col-lg-4 col-md-6 col-xs-12">
                    <div class="box box-block bg-white tile tile-1 mb-2" style="border-top-color: #4bcb73 !important;">
                        <div class="t-icon right"><span class="bg-success" style="background-color: #4bcb73 !important;"></span><i class="ti-money"></i></div>
                        <div class="t-content">
                            <h6 class="text-uppercase mb-1">@lang('admin.dashboard.Revenue')</h6>
                            <h1 class="mb-1">{{currency($revenue[0]->overall)}}</h1>
                            <i class="fa fa-caret-up text-success mr-0-5"></i><span>de {{$pagination->total}} viagens</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-lg-4 col-md-6 col-xs-12">
                    <div class="box box-block bg-white tile tile-1 mb-2" style="border-top-color: #4bcb73 !important;">
                        <div class="t-icon right"><span class="bg-success" style="background-color: #4bcb73 !important;"></span><i class="ti-money"></i></div>
                        <div class="t-content">
                            <h6 class="text-uppercase mb-1">@lang('admin.dashboard.total')</h6>
                            <h1 class="mb-1">{{currency($revenue[0]->overall)}}</h1>
                            <i class="fa fa-caret-up text-success mr-0-5"></i><span>de {{$pagination->total}} viagens</span>
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-lg-4 col-md-6 col-xs-12">
                    <div class="box box-block bg-white tile tile-1 mb-2" style="border-top-color: #f44236 !important;">
                        <div class="t-icon right"><span class="bg-primary" style="background-color: #f44236 !important;"></span><i class="ti-bar-chart"></i></div>
                        <div class="t-content">
                            <h6 class="text-uppercase mb-1">@lang('admin.dashboard.cancel_rides')</h6>
                            <h1 class="mb-1">{{$cancel_rides}}</h1>
                            <i class="fa fa-caret-down text-danger mr-0-5"></i><span>Viagens canceladas</span>
                        </div>
                    </div>
                </div>

                <div class="row row-md mb-2" style="padding: 15px;">
                    <div class="col-md-12">
                        <div class="box bg-white">
                            <div class="box-block clearfix">
                                <h5 class="float-xs-left">{{$listname}}</h5>
                                <div class="float-xs-right">
                                </div>
                            </div>

                            @if(count($rides) != 0)
                            <table class="table table-striped table-bordered dataTable" id="table-4">
                                <thead>
                                    <tr>
                                        <th>@lang('admin.request.Booking_ID')</th>
                                        <th>@lang('admin.request.os_id')</th>
                                        <th>@lang('admin.request.picked_up')</th>
                                        <th>@lang('admin.request.dropped')</th>
                                        @if(isset($statement_for) && $statement_for !="user")
                                        <th>@lang('admin.request.commission')</th>
                                        @endif
                                        @if(isset($statement_for) && $statement_for !="user")
                                        <th>@lang('admin.request.earned')</th>
                                        @else
                                        <th>@lang('admin.dashboard.total')</th>
                                        @endif
                                        <th>@lang('admin.request.date')</th>
                                        <th>@lang('admin.request.status')</th>
                                        <th>@lang('admin.request.Payment_Mode')</th>
                                        <th>@lang('admin.request.Payment_Status')</th>
                                        <th>@lang('admin.request.request_details')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $diff = ['-success', '-info', '-warning', '-danger']; ?>
                                    @foreach($rides as $index => $ride)
                                    <tr>
                                        <td>{{$ride->booking_id}}</td>
                                        <td>{{$ride->os_id? $ride->os_id:'N/A'}}</td>
                                        <td>
                                            @if($ride->s_address != '')
                                            {{$ride->s_address}}
                                            @else
                                            Não informado
                                            @endif
                                        </td>
                                        <td>
                                            @if($ride->d_address != '')
                                            {{$ride->d_address}}
                                            @else
                                            Não informado
                                            @endif
                                        </td>
                                        @if(isset($statement_for) && $statement_for !="user")
                                        <td>{{currency($ride->payment['provider_commission'])}}</td>
                                        @endif
                                        @if(isset($statement_for) && $statement_for !="user")
                                        <td>{{currency($ride->payment['provider_pay'])}}</td>
                                        @else
                                        <td>{{currency($ride->payment['total'])}}</td>
                                        @endif
                                        <td>
                                            <span class="text-muted">{{date('d M Y',strtotime($ride->created_at))}}</span>
                                        </td>
                                        <td>
                                            @if($ride->status == "COMPLETED")
                                            <span class="tag tag-success">CONCLUÍDA</span>
                                            @elseif($ride->status == "CANCELLED")
                                            <span class="tag tag-danger">CANCELADA</span>
                                            @else
                                            <span class="tag tag-info">{{$ride->status}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ride->payment_mode == "CASH")
                                                DINHEIRO
                                            @elseif($ride->payment_mode == "DEBIT_MACHINE")
                                                DÉBITO MÁQUINA
                                            @elseif($ride->payment_mode == "VOUCHER")
                                                VOUCHER
                                            @elseif($ride->payment_mode == "CARD")
                                                CARTÃO
                                            @else
                                                $ride->payment_mode
                                            @endif
                                        </td>
                                        <td>
                                            @if($ride->paid)
                                                Pago
                                            @else
                                                Não Pago
                                            @endif
                                        </td>
                                        <td>
                                            <a class="text-primary" href="{{route('fleet.requests.show',$ride->id)}}"><span class="underline">Ver detalhes</span></a>									
                                        </td>
                                    </tr>
                                    @endforeach

                                <tfoot>
                                    <tr>
                                        <th>@lang('admin.request.Booking_ID')</th>
                                        <th>@lang('admin.request.os_id')</th>
                                        <th>@lang('admin.request.picked_up')</th>
                                        <th>@lang('admin.request.dropped')</th>
                                        @if(isset($statement_for) && $statement_for !="user")
                                        <th>@lang('admin.request.commission')</th>
                                        @endif
                                        @if(isset($statement_for) && $statement_for !="user")
                                        <th>@lang('admin.request.earned')</th>
                                        @else
                                        <th>@lang('admin.dashboard.total')</th>
                                        @endif
                                        <th>@lang('admin.request.date')</th>
                                        <th>@lang('admin.request.status')</th>
                                        <th>@lang('admin.request.Payment_Mode')</th>
                                        <th>@lang('admin.request.Payment_Status')</th>
                                        <th>@lang('admin.request.request_details')</th>
                                    </tr>
                                </tfoot>
                            </table>
                            @include('common.pagination')
                            @else
                            <h6 class="no-result">Não existem registros</h6>
                            @endif 

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    $(".showdate").on('click', function () {
        var ddattr = $(this).attr('id');
        //console.log(ddattr);
        if (ddattr == 'tday') {
            $("#from_date").val('{{$dates["today"]}}');
            $("#to_date").val('{{$dates["today"]}}');
        } else if (ddattr == 'yday') {
            $("#from_date").val('{{$dates["yesterday"]}}');
            $("#to_date").val('{{$dates["yesterday"]}}');
        } else if (ddattr == 'cweek') {
            $("#from_date").val('{{$dates["cur_week_start"]}}');
            $("#to_date").val('{{$dates["cur_week_end"]}}');
        } else if (ddattr == 'pweek') {
            $("#from_date").val('{{$dates["pre_week_start"]}}');
            $("#to_date").val('{{$dates["pre_week_end"]}}');
        } else if (ddattr == 'cmonth') {
            $("#from_date").val('{{$dates["cur_month_start"]}}');
            $("#to_date").val('{{$dates["cur_month_end"]}}');
        } else if (ddattr == 'pmonth') {
            $("#from_date").val('{{$dates["pre_month_start"]}}');
            $("#to_date").val('{{$dates["pre_month_end"]}}');
        } else if (ddattr == 'pyear') {
            $("#from_date").val('{{$dates["pre_year_start"]}}');
            $("#to_date").val('{{$dates["pre_year_end"]}}');
        } else if (ddattr == 'cyear') {
            $("#from_date").val('{{$dates["cur_year_start"]}}');
            $("#to_date").val('{{$dates["cur_year_end"]}}');
        } else {
            alert('invalid dates');
        }
    });
</script>
@endsection