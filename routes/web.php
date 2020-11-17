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
use App\Http\Controllers\NivelController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SiteController;

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
Route::get('/test', function () {return view('welcome');});
### Route index
Route::get('/', function (){return view('inicio', ['principal' => 'active']);});
Route::get('/inicio', function (){return view('inicio', ['principal' => 'active']);});
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
####### CRUD Niveles
Route::get('/adminNiveles', [NivelController::class, 'index'])->middleware('auth');
####################
####### CRUD Paneles 
Route::get('/adminPaneles', [PanelController::class, 'index'])->middleware('auth');
Route::get('/modificarPanel/{id}', [PanelController::class, 'edit'])->middleware('auth');
Route::patch('/modificarPanel', [PanelController::class, 'update'])->middleware('auth');
Route::patch('/panelActivar', [PanelController::class, 'activar'])->middleware('auth');
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
Route::get('/adminUsers', [App\Http\Controllers\UserController::class, 'index'])->middleware('auth');
Route::get('/modificarUser/{id}', [App\Http\Controllers\UserController::class, 'edit'])->middleware('auth');
Route::patch('/modificarUser', [App\Http\Controllers\UserController::class, 'update'])->middleware('auth');
####################
####### CRUD Roles
Route::get('/adminRoles', [App\Http\Controllers\RoleController::class, 'index'])->middleware('auth');
Route::get('/modificarRole/{id}', [App\Http\Controllers\RoleController::class, 'edit'])->middleware('auth');
Route::patch('/modificarRole', [App\Http\Controllers\RoleController::class, 'update'])->middleware('auth');
####################
####### CRUD Permissions
Route::get('/adminPermissions', [App\Http\Controllers\PermissionController::class, 'index'])->middleware('auth');
Route::get('/modificarPermission/{id}', [App\Http\Controllers\PermissionController::class, 'edit'])->middleware('auth');
Route::patch('/modificarPermission', [App\Http\Controllers\PermissionController::class, 'update'])->middleware('auth');
####################
####### Auth Routes
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
