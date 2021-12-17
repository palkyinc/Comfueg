@extends('layouts.plantilla')
@section('contenido')
@can('proveedores_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Agregando Proveedor 2/2</h3>
<h4 class="pb-2">Gateway: {{$gateway->relEquipo->nombre}}</h4>
    <div class="alert bg-light border col-8 mx-auto p-4">
        <form action="/agregarProveedor3" method="post"">
            @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nombre">Nombre: </label>
                        <input type="text" name="nombre" value="{{old('nombre')}}" maxlength="30" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="ipProveedor">IP Proveedor: (*)</label>
                        <input type="text" name="ipProveedor" value="{{old('ipProveedor')}}" maxlength="15" class="form-control">
                        <p>(*)Vacio para DHCP</p>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="maskProveedor">Máscara Proveedor: (*)</label>
                        <input type="text" name="maskProveedor" value="{{old('maskProveedor')}}" maxlength="15" class="form-control">
                        <p>(*)Vacio para DHCP</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="interface">Interface: </label>
                        <select class="form-control" name="interface">
                            <option value="">Seleccione una...</option>
                            @foreach ($interfaces['rtas'] as $interface)
                                <option value="{{$interface['.id']}}">{{$interface['name']}}</option>
                            @endforeach
                            @foreach ($interfaces['vlans'] as$interface)
                                <option value="{{$interface['.id']}}?v">{{$interface['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="estado">Estado: </label>
                        <select class="form-control" name="estado">
                            <option value="1">Habilitado</option>
                            <option value="0">Deshabilitado</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="bajada">Bajada(Kb.)(Mult. de 5Mb): </label>
                        <input type="number" name="bajada" value="{{old('bajada')}}" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="subida">Subida (Kb.): </label>
                        <input type="number" name="subida" value="{{old('subida')}}" class="form-control">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="dns">DNS para recursión: </label>
                        <input type="text" name="dns" value="{{old('dns')}}" maxlength="15" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="ipGateway">IP defalut Gateway Router: </label>
                        <input type="text" name="ipGateway" value="{{old('ipGateway')}}" maxlength="15" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="div_classifier">Divisor para Classifier: </label>
                        <input type="text" name="div_classifier" value="{{$gateway->div_classifier}}" maxlength="15" class="form-control">
                    </div>
                </div>

                <input type="hidden" name="gateway_id" value="{{$gateway->id}}">
                <button type="submit" class="btn btn-primary">Crear Nuevo</button>
                <a href="/adminProveedores" class="btn btn-primary">volver</a>
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