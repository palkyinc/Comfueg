const vm = new Vue({
	el: "#vista",
	mounted: function() {
			/* fetch('http://' + website + '/allPanels')
			.then(response => response.json())
			.then(datos => {
				this.listaPaneles = datos;
				//console.log(this.listaPaneles);
				this.checkPanel();
			}); */
			console.log('mounted');
	},
	methods: {
		checkPanel: function(id, ip){
			console.log(id);
			this.flagBuscando = 0;
			/* const tabla = document.getElementById('tablaRender');
			for (let i = tabla.rows.length - 1; i > 0 ; i--) {
				tabla.deleteRow(i);
			}
			this.listaPaneles.forEach(element => {
				//console.log(this.flagBuscando);
				this.flagBuscando--;*/
				fetch('http://' + website + '/clientTest/' + ip)
				.then(response => response.json())
				.then( data => {
					/* data.ip = element.ip;
					data.sitio = element.sitio;
					data.HostnameDb = element.Hostname;
					this.dataPanels.push(data);
					this.flagBuscando++;*/
					this.dataClient = data;
					this.flagBuscando = 1;
					console.log(this.dataClient)
				});
			/* }); */
		},
	},

	data: {
		nombreBoton: "Test Panel",
		/* flagBuscando: [], */
		dataPanels: [],
		listaPaneles: [],
		dataClient: {}
	},
});