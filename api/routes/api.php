<?php

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/bookmarks', [BookmarkController::class, 'index']);
Route::get('/bookmarks/count', [BookmarkController::class, 'count']);
Route::get('/bookmarks/fetch', [BookmarkController::class, 'fetch']);
Route::get('/bookmarks/tag/{tag}', [BookmarkController::class, 'byTag']);
Route::get('/tags', [TagController::class, 'index']);
