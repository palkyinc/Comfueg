@extends('layouts.plantilla')

@section('contenido')

                    <form class="form-inline mx-6 margin-10" action="" method="GET">
                        <h2 class="mx-2">Administración de Antenas</h2>
                        <label for="descripcion" class="mx-2">Descripción</label>
                        <input type="text" name="descripcion" class="form-control mx-3" id="descripcion">
                        <label for="codComfueg" class="mx-2">Código Comfueg</label>
                        <input type="text" name="codComfueg" class="form-control mx-3" id="codComfueg">
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
                    <caption>Listado de Antenas</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Descripción </th>
                            <th scope="col"> Código Comfueg </th>
                            <th scope="col"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($antenas as $antena)
                            <tr>
                                
                            <th scope="row"> {{$antena->id}}</th>
                            <td>{{$antena->descripcion}}</td>
                            <td>{{$antena->cod_comfueg}}</td>
                            <td>
                                <a href="/modificarAntena/{{ $antena->id }}" class="margenAbajo btn btn-outline-secundary">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $antenas->links() }}
    
@endsection