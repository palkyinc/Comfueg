@extends('layouts.plantilla')

@section('contenido')
@can('direcciones_index')
                    <form class="form-inline mx-4 margin-10" action="" method="GET">
                        <h2 class="mx-3">Administración de direcciones</h2>
                        <label for="calle" class="mx-3">Calle: </label>
                        <input type="text" name="calle" class="form-control mx-3" id="nombreSearch">
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
                    <caption>Listado de direcciones</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Calle </th>
                            <th scope="col"> Número </th>
                            <th scope="col"> Entrecalle 1 </th>
                            <th scope="col"> Entrecalle 2 </th>
                            <th scope="col"> Barrio </th>
                            <th scope="col"> Ciudad </th>
                            <th scope="col" colspan="2">
                                @can('direcciones_create')
                                <a href="/agregarDireccion" class="btn btn-dark">Agregar</a>
                                @endcan
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($direcciones as $direccion)
                            <tr>
                                
                            <th scope="row"> {{$direccion->id}}</th>
                            <td>{{$direccion->relCalle->nombre}}</td>
                            <td>{{$direccion->numero}}</td>
                            @if ($direccion->relEntrecalle1)
                                <td>{{$direccion->relEntrecalle1->nombre}}</td>
                            @else
                                <td></td>
                            @endif
                            @if ($direccion->relEntrecalle2)
                                <td>{{$direccion->relEntrecalle2->nombre}}</td>
                            @else
                                <td></td>
                            @endif
                            <td>{{$direccion->relBarrio->nombre}}</td>
                            <td>{{$direccion->relCiudad->nombre}}</td>
                            <td>
                                @can('direcciones_edit')
                                <a href="/modificarDireccion/{{ $direccion->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                                @endcan
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $direcciones->links() }}
@endcan
@endsection
@section('javascript')
    <script>
        let datosArray=[], optionsFinal;
        fetch('/searchCalles')
        .then(valor=>valor.json())
        .then(valor=>
            {
                valor.forEach(element => datosArray.push(element.nombre));
            });
        optionsFinal = {data : datosArray, list:{match:{enabled:true}}};
        $("#nombreSearch").easyAutocomplete(optionsFinal);
    </script>
@endsection