@extends('layouts.plantilla')

@section('contenido')
<h3>Agregando esquema o foto al sitio con ID: {{ $sitio_id }}</h3> <!--  -->
@can('nodos_edit')
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/adminArchivosSitio" method="post" enctype="multipart/form-data">
        @csrf
        @method('patch')
            <div class="form-group">
                <label for="scheme_file">Seleccionar o arrastrar archivo</label>
                <input type="file" name="scheme_file" class="form-control-file">
            </div>
            <input type="hidden" name="sitioId" value="{{$sitio_id}}">
            <button type="submit" class="btn btn-primary">Cargar</button>
            <a href="/adminArchivosSitio/{{$sitio_id}}" class="btn btn-primary">Volver</a>
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