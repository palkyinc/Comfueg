@extends('layouts.plantilla')
@section('contenido')
@can('issues_titles_edit')
@php
$mostrarSololectura = true;
@endphp
<h3>Modificando Titulo de Ticket con ID: {{ $elemento->id }}</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarIssueTitle" method="post">
        @csrf
        @method('patch')
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="title">Título: </label>
                <input type="text" name="title" value="{{$elemento->title}}" maxlength="45"  class="form-control">
            </div>
            <div class="form-group col-md-6">
                <label for="tmr">TMR: </label>
                <input type="text" name="tmr" value="{{$elemento->tmr}}" maxlength="45"  class="form-control">
            </div>
        </div>
            <input type="hidden" name="id" value="{{$elemento->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
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