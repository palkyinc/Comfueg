@extends('layouts.plantilla')

@section('contenido')
@can('ciudades_index')
                    <form class="form-inline mx-4 margin-10" action="" method="GET">
                        <h2 class="mx-3">Administración de ciudades</h2>
                        <label for="nombre" class="mx-3">Nombre</label>
                        <input type="text" name="nombre" class="form-control mx-3" id="nombre">
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
                    <caption>Listado de ciudades</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Nombre </th>
                            <th scope="col" colspan="2">
                                @can('ciudades_create')
                                <a href="/agregarCiudad" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($ciudades as $ciudad)
                            <tr>
                                
                            <th scope="row"> {{$ciudad->id}}</th>
                            <td>{{$ciudad->nombre}}</td>
                            <td>
                                @can('ciudades_edit')
                                <a href="/modificarCiudad/{{ $ciudad->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $ciudades->links() }}
@endcan    
@endsection