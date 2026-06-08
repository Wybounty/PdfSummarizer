<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\PdfController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/file-analysis', [HomeController::class, 'file_analysis'])->name('file.analysis');

Route::get('/summary/{analyse}', [SummaryController::class, 'show'])->name('summary.show');
Route::get('/summary/{analyse}/pdf', [SummaryController::class, 'download'])->name('summary.pdf');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

//redirect login to home page
Route::get('/login', function () {
    return redirect()->route('home');
})->name('login');

Route::get('/register', function () {
    return redirect()->route('home');
})->name('register');

require __DIR__.'/settings.php';
