<?php

namespace App\Enums;
enum AssetType: string {
    case IMAGE = "image";
    case ASSET = "asset";


    public static function values(): array {
        return array_map(fn($type) => $type->value, AssetType::cases());
    }
}
