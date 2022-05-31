@extends('layouts.plantilla')
@section('contenido')
@can('issues_titles_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Nuevo Título de Ticket</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
        <form action="/agregarIssueTitle" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="titulo">Título: </label>
                    <input type="text" name="titulo" value="{{old('titulo')}}" maxlength="50"  class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="tmr">TMR: </label>
                    <input type="text" name="tmr" value="{{old('tmr')}}" maxlength="3"  class="form-control">
                </div>
            </div>
                <button type="submit" class="btn btn-primary" id="enviar">Crear Nuevo</button>
                <a href="/adminIssuesTitles" class="btn btn-primary">volver</a>
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