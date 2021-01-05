@extends('layouts.plantilla')
@section('contenido')
@can('calles_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Nueva Calle</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarCalle" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" value="{{old('nombre')}}" maxlength="45" class="form-control">
            </div>
        </div>
            <button type="submit" class="btn btn-primary" id="enviar">Crear Nueva</button>
            <a href="/adminCalles" class="btn btn-primary">volver</a>
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