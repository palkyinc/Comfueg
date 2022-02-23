@foreach ($altas as $alta)
    <div class="modal fade" id="altaCambioFecha{{$alta->id}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Cambio de fecha de Instalación</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="modificarAlta" method="post">
            <div class="modal-body">
                <p>Cliente: {{$alta->relCliente->getNomyApe()}}</p>
                @csrf
                    <div class="form-group">
                        <label for="nuevaFecha">Nueva fecha: </label>
                        <input id="nuevaFecha" type="datetime-local" name="nuevaFecha" value="{{$alta->instalacion_fecha}}" class="form-control">
                    </div>
                    <input type="hidden" name="id" value="{{$alta->id}}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
        </div>
    </div>
</div>
@endforeach
