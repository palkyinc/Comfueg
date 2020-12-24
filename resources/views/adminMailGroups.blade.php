@extends('layouts.plantilla')

@section('contenido')
@can('mail_group_index')
                    <form class="form-inline mx-6 margin-10" action="" method="GET">
                        <h2 class="mx-2">Administración de Grupos de mail</h2>
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
                    <caption>Listado de Grupos de mail</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Creado </th>
                            <th scope="col" colspan="2">
                                @can('mail_group_create')
                                <a href="/agregarMail_group" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($Mail_groups as $mail_group)
                            <tr>
                                
                            <th scope="row"> {{$mail_group->id}}</th>
                            <td>{{$mail_group->name}}</td>
                            <td>{{$mail_group->created_at}}</td>
                            <td>
                                @can('mail_group_edit')
                                <a href="/modificarMail_group/{{ $mail_group->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar Nombre">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                                <a href="/agregarUsersToMail_group/{{ $mail_group->id }}" class="margenAbajo btn btn-outline-secundary" title="Agregar/Quitar a Usuarios">
                                <img src="imagenes/iconfinder_user-permission_3018548.svg" alt="imagen de Cambio de Roles" height="20px">
                                </a>
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $Mail_groups->links() }}
@endcan
@endsection