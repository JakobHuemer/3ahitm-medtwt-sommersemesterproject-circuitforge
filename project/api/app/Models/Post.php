<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Entity
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $table = "posts";

    protected $fillable = [
        "id",
        "title",
        "content",
    ];


    protected $appends = [
        "author_id",
    ];


    public function entity(): BelongsTo {
        return $this->belongsTo(Entity::class, "id", "id");
    }

    public function getAuthorIdAttribute() {
        return $this->entity->author_id;
    }
}
