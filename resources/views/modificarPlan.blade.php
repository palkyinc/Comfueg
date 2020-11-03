@extends('layouts.plantilla')

@section('contenido')


    <h3>Modificando Plan con ID: {{ $elemento->id }}</h3>

    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarPlan" method="post">
        @csrf
        @method('patch')

        
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" value="{{$elemento->nombre}}" maxlength="30"  class="form-control">
            </div>
            
            <div class="form-group col-md-4">
                <label for="bajada">Bajada: </label>
                <input type="text" name="bajada" value="{{$elemento->bajada}}" maxlength="60"  class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="subida">Subida: </label>
                <input type="text" name="subida" value="{{$elemento->subida}}" maxlength="15"  class="form-control">
            </div>
        </div>
        <div class="form-row">
        </div>    
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="descripcion">Descripción: </label>
                <textarea name="descripcion" class="form-control" id="descripcion" rows="auto" cols="15">{{$elemento->descripcion}}</textarea>
            </div>
        </div>    
    
            <input type="hidden" name="id" value="{{$elemento->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
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
        
@endsection