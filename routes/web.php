<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NominationController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\BuildController;
use App\Http\Controllers\BuildItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})
->name('home');

Route::get('/portfolio', function () {
    return view('pages.portfolio');
})
->name('portfolio');

Route::get('/portfolio/web', function () {
    return view('pages.portfolio');
})
->name('portfolio.web');

Route::get('/portfolio/design', function () {
    return view('pages.portfolio');
})
->name('portfolio.design');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'attempt'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // Kept from existing for logout functionality
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Nomination Routes
Route::get('/nominations', [NominationController::class, 'index'])->middleware('auth')->name('nominations.index');
Route::get('/nominate', [NominationController::class, 'create'])->middleware('auth')->name('nominations.create');
Route::post('/nominations', [NominationController::class, 'store'])->middleware('auth')->name('nominations.store');
Route::get('/nominations/{category}', [NominationController::class, 'showCategoryNominations'])->middleware('auth')->name('nominations.category.show');

Route::post('/nominations/{nomination}/vote', [NominationController::class, 'vote'])->middleware('auth')->name('nominations.vote');
Route::get('/results', [NominationController::class, 'results'])->middleware('auth')->name('nominations.results');

// Build Routes
Route::get('/builds', [BuildController::class, 'index'])->name('builds.index');
Route::get('/builds/create', [BuildController::class, 'create'])->middleware('auth')->name('builds.create');
Route::post('/builds', [BuildController::class, 'store'])->middleware('auth')->name('builds.store');
Route::get('/builds/{build}', [BuildController::class, 'show'])->name('builds.show');
Route::delete('/builds/{build}', [BuildController::class, 'destroy'])->middleware('auth')->name('builds.destroy');
Route::post('/builds/{build}/items/{item}/submit', [BuildItemController::class, 'submit'])->middleware('auth')->name('builds.items.submit');

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'adminDashboard'])->name('dashboard');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::get('/nominations', [AdminController::class, 'index'])->name('nominations.index');
        Route::get('/nominations/{nomination}', [AdminController::class, 'show'])->name('nominations.show');
        Route::patch('/nominations/{nomination}/approve', [AdminController::class, 'approve'])->name('nominations.approve');
        Route::patch('/nominations/{nomination}/reject', [AdminController::class, 'reject'])->name('nominations.reject');

        Route::post('/process/start', [AdminController::class, 'startProcess'])->name('process.start');
        Route::post('/process/stop', [AdminController::class, 'stopProcess'])->name('process.stop');
        Route::post('/process/start-nomination', [AdminController::class, 'startNomination'])->name('process.startNomination');
        Route::post('/process/start-voting', [ProcessController::class, 'startVoting'])->name('process.startVoting');
        Route::post('/process/show-results', [ProcessController::class, 'showResults'])->name('process.showResults');
    });
