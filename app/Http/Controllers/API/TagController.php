<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Folder;
use App\Models\Bookmark;
use App\Http\Resources\TagResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TagController extends BaseController
{

    /**
     * Checks if bookmarks and folders IDs have been sent as parameters.
     *
     * @param Request $request
     * @return boolean
     */
    public function isDoubleId(Request $request)
    {
        return (isset($request->folder_id)) && (isset($request->bookmark_id));
    }

    /**
     * Check if no IDs is sent as parameter.
     *
     * @param Request $request
     * @return boolean
     */
    public function isNoId(Request $request)
    {
        return (!isset($request->folder_id)) && (!isset($request->bookmark_id));
    }

    /**
     * Store a newly created resource in storage,
     * a tag for folder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeForFolder(Request $request)
    {
        $folder = FolderController::getFolder($request);
        $tag = Tag::existInFolder(Auth::user(), $request->name);
        if (FolderController::getRoot($request) !== $request->folder_id) {
            if (!$tag->isEmpty()) {
                $tag = Tag::findOrFail($tag->first()->id);
                $folder->tags()->save($tag);
                return $this->sendResponse(new TagResource($tag), 'Tag updated successfully');
            } elseif (FolderController::getFolder($request)) {
                $tag = new Tag();
                $tag->name = $request->name;
                $folder->tags()->save($tag);
                return $this->sendResponse(new TagResource($tag), 'Tag created successfully');
            }
        }
        return $this->sendError(null, 'Bad request.', 400);
    }

    /**
     * Store a newly created resource in storage,
     * a tag for bookmark.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeForBookmark(Request $request)
    {
        $bookmark = BookmarkController::getBookmark($request);
        $tag = Tag::existInBookmark(Auth::user(), $request->name);
        if (!$tag->isEmpty()) {
            $tag = Tag::findOrFail($tag->first()->id);
            $bookmark->tags()->save($tag);
            return $this->sendResponse(new TagResource($tag), 'Tag updated successfully');
        } elseif (BookmarkController::getBookmark($request)) {
            $tag = new Tag();
            $tag->name = $request->name;
            $bookmark->tags()->save($tag);
            return $this->sendResponse(new TagResource($tag), 'Tag created successfully');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (TagController::isDoubleId($request) || (TagController::isNoId($request))) {
            return $this->sendError(null, 'Bad request.', 400);
        };
        if (isset($request->folder_id)) {
            return TagController::storeForFolder($request);
        }
        return TagController::storeForBookmark($request);
    }

    /**
     * Display a listing of the resource,
     * tags in folders.
     *
     * @return collection
     */
    public function indexForFolder()
    {
        $tagsId =  DB::table('users')
            ->join('folders', 'idOwnerFolder', '=', 'users.id')
            ->join('taggables', 'taggable_id', '=', 'folders.id')
            ->join('tags', 'tags.id', '=', 'tag_id')
            ->where('idOwnerFolder', '=', Auth::user()->id)
            ->where('taggable_type', '=', 'App\Models\Folder')
            ->select('tag_id')
            ->distinct()
            ->get();

        $tagsValue = json_decode(json_encode($tagsId), true);
        return Tag::findMany($tagsValue);
    }

    /**
     * Display a listing of the resource,
     * tags in bookmarks.
     *
     * @return collection
     */
    public function indexForBookmark()
    {
        $tagsId =  DB::table('users')
            ->join('bookmarks', 'idOwnerBookmark', '=', 'users.id')
            ->join('taggables', 'taggable_id', '=', 'bookmarks.id')
            ->join('tags', 'tags.id', '=', 'tag_id')
            ->where('idOwnerBookmark', '=', Auth::user()->id)
            ->where('taggable_type', '=', 'App\Models\Bookmark')
            ->select('tag_id')
            ->distinct()
            ->get();

        $tagsValue = json_decode(json_encode($tagsId), true);
        return Tag::findMany($tagsValue);
    }

    /**
     * Display a listing of the resource.
     *
     * @return collection
     */
    public function index()
    {
        return TagController::indexForFolder()->merge(TagController::indexForBookmark());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        if (TagController::index()->contains($tag)) {
            $tag->name = $request->name;
            $tag->save();
            return $this->sendResponse(new TagResource($tag), 'Tag updated successfully');
        }
        return $this->sendError(null, 'Unauthorized resource.', 403);
    }

    public function destroy(Tag $tag)
    {
        DB::table('taggables')
            ->join('tags', 'tags.id', '=', 'tag_id')
            ->where('tag_id', '=', $tag->id)
            ->get()->dd();


        /*if(TagController::index()->contains($tag) {
        })*/
        return $this->sendError(null, 'Unauthorized resource.', 403);
    }
}
