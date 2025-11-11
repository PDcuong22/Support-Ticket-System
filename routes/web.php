<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tickets', TicketController::class);

    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/tickets/{ticket}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::post('/tickets/{ticket}/attachments', [AttachmentController::class, 'store'])->name('attachments.store');
    Route::delete('/tickets/{ticket}/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tickets', [AdminDashboardController::class, 'tickets'])->name('tickets');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/categories', [AdminDashboardController::class, 'categories'])->name('categories');
    Route::get('/labels', [AdminDashboardController::class, 'labels'])->name('labels');
    Route::get('/ticket-logs', [AdminDashboardController::class, 'ticketLogs'])->name('ticket-logs');
});

require __DIR__ . '/auth.php';
