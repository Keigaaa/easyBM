<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    /**
     * Returns the bookmarks inside the current folder.
     *
     * @return hasMany
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'idFolder');
    }

    /**
     * Returns the folders inside the current folder.
     *
     * @return hasMany
     */
    public function folders()
    {
        return $this->hasmany(Folder::class, 'idParent');
    }

    /**
     * Returns the parent folder of the current folder.
     *
     * @return belongsTo
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'idParent');
    }

    /**
     * Returns the user who owns the current folder.
     *
     * @return belongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'idOwnerFolder');
    }

    /**
     * Returns the tags that belong to the current folder.
     *
     * @return morphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
