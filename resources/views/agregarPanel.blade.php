@extends('layouts.plantilla')

@section('contenido')


    <h3>Agregar Panel nuevo</h3>

    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarPanel" method="post" enctype="multipart/form-data">
        @csrf
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="ssid">SSID: </label>
                    <input type="text" name="ssid" value="" maxlength="15" class="form-control" id="ssid">
                    <input type="hidden" name="activo" value="0" class="form-control" id="activo">
                </div>
                <div class="form-group col-md-3">
                    <label for="rol">Rol: </label>
                    <select class="form-control" name="rol" id="rol">
                        <option value="">Seleccione un Rol...</option>
                        @foreach ($roles as $key => $rol)
                            <option value="{{$key}}">{{$rol}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="id_equipo">Equipo: </label>
                    <select class="form-control" name="id_equipo" id="id_equipo">
                        <option value="">Seleccione un Equipo...</option>
                        @foreach ($equipos as $dato)
                            <option value="{{$dato->id}}">{{$dato->nombre}}->{{$dato->ip}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="num_site">Sitio: </label>
                    <select class="form-control" name="num_site" id="num_site">
                        <option value="">Seleccione un Sitio...</option>
                        @foreach ($sitios as $sitio)
                            <option value="{{$sitio->id}}">{{$sitio->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="panel_ant">Panel Anterior: </label>
                    <select class="form-control" name="panel_ant" id="panel_ant">
                        <option value="">Gateway...</option>
                        @foreach ($paneles as $panel)
                            @if ($panel->activo)
                                <option value="{{$panel->id}}">{{$panel->relEquipo->nombre}}->{{$panel->relEquipo->ip}}</option>';
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-9">
                    <div class="custom-file">
                        <label for="cobertura">Archivo de Cobertura: (Solo PNG/JPG/SVG)</label>
                        <input type="file" class="form-control-file" id="cobertura" name="cobertura">
                    </div>
                </div>
            </div>
            <br>
            <div class="form-row">
                <div class="form-group col-md-9">
                    <label for="comentario">Comentario: </label>
                    <textarea name="comentario" class="form-control" id="comentario" rows="auto" cols="50"></textarea>
                </div>
            </div>
    
            <input type="hidden" name="id" value="">
            <button type="submit" class="btn btn-primary" id="enviar">Crear Nuevo</button>
            <a href="/adminPaneles" class="btn btn-primary">volver</a>
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