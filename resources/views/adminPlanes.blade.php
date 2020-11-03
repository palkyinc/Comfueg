@extends('layouts.plantilla')

@section('contenido')

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
        
<div class="table-responsive">
                
                <table class="table table-sm table-bordered table-hover">
                    <caption>Listado de planes</caption>
                    <thead class="thead-light">
                        <tr>
                            <th scope="col"> Id </th>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Bajada (Kb) </th>
                            <th scope="col"> Subida (Kb)</th>
                            <th scope="col"> Descripcion </th>
                            <th scope="col" colspan="2">
                                <a href="/agregarPlan" class="btn btn-dark">Agregar</a>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($planes as $plan)
                            <tr>
                                
                            <th scope="row"> {{$plan->id}}</th>
                            <td>{{$plan->nombre}}</td>
                            <td>{{$plan->bajada}}</td>
                            <td>{{$plan->subida}}</td>
                            <td>{{$plan->descripcion}}</td>
                            <td> 
                                <a href="/modificarPlan/{{$plan->id}}" class="margenAbajo btn btn-outline-secundary">
                                <img src="imagenes/iconfinder_new-24_103173.svg" alt="imagen de lapiz editor" height="20px">
                                </a>
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
        {{ $planes->links() }}
    
@endsection