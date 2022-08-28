@extends('user.layout.auth')

@section('content')

<?php $login_user = asset('asset/img/loginbg.png'); ?>
<div class="full-page-bg" style="background-image: url({{$login_user}});">
    <div class="log-overlay"></div>
    <div class="full-page-bg-inner">
        <div class="row no-margin">
            <div class="col-md-6 log-left">
                <span class=""><img src="https://aplicativo.vamo.app.br/wp-content/uploads/2021/05/logoV-1-50x50.png"></a></span>
                <h2>Crie sua conta e entregue em minutos</h2>
                <p>Bem-vindo(a) ao {{config('constants.site_title','Vamo')}}, a maneira mais fácil de entregar algo.</p>
            </div>
            <div class="col-md-6 log-right">
                <div class="login-box-outer">
                    <div id="vue__application" class="login-box row no-margin" style="background-color:#fff; border-radius: 40px;">
                        <div class="col-md-12">
                            <a class="log-blk-btn" style="background-color:#ff5e00; border-radius: 40px;" href="{{url('login')}}">JÁ TEM UMA CONTA?</a>
                            <h3>Criar um Conta</h3>
                        </div>
                        <form ref="registrationForm" role="form" method="POST" action="{{ url('/register') }}">

                            {{ csrf_field() }}

                            <div id="second_step">
                                <input value="+55" type="hidden" id="country_code" name="country_code" />

                                <div class="col-md-6">
                                    <input type="text" autofocus class="form-control" placeholder="Nome" name="first_name" value="{{ old('first_name') }}" data-validation="alphanumeric" data-validation-allowing=" -" style="border-radius: 20px" data-validation-error-msg="Primeiro nome é obrigatório" required>

                                    @if ($errors->has('first_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Sobrenome" name="last_name" value="{{ old('last_name') }}" data-validation="alphanumeric" data-validation-allowing=" -" style="border-radius: 20px" data-validation-error-msg="Sobrenome é obrigatório" required>

                                    @if ($errors->has('last_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <input type="email" class="form-control" name="email" style="border-radius: 20px" placeholder="E-mail" value="{{ old('email') }}" data-validation="email" required>

                                    @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <input type="text" id="phone_number" @keydown="validateNumber($event)" class="mask__phone form-control" placeholder="Número celular com DDD" name="phone_number" value="{{ old('phone_number') }}" style="border-radius: 20px" data-stripe="number" maxlength="11" required />
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="checkbox"><input type="radio" v-model="isCpf" :value="true" name="cpf-cnpj-type" data-validation="required" data-validation-error-msg="Por favor, selecione um tipo" required> Pessoa Física</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="checkbox"><input type="radio" v-model="isCpf" :value="false" name="cpf-cnpj-type" data-validation-error-msg="Por favor, selecione um tipo" required> Pessoa Jurídica</label>
                                        </div>
                                        <div class="col-12">

                                            @if ($errors->has('cpf_cnpj'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('cpf_cnpj') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- After VueJS load it checks for refs.cpfCnpjFields and remove hidden field -->
                                <!-- This avoids glitching -->
                                <div class="col-md-12" ref="cpjCnpjFields" hidden>
                                    <input v-if="isCpf" type="text" id="cpf_cnpj" class="mask__cpf form-control" placeholder="CPF" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}" style="border-radius: 20px" data-stripe="number" maxlength="11" required />
                                    <input v-else type="text" id="cpf_cnpj" class="mask__cnpj form-control" placeholder="CNPJ" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}" style="border-radius: 20px" data-stripe="number" maxlength="11" required />
                                </div>

                                <div class="col-12 col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="checkbox"><input type="radio" name="gender" value="MALE" checked data-validation="required" data-validation-error-msg="Por favor, selecione um gênero" required> Masculino</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="checkbox"><input type="radio" name="gender" value="FEMALE" data-validation-error-msg="Por favor, selecione um gênero" required> Feminino</label>
                                        </div>
                                        <div class="col-12">

                                            @if ($errors->has('gender'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('gender') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <input type="password" class="form-control" name="password" placeholder="Senha" data-validation="length" style="border-radius: 20px" data-validation-length="min6" data-validation-error-msg="Password should not be less than 6 characters">

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <input type="password" placeholder="Repita a Senha" class="form-control" name="password_confirmation" style="border-radius: 20px" data-validation="confirmation" data-validation-confirm="password" data-validation-error-msg="As senhas não correspondem">

                                @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                                @endif
                            </div>
                            @if(config('constants.referral') == 1)
                            <div class="col-md-12">
                                <input type="text" placeholder="Código de Referência (Opcional)" class="form-control" name="referral_code">

                                @if ($errors->has('referral_code'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('referral_code') }}</strong>
                                </span>
                                @endif
                            </div>
                            @else
                            <input type="hidden" name="referral_code">
                            @endif

                            <div class="col-md-12">
                                <button class="log-teal-btn" style="background-color:#ff5e00; border-radius: 40px;" type="submit">CADASTRAR</button>
                            </div>

                            <div class="col-md-12">
                                <p class="helper">Ou <a href="{{route('login')}}">Entre</a> com sua conta de usuário.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endsection


        @section('scripts')
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
        <script src="{{ asset('asset/js/jmask.js') }}"></script>
        <script defer src="https://unpkg.com/vue"></script>
        <script defer src="{{ asset('asset/vue/register.js') }}"></script>
        <script type="text/javascript">
            @if(count($errors) > 0)
            $("#second_step").show();
            @endif
            $.validate({
                modules: 'security',
            });
            $('.checkbox-inline').on('change', function() {
                $('.checkbox-inline').not(this).prop('checked', false);
            });
        </script>

        @endsection