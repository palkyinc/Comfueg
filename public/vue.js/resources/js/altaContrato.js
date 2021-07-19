Vue.component('contrato', {
    template: //html
        `
        <div>
            <h3>Nueva alta de contrato</h3>
            <cliente></cliente>
            <direccion></direccion>
        </div>
        `,
    mounted() {
        if (store.state.id_cliente == null) {
            store.dispatch('get_session_data');
        }
    },
});

const store = new Vuex.Store({
    state: {
        id_cliente: null,
        formulario_cliente: false,
        esempresa: false,
        id_direccion: null,
        formulario_direccion: false
    },
    mutations: {
        set_id_cliente(state, data) {
            if (data != false) {
                state.id_cliente = data;
                state.formulario_direccion = true;
            }else{
                state.formulario_direccion = false;
            }
        }
    },
    actions: {
        get_session_data() {
            store.dispatch('get_fetch_api', {callback: 'set_id_cliente', 
                url: 'http://' + website + '/Session/alta_contrato_id_cliente'})
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