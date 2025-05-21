<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Asset;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): Post {
        $data = $request->validated();

        $authorId = Auth::id();
        $content = $data["content"];
        $title = $data["title"];

        $assets = Post::fromDataToAssets($data);

        $post = Post::createPost($authorId, $title, $content, $assets);

        return $post;
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post) {
        //
    }

    public static function getAsset(string $assetId) {
        $asset = Asset::find($assetId);

        if ($asset) {
            // return asset static file
            $file = $asset->getFile();
            $asset->downloads++;
            $asset->save();
            return response($file, 200)
                ->header("Content-Type", $asset->mime_type)
                ->header("Content-Disposition", "inline; filename=\"" . $asset->file_name . "\"")
                ->header("Content-Length", strlen($file));

        } else {
            return response()->json([
                "error" => "Asset not found"
            ], 404);
        }
    }
}
