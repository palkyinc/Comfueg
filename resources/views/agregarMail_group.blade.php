@extends('layouts.plantilla')

@section('contenido')


    <h3>Nuevo Grupo de mail</h3>
@can('mail_group_create')
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/agregarMail_group" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Nombre: </label>
                <input type="text" name="name" value="{{old('name')}}" maxlength="45"  class="form-control" autofocus>
            </div>
        </div>
            <button type="submit" class="btn btn-primary" id="enviar">Crear Nuevo</button>
            <a href="/adminMail_groups" class="btn btn-primary">volver</a>
        
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