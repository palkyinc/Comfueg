@extends('layouts.plantilla')
@section('contenido')
@can('proveedores_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-4 margin-10" action="" method="GET">
                        <h2 class="mx-3">Administración de Proveedores</h2>
                        <label for="sitio" class="mx-3">Gateway</label>
                        <select class="form-control" name="gateway_id">
                            <option value="">Seleccione un Gateway...</option>
                            @foreach ($gateways as $gateway)
                                @if ($gateway->id == $gateway_id)
                                    <option value="{{$gateway->id}}" selected>{{$gateway->relEquipo->nombre}}</option>
                                @else
                                    <option value="{{$gateway->id}}">{{$gateway->relEquipo->nombre}}</option>
                                @endif
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary mx-3">Enviar</button>
                        @can('proveedores_edit')
                            @if ($actualizar)
                                <a href="/actualizarGateway/" class="margenAbajo btn btn-outline-warning" title="Actualizar">
                                    Actualizar Gateway
                                </a>
                            @endif
                        @endcan
                    </form>

        {{-- @if ( session('mensaje') )
            <div class="alert alert-success">
                @foreach (session('mensaje') as $item)
                    {{ $item }} <br>
                @endforeach
            </div>
        @endif --}}
        @if ( session('mensaje') )
        <ul class="list-group">
            @foreach (session('mensaje') as $key => $items)
                {{-- @dd($key . ' + ' . $items); --}}
                @if ($key === 'success')
                        @foreach ($items as $item)
                            <li class="list-group-item list-group-item-success">{{ $item }}</li>
                        @endforeach
                @endif
                @if ($key === 'error')
                        @foreach ($items as $item)
                            <li class="list-group-item list-group-item-danger"> {{ $item }} </li>
                        @endforeach
                @endif
                @if ($key === 'info')
                        @foreach ($items as $item)
                            <li class="list-group-item list-group-item-info"> {{ $item }} </li>
                        @endforeach
                @endif
            @endforeach
        </ul>
    @endif
        
<div class="table-responsive">
                
                <table class="table table-sm table-bordered table-hover">
                    <caption>Total de Proveedores: {{count($proveedores)}}</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Bajada/Subida (Kb)</th>
                            <th scope="col"> Interface </th>
                            <th scope="col"> DNS recursión </th>
                            <th scope="col"> Wan Failover ID </th>
                            <th scope="col"> Estado </th>
                            <th scope="col"> En Linea </th>
                            <th scope="col" colspan="2">
                                @can('proveedores_create')
                                    @if (!$gateway_id)
                                        <a href="/agregarProveedor" class="btn btn-dark">Agregar</a>
                                    @else
                                        <a href="/agregarProveedor2?gateway_id={{$gateway_id}}" class="btn btn-dark">Agregar</a>
                                    @endif
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody id="deleteFile">
                        @foreach ($proveedores as $proveedor)
                            @if ($proveedor->estado)
                                <tr>
                            @else
                                <tr class="table-danger">
                            @endif
                            <th scope="row"> {{$proveedor->id}}</th>
                            <td>{{$proveedor->nombre}}</td>
                            <td>{{$proveedor->bajada}}/{{$proveedor->subida}}</td>
                            <td>{{$proveedor->getInterfaceName()}}</td>
                            <td>{{$proveedor->dns}}</td>
                            <td>{{$proveedor->wan_failover_id}}</td>
                            @if ($proveedor->estado)
                                <td>Habilitado</td>
                            @else
                                <td>Deshabilitado</td>
                            @endif
                            @if ($proveedor->enLinea())
                                <td class="table-success">En Línea</td>
                            @else
                                <td class="table-danger">Fuera de Línea</td>
                            @endif
                            @can('proveedores_edit')
                                <td>
                                    <a href="/modificarProveedor/{{$proveedor->id}}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                    <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                    </a>
                                </td>
                                @if (!$proveedor->estado && !$actualizar)
                                    <td>
                                        <form method="post" action="/eliminarProveedor/{{ $proveedor->id }}" class="margenAbajo">
                                            @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-outline-secundary">
                                                    <img src="/imagenes/iconfinder_basket_1814090.svg" alt="imagen de basket borrar" height="25px" title="Borrar Proveedor">
                                                </button>
                                        </form>
                                    </td>
                                @endif
                            @endcan
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        @if ($paginate)
            {{ $proveedores->links() }}
        @endif
        @can('planes_create')
            @section('javascript')
                <script>
                const td = document.querySelector('#deleteFile');
                let botones = td.getElementsByTagName('button');
                for( let i=0; i< botones.length; i++) {
                        botones[i].addEventListener('click', e => 
                        {
                            if (!confirm("¿Estás seguro de borrar este Proveedor?")) {
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