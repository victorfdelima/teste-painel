<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <p class="card-title">Histórico Fast</p>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="myTable" class="display expandable-table table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Numero da Entrega</th>
                                    <th>Cliente</th>
                                    <th>Entregador</th>
                                    <th>Status</th>
                                    <th>Solicitado em</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trips as $trip)
                                <tr>
                                    <td>{{ $trip->booking_id}}</td>
                                    <td>{{ Auth::user()->first_name }}{{ Auth::user()->last_name }}</td>
                                    <td>{{$trip->provider->first_name}} {{$trip->provider->last_name}}</td>
                                  
                                    @if ($trip->status == 'CANCELLED')
                                    <td><button type="button" class="btn btn-sm btn-danger">{{$trip->status}}</button></td>
                                    @elseif($trip->status == 'COMPLETED')
                                    <td><button type="button" class="btn btn-sm btn-success">{{$trip->status}}</button></td>
                                    @endif
                                    @if ($trip->status == 'SEARCHING')
                                    <td><button type="button" class="btn btn-sm btn-warning">{{$trip->status}}</button></td>
                                    @endif
                                    <td>{{date('d-m-Y',strtotime($trip->assigned_at))}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$trips}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    const table = $('#myTable')
    if (table && table.tableSort) {
        table.tablesort();
    }
</script>