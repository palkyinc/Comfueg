@extends('layouts.plantilla')
@section('contenido')
@can('planes_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Agregar Plan nuevo</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarPlan" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" value="{{old('nombre')}}" maxlength="30"  class="form-control">
            </div>
            
            <div class="form-group col-md-4">
                <label for="bajada">Bajada (kb): </label>
                <input type="text" name="bajada" value="{{old('bajada')}}" maxlength="60"  class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="subida">Subida (kb): </label>
                <input type="text" name="subida" value="{{old('subida')}}" maxlength="15"  class="form-control">
            </div>
        </div>
        <div>
            <h5>Ráfagas</h5>
            <div class="form-row border border-warning m-2 p-2">
                <div class="form-group col-md-4">
                    <label for="mbt">Max Burst Time (seg.)(*): </label>
                    <input type="text" name="mbt" value="{{old('mbt')}}" maxlength="30"  class="form-control">
                    <p>Duración de la Ráfaga</p>
                </div>
                
                <div class="form-group col-md-4">
                    <label for="br">Burst Rate (%)(*): </label>
                    <input type="text" name="br" value="{{old('br')}}" maxlength="60"  class="form-control">
                    <p>Max. Carga o descarga</p>
                </div>
                <div class="form-group col-md-4">
                    <label for="bth">Burst Threshold (%)(*): </label>
                    <input type="text" name="bth" value="{{old('bth')}}" maxlength="15"  class="form-control">
                    <p>Umbral de comparación de lo contratado.</p>
                </div>
            </div>
            <p>(*)Para no configurar ráfagas completar con cero.</p>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="descripcion">Descripción: </label>
                <textarea name="descripcion" class="form-control" id="descripcion" rows="auto" cols="15">{{old('descripcion')}}</textarea>
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
@include('sinPermiso')
@endsection