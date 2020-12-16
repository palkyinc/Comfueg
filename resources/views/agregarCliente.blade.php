@extends('layouts.plantilla')

@section('contenido')


    <h3>Agregar Cliente nuevo</h3>
@can('cliente_create')
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarCliente" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="id">ID Genesys: </label>
                <input type="text" name="id" value="{{old('id')}}" maxlength="45"  class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" value="{{old('nombre')}}" maxlength="45"  class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="apellido">Apellido: </label>
                <input type="text" name="apellido" value="{{old('apellido')}}" maxlength="45"  class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="cod_area_tel">Código de área Tel: </label>
                <select class="form-control" name="cod_area_tel" required>
                    <option value="154">2964</option>
                    @foreach ($codigosArea as $codigoArea)
                        <option value="{{$codigoArea['id']}}">{{$codigoArea['codigoDeArea']}}</option>
                    @endforeach 
                    </select>
            </div>
            <div class="form-group col-md-4">
                <label for="telefono">Teléfono: </label>
                <input type="text" name="telefono" value="{{old('telefono')}}" maxlength="8"  class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="cod_area_cel">Código de área Cel: </label>
                <select class="form-control" name="cod_area_cel" required>
                    <option value="154">2964</option>
                    @foreach ($codigosArea as $codigoArea)
                        <option value="{{$codigoArea['id']}}">{{$codigoArea['codigoDeArea']}}</option>
                    @endforeach 
                    </select>
            </div>
            <div class="form-group col-md-1">
                <label for="prefijo">Prefijo</label>
                <input type="text" name="prefijo" value="15" class="form-control" disabled>
            </div>
            <div class="form-group col-md-4">
                <label for="celular">Celular: </label>
                <input type="text" name="celular" value="{{old('celular')}}" maxlength="8"  class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-8">
                <label for="email">Correo Electrónico: </label>
                <input type="email" name="email" value="{{old('email')}}" class="form-control">
            </div>
        </div>
            <button type="submit" class="btn btn-primary" id="enviar">Crear Nuevo</button>
            <a href="/adminClientes" class="btn btn-primary">volver</a>
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