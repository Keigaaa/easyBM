<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Tag extends Model
{
    use HasFactory;

    /**
     * Returns the tags inside in a folder.
     *
     * @return morphedByMany
     */
    public function folders()
    {
        return $this->morphedByMany(Folder::class, 'taggable');
    }

    /**
     * Returns the tags inside in a bookmark.
     *
     * @return morphedByMany
     */
    public function bookmarks()
    {
        return $this->morphedByMany(Bookmark::class, 'taggable');
    }


    /**
     * Find tag by name
     * @param [string] $tagName
     * @return [Tag] Tag if found else return null
     */
    public static function findByName($tagName)
    {
        $result = Tag::where('name', '=', $tagName)
            ->get();

        return ($result->isEmpty()) ? null : $result->first();
    }
}
