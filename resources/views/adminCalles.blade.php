@extends('layouts.plantilla')
@section('contenido')
@can('calles_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-4 margin-10" action="" method="GET">
                        <h2 class="mx-3">Administración de calles</h2>
                        <label for="nombre" class="mx-3">Nombre</label>
                        <input type="text" name="nombre" value="{{$nombre}}" class="form-control mx-3" id="nombreSearch">
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
                    <caption>Listado de calles</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Descripción </th>
                            <th scope="col">
                                @can('calles_create')
                                <a href="/agregarCalle" class="btn btn-dark">Agregar</a>
                                <a href="/actualizarCalle" class="btn btn-dark">Actualizar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($calles as $calle)
                            <tr>
                                
                            <th scope="row"> {{$calle->id}}</th>
                            <td>{{$calle->nombre}}</td>
                            <td>
                                @can('calles_edit')
                                <a href="/modificarCalle/{{ $calle->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $calles->appends(['nombre' => $nombre])->links() }}
@endcan
@include('sinPermiso')
@endsection
@section('javascript')
    <script>
        let datosArray=[], optionsFinal;
        fetch('/searchCalles')
        .then(valor=>valor.json())
        .then(valor=>
            {
                valor.forEach(element => datosArray.push(element.nombre));
            });
        optionsFinal = {data : datosArray, list:{match:{enabled:true}}};
        $("#nombreSearch").easyAutocomplete(optionsFinal);
    </script>
@endsection