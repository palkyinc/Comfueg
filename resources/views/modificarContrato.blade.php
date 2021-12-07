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
                <div class="form-group col-md-6">
                    <label for="num_cliente">Cliente: </label>
                    <select class="form-control" name="num_cliente">
                        <option value="null">Seleccione Cliente...</option>
                        @foreach ($clientes as $cliente)
                            @if ($cliente->id === $elemento->num_cliente)
                                <option value="{{$cliente->id}}" selected>{{$cliente->getNomYApe()}}</option>
                            @else
                                <option value="{{$cliente->id}}">{{$cliente->getNomYApe()}}</option>
                            @endif
                        @endforeach 
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="id_direccion">Dirección: </label>
                    <select class="form-control" name="id_direccion">
                        <option value="null">Seleccione Dirección...</option>
                        @foreach ($direcciones as $direccion)
                            @if ($direccion->id === $elemento->id_direccion)
                                <option value="{{$direccion->id}}" selected>{{$direccion->getResumida()}}</option>
                            @else
                                <option value="{{$direccion->id}}">{{$direccion->getResumida()}}</option>
                            @endif
                        @endforeach 
                    </select>
                </div>
                <div class="form-group col-md-6">
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
                </div>
                <div class="form-group col-md-6">
                    <label for="num_panel">Panel: </label>
                    <select class="form-control" name="num_panel">
                        <option value="null">Seleccione Panel a Asociarse...</option>
                        @foreach ($paneles as $panel)
                            @if ($panel->id === $elemento->num_panel)
                                <option value="{{$panel->id}}" selected>{{$panel->getResumida()}}</option>
                            @else
                                <option value="{{$panel->id}}">{{$panel->getResumida()}}</option>
                            @endif
                        @endforeach 
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="num_plan">Plan: </label>
                    <select class="form-control" name="num_plan">
                        <option value="null">Seleccione Plan...</option>
                        @foreach ($planes as $plan)
                            @if ($plan->id === $elemento->num_plan)
                                <option value="{{$plan->id}}" selected>{{$plan->nombre}}</option>
                            @else
                                <option value="{{$plan->id}}">{{$plan->nombre}}</option>
                            @endif
                        @endforeach 
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="created_at">Alta: </label>
                    <input id="created_at" type="datetime-local" name="created_at" value="{{$elemento->inicioDateTimeLocal()}}" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label for="coordenadas">Coordenadas: </label>
                    <input id="coordenadas" type="text" name="coordenadas" value="{{$elemento->relDireccion->coordenadas}}" class="form-control">
                </div>
                <div class="form-group col-md-2">
                </div>
            <div class="form-group col-md-2">
                @if ($elemento->activo)
                    <input class="form-check-input" type="checkbox" name="activo" id="flexSwitchCheckChecked" checked>
                @else
                    <input class="form-check-input" type="checkbox" name="activo" id="flexSwitchCheckChecked">
                @endif
                <label class="form-check-label" for="flexSwitchCheckChecked">Habilitado:</label>
            </div>
        </div>
            <input type="text" name="id" value="{{$elemento->id}}" hidden>
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
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