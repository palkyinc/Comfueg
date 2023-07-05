@extends('layouts.plantilla')
@section('contenido')
@can('btfDebitos_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Nuevo Btf débito</h3>
    @if ($sin_cliente_id && null === (old('con_cliente_id')))
        <div class="alert bg-light border col-8 mx-auto p-4">
            <form action="/agregarBtfDebito" method="post">
                @method('put')
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cliente_id">Genesys ID: </label>
                    <input type="text" name="cliente_id" value="{{old('cliente_id')}}" maxlength="6"  class="form-control">
                    </div>
                </div>
                    <button type="submit" class="btn btn-primary">Consultar</button>
                    <a href="/adminBtfDebitos" class="btn btn-primary">Volver</a>
            </form>
        </div>
    
    @else
        <div class="alert bg-light border col-8 mx-auto p-4">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="id_demo">Genesys ID</label>
                        <input type="text" value="{{old('cliente_id') ?? $cliente_id}}" name="id_demo" class="form-control" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="NomYApe_demo">Nombre y Apellido</label>
                        <input type="text" value="{{old('NomYApe') ?? $cliente_NomYApe}}" name="NomYApe_demo" class="form-control" disabled>
                    </div>
                </div>
        </div>
        <div class="alert bg-light border col-8 mx-auto p-4">
            <form action="/agregarBtfDebito" method="post">
                @csrf
                <input type="text" name="cliente_id" value="{{old('cliente_id') ?? $cliente_id}}" hidden>
                <input type="text" value="{{old('NomYApe') ?? $cliente_NomYApe}}" name="NomYApe" class="form-control" hidden>
                <input type="text" name="con_cliente_id" value="1" hidden>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">Importe</label>
                        <div class="form-row">
                                <div class="input-group-prepend col-md-5 mx-0 px-0">
                                    <span class="input-group-text">$</span>
                                    <input type="text" value="{{old('importe1')}}" name="importe1" maxlength="11" class="form-control" aling="right" autofocus>
                                </div>
                                <div class="input-group-append col-md-3 mx-0 px-0">
                                    <span class="input-group-text">.</span>
                                    <input type="text" value="{{old('importe2') ?? '00'}}" name="importe2" maxlength="2" class="form-control" aria-label="Amount (to the nearest dollar)">
                                </div>
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="dni">DNI</label>
                        <input type="text" value="{{old('dni')}}" name="dni"class="form-control" maxlength="8">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-auto">
                        <label for="cuenta">Cuenta</label>
                        <input type="text" value="{{old('cuenta')}}" name="cuenta" class="form-control" maxlength="10">
                    </div>
                    <div class="form-group col col-lg-3">
                        <label for="tipo_cuenta">Tipo de Cuenta</label>
                        <select name="tipo_cuenta" class="form-control">
                            <option value="01">Cuenta Corriente</option>
                            <option value="03">Caja de ahorro</option>
                        </select>
                    </div>
                    <div class="form-group col col-lg-2">
                        <label for="sucursal">Sucursal</label>
                        <select name="sucursal" class="form-control">
                            <option value="02">Ushuaia</option>
                            <option value="03" selected>Río Grande</option>
                            <option value="04">Río Gallegos</option>
                            <option value="05">Buenos Aires</option>
                            <option value="22">Kuanip</option>
                            <option value="24">El Calafate</option>
                            <option value="33">Chacra II</option>
                            <option value="42">Malvinas Argentinas</option>
                            <option value="43">Tolhuin</option>
                            <option value="20">Cuentas Sueldos</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <input class="btn btn-primary" type="submit" value="Guardar">
                        <a href="adminBtfDebitos" class="btn btn-primary">Volver</a>
                    </div>
                    <div class="form-check col-md-auto">
                        <input type="checkbox" name="excepcional" class="form-check-input">
                        <label for="excepcional" class="form-check-label">Excepcional</label>
                    </div>
                </div>
            </form>
        </div>
    @endif
    

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