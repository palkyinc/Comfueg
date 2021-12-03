<div class="modal fade" id=staticBackdropSyncCloud tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sincronizando desde Cloud...</h5>
      </div>
      <div class="modal-body d-flex align-items-center justify-content-center">
            <p id="parrafo">Esta acción sincroniza cloud al server y puede borrar archivos más nuevos del Server.</p>
            <div class="ocultar" id="downloading">
                <iframe src="imagenes/Downloading.gif" width="120" height="120" frameBorder="0"></iframe>
            </div>
      </div>
      <div class="modal-footer" id="footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <a href="/adminBackupsSync" type="button" class="btn btn-primary" id="syncBtn">Sincronizar</a>
      </div>
      <div class="modal-footer ocultar" id="footer2">
        <p>Aguarde que estamos descargando. No cierre la ventana. El tiempo de descarga depende de la velocidad de internet.</p>
      </div>
    </div>
  </div>
</div>

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
            }
        })
    </script>    
@endsection
