@extends('layouts.plantilla')

@section('contenido')
@can('SiteHasIncidente_index')
                    <form class="form-inline mx-4 margin-10" action="" method="GET">
                        <h2 class="mx-3">Administración de Incidentes Globales</h2>
                        <label for="ssid" class="mx-3">Revisar</label>
                        <input type="text" name="ssid" class="form-control mx-3" id="ssid">
                        <label for="sitio" class="mx-3">Sitio</label>
                        <select class="form-control" name="sitio" id="sitio">
                            <option value="">Seleccione un Sitio...</option>
                            @foreach ($sitios as $sitio)
                                    <option value="{{$sitio->id}}">{{$sitio->nombre}}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary mx-3">Enviar</button>
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
                            <th scope="col"> Tiempo Caída / Final </th>
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
                                    {{$incidente->tiempoCaida()}}
                                @else
                                    {{$incidente->final}}
                                @endif
                            </td>
                            <td>{{$incidente->relPanel->relEquipo->nombre}}</td>
                            <td>{{$incidente->relPanel->relSite->nombre}}</td>
                            <td>{{$incidente->relUser->name}}</td>
                            <td>
                                @can('SiteHasIncidente_edit')
                                    <a href="/modificarSiteHasIncidente/{{$incidente->id}}" class="margenAbajo btn btn-outline-secundary" title="Ver/Editar">
                                        <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                    </a>
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $incidentes->links() }}
@endcan
@endsection
