@extends('layouts.plantilla')

@section('contenido')


    <h3>Nuevo Incidente Global</h3>
@can('SiteHasIncidente_create')
@endcan
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarSiteHasIncidente" method="post">
        @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="tipo" class="mx-3">Tipo</label>
                    <select class="form-control" name="tipo">
                        <option value="">Seleccione un Tipo de Incidente...</option>
                        <option value="INCIDENTE">Incidente Global</option>
                        <option value="DEUDA TECNICA">Deuda Técnica</option>
                        
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="inicio">Inicio: </label>
                    <input type="datetime-local" name="inicio" value="{{old('inicio')}}" class="form-control">
                </div>
            </div>
            <div class="form-row">
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
                    <label for="causa">Causa/Diagnóstico: </label>
                    <textarea name="causa" class="form-control" rows="auto" cols="50">{{old('causa')}}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="mensaje_clientes">Mensaje para Clientes: </label>
                    <textarea name="mensaje_clientes" class="form-control" rows="auto" cols="50">{{old('mensaje_clientes')}}</textarea>
                </div>
            </div>
                <button type="submit" class="btn btn-primary" id="enviar">Crear Nueva</button>
                <a href="/adminIncidencias" class="btn btn-primary">volver</a>
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
@endsection