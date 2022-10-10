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

    public static function alreadyExist($user, $name)
    {
        $tags = DB::table('users')
            ->join('folders', 'idOwnerFolder', '=', 'users.id')
            ->join('taggables', 'taggable_id', '=', 'folders.id')
            ->join('tags', 'tags.id', '=', 'tag_id')
            ->where('idOwnerFolder', '=', $user->id)
            ->where('tags.name', '=', $name)
            ->get();

        if ($tags->isEmpty()) {
            return false;
        } else {
            return true;
        }
    }
}
