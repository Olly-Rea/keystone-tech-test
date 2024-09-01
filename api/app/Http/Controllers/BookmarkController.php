<?php

namespace App\Http\Controllers;

use App\Http\Services\Bookmarks\BookmarkFetchService;
use App\Models\Bookmark;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

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
    public static function byTags(Request $request): Collection
    {
        // Get the tags from the POST request (and sort numerically)
        $tags = $request->post('tags', []);
        sort($tags, SORT_NUMERIC);
        // Create the query
        $bookmarkQuery = Bookmark::with('tags:name');
        foreach ($tags as $tag) {
            $bookmarkQuery->whereHas('tags', function ($query) use (&$tag) {
                $query->where('id', $tag);
            });
        }
        // Return the output
        return \Cache::remember(
            key: 'bookmarksByTag-' . join($tags),
            ttl: 60, // Cache for 1 minute
            callback: fn () => $bookmarkQuery->get()
        );
    }
}
