Vue.component('Panel',
{
    template: //html
`
<div>
    <div :class="class_formulario_panel" class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h4>Seleccionar Panel</h4></div>
                        <div class="card-body">
                            <div class="form-group col-md-6">
                            <label for="num_panel">Panel: </label>
                            <select class="form-control" v-model="panel_id">
                                <option v-bind:value="0">Seleccione Panel a Asociarse...</option>
                                <option v-for="panel in paneles" v-bind:value="panel.id">{{panel.ssid}}</option>
                            </select>
                        </div>
                        <div class="card-footer">
                            <button :class="class_seleccionar_panel" class="btn btn-dark"  title="Seleccionar para Instalación" v-on:click="seleccionar_panel">Seleccionar</button>
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
            paneles: [],
            mensaje_usuario: '',
            alert_mensaje_usuario: '',
            class_seleccionar_panel: 'ocultar',
            panel_id: 0
        }
    },
    mounted(){
        this.get_paneles();
    },
    beforeUpdate() {
    },
    computed: {
        class_formulario_panel: {
            get: function () {
                return store.state.formulario_panel ? '' : 'ocultar';
            }
        }
    },
    watch: {
        panel_id: function () {
            this.validar_panel_id();
        }
    },
    methods: {
        get_paneles() {
            fetch('/getPanels/')
                .then(valor => valor.json())
                .then(valor => {
                    this.paneles = valor;
                });
        },
        validar_panel_id() {
            let rta = false;
            this.paneles.forEach(element => {
                if (this.panel_id == element.id) {
                    rta = true;
                }
            });
            if (rta) {
                this.class_seleccionar_panel = '';
            }else {
                this.class_seleccionar_panel = 'ocultar';
            }
        },
        seleccionar_panel() {
            this.mensaje_usuario = 'Seleccionando...';
            this.alert_mensaje_usuario = 'alert alert-success'
            const url = 'http://' + website + '/Session';
            const metodo = 'put';
            const datos = {
                datos: {
                    panel_id: this.panel_id,
                }
            };
            fetch_api(url, metodo, datos, this.seleccionar_panel1);
        },
        seleccionar_panel1() {
            store.state.inst_paso = this.siguiente_paso();
            store.state.panel_id = this.panel_id;
            this.mensaje_usuario = 'Seleccionado';
            this.alert_mensaje_usuario = 'alert alert-success';
        },
        siguiente_paso() {
            if(store.state.tipoInstalacion == 1) {
                return 4
            } else {
                return 5
            }
        }
    }
})