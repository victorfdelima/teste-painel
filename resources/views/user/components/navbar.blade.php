<!-- partial:partials/_navbar.html -->
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo mr-5" href="{{route('painel')}}"><img src="https://aplicativo.vamo.app.br/wp-content/uploads/2021/05/V-3.png" class="mr-2" alt="logo" /></a>
    <a class="navbar-brand brand-logo-mini" href="{{route('painel')}}"><img src="https://aplicativo.vamo.app.br/wp-content/uploads/2021/05/cropped-logoV-1-1-180x180.png" alt="logo" /></a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="icon-menu"></span>
    </button>
    <ul class="navbar-nav mr-lg-2">
      <div class="input-group">
        <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
          </span>
        </div>
      </div>
      </li>
    </ul>
    <ul class="navbar-nav navbar-nav-right">
      <li class="nav-item dropdown">
        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
          <i class="icon-bell mx-0"></i>
          <span class="count"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
          <p class="mb-0 font-weight-normal float-left dropdown-header">Notificações</p>
          <a class="dropdown-item preview-item">
            @if(sizeof($globalNotifications))
            @foreach ($globalNotifications as $notification )
            <div class="preview-thumbnail">
              <div class="preview-icon bg-success">
                @if($notify->image)
                <img src="{{$notify->image}}" class="img-responsive" alt="image">
                @else
                <i class="ti-info-alt mx-0"></i>
                @endif
              </div>
            </div>
            <div class="preview-item-content">
              <h6 class="preview-subject font-weight-normal">
                {{ str_limit($notify->description, $limit = 35, $end = '...') }}
              </h6>
              <p class="font-weight-light small-text mb-0 text-muted">
                {{$notification->created_at}}
              </p>
            </div>
            @endforeach
            @else
            <div>
              <h6 class="text-secondary mt-2 text-center">
                Você não tem nenhuma notificação.
              </h6>
              <div class="text-center w-100 notification-bell">
                <i class="ti-bell text-secondary" style="font-size: 32px; "></i>
              </div>
            </div>
            @endif
          </a>
        </div>

      </li>
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
          <!-- <img src="/images/faces/face28.jpg" alt="profile" /> -->
        <i class="ti-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
          <a class="dropdown-item">
            <i class="ti-settings text-primary"></i>
            Configuração do Perfil
          </a>
          <div class="logout">
          <a class="dropdown-item" href="{{ url('/logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">

            <i class="ti-power-off text-primary"></i>
            Sair
          </a>
          <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
          </div>
        </div>
      </li>
      <li class="nav-item nav-settings d-none d-lg-flex">
        <a class="nav-link" href="#">
          <i class="icon-ellipsis"></i>
        </a>
      </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="icon-menu"></span>
    </button>
  </div>
</nav>
<!-- partial -->
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