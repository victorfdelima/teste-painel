@extends('user.layout.auth')

@section('content')

<?php $login_user = asset('asset/img/loginbg.png'); ?>
<div class="full-page-bg" style="background-image: url({{$login_user}});">
<div class="log-overlay"></div>
    <div class="full-page-bg-inner">
        <div class="row no-margin">
            <div class="col-md-6 log-left">
                <span class=""><img src="https://aplicativo.vamo.app.br/wp-content/uploads/2021/05/logoV-1-50x50.png"></span>
                <h2>Redefina sua senha</h2>
                <p>Bem-vindo(a) a {{ config('constants.site_title', 'Vamo')  }}, a maneira mais fácil de entregar algo.</p>
            </div>
            <div class="col-md-6 log-right">
                <div class="login-box-outer">
                <div class="login-box row no-margin" style="background-color: #ffff; border-radius: 40px">
                    <div class="col-md-12">
                        <a class="log-blk-btn" style="background-color: #ff5e00; border-radius: 40px" href="{{url('login')}}">JÁ TEM UMA CONTA?</a>
                        <h3>Redefinir Senha</h3>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form role="form" method="POST" action="{{ url('/password/email') }}">
                        {{ csrf_field() }}

                        <div class="col-md-12">
                            <input type="email" class="form-control" style="border-radius: 20px" name="email" placeholder="Seu e-mail" value="{{ old('email') }}">

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif                        
                        </div>

                        
                        <div class="col-md-12">
                            <button class="log-teal-btn" style="background-color: #ff5e00; border-radius: 40px" type="submit">ENVIAR LINK DE REDEFINIÇÃO</button>
                        </div>
                    </form>     

                    <div class="col-md-12">
                        <p class="helper">Ou <a href="{{route('login')}}">Entre</a> com sua conta de usuário.</p>   
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
