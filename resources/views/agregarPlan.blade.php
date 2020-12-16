@extends('layouts.plantilla')

@section('contenido')


    <h3>Agregar Plan nuevo</h3>
@can('planes_create')
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarPlan" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" value="{{old('nombre')}}" maxlength="30"  class="form-control">
            </div>
            
            <div class="form-group col-md-4">
                <label for="bajada">Bajada: </label>
                <input type="text" name="bajada" value="{{old('bajada')}}" maxlength="60"  class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="subida">Subida: </label>
                <input type="text" name="subida" value="{{old('subida')}}" maxlength="15"  class="form-control">
            </div>
        </div>
        <div class="form-row">
        </div>    
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="descripcion">Descripción: </label>
                <textarea name="descripcion" class="form-control" id="descripcion" rows="auto" cols="15">{{old('decripcion')}}</textarea>
            </div>
        </div>    
    
            <button type="submit" class="btn btn-primary" id="enviar">Crear nuevo</button>
            <a href="/adminPlanes" class="btn btn-primary">volver</a>
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