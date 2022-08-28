@extends('admin.layout.base')

@section('title', $page)

@section('content')
<style type="text/css">

</style>
<!-- //TODO ALLAN - Alterações débito na máquina e voucher -->
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
           
           
            

            <div style="text-align: center;padding: 20px;color: blue;font-size: 24px;">
                Olá mundo</div>

          <div class="row">

            <div class="col-lg-4 col-md-6 col-xs-12">
                  <div class="box box-block bg-white tile tile-1 mb-2"  style="border-top-color: #3e70c9 !important;">
                    <div class="t-icon right"><span class="bg-danger" style="background-color: #3e70c9 !important;"></span><i class="ti-rocket"></i></div>
              </div>
                </div>

                @if(isset($statement_for) && $statement_for !="user")
                
                @else
                <div class="col-lg-4 col-md-6 col-xs-12">
                    <div class="box box-block bg-white tile tile-1 mb-2" style="border-top-color: #4bcb73 !important;">
                        <div class="t-icon right"><span class="bg-success" style="background-color: #4bcb73 !important;"></span><i class="ti-money"></i></div>
                        <div class="t-content">
                            <h6 class="text-uppercase mb-1">@lang('admin.dashboard.total')</h6>
                            <h1 class="mb-1">{{currency($revenue1[0]->overall)}}</h1>
                            <i class="fa fa-caret-up text-success mr-0-5"></i><span>de {{$totalRides}} viagens</span>
                        </div>
                  </div>
            </div>
                @endif
                

               
          </div>

                </div>

            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    $(".showdate").on('click', function () {
        var ddattr = $(this).attr('id');
        //console.log(ddattr);
        if (ddattr == 'tday') {
            $("#from_date").val('{{$dates["today"]}}');
            $("#to_date").val('{{$dates["today"]}}');
        } else if (ddattr == 'yday') {
            $("#from_date").val('{{$dates["yesterday"]}}');
            $("#to_date").val('{{$dates["yesterday"]}}');
        } else if (ddattr == 'cweek') {
            $("#from_date").val('{{$dates["cur_week_start"]}}');
            $("#to_date").val('{{$dates["cur_week_end"]}}');
        } else if (ddattr == 'pweek') {
            $("#from_date").val('{{$dates["pre_week_start"]}}');
            $("#to_date").val('{{$dates["pre_week_end"]}}');
        } else if (ddattr == 'cmonth') {
            $("#from_date").val('{{$dates["cur_month_start"]}}');
            $("#to_date").val('{{$dates["cur_month_end"]}}');
        } else if (ddattr == 'pmonth') {
            $("#from_date").val('{{$dates["pre_month_start"]}}');
            $("#to_date").val('{{$dates["pre_month_end"]}}');
        } else if (ddattr == 'pyear') {
            $("#from_date").val('{{$dates["pre_year_start"]}}');
            $("#to_date").val('{{$dates["pre_year_end"]}}');
        } else if (ddattr == 'cyear') {
            $("#from_date").val('{{$dates["cur_year_start"]}}');
            $("#to_date").val('{{$dates["cur_year_end"]}}');
        } else {
            alert('invalid dates');
        }
    });
</script>
@endsection