@extends('layouts.plantilla')

@section('contenido')


    <h3>Agregar Sitio nuevo</h3>

    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarSite" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" value="" maxlength="30"  class="form-control">
            </div>
            
            <div class="form-group col-md-6">
                <label for="coordenadas">Coordenadas: </label>
                <input type="text" name="coordenadas" value="" maxlength="60"  class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="rangoIp">Inicio Rango de IP: </label>
                <input type="text" name="rangoIp" value="" maxlength="15"  class="form-control">
            </div>
            <div class="form-group col-md-6">
                <label for="ipDisponible">IP Disponible: </label>
                <input type="text" name="ipDisponible" value="" maxlength="15"  class="form-control">
            </div>
        </div>    
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="descripcion">Descripción: </label>
                <textarea name="descripcion" class="form-control" id="descripcion" rows="auto" cols="15"></textarea>
            </div>
        </div>    
    
            <button type="submit" class="btn btn-primary" id="enviar">Crear nuevo</button>
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