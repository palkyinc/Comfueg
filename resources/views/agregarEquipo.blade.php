@extends('layouts.plantilla')
@section('contenido')
@can('equipos_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Agregar Equipo nuevo</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarEquipo" method="post">
        @csrf
        <div class="form-row">
        <div class="form-group col-md-3">
            <label for="nombre">Nombre: </label>
            <input type="text" name="nombre" value="{{old('nombre')}}" maxlength="45" class="form-control" id="nombre">
        </div>
        <div class="form-group col-md-3">
            <label for="num_dispositivo">Artículo: </label>
            <select class="form-control" name="num_dispositivo" id="num_dispositivo">
                <option value="">Seleccione un Dispositivo...</option>
                @foreach ($dispositivos as $dispositivo)
                    <option value="{{$dispositivo['id']}}">{{$dispositivo['modelo']}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="num_antena">Antena: </label>
            <select class="form-control" name="num_antena" id="num_antena">
                <option value="">Seleccione una Antena...</option>
                @foreach ($antenas as $antena)
                    <option value="{{$antena['id']}}">{{$antena['descripcion']}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="mac_address">Mac Address: </label>
            <input type="text" name="mac_address" value="{{old('mac_address')}}" maxlength="17" class="form-control" id="mac_address">
        </div>
        <div class="form-group col-md-3">
            <label for="ip">Ip: </label>
            <input type="text" name="ip" value="{{old('ip')}}" maxlength="15" class="form-control" id="ip">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-9">
            <label for="comentario">Comentario: </label>
            <textarea name="comentario" class=" form-control" id="comentario" rows="auto" cols="50">{{old('comentario')}}</textarea>
        </div>
    </div>
    
            <button type="submit" class="btn btn-primary" id="enviar">Crear nuevo</button>
            <a href="/adminEquipos" class="btn btn-primary">volver</a>
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