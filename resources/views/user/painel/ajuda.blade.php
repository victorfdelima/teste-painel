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
            <div class="content-wrapper">
                <div class="container px-5 py-4 bg-white shadow-sm rounded-sm">
                    <div class="row position-relative" id="vue__application">
                        <i class="ti-direction-alt rounded-circle text-black-50 h2 p-2 text-white position-absolute" style="right:0; transform: rotateZ(10deg)"></i>
                        <h3>

                            Suporte ao Cliente
                        </h3>
                        <div class="col-12 mt-4">
                            <ul class="nav nav-pills pb-0">
                                <li class="nav-item" @click="changeState(0)">
                                    <button class="btn-nav-pill mb-0" :class="{'active': state === 0}">
                                        <h4 class="m-0 p-0 text-black-50">Para Ecommerces</h4>
                                    </button>
                                </li>
                                <li class="nav-item" @click="changeState(1)">
                                    <button class="btn-nav-pill mb-0" :class="{'active': state === 1}">
                                        <h4 class=" m-0 p-0 text-black-50">Para Restaurantes</h4>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12" ref="supportPane" hidden>
                            <div class="support-pane mt-3 " v-if="state === 0" ref="supportContent0">
                                <ul class="article-list" style="list-style-type:none">
                                    <li class="article-list-item h5 py-2" v-for="(item, index) in ecommerces" :key="index">
                                        <i class ='ti-new-window mr-3 text-info'></i><a :href="base + item.link" class="article-list-link" v-html="item.title" target="_blank"> </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="support-pane mt-3 " v-if="state === 1" ref="supportContent1">
                                <ul class="article-list" style="list-style-type:none">
                                    <li class="article-list-item h5 py-2" v-for="(item, index) in restaurants" :key="index">
                                        <i class ='ti-new-window mr-3 text-info'></i><a :href="base + item.link" class="article-list-link" v-html="item.title" target="_blank"> </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('user.components.footer')
</div>
<script defer src="https://unpkg.com/vue"></script>
<script defer src="{{ asset('asset/vue/help.js') }}"></script>
<!-- Start of suportevamo Zendesk Widget script -->
<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=7dab0fd4-1d4c-4dac-9d69-34f32781fa51"> </script>
<!-- End of suportevamo Zendesk Widget script -->
@endsection
<style>
    .btn-nav-pill {
        border-radius: 5px 5px 0 0;
        border: none;
        background-color: white;
        padding: 10px;
        transition: ease-in 200ms;
    }

    .support-pane {
        animation: 500ms opacity forwards
    }

    .active {
        background-color: rgba(0, 0, 0, 0.05);
    }

    @keyframes opacity {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }
</style>