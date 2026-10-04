<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CustomerApplicationController;
use App\Http\Controllers\ApplicationDocumentController;
use App\Http\Controllers\DashboardController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/services', [ServiceController::class, 'index'])
    ->middleware('auth')
    ->name('services.index');

Route::get('/services/{service}/apply', [ApplicationController::class, 'create'])
    ->middleware('auth')
    ->name('applications.create');

Route::post('/services/{service}/apply', [ApplicationController::class, 'store'])
    ->middleware('auth')
    ->name('applications.store');

Route::get('/my-applications', [CustomerApplicationController::class, 'index'])
    ->middleware('auth')
    ->name('customer.applications.index');

Route::get('/my-applications/{application}', [CustomerApplicationController::class, 'show'])
    ->middleware('auth')
    ->name('customer.applications.show');
    
    Route::post(
    '/my-applications/{application}/documents',
    [ApplicationDocumentController::class, 'store']
)
    ->middleware('auth')
    ->name('application.documents.store');


Route::delete(
    '/application-documents/{document}',
    [ApplicationDocumentController::class, 'destroy']
)
    ->middleware('auth')
    ->name('application.documents.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
