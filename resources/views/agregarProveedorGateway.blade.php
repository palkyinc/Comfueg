@extends('layouts.plantilla')
@section('contenido')
@can('proveedores_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Agregando nuevo Proveedor 1/2</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarProveedor2" method="get"">
        @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="gateway_id">Gateway: </label>
                    <select class="form-control" name="gateway_id">
                        @foreach ($gateways as $dato)
                            <option value="{{$dato->id}}">{{$dato->relEquipo->nombre}}->{{$dato->relEquipo->ip}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
                
            <button type="submit" class="btn btn-primary">Siguiente</button>
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