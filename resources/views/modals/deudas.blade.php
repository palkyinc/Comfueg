 @foreach ($incidentes as $incidente)
 @if ($incidente->tipo === 'DEUDA TECNICA')
    <div class="modal fade" id="staticBackdrop{{$incidente->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdrop{{$incidente->id}}Label">Deuda Técnica N°: {{$incidente->id}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </div>
            <div class="modal-body">
                <form action="#" method="post">
                    @csrf
                    @method('patch')
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label for="tipo" class="mx-3">Tipo</label>
                                <input type="text" name="tipo" value="{{$incidente->tipo}}" class="form-control" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inicio">Inicio: </label>
                                <input type="datetime-local" name="inicio" value="{{$incidente->inicioDateTimeLocal()}}" class="form-control" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="final">Final: </label>
                                @if ($incidente->final)
                                    <input type="datetime-local" name="final" value="{{$incidente->finalDateTimeLocal()}}" class="form-control" readonly>
                                @else
                                    <input type="text" name="final" value="Aun Ocurriendo" class="form-control" readonly>
                                @endif
                            </div>
                            <div class="form-group col-md-2">
                                <label for="afectado">Equipo Afectado: </label>
                                <input type="text" name="afectado" value="{{$incidente->relPanel->relEquipo->nombre}}" class="form-control" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="user_creator">Creado Por: </label>
                                <input type="text" name="user_creator" value="{{$incidente->relUser->name}}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="mensaje_clientes">Título: </label>
                                <input type="text" name="mensaje_clientes" value="{{$incidente->mensaje_clientes}}" class="form-control" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="sitio">Sitio Afectado: </label>
                                <input type="text" name="sitio" value="{{$incidente->relPanel->relSite->nombre}}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="causa">Deuda Técnica: </label>
                                <textarea name="causa" class="form-control" rows="auto" cols="50" readonly>{{$incidente->causa}}</textarea>
                            </div>
                        </div>
                        @foreach ($incidente->incidente_has_mensaje as $mensaje)
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="actualizacion">Actualización realizada por: {{$mensaje->relUser->name}} el {{$mensaje->created_at}} </label>
                                    <textarea name="actualizacion" class="form-control" rows="auto" cols="50" readonly>{{$mensaje->mensaje}}</textarea>
                                </div>
                            </div>
                        @endforeach
                        @php
                            $archivos = $incidente->archivos;
                        @endphp
                        @if (count($archivos))
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <h5>Documentos PDF adjuntos a la incidencia</h5>
                                     @foreach ($archivos as $archivo)
                                        @if ($archivo->tipo === 'FILE')
                                            <a href="/imgUsuarios/pdf/{{$archivo->file_name}}" target="_blank">{{$archivo->file_name}}</a>
                                        @endif
                                     @endforeach
                                </div>
                            </div>
                        @endif
                </form>
            </div>
            <div class="modal-footer">
                @if (count($archivos))
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdropPhoto{{$incidente->id}}">
                        Fotos
                    </button>
                @endif
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@if (count($archivos))
@include('modals.photosCarrusel')
@endif
@endif
@endforeach