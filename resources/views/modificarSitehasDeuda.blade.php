@extends('layouts.plantilla')
@section('contenido')
@can('SiteHasIncidente_edit')
@php
$mostrarSololectura = true;
@endphp
<h3>Modificando Deuda Técnica</h3>
<div class="alert bg-light border col-10 mx-auto p-4">
    <form action="/modificarSiteHasDeuda" method="post" enctype="multipart/form-data">
        @csrf
        @method('patch')
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="tipo" class="mx-3">Tipo</label>
                    <input id="tipo" type="text" name="tipo" value="{{$incidente->tipo}}" class="form-control" readonly>
                </div>
                <div class="form-group col-md-3">
                    <label for="inicio">Inicio: </label>
                    <input id="inicio" type="datetime-local" name="inicio" value="{{$incidente->inicioDateTimeLocal()}}" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label for="final">Final: </label>
                    <input id="final" type="datetime-local" name="final" value="{{old('final')}}" class="form-control">
                </div>
                <div class="form-group col-md-2">
                    <label for="afectado">Equipo Afectado: </label>
                    <input id="afectado" type="text" name="afectado" value="{{$incidente->relPanel->relEquipo->nombre}}" class="form-control" readonly>
                </div>
                <div class="form-group col-md-2">
                    <label for="user_creator">Creado Por: </label>
                    <input id="user_creator" type="text" name="user_creator" value="{{$incidente->relUser->name}}" class="form-control" readonly>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="mensaje_clientes">Título</label>
                    <input type="text" name="mensaje_clientes" value="{{$incidente->mensaje_clientes}}" class="form-control" readonly>
                </div>
                <div class="form-group col-md-2">
                    <label for="sitio">Sitio</label>
                    <input type="text" name="sitio" value="{{ $incidente->relPanel->relSite->nombre }}" class="form-control" readonly>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="causa">Deuda Técnica: </label>
                    <textarea name="causa" class="form-control" rows="auto" cols="50" readonly>{{$incidente->causa}}</textarea>
                </div>
            </div>
            @foreach ($incidente->incidente_has_mensaje as $mensaje)
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="actualizacionOld">Actualización realizada por: {{$mensaje->relUser->name}} el {{$mensaje->created_at}} </label>
                        <textarea name="actualizacionOld" class="form-control" rows="auto" cols="50" readonly>{{$mensaje->mensaje}}</textarea>
                    </div>
                </div>
            @endforeach
            
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="actualizacion">Actualización: </label>
                    <textarea name="actualizacion" class="form-control" rows="auto" cols="50">{{old('actualizacion')}}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                <label for="scheme_file">Seleccionar o arrastrar archivos para adjuntar a la Incidencia.</label>
                <input type="file" id="scheme_file" name="scheme_file[]" class="form-control-file" multiple>
                </div>
            </div>
            <input type="hidden" name="id" value="{{$incidente->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
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