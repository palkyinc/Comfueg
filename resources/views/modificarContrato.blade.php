@extends('layouts.plantilla')
@section('contenido')
@can('contratos_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Editar Contrato con ID: {{$elemento->id}}</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarContrato" method="post">
        @csrf
        @method('patch')
        <div class="form-row">
            <div class="form-group col-md-10 border">
                <p class="m-3">Cliente: {{$elemento->relCliente->getNomYApe()}}</p>
                <p class="m-3">Genesys ID:{{$elemento->relCliente->id}}</p>
            </div>
            <div class="form-group col-md-2 align-self-center d-flex justify-content-center d-flex flex-column">
                <button class="btn btn-primary m-1">Cambiar</button>
                <a href="/modificarCliente/{{$elemento->relCliente->id}}" class="btn btn-primary m-1">Editar</a>
            </div>
            
            <div class="form-group col-md-10 border">
                <p class="m-3">Dirección: {{$elemento->reldireccion->getResumida()}}</p>
                <p class="m-3">Coordenadas: {{$elemento->relDireccion->coordenadas}}</p>
            </div>
            <div class="form-group col-md-2 align-self-center d-flex justify-content-center d-flex flex-column">
                <button class="btn btn-primary m-1">Cambiar</button>
                <button class="btn btn-primary m-1">Editar</button>
            </div>
            
                {{-- <div class="form-group col-md-6">
                    <label for="num_equipo">Equipo Cliente: </label>
                    <select class="form-control" name="num_equipo">
                        <option value="null">Seleccione Equipo Cliente...</option>
                        @foreach ($equipos as $equipo)
                            @if ($equipo->id === $elemento->num_equipo)
                                <option value="{{$equipo->id}}" selected>{{$equipo->getResumida()}}</option>
                            @else
                                <option value="{{$equipo->id}}">{{$equipo->getResumida()}}</option>
                            @endif
                        @endforeach 
                    </select>
                </div> --}}
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
    </form>
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