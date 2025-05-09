@extends('layouts.plantilla')
@section('contenido')
@can('testContrato_index')
@php
$mostrarSololectura = true;
@endphp
    <h3>Prueba contrato : {{$contrato->id}} | {{$contrato->relCLiente->getNomYApe()}} | {{$contrato->relPlan->nombre}} | Desde: {{$contrato->inicioDateTimeLocal()}}</h3>
    <div id="testContrato">
        <testantenacliente></testantenacliente>
    </div>
    <div class="bg-light border p-3 mx-0 my-3">
        <div id="chartInsta{{$contrato->id}}" style="height: 300px;" class="pt-4"></div>
        <div id="chartDay{{$contrato->id}}" style="height: 300px;"></div>
        <div id="chartWeek{{$contrato->id}}" style="height: 300px;"></div>
        <div id="chartMonthly{{$contrato->id}}" style="height: 300px;"></div>
    </div>
    
    @section('javascript')
        <script src="/vue.js/vendor/js/{{$vuejs}}"></script>
        <script src="/vue.js/vendor/js/vuex.js"></script>
        <script> website = '{{$website}}'</script>
        <script> contrato = '{{$contrato->id}}'</script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="/vue.js/resources/js/testantena.js"></script>
        <script src="/vue.js/resources/js/testAntenaCliente.js"></script>
        @include('layouts.consumosCliente2')
    @endsection
@endcan
@include('sinPermiso')
@endsection
