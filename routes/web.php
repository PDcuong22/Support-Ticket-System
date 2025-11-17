<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\LabelController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TicketLogController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tickets', TicketController::class)->except(['destroy', 'edit', 'update']);

    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/tickets/{ticket}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::post('/tickets/{ticket}/attachments', [AttachmentController::class, 'store'])->name('attachments.store');
    Route::delete('/tickets/{ticket}/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');
});

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/ticket-logs', [TicketLogController::class, 'index'])->name('ticket-logs.index');

    Route::resource('tickets', AdminTicketController::class)->except(['edit', 'update']);
    Route::resource('labels', LabelController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);

    Route::patch('users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');
});

Route::middleware(['auth', 'role:Admin,Support Agent'])->group(function () {
    Route::get('/admin/tickets/{ticket}/edit', [AdminTicketController::class, 'edit'])->name('admin.tickets.edit');
    Route::patch('/admin/tickets/{ticket}', [AdminTicketController::class, 'update'])->name('admin.tickets.update');
});

require __DIR__ . '/auth.php';
