@extends('layouts.plantilla')
@section('contenido')
@can('paneles_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Agregar Panel nuevo</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarPanel" method="post" enctype="multipart/form-data">
        @csrf
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="ssid">SSID: </label>
                    <input type="text" name="ssid" value="{{old('ssid')}}" maxlength="15" class="form-control">
                    <input type="hidden" name="activo" value="0" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label for="rol">Rol: </label>
                    <select class="form-control" name="rol">
                        <option value="">Seleccione un Rol...</option>
                        @foreach ($roles as $key => $rol)
                            <option value="{{$key}}">{{$rol}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="id_equipo">Equipo: </label>
                    <select class="form-control" name="id_equipo">
                        <option value="">Seleccione un Equipo...</option>
                        @foreach ($equipos as $dato)
                            <option value="{{$dato->id}}">{{$dato->nombre}}->{{$dato->ip}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="num_site">Sitio: </label>
                    <select class="form-control" name="num_site">
                        <option value="">Seleccione un Sitio...</option>
                        @foreach ($sitios as $sitio)
                            <option value="{{$sitio->id}}">{{$sitio->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="panel_ant">Panel Anterior: </label>
                    <select class="form-control" name="panel_ant">
                        <option value="">Gateway...</option>
                        @foreach ($paneles as $panel)
                            @if ($panel->activo)
                                <option value="{{$panel->id}}">{{$panel->relEquipo->nombre}}->{{$panel->relEquipo->ip}}</option>';
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="altura">Altura (mt.): </label>
                    <input type="text" name="altura" value="{{old('altura')}}" maxlength="15" class="form-control">
                </div>
            </div>
            <br>
            <div class="form-row">
                <div class="form-group col-md-9">
                    <label for="comentario">Comentario: </label>
                    <textarea name="comentario" class="form-control" rows="auto" cols="50">{{old('comentario')}}</textarea>
                </div>
            </div>
    
            <input type="hidden" name="id" value="">
            <button type="submit" class="btn btn-primary">Crear Nuevo</button>
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