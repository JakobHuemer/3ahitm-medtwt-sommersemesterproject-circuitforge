<?php

namespace App\Models;

use App\Enums\VersionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Version extends Model {

    protected $primaryKey = "version";
    protected $keyType = "string";

    protected $fillable = [
        "version",
        "type",
        "released",
    ];

    public $timestamps = false;

    protected function casts() {
        return [
            "type" => VersionType::class,
            "released" => "datetime"
        ];
    }

    public function posts(): BelongsToMany {
        return $this->belongsToMany(Post::class, "version_post", "post_id", "version_id");
    }

}
