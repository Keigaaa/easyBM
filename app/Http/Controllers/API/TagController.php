<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Folder;
use App\Http\Resources\TagResource;

class TagController extends BaseController
{
    public function store(Request $request)
    {
        $input = $request->all();
        $folder = Folder::findOrFail($input['folder_id']);
        $tag = new Tag();
        $tag->name = $request->name;
        $folder->tags()->save($tag);
        return $this->sendResponse(new TagResource($tag), 'Tag created successfully');
        /// TODO gate for search function
        // bookmark or folder if
        // si il existe ou pas
    }
}
