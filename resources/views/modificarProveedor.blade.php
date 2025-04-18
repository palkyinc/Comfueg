@extends('layouts.plantilla')
@section('contenido')
@can('proveedores_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Modificando Proveedor ID# {{$proveedor->id}}</h3>
<h4 class="pb-2">Gateway: {{$proveedor->relGateway->relEquipo->nombre}}</h4>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarProveedor" method="post"">
        @csrf
        @method('patch')
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nombre">Nombre: </label>
                    <input type="text" name="nombre" value="{{$proveedor->nombre}}" maxlength="30" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label for="ipProveedor">IP Proveedor: (*)</label>
                    <input type="text" name="ipProveedor" value="{{$proveedor->ipProveedor}}" maxlength="15" class="form-control">
                    <p>(*)Vacio para DHCP</p>
                </div>
                <div class="form-group col-md-3">
                    <label for="maskProveedor">Máscara Proveedor: (*)</label>
                    <input type="text" name="maskProveedor" value="{{$proveedor->maskProveedor}}" maxlength="15" class="form-control">
                    <p>(*)Vacio para DHCP</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="interface">Interface: </label>
                    <select class="form-control" name="interface">
                        @foreach ($interfaces['rtas'] as $interface)
                            @if ($proveedor->interface == $interface['.id'])
                                <option value="{{$interface['.id']}}" selected>{{$interface['name']}}</option>
                            @else
                                <option value="{{$interface['.id']}}">{{$interface['name']}}</option>
                            @endif
                        @endforeach
                        @foreach ($interfaces['vlans'] as$interface)
                            @if ($proveedor->interface == $interface['.id'])
                                <option value="{{$interface['.id']}}?v" selected>{{$interface['name']}}</option>
                            @else
                                <option value="{{$interface['.id']}}?v">{{$interface['name']}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="estado">Estado: </label>
                    <select class="form-control" name="estado">
                        @if ($proveedor->estado)
                            <option value="1" selected>Habilitado</option>
                            <option value="0">Deshabilitado</option>
                        @else
                            <option value="1">Habilitado</option>
                            <option value="0" selected>Deshabilitado</option>
                        @endif
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="bajada">Bajada(Kb.)(Mult. de 5Mb): </label>
                    <input type="number" name="bajada" value="{{$proveedor->bajada}}" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label for="subida">Subida (Kb.): </label>
                    <input type="number" name="subida" value="{{$proveedor->subida}}" class="form-control">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="dns">DNS para recursión: </label>
                    <input type="text" name="dns" value="{{$proveedor->dns}}" maxlength="15" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label for="ipGateway">IP defalut Gateway Router: </label>
                    <input type="text" name="ipGateway" value="{{$proveedor->ipGateway}}" maxlength="15" class="form-control">
                </div>
                @if (($proveedor->relGateway->wan_failover))
                    <div class="form-group col-md-4">
                        <label for="div_classifier">ID Wan Failover: </label>
                        <input type="text" name="wan_failover_id" value="{{$proveedor->wan_failover_id}}" maxlength="15" class="form-control">
                        <input type="text" name="div_classifier" value="1" maxlength="15" class="form-control" hidden>
                    </div>
                @else
                    <div class="form-group col-md-4">
                        <label for="div_classifier">Divisor para Classifier: </label>
                        <input type="text" name="div_classifier" value="{{$proveedor->relGateway->div_classifier}}" maxlength="15" class="form-control">
                    </div>
                @endif
            </div>

            <input type="hidden" name="gateway_id" value="{{$proveedor->gateway_id}}">
            <input type="hidden" name="id" value="{{$proveedor->id}}">
            <button type="submit" class="btn btn-primary">Modificar</button>
            <a href="/adminProveedores?gateway_id={{$proveedor->gateway_id}}" class="btn btn-primary">volver</a>
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