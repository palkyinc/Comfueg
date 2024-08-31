@extends('layouts.plantilla')
@section('contenido')
@can('issues_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Suspender cuenta por Mora</h3>
<div class="alert bg-light border col-8 mx-auto p-4">
        <form action="/agregarIssueSuspend" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group border col-md-6">
                    <label for="tipo" class="mx-3">Cliente:<br>
                        {{$contrato->relCliente->getNomYApe()}}<br>
                        {{$contrato->relCliente->relCodAreaCel->codigoDeArea}} - {{$contrato->relCliente->celular}}
                    </label>
                    <input type="hidden" name='cliente_id' value='{{$contrato->relCliente->id}}'>
                </div>
                <div class="form-group border col-md-6">
                    <label for="afectado">Contrato: {{$contrato->id}}<br>
                        {{$contrato->relDireccion->relCalle->nombre . ', ' . 
                        $contrato->relDireccion->numero . ', ' . 
                        $contrato->relDireccion->relBarrio->nombre}}
                    </label>
                    <input type="hidden" name='contrato_id' value='{{$contrato->id}}'>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group border col-md-6">
                    <label for="titulo" class="mx-3">Título: <br>
                        {{$titulo->title}}
                    </label>
                </div>
                <div class="form-group border col-md-6">
                    <label for="asignado" class="mx-3">Asignado a:<br>
                        {{$user->name}}
                    </label>
                    <input type="hidden" name='user_id' value='{{$user->id}}'>
                </div>
            </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="descripcion">Motivo de la suspensión: </label>
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
                <button type="submit" class="btn btn-primary" id="enviar">Crear</button>
                <a href="/adminIssues" class="btn btn-primary">Volver</a>
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