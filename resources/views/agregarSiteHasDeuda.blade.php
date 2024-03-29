@extends('layouts.plantilla')
@section('contenido')
@can('SiteHasIncidente_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Nueva Deuda Técnica</h3>
<div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarSiteHasDeuda" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="tipo" class="mx-3">Tipo</label>
                <select class="form-control" name="tipo" readonly>
                    <option value="DEUDA TECNICA">Deuda Técnica</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="inicio">Inicio: </label>
                <input type="datetime-local" name="inicio" value="{{old('inicio')}}" class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="precedencia">Precedencia:</label>
                <select name="precedencia" class="form-control">
                        <option value="" selected>Seleccione precedencia...</option>
                    @foreach ($deudas as $deuda)
                        <option value="{{$deuda->id}}">{{$deuda->mensaje_clientes}} | {{$deuda->relPanel->relSite->nombre}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="fecha_tentativa">A realizar el: </label>
                <input type="datetime-local" name="fecha_tentativa" value="{{old('fecha_tentativa')}}" class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="prioridad">Prioridad:</label>
                <select name="prioridad" class="form-control">
                    <option value="1">Alta</option>
                    <option value="2">Media</option>
                    <option value="3">Baja</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="mensaje_clientes">Título: </label>
                <input type="text" name="mensaje_clientes" value="{{old('mensaje_clientes')}}" class="form-control">
            </div>    
            <div class="form-group col-md-6">
                <label for="afectado" class="mx-3">Equipo Afectado</label>
                <select class="form-control" name="afectado">
                    <option value="">Seleccione un Equipo...</option>
                    @foreach ($paneles as $panel)
                    <option value="{{$panel->id}}">{{$panel->relEquipo->nombre . ' | ' . $panel->relEquipo->ip}}</option>
                    @endforeach
                </select>
            </div>
        </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="causa">Deuda Técnica: </label>
                    <textarea name="causa" class="form-control" rows="auto" cols="50">{{old('causa')}}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="scheme_file">Seleccionar o arrastrar archivos para adjuntar a la Incidencia.</label>
                    <input type="file" name="scheme_file[]" class="form-control-file" multiple>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="enviar">Crear Nueva</button>
            <a href="/adminDeudasTecnica" class="btn btn-primary">Volver</a>
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