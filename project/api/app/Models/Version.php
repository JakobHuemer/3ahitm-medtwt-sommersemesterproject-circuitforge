<?php

namespace App\Models;

use App\Enums\VersionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Version extends Model {

    protected $primaryKey = "version";
    public $incrementing = false;
    protected $keyType = "string";

    protected $fillable = [
        "version",
        "type",
        "released",
        "is_latest"
    ];

    public $timestamps = false;

    protected function casts() {
        return [
            "type" => VersionType::class,
            "released" => "datetime",
            "is_latest" => "boolean"
        ];
    }

    public function posts(): BelongsToMany {
        return $this->belongsToMany(Post::class, "version_post", "version_id", "post_id");
    }

}
