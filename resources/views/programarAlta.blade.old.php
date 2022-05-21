@extends('layouts.plantilla')
@section('contenido')
<h3>Pre-instalación</h3>
@if( $errores )
    <div class="alert alert-danger col-md-7 mx-auto">
        <ul>
            @foreach( $errores->messages() as $key => $items )
                @foreach ($items as $error)
                    <li>{{ $key . ': ' . $error }}</li>
                @endforeach
            @endforeach
        </ul>
    </div>
@endif

{{-- Paso 0: Datos del Cliente --}}
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4>Programar Alta en Contrato</h4></div>

                <div class="card-body">
                    <p>Cliente: {{$alta->relCliente->getNomyApe()}}</p>
                    <p>Direccion: {{$alta->relDireccion->getResumida()}}</p>
                    <p>Plan: {{$alta->relPlan->getResumida()}}</p>
                    <p class="{{null != $tipo_contrato ? '' : 'ocultar'}}">Tipo de Contrato: {{$tipo_contrato == 1 ? 'Standard' : ($tipo_contrato == 2 ? 'Bridge' : ($tipo_contrato == 3 ? 'Solo Router' : 'Error'))}}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Paso 1: ingresar Tipo de contrato --}}
<div class="container my-4 {{ $paso == 1 ? '' : 'ocultar'}}">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4>Seleccionar tipo de contrato</h4></div>
                    <form action="/programarAlta" method="post" class="maegenAbajo">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <div class="form-group col-md-6">
                            <label for="num_panel">Panel: </label>
                            <select class="form-control" name="tipo_contrato">
                                <option value="1">Standard</option>
                                <option value="2">Bridge</option>
                                <option value="3">Solo Router</option>
                            </select>
                        </div>
                        <div class="card-footer">
                            <input type="hidden" name="alta_id" value="{{$alta->id}}">
                            <input type="hidden" name="paso" value="{{$paso}}">
                            <button type="submit" class="btn btn-info"  title="Seleccionar tipo de contrato">Seleccionar</button>
                            <a href="/adminAltas" class="margenAbajo btn btn-dark mx-1" title="Volver a Altas">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Paso 2: Ingresar Mac ubiquiti --}}
<div class="container my-4 {{($paso == 2) ? '' : 'ocultar'}}">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4>{{$titulo_paso2}}</h4></div>

                <form action="/programarAlta" method="post" class="margenAbajo">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <input type="hidden" name="alta_id" value="{{$alta->id}}">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="text" name="mac_address" 
                                placeholder="{{(false === $macaddress) ? 'ERROR en el Mac Ingresado' : ''}}"
                                maxlength="17" class="form-control" id="mac_address">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <input type="hidden" name="alta_id" value="{{$alta->id}}">
                        <input type="hidden" name="paso" value="{{$paso}}">
                        <input type="hidden" name="tipo_contrato" value="{{$tipo_contrato}}">
                        <button type="submit" class="btn btn-dark"  title="Consultar">
                            Consultar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Paso 3: Error Mac Address Usado --}}
<div class="container my-4 {{($contrato || $panel) ? '' : 'ocultar'}}">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-danger">
                El MacAddress ingresado esta siendo usado en el  
                    {{$contrato ? ('contrato de ' . $contrato->relCliente->getNomyApe()) : ''}}
                    {{$panel ? ('Panel: ' . $panel->relEquipo->nombre) : ''}}
            </div>
            <div class="conFlex">
                <a href="/adminAltas" class="margenAbajo btn btn-dark mx-1" title="Volver a Altas">
                    Volver
                </a>
                <form action="/programarAlta" method="post" class="margenAbajo mx-1">
                @csrf
                @method('PUT')
                    <input type="hidden" name="alta_id" value="{{$alta->id}}">
                    <input type="hidden" name="paso" value="2">
                    <input type="hidden" name="tipo_contrato" value="{{$tipo_contrato}}">
                    <button type="submit" class="btn btn-info"  title="Ingresar otro MacAddress">
                        Otro Mac
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Equipo cargado y disponible --}}
<div class="container my-4 {{($equipo && $paso == 6) ? '' : 'ocultar'}}">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4>Equipo Disponible</h4></div>

                <div class="card-body">
                    <p>Nombre: {{$equipo->nombre ?? ''}}</p>
                    <p>MacAddress: {{$equipo->mac_address ?? ''}}</p>
                    <p>Dispositivo: {{$equipo ? $equipo->relProducto->getResumida() : ''}}</p>
                    <p>IP: {{$equipo->ip ?? ''}}</p>
                    <p>Alta: {{$equipo->fecha_alta ?? ''}}</p>
                    <p>Baja: {{$equipo->fecha_baja ?? ''}}</p>
                    <p>comentarios: {{$equipo->comentarios ?? ''}}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Paso 5: Datos Ubiquiti --}}
<div class="container my-4 {{($paso == 5) ? '' : 'ocultar'}}">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4>Equipo no registrado. Ingrese datos:</h4></div>
                <form action="/programarAlta" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="nombre">Nombre: </label>
                                <input type="text" name="nombre" value="{{$alta->relCliente->getNomyApe()}}" maxlength="45" class="form-control" id="nombre" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="mac_address">Mac Address: </label>
                                <input type="text" name="mac_address" value="{{$macaddress ?? ''}}" maxlength="17" class="form-control" id="mac_address" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="num_dispositivo">Artículo: </label>
                                <select class="form-control" name="num_dispositivo" id="num_dispositivo">
                                    <option value="">Seleccione un Dispositivo...</option>
                                    @foreach ($dispositivos as $dispositivo)
                                        @if ($dispositivo['id'] != old('num_dispositivo'))
                                            <option value="{{$dispositivo['id']}}">{{$dispositivo['modelo']}}</option>
                                        @else
                                            <option value="{{$dispositivo['id']}}" selected>{{$dispositivo['modelo']}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="num_antena">Antena: </label>
                                <select class="form-control" name="num_antena" id="num_antena">
                                    <option value="">Seleccione una Antena...</option>
                                    @foreach ($antenas as $antena)
                                        @if ($antena['id'] != old('num_antena'))
                                            <option value="{{$antena['id']}}">{{$antena['descripcion']}}</option>
                                        @else
                                            <option value="{{$antena['id']}}"selected>{{$antena['descripcion']}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="ip">Ip: (Vacio para automático)</label>
                                <input type="text" name="ip" value="{{old('ip')}}" maxlength="15" class="form-control" id="ip">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="comentario">Comentario del equipo: </label>
                                <textarea name="comentario" class=" form-control" id="comentario" rows="auto" cols="100">{{old('comentario')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <input type="hidden" name="paso" value="5">
                        <input type="hidden" name="alta_id" value="{{$alta->id}}">
                        <input type="hidden" name="tipo_contrato" value="{{$tipo_contrato}}">
                        <button type="submit" class="btn btn-primary" id="enviar">Nuevo Equipo</button>
                        <a href="/adminAltas" class="btn btn-dark">volver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Paso 6: Seleccionar Panel --}}
<div class="container my-4 {{($paso == 6) ? '' : 'ocultar'}}">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4>Seleccionar Panel</h4></div>
                    <form action="/programarAlta" method="post" class="maegenAbajo">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <div class="form-group col-md-6">
                            <label for="num_panel">Panel: </label>
                            <select class="form-control" name="num_panel">
                                <option value="null">Seleccione Panel a Asociarse...</option>
                                @foreach ($paneles as $panel)
                                    <option value="{{$panel->id}}">{{$panel->getResumida()}}</option>
                                @endforeach 
                            </select>
                        </div>
                        <div class="card-footer">
                            <input type="hidden" name="paso" value="6">
                            <input type="hidden" name="tipo_contrato" value="{{$tipo_contrato}}">
                            <input type="hidden" name="alta_id" value="{{$alta->id}}">
                            <input type="hidden" name="equipo_id" value="{{$equipo->id ?? ''}}">
                            <button type="submit" class="btn btn-info"  title="Crear Contrato" id="boton-guardar">Crear</button>
                            <a href="/adminAltas" class="margenAbajo btn btn-dark mx-1" title="Volver a Altas">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
    <script>
        let btn = document.getElementById('boton-guardar');
        btn.addEventListener('click', e => {
            if(!confirm("¿Confirma crear contrato?")) {
                e.preventDefault()
            }
        });
    </script>
@endsection