<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function show(Folder $folder)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folder $folder)
    {
        //
    }
}
