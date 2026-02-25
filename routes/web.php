<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ModeSwitcherController;
use App\Http\Controllers\{AuthorController, BookController};

Route::redirect('/', '/login');
Route::redirect('/dashboard', '/modes')->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/modes', 'modes')->name('home');

    Route::get('/switch-mode/{mode}', ModeSwitcherController::class)->name('switch.mode');

    // Rutas MVC
    Route::prefix('mvc')->name('mvc.')->group(function () {
        Route::view('/dashboard', 'mvc.dashboard')->name('dashboard');
        Route::resource('authors', AuthorController::class);
        Route::resource('books', BookController::class);
    });

    // Rutas Livewire (SFC)
    Route::prefix('livewire')->name('livewire.')->group(function () {
        Route::view('/dashboard', 'livewire.dashboard')->name('dashboard');
        
        Route::livewire('/authors', 'authors.index')->name('authors.index');
        Route::livewire('/authors/create', 'authors.form')->name('authors.new');
        Route::livewire('/authors/{author}', 'authors.show')->name('authors.show');
        Route::livewire('/authors/{author}/edit', 'authors.form')->name('authors.edit');

        Route::livewire('/books', 'books.index')->name('books.index');
        Route::livewire('/books/create', 'books.form')->name('books.new');
        Route::livewire('/books/{book}/edit', 'books.form')->name('books.edit');
    });
});

require __DIR__.'/settings.php';
