<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Bem Vindo {{ Auth::user()->first_name }}! </h3>
                <h6 class="font-weight-normal mb-0">Abaixo você verifica a ultima entrega em progresso <span class="text-primary"></span></h6>
            </div>
            <div class="col-12 col-xl-4">
                <div class="row justify-content-end d-flex">
                    <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                        <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="mdi mdi-calendar"></i> Classificar por
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                            <a class="dropdown-item" href="#">1 Dia</a>
                            <a class="dropdown-item" href="#">7 Dias</a>
                            <a class="dropdown-item" href="#">30 Dias</a>
                            <a class="dropdown-item" href="#">6 Meses</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card tale-bg">
            <div class="card-people mt-auto" id="map">
                <div id="legend">
                    <h3>Status: </h3>
                </div>
                <div class="weather-info">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin transparent">
        @if($flag=="painel" || $flag=='historico')
        <div class="row">
            <div class="col-md-6 mb-4 stretch-card transparent">
                <div class="card card-tale">
                    <div class="card-body">
                        <p class="mb-4">Total de Entregas</p>
                        <p class="fs-30 mb-2">{{$panelData['totalEntregas']}}</p>
                        <p>R$ {{number_format($viagem, 2,",",".")}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4 stretch-card transparent">
                <div class="card card-dark-blue">
                    <div class="card-body">
                        <p class="mb-4">Valores em Entregas</p>
                        <p class="fs-30 mb-2">R$ {{number_format($panelData['total'], 2,",",".")}}</p>
                        <p>R$ {{number_format($panelData['viagem'], 2,",",".")}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                <div class="card card-light-blue">
                    <div class="card-body">
                        <p class="mb-4">Total de Redução</p>
                        <p class="fs-30 mb-2">R$ {{number_format($panelData['totalReducao'], 2,",",".")}}</p>
                        <p>Período de 30 dias</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 stretch-card transparent">
                <div class="card card-light-danger">
                    <div class="card-body">
                        <p class="mb-4">Entregas Canceladas</p>
                        <p class="fs-30 mb-2">{{$panelData['cancelados']}}</p>
                        <p>Período de 30 dias</p>
                    </div>
                </div>
            </div>
            <input type="hidden" name="current_longitude" id="long">
            <input type="hidden" name="current_latitude" id="lat">
        </div>
        @elseif($flag=="entrega")
        <!--@include('common.notify')-->
        <div class="row justify-content-center">
            <div class="col-md-10  ">

                <div class="text-right col-12">
                    <form id="vue__application" action="{{url('confirm/ride')}}" method="GET">
                        @if($services instanceof Illuminate\Database\Eloquent\Collection && sizeof($services))
                        <button class="btn btn-sm rounded-sm fare-btn mb-4" style="background-color: #ff5e00;  color:white; margin-right:25px; " @click="addStop" type="button">
                            <b> @lang('user.ride.add_stop')</b>
                            <i class="fa fa-plus-circle"></i>
                        </button>
                        <div class="row justify-content-center">
                            <div class="col-md-11">
                                <div class="input-group dash-form">
                                    <input type="text" class="form-control" id="origin-input" name="s_address" placeholder="Local de partida">
                                </div>
                            </div>
                        </div>
                        <!-- //foreach ([1, 2, 3, 4, 5] as $key => $pa) -->
                        <div class="row justify-content-center" style="margin-top: 2px;">
                            <div class="col-md-12 px-4 my-2">
                                <div hidden ref="stopsForm">
                                    <div class="input-group dash-form paradas position-relative" :id="`parada-div-${index}`" v-for="(stop, index) in stops" style=" margin-top:20px;">
                                        <div class="col-md-12 d-flex justify-content-center flex-column align-items-center ">
                                            <input type="text" class="form-control my-1" :id="`parada-input-${index + 1}`" :name="`paradas[${index + 1}][descricao]`" :placeholder="`${index + 1}º parada` "><br />
                                            <input type="text" class="form-control my-1" :name="`paradas[${index + 1}][cliente]`" placeholder="Cliente">
                                            <div class="position-absolute trash-icon">
                                                <button type="button" class="btn btn-sm p-1 rounded-0 btn-warning" @click="removeStop(index)">
                                                    <i class="ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-11">
                                <div class="input-group dash-form">
                                    <input type="text" class="form-control" id="destination-input" name="s_address" placeholder="Local de chegada">
                                </div>
                            </div>
                        </div>
                        <!-- //endforeach -->

                        <div class="row justify-content-center" style="margin-top: 2px;">
                            <div class="col-md-11">
                                <input type="text" class="form-control" id="cliente-input" name="cliente_volta" placeholder="Cliente ou voltar para o estabelecimento">
                            </div>
                        </div>
                        <input type="hidden" name="s_latitude" id="origin_latitude">
                        <input type="hidden" name="s_longitude" id="origin_longitude">
                        <input type="hidden" name="d_latitude" id="destination_latitude">
                        <input type="hidden" name="d_longitude" id="destination_longitude">

                        <?php foreach ([1, 2, 3, 4, 5] as $key => $pa) : ?>
                            <input type="hidden" name="paradas[{{$pa}}][latitude]" id="pp_lat_{{$pa}}">
                            <input type="hidden" name="paradas[{{$pa}}][longitude]" id="pp_long_{{$pa}}">
                        <?php endforeach ?>

                        <div class="car-detail w-100 d-flex justify-content-center" style="margin-top:10px;">
                            @foreach($services as $service)
                            <div class="col-6 col-md-3 ">
                                <div class="car-radio">
                                    <input type="radio" name="service_type" value="{{$service->id}}" id="service_{{$service->id}}" @if ($loop->first) @endif>
                                    <label for="service_{{$service->id}}">
                                        <div class="car-radio-inner" style="height: 20px;">
                                            <div class="img"><img src="{{image($service->image)}}" style="width: 100px;"></div>
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

                        <!--<div class="input-group dash-form " id="hours">
                            <div class="row justify-content-center" style="margin-top: 2px; margin-left:0px;">
                                <div class="col-md-11">
                                    <input type="number" class="form-control" id="rental_hours" name="rental_hours" placeholder="(Horas de aluguel) Quantas horas?">
                                </div>
                            </div>
                        </div>-->

                        <button type="submit" class="btn btn-md mt-2 rounded" style="background-color: #ff5e00; border-radius: 40px; color:white; float:left; margin-left:301px;"><b>@lang('user.ride.ride_now')</b></button>

                        @else
                        <div class="alert-wrapper col-6">
                            <div class="alert alert-warning">
                                Pedimos desculpas, mas não temos serviços disponíveis para sua região.
                            </div>
                        </div>
                        @endif

                        <input type="hidden" name="current_longitude" id="long">
                        <input type="hidden" name="current_latitude" id="lat">

                    </form>
                </div>
            </div>
            <!--<div class="col-md-6">
                    <div class="map-responsive">
                        <div id="map" style="width: 100%; height: 450px;"></div>
                    </div>
                </div>-->
        </div>


        @elseif($flag = "cancelamento")

        @endif
    </div>
</div>
<script defer src="https://unpkg.com/vue"></script>
<script defer src="{{ asset('asset/vue/request-service.js') }}"></script>

<style>
    .trash-icon {
        right: -20px;
    }
</style>