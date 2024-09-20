@extends('layouts.plantilla')
@section('contenido')
@can('issues_create')
@php
$mostrarSololectura = true;
@endphp
<h3>{{$h1}}</h3>
<div class="alert bg-light border col-8 mx-auto p-4">
        <form action="/agregarIssueChangeSpeed" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group border col-md-6">
                    <label for="tipo" class="mx-3">Cliente:<br>
                        {{$contrato->relCliente->getNomYApe()}}<br>
                        {{$contrato->relCliente->relCodAreaCel->codigoDeArea}} - {{$contrato->relCliente->celular}}
                    </label>
                    <input type="hidden" name='cliente_id' value='{{$contrato->relCliente->id}}'>
                </div>
                <div class="form-group border col-md-6">
                    <label for="afectado">Contrato: {{$contrato->id}}<br>
                        {{$contrato->relDireccion->relCalle->nombre . ', ' . 
                        $contrato->relDireccion->numero . ', ' . 
                        $contrato->relDireccion->relBarrio->nombre}}
                    </label>
                    <input type="hidden" name='afectado' value='{{$contrato->id}}'>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group border col-md-6">
                    <label for="titulo" class="mx-3">Título: <br>
                        {{$titulo->title}}
                    </label>
                    <input type="hidden" name='titulo' value='{{$titulo->id}}'>
                </div>
                <div class="form-group border col-md-6">
                    <label for="asignado" class="mx-3">Asignado a:</label>
                    <select name="asignado" class="form-control">
                        <option value="">Seleccione uno...</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{$usuario->id}}" {{ null !== (old('asignado')) ? (old('asignado') == $usuario->id ? 'selected' : '') : '' }}>{{$usuario->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
                <div class="form-row border align-items-center">
                    <div class="form-group col-md-4">
                        <label for="num_plan">Plan:</label>
                        <select class="form-control" name="num_plan" autofocus>
                            @foreach ($planes as $plan)
                                @if ( (null !== old('num_plan') && old('num_plan') == $plan->id) || $plan->id === $contrato->num_plan)
                                    <option value="{{$plan->id}}" selected>{{$plan->nombre}} {{ $plan->id === $contrato->num_plan ? '(ACTUAL)' : ''}}</option>
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
                                {{ null === old('prueba_definitivo') || old('prueba_definitivo') === 'prueba' ? '' : 'checked'}}
                                >
                                <label class="form-check-label" for="flexSwitchCheckChecked">Definitivo</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row border py-2">
                    <div class="input-group col-md-8">
                        <label for="" class="py-2">Seleccionar usuarios en seguimiento:
                            <div class="form-row">
                                @foreach ($usuarios as $usuario)
                                    <div class="py-0 col-md-4">
                                        <input type="checkbox" name="viewer{{$usuario->id}}" value="{{$usuario->id}}">
                                        <label for="{{$usuario->id}}"> {{$usuario->name}} </label><br>
                                    </div>
                                @endforeach
                            </div>
                        </label>
                    </div>
                </div>
                {{-- <div class="form-row">
                    <div class="form-group">
                        <label for="scheme_file">Seleccionar o arrastrar archivos para adjuntar a la Incidencia.</label>
                        <input type="file" name="scheme_file[]" class="form-control-file" multiple>
                    </div>
                </div> --}}
                <button type="submit" class="btn btn-primary" id="enviar">Crear</button>
                <a href="/adminContratos?contrato={{$contrato->id}}" class="btn btn-primary">Volver</a>
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