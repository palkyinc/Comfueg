@extends('layouts.plantilla')
@section('contenido')
@can('issues_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-4 margin-10" action="adminIssuesRebusqueda" method="get">
                        <div class="conteiner">
                            <div class="row">
                                <div class="col-6">
                                    <h2 class="mx-3">Tickets / Pedidos de asistencia técnica de Clientes</h2>
                                </div>
                                <div class="form-check form-switch col-2">
                                    @if ($abiertas)
                                        <input class="form-check-input" type="checkbox" name="abiertas" id="flexSwitchCheckChecked" checked>
                                    @else
                                        <input class="form-check-input" type="checkbox" name="abiertas" id="flexSwitchCheckChecked">
                                    @endif
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Solo Abiertas</label>
                                </div>
                                <div class="col-3">
                                    <select class="form-control" name="sitio" id="sitio">
                                        @if (!$userSelected)
                                            <option value="" selected>Todos los Usuarios</option>
                                        @else
                                            <option value="">Todos los Usuarios</option>
                                        @endif
                                        @foreach ($usuarios as $usuario)
                                            @if ($usuario->id == $userSelected)
                                                <option value="{{$usuario->id}}" selected>{{$usuario->name}}</option>
                                            @else
                                                <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-1">
                                    <button type="submit" class="btn btn-primary mx-3">Buscar</button>
                                </div>
                            </div>
                        </div>
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
                    <caption>Listado de Incidentes</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> ID </th>
                            <th scope="col"> Título </th>
                            <th scope="col"> Asignada a: </th>
                            <th scope="col"> Cliente </th>
                            <th scope="col"> Vence en:</th>
                            <th scope="col"> N° Contrato </th>
                            <th scope="col"> Creado por: </th>
                            <th scope="col" colspan="2">
                                @can('issues_create')
                                <a href="/agregarIssue" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($incidentes as $incidente)
                            @if (!$incidente->closed)
                                <tr class="table-danger">
                            @else
                                <tr class="table-success">
                            @endif
                            <th scope="row"> {{$incidente->id}}</th>
                            <td>{{$incidente->relTitle->title}}</td>
                            <td>{{$incidente->relAsignado->name}}</td>
                            <td>{{$incidente->relCliente->getNomYApe()}}</td>
                            <td>{{$incidente->getVencida()}}</td>
                            <td>{{$incidente->relContrato->id}}</td>
                            <td>{{$incidente->relCreator->name}}</td>
                            <td>
                                @can('issues_edit')
                                    @if (!$incidente->closed)
                                        <a href="/modificarIssue/{{$incidente->id}}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                            <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                        </a>
                                        <a href="/adminArchivosIssue/{{$incidente->id}}" class="margenAbajo btn btn-outline-secundary" title="Editar archivoss">
                                            <img src="imagenes/iconfinder_document-text-file-sheet-doc_2931167.svg" alt="imagen editar archivo" height="20px">
                                        </a>
                                    @endif
                                @endcan
                                <a href="#" class="margenAbajo btn btn-outline-secundary" data-toggle="modal" data-target="#staticBackdrop{{$incidente->id}}" title="Ver">
                                        <img src="imagenes/iconfinder_VIEW_eye_2738306.svg" alt="imagen de ojo para ver" height="20px">
                                </a>
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $incidentes->links() }}
@include('modals.incidentes')
@endcan
@include('sinPermiso')
@endsection
