@extends('layouts.plantilla')
@section('contenido')
@can('SiteHasIncidente_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-4 margin-10" action="adminDeudasRebusqueda" method="get">
                        <div class="conteiner">
                            <div class="row">
                                <div class="col-6">
                                    <h2 class="mx-3">Deudas Técnica</h2>
                                </div>
                                <div class="form-check form-switch col-2">
                                    @if ($abiertas)
                                        <input class="form-check-input" type="checkbox" name="abiertas" id="flexSwitchCheckChecked" checked>
                                    @else
                                        <input class="form-check-input" type="checkbox" name="abiertas" id="flexSwitchCheckChecked">
                                    @endif
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Solo Abiertas</label>
                                </div>
                                <div class="col-4">
                                    <label for="sitio" class="mx-3">Sitio</label>
                                    <select class="form-control" name="sitio" id="sitio">
                                        @if (!$sitioSelected)
                                            <option value="" selected>Seleccione un Sitio...</option>
                                        @else
                                            <option value="">Seleccione un Sitio...</option>
                                        @endif
                                        @foreach ($sitios as $sitio)
                                            @if ($sitio->id == $sitioSelected)
                                                <option value="{{$sitio->id}}" selected>{{$sitio->nombre}}</option>
                                            @else
                                                <option value="{{$sitio->id}}">{{$sitio->nombre}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn btn-primary mx-3">Enviar</button>
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
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Tipo </th>
                            <th scope="col"> Inicio </th>
                            <th scope="col"> Finalizada </th>
                            <th scope="col"> Tiempo Caída</th>
                            <th scope="col"> Afectado </th>
                            <th scope="col"> Sitio </th>
                            <th scope="col"> Creado por </th>
                            <th scope="col" colspan="2">
                                @can('SiteHasIncidente_create')
                                <a href="/agregarSiteHasIncidente" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($incidentes as $incidente)
                            @if (!$incidente->final)
                                <tr class="table-danger">
                            @else
                                <tr class="table-success">
                            @endif
                            <th scope="row"> {{$incidente->crearNombre()}}</th>
                            <td>{{$incidente->tipo}}</td>
                            <td>{{$incidente->inicio}}</td>
                            <td>
                                @if((!$incidente->final))
                                    {{'Aún Ocurriendo'}}
                                @else
                                    {{$incidente->final}}
                                @endif
                            </td>
                            <td>{{$incidente->tiempoCaida()}}</td>
                            <td>{{$incidente->relPanel->relEquipo->nombre}}</td>
                            <td>{{$incidente->relPanel->relSite->nombre}}</td>
                            <td>{{$incidente->relUser->name}}</td>
                            <td>
                                @can('SiteHasIncidente_edit')
                                    @if (!$incidente->final)
                                        <a href="/modificarSiteHasIncidente/{{$incidente->id}}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                            <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                        </a>
                                        <a href="/adminArchivosIncidente/{{$incidente->id}}" class="margenAbajo btn btn-outline-secundary" title="Editar archivoss">
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
