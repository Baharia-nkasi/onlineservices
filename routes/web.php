<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationDocumentController;
use App\Http\Controllers\CustomerApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\Service;

Route::get('/language/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'sw'], true), 404);
    session(['locale' => $locale]);

    return back()->with('success', $locale === 'sw' ? 'Lugha imebadilishwa kuwa Kiswahili.' : 'Language changed to English.');
})->name('language.switch');

Route::middleware('locale')->group(function () {
    Route::get('/', function () {
        return view('welcome', [
            'services' => Service::where('is_active', true)->latest()->limit(4)->get(),
        ]);
    })->name('home');

    Route::get('/health', function () {
        try {
            DB::connection()->getPdo();

            return response()->json(['status' => 'ok', 'app' => config('app.name')]);
        } catch (Throwable $e) {
            report($e);
            return response()->json(['status' => 'error'], 503);
        }
    })->name('health');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('auth')->name('dashboard');

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
            ->middleware('throttle:20,1')->name('application.documents.store');

        Route::get('/application-documents/{document}/view', [ApplicationDocumentController::class, 'view'])
            ->name('application.documents.view');
        Route::get('/application-documents/{document}/download', [ApplicationDocumentController::class, 'download'])
            ->name('application.documents.download');

        Route::delete('/application-documents/{document}', [ApplicationDocumentController::class, 'destroy'])
            ->middleware('throttle:20,1')->name('application.documents.destroy');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('dashboard');
            Route::get('/applications/{application}', [AdminController::class, 'showApplication'])->name('applications.show');
            Route::patch('/applications/{application}/status', [AdminController::class, 'updateApplicationStatus'])->name('applications.status');
            Route::patch('/documents/{document}/status', [AdminController::class, 'updateDocumentStatus'])->name('documents.status');
            Route::delete('/documents/{document}', [AdminController::class, 'destroyDocument'])
                ->middleware('throttle:20,1')->name('documents.destroy');
            Route::get('/services', [AdminController::class, 'services'])->name('services.index');
            Route::post('/services', [AdminController::class, 'storeService'])
                ->middleware('throttle:20,1')->name('services.store');
            Route::patch('/services/{service}', [AdminController::class, 'updateService'])->name('services.update');
            Route::delete('/services/{service}', [AdminController::class, 'destroyService'])
                ->middleware('throttle:20,1')->name('services.destroy');
        });
    });

    require __DIR__.'/auth.php';
});
