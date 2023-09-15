@extends('layouts.plantilla')
@section('contenido')
@can('btfDebitos_create')
@php
$mostrarSololectura = true;
@endphp
<h3>Nuevo Btf débito</h3>
        <div class="alert bg-light border col-8 mx-auto p-4">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="id_demo">Genesys ID</label>
                        <input type="text" value="{{$debito->cliente_id}}" name="id_demo" class="form-control" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="NomYApe_demo">Nombre y Apellido</label>
                        <input type="text" value="{{$debito->relCliente->getNomYApe(true)}}" name="NomYApe_demo" class="form-control" disabled>
                    </div>
                </div>
        </div>
        <div class="alert bg-light border col-8 mx-auto p-4">
            <form action="/agregarBtfDebito" method="post">
                @csrf
                <input type="text" name="cliente_id" value="{{old('cliente_id') ?? $debito->cliente_id}}" hidden>
                <input type="text" value="{{old('NomYApe') ?? $debito->relCliente->getNomYApe(true)}}" name="NomYApe" class="form-control" hidden>
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
                        <input type="text" value="{{old('dni') ?? $debito->dni}}" name="dni"class="form-control" maxlength="8">
                    </div>
                    <div class="form-group col col-lg-4">
                        <label for="concepto_id">Concepto</label>
                        <select name="concepto_id" class="form-control">
                            <option value="">Seleccionar...</option>
                            @foreach ($conceptos as $concepto)
                                <option value="{{$concepto->id}}">{{$concepto->concepto}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-auto">
                        <label for="cuenta">Cuenta</label>
                        <input type="text" value="{{old('cuenta') ?? $debito->cuenta}}" name="cuenta" class="form-control" maxlength="9">
                    </div>
                    <div class="form-group col col-lg-3">
                        <label for="tipo_cuenta">Tipo de Cuenta</label>
                        <select name="tipo_cuenta" class="form-control">
                            <option value="01" {{ (null !== old('tipo_cuenta') && old('tipo_cuenta') === '01') ? 'selected' : '' }}>Cuenta Corriente</option>
                            <option value="03" {{ (null !== old('tipo_cuenta') && old('tipo_cuenta') === '03') ? 'selected' : '' }}>Caja de ahorro</option>
                        </select>
                    </div>
                    <div class="form-group col col-lg-2">
                        <label for="sucursal">Sucursal</label>
                        <select name="sucursal" class="form-control">
                            <option value="02" {{(null !== old('sucursal') && old('sucursal') === '02') ? 'selected' : ''}}>Ushuaia</option>
                            <option value="03" {{(null !== old('sucursal') && old('sucursal') === '03') ? 'selected' : (null === old('sucursal') ? 'selected' : '') }}>Río Grande</option>
                            <option value="04" {{(null !== old('sucursal') && old('sucursal') === '04') ? 'selected' : ''}}>Río Gallegos</option>
                            <option value="05" {{(null !== old('sucursal') && old('sucursal') === '05') ? 'selected' : ''}}>Buenos Aires</option>
                            <option value="22" {{(null !== old('sucursal') && old('sucursal') === '22') ? 'selected' : ''}}>Kuanip</option>
                            <option value="24" {{(null !== old('sucursal') && old('sucursal') === '24') ? 'selected' : ''}}>El Calafate</option>
                            <option value="33" {{(null !== old('sucursal') && old('sucursal') === '33') ? 'selected' : ''}}>Chacra II</option>
                            <option value="42" {{(null !== old('sucursal') && old('sucursal') === '42') ? 'selected' : ''}}>Malvinas Argentinas</option>
                            <option value="43" {{(null !== old('sucursal') && old('sucursal') === '43') ? 'selected' : ''}}>Tolhuin</option>
                            <option value="20" {{(null !== old('sucursal') && old('sucursal') === '20') ? 'selected' : ''}}>Cuentas Sueldos</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <input class="btn btn-primary" type="submit" value="Guardar">
                        <a href="/adminBtfDebitos" class="btn btn-primary">Volver</a>
                    </div>
                    <div class="form-check col-md-auto">
                        <input type="checkbox" name="excepcional" class="form-check-input">
                        <label for="excepcional" class="form-check-label">Excepcional</label>
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