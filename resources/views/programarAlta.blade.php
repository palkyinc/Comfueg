@extends('layouts.plantilla')
@section('contenido')
@can('programar_alta')
@php
$mostrarSololectura = true;
@endphp
    <div id="programaralta">
        <programaralta></programaralta>
    </div>

    @section('javascript')
                        <script src="vue.js/vendor/js/{{$vuejs}}"></script>
                        <script src="vue.js/vendor/js/vuex.js"></script>
                        <script> website = '{{$website}}'</script>
                        <script> alta_id = '{{$alta}}'</script>
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <script src="js/resources/misFunciones.js"></script>
                        <script src="vue.js/resources/js/panel.js"></script>
                        <script src="vue.js/resources/js/equipo.js"></script>
                        <script src="vue.js/resources/js/tipoInstalacion.js"></script>
                        <script src="vue.js/resources/js/programarAlta.js"></script>
                        
                        {{-- 
                        <script src="vue.js/resources/js/subirArchivo.js"></script>
                        <script src="vue.js/resources/js/numeroCarpeta.js"></script>
                        <script src="vue.js/resources/js/tipoContrato.js"></script>

                        <ubiquiti></ubiquiti>
                        <router></router>
                        <panel></panel>
                        --}}
                    @endsection

@endcan
@include('sinPermiso')
@endsection