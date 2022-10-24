<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FolderResource;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class FolderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $folder = Folder::where('idOwnerFolder', '=', Auth::user()->id)->get();
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
        $folder->owner()->associate(Auth::user());
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
        if (!Gate::allows('folder_owned', $folder)) {
            return $this->sendError(null, 'Unauthorized resource.', 403);
        } else {
            $folder = Folder::findOrFail($id);
            return $this->sendResponse(new FolderResource($folder), 'Folder showed successfully');
        }
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
        if (!Gate::allows('folder_owned', $folder) && ($folder->name == 'root')) {
            return $this->sendError(null, 'Unauthorized resource.', 403);
        } else {
            $input = $request->all();
            if (isset($input['name'])) {
                $folder->name = $request->name;
            }
            $folder->save();
            return $this->sendResponse(new FolderResource($folder), 'Folder updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folder $folder)
    {
        if (!Gate::allows('folder_owned', $folder) && ($folder->name == 'root')) {
            return $this->sendError(null, 'Unauthorized resource.', 403);
        } else {
            $folder->delete();
            return $this->sendResponse(null, 'Folder deleted successfully');
        }
    }

    /**
     * Returns the folder corresponding to the ID sent in the request.
     *
     * @param Request $request
     * @return Folder
     */
    static public function getFolder(Request $request)
    {
        $folder = Folder::findOrFail($request->folder_id);
        return $folder;
    }

    /**
     * Returns the root folder ID of the user sent in the request. 
     *
     * @return int
     */
    static public function getRoot(Request $request)
    {
        $root = DB::table('folders')
            ->where('name', '=', 'root')
            ->get();
        return $root->first()->id;
    }
}
