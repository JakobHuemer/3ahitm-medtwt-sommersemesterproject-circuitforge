<?php

namespace App\Models;

use app\Enums\AssetType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

const ASSET_PATH = "assets/";

class Asset extends Model {

    use HasUuids;

    const storagePath = "assets";

    protected $fillable = [
        "id",
        "file_name",
        "asset_type",
        "downloads"
    ];


    protected function casts() {
        return [
            "asset_type" => AssetType::class,
            "downloads" => "integer",
        ];
    }


    public function post(): BelongsTo {
        return $this->belongsTo(Post::class);
    }


    public function getFile() {
        return Storage::get(ASSET_PATH . $this->id);
    }

    public static function makeAssetFromFile(UploadedFile $file, AssetType $type = AssetType::ASSET): self {

        if (!$file->isFile()) throw new \Error("Uploaded file is not a file");

        $savedAsset = Asset::create([
            "file_name" => $file->getClientOriginalName(),
            "asset_type" => $type,
            "downloads" => 0
        ]);

        $savedAsset->save();


        Storage::put(ASSET_PATH . $savedAsset->id, $file->get());

        return $savedAsset;
    }

}
