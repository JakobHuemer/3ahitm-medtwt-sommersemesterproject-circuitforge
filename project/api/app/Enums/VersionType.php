<?php
namespace App\Enums;

// ENUM('snapshot', 'release', 'old_beta', 'old_alpha')
enum VersionType: string {
    case RELEASE = "release";
    case SNAPSHOT = "snapshot";
    case OLD_BETA = "old_beta";
    case OLD_ALPHA = "old_alpha";

    public static function values(): array {
        return array_map(fn($case) => $case->value, VersionType::cases());
    }
}
