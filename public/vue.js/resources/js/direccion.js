Vue.component('direccion', {
    template: //html
    `
    <div :class="class_div_tipoContrato">
        <div class="container alert alert-info mx-auto m-2 p-2" :class="class_direccion_seleccionada" role="alert">
            <div class="row justify-content-between">
                <div class="col-11 pr-2">
                <h4>{{nombre_calle}} | {{numero}} | {{barrio}}</h4></div>
                <div class="col-1 pl-2">
                    <button type="button" class="btn btn-secondary" @click="deseleccionar_direccion">Editar</button>
                </div>
            </div>
        </div>

        <div class="alert bg-light border col-8 mx-auto p-4" :class="class_formulario_direccion">
            <h4 class="text-aling-center">Dirección:</h4>
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="calle">Calle: </label>
                    <input type="text" v-model="nombre_calle" :class="class_calle" id="calleSearch">
                    <p>{{mensaje_calle}}</p>
                </div>
                <div class="form-group col-md-4">
                    <label for="numero">Altura: </label>
                    <input type="text" v-model="numero" value="" :class="class_numero">
                    <p>{{mensaje_numero}}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="entrecalle_1">Entrecalle 1: </label>
                    <input type="text" v-model="entrecalle1" :class="class_entrecalle1" id="entrecalle1Search">
                    <p>{{mensaje_entrecalle1}}</p>
                </div>
                <div class="form-group col-md-6">
                    <label for="entrecalle2">Entrecalle 2: </label>
                    <input type="text" v-model="entrecalle2" :class="class_entrecalle2" id="entrecalle2Search">
                    <p>{{mensaje_entrecalle2}}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="barrio">Barrio: </label>
                    <input type="text" v-model="barrio" :class="class_barrio" id="barrioSearch">
                    <p>{{mensaje_barrio}}</p>
                </div>
                <div class="form-group col-md-4">
                    <label for="ciudad">Ciudad: </label>
                    <input type="text" v-model="ciudad" :class="class_ciudad" id="ciudadSearch">
                    <p>{{mensaje_ciudad}}</p>
                </div>
                <div class="form-group col-md-4">
                    <label for="coordenadas">Coordenadas: </label>
                    <input type="text" v-model="coordenadas" :class="class_coordenadas">
                    <p>{{mensaje_coordenadas}}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="comentarios">Comentarios de la dirección: </label>
                    <textarea v-model="comentarios" :class="class_comentarios" rows="auto" cols="50"></textarea>
                    <p>{{mensaje_comentarios}}</p>
                </div>
            </div>
            <p>{{mensaje_direccion}}</p>
            <button class="btn btn-primary" :class="class_guardar_button_direccion" v-on:click="guardar_direccion">Guardar</button>
            <button class="btn btn-primary" :class="class_cambiar_button_direccion" v-on:click="cambiar_direccion">Modificar</button>
            <button class="btn btn-primary" :class="class_seleccionar_button_direccion" v-on:click="seleccionar_direccion">Seleccionar</button>
        </div>
    </div>
    `,
    data() {
        return {
            class_guardar_button_direccion: 'ocultar',
            class_cambiar_button_direccion: 'ocultar',
            class_seleccionar_button_direccion: 'ocultar',
            class_calle: 'form-control',
            class_numero: 'form-control',
            class_entrecalle1: 'form-control',
            class_entrecalle2: 'form-control',
            class_barrio: 'form-control',
            class_ciudad: 'form-control',
            class_coordenadas: 'form-control',
            class_comentarios: 'form-control',
            direccion_ori: '',
            nombre_calle_id: '',
            nombre_calle: '',
            numero: '',
            entrecalle1_id: '',
            entrecalle1: '',
            entrecalle2: '',
            entrecalle2_id: '',
            barrio_id: '',
            barrio: '',
            ciudad_id: '1',
            ciudad: 'Rio Grande',
            coordenadas: '',
            comentarios: '',
            mensaje_calle: '',
            mensaje_numero: '',
            mensaje_entrecalle1: '',
            mensaje_entrecalle2: '',
            mensaje_barrio: '',
            mensaje_ciudad: '',
            mensaje_direccion: '',
            mensaje_coordenadas: '',
            mensaje_comentarios: '',
            datosArray_calle: [],
            datosArray_calle_ori: [],
            datosArray_barrio: [],
            datosArray_barrio_ori: [],
            datosArray_ciudad: [],
            datosArray_ciudad_ori: [],
            optionsFinal: [],
            optionsFinalCiudad: [],
            optionsFinalBarrio: [],
            error_direccion: {
                'buscar_direccion': true,
                'id_direccion': false,
                'nombre_calle': false,
                'numero': false,
                'entrecalle1': true,
                'entrecalle2': true,
                'barrio': false,
                'ciudad': true,
                'coordenadas': true,
                'comentarios': true
            },
        }
    },
    computed: {
        buscar_inicial:{
            get:function () {
                return store.state.direccion_buscar_inicial;
            },
            set:function (newVal) {
                store.state.direccion_buscar_inicial = newVal;
            }
        },
        id_direccion:{
            get:function(){
                return store.state.id_direccion;
            },
            set: function (newVal){
                store.state.id_direccion = newVal;
            }
        },
        class_direccion_seleccionada: {
            get: function (){
                return (store.state.formulario_direccion ? 'ocultar' : '');
                /* if (store.state.formulario_direccion)
                {
                    return 'ocultar';
                } else {
                    return '';
                } */
            }
        },
        class_formulario_direccion:{
            get: function() {
                return (store.state.formulario_direccion ? '' : 'ocultar'); 
                /* if (store.state.formulario_direccion) {
                    return '';
                } else {
                    return 'ocultar';
                } */
            },
            set: function (newVal) {
                store.state.formulario_direccion = newVal;
            }
        },
        class_div_tipoContrato: {
            get: function () {
                if (store.state.div_tipoContrato) {
                    return '';
                } else {
                    return 'ocultar';
                }
            }
        } 
    },
    mounted() {
        let that = this;
        fetch('/searchCalles')
            .then(valor => valor.json())
            .then(valor => {
                valor.forEach(element => {
                    this.datosArray_calle.push(element.nombre.replace(/[\r\n]+/gm, ""));
                    this.datosArray_calle_ori.push(element);
                });
            });
        optionsFinal = { adjustWidth: false, data: this.datosArray_calle, 
                        list: { 
                                maxNumberOfElements: 10, 
                                match: { enabled: true },
                                onSelectItemEvent: function () {
                                    let value_nombre_calle = $("#calleSearch").getSelectedItemData();
                                    that.nombre_calle = value_nombre_calle == '-1' ? that.nombre_calle : value_nombre_calle;
                                    let value_entrecalle1 = $("#entrecalle1Search").getSelectedItemData();
                                    that.entrecalle1 = value_entrecalle1 == '-1' ? that.entrecalle1 : value_entrecalle1;
                                    let value_entrecalle2 = $("#entrecalle2Search").getSelectedItemData();
                                    that.entrecalle2 = value_entrecalle2 == '-1' ? that.entrecalle2 : value_entrecalle2;
                                }
                            } 
                        };
        $("#calleSearch").easyAutocomplete(optionsFinal);
        $("#entrecalle1Search").easyAutocomplete(optionsFinal);
        $("#entrecalle2Search").easyAutocomplete(optionsFinal);
        fetch('/searchBarrios')
            .then(valor => valor.json())
            .then(valor => {
                valor.forEach(element => {
                    this.datosArray_barrio.push(element.nombre.replace(/[\r\n]+/gm, ""));
                    this.datosArray_barrio_ori.push(element);
                });
            });
        optionsFinalBarrio = {
            adjustWidth: false, data: this.datosArray_barrio,
            list: {
                maxNumberOfElements: 10,
                match: { enabled: true },
                onSelectItemEvent: function () {
                    let value_barrio = $("#barrioSearch").getSelectedItemData();
                    that.barrio = value_barrio;
                }
            }
        };
        $("#barrioSearch").easyAutocomplete(optionsFinalBarrio);
        fetch('/searchCiudades')
            .then(valor => valor.json())
            .then(valor => {
                valor.forEach(element => {
                    this.datosArray_ciudad.push(element.nombre.replace(/[\r\n]+/gm, ""));
                    this.datosArray_ciudad_ori.push(element);
                });
            });
        optionsFinalciudad = {
            adjustWidth: false, data: this.datosArray_ciudad,
            list: {
                maxNumberOfElements: 10,
                match: { enabled: true },
                onSelectItemEvent: function () {
                    let value_ciudad = $("#ciudadSearch").getSelectedItemData();
                    that.ciudad = value_ciudad;
                }
            }
        };
        $("#ciudadSearch").easyAutocomplete(optionsFinalciudad);
    },
    watch: {
        id_direccion: function () {
            if (this.buscar_inicial) {
                this.buscarDireccion(true);
                this.validar_error_direccion();
            }
        },
        nombre_calle: function () {
            this.error_direccion.buscar_direccion = true;
            this.validar_calle();
            this.validar_error_direccion();
        },
        entrecalle1: function () {
            this.validar_entrecalle1();
            this.validar_error_direccion();
        },
        entrecalle2: function () {
            this.validar_entrecalle2();
            this.validar_error_direccion();
        },
        numero: function () {
            this.error_direccion.buscar_direccion = true;
            this.validar_numero();
            this.validar_error_direccion();
        },
        barrio: function () {
            this.validar_barrio();
            this.validar_error_direccion();
        },
        ciudad: function () {
            this.validar_ciudad();
            this.validar_error_direccion();
        },
        coordenadas: function () {
            this.validar_coordenadas();
            this.validar_error_direccion();
        },
        comentarios: function () {
            this.validar_comentarios();
            this.validar_error_direccion();
        }
    },
    methods: {
        validar_comentarios() {
            let longitud = this.comentarios.length;
            if ((longitud < 501 && longitud > 2) || longitud === 0) {
                this.class_comentarios = 'form-control is-valid';
                this.mensaje_comentarios = 'Max: 500. Caracteres: ' + longitud;
                this.error_direccion.comentarios = true;
            }else {
                this.class_comentarios = 'form-control is-invalid';
                this.mensaje_comentarios = 'Min: 3. Max: 500.';
                this.error_direccion.comentarios = false;
            }
        },
        validar_coordenadas() {
            let regex = /(^[-+]?(?:[1-8]?\d(?:\.\d+)?|90(?:\.0+)?)),\s*([-+]?(?:180(?:\.0+)?|(?:(?:1[0-7]\d)|(?:[1-9]?\d))(?:\.\d+)?))$/ ;
            if (regex.test(this.coordenadas) || !this.coordenadas.length) {
                this.class_coordenadas = 'form-control is-valid';
                this.mensaje_coordenadas = 'No Obligatorio';
                this.error_direccion.coordenadas = true;
            }else {
                this.class_coordenadas = 'form-control is-invalid';
                this.mensaje_coordenadas = 'formato o caracteres no válido';
                this.error_direccion.coordenadas = false;
            }
        },
        buscarDireccion(porId){
            let url_consulta;
            this.error_direccion.buscar_direccion = false;
            if (porId) {
                url_consulta = '/searchIdDireccion/' + this.id_direccion;
                this.buscar_inicial = false;
            }else {
                url_consulta = '/searchDireccion/' + this.nombre_calle_id + '/' + this.numero;
            }
            fetch(url_consulta)
                .then(valor => valor.json())
                .then(valor => {
                    if (valor.id === undefined){
                        this.mensaje_direccion = 'No se encontró dirección';
                        this.id_direccion = '';
                        this.error_direccion.id_direccion = false;
                    } else {
                        this.error_direccion.id_direccion = true;
                        this.mensaje_direccion = 'Direccion encontrada';
                        this.direccion_ori = valor;
                        this.id_direccion = this.direccion_ori.id = valor.id;
                        this.nombre_calle = this.direccion_ori.nombre_calle = valor.nombre_calle.replace(/[\r\n]+/gm, "");
                        this.numero = this.direccion_ori.numero = valor.numero.toString();
                        this.entrecalle1 = this.direccion_ori.entrecalle1 = (null === valor.entrecalle1) ? '' : valor.entrecalle1.replace(/[\r\n]+/gm, "");
                        this.entrecalle2 = this.direccion_ori.entrecalle2 = null === valor.entrecalle2 ? '' : valor.entrecalle2.replace(/[\r\n]+/gm, "");
                        this.barrio = this.direccion_ori.barrio = valor.barrio.replace(/[\r\n]+/gm, "");
                        this.ciudad = this.direccion_ori.ciudad = valor.ciudad.replace(/[\r\n]+/gm, "");
                        this.coordenadas = this.direccion_ori.coordenadas = (null === valor.coordenadas ? '' : valor.coordenadas);
                        this.comentarios = this.direccion_ori.comentarios = null === valor.comentarios ? '' : valor.comentarios;
                    }
                });
        },
        checkErrores() {
            if  (
                    !this.error_direccion.buscar_direccion &&
                    this.error_direccion.nombre_calle &&
                    this.error_direccion.numero &&
                    this.error_direccion.entrecalle1 &&
                    this.error_direccion.entrecalle2 &&
                    this.error_direccion.barrio &&
                    this.error_direccion.ciudad &&
                    this.error_direccion.coordenadas &&
                    this.error_direccion.comentarios
                ) {
                    return true;
            }
            return false;
        },
        es_igual_original(reducido) {
            let rta;
            if  (
                    this.id_direccion == this.direccion_ori.id &&
                    this.nombre_calle == this.direccion_ori.nombre_calle &&
                    this.numero == this.direccion_ori.numero
                ) {
                    if (reducido) {
                        return true;
                    } else {
                        rta = true;
                    }
                } else {
                    return false;
                }

            if (
                    rta &&
                    this.entrecalle1 === this.direccion_ori.entrecalle1 &&
                    this.entrecalle2 === this.direccion_ori.entrecalle2 &&
                    this.barrio === this.direccion_ori.barrio &&
                    this.ciudad === this.direccion_ori.ciudad &&
                    this.coordenadas === this.direccion_ori.coordenadas &&
                    this.comentarios === this.direccion_ori.comentarios
            ) {
                return true;
            } else {
                return false;
            }
        },
        validar_error_direccion () {
            if (this.error_direccion.nombre_calle && this.error_direccion.numero && this.error_direccion.buscar_direccion) {
                this.buscarDireccion();
            }else if (this.checkErrores() ) {
                if (this.es_igual_original()) {
                    /* Seleccionar */
                    this.mensaje_direccion = 'Datos OK';
                    this.class_seleccionar_button_direccion = '';
                    this.class_cambiar_button_direccion = 'ocultar';
                    this.class_guardar_button_direccion = 'ocultar';
                } else if (this.es_igual_original(true)) {
                    /* Cambiar */
                    this.mensaje_direccion = 'Datos OK';
                    this.class_cambiar_button_direccion = '';
                    this.class_guardar_button_direccion = 'ocultar';
                    this.class_seleccionar_button_direccion = 'ocultar';
                } else {
                    /* Guardar */
                    this.mensaje_direccion = 'Datos OK';
                    this.class_guardar_button_direccion = '';
                    this.class_cambiar_button_direccion = 'ocultar';
                    this.class_seleccionar_button_direccion = 'ocultar';
                }
            } 
            else {
                    this.mensaje_direccion = 'Completar formulario para continuar.';
            }
        },
        validar_numero() {
            const regex_numerico = /^[0-9]*$/;
            if (this.numero.length < 6 && this.numero.length > 0 && regex_numerico.test(this.numero)) {
                this.class_numero = 'form-control is-valid';
                this.mensaje_numero = '';
                this.error_direccion.numero = true;
            } else {
                this.class_numero = 'form-control is-invalid',
                this.mensaje_numero = 'Solo número (1-99999)';
                this.error_direccion.numero = false;
            }
        },
        buscar_en_array(candidato, array) {
            let match_id = false;
            array.forEach(element => {
                if (candidato == element.nombre.replace(/[\r\n]+/gm, ""))
                {
                    match_id = element.id;
                }
            });
            return match_id ? match_id : false ;
        },
        validar_ciudad() {
            if (this.ciudad_id = this.buscar_en_array(this.ciudad, this.datosArray_ciudad_ori)) {
                this.class_ciudad = 'form-control is-valid';
                this.mensaje_ciudad = '';
                this.error_direccion.ciudad = true;
            } else {
                this.class_ciudad = 'form-control is-invalid';
                this.mensaje_ciudad = 'No existe la ciudad';
                this.error_direccion.ciudad = false;
            }
            if (!this.ciudad) {
                this.class_ciudad = 'form-control is-invalid';
                this.mensaje_ciudad = 'Ciudad es Obligatorio';
                this.error_direccion.ciudad = false;
            }
        },
        validar_barrio() {
            if (this.barrio_id = this.buscar_en_array(this.barrio, this.datosArray_barrio_ori)) {
                this.class_barrio = 'form-control is-valid';
                this.mensaje_barrio = '';
                this.error_direccion.barrio = true;
                this.validar_ciudad();
            } else {
                this.class_barrio = 'form-control is-invalid';
                this.mensaje_barrio = 'No existe el barrio';
                this.error_direccion.barrio = false;
            }
            if (!this.barrio) {
                this.class_barrio = 'form-control is-invalid';
                this.mensaje_barrio = 'Barrio es Obligatorio';
                this.error_direccion.barrio = false;
            }
        },
        validar_calle() {
            if (this.nombre_calle_id = this.buscar_en_array(this.nombre_calle, this.datosArray_calle_ori)) {
                this.class_calle = 'form-control is-valid';
                this.mensaje_calle = '';
                this.error_direccion.nombre_calle = true;
            } else {
                this.class_calle = 'form-control is-invalid';
                this.mensaje_calle = 'No existe la calle';
                this.error_direccion.nombre_calle = false;
            }
            if (!this.nombre_calle) {
                this.class_calle = 'form-control is-invalid';
                this.mensaje_calle = 'Calle es Obligatorio';
                this.error_direccion.nombre_calle = false;
            }
        },
        validar_entrecalle1() {
            if (this.entrecalle1_id = this.buscar_en_array(this.entrecalle1, this.datosArray_calle_ori)) {
                this.class_entrecalle1 = 'form-control is-valid';
                this.mensaje_entrecalle1 = '';
                this.error_direccion.entrecalle1 = true;
            } else {
                this.class_entrecalle1 = 'form-control is-invalid';
                this.mensaje_entrecalle1 = 'No existe la calle';
                this.error_direccion.entrecalle1 = false;
            }
            if (!this.entrecalle1) {
                this.class_entrecalle1 = 'form-control is-valid';
                this.mensaje_entrecalle1 = '';
                this.error_direccion.entrecalle1 = true;
            }
        },
        validar_entrecalle2() {
            if (this.entrecalle2_id = this.buscar_en_array(this.entrecalle2, this.datosArray_calle_ori)) {
                this.class_entrecalle2 = 'form-control is-valid';
                this.mensaje_entrecalle2 = '';
                this.error_direccion.entrecalle2 = true;
            } else {
                this.class_entrecalle2 = 'form-control is-invalid';
                this.mensaje_entrecalle2 = 'No existe la calle';
                this.error_direccion.entrecalle2 = false;
            }
            if (!this.entrecalle2) {
                this.class_entrecalle2 = 'form-control is-valid';
                this.mensaje_entrecalle2 = '';
                this.error_direccion.entrecalle2 = true;
            }
        },
        guardar_direccion(){
            const url = 'http://' + website + '/direccion';
            const metodo = 'post';
            const datos = {
                id: this.id_direccion,
                id_calle: this.nombre_calle_id,
                numero: this.numero,
                entrecalle_1: this.entrecalle1_id,
                entrecalle_2: this.entrecalle2_id,
                id_barrio: this.barrio_id,
                id_ciudad: this.ciudad_id,
                coordenadas: this.coordenadas,
                comentarios: this.comentarios
            };
            this.fetch_api(url, metodo, datos, this.cambiar_direccion_1);   
        },
        cambiar_direccion() {
            const url = 'http://' + website + '/direccion';
            const metodo = 'patch';
            const datos = {
                id: this.id_direccion,
                id_calle: this.nombre_calle_id,
                numero: this.numero,
                entrecalle_1: this.entrecalle1_id,
                entrecalle_2: this.entrecalle2_id,
                id_barrio: this.barrio_id,
                id_ciudad: this.ciudad_id,
                coordenadas: this.coordenadas,
                comentarios: this.comentarios
            };
            this.fetch_api(url, metodo, datos, this.cambiar_direccion_1);   
        },
        cambiar_direccion_1(data) {
            if (typeof (data) == 'object') {
                this.mensaje_direccion = (data.message)
            } else if (data === true || typeof (data) == 'number') {
                this.mensaje_direccion = 'Dirección almacenada.';
                this.class_seleccionar_button_direccion = '';
                this.class_guardar_button_direccion = 'ocultar';
                this.class_cambiar_button_direccion = 'ocultar';
                if (typeof (data) == 'number'){
                    this.id_direccion = data;
                }
            } else {
                this.mensaje_direccion = 'Algo salió mal...';
                console.log('Algo salió mal...' + data);
            }
        },
        seleccionar_direccion() {
            this.mensaje_direccion = 'Seleccionado...';
            const url = 'http://' + website + '/Session';
            const metodo = 'put';
            const datos = {
                datos: {
                    alta_contrato_id_direccion: this.id_direccion,
                }
            };
            this.fetch_api(url, metodo, datos, this.seleccionar_direccion_1);           
        },
        seleccionar_direccion_1(data) {
            if (typeof (data) == 'object') {
                this.mensaje_direccion = (data.message)
            } else if (data === true) {
                this.mensaje_cliente = '';
                this.class_formulario_direccion = false;
                store.state.div_direccionNext = true;
            } else {
                this.mensaje_cliente = 'Algo salió mal...';
                console.log('Algo salió mal...' + data);
            }
        },
        fetch_api(url, metodo, data, callback) {
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
        deseleccionar_direccion() {
            this.class_formulario_direccion = true;
            store.state.div_direccionNext = false;
        }
    }
})