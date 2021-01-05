@extends('layouts.plantilla')

@section('contenido')


@can('antenas_edit')
@php
$mostrarSololectura = true;
@endphp
<h3>Modificando Antena con ID: {{ $elemento->id }}</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarAntena" method="post">
        @csrf
        @method('patch')

        
        <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="descripcion">Descripción: </label>
                    <input type="text" name="descripcion" value="{{$elemento->descripcion}}" maxlength="30"  class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label for="cod_comfueg">Código Comfueg: </label>
                    <input type="text" name="cod_comfueg" value="{{$elemento->cod_comfueg}}" maxlength="45" class="form-control">
                </div>
        </div>
            
    
            <input type="hidden" name="id" value="{{$elemento->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
            <a href="/adminAntenas" class="btn btn-primary">volver</a>
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