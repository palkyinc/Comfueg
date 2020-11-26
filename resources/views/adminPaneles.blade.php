@extends('layouts.plantilla')

@section('contenido')
@can('paneles_index')
                    <form class="form-inline mx-4 margin-10" action="" method="GET">
                        <h2 class="mx-3">Administración de Paneles</h2>
                        <label for="ssid" class="mx-3">SSID</label>
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
                    <caption>Listado de Paneles</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> SSID </th>
                            <th scope="col"> Rol </th>
                            <th scope="col"> Equipo </th>
                            <th scope="col"> IP Equipo </th>
                            <th scope="col"> Sitio </th>
                            <th scope="col"> Anterior </th>
                            <th scope="col"> Altura </th>
                            <th scope="col"> Comentario </th>
                            <th scope="col" colspan="2">
                                @can('paneles_create')
                                <a href="/agregarPanel" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($paneles as $panel)
                            @if ($panel->activo)
                                <tr>
                            @else
                                <tr class="table-danger">
                            @endif
                            <th scope="row"> {{$panel->id}}</th>
                            <td>{{$panel->ssid}}</td>
                            <td>{{$panel->rol}}</td>
                            <td>{{$panel->relEquipo->nombre}}</td>
                            <td>{{$panel->relEquipo->ip}}</td>
                            <td>{{$panel->relSite->nombre}}</td>
                            @if ( isset($panel->relPanel->ssid))
                            <td>{{$panel->relPanel->relEquipo->nombre}}</td>
                            @else
                            <td>Gateway</td>
                            @endif
                            <td>{{$panel->altura}}</td>
                            <td>{{$panel->comentario}}</td>
                            <td>
                                @can('paneles_edit')
                                    <a href="/modificarPanel/{{$panel->id}}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                    <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                    </a>
                                    @if ($panel->activo)
                                            <a href="/panelActivar/{{$panel->id}}" class="margenAbajo btn btn-outline-secundary" title="Desactivar">
                                                <img src="imagenes/iconfinder_Turn_On__Off_2134663.svg" alt="imagen de Desactivar" height="20px">
                                            </a>
                                    @else 
                                        <a href="/panelActivar/{{$panel->id}}" class="margenAbajo btn btn-outline-secundary" title="Activar">
                                            <img src="imagenes/iconfinder_Multimedia_Turn_on_off_power_button_interface_3841792.svg" alt="imagen de activar" height="20px">
                                        </a>
                                    @endif    
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $paneles->links() }}
@endcan    
@endsection

@section('javascript')
<script>
    let btnActivar = document.getElementsByClassName('btn-danger');
    for (let i = 0; i < btnActivar.length; i++) {
        btnActivar[i].addEventListener('click', e => {
            if(!confirm("¿Seguro de ACTIVAR?")) {
                e.preventDefault()
            }
            
        })
    }
    let btnDesactivar = document.getElementsByClassName('btn-success');
    for (let i = 0; i < btnDesactivar.length; i++) {
        btnDesactivar[i].addEventListener('click', e => {
            if(!confirm("¿Seguro de DESACTIVAR?")) {
                e.preventDefault()
            }
        })
    }
        
    
</script>
@stop