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
        //Recuperate the user actually register and the name of the tags in the request.
        $user = Auth::user();
        $name = $request->name;
        // If the request contain an id for folder and for bookmark, that send an error.
        if ((isset($request->folder_id)) && (isset($request->bookmark_id))) {
            return $this->sendError(null, 'Bad request.', 400);
        };
        //Use the tag function "alreadyExist" to see if the tag who i want create exist or no.
        $tagExist = Tag::alreadyExist($user, $name);
        //If the tag don't exist, create him.
        if ($tagExist->isEmpty()) {
            //For folder.
            if (isset($request->folder_id)) {
                $input = $request->all();
                $folder = Folder::findOrFail($input['folder_id']);
                $tag = new Tag();
                $tag->name = $request->name;
                $folder->tags()->save($tag);
                return $this->sendResponse(new TagResource($tag), 'Tag created successfully');
                //For bookmark.
            } elseif (isset($request->bookmark_id)) {
                $input = $request->all();
                $bookmark = Bookmark::findOrFail($input['bookmark_id']);
                $tag = new Tag();
                $tag->name = $request->name;
                $bookmark->tags()->save($tag);
                return $this->sendResponse(new TagResource($tag), 'Tag created successfully');
                //If request don't receive id for bookmark or for folder, that return an error.
            } else {
                return $this->sendError(null, 'Bad request.', 400);
            }
            //If the tag exist, associate him to the folder or the bookmaek in the request.
        } else {
            //For folder.
            if (isset($request->folder_id)) {
                $input = $request->all();
                $folder = Folder::findOrFail($input['folder_id']);
                $tag = Tag::findOrFail($tagExist->first()->tag_id);
                $folder->tags()->save($tag);
                return $this->sendResponse(new TagResource($tag), 'Tag updated successfully');
                //For bookmark.
            } elseif (isset($request->bookmark_id)) {
                $input = $request->all();
                $bookmark = Bookmark::findOrFail($input['bookmark_id']);
                $tag = Tag::findOrFail($tagExist->first()->tag_id);
                $bookmark->tags()->save($tag);
                return $this->sendResponse(new TagResource($tag), 'Tag updated successfully');
            }
        }
    }

    /*public function destroy(Tag $tag)
    {
        $user = Auth::user();
        dd(Tag::tag_owned($user, $tag));
    }*/
}
