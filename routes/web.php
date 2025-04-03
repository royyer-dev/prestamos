<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogosController;
use App\Http\Controllers\MovimientosController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('home',["breadcrumbs" => []]);
});

////////////////////////////////// CATALOGOS CONTROLLER //////////////////////////////////
// Puestos
Route::get("/catalogos/puestos", [CatalogosController::class, 'puestosGet']);
Route::get("/catalogos/puestos/agregar", [CatalogosController::class, 'puestosAgregarGet']);
Route::post("/catalogos/puestos/agregar", [CatalogosController::class, 'puestosAgregarPost']);
// Empleados Get - Agregar
Route::get("/empleados", [CatalogosController::class, 'empleadosGet']);
Route::get("/empleados/agregar", [CatalogosController::class, 'empleadosAgregarGet']);
Route::post("/empleados/agregar", [CatalogosController::class, 'empleadosAgregarPost']);
// Empleados Puestos Cambiar
Route::get("/empleados/{id}/puestos", [CatalogosController::class, 'empleadosPuestosGet'])->where("id", "[0-9]+");
Route::get("/empleados/{id}/puestos/cambiar", [CatalogosController::class, 'empleadosPuestosCambiarGet'])->where("id", "[0-9]+");
Route::post("/empleados/{id}/puestos/cambiar", [CatalogosController::class, 'empleadosPuestosCambiarPost'])->where("id", "[0-9]+");
// Empleados Prestamos
Route::get('/empleados/{id}/prestamos', [CatalogosController::class, 'empleadosPrestamosGet']);


////////////////////////////////// MOVIMIENTOS CONTROLLER //////////////////////////////////
Route::get ("/movimientos/prestamos",[MovimientosController::class, "prestamosGet"]);
Route::get ("/movimientos/prestamos/agregar",[MovimientosController::class, "prestamosAgregarGet"]);
Route::post ("/movimientos/prestamos/agregar",[MovimientosController::class, "prestamosAgregarPost"]);
Route::get ("/movimientos/prestamos/{prest}/abonos",[MovimientosController::class, "abonosGet"])->where("prest","\\d+");
Route::get("/prestamos/{prest}/abonos/agregar", [MovimientosController::class, "abonosAgregarGet"])->where("prest", "\\d+");
Route::post("/prestamos/{prest}/abonos/agregar", [MovimientosController::class, "abonosAgregarPost"])->where("prest", "\\d+");

////////////////////////////////// REPORTES CONTROLLER //////////////////////////////////
Route::get("/reportes",[ReportesController::class,"indexGet"]);
Route::get("/reportes",[ReportesController::class,"indexGet"]);
Route::get("/reportes/prestamos-activos",[ReportesController::class,"prestamosActivosGet"]);
Route::get("/reportes/matriz-abonos",[ReportesController::class,"matrizAbonosGet"]);

////////////////////////////////// LOGIN CONTROLLER //////////////////////////////////
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

////////////////////////////////// REGISTER CONTROLLER //////////////////////////////////
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);