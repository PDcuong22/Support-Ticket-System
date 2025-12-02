<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\PriorityController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LabelController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ActivityConttroller;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Laravel\Sanctum\PersonalAccessToken;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::post('/broadcasting/auth', function (Request $request) {
    $token = $request->bearerToken();

    if (!$token) {
        return response()->json(['message' => 'Missing token'], 401);
    }

    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Invalid token'], 403);
    }

    $request->setUserResolver(function () use ($accessToken) {
        return $accessToken->tokenable;
    });

    return Broadcast::auth($request);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [AuthController::class, 'profile']);

    Route::get('stats', [TicketController::class, 'stats']);
    Route::resources(['tickets' => TicketController::class]);

    Route::get('statuses', [StatusController::class, 'index']);
    Route::get('priorities', [PriorityController::class, 'index']);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('labels', [LabelController::class, 'index']);

    Route::get('comments/{ticketId}', [CommentController::class, 'index']);
    Route::post('comments/{ticketId}', [CommentController::class, 'store']);

    Route::middleware('role:Admin,Support Agent')->group(function () {
        Route::get('list-agents', [UserController::class, 'allAgents']);
    });

    Route::middleware('role:Admin')->group(function () {
        Route::resources(['users' => UserController::class]);
        Route::get('activities', [ActivityConttroller::class, 'index']);
        Route::get('roles', [RoleController::class, 'index']);
        Route::resource('categories', CategoryController::class)->except(['index', 'show']);
        Route::resource('labels', LabelController::class)->except(['index', 'show']);
    });
});
