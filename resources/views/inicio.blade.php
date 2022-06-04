@extends('layouts.plantilla')

    @section('contenido')
      <h3>
        @if ($frase)
          Aquí va la frase.     
        @endif
        </h3>
        @foreach ($proveedoresCaidos as $item)
          <div class="alert alert-danger" role="alert">
              ATENCIÓN: ({{$item->nombre}}) caído hace {{$item->tiempoCaida()}} en sitio: {{$item->relGateway->relSite->nombre}}
          </div>
        @endforeach
        <div class="container-fluid mt-5">
          <div class="row">
            
            <div class="col-sm">
              <div class="card  mb-4">
                <div class="card-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                      <h5 class="card-title">Resumen de mis Tickets Asignados</h5>
                    </li>
                    <li class="list-group-item">
                      <p class="card-text">Pendientes: {{$tickets['total']}}</p>
                    </li>
                    <li class="list-group-item">
                      <p class="card-text">Vencidos: {{$tickets['vencidos']}}</p>
                    </li>
                    <li class="list-group-item">
                      <p class="card-text">No Vencidos: {{$tickets['no_vencidos']}}</p>
                    </li>
                    <li class="list-group-item">
                      <p class="card-text">
                        <small class="text-muted">
                          <a href="/adminIssues">Ir a mis Tickets</a>
                        </small>
                      </p>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card  mb-4">
                <div class="card-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                      <h5 class="card-title">Últimos 30 días</h5>
                    </li>
                    <li class="list-group-item">
                      <p class="card-text">Total Generados: {{$total_tickets['total']}} (Prom: {{$total_tickets['total_prom_dia']}} tkt´s x día)</p>
                      <div id="chartTortaIssues" style="height: 500px;"></div>
                    </li>
                    <li class="list-group-item">
                      <p class="card-text">Pendientes: {{$total_tickets['abiertos']}} ({{$total_tickets['abiertos_porc']}}%)</p>
                    </li>
                    <li class="list-group-item">
                      <p class="card-text">Pendientes Vencidas: {{$total_tickets['abiertos_vencidos']}} ({{$total_tickets['abiertos_vencidos_porc']}}%)</p>
                    </li>
                    <li class="list-group-item">
                      <p class="card-text">Cerrados a tiempo: {{$total_tickets['finalizados_no_vencidos']}} ({{$total_tickets['finalizados_no_vencidos_porc']}}%)</p>
                    </li>
                    <li class="list-group-item">
                    <p class="card-text"><small class="text-muted">Asignados a cualquier usuario</small></p>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            
            <div class="col-sm">
              @auth
                @foreach ($incidentes as $incidente)
                  @if ($incidente->tipo === 'INCIDENTE')
                    <div class="card mb-4">
                      <div class="card-body">
                        <h5 class="card-title">Incidente Global: {{$incidente->crearNombre()}}</h5>
                        <p class="card-text">Tiempo de la Caída: <strong>{{$incidente->tiempoCaida()}}</strong></p>
                        <p class="card-text">Sitios Afectados: <strong>{{$incidente->sitios_afectados}}</strong></p>
                        <p class="card-text">Barrios Afectados: <strong>{{$incidente->barrios_afectados}}</strong></p>
                        <p class="card-text">Mensaje para Clientes: <strong>{{$incidente->mensaje_clientes}}</strong></p>
                        <p class="card-text">Cantidad de Actualizaciones: <strong>{{count($incidente->incidente_has_mensaje)}}</strong></p>
                        <p class="card-text"> <a href="#" class="margenAbajo btn btn-link" data-toggle="modal" data-target="#staticBackdrop{{$incidente->id}}" title="Ver">Ver Incidente</a> </p>
                        <p class="card-text"><small class="text-muted">Creado {{$incidente->created_at}} por {{$incidente->reluser->name}}</small></p>
                      </div>
                    </div>
                  @endif
                @endforeach
              @endauth
            </div>

        <div class="col-sm">
          @auth
            @can('SiteHasIncidente_edit')
              @foreach ($incidentes as $incidente)
                @if ($incidente->tipo === 'DEUDA TECNICA')
                  <div class="card mb-4">
                    <div class="card-body">
                      <h5 class="card-title">Deuda Técnica: {{$incidente->mensaje_clientes}}</h5>
                      <p class="card-text">Equipo: <strong>{{$incidente->relPanel->relEquipo->nombre}}</strong></p>
                      <p class="card-text">IP: <strong>{{$incidente->relPanel->relEquipo->ip}}</strong></p>
                      <p class="card-text">Sitio: <strong>{{$incidente->relPanel->relSite->nombre}}</strong></p>
                      <p class="card-text">Deuda: <strong>{{$incidente->causa}}</strong></p>
                      <p class="card-text">Cantidad de Actualizaciones: <strong>{{count($incidente->incidente_has_mensaje)}}</strong></p>
                      <p class="card-text"> <a href="#" class="margenAbajo btn btn-link" data-toggle="modal" data-target="#staticBackdrop{{$incidente->id}}" title="Ver">Ver Incidente</a> </p>
                      <p class="card-text"><small class="text-muted">Creado {{$incidente->created_at}} por {{$incidente->reluser->name}}</small></p>
                    </div>
                  </div>
                @endif
              @endforeach
            @endcan
          @endauth
        </div>

  </div>
</div>
@include('modals.deudas')
@include('modals.incidentes')
@include('layouts.tortaIssues')
@endsection
