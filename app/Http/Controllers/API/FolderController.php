<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FolderResource;
use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $folder = Folder::all();
        return $this->sendResponse(FolderResource::collection($folder), 'Folder retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $folder = new Folder();
        $folder->name = $request->name;
        $folder->idParent = $request->idParent;
        $folder->save();
        return $this->sendResponse(new FolderResource($folder), 'Folder created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $folder = Folder::findOrFail($id);
        return $this->sendResponse(new FolderResource($folder), 'Folder showed successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Folder $folder)
    {
        $input = $request->all();
        if (isset($input['name'])) {
            $folder->name = $request->name;
        }
        if (isset($input['url'])) {
            $folder->url = $request->url;
        }
        if (isset($input['commentary'])) {
            $folder->commentary = $request->commentary;
        }
        $folder->save();
        return $this->sendResponse(new FolderResource($folder), 'Folder updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folder $folder)
    {
        $folder->delete();
        return $this->sendResponse(null, 'Folder deleted successfully');
    }
}
