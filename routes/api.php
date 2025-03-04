<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\CategoryController;
// use App\Http\Controllers\Controller;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationUserController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/google/check', [AuthController::class, 'checkGoogleUser']);
Route::post('/auth/google', [AuthController::class, 'loginWithGoogle']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::get('auctions', [AuctionController::class, 'checkAuctionStatus']);
    Route::get('usersList', [UserController::class, 'usersList']);

    Route::middleware(['role:admin'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::put('users/{id}/status', [UserController::class, 'updateStatus']);
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', RoleController::class);
    });

    // Route::apiResource('users', UserController::class);
    // Route::patch('users/{id}/status', [UserController::class, 'updateStatus']);
    // Route::apiResource('roles', RoleController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('auctions', AuctionController::class);
    Route::apiResource('bids', BidController::class);
    Route::apiResource('attachments', AttachmentController::class);
    Route::apiResource('notifications', NotificationController::class);
    Route::apiResource('messages', MessageController::class);
    Route::apiResource('tickets', TicketController::class);
    Route::apiResource('payment-methods', PaymentMethodController::class);
    Route::apiResource('notification_user', NotificationUserController::class);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::get('messages/conversations/{userId}', [MessageController::class, 'getConversations']);
    Route::get('messages/{userId}/{otherUserId}', [MessageController::class, 'getMessagesBetweenUsers']);
});
