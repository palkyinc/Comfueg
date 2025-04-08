@extends('layouts.plantilla')
@section('contenido')
@can('mac_exception_index')
@php
$mostrarSololectura = true;
@endphp
@if ( session('mensaje') )
    <ul class="list-group">
        @foreach (session('mensaje') as $key => $items)
            @if ($key === 'success')
                    @foreach ($items as $item)
                        <li class="list-group-item list-group-item-success">{{ $item }}</li>
                    @endforeach
            @endif
            @if ($key === 'error')
                    @foreach ($items as $item)
                        <li class="list-group-item list-group-item-danger"> {{ $item }} </li>
                    @endforeach
            @endif
        @endforeach
    </ul>
@endif
<form class="form-inline mx-6 margin-10" action="" method="GET">
    <h2 class="mx-2">Administración de Mac Address Exceptions</h2>
    <label for="nombre" class="mx-3">Nombre</label>
    <input type="text" name="nombre" class="form-control mx-3" id="nombreSearch">
    <button type="submit" class="btn btn-primary mx-3">Enviar</button>
</form>
        
<div class="table-responsive">
                
    <table class="table table-sm table-bordered table-hover">
        <caption>Listado de Grupos Mac Address Execptions</caption>
        <thead class="thead-light">
            <tr>
                <th scope="col"> Id </th>
                <th scope="col"> Nombre </th>
                <th scope="col"> Mac Address </th>
                <th scope="col"> Panel </th>
                <th scope="col"> Descripción </th>
                <th scope="col" colspan="2">
                    {{-- @can('mac_exception_create')
                    <a href="/agregarException" class="btn btn-dark">Agregar</a>
                    @endcan --}}
                    Acciones
                </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($mac_exceptions as $mac_exception)
                <tr>
                    <th scope="row"> {{$mac_exception->id}}</th>
                    <td>{{$mac_exception->relEquipo->nombre}}</td>
                    <td>{{$mac_exception->relEquipo->mac_address}}</td>
                    <td>{{$mac_exception->relPanel->ssid}}({{$mac_exception->relPanel->relEquipo->ip}})</td>
                    <td>{{$mac_exception->description}}</td>
                    <td class="d-flex">
                        @can('mac_exception_edit')
                            {{-- <a href="/modificarException/{{ $mac_exception->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen delete basquet" height="20px">
                            </a> --}}
                            <form action="/borrarException" method="post" class="margenAbajo">
                            @csrf
                            @method('delete')
                                <input type="hidden" name="idEdit" value="{{$mac_exception->id}}">
                                <button type="submit" class="btn btn-outline-secundary" title="Borrar">
                                    <img src="imagenes/3556096_delete_list_remove_ui_icon.svg" alt="imagen de list remove" height="20px">
                                </botton>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
        {{ $mac_exceptions->links() }}
@endcan
@include('sinPermiso')
@endsection