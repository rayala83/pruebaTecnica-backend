<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {return view('login');})->name('login.form');

Route::post('/login', [ApiController::class, 'webLogin'])->name('login.submit');

Route::get('/logueado', function () {return view('logueado');})->name('logueado');

Route::get('/logout', function () {
    session()->forget('external_token');
    return redirect()->route('login.form')->with('message', 'Sesión cerrada correctamente.');
})->name('logout');

Route::get('/destino', [ApiController::class, 'showRegions'])->name('destination');

Route::post('/carrito', [ApiController::class, 'iniciarCarrito'])->name('carrito.init');
Route::get('/carrito', [ApiController::class, 'showCarrito'])->name('carrito.index');
Route::post('/carrito/agregar', [ApiController::class, 'agregarAlCarrito'])->name('carrito.agregar');
Route::post('/carrito/solicitar_tarifas', [ApiController::class, 'solicitarTarifas'])->name('carrito.solicitar_tarifas');
Route::get('/carrito/tarifas', [ApiController::class, 'mostrarTarifas'])->name('carrito.tarifas');
