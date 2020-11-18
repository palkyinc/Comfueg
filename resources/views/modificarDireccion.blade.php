@extends('layouts.plantilla')

@section('contenido')


    <h3>Modificando Dirección con ID: {{ $elemento->id }}</h3>
@can('direcciones_edit')
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarDireccion" method="post">
        @csrf
        @method('patch')

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="id_calle">Calle: </label>
                <select class="form-control" name="id_calle">
                    <option value="null">Seleccione una Calle...</option>
                    @foreach ($calles as $calle)
                        @if ($calle['id'] != $elemento->id_calle)
                            <option value="{{$calle['id']}}">{{$calle['nombre']}}</option>
                        @else
                            <option value="{{$calle['id']}}" selected>{{$calle['nombre']}}</option>
                        @endif
                    @endforeach 
                    </select>
            </div>
            <div class="form-group col-md-4">
                <label for="numero">Altura: </label>
                <input type="text" name="numero" value="{{$elemento->numero}}" maxlength="5"  class="form-control" id="numero">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="entrecalle_1">Entrecalle 1: </label>
                <select class="form-control" name="entrecalle_1">
                    <option value="">Seleccione una Entrecalle...</option>
                    @foreach ($calles as $calle)
                        @if ($calle['id'] != $elemento->entrecalle_1)
                            <option value="{{$calle['id']}}">{{$calle['nombre']}}</option>
                        @else
                            <option value="{{$calle['id']}}" selected>{{$calle['nombre']}}</option>
                        @endif
                    @endforeach 
                    </select>
            </div>
            <div class="form-group col-md-4">
                <label for="entrecalle_2">Entrecalle 2: </label>
                <select class="form-control" name="entrecalle_2">
                    <option value="">Seleccione una Entrecalle...</option>
                    @foreach ($calles as $calle)
                        @if ($calle['id'] != $elemento->entrecalle_2)
                            <option value="{{$calle['id']}}">{{$calle['nombre']}}</option>
                        @else
                            <option value="{{$calle['id']}}" selected>{{$calle['nombre']}}</option>
                        @endif
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
                        @if ($barrio['id'] != $elemento->id_barrio)
                            <option value="{{$barrio['id']}}">{{$barrio['nombre']}}</option>
                        @else
                            <option value="{{$barrio['id']}}" selected>{{$barrio['nombre']}}</option>
                        @endif
                    @endforeach 
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="id_ciudad">Ciudad: </label>
                    <select class="form-control" name="id_ciudad" id="id_barrio">
                      <option value="1">Rio Grande</option>
                        @foreach ($ciudades as $ciudad)
                            @if ($ciudad['id'] != $elemento->id_ciudad)
                                <option value="{{$ciudad['id']}}">{{$ciudad['nombre']}}</option>
                            @else
                                <option value="{{$ciudad['id']}}" selected>{{$ciudad['nombre']}}</option>
                            @endif
                        @endforeach 
                    </select>
                </div>
            </div>
    
            <input type="hidden" name="id" value="{{$elemento->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
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