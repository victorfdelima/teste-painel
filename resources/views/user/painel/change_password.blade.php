@extends('user.components.template')

@section('title', 'Change Password ')
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
        <div class="content-wrapper container shadow-sm rounded-sm">
        <div class="col-12 col-md-10">
        <div class="row no-margin">
            <div class="col-md-12">
            <h3 class="font-weight-bold">@lang('user.profile.change_password')</h3>
            </div>
        </div>
        @if(config('constants.demo_mode', 0) == 1)
            <div class="alert alert-danger">
                 @lang('admin.demomode')
            </div>
        @else
            @include('common.notify')
        @endif
        <div class="row no-margin edit-pro">
            <form class="form w-100" action="{{url('change/password')}}" method="post">
            {{ csrf_field() }}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('user.profile.old_password')</label>
                        <input type="password" name="old_password" class="form-control" placeholder="@lang('user.profile.old_password')">
                    </div>
                    <div class="form-group">
                        <label>@lang('user.profile.password')</label>
                        <input type="password" name="password" class="form-control" placeholder="@lang('user.profile.password')">
                    </div>

                    <div class="form-group">
                        <label>@lang('user.profile.confirm_password')</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="@lang('user.profile.confirm_password')">
                    </div>
                  
                    <div>
                        <button type="submit" class="btn btn-md pr-5 pl-5" style="background-color: #ff5e00;
    border-radius: 40px;
    color: white;">@lang('user.profile.change_password')</button>
                    </div>
                </div>
            </form>
        </div>
        </div></div></div>
    </div>
</div>

@endsection