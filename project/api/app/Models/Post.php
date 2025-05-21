<?php

namespace App\Models;

use App\Enums\AssetType;
use App\Enums\EntityType;
use App\Enums\Rating;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Post extends Entity {

    protected $table = "posts";

    protected $fillable = [
        "id",
        "title",
        "content",
        "archived"
    ];


    protected $hidden = [
        "entity",
        "pivot"
    ];


    protected $appends = [
        "author_id",
        "rating"
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

    public function casts(): array {
        return [
            "archived" => "boolean",
            "content" => "array"
        ];
    }

    public function getRatingAttribute(): int {
        return $this->getRating();
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

    public function rate(Rating $rating) {

        $this->ratings()->syncWithoutDetaching([
            Auth::id() => [
                "rating" => $rating
            ]
        ]);

    }

    public function unrate() {
        $this->ratings()->detach(Auth::id());
    }

    public function getRating(): int {
        return $this->ratings()->where("rating", 1)->count()
            - $this->ratings()->where("rating", -1)->count();

    }

    // custom creators

    /**
     * Create a new post.
     *
     * @param int $authorId
     * @param string $title
     * @param string $content
     * @param Asset[] $assets
     * @return Post
     */
    public static function createPost(int $authorId, string $title, string $content, array $assets = [], array $versions = []): Post {
        // create entity

        $entity = new Entity([
            "entity_type" => EntityType::POST,
            "author_id" => $authorId,
        ]);

        $entity->save();

        $post = new Post([
            "id" => $entity->id,
            "title" => $title,
            "content" => json_decode($content),
        ]);

        $post->save();

        // add assets
        $post->assets()->saveMany($assets);

        // add versions
        $post->versions()->attach($versions);

        return $post;
    }

    public function updatePost(string $title, string $content, array $assets = [], array $versions = []) {
        $this->title = $title;
        $this->content = json_decode($content);
        $this->save();

        // add assets
        $this->assets()->delete();
        $this->assets()->saveMany($assets);

        // overwrite versions
        $this->versions()->detach();
        $this->versions()->attach($versions);

        $this->refresh();
    }

    public static function fromDataToAssets($data): array {
        $images = $data["images"] ?? null;
        $assets = [];
        if ($images) {
            foreach ($images as $image) {
                $assets[] = Asset::makeAssetFromFile($image, AssetType::IMAGE);
            }
        }

        $files = $data["assets"] ?? null;
        if ($files) {
            foreach ($files as $file) {
                $assets[] = Asset::makeAssetFromFile($file, AssetType::ASSET);
            }
        }

        return $assets;
    }
}
