@extends('layouts.plantilla')
@section('contenido')
@can('contratos_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-6 margin-10" action="" method="GET">
                        <h2 class="mx-2">Administración de Contratos</h2>
                        <label for="cliente" class="mx-2">Apellido del Cliente</label>
                        <input type="text" name="cliente" class="form-control mx-3" id="cliente">
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
                    <caption>Listado de Contratos</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Cliente </th>
                            <th scope="col"> Plan </th>
                            <th scope="col"> Conteo </th>
                            <th scope="col"> IP </th>
                            <th scope="col"> Equipo </th>
                            <th scope="col"> Panel </th>
                            <th scope="col"> Ubicación </th>
                            <th scope="col"> Estado </th>
                            <th scope="col" colspan="2">
                                @can('contratos_create')
                                <a href="/agregarContrato" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody id="zona">
                        @foreach ($contratos as $contrato)
                            @if ($contrato->activo)
                                <tr>
                            @else
                                @if ($contrato->baja)
                                    <tr class="alert alert-danger" role="alert">
                                @else
                                    <tr class="alert alert-warning" role="alert">
                                @endif
                            @endif
                                
                                <th scope="row"> {{$contrato->id}}</th>
                                <td title="{{$contrato->relCliente->relCodAreaCel->codigoDeArea}}-15-{{$contrato->relCliente->celular}}">{{$contrato->relCliente->getNomYApe()}}</td>
                                <td>{{$contrato->relPlan->nombre}}</td>
                                <td>
                                    @foreach ($conteos as $conteo)
                                        @if ($conteo->contrato_id == $contrato->id)
                                            {{$conteo->imprimirActual()}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    <a href="http://{{$contrato->relEquipo->ip}}" target="_blank">{{$contrato->relEquipo->ip}}</a>
                                </td>
                                <td title="{{$contrato->relEquipo->mac_address}}"> 
                                    @can('equipos_edit')
                                    <a href="/modificarEquipo/{{$contrato->num_equipo}}" target="_blank">
                                    @endcan
                                    {{$contrato->relEquipo->relProducto->modelo}}
                                    @can('equipos_edit')
                                    </a>
                                    @endcan
                                </td>
                                <td>{{$contrato->relPanel->ssid}}</td>
                                <td
title="Dirección: 
{{$contrato->relDireccion->relCalle->nombre}}, {{$contrato->relDireccion->numero}}">
                                    {{$contrato->relDireccion->relBarrio->nombre}}
                                </td>
                                <td>{{($contrato->activo) ? 'Habilitado' : (($contrato->baja) ? 'Dado baja' : 'Suspendido' )}}</td>
                                <td class="conFlex">
                                    <a href="#" class="margenAbajo btn btn-link" data-toggle="modal" data-target="#staticBackdrop{{$contrato->id}}" title="Consumo Descargas">
                                        <img src="imagenes/iconfinder_graph_3338898.svg" alt="imagen de cosumo cliente" height="20px">
                                    </a>
                                    <a href="adminIssues?rebusqueda=on&usuario=todos&contrato={{$contrato->id}}" title="Historial tickets" >
                                    <img src="imagenes/iconfinder_cinema_ticket_film_media_movie_icon.svg" alt="imganen ticket" height="30px">
                                    </a>
                                    @if ($contrato->relDireccion->coordenadas != '')
                                        <a href="https://www.google.com/maps/place/{{$contrato->relDireccion->coordenadas}}" target="_blank"
                                            class="margenAbajo btn btn-link" title="Ver en Google maps">
                                            <img src="imagenes/pin_location.svg" alt="Pin en mapa" height="20px">
                                        </a>
                                    @endif
                                    @can('contratos_edit')
                                        <a href="/modificarContrato/{{ $contrato->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                            <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                        </a>
                                        @if ($contrato->baja)
                                            <form action="/realtaContrato" method="post" class="margenAbajo">
                                            @csrf
                                            @method('patch')
                                                <input type="hidden" name="id" value="{{$contrato->id}}">
                                                <button class="btn btn-outline-secundary boton-Alta"  title="Dar de Alta">
                                                    <img src="imagenes/iconfinder_Multimedia_Turn_on_off_power_button_interface_3841792.svg" alt="imagen de activar" height="20px">
                                                </button>
                                            </form>
                                        @else
                                            <form action="/eliminarContrato" method="post" class="margenAbajo">
                                            @csrf
                                            @method('delete')
                                                <input type="hidden" name="id" value="{{$contrato->id}}">
                                                <button class="btn btn-outline-secundary boton-Baja"  title="Dar de Baja">
                                                    <img src="imagenes/iconfinder_Turn_On__Off_2134663.svg" alt="imagen de Desactivar" height="20px">
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
    @if ($paginate)
        {{ $contratos->links() }}
    @endif
@include('modals.consumosCliente')
@endcan
@include('sinPermiso')
@endsection
