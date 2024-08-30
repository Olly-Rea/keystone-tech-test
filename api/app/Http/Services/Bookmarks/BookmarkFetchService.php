<?php

namespace App\Http\Services\Bookmarks;

use Cache;
use DB;
use Illuminate\Http\Response;

/**
 * Service class for the BookmarkController::fetch() method
 *
 * @return array
 */
class BookmarkFetchService
{
    public function __invoke(): Response
    {
        // Get the page HTML from the URL
        $bookmarks = Cache::remember(
            key: 'bookmarks',
            ttl: 60, // Cache for 1 minute
            callback: function () {
                $pageData = file_get_contents('https://pinboard.in/u:alasdairw?per_page=120');
                // Cleanup retrieved HTML data
                $bookmarksHTML = str_replace(
                    ' & ',
                    ' &amp; ',
                    tidy_repair_string(
                        $pageData,
                        ['output-html' => true]
                    )
                );
                // Create a DOMDocument from the cleaned up HTML string
                $dom = new \DOMDocument();
                $dom->loadHTML($bookmarksHTML);
                // Filter only the bookmark links
                $bookmarksDOM = (new \DOMXPath($dom))->query("//div[@class='bookmark']//div[@class='display']");
                // Loop through the bookmark links
                $bookmarks = [];
                $bookmarkID = 1;
                foreach ($bookmarksDOM as $bookmark) {
                    $validTags = false;
                    $details = [];
                    foreach ($bookmark->childNodes as $child) {
                        if(get_class($child) === "DOMElement") {
                            if ($child->className === 'bookmark_title') {
                                $details['title'] = $child->nodeValue;
                                foreach ($child->attributes as $attribute) {
                                    if($attribute->nodeName === 'href') {
                                        $details['url'] = $attribute->nodeValue;
                                        break;
                                    }
                                }
                            }
                            if ($child->className === 'description') {
                                $details['description'] = $child->nodeValue;
                            }
                            if ($child->className === 'tag') {
                                // Check to see if the bookmark contains the appropriate tags
                                if (in_array($child->nodeValue, ["laravel", "vue", "vue.js", "php", "api"])) {
                                    $validTags = true;
                                }
                                $details['tags'][] = $child->nodeValue;
                            }
                            if ($child->className === 'when') {
                                foreach ($child->attributes as $attribute) {
                                    if($attribute->nodeName === 'title') {
                                        $when = preg_replace('/\s+/', '', $attribute->nodeValue);
                                        $details['created_at'] = \DateTime::createFromFormat("Y.m.d H:i:s", $when);
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    if ($validTags) {
                        $details['id'] = $bookmarkID++;
                        $bookmarks[] = $details;
                    }
                }
                return $bookmarks;
            }
        );

        // Get the list of unique tags from the refined bookmarks
        $tags = array_unique(
            collect(
                array_map(fn ($bookmark) => $bookmark['tags'], $bookmarks)
            )->flatten()->toArray()
        );

        $bookmarkTagMap = [];
        // Map the tags to the tag id
        array_walk($bookmarks, function (&$bookmark) use ($tags, &$bookmarkTagMap) {
            foreach($bookmark['tags'] as &$tag) {
                $bookmarkTagMap[] = [
                    'bookmark_id' => $bookmark['id'],
                    'tag_id' => array_search($tag, $tags) + 1
                ];
            }
            unset($bookmark['tags']);
        });

        $tags = array_map(
            fn ($key, $tag) => ['id' => $key + 1, 'name' => $tag],
            array_keys($tags),
            $tags
        );

        // Clear tables for re-seeding
        DB::table('bookmark_tags')->delete();
        DB::table('bookmarks')->delete();
        DB::table('tags')->delete();
        // Start a DB transaction
        DB::transaction(function () use ($tags, $bookmarks, $bookmarkTagMap): void {
            // Bulk insert all data - adding pivot table data last (FK constraints)
            DB::table('tags')->insert($tags);
            DB::table('bookmarks')->insert($bookmarks);
            DB::table('bookmark_tags')->insert($bookmarkTagMap);
        });

        // return a "201 Created" response
        return response(status: 201);
    }
}
