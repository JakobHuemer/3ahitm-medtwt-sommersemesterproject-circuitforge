<?php

namespace App\Enums;

enum OAuthProviderType: string {
    case GITHUB = "github";
    case GOOGLE = "google";
    case DISCORD = "discord";
}
