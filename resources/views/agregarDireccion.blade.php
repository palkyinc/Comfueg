@extends('layouts.plantilla')

@section('contenido')


    <h3>Agregar Dirección nueva.</h3>
@can('direcciones_create')
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarDireccion" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="id_calle">Calle: </label>
                <select class="form-control" name="id_calle">
                    <option value="null">Seleccione una Calle...</option>
                    @foreach ($calles as $calle)
                        <option value="{{$calle['id']}}">{{$calle['nombre']}}</option>
                    @endforeach 
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="numero">Altura: </label>
                <input type="text" name="numero" value="{{old('numero')}}" maxlength="5"  class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="entrecalle_1">Entrecalle 1: </label>
                <select class="form-control" name="entrecalle_1">
                    <option value="">Seleccione una Entrecalle...</option>
                    @foreach ($calles as $calle)
                        <option value="{{$calle['id']}}">{{$calle['nombre']}}</option>
                    @endforeach 
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="entrecalle_2">Entrecalle 2: </label>
                <select class="form-control" name="entrecalle_2">
                    <option value="">Seleccione una Entrecalle...</option>
                    @foreach ($calles as $calle)
                        <option value="{{$calle['id']}}">{{$calle['nombre']}}</option>
                    @endforeach
                </select>
            </div>
            
        </div>
              
            <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="id_barrio">Barrio: </label>
                  <select class="form-control" name="id_barrio" id="id_barrio">
                        <option value="">Seleccione un Barrio...</option>
                        @foreach ($barrios as $barrio)
                            <option value="{{$barrio['id']}}">{{$barrio['nombre']}}</option>
                        @endforeach 
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="id_ciudad">Ciudad: </label>
                    <select class="form-control" name="id_ciudad" id="id_barrio">
                      <option value="1">Rio Grande</option>
                        @foreach ($ciudades as $ciudad)
                            <option value="{{$ciudad['id']}}">{{$ciudad['nombre']}}</option>
                        @endforeach 
                    </select>
                </div>
            </div>
    
            <button type="submit" class="btn btn-primary" id="enviar">Crear Nueva</button>
            <a href="/adminDirecciones" class="btn btn-primary">volver</a>
    </form>
    </div>

    @if( $errors->any() )
        <div class="alert alert-danger col-8 mx-auto">
            <ul>
                @foreach( $errors->all() as $error )
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endcan
@endsection