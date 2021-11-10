Vue.component('numeroCarpeta', {
    template: //html
    `
    <div :class="class_div_numeroCarpeta">
        <div class="container alert alert-info mx-auto m-2 p-2" :class="class_numeroCarpeta_seleccionado" role="alert">
            <div class="row justify-content-between">
                <div class="col-8 pr-2">Carpeta número: {{numeroCarpeta}}</div>
                <div class="col-1 pl-2">
                    <button type="button" class="btn btn-secondary" @click="deseleccionar_cliente" >Editar</button>
                </div>
            </div>
        </div>
        <div class="alert bg-light border col-6 mx-auto p-4 m-2" :class="class_formulario_numeroCarpeta">
            <h4>Número de Carpeta</h4>
            <div class="form-group col-md-6">
                <input type="text" v-model="numeroCarpeta" class="form-control" v-bind:class="class_numeroCarpeta">
                <p>{{mensaje_numeroCarpeta}}</p>
                <button class="btn btn-primary" v-bind:class="class_seleccionar_button_numeroCarpeta" v-on:click="seleccionar_numeroCarpeta">Seleccionar</button>
            </div>
        </div>
    </div>
    `,
    data(){
        return{
            mensaje_numeroCarpeta: '',
            class_numeroCarpeta: '',
            class_seleccionar_button_numeroCarpeta: 'ocultar',
        }
    },
    computed: {
        class_div_numeroCarpeta: {
            get: function () {
                if (store.state.div_numeroCarpeta) {
                    return '';
                } else {
                    return 'ocultar';
                }
            }
        },
        class_formulario_numeroCarpeta: {
            get: function() {
                if (store.state.formulario_numeroCarpeta) {
                    return '';
                } else {
                    return 'ocultar';
                }
            },
            set: function (newVal) {
                store.state.formulario_numeroCarpeta = newVal;
            }
        },
        class_numeroCarpeta_seleccionado: {
            get: function () {
                if (store.state.formulario_numeroCarpeta) {
                    return 'ocultar';
                } else {
                    return '';
                }
            }
        },
        numeroCarpeta: {
            get: function() {
                return store.state.id_numeroCarpeta;
            },
            set: function (newVal) {
                store.state.id_numeroCarpeta = newVal;
            }
        }
    },
    mounted() {
    /*     if (store.state.id_numeroCarpeta != null){
            this.select_funcion();
        } */
    },
    watch: {
        numeroCarpeta: function () {
            this.checkError_numeroCarpeta();
        }
    },
    methods: {
        checkError_numeroCarpeta: function() {
            const regex_numerico = /^[0-9]*$/;
            if (regex_numerico.test(this.numeroCarpeta) && this.numeroCarpeta != '' && this.check_numeroCarpeta_existe()){
                this.class_numeroCarpeta = 'is-valid';
                this.class_seleccionar_button_numeroCarpeta = '';
                this.mensaje_numeroCarpeta = '';
            }else{
                this.class_numeroCarpeta = 'is-invalid';   
                this.class_seleccionar_button_numeroCarpeta = 'ocultar';
                this.mensaje_numeroCarpeta = 'Solo caracteres numericos.';
                }
        },
        check_numeroCarpeta_existe: function () {
            //verificar si esiste carpeta en BD.
            return true;
        },
        seleccionar_numeroCarpeta: function () {
            this.mensaje_numeroCarpeta = 'Seleccionado...';
            const url = 'http://' + website + '/Session';
            const metodo = 'put';
            const datos = {
                datos: {
                    alta_numeroCarpeta: this.numeroCarpeta,
                }
            };
            this.fetch_api(url, metodo, datos, this.seleccionar_numeroCarpeta_1);
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
        seleccionar_numeroCarpeta_1: function(data) {
            if (typeof (data) == 'object') {
                this.mensaje_numeroCarpeta = (data.message)
            } else if (data === true) {
                this.select_funcion();
            } else {
                this.mensaje_numeroCarpeta = 'Algo salió mal...';
                console.log('Algo salió mal...' + data);
            }
        },
        select_funcion: function () {
            this.mensaje_numeroCarpeta = '';
            this.class_formulario_numeroCarpeta = false;
            this.class_numeroCarpeta_seleccionado = '';
            store.state.formulario_numeroCarpeta = false;
        },
        deseleccionar_cliente: function(){
            this.class_formulario_numeroCarpeta = true;
            this.class_numeroCarpeta_seleccionado = 'ocultar';
            store.state.formulario_numeroCarpeta = true;
        }
    }
})