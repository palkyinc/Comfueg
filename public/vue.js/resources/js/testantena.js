Vue.component('testantena',
{
    template://html
    `
    <div>
        <div :class=class_escaneando>
            <h4 class="alert alert-info">Escaneando Antena Cliente</h4>
        </div>
        <div :class=class_apagado>
            <h4 class="alert alert-danger">Equipo desasociado del Panel, apagado o demora mucho en responder.</h4>
            <div class="col-12 pl-2">
                <button type="button" class="btn btn-secondary" @click="volver_probar_cliente" >Nueva Prueba</button>
                <a v-bind:href="url_volver" class="btn btn-primary m-1">Volver Abono</a>
            </div>
        </div>
        <div :class=class_mensajes_tecnicos class="border border-2 border-info rounded my-3 mx-0 p-3">
            <h5>Mensajes Técnicos:</h5>
            <div :class="class_error_macadress" class="alert alert-danger">ATENCIÓN personal técnico: Al parecer el equipo Cliente fue reemplazado y no fue actualizado en SLAM.</div>
            <div :class="class_error_ssid" class="alert alert-danger">ATENCIÓN personal técnico: Al parecer el equipo fue cambiado de Panel y no fue actualizado en SLAM.</div>
            <div :class="class_comentarios_equipo" class="alert alert-danger" >ATENCIÓN personal técnico: Equipo cliente, {{comentarios_equipo}}</div>
        </div>
        <div :class="class_escaneado" class="border">
            <div class="border border-2 border-info rounded my-3 mx-0 p-3">
                <h5>Resultados:</h5>
                    
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
                    <div>
                        <h5>Evaluación:</h5>
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
                    <a v-bind:href="url_volver" class="btn btn-primary m-1"  target="_blank">Ver Abono</a>
                    <button type="button" class="btn btn-primary m-1" @click="pruebas_anteriores" >Pruebas Anteriores</button>
                    <a :href="url_antena" class="btn btn-primary m-1" target="_blank">Ir Antena Cliente</a>
                    <button class="btn btn-primary m-1" disabled>Reiniciar Antena</button>
                    <a :href="url_panel" class="btn btn-primary m-1" target="_blank">Ir {{panel_nombre}}</a>
                    <a :href="url_tickets" class="btn btn-primary m-1" target="_blank">Ver TKT´s(Total/Abiertos)</a>
                    <a :href="url_new_ticket" class="btn btn-primary m-1" target="_blank">Nuevo TKT</a>
                </div>
            </div>
            <div class="border border-2 border-info rounded my-3 mx-0 p-3" :class="class_pruebas_anteriores">
                    <h5>Historial:</h5>
                    <table class="table table-responsive-sm table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Tester</th>
                                <th>MacAddress</th>
                                <th>Firm.</th>
                                <th>Mod.</th>
                                <th>SSID</th>
                                <th>Pot. TX</th>
                                <th>Antena</th>
                                <th>Panel</th>
                                <th>CCQ</th>
                                <th>TX</th>
                                <th>RX</th>
                                <th>LAN</th>
                                <th>Int. AVG</th>
                                <th>Int. Per.</th>
                                <th>Gat. AVG</th>
                                <th>Gat. Per.</th>
                            </tr>
                        </thead>
                            <tr v-for="historial_prueba in historial_pruebas">
                                <td>{{historial_prueba.created_at.split("T")[0]}}</td>
                                <td>{{historial_prueba.user_id}}</td>
                                <td v-if="historial_prueba.contactado">{{historial_prueba.mac_address}}</td>
                                <td v-else class="alert alert-danger">No Contactado</td>
                                <td>{{historial_prueba.firmware}}</td>
                                <td>{{historial_prueba.dispositivo}}</td>
                                <td>{{historial_prueba.ssid}}</td>
                                <td>agregar</td>
                                <td>{{historial_prueba.senial}}</td>
                                <td>{{historial_prueba.remote}}</td>
                                <td>{{historial_prueba.ccq}}</td>
                                <td>{{historial_prueba.tx}}</td>
                                <td>{{historial_prueba.rx}}</td>
                                <td>{{historial_prueba.lan_velocidad}}</td>
                                <td v-if="historial_prueba.internet_avg!=''">{{historial_prueba.internet_avg}}ms</td>
                                <td v-else></td>
                                <td v-if="historial_prueba.internet_lost!=''">{{historial_prueba.internet_lost}}%</td>
                                <td v-else></td>
                                <td v-if="historial_prueba.wispro_avg!=''">{{historial_prueba.wispro_avg}}ms</td>
                                <td v-else></td>
                                <td v-if="historial_prueba.wispro_lost!=''">{{historial_prueba.wispro_lost}}%</td>
                                <td v-else></td>
                            </tr>
                    </table>
                    <button type="button" class="btn btn-secondary m-1" @click="cerrar_pruebas_anteriores" >Cerrar Pruebas Anteriores</button>
                </div>
        </div>
    </div>
    `,
    data(){
        return{
            url_volver: '/adminContratos?contrato=' + contrato,
            url_new_ticket: '/agregarIssue?contrato_id=' + contrato,
            url_panel: '',
            url_antena: '',
            url_tickets: '',
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
            class_comentarios_equipo: 'ocultar',
            class_mensajes_tecnicos: 'ocultar',
            contrato_datos: '',
            historial_pruebas: null,
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
                this.class_mensajes_tecnicos = '';
            }else {
                this.class_error_macadress = 'ocultar';
            }
        },
        comentarios_equipo: function() {
            if (this.comentarios_equipo) {
                this.class_comentarios_equipo = '';
                this.class_mensajes_tecnicos = '';
            }else {
                this.class_comentarios_equipo = 'ocultar';
            }
        },
        SSID: function () {
            if (this.SSID != this.contrato_datos.num_panel.ssid) {
                this.class_error_ssid = '';
                this.class_mensajes_tecnicos = '';
            } else {
                this.class_error_ssid = 'ocultar';
            }
        },
        contrato_datos: function () {
            this.url_panel = 'https://' + this.contrato_datos.num_panel.id_equipo.ip;
            this.url_antena = 'http://' + this.contrato_datos.num_equipo.ip;
            this.panel_nombre = this.contrato_datos.num_panel.ssid;
            this.comentarios_equipo = this.contrato_datos.num_equipo.comentario;
            this.url_tickets = 'http://' + website + '/adminIssues?rebusqueda=on&usuario=todos&contrato=' + this.contrato_datos.id;
        }
    },
    methods: {
        cerrar_mensajes_tecnicos: function() {
            
        },
        cerrar_pruebas_anteriores: function() {
            this.class_pruebas_anteriores = 'ocultar';
        },
        pruebas_anteriores: function() {
            if (!this.historial_pruebas){
                this.class_pruebas_anteriores = '';
                fetch('http://' + website + '/getPruebasContract/' + contrato)
                    .then(response => response.json())
                    .then(data => {
                        this.historial_pruebas = data;
                    })
            } else {
                this.class_pruebas_anteriores = this.class_pruebas_anteriores ? '' : 'ocultar';
            }
        },
        getContrato: function () {
            fetch('http://' + website + '/getContract/' + contrato)
                .then(response => response.json())
                .then(data => {
                    this.contrato_datos = data;
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