@extends('layouts.plantilla')
@section('contenido')
@can('altas_index')
@php
$mostrarSololectura = true;
@endphp
    @if ( session('mensaje') )
        <ul class="list-group">
            @foreach (session('mensaje') as $key => $items)
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
        <form class="form-inline mx-6 margin-10" action="" method="GET">
            <h2 class="mx-2">Admin Alta de Contrato</h2>
            <label for="apellido" class="mx-2">Apellido</label>
            <input type="text" name="apellido" class="form-control mx-3" id="apellido">
            <label for="calle" class="mx-2">Calle</label>
            <input type="text" name="calle" class="form-control mx-3" id="calle">
            @if ($instaladas)
                <input class="form-check-input" type="checkbox" name="instaladas" id="instaladas" checked>
            @else
                <input class="form-check-input" type="checkbox" name="instaladas" id="instaladas">
            @endif
            <label class="form-check-label mx-1" for="instaladas">
                Instaladas
            </label>
            @if ($anuladas)
                <input class="form-check-input" type="checkbox" name="anuladas" id="anuladas" checked>
            @else
                <input class="form-check-input" type="checkbox" name="anuladas" id="anuladas">
            @endif
            <input type="hidden" name="rebusqueda" value="on">
            <label class="form-check-label" for="anuladas">
                Anuladas
            </label>
            <button type="submit" class="btn btn-primary mx-3">Enviar</button>
        </form>
<div class="table-responsive">
                
                <table class="table table-sm table-bordered table-hover">
                    <caption>Pedidos de Alta de Contrato</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Apellido, Nombre </th>
                            <th scope="col"> Dirección </th>
                            <th scope="col"> Plan </th>
                            <th scope="col"> Comentarios </th>
                            <th scope="col"> Status </th>
                            <th scope="col" colspan="2">
                                @can('altas_create')
                                <a href="/agregarAlta" class="margenAbajo btn btn-dark">Nueva</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody id="zona">
                        @foreach ($altas as $alta)
                            <tr>
                                
                            <th scope="row"> {{$alta->relCliente->getNomyApe()}}</th>
                            <td>{{$alta->relDireccion->getResumida()}}</td>
                            <td>{{$alta->relPlan->nombre}}</td>
                            <td>{{$alta->comentarios}}</td>
                            @if ($alta->getStatus(true))
                                <td class="alert alert-success">
                            @else
                                <td class="alert alert-danger">
                            @endif
                                <a href="#" title="Cambiar fecha de instalación" class="btn btn-link" data-toggle="modal" data-target="#altaCambioFecha{{$alta->id}}">
                                    {{$alta->getStatus()}}
                                </a>
                            </td>
                            <td class="conFlex">
                                @can('altas_edit')
                                    <a href="/modificarAlta/{{ $alta->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                        <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                    </a>
                                    <form action="/anularAlta" method="post" class="margenAbajo">
                                    @csrf
                                    @method('patch')
                                        <input type="hidden" name="id" value="{{$alta->id}}">
                                        <button type="submit" class="btn btn-outline-secundary boton-anular"  title="Anular">
                                            <img src="imagenes/103761_comment_cancel_icon.svg" alt="imagen de Cancelar" height="20px">
                                        </button>
                                    </form>
                                    <form action="/programarAlta" method="post" class="margenAbajo">
                                    @csrf
                                    @method('PUT')
                                        <input type="hidden" name="alta_id" value="{{$alta->id}}">
                                        <button type="submit" class="btn btn-outline-secundary"  title="Pasar a Contrato">
                                            <img src="imagenes/contract_contract sign_deal_icon.svg" alt="imagen de firma contracto" height="20px">
                                        </button>
                                    </form>
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
{{ $altas->render() }}
@section('javascript')
    <script>
        let zona = document.getElementById('zona');
        let btnDesactivar = zona.getElementsByClassName('boton-anular');
        for (let i = 0; i < btnDesactivar.length; i++) {
            btnDesactivar[i].addEventListener('click', e => {
                if(!confirm("¿Seguro que anula el Alta de Contrato?")) {
                    e.preventDefault()
                }
            })
        }
    </script>
@endsection
@include('modals.altaCambioFecha')
@endcan
@include('sinPermiso')
@endsection