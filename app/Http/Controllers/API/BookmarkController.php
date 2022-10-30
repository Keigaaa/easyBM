<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookmarkResource;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Tag;

class BookmarkController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(BookmarkResource::collection(Bookmark::where('idOwnerBookmark', '=', Auth::user()->id)->get()), 'Bookmark retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'url' => 'url|max:255|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'commentary' => 'string|nullable|max:255',
            'idFolder' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError("Validation Error.", $validator->errors());
        }

        $bookmark = new Bookmark();
        $bookmark->owner()->associate(Auth::user());
        $bookmark->name = $request->name;
        $bookmark->url = $request->url;
        $bookmark->commentary = $request->commentary;
        $bookmark->idFolder = $request->idFolder;
        $bookmark->save();
        return $this->sendResponse(new BookmarkResource($bookmark), 'Bookmark created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bookmark = Bookmark::findOrFail($id);
        if (!Gate::allows('bookmark_owned', $bookmark)) {
            return $this->sendError(null, 'Unauthorized resource.', 403);
        }
        return $this->sendResponse(new BookmarkResource($bookmark), 'Bookmark showed successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bookmark $bookmark)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'url' => 'url|max:255|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'commentary' => 'string|nullable|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError("Validation Error.", $validator->errors());
        }

        if (!Gate::allows('bookmark_owned', $bookmark)) {
            return $this->sendError(null, 'Unauthorized resource.', 403);
        }
        $input = $request->all();
        if (isset($input['name'])) {
            $bookmark->name = $request->name;
        }
        if (isset($input['url'])) {
            $bookmark->url = $request->url;
        }
        if (isset($input['commentary'])) {
            $bookmark->commentary = $request->commentary;
        }
        $bookmark->save();
        return $this->sendResponse(new BookmarkResource($bookmark), 'Bookmark updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bookmark $bookmark)
    {
        if (!Gate::allows('bookmark_owned', $bookmark)) {
            return $this->sendError(null, 'Unauthorized resource.', 403);
        }
        $bookmark->delete();
        return $this->sendResponse(null, 'Bookmark deleted successfully');
    }

    /**
     * Returns the bookmark corresponding to the ID sent in the request.
     *
     * @param Request $request
     * @return Bookmark
     */
    static public function getBookmark(Request $request)
    {
        return Bookmark::findOrFail($request->bookmark_id);
    }

    /**
     * Remove a tag from a bookmark.
     *
     * @param \App\Models\Bookmark $bookmark
     * @param \App\Models\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function destroyForBookmark(Bookmark $bookmark, Tag $tag)
    {
        if (TagController::index()->contains($tag)) {
            $bookmark->tags()->detach($tag);
            return $this->sendResponse(null, 'Tag deleted successfully');
        };
        return $this->sendError(null, 'Unauthorized resource.', 403);
    }
}
