@extends('layouts.plantilla')
@section('contenido')
@can('dns_paneles_edit')
@php
$mostrarSololectura = true;
@endphp
<h3>Modificando DNS del Panel con ID: {{ $panel->id }}</h3>
   
<div class="alert col-8 mx-auto p-4">
        <table class="table">
            <thead>
                <tr>
                <th scope="col">IP</th>
                <th scope="col">Tipo</th>
                <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dnsList as $dns)
                    <tr>
                    <th scope="row">{{$dns['server']}}</th>
                    @if ($dns['passThrough'])
                        <td>Pass Through</td>
                    @elseif ($dns['external'])
                        <td>External</td>
                    @else
                        <td>None</td>
                    @endif
                    <td>
                        <form method="post" action="/eliminarDnsPanel">
                            @csrf
                                @method('delete')
                                <input type="hidden" name="panel_id" value="{{$panel->id}}" class="form-control">
                                <input type="hidden" name="dns_server" value="{{$dns['server']}}" class="form-control">
                                <button type="submit">
                                    <img src="/imagenes/iconfinder_basket_1814090.svg" alt="imagen de basket borrar" height="20px" title="Borrar Plan">
                                </button>
                        </form>
                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <form action="/agregarDnsPanel" method="post" enctype="multipart/form-data">
            @csrf
            @method('post')
                <div class="form-row bg-light border justify-content-md-center p-3">
                    <div class="form-group col-md-3 justify-content-center">
                        <label for="ssid">IP DNS Server: </label>
                        <input type="text" name="dns_server_ip" value="{{old('dns_server_ip')}}" maxlength="15" class="form-control">
                        <input type="hidden" name="panel_id" value="{{$panel->id}}" class="form-control">
                    </div>
                    <div class="form-group col-md-2">

                    </div>
                    <div class="form-group col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="inputCheck" value="external" {{old('inputCheck') === 'external' ? 'checked' : ''}}>
                            <label class="form-check-label" for="exampleRadios1">
                                External
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="inputCheck" value="passThrough" {{old('inputCheck') === 'passThrough' ? 'checked' : ''}}>
                            <label class="form-check-label" for="exampleRadios2">
                                Pass Through
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="inputCheck" value="none" {{(old('inputCheck') !== 'external' && old('inputCheck') !== 'passThrough')? 'checked' : ''}}>
                            <label class="form-check-label" for="exampleRadios3">
                                None
                            </label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Agregar</button>
                <a href="/adminPaneles" class="btn btn-primary">Volver Paneles</a>
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