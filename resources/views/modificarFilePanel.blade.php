@extends('layouts.plantilla')

@section('contenido')
@if (null != $file)
<h3>Modificando covertura "{{$file->file_name}}" del panel con ID: {{ $panel_id }}</h3> <!--  -->
@else
<h3>Agregando covertura del panel con ID: {{ $panel_id }}</h3> <!--  -->
@endif
@can('nodos_edit')
    <div class="alert bg-light border col-8 mx-auto p-4">
    <form action="/cambiarFilePanel" method="post" enctype="multipart/form-data">
        @csrf
        @method('patch')
            <div class="form-group">
                <label for="cover_file">Seleccionar o arrastrar archivo</label>
                <input type="file" name="cover_file" class="form-control-file">
            </div>
            @if (null != $file)
                <input type="hidden" name="archivoId" value="{{$file->id}}">
            @endif
            <input type="hidden" name="panelId" value="{{$panel_id}}">
            <input type="hidden" name="sitioId" value="{{$sitio_id}}">
            <button type="submit" class="btn btn-primary">Modificar</button>
            <a href="/mostrarNodo/{{$sitio_id}}" class="btn btn-primary">Volver</a>
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