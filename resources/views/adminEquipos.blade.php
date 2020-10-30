@extends('layouts.plantilla')

@section('contenido')

                    <form class="form-inline mx-4 margin-10" action="" method="GET">
                        <h2 class="mx-3">Administración de equipos</h2>
                        <label for="ip" class="mx-3">IP: </label>
                        <input type="text" name="ip" class="form-control mx-3" id="ip">
                        <label for="mac_address" class="mx-3">Mac Address: </label>
                        <input type="text" name="mac_address" class="form-control mx-3" id="mac_address">
                        <button type="submit" class="btn btn-primary mx-3">Enviar</button>
                    </form>

        @if ( session('mensaje') )
            <div class="alert alert-success">
                @foreach (session('mensaje') as $item)
                    {{ $item }} <br>
                @endforeach
            </div>
        @endif
        
<div class="table-responsive" id="table">
                
                <table class="table table-sm table-bordered table-hover">
                    <caption>Listado de equipos</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Dispositivo </th>
                            <th scope="col"> Mac Address </th>
                            <th scope="col"> IP </th>
                            <th scope="col"> Antena </th>
                            <th scope="col"> Estado </th>
                            <th scope="col"> Comentario </th>
                            <th scope="col"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($equipos as $equipo)
                            <tr>
                                
                            <th scope="row"> {{$equipo->id}}</th>
                            <td>{{$equipo->nombre}}</td>
                            <td>{{$equipo->relProducto->modelo}}</td>
                            <td>{{$equipo->mac_address}}</td>
                            <td>{{$equipo->ip}}</td>
                            <td>{{$equipo->relAntena->descripcion}}</td>
                            @if ($equipo->fecha_baja != '')
                                <td>
                                    <form action="/equipoActivar" method="post" class="margenAbajo">
                                    @csrf
                                    @method('patch')
                                        <input type="hidden" name="idEdit" value="{{$equipo->id}}">
                                        <button class="btn btn-danger">Cambiar</button>
                                    </form>
                                </td>
                            @else
                                <td>
                                    <form action="/equipoActivar" method="post" class="margenAbajo">
                                    @csrf
                                    @method('patch')
                                        <input type="hidden" name="idEdit" value="{{$equipo->id}}">
                                        <button class="btn btn-success">Cambiar</button>
                                    </form>
                                </td>
                            @endif
                            <td>{{$equipo->comentario}}</td>
                            <td>
                                <a href="/modificarEquipo/{{ $equipo->id }}" class="margenAbajo btn btn-outline-secundary">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $equipos->links() }}
    
@endsection

@section('javascript')
<script>
    let btnActivar = document.getElementsByClassName('btn-danger');
    for (let i = 0; i < btnActivar.length; i++) {
        btnActivar[i].addEventListener('click', e => {
            if(!confirm("¿Seguro de ACTIVAR?")) {
                e.preventDefault()
            }
            
        })
    }
    let btnDesactivar = document.getElementsByClassName('btn-success');
    for (let i = 0; i < btnDesactivar.length; i++) {
        btnDesactivar[i].addEventListener('click', e => {
            if(!confirm("¿Seguro de DESACTIVAR?")) {
                e.preventDefault()
            }
        })
    }
        
    
</script>
@stop