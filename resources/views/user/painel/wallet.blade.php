@extends('user.components.template')

@section('title', 'Wallet ')
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
                <div class="row no-margin">
                    <div class="col-md-12">
                        <h3 class="font-weight-bold">@lang('user.my_wallet')</h3>
                    </div>
                    @include('common.notify')
                    <div class="col-12 col-md-4 m-0 pl-0 text-center">
                        <div id="wallet" class="mt-5 border rounded-sm d-flex justify-content-center bg-white">
                            <div>
                                <span class="price text-success" style="font-size: 32px">
                                    <i class="ti-wallet" style="font-size: 30px"></i>
                                    {{currency(Auth::user()->wallet_balance)}}
                                </span><br />
                                <span class="txt" style="font-size: 18px">@lang('user.in_your_wallet')</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 pl-0">
                        <div class="w-100 mt-5">
                            <table class="earning-table table bg-white w-100 border-top">
                                <thead>
                                    <tr>
                                        <th>@lang('provider.sno')</th>
                                        <th>@lang('provider.transaction_ref')</th>
                                        <th>@lang('provider.transaction_desc')</th>
                                        <th>@lang('provider.status')</th>
                                        <th>@lang('provider.amount')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($page = ($pagination->currentPage-1)*$pagination->perPage)
                                    @foreach($wallet_transation as $index=>$wallet)
                                    @php($page++)
                                    <tr>
                                        <td>{{$page}}</td>
                                        <td>{{$wallet->transaction_alias}}</td>
                                        <td>{{$wallet->transaction_desc}}</td>
                                        <td>@if($wallet->type == 'C') @lang('user.credit') @else @lang('user.debit') @endif</td>
                                        <td>{{currency($wallet->amount)}}
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            {{ $wallet_transation->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
<style>
    #wallet {
        width: 350px;
        padding: 60px;
    }
</style>
@section('scripts')
@if(Config::get('constants.braintree') == 1)
<script src="https://js.braintreegateway.com/web/dropin/1.14.1/js/dropin.min.js"></script>

<script>
    var button = document.querySelector('#submit-button');
    var form = document.querySelector('#add_money');
    braintree.dropin.create({
        authorization: '{{$clientToken}}',
        container: '#dropin-container',
        //Here you can hide paypal
        paypal: {
            flow: 'vault'
        }
    }, function(createErr, instance) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (document.querySelector('select[name="payment_mode"]').value == "BRAINTREE") {
                instance.requestPaymentMethod(function(requestPaymentMethodErr, payload) {
                    document.querySelector('input[name="braintree_nonce"]').value = payload.nonce;
                    console.log(payload.nonce);
                    form.submit();
                });
            } else {
                form.submit();
            }

        });
    });
</script>
@endif

<script type="text/javascript">
    @if(Config::get('constants.card') == 1)
    card('CARD');
    @endif

    function card(value) {
        $('#card_id, #braintree').fadeOut(300);
        if (value == 'CARD') {
            $('#card_id').fadeIn(300);
        } else if (value == 'BRAINTREE') {
            $('#braintree').fadeIn(300);
        }
    }
</script>
@endsection