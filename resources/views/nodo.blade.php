@extends('layouts.plantilla')
@section('contenido')
@can('nodos_index')        
@php
$mostrarSololectura = true;
@endphp
<a id="cielo"></a>
<h2>Sitio: {{$site->nombre}}</h2>
<div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Equipos:</h5>
        <details class="card-text">
            <summary>Paneles</summary>
            <ul>
                @foreach ($paneles as $panel)
                    @if ($panel->rol == 'PANEL')
                        <li>
                            <a href="#{{$panel->ssid}}" class="btn btn-info btn-sm">{{$panel->ssid}}</a>
                        </li>
                    @endif
                @endforeach
            </ul>  
        </details>
        <details class="card-text">
            <summary>Punto a punto</summary>
            <ul>
                @foreach ($paneles as $panel)
                    @if ($panel->rol == 'PTPST' || $panel->rol == 'PTPAP')
                        <li>
                            <a href="#{{$panel->ssid}}" class="btn btn-info btn-sm">{{$panel->ssid}}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </details>
        <details class="card-text">
            <summary>Equipos Conectividad / UPS</summary>
            <ul>
                @foreach ($paneles as $panel)
                    @if ($panel->rol == 'SWITCH')
                        <li>
                            <a href="#{{$panel->ssid}}" class="btn btn-info btn-sm">{{$panel->ssid}}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </details>
        <details class="card-text">
            <summary>Informes</summary>
            <ul>
                @foreach ($archivos as $archivo)
                    @if (null != $archivo && $archivo->relModelo->nombre == 'SITIO' && $archivo->tipo == 'FILE')
                        <li>
                            <a href="/imgUsuarios/pdf/{{$archivo->file_name}}" class="btn btn-link btn-sm" target="_blank">
                                {{$archivo->file_name}}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </details>
        <br> 
        <footer class="blockquote-footer">
            @can('nodos_edit')
            <a href="/adminArchivosSitio/{{$site->id}}" class="btn btn-primary">Modificar Archivos</a>
            @endcan
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdropPhoto">
            Fotos
            </button>
            <a href="/adminNodos" class="btn btn-primary">Volver</a>
            @include('modals.photosCarrusel')
        </footer>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card">
        <div class="card-body">
            @foreach ($archivos as $archivo)
                @if (null != $archivo && $archivo->relModelo->nombre == 'SITIO' && $archivo->tipo == 'SCHEME' && $archivo->entidad_id == $site->id)
                    <img class="card-img-top" src="/imgUsuarios/{{$archivo->file_name}}" alt="Esquema de la rama">
                @endif
            @endforeach
        <h5 class="card-title">Esquema de la rama.</h5>
        @can('nodos_edit')
        <a href="/cambiarFileSitio/{{$site->id}}" class="btn btn-primary">Cambiar</a>
        @endcan
      </div>
    </div>
  </div>
</div>

@foreach ($paneles as $panel)
<a id="{{$panel->ssid}}"></a>
<div class="row">
    <div class="col-sm-6">
        <div class="card bg-light mb-3">
            <div class="card-header">{{$panel->rol}}</div>
            <div class="card-body">
                <h5 class="card-title">Detalles Técnicos</h5>
                <ul class="card-test">
                    <li>SSID: {{$panel->ssid}}</li>
                    <li>Nombre del Equipo: {{$panel->relEquipo->nombre}}</li>
                    <li>IP: 
                        <a href="https://{{$panel->relEquipo->ip}}" class="btn btn-link btn-sm" target="_blank">
                                {{$panel->relEquipo->ip}}
                            </a>
                        </li>
                        <li>Dispositivo: {{$panel->relEquipo->relProducto->modelo}}</li>
                        <li>Antena: {{$panel->relEquipo->relAntena->descripcion}}</li>
                        <li>Alta de equipo: {{$panel->relEquipo->fecha_alta}}</li>
                        <li>Altura: {{$panel->altura}}</li>
                        <li>Comentarios: {{$panel->comentario}}</li>
                    </ul>
                    <footer class="blockquote-footer">
                <a href="#cielo" class="btn btn-info btn-sm">Ir arriba</a>
            </footer>
        </div>
    </div>
</div>
<div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                @foreach ($imagenes as $imagen)
                @if (null != $imagen && $imagen->relModelo->nombre == 'PANEL' && $imagen->entidad_id == $panel->id)
                <img class="card-img-top" src="/imgUsuarios/{{$imagen->file_name}}" alt="Esquema de la rama">
                @endif
                @endforeach
                <h5 class="card-title">Cobertura / esquema de conexión</h5>
                @can('nodos_edit')
                <a href="/cambiarFilePanel/{{$panel->id}}/{{$site->id}}" class="btn btn-primary">Cambiar</a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endforeach
@endcan
@include('sinPermiso')
@endsection