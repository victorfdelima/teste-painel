@extends('user.components.template')

<!----------------------------Conteúdo------------------------------------------>

@section('content')

<div class="container-scroller">
    @include('user.components.navbar')
    <div class="container-fluid page-body-wrapper">
        <div id="right-sidebar" class="settings-panel">
            @include('user.components.chat')
        </div>
        @include('user.components.sidebar')
        <div class="main-panel">
            <div class="content-wrapper container shadow-sm rounded-sm bg-white">
                
            </div>
        </div>
    </div>
    @include('user.components.footer')
</div> 

@endsection