const vm = new Vue({
	el: "#vista",
	mounted: function() {
			fetch('http://comfueg.ar/allPanels')
			.then(response => response.json())
			.then(datos => {
				this.listaPaneles = datos;
				//console.log(this.listaPaneles);
				this.checkPanel();
			});
	},
	methods: {
		checkPanel: function(){
			const tabla = document.getElementById('tablaRender');
			for (let i = tabla.rows.length - 1; i > 0 ; i--) {
				tabla.deleteRow(i);
			}
			this.listaPaneles.forEach(element => {
				this.flagBuscando--;
				fetch('http://comfueg.ar/panelTest/' + element.ip)
				.then(response => response.json())
				.then( data => {
					data.ip = element.ip;
					data.sitio = element.sitio;
					data.HostnameDb = element.Hostname;
					this.dataPanels.push(data);
					this.flagBuscando++;
				});
			});
		},
	},

	data: {
		nombreBoton: "Test Panel",
		flagBuscando: 1,
		dataPanels: [],
		listaPaneles: [],
		dataPanel: {}
	},
});