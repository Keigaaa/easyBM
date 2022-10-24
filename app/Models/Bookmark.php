<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    /**
     * Returns the folder that contains a bookmark.
     *
     * @return belongsTo
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'idFolder');
    }

    /**
     * Returns the user who owns the current bookmark.
     *
     * @return belongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'idOwnerBookmark');
    }

    /**
     * Returns the tags that belong to the current bookmark.
     *
     * @return morphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
