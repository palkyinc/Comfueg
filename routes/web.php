<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AntenaController;
use App\Http\Controllers\BarrioController;
use App\Http\Controllers\CalleController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CodigoDeAreaController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\DireccionController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\GatewayInterfaceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Mail_groupController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\NodoController;
use App\Http\Controllers\Panel_has_barrioController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiteHasIncidenteController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\Contract_typeController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\Issue_titleController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\Info_ClienteController;
use App\Http\Controllers\AltaController;
use App\Http\Controllers\BtfDebitoController;
use App\Http\Controllers\Concepto_debitoController;
use App\Http\Controllers\MacAddressExceptionController;
use App\Http\Controllers\ConfigPanelController;

####TEST
/* 
use App\Models\Contrato; //TEST
use App\Custom\GatewayMikrotik;//TEST
use App\Custom\CronFunciones;//TEST
use Illuminate\Support\Facades\File; //TEST
use Illuminate\Support\Facades\Storage; //TEST
use App\Models\Proveedor;//TEST
use App\Models\Panel;//TEST
use App\Models\Equipo; //TEST
use Illuminate\Support\Facades\Mail;//TEST
use App\Models\Cliente; //TEST
use App\Custom\Ubiquiti;
use App\Models\Mail_group;
use Illuminate\Support\Facades\File;
use App\Models\Contadores_mensuales;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
*/



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
## Route Inicial Default
/* Route::get('/readDay', function () {
        dd(CronFunciones::readDay());
        }); */
/* Route::get('/archivoSem/{dias}', function ($dias) {
dd(CronFunciones::generarArchivoSem($dias));
}); */

/* Route::get('/auditPaneles', function () {
        //CronFunciones::bkpPaneles();
        CronFunciones::audoriaPaneles();
        //CronFunciones::logError(['clase' => 'routes/web.php', 'metodo' => 'sarasa', 'error' => 'Funciona OK.']);
        //CronFunciones::enviarErrorsMail();
        dd('Fin Sarasa.');
}); */

/* Route::view('/welcome', 'index'); */
/* Route::get('/test/', function (){
        $contrato = (Contrato::find(9498));
        dd($contrato->changeStateContratoGateway());
        $apiMikro = GatewayMikrotik::getConnection($contrato->relPlan->relPanel->relEquipo->ip, $contrato->relPlan->relPanel->relEquipo->getUsuario(), $contrato->relPlan->relPanel->relEquipo->getPassword());
        if ($apiMikro)
        {
                $apiMikro->checkDhcpServer($contrato->relPlan->relPanel->relEquipo->ip);
        } else {
                dd('Error');
        }
}); */

### Route index
Route::get('/', [Info_ClienteController::class, 'index']);
Route::get('/inicio', [Info_ClienteController::class, 'index2'])->middleware('auth');
/* Route::get('/charts', function (){dd('Hola Domun');return view('charts');}); */
####################
####### Config Panels
Route::get('/adminConfigPanels', [ConfigPanelController::class, 'index']); //->middleware('auth');
Route::get('/downloadConfigPanel/{filename}', [ConfigPanelController::class, 'download'])->name('downloadConfigPanel'); //->middleware('auth');
####################
####### Gateway API rest Web services
//Route::get('/gateway/clients/{ip}', [PruebaController::class, 'testPanel'])->middleware('auth');
Route::get('/ipLibre/{ip}', [EquipoController::class, 'ipLibre'])->middleware('auth');
Route::get('/panelTest/{ip}', [PruebaController::class, 'testPanel'])->middleware('auth');
Route::get('/clientTest/{ip}', [PruebaController::class, 'testClient'])->middleware('auth');
Route::get('/contractTest/{contrato}', [PruebaController::class, 'testContract'])->middleware('auth');
Route::get('/getContract/{contrato}', [ContratoController::class, 'getContract'])->middleware('auth');
Route::get('/getPruebasContract/{contrato}', [PruebaController::class, 'getPruebasContract'])->middleware('auth');
Route::get('/allPanels', [PruebaController::class, 'allPanels'])->middleware('auth');
Route::get('/adminControlPanelNodos', [PruebaController::class, 'index'])->middleware('auth');
Route::get('/adminPanelLogs', [PruebaController::class, 'indexLogs'])->middleware('auth');
Route::put('/Session', [SessionController::class, 'store'])->middleware('auth');
Route::get('/Session/{id}', [SessionController::class, 'show'])->middleware('auth');
Route::delete('/Session/{id}', [SessionController::class, 'destroy'])->middleware('auth');
Route::get('/SessionDeleteAll', [SessionController::class, 'destroyAll'])->middleware('auth');
####################
####### Info Clientes
Route::get('/infoClientes', [Info_ClienteController::class, 'index']);
####################
####### CRUD Conceptos Debitos
Route::get('/adminConceptoDebitos', [Concepto_debitoController::class, 'index'])->middleware('auth');
Route::get('/agregarConceptoDebito', [Concepto_debitoController::class, 'create'])->middleware('auth');
Route::post('/agregarConceptoDebito', [Concepto_debitoController::class, 'store'])->middleware('auth');
Route::get('/modificarConceptoDebitos/{id}', [Concepto_debitoController::class, 'edit'])->middleware('auth');
Route::patch('/modificarConceptoDebitos', [Concepto_debitoController::class, 'update'])->middleware('auth');
Route::patch('/habilitarConceptoDebitos', [Concepto_debitoController::class, 'enable'])->middleware('auth');
Route::delete('/deshabilitarConceptoDebitos', [Concepto_debitoController::class, 'unable'])->middleware('auth');
####################
####### Contract types
Route::get('/adminContractTypes', [Contract_typeController::class, 'index'])->middleware('auth');
Route::get('/agregarContractType', [Contract_typeController::class, 'create'])->middleware('auth');
Route::post('/agregarContractType', [Contract_typeController::class, 'store'])->middleware('auth');
Route::get('/modificarContractType/{id}', [Contract_typeController::class, 'edit'])->middleware('auth');
Route::patch('/modificarContractType', [Contract_typeController::class, 'update'])->middleware('auth');
####### Contract types API REST
Route::get('/ContractTypes', [Contract_typeController::class, 'indexRest'])->middleware('auth');
####################
####### Issues Titles
Route::get('/adminIssuesTitles', [Issue_titleController::class, 'index'])->middleware('auth');
Route::get('/agregarIssueTitle', [Issue_titleController::class, 'create'])->middleware('auth');
Route::post('/agregarIssueTitle', [Issue_titleController::class, 'store'])->middleware('auth');
Route::get('/modificarIssueTitle/{id}', [Issue_titleController::class, 'edit'])->middleware('auth');
Route::patch('/modificarIssueTitle', [Issue_titleController::class, 'update'])->middleware('auth');
####################
####### Panel tiene Barrio CRUD
Route::get('/adminPanelhasBarrio', [Panel_has_barrioController::class, 'index'])->middleware('auth');
Route::get('/modificarPanelHasBarrio/{id}', [Panel_has_barrioController::class, 'edit'])->middleware('auth');
Route::patch('/modificarPanelHasBarrio', [Panel_has_barrioController::class, 'update'])->middleware('auth');
####################
####### Issue
Route::get('/adminIssues', [IssueController::class, 'index'])->middleware('auth');
Route::get('/agregarIssue', [IssueController::class, 'create'])->middleware('auth');
Route::post('/agregarIssue', [IssueController::class, 'store'])->middleware('auth');
Route::post('/agregarIssueSuspend', [IssueController::class, 'storeSuspend'])->middleware('auth');
Route::post('/buscarIssueCliente', [IssueController::class, 'buscarCliente'])->middleware('auth');
Route::get('/modificarIssue/{id}', [IssueController::class, 'edit'])->middleware('auth');
Route::get('/suspenderIssue/{id}/{titulo_id}', [IssueController::class, 'createSuspend'])->middleware('auth');
Route::patch('/modificarIssue', [IssueController::class, 'update'])->middleware('auth');
Route::get('/listadoIssues', [IssueController::class, 'getListadoIssues'])->middleware('auth');
####################
####### Deuda
Route::get('/adminDeudasTecnica', [SiteHasIncidenteController::class, 'indexDeuda'])->middleware('auth');
Route::get('/adminDeudasRebusqueda', [SiteHasIncidenteController::class, 'indexDeudasRebusqueda'])->middleware('auth');
Route::get('/agregarSiteHasDeuda', [SiteHasIncidenteController::class, 'createDeuda'])->middleware('auth');
Route::post('/agregarSiteHasDeuda', [SiteHasIncidenteController::class, 'storeDeuda'])->middleware('auth');
Route::get('/modificarSiteHasDeuda/{id}', [SiteHasIncidenteController::class, 'editDeuda'])->middleware('auth');
Route::patch('/modificarSiteHasDeuda', [SiteHasIncidenteController::class, 'updateDeuda'])->middleware('auth');
Route::get('/adminArchivosDeuda/{id}', [SiteHasIncidenteController::class, 'editArchivosIncidente'])->middleware('auth');
####################
####### Inicidencias
Route::get('/adminIncidencias', [SiteHasIncidenteController::class, 'index'])->middleware('auth');
Route::get('/adminIncidenciasRebusqueda', [SiteHasIncidenteController::class, 'indexRebusqueda'])->middleware('auth');
Route::get('/agregarSiteHasIncidente', [SiteHasIncidenteController::class, 'create'])->middleware('auth');
Route::post('/agregarSiteHasIncidente', [SiteHasIncidenteController::class, 'store'])->middleware('auth');
Route::get('/modificarSiteHasIncidente/{id}', [SiteHasIncidenteController::class, 'edit'])->middleware('auth');
Route::post('/modificarSiteHasIncidente', [SiteHasIncidenteController::class, 'update'])->middleware('auth');
Route::get('/adminArchivosIncidente/{id}', [SiteHasIncidenteController::class, 'editArchivosIncidente'])->middleware('auth');
Route::delete('/eliminarArchivoIncidente/{id}', [SiteHasIncidenteController::class, 'destroyArchivo'])->middleware('auth');
Route::get('/agregarArchivoIncidente/{id}', [SiteHasIncidenteController::class, 'createArchivoIncidente'])->middleware('auth');
Route::patch('/adminArchivosIncidente', [SiteHasIncidenteController::class, 'updateArchivoIncidente'])->middleware('auth');
####################
####### Nodos
Route::get('/adminNodos', [NodoController::class, 'index'])->middleware('auth');
Route::get('/mostrarNodo/{id}', [NodoController::class, 'showNodo'])->middleware('auth');
Route::get('/cambiarFileSitio/{id}', [NodoController::class, 'editFileSitio'])->middleware('auth');
Route::patch('/cambiarFileSitio', [NodoController::class, 'updateFileSitio'])->middleware('auth');
Route::get('/cambiarFilePanel/{panel_id}/{sitio_id}', [NodoController::class, 'editFilePanel'])->middleware('auth');
Route::patch('/cambiarFilePanel', [NodoController::class, 'updateFilePanel'])->middleware('auth');
Route::get('/agregarArchivoSitio/{id}', [NodoController::class, 'createArchivoSitio'])->middleware('auth');
Route::get('/adminArchivosSitio/{id}', [NodoController::class, 'editArchivosSitio'])->middleware('auth');
Route::delete('/eliminarArchivo/{archivo_id}/{sitio_id}', [NodoController::class, 'destroyArchivo'])->middleware('auth');
Route::patch('/adminArchivosSitio', [NodoController::class, 'updateArchivoSitio'])->middleware('auth');
####################
####### CRUD Mail Group
Route::get('/adminMailGroups', [Mail_groupController::class, 'index'])->middleware('auth');
Route::get('/agregarMail_group', [Mail_groupController::class, 'create'])->middleware('auth');
Route::post('/agregarMail_group', [Mail_groupController::class, 'store'])->middleware('auth');
Route::get('/modificarMail_group/{id}', [Mail_groupController::class, 'edit'])->middleware('auth');
Route::patch('/modificarMail_group', [Mail_groupController::class, 'update'])->middleware('auth');
Route::get('/agregarUsersToMail_group/{id}', [Mail_groupController::class, 'show'])->middleware('auth');
Route::patch('/agregarUsersToMail_group', [Mail_groupController::class, 'updateUsersToMail_group'])->middleware('auth');
####################
####### CRUD Modelos
Route::get('/adminModelos', [ModeloController::class, 'index'])->middleware('auth');
Route::get('/agregarModelo', [ModeloController::class, 'create'])->middleware('auth');
Route::post('/agregarModelo', [ModeloController::class, 'store'])->middleware('auth');
Route::get('/modificarModelo/{id}', [ModeloController::class, 'edit'])->middleware('auth');
####################
####### CRUD Excepciones
Route::get('/adminExceptions', [MacAddressExceptionController::class, 'index'])->middleware('auth');
Route::get('/agregarException/{id}', [MacAddressExceptionController::class, 'create'])->middleware('auth');
Route::post('/agregarException', [MacAddressExceptionController::class, 'store'])->middleware('auth');
Route::delete('/borrarException', [MacAddressExceptionController::class, 'destroy'])->middleware('auth');####################
#####################
####### CRUD Contratos
Route::get('/adminContratos', [ContratoController::class, 'index'])->middleware('auth');
Route::get('/listadoContratos', [ContratoController::class, 'getListadoContratosactivos'])->middleware('auth');
Route::get('/listadoContratosNoActivos', [ContratoController::class, 'getListadoContratosNoActivos'])->middleware('auth');
Route::get('/listadoContratosFull', [ContratoController::class, 'getListadoContratosActivosFull'])->middleware('auth');
Route::get('/agregarContrato', [ContratoController::class, 'create'])->middleware('auth');
Route::post('/agregarContrato', [ContratoController::class, 'store'])->middleware('auth');
Route::get('/modificarContrato/{id}', [ContratoController::class, 'edit'])->middleware('auth');
Route::get('/testContrato/{id}', [ContratoController::class, 'test'])->middleware('auth');
Route::patch('/modificarContrato', [ContratoController::class, 'update'])->middleware('auth');
Route::patch('/realtaContrato', [ContratoController::class, 'undestroy'])->middleware('auth');
Route::delete('/eliminarContrato', [ContratoController::class, 'destroy'])->middleware('auth');
Route::get('/altaContrato', [ContratoController::class, 'vueIndex'])->middleware('auth');
### API-Rest Altas
Route::put('/guardarContrato', [ContratoController::class, 'storeContractFromAlta'])->middleware('auth');
####################
####### CRUD Altas
Route::get('/adminAltas', [AltaController::class, 'index'])->middleware('auth');
Route::post('/modificarAlta', [AltaController::class, 'updateInstallDate'])->middleware('auth');
Route::get('/modificarAlta/{id}', [AltaController::class, 'vueIndex2'])->middleware('auth');
Route::put('/programarAlta', [AltaController::class, 'vueIndexProgramarAlta'])->middleware('auth');
Route::get('/agregarAlta', [AltaController::class, 'vueIndex2'])->middleware('auth');
### API-Rest Altas
Route::put('/agregarAlta', [AltaController::class, 'storeApi'])->middleware('auth');
Route::patch('/agregarAlta', [AltaController::class, 'updateApi'])->middleware('auth');
Route::patch('/anularAlta', [AltaController::class, 'cancelApi'])->middleware('auth');
Route::get('/getAlta/{id}', [AltaController::class, 'getAltaPorId'])->middleware('auth');
####################
####### CRUD Antenas
Route::get('/adminAntenas', [AntenaController::class, 'index'])->middleware('auth');
Route::get('/modificarAntena/{id}', [AntenaController::class, 'edit'])->middleware('auth');
Route::patch('/modificarAntena', [AntenaController::class, 'update'])->middleware('auth');
Route::get('/agregarAntena', [AntenaController::class, 'create'])->middleware('auth');
Route::post('/agregarAntena', [AntenaController::class, 'store'])->middleware('auth');
####################
####### CRUD btfDebitos
Route::get('/adminBtfDebitos', [BtfDebitoController::class, 'index'])->middleware('auth');
Route::get('/modificarBtfDebitos/{id}', [BtfDebitoController::class, 'edit'])->middleware('auth');
Route::patch('/modificarBtfDebitos', [BtfDebitoController::class, 'update'])->middleware('auth');
Route::patch('/habilitarBtfDebito', [BtfDebitoController::class, 'enable'])->middleware('auth');
Route::delete('/deshabilitarBtfDebito', [BtfDebitoController::class, 'disable'])->middleware('auth');
Route::get('/agregarBtfDebito', [BtfDebitoController::class, 'create'])->middleware('auth');
Route::get('/agregarBtfDebito/{id}', [BtfDebitoController::class, 'create_ext'])->middleware('auth');
Route::put('/agregarBtfDebito', [BtfDebitoController::class, 'createClienteId'])->middleware('auth');
Route::post('/agregarBtfDebito', [BtfDebitoController::class, 'store'])->middleware('auth');
Route::get('/presentarBtfDebito', [BtfDebitoController::class, 'getPresentacion'])->middleware('auth');
####################
### API-Rest Planes
Route::get('/getAntenas', [AntenaController::class, 'getAllAntenas'])->middleware('auth');
####################
####### CRUD Barrios
Route::get('/adminBarrios', [BarrioController::class, 'index'])->middleware('auth');
Route::get('/searchBarrios', [BarrioController::class, 'search'])->middleware('auth');
Route::get('/modificarBarrio/{id}', [BarrioController::class, 'edit'])->middleware('auth');
Route::patch('/modificarBarrio', [BarrioController::class, 'update'])->middleware('auth');
Route::get('/agregarBarrio', [BarrioController::class, 'create'])->middleware('auth');
Route::post('/agregarBarrio', [BarrioController::class, 'store'])->middleware('auth');
Route::get('/updateBarrio', [BarrioController::class, 'updateGeneral'])->middleware('auth');
Route::get('/checkBarrio', [BarrioController::class, 'checkArchivo'])->middleware('auth');
####################
####### CRUD Calles
Route::get('/adminCalles', [CalleController::class, 'index'])->middleware('auth');
Route::get('/searchCalles', [CalleController::class, 'search'])->middleware('auth');
Route::get('/modificarCalle/{id}', [CalleController::class, 'edit'])->middleware('auth');
Route::patch('/modificarCalle', [CalleController::class, 'update'])->middleware('auth');
Route::get('/agregarCalle', [CalleController::class, 'create'])->middleware('auth');
Route::post('/agregarCalle', [CalleController::class, 'store'])->middleware('auth');
Route::view('/actualizarCalle', 'adminArchivoCalles')->middleware('auth');
Route::post('/actualizarCalle', [CalleController::class, 'updateGeneral'])->middleware('auth');
//Route::get('/updateCalle', [CalleController::class, ''])->middleware('auth');
//Route::get('/checkCalle', [CalleController::class, 'checkCalle'])->middleware('auth');
####################
####### CRUD Ciudades
Route::get('/adminCiudades', [CiudadController::class, 'index'])->middleware('auth');
Route::get('/modificarCiudad/{id}', [CiudadController::class, 'edit'])->middleware('auth');
Route::patch('/modificarCiudad', [CiudadController::class, 'update'])->middleware('auth');
Route::get('/agregarCiudad', [CiudadController::class, 'create'])->middleware('auth');
Route::post('/agregarCiudad', [CiudadController::class, 'store'])->middleware('auth');
Route::get('/searchCiudades', [CiudadController::class, 'search'])->middleware('auth');
####################
####### CRUD Codigos de Area
Route::get('/adminCodigosDeArea', [CodigoDeAreaController::class, 'index'])->middleware('auth');
Route::get('/modificarCodigoDeArea/{id}', [CodigoDeAreaController::class, 'edit'])->middleware('auth');
Route::patch('/modificarCodigoDeArea', [CodigoDeAreaController::class, 'update'])->middleware('auth');
Route::get('/agregarCodigoDeArea', [CodigoDeAreaController::class, 'create'])->middleware('auth');
Route::post('/agregarCodigoDeArea', [CodigoDeAreaController::class, 'store'])->middleware('auth');
### API-rest
Route::get('/CodigoDeArea/', [CodigoDeAreaController::class, 'search'])->middleware('auth');
Route::get('/CodigoDeArea/{id}', [CodigoDeAreaController::class, 'search'])->middleware('auth');
Route::get('/CodigoDeArea/Codigo/{codigo}', [CodigoDeAreaController::class, 'searchCodigo'])->middleware('auth');
####################
####### CRUD Direcciones
Route::get('/adminDirecciones', [DireccionController::class, 'index'])->middleware('auth');
Route::get('/modificarDireccion/{id}', [DireccionController::class, 'edit'])->middleware('auth');
Route::patch('/modificarDireccion', [DireccionController::class, 'update'])->middleware('auth');
Route::get('/agregarDireccion', [DireccionController::class, 'create'])->middleware('auth');
Route::post('/agregarDireccion', [DireccionController::class, 'store'])->middleware('auth');
### API-Rest Direcciones
Route::post('/direccion', [DireccionController::class, 'storeApi'])->middleware('auth');
Route::patch('/direccion', [DireccionController::class, 'updateApi'])->middleware('auth');
Route::get('/searchDireccion/{calle}/{numero}', [DireccionController::class, 'search'])->middleware('auth');
Route::get('/searchIdDireccion/{id}', [DireccionController::class, 'searchById'])->middleware('auth');
####################
####### CRUD Equipos
Route::get('/adminEquipos', [EquipoController::class, 'index'])->middleware('auth');
Route::get('/modificarEquipo/{id}', [EquipoController::class, 'edit'])->middleware('auth');
Route::get('/modificarEquipoUserPass/{id}', [EquipoController::class, 'editUserPass'])->middleware('auth');
Route::patch('/modificarEquipo', [EquipoController::class, 'update'])->middleware('auth');
Route::patch('/modificarEquipoUserPass', [EquipoController::class, 'updateUserPass'])->middleware('auth');
Route::patch('/equipoActivar', [EquipoController::class, 'activar'])->middleware('auth');
Route::get('/agregarEquipo', [EquipoController::class, 'create'])->middleware('auth');
Route::post('/agregarEquipo', [EquipoController::class, 'store'])->middleware('auth');
### API-Rest Equipos
Route::get('/getEquipo/{id}', [EquipoController::class, 'getById'])->middleware('auth');
Route::get('/existeEquipo/{macaddress}', [EquipoController::class, 'existByMac'])->middleware('auth');
Route::put('/agregarEquipo2', [EquipoController::class, 'storeApiRest'])->middleware('auth');
####################
####### CRUD Backups 
Route::get('/adminBackups', [BackupController::class, 'index'])->middleware('auth');
Route::get('/adminBackupsSync', [BackupController::class, 'syncCloud'])->middleware('auth');
Route::get('/adminBackupsBkpManual', [BackupController::class, 'backupManual'])->middleware('auth');
Route::post('/adminBackupRestore', [BackupController::class, 'restoreBackup'])->middleware('auth');
Route::get('/restoreFile/{file}', [BackupController::class, 'restoreFile'])->middleware('auth');
####################
####### CRUD Interfaces 
Route::get('/adminInterfaces', [GatewayInterfaceController::class, 'index'])->middleware('auth');
Route::get('/modificarInterface/{interface_id}/{gateway_id}', [GatewayInterfaceController::class, 'editInterface'])->middleware('auth');
Route::get('/modificarInterface/{interface_id}/{gateway_id}/{esVlan}', [GatewayInterfaceController::class, 'editInterface'])->middleware('auth');
Route::patch('/modificarInterface', [GatewayInterfaceController::class, 'updateInterface'])->middleware('auth');
Route::get('/agregarInterface/{gateway_id}', [GatewayInterfaceController::class, 'create'])->middleware('auth');
Route::post('/agregarInterface', [GatewayInterfaceController::class, 'store'])->middleware('auth');
Route::delete('/eliminarInterface/{interface_id}/{gateway_id}', [GatewayInterfaceController::class, 'destroy'])->middleware('auth');
####################
####### CRUD Paneles 
Route::get('/adminPaneles', [PanelController::class, 'index'])->middleware('auth');
Route::get('/modificarPanel/{id}', [PanelController::class, 'edit'])->middleware('auth');
Route::get('/modificarDnsServers/{id}', [PanelController::class, 'editDns'])->middleware('auth');
Route::patch('/modificarPanel', [PanelController::class, 'update'])->middleware('auth');
Route::get('/panelActivar/{id}', [PanelController::class, 'activar'])->middleware('auth');
Route::get('/agregarPanel', [PanelController::class, 'create'])->middleware('auth');
Route::post('/agregarPanel', [PanelController::class, 'store'])->middleware('auth');
Route::post('/agregarDnsPanel', [PanelController::class, 'storeDns'])->middleware('auth');
Route::delete('/eliminarDnsPanel/', [PanelController::class, 'destroyDns'])->middleware('auth');
### API-Rest Equipos
Route::get('/getPanels', [PanelController::class, 'getPanels'])->middleware('auth');
Route::get('/getPanel/{id}', [PanelController::class, 'getPanelById'])->middleware('auth');
####################
####### CRUD Proveedores
Route::get('/adminProveedores', [ProveedoresController::class, 'index'])->middleware('auth');
Route::get('/agregarProveedor', [ProveedoresController::class, 'preCreate'])->middleware('auth');
Route::get('/agregarProveedor2', [ProveedoresController::class, 'create'])->middleware('auth');
Route::post('/agregarProveedor3', [ProveedoresController::class, 'store'])->middleware('auth');
Route::get('/actualizarGateway', [ProveedoresController::class, 'refreshGateway'])->middleware('auth');
Route::get('/modificarProveedor/{id}', [ProveedoresController::class, 'edit'])->middleware('auth');
Route::patch('/modificarProveedor', [ProveedoresController::class, 'update'])->middleware('auth');
Route::delete('/eliminarProveedor/{id}', [ProveedoresController::class, 'destroy'])->middleware('auth');
####################
####### CRUD Planes
Route::get('/adminPlanes', [PlanController::class, 'index'])->middleware('auth');
Route::get('/modificarPlan/{id}', [PlanController::class, 'edit'])->middleware('auth');
Route::patch('/modificarPlan', [PlanController::class, 'update'])->middleware('auth');
Route::get('/agregarPlan', [PlanController::class, 'create'])->middleware('auth');
Route::post('/agregarPlan', [PlanController::class, 'store'])->middleware('auth');
Route::delete('/eliminarPlan/{plan_id}', [PlanController::class, 'destroy'])->middleware('auth');
### API-Rest Planes
Route::get('/getPlanes', [PlanController::class, 'getAllPlans'])->middleware('auth');
####################
####### CRUD Productos
Route::get('/adminProductos', [ProductoController::class, 'index'])->middleware('auth');
Route::get('/modificarProducto/{id}', [ProductoController::class, 'edit'])->middleware('auth');
Route::patch('/modificarProducto', [ProductoController::class, 'update'])->middleware('auth');
Route::get('/agregarProducto', [ProductoController::class, 'create'])->middleware('auth');
Route::post('/agregarProducto', [ProductoController::class, 'store'])->middleware('auth');
### API-Rest Planes
####################
Route::get('/getProductos', [ProductoController::class, 'getAllProducts'])->middleware('auth');
####### CRUD Sites
Route::get('/adminSites', [SiteController::class, 'index'])->middleware('auth');
Route::get('/modificarSite/{id}', [SiteController::class, 'edit'])->middleware('auth');
Route::patch('/modificarSite', [SiteController::class, 'update'])->middleware('auth');
Route::get('/agregarSite', [SiteController::class, 'create'])->middleware('auth');
Route::post('/agregarSite', [SiteController::class, 'store'])->middleware('auth');
Route::get('/siteActivar/{id}', [SiteController::class, 'activar'])->middleware('auth');
####################
####### CRUD Clientes
Route::get('/adminClientes', [ClienteController::class, 'index'])->middleware('auth');
Route::get('/modificarCliente/{id}', [ClienteController::class, 'edit'])->middleware('auth');
Route::patch('/modificarCliente', [ClienteController::class, 'update'])->middleware('auth');
Route::get('/agregarCliente', [ClienteController::class, 'create'])->middleware('auth');
Route::post('/agregarCliente', [ClienteController::class, 'store'])->middleware('auth');
### Cliente API-rest
Route::get('/Cliente/{id}', [ClienteController::class, 'search'])->middleware('auth');
Route::post('/Cliente', [ClienteController::class, 'storeApi'])->middleware('auth');
Route::patch('/Cliente', [ClienteController::class, 'updateApi'])->middleware('auth');
####################
####### Reportes
Route::get('/reports', function(){return view('reports');})->middleware('auth');
####################
####### CRUD Users
Route::get('/adminUsers', [UserController::class, 'index'])->middleware('auth');
Route::get('/modificarUser/{id}', [UserController::class, 'edit'])->middleware('auth');
Route::patch('/modificarUser', [UserController::class, 'update'])->middleware('auth');
Route::get('/agregarRoleToUser/{id}', [UserController::class, 'show'])->middleware('auth');
Route::patch('/agregarRoleToUser', [UserController::class, 'updateRoleToUser'])->middleware('auth');
Route::get('/agregarUser', [UserController::class, 'create'])->middleware('auth');
Route::post('/agregarUser', [UserController::class, 'store'])->middleware('auth');
### Cliente API-rest
Route::get('/user/{id}', [UserController::class, 'search'])->middleware('auth');
####################
####### CRUD Roles 
Route::get('/adminRoles', [RoleController::class, 'index'])->middleware('auth');
Route::get('/modificarRole/{id}', [RoleController::class, 'edit'])->middleware('auth');
Route::patch('/modificarRole', [RoleController::class, 'update'])->middleware('auth');
Route::get('/agregarPermissionsToRole/{id}', [RoleController::class, 'show'])->middleware('auth');
Route::patch('/agregarPermissionsToRole', [RoleController::class, 'updatePermissionsToRole'])->middleware('auth');
Route::get('/agregarRole', [RoleController::class, 'create'])->middleware('auth');
Route::post('/agregarRole', [RoleController::class, 'store'])->middleware('auth');
####################
####### CRUD Permissions
Route::get('/adminPermissions', [PermissionController::class, 'index'])->middleware('auth');
Route::get('/modificarPermission/{id}', [PermissionController::class, 'edit'])->middleware('auth');
Route::patch('/modificarPermission', [PermissionController::class, 'update'])->middleware('auth');
Route::get('/agregarPermissionToRoles/{id}', [PermissionController::class, 'show'])->middleware('auth');
Route::patch('/agregarPermissionToRoles', [PermissionController::class, 'updatePermissionToRoles'])->middleware('auth');
Route::get('/agregarPermission', [PermissionController::class, 'create'])->middleware('auth');
Route::post('/agregarPermission', [PermissionController::class, 'store'])->middleware('auth');
####################
####### Auth Routes
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
