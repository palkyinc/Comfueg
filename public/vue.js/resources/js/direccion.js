Vue.component('direccion', {
    template: //html
    `
    <div>
        <div class="container alert alert-info m-2 p-2" :class="class_direccion_seleccionada" role="alert">
            <div class="row">
                <div class="col-9 pr-2">Direción: Calle:  Altura: Entre calles Barrio</div>
                <button type="button" class="btn btn-secondary col-2" @click="deseleccionar_direccion" >Editar</button>
            </div>
        </div>

        <div class="alert bg-light border col-8 mx-auto p-4" :class="class_formulario_direccion">
            <h4>Dirección:</h4>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="id_calle">Calle: </label>
                    <select class="form-control" name="id_calle">
                        <option value="null">Seleccione una Calle...</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="numero">Altura: </label>
                    <input type="text" name="numero" value="" maxlength="5"  class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="entrecalle_1">Entrecalle 1: </label>
                    <select class="form-control" name="entrecalle_1">
                        <option value="">Seleccione una Entrecalle...</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="entrecalle_2">Entrecalle 2: </label>
                    <select class="form-control" name="entrecalle_2">
                        <option value="">Seleccione una Entrecalle...</option>
                    </select>
                </div>

            </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="id_barrio">Barrio: </label>
                        <select class="form-control" name="id_barrio" id="id_barrio">
                                <option value="">Seleccione un Barrio...</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                    <label for="id_ciudad">Ciudad: </label>
                        <select class="form-control" name="id_ciudad" id="id_barrio">
                        <option value="1">Rio Grande</option>
                        </select>
                    </div>
                </div>
                    <p>mensaje_direccion</p>
                    <button class="btn btn-primary" :class="class_guardar_button_direccion" v-on:click="guardar_direccion">Guardar</button>
                    <button class="btn btn-primary" :class="class_cambiar_button_direccion" v-on:click="cambiar_direccion">Cambiar</button>
                    <button class="btn btn-primary" :class="class_seleccionar_button_direccion" v-on:click="seleccionar_direccion">Seleccionar</button>
        </div>
    </div>
    `,
    data() {
        return {
            class_direccion_seleccionada: 'ocultar',
            class_formulario_direccion: 'ocultar',
            class_guardar_button_direccion: '',
            class_cambiar_button_direccion: '',
            class_seleccionar_button_direccion: '',
        }
    },
    computed: {
        formulario_direccion:{
            get: function () {
                return store.state.formulario_direccion;
            },
            set: function (newVal) {
                store.state.formulario_direccion = newVal;
            }

        }
    },
    watch: {
        formulario_direccion: function() {
            if (!store.state.formulario_cliente) {
                if (this.formulario_direccion) {
                    this.class_direccion_seleccionada = 'ocultar';
                    this.class_formulario_direccion = '';
                } else {
                    this.class_direccion_seleccionada = '';
                    this.class_formulario_direccion = 'ocultar';
                }
            } else {
                this.class_direccion_seleccionada = 'ocultar';
                this.class_formulario_direccion = 'ocultar';
            }
        }
    },
    methods: {
        guardar_direccion(){
            console.log('guardar_direccion')
        },
        cambiar_direccion() {
            console.log('cambiar_direccion')
        },
        seleccionar_direccion() {
            console.log('seleccionar_direccion')
        },
        deseleccionar_direccion() {
            console.log('deseleccionar_direccion')
        }
    }
})