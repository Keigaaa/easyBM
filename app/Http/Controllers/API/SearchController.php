<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\BookmarkResource;
use App\Http\Resources\FolderResource;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends BaseController
{
    public function Search(Request $request)
    {

        $tags = [];
        $input = $request->all();
        if (isset($input["tags"])) {
            $expl = explode(',', $input["tags"]);

            foreach ($expl as $item) {
                $item = trim($item);
                if (!empty($item)) {
                    $tags[] = $item;
                }
            }
        }


        $bookmarksraw = DB::table("Bookmark")
            ->join('Folder', 'Bookmark.Folder', '=', 'Folder.ID')
            ->where('Folder.WebUser', '=', Auth::user()->id);
        if (isset($input['name'])) {
            $n = $input['name'];
            $bookmarksraw = $bookmarksraw
                ->where(function ($query) use ($n) {
                    $query->where('Bookmark.Name', "LIKE", "%$n%")
                        ->orWhere('Bookmark.URL', 'LIKE', "%$n%");
                });
        }

        if (sizeof($tags) >= 1) {
            $bookmarksraw = $bookmarksraw
                ->join('Bookmark_Tag', 'Bookmark.ID', '=', 'Bookmark_Tag.Bookmark')
                ->whereIn('Bookmark_Tag.Tag', $tags)
                ->groupBy("Bookmark.ID", "Bookmark.Name", "Bookmark.Comment", "Bookmark.URL", "Bookmark.CreationDate", "Bookmark.ImgUrl", "Bookmark.Folder")
                ->having(DB::raw('count(Bookmark_Tag.Tag)'), '=', sizeof($tags));
        }

        $bookmarksraw = $bookmarksraw
            ->select("Bookmark.*")
            ->get();


        //## folders

        $foldersraw = DB::table('Folder')
            ->where('Folder.WebUser', '=', Auth::user()->id);
        if (sizeof($tags) >= 1) {
            $foldersraw = $foldersraw
                ->join('Folder_Tag', 'Folder.ID', '=', 'Folder_Tag.Folder')
                ->whereIn('Folder_Tag.Tag', $tags)
                ->groupBy('Folder.ID', 'Folder.Name', 'Folder.ImgUrl', 'Folder.Container', 'Folder.WebUser')
                ->having(DB::raw('count(distinct (Folder_Tag.Tag))'), '=', sizeof($tags));
        }
        if (isset($input['name'])) {
            $foldersraw = $foldersraw
                ->where('Folder.Name', 'LIKE', "%$n%");
        }
        $foldersraw = $foldersraw
            ->select('Folder.*')
            ->get();


        return $this->sendresponse(['bookmarks' => BookmarkResource::collection($bookmarksraw), 'folders' => FolderResource::collection($foldersraw)], "résultats");
    }
}
