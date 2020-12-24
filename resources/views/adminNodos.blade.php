@extends('layouts.plantilla')

@section('contenido')
@can('nodos_index')
<div class="card-columns">
     @foreach ($nodes as $node)
        <div class="card">
        <iframe class="card-img-top" src="https://www.google.com/maps/embed/v1/place?q={{$node->coordenadas}}&key=AIzaSyCjraCSscuuh32GnjPF_Hnvq0_-xY1Ay1Y" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            <div class="card-body">
            <h5 class="card-title">{{$node->nombre}}</h5>
                <p class="card-text">Rango de IP: {{$node->rangoIp}}</p>
                <p class="card-text">{{$node->descripcion}}</p>
                <footer class="blockquote-footer">
                    <a href="mostrarNodo/{{$node->id}}" class="btn btn-primary">Mas Info</a>
                </footer>
            </div>
        </div>
        @endforeach
    </div>
@endcan
@endsection
