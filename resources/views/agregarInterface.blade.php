@extends('layouts.plantilla')

@section('contenido')


@can('antenas_edit')
@php
$mostrarSololectura = true;
@endphp
<h3>Agregar interface.</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarInterface" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="name">Name: </label>
                <input type="text" name="name" value="{{old('name')}}" class="form-control">
            </div>
        
            <div class="form-group col-md-4">
                <label for="list">Miembro de: </label>
                <select class="form-control" name="list" id="list">
                            <option value="">Sin Lista</option>
                            <option value="LAN">LAN</option>
                            <option value="WAN">WAN</option>
                </select>
            </div>
        
            <div class="form-group col-md-4">
                <label for="disabled">Deshabilitado: </label>
                <select class="form-control" name="disabled" id="disabled">
                    <option value="no">No</option>
                    <option value="yes">Si</option>
                </select>
            </div>
        </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="vlan-id">Vlan ID: </label>
                    <input type="number" name="vlan-id" value="{{old('vlan-id')}}" class="form-control">
                </div>
            
                <div class="form-group col-md-4">
                    <label for="interface">Interface: </label>
                    <select class="form-control" name="interface" id="interface">
                            <option value="" selected>Seleccionar</option>
                        @foreach ($interfaces as $item)
                            <option value="{{$item['name']}}">{{$item['name']}}</option>
                        @endforeach
                             
                    </select>
                </div>
            </div>
            <input type="hidden" name="gateway_id" value="{{$gateway_id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Agregar</button>
            <a href="/adminInterfaces" class="btn btn-primary">volver</a>
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