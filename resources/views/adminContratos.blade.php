@extends('layouts.plantilla')
@section('contenido')
@can('contratos_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-6 margin-10" action="" method="GET">
                        <h2 class="mx-2">Administración de Contratos</h2>
                        <label for="cliente" class="mx-2">Cliente</label>
                        <input type="text" name="cliente" class="form-control mx-3" id="cliente">
                        <button type="submit" class="btn btn-primary mx-3">Enviar</button>
                    </form>

        @if ( session('mensaje') )
            <div class="alert alert-success">
                @foreach (session('mensaje') as $item)
                    {{ $item }} <br>
                @endforeach
            </div>
        @endif
        
<div class="table-responsive">
                
                <table class="table table-sm table-bordered table-hover">
                    <caption>Listado de Contratos</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Cliente </th>
                            <th scope="col"> Plan </th>
                            <th scope="col"> Equipo </th>
                            <th scope="col"> Panel </th>
                            <th scope="col"> Estado </th>
                            <th scope="col" colspan="2">
                                @can('contratos_create')
                                <a href="/agregarContrato" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($contratos as $contrato)
                            @if ($contrato->activo)
                                <tr>
                            @else
                                <tr class="alert alert-light" role="alert">
                            @endif
                                
                                <th scope="row"> {{$contrato->id}}</th>
                                <td>{{$contrato->relCliente->getNomYApe()}}</td>
                                <td>{{$contrato->relPlan->nombre}}</td>
                                <td>{{$contrato->relEquipo->relProducto->modelo}}</td>
                                <td>{{$contrato->relPanel->ssid}}</td>
                                <td>{{($contrato->activo) ? 'Habilitado' : 'Suspendido'}}</td>
                                <td>
                                    @can('contratos_edit')
                                    <a href="/modificarContrato/{{ $contrato->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                        <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                    </a>
                                    @endcan
                                    <a href="#" class="margenAbajo btn btn-link" data-toggle="modal" data-target="#staticBackdrop{{$contrato->id}}" title="Consumo Descargas">
                                        <img src="imagenes/iconfinder_graph_3338898.svg" alt="imagen de cosumo cliente" height="20px">
                                    </a>
                                </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $contratos->links() }}
@include('modals.consumosCliente')
@endcan
@include('sinPermiso')
@endsection