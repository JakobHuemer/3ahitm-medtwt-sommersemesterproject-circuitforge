<?php

namespace App\Models;

use App\Enums\EntityType;
use App\Enums\Rating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class Entity extends Model {
    protected $table = "entities";

    protected $fillable = [
        "entity_type",
        "author_id"
    ];

    public function author(): BelongsTo {
        return $this->belongsTo(User::class, "author_id");
    }

    public function hashtags(): BelongsToMany {
        return $this->belongsToMany(
            Hashtag::class,
            "hashtag_entity",
            "entity_id",
            "hashtag_id");
    }

    public function ratings(): BelongsToMany {
        return $this->belongsToMany(
            User::class,
            "ratings",
            "entity_id",
            "user_id")->withPivot("rating");
    }

    protected function casts(): array {
        return [
            'entity_type' => EntityType::class
        ];
    }

    public static function createWithType(EntityType $type, int $authorId) {
        return new Entity([
            "author_id" => $authorId,
            "entity_type" => $type,
        ]);
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

}
