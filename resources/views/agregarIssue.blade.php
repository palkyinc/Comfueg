@extends('layouts.plantilla')
@section('contenido')
@can('issues_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Nuevo Pedido de Asistencia Técnica</h3>
<div class="alert bg-light border col-8 mx-auto p-4">
    @if ($cliente)
        <form action="/agregarIssue" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group border col-md-6">
                    <label for="tipo" class="mx-3">Cliente:<br>
                        {{$cliente->getNomYApe()}}<br>
                        {{$cliente->relCodAreaCel->codigoDeArea}} - {{$cliente->celular}}
                    </label>
                    <input type="hidden" name='cliente_id' value='{{$cliente->id}}'>
                </div>
                <div class="form-group col-md-6">
                    <label for="afectado">Contrato: </label>
                    <select class="form-control" name="afectado">
                        <option value="">Sin Contrato</option>
                        @foreach ($contratos as $elemento)
                        <option value="{{$elemento->id}}"
                            @if (null != old('afectado') && old('afectado') == $elemento->id)
                                selected
                            @endif
                                                        >{{$elemento->id . ' | ' . 
                                                            $elemento->relDireccion->relCalle->nombre . ', ' . 
                                                            $elemento->relDireccion->numero . ', ' . 
                                                            $elemento->relDireccion->relBarrio->nombre}}</option>
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
                            <option value="{{$titulo->id}}" {{ null !== (old('titulo')) ? (old('titulo') == $titulo->id ? 'selected' : '') : '' }}>{{$titulo->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="asignado" class="mx-3">Asignado a:</label>
                    <select name="asignado" class="form-control">
                        <option value="">Seleccione uno...</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{$usuario->id}}" {{ null !== (old('asignado')) ? (old('asignado') == $usuario->id ? 'selected' : '') : '' }}>{{$usuario->name}}</option>
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
                <div class="form-row ">
                    <div class="input-group col-md-8">
                        <label for="">Seleccionar usuarios en seguimiento:
                            <div class="form-row">
                                @foreach ($usuarios as $usuario)
                                    <div class="col-md-4">
                                        <input type="checkbox" name="viewer{{$usuario->id}}" value="{{$usuario->id}}">
                                        <label for="{{$usuario->id}}"> {{$usuario->name}} </label><br>
                                    </div>
                                @endforeach
                            </div>
                        </label>
                    </div>
                </div>
                {{-- <div class="form-row">
                    <div class="form-group">
                        <label for="scheme_file">Seleccionar o arrastrar archivos para adjuntar a la Incidencia.</label>
                        <input type="file" name="scheme_file[]" class="form-control-file" multiple>
                    </div>
                </div> --}}
                <button type="submit" class="btn btn-primary" id="enviar">Crear Nueva</button>
                <a href="/adminIssues" class="btn btn-primary">Volver</a>
        </form>    
    @else
        @if ( session('mensaje') )
            <div class="alert alert-info">
                @foreach (session('mensaje') as $item)
                    {{ $item }} <br>
                @endforeach
            </div>
        @endif
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
    @endif
    
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