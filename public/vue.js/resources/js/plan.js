Vue.component('plan', {
template: //html
`
<div :class="class_div_plan">
    <div class="container alert alert-info mx-auto m-2 p-2" :class="class_plan_seleccionado" role="alert">
        <div class="row justify-content-between">
            <div class="col-11 pr-2">
                <h4>{{plan_completo.nombre}} | Descipción: bajada {{plan_completo.bajada}}Kbs, subida {{plan_completo.subida}}Kbs. {{plan_completo.descripcion}}</h4>
            </div>
            <div class="col-1 pl-2">
                <button type="button" class="btn btn-secondary" @click="deseleccionar_plan" >Editar</button>
            </div>
        </div>
    </div>

    <div class="alert bg-light border col-6 mx-auto p-4 m-2" :class="class_formulario_plan">
        <h4>Plan</h4>
        <select class="form-control m-2" v-model="id_plan">
            <option value="aa">Opciones de Plan</option>
            <option v-for="abono in planes" v-bind:value="abono.id">{{abono.nombre}}</option>
        </select>
        <p>{{mensaje_plan}}</p>
        <button class="btn btn-primary m-2" v-bind:class="class_guardar_button" v-on:click="seleccionar_plan">Seleccionar</button>
    </div>
</div>
`,
data() {
    return {
        class_guardar_button: 'ocultar',
        mensaje_plan: '',
        plan_completo: [],
        planes: []
    }
},
computed: {
    id_plan:{
        get: function () {
            return store.state.id_plan;
        },
        set: function (newval) {
            store.state.id_plan = newval;
        }
    },
    class_div_plan:{
        get: function () {
            if (store.state.div_direccionNext){
                return '';
            }else {
                return 'ocultar';
            }
        }
    },
    class_formulario_plan:{
        get: function () {
            if (store.state.formulario_plan) {
                return '';
            } else {
                return 'ocultar';
            }
        },
        set: function (newVal) {
            store.state.formulario_plan = newVal;
        }
    },
    class_plan_seleccionado: {
        get: function () {
            if (store.state.formulario_plan) {
                return 'ocultar';
            } else {
                return '';
            }
        }
    }
},
mounted() {
    fetch('/getPlanes')
        .then(valor => valor.json())
        .then(valor => {
            this.planes = valor;
            });
},
watch: {
    id_plan: function () {
        this.validar_plan();
    }
},
methods: {
    validar_plan: function () {
        const regex_numerico = /^[0-9]*$/;
        if (regex_numerico.test(this.id_plan)) {
            this.class_guardar_button = '';
            this.mensaje_plan = '';
            this.planes.forEach( element => {
                if (element.id == this.id_plan){
                    this.plan_completo = element;
                }
            });
        } else {
            this.class_guardar_button = 'ocultar';
            this.mensaje_plan = 'Seleccionar plan para continuar';
        }
    },
    seleccionar_plan: function () {
        this.mensaje_plan = 'Seleccionado...';
        const url = 'http://' + website + '/Session';
        const metodo = 'put';
        const datos = {
            datos: {
                alta_contrato_id_plan: this.id_plan,
            }
        };
        this.fetch_api(url, metodo, datos, this.seleccionar_plan_1);
    },
    seleccionar_plan_1: function (data) {
        if (typeof (data) == 'object') {
            this.mensaje_usuario = (data.message)
        } else if (data === true) {
            this.mensaje_plan = '';
            this.class_formulario_plan = false;
            store.state.div_planNext = true;
        } else {
            this.mensaje_plan = 'Algo salió mal...';
            console.log('Algo salió mal...' + data);
        }
    },
    deseleccionar_plan: function() {
        this.class_formulario_plan = true;
        store.state.div_planNext = false;
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