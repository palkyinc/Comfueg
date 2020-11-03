@extends('layouts.plantilla')

@section('contenido')

                    <form class="form-inline mx-4 margin-10" action="" method="GET">
                        <h2 class="mx-3">Administración de clientes</h2>
                        <label for="num_cliente" class="mx-3">Id Genesys</label>
                        <input type="text" name="num_cliente" class="form-control mx-3" id="num_cliente">
                        <label for="apellido" class="mx-3">Apellido</label>
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
                                <a href="/agregarCliente" class="btn btn-dark">Agregar</a>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                
                            <th scope="row"> {{$cliente->id}}</th>
                            @if ($cliente->nombre)
                                <td>{{$cliente->apellido . ', ' . $cliente->nombre}}</td>
                            @else 
                                <td>{{$cliente->apellido}}</td>
                            @endif
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
                                 <a href="/modificarCliente/{{$cliente->id}}" class="margenAbajo btn btn-outline-secundary">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $clientes->links() }}
    
@endsection