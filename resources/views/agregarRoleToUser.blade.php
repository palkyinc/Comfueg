@extends('layouts.plantilla')

@section('contenido')

    <h3>Cambiar Rol a Usuario: {{ $User->name }}</h3>
@can('usuarios_edit')

    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarRoleToUser" method="post">
        @csrf
        @method('patch')

        
                        <table class="table table-sm table-bordered table-hover">
                    <caption>Listado de Roles</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Seleccionado </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($Roles as $Role)
                            <tr>
                                <td>{{$Role->name}}</td>
                                <td>
                                    @if ($Role->checked === 1)
                                        <input type="radio" name="role" value="{{$Role->name}}" checked>
                                    @else    
                                        <input type="radio" name="role" value="{{$Role->name}}">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                        <td>Ninguno</td>
                        <td>
                            <input type="radio" name="role" value="none" default>
                        </td>
                        </tr>
                    </tbody>
                </table>

            <input type="hidden" name="id" value="{{$User->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
            <a href="/adminUsers" class="btn btn-primary">Volver</a>
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
@endsection