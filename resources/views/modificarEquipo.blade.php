@extends('layouts.plantilla')

@section('contenido')


@can('equipos_edit')
@php
$mostrarSololectura = true;
@endphp
<h3>Modificando Equipo con ID: {{ $elemento->id }}</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarEquipo" method="post">
        @csrf
        @method('patch')

        <div class="form-row">
        <div class="form-group col-md-3">
            <label for="nombre">Nombre: </label>
            <input type="text" name="nombre" value="{{$elemento->nombre}}" maxlength="45" class="form-control" id="nombre">
        </div>
        <div class="form-group col-md-3">
            <label for="num_dispositivo">Dispositivo: </label>
            <select class="form-control" name="num_dispositivo" id="num_dispositivo">
                <option value="">Seleccione un Dispositivo...</option>
                @foreach ($dispositivos as $dispositivo)
                @if ($dispositivo['id'] != $elemento->num_dispositivo)
                    <option value="{{$dispositivo['id']}}">{{$dispositivo['modelo']}}</option>
                @else
                    <option value="{{$dispositivo['id']}}" selected>{{$dispositivo['modelo']}}</option>
                @endif                
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="num_antena">Antena: </label>
            <select class="form-control" name="num_antena" id="num_antena">
                <option value="">Seleccione una Antena...</option>
                @foreach ($antenas as $antena)
                    @if ($antena['id'] != $elemento->num_antena)
                        <option value="{{$antena['id']}}">{{$antena['descripcion']}}</option>
                    @else
                        <option value="{{$antena['id']}}" selected>{{$antena['descripcion']}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="mac_address">Mac Address: </label>
            <input type="text" name="mac_address" value="{{$elemento->mac_address}}" maxlength="17" class="form-control" id="mac_address" readonly>
        </div>
        <div class="form-group col-md-3">
            <label for="ip">Ip: </label>
            <input type="text" name="ip" value="{{$elemento->ip}}" maxlength="15" class="form-control" id="ip">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="fecha_alta">Alta: </label>
            <input type="hidden" name="fecha_alta" value="{{$elemento->fecha_alta}}" id="fecha_alta">
            <div class="form-control" readonly>{{$elemento->fecha_alta}}</div>
        </div>
        <div class="form-group col-md-3">
            <label for="fecha_baja">Baja: </label>
            <div class="form-control" readonly>{{$elemento->fecha_baja}}</div>
            <input type="hidden" name="fecha_baja" value="{{$elemento->fecha_baja}}" id="fecha_baja">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-9">
            <label for="comentario">Comentario: </label>
            <textarea name="comentario" class=" form-control" id="comentario" rows="auto" cols="50">{{$elemento->comentario}}</textarea>
        </div>
    </div>
    
            <input type="hidden" name="id" value="{{$elemento->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
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