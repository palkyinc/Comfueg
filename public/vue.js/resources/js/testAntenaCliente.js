Vue.component('testantenacliente', {
    template: //html
        `
        <div>
            <testantena></testantena>
        </div>
        `,
    mounted() {
    },
    methods: {
    }
});

const store = new Vuex.Store({
    state: {
    },
    mutations: {
    },
    actions: {
    }
});

const testContrato = new Vue({
    el: '#testContrato',
    store: store,
})