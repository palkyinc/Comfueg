@extends('layouts.plantilla')
@section('contenido')
@can('testContrato_index')
@php
$mostrarSololectura = true;
@endphp
    <h2>Prueba contrato : {{$contrato->id}} | {{$contrato->relCLiente->getNomYApe()}}</h2>
    <div id="testContrato">
        <testantenacliente></testantenacliente>
    </div>
    
    @section('javascript')
        <script src="/vue.js/vendor/js/{{$vuejs}}"></script>
        <script src="/vue.js/vendor/js/vuex.js"></script>
        <script> website = '{{$website}}'</script>
        <script> contrato = '{{$contrato->id}}'</script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- <script src="vue.js/resources/js/numeroCarpeta.js"></script>
        <script src="vue.js/resources/js/tipoContrato.js"></script>
        <script src="vue.js/resources/js/cliente.js"></script> --}}
        <script src="/vue.js/resources/js/testantena.js"></script>
        <script src="/vue.js/resources/js/testAntenaCliente.js"></script>
        @endsection

@endcan
@include('sinPermiso')
@endsection