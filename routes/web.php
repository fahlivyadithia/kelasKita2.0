<?php

use App\Http\Controllers\KelasController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\Mentor\MentorDashboardController; // Pastikan path ini sesuai
use App\Http\Controllers\HomeController;    
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\Api\Mentor\KelasController;
use App\Http\Controllers\Api\Mentor\MateriController;
use App\Http\Controllers\Api\Mentor\SubMateriController;

use App\Http\Controllers\ProgressController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});