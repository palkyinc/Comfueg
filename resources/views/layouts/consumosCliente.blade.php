<div class="">
    <div id="chartInsta{{$contrato->id}}" style="height: 300px;"></div>
    <div id="chartDay{{$contrato->id}}" style="height: 300px;"></div>
    <div id="chartWeek{{$contrato->id}}" style="height: 300px;"></div>
    <div id="chartMonthly{{$contrato->id}}" style="height: 300px;"></div>
</div>
@section('javascript')
    <!-- Your application script -->
    @include('layouts.consumosCliente2')
@endsection