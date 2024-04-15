@extends('layouts.plantilla')
@section('contenido')
@can('issues_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-2 margin-10" action="adminIssues" method="get">
                        <div class="conteiner">
                            <div class="row">
                                <div class="col-6">
                                    <h2 class="mx-3">Pedidos de asistencias Técnicas</h2>
                                </div>
                                <div class="form-check form-switch col-2">
                                    @if ($abiertas == 'on')
                                        <input class="form-check-input" type="checkbox" name="abiertas" id="flexSwitchCheckChecked" checked>
                                    @else
                                        <input class="form-check-input" type="checkbox" name="abiertas" id="flexSwitchCheckChecked">
                                    @endif
                                    <input type="hidden" name="rebusqueda" value="on">
                                    <label class="form-check-label" for="flexSwitchCheckChecked">
                                        Solo Abiertas
                                    </label>
                                </div>
                                <div class="col-3">
                                    <select class="form-control" name="usuario" id="usuario">
                                        @if (!$userSelected)
                                            <option value="todos" selected>Todos los Usuarios</option>
                                        @else
                                            <option value="todos">Todos los Usuarios</option>
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
                                <!-- <div class="col-2">
                                    
                                </div> -->
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
                            <th scope="col"> Estado:</th>
                            <th scope="col"> N° Contrato </th>
                            <th scope="col"> Creado por: </th>
                            <th scope="col"> Novedades</th>
                            <th scope="col" colspan="2">
                                @can('issues_create')
                                @if ($contrato)
                                    <a href="/agregarIssue?contrato_id={{$contrato}}" class="btn btn-dark">Agregar</a>
                                    
                                @endif
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($incidentes as $incidente)
                            @if ($incidente->closed)
                                <tr class="table-info">
                            @else
                                <tr>
                            @endif
                            <th scope="row"> {{$incidente->id}}</th>
                            <td>{{$incidente->relTitle->title}}</td>
                            <td>{{$incidente->relAsignado->name}}</td>
                            <td>{{$incidente->relCliente->getNomYApe()}}</td>
                                    @if ($incidente->getVencida(true) === false)
                                        <td class="alert alert-success">
                                    @elseif ($incidente->getVencida(true) === true)
                                        <td class="alert alert-danger">
                                    @elseif ($incidente->getVencida(true) === null)
                                        <td>
                                    @endif
                                {{$incidente->getVencida()}}</td>
                            <td>
                                @if ($incidente->contrato_id != null)
                                    {{$incidente->relContrato->id}}
                                @else
                                    Sin Contrato.    
                                @endif
                            </td>
                            <td>{{$incidente->relCreator->name}}</td>
                            <td>{{ $incidente->cant_updates() }}</td>
                            <td>
                                @can('issues_edit')
                                    @if (!$incidente->closed)
                                        <a href="/modificarIssue/{{$incidente->id}}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                            <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                        </a>
                                        {{-- <a href="/adminArchivosIssue/{{$incidente->id}}" class="margenAbajo btn btn-outline-secundary" title="Editar archivoss">
                                            <img src="imagenes/iconfinder_document-text-file-sheet-doc_2931167.svg" alt="imagen editar archivo" height="20px">
                                        </a> --}}
                                    @else
                                        <a href="/modificarIssue/{{$incidente->id}}" class="margenAbajo btn btn-outline-secundary" title="Ver">
                                            <img src="imagenes/iconfinder_VIEW_eye_2738306.svg" alt="imagen de ojo para ver" height="20px">
                                        </a>
                                    @endif
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-2">
                    @if ($contrato)
                        <a href="/adminContratos?contrato={{$contrato}}" class="btn btn-primary m-1">Volver Abono</a>
                    @endif
                </div>
</div>
        {{ $incidentes->appends(['usuario' => $userSelected, 'abiertas' => $abiertas, 'cliente' => $cliente])->links() }}
@include('modals.incidentes')
@endcan
@include('sinPermiso')
@endsection
