<?php

namespace App\Models;

use App\Enums\EntityType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Entity {

    protected $table = "posts";

    protected $fillable = [
        "id",
        "title",
        "content",
    ];


    protected $hidden = [
        "entity",
    ];


    protected $appends = [
        "author_id",
    ];

    public function versions(): BelongsToMany {
        return $this
            ->belongsToMany(
                Version::class,
                "version_post",
                "post_id",
                "version_id"
            );
    }


    public function assets(): HasMany {
        return $this->hasMany(Asset::class);
    }


    public function entity(): BelongsTo {
        return $this->belongsTo(Entity::class, "id", "id");
    }

    public function getAuthorIdAttribute() {
        return $this->entity->author_id;
    }

    // custom creators

    public static function createPost(int $authorId, string $title, string $content, array $assets = []): Post {
        // create entity

        $entity = new Entity([
            "entity_type" => EntityType::POST,
            "author_id" => $authorId,
        ]);

        $entity->save();

        $post = new Post([
            "id" => $entity->id,
            "title" => $title,
            "content" => $content,
        ]);

        $post->save();

        return $post;
    }
}
