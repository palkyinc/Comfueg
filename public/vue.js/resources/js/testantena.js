Vue.component('testantena',
{
    template://html
    `
    <div>
        <div :class=class_escaneando>
            <h4 class="alert alert-info">Escaneando Antena Cliente</h4>
        </div>
        <div :class=class_apagado>
            <h4 class="alert alert-danger">Equipo apagado, desasociado del Panel o demora mucho en responder.</h4>
            <div class="col-12 pl-2">
                <button type="button" class="btn btn-secondary" @click="volver_probar_cliente" >Nueva Prueba</button>
                <a v-bind:href="url_volver" class="btn btn-primary m-1">Volver Abono</a>
            </div>
        </div>
        <div :class="class_escaneado" class="border">
            
            <p>Resultados:</p>
            <div :class="class_error_macadress" class="alert alert-danger">ATENCIÓN personal técnico: Al parecer el equipo Cliente fue reemplazado y no fue actualizado en SLAM.</div>
            <div :class="class_error_ssid" class="alert alert-danger">ATENCIÓN personal técnico: Al parecer el equipo fue cambiado de Panel y no fue actualizado en SLAM.</div>
            <div :class="class_comentarios_equipo" class="alert alert-danger" >ATENCIÓN personal técnico: Equipo cliente, {{comentarios_equipo}}</div>
                
            <table class="table table-sm table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Uptime</th>
                        <th>Temperatura</th>
                        <th>Nombre</th>
                        <th>MacAddress</th>
                        <th>Firmware</th>
                        <th>Modelo</th>
                        <th>Role</th>
                        <th>SSID</th>
                        <th>Frecuencia</th>
                        <th>Pot. TX</th>
                    </tr>
                </thead>
                    <tr>
                        <td>{{Uptime}}</td>
                        <td>{{Temperature}}</td>
                        <td>{{Hostname}}</td>
                        <td>{{MacAdrress}}</td>
                        <td>{{Firmware}}</td>
                        <td>{{DevModel}}</td>
                        <td>{{NetRole}}</td>
                        <td>{{SSID}}</td>
                        <td>{{Frecuency}}</td>
                        <td>{{TxPower}}dBm</td>
                    </tr>
            </table>
            <table class="table table-sm table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Antena</th>
                        <th>Panel</th>
                        <th>CCQ</th>
                        <th>Uso CPU</th>
                        <th>MEM Libre</th>
                        <th>TX</th>
                        <th>RX</th>
                        <th>LAN</th>
                        <th>Internet AVG</th>
                        <th>Internet Perdidos</th>
                        <th>Gateway AVG</th>
                        <th>Gateway Perdidos</th>
                    </tr>
                </thead>
                <tr>
                    <td class="alert alert-danger" v-if="statusSignal === 0">{{Signal}}</td>
                    <td class="alert alert-success" v-else-if="statusSignal === 1">{{Signal}}</td>
                    <td class="alert alert-warning" v-else-if="statusSignal === 2">{{Signal}}</td>
                    <td v-else>{{Signal}}</td>
                    <td class="alert alert-danger" v-if="statusRemote === 0">{{Remote}}</td>
                    <td class="alert alert-success" v-else-if="statusRemote === 1">{{Remote}}</td>
                    <td class="alert alert-warning" v-else-if="statusRemote === 2">{{Remote}}</td>
                    <td v-else>{{Remote}}</td>
                    <td class="alert alert-danger" v-if="statusCCQ === 0">{{CCQ}}</td>
                    <td class="alert alert-success" v-else-if="statusCCQ === 1">{{CCQ}}</td>
                    <td class="alert alert-warning" v-else-if="statusCCQ === 2">{{CCQ}}</td>
                    <td v-else>{{CCQ}}</td>
                    <td class="alert alert-danger" v-if="statusCpuUse === 0">{{CpuUse}}</td>
                    <td class="alert alert-success" v-else-if="statusCpuUse === 1">{{CpuUse}}</td>
                    <td class="alert alert-warning" v-else-if="statusCpuUse === 2">{{CpuUse}}</td>
                    <td v-else>{{CpuUse}}</td>
                    <td class="alert alert-danger" v-if="statusMemFree === 0">{{MemFree}}</td>
                    <td class="alert alert-success" v-else-if="statusMemFree === 1">{{MemFree}}</td>
                    <td class="alert alert-warning" v-else-if="statusMemFree === 2">{{MemFree}}</td>
                    <td v-else>{{MemFree}}</td>
                    <td class="alert alert-danger" v-if="statusTX === 0">{{TX}}</td>
                    <td class="alert alert-success" v-else-if="statusTX === 1">{{TX}}</td>
                    <td class="alert alert-warning" v-else-if="statusTX === 2">{{TX}}</td>
                    <td v-else>{{TX}}</td>
                    <td class="alert alert-danger" v-if="statusRX === 0">{{RX}}</td>
                    <td class="alert alert-success" v-else-if="statusRX === 1">{{RX}}</td>
                    <td class="alert alert-warning" v-else-if="statusRX === 2">{{RX}}</td>
                    <td v-else>{{RX}}</td>
                    <td class="alert alert-danger" v-if="statusLan === 0">{{LanSpeed}}</td>
                    <td class="alert alert-success" v-else-if="statusLan === 1">{{LanSpeed}}</td>
                    <td class="alert alert-warning" v-else-if="statusLan === 2">{{LanSpeed}}</td>
                    <td v-else>{{LanSpeed}}</td>
                    <td class="alert alert-danger" v-if="statusInternetAVG === 0">{{InternetAvg}}</td>
                    <td class="alert alert-success" v-else-if="statusInternetAVG === 1">{{InternetAvg}}</td>
                    <td class="alert alert-warning" v-else-if="statusInternetAVG === 2">{{InternetAvg}}</td>
                    <td v-else>{{InternetAvg}}</td>
                    <td class="alert alert-danger" v-if="statusInternet === 0">SIN INTERNET</td>
                    <td class="alert alert-success" v-else-if="statusInternet === 1">{{InternetLoss}}%</td>
                    <td class="alert alert-warning" v-else-if="statusInternet === 2">{{InternetLoss}}%</td>
                    <td v-else>{{InternetLoss}}</td>
                    <td class="alert alert-danger" v-if="statusGatewayAVG === 0">{{gatewayAvg}}</td>
                    <td class="alert alert-success" v-else-if="statusGatewayAVG === 1">{{gatewayAvg}}</td>
                    <td class="alert alert-warning" v-else-if="statusGatewayAVG === 2">{{gatewayAvg}}</td>
                    <td v-else>{{gatewayAvg}}</td>
                    <td class="alert alert-danger" v-if="statusGateway === 0">{{Gateway}}</td>
                    <td class="alert alert-success" v-else-if="statusGateway === 1">{{Gateway}}%</td>
                    <td class="alert alert-warning" v-else-if="statusGateway === 2">{{Gateway}}%</td>
                    <td v-else>{{Gateway}}</td>
                </tr>
            </table>
            <div>
                <div :class="class_pruebas_anteriores">
                    <p>Historial:</p>
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Nombre</th>
                                <th>MacAddress</th>
                                <th>Firmware</th>
                                <th>Modelo</th>
                                <th>SSID</th>
                                <th>Pot. TX</th>
                                <th>Antena</th>
                                <th>Panel</th>
                                <th>CCQ</th>
                                <th>TX</th>
                                <th>RX</th>
                                <th>LAN</th>
                                <th>Internet AVG</th>
                                <th>Internet Perdidos</th>
                                <th>Gateway AVG</th>
                                <th>Gateway Perdidos</th>
                            </tr>
                        </thead>
                            <tr v-for="historial_prueba in historial_pruebas">
                                <td>{{historial_prueba.created_at.split("T")[0]}}</td>
                                <td>{{historial_prueba.nom_equipo}}</td>
                                <td>{{historial_prueba.mac_address}}</td>
                                <td>{{historial_prueba.firmware}}</td>
                                <td>{{historial_prueba.dispositivo}}</td>
                                <td>{{historial_prueba.ssid}}</td>
                                <td>agregar</td>
                                <td>{{historial_prueba.senial}}</td>
                                <td>{{historial_prueba.remote}}</td>
                                <td>CCQ</td>
                                <td>TX</td>
                                <td>RX</td>
                                <td>{{historial_prueba.lan_velocidad}}</td>
                                <td>{{historial_prueba.internet_avg}}ms</td>
                                <td>{{historial_prueba.internet_lost}}%</td>
                                <td v-if="historial_prueba.wispro_avg">{{historial_prueba.wispro_avg}}ms</td>
                                <td v-else></td>
                                <td v-if="historial_prueba.wispro_lost">{{historial_prueba.wispro_lost}}%</td>
                                <td v-else></td>
                            </tr>
                    </table>
                    <button type="button" class="btn btn-secondary m-1" @click="cerrar_pruebas_anteriores" >Cerrar Pruebas Anteriores</button>
                </div>
                
                <div>
                    <p>Evaluación:</p>
                    <div class="alert alert-danger" v-if="statusSignal === 0 || statusRemote === 0">Radioenlace: Baja señal, se deberá revisar orientación/obstrucciones.</div>
                    <div class="alert alert-success" v-else-if="statusSignal === 1 && statusRemote === 1">Señal radioenlace OK</div>
                    <div class="alert alert-info" v-else>Señal radioenlace Bien.</div>
                    
                    <div v-if="!(statusSignal === 0 || statusRemote === 0)">
                        <div class="alert alert-danger" v-if="statusGatewayAVG === 0 && !(statusSignal === 0 || statusRemote === 0)">Se deberá revisar enlace Panel - Antena Cliente (Mucha Latencia a Gateway).</div>
                        <div class="alert alert-success" v-else-if="statusInternetAVG === 0">Mucha Latencia, ver consumos instantaneos.</div>
                        <div class="alert alert-success" v-else-if="statusInternetAVG === 1">Salida internet OK.</div>
                        <div class="alert alert-info" v-else>Salida internet Bien.</div>
                    </div>
                    
                    <div class="alert alert-danger" v-if="statusLan === 0">El Router WiFi de cliente está desconectado o apagado.</div>
                    <div class="alert alert-danger" v-if="statusLan === 2">Reiniciar Router WiFi y Antena del Cliente.</div>
                </div>

            </div>
            <div class="col-12 pl-2">
                <button type="button" class="btn btn-secondary m-1" @click="volver_probar_cliente" >Nueva Prueba</button>
                <a v-bind:href="url_volver" class="btn btn-primary m-1">Volver Abono</a>
                <button type="button" class="btn btn-primary m-1" @click="pruebas_anteriores" >Pruebas Anteriores</button>
                <a :href="url_antena" class="btn btn-primary m-1" target="_blank">Ir Antena</a>
                <button class="btn btn-primary m-1" disabled>Reiniciar Antena</button>
                <a :href="url_panel" class="btn btn-primary m-1" target="_blank">Ir {{panel_nombre}}</a>
                <a href="#" class="btn btn-primary m-1">Ver TKT´s(Total/Abiertos)</a>
            </div>
        </div>
    </div>
    `,
    data(){
        return{
            url_volver: '/adminContratos?contrato=' + contrato,
            url_panel: '',
            url_antena: '',
            CCQ: '',
            TxPower: '',
            ChannelWidth: '',
            CpuUse: '',
            DevModel: '',
            Firmware: '',
            Frecuency: '',
            Gateway: '',
            Hostname: '',
            InternetAvg: '',
            InternetLoss: '',
            LanSpeed: '',
            MacAdrress: '',
            MemFree: '',
            NetRole: '',
            NoiseFloor: '',
            RX: '',
            Remote: '',
            SSID: '',
            Signal: '',
            TX: '',
            Temperature: '',
            Uptime: '',
            gatewayAvg: '',
            status: '',
            statusCCQ: '',
            statusCpuUse: '',
            statusGateway: '',
            statusInternet: '',
            statusLan: '',
            statusMemFree: '',
            statusRX: '',
            statusRemote: '',
            statusSignal: '',
            statusTX: '',
            statusInternetAVG: '',
            statusGatewayAVG: '',
            class_escaneado: 'ocultar',
            class_escaneando: '',
            class_apagado: 'ocultar',
            class_error_macadress: 'ocultar',
            class_error_ssid: 'ocultar',
            class_pruebas_anteriores: 'ocultar',
            contrato_datos: '',
            historial_pruebas: '',
            comentarios_equipo: '',
            panel_nombre: ''
        }
    },
    mounted(){
        this.getContrato();
        this.escanearCliente();
    },
    beforeUpdate(){

    },
    computed:{

    },
    watch: {
        gatewayAvg: function () {
            this.gatewayAvg = this.gatewayAvg ? this.gatewayAvg : 'No testeado';
        },
        Gateway: function () {
            this.Gateway = this.Gateway ? this.Gateway : 'No testeado';
        },
        MacAdrress: function() {
            if (this.MacAdrress != this.contrato_datos.num_equipo.mac_address) {
                this.class_error_macadress = '';
            }else {
                this.class_error_macadress = 'ocultar';
            }
        },
        comentarios_equipo: function() {
            if (this.comentarios_equipo) {
                this.class_comentarios_equipo = '';
            }else {
                this.class_comentarios_equipo = 'ocultar';
            }
        },
        SSID: function () {
            if (this.SSID != this.contrato_datos.num_panel.nombre) {
                this.class_error_ssid = '';
            } else {
                this.class_error_ssid = 'ocultar';
            }
        },
        contrato_datos: function () {
            this.url_panel = 'https://' + this.contrato_datos.num_panel.ip;
            this.url_antena = 'http://' + this.contrato_datos.num_equipo.ip;
            this.panel_nombre = this.contrato_datos.num_panel.nombre;
            this.comentarios_equipo = this.contrato_datos.num_equipo.comentario;
        }
    },
    methods: {
        cerrar_pruebas_anteriores: function() {
            this.class_pruebas_anteriores = 'ocultar';
        },
        pruebas_anteriores: function() {
            this.class_pruebas_anteriores = '';
            fetch('http://' + website + '/getPruebasContract/' + contrato)
                .then(response => response.json())
                .then(data => {
                    this.historial_pruebas = data;
                    console.log(this.historial_pruebas);
                })
        },
        getContrato: function () {
            fetch('http://' + website + '/getContract/' + contrato)
                .then(response => response.json())
                .then(data => {
                    this.contrato_datos = data;
                    console.log(data);
                })
        },
        escanearCliente: function () {
            fetch('http://' + website + '/contractTest/' + contrato)
                .then(response => response.json())
                .then(data => {
                    if (data.status)
                    {

                        this.CCQ = data.CCQ;
                        this.TxPower = data.TxPower;
                        this.ChannelWidth = data.ChannelWidth;
                        this.CpuUse = data.CpuUse;
                        this.DevModel = data.DevModel;
                        this.Firmware = data.Firmware;
                        this.Frecuency = data.Frecuency;
                        this.Gateway = data.Gateway;
                        this.Hostname = data.Hostname;
                        this.InternetAvg = data.InternetAvg;
                        this.InternetLoss = data.InternetLoss;
                        this.LanSpeed = data.LanSpeed;
                        this.MacAdrress = data.MacAdrress;
                        this.MemFree = data.MemFree;
                        this.NetRole = data.NetRole;
                        this.NoiseFloor = data.NoiseFloor;
                        this.RX = data.RX;
                        this.Remote = data.Remote;
                        this.SSID = data.SSID;
                        this.Signal = data.Signal;
                        this.TX = data.TX;
                        this.Temperature = data.Temperature;
                        this.Uptime = data.Uptime;
                        this.gatewayAvg = data.gatewayAvg;
                        this.status = data.status;
                        this.statusCCQ = data.statusCCQ;
                        this.statusCpuUse = data.statusCpuUse;
                        this.statusGateway = data.statusGateway;
                        this.statusInternet = data.statusInternet;
                        this.statusLan = data.statusLan;
                        this.statusMemFree = data.statusMemFree;
                        this.statusRX = data.statusRX;
                        this.statusRemote = data.statusRemote;
                        this.statusSignal = data.statusSignal;
                        this.statusTX = data.statusTX;
                        this.statusInternetAVG = data.statusInternetAVG;
                        this.statusGatewayAVG = data.statusGatewayAVG;
                        this.class_escaneado = '';
                        this.class_escaneando = 'ocultar';
                        this.class_apagado = 'ocultar';
                    } else {
                        this.class_escaneado = 'ocultar';
                        this.class_escaneando = 'ocultar';
                        this.class_apagado = '';
                    }
                    
            })
        },
        volver_probar_cliente: function() {
            this.class_escaneado = 'ocultar';
            this.class_escaneando = '';
            this.class_apagado = 'ocultar';
            this.escanearCliente();
        }
    }
})