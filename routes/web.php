<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicePaymentController;
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

    Route::resource('sections', SectionController::class);
    Route::resource('products', ProductController::class);
    Route::get('sections/{section}/products', [ProductController::class, 'getSectionProducts'])->name('sections.products');

    Route::resource('invoices', InvoiceController::class);
    Route::delete('invoices/forceDelete/{invoice}', [InvoiceController::class, 'forceDestroy'])->name('invoices.forceDestroy');
    Route::put('invoices/restore/{invoice}', [InvoiceController::class, 'restore'])->withTrashed()->name('invoices.restore');

    Route::get('attachments/{attachment}', [InvoiceAttachmentController::class, 'show'])->name('invoices.attachments.show');
    Route::get('attachments/{attachment}/download', [InvoiceAttachmentController::class, 'download'])->name('invoices.attachments.download');
    Route::post('invoices/{invoice}/attachments', [InvoiceAttachmentController::class, 'store'])->name('invoices.attachments.store');
    Route::delete('attachments/{attachment}', [InvoiceAttachmentController::class, 'destroy'])->name('invoices.attachments.destroy');

    Route::get('invoices/{invoice}/payments', [InvoicePaymentController::class, 'create'])->name('invoices.payments.create');
    Route::post('invoices/{invoice}/payments', [InvoicePaymentController::class, 'store'])->name('invoices.payments.store');
});
