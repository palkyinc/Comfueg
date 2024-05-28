@extends('layouts.plantilla')
@section('contenido')
@can('equipos_index')
@php
$mostrarSololectura = true;
@endphp
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
        @if ( session('mensaje_full') )
            <ul class="list-group">
                @foreach (session('mensaje_full') as $key => $items)
                    @if ($key === 'success')
                            @foreach ($items as $item)
                                <li class="list-group-item list-group-item-success">{{ $item }}</li>
                            @endforeach
                    @endif
                    @if ($key === 'error')
                            @foreach ($items as $item)
                                <li class="list-group-item list-group-item-danger"> {{ $item }} </li>
                            @endforeach
                    @endif
                @endforeach
            </ul>
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
                            <th scope="col" colspan="2">
                                @can('equipos_create')
                                <a href="/agregarEquipo" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
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
                                    @can('equipos_edit')
                                    <form action="/equipoActivar" method="post" class="margenAbajo">
                                    @csrf
                                    @method('patch')
                                        <input type="hidden" name="idEdit" value="{{$equipo->id}}">
                                        <button class="btn btn-danger"  title="Habilitar">Activar</button>
                                    </form>
                                    @endcan
                                </td>
                            @else
                                <td>
                                    @can('equipos_edit')
                                    <form action="/equipoActivar" method="post" class="margenAbajo">
                                    @csrf
                                    @method('patch')
                                        <input type="hidden" name="idEdit" value="{{$equipo->id}}">
                                        <button class="btn btn-success" title="DesHabilitar">Desactivar</button>
                                    </form>
                                    @endcan
                                </td>
                            @endif
                            <td>{{$equipo->comentario}}</td>
                            <td  class="d-flex">
                                @can('equipos_edit')
                                <a href="/modificarEquipo/{{ $equipo->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                                @endcan
                                @can('mac_exception_create')
                                @if (!$equipo->isFree() && !$equipo->fecha_baja)
                                    <a href="/agregarException/{{ $equipo->id }}" class="margenAbajo btn btn-outline-secundary" title="Agregar Panel Excepción">
                                        <img src="imagenes/9111103_player_list_add_icon.svg" alt="imagen de list add" height="20px">
                                    </a>
                                @endif
                                @endcan
                                @can('mac_exception_create')
                                @if (isset($equipo->isFree()['exception_id']))
                                    <form action="/borrarException" method="post" class="margenAbajo">
                                    @csrf
                                    @method('delete')
                                        <input type="hidden" name="idEdit" value="{{$equipo->isFree()['exception_id']}}">
                                        <button type="submit" class="btn btn-outline-secundary" title="Borrar Excepción">
                                            <img src="imagenes/3556096_delete_list_remove_ui_icon.svg" alt="imagen de list remove" height="20px">
                                        </botton>
                                    </form>
                                @endif
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $equipos->render() }}
@endcan    
@include('sinPermiso')
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