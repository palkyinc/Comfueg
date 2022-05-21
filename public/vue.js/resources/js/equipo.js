Vue.component('Equipo',
{
template: //html
`
<div>
    <div :class="class_formulario_equipo_general">
        <div class="container my-4" :class="class_consultar_equipo">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header"><h4>Buscar MacAddress de {{titulo}}</h4></div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input type="text" v-model="equipo_macaddress" maxlength="17" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div :class="class_btn_consultar" class="card-footer">
                                <button type="submit" class="btn btn-dark"  title="Consultar existencia en BAse de Datos" v-on:click="consulta_mac">
                                    Consultar
                                </button>
                            </div>
                            <h5 class="my-0 px-3 py-1" :class="alert_mensaje_usuario">{{mensaje_usuario}}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="container my-4" :class="class_formulario_equipo">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header"><h4>Ingresar datos de {{titulo}}</h4></div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Nombre: </label>
                                        <input type="text" v-model="equipo_nombre" class="form-control" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Mac Address: </label>
                                        <input type="text" v-model="equipo_macaddress" class="form-control" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="nombre">Fecha de Alta: </label>
                                        <input type="text" v-model="equipo_alta" class="form-control" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Fecha de Baja: </label>
                                        <input type="text" v-model="equipo_baja" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Artículo: </label>
                                        <select class="custom-select" v-model="equipo_dispositivo">
                                            <option v-for="articulo in articulos" v-bind:value="articulo.id">{{articulo.modelo}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Antena: </label>
                                        <select class="custom-select" v-model="equipo_antena" required>
                                            <option v-for="antena in antenas" v-bind:value="antena.id">{{antena.descripcion}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Ip:</label>
                                        <input :class="class_equipo_ip" type="text" v-model="equipo_ip" class="form-control">
                                        <label>(0.0.0.0 para automático)</label>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Comentario del equipo: </label>
                                        <textarea :class="class_equipo_comentarios" v-model="equipo_comentario" class=" form-control" rows="auto" cols="100"></textarea>
                                        <label>{{mensaje_comentarios}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button :class="class_guardar_equipo" class="btn btn-primary" title="Guardar en Base de Datos" v-on:click="guardar_equipo">{{guardar_editar}}</button>
                                <button :class="class_seleccionar_equipo" class="btn btn-dark"  title="Seleccionar para Instalación" v-on:click="seleccionar_equipo">Seleccionar</button>
                                <button class="btn btn-info" title="Consultar otro MacAddress" v-on:click="consultar_otro_mac">Consultar otro Mac</button>
                            </div>
                            <h5 class="my-0 px-3 py-1" :class="alert_mensaje_usuario">{{mensaje_usuario}}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
`,
    data(){
        return{
            articulos: [],
            antenas: [],
            equipo_id: null,
            equipo_macaddress: null,
            equipo_dispositivo: 0,
            equipo_ip: '0.0.0.0',
            equipo_alta: null,
            equipo_antena: 0,
            equipo_baja: null,
            equipo_comentario: '',
            equipo_macaddress2: null,
            equipo_dispositivo2: null,
            equipo_ip2: null,
            equipo_alta2: null,
            equipo_antena2: 0,
            equipo_baja2: null,
            equipo_comentario2: null,
            tit_router: 'Router',
            tit_equipo: 'Ubiquiti',
            mensaje_usuario: '',
            mensaje_comentarios: '',
            alert_mensaje_usuario: '',
            class_btn_consultar: 'ocultar',
            existe_equipo: null,
            class_equipo_ip: '',
            class_equipo_comentarios: '',
            class_guardar_equipo: 'ocultar',
            class_seleccionar_equipo: 'ocultar',
            class_consultar_equipo: '',
            class_cargar_equipo: 'ocultar',
            class_formulario_equipo: 'ocultar',
            siguiente_paso: null
        }
    },
    mounted(){
        this.reset_datos();
        this.get_articulos();
        this.get_antenas();
    },
    beforeUpdate() {
    },
    computed: {
        guardar_editar: {
            get: function () {
                return (this.existe_equipo && this.existe_equipo.datos) ? 'Editar' : ' Guardar';
            }
        },
        equipo_nombre: {
            get: function () {
                return store.state.cliente_nombre;
            }
        },
        class_formulario_equipo_general:{
            get: function () {
                if (store.state.formulario_equipo || store.state.formulario_router) {
                    return '';
                }else {
                    return 'ocultar';
                }
            }
        },
        titulo:{
            get: function () {
                return (this.soy_equipo()) ? this.tit_equipo : this.tit_router;
            }
        },
        equipo_reset:{
            get: function () {
                return store.state.equipo_reset;
            },
            set: function (newVal) {
                store.state.equipo_reset = newVal;
            }
        }
    },
    watch: {
        equipo_reset: function () {
            this.consultar_otro_mac();
            store.state.equipo_reset = false;
        },
        equipo_macaddress: function () {
            const regex = /^(?:[0-9A-F]{2}[:]){5}(?:[0-9A-F]{2})$/;
            if (regex.test(this.equipo_macaddress)){
                this.class_btn_consultar = '';
                this.mensaje_usuario = 'Formato de Mac Address OK.'
                this.alert_mensaje_usuario = 'alert alert-success'
            } else {
                this.class_btn_consultar = 'ocultar';
                this.mensaje_usuario = 'Formato de Mac Address INCORRECTO.'
                this.alert_mensaje_usuario = 'alert alert-danger'
            }
        },
        equipo_ip: function () {
                this.validar_datos();
        },
        equipo_dispositivo: function () {
                this.validar_datos();
        },
        equipo_antena: function () {
                this.validar_datos();
        },
        equipo_comentario: function () {
                this.validar_datos();
        }
    },
    methods: {
        soy_equipo () {
            this.siguiente_paso = 3;
            switch (store.state.tipoInstalacion) {
                case '1':
                    return true;
                    break;

                case '2':
                    if (store.state.equipo_id) {
                        return false;
                    } else {
                        this.siguiente_paso = 4;
                        return true;
                    }
                    break;

                case '3':
                    return false;
                    break;

                default:
                    return null;
                    break;
            }
        },
        check_error_comentarios() {
            const longitud = this.equipo_comentario.length;
            if ((longitud < 101 && longitud > 2) || longitud === 0) {
                this.class_equipo_comentarios = 'is-valid';
                this.mensaje_comentarios = 'Max: 100. Caracteres: ' + longitud;
                return true;
            } else {
                this.class_equipo_comentarios = 'is-invalid';
                this.mensaje_comentarios = 'Min: 3. Max: 100.';
                return false;
            }
        },
        check_error_ip () {
            const regex = /^(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
            if (regex.test(this.equipo_ip)) {
                this.class_equipo_ip = 'is-valid';
                return true;
            } else {
                this.class_equipo_ip = 'is-invalid';
                return false;
            }
        },
        check_error_select_numerico (candidato, coleccion) {
            const regex = /^[0-9]*$/;
            let rta = false;
            if (regex.test(candidato)) {
                coleccion.forEach(element => {
                    if (element.id == candidato) {
                        rta = true;
                    }
                });
            }
            return rta;
        },
        validar_datos () {
            if (this.check_error_ip() && 
                this.check_error_select_numerico(this.equipo_dispositivo, this.articulos) && 
                this.check_error_select_numerico(this.equipo_antena, this.antenas) &&
                this.check_error_comentarios()) {
                    if (this.validar_cambios()){
                        this.class_guardar_equipo = 'ocultar';
                        this.class_seleccionar_equipo = '';
                        this.mensaje_usuario = 'Listo Seleccionar';
                        this.alert_mensaje_usuario = 'alert alert-success'
                    }else {
                        this.class_guardar_equipo = '';
                        this.class_seleccionar_equipo = 'ocultar';
                        this.mensaje_usuario = 'Listo para guardar en Base de datos';
                        this.alert_mensaje_usuario = 'alert alert-success'
                    }
            } else {
                this.class_guardar_equipo = 'ocultar';
                this.class_seleccionar_equipo = 'ocultar';
                this.mensaje_usuario = 'Completar todos los datos correctamente.';
                this.alert_mensaje_usuario = 'alert alert-danger'
            }
        },
        validar_cambios () {
            if (
                this.equipo_comentario == this.equipo_comentario2 &&
                this.equipo_ip == this.equipo_ip2 &&
                this.equipo_dispositivo == this.equipo_dispositivo2 &&
                this.equipo_antena == this.equipo_antena2 
            ) {
                return true;
            }else {
                return false;
            }
        },
        reset_datos () {
            this.equipo_macaddress = null;
            this.existe_equipo = null;
            this.equipo_macaddress = null;
            this.equipo_dispositivo = 0;
            this.equipo_ip = '0.0.0.0';
            this.equipo_alta = null;
            this.equipo_antena = 0;
            this.equipo_baja = null;
            this.equipo_comentario = '';
            this.equipo_macaddress2 = null;
            this.existe_equipo2 = null;
            this.equipo_macaddress2 = null;
            this.equipo_dispositivo2 = null;
            this.equipo_ip2 = null;
            this.equipo_alta2 = null;
            this.equipo_antena2 = null;
            this.equipo_baja2 = null;
            this.equipo_comentario2 = null;
        },
        consultar_otro_mac() {
            this.reset_datos();
            this.class_btn_consultar = 'ocultar';
            this.class_consultar_equipo = '';
            this.class_formulario_equipo = 'ocultar';
        },
        seleccionar_equipo() {
            this.mensaje_usuario = 'Seleccionando...';
            this.alert_mensaje_usuario = 'alert alert-success'
            const url = 'http://' + website + '/Session';
            const metodo = 'put';
            let datos;
            if (this.soy_equipo()) {
                datos = {
                    datos: {
                        equipo_id: this.equipo_id ?? this.existe_equipo.datos.id,
                    }
                };
            } else {
                datos = {
                    datos: {
                        router_id: this.equipo_id ?? this.existe_equipo.datos.id,
                    }
                };
            }
            fetch_api(url, metodo, datos, this.seleccionar_equipo1);
        },
        seleccionar_equipo1() {
            store.state.inst_paso = this.siguiente_paso;
            if (this.soy_equipo()) {
                store.state.equipo_id = this.equipo_id ?? this.existe_equipo.datos.id;
                console.log('seleccionar_equipo1: ' + store.state.equipo_id)
            }else {
                store.state.router_id = this.equipo_id ?? this.existe_equipo.datos.id;
            }
            this.mensaje_usuario = 'Seleccionado';
            this.alert_mensaje_usuario = 'alert alert-success';
        },
        post_guardar (data) {
            if (typeof(data) == 'Object') {
                this.mensaje_usuario = "Error al guardar datos.";
                this.alert_mensaje_usuario = 'alert alert-danger';
                console.log(data);
            } else if (typeof (data) == 'number') {
                this.mensaje_usuario = "Guardado OK.";
                this.alert_mensaje_usuario = 'alert alert-success';
                this.class_seleccionar_equipo = '';
                this.class_guardar_equipo = 'ocultar';
                this.equipo_id = data;

            }
        },
        guardar_equipo() {
            this.mensaje_usuario = 'Guardando...';
            this.alert_mensaje_usuario = 'alert alert-warning'
            const url = 'http://' + website + '/agregarEquipo2';
            const metodo = 'put';
            const datos = {
                    id: (this.existe_equipo && this.existe_equipo.datos ? this.existe_equipo.datos.id : null), 
                    nombre : this.equipo_nombre,
                    num_dispositivo: this.equipo_dispositivo,
                    mac_address: this.equipo_macaddress,
                    ip: this.equipo_ip,
                    num_antena: this.equipo_antena,
                    comentario: this.equipo_comentario,
            };
            fetch_api(url, metodo, datos, this.post_guardar);
        },
        consulta_mac() {
            fetch('/existeEquipo/' + this.equipo_macaddress)
                .then(valor => valor.json())
                .then(valor => {
                    this.existe_equipo = valor;
                    this.post_consulta_mac();
                });
        },
        get_articulos() {
            fetch('/getProductos/')
                .then(valor => valor.json())
                .then(valor => {
                    this.articulos = valor;
                });
        },
        get_antenas() {
            fetch('/getAntenas/')
                .then(valor => valor.json())
                .then(valor => {
                    this.antenas = valor;
                });
        },
        post_consulta_mac(){
            if (this.existe_equipo.status){
                this.mensaje_usuario = this.existe_equipo.mensaje;
                this.alert_mensaje_usuario = 'alert alert-danger'
            } else if (this.existe_equipo.datos) {
                if (this.existe_equipo.datos.nombre != this.equipo_nombre || this.existe_equipo.datos.nombre == 'NUEVO') {
                    const fecha = new Date();
                    const day = fecha.getDate();
                    const month = fecha.getMonth() + 1;
                    const year = fecha.getFullYear();
                    this.equipo_comentario = this.equipo_comentario2 = 
                        day + '-' + month + '-' + year + '|' +
                        'EX-' + ( this.existe_equipo.datos.nombre ?? '') +
                        '. ' + ((this.existe_equipo.datos.comentario) ?? '');
                } else {
                    this.equipo_comentario2 = this.equipo_comentario = 
                        (this.existe_equipo.datos.comentario) ?? '';
                }
                this.equipo_baja = this.equipo_baja2 = this.existe_equipo.datos.fecha_baja ?? '';
                this.equipo_alta = this.equipo_alta2 = this.existe_equipo.datos.fecha_alta ?? '';
                this.equipo_ip = this.equipo_ip2 = this.existe_equipo.datos.ip ?? '';
                this.equipo_dispositivo = this.equipo_dispositivo2 = this.existe_equipo.datos.num_dispositivo.id ?? '';
                this.equipo_antena = this.equipo_antena2 = this.existe_equipo.datos.num_antena.id ?? '';
                this.class_consultar_equipo = 'ocultar';
                this.class_formulario_equipo = '';
                this.mensaje_usuario = 'Existe el equipo y esta disponible';
                this.alert_mensaje_usuario = 'alert alert-success'
            } else {
                this.class_consultar_equipo = 'ocultar';
                this.class_formulario_equipo = '';
                this.mensaje_usuario = 'NO existe el equipo en base de datos';
                this.alert_mensaje_usuario = 'alert alert-warning';
            }
        }
    }
})