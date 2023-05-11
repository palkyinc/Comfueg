@extends('layouts.plantilla')
@section('contenido')
@can('calles_index')
@php
$mostrarSololectura = true;
@endphp
<h3>Cargando nuevo archivo Calles.txt</h3>
<div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/actualizarCalle" method="post" enctype="multipart/form-data">
        @csrf
        @method('post')
        <div class="form-group">
            <label for="scheme_file">Seleccionar o arrastrar archivo</label>
            <input type="file" name="scheme_file" class="form-control-file">
        </div>
        <button type="submit" class="btn btn-primary">Cargar</button>
        <a href="/adminCalles" class="btn btn-primary">Volver</a>
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