<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Retrieves all stored tags from the database.
     *
     * @return void
     */
    public static function show(): array
    {
        return Tag::all()->toArray();
    }
}
