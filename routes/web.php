<?php

use App\Http\Controllers\Detailkelas\KelasController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Keranjang\KeranjangController;
use App\Http\Controllers\Transaksi\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kelas/{id}', [KelasController::class, 'show'])->name('kelas.detail');
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
Route::delete('/keranjang/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi/checkout', [TransaksiController::class, 'checkout'])->name('transaksi.checkout');
Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.detail');