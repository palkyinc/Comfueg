@extends('layouts.plantilla')
@section('contenido')
@can('alta_contrato')
@php
$mostrarSololectura = true;
@endphp
    <div id="altaInternet">
        <contrato></contrato>
    </div>

    @section('javascript')
        <script src="/vue.js/vendor/js/{{$vuejs}}"></script>
        <script src="/vue.js/vendor/js/vuex.js"></script>
        <script> website = '{{$website}}'</script>
        <script> altaToEdit = '{{$alta}}'</script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="/vue.js/resources/js/plan.js"></script>
        <script src="/vue.js/resources/js/direccion.js"></script>
        <script src="/vue.js/resources/js/cliente.js"></script>
        <script src="/vue.js/resources/js/altaInternet.js"></script>
    @endsection

@endcan
@include('sinPermiso')
@endsection