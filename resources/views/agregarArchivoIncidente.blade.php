@extends('layouts.plantilla')

@section('contenido')
@can('SiteHasIncidente_edit')
@php
$mostrarSololectura = true;
@endphp
<h3>Agregando pdf o foto al Incidente con ID: {{ $incidente_id }}</h3> <!--  -->
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/adminArchivosIncidente" method="post" enctype="multipart/form-data">
        @csrf
        @method('patch')
            <div class="form-group">
                <label for="scheme_file">Seleccionar o arrastrar archivo</label>
                <input type="file" name="scheme_file[]" class="form-control-file" multiple>
            </div>
            <input type="hidden" name="incidenteId" value="{{$incidente_id}}">
            <button type="submit" class="btn btn-primary">Cargar</button>
            <a href="/adminArchivosIncidente/{{$incidente_id}}" class="btn btn-primary">Volver</a>
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