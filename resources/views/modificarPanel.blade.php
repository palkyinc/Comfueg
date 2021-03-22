@extends('layouts.plantilla')
@section('contenido')
@can('paneles_edit')
@php
$mostrarSololectura = true;
@endphp
<h3>Modificando Dispositivo con ID: {{ $elemento->id }}</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarPanel" method="post" enctype="multipart/form-data">
        @csrf
        @method('patch')

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="ssid">SSID: </label>
                    <input type="text" name="ssid" value="{{$elemento->ssid}}" maxlength="15" class="form-control">
                    <input type="hidden" name="activo" value="{{$elemento->activo}}" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label for="rol">Rol: </label>
                    <select class="form-control" name="rol">
                        <option value="">Seleccione un Rol...</option>
                        @foreach ($roles as $key => $rol)
                            @if ($key != $elemento->rol)
                                <option value="{{$key}}">{{$rol}}</option>
                            @else
                                <option value="{{$key}}" selected>{{$rol}}</option>
                            @endif    
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="id_equipo">Equipo: </label>
                    <select class="form-control" name="id_equipo">
                        <option value="">Seleccione un Equipo...</option>
                        @foreach ($equipos as $dato)
                                @if ($dato->id != $elemento->id_equipo)
                                    <option value="{{$dato->id}}">{{$dato->nombre}}->{{$dato->ip}}</option>
                                @else
                                    @php
                                        $seleccionado = true;
                                    @endphp
                                    <option value="{{$dato->id}}" selected>{{$dato->nombre}}->{{$dato->ip}}</option>
                                @endif
                        @endforeach
                        @if (!$seleccionado)
                            <option value="{{$elemento->relEquipo->id}}" selected>{{$elemento->relEquipo->nombre}}->{{$elemento->relEquipo->ip}}</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="num_site">Sitio: </label>
                    <select class="form-control" name="num_site">
                        <option value="">Seleccione un Sitio...</option>
                        @foreach ($sitios as $sitio)
                            @if ($sitio->id != $elemento->num_site)
                                <option value="{{$sitio->id}}">{{$sitio->nombre}}</option>
                            @else
                                <option value="{{$sitio->id}}" selected>{{$sitio->nombre}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="panel_ant">Panel Anterior: </label>
                    <select class="form-control" name="panel_ant">
                        <option value="">Internet...</option>
                        @foreach ($paneles as $panel)
                            @if ($panel->activo)
                                @if ($panel->id != $elemento->panel_ant)
                                    <option value="{{$panel->id}}">{{$panel->relEquipo->nombre}}->{{$panel->relEquipo->ip}}</option>';
                                @else
                                    <option value="{{$panel->id}}" selected>{{$panel->relEquipo->nombre}} -> {{$panel->relEquipo->ip}}</option>';
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="altura">Altura (mt.): </label>
                    <input type="text" name="altura" value="{{$elemento->altura}}" maxlength="15" class="form-control">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="cable_fecha">Instalación de Cable: </label>
                    <input type="datetime-local" name="cable_fecha" value="{{$elemento->cable_fecha}}" class="form-control">
                </div>
                <div class="form-group col-md-8">
                    <label for="cable_tipo">Cable Marca detalles: </label>
                    <input type="text" name="cable_tipo" value="{{$elemento->cable_tipo}}" maxlength="50" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-9">
                    <label for="comentario">Comentario: </label>
                    <textarea name="comentario" class="form-control" rows="auto" cols="50">{{$elemento->comentario}}</textarea>
                </div>
            </div>
    
            <input type="hidden" name="id" value="{{$elemento->id}}">
            <button type="submit" class="btn btn-primary">Modificar</button>
            <a href="/adminPaneles" class="btn btn-primary">volver</a>
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