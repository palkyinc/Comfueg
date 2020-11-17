@extends('layouts.plantilla')

@section('contenido')


    <h3>Modificando Usuario con ID: {{ $elemento->id }}</h3>

    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarUser" method="post">
        @csrf
        @method('patch')

        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Nombre: </label>
                <input type="text" name="name" value="{{$elemento->name}}" maxlength="255"  class="form-control">
            </div>
             <div class="form-group col-md-8">
                <label for="email">Correo Electrónico: </label>
                <input type="email" name="email" value="{{$elemento->email}}" class="form-control">
            </div>
        </div>
            <input type="hidden" name="id" value="{{$elemento->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
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
        
@endsection