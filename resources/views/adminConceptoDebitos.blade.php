@extends('layouts.plantilla')
@section('contenido')
@can('conceptoDebitos_index')
    @php
        $mostrarSololectura = true;
    @endphp
    @if ( session('mensaje') )
        @if (isset(session('mensaje')['success']))
            <div class="alert alert-success">
                @foreach (session('mensaje')['success'] as $item)
                    
                    {{ $item }} <br>
                @endforeach
            </div>
        @endif
        @if (isset(session('mensaje')['error']))
            <div class="alert alert-danger">
                @foreach (session('mensaje')['error'] as $item)
                    {{ $item }} <br>
                @endforeach
            </div>
        @endif
    @endif
    <div class="table-responsive">
        <table class="table table-sm table-bordered table-hover">
            <caption>Listado de Conceptos de Débitos</caption>
            <thead class="thead-light">
                <tr>
                    <th scope="col" class="text-center"> ID </th>
                    <th scope="col" class="text-center"> Concepto </th>
                    <th scope="col" class="text-center"> 
                        @can('conceptoDebitos_create')
                            <a href="/agregarConceptoDebito" class="btn btn-dark">Nuevo</a>
                        @endcan
                    </th>

                </tr>
            </thead>

            <tbody id="zona">
                @foreach ($conceptos as $concepto)
                    @if ($concepto->desactivado)
                        <tr class="alert alert-danger" role="alert">    
                    @else
                        <tr>
                    @endif
                        <td class="text-center">{{$concepto->id}}</td>
                        <td class="text-center">{{$concepto->concepto}}</td>
                        <td class="conFlex">
                            @can('conceptoDebitos_edit')
                                <a href="/modificarConceptoDebitos/{{ $concepto->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                                @if ($concepto->desactivado)
                                    <form action="/habilitarConceptoDebitos" method="post" class="margenAbajo">
                                        @csrf
                                        @method('patch')
                                            <input type="hidden" name="id" value="{{$concepto->id}}">
                                            <button class="btn btn-outline-secundary boton-Alta"  title="Habilitar">
                                                <img src="imagenes/iconfinder_Multimedia_Turn_on_off_power_button_interface_3841792.svg" alt="imagen de activar" height="20px">
                                            </button>
                                    </form>
                                @else
                                    <form action="/deshabilitarConceptoDebitos" method="post" class="margenAbajo">
                                        @csrf
                                        @method('delete')
                                            <input type="hidden" name="id" value="{{$concepto->id}}">
                                            <button class="btn btn-outline-secundary boton-Baja"  title="Deshabilitar">
                                                <img src="imagenes/iconfinder_Turn_On__Off_2134663.svg" alt="imagen de Desactivar" height="20px">
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
    {{ $conceptos->links() }}
<script>
    let zona = document.getElementById('zona');
    let btnActivar = zona.getElementsByClassName('boton-Alta');
    for (let i = 0; i < btnActivar.length; i++) {
        btnActivar[i].addEventListener('click', e => {
            if(!confirm("¿Seguro de Habilitar?")) {
                e.preventDefault()
            }
            
        })
    }
    let btnDesactivar = zona.getElementsByClassName('boton-Baja');
    for (let i = 0; i < btnDesactivar.length; i++) {
        btnDesactivar[i].addEventListener('click', e => {
            if(!confirm("¿Seguro de Deshabilitar?")) {
                e.preventDefault()
            }
        })
    }
</script>
@endcan
    @include('sinPermiso')
@endsection