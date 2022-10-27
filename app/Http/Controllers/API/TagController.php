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
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

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
     * Check if tag is in Bookmark
     * @param [tag] tag
     * @param [bookmark] bookmark
     * @return boolean if exist in bookmark
     */
    public function isTagIsInBookmark($tag, $bookmark)
    {
        $tagsInBookmark = $bookmark->tags()->get();
        foreach ($tagsInBookmark as &$tagBookMark) {
            if ($tagBookMark->id == $tag->id)
                return true;
        }
        return false;
    }

    /**
     * Check if tag is in Folder
     * @param [tag] tag
     * @param [folder] folder
     * @return boolean if exist in folder
     */
    public function isTagIsInFolder($tag, $folder)
    {
        $tagsInFolder = $folder->tags()->get();
        foreach ($tagsInFolder as &$tagFolder) {
            if ($tagFolder->id == $tag->id)
                return true;
        }
        return false;
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
        $tag = Tag::findByName($request->name);
        if ($tag == null) {
            $tag = new Tag();
            $tag->name = $request->name;
            $bookmark->tags()->save($tag);
            return $this->sendResponse(new TagResource($tag), 'Tag created successfully');
        } else {
            if ($this->isTagIsInBookmark($tag, $bookmark)) {
                return $this->sendResponse(new TagResource($tag), "Tag already exist in Bookmark");
            } else {
                $bookmark->tags()->save($tag);
                return $this->sendResponse(new TagResource($tag), 'Tag updated successfully');
            }
        }
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
        $tag = Tag::findByName($request->name);
        if ($tag == null) {
            $tag = new Tag();
            $tag->name = $request->name;
            $folder->tags()->save($tag);
            return $this->sendResponse(new TagResource($tag), 'Tag created successfully');
        } else {
            if ($this->isTagIsInFolder($tag, $folder)) {
                return $this->sendResponse(new TagResource($tag), "Tag already exist in Folder");
            } else {
                $folder->tags()->save($tag);
                return $this->sendResponse(new TagResource($tag), 'Tag updated successfully');
            }
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'folder_id' => 'integer|nullable',
            'bookmark_id' => 'integer|nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError("Validation Error.", $validator->errors());
        }
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:tags|max:255',
            'folder_id' => 'integer|nullable',
            'bookmark' => 'integer|nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError("Validation Error.", $validator->errors());
        }
        if (TagController::index()->contains($tag)) {
            $tag->name = $request->name;
            $tag->save();
            return $this->sendResponse(new TagResource($tag), 'Tag updated successfully');
        }
        return $this->sendError(null, 'Unauthorized resource.', 403);
    }

    /*public function destroy(Tag $tag)
    {
        DB::table('taggables')
            ->join('tags', 'tags.id', '=', 'tag_id')
            ->where('tag_id', '=', $tag->id)
            ->get()->dd();

        /*if(TagController::index()->contains($tag) {
        })
        return $this->sendError(null, 'Unauthorized resource.', 403);
    }*/
}
