@extends('layouts.plantilla')

@section('contenido')

                    <form class="form-inline mx-4 margin-10" action="" method="GET">
                        <h2 class="mx-3">Administración de niveles</h2>
                        <label for="nombre" class="mx-3">Nombre</label>
                        <input type="text" name="nombre" class="form-control mx-3" id="nombre">
                        <button type="submit" class="btn btn-primary mx-3">Enviar</button>
                    </form>

        @if ( session('mensaje') )
            <div class="alert alert-success">
                {{ session('mensaje') }}
            </div>
        @endif
        
<div class="table-responsive">
                
                <table class="table table-sm table-bordered table-hover">
                    <caption>Listado de niveles</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Nivel </th>
                            <th scope="col"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($niveles as $nivel)
                            <tr>
                                
                            <th scope="row"> {{$nivel->id}}</th>
                            <td>{{$nivel->nombre}}</td>
                            <td><form action="" method="post" class="margenAbajo">
                                <input type="hidden" name="idEdit" value="{{$nivel->id}}">
                                <button class="btn"><img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px"></button>
                            </form>
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $niveles->links() }}
    
@endsection