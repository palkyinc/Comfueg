@extends('layouts.plantilla')
@section('contenido')
@can('configPanels_index')
@php
$mostrarSololectura = true;
@endphp
<h2 class="mx-2">Back up de las configuraciones de paneles y PTP</h2>
        
<div class="table-responsive text-center">
                
                <table class="table table-sm table-bordered table-hover">
                    <caption>Listado de backups</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Archivo </th>
                            <th scope="col"> Acciones </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($files as $file)
                            <tr>
                                
                            <th scope="row"> {{$file->getFilename()}}</th>
                            <td>
                                @can('configPanels_edit')
                                <form method="GET" action="{{ route('downloadConfigPanel', ['filename' => $file->getFilename()]) }}">
                                    <button type="submit" class="btn btn-info">Download File</button>
                                </form>
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
@endcan
@include('sinPermiso')
@endsection