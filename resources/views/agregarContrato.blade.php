@extends('layouts.plantilla')
@section('contenido')
@can('contratos_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Nuevo Contrato</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarContrato" method="post">
        @csrf
        <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="num_cliente">Cliente: </label>
                    <select class="form-control" name="num_cliente">
                        <option value="null">Seleccione Cliente...</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{$cliente->id}}">{{$cliente->getNomYApe()}}</option>
                        @endforeach 
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="id_direccion">Dirección: </label>
                    <select class="form-control" name="id_direccion">
                        <option value="null">Seleccione Dirección...</option>
                        @foreach ($direcciones as $direccion)
                            <option value="{{$direccion->id}}">{{$direccion->getResumida()}}</option>
                        @endforeach 
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="num_equipo">Equipo CLiente: </label>
                    <select class="form-control" name="num_equipo">
                        <option value="null">Seleccione Equipo Cliente...</option>
                        @foreach ($equipos as $equipo)
                            <option value="{{$equipo->id}}">{{$equipo->getResumida()}}</option>
                        @endforeach 
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="num_panel">Panel: </label>
                    <select class="form-control" name="num_panel">
                        <option value="null">Seleccione Panel a Asociarse...</option>
                        @foreach ($paneles as $panel)
                            <option value="{{$panel->id}}">{{$panel->getResumida()}}</option>
                        @endforeach 
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="num_plan">Plan: </label>
                    <select class="form-control" name="num_plan">
                        <option value="null">Seleccione Plan...</option>
                        @foreach ($planes as $plan)
                            <option value="{{$plan->id}}">{{$plan->nombre}}</option>
                        @endforeach 
                    </select>
                </div>
                <div class="form-group col-md-4">
                </div>
            <div class="form-group col-md-4">
                <label class="form-check-label" for="flexSwitchCheckChecked">Habilitado:</label>
                <div class="form-check col-4">
                    <input class="form-check-input" type="checkbox" name="activo" id="flexSwitchCheckChecked" checked>
                </div>
            </div>
        </div>
            <button type="submit" class="btn btn-primary" id="enviar">Crear Nueva</button>
            <a href="/adminContratos" class="btn btn-primary">volver</a>
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