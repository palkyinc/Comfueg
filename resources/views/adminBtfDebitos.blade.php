@extends('layouts.plantilla')
@section('contenido')
@can('btfDebitos_index')
@php
$mostrarSololectura = true;
@endphp
    <form class="form-inline mx-6 margin-10" action="" method="GET">
        <h3 class="px-5">Administración de BTF Débitos</h3>
        <label for="codComfueg" class="px-3">Nro. de Cliente</label>
        <input type="text" name="codComfueg" value="{{$codComfueg}}" class="form-control mx-3">
        <div class="form-check form-switch col-2">
            @if ($habilitadas === 'on')
                <input class="form-check-input" type="checkbox" name="habilitadas" id="flexSwitchCheckChecked" checked>
            @else
                <input class="form-check-input" type="checkbox" name="habilitadas" id="flexSwitchCheckChecked">
            @endif
            <input type="hidden" name="rebusqueda" value="on">
            <label class="form-check-label" for="flexSwitchCheckChecked">
                Solo Habilitadas
            </label>
        </div>
        <button type="submit" class="btn btn-primary mx-3">Buscar</button>
    </form>

    @if ( session('mensaje') )
        <div class="alert alert-success">
            @foreach (session('mensaje') as $item)
                {{ $item }} <br>
            @endforeach
        </div>
    @endif
    @if ( session('warning') )
        <div class="alert alert-warning">
            @foreach (session('warning') as $item)
                {{ $item }} <br>
            @endforeach
        </div>
    @endif
        
<div class="table-responsive">
    <table class="table table-sm table-bordered table-hover">
        <caption>Listado de BTF Débitos</caption>
        <thead class="thead-light">
            <tr>
                <th scope="col" class="text-center"> DNI </th>
                <th scope="col" class="text-center"> N° Cliente </th>
                <th scope="col" class="text-center"> Suc. </th>
                <th scope="col" class="text-center"> Tipo Cta. </th>
                <th scope="col" class="text-center"> Cuenta </th>
                <th scope="col" class="text-center"> Importe </th>
                <th scope="col" class="text-center"> Concepto </th>
                <th scope="col" class="text-center"> Ult. Presentación </th>
                <th scope="col" class="text-center"> Excepcional </th>
                <th scope="col" colspan="2">
                    @can('btfDebitos_create')
                    <a href="/agregarBtfDebito" class="btn btn-dark">Agregar</a>
                    <a href="/presentarBtfDebito" class="btn btn-light">Presentar</a>
                    @endcan
                </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($btf_debitos as $btf_debito)
                @if ($btf_debito->desactivado)
                    <tr class="alert alert-danger" role="alert">
                @else
                    <tr role="alert">
                @endif
                    
                    <th scope="row" class="text-center"> {{$btf_debito->dni}}</th>
                    <td class="text-center">{{$btf_debito->cliente_id}}</td>
                    <td class="text-center">{{$btf_debito->getSucursal()}}</td>
                    <td class="text-center">{{$btf_debito->getTipoCuenta()}}</td>
                    <td class="text-center">{{$btf_debito->cuenta}}</td>
                    <td class="text-right">${{$btf_debito->importe}}</td>
                    <td class="text-center">{{$btf_debito->relConceptos_debito->concepto}}</td>
                    <td class="text-center">{{$btf_debito->fecha_presentacion}}</td>
                    <td class="text-center">
                        @if ($btf_debito->excepcional)
                            <img src="imagenes/299110_check_sign_icon.svg" alt="imagen de check" height="20px">
                        @endif
                    </td>
                    <td  class="conFlex text-center">
                        @can('btfDebitos_edit')
                            <a href="/modificarBtfDebitos/{{ $btf_debito->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                            </a>
                            @if ($btf_debito->desactivado)
                                <form action="/habilitarBtfDebito" method="post" class="margenAbajo">
                                @csrf
                                @method('patch')
                                    <input type="hidden" name="id" value="{{$btf_debito->id}}">
                                    <button class="btn btn-outline-secundary boton-Alta"  title="Habilitar">
                                        <img src="imagenes/iconfinder_Multimedia_Turn_on_off_power_button_interface_3841792.svg" alt="imagen de activar" height="20px">
                                    </button>
                                </form>
                            @else
                                <form action="/deshabilitarBtfDebito" method="post" class="margenAbajo">
                                @csrf
                                @method('delete')
                                    <input type="hidden" name="id" value="{{$btf_debito->id}}">
                                    <button class="btn btn-outline-secundary boton-Baja"  title="Deshabilitar">
                                        <img src="imagenes/iconfinder_Turn_On__Off_2134663.svg" alt="imagen de Desactivar" height="20px">
                                    </button>
                                </form>
                            @endif
                        @endcan
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
        {{ $btf_debitos->links() }}
@section('javascript')
    
@endsection
@endcan
@include('sinPermiso')
@endsection