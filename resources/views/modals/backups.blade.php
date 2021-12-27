{{-- Sync desde Cloud --}}
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

{{-- Sync Hacia Cloud --}}
<div class="modal fade" id=staticBackdropSyncCloudUp tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sincronizando hacia Cloud...</h5>
      </div>
      <div class="modal-body d-flex align-items-center justify-content-center">
            <p id="parrafo10">Esta acción sincroniza server al cloud y puede borrar archivos más nuevos del Cloud.</p>
            <div class="ocultar" id="downloading10">
                <iframe src="imagenes/Downloading.gif" width="120" height="120" frameBorder="0"></iframe>
            </div>
      </div>
      <div class="modal-footer" id="footer10">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <a href="/adminBackupsSync?action=true" type="button" class="btn btn-primary" id="syncBtn10">Sincronizar</a>
      </div>
      <div class="modal-footer ocultar" id="footer12">
        <p>Aguarde que estamos Cargando los aechivos en el Cloud. No cierre la ventana. El tiempo de carga depende de la velocidad de internet.</p>
      </div>
    </div>
  </div>
</div>

{{-- Backup manual --}}

<div class="modal fade" id=staticBackdropBackupNow tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Realizando backup manual...</h5>
      </div>
      <div class="modal-body d-flex align-items-center justify-content-center">
        <p id="parrafo20">Esta acción realiza un backup completo del sistema a disco.</p>
        <div class="ocultar" id="downloading20">
            <iframe src="imagenes/Downloading.gif" width="120" height="120" frameBorder="0"></iframe>
        </div>
      </div>
      <div class="modal-footer" id="footer20">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <a href="/adminBackupsBkpManual" type="button" class="btn btn-primary" id="syncBtn20">Sincronizar</a>
      </div>
      <div class="modal-footer ocultar" id="footer22">
        <p>Aguarde que estamos realizando un Backup completo. No cierre la ventana.</p>
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
        let syncBtn10 = document.getElementById('syncBtn10');
        let parrafo10 = document.getElementById('parrafo10');
        let downloading10 = document.getElementById('downloading10');
        let footer10 = document.getElementById('footer10');
        let footer12 = document.getElementById('footer12');
        syncBtn10.addEventListener ('click', e => {
            if(!confirm("¿Está seguro de continuar?"))
            {
                e.preventDefault()
            } else {
                parrafo10.classList.add('ocultar')
                downloading10.classList.remove('ocultar')
                footer10.classList.add('ocultar')
                footer12.classList.remove('ocultar')
            }
        })
        let syncBtn20 = document.getElementById('syncBtn20');
        let parrafo20 = document.getElementById('parrafo20');
        let downloading20 = document.getElementById('downloading20');
        let footer20 = document.getElementById('footer20');
        let footer22 = document.getElementById('footer22');
        syncBtn20.addEventListener ('click', e => {
            if(!confirm("¿Está seguro de continuar?"))
            {
                e.preventDefault()
            } else {
                parrafo20.classList.add('ocultar')
                downloading20.classList.remove('ocultar')
                footer20.classList.add('ocultar')
                footer22.classList.remove('ocultar')
            }
        })
    </script>    
@endsection
