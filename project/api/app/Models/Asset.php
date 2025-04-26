<?php

namespace App\Models;

use app\Enums\Asset\AssetFileType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use function Illuminate\Filesystem\join_paths;

class Asset extends Model {

    const storagePath = "assets";

    protected $fillable = [
        "path",
        "name",
        "filetype",
        "asset_type",
        "downloads"
    ];


    protected function casts() {
        return [
            "filetype" => AssetFileType::class,
            "asset_type" => \AssetType::class
        ];
    }


    public function post(): BelongsTo {
        return $this->belongsTo(Post::class);
    }

    public static function create(UploadedFile $file) {

        // generate uuid
        $uuid = Uuid::uuid4();

        Storage::put(join_paths(Asset::storagePath, $uuid), $file);

        $file->getMimeType()

    }


    public function getFile() {
        return Storage::get(join_paths(Asset::storagePath, $this->path));
    }


}
