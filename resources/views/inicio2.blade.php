@extends('layouts.plantilla')

    @section('contenido')
    {{-- Es Cliente y está habilitado --}}
    @if (isset($es_cliente) && $es_cliente)
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
              <th scope="row">Abono</th>
              <td>{{$contrato->relPlan->nombre}}</td>
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
      @include('layouts.consumosCliente')
      {{-- Inicio de gráficos --}}
    @else
      <p>Logueate para poder trabajar</p>
    @endif  
    @endsection
