@extends('layouts.plantilla')

@section('contenido')


    <h3>Nuevo Rol</h3>
@can('roles_create')
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarRole" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Nombre: </label>
                <input type="text" name="name" value="" maxlength="255"  class="form-control">
            </div>
        </div>
            <button type="submit" class="btn btn-primary" id="enviar">Crear Nuevo</button>
            <a href="/adminRoles" class="btn btn-primary">volver</a>
        
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
@endsection