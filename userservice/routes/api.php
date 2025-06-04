<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

    Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/username/{username}', [UserController::class, 'showByUsername']);
    Route::get('/by-name/{name}', [UserController::class, 'getByName']);
});
