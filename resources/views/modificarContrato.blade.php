@extends('layouts.plantilla')
@section('contenido')
@can('contratos_index')
@php
$mostrarSololectura = true;
@endphp
@include('layouts.mensajes')
<h3>Editar Contrato con ID: {{$elemento->id}}</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
        <div class="form-row">
            {{-- CLIENTE --}}
            <div class="form-group col-md-10 border">
                <p class="m-3">Cliente: {{$elemento->relCliente->getNomYApe()}}</p>
                <p class="m-3">Genesys ID:{{$elemento->relCliente->id}}</p>
            </div>
            <div class="form-group col-md-2 align-self-center d-flex justify-content-center d-flex flex-column">
                <div class="border border-1 border-info p-1">
                    <form action="/modificarContratoCliente" method="post" class="align-self-center d-flex justify-content-center d-flex flex-column margenAbajo">
                    @csrf
                    @method('patch')
                        <input type="hidden" name="id" value="{{$elemento->id}}">
                        <input type="text" name="genesys_id" class="form-control p-1" placeholder="Genesys ID" aria-label="nuevoCliente" aria-describedby="basic-addon1">
                        <button class="btn btn-primary p-1"  title="Cambiar Cliente">Cambiar</button>
                    </form>
                </div>
                <a href="/modificarCliente/{{$elemento->relCliente->id}}" class="btn btn-primary m-1">Editar</a>
            </div>
            {{-- DIRECCION --}}
            <div class="form-group col-md-10 border">
                <p class="m-3">Dirección: {{$elemento->reldireccion->getResumida()}}</p>
                @if ($elemento->relDireccion->coordenadas)
                    <p class="m-3">Coordenadas: {{$elemento->relDireccion->coordenadas}}</p>
                @else
                    <form action="/modificarContratoCoordenadas" method="post" class="">
                    @csrf
                    @method('patch')
                        <div class="m-3 form-inline">
                            <label for="coordenadas">Coordenadas: </label>
                            <input type="hidden" name="id" value="{{$elemento->id}}">
                            <input type="text" name="coordenadas" value="{{$elemento->coordenadas}}" maxlength="40"  class="form-control m-3 p-3">
                            <button class="btn btn-primary p-1"  title="Cambiar Cliente">Guardar</button>
                        </div>
                    </form>
                @endif
                <p class="m-3">Notas: {{$elemento->relDireccion->comentarios}}</p>
            </div>
            <div class="form-group col-md-2 align-self-center d-flex justify-content-center d-flex flex-column">
                <a href="/modificarContratoDireccion?contrato={{$elemento->id}}" class="btn btn-primary m-1">Cambiar</a>
                <a href="/modificarDireccion/{{$elemento->relDireccion->id}}?contrato_id={{$elemento->id}}" class="btn btn-primary m-1">Editar</a>
            </div>
            {{-- STATUS COMERCIAL --}}
            @if (auth()->user()->hasRole('Admin'))
                <div class="form-group col-md-10 border">
                    <p class="m-3">Status comercial: {{$elemento->no_paga ? 'NO FACTURA' : 'Factura'}}</p>
                </div>
                <div class="form-group col-md-2 align-self-center p-1 d-flex justify-content-center d-flex flex-column">
                    <a href="/modificarContratroNoPaga/{{$elemento->id}}" class="btn btn-primary m-1">Cambiar</a>
                </div>
                {{-- TIPO DE CONTRATO --}}
                <div class="form-group col-md-8 border">
                    <p class="m-3">Tipo de contrato: {{$tipos[$elemento->tipo]}}</p>
                </div>
                <div class="form-group col-md-4 align-self-center p-1 d-flex justify-content-center d-flex flex-column">
                    <div class="border border-1 border-info p-1 m-1 d-flex flex-column justify-content-center">
                        <form action="/modificarContratoTipo" method="post" class="d-flex justify-content-center d-flex flex-column margenAbajo">
                            @csrf
                            @method('patch')
                                <input type="hidden" name="id" value="{{$elemento->id}}">
                                <select class="custom-select p-1" name="tipo">
                                    @foreach ($tipos as $key => $tipo)
                                        @if ($key === $elemento->tipo || $key === 0)
                                            <option value="{{$key}}" selected disabled>{{$tipo}}</option>
                                        @else
                                            <option value="{{$key}}">{{$tipo}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <button class="btn btn-primary p-1"  title="Cambiar Tipo">Cambiar</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="form-group col-md-12 border">
                    <p class="m-3">Status comercial: {{$elemento->no_paga ? 'NO FACTURA' : 'Factura'}}</p>
                </div>
                {{-- TIPO DE CONTRATO --}}
                <div class="form-group col-md-12 border">
                    <p class="m-3">Tipo de contrato: {{$tipos[$elemento->tipo]}}</p>
                </div>
                
            @endif

            @if(auth()->user()->can('contratos_edit'))
                {{-- EQUIPO CLIENTE Y ROUTER CLIENTE--}}
                <div class="form-group col-md-10 border">
                    
                    @if ($elemento->num_equipo !== 157)
                    <p class="m-3">Equipo Cliente: {{$elemento->relEquipo->getResumida()}}</p>
                    @else
                    <p class="m-3">Equipo Cliente: NO CONFIGURADO</p>
                    @endif
                    
                    @if ($elemento->router_id !== null)
                        <p class="m-3">Router Cliente: {{$elemento->relRouter->getResumida()}}</p>
                    @else
                        <p class="m-3">Router Cliente: NO CONFIGURADO</p>
                    @endif
                </div>
                <div class="form-group col-md-2">
                    <div class="form-group">
                        <div class="border border-1 border-info p-1 m-1 d-flex flex-column justify-content-center">
                            <form action="/modificarContratoEquipo" method="post" class="align-self-center d-flex justify-content-center d-flex flex-column margenAbajo">
                            @csrf
                            @method('patch')
                                <input type="hidden" name="id" value="{{$elemento->id}}">
                                @if ($elemento->tipo === 2 || $elemento->tipo === 1 || $elemento->tipo === 0)
                                    <input type="text" name="mac_address" class="form-control p-1" placeholder="Mac Equipo" aria-label="nuevoCliente" aria-describedby="basic-addon1">
                                @endif
                                @if ($elemento->tipo === 2 || $elemento->tipo === 3)
                                    <input type="text" name="mac_address_2" class="form-control p-1" placeholder="Mac Router" aria-label="nuevoCliente" aria-describedby="basic-addon1">
                                @endif
                                <button class="btn btn-primary p-1"  title="Cambiar Equipo/s">Cambiar</button>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- PANEL --}}
                <div class="form-group col-md-10 border">
                    <p class="m-3">Panel: {{$elemento->relPanel->getResumida()}}</p>
                </div>
                <div class="form-group col-md-2">
                    <div class="border border-1 border-info p-1 m-1 d-flex flex-column justify-content-center">
                        @if ($elemento->baja || !$elemento->activo)
                            {{-- Si el cliente esta de baja o suspendido no habilitar cambio de panel --}}
                            <p class="alert alert-info">Baja o suspendido</p> 
                        @else
                            @if ($issue_id)
                                <p class="p-1 alert alert-info">Cambio de Panel en curso</p>
                                <a class="p-1 btn btn-primary" href="/modificarIssue/{{$issue_id}}">Ver ticket</a>
                            @else
                                    <form action="/modificarContratoPanel" method="post" class="d-flex justify-content-center d-flex flex-column margenAbajo">
                                    @csrf
                                    @method('patch')
                                        <input type="hidden" name="id" value="{{$elemento->id}}">
                                        <select class="custom-select p-1" name="num_panel">
                                            @foreach ($paneles as $item)
                                                @if ($item->id === $elemento->num_panel)
                                                    <option value="{{$item->id}}" selected disabled>{{$item->ssid}}</option>
                                                @else
                                                    <option value="{{$item->id}}">{{$item->ssid}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button class="btn btn-primary p-1"  title="Cambiar Cliente">Cambiar</button>
                                    </form>
                            @endif    
                        @endif
                    </div>
                </div>
            @else
                {{-- EQUIPO CLIENTE --}}
                @if ($elemento->tipo === 2 || $elemento->tipo === 1 || $elemento->tipo === 0)
                    <div class="form-group col-md-12 border">
                        <p class="m-3">Equipo Cliente: {{$elemento->relEquipo->getResumida()}}</p>
                    </div>
                @endif
                {{-- ROUTER CLIENTE --}}
                @if ($elemento->tipo === 2 || $elemento->tipo === 3)
                    <div class="form-group col-md-12 border">
                        <p class="m-3">Router Cliente: {{$elemento->relRouter->getResumida()}}</p>
                    </div>
                @endif
                {{-- PANEL --}}
                <div class="form-group col-md-12 border">
                    <p class="m-3">Panel: {{$elemento->relPanel->getResumida()}}</p>
                </div>
            @endif
            {{-- PLAN --}}
            <div class="form-group col-md-12 border">
                <p class="m-3">Plan: {{$elemento->relPlan->nombre}}</p>
            </div>
            {{-- ALTA --}}
            <div class="form-group col-md-6 border">
                <p class="m-3">Alta: {{$elemento->inicioDateTimeLocal()}}</p>
            </div>
            {{-- ESTADO DEL SERVICIO --}}
            <div class="form-group col-md-6 border">
                <p class="m-3">Servicio: 
                @if ($elemento->activo)
                    Habilitado
                @else
                    Suspendido por Mora
                @endif
                </p>
                <p class="m-3">Estado del Contrato: 
                @if ($elemento->baja)
                    Dado de Baja
                @else
                    Alta
                @endif
                </p>
            </div>

        </div>
            {{-- <input type="text" name="id" value="{{$elemento->id}}" hidden>
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button> --}}
            <a href="/adminContratos?contrato={{$elemento->id}}" class="btn btn-primary">Volver Contrato</a>
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
@endcan
@include('sinPermiso')
@endsection