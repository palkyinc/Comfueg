@extends('layouts.plantilla')
@section('contenido')
@can('contratos_index')
@php
$mostrarSololectura = true;
@endphp
@if ( session('mensaje') )
    <ul class="list-group m-1 p-1">
        @foreach (session('mensaje') as $key => $items)
            @if ($key === 'success')
                    @foreach ($items as $item)
                        <li class="list-group-item list-group-item-success">{{ $item }}</li>
                    @endforeach
            @endif
            @if ($key === 'error')
                    @foreach ($items as $item)
                        <li class="list-group-item list-group-item-danger"> {{ $item }} </li>
                    @endforeach
            @endif
            @if ($key === 'warning')
                    @foreach ($items as $item)
                        <li class="list-group-item list-group-item-warning"> {{ $item }} </li>
                    @endforeach
            @endif
            @if ($key === 'info')
                    @foreach ($items as $item)
                        <li class="list-group-item list-group-item-info"> {{ $item }} </li>
                    @endforeach
            @endif
        @endforeach
    </ul>
@endif
<h3>Editar Contrato con ID: {{$elemento->id}}</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
        <div class="form-row">
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
            
            <div class="form-group col-md-10 border">
                <p class="m-3">Dirección: {{$elemento->reldireccion->getResumida()}}</p>
                <p class="m-3">Coordenadas: {{$elemento->relDireccion->coordenadas}}</p>
            </div>
            <div class="form-group col-md-2 align-self-center d-flex justify-content-center d-flex flex-column">
                <button class="btn btn-primary m-1" disabled>Cambiar</button>
                <button class="btn btn-primary m-1" disabled>Editar</button>
            </div>
            
            @if (auth()->user()->hasRole('Admin'))
                <div class="form-group col-md-10 border">
                    <p class="m-3">Status comercial: {{$elemento->no_paga ? 'No Factura' : 'Factura'}}</p>
                </div>
                <div class="form-group col-md-2 align-self-center d-flex justify-content-center d-flex flex-column">
                    <a href="/modificarContratroNoPaga/{{$elemento->id}}" class="btn btn-primary m-1">Cambiar</a>
                </div>  
            @else
                <div class="form-group col-md-12 border">
                    <p class="m-3">Status comercial: {{$elemento->no_paga ? 'No Factura' : 'Factura'}}</p>
                </div>
            @endif
            
            <div class="form-group col-md-12 border">
                <p class="m-3">Equipo Cliente: {{$elemento->relEquipo->getResumida()}}</p>
            </div>
            
            <div class="form-group col-md-12 border">
                <p class="m-3">Panel: {{$elemento->relPanel->getResumida()}}</p>
            </div>
            
            <div class="form-group col-md-12 border">
                <p class="m-3">Plan: {{$elemento->relPlan->nombre}}</p>
            </div>

                <div class="form-group col-md-6 border">
                    <p class="m-3">Alta: {{$elemento->inicioDateTimeLocal()}}</p>
                </div>
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
            <a href="/adminContratos?contrato={{$elemento->id}}" class="btn btn-primary">Volver abono</a>
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