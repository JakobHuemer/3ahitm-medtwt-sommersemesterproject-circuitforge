<?php

namespace App\Models;

use App\Enums\EntityType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Entity extends Model {
    protected $table = "entities";

    protected $fillable = [
        "entity_type",
        "author_id"
    ];

    public function author(): BelongsTo {
        return $this->belongsTo(User::class, "author_id");
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

}
