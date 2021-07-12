const cliente = new Vue({
    el: '#cliente',
    data: {
        cliente_original: '',
        error_cliente: {'id_cliente' : true,
                        'nombre': true,
                        'apellido': true,
                        'cod_area_tel': true,
                        'telefono': false,
                        'cod_area_cel': true,
                        'celular': true,
                        'email': false,
                        },
        id_cliente: '',
        mensaje_id_cliente: '',
        mensaje_nombre: '',
        mensaje_apellido: '',
        mensaje_cod_area_tel: '',
        mensaje_telefono: '',
        mensaje_cod_area_cel: '',
        mensaje_celular: '',
        mensaje_email: '',
        mensaje_cliente: 'Completar Formulario ',
        nombre: '',
        apellido: '',
        cod_area_tel: '2964',
        telefono: '',
        cod_area_cel: '2964',
        celular: '',
        email: '',
        id_cod_area_tel: '',
        id_cod_area_cel: '',
        class_id_cliente: 'form-control',
        class_nombre: 'form-control',
        class_apellido: 'form-control',
        class_cod_area_tel: 'form-control',
        class_telefono: 'form-control',
        class_cod_area_cel: 'form-control',
        class_prefijo: 'form-control',
        class_celular: 'form-control',
        class_email: 'form-control',
        class_seleccionar_button_cliente: 'ocultar',
        class_guardar_button_cliente: 'ocultar',
        class_cambiar_button_cliente: 'ocultar',
        class_formulario_cliente: 'alert bg-light border col-8 mx-auto p-4',
        class_cliente_seleccionado: 'ocultar'
    },
    mounted: function() {
    },
    watch: {
        id_cliente: function () {
            this.checkId_cliente();
        },
        cod_area_tel: function () {
            this.checkCod_area_tel();
            this.checkTelefono();
            this.checkError_cliente();
        },
        cod_area_cel: function () {
            this.checkCod_area_cel();
            this.checkCelular();
            this.checkError_cliente();
        },
        nombre: function () {
            this.checkNombre();
            this.checkError_cliente();
        },
        apellido: function () {
            this.checkApellido();
            this.checkError_cliente();
        },
        telefono: function () {
            this.checkTelefono();
            this.checkError_cliente();
        },
        celular: function () {
            this.checkCelular();
            this.checkError_cliente();
        },
        email: function () {
            this.checkEmail();
            this.checkError_cliente();
        }
    },
    methods: {
        buscarCliente: function() {
            fetch('http://' + website + '/Cliente/' + this.id_cliente)
                .then(response => response.json())
                .then(data => {
                    this.cliente_original = data;
                    if (data.id === undefined) {
                        this.mensaje_id_cliente = 'Cliente no encontrado.';
                        this.class_id_cliente = 'form-control is-invalid';
                        this.nombre = '';
                        this.apellido = '';
                        this.cod_area_tel = '2964';
                        this.cod_area_cel = '2964';
                        this.telefono = '';
                        this.celular = '';
                        this.email = '';
                        this.error_cliente.id_cliente = true;
                    } else {
                            this.class_id_cliente = 'form-control is-valid';
                            this.nombre = data.nombre;
                            this.apellido = data.apellido;
                            this.cod_area_tel = data.cod_area_tel.codigoDeArea;
                            this.telefono = data.telefono;
                            this.cod_area_cel = data.cod_area_cel.codigoDeArea;
                            this.celular = data.celular;
                            this.email = data.email;
                            this.mensaje_id_cliente = '';
                            this.error_cliente.id_cliente = false;
                            };
                });
        },
        checkTelefono () {
            let longitud = this.cod_area_tel.length + this.telefono.length;
            let maxlong = 10 - this.cod_area_tel.length;
            const regex_numerico = /^[0-9]*$/;
            if ((longitud == 10 || longitud == this.cod_area_tel.length) && regex_numerico.test(this.telefono)) {
                this.mensaje_telefono = '';
                this.class_telefono = 'form-control is-valid';
                this.error_cliente.telefono = false;
                return true;
            } else {
                this.mensaje_telefono = 'Min:0, max: ' + maxlong + ', solo números.';
                this.class_telefono = 'form-control is-invalid';
                this.error_cliente.telefono = true;
                return false;
            }
        },
        checkCod_area_tel () {
            if (this.cod_area_tel) {
                fetch('http://' + website + '/CodigoDeArea/Codigo/' + this.cod_area_tel)
                    .then(response => response.json())
                    .then(data => {
                        if (data.id === undefined) {
                            this.class_cod_area_tel = 'form-control is-invalid';
                            this.mensaje_cod_area_tel = '';
                            this.id_cod_area_tel = '';
                            this.error_cliente.cod_area_tel = true;
                            return false;
                        }
                        else {
                            this.class_cod_area_tel = 'form-control is-valid';
                            this.mensaje_cod_area_tel = data.provincia;
                            this.id_cod_area_tel = data.id;
                            this.error_cliente.cod_area_tel = false;
                            return true;
                        }
                    })
            }
            else {
                this.class_cod_area_tel = 'form-control';
                return false;
            }
        },
        checkCod_area_cel () {
            if (this.cod_area_cel) {
                fetch('http://' + website + '/CodigoDeArea/Codigo/' + this.cod_area_cel)
                    .then(response => response.json())
                    .then(data => {
                        if (data.id === undefined) {
                            this.class_cod_area_cel = 'form-control is-invalid';
                            this.mensaje_cod_area_cel = '';
                            this.id_cod_area_cel = '';
                            this.error_cliente.cod_area_cel = true;
                            return false;
                        }
                        else {
                            this.class_cod_area_cel = 'form-control is-valid';
                            this.mensaje_cod_area_cel = data.provincia;
                            this.id_cod_area_cel = data.id;
                            this.error_cliente.cod_area_cel = false;
                            return true;
                        }
                    })
            }
            else {
                this.class_cod_area_cel = 'form-control';
                this.error_cliente.area_cel = true;
                return false;
            }
        },
        checkCelular () {
            let longitud = this.cod_area_cel.length + this.celular.length;
            let maxlong = 10 - this.cod_area_cel.length;
            const regex_numerico = /^[0-9]*$/;
            if (longitud == 10 && regex_numerico.test(this.celular)) {
                this.mensaje_celular = '';
                this.class_celular = 'form-control is-valid';
                this.error_cliente.celular = false;
                return true;
            } else {
                this.mensaje_celular = 'Max: ' + maxlong + ', solo números.';
                this.class_celular = 'form-control is-invalid';
                this.error_cliente.celular = true;
                return true;
            }
        },
        checkEmail(){
            const regex_email = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
            if (regex_email.test(this.email) || this.email.length == 0)
            {
                this.mensaje_email = '';
                this.class_email = 'form-control is-valid';
                this.error_cliente.email = false;
                return true;
            }else   {
                    this.mensaje_email = 'Email con formato incorrecto.'
                    this.class_email = 'form-control is-invalid';
                this.error_cliente.email = true;
                    return false;
                    }
        },
        checkNombre () {
            if (this.nombre.length < 3 || this.nombre.length > 45) {
                this.mensaje_nombre = 'Nombre: min 3, max 45 caracteres.';
                this.class_nombre = 'form-control is-invalid';
                this.error_cliente.nombre = true;
                return false;
            } else {
                this.mensaje_nombre = '';
                this.class_nombre = 'form-control is-valid';
                this.error_cliente.nombre = false;
                return true;
            }
        },
        checkApellido () {
            if (this.apellido.length < 3 || this.apellido.length > 45) {
                this.mensaje_apellido = 'Apellido: min 3, max 45 caracteres.';
                this.class_apellido = 'form-control is-invalid';
                this.error_cliente.apellido = true;
                return false;
            } else {
                this.mensaje_apellido = '';
                this.class_apellido = 'form-control is-valid';
                this.error_cliente.apellido = false;
                return true;
            }
        },
        checkId_cliente () {
            const regex_numerico = /^[0-9]*$/;
            if (this.id_cliente > 5 && regex_numerico.test(this.id_cliente)){
                this.buscarCliente();
            }else {
                this.mensaje_id_cliente = 'Max= 99999. Solo números'
            }
        },
        checkError_cliente (){
            this.class_seleccionar_button_cliente = 'ocultar';
            if (this.error_cliente.nombre || this.error_cliente.apellido || this.error_cliente.telefono || this.error_cliente.celular || this.error_cliente.email){
                this.mensaje_cliente = 'Completar los datos';
                this.class_guardar_button_cliente = 'ocultar';
                this.class_cambiar_button_cliente = 'ocultar';
            }
            else if (this.error_cliente.id_cliente){
                this.mensaje_cliente = '';
                this.class_guardar_button_cliente = 'btn btn-primary';
                this.class_cambiar_button_cliente = 'ocultar';
            } else {
                this.mensaje_cliente = '';
                if (this.es_igual_original()) {
                    this.class_guardar_button_cliente = 'ocultar';
                    this.class_cambiar_button_cliente = 'ocultar';
                    this.class_seleccionar_button_cliente = 'btn btn-primary';
                }else {
                    this.class_cambiar_button_cliente = 'btn btn-primary';
                    this.class_guardar_button_cliente = 'ocultar';
                }
            }
        },
        es_igual_original () {
            if (
                this.cliente_original.id == this.id_cliente &&
                this.cliente_original.nombre == this.nombre &&
                this.cliente_original.apellido == this.apellido &&
                this.cliente_original.cod_area_tel.codigoDeArea == this.cod_area_tel &&
                this.cliente_original.telefono == this.telefono &&
                this.cliente_original.cod_area_cel.codigoDeArea == this.cod_area_cel &&
                this.cliente_original.celular == this.celular &&
                this.cliente_original.email == this.email
            )
            {
                return true;
            }else{
                return false;
            }
        },
        guardar_cliente () {
            this.error_cliente.id_cliente = false;
            this.mensaje_cliente = 'Cliente guardado.';
            this.class_seleccionar_button_cliente = 'btn btn-primary';
            this.class_guardar_button_cliente = 'ocultar';
            this.class_cambiar_button_cliente = 'ocultar';
        },
        cambiar_cliente () {
            this.mensaje_cliente = 'Cliente modificado.';
            this.class_seleccionar_button_cliente = 'btn btn-primary';
            this.class_guardar_button_cliente = 'ocultar';
            this.class_cambiar_button_cliente = 'ocultar';
        },
        seleccionar_cliente () {
            this.class_formulario_cliente = 'ocultar';
            this.class_cliente_seleccionado = 'alert alert-info';
        }
    },
    computed: {
    }
})