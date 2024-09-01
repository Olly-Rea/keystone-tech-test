<?php

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

// Bookmark routes
Route::get('/bookmarks', [BookmarkController::class, 'index']);
Route::get('/bookmarks/count', [BookmarkController::class, 'count']);
Route::get('/bookmarks/fetch', [BookmarkController::class, 'fetch']);
Route::post('/bookmarks/bytags', [BookmarkController::class, 'byTags']);

// Tag routes
Route::get('/tags', [TagController::class, 'index']);
