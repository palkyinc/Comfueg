Vue.component('contrato', {
    template: //html
        `
        <div>
            <h3>Nueva alta de contrato</h3>
            <button class="btn btn-primary m-2" v-on:click="borrar_datos">Borrar Datos</button>
            <cliente></cliente>
            <tipoContrato></tipoContrato>
            <numeroCarpeta></numeroCarpeta>
        </div>
        `,
    mounted() {
        if (store.state.id_cliente == null) {
            store.dispatch('get_session_data');
        }
    },
    methods: {
        borrar_datos: function () {
            store.dispatch('get_fetch_api', {
                callback: 'datos_borrados',
                url: 'http://' + website + '/SessionDeleteAll'
            })
        }
    }
});

const store = new Vuex.Store({
    state: {
        id_cliente: null,
        esempresa: false,
        div_cliente: true,
        formulario_cliente: true,
        id_typeContract: '',
        elements_ContractType: [],
        div_tipoContrato: false,
        formulario_tipoContrato: true,
        id_numeroCarpeta: null,
        div_numeroCarpeta: false,
        formulario_numeroCarpeta: false,
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
        set_contract_type(state, data) {
            if (data != false) {
                state.id_typeContract = data;
                state.formulario_tipoContrato = false;
                console.log(data);
                if (data == 1 || data ==2){
                    state.div_numeroCarpeta = true;
                }
            }else {
                state.formulario_tipoContrato = true;
                state.div_numeroCarpeta = false;
            }
        },
        set_numero_carpeta(state, data) {
            if (data != false) {
                state.id_numeroCarpeta = data;
                state.formulario_numeroCarpeta = false;
                //hacer aca el dispatch de ContractType
            }else {
                state.formulario_numeroCarpeta = true;
            }
        },
        datos_borrados(state, data){
            store.dispatch('get_session_data');
            location.reload();
        },
        set_elements_ContractType (state, data){
            state.elements_ContractType = data;
            store.dispatch('get_fetch_api', {
                callback: 'set_contract_type',
                url: 'http://' + website + '/Session/alta_contrato_type'
            })
        }
    },
    actions: {
        get_session_data() {
            store.dispatch('get_fetch_api', {callback: 'set_elements_ContractType',
                url: 'http://' + website + '/ContractTypes'})
            store.dispatch('get_fetch_api', {callback: 'set_id_cliente', 
                url: 'http://' + website + '/Session/alta_contrato_id_cliente'})
            store.dispatch('get_fetch_api', {callback: 'set_numero_carpeta', 
                url: 'http://' + website + '/Session/alta_numeroCarpeta'})
        },
        get_fetch_api(state, datos) {
            fetch(datos.url)
                .then(response => response.json())
                .then(data => { 
                    store.commit(datos.callback, data) })
                .catch((error) => { console.error('Error:', error) })
        }
    }
});

const altaContrato = new Vue({
    el: '#altaContrato',
    store: store,
})