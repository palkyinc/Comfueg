@extends('layouts.plantilla')

@section('contenido')


    <h3>Modificando Código de Área con ID: {{ $elemento->id }}</h3>
@can('codigoDeArea_edit')
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarCodigoDeArea" method="post">
        @csrf
        @method('patch')

        
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="codigoDeArea">Código de Área: </label>
                <input type="text" name="codigoDeArea" value="{{$elemento->codigoDeArea}}" maxlength="4"  class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="provincia">Provincia: </label>
                <input type="text" name="provincia" value="{{$elemento->provincia}}" maxlength="45"  class="form-control">
            </div>
        </div>
        <div class="form-row">      
                <div class="form-group col-md-6">
                  <label for="localidades">Localidades: </label>
                  <textarea name="localidades" "class="form-control" rows="auto" cols="50">{{$elemento->localidades}}</textarea>
                </div>
        </div>
    
            <input type="hidden" name="id" value="{{$elemento->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
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
@endcan
@endsection