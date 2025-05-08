<?php

namespace app\Enums\Asset;
enum AssetFileType: string {
    case PNG = "image/png";
    case JPEG = "image/jpeg";
    case WEBP = "image/webp";
    case AVIF = "image/avif";
    case LITEMATIC = "image/vnd.zbrush.pcx image/x-pcx";
    case SCHEM = "application/octet-stream";
    case SCHEMATIC = "application/x-gzip";
    case ZIP = "application/zip";
    case TAR = "application/x-tar";
    case TAR_GZ = "application/gzip";
    case TAR_XZ = "application/x-xz";
}
