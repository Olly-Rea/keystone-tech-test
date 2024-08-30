<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends Controller
{
    /**
     * Retrieves all stored tags from the database.
     *
     * @return void
     */
    public static function index(): array
    {
        return Tag::whereIn('name', ["laravel", "vue", "vue.js", "php", "api"])->get()->toArray();
    }
}
