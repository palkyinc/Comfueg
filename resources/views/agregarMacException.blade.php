@extends('layouts.plantilla')
@section('contenido')
@can('mac_exception_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Nuevo Excepción en Panel</h3>
<h4>Equipo:</h4>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">IP</th>
                <th scope="col">Mac Address</th>
                <th scope="col">Marca</th>
                <th scope="col">Modelo</th>
                <th scope="col">Comentarios</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>{{$equipo->nombre}}</th>
                <th>{{$equipo->ip}}</th>
                <th>{{$equipo->mac_address}}</th>
                <th>{{$equipo->relProducto->marca}}</th>
                <th>{{$equipo->relProducto->modelo}}</th>
                <th>{{$equipo->comentario}}</th>
            </tr>
        </tbody>
        
    </table>    


    <div class="alert bg-light border col-8 mx-auto p-4">
        <form action="/agregarException" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="panel_id">Panel: </label>
                    <select class="form-control" name="panel_id">
                        <option value="" {{null !== old('panel_id') ? '' : 'selected'}} disabled>Seleccione un panel...</option>
                        @foreach ($paneles as $key => $panel)
                            <option value="{{$panel->id}}" {{null !== old('panel_id') && old('panel_id') == $panel->id ? 'selected' : ''}}>
                                {{$panel->ssid}} / {{$panel->relEquipo->ip}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="descripcion">Descripción: </label>
                    <textarea name="descripcion" class="form-control" rows="auto" cols="50">{{old('descripcion')}}</textarea>
                </div>
            </div>
            <input type="hidden" name="equipo_id" value="{{$equipo->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Crear Nuevo</button>
            <a href="/adminEquipos" class="btn btn-secundary">Volver</a>
        </form>
    </div>

    @if( $errors->any() )
        <div class="alert alert-danger col-8 mx-auto">
            <ul>
                @foreach( $errors->all() as $error )
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endcan
@include('sinPermiso')

@endsection