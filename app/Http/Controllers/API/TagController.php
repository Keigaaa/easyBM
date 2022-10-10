<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Folder;
use App\Models\Bookmark;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\Auth;

class TagController extends BaseController
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $name = $request->name;
        dd(Tag::alreadyExist($user, $name));
        if ((isset($request->folder_id)) && (isset($request->bookmark_id))) {
            return $this->sendError(null, 'Bad request.', 400);
        };
        if (isset($request->folder_id)) {
            $input = $request->all();
            $folder = Folder::findOrFail($input['folder_id']);
            $tag = new Tag();
            $tag->name = $request->name;
            $folder->tags()->save($tag);
            return $this->sendResponse(new TagResource($tag), 'Tag created successfully');
        } elseif (isset($request->bookmark_id)) {
            $input = $request->all();
            $bookmark = Bookmark::findOrFail($input['bookmark_id']);
            $tag = new Tag();
            $tag->name = $request->name;
            $bookmark->tags()->save($tag);
            return $this->sendResponse(new TagResource($tag), 'Tag created successfully');
        } else {
            return $this->sendError(null, 'Bad request.', 400);
        }
    }
}
