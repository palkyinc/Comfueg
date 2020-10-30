@extends('layouts.plantilla')

@section('contenido')

                    <form class="form-inline mx-4 margin-10" action="" method="GET">
                        <h2 class="mx-3">Administración de Códigos de área</h2>
                        <label for="codigoDeArea" class="mx-3">Código de área</label>
                        <input type="text" name="codigoDeArea" class="form-control mx-3" id="codigoDeArea">
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
                    <caption>Listado de Códigos de área</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Código de área </th>
                            <th scope="col"> Provicias </th>
                            <th scope="col"> Localidades </th>
                            <th scope="col"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($codigosDeArea as $codigo)
                            <tr>
                                
                            <th scope="row"> {{$codigo->id}}</th>
                            <td>{{$codigo->codigoDeArea}}</td>
                            <td>{{$codigo->provincia}}</td>
                            <td>{{$codigo->localidades}}</td>
                            <td>
                                <a href="/modificarCodigoDeArea/{{ $codigo->id }}" class="margenAbajo btn btn-outline-secundary">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $codigosDeArea->links() }}
    
@endsection