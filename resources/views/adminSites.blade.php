@extends('layouts.plantilla')
@section('contenido')
@can('sitios_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-4 margin-10" action="" method="GET">
                        <h2 class="mx-3">Administración de sitios</h2>
                        <label for="nombre" class="mx-3">Nombre: </label>
                        <input type="text" name="nombre" class="form-control mx-3" id="nombre">
                        <button type="submit" class="btn btn-primary mx-3">Enviar</button>
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
                    <caption>Listado de sitios</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Descripción </th>
                            <th scope="col"> Coordenadas </th>
                            <th scope="col"> Inicio Rango IP </th>
                            <th scope="col"> IP Disponible </th>
                            <th scope="col"> Dist Sitio Ant(Km) </th>
                            <th scope="col" colspan="2">
                                @can('sitios_create')
                                <a href="/agregarSite" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($sites as $site)
                           @if ($site->activo)
                                <tr>
                            @else
                                <tr class="table-danger">
                            @endif
                                
                            <th scope="row"> {{$site->id}}</th>
                            <td>{{$site->nombre}}</td>
                            <td>{{$site->descripcion}}</td>
                            <td>{{$site->coordenadas}}</td>
                            <td>{{$site->rangoIp}}</td>
                            <td>{{$site->ipDisponible}}</td>
                            <td>{{$site->distancia}}</td>
                            <td>
                                @can('sitios_edit')
                                <a href="/modificarSite/{{$site->id}}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                                @if ($site->activo)
                                            <a href="/siteActivar/{{$site->id}}" class="margenAbajo btn btn-outline-secundary" title="Desactivar">
                                                <img src="imagenes/iconfinder_Turn_On__Off_2134663.svg" alt="imagen de Desactivar" height="20px">
                                            </a>
                                    @else 
                                        <a href="/siteActivar/{{$site->id}}" class="margenAbajo btn btn-outline-secundary" title="Activar">
                                            <img src="imagenes/iconfinder_Multimedia_Turn_on_off_power_button_interface_3841792.svg" alt="imagen de activar" height="20px">
                                        </a>
                                    @endif
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $sites->links() }}
@endcan    
@include('sinPermiso')
@endsection