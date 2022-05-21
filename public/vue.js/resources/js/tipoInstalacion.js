Vue.component('tipoInstalacion',
{
    template: //html
`
<div>
    <div class="container my-4" :class="class_formulario_tipo_instalacion">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h4>Seleccionar tipo de contrato</h4></div>
                        <form action="" method="post" class="margenAbajo">
                            <div class="card-body">
                                <div class="form-group col-md-6">
                                    <form>
                                        <select class="form-control" v-model="tipo_instalacion">
                                            <option value="0">Seleccionar...</option>
                                            <option value="1">Standard</option>
                                            <option value="2">Bridge</option>
                                            <option value="3">Solo Router</option>
                                        </select>
                                    </form>
                                </div>
                                <p>{{mensaje_usuario}}</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
`,
    data(){
        return{
            mensaje_usuario: '',
        }
    },
    mounted(){

    },
    beforeUpdate() {
    },
    computed: {
        class_formulario_tipo_instalacion:{
            get: function () {
                return (store.state.formulario_tipoInstalacion) ? '' : 'ocultar';
            },
            set: function (newVal) {
                store.state.formulario_tipoInstalacion = newVal;
            }
        },
        tipo_instalacion:{
            get: function () {
                return (store.state.tipoInstalacion);
            },
            set: function (newVal) {
                store.state.tipoInstalacion = newVal;
            }
        }
    },
    watch: {
        tipo_instalacion: function () {
            if (this.tipo_instalacion > 0 && this.tipo_instalacion < 4) {
                this.seleccionar_tipo_instalacion();
            }
        }
    },
    methods: {
        seleccionar_tipo_instalacion() {
            this.mensaje_usuario = 'Seleccionado...';
            const url = 'http://' + website + '/Session';
            const metodo = 'put';
            const datos = {
                datos: {
                    tipo_instalacion: this.tipo_instalacion,
                    session_alta_id: alta_id,
                }
            };
            this.fetch_api(url, metodo, datos, this.seleccionar_tipo_instalacion_1);
        },
        seleccionar_tipo_instalacion_1(data) {
            if (typeof (data) == 'object') {
                this.mensaje_usuario = (data.message)
            } else if (data === true) {
                this.mensaje_usuario = '';
                if (this.tipo_instalacion == '1' || this.tipo_instalacion == '2' || this.tipo_instalacion == '3') {
                    store.state.inst_paso = 2;
                }else {
                    console.error('ERROR: tipoInstalacion.js: variable tipo_instalacion fuera de rango');
                }
            } else {
                this.mensaje_usuario = 'Algo salió mal...';
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
                .then(response => {
                    callback(response);
                })
                .catch((error) => {
                    console.error('FETCH Volvió con Error:', error);
                })
        },
    }
})