@extends('layouts.plantilla')

@section('contenido')


@can('antenas_edit')
@php
$mostrarSololectura = true;
@endphp
<h3>Modificando interface: {{ $elemento['name'] }}</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarInterface" method="post">
        @csrf
        @method('patch')

        
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="name">Name: </label>
                <input type="text" name="name" value="{{$elemento['name']}}" class="form-control">
            </div>
        
            <div class="form-group col-md-4">
                <label for="list">Miembro de: </label>
                <select class="form-control" name="list" id="list">
                            <option value="">Sin Lista</option>
                            @if (isset($elemento['list']) && 'WAN' == $elemento['list'])
                                <option value="WAN" selected>WAN</option>
                                <option value="LAN">LAN</option>
                            @elseif(isset($elemento['list']) && 'LAN' == $elemento['list'])
                                <option value="WAN">WAN</option>
                                <option value="LAN" selected>LAN</option>
                            @else
                                <option value="WAN">WAN</option>
                                <option value="LAN">LAN</option>
                            @endif
                </select>
            </div>
        
            <div class="form-group col-md-4">
                <label for="disabled">Deshabilitado: </label>
                <select class="form-control" name="disabled" id="disabled">
                            @if (isset($elemento['disabled']) && 'false' == $elemento['disabled'])
                                <option value="no" selected>No</option>
                                <option value="yes">Si</option>
                            @else
                                <option value="no">No</option>
                                <option value="yes" selected>Si</option>
                            @endif
                </select>
            </div>
        </div>
        @if ($esVlan)
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="vlan-id">Vlan ID: </label>
                    <input type="number" name="vlan-id" value="{{$elemento['vlan-id']}}" class="form-control">
                </div>
            
                <div class="form-group col-md-4">
                    <label for="interface">Interface: </label>
                    <select class="form-control" name="interface" id="interface">
                            <option value="" selected>Seleccionar</option>
                        @foreach ($interfaces as $item)
                            @if ($item['name'] == $elemento['interface'])
                                <option value="{{$item['name']}}" selected>{{$item['name']}}</option>
                            @else
                                <option value="{{$item['name']}}">{{$item['name']}}</option>
                            @endif
                        @endforeach
                             
                    </select>
                </div>
            </div>
            <input type="hidden" name="esVlan" value="1">
        @endif
            
    
            <input type="hidden" name="interface_id" value="{{$elemento['.id']}}">
            <input type="hidden" name="gateway_id" value="{{$gateway_id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
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