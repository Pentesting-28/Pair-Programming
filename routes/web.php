<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ModeSwitcherController;
use Livewire\Volt\Volt;

Route::redirect('/', '/login');

Route::view('/modes', 'modes')
    ->middleware(['auth', 'verified'])
    ->name('home');

Route::get('/switch-mode/{mode}', ModeSwitcherController::class)
    ->middleware(['auth'])
    ->name('switch.mode');

// Rutas MVC
Route::prefix('mvc')->name('mvc.')->middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'mvc.dashboard')->name('dashboard');
    Route::resource('authors', App\Http\Controllers\AutorController::class);
});

// Rutas Livewire (Volt SFC)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/livewire/dashboard', 'livewire.dashboard')->name('livewire.dashboard');
    
    Volt::route('/authors', 'authors.index')->name('authors.index');
    Volt::route('/authors/create', 'authors.form')->name('authors.new');
    Volt::route('/authors/{author}/edit', 'authors.form')->name('authors.edit');
});

require __DIR__.'/settings.php';
