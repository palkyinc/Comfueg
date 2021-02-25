@extends('layouts.plantilla')

@section('contenido')


@can('paneles_edit')
@php
$mostrarSololectura = true;
@endphp
<h3>Modificando Usuario y contraseña de Equipo con ID: {{ $elemento->id }}</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarEquipoUserPass" method="post">
        @csrf
        @method('patch')
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="usuario">Usuario: </label>
                <input type="text" name="usuario" value="{{old('usuario') ?? $elemento->usuario}}" class="form-control" id="usuario">
            </div>
            
            <div class="form-group col-md-4">
                <label for="password">Contrseña: </label>
                <input type="text" name="password" value="{{old('password') ?? $elemento->password}}" class="form-control" id="password" autocomplete="off">
            </div>
        </div>
        <input type="hidden" name="id" value="{{$elemento->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
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