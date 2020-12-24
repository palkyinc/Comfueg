@extends('layouts.plantilla')

@section('contenido')


    <h3>Modificando Grupo de mail con ID: {{ $elemento->id }}</h3>
@can('mail_group_edit')
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/modificarMail_group" method="post">
        @csrf
        @method('patch')
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Nombre: </label>
                <input type="text" name="name" value="{{$elemento->name}}" maxlength="45"  class="form-control">
            </div>
        </div>
            <input type="hidden" name="id" value="{{$elemento->id}}">
            <button type="submit" class="btn btn-primary" id="enviar">Modificar</button>
            <a href="/adminMailGroups" class="btn btn-primary">volver</a>
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