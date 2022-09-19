<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookmarkResource;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookmark = Bookmark::all(); // TODO ajouter la gate pour retourner que ceux que le user peut voir
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
        $bookmark->delete();
        return $this->sendResponse(null, 'Bookmark deleted successfully');
    }
}
