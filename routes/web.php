<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\RationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\FeedingController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FarmController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('locale', [\App\Http\Controllers\LocaleController::class, 'update'])->name('locale.update');
    Route::get('animals/scanner', [AnimalController::class, 'scanner'])->name('scanner');
    Route::resource('animals', AnimalController::class);
    Route::resource('tasks', TaskController::class);
    Route::resource('finances', FinancialController::class);
    Route::resource('rations', RationController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('notes', NoteController::class);
    Route::resource('feedings', FeedingController::class);
    Route::resource('health', HealthController::class);
    Route::resource('events', EventController::class);
    Route::resource('farms', FarmController::class)->only(['update']);
    Route::post('farms/{farm}/cover', [FarmController::class, 'updateCover'])->name('farms.updateCover');
    Route::resource('teams', TeamController::class)->only(['index', 'store', 'destroy']);
    Route::post('animals/{animal}/weights', [AnimalController::class, 'storeWeight'])->name('animals.weights.store');
    Route::post('animals/{animal}/productions', [AnimalController::class, 'storeProduction'])->name('animals.productions.store');
    Route::get('animals/check-inbreeding', [AnimalController::class, 'checkInbreeding'])->name('animals.check-inbreeding');
});

require __DIR__.'/auth.php';
