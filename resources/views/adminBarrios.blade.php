@extends('layouts.plantilla')

@section('contenido')

        <form class="form-inline mx-4 margin-10" action="" method="GET">
            <h2 class="mx-3">Administración de Barrios</h2>
            <label for="nombre" class="mx-3">Nombre</label>
            <input type="text" name="nombre" class="form-control mx-3" id="nombreSearch">
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
            <caption>Listado de barrios</caption>
            <thead class="thead-light">
                <tr>
                    <th scope="col"> Id </th>
                    <th scope="col"> Nombre </th>
                    <th scope="col" colspan="2">
                        <a href="/agregarBarrio" class="btn btn-dark">Agregar</a>
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($barrios as $barrio)
                    <tr>
                        
                    <th scope="row"> {{$barrio->id}}</th>
                    <td>{{$barrio->nombre}}</td>
                    <td>
                        <a href="/modificarBarrio/{{ $barrio->id }}" class="margenAbajo btn btn-outline-secundary" title="Editar">
                        <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                        </a>
                    </td>
                    
                    </tr>
                @endforeach
            </tbody>
        </table>
</div>
        {{ $barrios->links() }}
    
@endsection

@section('javascript')
    <script>
        let datosArray=[], optionsFinal;
        fetch('/searchBarrios')
        .then(valor=>valor.json())
        .then(valor=>
            {
                valor.forEach(element => datosArray.push(element.nombre));
            });
        optionsFinal = {data : datosArray, list:{match:{enabled:true}}};
        $("#nombreSearch").easyAutocomplete(optionsFinal);
    </script>
@endsection