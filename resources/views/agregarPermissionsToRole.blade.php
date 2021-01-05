@extends('layouts.plantilla')
@section('contenido')
@can('roles_edit')
@php
$mostrarSololectura = true;
@endphp
<h3>Agregar/quitar permisos a Rol: {{ $Role->name }}</h3>

    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarPermissionsToRole" method="post">
        @csrf
        @method('patch')

        
                        <table class="table table-sm table-bordered table-hover">
                    <caption>Listado de Permisos</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Seleccionado </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($Permissions as $Permission)
                            <tr>
                                <td>{{$Permission->name}}</td>
                                <td>
                                    @if ($Permission->checked === 1)
                                        <input type="checkbox" name="{{$Permission->name}}" value="1" checked>
                                    @else    
                                        <input type="checkbox" name="{{$Permission->name}}" value="1">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            
    
            <input type="hidden" name="id" value="{{$Role->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
            <a href="/adminRoles" class="btn btn-primary">Volver</a>
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