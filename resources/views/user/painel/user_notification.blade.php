@extends('user.components.template')

@section('title', 'Notificações ')
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
                <!-- notifications start-->
                <div class="notify">
                    <h3 class="font-weight-bold">Notificações</h3>
                    @if(sizeof($globalNotifications))
                    @foreach($globalNotifications as $index => $notify)
                    <div class="notify-sec">
                        <div class="row m-0 whlnot">
                            <div class="notify-img no ">
                                @if($notify->image)
                                <img src="{{$notify->image}}" class="img-responsive" alt="image">
                                @else
                                N/A
                                @endif
                            </div>
                            <div class="notify-content">
                                <h5>{{ date('F d, Y, h:i A', strtotime($notify->created_at)) }}</h5>
                                <p>{{ str_limit($notify->description, $limit = 100, $end = '...') }}</p>
                            </div>
                        </div>

                    </div>
                    @endforeach
                    @else
                    <h4 class="text-secondary mt-5 text-center">
                        Você não tem nenhuma notificação.
                    </h4>
                    <div class="text-center w-100 notification-bell">
                        <i class="ti-bell text-secondary" style="font-size: 32px; "></i>
                    </div>
                    @endif
                </div>
                <!-- notifications end-->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
</script>

@endsection

<style>
    .notification-bell {
        animation: spin-a 5s forwards infinite;
    }

    @keyframes spin-a {

        0%,
        20%,
        100% {
            transform: rotateZ(0)
        }

        5% {
            transform: rotateZ(15deg)
        }

        10%,
        16% {
            transform: rotateZ(-15deg)
        }
    }
</style>