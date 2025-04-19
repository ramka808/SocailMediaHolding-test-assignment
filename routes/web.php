<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/addPhones', [ProductController::class, 'index']);
Route::get('/phones', [ProductController::class, 'show']);