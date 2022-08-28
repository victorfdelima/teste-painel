
@extends('user.layout.auth')

@section('content')

<div class="full-page-bg" style="background-image: url({{ asset('asset/img/loginbg.png') }});">
    <div class="log-overlay"></div>
    <div class="full-page-bg-inner">
        <div class="row no-margin">
            <div class="col-md-6 log-left">
                <span class=""><img src="https://aplicativo.vamo.app.br/wp-content/uploads/2021/05/logoV-1-50x50.png"></span>
                <h2>É um contrato PJ?</h2>
                <p>Bem-vindo(a) ao {{config('constants.site_title', 'Vamo')}}, Faça login para começar a solicitar entregas!</p>
            </div>
            <div class="col-md-6 log-right">
                <div class="login-box-outer">
                <div class="login-box row no-margin" style="border-radius: 40px; background-color:#fff;">
                    <div class="col-md-12">
                        <a class="log-blk-btn" style="background-color:#ff5e00; border-radius: 40px;" href="{{url('register')}}">CRIAR UMA CONTA</a>
                        <h3>Entre com seus dados</h3>
                    </div>
                    <form  role="form" method="POST" action="{{ url('/login') }}"> 
                    {{ csrf_field() }}                      
                        <div class="col-md-12">
                             <input id="email" type="email" class="form-control" style="border-radius: 20px" placeholder="Digite o Email ou CNPJ" name="email" value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-md-12">
                            <input id="password" type="password" class="form-control" style="border-radius: 20px" placeholder="Senha" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}><span> Lembrar de mim</span>
                        </div>
                       
                        <div class="col-md-12">
                            <button type="submit" class="log-teal-btn" style="background-color:#ff5e00; border-radius: 40px">ENTRAR</button>
                        </div>
                    </form>
                    @if(config('constants.social_login', 0) == 1)
                    <div class="col-md-12">
                        <a href="{{ url('/auth/facebook') }}"><button type="submit" class="log-teal-btn fb"><i class="fa fa-facebook"></i>ENTRAR COM O FACEBOOK</button></a>
                    </div>  
<!--                    <div class="col-md-12">
                        <a href="{{ url('/auth/google') }}"><button type="submit" class="log-teal-btn gp"><i class="fa fa-google-plus"></i>ENTRAR COM O GOOGLE+</button></a>
                    </div>-->
                    @endif

                    <div class="col-md-12">
                        <p class="helper"> <a href="{{ url('/password/reset') }}">Esqueceu a senha?</a></p>   
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection