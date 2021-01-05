@extends('layouts.plantilla')
@section('contenido')
@can('paneles_edit')
@php
$mostrarSololectura = true;
@endphp
<h3>Agregar/quitar Barrios al Panel: {{ $panel_actual }}</h3>
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarPanelHasBarrio" method="post">
        @csrf
        @method('patch')

        
                        <table class="table table-sm table-bordered table-hover">
                    <caption>Listado de Barrios</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Seleccionado </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($barrios as $barrio)
                            <tr>
                                <td>{{$barrio->nombre}}</td>
                                <td>
                                        @php
                                             $encontrado = false
                                        @endphp
                                        @foreach ($panelHasBarrios as $item)
                                            @if ($item->barrio_id == $barrio->id)
                                                <input type="checkbox" name="{{$barrio->id}}" value="1" checked>
                                                @php
                                                    $encontrado = true
                                                @endphp
                                            @endif
                                        @endforeach
                                        @if (!$encontrado)
                                            <input type="checkbox" name="{{$barrio->id}}" value="1">
                                        @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            
    
            <input type="hidden" name="id" value="{{$panel_id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
            <a href="/adminPanelhasBarrio" class="btn btn-primary">Volver</a>
    </form>
    </div>

    @if( $errors->any() )
        <div class="alert alert-danger col-8 mx-auto">
            <ul>
                @foreach( $errors->all() as $error )
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif 
@endcan       
@include('sinPermiso')
@endsection