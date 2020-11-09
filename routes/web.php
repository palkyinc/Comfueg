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
Route::get('/adminAntenas', [AntenaController::class, 'index']);
Route::get('/modificarAntena/{id}', [AntenaController::class, 'edit']);
Route::patch('/modificarAntena', [AntenaController::class, 'update']);
Route::get('/agregarAntena', [AntenaController::class, 'create']);
Route::post('/agregarAntena', [AntenaController::class, 'store']);
####################
####### CRUD Barrios
Route::get('/adminBarrios', [BarrioController::class, 'index']);
Route::get('/searchBarrios', [BarrioController::class, 'search']);
Route::get('/modificarBarrio/{id}', [BarrioController::class, 'edit']);
Route::patch('/modificarBarrio', [BarrioController::class, 'update']);
Route::get('/agregarBarrio', [BarrioController::class, 'create']);
Route::post('/agregarBarrio', [BarrioController::class, 'store']);
####################
####### CRUD Calles
Route::get('/adminCalles', [CalleController::class, 'index']);
Route::get('/searchCalles', [CalleController::class, 'search']);
Route::get('/modificarCalle/{id}', [CalleController::class, 'edit']);
Route::patch('/modificarCalle', [CalleController::class, 'update']);
Route::get('/agregarCalle', [CalleController::class, 'create']);
Route::post('/agregarCalle', [CalleController::class, 'store']);
####################
####### CRUD Ciudades
Route::get('/adminCiudades', [CiudadController::class, 'index']);
Route::get('/modificarCiudad/{id}', [CiudadController::class, 'edit']);
Route::patch('/modificarCiudad', [CiudadController::class, 'update']);
Route::get('/agregarCiudad', [CiudadController::class, 'create']);
Route::post('/agregarCiudad', [CiudadController::class, 'store']);
####################
####### CRUD Codigos de Area
Route::get('/adminCodigosDeArea', [CodigoDeAreaController::class, 'index']);
Route::get('/modificarCodigoDeArea/{id}', [CodigoDeAreaController::class, 'edit']);
Route::patch('/modificarCodigoDeArea', [CodigoDeAreaController::class, 'update']);
Route::get('/agregarCodigoDeArea', [CodigoDeAreaController::class, 'create']);
Route::post('/agregarCodigoDeArea', [CodigoDeAreaController::class, 'store']);
####################
####### CRUD Direcciones
Route::get('/adminDirecciones', [DireccionController::class, 'index']);
Route::get('/modificarDireccion/{id}', [DireccionController::class, 'edit']);
Route::patch('/modificarDireccion', [DireccionController::class, 'update']);
Route::get('/agregarDireccion', [DireccionController::class, 'create']);
Route::post('/agregarDireccion', [DireccionController::class, 'store']);
####################
####### CRUD Equipos
Route::get('/adminEquipos', [EquipoController::class, 'index']);
Route::get('/modificarEquipo/{id}', [EquipoController::class, 'edit']);
Route::patch('/modificarEquipo', [EquipoController::class, 'update']);
Route::patch('/equipoActivar', [EquipoController::class, 'activar']);
Route::get('/agregarEquipo', [EquipoController::class, 'create']);
Route::post('/agregarEquipo', [EquipoController::class, 'store']);
####################
####### CRUD Niveles
Route::get('/adminNiveles', [NivelController::class, 'index']);
####################
####### CRUD Paneles 
Route::get('/adminPaneles', [PanelController::class, 'index']);
Route::get('/modificarPanel/{id}', [PanelController::class, 'edit']);
Route::patch('/modificarPanel', [PanelController::class, 'update']);
Route::patch('/panelActivar', [PanelController::class, 'activar']);
####################
####### CRUD Planes
Route::get('/adminPlanes', [PlanController::class, 'index']);
Route::get('/modificarPlan/{id}', [PlanController::class, 'edit']);
Route::patch('/modificarPlan', [PlanController::class, 'update']);
####################
####### CRUD Productos
Route::get('/adminProductos', [ProductoController::class, 'index']);
Route::get('/modificarProducto/{id}', [ProductoController::class, 'edit']);
Route::patch('/modificarProducto', [ProductoController::class, 'update']);
####################
####### CRUD Sites
Route::get('/adminSites', [SiteController::class, 'index']);
Route::get('/modificarSite/{id}', [SiteController::class, 'edit']);
Route::patch('/modificarSite', [SiteController::class, 'update']);
####################
####### CRUD Clientes
Route::get('/adminClientes', [ClienteController::class, 'index']);
Route::get('/modificarCliente/{id}', [ClienteController::class, 'edit']);
Route::patch('/modificarCliente', [ClienteController::class, 'update']);
