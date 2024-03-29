@extends('layouts.plantilla')
@section('contenido')
@can('productos_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-4 margin-10" action="" method="GET">
                        <h2 class="mx-3">Administración de Artículos</h2>
                        <label for="modelo" class="mx-3">Modelo</label>
                        <input type="text" name="modelo" class="form-control mx-3" id="modelo">
                        <label for="cod_comfueg" class="mx-3">Código comfueg</label>
                        <input type="text" name="cod_comfueg" class="form-control mx-3" id="cod_comfueg">
                        <button type="submit" class="btn btn-primary mx-3">Enviar</button>
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
                    <caption>Listado de Artículos</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Marca </th>
                            <th scope="col"> Modelo </th>
                            <th scope="col"> Código Comfueg </th>
                            <th scope="col"> Descripción </th>
                            <th scope="col" colspan="2">
                                @can('productos_create')
                                <a href="/agregarProducto" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($productos as $producto)
                            <tr>
                                
                            <th scope="row"> {{$producto->id}}</th>
                            <td>{{$producto->marca}}</td>
                            <td>{{$producto->modelo}}</td>
                            <td>{{$producto->cod_comfueg}}</td>
                            <td>{{$producto->descripcion}}</td>
                            <td>
                                @can('productos_edit')
                                <a href="/modificarProducto/{{$producto->id}}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $productos->links() }}
@endcan    
@include('sinPermiso')
@endsection