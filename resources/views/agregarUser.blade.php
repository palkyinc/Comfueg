@extends('layouts.plantilla')
@section('contenido')
@can('usuarios_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Nuevo Usuario</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarUser" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Nombre: </label>
                <input type="text" name="name" value="{{old('name')}}" maxlength="255"  class="form-control">
            </div>
             <div class="form-group col-md-8">
                <label for="email">Correo Electrónico: </label>
                <input type="text" name="email" value="{{old('email')}}" class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="password">Contraseña: </label>
                <input type="password" name="password" value="{{old('password')}}" maxlength="25"  class="form-control">
            </div>
        </div>
            <button type="submit" class="btn btn-primary" id="enviar">Crear Nuevo</button>
            <a href="/adminUsers" class="btn btn-primary">Volver</a>
        
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