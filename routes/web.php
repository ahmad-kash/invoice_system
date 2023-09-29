<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceArchiveController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicePaymentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserArchiveController;
use App\Http\Controllers\UserController;
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

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->middleware('throttle:login')->name('login.create');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');


    Route::middleware('signed')->group(function () {
        Route::get('reset', [ResetPasswordController::class, 'create'])->name('password.reset.create');
        Route::post('reset', [ResetPasswordController::class, 'store'])->name('password.reset.store');
    });
});


Route::middleware('auth')->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('notifications', [NotificationController::class, 'showAll'])->name('notifications.showAll');

    Route::resource('sections', SectionController::class);
    Route::resource('products', ProductController::class);
    Route::get('sections/{section}/products', [ProductController::class, 'getSectionProducts'])->name('sections.products');

    Route::get('invoices/restore', [InvoiceArchiveController::class, 'index'])->name('invoices.archive.index');
    Route::put('invoices/restore/{invoice}', [InvoiceArchiveController::class, 'update'])->withTrashed()->name('invoices.restore');
    Route::delete('invoices/forceDelete/{invoice}', [InvoiceArchiveController::class, 'destroy'])->withTrashed()->name('invoices.forceDestroy');

    Route::resource('invoices', InvoiceController::class);


    Route::get('attachments/{attachment}', [InvoiceAttachmentController::class, 'show'])->name('invoices.attachments.show');
    Route::get('attachments/{attachment}/download', [InvoiceAttachmentController::class, 'download'])->name('invoices.attachments.download');
    Route::post('invoices/{invoice}/attachments', [InvoiceAttachmentController::class, 'store'])->name('invoices.attachments.store');
    Route::delete('attachments/{attachment}', [InvoiceAttachmentController::class, 'destroy'])->name('invoices.attachments.destroy');

    Route::get('invoices/{invoice}/payments', [InvoicePaymentController::class, 'create'])->name('invoices.payments.create');
    Route::post('invoices/{invoice}/payments', [InvoicePaymentController::class, 'store'])->name('invoices.payments.store');


    Route::get('users/restore', [UserArchiveController::class, 'index'])->name('users.archive.index');
    Route::put('users/restore/{user}', [UserArchiveController::class, 'update'])->withTrashed()->name('users.restore');
    Route::delete('users/forceDelete/{user}', [UserArchiveController::class, 'destroy'])->withTrashed()->name('users.forceDestroy');

    Route::resource('users', UserController::class);
    Route::put('reset/{user}', [ResetPasswordController::class, 'update'])->name('password.reset.update');

    Route::resource('roles', RoleController::class);
});
