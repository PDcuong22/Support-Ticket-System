<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\PriorityController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LabelController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [AuthController::class, 'profile']);

    // Route::get('tickets', [TicketController::class, 'index']);
    // Route::get('tickets/{id}', [TicketController::class, 'show']);
    Route::resources(['tickets' => TicketController::class]);

    Route::get('statuses', [StatusController::class, 'index']);
    Route::get('priorities', [PriorityController::class, 'index']);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('labels', [LabelController::class, 'index']);

    Route::middleware('role:Admin,Support Agent')->group(function () {
        Route::get('users', [UserController::class, 'index']);
    });
});
