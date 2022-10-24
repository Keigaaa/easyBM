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
     * Vérifie si on a passé en paramètre les ID favoris et Bookmarks
     *
     * @param Request $request
     * @return boolean
     */
    public function isDoubleIdError(Request $request)
    {
        return (isset($request->folder_id)) && (isset($request->bookmark_id));
    }

    public function storeForFolder(Request $request)
    {
        if (TagController::isDoubleIdError($request)) {
            return $this->sendError(null, 'Bad request.', 400);
        };

        $folder = FolderController::getFolder($request);
        $tag = Tag::existInFolder(Auth::user(), $request->name);

        if (FolderController::getRoot($request) === 1) {
            if (!$tag->isEmpty()) {
                $tag = Tag::findOrFail($tag->first()->id); // TODO change code there (non sense)
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
}
