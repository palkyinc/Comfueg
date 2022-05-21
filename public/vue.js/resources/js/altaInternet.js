Vue.component('contrato', {
    template: //html
    `
    <div>
        <div :class="div_title_alta">
            <h3>Nueva alta de contrato</h3>
        </div>
        <div :class="div_title_modify">
            <h3>Modificando alta de contrato</h3>
        </div>
        <div :class="class_alert_error" role="alert">
                {{ mensaje_error }}
                <button class="btn btn-info m-2" v-on:click="volver_admin">Volver Altas</button>
        </div>
        <div :class="class_borrar_formulario">
            <button class="btn btn-primary m-2" v-on:click="borrar_formulario">Borrar formulario</button>
            <a :href="url_volver_altas" class="btn btn-info m-2">Volver Altas</a>
        </div>
        <div class="alert bg-light border col-10 mx-auto p-4" :class="div_guardar_alta">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Notas de la Instalación</span>
                </div>
                <textarea class="form-control" aria-label="Comentarios" v-model="comentarios" :class="class_comentarios" rows="auto" cols="50"></textarea>
                <div class="input-group-prepend">
                    <button class="btn btn-success m-2" :class="class_guardar_button" v-on:click="guardar_datos"">Guardar Alta</button>
                </div>
            </div>
            <p>{{mensaje_comentarios}}</p>
        </div>
        <cliente></cliente>
        <direccion></direccion>
        <plan></plan>
        
    </div>
    `,
    data() {
        return {
            mensaje_comentarios: 'Agregar comentarios para el Alta de Contrato. No obligatorio.',
            class_comentarios: ''
        }
    },
    computed: {
        url_volver_altas: {
            get: function () {
                return store.state.url_volver_altas;
            }
        },
        div_title_alta: {
            get:function () {
                return store.state.id_alta ? 'ocultar' : '';
            }
        },
        div_title_modify: {
            get:function () {
                return store.state.id_alta ? '' : 'ocultar';
            }
        },
        comentarios: {
            get:function () {
                return store.state.alta_comentarios;
            },
            set:function (newVal) {
                store.state.alta_comentarios = newVal;
            }
        },
        class_guardar_button: {
            get: function () {
              return (store.state.class_guardar_button ?  '' : 'ocultar');
            },
            set: function (newVal) {
                store.state.class_guardar_button = newVal;
            }
        },
        class_borrar_formulario: {
            get: function () {
              return (store.state.class_borrar_formulario ?  '' : 'ocultar');
            },
            set: function (newVal) {
                store.state.class_guardar_button = newVal;
            }
        },
        class_alert_error: {
            get: function () {
                console.log(store.state.class_alert_error);
                if (store.state.class_alert_error == 0) {
                    return 'ocultar';
                } else if (store.state.class_alert_error == 1) {
                    return 'alert alert-success';
                } else if (store.state.class_alert_error == 2) {
                    return 'alert alert-danger';
                }
            },
            set: function(newVal) {
                store.state.class_alert_error = newVal;
            }
        },
        mensaje_error: {
            get:function () {
                return store.state.mensaje_error;   
            },
            set:function (newVal) {
                store.state.mensaje_error = newVal;
            }
        },
        div_guardar_alta: {
            get: function() {
                return (store.state.div_planNext ? '' : 'ocultar');
            },
            set: function (newVal) {
                store.state.div_planNext = newVal;
            }
        },
        div_cliente: {
            get: function() {
                return (store.state.div_cliente);
            },
            set: function() {
            }
        },
        div_tipoContrato: {
            get: function() {
                return (store.state.div_tipoContrato ? true : false);
            },
            set: function(newVal) {
                store.state.div_tipoContrato = newVal;
            }
        },
        div_planNext: {
            get: function() {
                return (store.state.div_planNext ? true : false);
            },
            set: function(newVal) {
                store.state.div_planNext = newVal;
            }
        },
        div_direccionNext: {
            get: function() {
                return (store.state.div_direccionNext ? true : false);
            },
            set: function(newVal) {
                store.state.div_direccionNext = newVal;
            }
        }
    },
    watch:{
        div_cliente : function () {
            if (!this.div_cliente) {
                this.div_tipoContrato = false;
            }
        },
        div_tipoContrato : function () {
            if (!this.div_tipoContrato) {
                this.div_direccionNext = false;
                //this.class_alert_error = 0;
            } else if (store.state.id_direccion) {
                this.div_direccionNext = true
            }
        },
        div_direccionNext: function () {
            if (!this.div_direccionNext) {
                this.div_planNext = false
            } else if (store.state.id_plan) {
                this.div_planNext = true
            }
        },
        div_planNext: function () {
        },
        comentarios: function () {
            this.validar_comentarios();
        }
    },
    mounted() {
        if (altaToEdit) {
            store.dispatch('get_fetch_api', {
                callback: 'set_data_modify',
                url: 'http://' + website + '/getAlta/' + altaToEdit
            });
        }else if (store.state.id_cliente == null) {
            store.dispatch('get_session_data');
        }
    },
    methods: {
        validar_comentarios() {
            let longitud = this.comentarios.length;
            if ((longitud < 501 && longitud > 2) || longitud === 0) {
                this.class_comentarios = 'form-control is-valid';
                this.mensaje_comentarios = 'Restan aracteres: ' + (500 - longitud);
                this.class_guardar_button = true;
            } else {
                this.class_guardar_button = false;
                this.class_comentarios = 'form-control is-invalid';
                this.mensaje_comentarios = 'Min: 3. Max: 500.';
            }
        },
        volver_admin: function () {
            this.borrar_datos('datos_guardados');
        },
        borrar_formulario: function () {
            this.borrar_datos('datos_borrados');
        },
        borrar_datos: function (el_callback) {
            store.dispatch('get_fetch_api', {
                callback: el_callback,
                url: 'http://' + website + '/SessionDeleteAll'
            })
        },
        guardar_datos: function () {
            store.dispatch('guardar_alta', {comentarios: this.comentarios})
        }
    }
});

const store = new Vuex.Store({
    state: {
        url_volver_altas: 'http://' + website + '/adminAltas',
        id_alta: null,
        id_cliente: null,
        esempresa: false,
        div_cliente: true,
        formulario_cliente: true,
        id_direccion: null,
        div_tipoContrato: false, // en realidad div_direccion
        div_direccionNext: false,
        formulario_direccion: true,
        direccion_buscar_inicial: false,
        id_plan: null,
        formulario_plan: true,
        div_planNext: false,
        class_alert_error: 0, //0=ocultar 1=Success 2=danger
        mensaje_error: '',
        alta_comentarios: '',
        class_guardar_button: true,
        class_borrar_formulario: true
    },
    mutations: {
        set_id_cliente(state, data) {
            if (data != false) {
                state.id_cliente = data;
                state.formulario_cliente = false;
                state.div_tipoContrato = true;
            }else {
                state.id_cliente = null;
                state.formulario_cliente = true;
                state.div_tipoContrato = false;
            }
        },
        set_id_direccion(state, data) {
            if (data != false) {
                state.direccion_buscar_inicial = true;
                state.formulario_direccion = false;
                state.div_direccionNext = true;
                state.id_direccion = data;
            }else {
                state.id_direccion = null;
                state.formulario_direccion = true;
                state.div_direccionNext = false;
            }
        },
        set_id_plan(state, data) {
            if (data != false) {
                state.formulario_plan = false;
                state.div_planNext = true;
                state.id_plan = data;
            }else {
                state.id_plan = null;
                state.formulario_plan = true;
                state.div_planNext = false;
            }
        },
        datos_borrados(state, data){
            store.dispatch('get_session_data');
            location.reload();
        },
        datos_guardados(state, data){
            window.location.href="/adminAltas";
        },
        post_guardar_alta(state, datos) {
            if (typeof (datos) == 'object') {
                store.state.mensaje_error = (datos.message)
                store.state.class_alert_error = 2;
            }else if (datos === true) {
                store.state.class_alert_error = 1;
                store.state.class_guardar_button = false;
                store.state.class_borrar_formulario = false;
                store.state.div_cliente = false;
                store.state.mensaje_error = 'Alta guardada OK.';
            } else {
                store.state.mensaje_error = 'Algo salió mal...';
                store.state.class_alert_error = 2;
                console.log('Algo salió mal...' + datos);
            }
        },
        set_data_modify (state, datos) {
            store.commit('set_id_cliente', datos.cliente_id);
            store.commit('set_id_direccion', datos.direccion_id);
            store.commit('set_id_plan', datos.plan_id);
            store.state.alta_comentarios = datos.comentarios;
            store.state.id_alta = datos.id;
        }
    },
    actions: {
        get_session_data() {
            store.dispatch('get_fetch_api', {callback: 'set_id_cliente', 
                url: 'http://' + website + '/Session/alta_contrato_id_cliente'})
            store.dispatch('get_fetch_api', {callback: 'set_id_direccion', 
                url: 'http://' + website + '/Session/alta_contrato_id_direccion'})
            store.dispatch('get_fetch_api', {callback: 'set_id_plan', 
                url: 'http://' + website + '/Session/alta_contrato_id_plan'})
        },
        get_fetch_api(state, datos) {
            fetch(datos.url)
                .then(response => response.json())
                .then(data => { 
                    store.commit(datos.callback, data) })
                .catch((error) => { console.error('get_fetch_api_Error:', error) })
        },
        guardar_alta(state, arrayIn) {
            const url = 'http://' + website + '/agregarAlta';
            const metodo = (store.state.id_alta) ? 'patch' : 'put';
            const datos = {
                data: {
                    alta_id: store.state.id_alta,
                    cliente_id: store.state.id_cliente,
                    direccion_id: store.state.id_direccion,
                    plan_id: store.state.id_plan,
                    comentarios: arrayIn.comentarios,
                }
            };
            store.dispatch('fetch_api', {
            url: url,
            metodo: metodo,
                datos: datos,
                callback: 'post_guardar_alta',
            });
        },
        fetch_api(state, arrayIn) {
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(arrayIn.url, {
                method: arrayIn.metodo,
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json, text-plain, */*",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": token
                },
                body: JSON.stringify(arrayIn.datos.data),
            })
                .then(response => response.json())
                .then(data => {
                    store.commit(arrayIn.callback, data);
                })
                .catch((error) => {
                    store.commit(arrayIn.callback, error);
                    console.error('FETCH Volvió con Error:', error);
                })
        }
    }
});

const altaInternet = new Vue({
    el: '#altaInternet',
    store: store,
})