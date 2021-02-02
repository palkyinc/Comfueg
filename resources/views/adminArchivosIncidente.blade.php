@extends('layouts.plantilla')
@section('contenido')
@can('SiteHasIncidente_index')
@php
$mostrarSololectura = true;
@endphp
                        <form class="form-inline mx-6 margin-10" action="" method="GET">
                        <h2 class="mx-2">Administración de Archivos del Incidente con ID: {{$incidente->crearNombre()}}</h2>
                        <!--    <label for="descripcion" class="mx-2">Nombre</label>
                            <input type="text" name="descripcion" class="form-control mx-3" id="descripcion">
                            <button type="submit" class="btn btn-primary mx-3">Buscar</button> -->
                            <a href="/adminIncidencias" class="btn btn-primary">volver</a>
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
                        <caption>Listado de Archivos</caption>
                        <thead class="thead-light">
                            <tr>
                                <th scope="col"> Id </th>
                                <th scope="col"> Tipo </th>
                                <th scope="col"> Nombre </th>
                                <th scope="col"> Fecha </th>
                                <th scope="col" colspan="2">
                                    @can('SiteHasIncidente_edit')
                                    <a href="/agregarArchivoIncidente/{{$incidente->id}}" class="btn btn-dark">Agregar</a>
                                    @endcan
                                </th>
                            </tr>
                        </thead>

                        <tbody id="deleteFile">
                            @foreach ($files as $file)
                            <tr>
                                
                                <th scope="row"> {{$file->id}}</th>
                                @if ($file->tipo == 'FILE')
                                    <td>Informe</td>
                                @else
                                    <td>Foto</td>
                                @endif
                                    <td>{{$file->file_name}}</td>
                                <td>{{$file->created_at}}</td>
                                <td>
                                    @can('SiteHasIncidente_edit')
                                    <form method="post" action="/eliminarArchivoIncidente/{{$file->id}}">
                                        @csrf
                                        @method('delete')
                                    <button type="submit"><img src="/imagenes/iconfinder_basket_1814090.svg" 
                                        alt="imagen de basket borrar" height="25px" title="Borrar"></button>
                                    </form>
                                    @endcan
                                </td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
    </div>
    {{ $files->links() }}
    @can('nodos_edit')
        @section('javascript')
            <script>
            const td = document.querySelector('#deleteFile');
            let botones = td.getElementsByTagName('button');
            for( let i=0; i< botones.length; i++) {
                    botones[i].addEventListener('click', e => 
                    {
                        if (!confirm("¿Estás seguro de borrar?")) {
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