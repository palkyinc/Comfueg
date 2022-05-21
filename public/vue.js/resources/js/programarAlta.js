Vue.component('programaralta', {
    template: //html
        `
        <div>
            <div :class="class_mensajes_postGuardar">
                <ul class="list-group">
                    <li v-for="mensajeok in mensajes.success" class="list-group-item list-group-item-success">{{ mensajeok }}</li>
                    <li v-for="mensajeerror in mensajes.error" class="list-group-item list-group-item-danger">{{ mensajeerror }}</li>
                </ul>
                <a :href="url_volver_altas" class="btn btn-info m-2">Volver Altas</a>
            </div>
            <div :class="class_preGuardar" >
                <h3>Pre-instalación</h3>
                <button class="btn btn-primary m-2" v-on:click="borrar_datos">Borrar Datos</button>
                <a :href="url_volver_altas" class="btn btn-info m-2">Volver Altas</a>
                <div class="container my-4">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header"><h4>Programar Alta en Contrato</h4></div>
                                    <div class="card-body">
                                        <p><strong>Cliente</strong>: {{cliente}}</p>
                                        <p><strong>Direccion</strong>: {{direccion}}</p>
                                        <p><strong>Plan</strong>: {{plan}}</p>
                                        <p><strong>Autor</strong>: <strong>Fecha de alta</strong>: {{fecha_alta}} | <strong>Coordinado</strong>: {{instalacion_fecha}}</p>
                                        <p><strong>Comentarios de la Instalación</strong>: {{comentarios_ins}}</p>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li  :class="class_tipo_instalacion" class="list-group-item">
                                            <p><strong>Tipo de Instalación</strong>: {{tipo_instalacion}}</p>
                                        </li>
                                        <li :class="class_ubiquiti" class="list-group-item">
                                            <p><strong>Equipo</strong>: {{Equipo}}</p>
                                        </li>
                                        <li :class="class_router" class="list-group-item">
                                            <p><strong>Router</strong>: {{Router}}</p>
                                        </li>
                                        <li :class="class_panel" class="list-group-item">
                                            <p><strong>Panel</strong>: {{Panel}}</p>
                                        </li>
                                        <li :class="class_guardar" class="list-group-item">
                                            <button class="btn btn-primary" title="Crear Contrato" v-on:click="guardar_contrato">Crear Contrato</button>
                                        </li>
                                    </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <tipoInstalacion></tipoInstalacion>
                <Equipo></Equipo>
                <Panel></Panel>
            </div>
        </div>
        `,
    data () {
        return {
            class_mensajes_postGuardar: 'ocultar',
            class_preGuardar: '',
            mensajes: []
        }
    },
    mounted() {
        store.dispatch('get_alta_data');
        store.dispatch('get_session_data');
    },
    methods: {
        post_guardar: function (data) {
            console.log(data);
            this.class_mensajes_postGuardar =  '';
            this.class_preGuardar =  'ocultar';
            this.mensajes =  data;
            this.borrar_datos();
        },
        guardar_contrato: function () {
            const url = 'http://' + website + '/guardarContrato';
            const metodo = 'put';
            const datos = {
                alta_id: alta_id,
                num_equipo: this.equipo_id,
                num_panel: this.panel_id,
                router_id: this.router_id,
                tipo: store.state.tipoInstalacion
            };
            fetch_api(url, metodo, datos, this.post_guardar);
        },
        borrar_datos: function () {
            store.dispatch('delete_session_data');
        },
        divs_std: function () {
            switch (this.inst_paso) {
                case 1:
                    this.class_tipo_instalacion = true;
                    this.formulario_equipo = false;
                    this.class_ubiquiti = false;
                    this.formulario_panel = false;
                    this.class_panel = false;
                    this.class_guardar = false;
                    break;
            
                case 2:
                    this.class_tipo_instalacion = false;
                    this.formulario_equipo = true;
                    this.class_ubiquiti = false;
                    this.formulario_panel = false;
                    this.class_panel = false;
                    this.class_guardar = false;
                    break;
            
                case 3:
                    this.class_tipo_instalacion = false;
                    this.formulario_equipo = false;
                    this.class_ubiquiti = true;
                    this.formulario_panel = true;
                    this.class_panel = false;
                    this.class_guardar = false;
                    break;
                
                case 4:
                    this.class_tipo_instalacion = false;
                    this.formulario_equipo = false;
                    this.class_ubiquiti = true;
                    this.formulario_panel = false;
                    this.class_panel = true;
                    this.class_guardar = true;
                    break;
            
                default:
                    break;
            }
        },
        divs_brd: function () {
            switch (this.inst_paso) {
                case 1:
                    this.class_tipo_instalacion = true;
                    this.formulario_equipo = false;
                    this.class_ubiquiti = false;
                    this.class_router = false;
                    this.formulario_panel = false;
                    this.class_panel = false;
                    this.class_guardar = false;
                    break;

                case 2:
                    this.class_tipo_instalacion = false;
                    this.formulario_equipo = true;
                    this.class_ubiquiti = false;
                    this.class_router = false;
                    this.formulario_panel = false;
                    this.class_panel = false;
                    this.class_guardar = false;
                    break;
                
                case 3:
                    store.state.equipo_reset = true;
                    this.class_tipo_instalacion = false;
                    this.formulario_equipo = true;
                    this.class_ubiquiti = true;
                    this.class_router = false;
                    this.formulario_panel = false;
                    this.class_panel = false;
                    this.class_guardar = false;
                    break;

                case 4:
                    this.class_tipo_instalacion = false;
                    this.formulario_equipo = false;
                    this.class_ubiquiti = true;
                    this.class_router = true;
                    this.formulario_panel = true;
                    this.class_panel = false;
                    this.class_guardar = false;
                    break;

                case 5:
                    this.class_tipo_instalacion = false;
                    this.formulario_equipo = false;
                    this.class_ubiquiti = true;
                    this.class_router = true;
                    this.formulario_panel = false;
                    this.class_panel = true;
                    this.class_guardar = true;
                    break;

                default:
                    break;
            }
        },
        divs_rtr: function () {
            switch (this.inst_paso) {
                case 1:
                    this.class_tipo_instalacion = true;
                    this.formulario_equipo = false;
                    this.class_ubiquiti = false;
                    this.class_router = false;
                    this.formulario_panel = false;
                    this.class_panel = false;
                    this.class_guardar = false;
                    break;

                case 2:
                    this.class_tipo_instalacion = false;
                    this.formulario_equipo = true;
                    this.class_ubiquiti = false;
                    this.class_router = false;
                    this.formulario_panel = false;
                    this.class_panel = false;
                    this.class_guardar = false;
                    break;

                case 3:
                    this.class_tipo_instalacion = false;
                    this.formulario_equipo = false;
                    this.class_ubiquiti = false;
                    this.class_router = true;
                    this.formulario_panel = false;
                    this.class_panel = false;
                    this.class_guardar = true;
                    break;

                default:
                    break;
            }
        },

    },
    computed: {
        Router: {
            get: function () {
                return store.state.router_datos;
            }
        },
        Equipo: {
            get: function () {
                return store.state.equipo_datos;
            }
        },
        Panel: {
            get: function () {
                return store.state.panel_datos;
            }
        },
        equipo_id: {
            get: function() {
                return store.state.equipo_id;
            },
            set: function (newVal) {
                store.state.equipo_id = newVal;
            }
        },
        router_id: {
            get: function() {
                return store.state.router_id;
            },
            set: function (newVal) {
                store.state.router_id = newVal;
            }
        },
        panel_id: {
            get: function() {
                return store.state.panel_id;
            },
            set: function (newVal) {
                store.state.panel_id = newVal;
            }
        },
        inst_paso: {
            get: function () {
                return store.state.inst_paso;
            }
        },
        tipo_instalacion: {
            get: function () {
                switch (store.state.tipoInstalacion) {
                    case '1':
                        return 'Standard | Antena como Router';
                        break;
                
                    case '2':
                        return 'Bridge | Contrato con Telefonía IP';
                        break;
                
                    case '3':
                        return 'Solo Router | Router WiFi cliente directo a Nodo';
                        break;
                
                    default:
                        return 'ERROR en tipo de instalación';
                        break;
                }
            }
        },
        url_volver_altas: {
            get: function () {
                return store.state.url_volver_altas;
            }
        },
        cliente: {
            get: function () {
                return store.state.cliente;
            }
        },
        direccion: {
            get: function () {
                return store.state.direccion;
            }
        },
        class_tipo_instalacion: {
            get: function () {
                return !store.state.formulario_tipoInstalacion ? '' : 'ocultar';
            },
            set: function (newVal) {
                store.state.formulario_tipoInstalacion = newVal;
            }
        },
        class_ubiquiti: {
            get: function () {
                return store.state.class_ubiquiti ? '' : 'ocultar';
            },
            set: function (newVal) {
                store.state.class_ubiquiti = newVal;
            }
        },
        formulario_equipo: {
            get: function () {
                return store.state.formulario_equipo;
            },
            set: function (newVal) {
                store.state.formulario_equipo = newVal;
            }
        },
        class_router: {
            get: function () {
                return store.state.class_router ? '' : 'ocultar';
            },
            set: function (newVal) {
                store.state.class_router = newVal;
            }
        },
        class_guardar: {
            get: function () {
                return store.state.class_guardar ? '' : 'ocultar';
            },
            set: function (newVal) {
                store.state.class_guardar = newVal;
            }
        },
        formulario_router: {
            get: function () {
                return store.state.formulario_router;
            },
            set: function (newVal) {
                store.state.formulario_router = newVal;
            }
        },
        formulario_panel: {
            get: function () {
                return store.state.formulario_panel;
            },
            set: function (newVal) {
                store.state.formulario_panel = newVal;
            }
        },
        class_panel: {
            get: function () {
                return store.state.class_panel ? '' : 'ocultar';
            },
            set: function (newVal) {
                store.state.class_panel = newVal;
            }
        },
        plan: {
            get: function () {
                return store.state.plan
            }
        },
        fecha_alta: {
            get: function () {
                return store.state.fecha_alta
            }
        },
        comentarios_ins: {
            get: function () {
                return store.state.comentarios_ins
            }
        },
        instalacion_fecha: {
            get: function () {
                return store.state.instalacion_fecha
            }
        },
    },
    watch: {
        equipo_id: function () {
            store.dispatch('get_equipo_data');
        },
        router_id: function () {
            store.dispatch('get_router_data');
        },
        panel_id: function () {
            store.dispatch('get_panel_data');
        },
        inst_paso: function () {
            switch (store.state.tipoInstalacion) {
                case '1':
                    this.divs_std();
                    break;
                    
                case '2':
                    this.divs_brd();
                    break;
            
                case '3':
                    this.divs_rtr();
                    break;
            
                default:
                    break;
            }
        }
    }
});

const store = new Vuex.Store({
    state: {
        url_volver_altas: 'http://' + website + '/adminAltas',
        inst_paso: '1',
        cliente_nombre: null,
        cliente: null,
        direccion: null,
        fecha_alta: null,
        instalacion_fecha: null,
        plan_id: null,
        plan: null,
        comentarios_ins: null,
        class_ubiquiti: false,
        class_router: false,
        class_panel: false,
        class_guardar: false,
        div_tipoInstalacion: true,
        tipoInstalacion: 0,
        formulario_tipoInstalacion: true,
        equipo_id: 0,
        router_id: 0,
        panel_id: 0,
        equipo_datos: 'Sin Seleccionar',
        router_datos: 'Sin Seleccionar',
        panel_datos: 'Sin Seleccionar',
        equipo_reset: false,
        formulario_equipo: false,
        formulario_router: false, 
        formulario_panel: false 
    },
    mutations: {
        datos_borrados(state, data) {
            store.dispatch('get_session_data2');
            state.class_ubiquiti = false;
            state.class_router = false;
            state.class_panel = false;
            state.class_guardar = false;
            state.div_tipoInstalacion = true;
            state.inst_paso = '1';
            state.tipoInstalacion = 0;
            state.equipo_id = 0;
            state.router_id = 0;
            state.panel_id = 0;
            state.equipo_datos = 'Sin Seleccionar';
            state.router_datos = 'Sin Seleccionar';
            state.panel_datos = 'Sin Seleccionar';
            state.equipo_reset = true;
            state.formulario_tipoInstalacion= true;
            state.formulario_equipo= false;
            state.formulario_router= false;
            state.formulario_panel= false;
        },
        set_elements_cliente (state, data) {
            if (!data.es_empresa)
            {
                state.cliente_nombre = data.apellido + ', ' + data.nombre;
                state.cliente = state.cliente_nombre + ' | ' + 
                    data.cod_area_cel.codigoDeArea + '-15-' + data.celular + ' (' + data.cod_area_cel.provincia + ')';
            } else {
                state.cliente_nombre = data.apellido;
                state.cliente = state.cliente_nombre + ' | ' +
                    data.cod_area_cel.codigoDeArea + '-15-' + data.celular + ' (' + data.cod_area_cel.provincia + ')';
            }
        },
        set_elements_direccion (state, data) {
            state.direccion = data.nombre_calle + ', ' + data.numero + ' | ' + data.barrio + ' | ' + data.ciudad;
            if (data.comentarios)
            {
                state.direccion = state.direccion + ' | ' + data.comentarios;
            }
        },
        set_elements_plan (state, data) {
            data.forEach(element => {
                if (element.id == state.plan_id ) {
                    state.plan = element.nombre + ' | ' + element.descripcion;
                }
            });
        },
        set_elements_alta(state, data) {
            state.plan_id = data.plan_id;
            state.comentarios_ins = data.comentarios;
            store.dispatch('get_fetch_api', {
                callback: 'set_elements_plan',
                url: 'http://' + website + '/getPlanes'
            });
            store.dispatch('get_fetch_api', {
                callback: 'set_elements_cliente',
                url: 'http://' + website + '/Cliente/' + data.cliente_id
            });
            store.dispatch('get_fetch_api', {
                callback: 'set_elements_direccion',
                url: 'http://' + website + '/searchIdDireccion/' + data.direccion_id
            });
            state.fecha_alta = data.created_at.split('T')[0];
            state.instalacion_fecha = data.instalacion_fecha.split(' ')[0];
        },
        set_elements_tipoInstalacion (state, data) {
            if (data > 0 && data < 4) {
                state.tipoInstalacion = data;
                state.inst_paso = 2;
                store.dispatch('get_session_equipo');
                store.dispatch('get_session_router');
                store.dispatch('get_session_panel');
            } else {
                state.tipoInstalacion = 0;
            }
        },
        check_alta_id (state, data) {
            if(alta_id == data) {
                store.dispatch('get_session_data2');
            } else {
                store.dispatch('delete_session_data');
            }
        },
        set_elements_equipo_id (state, data) {
            state.equipo_id = data;
        },
        set_elements_router_id (state, data) {
            state.router_id = data;
        },
        set_elements_panel_id (state, data) {
            state.panel_id = data;
        },
        set_elements_equipo (state, data) {
            if (data) {
                state.equipo_datos =    data.num_dispositivo.marca + ' | ' +
                                        data.num_dispositivo.modelo + ' | ' +
                                        data.mac_address + ' | ' +
                                        data.ip + ' | ' +
                                        data.num_antena.descripcion + 
                                        (data.comentario ? (' | ' + data.comentario) : '');
                state.inst_paso = 3;
            } else {
                console.error('ERROR en el id de equipo consultado.');
            }
        },
        set_elements_router (state, data) {
            if (data) {
                state.router_datos =    data.num_dispositivo.marca + ' | ' +
                                        data.num_dispositivo.modelo + ' | ' +
                                        data.mac_address + ' | ' +
                                        data.ip + 
                                        (data.comentario ? (' | ' + data.comentario) : '');
                if (state.tipoInstalacion == 2) {
                    state.inst_paso = 4;
                } else if (state.tipoInstalacion == 3){
                    state.inst_paso = 3;
                }
            } else {
                console.error('ERROR en el id de router consultado.');
            }
        },
        set_elements_panel (state, data) {
            if (data) {
                state.panel_datos = data.ssid;
                if (state.tipoInstalacion == 1) {
                    state.inst_paso = 4;
                } else if (state.tipoInstalacion == 2) {
                    state.inst_paso = 5;
                }
            } else {
                console.error('ERROR en el id de Panel consultado.');
            }
        }
    },
    actions: {
        delete_session_data () {
            store.dispatch('get_fetch_api', {
                callback: 'datos_borrados',
                url: 'http://' + website + '/SessionDeleteAll'
            })
        },
        get_alta_data() {
            store.dispatch('get_fetch_api', {
                callback: 'set_elements_alta',
                url: 'http://' + website + '/getAlta/' + alta_id
            })
        },
        get_session_data() {
            store.dispatch('get_fetch_api', {
                callback: 'check_alta_id',
                url: 'http://' + website + '/Session/session_alta_id'
            })
        },
        get_session_data2() {
            store.dispatch('get_fetch_api', {
                callback: 'set_elements_tipoInstalacion',
                url: 'http://' + website + '/Session/tipo_instalacion'
            })
        },
        get_session_equipo() {
            store.dispatch('get_fetch_api', {
                callback: 'set_elements_equipo_id',
                url: 'http://' + website + '/Session/equipo_id'
            })
        },
        get_session_router() {
            store.dispatch('get_fetch_api', {
                callback: 'set_elements_router_id',
                url: 'http://' + website + '/Session/router_id'
            })
        },
        get_session_panel() {
            store.dispatch('get_fetch_api', {
                callback: 'set_elements_panel_id',
                url: 'http://' + website + '/Session/panel_id'
            })
        },
        get_equipo_data() {
            store.dispatch('get_fetch_api', {
                callback: 'set_elements_equipo',
                url: 'http://' + website + '/getEquipo/' + store.state.equipo_id
            })
        },
        get_router_data() {
            store.dispatch('get_fetch_api', {
                callback: 'set_elements_router',
                url: 'http://' + website + '/getEquipo/' + store.state.router_id
            })
        },
        get_panel_data() {
            store.dispatch('get_fetch_api', {
                callback: 'set_elements_panel',
                url: 'http://' + website + '/getPanel/' + store.state.panel_id
            })
        },
        get_fetch_api(state, datos) {
            fetch(datos.url)
                .then(response => response.json())
                .then(data => {
                    store.commit(datos.callback, data)
                })
                .catch((error) => { console.error('Error:', error) })
        }
    }
});

const programaralta = new Vue({
    el: '#programaralta',
    store: store,
})