@extends('layouts.plantilla')

    @section('contenido')
      <h3>
        @if ($frase)
          @php
              date_default_timezone_set(Config::get('constants.USO_HORARIO_ARG'));
              switch ((date ('W')) % 4) {
                case '3':
                    switch (date ('N'))
                      {
                      case '1':
                        echo "Si estás triste, ponte una canción pop. Seguirás triste, pero podrás bailar mientras lloras...";
                        break;
                      case '2':
                        echo "No te tomes la vida demasiado en serio. No saldrás de ella con vida (Elbert Hubbard).";
                        break;
                      case '3':
                        echo "Al que madruga, nadie le hace el desayuno.";
                        break;
                      case '4':
                        echo "El tiempo sin ti es empo.";
                        break;
                      case '5':
                        echo "Hoy es viernes, y un día no te quedará ningún diente... ¡así que sonríe ahora!.";
                        break;
                      case '6':
                        echo "Hoy es sabado, y dicen que el trabajo duro nunca mató a nadie pero, ¿por qué correr el riesgo?";
                        break;
                      case '7':
                        echo "Hoy es Domingo, NO deberias estar trabajando!!.";
                        break;
                      default:
                        echo "ERROR estoy saliedo por el Default.";
                        break;
                      }
                  break;
                case '2':
                  switch (date ('N'))
                      {
                      case '1':
                        echo "Sé buena persona, los desagradables ya no están de moda.";
                        break;
                      case '2':
                        echo "El amor es como el WiFi, está en el aire pero no todos tienen la clave.";
                        break;
                      case '3':
                        echo "Si no puedes convencerlos, confúndelos.";
                        break;
                      case '4':
                        echo "Las mentes son como los paracaídas... solo funcionan cuando están abiertos.";
                        break;
                      case '5':
                        echo "Que linda es la vida... ahora que es viernes...";
                        break;
                      case '6':
                        echo "Hoy es sabado y Trabajar no es malo, lo malo es tener que trabajar (Don Ramón)";
                        break;
                      case '7':
                        echo "Hoy es Domingo, NO deberias estar trabajando!!.";
                        break;
                      default:
                        echo "ERROR estoy saliedo por el Default.";
                        break;
                      }
                  break;
                case '1':
                  switch (date ('N'))
                      {
                      case '1':
                        echo "Hay personas que te tratan como Google, solo te buscan cuando quieren algo.";
                        break;
                      case '2':
                        echo "Todo es divertido, siempre y cuando le ocurra a otra persona.";
                        break;
                      case '3':
                        echo "Tener la conciencia limpia es señal de mala memoria (Steven Wright)";
                        break;
                      case '4':
                        echo "En la vida hay 10 tipos de personas, los que saben binario y los que no.";
                        break;
                      case '5':
                        echo "Odio que hablen cuando interrumpo.";
                        break;
                      case '6':
                        echo "El dinero no da la felicidad, pero procura una sensación tan parecida que necesita un especialista muy avanzado para verificar la diferencia (Woody Allen).";
                        break;
                      case '7':
                        echo "Hoy es Domingo, NO deberias estar trabajando!!.";
                        break;
                      default:
                        echo "ERROR estoy saliedo por el Default.";
                        break;
                      }
                  break;
                
                case '0':
                    switch (date ('N'))
                      {
                      case '1':
                        echo "Hoy es lunes, el lado bueno del Lunes es que, la semana tiene un solo Lunes.";
                        break;
                      case '2':
                        echo "Ríe y el mundo reirá contigo, ronca y dormirás solo. (Anthony Burgess).";
                        break;
                      case '3':
                        echo "Si pudieras patear en el trasero al responsable de casi todos tus problemas, no podrías sentarte por un mes (Theodore Roosevelt)";
                        break;
                      case '4':
                        echo "Sólo hay dos cosas infinitas: el universo y la estupidez humana. Y no estoy tan seguro de la primera (Albert Einstein).";
                        break;
                      case '5':
                        echo "Por supuesto que debes casarte! Si consigues una buena pareja, te convertirás en alguien feliz. Si consigues una mala, te convertirás en filósofo (Sócrates)";
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
                  break;
                
                default:
                  dd ('error en el numero de semana');
                  break;
              }
          @endphp     
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
                  <h5 class="card-title">Resumen de mis Tickets Asignados</h5>
                  <p class="card-text">Pendientes: {{$tickets['total']}}</p>
                  <p class="card-text">Vencidos: {{$tickets['vencidos']}}</p>
                  <p class="card-text">No Vencidos: {{$tickets['no_vencidos']}}</p>
                  <p class="card-text"><small class="text-muted"></small></p>
                </div>
              </div>
              <div class="card  mb-4">
                <div class="card-body">
                  <h5 class="card-title">Últimos 30 días</h5>
                  <p class="card-text">Total Generados: {{$total_tickets['total']}} (Prom: {{$total_tickets['total_prom_dia']}} tkt´s x día)</p>
                  <p class="card-text">Pendientes: {{$total_tickets['abiertos']}} ({{$total_tickets['abiertos_porc']}}%)</p>
                  <p class="card-text">Pendientes Vencidas: {{$total_tickets['abiertos_vencidos']}} ({{$total_tickets['abiertos_vencidos_porc']}}%)</p>
                  <p class="card-text">Cerrados a tiempo: {{$total_tickets['finalizados_no_vencidos']}} ({{$total_tickets['finalizados_no_vencidos_porc']}}%)</p>
                  <p class="card-text"><small class="text-muted">Asignados a cualquier usuario</small></p>
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
@endsection
