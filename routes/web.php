<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AntenaController;
use App\Http\Controllers\BarrioController;
use App\Http\Controllers\CalleController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CodigoDeAreaController;
use App\Http\Controllers\DireccionController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Mail_groupController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\NodoController;
use App\Http\Controllers\panel_has_barrioController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PruebaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiteHasIncidenteController;
use App\Mail\IncidenciaGlobal;
use App\Models\Site_has_incidente;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
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
Route::get('/test', function () {
    $incidente = Site_has_incidente::find(1);
    Mail::to(['laboratorio@comunicacionesfueguinas.com','migvicpereyra@hotmail.com'])->send(new IncidenciaGlobal($incidente));
    dd($incidente);
});
### Route index
Route::get('/', function (){return view('inicio', ['incidentes' => Site_has_incidente::incidentesAbiertos() , 'principal' => 'active']);});
Route::get('/inicio', function (){return view('inicio', ['incidentes' => Site_has_incidente::incidentesAbiertos(), 'principal' => 'active']);});
Route::get('/contratos', function (){return view('contratos', ['contratos' => 'active']);});
####################
####### panel test Web services
Route::get('/panelTest/{ip}', [PruebaController::class, 'testPanel'])->middleware('auth');
Route::get('/allPanels', [PruebaController::class, 'allPanels'])->middleware('auth');
####################
####### Panel tiene Barrio CRUD
Route::get('/adminPanelhasBarrio', [Panel_has_barrioController::class, 'index'])->middleware('auth');
Route::get('/modificarPanelHasBarrio/{id}', [Panel_has_barrioController::class, 'edit'])->middleware('auth');
Route::patch('/modificarPanelHasBarrio', [Panel_has_barrioController::class, 'update'])->middleware('auth');
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
####### NodosControlPanel
Route::get('/adminControlPanelNodos', function () {return view('adminControlPanelNodos', [
    'nodos' => 'active',
    'website' => Config::get('constants.DOMINIO_COMFUEG'),
    'vuejs' => Config::get('constants.VUEJS_VERSION')
    ]);})->middleware('auth');
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
Route::patch('/modificarModelo', [ModeloController::class, 'update'])->middleware('auth');
####################
####### CRUD Antenas
Route::get('/adminAntenas', [AntenaController::class, 'index'])->middleware('auth');
Route::get('/modificarAntena/{id}', [AntenaController::class, 'edit'])->middleware('auth');
Route::patch('/modificarAntena', [AntenaController::class, 'update'])->middleware('auth');
Route::get('/agregarAntena', [AntenaController::class, 'create'])->middleware('auth');
Route::post('/agregarAntena', [AntenaController::class, 'store'])->middleware('auth');
####################
####### CRUD Barrios
Route::get('/adminBarrios', [BarrioController::class, 'index'])->middleware('auth');
Route::get('/searchBarrios', [BarrioController::class, 'search'])->middleware('auth');
Route::get('/modificarBarrio/{id}', [BarrioController::class, 'edit'])->middleware('auth');
Route::patch('/modificarBarrio', [BarrioController::class, 'update'])->middleware('auth');
Route::get('/agregarBarrio', [BarrioController::class, 'create'])->middleware('auth');
Route::post('/agregarBarrio', [BarrioController::class, 'store'])->middleware('auth');
####################
####### CRUD Calles
Route::get('/adminCalles', [CalleController::class, 'index'])->middleware('auth');
Route::get('/searchCalles', [CalleController::class, 'search'])->middleware('auth');
Route::get('/modificarCalle/{id}', [CalleController::class, 'edit'])->middleware('auth');
Route::patch('/modificarCalle', [CalleController::class, 'update'])->middleware('auth');
Route::get('/agregarCalle', [CalleController::class, 'create'])->middleware('auth');
Route::post('/agregarCalle', [CalleController::class, 'store'])->middleware('auth');
####################
####### CRUD Ciudades
Route::get('/adminCiudades', [CiudadController::class, 'index'])->middleware('auth');
Route::get('/modificarCiudad/{id}', [CiudadController::class, 'edit'])->middleware('auth');
Route::patch('/modificarCiudad', [CiudadController::class, 'update'])->middleware('auth');
Route::get('/agregarCiudad', [CiudadController::class, 'create'])->middleware('auth');
Route::post('/agregarCiudad', [CiudadController::class, 'store'])->middleware('auth');
####################
####### CRUD Codigos de Area
Route::get('/adminCodigosDeArea', [CodigoDeAreaController::class, 'index'])->middleware('auth');
Route::get('/modificarCodigoDeArea/{id}', [CodigoDeAreaController::class, 'edit'])->middleware('auth');
Route::patch('/modificarCodigoDeArea', [CodigoDeAreaController::class, 'update'])->middleware('auth');
Route::get('/agregarCodigoDeArea', [CodigoDeAreaController::class, 'create'])->middleware('auth');
Route::post('/agregarCodigoDeArea', [CodigoDeAreaController::class, 'store'])->middleware('auth');
####################
####### CRUD Direcciones
Route::get('/adminDirecciones', [DireccionController::class, 'index'])->middleware('auth');
Route::get('/modificarDireccion/{id}', [DireccionController::class, 'edit'])->middleware('auth');
Route::patch('/modificarDireccion', [DireccionController::class, 'update'])->middleware('auth');
Route::get('/agregarDireccion', [DireccionController::class, 'create'])->middleware('auth');
Route::post('/agregarDireccion', [DireccionController::class, 'store'])->middleware('auth');
####################
####### CRUD Equipos
Route::get('/adminEquipos', [EquipoController::class, 'index'])->middleware('auth');
Route::get('/modificarEquipo/{id}', [EquipoController::class, 'edit'])->middleware('auth');
Route::patch('/modificarEquipo', [EquipoController::class, 'update'])->middleware('auth');
Route::patch('/equipoActivar', [EquipoController::class, 'activar'])->middleware('auth');
Route::get('/agregarEquipo', [EquipoController::class, 'create'])->middleware('auth');
Route::post('/agregarEquipo', [EquipoController::class, 'store'])->middleware('auth');
####################
####### CRUD Paneles 
Route::get('/adminPaneles', [PanelController::class, 'index'])->middleware('auth');
Route::get('/modificarPanel/{id}', [PanelController::class, 'edit'])->middleware('auth');
Route::patch('/modificarPanel', [PanelController::class, 'update'])->middleware('auth');
Route::get('/panelActivar/{id}', [PanelController::class, 'activar'])->middleware('auth');
Route::get('/agregarPanel', [PanelController::class, 'create'])->middleware('auth');
Route::post('/agregarPanel', [PanelController::class, 'store'])->middleware('auth');
####################
####### CRUD Planes
Route::get('/adminPlanes', [PlanController::class, 'index'])->middleware('auth');
Route::get('/modificarPlan/{id}', [PlanController::class, 'edit'])->middleware('auth');
Route::patch('/modificarPlan', [PlanController::class, 'update'])->middleware('auth');
Route::get('/agregarPlan', [PlanController::class, 'create'])->middleware('auth');
Route::post('/agregarPlan', [PlanController::class, 'store'])->middleware('auth');
####################
####### CRUD Productos
Route::get('/adminProductos', [ProductoController::class, 'index'])->middleware('auth');
Route::get('/modificarProducto/{id}', [ProductoController::class, 'edit'])->middleware('auth');
Route::patch('/modificarProducto', [ProductoController::class, 'update'])->middleware('auth');
Route::get('/agregarProducto', [ProductoController::class, 'create'])->middleware('auth');
Route::post('/agregarProducto', [ProductoController::class, 'store'])->middleware('auth');
####################
####### CRUD Sites
Route::get('/adminSites', [SiteController::class, 'index'])->middleware('auth');
Route::get('/modificarSite/{id}', [SiteController::class, 'edit'])->middleware('auth');
Route::patch('/modificarSite', [SiteController::class, 'update'])->middleware('auth');
Route::get('/agregarSite', [SiteController::class, 'create'])->middleware('auth');
Route::post('/agregarSite', [SiteController::class, 'store'])->middleware('auth');
####################
####### CRUD Clientes
Route::get('/adminClientes', [ClienteController::class, 'index'])->middleware('auth');
Route::get('/modificarCliente/{id}', [ClienteController::class, 'edit'])->middleware('auth');
Route::patch('/modificarCliente', [ClienteController::class, 'update'])->middleware('auth');
Route::get('/agregarCliente', [ClienteController::class, 'create'])->middleware('auth');
Route::post('/agregarCliente', [ClienteController::class, 'store'])->middleware('auth');
####################
####### CRUD Users
Route::get('/adminUsers', [UserController::class, 'index'])->middleware('auth');
Route::get('/modificarUser/{id}', [UserController::class, 'edit'])->middleware('auth');
Route::patch('/modificarUser', [UserController::class, 'update'])->middleware('auth');
Route::get('/agregarRoleToUser/{id}', [UserController::class, 'show'])->middleware('auth');
Route::patch('/agregarRoleToUser', [UserController::class, 'updateRoleToUser'])->middleware('auth');
Route::get('/agregarUser', [UserController::class, 'create'])->middleware('auth');
Route::post('/agregarUser', [UserController::class, 'store'])->middleware('auth');
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
