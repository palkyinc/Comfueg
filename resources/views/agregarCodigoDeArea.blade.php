@extends('layouts.plantilla')

@section('contenido')


    <h3>Nuevo Código de Área</h3>

    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarCodigoDeArea" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="codigoDeArea">Código de Área: </label>
                <input type="text" name="codigoDeArea" value="" maxlength="4"  class="form-control">
            </div>
            <div class="form-group col-md-6">
                <label for="provincia">Provincia: </label>
                <input type="text" name="provincia" value="" maxlength="45"  class="form-control">
            </div>
        </div>
        <div class="form-row">      
                <div class="form-group col-md-12">
                  <label for="localidades">Localidades: </label>
                  <textarea name="localidades" class="form-control"></textarea>
                </div>
        </div>
            <button type="submit" class="btn btn-primary" id="enviar">Crear Nuevo</button>
            <a href="/adminCodigosDeArea" class="btn btn-primary">volver</a>
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