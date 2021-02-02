const vm = new Vue({
	el: "#vista",
	methods: {
		clickBoton() {
			this.claseBoton = `boton boton-${this.sigEstado}`;
            this.sigEstado = this.sigEstado === "enter" ? "leave" : "enter";
            console.log('apretado');
		},
	},

	data: {
		nombreBoton: "Clickeame",
		claseBoton: "boton",
		sigEstado: "enter",
	},
});