@extends('layouts.plantilla')

    @section('contenido')
        <h2>Sitio: {{$site->nombre}}</h2>
            <div class="container-fluid" style="border: 2px solid;">
                        <div class="row">
                            <div class="col" style="border: 2px solid;">
                                    
                            </div>
                            <div class="col" style="border: 2px solid;">
                            Esquema de la rama.
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="border: 2px solid;">
                                <div class="card bg-light mb-3">
                                <div class="card-header">Panel 1</div>
                                <div class="card-body">
                                    <h5 class="card-title">Detalles Técnicos</h5>
                                    <ul class="card-test">
                                        <li>SSID</li>
                                        <li>Nombre del Equipo</li>
                                        <li>IP</li>
                                        <li>Dispositivo</li>
                                        <li>Antena: </li>
                                        <li>Fecha de Alta: </li>
                                        <li>Altura: </li>
                                        <li>Comentarios: </li>
                                    </ul>
                                </div>
                                </div>
                            </div>
                            <div class="col" style="border: 2px solid;">
                            Cobertura / esquema de conexión
                            </div>
                        </div>
            </div>
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
                        <li>{{$panel->ssid}}</li>
                    @endif
                @endforeach
            </ul>  
        </details>
        <details class="card-text">
            <summary>Punto a punto</summary>
            <ul>
                @foreach ($paneles as $panel)
                    @if ($panel->rol == 'PTPST' || $panel->rol == 'PTPAP')
                        <li>{{$panel->ssid}}</li>
                    @endif
                @endforeach
            </ul>
        </details>
        <details class="card-text">
            <summary>Equipos Conectividad / UPS</summary>
            <ul>
                <li>SW 1</li>
                <li>SW 2</li>
                <li>UPS 3</li>
            </ul>
        </details>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Esquema de la rama.</h5>
        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
        <a href="#" class="btn btn-primary">Go somewhere</a>
      </div>
    </div>
  </div>
</div>
    @endsection
