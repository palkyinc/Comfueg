@extends('layouts.plantilla')

@section('contenido')
@can('backups_create')
@php
$mostrarSololectura = true;
@endphp
<div class="container">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Restaurar Backup...</h5>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center">
                    <p id="parrafo">Esta acción restaura, base de datos y archivos desde archivo zip a Slam server. Puede borrar archivos no deseados.</p>
                    <div class="ocultar" id="downloading">
                        <iframe src="../imagenes/Downloading.gif" width="120" height="120" frameBorder="0"></iframe>
                    </div>
            </div>
            <div class="modal-footer" id="footer">
                <a href="/adminBackups" type="button" class="btn btn-secondary" data-dismiss="modal">Volver</a>
                <form action="/adminBackupRestore" method="post">
                    @csrf
                    @method('post')
                    <input type="hidden" name="file" value="{{$archivo}}">
                    <button type="submit" class="btn btn-primary" id="syncBtn">Restaurar</button>
                </form>
            </div>
            <div>
                <div class="modal-footer ocultar" id="footer2">
                    <p>Aguarde que estamos restaurando. No cierre la ventana.</p>
                </div>
            </div>
        </div>
</div>
@endcan
@include('sinPermiso')
@endsection

@section('javascript')
    <script>
        let syncBtn = document.getElementById('syncBtn');
        let parrafo = document.getElementById('parrafo');
        let downloading = document.getElementById('downloading');
        let footer = document.getElementById('footer');
        let footer2 = document.getElementById('footer2');
        syncBtn.addEventListener ('click', e => {
            if(!confirm("¿Está seguro de continuar?"))
            {
                e.preventDefault()
            } else {
                parrafo.classList.add('ocultar')
                downloading.classList.remove('ocultar')
                footer.classList.add('ocultar')
                footer2.classList.remove('ocultar')
                //e.preventDefault()
            }
        })
    </script>    
@endsection