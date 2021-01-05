@extends('layouts.plantilla')
@section('contenido')
@can('mail_group_edit')
@php
$mostrarSololectura = true;
@endphp
<h3>Agregar/quitar Usuarios a Grupo de mail: {{ $mail_group->name }}</h3>

    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarUsersToMail_group" method="post">
        @csrf
        @method('patch')

        
                        <table class="table table-sm table-bordered table-hover">
                    <caption>Listado de Usuarios</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Seleccionado </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($Users as $user)
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>
                                    @php
                                             $encontrado = false
                                        @endphp
                                        @foreach ($usuarios_agregados as $item)
                                            @if ($item->id == $user->id)
                                                <input type="checkbox" name="{{$user->id}}" value="1" checked>
                                                @php
                                                    $encontrado = true
                                                @endphp
                                            @endif
                                        @endforeach
                                        @if (!$encontrado)
                                            <input type="checkbox" name="{{$user->id}}" value="1">
                                        @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            
    
            <input type="hidden" name="id" value="{{$mail_group->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
            <a href="/adminMailGroups" class="btn btn-primary">Volver</a>
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