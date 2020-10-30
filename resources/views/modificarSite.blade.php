@extends('layouts.plantilla')

@section('contenido')


    <h3>Modificando Sitio con ID: {{ $elemento->id }}</h3>

    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarSite" method="post">
        @csrf
        @method('patch')

        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" value="{{$elemento->nombre}}" maxlength="30"  class="form-control">
            </div>
            
            <div class="form-group col-md-6">
                <label for="coordenadas">Coordenadas: </label>
                <input type="text" name="coordenadas" value="{{$elemento->coordenadas}}" maxlength="60"  class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="rangoIp">Inicio Rango de IP: </label>
                <input type="text" name="rangoIp" value="{{$elemento->rangoIp}}" maxlength="15"  class="form-control">
            </div>
            <div class="form-group col-md-6">
                <label for="ipDisponible">IP Disponible: </label>
                <input type="text" name="ipDisponible" value="{{$elemento->ipDisponible}}" maxlength="15"  class="form-control">
            </div>
        </div>    
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="descripcion">Descripción: </label>
                <textarea name="descripcion" class="form-control" id="descripcion" rows="auto" cols="15">{{$elemento->descripcion}}</textarea>
            </div>
        </div>    
    
            <input type="hidden" name="id" value="{{$elemento->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
            <a href="/adminSites" class="btn btn-primary">volver</a>
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