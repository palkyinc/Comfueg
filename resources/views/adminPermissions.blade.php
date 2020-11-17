@extends('layouts.plantilla')

@section('contenido')
@can('permisos_index')
                    <form class="form-inline mx-6 margin-10" action="" method="GET">
                        <h2 class="mx-2">Administración de Permisos</h2>
                        <label for="nombre" class="mx-3">Nombre</label>
                        <input type="text" name="nombre" class="form-control mx-3" id="nombreSearch">
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
                    <caption>Listado de Permisos</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Creado </th>
                            <th scope="col" colspan="2">
                                @can('permisos_create')
                                <a href="/agregarPermission" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($Permissions as $Permission)
                            <tr>
                                
                            <th scope="row"> {{$Permission->id}}</th>
                            <td>{{$Permission->name}}</td>
                            <td>{{$Permission->created_at}}</td>
                            <td>
                                @can('permisos_edit')
                                <a href="/modificarPermission/{{ $Permission->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                                <a href="/agregarPermissionToRoles/{{ $Permission->id }}" class="margenAbajo btn btn-outline-secundary" title="Agregar/Quitar a Rol">
                                <img src="imagenes/iconfinder_user-permission_3018548.svg" alt="imagen de Cambio de Roles" height="20px">
                                </a>
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $Permissions->links() }}
@endcan
@endsection