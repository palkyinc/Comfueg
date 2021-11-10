@extends('layouts.plantilla')
@section('contenido')
@can('planes_index')
@php
$mostrarSololectura = true;
@endphp
                    <form class="form-inline mx-4 margin-10" action="" method="GET">
                        <h2 class="mx-3">Administración de planes</h2>
                        <label for="nombre" class="mx-3">Nombre</label>
                        <input type="text" name="nombre" class="form-control mx-3" id="nombre">
                        <button type="submit" class="btn btn-primary mx-3">Enviar</button>
                    </form>

        @if ( session('mensaje') )
            <div class="alert alert-success">
                @foreach (session('mensaje') as $item)
                    {{ $item }} <br>
                @endforeach
            </div>
        @endif
        @if ($warning)
            <div class="alert alert-warning">
                @foreach ($warning as $item)
                    {{ $item }} <br>
                @endforeach
            </div>
        @endif
        
<div class="table-responsive">
                
                <table class="table table-sm table-bordered table-hover">
                    <caption>Listado de planes</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Bajada (Kb) </th>
                            <th scope="col"> Subida (Kb)</th>
                            <th scope="col"> Descripción </th>
                            <th scope="col"> Gateway </th>
                            <th scope="col"> Contratos </th>
                            <th scope="col" colspan="2">
                                @can('planes_create')
                                <a href="/agregarPlan" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody id="deleteFile">
                        @foreach ($planes as $plan)
                            
                            @if ($plan->reconcile)
                                <tr class="alert alert-warning">
                            @else
                                <tr>
                            @endif
                                
                                <th  scope="row">{{$plan->nombre}}</th>
                                <td>{{$plan->bajada}}</td>
                                <td>{{$plan->subida}}</td>
                                <td>{{$plan->descripcion}}</td>
                                @if (isset($plan->gateway_id))
                                    <td>{{$plan->relPanel->relEquipo->nombre}}</td>
                                @else
                                    <td>No asignado</td>    
                                @endif
                                <td>{{$plan->getContractCount()}}</td>
                                <td> 
                                    @can('planes_edit')
                                        <a href="/modificarPlan/{{$plan->id}}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                            <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                        </a>
                                    @endcan
                                </td>
                                <td>
                                    @can('planes_create')
                                        @if ($plan->gateway_id == null)
                                            <form method="post" action="/eliminarPlan/{{ $plan->id }}">
                                            @csrf
                                                @method('delete')
                                                <button type="submit">
                                                    <img src="/imagenes/iconfinder_basket_1814090.svg" alt="imagen de basket borrar" height="20px" title="Borrar Plan">
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $planes->links() }}

    @can('planes_create')
        @section('javascript')
            <script>
            const td = document.querySelector('#deleteFile');
            let botones = td.getElementsByTagName('button');
            for( let i=0; i< botones.length; i++) {
                    botones[i].addEventListener('click', e => 
                    {
                        if (!confirm("¿Estás seguro de borrar este Plan?")) {
                        e.preventDefault();
                        }
                    });
            }
            </script>
        @endsection
    @endcan
@endcan    
@include('sinPermiso')
@endsection