@extends('layouts.plantilla')
@section('contenido')
@can('nodos_index')
@php
$mostrarSololectura = true;
@endphp
                        <form class="form-inline mx-6 margin-10" action="" method="GET">
                        <h2 class="mx-2">Administración de Archivos del Sitio con ID: {{$sitio_id}}</h2>
                            <label for="descripcion" class="mx-2">Nombre</label>
                            <input type="text" name="descripcion" class="form-control mx-3" id="descripcion">
                            <button type="submit" class="btn btn-primary mx-3">Buscar</button>
                            <a href="/mostrarNodo/{{$sitio_id}}" class="btn btn-primary">volver</a>
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
                                    @can('nodos_edit')
                                    <a href="/agregarArchivoSitio/{{$sitio_id}}" class="btn btn-dark">Agregar</a>
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
                                    @can('nodos_edit')
                                    <a href="/eliminarArchivo/{{ $file->id }}/{{$sitio_id}}"
                                        class="margenAbajo btn btn-outline-secundary" title="Borrar">
                                        <img src="/imagenes/iconfinder_basket_1814090.svg" 
                                        alt="imagen de basket borrar" height="25px">
                                    </a>
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
            let botones = td.getElementsByTagName('a');
            for( let i=0; i< botones.length; i++) {
                    botones[i].addEventListener('click', e => 
                    {
                        e.preventDefault();
                        console.log(botones[i].href);
                        Swal.fire({
                                title: '¿Desea eliminar el archivo?',
                                text: "Esta acción no se puede deshacer.",
                                showCancelButton: true,
                                cancelButtonColor: '#8fc87a',
                                cancelButtonText: 'No, no lo quiero eliminar',
                                confirmButtonColor: '#d00',
                                confirmButtonText: 'Si, lo quiero eliminar'
                            }).then((result) => {
                                if (result.value) {
                                    //redirección a adminProductos
                                    window.location = botones[i].href
                                }
                            })

                    });
            }
            </script>
        @endsection
    @endcan
    @endcan
@include('sinPermiso')
@endsection