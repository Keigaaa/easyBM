<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $table = 'bookmarks';

    public function folder()
    {
        return $this->belongsTo(Folder::class, 'idFolder');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'idOwnerBookmark');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
