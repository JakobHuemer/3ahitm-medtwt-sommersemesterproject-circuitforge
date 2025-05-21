<?php

namespace App\Http\Controllers;

use App\Enums\AssetType;
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

//        $authorId = Auth::id();
        // TODO: Use actual user id
//        echo "<pre>";
//        var_dump($data);
//        echo "</pre>";

        $authorId = 1;
        $content = $data["content"];
        $title = $data["title"];

        // loop over $data->images as UploadedFile s
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
}
