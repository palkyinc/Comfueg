@extends('layouts.plantilla')
@section('contenido')
@can('antenas_index')
@php
$mostrarSololectura = true;
@endphp
<div id="vista">
                    <div class="mx-6 margin-10">
                        <h2 class="mx-2">Status de Paneles</h2>
                    </div>
                    <h4 v-if="flagBuscando === 1"><a type="button" class="btn btn-success" v-on:click="checkPanel">Nuevo Scan</a></h4>
                    <h4 v-else-if="flagBuscando < 1" class="alert alert-warning" role="alert"> Buscando </h4>
                    <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="tablaRender">
                        <thead>
                            <tr>
                                <th>Hostname</th>
                                <th>Site</th>
                                <th>Status</th>
                                <th>CCQ</th>
                                <th>Uptime</th>
                                <th>ChaWidth</th>
                                <th>Clients</th>
                                <th>CpuUse</th>
                                <th>DevModel</th>
                                <th>Firmware</th>
                                <th>Frecuency</th>
                                <th>LanSpeed</th>
                                <th>MemFree</th>
                                <th>RX</th>
                                <th>TX</th>
                                <th>Signal</th>
                                <th>Temperature</th>
                            </tr>
                        </thead>
                        <tr v-for="dataPanel in dataPanels">
                            <td v-if="dataPanel.status"> <a v-bind:href="'https://' + dataPanel.ip " target="_blank" v-bind:title="'Ir a Equipo' + dataPanel.ip">@{{dataPanel.Hostname}}</a></td>
                            <td v-else><a v-bind:href="'https://' + dataPanel.ip " target="_blank"  v-bind:title="'Ir a Equipo' + dataPanel.ip">@{{dataPanel.HostnameDb}}</a></td>
                            
                            <td>@{{dataPanel.sitio}}</td>

                            <td v-if="dataPanel.status" class="alert alert-success" role="alert">Up</td>
                            <td v-else class="alert alert-danger" role="alert">Down</td>
                            
                            <td v-if="dataPanel.statusCCQ === 1" class="alert alert-success" role="alert">@{{dataPanel.CCQ}}</td>
                            <td v-else-if="dataPanel.statusCCQ === 0" class="alert alert-danger" role="alert">@{{dataPanel.CCQ}}</td>
                            <td v-else-if="dataPanel.statusCCQ === 2" class="alert alert-warning" role="alert">@{{dataPanel.CCQ}}</td>
                            <td v-else-if="dataPanel.statusCCQ === 3">N/A</td>
                            
                            <td>@{{dataPanel.Uptime}}</td>
                            <td>@{{dataPanel.ChannelWidth}}</td>

                            <td v-if="dataPanel.statusClients === 1" class="alert alert-success" role="alert">@{{dataPanel.Clients}}</td>
                            <td v-else-if="dataPanel.statusClients === 0" class="alert alert-danger" role="alert">@{{dataPanel.Clients}}</td>
                            <td v-else-if="dataPanel.statusClients === 2" class="alert alert-warning" role="alert">@{{dataPanel.Clients}}</td>
                            <td v-else-if="dataPanel.statusClients === 3">N/A</td>

                            <td v-if="dataPanel.statusCpuUse === 1" class="alert alert-success" role="alert">@{{dataPanel.CpuUse}}</td>
                            <td v-else-if="dataPanel.statusCpuUse === 0" class="alert alert-danger" role="alert">@{{dataPanel.CpuUse}}</td>
                            <td v-else-if="dataPanel.statusCpuUse === 2" class="alert alert-warning" role="alert">@{{dataPanel.CpuUse}}</td>
                            <td v-else-if="dataPanel.statusCpuUse === 3">@{{dataPanel.CpuUse}}</td>

                            <td>@{{dataPanel.DevModel}}</td>
                            <td>@{{dataPanel.Firmware}}</td>
                            <td>@{{dataPanel.Frecuency}}</td>

                            <td v-if="dataPanel.statusLan === 1" class="alert alert-success" role="alert">@{{dataPanel.LanSpeed}}</td>
                            <td v-else-if="dataPanel.statusLan === 0" class="alert alert-danger" role="alert">@{{dataPanel.LanSpeed}}</td>
                            <td v-else-if="dataPanel.statusLan === 2" class="alert alert-warning" role="alert">@{{dataPanel.LanSpeed}}</td>
                            <td v-else-if="dataPanel.statusLan === 3">@{{dataPanel.LanSpeed}}</td>

                            <td v-if="dataPanel.statusMemFree === 1" class="alert alert-success" role="alert">@{{dataPanel.MemFree}}</td>
                            <td v-else-if="dataPanel.statusMemFree === 0" class="alert alert-danger" role="alert">@{{dataPanel.MemFree}}</td>
                            <td v-else-if="dataPanel.statusMemFree === 2" class="alert alert-warning" role="alert">@{{dataPanel.MemFree}}</td>
                            <td v-else-if="dataPanel.statusMemFree === 3">@{{dataPanel.MemFree}}</td>
                            
                            <td v-if="dataPanel.statusRX === 1" class="alert alert-success" role="alert">@{{dataPanel.RX}}</td>
                            <td v-else-if="dataPanel.statusRX === 0" class="alert alert-danger" role="alert">@{{dataPanel.RX}}</td>
                            <td v-else-if="dataPanel.statusRX === 2" class="alert alert-warning" role="alert">@{{dataPanel.RX}}</td>
                            <td v-else-if="dataPanel.statusRX === 3">@{{dataPanel.RX}}</td>
                            
                            <td v-if="dataPanel.statusTX === 1" class="alert alert-success" role="alert">@{{dataPanel.TX}}</td>
                            <td v-else-if="dataPanel.statusTX === 0" class="alert alert-danger" role="alert">@{{dataPanel.TX}}</td>
                            <td v-else-if="dataPanel.statusTX === 2" class="alert alert-warning" role="alert">@{{dataPanel.TX}}</td>
                            <td v-else-if="dataPanel.statusTX === 3">@{{dataPanel.TX}}</td>
                            
                            <td v-if="dataPanel.statusSignal === 1" class="alert alert-success" role="alert">@{{dataPanel.Signal}}</td>
                            <td v-else-if="dataPanel.statusSignal === 0" class="alert alert-danger" role="alert">@{{dataPanel.Signal}}</td>
                            <td v-else-if="dataPanel.statusSignal === 2" class="alert alert-warning" role="alert">@{{dataPanel.Signal}}</td>
                            <td v-else-if="dataPanel.statusSignal === 3">@{{dataPanel.Signal}}</td>
                            
                            <td>@{{dataPanel.Temperature}}</td>

                        </tr>
                    </table>
                    </div>
</div>
                    @section('javascript')
                        <script src="vue.js/vendor/js/vue.js"></script>
                        <script src="vue.js/resources/js/adminControlPanelNodos.js"></script>
                    @endsection
@endcan
@include('sinPermiso')
@endsection