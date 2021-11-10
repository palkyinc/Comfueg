@extends('layouts.plantilla')
@section('contenido')
@can('issues_titles_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-6 margin-10" action="" method="GET">
                        <h2 class="mx-2">Administración de títulos de Tikects</h2>
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
                    <caption>Listado de títulos de tickets</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Título </th>
                            <th scope="col" colspan="2">
                                @can('issues_titles_create')
                                <a href="/agregarIssueTitle" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($tipos as $tipo)
                            <tr>
                                
                            <th scope="row"> {{$tipo->id}}</th>
                            <td>{{$tipo->title}}</td>
                            <td>
                                @can('issues_titles_edit')
                                <a href="/modificarIssueTitle/{{ $tipo->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $tipos->links() }}
@endcan
@include('sinPermiso')
@endsection