@extends('layouts.plantilla')
@section('contenido')
@can('clientes_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-4 margin-10" action="" method="GET">
                        <h2 class="mx-3">Administración de clientes</h2>
                        <label for="num_cliente" class="mx-3">Id Genesys</label>
                        <input type="text" name="num_cliente" class="form-control mx-3" id="num_cliente">
                        <label for="apellido" class="mx-3">Apellido / Razón Social</label>
                        <input type="text" name="apellido" class="form-control mx-3" id="apellido">
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
                    <caption>Listado de clientes</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> APELLIDO, Nombre </th>
                            <th scope="col"> Telefono </th>
                            <th scope="col"> Celular </th>
                            <th scope="col"> Email </th>
                            <th scope="col" colspan="2">
                                @can('clientes_create')
                                <a href="/agregarCliente" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                
                            <th scope="row"> {{$cliente->id}}</th>
                                <td>{{$cliente->getNomYApe()}}</td>
                            @if (!$cliente->telefono)
                                <td></td>
                            @else 
                                <td>{{$cliente->relCodAreaTel->codigoDeArea . '-' . $cliente->telefono}}</td>
                            @endif
                            @if (!$cliente->celular)
                                <td></td>
                            @else 
                                <td>{{$cliente->relCodAreaCel->codigoDeArea . '-15-' . $cliente->celular}}</td>
                            @endif
                            <td>{{$cliente->email}}</td>
                            <td>
                                @can('clientes_edit')
                                <a href="/modificarCliente/{{$cliente->id}}" title="Editar Cliente">
                                    <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="30px">
                                </a>
                                <a href="adminIssues/?rebusqueda=on&usuario=todos&cliente={{$cliente->id}}" title="Historial tickets" >
                                    <img src="imagenes/iconfinder_cinema_ticket_film_media_movie_icon.svg" alt="imganen ticket" height="30px">
                                </a>
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $clientes->appends(['num_cliente' => $num_cliente, 'apellido' => $apellido])->links() }}
@endcan    
@include('sinPermiso')
@endsection