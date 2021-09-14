@extends('layouts.plantilla')
@section('contenido')
@can('issues_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Seleccionando Cliente:</h3>
<div class="alert bg-light border col-8 mx-auto p-4">
    @foreach ($clientes as $cliente)
        <div class="form-row border">
            <div class="form-group col-md-6">
                {{$cliente->getNomYApe()}}
            </div>
            <a href="/agregarIssue?cliente_id={{$cliente->id}}" class="btn btn-primary">Seleccionar</a>
        </div>
    @endforeach
    <a href="/adminIssues" class="btn btn-primary">Volver</a>
    {{-- @if ($cliente && $contrato)
        <form action="/agregarIssue" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="tipo" class="mx-3">Cliente:</label>
                    <select class="form-control" name="afectado">
                        <option value="">Seleccione uno...</option>
                        @foreach ($paneles as $panel)
                        <option value="{{$panel->id}}">{{$panel->relEquipo->nombre . ' | ' . $panel->relEquipo->ip}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="inicio">Contrato: </label>
                    <select class="form-control" name="afectado">
                        <option value="">Seleccione uno...</option>
                        @foreach ($paneles as $panel)
                        <option value="{{$panel->id}}">{{$panel->relEquipo->nombre . ' | ' . $panel->relEquipo->ip}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="titulo" class="mx-3">Título</label>
                    <select class="form-control" name="titulo">
                        <option value="">Seleccione uno...</option>
                        @foreach ($titulos as $titulo)
                            <option value="{{$titulo->id}}">{{$titulo->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="descripcion">Descripción de la problematica: </label>
                        <textarea name="descripcion" class="form-control" rows="auto" cols="50">{{old('descripcion')}}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="scheme_file">Seleccionar o arrastrar archivos para adjuntar a la Incidencia.</label>
                        <input type="file" name="scheme_file[]" class="form-control-file" multiple>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" id="enviar">Crear Nueva</button>
                <a href="/adminIssues" class="btn btn-primary">Volver</a>
            </form>    
    @else
        <form action="/buscarIssueCliente" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cliente">Cliente: </label>
                    <input type="text" name="cliente" value="{{old('cliente')}}" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="/adminIssues" class="btn btn-primary">Volver</a>
        </form>
    @endif --}}
    
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