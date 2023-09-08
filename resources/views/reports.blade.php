@extends('layouts.plantilla')
@section('contenido')
@can('reports')
@php
$mostrarSololectura = true;
@endphp
    <table class="table table-striped">
        <thead>
            <tr>
            <th scope="col">Reporte</th>
            <th scope="col">Detalle</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <th scope="row"> <a href="listadoContratos">Listado Contratos</a></th>
            <td>Listado para reporte mensual. Campos: Genesys ID, APELLIDO Nombre, Plan, Estado, Sistema, Panel, Nodo, Barrio, Comentarios: Si posee velocidad a prueba.</td>
            </tr>
            <tr>
            <th scope="row"> <a href="listadoIssues">Listado Tickets</a></th>
            <td>Historial completo. Campos: Número de Ticket, Fecha de Apertura, Titulo, Creador, Cliente, Estado, Panel, Descripcion del Ticket.</td>
            </tr>
            <tr>
            <th scope="row"> <a href="listadoContratosFull">Listado Completo Full</a></th>
            <td>Listado de clientes activos (no dados de baja). Incluye los campos: ID Contrato, APELLIDO-Nombre, Plan, Estado, Barrio, Panel, Cliente Desde, Equipo, Cantidad de Reclamos.</td>
            </tr>
            <tr>
            <th scope="row"> <a href="listadoContratosNoActivos">Listado Contratos de Baja</a></th>
            <td>Listado de Contratos de baja. Campos: Genesys ID, APELLIDO Nombre, Plan, Estado, Sistema, Panel, Nodo, Barrio, Comentarios: Si posee velocidad a prueba.</td>
            </tr>
        </tbody>
    </table>
@endcan
@include('sinPermiso')
@endsection