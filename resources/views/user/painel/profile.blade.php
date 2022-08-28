@extends('user.components.template')

@section('title', 'Profile ')
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
        <!--     <div class="content-wrapper"> -->
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold">@lang('user.profile.general_information')</h3>
            </div>
     
            @include('common.notify')
        <div class="col-12 col-md-10">
        <div class="row mt-4">
     <!--        <form> -->
                <div class="col-md-6 pro-form">
                    <h5 ><strong>@lang('user.profile.first_name')</strong></h5>
                    <p>{{Auth::user()->first_name}}</p>                       
                </div>
                <div class="col-md-6 pro-form">
                    <h5 ><strong>@lang('user.profile.last_name')</strong></h5>
                    <p>{{Auth::user()->last_name}}</p>                       
                </div>
                <div class="col-md-6 pro-form">
                    <h5 ><strong>@lang('user.profile.email')</strong></h5>
                    <p>{{Auth::user()->email}}</p>
                </div>

                <div class="col-md-6 pro-form">
                    <h5 ><strong>@lang('user.profile.mobile')</strong></h5>
                    <p>{{Auth::user()->mobile}}</p>
                </div>
               
                <div class="col-md-6 pro-form">
                    <h5 ><strong>@lang('user.profile.wallet_balance')</strong></h5>
                    <p>{{currency(Auth::user()->wallet_balance)}}</p>
                </div>                  

                <div class="col-md-6 pro-form">
                    <h5 ><strong>@lang('user.profile.language')</strong></h5>
                    @php($language=get_all_language())
                    <p>
                        @if(!empty($language[Auth::user()->language]))
                        {{$language[Auth::user()->language]}}
                        @else
                        {{$language['pt-br']}}
                        @endif</p>
                </div>
                <div class="col-md-6 pro-form">
                    <h5 ><strong>@lang('user.profile.country_code')</strong></h5>
                    <p>{{Auth::user()->country_code}}</p>
                </div> 
                <div class="col-md-6 pro-form mt-5">
                    <a class="btn btn-md pr-5 pl-5" style="background-color: #ff5e00;
    border-radius: 40px;
    color: white;" href="{{url('edit/profile')}}">@lang('user.profile.edit')</a>
                </div>
                </div>    
 <!--            </form> -->
        </div>
        <!--     </div> -->
    </div>   </div>
</div>
    </div></div>
@endsection