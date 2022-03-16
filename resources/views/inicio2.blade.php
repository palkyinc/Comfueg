@extends('layouts.plantilla')

    @section('contenido')
    {{-- Es Cliente y está habilitado --}}
      <div class="table-responsive col-5 mx-auto mt-5">
        <table class="table table-sm table-bordered mt-5">
          <caption>Datos del Contrato</caption>
          <thead>
            <tr>
              <th scope="col">Contrato N°: {{$contrato->id}}</th>
              <th scope="col" class="text-center">
                <a href="#">Editar Datos</a>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">Cliente</th>
              <td>{{$contrato->relCliente->getNomYApe()}}</td>
            </tr>
            <tr>
              <th scope="row">Plan</th>
              <td>{{$contrato->relPlan->nombre}}</td>
            </tr>
            <tr>
            <tr>
              <th scope="row">Descripción Plan</th>
              <td>{{$contrato->relPlan->descripcion}}</td>
            </tr>
            <tr>
              <th scope="row">Estado</th>
              @if ($contrato->activo)
                <td class="table-success">Habilitado</td>
              @else
                <td class="table-warning">Suspendido</td>
              @endif
            </tr>
            <tr>
              <th scope="row">Celular</th>
              <td>{{$contrato->relCliente->relCodAreaCel->codigoDeArea . ' - 15 - ' . $contrato->relCliente->celular}}</td>
            </tr>
            <tr>
              <th scope="row">Email</th>
              <td>{{$contrato->relCliente->email}}</td>
            </tr>
            <tr>
              <th scope="row">Ubicación</th>
              <td>
                {{  $contrato->relDireccion->relCalle->nombre . '  | ' . 
                          $contrato->relDireccion->numero . ', ' . 
                          $contrato->relDireccion->relBarrio->nombre}}
                  @if ($contrato->relDireccion->coordenadas != '')
                      <a href="https://www.google.com/maps/place/{{$contrato->relDireccion->coordenadas}}" target="_blank"
                          class="margenAbajo btn btn-link" title="Ver en Google maps">
                          <img src="/imagenes/pin_location.svg" alt="Pin en mapa" height="20px">
                      </a>
                  @endif
              </td>
            </tr>
            <tr>
              <th scope="row">Reclamos</th>
              <th scope="col" class="text-center">
                <a href="#">Abrir</a>
                <a href="#">Ver</a>
              </th>
            </tr>
          </tbody>
        </table>
      </div>
      {{-- Fin de la tabla --}}

      {{-- Inicio de Mensaje para suspendidos --}}
      @if (!$contrato->activo)
        <div class="alert alert-warning">
          <strong>Su Servicio se encuentra suspendido.</strong> Comunicarse con el sector de Amdinistración al 42-0040, 42-0800 o 42-2317 int: 11. Whatsapp: +549 2964 422317.
        </div>
      @endif
      
      {{-- Inicio de gráficos --}}
      @if ($contrato->activo)
        @include('layouts.consumosCliente')
      @endif
    @endsection
