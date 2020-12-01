@extends('layouts.plantilla')

    @section('contenido')
        <a id="cielo"></a>
        <h2>Sitio: {{$site->nombre}}</h2>
@can('nodos_index')        

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
                    @if (null != $archivo && $archivo->entidad == 'SITIO' && $archivo->tipo == 'FILE')
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
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
            Fotos
            </button>
            <a href="/adminNodos" class="btn btn-primary">Volver</a>

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Fotos del Sitio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Carrousel -->
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                             @php
                                    $number = 0;
                            @endphp
                                @foreach ($archivos as $archivo)
                                    @if (null != $archivo && $archivo->entidad == 'SITIO' && $archivo->tipo == 'PHOTO')
                                        @if ($number == 0)
                                            <li data-target="#carouselExampleIndicators" data-slide-to="{{$number}}" class="active"></li>
                                        @else
                                            <li data-target="#carouselExampleIndicators" data-slide-to="{{$number}}"></li>
                                        @endif
                                        @php
                                            $number++;
                                        @endphp
                                    @endif
                                @endforeach
                    </ol>
                    <div class="carousel-inner">
                        @php
                            $active = true;
                        @endphp
                            @foreach ($archivos as $archivo)
                            @if (null != $archivo && $archivo->entidad == 'SITIO' && $archivo->tipo == 'PHOTO')
                                @if ($active === true)
                                    <div class="carousel-item active">
                                    <img src="/imgUsuarios/photos/{{$archivo->file_name}}" class="d-block w-100" alt="{{$archivo->file_name}}">
                                        <div class="carousel-caption d-none d-md-block">
                                            <p>{{$archivo->file_name}}</p>
                                        </div>
                                    </div>
                                    @php
                                        $active = false;
                                    @endphp
                                @else
                                    <div class="carousel-item ">
                                    <img src="/imgUsuarios/photos/{{$archivo->file_name}}" class="d-block w-100" alt="{{$archivo->file_name}}">
                                        <div class="carousel-caption d-none d-md-block">
                                            <p>{{$archivo->file_name}}</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
            </div>

        </footer>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card">
        <div class="card-body">
            @foreach ($archivos as $archivo)
                @if (null != $archivo && $archivo->entidad == 'SITIO' && $archivo->tipo == 'SCHEME' && $archivo->entidad_id == $site->id)
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
                    <li>IP: {{$panel->relEquipo->ip}}</li>
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
                    @if (null != $imagen && $imagen->entidad == 'PANEL' && $imagen->entidad_id == $panel->id)
                        <img class="card-img-top" src="/imgUsuarios/{{$imagen->file_name}}" alt="Esquema de la rama">
                    @endif
                @endforeach
                <h5 class="card-title">Cobertura / esquema de conexión</h5>
                @can('nodos_edit')
                <a href="/cambiarFilePanel/{{$panel->id}}" class="btn btn-primary">Cambiar</a>
                @endcan
            </div>
        </div>
    </div>
</div>

@endforeach
@endcan
@endsection