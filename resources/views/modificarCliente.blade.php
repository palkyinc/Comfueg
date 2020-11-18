@extends('layouts.plantilla')

@section('contenido')


    <h3>Modificando Cliente con ID: {{ $elemento->id }}</h3>
@can('clientes_edit')
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarCliente" method="post">
        @csrf
        @method('patch')

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" value="{{$elemento->nombre}}" maxlength="45"  class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="apellido">Apellido: </label>
                <input type="text" name="apellido" value="{{$elemento->apellido}}" maxlength="45"  class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="cod_area_tel">Código de área Tel: </label>
                <select class="form-control" name="cod_area_tel" required>
                    <option value="">Seleccione Código de área...</option>
                    @foreach ($codigosArea as $codigoArea)
                        @if ($codigoArea['id'] != $elemento->cod_area_tel)
                            <option value="{{$codigoArea['id']}}">{{$codigoArea['codigoDeArea']}}</option>
                        @else
                            <option value="{{$codigoArea['id']}}" selected>{{$codigoArea['codigoDeArea']}}</option>
                        @endif
                    @endforeach 
                    </select>
            </div>
            <div class="form-group col-md-4">
                <label for="telefono">Teléfono: </label>
                <input type="text" name="telefono" value="{{$elemento->telefono}}" maxlength="8"  class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="cod_area_cel">Código de área Cel: </label>
                <select class="form-control" name="cod_area_cel" required>
                    <option value="">Seleccione Código de área...</option>
                    @foreach ($codigosArea as $codigoArea)
                        @if ($codigoArea['id'] != $elemento->cod_area_cel)
                            <option value="{{$codigoArea['id']}}">{{$codigoArea['codigoDeArea']}}</option>
                        @else
                            <option value="{{$codigoArea['id']}}" selected>{{$codigoArea['codigoDeArea']}}</option>
                        @endif
                    @endforeach 
                    </select>
            </div>
            <div class="form-group col-md-1">
                <label for="prefijo">Prefijo</label>
                <input type="text" name="prefijo" value="15" class="form-control" disabled>
            </div>
            <div class="form-group col-md-4">
                <label for="celular">Celular: </label>
                <input type="text" name="celular" value="{{$elemento->celular}}" maxlength="8"  class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-8">
                <label for="email">Correo Electrónico: </label>
                <input type="email" name="email" value="{{$elemento->email}}" class="form-control">
            </div>
        </div>
            <input type="hidden" name="id" value="{{$elemento->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
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