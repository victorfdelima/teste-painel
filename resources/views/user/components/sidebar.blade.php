<!-- partial:partials/_sidebar.html -->

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{url('/painel')}}">
                <i class="ti-home menu-icon"></i>
                <span class="menu-title">Home</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('/notifications')}}">
                <i class="icon-bell menu-icon"></i>
                <span class="menu-title">Notificações</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="ti-truck menu-icon"></i>
                <span class="menu-title">Entregas</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{route('entrega')}}">Solicitar</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{route('historico')}}">Histórico</a></li>
                </ul>
            </div>
        </li>
        <!--    <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <i class="ti-money menu-icon"></i>
                <span class="menu-title">Pagamento</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Fatura</a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Ver contrato</a></li>
                </ul>
            </div>
        </li> -->
        <li class="nav-item">
            <a class="nav-link" href="{{url('/payment')}}">
                <i class="ti-money menu-icon"></i>
                <span class="menu-title">Pagamento</span>
                <!--     <i class="menu-arrow"></i> -->
            </a>

        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('/wallet')}}">
                <i class="ti-wallet menu-icon"></i>
                <span class="menu-title">Minha Carteira</span>
                <!--     <i class="menu-arrow"></i> -->
            </a>

        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <i class="icon-head menu-icon"></i>
                <span class="menu-title">Perfil</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="/change/password"> Alterar Senha </a></li>
                    <li class="nav-item"> <a class="nav-link" href="/profile"> Configurações </a></li>
                </ul>
            </div>
        <li class="nav-item">
            <a class="nav-link" href="{{url('/suporte')}}">
                <i class="ti-help-alt menu-icon"></i>
                <span class="menu-title">Ajuda</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                <i class="ti-shift-right menu-icon"></i>
                <span class="menu-title">Logout</span>
            </a>
        </li>
        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
        </li>

    </ul>
</nav>

<!-- partial -->