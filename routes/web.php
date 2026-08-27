<?php

// use App\Http\Controllers\BelajarController;
// use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Route;
// use League\Uri\Http;

// Route::inertia('/', 'welcome')->name('home');

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::inertia('dashboard', 'dashboard')->name('dashboard');
// });

// require __DIR__ . '/settings.php';

// method : GET, POST, PUT, PATCH, DELETE
// GET : Lihat dan Baca
// POST : Mengirim data dari form, askinya insert
// PUT : Mengirim many data dari form, askinya update
// PATCH : Mengirim 1 data dari form, askinya update
// DELETE : Mengirim data dari form, askinya delete

// Route::get('salam', [/app/Http/Controllers/BelajarController::class, 'greeting']);
// Route::get('salam', [App\Http\Controllers\BelajarController::class, 'greeting']);
// Route::get('tambah', [App\Http\Controllers\BelajarController::class, 'tambah']);

use App\Http\Controllers\BelajarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

// Login
Route::get('/', [LoginController::class, 'login']);
Route::get('login', [LoginController::class, 'login'])->name('login');
Route::post('actionLogin', [LoginController::class, 'actionLogin'])->name('actionLogin');


Route::get('counting', [BelajarController::class, 'index']);
Route::get('salam', [BelajarController::class, 'greeting']);

// Route::get('hitung-tambah', [BelajarController::class, 'tambah'])->name('tambah');

// Tambah
Route::get('tambah', [BelajarController::class, 'tambah']);
Route::get('hitung-tambah', [BelajarController::class, 'indexTambah']);
Route::post('action-tambah', [BelajarController::class, 'tambah'])->name('action-tambah');

// Kurang
Route::get('kurang', [BelajarController::class, 'kurang']);
Route::get('hitung-kurang', [BelajarController::class, 'indexKurang']);
Route::post('action-kurang', [BelajarController::class, 'kurang'])->name('action-kurang');

// Kali
Route::get('kali', [BelajarController::class, 'kali']);
Route::get('hitung-kali', [BelajarController::class, 'indexKali']);
Route::post('action-kali', [BelajarController::class, 'kali'])->name('action-kali');

// Bagi
Route::get('bagi', [BelajarController::class, 'bagi']);
Route::get('hitung-bagi', [BelajarController::class, 'indexBagi']);
Route::post('action-bagi', [BelajarController::class, 'bagi'])->name('action-bagi');

// Peserta
Route::get('peserta', [PesertaController::class, 'index']);
Route::get('create', [PesertaController::class, 'create'])->name('action-create');
Route::post('store-peserta', [PesertaController::class, 'store'])->name('store-peserta');
Route::get('edit/{id}', [PesertaController::class, 'edit'])->name('edit.peserta');
Route::put('update/{id}', [PesertaController::class, 'update'])->name('update.peserta');
Route::delete('delete/{id}', [PesertaController::class, 'delete'])->name('delete.peserta');

// Middleware:
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::resource('dashboard', DashboardController::class);
    // Menu
    Route::resource('menu', MenuController::class);

    Route::get('menu/sidebar', [MenuController::class, 'sidebar']);
    // Role
    Route::resource('role', RoleController::class);
    // Category
    Route::resource('category', CategoryController::class);
    // Product
    Route::resource('product', ProductController::class);
    // Order
    Route::resource('order', OrderController::class);
    // Order Detail
    // Route::resource('order', OrderController::class);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

});
