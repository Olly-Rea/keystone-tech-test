<?php

namespace App\Http\Controllers;

use App\Http\Services\Bookmarks\BookmarkFetchService;
use App\Models\Bookmark;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

class BookmarkController extends Controller
{
    /**
     * Fetch links from "https://pinboard.in/u:alasdairw?per_page=120" and seed the DB
     *
     * @return void
     */
    public static function fetch(BookmarkFetchService $linkFetchService): Response
    {
        return $linkFetchService();
    }

    /**
     * Retrieves all stored links from the database (caching response).
     *
     * @return void
     */
    public static function index(): Collection
    {
        return Bookmark::with('tags:name')->get();
    }

    /**
     * Retrieves specific links from the database (caching response).
     *
     * @return void
     */
    public static function byTag(Tag $tag): Collection
    {
        return $tag->bookmarks()->with('tags:name')->get();
    }
}
