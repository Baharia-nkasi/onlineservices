<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationDocumentController;
use App\Http\Controllers\CustomerApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/services', [ServiceController::class, 'index'])
    ->name('services.index');

Route::middleware('auth')->group(function () {
    Route::get('/services/{service}/apply', [ApplicationController::class, 'create'])
        ->name('applications.create');

    Route::post('/services/{service}/apply', [ApplicationController::class, 'store'])
        ->name('applications.store');

    Route::get('/my-applications', [CustomerApplicationController::class, 'index'])
        ->name('customer.applications.index');

    Route::get('/my-applications/{application}', [CustomerApplicationController::class, 'show'])
        ->name('customer.applications.show');

    Route::post('/my-applications/{application}/documents', [ApplicationDocumentController::class, 'store'])
        ->name('application.documents.store');

    Route::delete('/application-documents/{document}', [ApplicationDocumentController::class, 'destroy'])
        ->name('application.documents.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])
            ->name('dashboard');

        Route::get('/applications/{application}', [AdminController::class, 'showApplication'])
            ->name('applications.show');

        Route::patch('/applications/{application}/status', [AdminController::class, 'updateApplicationStatus'])
            ->name('applications.status');

        Route::patch('/documents/{document}/status', [AdminController::class, 'updateDocumentStatus'])
            ->name('documents.status');

        Route::patch('/services/{service}', [AdminController::class, 'updateService'])
            ->name('services.update');
    });
});

require __DIR__.'/auth.php';
