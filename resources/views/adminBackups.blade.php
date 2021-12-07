@extends('layouts.plantilla')

@section('contenido')
@can('backups_index')
@php
$mostrarSololectura = true;
@endphp
<div class="container">
    @if ( session('mensaje') )
        <div class="alert alert-success">
            @foreach (session('mensaje') as $item)
                {{ $item }} <br>
            @endforeach
        </div>
    @endif
    <div class="row justify-content-center">
        <h2>Listado de Backups almacenados local.</h2>
        <div class="col-md-12">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="text-center">Archivo</th>
                    <th scope="col" class="text-center">Tamaño</th>
                    <th scope="col" class="text-center">
                        @can('backups_create')
                            <a href="#" class="btn btn-dark" data-toggle="modal" data-target="#staticBackdropSyncCloud" title="Sincroniza Cloud contra Server">Sync desde Cloud</a>
                        @endcan
                    </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($archivos as $key => $archivo)
                        <tr>
                            <th class="text-center" scope="row">{{$key}}</th>
                            <td class="text-center">{{$archivo['name']}}</td>
                            <td class="text-right">{{$archivo['size']}} bytes</td>
                            <td class="text-center">
                                @can('backups_create')
                                    <a href="restoreFile/{{$key}}" class="btn btn-warning" title="Restaura Backup de zip al servidor">Restaurar</a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endcan
@include('sinPermiso')
@endsection
@include('modals.backups')