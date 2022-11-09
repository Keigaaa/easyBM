<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SearchController extends BaseController
{

    /**
     * Search a folder.
     *
     * @param [string] $search
     * @return collection
     */
    public function searchFolder($search)
    {
        $folder = DB::table('folders')
            ->where('idOwnerFolder', '=', Auth::user()->id)
            ->where('folders.name', 'like', '%' . $search . '%')
            ->get();

        if (isset($folder)) {
            return $folder;
        }
        return $this->sendError(null, 'Ressource not found', 403);
    }

    /**
     * Search a bookmark.
     *
     * @param [string] $search
     * @return collection
     */
    public function searchBookmark($search)
    {
        $folder = DB::table('bookmarks')
            ->where('idOwnerBookmark', '=', Auth::user()->id)
            ->where('bookmarks.name', 'like', '%' . $search . '%')
            ->get();

        if (isset($folder)) {
            return $folder;
        }
        return $this->sendError(null, 'Ressource not found', 403);
    }

    /**
     * Search a tag in bookmark.
     *
     * @param [string] $search
     * @return collection
     */
    public function searchTagInBookmark($search)
    {
        $tagInBookmark = DB::table('bookmarks')
            ->join('taggables', 'taggable_id', '=', 'bookmarks.id')
            ->join('tags', 'tags.id', '=', 'tag_id')
            ->where('taggable_type', '=', 'App\Models\Bookmark')
            ->where('idOwnerBookmark', '=', Auth::user()->id)
            ->where('tags.name', 'like', '%' . $search . '%')
            ->select('tags.id', 'tags.name', 'tags.created_at', 'tags.updated_at')
            ->get();

        if (isset($tagInBookmark)) {
            return $tagInBookmark;
        }
        return $this->sendError(null, 'Ressource not found', 403);
    }

    /**
     * Search a tag in folder.
     *
     * @param [string] $search
     * @return collection
     */
    public function searchTagInFolder($search)
    {
        $tagInFolder = DB::table('folders')
            ->join('taggables', 'taggable_id', '=', 'folders.id')
            ->join('tags', 'tags.id', '=', 'tag_id')
            ->where('taggable_type', '=', 'App\Models\Folder')
            ->where('idOwnerFolder', '=', Auth::user()->id)
            ->where('tags.name', 'like', '%' . $search . '%')
            ->select('tags.id', 'tags.name', 'tags.created_at', 'tags.updated_at')
            ->get();

        if (isset($tagInFolder)) {
            return $tagInFolder;
        }
        return $this->sendError(null, 'Ressource not found', 403);
    }
    /**
     * Search in folders, bookmarks and tags.
     *
     * @param [string] $search
     * @return collection
     */
    public function searchAll($search)
    {
        return 'Folders :<br>' . SearchController::searchFolder($search) . '<br>Bookmarks :<br>' . SearchController::searchBookmark($search) . '<br>Tags in folders :<br>' . SearchController::searchTagInFolder($search) . '<br>Tags in bookmarks :<br>' . SearchController::searchTagInBookmark($search);
    }
}
