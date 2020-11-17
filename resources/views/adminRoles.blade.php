@extends('layouts.plantilla')

@section('contenido')

                    <form class="form-inline mx-6 margin-10" action="" method="GET">
                        <h2 class="mx-2">Administración de Roles</h2>
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
                    <caption>Listado de Roles</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Creado </th>
                            <th scope="col" colspan="2">
                                <a href="/agregarAntena" class="btn btn-dark">Agregar</a>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($Roles as $Role)
                            <tr>
                                
                            <th scope="row"> {{$Role->id}}</th>
                            <td>{{$Role->name}}</td>
                            <td>{{$Role->created_at}}</td>
                            <td>
                                <a href="/modificarRole/{{ $Role->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $Roles->links() }}
    
@endsection