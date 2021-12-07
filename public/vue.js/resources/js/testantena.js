Vue.component('testantena',
{
    template://html
    `
    <div>
        <div :class=class_escaneando>
            <h4 class="alert alert-info">Escaneando Antena Cliente</h4>
        </div>
        <div :class=class_apagado>
            <h4 class="alert alert-danger">Equipo apagado o desasociado del Panel</h4>
            <div class="col-12 pl-2">
                <button type="button" class="btn btn-secondary" @click="volver_probar_cliente" >Nueva Prueba</button>
                <a v-bind:href="url" class="btn btn-primary m-1">Volver Abono</a>
            </div>
        </div>
        <div :class="class_escaneado">
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
                        <th>Ruido</th>
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
                    <td>{{NoiseFloor}}</td>
                    </tr>
            </table>
            <table class="table table-sm table-bordered table-hover">
                <caption>Test de radioenlace</caption>
                <thead class="thead-light">
                    <tr>
                        <th>Señal</th>
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
                    <td class="alert alert-success" v-else-if="statusInternet === 1">{{InternetLoss}}</td>
                    <td class="alert alert-warning" v-else-if="statusInternet === 2">{{InternetLoss}}</td>
                    <td v-else>{{InternetLoss}}</td>
                    <td class="alert alert-danger" v-if="statusGatewayAVG === 0">{{gatewayAvg}}</td>
                    <td class="alert alert-success" v-else-if="statusGatewayAVG === 1">{{gatewayAvg}}</td>
                    <td class="alert alert-warning" v-else-if="statusGatewayAVG === 2">{{gatewayAvg}}</td>
                    <td v-else>{{gatewayAvg}}</td>
                    <td class="alert alert-danger" v-if="statusGateway === 0">{{Gateway}}</td>
                    <td class="alert alert-success" v-else-if="statusGateway === 1">{{Gateway}}</td>
                    <td class="alert alert-warning" v-else-if="statusGateway === 2">{{Gateway}}</td>
                    <td v-else>{{gateway}}</td>
                </tr>
            </table>
            <div class="col-12 pl-2">
                <button type="button" class="btn btn-secondary" @click="volver_probar_cliente" >Nueva Prueba</button>
                <a v-bind:href="url" class="btn btn-primary m-1">Volver Abono</a>
            </div>
        </div>
    </div>
    `,
    data(){
        return{
            url: '/adminContratos?contrato=' + contrato,
            CCQ: '',
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
            class_apagado: 'ocultar'
        }
    },
    mounted(){
        this.escanearCliente();
    },
    beforeUpdate(){

    },
    computed:{

    },
    watch: {

    },
    methods: {
        escanearCliente: function () {
            fetch('http://' + website + '/contractTest/' + contrato)
                .then(response => response.json())
                .then(data => {
                    if (data.status)
                    {

                        this.CCQ = data.CCQ;
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