Vue.component('cliente',
{
    template: //html
`
<div>
    <div class="container alert alert-info mx-auto m-2 p-2" :class="class_cliente_seleccionado" role="alert">
        <div class="row justify-content-between">
            <div class="col-8 pr-2" v-if="esempresa">ID: {{id_cliente}} | {{apellido}}</div>
            <div class="col-8 pr-2" v-else>Cliente: ID: {{id_cliente}} | {{apellido}}, {{nombre}} | {{cod_area_cel}} 15 {{celular}}</div>
            <div class="col-1 pl-2">
                <button type="button" class="btn btn-secondary" @click="deseleccionar_cliente" >Editar</button>
            </div>
        </div>
    </div>
    <div class="alert bg-light border col-6 mx-auto p-4 m-1" :class="class_formulario_cliente">
        <div class="custom-control custom-switch m-4">
            <input type="checkbox" class="custom-control-input" id="customSwitch1" v-model="esempresa">
            <label class="custom-control-label" for="customSwitch1">Es Empresa</label>
        </div>
        <div :class="mostrar_cliente">
                <h4>Cliente:</h4>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <div class="input-group mb-3">
                            <input  type="text"
                                    v-model="id_cliente"
                                    placeholder="ID Genesys"
                                    v-bind:class="class_id_cliente">
                        </div>
                        <p>{{mensaje_id_cliente}}</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nombre">Nombre: </label>
                        <input type="text" v-model="nombre" v-bind:class="class_nombre">
                        <p>{{mensaje_nombre}}</p>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="apellido">Apellido: </label>
                        <input type="text" v-model="apellido" v-bind:class="class_apellido">
                        <p>{{mensaje_apellido}}</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="cod_area_tel">Código de área Tel: </label>
                        <input v-bind:class="class_cod_area_tel" placeholder="Sin Ceros" v-model="cod_area_tel">
                        <p>{{mensaje_cod_area_tel}}</p>
                    </div>
                    <div class="form-group col-md-9">
                        <label for="telefono">Teléfono: </label>
                        <input type="text" v-model="telefono" v-bind:class="class_telefono">
                        <p>{{mensaje_telefono}}</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="cod_area_cel">Código de área Cel: </label>
                        <input v-bind:class="class_cod_area_cel" name="cod_area_cel" placeholder="Sin Ceros" v-model="cod_area_cel">
                        <p>{{mensaje_cod_area_cel}}</p>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="prefijo">Prefijo</label>
                        <input type="text" name="prefijo" v-bind:value="15" v-bind:class="class_prefijo" disabled>
                    </div>
                    <div class="form-group col-md-7">
                        <label for="celular">Celular: </label>
                        <input type="text" v-model="celular"v-bind:class="class_celular">
                        <p>{{mensaje_celular}}</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="email">Correo Electrónico: </label>
                        <input type="email" v-model="email" v-bind:class="class_email">
                        <p>{{mensaje_email}}</p>
                    </div>
                </div>
                    <p>{{mensaje_cliente}}</p>
                    <button v-bind:class="class_guardar_button_cliente" v-on:click="guardar_cliente">Guardar</button>
                    <button v-bind:class="class_cambiar_button_cliente" v-on:click="cambiar_cliente">Cambiar</button>
                    <button v-bind:class="class_seleccionar_button_cliente" v-on:click="seleccionar_cliente">Seleccionar</button>
        </div>
        <div :class="mostrar_empresa">
            <h4>Empresa:</h4>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <div class="input-group mb-3">
                            <input  type="text"
                                    v-model="id_cliente"
                                    placeholder="ID Genesys"
                                    v-bind:class="class_id_cliente">
                        </div>
                        <p>{{mensaje_id_cliente}}</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="apellido">Razón Social: </label>
                        <input type="text" v-model="apellido" v-bind:class="class_apellido">
                        <p>{{mensaje_apellido}}</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="cod_area_tel">Código de área Tel: </label>
                        <input v-bind:class="class_cod_area_tel" placeholder="Sin Ceros" v-model="cod_area_tel">
                        <p>{{mensaje_cod_area_tel}}</p>
                    </div>
                    <div class="form-group col-md-9">
                        <label for="telefono">Teléfono: </label>
                        <input type="text" v-model="telefono" v-bind:class="class_telefono">
                        <p>{{mensaje_telefono}}</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="cod_area_cel">Código de área Cel: </label>
                        <input v-bind:class="class_cod_area_cel" name="cod_area_cel" placeholder="Sin Ceros" v-model="cod_area_cel">
                        <p>{{mensaje_cod_area_cel}}</p>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="prefijo">Prefijo</label>
                        <input type="text" name="prefijo" v-bind:value="15" v-bind:class="class_prefijo" disabled>
                    </div>
                    <div class="form-group col-md-7">
                        <label for="celular">Celular: </label>
                        <input type="text" v-model="celular"v-bind:class="class_celular">
                        <p>{{mensaje_celular}}</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="email">Correo Electrónico: </label>
                        <input type="email" v-model="email" v-bind:class="class_email">
                        <p>{{mensaje_email}}</p>
                    </div>
                </div>
                    <p>{{mensaje_cliente}}</p>
                    <button v-bind:class="class_guardar_button_cliente" v-on:click="guardar_cliente">Guardar</button>
                    <button v-bind:class="class_cambiar_button_cliente" v-on:click="cambiar_cliente">Cambiar</button>
                    <button v-bind:class="class_seleccionar_button_cliente" v-on:click="seleccionar_cliente">Seleccionar</button>
        </div>
    </div>    
</div>
`,
    data(){
        return{
            cliente_original: '',
            error_cliente: {
                'id_cliente': true,
                'nombre': true,
                'apellido': true,
                'cod_area_tel': true,
                'telefono': false,
                'cod_area_cel': true,
                'celular': true,
                'email': false,
            },
            mensaje_id_cliente: '',
            mensaje_nombre: '',
            mensaje_apellido: '',
            mensaje_cod_area_tel: '',
            mensaje_telefono: '',
            mensaje_cod_area_cel: '',
            mensaje_celular: '',
            mensaje_email: '',
            mensaje_cliente: 'Completar Formulario ',
            nombre: '',
            apellido: '',
            cod_area_tel: '2964',
            telefono: '',
            cod_area_cel: '2964',
            celular: '',
            email: '',
            id_cod_area_tel: '',
            id_cod_area_cel: '',
            class_id_cliente: 'form-control',
            class_nombre: 'form-control',
            class_apellido: 'form-control',
            class_cod_area_tel: 'form-control',
            class_telefono: 'form-control',
            class_cod_area_cel: 'form-control',
            class_prefijo: 'form-control',
            class_celular: 'form-control',
            class_email: 'form-control',
            class_seleccionar_button_cliente: 'ocultar',
            class_guardar_button_cliente: 'ocultar',
            class_cambiar_button_cliente: 'ocultar',
            mostrar_cliente: '',
            mostrar_empresa: 'ocultar',

        }
    },
    mounted(){
        if(this.id_cliente != null) {
            this.buscarCliente();
            this.checkError_cliente();
        }
    },
    beforeUpdate() {
        this.esempresa ? (this.mostrar_empresa = '') : (this.mostrar_empresa = 'ocultar');
        this.esempresa ? (this.mostrar_cliente = 'ocultar') : (this.mostrar_cliente = '');
    },
    computed: {
        id_cliente: {
            get: function () {
                return store.state.id_cliente;
            },
            set: function (newVal) {
                store.state.id_cliente = newVal;
            }
        },
        esempresa: {
            get: function () {
                return store.state.esempresa;
            },
            set: function (newVal) {
                store.state.esempresa = newVal;
            }
        },
        class_cliente_seleccionado: {
            get: function () {
                if (store.state.formulario_cliente){
                    return 'ocultar';
                } else {
                    return '';
                }
            }
        },
        class_formulario_cliente: {
            get: function () {
                if(store.state.formulario_cliente){
                    return '';
                } else {
                    return 'ocultar';
                }
            },
            set: function (newVal) {
                store.state.formulario_cliente = newVal;
            }
        }
    },
    watch: {
        esempresa: function () {
            this.checkError_cliente();
        },
        id_cliente: function () {
            this.checkId_cliente();
        },
        cod_area_tel: function () {
            this.checkCod_area_tel();
            this.checkTelefono();
            this.checkError_cliente();
        },
        cod_area_cel: function () {
            this.checkCod_area_cel();
            this.checkCelular();
            this.checkError_cliente();
        },
        nombre: function () {
            this.checkNombre();
            this.checkError_cliente();
        },
        apellido: function () {
            this.checkApellido();
            this.checkError_cliente();
        },
        telefono: function () {
            this.checkTelefono();
            this.checkError_cliente();
        },
        celular: function () {
            this.checkCelular();
            this.checkError_cliente();
        },
        email: function () {
            this.checkEmail();
            this.checkError_cliente();
        }
    },
    methods: {
        buscarCliente: function () {
            fetch('http://' + website + '/Cliente/' + this.id_cliente)
                .then(response => response.json())
                .then(data => {
                    this.cliente_original = data;
                    if (data.id === undefined) {
                        this.mensaje_id_cliente = 'Cliente no encontrado.';
                        this.class_id_cliente = 'form-control is-invalid';
                        this.nombre = '';
                        this.apellido = '';
                        this.cod_area_tel = '2964';
                        this.cod_area_cel = '2964';
                        this.telefono = '';
                        this.celular = '';
                        this.email = '';
                        this.error_cliente.id_cliente = true;
                    } else {
                        this.class_id_cliente = 'form-control is-valid';
                        this.nombre = data.nombre;
                        this.apellido = data.apellido;
                        this.cod_area_tel = (null != data.cod_area_tel) ? data.cod_area_tel.codigoDeArea : '2964';
                        this.telefono = (null != data.telefono) ? data.telefono : '';
                        this.cod_area_cel = (null != data.cod_area_cel) ? data.cod_area_cel.codigoDeArea : '2964';
                        this.celular = (null != data.celular) ? data.celular : '',
                        this.esempresa = data.es_empresa ? true : false;
                        this.email = data.email;
                        this.mensaje_id_cliente = '';
                        this.error_cliente.id_cliente = false;
                        if (store.state.formulario_cliente){
                            this.class_formulario_cliente = true;
                        }else {
                            this.class_formulario_cliente = false;
                            this.seleccionar_cliente();
                        }
                    };
                });
        },
        checkTelefono() {
            let longitud = this.cod_area_tel.length + this.telefono.length;
            let maxlong = 10 - this.cod_area_tel.length;
            const regex_numerico = /^[0-9]*$/;
            if ((longitud == 10 || longitud == this.cod_area_tel.length) && regex_numerico.test(this.telefono)) {
                this.mensaje_telefono = '';
                this.class_telefono = 'form-control is-valid';
                this.error_cliente.telefono = false;
                return true;
            } else {
                this.mensaje_telefono = 'Min:0, max: ' + maxlong + ', solo números.';
                this.class_telefono = 'form-control is-invalid';
                this.error_cliente.telefono = true;
                return false;
            }
        },
        checkCod_area_tel() {
            if (this.cod_area_tel) {
                fetch('http://' + website + '/CodigoDeArea/Codigo/' + this.cod_area_tel)
                    .then(response => response.json())
                    .then(data => {
                        if (data.id === undefined) {
                            this.class_cod_area_tel = 'form-control is-invalid';
                            this.mensaje_cod_area_tel = '';
                            this.id_cod_area_tel = '';
                            this.error_cliente.cod_area_tel = true;
                            return false;
                        }
                        else {
                            this.class_cod_area_tel = 'form-control is-valid';
                            this.mensaje_cod_area_tel = data.provincia;
                            this.id_cod_area_tel = data.id;
                            this.error_cliente.cod_area_tel = false;
                            return true;
                        }
                    })
            }
            else {
                this.class_cod_area_tel = 'form-control';
                this.mensaje_cod_area_tel = '';
                this.error_cliente.cod_area_tel = true;
                return false;
            }
        },
        checkCod_area_cel() {
            if (this.cod_area_cel) {
                fetch('http://' + website + '/CodigoDeArea/Codigo/' + this.cod_area_cel)
                    .then(response => response.json())
                    .then(data => {
                        if (data.id === undefined) {
                            this.class_cod_area_cel = 'form-control is-invalid';
                            this.mensaje_cod_area_cel = '';
                            this.id_cod_area_cel = '';
                            this.error_cliente.cod_area_cel = true;
                            return false;
                        }
                        else {
                            this.class_cod_area_cel = 'form-control is-valid';
                            this.mensaje_cod_area_cel = data.provincia;
                            this.id_cod_area_cel = data.id;
                            this.error_cliente.cod_area_cel = false;
                            return true;
                        }
                    })
            }
            else {
                this.class_cod_area_cel = 'form-control is-invalid';
                this.mensaje_cod_area_cel = '';
                this.error_cliente.area_cel = true;
                return false;
            }
        },
        checkCelular() {
            let longitud = this.cod_area_cel.length + this.celular.length;
            let maxlong = 10 - this.cod_area_cel.length;
            const regex_numerico = /^[0-9]*$/;
            if ((longitud == 10 && regex_numerico.test(this.celular) && !this.esempresa) ||
                ((longitud == 10 || longitud == this.cod_area_cel.length) && regex_numerico.test(this.celular) && this.esempresa)) {
                this.mensaje_celular = '';
                this.class_celular = 'form-control is-valid';
                this.error_cliente.celular = false;
                return true;
            } else {
                this.mensaje_celular = 'Max: ' + maxlong + ', solo números.';
                this.class_celular = 'form-control is-invalid';
                this.error_cliente.celular = true;
                return true;
            }
        },
        checkEmail() {
            const regex_email = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
            if (regex_email.test(this.email) || this.email.length == 0) {
                this.mensaje_email = '';
                this.class_email = 'form-control is-valid';
                this.error_cliente.email = false;
                return true;
            } else {
                this.mensaje_email = 'Email con formato incorrecto.'
                this.class_email = 'form-control is-invalid';
                this.error_cliente.email = true;
                return false;
            }
        },
        checkNombre() {
            if (this.nombre.length < 3 || this.nombre.length > 45) {
                this.mensaje_nombre = 'Nombre: min 3, max 45 caracteres.';
                this.class_nombre = 'form-control is-invalid';
                this.error_cliente.nombre = true;
                return false;
            } else {
                this.mensaje_nombre = '';
                this.class_nombre = 'form-control is-valid';
                this.error_cliente.nombre = false;
                return true;
            }
        },
        checkApellido() {
            if (this.apellido.length < 3 || this.apellido.length > 45) {
                this.mensaje_apellido = 'Apellido: min 3, max 45 caracteres.';
                this.class_apellido = 'form-control is-invalid';
                this.error_cliente.apellido = true;
                return false;
            } else {
                this.mensaje_apellido = '';
                this.class_apellido = 'form-control is-valid';
                this.error_cliente.apellido = false;
                return true;
            }
        },
        checkId_cliente() {
            const regex_numerico = /^[0-9]*$/;
            if (this.id_cliente > 5 && regex_numerico.test(this.id_cliente)) {
                this.buscarCliente();
            } else {
                this.mensaje_id_cliente = 'Max= 99999. Solo números'
            }
        },
        checkError_cliente() {
            this.class_seleccionar_button_cliente = 'ocultar';
            if ((this.error_cliente.nombre && !this.esempresa) || this.error_cliente.apellido || this.error_cliente.telefono || this.error_cliente.celular || this.error_cliente.email) {
                this.mensaje_cliente = 'Completar los datos';
                this.class_guardar_button_cliente = 'ocultar';
                this.class_cambiar_button_cliente = 'ocultar';
            }
            else if (this.error_cliente.id_cliente) {
                this.mensaje_cliente = '';
                this.class_guardar_button_cliente = 'btn btn-primary';
                this.class_cambiar_button_cliente = 'ocultar';
            } else {
                this.mensaje_cliente = '';
                if (this.es_igual_original()) {
                    this.class_guardar_button_cliente = 'ocultar';
                    this.class_cambiar_button_cliente = 'ocultar';
                    this.class_seleccionar_button_cliente = 'btn btn-primary';
                } else {
                    this.class_cambiar_button_cliente = 'btn btn-primary';
                    this.class_guardar_button_cliente = 'ocultar';
                }
            }
        },
        es_igual_original() {
            if (this.cliente_original.cod_area_tel == null) {
                this.cliente_original.cod_area_tel = { codigoDeArea: '2964' };
            }
            if (this.cliente_original.cod_area_cel == null) {
                this.cliente_original.cod_area_cel = { codigoDeArea: '2964' };
            }
            if (
                this.cliente_original.id == this.id_cliente &&
                this.cliente_original.nombre == this.nombre &&
                this.cliente_original.apellido == this.apellido &&
                this.cliente_original.cod_area_tel.codigoDeArea == this.cod_area_tel &&
                this.cliente_original.telefono == this.telefono &&
                this.cliente_original.cod_area_cel.codigoDeArea == this.cod_area_cel &&
                this.cliente_original.celular == this.celular &&
                this.cliente_original.email == this.email &&
                this.cliente_original.es_empresa == this.esempresa
            ) {
                return true;
            } else {
                return false;
            }
        },
        guardar_cliente() {
            this.mensaje_cliente = 'Guardando Cliente...';
            const url = 'http://' + website + '/CodigoDeArea/Codigo/' +  this.cod_area_tel;
            this.get_fetch_api(url, this.guardar_cliente_1);
        },
        guardar_cliente_1(data) {
            this.id_cod_area_tel = data.id;
            const url = 'http://' + website + '/CodigoDeArea/Codigo/' + this.cod_area_cel;
            this.get_fetch_api(url, this.guardar_cliente_2);
        },
        guardar_cliente_2(data) {
            const url = 'http://' + website + '/Cliente';
            const metodo = 'post';
            const datos = {  id: this.id_cliente,
                            nombre: this.nombre,
                            apellido: this.apellido,
                            cod_area_tel: this.id_cod_area_tel,
                            telefono: this.telefono,
                            cod_area_cel: data.id,
                            celular: this.celular,
                            email: this.email,
                            es_empresa: this.esempresa
                        };
            this.fetch_api(url, metodo, datos, this.guardar_cliente_3);
        },
        guardar_cliente_3(data){
            if (typeof(data) == 'object'){
                this.mensaje_cliente = (data.message)
            }else if (data === true) {
                this.error_cliente.id_cliente = false;
                this.mensaje_cliente = 'Cliente guardado.';
                this.class_seleccionar_button_cliente = 'btn btn-primary';
                this.class_guardar_button_cliente = 'ocultar';
                this.class_cambiar_button_cliente = 'ocultar';
                this.class_id_cliente = 'form-control is-valid';
                this.mensaje_id_cliente = '';
            }else {
                this.mensaje_cliente = 'Algo salió mal...';
                console.log('Algo salió mal...' + data);
            }
        },
        fetch_api(url, metodo, data, callback){
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(url, {
                method: metodo,
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json, text-plain, */*",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": token
                },
                body: JSON.stringify(data),
            })
                .then(response => response.json())
                .then(data => {
                    callback(data);
                })
                .catch((error) => {
                    console.error('FETCH Volvió con Error:', error);
                })
        },
        get_fetch_api(url, callback){
            fetch(url)
                .then(response => response.json())
                .then(data => { callback(data)})
                .catch((error) => {
                    console.error('Error:', error);
                })
        },
        cambiar_cliente() {
            //modificar nuevo cliente
            /* this.mensaje_cliente = 'Cliente modificado.';
            this.class_seleccionar_button_cliente = 'btn btn-primary';
            this.class_guardar_button_cliente = 'ocultar';
            this.class_cambiar_button_cliente = 'ocultar'; */
            this.mensaje_cliente = 'Cambiando Cliente...';
            const url = 'http://' + website + '/CodigoDeArea/Codigo/' + this.cod_area_tel;
            this.get_fetch_api(url, this.cambiar_cliente_1);
        },
        cambiar_cliente_1(data) {
            this.id_cod_area_tel = data.id;
            const url = 'http://' + website + '/CodigoDeArea/Codigo/' + this.cod_area_cel;
            this.get_fetch_api(url, this.cambiar_cliente_2);
        },
        cambiar_cliente_2(data) {
            const url = 'http://' + website + '/Cliente';
            const metodo = 'patch';
            const datos = {
                id: this.id_cliente,
                nombre: this.nombre,
                apellido: this.apellido,
                cod_area_tel: this.id_cod_area_tel,
                telefono: this.telefono,
                cod_area_cel: data.id,
                celular: this.celular,
                email: this.email,
                es_empresa: this.esempresa
            };
            this.fetch_api(url, metodo, datos, this.cambiar_cliente_3);
        },
        cambiar_cliente_3(data) {
            if (typeof (data) == 'object') {
                this.mensaje_cliente = (data.message)
            } else if (data === true) {
                this.error_cliente.id_cliente = false;
                this.mensaje_cliente = 'Cliente cambiado.';
                this.class_seleccionar_button_cliente = 'btn btn-primary';
                this.class_guardar_button_cliente = 'ocultar';
                this.class_cambiar_button_cliente = 'ocultar';
                this.class_id_cliente = 'form-control is-valid';
                this.mensaje_id_cliente = '';
            } else {
                this.mensaje_cliente = 'Algo salió mal...';
                console.log('Algo salió mal...' + data);
            }
        },
        seleccionar_cliente() {
            this.mensaje_cliente = 'Seleccionado...';
            const url = 'http://' + website + '/Session';
            const metodo = 'put';
            const datos = {datos: {
                                alta_contrato_id_cliente: this.id_cliente,
                                }
            };
            this.fetch_api(url, metodo, datos, this.seleccionar_cliente_1);
        },
        seleccionar_cliente_1(data) {
            if (typeof (data) == 'object') {
                this.mensaje_cliente = (data.message)
            } else if (data === true) {
                this.mensaje_cliente = '';
                this.class_formulario_cliente = false;
                store.state.div_tipoContrato = true;
            } else {
                this.mensaje_cliente = 'Algo salió mal...';
                console.log('Algo salió mal...' + data);
            }
        },
        deseleccionar_cliente() {
            this.class_formulario_cliente = true;
            store.state.div_tipoContrato = false;
        }
    }
})