@extends('layouts.plantilla')
@section('contenido')
@can('issues_edit')
@php
$mostrarSololectura = true;
@endphp
<h3>Editando Pedido de Asistencia Técnica N°: {{$issue->id}}
    @if ($issue->closed)
        CERRADA.
    @endif
</h3>
<div class="alert bg-light border m-3 col-8 mx-auto p-4">
<form action="/modificarIssueChangeSpeed" method="post" enctype="multipart/form-data">
@csrf
@method('patch')
<div class="form-row">
    <div class="form-group border col-md-6">
        <p class="font-weight-bold">Cliente:</p>
            <p>{{$issue->relCliente->getNomYApe()}}</p>
            <p>{{$issue->relCliente->relCodAreaCel->codigoDeArea}} - 15 - {{$issue->relCliente->celular}}</p>
            <input type="hidden" name="id" value="{{$issue->id}}">
    </div>
    <div class="form-group border col-md-6">
        <p class="font-weight-bold">Dirección: </p>
        <p>
            @if ($issue->contrato_id != null)
                {{  $issue->relContrato->relDireccion->relCalle->nombre . ' ' . 
                    $issue->relContrato->relDireccion->numero . ', ' . 
                    $issue->relContrato->relDireccion->relBarrio->nombre}}
                @if ($issue->relContrato->relDireccion->coordenadas != '')
                    <a href="https://www.google.com/maps/place/{{$issue->relContrato->relDireccion->coordenadas}}" target="_blank"
                        class="margenAbajo btn btn-link" title="Ver en Google maps">
                        <img src="/imagenes/pin_location.svg" alt="Pin en mapa" height="20px">
                    </a>
                @endif
            @else
                Sin Contrato.
            @endif
        </p>
    </div>
</div>
<div class="row">
    <div class="form-group border col-md-6">
        <p class="font-weight-bold">Contrato N°: {{$issue->contrato_id}}</p>
        @if ($issue->contrato_id != null)
            <p>{{$issue->relContrato->relPlan->nombre}}</p>
            <p>Panel: <a href="http://{{$issue->relContrato->relPanel->relEquipo->ip}}" target="_blank">{{$issue->relContrato->relPanel->ssid}}</a></p>
        @else
            Sin Contrato.    
        @endif
    </div>
    <div class="form-group border col-md-6">
        <p class="font-weight-bold">Equipo Cliente:</p>
        @if ($issue->contrato_id != null)
            <p>{{$issue->relContrato->relEquipo->relProducto->modelo}}</p>
            <p><a href="http://{{$issue->relContrato->relEquipo->ip}}" target="_blank">{{$issue->relContrato->relEquipo->ip}}</a></p>
        @else
            Sin Contrato.    
        @endif
    </div>
</div>
<div class="form-row">
    <div class="form-group border col-md-6">
        <label for="titulo" class="mx-3 font-weight-bold">Título:</label><br>
        {{$issue->relTitle->title}}
    </div>
    <div class="form-group col-md-6">
        <label for="asignado" class="mx-3">Asignado a:</label>
        @if ($issue->closed)
            {{$issue->relAsignado->name}}
        @else
            <select name="asignado" class="form-control">
                @foreach ($usuarios as $usuario)
                    <option value="{{$usuario->id}}"
                        @if ($usuario->id == $issue->asignado_id)
                            selected
                        @endif
                        >{{$usuario->name}}</option>
                @endforeach
            </select>
        @endif
    </div>
</div>
<div class="form-row">
    <div class="form-group border col-md-12">
        <p class="font-weight-bold">Descripción de la problemática: </p>
        <p>{{$issue->descripcion}}</p>
        <p class="card-text"><small class="text-muted">Creado {{$issue->created_at}} por {{$issue->relCreator->name}}</small></p>
    </div>
</div>
@foreach ($issues_updates as $item)
    <div class="form-row">
        <div class="form-group border col-md-12">
            <p class="font-weight-bold">Actualización:</p>
            <p>{{$item->descripcion}}</p>
            <p class="card-text"><small class="text-muted">Actualizado {{$item->created_at}} por {{$item->relUsuario->name}}</small></p>
            @if ($item->relAsignadoAnt->name != $item->relAsignadoSig->name)
            <p class="card-text"><small class="text-muted">Antes asignado a: {{$item->relAsignadoAnt->name}}. Fue cambiado a: {{$item->relAsignadoSig->name}}</small></p>
            @endif
        </div>
    </div>
@endforeach
@if (!$issue->closed)
<div class="form-row border align-items-center">
    <div class="form-group col-md-4">
        <label for="num_plan">Plan:</label>
        <select class="form-control" name="num_plan" autofocus>
            @foreach ($planes as $plan)
                @if ( (null !== old('num_plan') && old('num_plan') == $plan->id) || $plan->id === $issue->relContrato->num_plan)
                    <option value="{{$plan->id}}" selected>{{$plan->nombre}} {{ $plan->id === $issue->relContrato->num_plan ? '(ACTUAL)' : ''}}</option>
                @else
                    <option value="{{$plan->id}}">{{$plan->nombre}}</option>
                @endif
            @endforeach 
        </select>
    </div>
    <div class="form-group form-check align-items-center d-flex justify-content-center col-md-8">
        <div class="row">
            <div class="col-md-12">
                <input class="form-check-input" type="radio" name="prueba_definitivo" value="prueba" id="flexSwitchCheckChecked"
                {{ null === old('prueba_definitivo') || old('prueba_definitivo') === 'prueba' ? 'checked' : ''}}
                >
                <label class="form-check-label" for="flexSwitchCheckChecked">A Prueba</label>
            </div>
            <div class="col-md-12">
                <input class="form-check-input" type="radio" name="prueba_definitivo" value="definitivo" id="flexSwitchCheckChecked"
                {{ null === old('prueba_definitivo') || old('prueba_definitivo') === 'definitivo' ? 'checked' : ''}}
                >
                <label class="form-check-label" for="flexSwitchCheckChecked">Definitivo</label>
            </div>
            <div class="col-md-12">
                <input class="form-check-input" type="radio" name="prueba_definitivo" value="original" id="flexSwitchCheckChecked"
                {{ null === old('prueba_definitivo') || old('prueba_definitivo') === 'original' ? 'checked' : ''}}
                >
                <label class="form-check-label" for="flexSwitchCheckChecked">Volver a velocidad Original</label>
            </div>
        </div>
    </div>
</div>
<div class="form-row ">
    <div class="input-group col-md-8">
        <p class="font-weight-bold">Seleccionar usuarios en seguimiento:</p>
        <div class="form-row">
            @foreach ($usuarios as $usuario)
                <div class="col-md-4">
                    <input type="checkbox" name="viewer{{$usuario->id}}" value="{{$usuario->id}}"
                        @foreach ((($issue->viewers != 'null' && $issue->viewers != null) ? json_decode($issue->viewers) : []) as $value)
                            @if ($value == $usuario->id)
                                checked
                            @endif
                        @endforeach
                    >    
                    <label for="{{$usuario->id}}"> {{$usuario->name}} </label><br>
                </div>
            @endforeach
        </div>
    </div>
</div>
{{-- <div class="form-row">
    <div class="form-group">
        <label for="scheme_file">Seleccionar o arrastrar archivos para adjuntar a la Incidencia.</label>
        <input type="file" name="scheme_file[]" class="form-control-file" multiple>
    </div>
</div> --}}
@endif
    <div class="row m-3">
        <div class="input-group col-md-12">
            @if (!$issue->closed)
                <button type="submit" class="btn btn-primary m-1" id="enviar">Modificar</button>
            @endif
            <a href="/adminIssues" class="btn btn-primary m-1">Mis tickes</a>
            <a href="/adminIssues?rebusqueda=on&usuario=todos&contrato={{$issue->contrato_id}}" class="btn btn-primary m-1">Tickes {{$issue->relCliente->getNomYApe()}}</a>
            <a href="/adminContratos?contrato={{$issue->contrato_id}}" class="btn btn-primary m-1">Volver Abono</a>
        </div>
    </div>
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