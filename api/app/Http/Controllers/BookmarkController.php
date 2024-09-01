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
     * Fetch links from "https://pinboard.in/u:alasdairw?per_page=120" and seed the DB.
     *
     * @return void
     */
    public static function fetch(BookmarkFetchService $linkFetchService): Response
    {
        return $linkFetchService();
    }

    /**
     * Return the number of bookmarks in the DB.
     *
     * @return int
     */
    public static function count(): int
    {
        return Bookmark::count();
    }

    /**
     * Retrieves all stored links from the database (caching response).
     *
     * @return void
     */
    public static function index(): Collection
    {
        return \Cache::remember(
            key: 'bookmarksIndex',
            ttl: 60, // Cache for 1 minute
            callback: fn () => Bookmark::with('tags:name')->get()
        );
    }

    /**
     * Retrieves specific links from the database (caching response).
     *
     * @return void
     */
    public static function byTag(Tag $tag): Collection
    {
        return \Cache::remember(
            key: "bookmarksByTag-$tag",
            ttl: 60, // Cache for 1 minute
            callback: fn () => $tag->bookmarks()->with('tags:name')->get()
        );
    }
}
