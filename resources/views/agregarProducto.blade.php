@extends('layouts.plantilla')

@section('contenido')


    <h3>Agregar Producto nuevo</h3>
@can('productos_create')
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarProducto" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="marca">Marca: </label>
                <input type="text" name="marca" value="{{old('marca')}}" maxlength="45"  class="form-control">
            </div>
            <div class="form-group col-md-6">
                <label for="modelo">Modelo: </label>
                <input type="text" name="modelo" value="{{old('modelo')}}" maxlength="45"  class="form-control">
            </div>
            <div class="form-group col-md-6">
                <label for="cod_comfueg">Código Comfueg: </label>
                <input type="text" name="cod_comfueg" value="{{old('cod_comfueg')}}" maxlength="45"  class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-9">
                <label for="descripcion">Descripción: </label>
                <textarea name="descripcion" class="form-control" id="descripcion" rows="auto" cols="50">{{old('descripcion')}}</textarea>
            </div>
        </div>    
    
            <input type="hidden" name="id" value="">
            <button type="submit" class="btn btn-primary" id="enviar">Crear Nuevo</button>
            <a href="/adminProductos" class="btn btn-primary">volver</a>
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