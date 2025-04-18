<?php

namespace App\Models;

use App\Enums\OAuthProviderType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OAuthProvider extends Model {

    protected $table = "oauth_providers";

    protected $fillable = [
        "token",
        "refresh_token",
        "user_id",
        "provider",
        "email"
    ];

    public function casts() {
        return [
            "provider" => OAuthProviderType::class,
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo("user", "id", "user_id");
    }
}
