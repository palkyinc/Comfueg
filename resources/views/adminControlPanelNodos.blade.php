@extends('layouts.plantilla')
@section('contenido')
@can('antenas_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-6 margin-10" action="" method="GET">
                        <h2 class="mx-2">Panel de Control de Nodos</h2>
                        <label for="descripcion" class="mx-2">Descripción</label>
                        <input type="text" name="descripcion" class="form-control mx-3" id="descripcion">
                        <label for="codComfueg" class="mx-2">Código Comfueg</label>
                        <input type="text" name="codComfueg" class="form-control mx-3" id="codComfueg">
                        <button type="submit" class="btn btn-primary mx-3">Enviar</button>
                    </form>

                    <div id="vista">
                            <button v-bind:class="claseBoton" v-on:click="clickBoton" >
                                Hola domun
                            </button>
                    </div>
                    @section('javascript')
                        <script src="vue.js/vendor/js/vue.js"></script>
                        <script src="vue.js/resources/js/adminControlPanelNodos.js"></script>
                    @endsection
@endcan
@include('sinPermiso')
@endsection