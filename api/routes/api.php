<?php

use App\Http\Controllers\LinkController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/links', [LinkController::class, 'index']);
Route::get('/links/fetch', [LinkController::class, 'fetch']);
Route::get('/links/tag/{tag}', [LinkController::class, 'byTag']);
Route::get('/tags/get', [TagController::class, 'show']);
