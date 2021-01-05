@extends('layouts.plantilla')
@section('contenido')
@can('paneles_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-6 margin-10" action="" method="GET">
                        <h2 class="mx-2">Administración del alcance de Barrio por Panel.</h2>
                    </form>

        @if ( session('mensaje') )
            <div class="alert alert-success">
                @foreach (session('mensaje') as $item)
                    {{ $item }} <br>
                @endforeach
            </div>
        @endif
        
<div class="table-responsive">
                
                <table class="table table-sm table-bordered table-hover">
                    <caption>Listado de Paneles</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> SSID </th>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> IP </th>
                            <th scope="col"> Editar </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($paneles as $panel)
                            <tr>
                                
                            <th scope="row"> {{$panel->ssid}}</th>
                            <td>{{$panel->relEquipo->nombre}}</td>
                            <td>{{$panel->relEquipo->ip}}</td>
                            <td>
                                @can('paneles_edit')
                                <a href="/modificarPanelHasBarrio/{{ $panel->id }}" class="margenAbajo btn btn-outline-secundary" title="Agregar/Quitar Barrio">
                                    <img src="imagenes/iconfinder_user-permission_3018548.svg" alt="imagen de Cambio de Barrio en Panel" height="20px">
                                </a>
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $paneles->links() }}
@endcan
@include('sinPermiso')
@endsection