Vue.component('tipoContrato', {
template: //html
`
<div :class="class_div_tipoContrato">
    <div class="container alert alert-info mx-auto m-2 p-2" :class="class_ContractType_seleccionado" role="alert">
        <div class="row justify-content-between">
            <div class="col-8 pr-2">Tipo de Contrato: {{ContractType.nombre}}</div>
            <div class="col-1 pl-2">
                <button type="button" class="btn btn-secondary" @click="deseleccionar_ContractType" >Editar</button>
            </div>
        </div>
    </div>

    <div class="alert bg-light border col-6 mx-auto p-4 m-2" :class="class_formulario_tipoContrato">
        <h4>Tipo de Contrato</h4>
        <select class="form-control m-2" v-model="ContractType">
            <option value="" disabled>Seleccione tipo de contrato</option>
            <option v-for="ContractType1 in ContractTypes" v-bind:value="ContractType1">{{ContractType1.nombre}}</option>
        </select>
        <p>{{mensaje_usuario}}</p>
        <button class="btn btn-primary m-2" v-bind:class="class_guardar_button" v-on:click="seleccionar_contractType">Seleccionar</button>
    </div>
</div>
`,
data() {
    return {
        class_guardar_button: 'ocultar',
        ContractType: '',
        mensaje_usuario: '',
    }
},
computed: {
    id_ContractType:{
        get: function () {
            return store.state.id_typeContract;
        },
        set: function (newval) {
            store.state.id_typeContract = newval;
        }
    },
    class_div_tipoContrato:{
        get: function () {
            if (store.state.div_tipoContrato){
                return '';
            }else {
                return 'ocultar';
            }
        }
    },
    class_formulario_tipoContrato:{
        get: function () {
            if (store.state.formulario_tipoContrato) {
                return '';
            } else {
                return 'ocultar';
            }
        },
        set: function (newVal) {
            store.state.formulario_tipoContrato = newVal;
        }
    },
    class_ContractType_seleccionado: {
        get: function () {
            if (store.state.formulario_tipoContrato) {
                return 'ocultar';
            } else {
                return '';
            }
        }
    },
    ContractTypes: {
        get: function (){
            return store.state.elements_ContractType;
        }
    }
},
mounted() {
},
watch: {
    id_ContractType: function() {
        if (this.id_ContractType)
        {
            this.class_guardar_button = '';
            this.ContractTypes.forEach(element => {
                if (element.id == this.id_ContractType) {
                    this.ContractType = element;
                }
            });
        } else {
            this.class_guardar_button = 'ocultar';
        }
    },
    ContractType: function () {
        if (this.ContractType){
            this.id_ContractType = this.ContractType.id;
        }
    }
},
methods: {
    seleccionar_contractType: function () {
        this.mensaje_usuario = 'Seleccionado...';
        const url = 'http://' + website + '/Session';
        const metodo = 'put';
        const datos = {
            datos: {
                alta_contrato_type: this.id_ContractType,
            }
        };
        this.fetch_api(url, metodo, datos, this.seleccionar_contractType_1);
    },
    seleccionar_contractType_1: function (data) {
        if (typeof (data) == 'object') {
            this.mensaje_usuario = (data.message)
        } else if (data === true) {
            this.mensaje_usuario = '';
            this.class_formulario_tipoContrato = false;
            store.state.div_numeroCarpeta = true;
        } else {
            this.mensaje_usuario = 'Algo salió mal...';
            console.log('Algo salió mal...' + data);
        }
    },
    deseleccionar_ContractType: function() {
        this.class_formulario_tipoContrato = true;
        store.state.div_numeroCarpeta = false;
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