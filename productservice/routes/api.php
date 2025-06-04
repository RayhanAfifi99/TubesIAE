<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
Route::post('/products/{id}/decrease-stock', [ProductController::class, 'decreaseStock']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/by-name/{name}', [ProductController::class, 'getByName']);