@extends('layouts.plantilla')

@section('contenido')

                    <form class="form-inline mx-6 margin-10" action="" method="GET">
                        <h2 class="mx-2">Administración de Usuarios</h2>
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
                    <caption>Listado de Usuarios</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Email </th>
                            <th scope="col"> Creado </th>
                            <th scope="col" colspan="2">
                                <a href="/agregarUser" class="btn btn-dark">Agregar</a>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($Users as $User)
                            <tr>
                                
                            <th scope="row"> {{$User->id}}</th>
                            <td>{{$User->name}}</td>
                            <td>{{$User->email}}</td>
                            <td>{{$User->created_at}}</td>
                            <td>
                                <a href="/modificarUser/{{ $User->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                                <a href="/agregarRoleToUser/{{ $User->id }}" class="margenAbajo btn btn-outline-secundary" title="Cambiar a Rol">
                                <img src="imagenes/iconfinder_user-permission_3018548.svg" alt="imagen de Cambio de Roles" height="20px">
                                </a>
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $Users->links() }}
    
@endsection