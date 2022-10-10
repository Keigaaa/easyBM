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

    public static function alreadyExist($name, $user)
    {
        $names = DB::table('tags')->where('name', $name);
        if (isset($names)) {
            return true;
        } else {
            return false;
        }
        // link 
    }
}
