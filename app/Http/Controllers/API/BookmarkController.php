<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookmarkResource;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookmark = Bookmark::where('idOwnerBookmark', '=', Auth::user()->id)->get();
        return $this->sendResponse(BookmarkResource::collection($bookmark), 'Bookmark retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        } else {
            return $this->sendResponse(new BookmarkResource($bookmark), 'Bookmark showed successfully');
        }
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
        if (!Gate::allows('bookmark_owned', $bookmark)) {
            return $this->sendError(null, 'Unauthorized resource.', 403);
        } else {
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
        } else {
            $bookmark->delete();
            return $this->sendResponse(null, 'Bookmark deleted successfully');
        }
    }

    /**
     * Returns the bookmark corresponding to the ID sent in the request.
     *
     * @param Request $request
     * @return Bookmark
     */
    static public function getBookmark(Request $request)
    {
        $bookmark = Bookmark::findOrFail($request->bookmark_id);
        return $bookmark;
    }
}
