<?php

namespace App\Enums;

enum OAuthProvider: string {
    case GITHUB = "github";
    case GOOGLE = "google";
}
