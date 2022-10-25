<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Folder;
use App\Models\Bookmark;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeForFolder(Request $request)
    {
        if (TagController::isDoubleId($request) || (TagController::isNoId($request))) {
            return $this->sendError(null, 'Bad request.', 400);
        };

        $folder = FolderController::getFolder($request);
        $tag = Tag::existInFolder(Auth::user(), $request->name);

        if (FolderController::getRoot($request) === 1) {
            if (!$tag->isEmpty()) {
                $tag = Tag::findOrFail($tag->first()->id);
                $folder->tags()->save($tag);
                return $this->sendResponse(new TagResource($tag), 'Tag updated successfully');
            } elseif (FolderController::getFolder($request)) {
                $tag = new Tag();
                $tag->name = $request->name;
                $folder->tags()->save($tag);
                return $this->sendResponse(new TagResource($tag), 'Tag created successfully');
            } else {
                return $this->sendError(null, 'Bad request.', 400);
            }
        } else {
            return $this->sendError(null, 'Bad request.', 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeForBookmark(Request $request)
    {
        if (TagController::isDoubleId($request) || (TagController::isNoId($request))) {
            return $this->sendError(null, 'Bad request.', 400);
        };

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
        } else {
            return $this->sendError(null, 'Bad request.', 400);
        }
    }
}
