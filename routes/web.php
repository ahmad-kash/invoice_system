<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return view('home');
})->middleware('auth')->name('home');


Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->middleware('throttle:login')->name('login.create');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});


Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    // Route::resource('invoices', InvoiceController::class);
    Route::resource('products', ProductController::class);
    Route::resource('sections', SectionController::class);
});
