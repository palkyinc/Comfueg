@extends('layouts.plantilla')
@section('contenido')
@can('conceptoDebitos_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Nuevo Concepto para Débitos</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
        <form action="/agregarConceptoDebito" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="cod_concepto">Código de Concepto: </label>
                    <input type="text" name="cod_concepto" value="{{old('cod_concepto')}}" maxlength="45" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="descripcion">Descripción: </label>
                    <input type="text" name="descripcion" value="{{old('descripcion')}}" maxlength="30"  class="form-control">
                </div>
            </div>
                <button type="submit" class="btn btn-primary" id="enviar">Crear Nueva</button>
                <a href="/adminConceptoDebitos" class="btn btn-primary">volver</a>
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