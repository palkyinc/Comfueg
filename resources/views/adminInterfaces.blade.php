@extends('layouts.plantilla')
@section('contenido')
@can('interfaces_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-6 margin-10" action="" method="GET">
                        <h2 class="mx-2">Administración de Interfaces</h2>
                        <select class="form-control" name="gateway_id" id="gateway_id">
                            <option value="">Seleccione un Gateway</option>
                            @foreach ($gateways as $gateway)
                                @if ($gateway_id && $gateway_id == $gateway->id)
                                    <option value="{{$gateway->id}}" selected>{{$gateway->relEquipo->nombre}}</option>
                                @else
                                    <option value="{{$gateway->id}}">{{$gateway->relEquipo->nombre}}</option>
                                @endif
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary mx-3">Buscar</button>
                    </form>

        @if ( session('mensaje') )
            <div class="alert alert-success">
                @foreach (session('mensaje') as $item)
                    {{ $item }} <br>
                @endforeach
            </div>
        @endif
        
<div class="table-responsive">
                
                <table class="table table-sm table-bordered table-hover">
                    <caption>Listado de Interfaces </caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Mac-Address </th>
                            <th scope="col"> Status </th>
                            <th scope="col"> List/Interface </th>
                            <th scope="col"> Vlan id </th>
                            <th scope="col"> Deshabilitado </th>
                            <th scope="col" colspan="2">
                                @can('interfaces_create')
                                    @if (isset($gateway_id))
                                        <a href="/agregarInterface/{{$gateway_id}}" class="btn btn-dark">Agregar Vlan</a>
                                    @endif
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody id="deleteFile">
                        @foreach ($elementos as $elemento)
                                @if ($elemento['disabled'] == 'true')
                                    <tr class="alert alert-light" role="alert">
                                @else
                                    <tr>
                                @endif
                                <th scope="row"> {{$elemento['.id']}}</th>
                                <td>{{$elemento['name']}}</td>
                                <td>{{$elemento['mac-address']}}</td>
                                <td>{{$elemento['status'] == 'link-ok' ? $elemento['rate'] : $elemento['status']}}</td>
                                <td>{{$elemento['list'] ?? 'Sin lista'}}</td>
                                <td>No</td>
                                <td>{{$elemento['disabled'] == 'true' ? 'Si' : 'No'}}</td>
                                <td colspan="2">
                                    @can('interfaces_edit')
                                    <a href="/modificarInterface/{{$elemento['.id']}}/{{$gateway_id}}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                    <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                    </a>
                                    @endcan
                                </td>
                                </tr>
                        @endforeach
                        @foreach ($vlans as $elemento)
                                @if ($elemento['disabled'] == 'true')
                                    <tr class="alert alert-light" role="alert">
                                @else
                                    <tr>
                                @endif
                                <th scope="row"> {{$elemento['.id']}}</th>
                                <td>{{$elemento['name']}}</td>
                                <td>{{$elemento['mac-address']}}</td>
                                <td>Vlan</td>
                                <td>{{$elemento['list'] ?? 'Sin lista'}} / {{$elemento['interface']}}</td>
                                <td>{{$elemento['vlan-id']}}</td>
                                <td>{{$elemento['disabled'] == 'true' ? 'Si' : 'No'}}</td>
                                <td>
                                    @can('interfaces_edit')
                                    <a href="/modificarInterface/{{$elemento['.id']}}/{{$gateway_id}}/true" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                        <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                    </a>
                                    @endcan
                                </td>
                                @if (!$elemento['tieneVlan'])
                                @can('interfaces_edit')
                                <td>
                                    <form method="post" action="/eliminarInterface/{{ $elemento['.id'] }}/{{$gateway_id}}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit"><img src="/imagenes/iconfinder_basket_1814090.svg" 
                                        alt="imagen de basket borrar" height="20px" title="Borrar"></button>
                                    </form>
                                </td>
                                    @endcan
                                    @endif
                                </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
    @can('interfaces_edit')
        @section('javascript')
            <script>
            const td = document.querySelector('#deleteFile');
            let botones = td.getElementsByTagName('button');
            for( let i=0; i< botones.length; i++) {
                    botones[i].addEventListener('click', e => 
                    {
                        if (!confirm("¿Estás seguro de borrar esta Interface?")) {
                        e.preventDefault();
                        }
                    });
            }
            </script>
        @endsection
    @endcan   
@endcan
@include('sinPermiso')
@endsection