@extends('layouts.plantilla')

    @section('contenido')
            <h2>
        @php
            date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
            $dia = date ('N');
            switch ($dia) {
              case '1':
                echo "Hoy es lunes, el lado bueno del Lunes es que, la semana tiene un solo Lunes.";
                break;
              case '2':
                echo "Hoy es martes, Ríe y el mundo reirá contigo, ronca y dormirás solo. Anthony Burgess.";
                break;
              case '3':
                echo "Hoy es miercoles, a las 12 del mediodia, la semana se parte al medio.";
                break;
              case '4':
                echo "Hoy es jueves, ya falta menos para el fin de semana.";
                break;
              case '5':
                echo "Hoy es viernes, es el dia para sonreir.";
                break;
              case '6':
                echo "Hoy es sabado, Del Griego Shabbaton, a su vez del Hebreo Shabbat, esto significa “Reposo”..";
                break;
              case '7':
                echo "Hoy es Domingo, NO deberias estar trabajando!!.";
                break;
              default:
                echo "ERROR estoy saliedo por el Default.";
                break;
            }
        @endphp     
        </h2>
        
        <div class="container-fluid mt-5">
          <div class="row">
            
            <div class="col-sm">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Card title</h5>
                  <p class="card-text">This is another card with title and supporting text below. This card has some additional content to make it slightly taller overall.</p>
                  <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
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
          @endauth
        </div>

  </div>
</div>
@include('modals.deudas')
@include('modals.incidentes')
@endsection
