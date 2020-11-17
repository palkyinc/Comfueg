@extends('layouts.plantilla')

@section('contenido')


    <h3>Agregar/quitar roles a Permiso: {{ $Permission->name }}</h3>

    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarPermissionToRoles" method="post">
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
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{$role->name}}</td>
                                <td>
                                    @if ($role->checked === 1)
                                        <input type="checkbox" name="{{$role->name}}" value="1" checked>
                                    @else    
                                        <input type="checkbox" name="{{$role->name}}" value="1">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            
    
            <input type="hidden" name="id" value="{{$Permission->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
            <a href="/adminPermissions" class="btn btn-primary">Volver</a>
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
        
@endsection