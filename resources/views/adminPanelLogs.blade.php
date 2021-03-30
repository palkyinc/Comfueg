@extends('layouts.plantilla')
@section('contenido')
@can('logsPanel_index')
@php
$mostrarSololectura = true;
@endphp
<div id="vista">
                    <div class="mx-4 margin-10">
                        <form class="form-inline mx-6 margin-10" action="" method="GET">
                            <h2 class="mx-2">Log de Panel: </h2>
                            <select class="form-control" name="panel_ip" id="panel_ip">
                                <option value="">Seleccionar un Panel...</option>
                                @foreach ($paneles as $panel)
                                    @if ($panel->relEquipo->ip == $actual)
                                       <option value="{{$panel->relEquipo->ip}}" selected>{{$panel->relEquipo->ip}} | {{$panel->relEquipo->nombre}}</option>
                                    @else
                                       <option value="{{$panel->relEquipo->ip}}">{{$panel->relEquipo->ip}} | {{$panel->relEquipo->nombre}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary mx-3">Buscar</button>
                        </form>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="tablaRender">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Hostname</th>
                                <th>Ip</th>
                                <th>Status</th>
                                <th>CCQ</th>
                                <th>Clients</th>
                                <th>CpuUse</th>
                                <th>MemFree</th>
                                <th>DevModel</th>
                                <th>Firmware</th>
                                <th>Frecuency</th>
                                <th>LanSpeed</th>
                                <th>RX</th>
                                <th>TX</th>
                                <th>Signal</th>
                            </tr>
                        </thead>
                        @foreach ($logs as $elemento)
                        <tr>
                            <td>{{$elemento->created_at}}</td>
                            <td>{{$elemento->mac_address}}</td>
                            <td>{{$elemento->ip_equipo}}</td>
                            @if ($elemento->contactado)
                                <td>Contactado</td>
                            @else
                                <td>NO Contactado</td>
                            @endif
                            <td>{{$elemento->ccq}}</td>
                            <td>{{$elemento->clientes_conec}}</td>
                            <td>{{$elemento->uso_cpu}}</td>
                            <td>{{$elemento->mem_libre}}</td>
                            <td>{{$elemento->dispositivo}}</td>
                            <td>{{$elemento->firmware}}</td>
                            <td>{{$elemento->canal}}</td>
                            <td>{{$elemento->lan_velocidad}}</td>
                            <td>{{$elemento->rx}}</td>
                            <td>{{$elemento->tx}}</td>
                            <td>{{$elemento->senial}}</td>
                        </tr>
                        @endforeach
                    </table>
                    </div>
</div>
                    
@endcan
@include('sinPermiso')
@endsection