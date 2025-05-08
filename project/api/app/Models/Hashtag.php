<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hashtag extends Model {

    protected $fillable = [
        "tag",
    ];

    public function entities(): BelongsToMany {
        return $this->belongsToMany(
            Entity::class,
            "hashtag_entity",
            "hashtag_id",
            "entity_id");
    }
}
