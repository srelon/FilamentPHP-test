<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

Route::get('layout', [LayoutController::class, 'index']);
Route::post('newsletter', [NewsletterController::class, 'store']);
Route::get('home', [HomeController::class, 'index']);
