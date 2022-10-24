<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    use HasFactory;

    public function folders()
    {
        return $this->morphedByMany(Folder::class, 'taggable');
    }

    public function bookmarks()
    {
        return $this->morphedByMany(Bookmark::class, 'taggable');
    }

    public static function existInFolder($user, $name)
    {
        $existInFolder = DB::table('users')
            ->join('folders', 'idOwnerFolder', '=', 'users.id')
            ->join('taggables', 'taggable_id', '=', 'folders.id')
            ->join('tags', 'tags.id', '=', 'tag_id')
            ->where('idOwnerFolder', '=', $user->id)
            ->where('tags.name', '=', $name)
            ->select('tags.id')
            ->get();

        return $existInFolder;
    }

    public static function existInBookmark($user, $name)
    {
        $existInBookmark = DB::table('users')
            ->join('bookmarks', 'idOwnerBookmark', '=', 'users.id')
            ->join('taggables', 'taggable_id', '=', 'folders.id')
            ->join('tags', 'tags.id', '=', 'tag_id')
            ->where('idOwnerBookmark', '=', $user->id)
            ->where('tags.name', '=', $name)
            ->select('tags.id')
            ->get();

        return $existInBookmark;
    }
}

    /*public static function tag_owned($user, $tag)
    {
        $userTag = DB::table('users')
            ->join('folders', 'idOwnerFolder', '=', 'users.id')
            ->join('taggables', 'taggable_id', '=', 'folders.id')
            ->join('tags', 'tags.id', '=', 'tag_id')
            ->where('idOwnerFolder', '=', $user->id)
            ->where('tags.name', '=', $tag->name)
            ->select('tag_id')
            ->get();

        return $userTag;

        // casser l'associùation du tag dans taggable
    }*/
